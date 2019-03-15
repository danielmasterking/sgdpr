<?php

namespace app\models;

use Yii;


/**
 * This is the model class for table "detalle_disp_admin".
 *
 * @property integer $id
 * @property integer $id_disp_admin
 * @property string $cod_dependencia
 */
class DetalleDispAdmin extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'detalle_disp_admin';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_disp_admin', 'cod_dependencia'], 'required'],
            [['id_disp_admin'], 'integer'],
            [['cod_dependencia'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_disp_admin' => 'Id Disp Admin',
            'cod_dependencia' => 'Cod Dependencia',
        ];
    }

    public function getDependencia()
    {
        return $this->hasOne(CentroCosto::className(), ['codigo' => 'cod_dependencia']);
    }
}
