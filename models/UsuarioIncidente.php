<?php 

namespace app\models;

use Yii;

class UsuarioIncidente extends \yii\db\ActiveRecord
{


	public static function tableName()
    {
        return 'usuario_incidente';
    }


    public function rules()
    {
        return [
            
            [['usuario','id_incidente'], 'safe']
            
        ];
    }


    public function getIncidente()
    {
        return $this->hasOne(Incidente::className(), ['id' => 'id_incidente']);
    }


}




?>