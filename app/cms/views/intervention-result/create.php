<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model cms\models\InterventionResultForLongevity */

$this->title = 'Добавить результат вмешательства';
$this->params['breadcrumbs'][] = ['label' => 'Intervention Results', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="intervention-result-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>