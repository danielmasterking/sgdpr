<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "adjunto_novedad_capacitacion".
 *
 * @property integer $id
 * @property string $archivo
 * @property integer $novedad_capacitacion_id
 */
class AdjuntoNovedadCapacitacion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'adjunto_novedad_capacitacion';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['archivo', 'novedad_capacitacion_id'], 'required'],
            [['id', 'novedad_capacitacion_id'], 'integer'],
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
            'archivo' => 'Archivo',
            'novedad_capacitacion_id' => 'Novedad Capacitacion ID',
        ];
    }

    public static function adjuntos($id){
        $archivos=AdjuntoNovedadCapacitacion::find()->where('novedad_capacitacion_id='.$id)->all();
        foreach ($archivos as $arch) {
            $nombre_archivo=str_replace('/uploads/novedad_capacitacion/','',$arch->archivo);
            $extension=explode('.',$nombre_archivo);

            if($extension[1]=='jpg' or $extension[1]=='JPG' or $extension[1]=='png' or $extension[1]=='PNG' or $extension[1]=='gif' or $extension[1]=='GIF'  ){
                $style='style=" width: 200px; height: 100px;"';
                $imagen="<img class='thumbnail' ".$style."  src='".Yii::$app->request->baseUrl.$arch->archivo."' alt='...'>";

                echo  $imagen;

            }else{

                $documento="<a style='font-size: 9px;' href='".Yii::$app->request->baseUrl.$arch->archivo."' download=''><i class='fa fa-paperclip'></i> ".$nombre_archivo." </a>";
                echo $documento;
            }
        }
    }

    public static function Documentos($id){
        $adjuntos=AdjuntoNovedadCapacitacion::find()->where('novedad_capacitacion_id='.$id)->all();

        return $adjuntos;
    }
}
