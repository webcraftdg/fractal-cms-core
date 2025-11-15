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

namespace FractalCMS\Core;

use Exception;
use FractalCMS\Core\components\Constant;
use FractalCMS\Core\console\AdminController;
use FractalCMS\Core\console\AuthorController;
use FractalCMS\Core\console\RbacController;
use FractalCMS\Core\interfaces\FractalCmsCoreInterface;
use FractalCMS\Core\models\User;
use FractalCMS\Core\helpers\Menu;
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


    public $layoutPath = '@fractalCms/views/layouts';
    public $layout = 'main';
    public $defaultRoute = 'default/index';
    public $version = 'v1.0.0';
    public string $name = 'FractalCMS';
    public string $commandNameSpace = 'fractalCms:';

    private string $contextId = 'cms';
    private static array $routeRules = [
        [
            'pattern' =>'tableau-de-bord',
            'route' => 'default/index',
        ],
        [
            'pattern' => 'gestion-des-utilisateurs',
            'route' => 'user/index',
        ],
        [
            'pattern' => 'connexion',
            'route' => 'authentification/login',
        ],
        [
            'pattern' => 'deconnexion',
            'route' => 'authentication/logout',
        ],
        [
            'pattern' => 'utilisateurs/<id:([^/]+)>/editer',
            'route' => 'user/update',
        ],
        [
            'pattern' => 'utilisateurs/<id:([^/]+)>/supprimer',
            'route' => 'user-api/delete',
        ],
        [
            'pattern' => 'utilisateurs/<id:([^/]+)>/activer-desactiver',
            'route' => 'user-api/activate',
        ],
        [
            'pattern' => 'utilisateurs/creer',
            'route' => 'user/create',
        ],
        [
            'pattern' => 'utilisateurs/liste',
            'route' => 'user/index',
        ],
    ];
    public function bootstrap($app)
    {
        try {
            Yii::setAlias('@fractalcms', __DIR__);
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
            $app->getUrlManager()->addRules(
                [
                    new GroupUrlRule([
                        'prefix' => Module::getInstance()->id,
                        'routePrefix' => Module::getInstance()->id,
                        'rules' => static::$routeRules
                    ]),
                ], true);            //adding route here
        }catch (Exception $e) {
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
                    $app->controllerMap['migrate']['migrationNamespaces'][] = 'FractalCMS\Core\migrations';
                } else {
                    $app->controllerMap['migrate']['migrationNamespaces'] = ['FractalCMS\Core\migrations'];
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
     * Return context Permission
     * @return array
     */
    public function getPermissions(): array
    {
        return [
            Constant::PERMISSION_MAIN_USER,
            Constant::PERMISSION_MAIN_PARAMETER,
        ];
    }

    public function getMenu() : array
    {
        try {
            Yii::debug(Constant::TRACE_DEBUG, __METHOD__, __METHOD__);
            $configuration = [
                'title' => 'Configuration',
                'url' => null,
                'optionsClass' => [],
                'children' => []
            ];
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
                    'url' => Url::to(['user/index']),
                    'optionsClass' => $optionsClass,
                    'children' => [],
                ];

            }
            if (Yii::$app->user->can(Constant::PERMISSION_MAIN_PARAMETER.Constant::PERMISSION_ACTION_LIST) === true) {
                $optionsClass = [];
                if (Yii::$app->controller->id == 'parameter') {
                    $optionsClass[] = 'text-primary fw-bold';
                }
                if(empty($configuration['optionsClass']) === true) {
                    $configuration['optionsClass'] = $optionsClass;
                }
                $configuration['children'][] = [
                    'title' => 'Paramètres',
                    'url' => Url::to(['parameter/index']),
                    'optionsClass' => $optionsClass,
                    'children' => [],
                ];
            }
            $data = [];
            if (empty($admins['children']) === false) {
                $data[] = $admins;
            }
            if (empty($configuration['children']) === false) {
                $data[] = $configuration;
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
            $permissions = [];
            $modules = Yii::$app->getModules();
            foreach ($modules as $module) {
                if ($module instanceof FractalCmsCoreInterface) {
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
            $modules = Yii::$app->getModules();
            foreach ($modules as $module) {
                if ($module instanceof FractalCmsCoreInterface) {
                    $permissions = ArrayHelper::merge($permissions, $module->getMenu());
                }
            }
            return $menus;
        }catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw  $e;
        }
    }
}
