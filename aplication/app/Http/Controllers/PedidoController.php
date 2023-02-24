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
use Barryvdh\DomPDF\Facade as PDF;
use DB;  
use App\tpmaestra; 
use App\CustomClasses\ColectionPaginate; 
    
       
class PedidoController extends Controller
{
    public function __construct() {
    	$this->middleware('auth');
    }

    public function index(Request $request) {
    	if ($request) {
            $filtro=trim($request->get('filtro'));
            $codcli = sCodigoClienteActivo();
            vEliminarPedidoBlanco($codcli);

            $botonExportar = 0;
            $inventario = 'inventario_'.$codcli;
            if (VerificaTabla($inventario)) {
                $botonExportar = 1;
            }

            $pedido = DB::table('pedido')
            ->where('codcli','=',$codcli)
            ->where('estado','=','PARCIAL')
            ->where(function ($q)  {
                $q->where('tipedido','=','N')
                ->Orwhere('tipedido','=','A');
            })
            ->get();
            foreach ($pedido as $ped) {
                DB::table('pedren')
                ->where('id', '=', $ped->id)
                ->where('estado','=','NUEVO')
                ->where('aprobacion','!=','NO')
                ->update(array("estado" => 'RECIBIDO' ));
            }
            $tabla=DB::table('pedido')
            ->where('codcli','=',$codcli)
            ->where(function ($q)  {
                $q->where('tipedido','=','N')
                ->Orwhere('tipedido','=','A');
            })
            ->where(function ($q) use ($filtro) {
                $q->where('id','LIKE','%'.$filtro.'%')
                ->orwhere('estado','LIKE','%'.$filtro.'%')
                ->orwhere('origen','LIKE','%'.$filtro.'%')
                ->orwhere('fecha','LIKE','%'.date('Y-m-d', strtotime($filtro)).'%')
                ->orwhere('fecenviado','LIKE','%'.date('Y-m-d', strtotime($filtro)).'%');
            })
            ->orderBy('id','desc')
            ->paginate(20);
            $ped = DB::table('pedido')
            ->selectRaw('count(*) as contador')
            ->where('codcli','=',$codcli)
            ->where(function ($q)  {
                $q->where('tipedido','=','N')
                ->Orwhere('tipedido','=','A');
            })
            ->where(function ($q) use ($filtro) {
                $q->where('id','LIKE','%'.$filtro.'%')
                ->orwhere('estado','LIKE','%'.$filtro.'%')
                ->orwhere('origen','LIKE','%'.$filtro.'%')
                ->orwhere('fecha','LIKE','%'.date('Y-m-d', strtotime($filtro)).'%')
                ->orwhere('fecenviado','LIKE','%'.date('Y-m-d', strtotime($filtro)).'%');
            })
            ->first();
            $subtitulo = "PEDIDOS (".$ped->contador.")";
            return view('isacom.pedido.index' ,["menu" => "Pedidos",
                                                "cfg" => DB::table('maecfg')->first(),
                                                "tabla" => $tabla, 
                                                "filtro" => $filtro,
                                                "botonExportar" => $botonExportar,
                                                "subtitulo" => $subtitulo]);
    	}
    }

	public function show($id) { 
        if ($id == "verprod") {
            return redirect()->back()->with('result');
        }
        $s1 = explode('-', $id );
        if (count($s1) == 1) {
            $tpactivo = "MAESTRO";  
        } else {
            $id = $s1[0];
            $tpactivo = $s1[1];
        }
        $codcli = sCodigoClienteActivo();
    	$subtitulo = "CONSULTA DE PEDIDO";
	
    	// TABLA DE PEDIDO
        $tabla = DB::table('pedido')
	    ->where('id','=',$id)
        ->first();

        // TABLA DE RENGLONES DE PEDIDO
        $tabla2 = DB::table('pedren')
        ->where('id','=',$id)
        ->orderBy('item','asc')
        ->get();
        
        $provs = DB::table('maeclieprove')
        ->where('codcli','=',$codcli)
        ->where('status','=','ACTIVO')
        ->orderBy('preferencia','asc')
        ->get();

        $arrayProv = array('MAESTRO');
        foreach ($provs as $prov) {
            $pedren = DB::table('pedren')
            ->where('id','=',$id)
            ->where('codprove','=',$prov->codprove)
            ->first();
            if ($pedren) {
                $arrayProv[] = $prov->codprove;
            }
        }
        return view('isacom.pedido.show',["menu" => "Pedidos",
                                          "cfg" => DB::table('maecfg')->first(),
                                          "tabla" => $tabla, 
                                          "tabla2" => $tabla2, 
                                          "subtitulo" => $subtitulo,
                                          "tpactivo" => $tpactivo,
                                          "arrayProv" => $arrayProv,
                                          "id" => $id] );
    }

    public function exportar($id) {

        $s1 = explode('-', $id );
        if (count($s1) == 1) {
            $tpactivo = "MAESTRO";  
        } else {
            $id = $s1[0];
            $tpactivo = $s1[1];
        }
        $codcli = sCodigoClienteActivo();
        $subtitulo = "EXPORTAR PEDIDO";
    
        // TABLA DE PEDIDO
        $tabla = DB::table('pedido')
        ->where('id','=',$id)
        ->first();

        // TABLA DE RENGLONES DE PEDIDO
        $tabla2 = DB::table('pedren')
        ->where('id','=',$id)
        ->orderBy('item','asc')
        ->get();

        $provs = DB::table('maeclieprove')
        ->where('codcli','=',$codcli)
        ->where('status','=','ACTIVO')
        ->get();
        $arrayProv = [];
        $arrayProv[] = [ 'codprove' => 'MAESTRO', 'exportado' => '0' ];
        foreach ($provs as $prov) {
            if (empty($prov->codprove_adm))  {
                // FALTAN PARAMETROS DE EXPORTACION
                $arrayProv[] = [ 'codprove' => $prov->codprove, 
                                 'exportado' => '0'];
                continue;
            }
            $pedren = DB::table('pedren')
            ->where('id','=',$id)
            ->where('codprove','=',$prov->codprove)
            ->first();
            if ($pedren) {
                $arrayProv[] = [ 'codprove' => $prov->codprove, 
                                 'exportado' => $pedren->exportado ];
            }
        }
        $invent = collect();
        $inventario = 'inventario_'.$codcli;
        if (VerificaTabla($inventario)) {
            $invent = DB::table($inventario)
            ->where('cuarentena', '=', '0')
             ->orderBy('desprod','asc')
            ->get();
        }
        return view('isacom.pedido.exportar',["menu" => "Pedidos",
                                              "cfg" => DB::table('maecfg')->first(),
                                              "invent" => $invent,
                                              "tabla" => $tabla, 
                                              "tabla2" => $tabla2, 
                                              "subtitulo" => $subtitulo,
                                              "tpactivo" => $tpactivo,
                                              "arrayProv" => $arrayProv,
                                              "codcli" => $codcli,
                                              "id" => $id] );
    }

    public function procexportar(Request $request) {
        $sumaExportado = 0;
        $exportar = $request->get('exportar');
        if (count($exportar) == 0) {
            session()->flash('error', "No hay ningun proveedor marcado");
            return redirect()->back()->with('result');
        }
        $codcli = $request->get('codcli');
        $id = $request->get('id');
        $codmoneda = $request->get('codmoneda');
        $tasa = $request->get('tasa');
        $codprove = $request->get('codprove');
        $modalidad = $request->get('modalidad');
        for ($x=0; $x < count($exportar); $x++) {
            $prov = DB::table('maeclieprove')
            ->where('codcli','=',$codcli)
            ->where('codprove','=',$codprove[$x])
            ->first();
            if ($prov) {
                $exportado = 0;
                $pedren = DB::table('pedren')
                ->where('id','=',$id)
                ->where('codprove','=',$codprove[$x])
                ->get();
                foreach ($pedren as $pr) {
                    $respAlterno = VerificarCodalterno($pr->barra);
                    $codalterno = $respAlterno['codalterno'];
                    //log::info("CODALTERNO: ".$codalterno);

                    if ($codalterno == "")
                        continue;
                    DB::table('pedren')
                    ->where('item', '=', $pr->item)
                    ->update(array("exportado" => 1,
                        "codalterno" => $codalterno
                    ));
                    $exportado = 1;
                }
                if ($exportado > 0)  {
                    $sumaExportado++;
                    DB::table('docexportado')->insert([
                        'codcli' => $codcli, 
                        'coddoc' => $id, 
                        'tipo' => 'PED', 
                        'fecha' => date("Y-m-d H:i:s"),
                        'codprove' => $codprove[$x], 
                        'codprove_adm' => $prov->codprove_adm, 
                        'factor' => $tasa[$x],
                        'codmoneda' => $codmoneda[$x],
                        'usuario' => Auth::user()->name,
                        'modalidad' => $modalidad[$x],
                        'exportado' => 1
                    ]);
                }
            }
        }
        if ($sumaExportado == 0)
            session()->flash('error', "No se pudo exportar un(os) pedido(s)");
        //return redirect()->back()->with('result');
        return Redirect::to('/pedido');
    }

