<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "admin_dependencia".
 *
 * @property integer $id
 * @property string $centro_costos_codigo
 * @property string $precio
 * @property integer $id_admin
 */
class AdminDependencia extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_dependencia';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_admin'], 'integer'],
            [['centro_costos_codigo'], 'string', 'max' => 50],
            [['precio'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'centro_costos_codigo' => 'Centro Costos Codigo',
            'precio' => 'Precio',
            'id_admin' => 'Id Admin',
        ];
    }


    public function getDependencia()
    {
        return $this->hasOne(CentroCosto::className(), ['codigo' => 'centro_costos_codigo']);
    }  


     public function getzona($codigo){
        $rows = (new \yii\db\Query())
        ->select(['zona.nombre'])
        ->from('centro_costo cc')
        ->innerJoin('ciudad_zona cz', 'cc.ciudad_codigo_dane=cz.ciudad_codigo_dane')
        ->innerJoin('zona', 'cz.zona_id=zona.id')
        ->where('cc.codigo="'.$codigo.'" limit 1 ');

        $command = $rows->createCommand();
        //echo $command->sql;exit();
        $query = $command->queryOne();


        return $query;
    }
}
