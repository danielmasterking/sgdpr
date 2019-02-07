<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "investigacion_infractor".
 *
 * @property integer $id
 * @property integer $incidente_id
 * @property integer $infractor_id
 */
class InvestigacionInfractor extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'investigacion_infractor';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['incidente_id', 'infractor_id'], 'required'],
            [['incidente_id', 'infractor_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'incidente_id' => 'Incidente ID',
            'infractor_id' => 'Infractor ID',
        ];
    }

    public function getTipoInfractor()
    {
        return $this->hasOne(TipoInfractor::className(), ['id' => 'infractor_id']);
    }
}