	public function destroy($id) {
        try {
            DB::beginTransaction();
            $codcli = sCodigoClienteActivo();
            DB::table('pedren')
            ->where('id','=',$id)
            ->delete();
       	    DB::table('pedido')
    		->where('id','=',$id)
    		->delete();
            DB::commit();
            session()->flash('message', 'Pedido '.$id.' eliminado satisfactoriamente');
        } catch (Exception $e) {
            DB::rollBack();
            session()->flash('error', $e);
        }
		return Redirect::to('/pedido');
	}

    public function edit($id) {

        $s1 = explode('-', $id );
        if (count($s1) == 1) {
            $tpactivo = "MAESTRO";  
        } else {
            $id = $s1[0];
            $tpactivo = $s1[1];
        }

        $codcli = sCodigoClienteActivo();
        $usuario = Auth::user()->name;
        $subtitulo = "PEDIDO";

        $cliente = DB::table('maecliente')
        ->where('codcli','=',$codcli)
        ->first();

        $tabla = DB::table('pedido')
        ->where('id','=',$id)
        ->first();

        // TABLA DE RENGLONES DE PEDIDO
        $tabla2 = DB::table('pedren')
        ->where('id','=',$id)
        ->orderBy('item','asc')
        ->get();

        // CUENTA LA CANTIAD DE ITEM DEL PEDIDO ABIERTO
        $reg = DB::table('pedren')
        ->where('id','=', $id)
        ->selectRaw('count(*) as contitem')
        ->first();
        $contItem = $reg->contitem;

        $subtitulo = "EDITAR PEDIDO";
        $provs = TablaMaecliproveActiva($codcli);
        $arrayProv = array('MAESTRO');
        foreach ($provs as $prov) {
            $pedren = DB::table('pedren')
            ->where('id','=',$id)
            ->where('codprove','=',$prov->codprove)
            ->first();
            if ($pedren) {
                $arrayProv[] = $prov->codprove;
            }
        }
        return view("isacom.pedido.edit", ["menu" => "Pedidos",
                                           "cfg" => DB::table('maecfg')->first(),
                                           "id" => $id,
                                           "tabla" => $tabla,
                                           "tabla2" => $tabla2,
                                           "subtitulo" => $subtitulo,
                                           "cliente" => $cliente,
                                           "filtro" => '',
                                           "tipo" => 'C',
                                           "accion" => "EDITAR",
                                           "tpactivo" => $tpactivo,
                                           "arrayProv" => $arrayProv,
                                           "contItem" => $contItem ]);
    }

    public function create() {
        $codcli = sCodigoClienteActivo();
        $id = iCrearPedidoNuevo('', 'N', '', 7, 0); 
        return Redirect::to('/pedido/catalogo/C');
    } 

    public function tendencia($codprod) {
        $subtitulo = "TENDENCIA DEL PRODUCTO (VMD/SEMANAS)";
        $chart_data = "";
        $cfg = DB::table('maecfg')->first();
        $codcli = sCodigoClienteActivo();
        $invent = LeerInventarioCodigo($codprod, $codcli);
        if (!is_null($invent)) {
            $chart_data = LineaTendenciaProd($codcli, $codprod);
        }
        return view('isacom.pedido.tendencia',["menu" => "Catalogo",
                                               "subtitulo" => $subtitulo,
                                               "cfg" => $cfg,
                                               "chart_data" => $chart_data,
                                               "invent" => $invent,
                                               "codprod" => $codprod]);
    }

	public function verprod($barra) {
        $subtitulo = "VER PRODUCTO";
        $tipo = Auth::user()->tipo;
        $tcmaestra = null;
        if ($tipo == "G") {
            $codgrupo = Auth::user()->codcli;
            $tabla = 'tcmaestra'.$codgrupo;
            if (VerificaTabla($tabla)) {
                $tcmaestra = DB::table($tabla)
                ->where('barra','=',$barra)
                ->first();
            }
        }
        $codcli = sCodigoClienteActivo();

        $cliente = DB::table('maecliente')
        ->where('codcli','=',$codcli)
        ->first();

        $grupo = DB::table('gruporen')
        ->where('id','=',Auth::user()->codcli)
        ->where('status','=', 'ACTIVO')
        ->orderBy("preferencia","asc")
        ->get();

        $provs = TablaMaecliproveActiva($codcli);
        $tabla = DB::table('maeprodimg')
        ->where('barra','=',$barra)
        ->first(); 

        $tpmaestra = DB::table('tpmaestra')
        ->where('barra','=',$barra)
        ->first();

        $maecatalogo = DB::table('maecatalogo')
        ->where('barra','=',$barra) 
        ->get();

        $pedren = DB::table('pedren')
        ->where('codcli','=',$codcli)
        ->where('barra','=',$barra)
        ->where('estado','=','RECIBIDO')
        ->orderBy('fecenviado','desc')
        ->take(10)
        ->get();

        $inv = verificarProdInventario($barra, $codcli);

        return view('isacom.pedido.verprod',["menu" =>"Pedidos",
                                             "cfg" => DB::table('maecfg')->first(),
                                             "cliente" => $cliente,
                                             "grupo" => $grupo,
                                             "tabla" => $tabla, 
                                             "provs" => $provs,
                                             "tcmaestra" => $tcmaestra,
                                             "tpmaestra" => $tpmaestra,
                                             "subtitulo" => $subtitulo,
                                             "tipo" => $tipo,
                                             "maecatalogo" => $maecatalogo, 
                                             "codcli" => $codcli,
                                             "inv" => $inv,
                                             "pedren" => $pedren,
                                             "barra" => $barra]);
    }

