<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "gestion_riesgo".
 *
 * @property integer $id
 * @property string $id_centro_costo
 * @property string $fecha
 * @property string $fecha_visita
 * @property string $observacion
 */
class GestionRiesgo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gestion_riesgo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_centro_costo','fecha_visita'], 'required'],
            [['id_centro_costo'], 'string'],
            [['fecha','observacion'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_centro_costo' => 'Dependencia',
            'fecha' => 'Fecha',
            'fecha_visita'=>'Fecha Visita',
            'observacion'=>'Novedad'
        ];
    }


    public function getDependencia()
    {
        return $this->hasOne(CentroCosto::className(), ['codigo' => 'id_centro_costo']);
    }
}
