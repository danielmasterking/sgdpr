<?php

namespace app\models;

use Yii;
use app\models\DetalleDispAdmin;

/**
 * This is the model class for table "dispositivo_admin".
 *
 * @property integer $id
 * @property string $nit_empresa
 * @property string $nombre
 */
class DispositivoAdmin extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dispositivo_admin';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nit_empresa', 'nombre','id_regional'], 'required'],
            [['nit_empresa'], 'string', 'max' => 100],
            [['nombre'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nit_empresa' => 'Nit Empresa',
            'nombre' => 'Nombre',
            'id_regional'=>'Regional'
        ];
    }

    public function getEmpresa()
    {
        return $this->hasOne(Empresa::className(), ['nit' => 'nit_empresa']);
    }

    public static function DependenciasDisp($id){

        $query=DetalleDispAdmin::find()->where('id_disp_admin='.$id)->all();

        $value_dep=[];

        foreach ($query as $key => $value) {
             $value_dep[]=$value->cod_dependencia;
        }
        return $value_dep;
    }
}
