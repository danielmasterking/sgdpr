<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "proyecto_seguimiento_archivo".
 *
 * @property integer $id
 * @property integer $seguimiento_id
 * @property string $archivo
 */
class ProyectoSeguimientoArchivo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'proyecto_seguimiento_archivo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['seguimiento_id', 'archivo'], 'required'],
            [['seguimiento_id'], 'integer'],
            [['archivo'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'seguimiento_id' => 'Seguimiento ID',
            'archivo' => 'Archivo',
        ];
    }


    public static function Adjuntos($id){
        $adjuntos=ProyectoSeguimientoArchivo::find()->where('seguimiento_id='.$id)->all();

        return $adjuntos;
    }
}
