<?php
/**
 * index.php
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

use fractalCms\core\helpers\Html;
use fractalCms\core\components\Constant;
?>
<div class="fc-row">
        <?php echo Html::beginForm(); ?>

        <div class="flex mb-4">
            <div class="flex items-center gap-2">
                <?php
                echo Html::activeCheckbox($model, 'active', ['label' =>  null, 'class' => 'h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500']);
                echo Html::activeLabel($model, 'active', ['label' => 'Actif', 'class' => 'text-sm font-medium text-gray-700']);
                ?>
            </div>
        </div>

        <div class="mb-4">
            <?php
            echo Html::activeLabel($model, 'email', ['label' => 'Identifiant (Email)', 'class' => 'block text-sm font-medium text-gray-700 mb-1']);
            echo Html::activeTextInput($model, 'email', ['placeholder' => 'Email', 'class' => 'w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500']);
            ?>
        </div>
        <div class="mb-4">
            <?php
            echo Html::activeLabel($model, 'lastname', ['label' => 'Nom', 'class' => 'block text-sm font-medium text-gray-700 mb-1']);
            echo Html::activeTextInput($model, 'lastname', ['placeholder' => 'Nom', 'class' => 'w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500']);
            ?>
        </div>
        <div class="mb-4">
            <?php
            echo Html::activeLabel($model, 'firstname', ['label' => 'Prénom', 'class' => 'block text-sm font-medium text-gray-700 mb-1']);
            echo Html::activeTextInput($model, 'firstname', ['placeholder' => 'Prénom', 'class' => 'w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500']);
            ?>
        </div>
        <div class="mt-6 border rounded-md">
            <div class="px-4 py-2 border-b font-medium text-gray-800">
                Mot de passe
            </div>
            <div class="cp-4 space-y-4">
                <div>
                    <?php
                    echo Html::activeLabel($model, 'tmpPassword', ['label' => 'Mot de passe', 'class' => 'block text-sm font-medium text-gray-700 mb-1']);
                    echo Html::activePasswordInput($model, 'tmpPassword', ['placeholder' => 'Votre mot de passe', 'class' => 'w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500', 'autocomplete' => 'off']);
                    ?>
                </div>
                <div>
                    <?php
                    echo Html::activeLabel($model, 'tmpCheckPassword', ['label' => 'Mot de passe (vérification)', 'class' => 'block text-sm font-medium text-gray-700 mb-1']);
                    echo Html::activePasswordInput($model, 'tmpCheckPassword', ['placeholder' => 'Vérification du mot de passe', 'class' => 'w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500', 'autocomplete' => 'off']);
                    ?>
                </div>
                <div class="border rounded-md">
                    <div class="px-3 py-2 border-b font-medium text-gray-800">
                        R&egrave;gles
                    </div>
                    <div class="p-3 space-y-1 text-sm italic text-gray-600">
                        <p>8 à 16 caractères</p>
                        <p>1 ou + chiffres</p>
                        <p>1 ou + majuscules</p>
                        <p>1 ou + caractères spéciaux parmi "!@#$%"</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-6 border rounded-md">
            <div class="px-4 py-2 border-b font-medium text-gray-800">
                Droits et autorisations
            </div>
            <div class="p-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <?php
                    foreach ($model->authRules as $nameMain => $item):
                ?>
                        <?php
                        echo Html::beginTag('div', ['class' => 'border rounded-md', 'fractal-cms-core-check-rules' => '']);
                            echo Html::beginTag('div', ['class' => 'px-3 py-2 border-b']);
                        ?>
                        <div class="flex items-center justify-between">
                            <?php
                            echo Html::activeCheckbox($model, 'authRules['.$item['id'].'][value]',
                                [
                                    'label' =>  null,
                                    'class' => 'h-4 w-4 main-check',
                                    'checked' =>(boolean) $item['value'],
                                    'disabled' => Yii::$app->user->getIdentity()->id == $model->id && Yii::$app->user->can(Constant::PERMISSION_MAIN_USER.Constant::PERMISSION_ACTION_UPDATE)
                                ]);
                            echo Html::activeLabel($model, 'authRules['.$item['id'].'][value]', ['label' => $item['title'], 'class' => 'text-sm font-medium']);
                            ?>
                        </div>
                        <?php
                        echo Html::endTag('div');
                        ?>
                            <?php if (empty($item['children']) === false): ?>
                        <div class="p-3 space-y-2">
                        <?php
                        foreach ($item['children'] as $nameChild => $child):
                            ?>
                            <div class="flex items-center justify-between">
                                <?php
                                echo Html::activeCheckbox($model, 'authRules['.$item['id'].'][children]['.$child['id'].'][value]',
                                    [
                                        'label' =>  null,
                                        'class' => 'h-4 w-4 sub-check',
                                        'checked' =>(boolean)$child['value'],
                                        'disabled' => Yii::$app->user->getIdentity()->id == $model->id  && Yii::$app->user->can(Constant::PERMISSION_MAIN_USER.Constant::PERMISSION_ACTION_UPDATE)
                                    ]);
                                echo Html::activeLabel($model, 'authRules['.$item['id'].'][children]['.$child['id'].'][value]', ['label' => $child['title'], 'class' => 'text-sm']);
                                ?>
                            </div>

                        <?php
                        endforeach;
                        ?>
                        </div>
                <?php  endif;?>
                        <?php
                        echo Html::endTag('div');
                        ?>
                <?php
                    endforeach;
                ?>
            </div>
        </div>

        <div class="mt-6 flex justify-center">
            <button type="submit" class="px-6 py-2 rounded-md bg-indigo-600 text-white hover:bg-indigo-700 transition">Valider</button>
        </div>
        <?php  echo Html::endForm(); ?>
</div>
