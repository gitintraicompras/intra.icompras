<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Query\Builder;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use App\user;
use DB;
use Session;
 
 
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request) {
        if ($request) {
            ContadorVisitas();
            if (!Session::has('sidebarMode')) {
                Session::put('sidebarMode', '1');
                Session::put('moneda', 'BSS');
                Session::put('info', '0');
                Session::put('tipedido', 'N'); 
            }
            $moneda = Session::get('moneda', 'BSS');
            $subtitulo = "MENU PRINCIPAL";
            $tipo = Auth::user()->tipo;
            $codcli = sCodigoClienteActivo();
            if ($tipo == "C" || 
                $tipo == "G" || 
                $tipo == "P" ||
                $tipo == "N" || 
                $tipo == "O") {
                switch ($tipo) {
                    case 'P':
                        $codprove = Auth::user()->ultcodcli;
                        $maeprove = LeerProve($codprove);
                        if (is_null($maeprove)) {
                            Auth::logout();
                            session()->flash('error', "Tabla proveedor no encontrada: ".$maeprove);
                            return redirect()->route('login');
                        }
 
                        // MODULO CLIENTE
                        $cliente = DB::table('maecliente')
                        ->where('codcli','=',$codcli)
                        ->first();

                        $final = date('d-m-Y');
                        $Fechax = strtotime('-30 day', strtotime($final));
                        $fechax = date('d-m-Y', $Fechax);
                        $comienzo = substr($fechax, 0, 10);
                        $fechaInicio=strtotime($comienzo);
                        $fechaFin=strtotime($final);
                        $chart_data = '';
                        for($i=$fechaInicio; $i<=$fechaFin; $i+=86400) {
                            $fechai = date("Y-m-d", $i);
                            $desde = $fechai.' 00:00:00';
                            $hasta = $fechai.' 23:59:00';
                            $reg = DB::table('provpedido')
                            ->whereBetween('fecha', array($desde, $hasta))
                            ->where('codprove','=',$codprove)
                            ->selectRaw('sum(total) as total')
                            ->first();
                            $pedtotal = 0;
                            if ($reg->total)
                                $pedtotal = $reg->total;
                            $factor = RetornaFactorCambiario("", $moneda);
                            $pedtotal = $pedtotal/$factor; 
                            $pedtotal = number_format($pedtotal, 2, '.', '');
                            $chart_data .= "{periodo:'".$fechai."',pedidos:".$pedtotal."},";
                        }
                        $chart_data = substr($chart_data, 0, -1);

                        $contCatalogo = 0;
                        $fechaCat = date('d-m-Y H:i');
                        $tabla = strtolower($codprove);
                        if (VerificaTabla($tabla)) {
                            //Auth::logout();
                            //session()->flash('error', "Tabla proveedor no encontrada: ".$tabla);
                            //return redirect()->route('login');

                            $reg = DB::table($tabla)
                            ->selectRaw('count(*) as contador')
                            ->first();
                            $contCatalogo = $reg->contador;

                            $reg = DB::table($tabla)->first();
                            if ($reg)
                                $fechaCat = date('d-m-Y H:i', strtotime($reg->fechacata));
                
                        }  

                        $reg = DB::table('provpedido')
                        ->selectRaw('count(*) as contador')
                        ->where('codprove','=',$codprove)
                        ->first();
                        $contPedido = $reg->contador;
                        return view('isacom.indexProve', ["menu" => "Inicio",
                                                          "subtitulo" => $subtitulo,
                                                          "codcli" => $codcli,
                                                          "codprove" => $codprove,
                                                          "fechaCat" => $fechaCat,
                                                          "cliente" => $cliente,
                                                          "contCatalogo" => $contCatalogo,
                                                          "maeprove" => $maeprove,
                                                          "contPedido" => $contPedido,
                                                          "chart_data" => $chart_data,
                                                          "cfg" => DB::table('maecfg')->first() ]);
                        break;
                    case 'C':
                    case 'G':
                        $prodestacado = DB::table('prodestacado')->get();
                        $codgrupo = Auth::user()->codcli;
                        $provs = TablaMaecliproveActiva("");
                        $contProvActivo = count($provs);
                        $contProv = count($provs);

                        $maeprove = DB::table('maeprove')
                            ->where('status','=',"ACTIVO")
                            ->selectRaw('count(*) as contador')->first(); 

                        $contProvNuevo = $maeprove->contador - $contProvActivo;

                        $reg = DB::table('tpmaestra')
                        ->selectRaw('count(*) as contador')
                        ->first();
                        $contCatalogo = $reg->contador;

                        // MIEMBROS DEL GRUPO
                        $grupo = DB::table('gruporen')
                        ->where('id','=',$codgrupo)
                        ->where('status','=', 'ACTIVO')
                        ->orderBy("preferencia","asc")
                        ->get();

                        // MODULO CLIENTE
                        $cliente = DB::table('maecliente')
                        ->where('codcli','=',$codcli)
                        ->first();

                        // 1.- CONTADOR DE PEDIDOS ENVIADOS
                        $reg = DB::table('pedido')
                            ->selectRaw('count(*) as contador')
                            ->where('codcli','=',$codcli)
                            ->first();
                        $contPedido = $reg->contador;
                      
                        $fechaInv = date('d-m-Y H:i');
                        $contInv = 0;
                        $tabla = 'inventario_'.$codcli;
                        if (VerificaTabla($tabla)) {
                            $reg = DB::table('inventario_'.$codcli)
                            ->where('cuarentena', '=', '0')
                            ->selectRaw('count(*) as contador')
                            ->first();
                            $contInv = $reg->contador;
                            $reg = DB::table('inventario_'.$codcli)->first();
                            if ($reg)
                                $fechaInv = date('d-m-Y H:i', strtotime($reg->feccatalogo));
                        }
                
                        $final = date('d-m-Y');
                        $Fechax = strtotime('-7 day', strtotime($final));
                        $fechax = date('d-m-Y', $Fechax);
                        $comienzo = substr($fechax, 0, 10);
                        $fechaInicio=strtotime($comienzo);
                        $fechaFin=strtotime($final);
                        $chart_data = '';
                        for($i=$fechaInicio; $i<=$fechaFin; $i+=86400) {
                            $fechai = date("Y-m-d", $i);
                            $desde = $fechai.' 00:00:00';
                            $hasta = $fechai.' 23:59:00';
                            $reg = DB::table('pedido')
                            ->whereBetween('fecha', array($desde, $hasta))
                            ->where('codcli','=',$codcli)
                            ->selectRaw('sum(total) as total')
                            ->first();
                            $pedtotal = 0;
                            if ($reg->total)
                                $pedtotal = $reg->total;
                            $factor = RetornaFactorCambiario("", $moneda);
                            $pedtotal = $pedtotal/$factor; 
                            $pedtotal = number_format($pedtotal, 2, '.', '');
                            $chart_data .= "{periodo:'".$fechai."',pedidos:".$pedtotal."},";
                        }
                        $chart_data = substr($chart_data, 0, -1);
                        if (Auth::user()->estado == "INACTIVO") {
                            Auth::logout();
                            session()->flash('message','EL USUARIO SE ENCUENTRA INACTIVO');
                            return Redirect::to('/');
                        }
                        return view('isacom.index', ["menu" => "Inicio",
                                                     "cfg" => DB::table('maecfg')->first(),
                                                     "prodestacado" => $prodestacado,
                                                     "moneda" => $moneda,
                                                     "contPedido" => $contPedido,
                                                     "contCatalogo" => $contCatalogo,
                                                     "contInv" => $contInv,
                                                     "contProv" => $contProv,
                                                     "chart_data" => $chart_data,
                                                     "subtitulo" => $subtitulo,
                                                     "tipo" => $tipo,
                                                     "codcli" => $codcli,
                                                     "grupo" => $grupo,
                                                     "fechaInv" => $fechaInv,
                                                     "cliente" => $cliente ]);
                        break;
                    case 'O':
                        $contDa = 0;
                        $reg = DB::table('tpmaestra')
                        ->selectRaw('count(*) as contador')
                        ->first();
                        $contCatalogo = $reg->contador;

                        $provs = TablaMaecliproveActivaOfertas();
                        $contProv = count($provs);
 
                        $fechaInv = date('d-m-Y H:i');
                        $contInv = 0;
                        $tabla = 'inventario_'.$codcli;
                        if (VerificaTabla($tabla)) {
                            $reg = DB::table('inventario_'.$codcli)
                            ->where('cuarentena', '=', '0')
                            ->where('da','>',0)
                            ->where('cantidad','>',0)
                            ->selectRaw('count(*) as contador')
                            ->first();
                            $contDa = $reg->contador;
                            $reg = DB::table('inventario_'.$codcli)
                            ->where('cuarentena', '=', '0')
                            ->where('cantidad','>',0)
                            ->selectRaw('count(*) as contador')
                            ->first();
                            $contInv = $reg->contador;
                            $reg = DB::table('inventario_'.$codcli)
                            ->first();
                            if ($reg)
                                $fechaInv = date('d-m-Y H:i', strtotime($reg->feccatalogo));
                        }

                        $reg = DB::table('regofertas')
                        ->selectRaw('count(*) as contador')
                        ->where('codcli','=',$codcli)
                        ->first();
                        $contOfertas = $reg->contador;

                        $cliente = DB::table('maecliente')
                        ->where('codcli','=',$codcli)
                        ->first();

                        $chart_data = '';
                        $chart_color = '';
                        $array = [];
                     
                        foreach($provs as $prov) {
                            $cantren = 0;
                            $confprov = LeerProve($prov->codprove);
                            $codprove = strtolower($prov->codprove);
                            if (!VerificaCampoTabla('tpmaestra', $prov->codprove))
                                continue;
                            $prods = DB::table('tpmaestra')->get(); 
                            if ($prods) {
                                foreach ($prods as $prod) {
                                    try {
                                        $campos = $prod->$codprove;
                                        $campo = explode("|", $campos);
                                    } catch (Exception $e) {
                                        continue;
                                    }
                                    $cantidad = $campo[1];
                                    if ($cantidad > 0)
                                        $cantren++;
                                }
                                $array[] = [
                                    'nomprove' => $prov->codprove,
                                    'cantren' => $cantren,
                                    'backcolor' => $confprov->backcolor
                                ];
                            }   
                        }
                        if (count($array) > 1) { 
                            foreach ($array as $key => $row) {
                                $aux[$key] = $row['cantren'];
                            }
                            array_multisort($aux, SORT_DESC, $array);
                        }
                        $total = count($array);
                        for ($i = 0; $i < $total; $i++) {
                            $nomprove = $array[$i]['nomprove'];
                            $cantren = $array[$i]['cantren'];
                            $backcolor = $array[$i]['backcolor'];
                            $chart_data .= "{ y:'".$nomprove."',a:".$cantren."},";
                            $chart_color .= "'".$backcolor."',";
                        }
                        $chart_data = substr($chart_data, 0, -1);
                        $chart_color = substr($chart_color, 0, -1);
                        return view('ofertas.index', ["menu" => "Inicio",
                                                      "cfg" => DB::table('maecfg')->first(),
                                                      "subtitulo" => $subtitulo,
                                                      "contCatalogo" => $contCatalogo,
                                                      "tipo" => $tipo,
                                                      "codcli" => $codcli,
                                                      "contDa" => $contDa,
                                                      "contProv" => $contProv,
                                                      "contInv" => $contInv,
                                                      "fechaInv" => $fechaInv,
                                                      "contOfertas" => $contOfertas,
                                                      "chart_data" => $chart_data,
                                                      "chart_color" => $chart_color,
                                                      "cliente" => $cliente ]);
                        break;
                    case 'N':
                        $subtitulo = "MODULO DE CANALES";
                        $codcanal = Auth::user()->codcli;
                        $canal = DB::table('maecanales')
                        ->where('codcanal','=',$codcanal)
                        ->first();
                        return view('canales.index', ["menu" => "Inicio",
                                                    "subtitulo" => $subtitulo,
                                                    "codcanal" => $codcanal,
                                                    "cfg" => DB::table('maecfg')->first() ]);
                        break;
                }
            } else {
                if (Auth::check())
                {
                    if (Auth::User()->is_active != 'Y')
                    {
                        Auth::logout();
                        return redirect()->to('/')->with('warning', 'Your session has expired because your account is deactivated.');
                    }
                }
                return $next($request);
            }
        }
    }

    public function cambiar(Request $request, $codant) {
        $codcli = trim($request->get('codcli'));
        if ($codcli) {
            if (Session::has('codcli')) {
                Session::forget('codcli');
            }
            Session::put('codcli', $codcli);
            DB::table('users')
            ->where('id','=',Auth::user()->id)
            ->update(array("ultcodcli" => $codcli));
        }
        $provs = TablaMaecliproveActiva($codcli);
        foreach($provs as $prov) {
            UpdateCondComercialCliente($codcli, $prov->codprove);
        }
        return Redirect::to('/');
    }   

    public function moneda($moneda) {
        if (Session::has('moneda')) {
            Session::forget('moneda');
        }
        Session::put('moneda', $moneda);
        //return Redirect::to('/');
        return redirect()->back()->with('result');
    }

    public function tipedido($tipedido) {
        if (Session::has('tipedido')) {
            Session::forget('tipedido');
        }
        Session::put('tipedido', $tipedido);
        //log::info('tipedido: '.$tipedido);
        //return redirect()->back()->with('result');
        return Redirect::to('/');
    }   

    public function modificar(Request $request) {
        $modo = $request->get('modo');
        if (!Session::has('sidebarMode')) {
            Session::put('sidebarMode', $modo);
        } else  {
            $sidebarMode = Session::get('sidebarMode', "");
            if ($sidebarMode == '1') 
                Session::put('sidebarMode', '2');
            else 
                Session::put('sidebarMode', '1');
        }
        return response()->json(['msg' => "" ]);
    }

    public function info(Request $request) {
        Session::put('info', '1');
        return response()->json(['msg' => "" ]);
    }

    public function prueba() {
        GenerarSugeridoAutomatico();
        dd("OK");
    } 

    public function prueba2() {
        $costo = 27.37;
        $precioInv = 32.92;
        $mpc = 29.84;
        $damin = 1.00;
        $damax = 8.00;
        $utilm = 8.00;
        $daInv = 5.00;
        $modo = 1;
        $resp = algoritmoOferta($costo, $precioInv, $mpc, $damin, $damax, $utilm, $daInv, $modo);
        dd($resp);
     }
   
} 