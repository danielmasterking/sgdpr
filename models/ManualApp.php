<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "manual_app".
 *
 * @property integer $id
 * @property string $modulo
 * @property string $archivo
 */
class ManualApp extends \yii\db\ActiveRecord
{
     public $pdf;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'manual_app';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['modulo'], 'required'],
            [['modulo'], 'string', 'max' => 100],
            [['archivo'], 'string', 'max' => 400],
            [['pdf'],'safe'],
            [['pdf'],'file','extensions'=>'jpg, gif, png, pdf, jpeg', 'maxFiles' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'modulo' => 'Modulo',
            'archivo' => 'Archivo',
            'pdf' => 'Archivo',

        ];
    }
}
