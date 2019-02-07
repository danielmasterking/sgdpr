<?php

namespace app\models;

use Yii;
use app\models\Zona;
use app\models\CentroCosto;
/**
 * This is the model class for table "visita_mensual".
 *
 * @property integer $id
 * @property string $fecha
 * @property string $fecha_visita
 * @property string $atendio
 * @property string $otro
 * @property string $usuario
 * @property string $centro_costo_codigo
 * @property string $detalle
 * @property string $recomendaciones
 *
 * @property ArchivoVisitaMensual[] $archivoVisitaMensuals
 * @property Usuario $usuario0
 * @property CentroCosto $centroCostoCodigo
 */
class VisitaMensual extends \yii\db\ActiveRecord
{
    
	public $file;
	
	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'visita_mensual';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fecha', 'fecha_visita', 'usuario', 'centro_costo_codigo','semestre','atendio'], 'required'],
            [['fecha', 'fecha_visita','file'], 'safe'],
            [['atendio', 'otro'], 'string', 'max' => 80],
            [['usuario'], 'string', 'max' => 50],
            [['centro_costo_codigo'], 'string', 'max' => 15],
            [['detalle'], 'string', 'max' => 5000],
            [['recomendaciones'], 'string', 'max' => 5000],
            [['usuario'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::className(), 'targetAttribute' => ['usuario' => 'usuario']],
            [['centro_costo_codigo'], 'exist', 'skipOnError' => true, 'targetClass' => CentroCosto::className(), 'targetAttribute' => ['centro_costo_codigo' => 'codigo']],
            [['file'],'file','extensions'=>'jpg, gif, png, pdf, jpeg, doc, docx, xls, xlsx, ppt, pptx', 'maxFiles' => 5],
		];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fecha' => 'Fecha',
            'fecha_visita' => 'Fecha inicio Visita',
            'atendio' => 'Atendio',
            'otro' => 'Otro',
            'usuario' => 'Usuario',
            'centro_costo_codigo' => 'Dependencia',
            'detalle' => 'Observaciones',
			'recomendaciones' => 'Recomendaciones',
			'file' => 'Archivos',
            'descripcion'=>'Descripcion',
            'semestre'=>'Semestre'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArchivos()
    {
        return $this->hasMany(ArchivoVisitaMensual::className(), ['visita_mensual_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(Usuario::className(), ['usuario' => 'usuario']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDependencia()
    {
        return $this->hasOne(CentroCosto::className(), ['codigo' => 'centro_costo_codigo']);
    }

    public static function CalifSemestre($dependencia,$semestre,$inicio){

        $visitaMensual=VisitaMensual::find();
        $ano=date('Y');
        if($inicio!=''){

           $ano=date("Y", strtotime($inicio)); 
        }
        switch ($semestre) {
            case 1:
                
                $TotalSemestre=$visitaMensual->where('centro_costo_codigo="'.$dependencia.'" AND  fecha_visita BETWEEN "'.$ano.'-01-01" AND
                "'.$ano.'-06-30" AND estado="cerrado" ')->count();

                break;

            case 2: 
                $TotalSemestre=$visitaMensual->where('centro_costo_codigo="'.$dependencia.'" AND fecha_visita BETWEEN "'.$ano.'-07-01" AND
                "'.$ano.'-12-31" AND estado="cerrado"')->count();
            break;
        }

        $califSemestre=0;
        if($TotalSemestre>=1){
            $califSemestre=100;

        }/*else if($TotalSemestre>=2){
            $califSemestre=100;            
        }*/

        return $califSemestre;
    }

    public static function DependenciasZona($id){

        $zona=Zona::findOne($id);
        $ciudades=$zona->ciudades;//->centroCostos;
        $in=" IN(";

        foreach ($ciudades as $value) {
            
            $in.=" '".$value->ciudad_codigo_dane."',";    
        }
        $in_final = substr($in, 0, -1).")";

        $deps=CentroCosto::find()->where('ciudad_codigo_dane '.$in_final.' AND estado NOT IN("C") ')->all();

        return $deps;
    }

    public static function DepsAll(){
        $deps=CentroCosto::find()->where('estado NOT IN("C") ')->all();

        return $deps;
    }
}
