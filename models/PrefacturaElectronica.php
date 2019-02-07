<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "prefactura_electronica".
 *
 * @property integer $id
 * @property string $mes
 * @property string $ano
 * @property string $usuario
 * @property string $centro_costo_codigo
 * @property string $empresa
 * @property string $created
 * @property string $updated
 * @property string $regional
 * @property string $ciudad
 * @property string $marca
 * @property string $estado
 * @property string $numero_factura
 * @property string $fecha_factura
 */
class PrefacturaElectronica extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'prefactura_electronica';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mes', 'ano', 'usuario', 'centro_costo_codigo', 'empresa', 'created', 'updated', 'regional', 'ciudad', 'marca'], 'required'],
            [['created', 'updated','numero_factura','fecha_factura'], 'safe'],
            [['mes'], 'string', 'max' => 2],
            [['ano'], 'string', 'max' => 4],
            [['usuario', 'regional', 'ciudad', 'marca', 'estado'], 'string', 'max' => 50],
            [['centro_costo_codigo'], 'string', 'max' => 15],
            [['empresa'], 'string', 'max' => 10],
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
            'ano' => 'Ano',
            'usuario' => 'Usuario',
            'centro_costo_codigo' => 'Dependencia',
            'empresa' => 'Empresa',
            'created' => 'Created',
            'updated' => 'Updated',
            'regional' => 'Regional',
            'ciudad' => 'Ciudad',
            'marca' => 'Marca',
            'estado' => 'Estado',
        ];
    }



    public function getFkDependencia()
    {
        return $this->hasOne(CentroCosto::className(), ['codigo' => 'centro_costo_codigo']);
    }


    public function getFkEmpresa()
    {
        return $this->hasOne(Empresa::className(), ['nit' => 'empresa']);
    }


    function number_unformat($number, $force_number = true, $dec_point = ',', $thousands_sep = '.') {
        if ($force_number) {
            $number = preg_replace('/^[^\d]+/', '', $number);
        } else if (preg_match('/^[^\d]+/', $number)) {
            return false;
        }
        $type = (strpos($number, $dec_point) === false) ? 'int' : 'float';
        $number = str_replace(array($dec_point, $thousands_sep), array('.', ''), $number);
        settype($number, $type);
        return $number;
    }
}
