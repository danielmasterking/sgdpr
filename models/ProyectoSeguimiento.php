<?php

namespace app\models;
use yii\helpers\ArrayHelper;
use app\models\SistemaProyectos;
use Yii;

/**
 * This is the model class for table "proyecto_seguimiento".
 *
 * @property integer $id
 * @property integer $id_sistema
 * @property string $fecha
 * @property string $reporte
 * @property string $avance
 * @property string $usuario
 */
class ProyectoSeguimiento extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $image;
    public static function tableName()
    {
        return 'proyecto_seguimiento';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_sistema', 'fecha', 'reporte', /*'avance',*/ 'usuario','id_provedor','id_tipo_reporte'], 'required'],
            [['id_sistema'], 'integer'],
            [['fecha'], 'safe'],
            [['reporte'], 'string'],
            [['avance', 'usuario'], 'string', 'max' => 50],
            [['image'],'safe'],
            [['image'],'file','extensions'=>'jpg, gif, png, jpeg', 'maxFiles' => 5],   
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_sistema' => 'Sistema',
            'fecha' => 'Fecha de seguimiento',
            'reporte' => 'Reporte',
            'avance' => '%Avance',
            'usuario' => 'Usuario Creador',
            'id_provedor'=>"Provedor",
            'image'=>'Fotos o informacion',
            'id_tipo_reporte'=>'Tipo Reporte'
        ];
    }

    public function getSistema()
    {
        return $this->hasOne(SistemaProyectos::className(), ['id' => 'id_sistema']);
    }

    public function getProvedor()
    {
        return $this->hasOne(Proveedor::className(), ['id' => 'id_provedor']);
    }

    public function getReportes()
    {
        return $this->hasOne(TipoReportes::className(), ['id' => 'id_tipo_reporte']);
    }

    public function Sistemas(){
        $query=SistemaProyectos::find()->all();
        $list=ArrayHelper::map($query,'id','nombre');

        return $list;
    }
}
