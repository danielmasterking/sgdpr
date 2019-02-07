<?php

namespace app\controllers;

use app\models\Capacitacion;
use app\models\CapacitacionDependencia;
use app\models\Ciudad;
use app\models\Comite;
use app\models\Distrito;
use app\models\Empresa;
use app\models\Evento;
use app\models\Incidente;
use app\models\Macroactividad;
use app\models\Marca;
use app\models\Merma;
use app\models\Microactividad;
use app\models\Rol;
use app\models\RolUsuario;
use app\models\Siniestro;
use app\models\Usuario;
use app\models\UsuarioDistrito;
use app\models\UsuarioEmpresa;
use app\models\UsuarioMacroactividad;
use app\models\UsuarioMarca;
use app\models\UsuarioMicroactividad;
use app\models\UsuarioZona;
use app\models\VisitaDia;
use app\models\VisitaMensual;
use app\models\Zona;
use app\models\GestionRiesgo;
use app\models\CentroCosto;
use app\models\ConsultasGestion;
use app\models\DetalleGestionRiesgo;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use app\models\LogUsuarios;
use yii\helpers\ArrayHelper;
use app\models\Novedad;
use app\models\VisitaMensualDetalle;
use app\models\NovedadCapacitacion;
use app\models\NovedadPedido;
use app\models\CategoriaVisita;
/**
 * UsuarioController implements the CRUD actions for Usuario model.
 */
