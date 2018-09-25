<?php

/**
 * @var yii\web\View $this
 * @var backend\models\Test $model
 */

$this->title                   = 'Изменение: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Tests', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Редактирование';

?>

<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
            <?=\vsk\modalForm\ModalFormWidget::widget([
                'template' => function ($widget) use ($model){
                    $id = $widget->getId();
                    $url = \yii\helpers\Url::to(['/test/modal-form-update/', 'id' => $model->id]);
                    return "<button id='$id' data-modal-url='$url'>TEST UPDATE</button>";
                }
            ]);?>
        </div>
    </div>
</div>