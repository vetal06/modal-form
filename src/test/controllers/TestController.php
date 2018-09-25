<?php

namespace vsk\modalForm\test\controllers;

use vsk\modalForm\actions\ModalFormAction;
use vsk\modalForm\test\models\Test;
use yii\web\Controller;

/**
 * Class TestController
 * @package vsk\test\controllers
 */
class TestController extends Controller
{

    public function actions()
    {
        return [
            'modal-form' => [
                'class' => ModalFormAction::class,
                'getBody' => function ($action) {
                    return $this->render('@vendor/vsk/modal-form/src/test/views/modal-list');
                },
                'getTitle' => function() {
                    return 'Список тестовых данных';
                },
            ],
            'modal-form-update' => [
                'class' => ModalFormAction::class,
                'getBody' => function ($action) {
                    return $this->render('@vendor/vsk/modal-form/src/test/views/update', [
                        'model' => $action->getModel(),
                    ]);
                },
                'getTitle' => function() {
                    return 'title';
                },
                'getModel' => function () {
                    $id = \Yii::$app->request->get('id');
                    return Test::findOne($id);
                },
                'submitForm' => function ($action) {
                    $model = $action->getModel();
                    $model->load(\Yii::$app->request->post());
                    return $model->save();
                }
            ],
        ];
    }
}
