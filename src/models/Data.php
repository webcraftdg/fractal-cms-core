<?php
/**
 * Data.php
 *
 * PHP Version 8.2+
 *
 * @author David Ghyse <davidg@webcraftdg.fr>
 * @version XXX
 * @package app\models
 */
namespace fractalCms\core\models;

use yii\base\Model;
use yii\helpers\HtmlPurifier;

/**
 * This is for data display on home FractalCMS
 *
 * @property int $nbSections
 * @property int $nbActicles
 * @property string $lastDate
 *
 */
class Data extends Model
{

    const SCENARIO_CREATE = 'create';

    public $nbSections;
    public $nbActicles;
    public $lastDate;

    public function scenarios() : array
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = [
            'nbSections', 'nbArticles', 'lastDate',
        ];

        return $scenarios;
    }

    public function rules()
    {
        return [
            [[ 'lastDate'], 'filter', 'filter' => function ($value) {
                return HtmlPurifier::process($value);
            }],
            [[ 'nbSections', 'nbArticles'], 'number', 'on' => [self::SCENARIO_CREATE], 'message' => 'Vous devez indiquer des valeurs valides'],
        ];
    }
}
