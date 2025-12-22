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

?>

<div class="mt-3 flex items-center justify-center">
    <div class="w-full sm:w-1/2">
        <h2>Création d'un utilisateur</h2>
    </div>
</div>
<div class="mt-4 flex justify-center">
    <div class="w-full sm:w-1/2">
        <?php
        echo $this->render('_form', ['model' => $model]);
        ?>
    </div>
</div>