	public function catalogo(Request $request, $tipo) {
        $subtitulo = "PEDIDO";
        $filtro = trim($request->get('filtro'));
        $campo = explode("*", $filtro);
        $contador = count($campo);
        $provs = null;
        $arrayProv = null;
        $catalogo = "";
        $provactivo = "";
        $codcli = sCodigoClienteActivo();
        $provs = TablaMaecliproveActiva($codcli);
        $moneda = Session::get('moneda', 'BSS');
        $id = iCrearPedidoNuevo('', 'N', '', 7, 0); 
        $cliente = DB::table('maecliente')
        ->where('codcli','=',$codcli)
        ->first();
        // TABLA DE MOLECULAS
        $molecula = DB::table('molecula')
        ->where('descrip','!=', 'POR DEFINIR')
        ->orderBy("descrip","asc")
        ->get();

        // ENCABEZADO DEL PEDIDO
        $tabla = DB::table('pedido')
        ->where('id','=',$id)
        ->first();
    
        // MONTO TOTAL DEL PEDIDO
        $reg = DB::table('pedren')
        ->where('id','=', $id)
        ->selectRaw('SUM(cantidad * precio) as subtotal')
        ->first();
        $montoTotal = $reg->subtotal;
 
        // CUENTA LA CANTIAD DE ITEM DEL PEDIDO ABIERTO
        $reg = DB::table('pedren')
        ->where('id','=', $id)
        ->selectRaw('count(*) as contitem')
        ->first();
        $contItem = $reg->contitem;
        
        $s1 = explode('-', $tipo );
        $tipo = $s1[0];
        $tpactivo = '';

        //dd(count($s1));
        //dd($s1);
      
        if ( count($s1) > 1) {
            $tpactivo = $s1[1];
            if ($tipo == "TOP")
                $tipo = "C";
        }
        else {
            $tpactivo = 'MAESTRO';
        
            //if ($tipo == "TOP") 
            //    $tipo = "C";
        }

        // OBTENER EL PRIMER RENGLON DE LA MOLECULA
        if ($tipo == 'M') {
            if (empty($filtro)) {
                $firstmol = DB::table('molecula')
                ->orderBy("descrip","asc")
                ->first();
                if (!empty($firstmol)) 
                    $filtro = $firstmol->descrip; 
            }
        }
        
        $provactivo = null;
        $arrayCampo = array();
        $arrayTp = array();
        $arrayProv = array('MAESTRO');
        foreach ($provs as $prov) {
            $arrayCampo[] = strtolower($prov->codprove);
            $arrayTp[] = strtolower($prov->codprove);
            $pedren = DB::table('pedren')
            ->where('id','=',$id)
            ->where('codprove','=',$prov->codprove)
            ->first();
            if ($pedren) {
                $arrayProv[] = $prov->codprove;
            } 
        }
        $subtitulo = "CATALOGO MAESTRO";
        if ($tpactivo=="MAESTRO") {
            if ($contador == 1) {
                // BUSQUEDA SENCILLA (FILTRO)
                if ($tipo == "TOP") {
                    // TOP 20
                    $subtitulo = "TOP 20 DE ICOMPRAS";
                    $catalogo = DB::table('pedren')
                    ->select(DB::raw('sum(cantidad) as total, barra'))
                    ->groupBy('barra')
                    ->orderBy("total","desc")
                    ->take(20)
                    ->get();
                } 
                if ($tipo == "C") {
                    // CATALOGO GENERAL
                    $catalogo = tpmaestra::select('*')
                    ->orwhere('desprod','LIKE','%'.$filtro.'%')
                    ->Orwhere('barra','LIKE','%'.$filtro.'%')
                    ->Orwhere('marca','LIKE','%'.$filtro.'%')
                    ->Orwhere('pactivo','LIKE','%'.$filtro.'%')
                    ->orderBy('desprod','asc')
                    ->paginate(25);
                    // ENTRA AQUI INICIAL
                } 
                // ENTRADAS, OFERTAS, RNK1, MOLECULAS
                if ($tipo == "M") {
                    // MOLECULAS
                    $catalogo = array();
                    $catatemp = tpmaestra::select('*')
                    ->where('molecula','=',$filtro)
                    ->orderBy('desprod','asc')
                    ->get();
                    foreach ($catatemp as $cat) {
                        $dataprod = obtenerDataTpmaestra($cat, $provs, 1);
                        if (is_null($dataprod))
                            continue;   
                        $mpp = $dataprod['mpp'];
                        $unidadmolecula = $dataprod['unidadmolecula'];
                        $cat->unidadmolecula = $unidadmolecula;
                        $cat->liqmolecula = $mpp;
                        $cat->mppliq = $dataprod['mppliq'];
                        $cat->mppliqmol = $dataprod['mppliqmol'];
                        $catalogo[] = $cat;
                    }
                    $catalogo = collect($catalogo);
                    $catalogo = $catalogo->sortBy('liqmolecula');
                    $catalogo = ColectionPaginate::paginate($catalogo, 100);
                } 
                if ($tipo == "E" || $tipo == "O" || $tipo == "R") {
                    // ENTRADAS, OFERTAS, RNK1
                    $catalogo = tpmaestra::select('*')
                    ->orwhere('desprod','LIKE','%'.$filtro.'%')
                    ->Orwhere('barra','LIKE','%'.$filtro.'%')
                    ->Orwhere('marca','LIKE','%'.$filtro.'%')
                    ->Orwhere('pactivo','LIKE','%'.$filtro.'%')
                    ->orderBy('desprod','asc')
                    ->paginate(100);
                }
            } else {
                // BUSQUEDA COMPUESTA (FILTRO)
                $filtro1 = $campo[0];
                $filtro2 = $campo[1];
                if ($tipo == "C") {
                    if (empty($filtro1) && $filtro2) {
                        // BUSCAR POR MARCA
                        $catalogo = tpmaestra::select('*')
                        ->where('marca', 'LIKE', '%'.$filtro2.'%')
                        ->orderBy('desprod','asc')
                        ->paginate(100);
                    } else {
                        $catalogo = tpmaestra::select('*')
                        ->Orwhere(function ($q) use ($filtro1, $filtro2) {
                            $q->where('desprod','LIKE','%'.$filtro1.'%')
                            ->where('desprod','LIKE','%'.$filtro2.'%');
                        })
                        ->Orwhere(function ($q) use ($filtro1, $filtro2) {
                            $q->where('desprod','LIKE','%'.$filtro1.'%')
                            ->where('marca','LIKE','%'.$filtro2.'%');
                        })
                        ->orderBy('desprod','asc')
                        ->paginate(100);
                    }
                } else {
                    if (empty($filtro1) && $filtro2) {
                        // BUSCAR POR MARCA
                        $catalogo = tpmaestra::select('*')
                        ->where('marca', 'LIKE', '%'.$filtro2.'%')
                        ->orderBy('desprod','asc')
                        ->paginate(100);
                    } else {
                        $catalogo = tpmaestra::select('*')
                        ->Orwhere(function ($q) use ($filtro1, $filtro2) {
                            $q->where('desprod','LIKE','%'.$filtro1.'%')
                            ->where('desprod','LIKE','%'.$filtro2.'%');
                        })
                        ->Orwhere(function ($q) use ($filtro1, $filtro2) {
                            $q->where('desprod','LIKE','%'.$filtro1.'%')
                            ->where('marca','LIKE','%'.$filtro2.'%');
                        })
                        ->orderBy('desprod','asc')
                        ->paginate(100);
                    }
                } 
            }
        } else {
            $tp = strtolower($tpactivo);
            $codprove = strtolower($tpactivo);
            $factor = RetornaFactorCambiario($codprove, $moneda);
            $provactivo = LeerClieProve($tpactivo, "");
            $tipoprecio = $provactivo->tipoprecio;
            $confprov = LeerProve($codprove);
            if ($contador == 1) {
                // BUSQUEDA SENCILLA (FILTRO)
                if ($tipo == "C") {
                    // CATALOGO GENERAL
                    if (VerificaTabla($tp)) {
                        $catalogo =  DB::table($tp)
                        ->orwhere('desprod','LIKE','%'.$filtro.'%')
                        ->Orwhere('codprod','LIKE','%'.$filtro.'%')
                        ->Orwhere('barra','LIKE','%'.$filtro.'%')
                        ->Orwhere('marca','LIKE','%'.$filtro.'%')
                        ->Orwhere('pactivo','LIKE','%'.$filtro.'%')
                        ->orderBy('desprod','asc')
                        ->paginate(100);
                    }
                } else {
                    // ENTRADAS, OFERTAS, RNK1, MOLECULAS
                    $catalogo = array();
                    if ($tipo == "M")  {
                        if (VerificaTabla($tp)) {
                            $catatemp = DB::table($tp)
                            ->where('molecula','=',$filtro)
                            ->orderBy('desprod','asc')
                            ->get();
                            foreach ($catatemp as $cat) {
                                $unidadmolecula = 1;
                                $prodcaract = DB::table('prodcaract')
                                ->where('barra','=',$cat->barra)
                                ->first();
                                if ($prodcaract) 
                                    $unidadmolecula = $prodcaract->unidadmolecula;
                              
                                $precio = $cat->precio1;
                                switch ($tipoprecio) {
                                    case 1:
                                        $precio = $cat->precio1/$factor;
                                        break;
                                    case 2:
                                        $precio = $cat->precio2/$factor;
                                        break;
                                    case 3:
                                        $precio = $cat->precio3/$factor;
                                        break;
                                    default:
                                        $precio = $cat->precio1/$factor;
                                        break;
                                }
                                $dc = $provactivo->dcme;
                                $di = $provactivo->di;
                                $pp = $provactivo->ppme;
                                $da = 0.00;
                                if ($tipoprecio == $confprov->aplicarDaPrecio)
                                    $da = $cat->da;
                                $neto = CalculaPrecioNeto($precio, $da, $di, $dc, $pp, 0.00);
                                $liquida = $neto + (($neto * $cat->iva)/100);
                                $cat->liquida = $liquida;
                                $cat->liqmolecula = $liquida/$unidadmolecula;
                                $cat->unidadmolecula = $unidadmolecula;
                                $catalogo[] = $cat;
                            }

                            $catalogo = collect($catalogo);
                            $catalogo = $catalogo->sortBy('liqmolecula');
                            $catalogo = ColectionPaginate::paginate($catalogo, 1000);

                        }
                    } else {
                        if (VerificaTabla($tp)) {
                            $catalogo =  DB::table($tp)
                            ->orwhere('desprod','LIKE','%'.$filtro.'%')
                            ->Orwhere('barra','LIKE','%'.$filtro.'%')
                            ->Orwhere('marca','LIKE','%'.$filtro.'%')
                            ->Orwhere('pactivo','LIKE','%'.$filtro.'%')
                            ->orderBy('desprod','asc')
                            ->paginate(100);
                        }
                    }
                }
            } else {
                // BUSQUEDA COMPUESTA (FILTRO)
                $filtro1 = $campo[0];
                $filtro2 = $campo[1];
                if ($tipo == "C") {
                    // CATALOGO
                    if (empty($filtro1) && $filtro2) {
                        // BUSCAR POR MARCA
                        if (VerificaTabla($tp)) {
                            $catalogo =  DB::table($tp)
                            ->where('marca','LIKE','%'.$filtro2.'%')
                            ->orderBy('desprod','asc')
                            ->paginate(100);
                        }
                    } else {
                        if (VerificaTabla($tp)) {
                            $catalogo =  DB::table($tp)
                            ->Orwhere(function ($q) use ($filtro1, $filtro2) {
                                $q->where('desprod','LIKE','%'.$filtro1.'%')
                                ->where('desprod','LIKE','%'.$filtro2.'%');
                            })
                            ->Orwhere(function ($q) use ($filtro1, $filtro2) {
                                $q->where('desprod','LIKE','%'.$filtro1.'%')
                                ->where('marca','LIKE','%'.$filtro2.'%');
                            })
                            ->orderBy('desprod','asc')
                            ->paginate(100);
                        }
                    }
                } else {
                    if (empty($filtro1) && $filtro2) {
                        // BUSCAR POR MARCA
                        if (VerificaTabla($tp)) {
                            $catalogo =  DB::table($tp)
                            ->where('marca','LIKE','%'.$filtro2.'%')
                            ->orderBy('desprod','asc')
                            ->paginate(100);
                        }
                    } else {
                        if (VerificaTabla($tp)) {
                            $catalogo =  DB::table($tp)
                            ->Orwhere(function ($q) use ($filtro1, $filtro2) {
                                $q->where('desprod','LIKE','%'.$filtro1.'%')
                                ->where('desprod','LIKE','%'.$filtro2.'%');
                            })
                            ->Orwhere(function ($q) use ($filtro1, $filtro2) {
                                $q->where('desprod','LIKE','%'.$filtro1.'%')
                                ->where('marca','LIKE','%'.$filtro2.'%');
                            })
                            ->orderBy('desprod','asc')
                            ->paginate(100);
                        }
                    }
                }
            }
            $subtitulo = "CATALOGO DE ".$confprov->descripcion;
        }
        return view("isacom.pedido.catalogo",["menu" => "Catalogo",
                                              "cfg" => DB::table('maecfg')->first(),
                                              "molecula" => $molecula,
                                              "catalogo" => $catalogo, 
                                              "tabla" => $tabla,
                                              "filtro" => $filtro,
                                              "tipo" => $tipo,
                                              "subtitulo" => $subtitulo,
                                              "contItem" => $contItem,
                                              "cliente" => $cliente,
                                              "provs" => $provs,
                                              "arrayProv" => $arrayProv,
                                              "accion" => "CONSULTAR",
                                              "tpactivo" => $tpactivo,
                                              "provactivo" => $provactivo,
                                              "codcli" => $codcli,
                                              "montoTotal"=> $montoTotal]);
    }

