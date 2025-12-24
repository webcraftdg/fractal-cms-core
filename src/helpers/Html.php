<?php
/**
 * Html.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package fractalCms\content\helpers
 */
namespace fractalCms\core\helpers;

class Html extends \yii\helpers\Html
{

    /**
     * @inheritDoc
     */
    public static function activeInput($type, $model, $attribute, $options = [])
    {
        if ($model->hasErrors($attribute) === true) {
            $classes = ($options['class']) ?? '';
            $classes .= ' fc-error';
            $options['class'] = $classes;
        }
        return parent::activeInput($type, $model, $attribute, $options);
    }

    /**
     * @inheritDoc
     */
    public static function activeDropDownList($model, $attribute, $items, $options = [])
    {
        if ($model->hasErrors($attribute) === true) {
            $classes = ($options['class']) ?? '';
            $classes .= ' fc-error';
            $options['class'] = $classes;
        }
        return parent::activeDropDownList($model, $attribute, $items, $options);
    }

    /**
     * @inheritDoc
     */
    public static function activeTextarea($model, $attribute, $options = [])
    {
        if ($model->hasErrors($attribute) === true) {
            $classes = ($options['class']) ?? '';
            $classes .= ' fc-error';
            $options['class'] = $classes;
        }
        return parent::activeTextarea($model, $attribute, $options );
    }
}
