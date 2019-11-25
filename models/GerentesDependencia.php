<?php

namespace app\models;
use app\models\Usuario;
use Yii;

/**
 * This is the model class for table "gerentes_dependencia".
 *
 * @property integer $id
 * @property string $usuario
 * @property string $codigo_dependencia
 */
class GerentesDependencia extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gerentes_dependencia';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['usuario', 'codigo_dependencia'], 'required'],
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
        $sql=GerentesDependencia::find()->where('codigo_dependencia="'.$codigo.'"')->all();
        $array=[];
        foreach ($sql as $key => $value) {
            $array[]=$value->usuario;
        }
        return $array;
    }
}
