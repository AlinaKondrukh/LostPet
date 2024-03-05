<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\PetRequests $model */

$this->title = 'Добавить заявление о пропаже';
$this->params['breadcrumbs'][] = ['label' => 'Заявления', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pet-requests-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
