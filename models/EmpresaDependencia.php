<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "empresa_dependencia".
 *
 * @property integer $id
 * @property string $codigo_dependencia
 * @property string $nit_empresa
 */
class EmpresaDependencia extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'empresa_dependencia';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['codigo_dependencia', 'nit_empresa'], 'required'],
            [['codigo_dependencia', 'nit_empresa'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'codigo_dependencia' => 'Codigo Dependencia',
            'nit_empresa' => 'Nit Empresa',
        ];
    }

    public function get_empresa_deps($dep){
        $query=EmpresaDependencia::find()->where('codigo_dependencia="'.$dep.'"')->all();
        $array=[];
        foreach ($query as $key => $value) {
            $array[]=$value->nit_empresa;
        }
        return $array;
    }
}
