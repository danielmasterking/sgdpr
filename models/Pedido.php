<?php

namespace app\models;

use Yii;
use app\models\CentroCosto;
use app\models\Usuario;

/**
 * This is the model class for table "pedido".
 *
 * @property integer $id
 * @property string $estado
 * @property string $solicitante
 * @property string $centro_costo_codigo
 * @property string $fecha
 * @property string $observaciones
 *
 * @property DetallePedido[] $detallePedidos
 * @property Usuario $solicitante0
 * @property CentroCosto $centroCostoCodigo
 */
class Pedido extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
	public $file; 
	 
    public static function tableName()
    {
        return 'pedido';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
		
            [['solicitante', 'centro_costo_codigo', 'fecha'], 'required'],
            ['observaciones', 'required', 'message' => 'Por favor, Escriba la Observacion'],
            [['fecha'], 'safe'],
            [['estado','especial'], 'string', 'max' => 1],
            [['solicitante'], 'string', 'max' => 50],
            [['centro_costo_codigo'], 'string', 'max' => 15],
            [['observaciones'], 'string', 'max' => 200],
            [['solicitante'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::className(), 'targetAttribute' => ['solicitante' => 'usuario']],
            [['centro_costo_codigo'], 'exist', 'skipOnError' => true, 'targetClass' => CentroCosto::className(), 'targetAttribute' => ['centro_costo_codigo' => 'codigo']],
			[['file'],'safe'],
            [['file'],'file','extensions'=>'xlsx, xls, pdf, jpg, gif, png, jpeg'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'estado' => 'Estado',
            'solicitante' => 'Solicitante',
            'centro_costo_codigo' => 'Dependencia',
            'fecha' => 'Fecha',
            'observaciones' => 'Observaciones',
			'especial' => 'especial',
			'file' => 'Cotización',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDetallePedidos()
    {
        return $this->hasMany(DetallePedido::className(), ['pedido_id' => 'id']);
    }
	
	 public function getDetallePedidosEspecial()
    {
        return $this->hasMany(DetallePedidoEspecial::className(), ['pedido_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSolicitante()
    {
        return $this->hasOne(Usuario::className(), ['usuario' => 'solicitante']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDependencia()
    {
        return $this->hasOne(CentroCosto::className(), ['codigo' => 'centro_costo_codigo']);
    }

    public static function DependenciasUsuario($id,$idname){//$idname "Id" or "Name"
        $usuario= Usuario::findOne($id);
        $zonasUsuario     = array();
        $marcasUsuario    = array();
        $distritosUsuario = array();
        $dependencias     = CentroCosto::find()->where(['not in', 'estado', ['C']])->orderBy(['nombre' => SORT_ASC])->all();

        if ($usuario != null) {

            $zonasUsuario     = $usuario->zonas;
            $marcasUsuario    = $usuario->marcas;
            $distritosUsuario = $usuario->distritos;

        }


        $ciudades_zonas = array();

            foreach($zonasUsuario as $zona){
                
                 $ciudades_zonas [] = $zona->zona->ciudades;    
                
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
                           
                           //$data_dependencias[] =  $value->codigo;

                           $data_dependencias[$idname=="Id"?$value->codigo:$value->nombre] =  $value->nombre;
                       }    
                   
                    }

                }
            }
            return $data_dependencias;
    }
}
