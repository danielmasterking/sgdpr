<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "planes_accion_visitas".
 *
 * @property integer $id
 * @property string $tipo
 * @property string $plan_de_accion
 * @property string $cumplimiento
 * @property string $fecha
 * @property string $observacion
 * @property integer $visita_mensual_id
 * @property string $usuario
 */
class PlanesAccionVisitas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'planes_accion_visitas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tipo', 'plan_de_accion'/*, 'cumplimiento'*/, 'fecha',/* 'observacion',*/ 'visita_mensual_id', 'usuario'], 'required'],
            [['plan_de_accion', 'observacion'], 'string'],
            [['fecha'], 'safe'],
            [['visita_mensual_id'], 'integer'],
            [['tipo', 'usuario'], 'string', 'max' => 50],
            [['cumplimiento'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tipo' => 'Tipo',
            'plan_de_accion' => 'Plan De Accion',
            'cumplimiento' => 'Cumplimiento',
            'fecha' => 'Fecha',
            'observacion' => 'Observacion',
            'visita_mensual_id' => 'Visita Mensual ID',
            'usuario' => 'Usuario',
        ];
    }
}
