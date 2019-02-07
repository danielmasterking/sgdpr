<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "admin_supervision".
 *
 * @property integer $id
 * @property string $mes
 * @property string $ano
 * @property string $usuario
 * @property string $created
 * @property string $detalle
 * @property string $precio
 */
class AdminSupervision extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_supervision';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['empresa'], 'required'],
            [['created'], 'safe'],
            [['mes'], 'string', 'max' => 2],
            [['ano'], 'string', 'max' => 4],
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
            'mes' => 'Mes',
            'ano' => 'Año',
            'usuario' => 'Usuario',
            'created' => 'Created',
            'empresa'=>'Empresa',
            'fecha_desde'=>'fecha servicio desde',
            'fecha_hasta'=>'fecha servicio hasta',
            'dias'=>'Cantidad dias',
        ];
    }


     public function getEmpresa_seg()
    {
        return $this->hasOne(Empresa::className(), ['nit' => 'empresa']);
    }  



}
