<?php

namespace app\models\forms;

use yii\base\Model;

class FormGeneInfoByGoTerm extends Model
{
    public $term;
    public $pageSize;
    public $dateConnect;
    public $status;

    public function rules()
    {
        return [
            [['dateConnect', 'status', 'hrmisId'], 'required'],
            ['status', 'boolean', 'trueValue' => true, 'falseValue' => false],
            ['hrmisId', 'integer'],
            [['dateConnect'], 'date', 'format' => 'php:Y-m-d'],
        ];
    }
}