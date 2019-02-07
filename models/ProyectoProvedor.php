<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "proyecto_provedor".
 *
 * @property integer $id
 * @property integer $id_proyecto
 * @property integer $id_provedor
 */
class ProyectoProvedor extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'proyecto_provedor';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_proyecto', 'id_provedor'], 'required'],
            [['id_proyecto', 'id_provedor'], 'integer'],
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
            'id_provedor' => 'Id Provedor',
        ];
    }

    public function getProvedor()
    {
        return $this->hasOne(Proveedor::className(), ['id' => 'id_provedor']);
    }
}
