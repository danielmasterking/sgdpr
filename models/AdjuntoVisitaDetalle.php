<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "adjunto_visita_detalle".
 *
 * @property integer $id
 * @property integer $visita_detalle_id
 * @property string $archivo
 */
class AdjuntoVisitaDetalle extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'adjunto_visita_detalle';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['visita_detalle_id', 'archivo'], 'required'],
            [['visita_detalle_id'], 'integer'],
            [['archivo'], 'string', 'max' => 150],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'visita_detalle_id' => 'Visita Detalle ID',
            'archivo' => 'Archivo',
        ];
    }

    public static function adjuntos($id){
        $archivos=AdjuntoVisitaDetalle::find()->where('visita_detalle_id='.$id)->all();
        foreach ($archivos as $arch) {
            $nombre_archivo=str_replace('/uploads/VisitaMensual/','',$arch->archivo);
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
        $adjuntos=AdjuntoVisitaDetalle::find()->where('visita_detalle_id='.$id)->all();

        return $adjuntos;
    }
}
