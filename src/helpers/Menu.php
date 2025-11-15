<?php
/**
 * Menu.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package fractalCms/helpers
 */

namespace FractalCMS\Core\helpers;

use FractalCMS\Core\components\Constant;
use FractalCMS\Core\Module;
use yii\base\Component;
use Exception;
use Yii;
use yii\helpers\Url;

class Menu extends Component
{

    /**
     * Get Cms menu
     *
     * @return array
     * @throws Exception
     */
    public function get() : array
    {
        try {
            Yii::debug(Constant::TRACE_DEBUG, __METHOD__, __METHOD__);
            return $this->build();
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    /**
     * Build Cms menu
     *
     * Items => [
     *   [
     *      'title' => string,
     *      'url' => string,
     *      'icon' => string (svg),
     *      'optionsClass' => string,
     *      'children' => [] (array of items)
     *   ]
     * ]
     * @return array
     * @throws Exception
     */
    protected function build() : array
    {
        try {
            Yii::debug(Constant::TRACE_DEBUG, __METHOD__, __METHOD__);
            return Module::getInstance()->getAllMenus();
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }
    }
}
