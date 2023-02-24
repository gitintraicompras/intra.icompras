<?php
namespace App\Http\Controllers\Canales;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Query\Builder;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use App\user; 
use DB;
use Session;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use App\Maelicencias;
use App\Http\Requests\MaelicenciasFormRequest;

 
class ActivacionController extends Controller {
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    public function index(Request $request) {
        $subtitulo = "ACTIVACION DE PRODUCTOS";
        $maeprove = DB::table('maeprove')
        ->where('status','=','ACTIVO')
        ->orderBy('region','asc')
        ->get();

        $vendedor = DB::table('vendedor')
        ->orderBy('nombre','asc')
        ->get();
 
        return view('canales.activacion.index', ["menu" => "Activacion",
                                                 "subtitulo" => $subtitulo,
                                                 "vendedor" => $vendedor,
                                                 "maeprove" => $maeprove
        ]); 
    }
    
    public function create() {
        $subtitulo = "ACTIVACION DE PRODUCTO";
        return view("canales.activacion.create",["menu" => "Activacion",
                                                "subtitulo" => $subtitulo]);
    }

    public function store(Request $request) {
        $rif = $request->get('rif');
        $rif = str_replace("-", "", $rif);
        $rif = str_replace(" ", "", $rif);
        $rif = strtoupper(trim($rif));
        $codisb = $rif;
        $id = substr($codisb, 0, 1);
        if ($id == "V" || $id == "J" || $id == "G") {
            $codisb = substr($codisb, 1, strlen($codisb)-1);
        }
        //dd("RIF: ".$rif." CODISB: ".$codisb);

        $codcanal = Auth::user()->codcli;

        $maeprove = DB::table('maeprove')
        ->where('status','=','ACTIVO')
        ->orderBy('region','asc')
        ->get();

        $vendedor = DB::table('vendedor')
        ->where('codcanal','=',$codcanal)
        ->orderBy('nombre','asc')
        ->get();

        $cliente = DB::table('maecliente')
        ->where('codcli','=',$codisb)
        ->first();
        if (empty($cliente)) {
            $cliente = null;
        } else {
            // VERIFICAR SI TIENE LICENCIA
            $activa = "";
            $licencia = DB::table('maelicencias')
            ->where('codisb','=',$codisb)
            ->where('estado','=','P')
            ->where('status','=','ACTIVO')
            ->get();
            foreach ($licencia as $lic) { 
                $codsoftx = substr($lic->cod_lic, 2, 5);
                if ($codsoftx == 'ISACO') {
                    $activa = $lic->cod_lic;
                    break;
                }
            }   
            if ($activa != "") {
                session()->flash('error', "ESTE CLIENTE, YA POSEE UNA LICENCIA ACTIVA: ".$activa." !!!");
                return back()->withInput();
            }    
        }
        $users = DB::table('users')
        ->where('codcli','=',$codisb)
        ->where('tipo','=','C')
        ->first();
        if ($users) {
            session()->flash('error', "ESTE CLIENTE, YA POSEE UN USUARIO ASIGNADO: ".$users->email." !!!");
            return back()->withInput();
        }

        $subtitulo = "EDITAR ACTIVACION";
        return view("canales.activacion.edit",["menu" => "Activacion",
                                               "rif" => $rif,
                                               "codisb" => $codisb,
                                               "cliente" => $cliente,
                                               "maeprove" => $maeprove,
                                               "codcanal" => $codcanal,
                                               "vendedor" => $vendedor,
                                               "subtitulo" => $subtitulo]);
    }

    public function verificar(Request $request) {
        $codprove = $request->get('codprove');
        $codigo = $request->get('codigo');
        $usuario = $request->get('usuario');
        $clave = $request->get('clave');     
        //log::info("codprove: ".$codprove.' codigo: '.$codigo.' usuario: '.$usuario.' clave: '.$clave);
        $resp = VerificarCodprove($codprove, $codigo, $usuario, $clave); 
        return response()->json(['resp' => $resp ]);
    }