    public function agregar(Request $request) {
        $barra = $request->get('barra');
        $id = $request->get('id');
        $pedir = $request->get('pedir');
        $pedir = ($pedir == '0' || $pedir == '' ) ? '1' : $pedir;
        $codprove = $request->get('codprove');
        $tprnk1 = $request->get('tprnk1');
        $ranking = ($codprove != $tprnk1) ? '0' : '1';
        $msg = "";
        $subrenglon = 0.00;
        $item = 0;
        $resp = "";
        $cantTransito = 0;
        $usuarioCreador = Auth::user()->email;
        $pederror = DB::table('pedido')
        ->where('id','=',$id)
        ->first();
        if ($pederror) {
            $codcli = $pederror->codcli;     
        }
        $subrenglonError = $pederror->subrenglon;
        $itemError = $pederror->numren;
        $cliente = DB::table('maecliente')
        ->where('codcli','=',$codcli)
        ->first();
        if ($cliente->diasTransito > 0) 
            $cantTransito = verificarProdTransito($barra, $codcli, "");
        $provs = TablaMaecliproveActiva($codcli);
        if ($tprnk1 == "D") {
            // PEDIDO DIRECTO
            if ($cantTransito > 0) {
                if ($cliente->ModoNotiTrans == 0) {
                    $msg = "EXISTEN: (".$cantTransito.") UNIDADES EN TRANSITO DEL PRODUCTO: ".$prod->desprod." \n NO SE AGREGARA AL CARRITO";
                    $resp = "NO";
                }  else { 
                    $msg = "EXISTEN: (".$cantTransito.") UNIDADES EN TRANSITO DEL PRODUCTO: ".$prod->desprod;
                }
            }
            if ($resp == "") {
                // BUSCAR DATOS DEL PRODUCTO A AGREGAR
                $prod = DB::table('inventario_'.$codcli)
                ->where('barra','=',$barra)
                ->first();
                if (empty($prod)) {
                    return response()->json(['msg' => "ERROR INESPERADO (inventario=".$barra."), INTENTE MAS TARDE!!",
                         'resp' => $resp, 
                         'total' => $subrenglonError,
                         'item' => $itemError]);
                }
                $usaprecio = $cliente->usaprecio;
                $usaprecio = (empty($usaprecio)) ? "1" : $usaprecio;
                $precio = 'precio'.$usaprecio;
                $precio = $prod->$precio;
                DB::table('pedren')->insert([
                    'id' => $id, 
                    'codprod' => $prod->codprod, 
                    'desprod' => $prod->desprod, 
                    'cantidad' => $pedir, 
                    'precio' => $precio, 
                    'barra' => $barra,
                    'codprove' => $codprove,
                    'regulado' => $prod->regulado,
                    'tipo' => $prod->tipo,
                    'pvp' => $precio,
                    'iva' => $prod->iva,
                    'da' => 0.00,
                    'di' => 0.00,
                    'dc' => 0.00,
                    'pp' => 0.00,
                    'neto' => $precio,
                    'codcli' => $codcli,
                    'ahorro' => 0.00,
                    'subtotal' => $precio * $pedir,
                    'aprobacion' => "NO",
                    'estado' => "NUEVO",
                    "fecha" => date("Y-m-d H:i:s"),
                    "fecenviado" => date("Y-m-d H:i:s"),
                    "bulto" => $prod->bulto,
                    "usuarioCreador" => $usuarioCreador,
                    "tprnk1" => '0',
                    "ranking" => '0-0',
                    "dcredito" => '0'
                ]);
            }
        } else {
            $maeclieprove = DB::table('maeclieprove')
            ->where('codcli','=',$codcli)
            ->where('codprove','=',$codprove)
            ->where('status','=','ACTIVO')
            ->first();
            if (empty($maeclieprove)) {
                return response()->json(['msg' => "ERROR INESPERADO (maeclieprove=".$codprove."), INTENTE MAS TARDE!!",
                     'resp' => $resp, 
                     'total' => $subrenglon,
                     'item' => $item]);
            }
            $dc = $maeclieprove->dcme;
            $di = $maeclieprove->di;
            $pp = $maeclieprove->ppme;
            $tipoprecio = $maeclieprove->tipoprecio;

            // BUSCAR DATOS DEL PRODUCTO A AGREGAR
            $prod = DB::table('tpmaestra')
            ->where('barra','=',$barra)
            ->first();
            if (empty($prod)) {
                return response()->json(['msg' => "ERROR INESPERADO (tpmaestra=".$barra."), INTENTE MAS TARDE!!",
                     'resp' => $resp, 
                     'total' => $subrenglonError,
                     'item' => $itemError]);
            }
            $codprovminuscula = trim(strtolower($codprove));
            $campo = explode("|", $prod->$codprovminuscula);
            $cantidad = $campo[1];
            if (floatval($pedir) > floatval($cantidad)) {
                $msg = "CANTIDAD A PEDIR: (".$pedir.") ES MAYOR AL INVENTARIO ACTUAL: (".$cantidad.") PROVEEDOR: (".$codprove.") DEL PRODUCTO: ".$prod->desprod;
            } else {
                if ($cantTransito > 0) {
                    if ($cliente->ModoNotiTrans == 0) {
                        $msg = "EXISTEN: (".$cantTransito.") UNIDADES EN TRANSITO DEL PRODUCTO: ".$prod->desprod." \n NO SE AGREGARA AL CARRITO";
                        $resp = "NO";
                    }
                    else {
                        $msg = "EXISTEN: (".$cantTransito.") UNIDADES EN TRANSITO DEL PRODUCTO: ".$prod->desprod;
                    }
                } 
                if ($resp == "") {
                    switch ($tipoprecio) {
                        case 1:
                            $precio = $campo[0];
                            break;
                        case 2:
                            $precio = $campo[5];
                            break;
                        case 1:
                            $precio = $campo[6];
                            break;
                        default:
                            $precio = $campo[0];
                            break;
                    }
                    $confprov = LeerProve($codprove);
                    $da = 0.00;
                    if ($tipoprecio == $confprov->aplicarDaPrecio )
                        $da = $campo[2];
                    $codprod = $campo[3];
                    $desprod = $campo[9];
                    $dcredito = $campo[12];
                    $dp = 0.00;
                    if (bValida_Preempaque($pedir, $campo[11], $campo[10])) {
                        $dp = $campo[10];
                    }
                    $neto = CalculaPrecioNeto($precio, $da, $di, $dc, $pp, $dp);
                    $ahorro = dBuscarMontoAhorro($barra, $neto, $codcli);
                    $subtotal = floatval($neto) * intval($pedir);
                    $ranking = BuscarRanking($prod, $provs, $neto);
                    DB::table('pedren')->insert([
                        'id' => $id, 
                        'codprod' => $codprod, 
                        'desprod' => $desprod, 
                        'cantidad' => $pedir, 
                        'precio' => $precio, 
                        'barra' => $barra,
                        'codprove' => $codprove,
                        'regulado' => $prod->regulado,
                        'tipo' => $prod->tipo,
                        'pvp' => $precio,
                        'iva' => $prod->iva,
                        'da' => $da,
                        'dp' => $dp,
                        'di' => $di,
                        'dc' => $dc,
                        'pp' => $pp,
                        'neto' => $neto,
                        'codcli' => $codcli,
                        'ahorro' => $ahorro,
                        'subtotal' => $subtotal,
                        'aprobacion' => "NO",
                        'estado' => "NUEVO",
                        "fecha" => date("Y-m-d H:i:s"),
                        "fecenviado" => date("Y-m-d H:i:s"),
                        "bulto" => $prod->bulto,
                        "usuarioCreador" => $usuarioCreador,
                        "tprnk1" => $tprnk1,
                        "ranking" => $ranking,
                        "dcredito" => $dcredito
                    ]);
                    ///// aqui
                }
            }
        }
        CalculaTotalesPedido($id);
        $ped = DB::table('pedido')
        ->where('id','=',$id)
        ->first();
        $subrenglon = $ped->subrenglon;
        $item = $ped->numren;
        return response()->json(['msg' => $msg,
                                 'resp' => $resp, 
                                 'total' => $subrenglon,
                                 'item' => $item]);
    }

