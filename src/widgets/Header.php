<?php
/**
 * Header.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package fractalCms/widgets
 */
namespace fractalCms\core\widgets;

use yii\base\Widget;
use Yii;
use yii\helpers\Url;
use Exception;
use fractalCms\core\Module;

class Header extends Widget
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        try {
            Yii::debug('Trace: '.__METHOD__, __METHOD__);
            parent::init();
            ob_start();
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw  $e;
        }
    }

    /**
     * Render widget
     *
     * @return string
     * @since 1.0
     */
    public function run()
    {
        try {
            Yii::debug('Trace: '.__METHOD__, __METHOD__);
            $content = ob_get_clean();
            return $this->render(
                'header', [
                    'identity' => Yii::$app->user->getIdentity(),
                    'logoutUrl' => Url::to(Module::getInstance()->getLogoutUrl()),
                ]
            );
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw  $e;
        }

    }
}
