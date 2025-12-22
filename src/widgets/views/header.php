<?php
/**
 * header.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package cms/widgets/views
 *
 * @var $this \yii\web\View
 * @var \fractalCms\core\models\User $identity
 * @var string $logoutUrl
 */

use fractalCms\core\assets\StaticAsset;
use yii\helpers\Html;
use fractalCms\core\Module;

$moduleInstance = Module::getInstance();
$baseUrl = StaticAsset::register($this)->baseUrl;

?>
<header class="bg-white border-b border-fractal-border">
    <div class="max-w-7xl mx-auto px-6">
        <div class="h-14 flex items-center justify-between gap-3">
            <div class="fc-col-4 flex items-center ">
                <?php
                echo Html::img($baseUrl.'/img/logo.png', ['alt' => 'logo Fractal CMS', 'width' => 32, 'height' => 32]);

                echo Html::a($moduleInstance->name.' : '.$moduleInstance->version, ['default/index'], ['class' => 'fw-bold no-underline']);
                ?>
            </div>
                <?php
                echo \fractalCms\core\widgets\Menu::widget();
                ?>
            <div class="fc-col-3 flex items-center justify-center">
                <span class="fc-badge fc-badge-primary"><?php echo $identity->getInitials(); ?></span>
                <?php
                echo Html::beginTag('a', ['class' => '', 'href' => $logoutUrl, 'title' => 'Déconnexion']);
                ?>
                <svg width="32px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M21.593 10.943c.584.585.584 1.53 0 2.116L18.71 15.95c-.39.39-1.03.39-1.42 0a.996.996 0 0 1 0-1.41 9.552 9.552 0 0 1 1.689-1.345l.387-.242-.207-.206a10 10 0 0 1-2.24.254H8.998a1 1 0 1 1 0-2h7.921a10 10 0 0 1 2.24.254l.207-.206-.386-.241a9.562 9.562 0 0 1-1.69-1.348.996.996 0 0 1 0-1.41c.39-.39 1.03-.39 1.42 0l2.883 2.893zM14 16a1 1 0 0 0-1 1v1.5a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5v-13a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v1.505a1 1 0 1 0 2 0V5.5A2.5 2.5 0 0 0 12.5 3h-7A2.5 2.5 0 0 0 3 5.5v13A2.5 2.5 0 0 0 5.5 21h7a2.5 2.5 0 0 0 2.5-2.5V17a1 1 0 0 0-1-1z" fill="#000000"/></svg>
                <?php echo Html::endTag('a'); ?>
            </div>
        </div>

    </div>

</header>


