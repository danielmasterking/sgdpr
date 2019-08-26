<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "finalizados_asignados_proyectos".
 *
 * @property integer $id
 * @property integer $id_tipo_finalizado
 * @property integer $id_sistema
 * @property integer $id_proyecto
 * @property integer $orden
 */
class FinalizadosAsignadosProyectos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'finalizados_asignados_proyectos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_tipo_finalizado', 'id_sistema', 'id_proyecto', 'orden'], 'required'],
            [['id_tipo_finalizado', 'id_sistema', 'id_proyecto', 'orden'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_tipo_finalizado' => 'Id Tipo Finalizado',
            'id_sistema' => 'Id Sistema',
            'id_proyecto' => 'Id Proyecto',
            'orden' => 'Orden',
        ];
    }
}
