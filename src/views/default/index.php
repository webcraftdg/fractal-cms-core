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
 * @var \webcraftdg\fractalCms\core\models\User $model
 * @var $nbSections
 * @var $nbArticles
 * @var $lastDate;
 */

use webcraftdg\fractalCms\core\Module;

$moduleInstance = Module::getInstance();
?>
<!-- Main -->
<main class="container mx-auto px-6 py-10">

    <!-- Bienvenue -->
    <section class="mb-10">
        <h1 class="text-2xl font-bold">👋 Bienvenue, <?php echo ucfirst($model->firstname);?></h1>
        <p class="text-gray-600">Gérez vos contenus et sections simplement avec <?php echo $moduleInstance->name.' '.$moduleInstance->version?></p>
    </section>

</main>
