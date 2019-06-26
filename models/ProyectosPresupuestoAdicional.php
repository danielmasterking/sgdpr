<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "proyectos_presupuesto_adicional".
 *
 * @property integer $id
 * @property integer $id_proyecto
 * @property string $activo
 * @property string $gasto
 * @property string $observacion
 */
class ProyectosPresupuestoAdicional extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'proyectos_presupuesto_adicional';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_proyecto', 'activo', 'gasto', 'observacion'], 'required'],
            [['id_proyecto'], 'integer'],
            [['activo', 'gasto'], 'number'],
            [['observacion'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_proyecto' => 'Id Proyecto',
            'activo' => 'Activo',
            'gasto' => 'Gasto',
            'observacion' => 'Observacion',
        ];
    }
}