    public function leerpedgrupo(Request $request) {
        $id = $request->get('id');
        return response()->json(['status'=>200, 'data' => DB::table('pedgrupo')
            ->where('id','=',$id)
            ->first()
        ]);
    }

    public function modificar(Request $request) {
        set_time_limit(300);
        $tipedido = (Auth::user()->userPedDirecto == 1 ) ? "D" : "N"; 
        $item = $request->get('item');
        $pedir = $request->get('pedir');
        $idpedido = $request->get('idpedido');

        $subrenglon = 0;
        $descuento = 0;
        $subtotal = 0;
        $impuesto = 0;
        $total = 0;
        $msg = "";

        $pedren = DB::table('pedren')
        ->where('item','=',$item)
        ->first();

        $barra = $pedren->barra;
        $pedirOri = $pedren->cantidad;
        $codprove = strtolower($pedren->codprove);

        $catalogo = DB::table('tpmaestra')
        ->where('barra','=',$barra)
        ->first();
        $campos = $catalogo->$codprove;
        $campo = explode("|", $campos);
        $cantidad = $campo[1];
        if (floatval($pedir) > floatval($cantidad)) {
            $msg = "CANTIDAD A PEDIR ES MAYOR > AL INVENTARIO"; 
        }
        if ($msg == "") {
            DB::table('pedren')
            ->where('item', '=', $item)
            ->update(array('cantidad' => $pedir));
            CalculaTotalesPedido($idpedido);
            $pedido = DB::table('pedido')
            ->where('id','=',$idpedido)
            ->first();
            $subrenglon = $pedido->subrenglon;
            $descuento = $pedido->descuento;
            $subtotal = $pedido->subtotal;
            $impuesto = $pedido->impuesto;
            $total = $pedido->total;
        }
        return response()->json(['msg' => $msg,
                                 'subrenglon' => $subrenglon,
                                 'descuento' => $descuento,
                                 'subtotal' => $subtotal,
                                 'impuesto' => $impuesto,
                                 'total' => $total,
                                 'pedirOri' => $pedirOri ]);
    }

    public function modcodalterno(Request $request) {
        set_time_limit(300);
        $barra = trim($request->get('barra'));
        $codalterno = trim($request->get('codalterno'));
        $color = $request->get('color');
        $codcli = sCodigoClienteActivo();
        $existe = 0; 
        $invent = 'inventario_'.$codcli;
        //$msg = $barra ." - ". $codalterno 
        $msg = '';
        $prodalterno = DB::table('prodalterno')
        ->where('codcli','=',$codcli)
        ->where('barra','=',$barra)
        ->first();
        if (empty($prodalterno)) {
            if ($codalterno != "") {
                if (VerificaTabla($invent)) {
                    $invent = DB::table($invent)
                    ->where('codprod', '=', $codalterno)
                    ->where('cuarentena', '=', '0')
                    ->first();
                    if ($invent) 
                        $existe = 1; 
                }
                if ($existe == 1) {
                    DB::table('prodalterno')->insertGetId([
                        'barra' => $barra, 
                        'codcli' => $codcli, 
                        'codalterno' => $codalterno
                    ]);
                } else {
                    $msg = "CODIGO ALTERNO NO EXISTE";
                }
            } 
        } else {
            if ($codalterno == "") {
                DB::table('prodalterno')
                ->where('codcli','=',$codcli)
                ->where('barra','=',$barra)
                ->delete();
            } else {
                if (VerificaTabla($invent)) {
                    $invent = DB::table($invent)
                    ->where('codprod', '=', $codalterno)
                    ->where('cuarentena', '=', '0')
                    ->first();
                    if ($invent) 
                        $existe = 1; 
                }
                if ($existe == 1) {
                    if ($codalterno != $prodalterno->codalterno) {
                        DB::table('prodalterno')
                        ->where('codcli','=',$codcli)
                        ->where('barra','=',$barra)
                        ->update(array("codalterno" => $codalterno));                    
                    }
                } else {
                    $msg = "CODIGO ALTERNO NO EXISTE";
                } 
            }
        } 
        return response()->json(['msg' => $msg ]);
    }

    public function deleprod(Request $request, $item) {
        $id = trim($request->get('id'));
        $regs = DB::table('pedren')
        ->where('item','=',$item)
        ->delete();
        CalculaTotalesPedido($id);
        return redirect()->back()->with('result');
    }

