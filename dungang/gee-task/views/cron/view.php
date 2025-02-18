<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Cron */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Crons', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">&times;</button>
		<h4 class="modal-title"><?= Html::encode($this->title) ?></h4>
</div>
<div class="modal-body">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'task',
            'mhdmd',
            'job_script',
            'param',
            'intro',
            'token',
            'error_msg',
            'is_ok:boolean',
            'is_active:boolean',
            'run_at',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
