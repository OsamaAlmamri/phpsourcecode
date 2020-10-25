<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '导航';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginBlock('content-header') ?>
<?= $this->title . ' ' . Html::a('新导航', ['create'], ['class' => 'btn btn-primary btn-flat']) ?>
<?php $this->endBlock() ?>
<div class="box box-primary">
    <div class="box-body">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                'id',
                'key',
                'title',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{refresh} {update} {delete}',
                    'buttons' => [
                        'refresh' => function ($url, $model, $key) {
                            return Html::a('刷新', $url, ['class' => 'btn btn-primary btn-xs']);
                        },
                    ]
                ],
            ],
        ]); ?>
    </div>
</div>