    public function enviar(Request $request, $id) {
        set_time_limit(300);
        $tipedido = $request->get('tipedido');
        $pedido = DB::table('pedido')
        ->where('id','=', $id)
        ->first();
        if (empty($pedido)) {
            session()->flash('error', 'ERROR EN EL ENVIO, INTENTE MAS TARDE !!!');
            return Redirect::to('/pedido');
        } 
        $codcli = $pedido->codcli;
        $usuario = Auth::user()->email;
        $mensaje = "";
        try {
            // PEDIDOS A PROVEEDORES
            $provs = TablaMaecliproveActiva($codcli);
            $arrayProv = array();
            foreach ($provs as $prov) {
                $pedren = DB::table('pedren')
                ->where('id','=',$id)
                ->where('codprove','=',$prov->codprove)
                ->first();
                if ($pedren) {
                    $arrayProv[] = $prov->codprove;
                }
            }
            $marcar = 0;
            foreach ($arrayProv as $codprove) {
                $check = ($request->get('check-'.$codprove) == 'on' ? true : false);
                if ($check) {
                    if (empty($codprove))
                        continue;
                    $marcar = 1;
                    $maeprove = LeerProve($codprove);
                    if (is_null($maeprove)) {
                        log::info("ENVIO -> ID: ".$id." CODPROV: ".$codprove.' WARNING: INEXPERADO(1)');
                        continue;
                    }
                    $tablaclieprove = LeerClieProve($codprove, $codcli);
                    if (is_null($tablaclieprove)) {
                        log::info("ENVIO -> ID: ".$id." CODPROV: ".$codprove.' WARNING: INEXPERADO(2)');
                        continue;
                    }
                    $codigo = $tablaclieprove->codigo;
                    $subcarpeta = $tablaclieprove->subcarpetaftp;
                    $codsede = $maeprove->codsede;
                    $tipocata = $maeprove->tipocata;
                    $modoEnvioPedido = $maeprove->modoEnvioPedido;
                    $modoconexion = $maeprove->modoconexion;
                    $ftp = $maeprove->ftpserver; 
                    $ftpuser = $maeprove->ftpuser; 
                    $ftppass = $maeprove->ftppass; 
                    $ftppasv = $maeprove->ftppasv; 

                    $host = $maeprove->host; 
                    $basedato = $maeprove->basedato; 
                    $username = $maeprove->username; 
                    $clave = $maeprove->password; 

                    $pedren = DB::table('pedren')
                    ->where('id','=', $id)
                    ->where('estado','=', 'NUEVO')
                    ->where('codprove','=', $codprove)
                    ->get();
                    if (empty($pedren)) {
                        log::info("ENVIO -> ID: ".$id." CODPROV: ".$codprove.' WARNING: INEXPERADO(4)');
                        continue;
                    }

                    // CALCULO DE TOTALES DEL PEDIDO
                    $dSubrenglon = 0.00;
                    $dDecuento = 0.00;
                    $dSubtotal = 0.00;
                    $dImpuesto = 0.00;
                    $dTotal = 0.00;
                    $iNumren = 0;
                    $iNumund = 0;
                    $dMontoAhorro = 0;
                    foreach ($pedren as $pr) {
                        $neto = CalculaPrecioNeto($pr->precio, $pr->da, $pr->di, $pr->dc, $pr->pp, $pr->dp);
                        $subtotal = $neto * $pr->cantidad;
                        if ($pr->iva > 0) {
                            $dImpuesto = $dImpuesto + (($subtotal * $pr->iva)/100);
                        }
                        $dSubtotal = $dSubtotal + $subtotal;
                        $iNumren++;
                        $iNumund = $iNumund + $pr->cantidad;
                        $dSubrenglon = $dSubrenglon + ($pr->precio * $pr->cantidad);
                        $dMontoAhorro = $dMontoAhorro + $pr->ahorro;
                    }
                    $dDecuento = $dSubrenglon - $dSubtotal;
                    $dTotal = $dSubtotal + $dImpuesto;

                    if ($iNumren <= 0 || $dTotal <= 0) {
                        log::info("ENVIO -> ID: ".$id." CODPROV: ".$codprove.' WARNING: EN BLANCO');
                        continue;
                    }
                    switch ($modoEnvioPedido) { 
                        case 'MYSQL':
                            try {
                                if (empty($host)) {
                                    $mensaje = $codprove." ,FALTAN DATOS DE CONEXION";
                                    break;
                                }
                                log::info("MYSQL1       : ".$maeprove->descripcion);
                                log::info("host         : ".$host);
                                log::info("database     : ".$basedato);
                                log::info("username     : ".$username);
                                log::info("password     : ".$clave);
                                try {
                                    Config::set("database.connections.mysql2", [
                                        "driver" => "mysql",
                                        "host" => $host,
                                        "database" => $basedato,
                                        "username" => $username,
                                        "password" => $clave
                                    ]);
                                    Config::set('database.default', 'mysql2');
                                    DB::reconnect('mysql2');
                                    $enviado = 0;
                                    $idrepetido = DB::table('pedido')
                                    ->where('origen','=', 'C-ICOMPRAS ID: '.$id)
                                    ->get();
                                    foreach ($idrepetido as $idrep) {
                                        // REPETIDO, DEBE ELIMINAR EL PEDIDO ANTERIOR
                                        // [ARA ENVIARLO NUEVAMENTE
                                        $idx = $idrep->id;
                                        // ENCAABEZADO
                                        DB::table('pedido')
                                        ->where('id','=', $idx)
                                        ->delete();
                                        // RENGLONES
                                        DB::table('pedren')
                                        ->where('id','=', $idx)
                                        ->delete();
                                    }
                                    $idnew = DB::table('pedido')->insertGetId([
                                        'codcli' => $codigo,
                                        'fecha' => date("Y-m-d H:i:s"), 
                                        'estado' => 'NUEVO', 
                                        'fecenviado' => date("Y-m-d H:i:s"),
                                        'fecprocesado' => date("Y-m-d H:i:s"), 
                                        'origen' => 'C-ICOMPRAS ID: '.$id, 
                                        'codvend' => 'ICOMPRAS', 
                                        'usuario' => $usuario,
                                        'tipedido' => 'N',
                                        'nomcli' => $pedido->nomcli,
                                        'rif' => $pedido->rif,
                                        'dcredito' => $tablaclieprove->dcredito,
                                        'di' => $tablaclieprove->di,
                                        'dc' => $tablaclieprove->dcme,
                                        'pp' => $tablaclieprove->ppme,
                                        'subrenglon' => $dSubrenglon,
                                        'descuento' => $dDecuento,
                                        'subtotal' => $dSubtotal,
                                        'impuesto' => $dImpuesto,
                                        'total' => $dTotal,
                                        'numren' => $iNumren,
                                        'numund' => $iNumund,
                                        'destino' => 'PRINCIPAL',
                                        'codisb' => $maeprove->codisb,
                                        'ruta' => '',
                                        'pedfiscal' => '0',
                                        'documento' => ''
                                    ]);
                                    $enviado = 1;
                                    log::info("MYSQL2-PEDIDO: ".$maeprove->descripcion);
                                    if ($enviado == 1) {
                                        // AGREGA LOS RENGLONES DEL PEDIDO
                                        $errorinexperado = 0;
                                        foreach ($pedren as $pr) {
                                            try {
                                                if ($codprove == 'TPDMARI') {
                                                    // ESTRUCTURA DE TABLA VERSION VIEJA
                                                    DB::table('pedren')->insert([
                                                        'id' => $idnew, 
                                                        'codprod' => $pr->codprod, 
                                                        'desprod' => $pr->desprod, 
                                                        'cantidad' => $pr->cantidad, 
                                                        'precio' => $pr->precio, 
                                                        'barra' => $pr->barra,
                                                        'tipocatalogo' => 'PRINCIPAL',
                                                        'regulado' => $pr->regulado,
                                                        'tipo' => $pr->tipo,
                                                        'pvp' => $pr->pvp,
                                                        'iva' => $pr->iva,
                                                        'da' => $pr->da,
                                                        'di' => $pr->di,
                                                        'dc' => $pr->dc,
                                                        'pp' => $pr->pp,
                                                        'neto' => $pr->neto,
                                                        'codisb' => $maeprove->codisb,
                                                        'cantdesp' => "0",
                                                        'bulto' => $pr->bulto,
                                                        'ubicacion' => "",
                                                        'packing' => "0",
                                                        'costo' => "0.00",
                                                        'subtotal' => $pr->subtotal
                                                    ]);
                                                } else {
                                                    // ESTRUCTURA DE TABLA NUEVA
                                                    DB::table('pedren')->insert([
                                                        'id' => $idnew, 
                                                        'codprod' => $pr->codprod, 
                                                        'desprod' => $pr->desprod, 
                                                        'cantidad' => $pr->cantidad, 
                                                        'precio' => $pr->precio, 
                                                        'barra' => $pr->barra,
                                                        'tipocatalogo' => 'PRINCIPAL',
                                                        'regulado' => $pr->regulado,
                                                        'tipo' => $pr->tipo,
                                                        'pvp' => $pr->pvp,
                                                        'iva' => $pr->iva,
                                                        'da' => $pr->da,
                                                        'di' => $pr->di,
                                                        'dc' => $pr->dc,
                                                        'pp' => $pr->pp,
                                                        'neto' => $pr->neto,
                                                        'codisb' => $maeprove->codisb,
                                                        'cantdesp' => "0",
                                                        'bulto' => $pr->bulto,
                                                        'ubicacion' => "",
                                                        'packing' => "0",
                                                        'costo' => "0.00",
                                                        'subtotal' => $pr->subtotal,
                                                        "codcli" => $codigo
                                                    ]);
                                                }
                                            } catch (Exception $e) { 
                                                $errorinexperado = -1;
                                                break;
                                            }
                                        }
                                        if ($errorinexperado == -1) {
                                            log::info("ERROR ENVIO (MYSQL3) ");
                                            $mensaje.= $maeprove->descripcion. "-> NO ENVIADO | ";
                                            break;
                                        }
                                        log::info("MYSQL3-PEDREN: ".$maeprove->descripcion);
                                        try {
                                            // UPDATE PEDIDO EN ENVIADO
                                            DB::table('pedido')
                                            ->where('id', '=', $idnew)
                                            ->update(array("estado" => "ENVIADO"));
                                        } catch (Exception $e) { 
                                            log::info("ERROR ENVIO (MYSQL4): ".$e);
                                            $mensaje.= $maeprove->descripcion. "-> NO ENVIADO, ".$e. "| ";
                                            break;
                                        }
                                        log::info("UPD PEDIDO REMOTA (MYSQL4)   -> OK: ".$maeprove->descripcion);
                                        log::info("MYSQL4-UPD   : ".$maeprove->descripcion);
                                    }

                                } catch (Exception $e) { 
                                    log::info("ERROR ENVIO (MYSQL1): ".$e);
                                    $mensaje.= $maeprove->descripcion. "-> NO ENVIADO, ".$e. "| ";
                                    break;
                                }
                                DB::purge('mysql2');
                                Config::set('database.default', 'mysql');
                                DB::reconnect('mysql');
                                if ($enviado == 1) {
                                    // COLOCA LOS RENGLONES EN ENVIADO
                                    if (vGrabarPedRenEnviado($id, $codprove, $idnew) == 0) {
                                        // GUARDAR EL MONTO DEL AHORRO EN EL HISTORIAL
                                        vGrabarAhorroHistorial($codcli, $dMontoAhorro);
                                        log::info("PEDIDO: ".$id. " CODPROV: ".$maeprove->descripcion." ENVIADO OK");
                                    }
                                }
                            }
                            catch (Exception $e) { 
                                $mensaje .= $maeprove->descripcion. "-> NO ENVIADO: ".$e. "| ";
                            }
                            break;
                        case 'SIAD': // CASO COBECA
                            try {
                                $alcabalacb = DB::table('alcabalacb')
                                ->where('id','=',$id)
                                ->where('codprove','=',$codprove)
                                ->first();
                                if (!empty($alcabalacb)) {
                                    DB::table('alcabalacb')
                                    ->where('id','=',$id)
                                    ->where('codprove','=',$codprove)
                                    ->delete();
                                }
                                DB::table('alcabalacb')->insert([
                                    'id' => $id, 
                                    'codcli' => $codcli,
                                    'codprove' => $codprove,
                                    'codsede' => $codsede,
                                    "tabla" => "pedido"
                                ]); 
                                if (vGrabarPedRenEnviado($id, $codprove, "PEND-APROBACION") == 0) {
                                    // GUARDAR EL MONTO DEL AHORRO EN EL HISTORIAL
                                    vGrabarAhorroHistorial($codcli, $dMontoAhorro);
                                    log::info("ALCABALA PEDIDO: ".$id. " AGREGO AL ALCABALA COBECA");
                                    log::info("PEDIDO: ".$id. " CODPROV: ".$maeprove->descripcion." ENVIADO OK");
                                }
                            } catch (Exception $e) { 
                                $mensaje .= $maeprove->descripcion. "-> NO ENVIADO, ".$e. "| ";
                            }
                            break;
                        case "CORREO":
                            log::info("PEDIDO NORMAL: ".$id. " ENVIO POR CORREO");
                            if (EnvioPedidoxCorreo($id, $codprove) == "") {
                                if (vGrabarPedRenEnviado($id, $codprove, "OK-CORREO") == 0) {
                                    // GUARDAR EL MONTO DEL AHORRO EN EL HISTORIAL
                                    vGrabarAhorroHistorial($codcli, $dMontoAhorro);
                                    vCopiaProvPedido($id, $codprove);
                                    log::info("PEDIDO: ".$id. " CODPROV: ".$maeprove->descripcion." ENVIADO OK");
                                }
                            } else {
                                $mensaje .= $maeprove->descripcion. "-> NO ENVIADO | ";
                                log::info("PEDIDO: ".$id. " CODPROV: ".$maeprove->descripcion." ENVIO ERROR");
                            }
                            break;
                        case 'FTP':
                            switch ($tipocata) {
                                case "DRONENA":
                                    try {
                                        $archivo = 'PED'.$codcli.'-'.$codprove.'-'.$id.'.txt';
                                        $rutaOrigen = public_path().'/public/storage/pedidos/'.$archivo;
                                        $fs = fopen($rutaOrigen,"w");
                                        fwrite($fs, "D000 ".$id.PHP_EOL);
                                        foreach ($pedren as $pr) {
                                            fwrite($fs, "D001 ".trim($pr->codprod).PHP_EOL);
                                            fwrite($fs, "D002 ".trim($pr->cantidad).PHP_EOL);
                                            fwrite($fs, "D003 ".trim($pr->desprod).PHP_EOL);
                                        }
                                        fclose($fs);
                                        $pedidoDestinoFtp = "factu01.txt";
                                        $ruta = $maeprove->ftprutapedido;
                                        $rutaDestino = $ruta ."/".$codigo."/";
                                        if ($subcarpeta != "N/A" && $subcarpeta != "")
                                            $rutaDestino = $ruta."/".$subcarpeta."/".$codigo."/";
                                        $error = false;
                                        $cont = 1;
                                        while (true) {
                                            $resp = iExisteArchivoFtp($ftp, $ftpuser, $ftppass, $ftppasv, $rutaDestino, $pedidoDestinoFtp);
                                            if ($resp == 0) {
                                                $exito = true;
                                                break;
                                            }
                                            if ($resp == -1) {
                                                $error = true;
                                                break;
                                            }
                                            $cont++;
                                            $ped = "00".$cont;
                                            $ped = substr($ped,strlen($ped)-2,2);
                                            $pedidoDestinoFtp = "factu".$ped.".txt";
                                        }
                                        if ($error)  {
                                            $mensaje .= $maeprove->descripcion. "-> NO ENVIADO | ";
                                            break;
                                        } else {
                                            log::info("PROVEEDOR DRONENA");
                                            log::info("FTPDESTINO  : ".$pedidoDestinoFtp);
                                            log::info("ftp         : ".$ftp);
                                            log::info("ftpuser     : ".$ftpuser);
                                            log::info("ftppass     : ".$ftppass);
                                            log::info("ftppasv     : ".$ftppasv);
                                            log::info("rutaOrigen  : ".$rutaOrigen);
                                            log::info("rutaDestino : ".$rutaDestino);
                                            $resp = iEnviarArchivoFtp($ftp, $ftpuser, $ftppass, $ftppasv, $rutaOrigen, $rutaDestino, "Pedido.tmp");
                                            if ($resp == 0) {
                                                log::info("ENVIADO     : Pedido.tmp");
                                                $resp = iRenameArchivoFtp($ftp, $ftpuser, $ftppass, $ftppasv, $rutaDestino, "Pedido.tmp", $pedidoDestinoFtp);
                                                if ($resp == 0 ) {
                                                    log::info("RENAME       : Pedido.tmp->".$pedidoDestinoFtp);
                                                    $resp = vGrabarPedRenEnviado($id, $codprove, "OK->FTP");
                                                    if ($resp == -1) {
                                                        log::info("RECONEXION   : INICIO");
                                                        DB::purge('mysql');
                                                        Config::set('database.default', 'mysql');
                                                        DB::reconnect('mysql');
                                                        if (vGrabarPedRenEnviado($id, $codprove, "OK->FTP") == 0) {
                                                            log::info("RECONEXION  : OK");
                                                        }
                                                    }
                                                    // GUARDAR EL MONTO DEL AHORRO EN EL HISTORIAL
                                                    vGrabarAhorroHistorial($codcli, $dMontoAhorro);
                                                    log::info("PEDIDO      : ".$id. " CODPROV: ".$maeprove->descripcion." ENVIADO OK");
                                                } else {        
                                                    $mensaje .= $maeprove->descripcion. "-> NO ENVIADO | ";
                                                    break;
                                                }                  
                                            } else {
                                                $mensaje.=$maeprove->descripcion. "-> NO ENVIADO | ";
                                                break;
                                            }
                                        }
                                    } catch (Exception $e) { 
                                        log::info("ERROR ENVIO (DRONENA): ".$e);
                                        $mensaje.=$maeprove->descripcion. "-> NO ENVIADO, ".$e. "| ";
                                    }
                                    break;
                                case 'DROLANCA':
                                    try {
                                        $ftpuser = $tablaclieprove->usuario; 
                                        $ftppass = $tablaclieprove->clave; 
                                        $archivo = 'PED'.$codcli.'-'.$codprove.'-'.$id.'.txt';
                                        $num = "00000".$id;
                                        $num = substr($num,strlen($num)-5,5);
                                        $numpedido = $codigo.$num;
                                        $nompedido = $codigo.$num.".txt";
                                        $rutaOrigen = public_path().'/public/storage/pedidos/'.$archivo;
                                        $fs = fopen($rutaOrigen,"w");
                                        foreach ($pedren as $pr) {
                                            $traza = $codigo .";". $pr->desprod .";". $pr->cantidad .";". $pr->codprod .";". $numpedido .";". $codsede .";";
                                            fwrite($fs, $traza.PHP_EOL);
                                        }
                                        fclose($fs);
                                        $pedidoDestinoFtp = $nompedido;
                                        $rutaDestino = $maeprove->ftprutapedido."/";
                                        log::info("PROVEEDOR DROLANCA");
                                        log::info("ftp         : ".$ftp);
                                        log::info("ftpuser     : ".$ftpuser);
                                        log::info("ftppass     : ".$ftppass);
                                        log::info("ftppasv     : ".$ftppasv);
                                        log::info("rutaOrigen  : ".$rutaOrigen);
                                        log::info("rutaDestino : ".$rutaDestino);
                                        $resp = iEnviarArchivoFtp($ftp, $ftpuser, $ftppass, $ftppasv, $rutaOrigen, $rutaDestino, $pedidoDestinoFtp);
                                        if ($resp == 0) {
                                            $resp = vGrabarPedRenEnviado($id, $codprove, "OK->FTP");
                                            if  ($resp == -1) {
                                                log::info("RECONEXION  : INICIO");
                                                DB::purge('mysql');
                                                Config::set('database.default', 'mysql');
                                                DB::reconnect('mysql');
                                                if (vGrabarPedRenEnviado($id, $codprove, "OK->FTP") == 0) {
                                                    log::info("RECONEXION  : OK");
                                                }
                                            }
                                            log::info("ENVIADO      : ".$pedidoDestinoFtp);
                                            // GUARDAR EL MONTO DEL AHORRO EN EL HISTORIAL
                                            vGrabarAhorroHistorial($codcli, $dMontoAhorro);
                                            log::info("PEDIDO       : ".$id. " CODPROV: ".$maeprove->descripcion." ENVIADO OK");
                                        } else {
                                           $mensaje .= $maeprove->descripcion. "-> NO ENVIADO | ";
                                           break;
                                        }
                                    } catch (Exception $e) { 
                                        log::info("ERROR ENVIO (DROLANCA): ".$e);
                                        $mensaje.=$maeprove->descripcion. "-> NO ENVIADO, ".$e. "| ";
                                    }
                                    break;
                                case 'DROCERCA':
                                    try {
                                        $ftpuser = $tablaclieprove->usuario; 
                                        $ftppass = $tablaclieprove->clave; 
                                        $codigo = $tablaclieprove->codigo;
                                        $sede = substr($codsede, 0, 2);
                                        //00U42P005879.txt.bak

                                        $archivo = 'PED'.$codcli.'-'.$codprove.'-'.$id.'.txt';
                                        $num = "000000".$id;
                                        $num = substr($num,strlen($num)-6,6);
                                        $numpedido = $codigo.$num;
                                        $nompedido = $codigo.'P'.$num.$sede.".txt";
                                        $rutaOrigen = public_path().'/public/storage/pedidos/'.$archivo;
                                        $fs = fopen($rutaOrigen,"w");
                                        foreach ($pedren as $pr) {
                                            $traza = $pr->codprod .";". $pr->desprod .";". $pr->cantidad .";". $pr->precio .";". $codsede .";". $codigo .";";
                                            fwrite($fs, $traza.PHP_EOL);
                                        }
                                        fclose($fs);
                                        $pedidoDestinoFtp = $nompedido;
                                        $rutaDestino = $maeprove->ftprutapedido."/";
                                        log::info("PROVEEDOR DROCERCA");
                                        log::info("ftp         : ".$ftp);
                                        log::info("ftpuser     : ".$ftpuser);
                                        log::info("ftppass     : ".$ftppass);
                                        log::info("ftppasv     : ".$ftppasv);
                                        log::info("rutaOrigen  : ".$rutaOrigen);
                                        log::info("rutaDestino : ".$rutaDestino);
                                        log::info("nompedido   : ".$nompedido);
                                        $resp = iEnviarArchivoFtp($ftp, $ftpuser, $ftppass, $ftppasv, $rutaOrigen, $rutaDestino, $pedidoDestinoFtp);
                                        if ($resp == 0) {
                                            $resp = vGrabarPedRenEnviado($id, $codprove, "OK->FTP");
                                            if  ($resp == -1) {
                                                log::info("RECONEXION  : INICIO");
                                                DB::purge('mysql');
                                                Config::set('database.default', 'mysql');
                                                DB::reconnect('mysql');
                                                if (vGrabarPedRenEnviado($id, $codprove, "OK->FTP") == 0) {
                                                    log::info("RECONEXION   : OK");
                                                }
                                            }
                                            log::info("ENVIADO      : ".$pedidoDestinoFtp);
                                            // GUARDAR EL MONTO DEL AHORRO EN EL HISTORIAL
                                            vGrabarAhorroHistorial($codcli, $dMontoAhorro);
                                            log::info("PEDIDO      : ".$id. " CODPROV: ".$maeprove->descripcion." ENVIADO OK");
                                        } else {
                                           $mensaje .= $maeprove->descripcion. "-> NO ENVIADO | ";
                                           break;
                                        }
                                    } catch (Exception $e) { 
                                        log::info("ERROR ENVIO (DROCERCA): ".$e);
                                        $mensaje.=$maeprove->descripcion. "-> NO ENVIADO, ".$e. "| ";
                                    }
                                    break;
                            }
                    }
                }
            } 
            if ($marcar > 0) {
                vUpdateEstadoPedido($id);
            }
        } catch (Exception $e) {
            $mensaje = $e;
        }
        if ($mensaje == "")
            session()->flash('message', 'Pedido '.$id.' enviado satisfactoriamente');
        else 
            session()->flash('error', $mensaje);
        return Redirect::to('/pedido');
    }

