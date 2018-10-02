modalForm for Yii2
========================
Installation
------------
The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
composer require vsk/modal-form
```
or add

```json
"vsk/modal-form" : "~1.0.0"
```

to the require section of your application's `composer.json` file.


Usage
-----
![Edit modal example](./resources/images/modalForm.gif?raw=true)



Include in controller

```
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

```

Include in you view

```
<?php

echo \vsk\modalForm\ModalFormWidget::widget([
                     'template' => function ($widget) {
                         $id = $widget->getId();
                         return "<button id='{$id}' data-modal-url='/adminx24/test/modal-form'>TEST</button>";
                     }
                 ]);

```