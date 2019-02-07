<?php 

namespace app\models;

use Yii;


class AdminDispositivo extends \yii\db\ActiveRecord
{



	public static function tableName()
    {
        return 'admin_dispositivo';
    }


    public function rules()
    {
        return [
            [['descripcion',/*'horas','ftes',*/'cantidad','precio_unitario'/*,'ftes_dependencia'*/], 'required'],
            [['lunes','martes','miercoles','jueves','viernes','sabado','domingo','festivo','detalle','hora_inicio','horas','ftes','ftes_dependencia'], 'safe'],
            
        ];
    }


     public function attributeLabels()
    {
        return [
            'descripcion' => 'Descripcion',
            'horas' => 'Horas',
            'ftes' => 'ftes',
            'ftes_dependencia'=>'Ftes dependencia',
            'cantidad' => 'Cantidad de servicios',
            'precio_unitario' => 'Precio unitario',
            'lunes'=>'Lunes',
            'martes'=>'Martes',
            'Miercoles'=>'miercoles',
            'jueves'=>'Jueves',
            'viernes'=>'Viernes',
            'sabado'=>'Sabado',
            'domingo'=>'Domingo',
            'festivo'=>'Festivo',
            'observacion'=>'Observacion',
            'precio_total'=>'Precio Total',
            'precio_dependencia'=>'Precio dependencia',
            'detalle'=>'Detalle',
            'hora_inicio'=>'Hora Inicio',
            'ftes_diurno_dep'=>'Ftes diurno dependencia',
            'ftes_nocturno_dep'=>'Ftes nocturno dependencia'
        ];
    }

}

?>