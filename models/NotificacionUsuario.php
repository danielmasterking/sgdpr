<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "notificacion_usuario".
 *
 * @property integer $id
 * @property integer $id_notificacion
 * @property string $usuario
 */
class NotificacionUsuario extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notificacion_usuario';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_notificacion'], 'integer'],
            [['usuario'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_notificacion' => 'Id Notificacion',
            'usuario' => 'Usuario',
        ];
    }
}
