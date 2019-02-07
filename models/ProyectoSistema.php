<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "proyecto_sistema".
 *
 * @property integer $id
 * @property integer $id_proyecto
 * @property integer $id_sistema
 */
class ProyectoSistema extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'proyecto_sistema';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_proyecto', 'id_sistema'], 'required'],
            [['id_proyecto', 'id_sistema'], 'integer'],
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
            'id_sistema' => 'Id Sistema',
        ];
    }


    public function getSistema()
    {
        return $this->hasOne(SistemaProyectos::className(), ['id' => 'id_sistema']);
    }
}
