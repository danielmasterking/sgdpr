<?php

namespace app\models;
use app\models\Usuario;
use app\models\RolUsuario;
use app\models\NotificacionUsuario;
use Yii;

/**
 * This is the model class for table "notificacion".
 *
 * @property integer $id
 * @property string $descripcion
 */
class Notificacion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notificacion';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['descripcion','fecha_inicio','fecha_final','titulo'], 'required'],
            [['descripcion'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'descripcion' => 'Descripcion',
            'fecha_inicio'=>'Fecha inicial',
            'fecha_final'=>'Fecha final',
            'titulo'=>'Titulo'
        ];
    }


    public static function CrearNotificacion($titulo,$descripcion){
        $fecha_inicial = date('Y-m-d');
        $fecha_final=strtotime ( '+29 day' , strtotime ( $fecha_inicial ) ) ;
        $fecha_final = date ( 'Y-m-d' , $fecha_final );

        $model=new Notificacion;
        $model->setAttribute('descripcion', $descripcion);
        $model->setAttribute('fecha_inicio', $fecha_inicial);
        $model->setAttribute('fecha_final', $fecha_final);
        $model->setAttribute('titulo', $titulo);
        $model->save();

        $usuarios=Usuario::find()->all();

        foreach ($usuarios as $us) {
            
            $rol=RolUsuario::find()->where('usuario="'.$us->usuario.'" AND rol_id IN(1,2) ')->one();

            if($rol!=null){
                $model2=new NotificacionUsuario;
                $model2->setAttribute('id_notificacion', $model->id);
                $model2->setAttribute('usuario', $us->usuario);
                $model2->save();
            }
        }



    }
}
