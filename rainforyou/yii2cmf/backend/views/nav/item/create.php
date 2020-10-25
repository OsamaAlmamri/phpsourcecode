<?php
/** @var $this yii\web\View
 * @var $model common\models\NavItem
 * @var $nav common\models\Nav
 */

$this->title = '新增导航项';
$this->params['breadcrumbs'][] = ['label' => '导航项', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $nav->key, 'url' => ['update', 'id' => $nav->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');
?>
<div class="widget-Nav-item-create">

    <?php echo $this->render('_form', [
        'model' => $model
    ]) ?>

</div>
