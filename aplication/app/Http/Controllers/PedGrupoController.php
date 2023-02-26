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


class PedGrupoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request) {
            $filtro = trim($request->get('filtro'));
            $codgrupo = Auth::user()->codcli;

            $pedgrupo = DB::table('pedgrupo')
                ->where('estado', '=', 'ABIERTO')
                ->get();
            foreach ($pedgrupo as $pg) {
                $cont1 = 0;
                $cont2 = 0;
                $reg = DB::table('pedido')
                    ->selectRaw('count(*) as contador')
                    ->where('idpedgrupo', '=', $pg->id)
                    ->first();
                if ($reg)
                    $cont1 = $reg->contador;
                $reg = DB::table('pedido')
                    ->selectRaw('count(*) as contador')
                    ->where('idpedgrupo', '=', $pg->id)
                    ->where('estado', '=', 'GUARDADO')
                    ->first();
                if ($reg)
                    $cont2 = $reg->contador;
                if ($cont1 > 0 && ($cont1 == $cont2)) {
                    DB::table('pedgrupo')
                        ->where('id', '=', $pg->id)
                        ->update(array("estado" => 'GUARDADO'));
                }
            }

            $marca = DB::table('marca')
                ->orderBy("descrip", "asc")
                ->get();
            $grupo = DB::table('grupo')
                ->where('id', '=', $codgrupo)
                ->first();
            if ($grupo)
                $subtitulo = $grupo->nomgrupo;
            $tabla = DB::table('pedgrupo as pg')
                ->leftjoin('grupo as grp', 'pg.codgrupo', '=', 'grp.id')
                ->select('*', 'pg.id as pgid')
                ->where('pg.codgrupo', '=', $codgrupo)
                ->where(function ($q) use ($filtro) {
                    $q->where('pg.id', 'LIKE', '%' . $filtro . '%')
                        ->orwhere('pg.estado', 'LIKE', '%' . $filtro . '%')
                        ->orwhere('pg.marca', 'LIKE', '%' . $filtro . '%')
                        ->orwhere('pg.fecha', 'LIKE', '%' . date('Y-m-d', strtotime($filtro)) . '%');
                })
                ->orderBy('pg.id', 'desc')
                ->paginate(100);
            $subtitulo2 = "PEDIDOS DEL GRUPO";
            return view('isacom.pedgrupo.index', [
                "menu" => "Grupo",
                "cfg" => DB::table('maecfg')->first(),
                "tabla" => $tabla,
                "marca" => $marca,
                "codgrupo" => $codgrupo,
                "filtro" => $filtro,
                "subtitulo" => $subtitulo,
                "subtitulo2" => $subtitulo2
            ]);
        }
    }

    public function show($id)
    {
        $codgrupo = Auth::user()->codcli;
        $codcli = sCodigoClienteActivo();

        $pedgrupo = DB::table('pedgrupo')
            ->where('id', '=', $id)
            ->first();

        $grupo = DB::table('grupo')
            ->where('id', '=', $codgrupo)
            ->first();

        $gruporen = DB::table('gruporen')
            ->where('id', '=', $codgrupo)
            ->where('status', '=', 'ACTIVO')
            ->orderBy("preferencia", "asc")
            ->get();

        //$pg = collect();
        $pgarray = array();
        $pedidos = DB::table('pedido')
            ->where('idpedgrupo', '=', $id)
            ->get();
        foreach ($pedidos as $ped) {

            $pedren = DB::table('pedren')
                ->where('id', '=', $ped->id)
                ->get();
            foreach ($pedren as $pr) {

                if (empty($pgarray)) {
                    $pgarray[] = $pr;
                } else {

                    $found_key = trim(array_search($pr->barra, array_column($pgarray, 'barra')));
                    if ($found_key == "")
                        $pgarray[] = $pr;

                }

            }

        }
        $pg = collect($pgarray);
        $pg = $pg->sortBy('desprod');
        $pg = ColectionPaginate::paginate($pg, 100);
        $subtitulo = "CONSULTA DE PEDIDO DIRECTO";
        $moneda = Session::get('moneda', 'BSS');
        $factor = RetornaFactorCambiario("", $moneda);
        if ($pedgrupo->estado == "ENVIADO" && $moneda == 'USD') {
            $factor = $pedgrupo->factor;
        }
        return view('isacom.pedgrupo.show', [
            "menu" => "Grupo",
            "cfg" => DB::table('maecfg')->first(),
            "pg" => $pg,
            "subtitulo" => $subtitulo,
            "codgrupo" => $codgrupo,
            "grupo" => $grupo,
            "gruporen" => $gruporen,
            "pedidos" => $pedidos,
            "codcli" => $codcli,
            "pedgrupo" => $pedgrupo,
            "moneda" => $moneda,
            "factor" => $factor,
            "id" => $id
        ]);
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $pedgrupo = DB::table('pedgrupo')
                ->where('id', '=', $id)
                ->first();
            if ($pedgrupo) {
                $pedido = DB::table('pedido')
                    ->where('idpedgrupo', '=', $id)
                    ->get();
                foreach ($pedido as $ped) {
                    DB::table('pedren')
                        ->where('id', '=', $ped->id)
                        ->delete();
                    DB::table('pedido')
                        ->where('id', '=', $ped->id)
                        ->delete();
                    log::info("ELIMINAR PED ID -> " . $ped->id);
                }
                DB::table('pedgrupo')
                    ->where('id', '=', $id)
                    ->delete();
                log::info("ELIMINAR GRUPO ID -> " . $id);
            }
            DB::commit();
            session()->flash('message', 'Pedido ' . $id . ' eliminado satisfactoriamente');
        } catch (Exception $e) {
            DB::rollBack();
            session()->flash('error', $e);
        }
        return Redirect::to('/pedgrupo');
    }

    public function edit(Request $request, $id)
    {
        $filtro = trim($request->get('filtro'));
        $pedcorreo = "";
        $codgrupo = Auth::user()->codcli;
        $pedgrupo = DB::table('pedgrupo')
            ->where('id', '=', $id)
            ->first();

        $tmarca = DB::table('marca')
            ->where('descrip', '=', $pedgrupo->marca)
            ->first();
        if ($tmarca)
            $pedcorreo = $tmarca->pedcorreo;

        $grupo = DB::table('grupo')
            ->where('id', '=', $codgrupo)
            ->first();

        $gruporen = DB::table('gruporen')
            ->where('id', '=', $codgrupo)
            ->where('status', '=', 'ACTIVO')
            ->orderBy("preferencia", "asc")
            ->get();

        $pgarray = array();
        $pedido = DB::table('pedido')
            ->where('idpedgrupo', '=', $id)
            ->get();
        foreach ($pedido as $ped) {
            $pedren = DB::table('pedren')
                ->where('id', '=', $ped->id)
                ->where(function ($q) use ($filtro) {
                    $q->where('desprod', 'LIKE', '%' . $filtro . '%')
                        ->orwhere('barra', 'LIKE', '%' . $filtro . '%')
                        ->orwhere('codprove', 'LIKE', '%' . $filtro . '%');
                })
                ->get();
            foreach ($pedren as $pr) {
                $barra = trim($pr->barra);
                if (empty($pgarray) || is_null($pgarray)) {
                    $pgarray[] = $pr;
                } else {
                    $found_key = trim(array_search($barra, array_column($pgarray, 'barra')));
                    if ($found_key == "") {
                        $pgarray[] = $pr;
                    }
                }
            }
        }
        $pg = collect($pgarray);
        $pg = $pg->sortBy('desprod');
        $pg = ColectionPaginate::paginate($pg, 500);
        $subtitulo = "EDITAR PEDIDO DIRECTO";
        $moneda = Session::get('moneda', 'BSS');
        $factor = RetornaFactorCambiario("", $moneda);
        if ($pedgrupo->estado == "ENVIADO" && $moneda == 'USD') {
            $factor = $pedgrupo->factor;
        }
        $codcli = sCodigoPredetGrupo($codgrupo);
        return view('isacom.pedgrupo.edit', [
            "menu" => "Grupo",
            "cfg" => DB::table('maecfg')->first(),
            "pg" => $pg,
            "subtitulo" => $subtitulo,
            "codgrupo" => $codgrupo,
            "grupo" => $grupo,
            "gruporen" => $gruporen,
            "pedido" => $pedido,
            "pedgrupo" => $pedgrupo,
            "moneda" => $moneda,
            "factor" => $factor,
            "filtro" => $filtro,
            "pedcorreo" => $pedcorreo,
            "codcli" => $codcli,
            "id" => $id
        ]);
    }

    public function obtenerCodcliGrupo(Request $request)
    {
        $codgrupo = $request->get('codgrupo');
        $resp = "";
        $gruporen = DB::table('gruporen')
            ->where('id', '=', $codgrupo)
            ->orderBy("preferencia", "asc")
            ->get();
        if ($gruporen) {
            foreach ($gruporen as $gr) {
                $resp = $resp . $gr->codcli . '|';
            }
        }
        //log::info($resp);
        return response()->json(['resp' => $resp]);
    }

    public function obtenerTablaCliMaestra(Request $request)
    {
        //log::info($request);
        $codgrupo = $request->get('codgrupo');
        $codsuc = sCodigoPredetGrupo($codgrupo);
        $filtro = $request->get('filtro');
        $resp = "";
        $tabla = 'inventario_' . $codsuc;
        if (VerificaTabla($tabla)) {
            $tabla = DB::table($tabla)
                ->select('desprod', 'barra', 'marca')
                ->where(function ($q) use ($filtro) {
                    $q->where('barra', 'LIKE', '%' . $filtro . '%')
                        ->orwhere('desprod', 'LIKE', '%' . $filtro . '%')
                        ->orwhere('marca', 'LIKE', '%' . $filtro . '%');
                })
                ->orderBy("desprod", "asc")
                ->get();
            if ($tabla) {
                $resp = $tabla;
            }
        }
        return response()->json(['resp' => $resp]);
    }

    public function agregar($idx)
    {
        $subtitulo = "AGREGAR";
        $s1 = explode('_', $idx);
        $id = $s1[0];
        $codgrupo = $s1[1];
        $cant = $s1[2];
        $codsuc = $s1[3];
        $barra = $s1[4];
        $codsucdet = sCodigoPredetGrupo($codgrupo);
        $usuarioCreador = Auth::user()->email;
        $cant = ($cant == '0' || $cant == '') ? '1' : $cant;
        $pedgrupo = DB::table('pedgrupo')
            ->where('id', '=', $id)
            ->first();
        if (!empty($pedgrupo)) {
            $codprove = $pedgrupo->marca;
        }
        $pedido = DB::table('pedido')
            ->where('codcli', '=', $codsuc)
            ->where('idpedgrupo', '=', $id)
            ->first();
        if (!empty($pedido)) {
            $idpedido = $pedido->id;
        } else {
            $idpedido = iCrearPedidoNuevo($codsuc, 'D', $pedgrupo->marca, $pedgrupo->reposicion, $id);
            DB::table('pedido')
                ->where('id', '=', $idpedido)
                ->update(array("estado" => 'GUARDADO'));
        }
        $existe = false;
        $tabla = 'inventario_' . $codsucdet;
        if (VerificaTabla($tabla)) {
            $prod = DB::table($tabla)
                ->where('barra', '=', $barra)
                ->first();
            if (!empty($prod)) {
                $precio = $prod->costo;
                $codprod = $prod->codprod;
                $desprod = $prod->desprod;
                $regulado = $prod->regulado;
                $tipo = $prod->tipo;
                $iva = $prod->iva;
                $bulto = $prod->bulto;
                $existe = true;
            }
        }
        if (!$existe) {
            $prod = DB::table('tcmaestra' . $codgrupo)
                ->where('barra', '=', $barra)
                ->first();
            if (!empty($prod)) {
                $data = 'tc' . $codsuc;
                $campos = $prod->$data;
                $campo = explode("|", $campos);
                $precio = $campo[4];
                $codprod = $campo[2];
                $desprod = $campo[3];
                $regulado = "N";
                $tipo = 'M';
                $iva = $prod->iva;
                $bulto = $prod->bulto;
            }
        }
        $pedren = DB::table('pedren')
            ->where('id', '=', $idpedido)
            ->where('codprove', '=', $codprove)
            ->where('barra', '=', $barra)
            ->first();
        if (!empty($pedren)) {
            return back()->with('warning', 'Barra: ' . $barra . ' - Sucursal: ' . $codsuc . ' Este producto ya existe en el pedido!!!');
        }
        DB::table('pedren')->insert([
            'id' => $idpedido,
            'codprod' => $codprod,
            'desprod' => $desprod,
            'cantidad' => $cant,
            'precio' => $precio,
            'barra' => $barra,
            'codprove' => $codprove,
            'regulado' => $regulado,
            'tipo' => $tipo,
            'pvp' => $precio,
            'iva' => $iva,
            'da' => 0.00,
            'di' => 0.00,
            'dc' => 0.00,
            'pp' => 0.00,
            'neto' => $precio,
            'codcli' => $codsuc,
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
        CalculaTotalesPedido($idpedido);
        //dd($id .' - '. $codgrupo .' - '. $cant .' - '. $codsuc .' - '. $barra);
        //return $this->edit($id);
        return Redirect::to('/pedgrupo/' . $id . '/edit');
    }

    public function enviar(Request $request, $id)
    {
        set_time_limit(300);
        $mensaje = "";
        try {
            DB::beginTransaction();
            $factor = RetornaFactorCambiario("", "USD");
            $codgrupo = $request->get('codgrupo');
            $codmarca = $request->get('codmarca');
            $formato = $request->get('formato');
            $pedcorreo = $request->get('pedcorreo');
            if (iEnvioPedidoDirectoxCorreo($id, $pedcorreo, $formato, $codmarca) > 0) {
                DB::table('pedgrupo')
                    ->where('id', '=', $id)
                    ->update(
                        array(
                            "estado" => "ENVIADO",
                            "enviado" => date("Y-m-d H:i:s"),
                            "factor" => $factor
                        )
                    );
                $pedido = DB::table('pedido')
                    ->where('idpedgrupo', '=', $id)
                    ->get();
                foreach ($pedido as $ped) {
                    DB::table('pedido')
                        ->where('id', '=', $ped->id)
                        ->update(
                            array(
                                "estado" => "ENVIADO",
                                "fecenviado" => date("Y-m-d H:i:s"),
                                "factor" => $factor
                            )
                        );
                    vGrabarPedRenEnviado($ped->id, $codmarca, "OK-CORREO");
                }
            }
            DB::commit();
        } catch (Exception $e) {
            $mensaje = $e;
            DB::rollBack();
        }
        if ($mensaje == "")
            session()->flash('message', 'Pedido ' . $id . ' guardado satisfactoriamente');
        else
            session()->flash('error', 'Pedido ' . $id . ' ->' . $mensaje);
        return Redirect::to('/pedgrupo');
    }

    public function modificar(Request $request)
    {
        set_time_limit(300);
        $barra = $request->get('barra');
        $pedir = $request->get('pedir');
        $codsuc = $request->get('codsuc');
        $idpgrp = $request->get('idpgrp');
        $codgrupo = $request->get('codgrupo');
        $msg = 0;
        $reg = "";
        $pedgrupo = DB::table('pedgrupo')
            ->where('id', '=', $idpgrp)
            ->first();
        $pedido = DB::table('pedido')
            ->where('codcli', '=', $codsuc)
            ->where('idpedgrupo', '=', $idpgrp)
            ->first();
        if (empty($pedido)) {
            // DEBE CREAR EL PEDIDO
            if ($pedir <= 0) {
                return response()->json(['msg' => $msg, 'reg' => $reg]);
            }
            $idpedido = iCrearPedidoNuevo($codsuc, 'D', $pedgrupo->marca, $pedgrupo->reposicion, $idpgrp);
            DB::table('pedido')
                ->where('id', '=', $idpedido)
                ->update(array("estado" => 'GUARDADO'));
            $msg = 1;
        } else {
            $idpedido = $pedido->id;
        }
        log::info('IDPED: ' . $idpedido . ' BARRA: ' . $barra . ' PEDIR: ' . $pedir . ' SUCURSAL: ' . $codsuc . ' IDGRP: ' . $idpgrp);
        $vmd = 0;
        $vmd = number_format($vmd, 4, '.', ',');
        $inv = 0;
        $dias = 0;
        $tran = 0;
        $pedren = DB::table('pedren')
            ->where('id', '=', $idpedido)
            ->where('barra', '=', $barra)
            ->where('codcli', '=', $codsuc)
            ->first();
        if ($pedren) {
            if ($pedir <= 0) {
                DB::table('pedren')
                    ->where('id', '=', $idpedido)
                    ->where('barra', '=', $barra)
                    ->where('codcli', '=', $codsuc)
                    ->delete();
                $pr = DB::table('pedren')
                    ->selectRaw('count(*) as contitem')
                    ->where('id', '=', $idpedido)
                    ->where('codcli', '=', $codsuc)
                    ->first();
                //log::info('CONTADOR: '.$pr->contitem);
                if ($pr->contitem == 0) {
                    // PEDIDO EN BLANCO
                    DB::table('pedido')
                        ->where('id', '=', $idpedido)
                        ->delete();
                }
                $msg = 1;
            } else {
                DB::table('pedren')
                    ->where('id', '=', $idpedido)
                    ->where('barra', '=', $barra)
                    ->where('codcli', '=', $codsuc)
                    ->update(array('cantidad' => $pedir));
                //log::info('RESP: '.$msg.' IDPED: '.$idpedido);
            }
        } else {
            if ($pedir > 0) {
                $usuarioCreador = Auth::user()->email;
                $existe = false;
                $codprove = $pedgrupo->marca;
                $codsucpred = sCodigoPredetGrupo($codgrupo);
                $tabla = 'inventario_' . $codsucpred;
                if (VerificaTabla($tabla)) {
                    $prod = DB::table($tabla)
                        ->where('barra', '=', $barra)
                        ->first();
                    if (!empty($prod)) {
                        $precio = $prod->costo;
                        $codprod = $prod->codprod;
                        $desprod = $prod->desprod;
                        $regulado = $prod->regulado;
                        $tipo = $prod->tipo;
                        $iva = $prod->iva;
                        $bulto = $prod->bulto;
                        $existe = true;
                    }
                }
                if (!$existe) {
                    $prod = DB::table('tcmaestra' . $codgrupo)
                        ->where('barra', '=', $barra)
                        ->first();
                    if (!empty($prod)) {
                        $data = 'tc' . $codsuc;
                        $campos = $prod->$data;
                        $campo = explode("|", $campos);
                        $precio = $campo[4];
                        $codprod = $campo[2];
                        $desprod = $campo[3];
                        $regulado = "N";
                        $tipo = 'M';
                        $iva = $prod->iva;
                        $bulto = $prod->bulto;
                    }
                }
                $item = DB::table('pedren')->insertGetId([
                    'id' => $idpedido,
                    'codprod' => $codprod,
                    'desprod' => $desprod,
                    'cantidad' => $pedir,
                    'precio' => $precio,
                    'barra' => $barra,
                    'codprove' => $codprove,
                    'regulado' => $regulado,
                    'tipo' => $tipo,
                    'pvp' => $precio,
                    'iva' => $iva,
                    'da' => 0.00,
                    'di' => 0.00,
                    'dc' => 0.00,
                    'pp' => 0.00,
                    'neto' => $precio,
                    'codcli' => $codsuc,
                    'ahorro' => 0.00,
                    'subtotal' => $precio * $pedir,
                    'aprobacion' => "NO",
                    'estado' => "GUARDADO",
                    "fecha" => date("Y-m-d H:i:s"),
                    "fecenviado" => date("Y-m-d H:i:s"),
                    "bulto" => $bulto,
                    "usuarioCreador" => $usuarioCreador,
                    "tprnk1" => '0',
                    "ranking" => '0-0'
                ]);
                $reg = DB::table('pedren')
                    ->where('item', '=', $item)
                    ->first();
                if ($reg) {
                    $tran = verificarProdTransito($barra, $codsuc, "");
                    $invent = verificarProdInventario($barra, $codsuc);
                    if (!is_null($invent)) {
                        $vmd = $invent->vmd;
                        $inv = $invent->cantidad;
                        if ($vmd > 0) {
                            $dias = $inv / $vmd;
                        }
                    }
                    $dias = number_format($dias, 0, '.', ',');
                    $vmd = number_format($vmd, 4, '.', ',');
                    $inv = number_format($inv, 0, '.', ',');
                    $tran = number_format($tran, 0, '.', ',');
                }
            } else {
                // NO EXISTE EL RENGLON Y PEDIR ES CERO
                $pr = DB::table('pedren')
                    ->selectRaw('count(*) as contitem')
                    ->where('id', '=', $idpedido)
                    ->where('codcli', '=', $codsuc)
                    ->first();
                //log::info('CONTADOR: '.$pr->contitem);
                if ($pr->contitem == 0) {
                    // PEDIDO EN BLANCO
                    DB::table('pedido')
                        ->where('id', '=', $idpedido)
                        ->delete();
                }
                $msg = 1;
            }
        }
        CalculaTotalesPedido($idpedido);
        return response()->json([
            'msg' => $msg,
            'reg' => $reg,
            'vmd' => $vmd,
            'inv' => $inv,
            'dias' => $dias,
            'tran' => $tran
        ]);
    }

    public function deleprod(Request $request, $id)
    {
        $barra = $request->get('barra');
        $codsuc = $request->get('codsuc');
        $pedido = DB::table('pedido')
            ->where('codcli', '=', $codsuc)
            ->where('idpedgrupo', '=', $id)
            ->first();
        if (!empty($pedido)) {
            $idpedido = $pedido->id;
            DB::table('pedren')
                ->where('id', '=', $idpedido)
                ->where('barra', '=', $barra)
                ->where('codcli', '=', $codsuc)
                ->delete();
            $pr = DB::table('pedren')
                ->selectRaw('count(*) as contitem')
                ->where('id', '=', $idpedido)
                ->where('codcli', '=', $codsuc)
                ->first();
            if ($pr->contitem == 0) {
                // PEDIDO EN BLANCO
                DB::table('pedido')
                    ->where('id', '=', $idpedido)
                    ->delete();
            }
            CalculaTotalesPedido($idpedido);
        }
        return Redirect::to('/pedgrupo/' . $id . '/edit');
    }

    public function store(Request $request)
    {
        set_time_limit(500);
        $reposicion = $request->reposicion;
        $codmarca = $request->codmarca;
        $codgrupo = $request->codgrupo;
        $cantsug = $request->cantsug;
        $usuarioCreador = Auth::user()->email;
        $codcli = sCodigoPredetGrupo($codgrupo);
        $tabla = "inventario_" . $codcli;
        if (!VerificaTabla($tabla)) {
            return back()->with('warning', 'Tabla inventario no existe!');
        }
        //dd($codmarca ."-". $reposicion ."-". $codgrupo ."-". $codcli);
        $idpedgrupo = DB::table('pedgrupo')->insertGetId([
            'marca' => $codmarca,
            'fecha' => date('Y-m-d H:i:s'),
            'codgrupo' => $codgrupo,
            'estado' => 'ABIERTO',
            'reposicion' => $reposicion
        ]);
        $gruporen = DB::table('gruporen')
            ->where('id', '=', $codgrupo)
            ->where('status', '=', 'ACTIVO')
            ->get();
        foreach ($gruporen as $gr) {
            $procesar = 0;
            $codsuc = $gr->codcli;
            $maecliente = LeerCliente($codsuc);
            if ($maecliente->estado == "ACTIVO") {
                $id = iCrearPedidoNuevo($codsuc, 'D', $codmarca, $reposicion, $idpedgrupo);
                $invent = DB::table($tabla)
                    ->where('barra', '!=', '')
                    ->where('cuarentena', '=', '0')
                    ->where('marca', '=', $codmarca)
                    ->orderBy('desprod', 'asc')
                    ->get();
                foreach ($invent as $inv) {
                    $vmd = 0.00;
                    $cantidad = 0;
                    $barra = $inv->barra;
                    $codprod = $inv->codprod;
                    $prod = verificarProdInventario($barra, $codsuc);
                    if (!is_null($prod)) {
                        $vmd = $prod->vmd;
                        $cantidad = $prod->cantidad;
                        $codprod = $prod->codprod;
                    }
                    $cantran = verificarProdTransito($barra, $codsuc, "");
                    $pedir = intval(($vmd * $reposicion) - $cantran);
                    if ($pedir == 0) {
                        if ($cantsug > 0)
                            $pedir = $cantsug;
                    }
                    $pedir = $pedir - $cantidad;
                    $subtotal = $inv->costo * $pedir;
                    if ($pedir > 0) {
                        $procesar = 1;
                        DB::table('pedren')->insert([
                            'id' => $id,
                            'codprod' => $codprod,
                            'desprod' => $inv->desprod,
                            'cantidad' => $pedir,
                            'precio' => $inv->costo,
                            'barra' => $barra,
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
                            'codcli' => $codsuc,
                            'ahorro' => 0.00,
                            'subtotal' => $subtotal,
                            'aprobacion' => "NO",
                            'estado' => "ABIERTO",
                            "fecha" => date("Y-m-d H:i:s"),
                            "fecenviado" => date("Y-m-d H:i:s"),
                            "ranking" => "0-0",
                            "bulto" => $inv->bulto,
                            "usuarioCreador" => $usuarioCreador,
                            "tprnk1" => ''
                        ]);
                    }
                }
                if ($procesar == 1) {
                    CalculaTotalesPedido($id);
                } else {
                    DB::table('pedido')
                        ->where('id', '=', $id)
                        ->delete();
                }
            }
        }
        return Redirect::to('/pedgrupo');
    }

}