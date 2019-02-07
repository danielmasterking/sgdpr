<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "proyecto_usuarios".
 *
 * @property integer $id
 * @property integer $id_proyecto
 * @property string $usuario
 */
class ProyectoUsuarios extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'proyecto_usuarios';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_proyecto', 'usuario'], 'required'],
            [['id', 'id_proyecto'], 'integer'],
            [['usuario'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_proyecto' => 'Id Proyecto',
            'usuario' => 'Usuario',
        ];
    }

    public static function UsuariosAsignados($usuario){

        $query=ProyectoUsuarios::find()->where('usuario="'.$usuario.'" ')->all();
        $data_usuarios=[];
        foreach ($query as $key => $value) {
            $data_usuarios[]=$value->id_proyecto;
        }

        $num_usuarios=count($data_usuarios);

        if($num_usuarios>0){
            $in=" id IN(";

            foreach ($data_usuarios as $row) {
                
                $in.=" '".$row."',";    
            }

            $in_final = substr($in, 0, -1).") OR";
        }else{

            $in_final="";
        } 

        return $in_final;
    }
}
