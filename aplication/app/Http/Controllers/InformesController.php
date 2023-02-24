<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request; 
use App\Http\Requests;
use App\User;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\UsuarioFormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\MaeclieproveFormRequest;
use App\Http\Requests\MaeproveFormRequest;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;
use DB;
use Barryvdh\DomPDF\Facade as PDF;
 
 
class InformesController extends Controller
{
    public function __construct() {
    	$this->middleware('auth');
    }

    public function index(Request $request) {
        $subtitulo = "MODULO DE INFORMES";
        return view('isacom.informes.index' ,["menu" => "Informes",
                                              "cfg" => DB::table('maecfg')->first(),
                                              "subtitulo" => $subtitulo ]);
    }

    public function pedidocliline(Request $request) {

        $moneda = Session::get('moneda', 'BSS');
        $subtitulo = "PEDIDOS (".$moneda.")";
        $codcli = sCodigoClienteActivo();
        $final = date('d-m-Y');
        $Fechax = strtotime('-30 day', strtotime($final));
        $fechax = date('d-m-Y', $Fechax);
        $comienzo = substr($fechax, 0, 10);
        $fechaInicio=strtotime($comienzo);
        $fechaFin=strtotime($final);
        $factor = RetornaFactorCambiario("", $moneda);
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
            $pedtotal = $pedtotal/$factor; 
            $pedtotal = number_format($pedtotal , 2, '.', '');
            $chart_data .= "{periodo:'".$fechai."',pedidos:".$pedtotal."},";
        }
        $chart_data = substr($chart_data, 0, -1);
        return view('isacom.informes.pedidocliline' ,["menu" => "Informes",
                                                      "cfg" => DB::table('maecfg')->first(),
                                                      "chart_data" => $chart_data,
                                                      "subtitulo" => $subtitulo ]);
    }   

    public function pedidoclibarra(Request $request) {
        $subtitulo = "";
        $moneda = Session::get('moneda', 'BSS');
        $subtitulo = "PEDIDOS (".$moneda.")";
        $chart_data = '';
        $chart_color = '';
        $array = [];
        $codcli = sCodigoClienteActivo();
        $provs = TablaMaecliproveActiva("");
        $factor = RetornaFactorCambiario("", $moneda);
        $hasta = date('Y-m-d');
        $desde = date('Y-m-d', strtotime('-365 day', strtotime($hasta)));
        $desde = $desde.' 00:00:00';
        $hasta = $hasta.' 23:59:00';
        foreach($provs as $prov) {
            $monto = 0;
            $confprov = LeerProve($prov->codprove);
            $codprove = strtolower($prov->codprove);

            //dd("Desde: ".$desde." - Hasta: ".$hasta ." - Codprove: ".$prov->codprove);

            $pedren = DB::table('pedren')
            ->whereBetween('fecha', array($desde, $hasta))
            ->where('codcli','=',$codcli)
            ->where('estado','=','RECIBIDO')
            ->where('codprove','=',$prov->codprove)
            ->selectRaw('sum(neto * cantidad) as subtotal')
            ->first();
            if ($pedren) {
                $monto = $pedren->subtotal/$factor;
                if ($monto > 0) {
                    $monto = number_format($monto , 2, '.', '');
                    $array[] = [
                        'nomprove' => $prov->codprove,
                        'monto' => $monto,
                        'backcolor' => $confprov->backcolor
                    ];
                }
            }   
        }
        if (count($array) > 1) { 
            foreach ($array as $key => $row) {
                $aux[$key] = $row['monto'];
            }
            array_multisort($aux, SORT_DESC, $array);
        }

        $total = count($array);
        for ($i = 0; $i < $total; $i++) {
            $nomprove = $array[$i]['nomprove'];
            $monto = $array[$i]['monto'];
            $backcolor = $array[$i]['backcolor'];
            $chart_data .= "{ y:'".$nomprove."',a:".$monto."},";
            $chart_color .= "'".$backcolor."',";
        }
        $chart_data = substr($chart_data, 0, -1);
        $chart_color = substr($chart_color, 0, -1);
        return view('isacom.informes.valprovbarra' ,["menu" => "Informes",
                                                     "cfg" => DB::table('maecfg')->first(),
                                                     "chart_data" => $chart_data,
                                                     "chart_color" => $chart_color,
                                                     "subtitulo" => $subtitulo ]);
    }  

    public function uniprovbarra(Request $request) {
        $subtitulo = "UNIDADES POR PROVEEDORES";
        $chart_data = '';
        $chart_color = '';
        $array = [];

        $provs = TablaMaecliproveActiva("");
        foreach($provs as $prov) {
            $cantinv = 0;
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
                    $cantinv = $cantinv + $cantidad;
                }
                $array[] = [
                    'nomprove' => $prov->codprove,
                    'cantinv' => $cantinv,
                    'backcolor' => $confprov->backcolor
                ];
            }   
        }
        if (count($array) > 1) { 
            foreach ($array as $key => $row) {
                $aux[$key] = $row['cantinv'];
            }
            array_multisort($aux, SORT_DESC, $array);
        }

        $total = count($array);
        for ($i = 0; $i < $total; $i++) {
            $nomprove = $array[$i]['nomprove'];
            $cantinv = $array[$i]['cantinv'];
            $backcolor = $array[$i]['backcolor'];
            $chart_data .= "{ y:'".$nomprove."',a:".$cantinv."},";
            $chart_color .= "'".$backcolor."',";
        }
        $chart_data = substr($chart_data, 0, -1);
        $chart_color = substr($chart_color, 0, -1);
        return view('isacom.informes.uniprovbarra' ,["menu" => "Informes",
                                                     "cfg" => DB::table('maecfg')->first(),
                                                     "chart_data" => $chart_data,
                                                     "chart_color" => $chart_color,
                                                     "subtitulo" => $subtitulo ]);
    }
 
    public function renprovbarra(Request $request) {
        $subtitulo = "RENGLONES POR PROVEEDORES";
        $chart_data = '';
        $chart_color = '';
        $array = [];
     
        $provs = TablaMaecliproveActiva("");
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
        return view('isacom.informes.renprovbarra' ,["menu" => "Informes",
                                                     "cfg" => DB::table('maecfg')->first(),
                                                     "chart_data" => $chart_data,
                                                     "chart_color" => $chart_color,
                                                     "subtitulo" => $subtitulo ]);
    }

    public function rnk1provbarra(Request $request) {
        set_time_limit(1000); 
        $subtitulo = "RNK-1 POR PROVEEDORES";
        $chart_data = '';
        $chart_color = '';
        $pedir = 1;
        $arrayRnk1 = [];
        $criterio = 'PRECIO';
        $preferencia = 'NINGUNA';
        $provs = TablaMaecliproveActiva("");
        $tpmaestra = DB::table('tpmaestra')
        ->get();
        if ($tpmaestra->count()>0) {
            foreach ($tpmaestra as $tp) {
                $barra = $tp->barra;
                $mejoropcion = BuscarMejorOpcion($barra, $criterio, $preferencia, $pedir, $provs);
                if ($mejoropcion != null) {
                    $existe = false;
                    $codprove = $mejoropcion[0]['codprove'];
                    $total = count($arrayRnk1);
                    for ($i = 0; $i < $total; $i++) {
                        if ($codprove == $arrayRnk1[$i]['codprove']) {
                            $arrayRnk1[$i]['contador'] = $arrayRnk1[$i]['contador'] + 1;
                            $existe = true;
                            break;
                        }
                    }
                    if ($existe == false) {
                        $arrayRnk1[] = [
                            'codprove' => $codprove,
                            'contador' => 1
                        ];
                    }
                } 
            }
        }

        foreach ($arrayRnk1 as $key => $row) {
            $aux[$key] = $row['contador'];
        }
        array_multisort($aux, SORT_DESC, $arrayRnk1);

        $total = count($arrayRnk1);
        for ($i = 0; $i < $total; $i++) {
            $codprove = strtolower($arrayRnk1[$i]['codprove']);
            $contador = $arrayRnk1[$i]['contador'];
            $confprov = LeerProve($arrayRnk1[$i]['codprove']);
            $nomprove = $confprov->descripcion;
            $chart_data .= "{ y:'".$arrayRnk1[$i]['codprove']."',a:".$contador."},";
            $chart_color .= "'".$confprov->backcolor."',";
        }
        $chart_data = substr($chart_data, 0, -1);
        $chart_color = substr($chart_color, 0, -1);
        return view('isacom.informes.rnk1provbarra' ,["menu" => "Informes",
                                                     "cfg" => DB::table('maecfg')->first(),
                                                     "chart_data" => $chart_data,
                                                     "chart_color" => $chart_color,
                                                     "subtitulo" => $subtitulo ]);
    }   

    public function mejoropciontable(Request $request) {
        set_time_limit(1000); 
        $subtitulo = "MEJOR OPCION";
        $array = [];

        $provs = TablaMaecliproveActiva("");
        foreach($provs as $prov) {
            $confprov = LeerProve($prov->codprove);
            $codprove = strtolower($prov->codprove);
            if (VerificaTabla($codprove)) {
                $prod = DB::table($codprove)
                ->selectRaw('SUM(cantidad) as contador')
                ->first(); 
                if ($prod) {
                    $array[] = [
                        'codprove' => $prov->codprove,
                        'contador' => $prod->contador
                    ];
                }   
            }
        }
        foreach ($array as $key => $row) {
            $aux[$key] = $row['contador'];
        }
        array_multisort($aux, SORT_DESC, $array);
        $arrayMejorInv = $array;

        $array = [];
        foreach($provs as $prov) {
            $confprov = LeerProve($prov->codprove);
            $codprove = strtolower($prov->codprove);
            if (VerificaTabla($codprove)) {
                $prod = DB::table($codprove)
                ->selectRaw('count(*) as contador')
                ->first(); 
                if ($prod) {
                    $array[] = [
                        'codprove' => $prov->codprove,
                        'contador' => $prod->contador
                    ];
                }   
            }
        }
        foreach ($array as $key => $row) {
            $aux[$key] = $row['contador'];
        }
        array_multisort($aux, SORT_DESC, $array);
        $arrayMayorVar = $array;

        $maximo = 100;
        $pedir = 1;
        $array = [];
        $criterio = 'PRECIO';
        $preferencia = 'NINGUNA';
        $tpmaestra = DB::table('tpmaestra')
        ->get();
        if ($tpmaestra->count()>0) {
            foreach ($tpmaestra as $tp) {
                $barra = $tp->barra;
                $mejoropcion = BuscarMejorOpcion($barra, $criterio, $preferencia, $pedir, $provs);
                if ($mejoropcion != null) {
                    $existe = false;
                    $codprove = $mejoropcion[0]['codprove'];
                    //$maximo--;
                    //if ($maximo <= 0)
                    //    break;
                    $total = count($array);
                    for ($i = 0; $i < $total; $i++) {

                        if ($codprove == $array[$i]['codprove']) {
                            $array[$i]['contador'] = $array[$i]['contador'] + 1;
                            $existe = true;
                            break;
                        }

                    }
                    if ($existe == false) {
                        $array[] = [
                            'codprove' => $codprove,
                            'contador' => 1
                        ];
                    }
                    
                } 
            }
        }

        foreach ($array as $key => $row) {
            $aux[$key] = $row['contador'];
        }
        array_multisort($aux, SORT_DESC, $array);
        $arrayMejorRnk1 = $array;

        return view('isacom.informes.mejoropciontable' ,["menu" => "Informes",
                                                         "cfg" => DB::table('maecfg')->first(),
                                                         "arrayMejorInv" => $arrayMejorInv,
                                                         "arrayMayorVar" => $arrayMayorVar,
                                                         "arrayMejorRnk1" => $arrayMejorRnk1,
                                                         "subtitulo" => $subtitulo ]);
    } 

    public function ejemplotable(Request $request) {
        $subtitulo = "TABLE";
        return view('isacom.informes.ejemplotable' ,["menu" => "Informes",
                                                     "cfg" => DB::table('maecfg')->first(),
                                                     "subtitulo" => $subtitulo ]);
    }

    public function invvalor(Request $request) {
        $moneda = Session::get('moneda', 'BSS');
        $subtitulo = "VALOR DEL INVENTARIO EN BASE AL COSTO (".$moneda.")";
        $tasaEur = RetornaFactorCambiario("", $moneda);
        $codcli = sCodigoClienteActivo();
        $chart_data = '';
        $cliente = DB::table('maecliente')
        ->where('codcli','=',$codcli)
        ->first();
        if ($cliente) {
            $fecha = date('Ymd');
            $fecHoy = substr($fecha, 0, 8);
            $mes = date("m", strtotime($fecHoy));
            $contador = strval($mes);
            if ($contador > 0) {
                for ($i = 1; $i <= $contador; $i++) {
                    $tmes = "";
                    switch ($i) {
                        case '1':
                            $dat = explode(";", $cliente->valorInv_01);
                            $tmes = "ENE";
                            break;
                        case '2':
                            $dat = explode(";", $cliente->valorInv_02);
                            $tmes = "FEB";
                            break;
                        case '3':
                            $dat = explode(";", $cliente->valorInv_03);
                            $tmes = "MAR";
                            break;
                        case '4':
                            $dat = explode(";", $cliente->valorInv_04);
                            $tmes = "ABR";
                            break;
                        case '5':
                            $dat = explode(";", $cliente->valorInv_05);
                            $tmes = "MAY";
                            break;
                        case '6':
                            $dat = explode(";", $cliente->valorInv_06);
                            $tmes = "JUN";
                            break;
                        case '7':
                            $dat = explode(";", $cliente->valorInv_07);
                            $tmes = "JUL";
                            break;
                        case '8':
                            $dat = explode(";", $cliente->valorInv_08);
                            $tmes = "AGO";
                            break;
                        case '9':
                            $dat = explode(";", $cliente->valorInv_09);
                            $tmes = "SEP";
                            break;
                        case '10':
                            $dat = explode(";", $cliente->valorInv_10);
                            $tmes = "OCT";
                            break;
                        case '11':
                            $dat = explode(";", $cliente->valorInv_11);
                            $tmes = "NOV";
                            break;
                        case '12':
                            $dat = explode(";", $cliente->valorInv_12);
                            $tmes = "DIC";
                            break;
                    }
                    $valor = $dat[0];
                    $tasa = $dat[1]; 
                    if ($moneda == 'USD')
                        $valor = $valor/$tasa;
                    if ($moneda == "EUR") 
                        $valor = $valor/$tasaEur;
                    $valor = number_format($valor, 2, '.', '');
                    $chart_data .= "{mes:'".$tmes."',valor:".$valor."},";
                }
                $chart_data = substr($chart_data, 0, -1);
            }
        }
        return view('isacom.informes.invvalor' ,["menu" => "Informes",
                                                  "cfg" => DB::table('maecfg')->first(),
                                                  "chart_data" => $chart_data,
                                                  "subtitulo" => $subtitulo ]);
    }   

    public function invvalorBorrar(Request $request) {
        $moneda = Session::get('moneda', 'BSS');
        $subtitulo = "VALOR DEL INVENTARIO (".$moneda.")";
        $tasaEur = RetornaFactorCambiario("", $moneda);
        $codcli = sCodigoClienteActivo();
        $chart_data = '';
        $cliente = DB::table('maecliente')
        ->where('codcli','=',$codcli)
        ->first();
        if ($cliente) {
            $dat = explode("|", $cliente->histValorInvent);
            array_pop($dat);
            $contador = count($dat);
            if ($contador > 0) {
                for ($i = 0; $i < $contador; $i++) {
                    $arrayvmd = explode(";", $dat[$i]);
                    $mes = $arrayvmd[0];
                    $valor = $arrayvmd[1];
                    $tasa = $arrayvmd[2]; 
                    if ($moneda == 'USD')
                        $valor = $valor/$tasa;


                    if ($moneda == "EUR") 
                        $valor = $valor/$tasaEur;
                    $valor = number_format($valor, 2, '.', '');
                    $chart_data .= "{mes:'".$mes."',valor:".$valor."},";
                }
                $chart_data = substr($chart_data, 0, -1);
            }
        }
        return view('isacom.informes.invvalor' ,["menu" => "Informes",
                                                  "cfg" => DB::table('maecfg')->first(),
                                                  "chart_data" => $chart_data,
                                                  "subtitulo" => $subtitulo ]);
    }   
  
    public function vendidoisacom(Request $request) {
        $subtitulo = "LOS 100 PRODUCTOS MAS VENDIDOS (ICOMPRAS)";
        $provs = TablaMaecliproveActiva("");
        $tabla = DB::table('pedren')
        ->select(DB::raw('sum(cantidad) as total, barra'))
        ->groupBy('barra')
        ->orderBy("total","desc")
        ->take(100)
        ->get();
        return view('isacom.informes.vendidoisacom' ,["menu" => "Informes",
                                                      "cfg" => DB::table('maecfg')->first(),
                                                      "tabla" => $tabla,
                                                      "provs" => $provs,
                                                      "subtitulo" => $subtitulo ]);
    }

    public function verauditoria(Request $request) {
        $codcli = sCodigoClienteActivo();
        $subtitulo = "AUDITORIA";
        $tabla = DB::table('logtrans')
        ->where('codcli','=',$codcli)
        ->orderBy("id","desc")
        ->paginate(100);
        return view('isacom.informes.verauditoria' ,["menu" => "Informes",
                                                     "cfg" => DB::table('maecfg')->first(),
                                                     "tabla" => $tabla,
                                                     "subtitulo" => $subtitulo ]);
    }

    public function verahorro(Request $request) {
        $moneda = Session::get('moneda', 'BSS');
        $subtitulo = "AHORROS (".$moneda.")";
        $chart_data = "";
        $codcli = sCodigoClienteActivo();
        $cliente = DB::table('maecliente')
        ->where('codcli','=',$codcli)
        ->first();
        if ($cliente) {
            $factor = RetornaFactorCambiario("", $moneda);
            $histAhorro = $cliente->histAhorro;
            $reg = explode("|", $histAhorro);
            if (!empty($reg[0])) {
                $contador = count($reg);
                for ($i = 0; $i < $contador; $i++) {
                    $campo = explode(";", $reg[$i]);
                    if (!empty($campo[0])) {
                        $mes = $campo[0];
                        $ahorro = strval($campo[1])/$factor;
                        $ahorro = number_format($ahorro, 2, '.', '');
                        //log::info("AHORRO: ".$ahorro);
                        $chart_data .= "{ y: '".$mes."', a: ".$ahorro."},";
                    }
                }
                $chart_data = substr($chart_data, 0, -1);
            }
        }
        return view('isacom.informes.verahorro' ,["menu" => "Informes",
                                                  "cfg" => DB::table('maecfg')->first(),
                                                  "chart_data" => $chart_data,
                                                  "subtitulo" => $subtitulo ]);
    }    

    public function desvped(Request $request) {
        $subtitulo = "AUDITORIA DE DESVIOS DE PEDIDOS";
        $codcli = sCodigoClienteActivo();
        $desde = trim($request->get('desde'));
        $hasta = trim($request->get('hasta'));
        if ($desde=='' || $hasta=='') {
            $hasta = date('Y-m-d');
            $desde = date('Y-m-d', strtotime('-7 day', strtotime($hasta)));
        }
        $desde = $desde.' 00:00:00';
        $hasta = $hasta.' 23:59:00';

        $tabla = DB::table('pedido as p')
        ->leftjoin('pedren as pr', 'p.id', '=', 'pr.id')
        ->where('p.codcli','=',$codcli)
        ->where('pr.ranking','!=','1')
        ->where('p.estado','!=','NUEVO')
        ->whereBetween('p.fecenviado', array($desde, $hasta))
        ->orderBy('p.fecenviado','desc')
        ->paginate(100);
        return view('isacom.informes.desvped' ,["menu" => "Informes",
                                                "tabla" => $tabla,
                                                "cfg" => DB::table('maecfg')->first(),
                                                "desde" => $desde,
                                                "hasta" => $hasta,
                                                "subtitulo" => $subtitulo ]);
    }
 }
