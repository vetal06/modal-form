<?php
namespace vsk\modalForm\actions;


use yii\base\Action;
use yii\base\Exception;
use yii\web\Response;
use yii\base\Widget;
use yii\widgets\Pjax;

/**
 * Class ModalFormAction
 * @package vsk\modalForm\actionsS
 */
class ModalFormAction extends Action
{
    /**
     * @var \Closure
     */
    public $getBody;
    
    /**
     * @var \Closure
     */
    public $getTitle;

    public $submitForm;
    /**
     * @var \Closure
     */
    public $getModel;

    private $model;

    /**
     * @return array
     * @throws Exception
     */
    public function run()
    {
        if (\Yii::$app->request->isAjax || \Yii::$app->request->isPjax) {
            Widget::$autoIdPrefix = 'modalWidget-'.uniqid();
            Pjax::$autoIdPrefix = 'p-'.uniqid();
            $this->controller->layout = false;
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $this->chackData();
            if (\Yii::$app->request->isPost && is_callable($this->submitForm)) {
                if ((boolean)call_user_func($this->submitForm, $this)) {
                    return [
                        'status' => 200,
                    ];
                }
            }
            $title = call_user_func($this->getTitle, $this);
            $body = $this->controller->render('@vendor/vsk/modal-form/src/views/modal-form', [
                'body' => call_user_func($this->getBody, $this),
            ]);
            ob_start();
            $this->controller->view->endBody(); // это для регистрации js файлов со всех assets
            ob_end_clean();

            return [
                'js' => $this->controller->view->js,
                'jsFiles' => $this->controller->view->jsFiles,
                'cssFiles' => $this->controller->view->cssFiles,
                'modal' => [
                    'title' => $title,
                    'body' => $body,
                ],
            ];
        } else {
            return call_user_func($this->getBody, $this);
        }

    }

    /**
     * @throws Exception
     */
    protected function chackData()
    {
        if (!$this->getBody instanceof \Closure) {
            throw new Exception('Set getBody Closure for ModalFormAction!');
        }
        if (!$this->getTitle instanceof \Closure) {
            throw new Exception('Set getTitle Closure for ModalFormAction!');
        }

    }


    /**
     * @return mixed
     * @throws Exception
     */
    public function getModel()
    {
        if (empty($this->model)) {
            if (!is_callable($this->getModel)) {
                throw new Exception('Set getModel in widget!');
            }
            $this->model = call_user_func($this->getModel, $this);
        }
        return $this->model;
    }
}