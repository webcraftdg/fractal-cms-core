<?php
/**
 * Menu.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package fractalCms/widgets
 */
namespace fractalCms\core\widgets;

use fractalCms\core\helpers\Menu as MenuHelper;
use yii\base\Widget;
use Yii;
use yii\helpers\Url;
use Exception;
use yii\helpers\Html;

class Menu extends Widget
{


    protected MenuHelper $menu;
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        Yii::debug('Trace: '.__METHOD__, __METHOD__);
        ob_start();
    }

    public function __construct(MenuHelper $menu, $config = [])
    {
        $this->menu = $menu;
        parent::__construct($config);
    }


    public function run()
    {
        try {
            Yii::debug('Trace: '.__METHOD__, __METHOD__);
            $content = ob_get_clean();
            return $this->render(
                'menu', [
                    'data' => $this->menu->get(),
                ]
            );
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw  $e;
        }
    }


    protected function build() : string
    {
        try {
            Yii::debug('Trace: '.__METHOD__, __METHOD__);
            $data = $this->menu->get();
            $html = '';
            foreach ($data as $index => $item){
                $classItem = 'nav-item';
                $classLinkItem = 'nav-link';
                $optionsClass = (empty($item['optionsClass']) === false) ? $item['optionsClass'] : [];
                $title = (empty($item['title']) === false) ? $item['title'] : null;
                $url = (empty($item['url']) === false) ? $item['url'] : null;
                $icon = (empty($item['icon']) === false) ? $item['icon'] : null;
                $children = [];
                $hasChildren = false;
                $linkOptions = [];
                if ($url !== null) {
                    $linkOptions['href'] = $url;
                } else {
                    $optionsClass[] = 'btn';
                }
                if (empty($item['children']) === false) {
                    $children = $item['children'];
                    $classItem .= ' dropdown';
                    $hasChildren = true;
                    $classLinkItem .= ' dropdown-toggle';
                    $linkOptions['role'] = 'button';
                    $linkOptions['data-toggle'] = 'dropdown';
                    $linkOptions['aria-expanded'] = 'false';
                }
                $classLinkItem .= ' '.implode(' ', $optionsClass);
                $linkOptions['class'] = $classLinkItem;

                $html .= Html::beginTag('li', ['class' => $classItem]);
                if ($url === null) {
                    $linkOptions['type'] = 'button';
                    $linkOptions['aria-controls'] = 'dropdown-'.($index+1);
                    unset($linkOptions['role']);
                    $html .= Html::beginTag('button', $linkOptions);
                    $html .= '<svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<g id="SVGRepo_bgCarrier" stroke-width="0"/>
<g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"/>
<g id="SVGRepo_iconCarrier"> <path d="M19 9L14 14.1599C13.7429 14.4323 13.4329 14.6493 13.089 14.7976C12.7451 14.9459 12.3745 15.0225 12 15.0225C11.6255 15.0225 11.2549 14.9459 10.9109 14.7976C10.567 14.6493 10.2571 14.4323 10 14.1599L5 9" stroke="#0d6efd" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> </g>
</svg>';
                } else {
                    $html .= Html::beginTag('a', $linkOptions);
                }
                $html .= $icon.$title;

                if ($url === null) {
                    $html .= Html::endTag('button');
                } else {
                    $html .= Html::endTag('a');
                }
                if ($hasChildren === true) {
                    $html .= Html::beginTag('ul', ['class' => 'dropdown-menu', 'id' => 'dropdown-'.($index + 1), 'aria-expanded' => 'false']);
                }
                foreach ($children as $childItem) {
                    $html .= Html::tag('li', Html::a($childItem['title'], $childItem['url'], ['class' => 'dropdown-item']));
                }
                if ($hasChildren === true) {
                    $html .= Html::endTag('ul');
                }
                $html .= Html::endTag('li');
            }
            return $html;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw  $e;
        }
    }
}
