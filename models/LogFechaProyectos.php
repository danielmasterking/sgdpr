<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "log_fecha_proyectos".
 *
 * @property integer $id
 * @property string $fecha
 * @property string $descripcion
 * @property integer $id_proyecto
 */
class LogFechaProyectos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'log_fecha_proyectos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fecha', 'descripcion', 'id_proyecto'], 'required'],
            [['fecha'], 'safe'],
            [['descripcion'], 'string'],
            [['id_proyecto'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fecha' => 'Fecha',
            'descripcion' => 'Descripcion',
            'id_proyecto' => 'Id Proyecto',
        ];
    }
}
