<?php

namespace cms\widgets;

use cms\models\GeneToProteinActivity;
use yii\base\Widget;

class GeneProteinActivity extends Widget
{
    /** @var GeneToProteinActivity */
    public $model;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        return $this->render('geneProteinActivity', ['geneToProteinActivity' => $this->model]);
    }
}