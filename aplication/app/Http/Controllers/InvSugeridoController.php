<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;
use DB;
use Barryvdh\DomPDF\Facade as PDF;

   
class InvSugeridoController extends Controller
{
    public function __construct() {
    	$this->middleware('auth');
    }

    public function index(Request $request) {
        $filtro=trim($request->get('filtro'));
        $codcli = sCodigoClienteActivo();
        $sugerido = DB::table("sugerido")
        ->where('codcli','=',$codcli)
        ->where(function ($q) use ($filtro) {
            $q->where('barra','LIKE','%'.$filtro.'%')
            ->orwhere('desprod','LIKE','%'.$filtro.'%')
            ->orwhere('codprod','LIKE','%'.$filtro.'%');
        })
        ->orderBy('desprod','asc')
        ->paginate(100);
        $subtitulo = "SUGERIDO";
        $subtitulo2 = "SIN SUGERIDO";
        if ($sugerido->count()>0) {
        
            $sug = DB::table("sugerido")
            ->where('codcli','=',$codcli)
            ->selectRaw('count(*) as contador')
            ->first(); 
            $contador =number_format($sug->contador,0);

            $fecha = date('d-m-Y H:i', strtotime($sugerido[0]->fecha));
            $subtitulo2 = "SUGERIDO (FECHA: ".$fecha.", RENGLONES: ".$contador.")";
        }
        return view('isacom.invsugerido.index' ,["menu" => "Inventario",
                                                 "cfg" => DB::table('maecfg')->first(),
                                                 "codcli" => $codcli,
                                                 "sugerido" => $sugerido,
                                                 "filtro" => $filtro,
                                                 "subtitulo" => $subtitulo,
                                                 "subtitulo2" => $subtitulo2 ]);
    }
 
    public function destroy($id) {
        try {
            DB::table('sugerido')
            ->where('codcli','=',$id)
            ->delete();
        } catch (Exception $e) {
            return Redirect::back()->with('error', 'Sugerido '.$id.' '.$e);
        }
        return Redirect::back()->with('message', 'Sugerido '.$id.' eliminado satisfactoriamente');
    }

    public function deleprod(Request $request, $id) {
        $regs = DB::table('sugerido')
        ->where('id','=',$id)
        ->delete();
        return Redirect::back()->with('message', 'Producto eliminado satisfactoriamente');
    }

    public function descargar(Request $request) {
        set_time_limit(500); 
        $codcli = sCodigoClienteActivo();
        $cfg = DB::table('maecfg')->first();

        $titulo = "SUGERIDO";
        $sugerido = DB::table('sugerido')
        ->where('codcli','=',$codcli)
        ->get();

        if ($sugerido)
            $fecha = date('d-m-Y', strtotime($sugerido[0]->fecha)).' - RENGLONES: '.number_format($sugerido->count(), 0, ',', '.');

        $cliente = DB::table('maecliente')
        ->where('codcli','=',$codcli)
        ->first();

        if ($cliente)
            $subtitulo = $cliente->nombre;
      
        $data = [
            "menu" => "Inventario",
            "tabla2" => $sugerido, 
            "cfg" => $cfg,
            "fecha" => $fecha,
            "titulo" => $titulo,
            "subtitulo" => $subtitulo,
            "cliente" => $cliente
        ];

        return PDF::loadView('layouts.rptsug', $data)
        ->download('sugerido_'.$codcli.'.pdf');
    }

    public function crear(Request $request) {
        $codcli = sCodigoClienteActivo();
        $tabla = "inventario_".$codcli;
        if (VerificaTabla($tabla)) {
            $inv = DB::table($tabla)
            ->where('cuarentena', '=', '0')
            ->selectRaw('count(*) as contador')
            ->first(); 
            $contador = number_format($inv->contador,0);
            if ($contador <= 0) {
                return back()->with('warning', 'Archivo de inventario vacio!');
            }
            $marca = DB::table('marca')
            ->orderBy("descrip","asc")
            ->get();
            $categoria = DB::table('maecategoria')
            ->orderBy("descrip","asc")
            ->get();
        }
        $subtitulo = "CREAR SUGERIDO";
        return view('isacom.invsugerido.crear' ,["menu" => "Inventario",
                                                 "cfg" => DB::table('maecfg')->first(),
                                                 "codcli" => $codcli,
                                                 "marca" => $marca,
                                                 "categoria" => $categoria,
                                                 "subtitulo" => $subtitulo]);
    }

