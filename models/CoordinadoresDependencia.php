<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "coordinadores_dependencia".
 *
 * @property integer $id
 * @property string $usuario
 * @property string $codigo_dependencia
 */
class CoordinadoresDependencia extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'coordinadores_dependencia';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['usuario', 'codigo_dependencia'], 'required']
          
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'usuario' => 'Usuario',
            'codigo_dependencia' => 'Codigo Dependencia',
        ];
    }

    function ListUsuarios(){
        $sql=Usuario::find()->all();
        $users=[];
        foreach ($sql as $key => $value) {
            $users[(string)$value->usuario]=$value->usuario;
        }

        return $users;
    }

    function GetGerentes($codigo){
        $sql=CoordinadoresDependencia::find()->where('codigo_dependencia="'.$codigo.'"')->all();
        $array=[];
        foreach ($sql as $key => $value) {
            $array[]=$value->usuario;
        }
        return $array;
    }
}
