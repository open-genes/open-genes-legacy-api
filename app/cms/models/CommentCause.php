<?php

namespace cms\models;

use cms\models\behaviors\ChangelogBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "comment_cause".
 *
 */
class CommentCause extends \common\models\CommentCause
{
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            ChangelogBehavior::class
        ];
    }

    public static function findAllAsArray()
    {
        $result = [];
        $commentCauses = self::find()->all();
        foreach ($commentCauses as $commentCause) {
            $result[$commentCause->id] = $commentCause->name_ru;
        }

        return $result;
    }
}
