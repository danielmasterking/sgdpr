<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "novedad_dependencia".
 *
 * @property integer $id
 * @property string $centro_costo_codigo
 * @property integer $id_novedad
 * @property integer $cantidad
 */
class NovedadDependencia extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'novedad_dependencia';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['centro_costo_codigo', 'id_novedad', 'cantidad'], 'required'],
            [['id_novedad', 'cantidad'], 'integer'],
            //[['id_novedad'], 'unique'],
            [['centro_costo_codigo'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'centro_costo_codigo' => 'Centro Costo Codigo',
            'id_novedad' => 'Novedad',
            'cantidad' => 'Cantidad',
        ];
    }

    public function getDependencia()
    {
        return $this->hasOne(CentroCosto::className(), ['codigo' => 'centro_costo_codigo']);
    }

    public function getNovedad()
    {
        return $this->hasOne(Novedad::className(), ['id' => 'id_novedad']);
    }

    public static function CalificacionTema($tema,$dependencia,$inicio,$final){
        $ano=date('Y');
        $connection = \Yii::$app->db;
        //$novedades_seleccionadas=NovedadDependencia::find()->where('centro_costo_codigo="'.$dependencia.'" AND id_novedad='.$tema.' ')->one();

        if($inicio=='' && $final==''){

            $filtro1="'$ano-01-01' AND '$ano-06-31'";
        }else{

            $ano=date("Y", strtotime($inicio)); 

            if($final!=''){
                $filtro1="'$inicio' AND '$final'";
            }else{
                $filtro1="'$ano-01-01' AND '$ano-06-31'";
            }
        }

        $sqlSem='SELECT COUNT(capacitacion.id) as cantidad FROM capacitacion  
            inner join capacitacion_dependencia as cd on cd.capacitacion_id=capacitacion.id
            where cd.centro_costo_codigo=:dependencia AND capacitacion.novedad_id=:novedad 
            ';

        $consultaSem=$sqlSem."AND (fecha_capacitacion BETWEEN $filtro1)";
            
        $capSem= $connection->createCommand($consultaSem, [
            ':dependencia' => $dependencia,
            ':novedad'=>$tema
        ])->queryOne();

        if($capSem['cantidad']!=0 ){
            $califSem=($capSem['cantidad']/1)*100;
            if ($califSem>100) {
               $califSem=100; 
            }
        }else{

            $califSem=0;
        }

        if($inicio=='' && $final==''){

            $filtro2="'$ano-07-01' AND '$ano-12-31'";
        }else{

            $ano=date("Y", strtotime($inicio)); 
            if($final!=''){
                $filtro2="'$inicio' AND '$final'";
            }else{
                $filtro2="'$ano-07-01' AND '$ano-12-31'";
            }  
        }


        $consultaSem2=$sqlSem."AND (fecha_capacitacion BETWEEN $filtro2)";

            $capSem2= $connection->createCommand($consultaSem2, [
                ':dependencia' => $dependencia,
                ':novedad'=>$tema
            ])->queryOne();

            if($capSem2['cantidad']!=0 ){
                $califSem2=($capSem2['cantidad']/1)*100;

                if ($califSem2>100) {
                   $califSem2=100; 
                }
            }else{
                $califSem2=0;
            }

        $califFinal=($califSem+$califSem2)/2;

        return $califFinal;
    }

    public static function  ContarCapacitaciones($id,$inicio){
        $ano=date('Y');
        if($inicio!=''){

           $ano=date("Y", strtotime($inicio)); 
        }
        $connection = \Yii::$app->db;
        $sql='SELECT SUM(cd.cantidad) as cantidad,COUNT(capacitacion.id) as capacitaciones FROM capacitacion  
            inner join capacitacion_dependencia as cd on cd.capacitacion_id=capacitacion.id
            where cd.centro_costo_codigo=:dependencia  AND (YEAR(fecha_capacitacion)=:ano)
            ';

        $capDep= $connection->createCommand($sql, [
                ':dependencia' => $id,
                ':ano'=>$ano
            ])->queryOne();

        return $capDep;
    }
}
