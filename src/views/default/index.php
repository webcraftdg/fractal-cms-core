<?php
/**
 * main.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package views
 *
 * @var \yii\web\View $this
 * @var \fractalCms\core\models\User $model
 * @var $informations array
 */

use fractalCms\core\Module;

$moduleInstance = Module::getInstance();
?>
<!-- Main -->
<main class="container mx-auto px-6 py-10">

    <!-- Bienvenue -->
    <section class="mb-10">
        <h1 class="text-2xl font-bold">👋 Bienvenue, <?php echo ucfirst($model->firstname);?></h1>
        <p class="text-gray-600">Gérez vos contenus et sections simplement avec <?php echo $moduleInstance->name.' '.$moduleInstance->version?></p>
    </section>
    <?php if(empty($informations) === false):?>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php foreach ($informations as $moduleTitle => $moduleInfos):?>
            <!-- Vue d’ensemble -->
            <section class="md:col-span-2 bg-white border border-gray-200 rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-blue-700 mb-4"><?php echo  $moduleTitle?></h2>
                <ul class="space-y-2 text-gray-700">
                    <?php foreach ($moduleInfos as $infoTitle => $info):?>
                        <li><?php echo $infoTitle.' : '; ?><span class="font-bold"><?php echo $info;?></span></li>
                    <?php endforeach;?>
                </ul>
            </section>

            <?php endforeach; ?>
        </div>
    <?php endif;?>
</main>
