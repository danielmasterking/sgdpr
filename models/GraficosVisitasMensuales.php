<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "graficos_visitas_mensuales".
 *
 * @property integer $id
 * @property string $data
 * @property integer $visita_id
 * @property integer $tipo
 */
class GraficosVisitasMensuales extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'graficos_visitas_mensuales';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['data', 'visita_id', 'tipo'], 'required'],
            [['data'], 'string'],
            [['visita_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'data' => 'Data',
            'visita_id' => 'Visita ID',
            'tipo' => 'Tipo',
        ];
    }
}
