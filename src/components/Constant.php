<?php
/**
 * Constant.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package fractalCms\components
 */

namespace  fractalCms\core\components;

use fractalCms\core\models\User;
use fractalCms\core\Module;
use Yii;
use Exception;

class Constant
{
    const PERMISSION_ACTION_MANAGE = 'MANAGE';
    const PERMISSION_ACTION_CREATE = 'CREATE';
    const PERMISSION_ACTION_UPDATE = 'UPDATE';
    const PERMISSION_ACTION_DELETE = 'DELETE';
    const PERMISSION_ACTION_ACTIVATION = 'ACTIVATION';
    const PERMISSION_ACTION_LIST = 'LIST';

    const PERMISSION_MAIN_USER = 'FRACTAL_CMS:USER:';
    const PERMISSION_MAIN_PARAMETER = 'FRACTAL_CMS:PARAMETER:';

    const ROLE_ADMIN = 'FRACTAL_CMS:ADMIN';
    const ROLE_AUTHOR = 'FRACTAL_CMS:AUTHOR';
    const TRACE_DEBUG = 'debug';

    public static $actions = [
        Constant::PERMISSION_ACTION_CREATE => 'Créer',
        Constant::PERMISSION_ACTION_UPDATE => 'Mettre à jour',
        Constant::PERMISSION_ACTION_DELETE => 'Supprimer',
        Constant::PERMISSION_ACTION_ACTIVATION => 'Activer / désactiver',
        Constant::PERMISSION_ACTION_LIST => 'Lister',
    ];

    /**
     * @param User $user
     * @return array
     * @throws Exception
     */
    public static function buildListRules(User $user) : array
    {
        try {
            $auth = Yii::$app->authManager;
            $rules = [];

            foreach (Module::getInstance()->getAllPermissions() as $permissionMain => $title) {
                $permissionManageName = $permissionMain.Constant::PERMISSION_ACTION_MANAGE;
                $permissionManageTitle = $title.' Gestion';
                $manageRules = [
                    'id' => $permissionManageName,
                    'title' => $permissionManageTitle,
                    'value' => ($auth->checkAccess($user->id, $permissionManageName) === true) ? 1 : 0,
                    'children' => []
                ];

                foreach (self::$actions as $action => $titleAction) {
                    $permissionName = $permissionMain.$action;
                    $permissiontTitle = $title.' '.$titleAction;
                    $permisseChild = [
                        'id' => $permissionName,
                        'title' => $permissiontTitle,
                        'value' => ($auth->checkAccess($user->id, $permissionName) === true) ? 1 : 0,
                        'children' => null
                    ];
                    $manageRules['children'][$permissionName] = $permisseChild;
                }
                $rules[$permissionManageName] = $manageRules;
            }
            return $rules;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    /**
     * @return array
     * @throws Exception
     */
    public static function getDbPermissions() : array
    {
        try {
            $authManager = Yii::$app->authManager;
            $permissions = $authManager->getPermissions();
            return array_map(function($permission) {
                return $permission->name;
            }, $permissions);
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

}
