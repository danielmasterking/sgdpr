<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "adjunto_novedad_pedido".
 *
 * @property integer $id
 * @property string $archivo
 * @property integer $novedad_pedido_id
 */
class AdjuntoNovedadPedido extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'adjunto_novedad_pedido';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['archivo', 'novedad_pedido_id'], 'required'],
            [['id', 'novedad_pedido_id'], 'integer'],
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
            'novedad_pedido_id' => 'Novedad Pedido ID',
        ];
    }

    public static function adjuntos($id){
        $archivos=AdjuntoNovedadPedido::find()->where('novedad_pedido_id='.$id)->all();
        foreach ($archivos as $arch) {
            $nombre_archivo=str_replace('/uploads/novedad_pedido/','',$arch->archivo);
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
        $adjuntos=AdjuntoNovedadPedido::find()->where('novedad_pedido_id='.$id)->all();

        return $adjuntos;
    }
}
