<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cronograma_proyecto".
 *
 * @property integer $id
 * @property integer $id_proyecto
 * @property string $tipo_trabajo
 * @property string $descripcion
 * @property string $fecha_inicio
 * @property string $fecha_fin
 * @property string $encargado
 */
class CronogramaProyecto extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cronograma_proyecto';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tipo_trabajo', 'descripcion', 'fecha_inicio', 'fecha_fin', 'encargado'], 'required'],
            [['id_proyecto'], 'integer'],
            [['descripcion'], 'string'],
            [['fecha_inicio', 'fecha_fin'], 'safe'],
            [['tipo_trabajo'], 'string', 'max' => 100],
            [['encargado'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_proyecto' => 'Id Proyecto',
            'tipo_trabajo' => 'Tipo Trabajo',
            'descripcion' => 'Descripcion',
            'fecha_inicio' => 'Fecha Inicio',
            'fecha_fin' => 'Fecha Fin',
            'encargado' => 'Encargado',
        ];
    }
}
