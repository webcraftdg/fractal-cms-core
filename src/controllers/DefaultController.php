<?php
/**
 * DefaultController.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package fractalCms\config
 *
 */

namespace fractalcms\core\controllers;

use fractalcms\core\models\User;
use yii\filters\AccessControl;
use yii\web\Controller;
use Exception;
use Yii;

class DefaultController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'only' => ['index'],
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['index'],
                    'roles' => ['@'],
                    'denyCallback' => function ($rule, $action) {
                        return $this->redirect(['authentication/login']);
                    }
                ],
            ]
        ];
        return $behaviors;
    }


    /**
     * Dashboard
     *
     * @return string
     * @throws \Throwable
     */
    public function actionIndex()
    {
        try {
            /** @var User $user */
            $user = Yii::$app->user->getIdentity();
            return $this->render('index', [
                'model' => $user,
            ]);
        } catch (Exception $e)  {
            Yii::error($e->getMessage(), __METHOD__);
            throw  $e;
        }
    }
}
