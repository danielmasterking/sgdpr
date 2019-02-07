<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "help_consulta_gestion".
 *
 * @property integer $id
 * @property string $descripcion
 * @property integer $id_consulta_gestion
 * @property string $estado
 */
class HelpConsultaGestion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'help_consulta_gestion';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['descripcion','id_consulta_gestion'], 'required'],
            [['descripcion'], 'string'],
            [['id_consulta_gestion'], 'integer'],
            [['id_consulta_gestion'], 'unique','message' => 'Este Tema ya tiene asignada una ayuda'],
            [['estado'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'descripcion' => 'Descripcion',
            'id_consulta_gestion' => 'Temas',
            'estado' => 'Estado',
        ];
    }

    public function getConsulta()
    {
        return $this->hasOne(ConsultasGestion::className(), ['id' => 'id_consulta_gestion']);
    }

}