    public function store(Request $request) {
        set_time_limit(1000); 
        $modalidad = $request->get('modalidad');
        $codcli = $request->get('codcli');

        if ($modalidad == "CREAR") {
        
            $reposicion = $request->get('reposicion');
            $vmd = $request->get('vmd');
            $desprod = trim($request->get('desprod'));
            $marca = trim($request->get('marca'));
            $categoria = trim($request->get('categoria'));
            $sugerido = DB::table('sugerido')
            ->where('codcli','=',$codcli)
            ->delete();
     
            $tabla = "inventario_".$codcli;
            if (VerificaTabla($tabla)) {
                if (empty($desprod) && empty($marca) && empty($categoria)) {
                    $invent = DB::table($tabla)
                    ->where('cuarentena', '=', '0')
                    ->orderBy('desprod','asc')
                    ->get();
                }
                if (empty($desprod) && empty($marca) && !empty($categoria)) {
                    $invent = DB::table($tabla)
                    ->where('cuarentena', '=', '0')
                    ->where('categoria','=',$categoria)
                    ->orderBy('desprod','asc')
                    ->get();
                }
                if (empty($desprod) && !empty($marca) && empty($categoria)) {
                    $invent = DB::table($tabla)
                    ->where('cuarentena', '=', '0')
                    ->where('marca','=',$marca)
                    ->orderBy('desprod','asc')
                    ->get();
                }
                if (empty($desprod) && !empty($marca) && !empty($categoria)) {
                    $invent = DB::table($tabla)
                    ->where('cuarentena', '=', '0')
                    ->where('marca','=',$marca)
                    ->where('categoria','=',$categoria)
                    ->orderBy('desprod','asc')
                    ->get();
                }
                if (!empty($desprod) && empty($marca) && empty($categoria)) {
                    $invent = DB::table($tabla)
                    ->where('cuarentena', '=', '0')
                    ->where('desprod','LIKE','%'.$desprod.'%')
                    ->orderBy('desprod','asc')
                    ->get();
                }
                if (!empty($desprod) && empty($marca) && !empty($categoria)) {
                    $invent = DB::table($tabla)
                    ->where('cuarentena', '=', '0')
                    ->where('desprod','LIKE','%'.$desprod.'%')
                    ->where('categoria','=',$categoria)
                    ->orderBy('desprod','asc')
                    ->get();
                }
                if (!empty($desprod) && !empty($marca) && empty($categoria)) {
                    $invent = DB::table($tabla)
                    ->where('cuarentena', '=', '0')
                    ->where('desprod','LIKE','%'.$desprod.'%')
                    ->where('marca','=',$marca)
                    ->orderBy('desprod','asc')
                    ->get();
                }
                if (!empty($desprod) && !empty($marca) && !empty($categoria)) {
                    $invent = DB::table($tabla)
                    ->where('cuarentena', '=', '0')
                    ->where('desprod','LIKE','%'.$desprod.'%')
                    ->where('marca','=',$marca)
                    ->where('categoria','=',$categoria)
                    ->orderBy('desprod','asc')
                    ->get();
                }

                foreach ($invent as $inv) {

                    $cantran = verificarProdTransito($inv->barra, $codcli, "");
                    if ($vmd>0)
                        $pedir = intval(($vmd * $reposicion) - $cantran);
                    else
                        $pedir = intval(($inv->vmd * $reposicion) - $cantran);
                 
                    $pedir = $pedir - $inv->cantidad;

                    if ($pedir > 0) {
                        DB::table('sugerido')->insertGetId([
                            'codprod' => $inv->codprod,
                            'pedir' => $pedir,
                            'desprod' => $inv->desprod,
                            'barra' => $inv->barra,
                            'codcli' => $codcli,
                            'fecha' => date('Y-m-d H:i:s')
                        ]);
                    }

                }

            } else {
                return back()->with('warning', 'Tabla inventario no existe!');       
            }
        }
        if ($modalidad == "PROCESAR" ) {
            $criterio = $request->get('criterio');
            $preferencia = $request->get('preferencia');
            $tipedido = $request->get('tipedido');
            $id = GenerarPedidoAutomatico($codcli, $criterio, $preferencia, $tipedido);
            if ($id > 0) {
                session()->flash('message','SUGERIDO -> PEDIDO '.$id.' CREADO SATISFACTORIAMENTE');
            } else {
                session()->flash('error','PEDIDO NO SE PUDO CREAR');
            }
        }
        return Redirect::to('/invsugerido');
    }

    public function procesar(Request $request) {
        $codcli = sCodigoClienteActivo();
        $subtitulo = "PROCESAR SUGERIDO";
        return view('isacom.invsugerido.procesar' ,["menu" => "Inventario",
                                                    "cfg" => DB::table('maecfg')->first(),
                                                    "codcli" => $codcli,
                                                    "subtitulo" => $subtitulo]);
    }

    public function modificaritem(Request $request) {

        $id = $request->get('id');
        $pedir = $request->get('pedir');

        DB::table('sugerido')
        ->where('id', '=', $id)
        ->update(array("pedir" => $pedir, 'fecha' => date('Y-m-d H:i:s') ));
        return response()->json(['msg' => $msg ]);
    } 

 }
