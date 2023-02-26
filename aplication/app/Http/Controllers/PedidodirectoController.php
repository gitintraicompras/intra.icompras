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

class PedidodirectoController extends Controller
{
    public function __construct() {
    	$this->middleware('auth');
    }

    public function index(Request $request) {
    	if ($request) {
            $filtro = trim($request->get('filtro'));
            $codcli = sCodigoClienteActivo();
            vEliminarPedidoBlanco($codcli);
            $tipedido = "D";
            $tabla=DB::table('pedido')
            ->where('codcli','=',$codcli)
            ->where('tipedido','=',$tipedido)
            ->where(function ($q) use ($filtro) {
                $q->where('id','LIKE','%'.$filtro.'%')
                ->orwhere('estado','LIKE','%'.$filtro.'%')
                ->orwhere('origen','LIKE','%'.$filtro.'%')
                ->orwhere('fecha','LIKE','%'.date('Y-m-d', strtotime($filtro)).'%')
                ->orwhere('fecenviado','LIKE','%'.date('Y-m-d', strtotime($filtro)).'%');
            })
            ->orderBy('id','desc')
            ->paginate(100);
            $ped = DB::table('pedido')
            ->selectRaw('count(*) as contador')
            ->where('codcli','=',$codcli)
            ->where('tipedido','=',$tipedido)
            ->where(function ($q) use ($filtro) {
                $q->where('id','LIKE','%'.$filtro.'%')
                ->orwhere('estado','LIKE','%'.$filtro.'%')
                ->orwhere('origen','LIKE','%'.$filtro.'%')
                ->orwhere('fecha','LIKE','%'.date('Y-m-d', strtotime($filtro)).'%')
                ->orwhere('fecenviado','LIKE','%'.date('Y-m-d', strtotime($filtro)).'%');
            })
            ->first();
            $subtitulo = "PEDIDOS DIRECTO (".$ped->contador.")";
            return view('isacom.pedidodirecto.index' ,["menu" => "Pedidos",
                                                       "cfg" => DB::table('maecfg')->first(),
                                                       "tabla" => $tabla,
                                                       "filtro" => $filtro,
                                                       "codcli" => $codcli,
                                                       "subtitulo" => $subtitulo]);
    	}
    }

