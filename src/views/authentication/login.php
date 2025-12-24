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
 */

use yii\helpers\Html;

?>
<div class="min-h-screen flex items-center justify-center bg-fractal-bg px-4">
    <div class="w-full max-w-md space-y-6">
        <!-- Titre -->
        <h1 class="text-2xl font-semibold text-center text-fractal-text">
            Veuillez vous identifier
        </h1>

        <!-- Message d’erreur -->
        <?php if (!empty($model->errors)): ?>
            <div class="rounded-md bg-fractal-danger/10 border border-fractal-danger text-fractal-danger px-4 py-3 text-sm text-center">
                Veuillez vérifier vos informations
            </div>
        <?php endif; ?>
        <!-- Formulaire -->
        <div class="fc-card">
            <?php echo Html::beginForm(); ?>
            <div class="space-y-4">
                <!-- Email -->
                <div>
                    <?php echo Html::activeLabel(
                        $model,
                        'email',
                        [
                            'label' => 'Identifiant (Email)',
                            'class' => 'fc-label'
                        ]
                    ); ?>

                    <?php echo Html::activeTextInput(
                        $model,
                        'email',
                        [
                            'placeholder' => 'votre login / email',
                            'class' => 'fc-input'
                        ]
                    ); ?>
                </div>

                <!-- Mot de passe -->
                <div>
                    <?php echo Html::activeLabel(
                        $model,
                        'tmpPassword',
                        [
                            'label' => 'Mot de passe',
                            'class' => 'fc-label'
                        ]
                    ); ?>

                    <?php echo Html::activePasswordInput(
                        $model,
                        'tmpPassword',
                        [
                            'placeholder' => 'Votre mot de passe',
                            'class' => 'fc-input'
                        ]
                    ); ?>
                </div>

                <!-- Bouton -->
                <div class="pt-2">
                    <button type="submit" class="fc-btn fc-btn-primary w-full">
                        S’identifier
                    </button>
                </div>

            </div>

            <?php echo Html::endForm(); ?>
        </div>

    </div>
</div>

