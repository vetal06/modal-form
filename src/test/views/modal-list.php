<?php
$dataProvider = new \yii\data\ActiveDataProvider([
        'query' => \common\models\Test::find(),
]);
?>

<?=\yii\grid\GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'name',
        'number',
        'created_at',
        [
            'format' => 'raw',
            'value' => function($model) {
                return \vsk\modalForm\ModalFormWidget::widget([
                    'template' => function ($widget) use ($model){
                        $id = $widget->getId();
                        $url = \yii\helpers\Url::to(['/test/modal-form-update/', 'id' => $model->id]);
                        return "<button id='$id' data-modal-url='$url'>TEST UPDATE</button>";
                    }
                ]);
            }
        ]
    ],
])?>
