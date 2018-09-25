<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var backend\models\Test $model
 * @var yii\widgets\ActiveForm $form
 */

?>

<?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'],
    ]); ?>
    <div class="box-body">
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'number')->textInput() ?>
    </div>
    <div class="box-footer">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить'        : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
<?php ActiveForm::end(); ?>
