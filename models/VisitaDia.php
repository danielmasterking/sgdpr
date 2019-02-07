<?php

namespace app\models;

use Yii;
use app\models\Usuario;
use app\models\CentroCosto;

/**
 * This is the model class for table "visita_dia".
 *
 * @property integer $id
 * @property string $fecha
 * @property string $centro_costo_codigo
 * @property string $usuario
 * @property string $observaciones
 * @property string $responsable
 * @property string $otro
 *
 * @property DetalleVisitaDia[] $detalleVisitaDias
 * @property CentroCosto $centroCostoCodigo
 * @property Usuario $usuario0
 */
class VisitaDia extends \yii\db\ActiveRecord
{
    
	public $image;
	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'visita_dia';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fecha', 'centro_costo_codigo', 'usuario'], 'required'],
            [['fecha'], 'safe'],
            [['centro_costo_codigo'], 'string', 'max' => 15],
            [['usuario'], 'string', 'max' => 50],
			[['foto'], 'string', 'max' => 500],
            [['observaciones'], 'string', 'max' => 4000],
            [['responsable', 'otro'], 'string', 'max' => 80],
            [['centro_costo_codigo'], 'exist', 'skipOnError' => true, 'targetClass' => CentroCosto::className(), 'targetAttribute' => ['centro_costo_codigo' => 'codigo']],
            [['usuario'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::className(), 'targetAttribute' => ['usuario' => 'usuario']],
            [['image'],'safe'],
            [['image'],'file','extensions'=>'jpg, gif, png, jpeg'],        
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
            'centro_costo_codigo' => 'Dependencia',
            'usuario' => 'Usuario',
            'observaciones' => 'Observaciones',
            'responsable' => 'Atendió visita',
            'otro' => 'Otro',
			'image' => 'Fotografía',
			'foto' => 'Fotografía',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDetalle()
    {
        return $this->hasMany(DetalleVisitaDia::className(), ['visita_dia_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDependencia()
    {
        return $this->hasOne(CentroCosto::className(), ['codigo' => 'centro_costo_codigo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario0()
    {
        return $this->hasOne(Usuario::className(), ['usuario' => 'usuario']);
    }

    public function Visitas($mes,$dependencia,$fi='',$ff=''){

        $ano=date('Y');

        if ($fi!='' and $fi!='') {
            $filtro=" DATE(fecha) between '".$fi."' AND '".$ff."'"; 
        }else{
            $filtro=" YEAR(fecha)='".$ano."' ";
        }

        $query=VisitaDia::find()->where('  ('.$filtro.' and   MONTH(fecha)="'.$mes.'" ) AND (centro_costo_codigo="'.$dependencia.'") ')->all();

        return $query;
    } 

    public function Num_visitas($mes,$dependencia,$fi='',$ff=''){
        $ano=date('Y');

        if (trim($fi)!='' and trim($ff)!='') {
            $filtro=" DATE(fecha) between '".$fi."' AND '".$ff."'"; 
            
        }else{
           $filtro=" YEAR(fecha)='".$ano."' ";
        }
        $query=VisitaDia::find()->where('  ('.$filtro.' and  MONTH(fecha)="'.$mes.'" ) AND (centro_costo_codigo="'.$dependencia.'") ')->count();

        return $query;
    }


    public static function Detalle_visitas($id_visita){

        $consulta=DetalleVisitaDia::find()
                ->select('detalle_visita_dia.*')
                ->innerJoin('novedad_categoria_visita', 'novedad_categoria_visita.id = detalle_visita_dia.novedad_categoria_visita_id')
                ->innerJoin('categoria_visita', 'categoria_visita.id = novedad_categoria_visita.categoria_visita_id')
                ->where(' detalle_visita_dia.visita_dia_id='.$id_visita.' ')
                ->all();


        return $consulta;
    }


    public function dependencias_regional($zonaID,$id){

        $usuario= Usuario::findOne($id);
        $zonasUsuario     = array();
        $marcasUsuario    = array();
        $distritosUsuario = array();
        $dependencias     = CentroCosto::find()->where('estado NOT IN("C") AND indicador_visita="S"')->orderBy(['nombre' => SORT_ASC])->all();

        if ($usuario != null) {

            $zonasUsuario     = $usuario->zonas;
            //$zonasUsuario=[$zona];
            $marcasUsuario    = $usuario->marcas;
            $distritosUsuario = $usuario->distritos;

        }


        $ciudades_zonas = array();

            foreach($zonasUsuario as $zona){

                if($zona->zona_id==$zonaID){
                
                 $ciudades_zonas [] = $zona->zona->ciudades;    
                }
                
            }

            $ciudades_permitidas = array();

            foreach($ciudades_zonas as $ciudades){
                
                foreach($ciudades as $ciudad){
                    
                    $ciudades_permitidas [] = $ciudad->ciudad->codigo_dane;
                    
                }
                
            }

            $marcas_permitidas = array();

            foreach($marcasUsuario as $marca){
                
                    
                    $marcas_permitidas [] = $marca->marca_id;

            }

            $dependencias_distritos = array();

            foreach($distritosUsuario as $distrito){
                
                 $dependencias_distritos [] = $distrito->distrito->dependencias;    
                
            }

            $dependencias_permitidas = array();

            foreach($dependencias_distritos as $dependencias0){
                
                foreach($dependencias0 as $dependencia0){
                    
                    $dependencias_permitidas [] = $dependencia0->dependencia->codigo;
                    
                }
                
            }


            foreach($dependencias as $value){
    
                if(in_array($value->ciudad_codigo_dane,$ciudades_permitidas)){
                    
                    if(in_array($value->marca_id,$marcas_permitidas)){
                        
                       if($tamano_dependencias_permitidas > 0){
                           
                           if(in_array($value->codigo,$dependencias_permitidas)){
                               
                             $data_dependencias[$value->codigo] =  $value->nombre;
                               
                           }else{
                               //temporal mientras se asocian distritos
                               $data_dependencias[] =  $value->codigo;
                           }
                           
                           
                       }else{
                           
                           $data_dependencias[] =  $value->codigo;
                       }    
                   
                    }

                }
            }
            return $data_dependencias;
    }

}
