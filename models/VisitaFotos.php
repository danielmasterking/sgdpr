<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "visita_fotos".
 *
 * @property integer $id
 * @property integer $id_visita
 * @property string $archivo
 */
class VisitaFotos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'visita_fotos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_visita', 'archivo'], 'required'],
            [['id_visita'], 'integer'],
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
            'id_visita' => 'Id Visita',
            'archivo' => 'Archivo',
        ];
    }

    public static function Fotos($id){
        $query=VisitaFotos::find()->where('id_visita='.$id)->all();

        return $query;
    }
}
