<?php
/**
 * RbacController.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package fractalCms\console
 */
namespace fractalCms\core\console;

use fractalCms\core\components\Constant;
use fractalCms\core\Module;
use yii\console\Controller;
use yii\base\Exception as BaseException;
use Exception;
use Yii;
use yii\console\ExitCode;
use yii\helpers\Json;
use yii\rbac\Permission;
use yii\rbac\Role;

class RbacController extends Controller
{
    public string $roleName = '';
    public string | int  $userId = '';

    /**
     * @inheritdoc
     */
    public function options($actionID)
    {
        return ['roleName', 'userId'];
    }

    /**
     * @inheritdoc
     */
    public function optionAliases()
    {
        return [
            'roleName' => 'roleName',
            'userId' => 'userId',
        ];
    }

    /**
     * Init Rabc permissions
     *
     * @return int
     * @throws \yii\base\Exception
     */
    public function actionIndex()
    {
        try {
            $this->stdout('rbac init');
            $auth = Yii::$app->authManager;
            //ROLES
            $admin = $auth->getRole(Constant::ROLE_ADMIN);
            if ($admin === null) {
                $admin = $auth->createRole(Constant::ROLE_ADMIN);
                $auth->add($admin);
            }
            $author = $auth->getRole(Constant::ROLE_AUTHOR);
            if ($author === null) {
                $author = $auth->createRole(Constant::ROLE_AUTHOR);
                $auth->add($author);
                $auth->addChild($admin, $author);
            }
            //PERMISSION
            $main = array_keys(Module::getInstance()->getAllPermissions());

            $actions = [
                Constant::PERMISSION_ACTION_CREATE,
                Constant::PERMISSION_ACTION_UPDATE,
                Constant::PERMISSION_ACTION_DELETE,
                Constant::PERMISSION_ACTION_ACTIVATION,
                Constant::PERMISSION_ACTION_LIST,
            ];

            foreach ($main as $permissionMain) {
                $permissionManageName = $permissionMain.Constant::PERMISSION_ACTION_MANAGE;
                /** @var Permission $permissionManage */
                $permissionManage = $auth->getPermission($permissionManageName);
                if ($permissionManage === null) {
                    $permissionManage = $auth->createPermission($permissionManageName);
                    $auth->add($permissionManage);
                    $hasPermissionManage = $auth->hasChild($admin, $permissionManage);
                    if ($admin instanceof Role && $hasPermissionManage === false) {
                        $auth->addChild($admin, $permissionManage);
                    }
                }

                foreach ($actions as $action) {
                    $permissionName = $permissionMain.$action;
                    $permission = $auth->getPermission($permissionName);
                    if ($permission === null) {
                        $permission = $auth->createPermission($permissionName);
                        $auth->add($permission);
                    }
                    $hasChild = $auth->hasChild($permissionManage, $permission);
                    if ($hasChild === false) {
                        $auth->addChild($permissionManage, $permission);
                    }
                    $hasPermission = $auth->hasChild($author, $permission);
                    if ($author instanceof Role
                        && in_array($permissionMain, [Constant::PERMISSION_MAIN_USER, Constant::PERMISSION_MAIN_PARAMETER]) === false
                        && in_array($action, [
                            Constant::PERMISSION_ACTION_LIST,
                            Constant::PERMISSION_ACTION_CREATE,
                            Constant::PERMISSION_ACTION_UPDATE,
                            Constant::PERMISSION_ACTION_ACTIVATION]) === true
                        && $hasPermission === false) {
                            $auth->addChild($author, $permission);

                    }
                }

            }
            return ExitCode::OK;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    public function actionRemoveAll()
    {
        try {
            $this->stdout('rbac assign role');
            $authManager = Yii::$app->authManager;
            $authManager->removeAll();
            return ExitCode::OK;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }



    /**
     * Assigne role to user : php yii.php fractalCms:rbac/assign-role -roleName=FRACTAL_CMS:ADMIN -userId=user-id
     *
     * @return int
     * @throws BaseException
     */
    public function actionAssignRole()
    {
        try {
            $this->stdout('rbac assign role');
            $authManager = Yii::$app->authManager;
            if (empty($this->roleName) === true || in_array($this->roleName, [Constant::ROLE_ADMIN,Constant::ROLE_AUTHOR]) === false) {
                throw new BaseException('Le roleName est obligatoire: '.Constant::ROLE_ADMIN.', '.Constant::ROLE_AUTHOR);
            }

            if (empty($this->userId) === true) {
                throw new BaseException('user Id est obligatoire');
            }
            $role = $authManager->getRole($this->roleName);
            if ($role !== null) {
                $authManager->revoke($role, $this->userId);
                $authManager->assign($role,  $this->userId);
            } else {
                throw new BaseException('Roles authorisés:  ADMIN, AUTHOR');
            }
            return ExitCode::OK;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    /**
     * Revoke role : php yii.php fractalCms:rbac/revoke-role -roleName=FRACTAL_CMS:ADMIN -userId=user-id
     * @return int
     * @throws BaseException
     */
    public function actionRevokeRole()
    {
        try {
            $this->stdout('rbac revoke role');
            $authManager = Yii::$app->authManager;
            if (empty($this->roleName) === true || in_array($this->roleName, [Constant::ROLE_ADMIN,Constant::ROLE_AUTHOR]) === false) {
                throw new BaseException('Le roleName est obligatoire: '.Constant::ROLE_ADMIN.', '.Constant::ROLE_AUTHOR);
            }
            if (empty($this->userId) === true) {
                throw new BaseException('user Id est obligatoire');
            }
            $role = $authManager->getRole($this->roleName);
            if ($role !== null) {
                $authManager->revoke($role, $this->userId);
            } else {
                throw new BaseException('Roles authorisés:  ADMIN, AUTHOR');
            }
            return ExitCode::OK;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    /**
     * Assign permission : php yii.php fractalCms:rbac/assign-permission -roleName=FRACTAL_CMS:PARAMETER:MANAGE -userId=user-id
     * @return int
     * @throws BaseException
     */
    public function actionAssignPermission()
    {
        try {
            $this->stdout('rbac assign permission');
            $authManager = Yii::$app->authManager;
            $permissionNames = Constant::getDbPermissions();

            if (empty($this->roleName) === true || in_array($this->roleName, array_keys($permissionNames)) === false) {
                throw new BaseException('La permission est obligatoire : '.implode(',', array_keys($permissionNames)));
            }

            if (empty($this->userId) === true) {
                throw new BaseException('user Id est obligatoire');
            }
            $permission = $authManager->getPermission($this->roleName);
            if ($permission !== null) {
                $authManager->revoke($permission, $this->userId);
                $authManager->assign($permission,  $this->userId);
            } else {
                throw new BaseException('Permission non trouvée');
            }
            return ExitCode::OK;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    /**
     * Revoke permission : php yii.php fractalCms:rbac/revoke-permission -roleName=FRACTAL_CMS:PARAMETER:MANAGE -userId=user-id
     * @return int
     * @throws BaseException
     */
    public function actionRevokePermission()
    {
        try {
            $this->stdout('rbac revoke permission');
            $authManager = Yii::$app->authManager;
            $permissionNames = Constant::getDbPermissions();
            if (empty($this->roleName) === true || in_array($this->roleName, array_keys($permissionNames)) === false) {
                throw new BaseException('La permission est obligatoire : '.implode(',', array_keys($permissionNames)));
            }

            if (empty($this->userId) === true) {
                throw new BaseException('user Id est obligatoire');
            }
            $permission = $authManager->getPermission($this->roleName);
            if ($permission !== null) {
                $authManager->revoke($permission, $this->userId);
            } else {
                throw new BaseException('Permission non trouvée');
            }
            return ExitCode::OK;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }
}
