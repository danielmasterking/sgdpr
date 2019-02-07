<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "proyectos_historico_porcentaje".
 *
 * @property integer $id
 * @property integer $id_seguimiento
 * @property string $fecha
 * @property string $porcentaje
 */
class ProyectosHistoricoPorcentaje extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'proyectos_historico_porcentaje';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_seguimiento', 'fecha', 'porcentaje'], 'required'],
            [['id_seguimiento'], 'integer'],
            [['fecha'], 'safe'],
            [['porcentaje'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_seguimiento' => 'Id Seguimiento',
            'fecha' => 'Fecha',
            'porcentaje' => 'Porcentaje',
        ];
    }

    public function getSistema()
    {
        return $this->hasOne(SistemaProyectos::className(), ['id' => 'id_sistema']);
    }

    public function getReportes()
    {
        return $this->hasOne(TipoReportes::className(), ['id' => 'id_reporte']);
    }
}