    public function guardar(Request $request, $id) {
        set_time_limit(300);
        $mensaje = "";
        try {

            DB::table('pedido')
            ->where('id', '=', $id)
            ->update(array("estado" => "GUARDADO"));
            DB::table('pedren')
            ->where('id', '=', $id)
            ->update(array("estado" => "GUARDADO"));

        } catch (Exception $e) {
            $mensaje = $e;
        }
        if ($mensaje == "")
            session()->flash('message', 'Pedido '.$id.' guardado satisfactoriamente');
        else 
            session()->flash('error', $mensaje);
        return Redirect::to('/pedido');
    }
 
    public function pedidopdf($id) {
        set_time_limit(300);
        $s1 = explode('-', $id );
        $id = $s1[0];
        $codprove = $s1[1];

        $fechaHoy = date('j-m-Y');
        $FechaPedido = substr($fechaHoy, 0, 10);
        $moneda = Session::get('moneda', 'BSS');

        $tituloppal = "PEDIDO MAESTRO";
        $maeprove = null;
        if ($codprove != "MAESTRO")  {
            $maeprove = LeerProve($codprove);
            $tituloppal = $maeprove->descripcion; 
            $tabla2 = DB::table('pedren')
            ->where('id','=',$id)
            ->where('codprove','=',$codprove)
            ->get();
        } else {
            $tabla2 = DB::table('pedren')
            ->where('id','=',$id)
            ->get();
        }

        // TABLA DE PEDIDO
        $tabla = DB::table('pedido')
        ->where('id','=',$id)
        ->first();

        $titulo = "PEDIDO: ".$id;
        $subtitulo = $tabla->nomcli;
        $codcli = $tabla->codcli;
      
        // TABLA DE RENGLONES DE PEDIDO
        $cliente = DB::table('maecliente')
        ->where('codcli','=',$codcli)
        ->first();

        // CALCULO DE TOTALES DEL PEDIDO
        $dSubrenglon = 0.00;
        $dDecuento = 0.00;
        $dSubtotal = 0.00;
        $dImpuesto = 0.00;
        $dTotal = 0.00;
        foreach ($tabla2 as $pr) {

            $factor = RetornaFactorCambiario($pr->codprove, $moneda);
            $precio = $pr->precio/$factor;

            $neto = CalculaPrecioNeto($precio, $pr->da, $pr->di, $pr->dc, $pr->pp, $pr->dp);
            $subtotal = $neto * $pr->cantidad;
            if ($pr->iva > 0) {
                $dImpuesto = $dImpuesto + (($subtotal * $pr->iva)/100);
            }
            $dSubtotal = $dSubtotal + $subtotal;
            $dSubrenglon = $dSubrenglon + ($pr->precio * $pr->cantidad);
        }
        $dDecuento = $dSubrenglon - $dSubtotal;
        $dTotal = $dSubtotal + $dImpuesto;

        $data = [
            "tituloppal" => $tituloppal,
            "titulo" => $titulo,
            "subtitulo" => $subtitulo,
            "tabla" => $tabla, 
            "tabla2" => $tabla2, 
            "impuesto" => $dImpuesto,
            "total" => $dTotal,
            "maeprove" => $maeprove,
            "codprove" => $codprove,
            "cliente" => $cliente,
            "factor" => $factor,
            "moneda" => $moneda,
            "cfg" => DB::table('maecfg')->first(),
            "id" => $id,
            "codcli" => $codcli
        ];
        return PDF::loadView('layouts.rptpedido', $data)
        ->download('pedido_'.$codprove.'_'.$codcli.'.pdf');
    }

