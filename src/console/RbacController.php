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
use Exception;
use Yii;
use yii\console\ExitCode;
use yii\rbac\Permission;
use yii\rbac\Role;

class RbacController extends Controller
{
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
}
