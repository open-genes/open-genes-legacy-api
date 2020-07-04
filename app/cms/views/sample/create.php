<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model cms\models\Sample */

$this->title = 'Добавить образец';
$this->params['breadcrumbs'][] = ['label' => 'Samples', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sample-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>