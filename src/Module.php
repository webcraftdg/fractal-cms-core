<?php
/**
 * Module.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package fractalCms
 */

namespace fractalCms\core;

use Exception;
use fractalCms\core\components\Constant;
use fractalCms\core\console\AdminController;
use fractalCms\core\console\AuthorController;
use fractalCms\core\console\RbacController;
use fractalCms\core\interfaces\FractalCmsCoreInterface;
use fractalCms\core\models\Data;
use fractalCms\core\models\User;
use fractalCms\core\helpers\Menu;
use Yii;
use yii\base\BootstrapInterface;
use yii\console\Application as ConsoleApplication;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Application as WebApplication;
use yii\web\GroupUrlRule;
use yii\web\User as WebUser;

class Module extends \yii\base\Module implements BootstrapInterface, FractalCmsCoreInterface
{


    public $layoutPath = '@fractalCms/core/views/layouts';
    public $layout = 'main';
    public $defaultRoute = 'default/index';
    public $version = 'v1.0.0';
    public string $name = 'FractalCMS';
    public string $commandNameSpace = 'fractalCms:';

    private string $contextId = 'fractal-cms-core';
    public function bootstrap($app)
    {
        try {
            Yii::setAlias('@fractalCms/core', __DIR__);
            $app->setComponents([
                'user' => [
                    'class' => WebUser::class,
                    'identityClass' => User::class,
                    'enableAutoLogin' => true,
                    'autoRenewCookie' => true,
                    'loginUrl' => [$this->uniqueId.'/authentication/login'],
                    'idParam' => '__cmsId',
                    'returnUrlParam' => '__fractalCmsReturnUrl',
                    'identityCookie' => [
                        'name' => '_fractalCmsIdentity', 'httpOnly' => true
                    ]
                ],
            ]);

            Yii::$container->setSingleton(Menu::class, [
                'class' => Menu::class,
            ]);

            if ($app instanceof ConsoleApplication) {
                $this->configConsoleApp($app);
            } elseif ($app instanceof WebApplication) {
                $this->configWebApp($app);
            }
        } catch (Exception $e){
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    /**
     * Config Web application
     *
     * @param WebApplication $app
     * @return void
     * @throws Exception
     */
    public function configWebApp(WebApplication $app) : void
    {
        try {
            $routes = $this->getAllRoutes();
            $app->getUrlManager()->addRules($routes, false);  //adding route here
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw  $e;
        }
    }

    /**
     * Config Console Application
     *
     * @param ConsoleApplication $app
     * @return void
     * @throws Exception
     */
    protected function configConsoleApp(ConsoleApplication $app) : void
    {
        try {
            //Init migration
            if (isset($app->controllerMap['migrate']) === true) {
                //Add migrations namespace
                if (isset($app->controllerMap['migrate']['migrationNamespaces']) === true) {
                    $app->controllerMap['migrate']['migrationNamespaces'][] = 'fractalCms\core\migrations';
                } else {
                    $app->controllerMap['migrate']['migrationNamespaces'] = ['fractalCms\core\migrations'];
                }
                //Add rbac
                if (isset($app->controllerMap['migrate']['migrationPath']) === true) {
                    $app->controllerMap['migrate']['migrationPath'][] = '@yii/rbac/migrations';
                } else {
                    $app->controllerMap['migrate']['migrationPath'] = ['@yii/rbac/migrations'];
                }
            }
            $app->controllerMap[$this->commandNameSpace.'rbac'] = [
                'class' => RbacController::class,
            ];
            $app->controllerMap[$this->commandNameSpace.'admin'] = [
                'class' => AdminController::class,
            ];
            $app->controllerMap[$this->commandNameSpace.'author'] = [
                'class' => AuthorController::class,
            ];
        }catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw  $e;
        }
    }

    /**
     * Get context id
     *
     * @return string
     * @throws Exception
     */
    public function getContextId() : string
    {
        try {
            return $this->contextId;
        }catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw  $e;
        }
    }

