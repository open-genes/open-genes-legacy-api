<?php

namespace cms\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "age".
 *
 */
class GeneToProteinActivity extends \common\models\GeneToProteinActivity
{
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public static function findAllAsArray()
    {
        $result = [];
        $ages = self::find()->all();
        foreach ($ages as $age) {
            $result[$age->id] = $age->name_phylo;
        }

        return $result;
    }


}