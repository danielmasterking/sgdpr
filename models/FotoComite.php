<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "foto_comite".
 *
 * @property integer $id
 * @property integer $id_comite
 * @property string $archivo
 */
class FotoComite extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'foto_comite';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_comite', 'archivo'], 'required'],
            [['id_comite'], 'integer'],
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
            'id_comite' => 'Id Comite',
            'archivo' => 'Archivo',
        ];
    }
    
    public static function Fotos($id){
        $query=FotoComite::find()->where('id_comite='.$id)->all();

        return $query;
    }
}
