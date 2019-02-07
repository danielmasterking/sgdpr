<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "capacitacion_foto".
 *
 * @property integer $id
 * @property integer $id_capacitacion
 * @property string $archivo
 */
class CapacitacionFoto extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'capacitacion_foto';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'id_capacitacion', 'archivo'], 'required'],
            [[ 'id_capacitacion'], 'integer'],
            [['archivo'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_capacitacion' => 'Id Capacitacion',
            'archivo' => 'Archivo',
        ];
    }
    public static function Fotos($id){
        $query=CapacitacionFoto::find()->where('id_capacitacion='.$id)->all();

        return $query;
    }
}