    /**
     * Set context id
     *
     * @param $id
     * @return void
     * @throws Exception
     */
    public function setContextId($id) : void
    {
        try {
            $this->contextId = $id;
        }catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw  $e;
        }
    }

    /**
     * Get context routes
     *
     * @return array[]
     */
    public function getRoutes(): array
    {
        return [
            $this->id.'/tableau-de-bord' => $this->id.'/default/index',
            $this->id.'/gestion-des-utilisateurs' => $this->id.'/user/index',
            $this->id.'/connexion' => $this->id.'/authentification/login',
            $this->id.'/deconnexion' => $this->id.'/authentication/logout',
            $this->id.'/utilisateurs/<id:([^/]+)>/editer'=> $this->id.'/user/update',
            $this->id.'/utilisateurs/<id:([^/]+)>/supprimer' => $this->id.'/user-api/delete',
            $this->id.'/utilisateurs/<id:([^/]+)>/activer-desactiver' => $this->id.'/user-api/activate',
            $this->id.'/utilisateurs/creer' => $this->id.'/user/create',
            $this->id.'/utilisateurs/liste' => $this->id.'/user/index',
            $this->id.'/parametres/liste' => $this->id.'/parameter/index',
            $this->id.'/parametres/creer' => $this->id.'/parameter/create',
            $this->id.'/parametres/<id:([^/]+)>/editer' => $this->id.'/parameter/update',
            $this->id.'/parametres/<id:([^/]+)>/supprimer' => $this->id.'/api/parameter/delete',
        ];
    }

    /**
     * @return Data
     * @throws \yii\base\InvalidConfigException
     */
    public function getData(): Data
    {
        return Yii::createObject(Data::class);
    }

    /**
     * Return context Permission
     * @return array
     */
    public function getPermissions(): array
    {
        return [
            Constant::PERMISSION_MAIN_USER => 'Utilisateur',
            Constant::PERMISSION_MAIN_PARAMETER => 'Configuration Paramètres',
        ];
    }

    public function getMenu() : array
    {
        try {
            Yii::debug(Constant::TRACE_DEBUG, __METHOD__, __METHOD__);
            $admins = [
                'title' => 'Administration',
                'url' => null,
                'optionsClass' => [],
                'children' => []
            ];
            if (Yii::$app->user->can(Constant::PERMISSION_MAIN_USER.Constant::PERMISSION_ACTION_LIST) === true) {
                $optionsClass = [];
                if (Yii::$app->controller->id == 'user') {
                    $optionsClass[] = 'text-primary fw-bold';
                }
                if(empty($admins['optionsClass']) === true) {
                    $admins['optionsClass'] = $optionsClass;
                }
                $admins['children'][] =  [
                    'title' => 'Utilisateurs',
                    'url' => Url::to(['/'.$this->id.'/user/index']),
                    'optionsClass' => $optionsClass,
                    'children' => [],
                ];

            }
            if (Yii::$app->user->can(Constant::PERMISSION_MAIN_PARAMETER.Constant::PERMISSION_ACTION_LIST) === true) {
                $optionsClass = [];
                if (Yii::$app->controller->id == 'parameter') {
                    $optionsClass[] = 'text-primary fw-bold';
                }
                if(empty($admins['optionsClass']) === true) {
                    $admins['optionsClass'] = $optionsClass;
                }
                $admins['children'][] = [
                    'title' => 'Paramètres',
                    'url' => Url::to(['/'.$this->id.'/parameter/index']),
                    'optionsClass' => $optionsClass,
                    'children' => [],
                ];
            }
            $data = [];
            if (empty($admins['children']) === false) {
                $data[] = $admins;
            }
            return $data;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }


    /**
     * Get all persmission
     *
     * @return array
     * @throws Exception
     */
    public function getAllPermissions() : array
    {
        try {
            $permissions = $this->getPermissions();
            $modules = Yii::$app->modules;
            foreach ($modules as $id => $module) {
                $module = Yii::$app->getModule($id);
                if ($module instanceof FractalCmsCoreInterface && $module->id !== $this->id) {
                    $module->setContextId($id);
                    $permissions = ArrayHelper::merge($permissions, $module->getPermissions());
                }
            }
            return $permissions;
        }catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw  $e;
        }
    }


    /**
     * get menu
     *
     * @return array
     * @throws Exception
     */
    public function getAllMenus() : array
    {
        try {
            $menus = $this->getMenu();
            $modules = Yii::$app->modules;
            foreach ($modules as $id => $module) {
                $module = Yii::$app->getModule($id);
                if ($module instanceof FractalCmsCoreInterface && $module->id !== $this->id) {
                    $module->setContextId($id);
                    $menus = array_merge_recursive($menus, $module->getMenu());
                }
            }
            return $menus;
        }catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw  $e;
        }
    }

    /**
     * Get routes of all module
     *
     * @return array[]
     * @throws Exception
     */
    public function getAllRoutes() : array
    {
        try {
            $routes = $this->getRoutes();
            $modules = Yii::$app->modules;
            foreach ($modules as $id => $module) {
                $module = Yii::$app->getModule($id);
                if ($module instanceof FractalCmsCoreInterface && $module->id !== $this->id) {
                    $module->setContextId($id);
                    $routes = ArrayHelper::merge($routes, $module->getRoutes());
                }
            }
            return $routes;
        }catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw  $e;
        }
    }
}