    public function deleteAll(Request $request) {
        set_time_limit(300);
        $id = $request->get('id');
        $tpactivo = trim($request->get('tpactivo'));
        DB::table('pedren')
        ->where('id','=',$id)
        ->where('marcado','=',1)
        ->delete();
        CalculaTotalesPedido($id);
        if ($tpactivo != "") {
            $pedren = DB::table('pedren')
            ->selectRaw('count(*) as contador')
            ->where('id','=',$id)
            ->where('codprove','=',$tpactivo)
            ->first();
            if ($pedren) {
                $contador = $pedren->contador;
                if ($contador == 0) 
                    return Redirect::to('/pedido/'.$id.'/edit');
            }
        }
        return Redirect::to('/pedido/'.$id.'/edit');
    }

    public function marcaritem(Request $request) {
        $item = $request->get('item');
        $marcar = $request->get('marcar');
        if ($marcar == "") {
            $pedren = DB::table('pedren')
            ->where('item', '=', $item)
            ->first();
            if ($pedren) {
                $marcado = $pedren->marcado;
                if ($marcado == 0)
                    $marcado = 1;
                else
                    $marcado = 0;
                DB::table('pedren')
                ->where('item', '=', $item)
                ->update(array("marcado" => $marcado));
            }
        } else {
            DB::table('pedren')
            ->where('item', '=', $item)
            ->update(array("marcado" => $marcar));
        }
        return response()->json(['msg' => '' ]);
    }

 }
