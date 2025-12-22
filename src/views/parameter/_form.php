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
 * @var \fractalCms\core\models\Parameter $model
 * @var \yii\redis\ActiveQuery $itemsQuery
 */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
?>
<div class="row">
    <div class="col-sm-12">
        <?php echo Html::beginForm('', 'post', []); ?>
        <div class="col-sm-6">
            <div class="flex mb-4">
                <div class="col form-group p-0">
                    <?php
                    echo Html::activeLabel($model, 'group', ['label' => 'Groupe', 'class' => 'block text-sm font-medium text-gray-700 mb-1']);
                    echo Html::activeTextInput($model, 'group', ['placeholder' => 'Groupe', 'class' => 'w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500']);
                    ?>
                </div>
            </div>
            <div class="flex mb-4">
                <div class="col form-group p-0">
                    <?php
                    echo Html::activeLabel($model, 'name', ['label' => 'Nom', 'class' => 'block text-sm font-medium text-gray-700 mb-1']);
                    echo Html::activeTextInput($model, 'name', ['placeholder' => 'Nom', 'class' => 'w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500']);
                    ?>
                </div>
            </div>
            <div class="flex mb-4">
                <div class="col form-group p-0">
                    <?php
                    echo Html::activeLabel($model, 'value', ['label' => 'Valeur', 'class' => 'block text-sm font-medium text-gray-700 mb-1']);
                    echo Html::activeTextInput($model, 'value', ['placeholder' => 'Valeur', 'class' => 'w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500']);
                    ?>
                </div>
            </div>
        </div>
        <div class="mt-6 flex justify-center">
            <button type="submit" class="px-6 py-2 rounded-md bg-indigo-600 text-white hover:bg-indigo-700 transition">Enregister</button>
        </div>
        <?php  echo Html::endForm(); ?>
    </div>
</div>