	public function show($id) {

        $s1 = explode('-', $id );
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

        $subtitulo ="CONSULTA DE PEDIDO DIRECTO (".$tabla->marca." - REPOSICION: ".$tabla->reposicion.")";
        return view('isacom.pedidodirecto.show',["menu" => "Pedidos",
                                                 "cfg" => DB::table('maecfg')->first(),
                                                 "tabla" => $tabla,
                                                 "tabla2" => $tabla2,
                                                 "subtitulo" => $subtitulo,
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
        return Redirect::to('/pedidodirecto');
    }

	public function destroy(Request $request, $id) {
        try {
            DB::beginTransaction();
            $accion = $request->accion;
            $codcli = $request->codcli;
            if ($accion == 'TOMAR') {

                DB::table('pedido')
                ->where('estado','=','NUEVO')
                ->where('codcli','=',$codcli)
                ->update(array("estado" => "ABIERTO"));

                DB::table('pedido')
                ->where('id','=',$id)
                ->update(array("estado" => "NUEVO"));
                session()->flash('message', 'Pedido '.$id.' tomado satisfactoriamente');

            } else {
                $codcli = sCodigoClienteActivo();
                $pedido = DB::table('pedido')
                ->where('id','=',$id)
                ->first();
                if ($pedido) {
                    if ($pedido->tipedido == "D") {
                        $idpedgrupo = $pedido->idpedgrupo;
                        $ped = DB::table('pedido')
                        ->selectRaw('count(*) as contitem')
                        ->where('idpedgrupo','=', $idpedgrupo)
                        ->first();
                        if ($ped->contitem == 1) {
                            DB::table('pedgrupo')
                            ->where('id','=',$idpedgrupo)
                            ->delete();
                        }
                    }
                }
                DB::table('pedren')
                ->where('id','=',$id)
                ->delete();
           	    DB::table('pedido')
        		->where('id','=',$id)
        		->delete();
                session()->flash('message', 'Pedido '.$id.' eliminado satisfactoriamente');
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            session()->flash('error', $e);
        }
		return Redirect::to('/pedidodirecto');
	}

    public function edit(Request $request, $id) {
        $filtro=trim($request->get('filtro'));
        $btnguardar = 0;
        $codcli = sCodigoClienteActivo();
        $usuario = Auth::user()->name;
        $tipo = Auth::user()->tipo;
        if ($tipo == 'C') {
            $gruporen = DB::table('gruporen')
            ->where('status','=','ACTIVO')
            ->where('codcli','=',$codcli)
            ->first();
            if ($gruporen)
                $btnguardar = 1;
        }
        if ($tipo == 'G')
            $btnguardar = 1;

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
        ->where(function ($q) use ($filtro) {
            $q->where('desprod','LIKE','%'.$filtro.'%')
            ->orwhere('barra','LIKE','%'.$filtro.'%')
            ->orwhere('codprove','LIKE','%'.$filtro.'%');
        })
        ->orderBy('item','asc')
        ->get();

        // CUENTA LA CANTIAD DE ITEM DEL PEDIDO ABIERTO
        $reg = DB::table('pedren')
        ->where('id','=', $id)
        ->selectRaw('count(*) as contitem')
        ->first();
        $contItem = $reg->contitem;

        $subtitulo ="EDITAR PEDIDO DIRECTO (".$tabla->marca." - REPOSICION: ".$tabla->reposicion.")";
        return view("isacom.pedidodirecto.edit", ["menu" => "Pedidos",
                                           "cfg" => DB::table('maecfg')->first(),
                                           "id" => $id,
                                           "codcli" => $codcli,
                                           "marca" => $tabla->marca,
                                           "tabla" => $tabla,
                                           "tabla2" => $tabla2,
                                           "subtitulo" => $subtitulo,
                                           "cliente" => $cliente,
                                           "filtro" => $filtro,
                                           "btnguardar" => $btnguardar,
                                           "contItem" => $contItem ]);
    }

    public function create() {
        $codcli = sCodigoClienteActivo();
        $tipedido = (Auth::user()->userPedDirecto == 1 ) ? "D" : "N";
        $id = iIdUltPedAbierto($codcli, $tipedido);
        if ( $id > 0) {
             return Redirect::to('/pedido/catalogo/C');
        }
        $subtitulo = "PEDIDO DIRECTO NUEVO";
        $marca = DB::table('marca')
        ->orderBy("descrip","asc")
        ->get();
        if (Auth::user()->tipo == "G") {
            $codgrupo = Auth::user()->codcli;
            $grpren = DB::table('gruporen')
            ->where('status','=', 'ACTIVO')
            ->where('codcli','=', $codcli)
            ->where('id','=', $codgrupo)
            ->first();
            if ($grpren) {
                $pedgrupo = DB::table('pedgrupo')
                ->where('estado','=', 'NUEVO')
                ->where('codgrupo','=', $grpren->id)
                ->get();
            }
        } else {
            $grpren = collect();
            $pedgrupo = collect();
        }
        return view("isacom.pedidodirecto.create",["menu" => "Pedidos",
                                                   "marca" => $marca,
                                                   "grpren" => $grpren,
                                                   "pedgrupo" => $pedgrupo,
                                                   "cfg" => DB::table('maecfg')->first(),
                                                   "subtitulo" => $subtitulo]);
    }

    public function store(Request $request) {
        set_time_limit(500);
        $procesar = 0;
        $codcli = sCodigoClienteActivo();
        $reposicion = $request->reposicion;
        $codmarca = $request->codmarca;
        $idpedgrupo = $request->idpedgrupo;
        $cantsug = $request->cantsug;
        $usuarioCreador = Auth::user()->email;
        $pedido = DB::table('pedido')
        ->where('codcli', '=', $codcli)
        ->where('marca','=',$codmarca)
        ->where('estado','=','NUEVO')
        ->where('tipedido','=','D')
        ->orderBy('id','desc')
        ->first();
        if ($pedido) {
            return back()->with('warning', 'Ya existe un pedido abierto para esa marca!!!');
        }
        //dd($codmarca ."-". $reposicion ."-". $idpedgrupo);
        $id = iCrearPedidoNuevo('', 'D', $codmarca, $reposicion, $idpedgrupo);
        $tabla = "inventario_".$codcli;
        if (VerificaTabla($tabla)) {
            $cliente = DB::table('maecliente')
            ->where('codcli','=',$codcli)
            ->first();
            $invent = DB::table($tabla)
            ->where('barra', '!=', '')
            ->where('cuarentena', '=', '0')
            ->where('marca','=',$codmarca)
            ->orderBy('desprod','asc')
            ->get();
            foreach ($invent as $inv) {
                $cantran = verificarProdTransito($inv->barra, $codcli, "");
                $pedir = intval(($inv->vmd * $reposicion) - $cantran);
                if ($pedir == 0) {
                    if ($cantsug > 0)
                        $pedir = $cantsug;
                }
                $pedir = $pedir - $inv->cantidad;
                $subtotal = $inv->costo * $pedir;
                if ($pedir > 0) {
                    $procesar = 1;
                    DB::table('pedren')->insert([
                        'id' => $id,
                        'codprod' => $inv->codprod,
                        'desprod' => $inv->desprod,
                        'cantidad' => $pedir,
                        'precio' => $inv->costo,
                        'barra' => $inv->barra,
                        'codprove' => $codmarca,
                        'regulado' => 'N',
                        'tipo' => 'M',
                        'pvp' => $inv->costo,
                        'iva' => $inv->iva,
                        'da' => 0.00,
                        'di' => 0.00,
                        'dc' => 0.00,
                        'pp' => 0.00,
                        'neto' => $inv->costo,
                        'codcli' => $codcli,
                        'ahorro' => 0.00,
                        'subtotal' => $subtotal,
                        'aprobacion' => "NO",
                        'estado' => "NUEVO",
                        "fecha" => date("Y-m-d H:i:s"),
                        "fecenviado" => date("Y-m-d H:i:s"),
                        "ranking" => "0-0",
                        "bulto" => $inv->bulto,
                        "usuarioCreador" => $usuarioCreador,
                        "tprnk1" => ''
                    ]);
                }
            }
            if ($procesar > 0)
                CalculaTotalesPedido($id);
            else
                return back()->with('warning', 'No encontro productos con los parametros actuales!');
        } else {
            return back()->with('warning', 'Tabla inventario no existe!');
        }
        return Redirect::to('/pedidodirecto');
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
        ->get();

        $provs = TablaMaecliproveActiva("");
        $tabla = DB::table('maeprodimg')
        ->where('barra','=',$barra)
        ->first();

        $tpmaestra = DB::table('tpmaestra')
        ->where('barra','=',$barra)
        ->first();

        $maecatalogo = DB::table('maecatalogo')
        ->where('barra','=',$barra)
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
                                             "barra" => $barra]);
    }

    public function agregar($idx) {
        $subtitulo = "AGREGAR";
        $s1 = explode('_', $idx);
        $id = $s1[0];
        $codcli = $s1[1];
        $cant = $s1[2];
        $barra = $s1[3];
        $marca = $s1[4];
        $usuarioCreador = Auth::user()->email;
        $cant = ($cant == '0' || $cant == '' ) ? '1' : $cant;
        $tabla = 'inventario_'.$codcli;
        if (VerificaTabla($tabla)) {
            $prod = DB::table($tabla)
            ->where('barra','=',$barra)
            ->first();
            if (!empty($prod)) {
                $precio = $prod->costo;
                $codprod = $prod->codprod;
                $desprod = $prod->desprod;
                $regulado = $prod->regulado;
                $tipo = $prod->tipo;
                $iva = $prod->iva;
                $bulto = $prod->bulto;
                $pedren = DB::table('pedren')
                ->where('id','=',$id)
                ->where('codprove','=',$marca)
                ->where('barra','=',$barra)
                ->first();
                if (!empty($pedren)) {
                    return back()->with('warning', 'Barra: '.$barra.' Este producto ya existe en el pedido!!!');
                }
                DB::table('pedren')->insert([
                    'id' => $id,
                    'codprod' => $codprod,
                    'desprod' => $desprod,
                    'cantidad' => $cant,
                    'precio' => $precio,
                    'barra' => $barra,
                    'codprove' => $marca,
                    'regulado' => $regulado,
                    'tipo' => $tipo,
                    'pvp' => $precio,
                    'iva' => $iva,
                    'da' => 0.00,
                    'di' => 0.00,
                    'dc' => 0.00,
                    'pp' => 0.00,
                    'neto' => $precio,
                    'codcli' => $codcli,
                    'ahorro' => 0.00,
                    'subtotal' => $precio * $cant,
                    'aprobacion' => "NO",
                    'estado' => "NUEVO",
                    "fecha" => date("Y-m-d H:i:s"),
                    "fecenviado" => date("Y-m-d H:i:s"),
                    "bulto" => $bulto,
                    "usuarioCreador" => $usuarioCreador,
                    "tprnk1" => '0',
                    "ranking" => '0-0'
                ]);
                CalculaTotalesPedido($id);
            }
        }
        return Redirect::to('/pedidodirecto/'.$id.'/edit');
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
        $codcli = sCodigoClienteActivo();
        $usuario = Auth::user()->email;

        $mensaje = "";
        try {
            DB::beginTransaction();
            if ($tipedido == 'D') {
                // PEDIDOS DIRECTOS
                log::info("PEDIDO DIRECTO: ".$id. " ENVIO POR CORREO");
                $pedcorreo = $request->get('pedcorreo');
                $formato = $request->get('formato');
                $marca = $request->get('marca');
                if (EnvioPedidoDirectoxCorreo($id, $pedcorreo, $formato, $marca) == "") {
                    if (vGrabarPedRenEnviado($id, $marca, "OK-CORREO") == 0) {
                        // GUARDAR EL MONTO DEL AHORRO EN EL HISTORIAL
                        log::info("PEDIDO DIRECTO: ".$id. " MARCA: ".$marca." ENVIADO OK");
                        vUpdateEstadoPedido($id);
                    }
                } else {
                    $mensaje .= "ID: ".$id.' - MARCA: '.$marca. "-> NO ENVIADO ";
                    log::info("PEDIDO DIRECTO: ".$id. " MARCA: ".$marca." ENVIO ERROR");
                }
            } else {
                // PEDIDOS A PROVEEDORES
                $provs = TablaMaecliproveActiva("");
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
                foreach ($arrayProv as $codprove) {
                    $check = ($request->get('check-'.$codprove) == 'on' ? true : false);
                    if ($check) {

                        $maeprove = LeerProve($codprove);
                        if (is_null($maeprove)) {
                            log::info("ENVIO -> ID: ".$id." CODPROV: ".$codprove.' WARNING: INEXPERADO(1)');
                            continue;
                        }
                        $tablaclieprove = LeerClieProve($codprove, "");

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

                        $pedido = DB::table('pedido')
                        ->where('id','=', $id)
                        ->first();
                        if (empty($pedido)) {
                            log::info("ENVIO -> ID: ".$id." CODPROV: ".$codprove.' WARNING: INEXPERADO(2)');
                            continue;
                        }

                        $pedren = DB::table('pedren')
                        ->where('id','=', $id)
                        ->where('estado','=', 'NUEVO')
                        ->where('codprove','=', $codprove)
                        ->get();
                        if (empty($pedren)) {
                            log::info("ENVIO -> ID: ".$id." CODPROV: ".$codprove.' WARNING: INEXPERADO(3)');
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

                                    // INICIO DE CONEXION MYSQL REMOTA
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
                                        log::info("CONEXION REMOTA (MYSQL1)     -> OK: ".$maeprove->descripcion);
                                    } catch (Exception $e) {
                                        log::info("ERROR ENVIO (MYSQL1): ".$e);
                                        $mensaje.= $maeprove->descripcion. "-> NO ENVIADO, ".$e. "| ";
                                        break;
                                    }
                                    $enviado = 0;
                                    try {
                                        // VERIFICA SI EXISTE UN ID REPETIDO
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
                                        log::info("ENVIO PEDIDO REMOTA (MYSQL2) -> OK: ".$maeprove->descripcion);
                                    } catch (Exception $e) {
                                        log::info("ERROR ENVIO (MYSQL2): ".$e);
                                        $mensaje.= $maeprove->descripcion. "-> NO ENVIADO, ".$e. "| ";
                                        break;
                                    }
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
                                        log::info("ENVIO PEDREN REMOTA (MYSQL3) -> OK: ".$maeprove->descripcion);
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
                                        // FINAL DE CONEXION MYSQL REMOTA
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
                                    ->first();
                                    if (!empty($alcabalacb)) {
                                        DB::table('alcabalacb')
                                        ->where('id','=',$id)
                                        ->delete();
                                    }
                                    DB::table('alcabalacb')->insert([
                                        'id' => $id,
                                        'codcli' => $codcli,
                                        'codprove' => $codprove,
                                        'codsede' => $codsede
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
                                            //$rutaOrigen = "C:/xampp74/htdocs/isbsistema/public/storage/descargas/".$archivo;

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
                                                    log::info("ENVIADO      : Pedido.tmp");
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
                                                                log::info("RECONEXION   : OK");
                                                            }
                                                        }
                                                        // GUARDAR EL MONTO DEL AHORRO EN EL HISTORIAL
                                                        vGrabarAhorroHistorial($codcli, $dMontoAhorro);
                                                        log::info("PEDIDO       : ".$id. " CODPROV: ".$maeprove->descripcion." ENVIADO OK");
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
                                            $archivo = 'PED'.$codcli.'-'.$codprove.'-'.$id.'.txt';
                                            $num = "000000".$id;
                                            $num = substr($num,strlen($num)-6,6);
                                            $numpedido = $codigo.$num;
                                            $nompedido = $codigo.'P'.$num.".txt";
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
                                            log::info("ERROR ENVIO (DROCERCA): ".$e);
                                            $mensaje.=$maeprove->descripcion. "-> NO ENVIADO, ".$e. "| ";
                                        }
                                        break;
                                }
                        }
                    }
                }
                vUpdateEstadoPedido($id);
            }
            DB::commit();
        } catch (Exception $e) {
            $mensaje = $e;
            DB::rollBack();
        }

        if ($mensaje == "")
            session()->flash('message', 'Pedido '.$id.' enviado satisfactoriamente');
        else
            session()->flash('error', $mensaje);

        return Redirect::to('/pedidodirecto');
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
        return Redirect::to('/pedidodirecto');
    }

    public function pedidopdf($id) {

        $s1 = explode('-', $id );
        $id = $s1[0];
        $codprove = $s1[1];

        //dd($id .'-'.$codprove);

        $fechaHoy = date('j-m-Y');
        $FechaPedido = substr($fechaHoy, 0, 10);
        $tabla2 = DB::table('pedren')
        ->where('id','=',$id)
        ->get();

        // TABLA DE PEDIDO
        $tabla = DB::table('pedido')
        ->where('id','=',$id)
        ->first();

        $titulo = "PEDIDO: ".$id;
        $subtitulo = $tabla->nomcli;
        $codcli = $tabla->codcli;
        $moneda = Session::get('moneda', 'BSS');
        $factor = RetornaFactorCambiario('', $moneda);
        if ($moneda == "USD") {
            if ($tabla->factor != 1)
                $factor = $tabla->factor;
        }

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
            "titulo" => $titulo,
            "subtitulo" => $subtitulo,
            "tabla" => $tabla,
            "tabla2" => $tabla2,
            "impuesto" => $dImpuesto,
            "total" => $dTotal,
            "moneda" => $moneda,
            "factor" => $factor,
            "codprove" => $codprove,
            "cliente" => $cliente,
            "cfg" => DB::table('maecfg')->first(),
            "id" => $id
        ];
        return PDF::loadView('layouts.rptpedido', $data)
        ->download('pedido_'.$codprove.'_'.$codcli.'.pdf');
    }

    public function deleteAll(Request $request) {
        set_time_limit(300);
        $id = $request->get('id');
        DB::table('pedren')
        ->where('id','=',$id)
        ->where('marcado','=',1)
        ->delete();
        CalculaTotalesPedido($id);
        return Redirect::to('/pedidodirecto/'.$id.'/edit');
    }

    public function marcaritem(Request $request) {
        $item = $request->get('item');
        $marcar = $request->get('marcar');


        //log::info($item .'-'. $marcar);

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

    public function obtenerTablaCliMaestra(Request $request) {
        $codcli = $request->get('codcli');
        $filtro = $request->get('filtro');
        $resp = "";
        $tabla = 'inventario_'.$codcli;
        if (VerificaTabla($tabla)) {
            $tabla = DB::table($tabla)
            ->select('desprod', 'barra', 'marca')
            ->where(function ($q) use ($filtro) {
                  $q->where('barra','LIKE','%'.$filtro.'%')
                  ->orwhere('desprod','LIKE','%'.$filtro.'%')
                  ->orwhere('marca','LIKE','%'.$filtro.'%');
            })
            ->orderBy("desprod","asc")
            ->get();
            if ($tabla) {
                $resp = $tabla;
            }
        }
        return response()->json(['resp' => $resp ]);
    }

 }
