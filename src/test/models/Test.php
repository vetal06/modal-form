<?php

namespace vsk\modalForm\test\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Модель таблицы "{{%test}}".
 *
 * @property integer $id 
 * @property string $name 
 * @property integer $number 
 */
class Test extends ActiveRecord
{
    /**
     * @return string Название таблицы
     */
    public static function tableName()
    {
        return '{{%test}}';
    }

    /**
     * @return array Правила валидации
     */
    public function rules()
    {
        return [
            [['number'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
    * @return array Надписи атрибутов
    */
    public function attributeLabels()
    {
        return [
            'name' => 'Name',
            'number' => 'Number',
        ];
    }

}
