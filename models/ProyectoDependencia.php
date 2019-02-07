<?php

namespace app\models;

use Yii;
use app\models\Proveedor;
use app\models\ProyectoProvedor;
use app\models\Usuario;
use app\models\ProyectoUsuarios;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "proyecto_dependencia".
 *
 * @property integer $id
 * @property string $codigo_dependencia
 * @property string $fecha_creacion
 * @property integer $fecha_apertura
 * @property string $usuario
 */
class ProyectoDependencia extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    

    public static function tableName()
    {
        return 'proyecto_dependencia';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['codigo_dependencia', 'fecha_creacion', 'fecha_apertura', 'usuario'], 'required'],
            [['fecha_creacion','fecha_apertura'], 'safe'],
            [['codigo_dependencia', 'usuario'], 'string', 'max' => 50],
            
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'codigo_dependencia' => 'Dependencia',
            'fecha_creacion' => 'Fecha de Creacion',
            'fecha_apertura' => 'Fecha de Apertura',
            'usuario' => 'Usuario Creador'

        ];
    }

    public function getDependencia()
    {
        return $this->hasOne(CentroCosto::className(), ['codigo' => 'codigo_dependencia']);
    }

    public function Provedores(){
        $query=Proveedor::find()->all();

        $list=ArrayHelper::map($query,'id','nombre');

        return $list;
    }

    public function ProyectoProvedor($id_proyecto){
        $query=ProyectoProvedor::find()->where('id_proyecto='.$id_proyecto)->all();

        $provedores=[];
        foreach ($query as $row) {
            $provedores[$row->id_provedor]=$row->provedor->nombre;
        }

        return $provedores;
    }


    public function Usuarios(){
        $query=Usuario::find()->all();
        $list=ArrayHelper::map($query,'usuario','usuario');

        return $list;
    }

    public function ProyectoUsuario($id_proyecto){
        $query=ProyectoUsuarios::find()->where('id_proyecto='.$id_proyecto)->all();

        $usuarios=[];

        foreach ($query as $row) {
            $usuarios[$row->usuario]=$row->usuario;
        }

        return $usuarios; 


    }

}
