<?php

namespace app\models;

use Yii;
use app\models\NovedadCategoriaVisita;
use app\models\Resultado;
/**
 * This is the model class for table "detalle_visita_dia".
 *
 * @property integer $id
 * @property integer $visita_dia_id
 * @property integer $novedad_categoria_visita_id
 * @property integer $resultado_id
 * @property string $observacion
 * @property integer $mensaje_novedad_id
 *
 * @property VisitaDia $visitaDia
 * @property NovedadCategoriaVisita $novedadCategoriaVisita
 * @property Resultado $resultado
 * @property MensajeNovedad $mensajeNovedad
 */
class DetalleVisitaDia extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'detalle_visita_dia';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['visita_dia_id', 'novedad_categoria_visita_id', 'resultado_id'/*, 'mensaje_novedad_id'*/], 'required'],
            [['visita_dia_id', 'novedad_categoria_visita_id', 'resultado_id', 'mensaje_novedad_id'], 'integer'],
            [['observacion'], 'string', 'max' => 80],
            [['visita_dia_id'], 'exist', 'skipOnError' => true, 'targetClass' => VisitaDia::className(), 'targetAttribute' => ['visita_dia_id' => 'id']],
            [['novedad_categoria_visita_id'], 'exist', 'skipOnError' => true, 'targetClass' => NovedadCategoriaVisita::className(), 'targetAttribute' => ['novedad_categoria_visita_id' => 'id']],
            [['resultado_id'], 'exist', 'skipOnError' => true, 'targetClass' => Resultado::className(), 'targetAttribute' => ['resultado_id' => 'id']],
            [['mensaje_novedad_id'], 'exist', 'skipOnError' => true, 'targetClass' => MensajeNovedad::className(), 'targetAttribute' => ['mensaje_novedad_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'visita_dia_id' => 'Visita Dia ID',
            'novedad_categoria_visita_id' => 'Novedad Categoria Visita ID',
            'resultado_id' => 'Resultado ID',
            'observacion' => 'Observacion',
            'mensaje_novedad_id' => 'Mensaje Novedad ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVisitaDia()
    {
        return $this->hasOne(VisitaDia::className(), ['id' => 'visita_dia_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNovedad()
    {
        return $this->hasOne(NovedadCategoriaVisita::className(), ['id' => 'novedad_categoria_visita_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResultado()
    {
        return $this->hasOne(Resultado::className(), ['id' => 'resultado_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMensajeNovedad()
    {
        return $this->hasOne(MensajeNovedad::className(), ['id' => 'mensaje_novedad_id']);
    }
	
	 public function getSeccion()
    {
        return $this->hasOne(DetalleVisitaSeccion::className(), ['detalle_visita_dia_id' => 'id']);
    }


    public function categorias_visita($categoria){

        $sql=NovedadCategoriaVisita::find()->where(' categoria_visita_id='.$categoria)->all();

        $categoria=array();
        foreach ($sql as $key => $value) {
            
            $categoria[]=(string)$value->nombre;
        }


        return json_encode($categoria);

    }

    public function respuestas_visita($categoria,$cc){

        $categorias=NovedadCategoriaVisita::find()->where(' categoria_visita_id='.$categoria)->all();;

        // $sql="SELECT COUNT(dvd.id)AS total  FROM detalle_visita_dia AS dvd
        //         INNER JOIN visita_dia AS vd ON dvd.visita_dia_id=vd.id
        //         INNER JOIN resultado ON dvd.resultado_id=resultado.id";

        $rows = (new \yii\db\Query())
        ->select(['COUNT(dvd.id)AS total'])
        ->from('detalle_visita_dia AS dvd')
        ->innerJoin(['visita_dia AS vd'], 'dvd.visita_dia_id=vd.id')
        ->innerJoin(['resultado'], 'dvd.resultado_id=resultado.id');
        // ->where(['last_name' => 'Smith'])
        // ->limit(10)
        // ->all();

        $arreglo=array();

        $resultados=Resultado::find()->all();

        foreach ($resultados as  $re) {

            $arreglo[]=array('name'=>$re->nombre,'data'=>array());
        }

        foreach ($categorias as $key => $value) {

            $i=0;
            foreach ($resultados as  $re) {

               // $sql.=" WHERE dvd.novedad_categoria_visita_id=".$value->id." AND resultado.nombre='".$re->nombre."' AND vd.centro_costo_codigo='".$cc."' ";
                
                $rows->where("  dvd.novedad_categoria_visita_id=".$value->id." AND resultado.nombre='".$re->nombre."' AND vd.centro_costo_codigo='".$cc."' ");

                $command = $rows->createCommand();
                //echo $command->sql;exit();
                $resultado = $command->queryOne();
               // $resultado=DetalleVisitaDia::findBySql($sql)->one();

                array_push($arreglo[$i]['data'],(int)$resultado['total']);
                

                $i++;

            }
            

        }

        return  json_encode($arreglo);
    }


    public static function Detalle_visitas($cat_id,$id_visita){

        $consulta=DetalleVisitaDia::find()
                ->select('detalle_visita_dia.*')
                ->innerJoin('novedad_categoria_visita', 'novedad_categoria_visita.id = detalle_visita_dia.novedad_categoria_visita_id')
                ->innerJoin('categoria_visita', 'categoria_visita.id = novedad_categoria_visita.categoria_visita_id')
                ->where(' novedad_categoria_visita.categoria_visita_id='.$cat_id.' AND detalle_visita_dia.visita_dia_id='.$id_visita.' ')
                ->all();


        return $consulta;
    }
}