    public function update(Request $request, $id) {
        try {
            $cfg = DB::table('maecfg')->first();
            $correo = trim($request->get('correo'));
            $users = DB::table('users')
            ->where('email','=',$correo)
            ->first();
            if ($users) {
                session()->flash('error', "ESTE CORREO, YA ESTA SIENDO UTILIZADO !!!");
                return back()->withInput();
            }
            $codisb = $request->get('codisb');
            $codcanal = $request->get('codcanal');
            $codprove = $request->get('codprove');
            $rif = strtoupper($request->get('rif')); 
            $nombre = strtoupper($request->get('nombre'));
            $direccion = strtoupper($request->get('direccion')); 
            $telefono = $request->get('telefono');
            $contacto = strtoupper($request->get('contacto'));
            $localidad = strtoupper($request->get('localidad'));
            if (empty($rif) 
                || empty($nombre)
                || empty($direccion)
                || empty($telefono)
                || empty($contacto)
                || empty($localidad) ) {
                session()->flash('error', "COMPLETE LOS DATOS BASICOS !!!");
                return back()->withInput();
            }

            $erp = $request->get('erp');
            $sector = $request->get('sector');
            $vericompras = $request->get('vericompras');
            $linkpagina = strtolower($request->get('linkpagina'));
            $correo = strtolower($request->get('correo'));
            $correo = ($correo == 'n/a') ? "N/A" : $correo;
            $codvendedor = strtoupper($request->get('codvendedor'));
            if (empty($erp) 
                || empty($sector)
                || empty($vericompras)
                || empty($linkpagina)
                || empty($correo) ) {
                session()->flash('error', "COMPLETE LOS DATOS DE DETALLES !!!");
                return back()->withInput();
            }

            $procesar = 0;
            // 1.- CREAR O ACTUALIZA FICHA DE CLIENTE
            $cliente = DB::table('maecliente')
            ->where('codcli','=',$codisb)
            ->first();
            if ($cliente) {
                DB::table('maecliente')
                ->where('codcli','=',$codisb)
                ->delete();
            }
            DB::table('maecliente')->insert([
                    'codcli' => $codisb,
                    'nombre' => $nombre,
                    'rif' => $rif,
                    'direccion' => $direccion,
                    'telefono' => $telefono,
                    'contacto' => $contacto,
                    'usuario' => 'N/A',
                    'clave' => 'N/A',
                    'zona' => $localidad,
                    "fecha" => date('Y-m-d H:i:s'),
                    'estado' => 'ACTIVO',
                    'campo1' => $vericompras,
                    'campo2' => '1',
                    'campo3' => $correo,
                    'campo4' => '2',
                    'campo5' => '2',
                    'campo6' => '2',
                    'campo7' => '',
                    'campo8' => $erp,
                    'campo9' => '',
                    'campo10' => '',
                    'cadena' => 'INDEPENDIENTE',
                    'sector' => $sector,
                    'linkpagina' => $linkpagina,
                    'isabuscar' => '2',
                    'mostrarprecio' => '2',
                    'mostrarcantidad' => '2',
                    'mostrarIsabuscarIsacom' => '2',
                    'diasTransito' => '7',
                    'rutaimg' => 'nofoto.jpg'
            ]);
           
            // 2.- CREAR TABLA PROVEEDORES DEL CLIENTE
            $codigo = $request->get('codigo');
            $usuario = $request->get('usuario');
            $clave = $request->get('clave');
            $dcredito = $request->get('dcredito');
            $dc = $request->get('dc');
            $pp = $request->get('pp');
            $di = $request->get('di');
            $do = $request->get('do');
            $pref = 0;
            $activar = $request->get('activar');
            if (isset($activar)) {
                // ELIMINA LOS PROVEEDORES ANTERIORES
                DB::table('maeclieprove')
                ->where('codcli','=',$codisb)
                ->delete();
                foreach( $activar as $key => $val ) {
                    $campo = explode("-", trim($key));
                    $i = intval($campo[1])-1;
                    $pref++; 
                    if ($pref  <= 9) {
                        $pref = '0'.$pref;
                    } else {
                        $pref = $i; 
                    }
                    $pref = substr($pref, 0, 2);
                    $codprovex = $campo[0];
                    $codigox = $codigo[$i];
                    $usuariox = $usuario[$i];
                    $clavex = $clave[$i];
                    $dcreditox = $dcredito[$i];
                    $dcx = $dc[$i];
                    $ppx = $pp[$i];
                    $dix = $di[$i];
                    $dox = $do[$i];
                    DB::table('maeclieprove')->insert([
                        'codcli' => $codisb,
                        'codprove' => $codprovex,
                        'codigo' => $codigox,
                        'subcarpetaftp' => 'N/A',
                        'dcredito' => $dcreditox,
                        'mcredito' => '2000.00',
                        'corte' => 'LUNES',
                        'dcme' => $dcx,
                        'dcmer' => '0.00',
                        'dcmi' => '0.00',
                        'dcmir' => '0.00',
                        'ppme' => $ppx,
                        'ppmi' => '0.00',
                        'di' => $dix,
                        'dotro' => $dox,
                        'usuario' => $usuariox,
                        'clave' => $clavex,
                        'status' => 'ACTIVO',
                        'tipoprecio' => 1,
                        'preferencia' => $pref,
                        'codprove_adm' => '',
                        'statusOfertas' => 'ACTIVO',
                        'updCondComercial' => 0
                    ]);
                }
            } 


            // 3.- CREAR LICENCIA
            // VEISAAP0000100100192
            // VE    => PAIS
            // ISACO => SOFTWARE
            // 00001 => CANAL
            $codlic = "SIN KEYS";
            DB::table('maelicencias')->insert([
                'cod_lic' => $codlic,
                'estado' => 'P',
                'fec_reg' => date('Y-m-d'),
                'fec_act' => date('Y-m-d'),
                'diaLicencia' => 30,
                'codisb' => $codisb,
                'serial' => 'N/A',
                'status' => 'ACTIVO',
                'tipo' => 'NORMAL',
                'version' => '3.0.0',
                'ultPing' => date('Y-m-j H:i:s'),
                'cadprove' => '',
                'observ' => 'LICENCIA TRIAL',
                'codvendedor' => $codvendedor
            ]);
            $ult = Maelicencias::max('id'); 
            $sId = "00000$ult";
            $largo = strlen($sId);
            $sIdCorrelativo = substr($sId, $largo-5, 5);
            // VE ISAAP 00001 001 00192
            $codlic = 'VEISACO'.$codcanal.'001'.$sIdCorrelativo;
            // UPDATE
            $regs = Maelicencias::findOrFail($ult);
            $regs->cod_lic = $codlic;
            $regs->update();
         
            // 4.- CREAR USUARIO
            DB::table('users')->insert([
                'name' => $nombre,
                'email' => $correo,
                'password' => bcrypt($codisb),
                'created_at' => date('Y-m-j H:i:s'),
                'updated_at' => date('Y-m-j H:i:s'),
                'codcli' => $codisb,
                'tipo' => 'C',
                'estado' => 'ACTIVO',
                'clave' => $codisb,
                'ultcodcli' => '',
                'mostrarCatIsaBuscar' => 0,
                'versionLight' => ($vericompras=='LIGHT') ? 1 : 0,
                'last_login' => date('Y-m-j H:i:s'),
                'userAdmin' => 1,
                'botonMolecula' => 0,
                'userPedDirecto' => 0,
                'botonEnvio' => 1
            ]);
      
            //if (FALSE) {   
                // 5.- ENVIA CORREO DE SUSCRIPCION
                $fechaHoy = date('j-m-Y');
                $FechaVenta = substr($fechaHoy, 0, 10);
                $correoRemitente = $cfg->correo;
                if (!empty($correoRemitente)) {
                    $cliente = DB::table('maecliente')
                    ->where('codcli','=',$codisb)
                    ->first();
                    if ($cliente) {
                        $nombre = $cliente->nombre;
                        // FORMULARIO DEL CORREO
                        $subject = "REGISTRO DE USUARIO (".$FechaVenta.") - ".$cfg->nombre;
                        $headers = "MIME-Version: 1.0\r\n";
                        $headers .= "Content-type:text/html; charset=iso-8859-1\r\n";
                        $headers .= "Content-Transfer-Encoding: 8bit\r\n";
                        $headers .= "X-Priority: 1\r\n";
                        $headers .= "X-MSMail-Priority: High\r\n";
                        $headers .= "From: <".$correoRemitente.">\r\n";
                        $headers .= "Reply-To: <".$correoRemitente.">\r\n";
                        $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
                        $headers .= "X-originating-IP: \r\n";
                        // ENCABEZADO
                        $message = "
                        <!DOCTYPE html>
                        <html>
                        <head>
                        <title>HTML</title>
                        </head>
                        <body> <br>";


                        $message .= "<img src='http://icompras360.com/public/storage/icompras.png'><br>";
                     
                        $message .= "
                        <br><br>
                        <center><h3>Estimado(a)</h3>
                        <h2>$nombre</h2></center>
                        <div>";

                        $message .= "<div><h4>

                        Le informamos que se ha registrado el siguiente usuario para tener acceso a nuestro portal web.
                        <br>
                        </h4></div>";

                        $message .= "<div><h3>USUARIO: $correo</h3></div>";
                        $message .= "<div><h3>CLAVE  : $codisb</h3></div>";

                        $message .= "
                        <strong>Ingresar para realizar su pedido: 
                            <a href='http://intra.icompras360.com'>
                                http://intra.icompras360.com
                            </a>
                        </strong>";

                        $message .= "</div>";

                        // PIE DEL FORMULARIO
                        $message .= "<h4>
                            <br><br>
                            <center>
                                ".$cfg->nombre." | RIF: ".$cfg->rif." 
                            </center>
                        </h4>";

                        $message .= "<h5>
                            <center>
                                ".$cfg->direccion."
                            </center>
                        </h5>";

                        $message .= "<h5>
                            <center>
                                TELEFONO: ".$cfg->telefono." CONTACTO: ".$cfg->contacto."
                            </center>
                        </h5>";

                        $message .= "<h5>
                            <center>
                            <a href='https://isbsistemas.com'>https://isbsistemas.com
                            </a>
                            </center>
                        </h5>";

                        $message .= "</div>
                        </body>
                        </html>";

                        if (mail($correo, $subject, $message, $headers)) {
                            log::info("USR NUEVO mail enviado: ".$correo);
                        } else  {
                            log::info("USR NUEVO mail no enviado: ".$correo);
                        }
                    }
                }
            //}

            // ACTUALIZAR EL CODIGO DE LA LICENCIA EN CLIENTE
            DB::table('maecliente')
            ->where('codcli','=',$codisb)
            ->update(array("KeyIsacom" => $codlic));

            session()->flash('message', "SUSCRIPCION HA SIDO REALIZADA EXITOSAMENTE !!!");
        } catch (Exception $e) {
            session()->flash('error', "ERROR: ".$e." ->IMPOSIBLE REALIZAR SU SUSCRIPCION, INTENTE MAS TARDE !!!");
        }
        return Redirect::to('/');
    }
  
}