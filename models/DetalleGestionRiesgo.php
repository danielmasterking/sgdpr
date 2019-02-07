<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "detalle_gestion_riesgo".
 *
 * @property integer $id
 * @property integer $id_consulta
 * @property integer $id_respuesta
 * @property string $observaciones
 * @property string $planes_de_accion
 */
class DetalleGestionRiesgo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'detalle_gestion_riesgo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_consulta', 'id_respuesta','id_gestion'], 'integer'],
            [['observaciones', 'planes_de_accion'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_consulta' => 'Id Consulta',
            'id_respuesta' => 'Id Respuesta',
            'observaciones' => 'Observaciones',
            'planes_de_accion' => 'Planes De Accion',
        ];
    }


    public function getConsulta()
    {
        return $this->hasOne(ConsultasGestion::className(), ['id' => 'id_consulta']);
    }

    public function getRespuesta()
    {
        return $this->hasOne(RespuestasGestion::className(), ['id' => 'id_respuesta']);
    }

    public function getGestion()
    {
        return $this->hasOne(GestionRiesgo::className(), ['id' => 'id_gestion']);
    }

    public static function detalle_gestion($id_gestion,$id_tema){

        $query=DetalleGestionRiesgo::find()->where(' id_gestion='.$id_gestion.' AND id_consulta='.$id_tema.' ')->one();

        return $query; 
    }
}
