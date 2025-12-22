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
<main class="max-w-7xl mx-auto px-6 py-10 space-y-10">

    <!-- Bienvenue -->
    <section class="space-y-2">
        <h1 class="text-2xl font-semibold text-fractal-text">
            👋 Bienvenue, <?php echo ucfirst($model->firstname); ?>
        </h1>
        <p class="text-sm text-fractal-muted">
            Gérez vos contenus et sections avec
            <span class="font-medium text-fractal-text">
                <?php echo $moduleInstance->name . ' ' . $moduleInstance->version ?>
            </span>
        </p>
    </section>

    <?php if (!empty($informations)): ?>
        <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <?php foreach ($informations as $moduleTitle => $moduleInfos): ?>

                <div class="lg:col-span-2 fc-card">
                    <!-- Titre du module -->
                    <h2 class="text-lg font-semibold text-fractal-primary mb-4">
                        <?php echo $moduleTitle ?>
                    </h2>
                    <!-- Infos -->
                    <ul class="divide-y divide-fractal-border text-sm">
                        <?php foreach ($moduleInfos as $infoTitle => $info): ?>
                            <li class="flex justify-between py-2">
                                <span class="text-fractal-muted">
                                    <?php echo  $infoTitle ?>
                                </span>
                                <span class="font-medium text-fractal-text">
                                    <?php echo $info ?>
                                </span>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                </div>

            <?php endforeach; ?>

        </section>
    <?php endif; ?>
</main>
