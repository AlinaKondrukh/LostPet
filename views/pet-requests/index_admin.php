<?php

use app\models\PetRequests;
use app\models\Status;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\models\PetRequestsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Заявления';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pet-requests-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'user',
            'name',
            'description:ntext',
            [
                'attribute'=> 'admin_message',
                'content' => function ($model) {
                    $html = Html::beginForm(['update', 'id' => $model->id]);
                    $html .= Html::activeTextarea($model, 'admin_message');
                    $html .= Html::submitButton('Изменить', ['class' => 'btn btn-link']);
                    $html .= Html::endForm();
                    return $html;
                }
            ],
            'missing_date',
            [
                'attribute' => 'status',
                'content' => function ($PetRequests) {
                    $html = Html::beginForm(['update', 'id' => $PetRequests->id]);
                    if ($PetRequests -> status_id == Status::NEW_STATUS) {
                        $html .= Html::activeDropDownList($PetRequests, 'status_id',
                            [
                                2 => 'Принята',
                                3 => 'Отклонена'
                            ],
                            [
                                'prompt' => [
                                    'text' => 'В обработке',
                                    'options' => [
                                        'style' => 'display:none'
                                    ]
                                ]
                            ]
                        );
                    } elseif ($PetRequests -> status_id == Status::PRINYAT_STATUS) {
                        $html .= Html::activeDropDownList($PetRequests, 'status_id',
                            [
                                4 => 'Найден',
                                5 => 'Не найден'
                            ],
                            [
                                'prompt' => [
                                    'text' => 'Принята',
                                    'options' => [
                                        'style' => 'display:none'
                                    ]
                                ]
                            ]
                        );
                    } else {
                        return $PetRequests->status;
                    }
                    $html .= Html::submitButton('Подтвердить', ['class' => 'btn btn-link']);
                    $html .= Html::endForm();
                    return $html;
                }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