class UsuarioController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only'  => ['index', 'cordinadores', 'CordinadoresTorta', 'DeleteMacro', 'DeleteMicro', 'View', 'Create', 'Update', 'Delete', 
                            'ActividadesMacro', 'ActividadesMicro', 'capacitacion', 'comite', 'Siniestro', 'visita', 'incidente', 'merma', 
                            'Evento', 'Mensual','gestiones','reporte_ingreso','visita','indicadorCapacitaciones','visita_user','inspSemestral'],
                'rules' => [
                    [
                        'allow'   => true,
                        'actions' => ['index', 'cordinadores', 'CordinadoresTorta', 'DeleteMacro', 'DeleteMicro', 'View', 'Create', 'Update', 'Delete', 
                                      'ActividadesMacro', 'ActividadesMicro', 'capacitacion', 'comite', 'Siniestro', 'visita', 'incidente', 'merma', 
                                      'Evento', 'Mensual','gestiones','reporte_ingreso','indicadorCapacitaciones','visita_user','inspSemestral'],
                        'roles'   => ['@'], //para usuarios logueados
                    ],
                ],  
            ],
            'verbs' => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Usuario models.
     * @return mixed
     */
    public function actionIndex()
    {

        $roles = Yii::$app->session['rol-exito'];

        $usuarios = Usuario::find()->orderBy(['apellidos' => SORT_ASC])->all();

        return $this->render('index', [
            'usuarios' => $usuarios,

        ]);
    }

    public function actionHashPassword(){
        $usuarios=Usuario::find()->all();
        foreach ($usuarios as $value) {
            $model     = $this->findModel($value->usuario);
            $hash = Yii::$app->getSecurity()->generatePasswordHash($model->password);
            $model->setAttribute('password',$hash);
            $model->save();
        }

         return $this->redirect(['index']);
    }
   /* public function actionCordinadores()
    {

        $array_post = Yii::$app->request->post();

        $primaryConnection = Yii::$app->db;

        $sql = '';

        $parametros_array = array();

        if (array_key_exists('consultar', $array_post)) {

            if (array_key_exists('fecha_inicial', $array_post) && array_key_exists('fecha_final', $array_post)) {

                if ($array_post['fecha_inicial'] != '' && $array_post['fecha_final'] != '') {

                    $sql .= ' AND c.fecha BETWEEN :FECHA_1 AND DATE_ADD(:FECHA_2, INTERVAL 1 DAY) ';

                    $parametros_array[':FECHA_1'] = $array_post['fecha_inicial'];
                    $parametros_array[':FECHA_2'] = $array_post['fecha_final'];

                }

            }

        }

        $capacitaciones_zonas_sql = "SELECT COUNT(*) AS TOTAL, z.nombre AS REGIONAL
                                        FROM capacitacion c, usuario_zona uz, zona z
                                        WHERE  c.usuario = uz.usuario
                                        AND   uz.zona_id = z.id
                                        AND   c.usuario NOT IN ('admin','miguel guevara')" . $sql . "
                                        GROUP BY z.nombre";

        $comites_zonas_sql = "SELECT COUNT(*) AS TOTAL, z.nombre AS REGIONAL
                                FROM comite c, usuario_zona uz, zona z
                                WHERE  c.usuario = uz.usuario
                                AND   uz.zona_id = z.id
                                AND   c.usuario NOT IN ('admin','miguel guevara')" . $sql . "
                                GROUP BY z.nombre";

        $visitas_zonas_sql = "SELECT COUNT(*) AS TOTAL, z.nombre AS REGIONAL
                                FROM visita_dia c, usuario_zona uz, zona z
                                WHERE  c.usuario = uz.usuario
                                AND   uz.zona_id = z.id
                                AND   c.usuario NOT IN ('admin','miguel guevara')" . $sql . "
                                GROUP BY z.nombre";

        $visitas_mensuales_zonas_sql = "SELECT COUNT(*) AS TOTAL, z.nombre AS REGIONAL
                                FROM visita_mensual c, usuario_zona uz, zona z
                                WHERE  c.usuario = uz.usuario
                                AND   uz.zona_id = z.id
                                AND   c.usuario NOT IN ('admin','miguel guevara')" . $sql . "
                                GROUP BY z.nombre";

        $siniestros_zonas_sql = "SELECT COUNT(*) AS TOTAL, z.nombre AS REGIONAL
                                FROM siniestro c, usuario_zona uz, zona z
                                WHERE  c.usuario = uz.usuario
                                AND   uz.zona_id = z.id
                                AND   c.usuario NOT IN ('admin','miguel guevara')" . $sql . "
                                GROUP BY z.nombre";

        $incidentes_zonas_sql = "SELECT COUNT(*) AS TOTAL, z.nombre AS REGIONAL
                                        FROM incidente c, usuario_zona uz, zona z
                                        WHERE  c.usuario = uz.usuario
                                        AND   uz.zona_id = z.id
                                        AND   c.usuario NOT IN ('admin','miguel guevara')" . $sql . "
                                        GROUP BY z.nombre";

        $capacitacionesCommand = $primaryConnection->createCommand($capacitaciones_zonas_sql);
        $comitesCommand        = $primaryConnection->createCommand($comites_zonas_sql);
        $visitasCommand        = $primaryConnection->createCommand($visitas_zonas_sql);
        $siniestrosCommand     = $primaryConnection->createCommand($siniestros_zonas_sql);
        $incidentesCommand     = $primaryConnection->createCommand($incidentes_zonas_sql);

        if (array_key_exists('consultar', $array_post)) {

            $consolidadoCapacitaciones = $capacitacionesCommand->bindValues($parametros_array)->queryAll();

            $consolidadoComites = $comitesCommand->bindValues($parametros_array)->queryAll();

            $consolidadoVisitas = $visitasCommand->bindValues($parametros_array)->queryAll();

            $consolidadoSiniestros = $siniestrosCommand->bindValues($parametros_array)->queryAll();

            $consolidadoIncidentes = $incidentesCommand->bindValues($parametros_array)->queryAll();
        } else {

            $consolidadoCapacitaciones = $capacitacionesCommand->queryAll();

            $consolidadoComites = $comitesCommand->queryAll();

            $consolidadoVisitas = $visitasCommand->queryAll();

            $consolidadoSiniestros = $siniestrosCommand->queryAll();

            $consolidadoIncidentes = $incidentesCommand->queryAll();

        }

        $roles       = Yii::$app->session['rol-exito'];
        $active_user = Usuario::findOne(Yii::$app->session['usuario-exito']);
        $usuarios    = Usuario::find()->orderBy(['apellidos' => SORT_ASC])->all();

        return $this->render('cordinadores', [
            'usuarios'                  => $usuarios,
            'active_user'               => $active_user,
            'consolidadoCapacitaciones' => $consolidadoCapacitaciones,
            'consolidadoComites'        => $consolidadoComites,
            'consolidadoVisitas'        => $consolidadoVisitas,
            'consolidadoSiniestros'     => $consolidadoSiniestros,
            'consolidadoIncidentes'     => $consolidadoSiniestros,
        ]);
    }

    public function actionCordinadoresTorta()
    {

        $regionales = Zona::find()->orderBy(['nombre' => SORT_ASC])->all();

        $array_post = Yii::$app->request->post();

        $primaryConnection = Yii::$app->db;

        $sql = '';

        $parametros_array = array();

        if (array_key_exists('consultar', $array_post)) {

            if (array_key_exists('fecha_inicial', $array_post) && array_key_exists('fecha_final', $array_post)) {

                if ($array_post['fecha_inicial'] != '' && $array_post['fecha_final'] != '') {

                    $sql .= ' AND c.fecha BETWEEN :FECHA_1 AND DATE_ADD(:FECHA_2, INTERVAL 1 DAY) ';

                    $parametros_array[':FECHA_1'] = $array_post['fecha_inicial'];
                    $parametros_array[':FECHA_2'] = $array_post['fecha_final'];

                }

            }

        }

        $capacitaciones_zonas_sql = "SELECT COUNT(*) AS TOTAL, z.nombre AS REGIONAL, n.nombre AS TEMA
                                        FROM capacitacion c, usuario_zona uz, zona z, novedad n
                                        WHERE  c.usuario = uz.usuario
                                        AND   uz.zona_id = z.id
                                        AND   c.usuario NOT IN ('admin','miguel guevara')
                                        AND   c.novedad_id = n.id
                                        AND   n.tipo = 'C'
                                        AND   z.id = :regional" . $sql . "
                                        GROUP BY z.nombre, n.nombre";

        $capacitacionesCommand = $primaryConnection->createCommand($capacitaciones_zonas_sql);

        if (array_key_exists('consultar', $array_post)) {

            $parametros_array[':regional'] = $array_post['regional'];
            $consolidadoCapacitaciones     = $capacitacionesCommand->bindValues($parametros_array)->queryAll();
            $selected                      = $array_post['regional'];

        } else {

            $consolidadoCapacitaciones = $capacitacionesCommand->bindValue(':regional', $regionales[0]->id)->queryAll();
            $selected                  = $regionales[0]->id;
        }

        $active_user = Usuario::findOne(Yii::$app->session['usuario-exito']);
        $usuarios    = Usuario::find()->orderBy(['apellidos' => SORT_ASC])->all();

        return $this->render('torta', [
            'usuarios'                  => $usuarios,
            'active_user'               => $active_user,
            'consolidadoCapacitaciones' => $consolidadoCapacitaciones,
            'regionales'                => $regionales,
            'selected'                  => $selected,

        ]);
    }*/

    public function actionCordinadores()
    {

        $array_post = Yii::$app->request->post();

        
        $roles       = Yii::$app->session['rol-exito'];
        $active_user = Usuario::findOne(Yii::$app->session['usuario-exito']);
        $usuarios    = Usuario::find()->orderBy(['apellidos' => SORT_ASC])->all();

        $regionales=Zona::find()->all();
        $regional=isset($_POST['reg'])?$_POST['reg']:'';
        $reg_nombre=isset($_POST['reg'])?Zona::findOne($_POST['reg']):'';
        $fecha_inicial=isset($_POST['fecha_inicial'])?$_POST['fecha_inicial']:'';
        $fecha_final=isset($_POST['fecha_final'])?$_POST['fecha_final']:'';

        return $this->render('cordinadores', [
            'usuarios'                  => $usuarios,
            'active_user'               => $active_user,
            'consolidadoCapacitaciones' => $consolidadoCapacitaciones,
            'consolidadoComites'        => $consolidadoComites,
            'consolidadoVisitas'        => $consolidadoVisitas,
            'consolidadoSiniestros'     => $consolidadoSiniestros,
            'consolidadoIncidentes'     => $consolidadoSiniestros,
            'regionales'                =>$regionales,
            'regional'=> $regional,
            'reg_nombre'=>$reg_nombre,
            'fecha_inicial'=>$fecha_inicial,
            'fecha_final'=>$fecha_final
        ]);
    }

    public function actionDeleteMacro($id, $macro)
    {

        $primaryConnection = Yii::$app->db;
        $primaryCommand    = $primaryConnection->createCommand("DELETE
                                                             FROM  usuario_macroactividad
                                                             WHERE usuario = :usuario
                                                             AND   macroactividad_id = :macro
                                                             ");

        $borrado = $primaryCommand->bindValue(':usuario', $id)
            ->bindValue(':macro', $macro)
            ->execute();

        return $this->redirect(Yii::$app->request->baseUrl . '/usuario/actividades-macro?id=' . $id);

    }

    public function actionDeleteMicro($id, $micro)
    {

        $primaryConnection = Yii::$app->db;
        $primaryCommand    = $primaryConnection->createCommand("DELETE
                                                             FROM  usuario_microactividad
                                                             WHERE usuario = :usuario
                                                             AND   microactividad_id = :micro
                                                             ");

        $borrado = $primaryCommand->bindValue(':usuario', $id)
            ->bindValue(':micro', $micro)
            ->execute();

        return $this->redirect(Yii::$app->request->baseUrl . '/usuario/actividades-micro?id=' . $id);

    }

    //$id usuario
    public function actionActividadesMacro($id)
    {

        $array_post = Yii::$app->request->post();

        $macroactividades_asignadas = UsuarioMacroactividad::find()->where(['usuario' => $id])->all();
        $usuario                    = $this->findModel($id);

        $asignaciones_macro = array_key_exists('macroactividades-asi', $array_post) ? $array_post['macroactividades-asi'] : array();

        $tamano_asignaciones_macro = count($asignaciones_macro);

        $index = 0;

        while ($index < $tamano_asignaciones_macro) {

            $model = new UsuarioMacroactividad();
            $model->SetAttribute('macroactividad_id', $asignaciones_macro[$index]);
            $model->SetAttribute('usuario', $id);
            $model->save();
            $index++;

        }

        if ($tamano_asignaciones_macro > 0) {

            return $this->redirect('index');

        }

        $macroactividades = Macroactividad::find()->orderBy(['nombre' => SORT_ASC])->all();

        return $this->render('macroactividades', ['usuario' => $usuario, 'macroactividades_asignadas' => $macroactividades_asignadas, 'macroactividades' => $macroactividades]);

    }

    public function actionActividadesMicro($id)
    {

        $array_post = Yii::$app->request->post();

        $microactividades_asignadas = UsuarioMicroactividad::find()->where(['usuario' => $id])->all();
        $usuario                    = $this->findModel($id);

        $asignaciones_micro = array_key_exists('microactividades-asi', $array_post) ? $array_post['microactividades-asi'] : array();

        $tamano_asignaciones_micro = count($asignaciones_micro);

        $index = 0;

        while ($index < $tamano_asignaciones_micro) {

            $model = new UsuarioMicroactividad();
            $model->SetAttribute('microactividad_id', $asignaciones_micro[$index]);
            $model->SetAttribute('usuario', $id);
            $model->save();
            $index++;

        }

        if ($tamano_asignaciones_micro > 0) {

            return $this->redirect('index');

        }

        $microactividades = Microactividad::find()->orderBy(['nombre' => SORT_ASC])->all();

        return $this->render('microactividades', ['usuario' => $usuario, 'microactividades_asignadas' => $microactividades_asignadas, 'microactividades' => $microactividades]);

    }

    public function actionCapacitacion($id)
    {

        //obtener objeto usuario
        $usuarioObj        = Usuario::findOne($id);
        $roles             = array();
        $regional          = false;
        $array_post        = Yii::$app->request->post();
        $primaryConnection = Yii::$app->db;

        if ($usuarioObj != null) {

            $roles = $usuarioObj->roles;

            if ($roles != null) {

                foreach ($roles as $rol) {

                    if ($rol->rol->nombre == 'Coordinador Regional') {

                        $regional = true;
                        break;

                    }

                }

            }

        }

        $consolidadoCoordinadores = array();
        $consolidadoTemas         = array();

        if ($regional) {

            $zonasObj = $usuarioObj->zonas;

            if ($zonasObj != null) {

                foreach ($zonasObj as $zona) {

                    $usuarios    = $zona->zona->usuarios;
                    $regional_id = $zona->zona_id;

                    $sql = '';

                    $parametros_array = array();

                    if (array_key_exists('consultar', $array_post)) {

                        if (array_key_exists('fecha_inicial', $array_post) && array_key_exists('fecha_final', $array_post)) {

                            if ($array_post['fecha_inicial'] != '' && $array_post['fecha_final'] != '') {

                                $sql .= ' AND c.fecha BETWEEN :FECHA_1 AND DATE_ADD(:FECHA_2, INTERVAL 1 DAY) ';

                                $parametros_array[':FECHA_1'] = $array_post['fecha_inicial'];
                                $parametros_array[':FECHA_2'] = $array_post['fecha_final'];

                            }

                        }

                    }

                    $regionalSql = "SELECT COUNT(*) AS TOTAL, uz.usuario AS USER
                                        FROM capacitacion c, usuario_zona uz
                                        WHERE  c.usuario = uz.usuario
                                        AND   c.usuario NOT IN ('admin','miguel guevara')
                                        AND   uz.zona_id = :regional" . $sql . "
                                        GROUP BY uz.usuario
                                        ";

                    $temaSql = "SELECT COUNT(*) AS TOTAL, z.nombre AS REGIONAL, n.nombre AS TEMA
                                        FROM capacitacion c, usuario_zona uz, zona z, novedad n
                                        WHERE  c.usuario = uz.usuario
                                        AND   uz.zona_id = z.id
                                        AND   c.usuario NOT IN ('admin','miguel guevara')
                                        AND   c.novedad_id = n.id
                                        AND   n.tipo = 'C'
                                        AND   z.id = :regional" . $sql . "
                                        GROUP BY z.nombre, n.nombre";

                    $coordinadoresCommand = $primaryConnection->createCommand($regionalSql);
                    $temasCommand         = $primaryConnection->createCommand($temaSql);

                    $parametros_array[':regional'] = $regional_id;
                    $consolidadoCoordinadores      = $coordinadoresCommand->bindValues($parametros_array)->queryAll();
                    $consolidadoTemas              = $temasCommand->bindValues($parametros_array)->queryAll();

                    $capacitaciones = array();
                    $temporal       = array();

                    if ($usuarios != null) {

                        foreach ($usuarios as $key) {

                            $usuarioKey = $key->usuario0;
                            //VarDumper::dump($key->);
                            if ($usuarioKey != null) {

                                $rolesKey = $usuarioKey->roles;

                            }

                            if ($rolesKey != null && $rolesKey[0]->rol->id != 1) {

                                $temporal       = Capacitacion::find()->where(['usuario' => $key->usuario])->orderBy(['id' => SORT_DESC])->all();
                                 $rows = (new \yii\db\Query())
                                ->select(['cap.id as id_cap','cap.fecha','cap.usuario','dep.nombre as Dependencia',
                                    'nov.nombre as Novedad'
                                    ])
                                ->from('capacitacion_dependencia cpd')
                                ->leftJoin('capacitacion as cap', 'cpd.capacitacion_id=cap.id')
                                ->leftJoin('novedad as nov', 'cap.novedad_id=nov.id')
                                ->leftJoin('centro_costo as dep', 'dep.codigo=cpd.centro_costo_codigo')
                                ->where('cap.usuario="'.$id.'" ')
                                ->orderBy(['cap.id'=> SORT_DESC]);

                                $command = $rows->createCommand();
                                $capacitaciones = $command->queryAll();

                                //$capacitaciones = array_merge($capacitaciones, $temporal);

                            }

                        }

                    }

                }

            }

        } else {

            //$capacitaciones = Capacitacion::find()->where(['usuario' => $id])->orderBy(['id' => SORT_DESC])->all();
             $rows = (new \yii\db\Query())
            ->select(['cap.id as id_cap','cap.fecha','cap.usuario','dep.nombre as Dependencia',
                'nov.nombre as Novedad'
                ])
            ->from('capacitacion_dependencia cpd')
            ->leftJoin('capacitacion as cap', 'cpd.capacitacion_id=cap.id')
            ->leftJoin('novedad as nov', 'cap.novedad_id=nov.id')
            ->leftJoin('centro_costo as dep', 'dep.codigo=cpd.centro_costo_codigo')
            ->where('cap.usuario="'.$id.'" ')
            ->orderBy(['cap.id'=> SORT_DESC]);

            $command = $rows->createCommand();
            
            $capacitaciones = $command->queryAll();

        }

        return $this->render('capacitacion', [

            'capacitaciones'           => 'active',
            'capacitaciones_usuario'   => $capacitaciones,
            'usuario'                  => $id,
            'consolidadoCoordinadores' => $consolidadoCoordinadores,
            'consolidadoTemas'         => $consolidadoTemas,

        ]);

    }


     public function actionIndicadorCapacitaciones($id){
        $dependencias_user=$this->dependencias_usuario($id);

        $in=" IN(";

        foreach ($dependencias_user as $value) {
            
            $in.=" '".$value."',";    
        }

        $in_final = substr($in, 0, -1).")";
        /**********************DEPENDENCIAS******************************/
        $novedades=Novedad::find()->where('tipo="C" AND estado="A"')->all();
        $connection = \Yii::$app->db;
        $torta=array();
        $capacitaciones_tema=array();
        $ano=date('Y');

        $dependencias=CentroCosto::find()->where('codigo '.$in_final.' ')->all();

        if($_POST['inicio']=='' && $_POST['final']==''){

            $filtro="'$ano-01-01' AND '$ano-12-31'";
        }else{

            $filtro="'".$_POST['inicio']."' AND '".$_POST['final']."'";
            
        }

        foreach ($novedades as  $value) {
            $sql='SELECT SUM(cd.cantidad) as cantidad,COUNT(capacitacion.id) as capacitaciones FROM capacitacion  
            inner join capacitacion_dependencia as cd on cd.capacitacion_id=capacitacion.id
            inner join  centro_costo as cc on cd.centro_costo_codigo=cc.codigo
            where cd.centro_costo_codigo '.$in_final.' AND capacitacion.novedad_id=:novedad  AND (fecha_capacitacion BETWEEN '.$filtro.') AND cc.indicador_capacitacion="S"
            ';
            $capDep= $connection->createCommand($sql, [
                ':novedad'=>$value->id,
                //':ano'=>$ano
            ])->queryOne();
            $torta[]=array('name'=>$value->nombre,'y'=>(int)$capDep['cantidad']);
            $capacitaciones_tema[]=array('name'=>$value->nombre,'y'=>(int)$capDep['cantidad'],'capacitaciones'=>$capDep['capacitaciones']);
        }
        $torta=json_encode($torta);

        return $this->render('indicador_capacitaciones', [
            'torta'              =>$torta,
            'capacitaciones_tema'=>$capacitaciones_tema,
            'usuario'=>$id,
            'dependencias'=>$dependencias,
            'inicio'=>$_POST['inicio'],
            'final'=>$_POST['final']
        ]);
    }

    public function actionComite($id)
    {

        //obtener objeto usuario
        $usuarioObj        = Usuario::findOne($id);
        $roles             = array();
        $regional          = false;
        $array_post        = Yii::$app->request->post();
        $primaryConnection = Yii::$app->db;

        if ($usuarioObj != null) {

            $roles = $usuarioObj->roles;

            if ($roles != null) {

                foreach ($roles as $rol) {

                    if ($rol->rol->nombre == 'Coordinador Regional') {

                        $regional = true;
                        break;

                    }

                }

            }

        }

        $consolidadoCoordinadores = array();
        $consolidadoTemas         = array();

        if ($regional) {

            $zonasObj = $usuarioObj->zonas;

            if ($zonasObj != null) {

                foreach ($zonasObj as $zona) {

                    $usuarios    = $zona->zona->usuarios;
                    $regional_id = $zona->zona_id;

                    $sql = '';

                    $parametros_array = array();

                    if (array_key_exists('consultar', $array_post)) {

                        if (array_key_exists('fecha_inicial', $array_post) && array_key_exists('fecha_final', $array_post)) {

                            if ($array_post['fecha_inicial'] != '' && $array_post['fecha_final'] != '') {

                                $sql .= ' AND c.fecha BETWEEN :FECHA_1 AND DATE_ADD(:FECHA_2, INTERVAL 1 DAY) ';

                                $parametros_array[':FECHA_1'] = $array_post['fecha_inicial'];
                                $parametros_array[':FECHA_2'] = $array_post['fecha_final'];

                            }

                        }

                    }

                    $regionalSql = "SELECT COUNT(*) AS TOTAL, uz.usuario AS USER
                                        FROM comite c, usuario_zona uz
                                        WHERE  c.usuario = uz.usuario
                                        AND   c.usuario NOT IN ('admin','miguel guevara')
                                        AND   uz.zona_id = :regional" . $sql . "
                                        GROUP BY uz.usuario
                                        ";

                    $temaSql = "SELECT COUNT(*) AS TOTAL, z.nombre AS REGIONAL, n.nombre AS TEMA
                                        FROM comite c, usuario_zona uz, zona z, novedad n
                                        WHERE  c.usuario = uz.usuario
                                        AND   uz.zona_id = z.id
                                        AND   c.usuario NOT IN ('admin','miguel guevara')
                                        AND   c.novedad_id = n.id
                                        AND   n.tipo = 'D'
                                        AND   z.id = :regional" . $sql . "
                                        GROUP BY z.nombre, n.nombre";

                    $coordinadoresCommand = $primaryConnection->createCommand($regionalSql);
                    $temasCommand         = $primaryConnection->createCommand($temaSql);

                    $parametros_array[':regional'] = $regional_id;
                    $consolidadoCoordinadores      = $coordinadoresCommand->bindValues($parametros_array)->queryAll();
                    $consolidadoTemas              = $temasCommand->bindValues($parametros_array)->queryAll();
                    $comites                       = array();
                    $temporal                      = array();

                    if ($usuarios != null) {

                        foreach ($usuarios as $key) {

                            $usuarioKey = $key->usuario0;
                            //VarDumper::dump($key->);
                            if ($usuarioKey != null) {

                                $rolesKey = $usuarioKey->roles;

                            }

                            if ($rolesKey != null && $rolesKey[0]->rol->id != 1) {

                                $temporal = Comite::find()->where(['usuario' => $key->usuario])->orderBy(['id' => SORT_DESC])->all();
                                $comites  = array_merge($comites, $temporal);

                            }

                        }

                    }

                }

            }

        } else {

            $comites = Comite::find()->where(['usuario' => $id])->orderBy(['id' => SORT_DESC])->all();

        }

        return $this->render('comite', [

            'comites'                  => 'active',
            'comites_usuario'          => $comites,
            'usuario'                  => $id,
            'consolidadoCoordinadores' => $consolidadoCoordinadores,
            'consolidadoTemas'         => $consolidadoTemas,

        ]);

    }



    public function actionGestiones($id){

        $primaryConnection = Yii::$app->db;

        $dependencias=$this->dependencias_usuario($id);

        $in=" IN(";

        foreach ($dependencias as $value) {
            
            $in.=" '".$value."',";    
        }

        $in_final = substr($in, 0, -1).")";


        ////Categorias
        $temas=ConsultasGestion::find()->orderBy(['id' => SORT_ASC])->all();
        $datos=array(
            array('name'=>'Cumple','data'=>array()),
            array('name'=>'No cumple ','data'=>array()),
            array('name'=>'En proceso','data'=>array()),
            array('name'=>'No aplica','data'=>array())

        );

        $categorias=array();
        foreach ($temas as $row_temas) {
            $categorias[]=$row_temas->descripcion;
            //////Cumplen
             $sql_cumplen="
            SELECT COUNT(gestion_riesgo.id_centro_costo)as total FROM gestion_riesgo
            INNER JOIN detalle_gestion_riesgo ON gestion_riesgo.id=detalle_gestion_riesgo.id_gestion
            WHERE  (detalle_gestion_riesgo.id_consulta=".$row_temas->id."  AND detalle_gestion_riesgo.id_respuesta=2 /*AND gestion_riesgo.usuario='".$id."'*/) AND (gestion_riesgo.id_centro_costo ".$in_final.")
            ";

            $cumplen=$primaryConnection->createCommand($sql_cumplen)->queryOne();

            $datos[0]['data'][]=(int)$cumplen['total'];
            ///////////

            //NO CUMPLEN
             $sql_no_cumplen="
        SELECT COUNT(gestion_riesgo.id_centro_costo)as total FROM gestion_riesgo
        INNER JOIN detalle_gestion_riesgo ON gestion_riesgo.id=detalle_gestion_riesgo.id_gestion
        WHERE detalle_gestion_riesgo.id_consulta=".$row_temas->id."  AND detalle_gestion_riesgo.id_respuesta=3 /*AND gestion_riesgo.usuario='".$id."'*/ AND (gestion_riesgo.id_centro_costo ".$in_final.")
        ";

        $no_cumplen=$primaryConnection->createCommand($sql_no_cumplen)->queryOne();

        $datos[1]['data'][]=(int)$no_cumplen['total'];
            ////////////////

        //En proceso
         $sql_proceso="
        SELECT COUNT(gestion_riesgo.id_centro_costo)as total FROM gestion_riesgo
        INNER JOIN detalle_gestion_riesgo ON gestion_riesgo.id=detalle_gestion_riesgo.id_gestion
        WHERE detalle_gestion_riesgo.id_consulta=".$row_temas->id."  AND detalle_gestion_riesgo.id_respuesta=4 /*AND gestion_riesgo.usuario='".$id."'*/ AND (gestion_riesgo.id_centro_costo ".$in_final.")
        ";

        $en_proceso=$primaryConnection->createCommand($sql_proceso)->queryOne();

        $datos[2]['data'][]=(int)$en_proceso['total'];
        //////////////////


        //No aplica
         $sql_na="
        SELECT COUNT(gestion_riesgo.id_centro_costo)as total FROM gestion_riesgo
        INNER JOIN detalle_gestion_riesgo ON gestion_riesgo.id=detalle_gestion_riesgo.id_gestion
        WHERE detalle_gestion_riesgo.id_consulta=".$row_temas->id."  AND detalle_gestion_riesgo.id_respuesta=5 /*AND gestion_riesgo.usuario='".$id."'*/ AND (gestion_riesgo.id_centro_costo ".$in_final.")
        ";

        $na=$primaryConnection->createCommand($sql_na)->queryOne();

        $datos[3]['data'][]=(int)$na['total'];

        }

        $json=json_encode($datos);
        // echo "<pre>";
        // print_r($json);
        // echo "</pre>";

        $categoria_temas=json_encode($categorias);
        //////////////////////////////////////////////
        
        $gestiones=GestionRiesgo::find()->where('id_centro_costo '.$in_final.' ')->all();

       
        ///////////
        //echo $in_final;
        return $this->render('gestiones', [

           'usuario'=>$id,
           'json'=>$json,
           'gestiones'=>'active',
           'consulta'=>$gestiones,
           'categorias'=>$categoria_temas

        ]);
    }

    public function actionDelete_gestiones($id,$usuario){

        $model = GestionRiesgo::findOne($id);

        $model_detalle=DetalleGestionRiesgo::deleteAll('id_gestion ='.$id.' ');


        if ($model != null) {

            $model->delete();

        }

        Yii::$app->session->setFlash('success',' Eliminado correctamente');

        return $this->redirect(['gestiones', 'id' => $usuario]);
    }


     public function dependencias_usuario($id){

        $usuario= Usuario::findOne($id);
        $zonasUsuario     = array();
        $marcasUsuario    = array();
        $distritosUsuario = array();
        $dependencias     = CentroCosto::find()->where(['not in', 'estado', ['C']])->orderBy(['nombre' => SORT_ASC])->all();

        if ($usuario != null) {

            $zonasUsuario     = $usuario->zonas;
            $marcasUsuario    = $usuario->marcas;
            $distritosUsuario = $usuario->distritos;

        }


        $ciudades_zonas = array();

            foreach($zonasUsuario as $zona){
                
                 $ciudades_zonas [] = $zona->zona->ciudades;    
                
            }

            $ciudades_permitidas = array();

            foreach($ciudades_zonas as $ciudades){
                
                foreach($ciudades as $ciudad){
                    
                    $ciudades_permitidas [] = $ciudad->ciudad->codigo_dane;
                    
                }
                
            }

            $marcas_permitidas = array();

            foreach($marcasUsuario as $marca){
                
                    
                    $marcas_permitidas [] = $marca->marca_id;

            }

            $dependencias_distritos = array();

            foreach($distritosUsuario as $distrito){
                
                 $dependencias_distritos [] = $distrito->distrito->dependencias;    
                
            }

            $dependencias_permitidas = array();

            foreach($dependencias_distritos as $dependencias0){
                
                foreach($dependencias0 as $dependencia0){
                    
                    $dependencias_permitidas [] = $dependencia0->dependencia->codigo;
                    
                }
                
            }


            foreach($dependencias as $value){
    
                if(in_array($value->ciudad_codigo_dane,$ciudades_permitidas)){
                    
                    if(in_array($value->marca_id,$marcas_permitidas)){
                        
                       if($tamano_dependencias_permitidas > 0){
                           
                           if(in_array($value->codigo,$dependencias_permitidas)){
                               
                             $data_dependencias[$value->codigo] =  $value->nombre;
                               
                           }else{
                               //temporal mientras se asocian distritos
                               $data_dependencias[] =  $value->codigo;
                           }
                           
                           
                       }else{
                           
                           $data_dependencias[] =  $value->codigo;
                       }    
                   
                    }

                }
            }
            return $data_dependencias;



    }



    public function actionSiniestro($id)
    {
        //obtener objeto usuario
        $usuarioObj        = Usuario::findOne($id);
        $roles             = array();
        $regional          = false;
        $array_post        = Yii::$app->request->post();
        $primaryConnection = Yii::$app->db;

        if ($usuarioObj != null) {

            $roles = $usuarioObj->roles;

            if ($roles != null) {

                foreach ($roles as $rol) {

                    if ($rol->rol->nombre == 'Coordinador Regional') {

                        $regional = true;
                        break;

                    }

                }

            }

        }

        $consolidadoCoordinadores = array();
        $consolidadoTemas         = array();

        if ($regional) {

            $zonasObj = $usuarioObj->zonas;

            if ($zonasObj != null) {

                foreach ($zonasObj as $zona) {

                    $usuarios    = $zona->zona->usuarios;
                    $regional_id = $zona->zona_id;

                    $sql = '';

                    $parametros_array = array();

                    if (array_key_exists('consultar', $array_post)) {

                        if (array_key_exists('fecha_inicial', $array_post) && array_key_exists('fecha_final', $array_post)) {

                            if ($array_post['fecha_inicial'] != '' && $array_post['fecha_final'] != '') {

                                $sql .= ' AND c.fecha BETWEEN :FECHA_1 AND DATE_ADD(:FECHA_2, INTERVAL 1 DAY) ';

                                $parametros_array[':FECHA_1'] = $array_post['fecha_inicial'];
                                $parametros_array[':FECHA_2'] = $array_post['fecha_final'];

                            }

                        }

                    }

                    $regionalSql = "SELECT COUNT(*) AS TOTAL, uz.usuario AS USER
                                        FROM siniestro c, usuario_zona uz
                                        WHERE  c.usuario = uz.usuario
                                        AND   c.usuario NOT IN ('admin','miguel guevara')
                                        AND   uz.zona_id = :regional" . $sql . "
                                        GROUP BY uz.usuario
                                        ";

                    $temaSql = "SELECT COUNT(*) AS TOTAL, z.nombre AS REGIONAL, n.nombre AS TEMA
                                        FROM siniestro c, usuario_zona uz, zona z, novedad n
                                        WHERE  c.usuario = uz.usuario
                                        AND   uz.zona_id = z.id
                                        AND   c.usuario NOT IN ('admin','miguel guevara')
                                        AND   c.novedad_id = n.id
                                        AND   n.tipo = 'S'
                                        AND   z.id = :regional" . $sql . "
                                        GROUP BY z.nombre, n.nombre";

                    $coordinadoresCommand = $primaryConnection->createCommand($regionalSql);
                    $temasCommand         = $primaryConnection->createCommand($temaSql);

                    $parametros_array[':regional'] = $regional_id;
                    $consolidadoCoordinadores      = $coordinadoresCommand->bindValues($parametros_array)->queryAll();
                    $consolidadoTemas              = $temasCommand->bindValues($parametros_array)->queryAll();

                    $siniestros = array();
                    $temporal   = array();

                    if ($usuarios != null) {

                        foreach ($usuarios as $key) {

                            $usuarioKey = $key->usuario0;
                            //VarDumper::dump($key->);
                            if ($usuarioKey != null) {

                                $rolesKey = $usuarioKey->roles;

                            }

                            if ($rolesKey != null && $rolesKey[0]->rol->id != 1) {

                                $temporal   = Siniestro::find()->where(['usuario' => $key->usuario])->orderBy(['id' => SORT_DESC])->all();
                                $siniestros = array_merge($siniestros, $temporal);

                            }

                        }

                    }

                }

            }

        } else {

            $siniestros = Siniestro::find()->where(['usuario' => $id])->orderBy(['id' => SORT_DESC])->all();

        }

        return $this->render('siniestro', [

            'siniestros'               => 'active',
            'siniestros_usuario'       => $siniestros,
            'usuario'                  => $id,
            'consolidadoCoordinadores' => $consolidadoCoordinadores,
            'consolidadoTemas'         => $consolidadoTemas,

        ]);

    }

    public function actionVisita_user($id)
    {
        //obtener objeto usuario
        $usuarioObj        = Usuario::findOne($id);
        $roles             = array();
        $regional          = false;
        $array_post        = Yii::$app->request->post();
        $primaryConnection = Yii::$app->db;

        if ($usuarioObj != null) {

            $roles = $usuarioObj->roles;

            if ($roles != null) {

                foreach ($roles as $rol) {

                    if ($rol->rol->nombre == 'Coordinador Regional') {

                        $regional = true;
                        break;

                    }

                }

            }

        }

        $consolidadoCoordinadores = array();

        if ($regional) {

            $zonasObj = $usuarioObj->zonas;

            if ($zonasObj != null) {

                foreach ($zonasObj as $zona) {

                    $usuarios    = $zona->zona->usuarios;
                    $sql         = '';
                    $regional_id = $zona->zona_id;

                    $parametros_array = array();

                    if (array_key_exists('consultar', $array_post)) {

                        if (array_key_exists('fecha_inicial', $array_post) && array_key_exists('fecha_final', $array_post)) {

                            if ($array_post['fecha_inicial'] != '' && $array_post['fecha_final'] != '') {

                                $sql .= ' AND c.fecha BETWEEN :FECHA_1 AND DATE_ADD(:FECHA_2, INTERVAL 1 DAY) ';

                                $parametros_array[':FECHA_1'] = $array_post['fecha_inicial'];
                                $parametros_array[':FECHA_2'] = $array_post['fecha_final'];

                            }

                        }

                    }

                    $regionalSql = "SELECT COUNT(*) AS TOTAL, uz.usuario AS USER
                                        FROM visita_dia c, usuario_zona uz
                                        WHERE  c.usuario = uz.usuario
                                        AND   c.usuario NOT IN ('admin','miguel guevara')
                                        AND   uz.zona_id = :regional" . $sql . "
                                        GROUP BY uz.usuario
                                        ";

                    $coordinadoresCommand          = $primaryConnection->createCommand($regionalSql);
                    $parametros_array[':regional'] = $regional_id;

                    $consolidadoCoordinadores = $coordinadoresCommand->bindValues($parametros_array)->queryAll();
                    $visitas                  = array();
                    $temporal                 = array();

                    if ($usuarios != null) {

                        foreach ($usuarios as $key) {

                            $usuarioKey = $key->usuario0;
                            //VarDumper::dump($key->);
                            if ($usuarioKey != null) {

                                $rolesKey = $usuarioKey->roles;

                            }

                            if ($rolesKey != null && $rolesKey[0]->rol->id != 1) {

                                $temporal = VisitaDia::find()->where(['usuario' => $key->usuario])->orderBy(['id' => SORT_DESC])->all();
                                $visitas  = array_merge($visitas, $temporal);

                            }

                        }

                    }

                }

            }

        } else {

            $visitas = VisitaDia::find()->where(['usuario' => $id])->orderBy(['id' => SORT_DESC])->all();

        }

        return $this->render('visita_user', [

            'visitas'                  => 'active',
            'visitas_usuario'          => $visitas,
            'usuario'                  => $id,
            'consolidadoCoordinadores' => $consolidadoCoordinadores,

        ]);

    }

    public function actionVisita($id)
    {
 
       //////////DEPENDENCIAS DEL USUARIO
        $model_visita=new VisitaDia;

        $arr_meses=array('01'=>'Enero','02'=>'Febrero','03'=>'Marzo','04'=>'Abril','05'=>'Mayo','06'=>'Junio','07'=>'Julio','08'=>'Agosto',
            '09'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre'
        );

        $dependencias_user=$this->dependencias_usuario($id);

        $in=" IN(";

        foreach ($dependencias_user as $value) {
            
            $in.=" '".$value."',";    
        }

        $in_final = substr($in, 0, -1).")";
        /**********************DEPENDENCIAS******************************/

        /*********paginacion************************/
        $page=0;$rowsPerPage=50;
        if(isset($_POST['page'])) {
            if($_POST['page']!=0){
                $page = (isset($_POST['page']) ? $_POST['page'] : 1);
                $cur_page = $page;
                $page -= 1;
                $per_page = $rowsPerPage; // Per page records
                $previous_btn = true;
                $next_btn = true;
                $first_btn = true;
                $last_btn = true;
                $start = $page * $per_page;
            }else{
                $per_page = $rowsPerPage; // Per page records
                $start = $page * $per_page;
                $cur_page = 1;
                $previous_btn = true;
                $next_btn = true;
                $first_btn = true;
                $last_btn = true;
            }
        }else{
            $per_page = $rowsPerPage; // Per page records
            $start = $page * $per_page;
            $cur_page = 1;
            $previous_btn = true;
            $next_btn = true;
            $first_btn = true;
            $last_btn = true;
        }
        /******************************************/
        $dependencias = (new \yii\db\Query())
        ->select(['cc.nombre','cc.codigo','(
            select zona.nombre  from centro_costo cco
            inner join ciudad_zona cz on cco.ciudad_codigo_dane=cz.ciudad_codigo_dane
            inner join zona on  cz.zona_id=zona.id
            WHERE cco.codigo=cc.codigo limit 1
            ) regional','c.nombre as ciudad','cc.indicador_visita'])
        ->from('centro_costo cc')
        ->leftJoin(['ciudad AS c'], 'c.codigo_dane=cc.ciudad_codigo_dane')
        ->where(' cc.codigo '.$in_final.' ');

        if(isset($_POST['buscar'])){
            if (trim($_POST['buscar'])!='') {
                $buscar=trim($_POST['buscar']);
                $dependencias->andWhere("cc.nombre like '%".$_POST['buscar']."%'");
            }
        }

        $rowsCount= clone $dependencias;
        //$dependencias->limit($rowsPerPage)->offset($start);
        $command = $dependencias->createCommand();
        $deps = $command->queryAll();

        $modelcount = $rowsCount->count();
        $no_of_paginations = ceil($modelcount / $per_page);
        $res='';
        if($modelcount > $rowsPerPage){
           
            /*$res.=$this->renderPartial('_paginacion_partial', array(
                'cur_page' => $cur_page,
                'no_of_paginations' => $no_of_paginations,
                'first_btn' => $first_btn,
                'previous_btn' => $previous_btn,
                'next_btn' => $next_btn,
                'last_btn' => $last_btn,
                'modelcount' => $modelcount
                //'model_dispositivo'=>$model_dispositivo
            ), true);*/
        }

        $res.= $this->renderPartial('_partial_visita_dia', array(
            'dependencias'             =>$deps,
            'model_visita'       =>$model_visita,
            'arr_meses'=>$arr_meses
                ), true);
        //*******************************************************************************//

        //JSON PARA LOS GRAFICOS*********************************************************
        $ano=date('Y');
        $fecha_inicio=isset($_POST['fecha_inicial'])?$_POST['fecha_inicial']:'';
        $fecha_final=isset($_POST['fecha_final'])?$_POST['fecha_final']:'';

        $rows_bueno = (new \yii\db\Query())
        ->select(['COUNT(dvd.id)AS total','resultado.nombre'])
        ->from('detalle_visita_dia AS dvd')
        ->leftJoin(['visita_dia AS vd'], 'dvd.visita_dia_id=vd.id')
        ->leftJoin(['centro_costo AS cc'], 'vd.centro_costo_codigo=cc.codigo')
        ->leftJoin(['resultado'], 'dvd.resultado_id=resultado.id')
        ->where("(resultado.nombre IN('Bueno','Malo','Regular') )  AND (vd.centro_costo_codigo ".$in_final.") AND (cc.indicador_visita='S') AND cc.estado NOT IN('C')");

        if ($fecha_inicio!='' AND $fecha_final!='' ) {
            $rows_bueno->andWhere("DATE(vd.fecha) between '".$_POST['fecha_inicial']."' AND '".$_POST['fecha_final']."'");
        }else{

            $rows_bueno->andWhere(" (YEAR(fecha)='".$ano."') ");
        }
        
        $rows_bueno->groupBy(['resultado.nombre']);

        $command_bueno = $rows_bueno->createCommand();
        
        $resultado_bueno = $command_bueno->queryAll();

        $arreglo_bueno=array();
        foreach ($resultado_bueno as $key => $value) {
            
            $arreglo_bueno[]=array('name'=>(string)$value['nombre'],'y'=>(int)$value['total'] );
        }

        $json_bueno=json_encode($arreglo_bueno);
        /********************************************/

        $rows_negativo = (new \yii\db\Query())
        ->select(['COUNT(dvd.id)AS total','ct.nombre'])
        ->from('detalle_visita_dia AS dvd')
        ->leftJoin(['visita_dia AS vd'], 'dvd.visita_dia_id=vd.id')
         ->leftJoin(['centro_costo AS cc'], 'vd.centro_costo_codigo=cc.codigo')
        ->leftJoin(['resultado'], 'dvd.resultado_id=resultado.id')
        ->leftJoin(['novedad_categoria_visita AS nc'], 'dvd.novedad_categoria_visita_id=nc.id')
        ->leftJoin(['categoria_visita AS ct'], 'nc.categoria_visita_id=ct.id')
        ->where("(resultado.nombre IN('Bueno','Malo','Regular') ) AND (vd.centro_costo_codigo".$in_final.") AND (ct.estado='A') AND (cc.indicador_visita='S') AND cc.estado NOT IN('C')");

        if ($fecha_inicio!='' AND $fecha_final!='') {

            $rows_negativo->andWhere("DATE(vd.fecha) between '".$_POST['fecha_inicial']."' AND '".$_POST['fecha_final']."'");
        }else{

            $rows_negativo->andWhere(" (YEAR(fecha)='".$ano."') ");

        }

        $rows_negativo->groupBy(['ct.nombre']);

        $command_negativo = $rows_negativo->createCommand();
        
        $resultado_negativo = $command_negativo->queryAll();

        $arreglo_negativo=array();
        foreach ($resultado_negativo as $key1 => $value1) {
            
            $arreglo_negativo[]=array('name'=>(string)$value1['nombre'],'y'=>(int)$value1['total'] );
        }

        $json_negativo=json_encode($arreglo_negativo);

        //$usuario = Usuario::findOne(Yii::$app->session['usuario-exito']);
        $zonasUsuario = UsuarioZona::find()->where('usuario="'.$id.'" ')->all();  

        if(isset($_POST['page'])){
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'respuesta' => $res,
                //'query' => $command->sql,
            ];
        }else{



            return $this->render('visita', [
                'partial' => $res,
                'visitas'                  => 'active',
                'usuario'                  => $id,
                //'dependencias'             =>$dependencias,
                'json_bueno'               =>$json_bueno,
                'json_negativo'            =>$json_negativo,
                'fecha_inicio'       =>$fecha_inicio,
                'fecha_final'        =>$fecha_final,
                'model_visita'       =>$model_visita,
                'arr_meses'          =>$arr_meses,
                'zonasUsuario'       =>$zonasUsuario

            ]);
        }

    }

    public function actionIncidente($id)
    {
        //obtener objeto usuario
        $usuarioObj        = Usuario::findOne($id);
        $roles             = array();
        $regional          = false;
        $array_post        = Yii::$app->request->post();
        $primaryConnection = Yii::$app->db;

        if ($usuarioObj != null) {

            $roles = $usuarioObj->roles;

            if ($roles != null) {

                foreach ($roles as $rol) {

                    if ($rol->rol->nombre == 'Coordinador Regional') {

                        $regional = true;
                        break;

                    }

                }

            }

        }

        $consolidadoCoordinadores = array();
        $consolidadoTemas         = array();

        if ($regional) {

            $zonasObj = $usuarioObj->zonas;

            if ($zonasObj != null) {

                foreach ($zonasObj as $zona) {

                    $usuarios    = $zona->zona->usuarios;
                    $regional_id = $zona->zona_id;

                    $sql = '';

                    $parametros_array = array();

                    if (array_key_exists('consultar', $array_post)) {

                        if (array_key_exists('fecha_inicial', $array_post) && array_key_exists('fecha_final', $array_post)) {

                            if ($array_post['fecha_inicial'] != '' && $array_post['fecha_final'] != '') {

                                $sql .= ' AND c.fecha BETWEEN :FECHA_1 AND DATE_ADD(:FECHA_2, INTERVAL 1 DAY) ';

                                $parametros_array[':FECHA_1'] = $array_post['fecha_inicial'];
                                $parametros_array[':FECHA_2'] = $array_post['fecha_final'];

                            }

                        }

                    }

                    $regionalSql = "SELECT COUNT(*) AS TOTAL, uz.usuario AS USER
                                        FROM incidente c, usuario_zona uz
                                        WHERE  c.usuario = uz.usuario
                                        AND   c.usuario NOT IN ('admin','miguel guevara')
                                        AND   uz.zona_id = :regional" . $sql . "
                                        GROUP BY uz.usuario
                                        ";

                    $temaSql = "SELECT COUNT(*) AS TOTAL, z.nombre AS REGIONAL, n.nombre AS TEMA
                                        FROM incidente c, usuario_zona uz, zona z, novedad n
                                        WHERE  c.usuario = uz.usuario
                                        AND   uz.zona_id = z.id
                                        AND   c.usuario NOT IN ('admin','miguel guevara')
                                        AND   c.novedad_id = n.id
                                        AND   n.tipo = 'I'
                                        AND   z.id = :regional" . $sql . "
                                        GROUP BY z.nombre, n.nombre";

                    $coordinadoresCommand = $primaryConnection->createCommand($regionalSql);
                    $temasCommand         = $primaryConnection->createCommand($temaSql);

                    $parametros_array[':regional'] = $regional_id;
                    $consolidadoCoordinadores      = $coordinadoresCommand->bindValues($parametros_array)->queryAll();
                    $consolidadoTemas              = $temasCommand->bindValues($parametros_array)->queryAll();
                    $incidentes                    = array();
                    $temporal                      = array();

                    if ($usuarios != null) {

                        foreach ($usuarios as $key) {

                            $usuarioKey = $key->usuario0;
                            //VarDumper::dump($key->);
                            if ($usuarioKey != null) {

                                $rolesKey = $usuarioKey->roles;

                            }

                            if ($rolesKey != null && $rolesKey[0]->rol->id != 1) {

                                $temporal   = Incidente::find()->where(['usuario' => $key->usuario])->orderBy(['id' => SORT_DESC])->all();
                                $incidentes = array_merge($incidentes, $temporal);

                            }

                        }

                    }

                }

            }

        } else {

            $incidentes = Incidente::find()->where(['usuario' => $id])->orderBy(['id' => SORT_DESC])->all();

        }

        return $this->render('incidente', [

            'investigaciones'          => 'active',
            'incidentes_usuario'       => $incidentes,
            'usuario'                  => $id,
            'consolidadoCoordinadores' => $consolidadoCoordinadores,
            'consolidadoTemas'         => $consolidadoTemas,

        ]);

    }

    public function actionMerma($id)
    {
        //obtener objeto usuario
        $usuarioObj = Usuario::findOne($id);
        $roles      = array();
        $regional   = false;
        if ($usuarioObj != null) {

            $roles = $usuarioObj->roles;

            if ($roles != null) {

                foreach ($roles as $rol) {

                    if ($rol->rol->nombre == 'Coordinador Regional') {

                        $regional = true;
                        break;

                    }

                }

            }

        }

        if ($regional) {

            $zonasObj = $usuarioObj->zonas;

            if ($zonasObj != null) {

                foreach ($zonasObj as $zona) {

                    $usuarios = $zona->zona->usuarios;
                    $mermas   = array();
                    $temporal = array();

                    if ($usuarios != null) {

                        foreach ($usuarios as $key) {

                            $usuarioKey = $key->usuario0;
                            //VarDumper::dump($key->);
                            if ($usuarioKey != null) {

                                $rolesKey = $usuarioKey->roles;

                            }

                            if ($rolesKey != null && $rolesKey[0]->rol->id != 1) {

                                $temporal = Merma::find()->where(['usuario' => $key->usuario])->orderBy(['id' => SORT_DESC])->all();
                                $mermas   = array_merge($mermas, $temporal);

                            }

                        }

                    }

                }

            }

        } else {

            $mermas = Merma::find()->where(['usuario' => $id])->orderBy(['id' => SORT_DESC])->all();

        }

        return $this->render('merma', [

            'investigaciones' => 'active',
            'mermas_usuario'  => $mermas,
            'usuario'         => $id,

        ]);

    }

    public function actionEvento($id)
    {
        //obtener objeto usuario
        $usuarioObj = Usuario::findOne($id);
        $roles      = array();
        $regional   = false;
        if ($usuarioObj != null) {

            $roles = $usuarioObj->roles;

            if ($roles != null) {

                foreach ($roles as $rol) {

                    if ($rol->rol->nombre == 'Coordinador Regional') {

                        $regional = true;
                        break;

                    }

                }

            }

        }

        if ($regional) {

            $zonasObj = $usuarioObj->zonas;

            if ($zonasObj != null) {

                foreach ($zonasObj as $zona) {

                    $usuarios = $zona->zona->usuarios;
                    $visitas  = array();
                    $temporal = array();

                    if ($usuarios != null) {

                        foreach ($usuarios as $key) {

                            $usuarioKey = $key->usuario0;
                            //VarDumper::dump($key->);
                            if ($usuarioKey != null) {

                                $rolesKey = $usuarioKey->roles;

                            }

                            if ($rolesKey != null && $rolesKey[0]->rol->id != 1) {

                                $temporal = Evento::find()->where(['usuario' => $key->usuario])->orderBy(['id' => SORT_DESC])->all();
                                $visitas  = array_merge($visitas, $temporal);

                            }

                        }

                    }

                }

            }

        } else {

            $visitas = Evento::find()->where(['usuario' => $id])->orderBy(['id' => SORT_DESC])->all();

        }

        return $this->render('evento', [

            'visitas'         => 'active',
            'visitas_usuario' => $visitas,
            'usuario'         => $id,

        ]);

    }

/***************Mensual******************/
    public function actionMensual($id)
    {
        //obtener objeto usuario
        $usuarioObj = Usuario::findOne($id);
        $roles      = array();
        $regional   = false;
        if ($usuarioObj != null) {

            $roles = $usuarioObj->roles;

            if ($roles != null) {

                foreach ($roles as $rol) {

                    if ($rol->rol->nombre == 'Coordinador Regional') {

                        $regional = true;
                        break;

                    }

                }

            }

        }

        if ($regional) {

            $zonasObj = $usuarioObj->zonas;

            if ($zonasObj != null) {

                foreach ($zonasObj as $zona) {

                    $usuarios = $zona->zona->usuarios;
                    $visitas  = array();
                    $temporal = array();

                    if ($usuarios != null) {

                        foreach ($usuarios as $key) {

                            $usuarioKey = $key->usuario0;
                            //VarDumper::dump($key->);
                            if ($usuarioKey != null) {

                                $rolesKey = $usuarioKey->roles;

                            }

                            if ($rolesKey != null && $rolesKey[0]->rol->id != 1) {

                                $temporal = VisitaMensual::find()->where(['usuario' => $key->usuario])->orderBy(['id' => SORT_DESC])->all();
                                $visitas  = array_merge($visitas, $temporal);

                            }

                        }

                    }

                }

            }

        } else {

            $visitas = VisitaMensual::find()->where(['usuario' => $id])->orderBy(['id' => SORT_DESC])->all();

        }

        return $this->render('mensual', [

            'visitas'         => 'active',
            'visitas_usuario' => $visitas,
            'usuario'         => $id,

        ]);

    }

/****************************************/

    /**
     * Displays a single Usuario model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Usuario model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $array_post = Yii::$app->request->post(); // almacenar variables POST
        $roles      = Yii::$app->session['rol-exito'];

        $roles     = Rol::find()->orderBy(['nombre' => SORT_ASC])->all();
        $distritos = Distrito::find()->orderBy(['nombre' => SORT_ASC])->all();
        $ciudades  = Ciudad::find()->orderBy(['nombre' => SORT_ASC])->all();
        $marcas    = Marca::find()->orderBy(['nombre' => SORT_ASC])->all();
        $zonas     = Zona::find()->orderBy(['nombre' => SORT_ASC])->all();
        $empresas  = Empresa::find()->orderBy(['nombre' => SORT_ASC])->all();

        $model = new Usuario();

        $primaryConnection = Yii::$app->db;

        $primaryCommand = $primaryConnection->createCommand("DELETE
                                                             FROM usuario_zona
                                                             WHERE usuario = :usuario
                                                             ");

        $secondCommand = $primaryConnection->createCommand("DELETE
                                                             FROM usuario_distrito
                                                             WHERE usuario = :usuario
                                                             ");

        $quintoCommand = $primaryConnection->createCommand("DELETE
                                                             FROM usuario_marca
                                                             WHERE usuario = :usuario
                                                             ");

        $sextoCommand = $primaryConnection->createCommand("DELETE
                                                             FROM usuario_ciudad
                                                             WHERE usuario = :usuario
                                                             ");

        $septimoCommand = $primaryConnection->createCommand("DELETE
                                                             FROM usuario_dependencia
                                                             WHERE usuario = :usuario
                                                             ");

        $octavoCommand = $primaryConnection->createCommand("DELETE
                                                             FROM usuario_empresa
                                                             WHERE usuario = :usuario
                                                             ");

        $thirdCommand = $primaryConnection->createCommand("DELETE
                                                            FROM rol_usuario
                                                            WHERE usuario = :usuario
                                                            ");

        $fourthCommand = $primaryConnection->createCommand("SELECT rol_id
                                                            FROM  rol_usuario
                                                            WHERE usuario = :usuario
                                                            ");

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $roles_array     = array_key_exists('roles_array', $array_post) ? $array_post['roles_array'] : array();
            $zonas_array     = array_key_exists('zonas_array', $array_post) ? $array_post['zonas_array'] : array();
            $marcas_array    = array_key_exists('marcas_array', $array_post) ? $array_post['marcas_array'] : array();
            $distritos_array = array_key_exists('distritos_array', $array_post) ? $array_post['distritos_array'] : array();
            $empresas_array  = array_key_exists('empresas_array', $array_post) ? $array_post['empresas_array'] : array();
            $usuario         = $model->getAttribute('usuario');
            $thirdCommand->bindValue(':usuario', $usuario)->execute();
            $tamano_roles     = count($roles_array);
            $tamano_zonas     = count($zonas_array);
            $tamano_distritos = count($distritos_array);
            $tamano_marcas    = count($marcas_array);
            $tamano_empresas  = count($empresas_array);
            $tipo_area        = array_key_exists('tipo-area', $array_post) ? $array_post['tipo-area'] : '';

            $password=$array_post['Usuario']['password'];
            $hash = Yii::$app->getSecurity()->generatePasswordHash($password);
            $model->setAttribute('password',$hash);
            $model->save();
            if ($tipo_area == 'on') {

                $model->setAttribute('ambas_areas', 'S');
                $model->save();

            }

            $index = 0;

            while ($index < $tamano_roles) {

                /*Modelo principal guardado*/
                $usuario_roles_model = new RolUsuario();
                /*establecer valores de Atributos del objeto prorroga*/
                $usuario_roles_model->setAttribute('usuario', $usuario);
                $usuario_roles_model->setAttribute('rol_id', $roles_array[$index]);
                $usuario_roles_model->save();

                $index++;

            }

            $index = 0;

            while ($index < $tamano_zonas) {

                /*Modelo principal guardado*/
                $usuario_zonas_model = new UsuarioZona();
                /*establecer valores de Atributos del objeto prorroga*/
                $usuario_zonas_model->setAttribute('usuario', $usuario);
                $usuario_zonas_model->setAttribute('zona_id', $zonas_array[$index]);
                $usuario_zonas_model->save();

                $index++;

            }

            $index = 0;

            while ($index < $tamano_distritos) {

                /*Modelo principal guardado*/
                $usuario_distritos_model = new UsuarioDistrito();
                /*establecer valores de Atributos del objeto prorroga*/
                $usuario_distritos_model->setAttribute('usuario', $usuario);
                $usuario_distritos_model->setAttribute('distrito_id', $distritos_array[$index]);
                $usuario_distritos_model->save();

                $index++;

            }

            $index = 0;

            while ($index < $tamano_marcas) {

                /*Modelo principal guardado*/
                $usuario_marcas_model = new UsuarioMarca();
                /*establecer valores de Atributos del objeto prorroga*/
                $usuario_marcas_model->setAttribute('usuario', $usuario);
                $usuario_marcas_model->setAttribute('marca_id', $marcas_array[$index]);
                $usuario_marcas_model->save();

                $index++;

            }

            $index = 0;

            while ($index < $tamano_empresas) {

                /*Modelo principal guardado*/
                $usuario_empresa_model = new UsuarioEmpresa();
                /*establecer valores de Atributos del objeto prorroga*/
                $usuario_empresa_model->setAttribute('usuario', $usuario);
                $usuario_empresa_model->setAttribute('nit', $empresas_array[$index]);
                $usuario_empresa_model->save();

                $index++;

            }

            return $this->redirect('index');

        } else {
            return $this->render('create', [
                'model'     => $model,
                'roles'     => $roles,
                'distritos' => $distritos,
                'marcas'    => $marcas,
                'ciudades'  => $ciudades,
                'zonas'     => $zonas,
                'empresas'  => $empresas,

            ]);
        }
    }

    /**
     * Updates an existing Usuario model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $array_post = Yii::$app->request->post(); // almacenar variables POST
        $roles      = Yii::$app->session['rol-exito'];

        $model     = $this->findModel($id);
        $roles     = Rol::find()->orderBy(['nombre' => SORT_ASC])->all();
        $distritos = Distrito::find()->orderBy(['nombre' => SORT_ASC])->all();
        $ciudades  = Ciudad::find()->orderBy(['nombre' => SORT_ASC])->all();
        $marcas    = Marca::find()->orderBy(['nombre' => SORT_ASC])->all();
        $zonas     = Zona::find()->orderBy(['nombre' => SORT_ASC])->all();
        $empresas  = Empresa::find()->orderBy(['nombre' => SORT_ASC])->all();

        $primaryConnection = Yii::$app->db;

        $primaryCommand = $primaryConnection->createCommand("DELETE
                                                             FROM usuario_zona
                                                             WHERE usuario = :usuario
                                                             ");

        $zonaCommand = $primaryConnection->createCommand("SELECT zona_id
                                                            FROM  usuario_zona
                                                            WHERE usuario = :usuario
                                                            ");

        $secondCommand = $primaryConnection->createCommand("DELETE
                                                             FROM usuario_distrito
                                                             WHERE usuario = :usuario
                                                             ");

        $distritoCommand = $primaryConnection->createCommand("SELECT distrito_id
                                                            FROM  usuario_distrito
                                                            WHERE usuario = :usuario
                                                            ");

        $quintoCommand = $primaryConnection->createCommand("DELETE
                                                             FROM usuario_marca
                                                             WHERE usuario = :usuario
                                                             ");

        $marcaCommand = $primaryConnection->createCommand("SELECT marca_id
                                                            FROM  usuario_marca
                                                            WHERE usuario = :usuario
                                                            ");

        $empresaCommand = $primaryConnection->createCommand("SELECT nit
                                                            FROM  usuario_empresa
                                                            WHERE usuario = :usuario
                                                            ");

        $sextoCommand = $primaryConnection->createCommand("DELETE
                                                             FROM usuario_ciudad
                                                             WHERE usuario = :usuario
                                                             ");

        $septimoCommand = $primaryConnection->createCommand("DELETE
                                                             FROM usuario_dependencia
                                                             WHERE usuario = :usuario
                                                             ");

        $octavoCommand = $primaryConnection->createCommand("DELETE
                                                             FROM usuario_empresa
                                                             WHERE usuario = :usuario
                                                             ");

        $thirdCommand = $primaryConnection->createCommand("DELETE
                                                            FROM rol_usuario
                                                            WHERE usuario = :usuario
                                                            ");

        $fourthCommand = $primaryConnection->createCommand("SELECT rol_id
                                                            FROM  rol_usuario
                                                            WHERE usuario = :usuario
                                                            ");

        $usuario = $id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $roles_array     = array_key_exists('roles_array', $array_post) ? $array_post['roles_array'] : array();
            $zonas_array     = array_key_exists('zonas_array', $array_post) ? $array_post['zonas_array'] : array();
            $marcas_array    = array_key_exists('marcas_array', $array_post) ? $array_post['marcas_array'] : array();
            $distritos_array = array_key_exists('distritos_array', $array_post) ? $array_post['distritos_array'] : array();
            $empresas_array  = array_key_exists('empresas_array', $array_post) ? $array_post['empresas_array'] : array();
            $usuario         = $model->getAttribute('usuario');
            $thirdCommand->bindValue(':usuario', $usuario)->execute();
            $primaryCommand->bindValue(':usuario', $usuario)->execute();
            $secondCommand->bindValue(':usuario', $usuario)->execute();
            $quintoCommand->bindValue(':usuario', $usuario)->execute();
            $octavoCommand->bindValue(':usuario', $usuario)->execute();
            $tamano_roles     = count($roles_array);
            $tamano_zonas     = count($zonas_array);
            $tamano_distritos = count($distritos_array);
            $tamano_marcas    = count($marcas_array);
            $tamano_empresas  = count($empresas_array);
            $tipo_area        = array_key_exists('tipo-area', $array_post) ? $array_post['tipo-area'] : '';

            /*$password=$array_post['Usuario']['password'];
            

            $hash = Yii::$app->getSecurity()->generatePasswordHash($password);
            $model->setAttribute('password',$hash);
            $model->save();*/
            if ($tipo_area == 'on') {

                $model->setAttribute('ambas_areas', 'S');
                $model->save();

            }

            $index = 0;

            while ($index < $tamano_roles) {

                /*Modelo principal guardado*/
                $usuario_roles_model = new RolUsuario();
                /*establecer valores de Atributos del objeto prorroga*/
                $usuario_roles_model->SetAttribute('usuario', $usuario);
                $usuario_roles_model->SetAttribute('rol_id', $roles_array[$index]);
                $usuario_roles_model->save();

                $index++;

            }

            $index = 0;

            while ($index < $tamano_zonas) {

                /*Modelo principal guardado*/
                $usuario_zonas_model = new UsuarioZona();
                /*establecer valores de Atributos del objeto prorroga*/
                $usuario_zonas_model->setAttribute('usuario', $usuario);
                $usuario_zonas_model->setAttribute('zona_id', $zonas_array[$index]);
                $usuario_zonas_model->save();

                $index++;

            }

            $index = 0;

            while ($index < $tamano_distritos) {

                /*Modelo principal guardado*/
                $usuario_distritos_model = new UsuarioDistrito();
                /*establecer valores de Atributos del objeto prorroga*/
                $usuario_distritos_model->setAttribute('usuario', $usuario);
                $usuario_distritos_model->setAttribute('distrito_id', $distritos_array[$index]);
                $usuario_distritos_model->save();

                $index++;

            }

            $index = 0;

            while ($index < $tamano_marcas) {

                /*Modelo principal guardado*/
                $usuario_marcas_model = new UsuarioMarca();
                /*establecer valores de Atributos del objeto prorroga*/
                $usuario_marcas_model->setAttribute('usuario', $usuario);
                $usuario_marcas_model->setAttribute('marca_id', $marcas_array[$index]);
                $usuario_marcas_model->save();

                $index++;

            }

            $index = 0;

            while ($index < $tamano_empresas) {

                /*Modelo principal guardado*/
                $usuario_empresa_model = new UsuarioEmpresa();
                /*establecer valores de Atributos del objeto prorroga*/
                $usuario_empresa_model->setAttribute('usuario', $usuario);
                $usuario_empresa_model->setAttribute('nit', $empresas_array[$index]);
                $usuario_empresa_model->save();

                $index++;

            }

            return $this->redirect('index');
        } else {

            $roles_actuales     = $fourthCommand->bindValue(':usuario', $usuario)->queryAll();
            $zonas_actuales     = $zonaCommand->bindValue(':usuario', $usuario)->queryAll();
            $distritos_actuales = $distritoCommand->bindValue(':usuario', $usuario)->queryAll();
            $marcas_actuales    = $marcaCommand->bindValue(':usuario', $usuario)->queryAll();
            $empresas_actuales  = $empresaCommand->bindValue(':usuario', $usuario)->queryAll();

            return $this->render('update', [
                'model'              => $model,
                'roles'              => $roles,
                'roles_actuales'     => $roles_actuales,
                'zonas'              => $zonas,
                'distritos'          => $distritos,
                'marcas'             => $marcas,
                'marcas_actuales'    => $marcas_actuales,
                'zonas_actuales'     => $zonas_actuales,
                'distritos_actuales' => $distritos_actuales,
                'empresas_actuales'  => $empresas_actuales,
                'empresas'           => $empresas,
            ]);
        }
    }

    /**
     * Deletes an existing Usuario model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Usuario model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Usuario the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Usuario::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionReporte_ingreso(){

        $users=Usuario::find()->all();
        $list_usuario=ArrayHelper::map($users,'usuario','usuario');



        $page=0;$rowsPerPage=20;
        if(isset($_POST['page'])) {
            if($_POST['page']!=0){
                $page = (isset($_POST['page']) ? $_POST['page'] : 1);
                $cur_page = $page;
                $page -= 1;
                $per_page = $rowsPerPage; // Per page records
                $previous_btn = true;
                $next_btn = true;
                $first_btn = true;
                $last_btn = true;
                $start = $page * $per_page;
            }else{
                $per_page = $rowsPerPage; // Per page records
                $start = $page * $per_page;
                $cur_page = 1;
                $previous_btn = true;
                $next_btn = true;
                $first_btn = true;
                $last_btn = true;
            }
        }else{
            $per_page = $rowsPerPage; // Per page records
            $start = $page * $per_page;
            $cur_page = 1;
            $previous_btn = true;
            $next_btn = true;
            $first_btn = true;
            $last_btn = true;
        }

        $date=date('Y-m-d');
        $rows = (new \yii\db\Query())
        ->select(['log.usuario','log.fecha','log.hora_inicio','log.hora_fin','log.dispositivo'])
        ->from('log_usuarios log');

        if (trim($_POST['fecha'])!='' && trim($_POST['fecha_hasta'])!='') {
            $rows->where(' log.fecha between "'.$_POST['fecha'].'"  AND "'.$_POST['fecha_hasta'].'" ');
        }else{
            $rows->where(' log.fecha="'.$date.'" ');

        }

        if (trim($_POST['user'])!='') {
             $rows->andWhere(" log.usuario='".$_POST['user']."' ");
        }


        $rows->orderBy(['log.id' => SORT_DESC]);

        $rowsCount= clone $rows;

        if(!isset($_POST['excel'])){
            $rows->limit($rowsPerPage)->offset($start);
        }

        $command = $rows->createCommand();

        $usuarios = $command->queryAll();


         if(isset($_POST['excel'])){
            \moonland\phpexcel\Excel::widget([
                'models' => $usuarios,
                'mode' => 'export',
                'fileName' => 'Reporte de ingreso', 
                'columns' => ['usuario','fecha','hora_inicio','hora_fin'],
                'headers' => [
                    'usuario'=>'Usuario',
                    'fecha'=>'Fecha',
                    'hora_inicio'=>'Hora inicio conexion',
                    'hora_fin'=>'Hora fin conexion'
                ], 
            ]);
        }


        $modelcount = $rowsCount->count();
        $no_of_paginations = ceil($modelcount / $per_page);
        $res='';


        if($modelcount > $rowsPerPage){
           
            $res.=$this->renderPartial('_paginacion_partial', array(
                'cur_page' => $cur_page,
                'no_of_paginations' => $no_of_paginations,
                'first_btn' => $first_btn,
                'previous_btn' => $previous_btn,
                'next_btn' => $next_btn,
                'last_btn' => $last_btn,
                'modelcount' => $modelcount
                
            ), true);
        }
        $res.= $this->renderPartial('_partial_usuario', array(
            'usuarios' => $usuarios,
            'modelcount' => $modelcount
            
                ), true);
        if(isset($_POST['page'])){
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'respuesta' => $res,
                'query' => $command->sql,
            ];
        }else{
            return $this->render('reporte_ingreso',
                [
                    'partial' => $res,
                    'list_user'=>$list_usuario

                ]);
        }




    }

    public function actionInspSemestral($id){
        $dependencias_user=$this->dependencias_usuario($id);

        $in=" IN(";

        foreach ($dependencias_user as $value) {
            
            $in.=" '".$value."',";    
        }

        $in_final = substr($in, 0, -1).")";

        ////////////////////////////////////////////////////////////

        if ($_POST['inicio']!='' && $_POST['final']!='') {
            $filtro="fecha_novedad BETWEEN '".$_POST['inicio']."' AND '".$_POST['final']."'";
        }else{
            $ano=date('Y');    
            $filtro="YEAR(fecha_novedad)='$ano'";
        }

        $NovedadVisitas=VisitaMensualDetalle::find()
        ->leftJoin('visita_mensual', 'visita_mensual_id= visita_mensual.id')
        ->where('visita_mensual.centro_costo_codigo '.$in_final.' AND  ('.$filtro.')  ');
        $cantidad_visitas=$NovedadVisitas->count();

        $NovedadCapacitacion=NovedadCapacitacion::find()
        ->leftJoin('visita_mensual', 'visita_mensual_id= visita_mensual.id')
        ->where('visita_mensual.centro_costo_codigo '.$in_final.' AND  ('.$filtro.')  ');

        $cantidad_capacitacion=$NovedadCapacitacion->count();

        $Novedadpedido=NovedadPedido::find()
        ->leftJoin('visita_mensual', 'visita_mensual_id= visita_mensual.id')
        ->where('visita_mensual.centro_costo_codigo '.$in_final.' AND  ('.$filtro.')  ');
        $cantidad_pedido=$Novedadpedido->count();

        $total_novedades=($cantidad_visitas+$cantidad_capacitacion+$cantidad_pedido);

        $mayoresNovedades=[array('name'=>'Visitas','y'=>(int)$cantidad_visitas ),array('name'=>'Capacitacion','y'=>(int)$cantidad_capacitacion),array('name'=>'Pedidos','y'=>(int)$cantidad_pedido )];

        $mayoresNovedades=json_encode($mayoresNovedades);

        $dependencias=CentroCosto::find()->where('codigo '.$in_final.' ')->all();

         $categorias=CategoriaVisita::find()->all();
        $novedades = Novedad::find()->where('tipo="C" AND estado="A" ')->orderBy(['nombre' => SORT_ASC])->all();

        $array_novedades=[];
        foreach ($categorias as $key => $value) {

           $cantidad_categoria=VisitaMensualDetalle::find()
            ->leftJoin('visita_mensual', 'visita_mensual_id= visita_mensual.id')
            ->where('visita_mensual.centro_costo_codigo '.$in_final.' AND  ('.$filtro.')  ')
            ->andWhere(' categoria_id='.$value->id.' ')
            ->count();

           $array_novedades[]=array('name'=>$value->nombre,'y'=>(int)$cantidad_categoria);
        }

        foreach ($novedades as $key1 => $value1) {
            $cantidad_temas=NovedadCapacitacion::find()
            ->leftJoin('visita_mensual', 'visita_mensual_id= visita_mensual.id')
            ->where('visita_mensual.centro_costo_codigo '.$in_final.' AND  ('.$filtro.')  ')
            ->andWhere(' tema_cap_id='.$value1->id.' ')
            ->count();

           $array_novedades[]=array('name'=>$value1->nombre,'y'=>(int)$cantidad_temas);
        }

      
        $array_novedades=json_encode($array_novedades);

        // echo "<pre>";
        // print_r($array_novedades);
        // echo "</pre>";
        return $this->render('inspeccion_semestral',
        [
            'usuario' => $id,
            'mayoresNovedades'=>$mayoresNovedades,
            'cantidad_visitas'=>$cantidad_visitas,
            'cantidad_capacitacion'=>$cantidad_capacitacion,
            'cantidad_pedido'=>$cantidad_pedido,
            'total_novedades'=>$total_novedades,
            'dependencias'=>$dependencias,
            'array_novedades'=>$array_novedades,
            'inicio'=>$_POST['inicio'],
            'final'=>$_POST['final'],

        ]);
    }

    public function actionRescontrasena($id){
        $model=$this->findModel($id);
        $hash = Yii::$app->getSecurity()->generatePasswordHash('Exito321*');
        $model->setAttribute('password', $hash);
        $model->setAttribute('update_contrasena','N');
        $model->save();
        Yii::$app->session->setFlash('success','Clave actualizada correctamente');
        return $this->redirect('index');

    }

}
