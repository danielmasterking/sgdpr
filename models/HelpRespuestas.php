<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "help_respuestas".
 *
 * @property integer $id
 * @property integer $id_consulta
 * @property string $cumple
 * @property string $no_cumple
 * @property string $en_proceso
 */
class HelpRespuestas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'help_respuestas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_consulta'], 'required'],
            [['id_consulta'], 'integer'],
            [['id_consulta'], 'unique','message' => 'Este Tema ya tiene asignada una ayuda'],
            [['cumple', 'no_cumple', 'en_proceso'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_consulta' => 'Tema',
            'cumple' => 'Cumple',
            'no_cumple' => 'No Cumple',
            'en_proceso' => 'En Proceso',
        ];
    }


    public function getTema()
    {
        return $this->hasOne(ConsultasGestion::className(), ['id' => 'id_consulta']);
    }

}



