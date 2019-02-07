<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "foto_novedad_incidente".
 *
 * @property integer $id
 * @property string $foto
 * @property integer $id_novedad
 */
class FotoNovedadIncidente extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'foto_novedad_incidente';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_novedad'], 'integer'],
            [['foto'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'foto' => 'Foto',
            'id_novedad' => 'Id Novedad',
        ];
    }
}
