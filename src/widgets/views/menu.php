<?php
/**
 * menu.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package cms/widgets/views
 *
 * @var $data array
 *
 */

use yii\helpers\Html;

?>
<nav  class="fractal-nav" aria-label="Navigation principale" fractal-cms-core-menu="">
    <div  class="mx-auto max-w-6xl px-4">
        <button
                class="fractal-nav-button-burger"
                type="button"
                aria-controls="navbarSupportedContent"
                aria-expanded="false"
                aria-label="Ouvrir menu"
        >
            <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g id="SVGRepo_bgCarrier" stroke-width="0"/>
                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"/>
                <g id="SVGRepo_iconCarrier"> <path d="M5 12H20" stroke="#2563eb" stroke-width="2" stroke-linecap="round"/> <path d="M5 17H20" stroke="#2563eb" stroke-width="2" stroke-linecap="round"/> <path d="M5 7H20" stroke="#2563eb" stroke-width="2" stroke-linecap="round"/> </g>
            </svg>
        </button>
        <ul class="fractal-nav-list" id="navbarSupportedContent">
            <?php
            foreach ($data as $index => $item) {
                $classItem = '';
                $classLinkItem = '';
                $html = '';
                $optionsClass = (empty($item['optionsClass']) === false) ? $item['optionsClass'] : [];
                $title = (empty($item['title']) === false) ? $item['title'] : null;
                $url = (empty($item['url']) === false) ? $item['url'] : null;
                $icon = (empty($item['icon']) === false) ? $item['icon'] : '<svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<g id="SVGRepo_bgCarrier" stroke-width="0"/>
<g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"/>
<g id="SVGRepo_iconCarrier"> <path d="M19 9L14 14.1599C13.7429 14.4323 13.4329 14.6493 13.089 14.7976C12.7451 14.9459 12.3745 15.0225 12 15.0225C11.6255 15.0225 11.2549 14.9459 10.9109 14.7976C10.567 14.6493 10.2571 14.4323 10 14.1599L5 9" stroke="#0d6efd" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> </g>
</svg>';
                $children = [];
                $hasChildren = false;
                $linkOptions = [];
                if ($url !== null) {
                    $linkOptions['href'] = $url;
                } else {
                    $linkOptions['class'] = 'fractal-nav-link fractal-submenu-toggle';
                }
                if (empty($item['children']) === false) {
                    $children = $item['children'];
                    $classItem .= ' fractal-nav-item fractal-has-submenu';
                    $hasChildren = true;
                    $classLinkItem .= ' fractal-nav-link';
                    $linkOptions['role'] = 'button';
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
                    $html .= Html::beginTag('ul', ['class' => 'fractal-submenu', 'id' => 'dropdown-'.($index + 1), 'aria-expanded' => 'false', 'aria-hidden' => 'true', 'role' => 'menu']);
                }
                foreach ($children as $childItem) {
                    $html .= Html::tag('li', Html::a($childItem['title'], $childItem['url'], ['class' => 'fractal-submenu-link']), ['class' => 'fractal-submenu-item']);
                }
                if ($hasChildren === true) {
                    $html .= Html::endTag('ul');
                }
                $html .= Html::endTag('li');
                echo $html;

            }
            ?>
        </ul>
    </div>
</nav>


