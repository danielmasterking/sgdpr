<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "prefactura_consolidado_pedido".
 *
 * @property integer $id_pedido
 * @property integer $posicion
 * @property string $orden_compra
 * @property string $usuario_aprueba
 * @property string $usuario_rechaza
 * @property string $fecha_aprobacion
 * @property string $fecha_rechazo
 * @property string $motivo_rechazo_prefactura
 * @property string $nombre_factura
 * @property integer $id
 * @property string $fecha
 * @property string $mes
 * @property string $ano
 * @property string $usuario
 * @property string $dependencia
 * @property string $empresa
 * @property string $estado
 * @property string $cebe
 * @property double $total_ftes
 * @property string $total_mes
 * @property string $nit
 * @property string $numero_factura
 * @property string $created
 * @property string $ceco
 * @property string $cantidad_servicios
 * @property string $horas
 * @property double $ftes_diurnos
 * @property double $ftes_nocturnos
 * @property string $marca
 * @property double $ftes_fijos
 * @property double $ftes_variables
 * @property string $total_fijo
 * @property string $total_variable
 * @property string $Factura_numero
 * @property string $fecha_factura
 * @property string $regional
 * @property string $ciudad
 * @property string $Nit_empresa
 * @property string $estado_pedido
 */
class PrefacturaConsolidadoPedido extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'prefactura_consolidado_pedido';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_pedido', 'posicion', 'id', 'cantidad_servicios'], 'integer'],
            [['fecha_aprobacion', 'fecha_rechazo', 'fecha', 'created', 'horas', 'fecha_factura'], 'safe'],
            [['motivo_rechazo_prefactura', 'mes', 'ano', 'usuario', 'dependencia', 'empresa', 'nit', 'created', 'marca', 'regional', 'ciudad', 'Nit_empresa'], 'required'],
            [['motivo_rechazo_prefactura'], 'string'],
            [['total_ftes', 'total_mes', 'ftes_diurnos', 'ftes_nocturnos', 'ftes_fijos', 'ftes_variables', 'total_fijo', 'total_variable'], 'number'],
            [['orden_compra', 'usuario_aprueba', 'usuario_rechaza', 'nombre_factura', 'usuario', 'estado', 'numero_factura', 'marca', 'Factura_numero', 'regional', 'ciudad'], 'string', 'max' => 50],
            [['mes'], 'string', 'max' => 2],
            [['ano'], 'string', 'max' => 4],
            [['dependencia'], 'string', 'max' => 150],
            [['empresa'], 'string', 'max' => 45],
            [['cebe'], 'string', 'max' => 15],
            [['nit', 'ceco', 'Nit_empresa'], 'string', 'max' => 10],
            [['estado_pedido'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_pedido' => 'Id Pedido',
            'posicion' => 'Posicion',
            'orden_compra' => 'Orden Compra',
            'usuario_aprueba' => 'Usuario Aprueba',
            'usuario_rechaza' => 'Usuario Rechaza',
            'fecha_aprobacion' => 'Fecha Aprobacion',
            'fecha_rechazo' => 'Fecha Rechazo',
            'motivo_rechazo_prefactura' => 'Motivo Rechazo Prefactura',
            'nombre_factura' => 'Nombre Factura',
            'id' => 'ID',
            'fecha' => 'Fecha',
            'mes' => 'Mes',
            'ano' => 'Ano',
            'usuario' => 'Usuario',
            'dependencia' => 'Dependencia',
            'empresa' => 'Empresa',
            'estado' => 'Estado',
            'cebe' => 'Cebe',
            'total_ftes' => 'Total Ftes',
            'total_mes' => 'Total Mes',
            'nit' => 'Nit',
            'numero_factura' => 'Numero Factura',
            'created' => 'Created',
            'ceco' => 'Ceco',
            'cantidad_servicios' => 'Cantidad Servicios',
            'horas' => 'Horas',
            'ftes_diurnos' => 'Ftes Diurnos',
            'ftes_nocturnos' => 'Ftes Nocturnos',
            'marca' => 'Marca',
            'ftes_fijos' => 'Ftes Fijos',
            'ftes_variables' => 'Ftes Variables',
            'total_fijo' => 'Total Fijo',
            'total_variable' => 'Total Variable',
            'Factura_numero' => 'Factura Numero',
            'fecha_factura' => 'Fecha Factura',
            'regional' => 'Regional',
            'ciudad' => 'Ciudad',
            'Nit_empresa' => 'Nit Empresa',
            'estado_pedido' => 'Estado Pedido',
        ];
    }
}
