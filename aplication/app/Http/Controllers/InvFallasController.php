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


class InvFallasController extends Controller
{
    public function __construct() {
    	$this->middleware('auth');
    }

    public function index(Request $request) {
        $filtro=trim($request->get('filtro'));
        $codcli = sCodigoClienteActivo();
        $fallas = DB::table("fallas")
        ->where('codcli','=',$codcli)
        ->where(function ($q) use ($filtro) {
            $q->where('barra','LIKE','%'.$filtro.'%')
            ->orwhere('desprod','LIKE','%'.$filtro.'%')
            ->orwhere('codprod','LIKE','%'.$filtro.'%');
        })
        ->orderBy('desprod','asc')
        ->paginate(100);
        $subtitulo = "FALLAS";
        $subtitulo2 = "";
        if ($fallas->count()>0) {
        
            $falla = DB::table("fallas")
            ->where('codcli','=',$codcli)
            ->selectRaw('count(*) as contador')
            ->first(); 
            $contador =number_format($falla->contador,0);

            $fecha = date('d-m-Y H:i', strtotime($fallas[0]->fecha));
            $subtitulo2 = "FALLAS (FECHA: ".$fecha.", RENGLONES: ".$contador.")";
        }
        return view('isacom.invfallas.index' ,["menu" => "Inventario",
                                               "cfg" => DB::table('maecfg')->first(),
                                               "codcli" => $codcli,
                                               "fallas" => $fallas,
                                               "filtro" => $filtro,
                                               "subtitulo" => $subtitulo,
                                               "subtitulo2" => $subtitulo2 ]);
    }

    public function destroy($id) {
        try {
            DB::table('fallas')
            ->where('codcli','=',$id)
            ->delete();
        } catch (Exception $e) {
            return Redirect::back()->with('error', 'Fallas '.$id.' '.$e);
        }
        return Redirect::back()->with('message', 'Fallas '.$id.' eliminado satisfactoriamente');
    }

    public function deleprod(Request $request, $id) {
        $regs = DB::table('fallas')
        ->where('id','=',$id)
        ->delete();
        return Redirect::back()->with('message', 'Producto eliminado satisfactoriamente');
    }

    public function descargar(Request $request) {
        set_time_limit(500); 
        $codcli = sCodigoClienteActivo();
        $cfg = DB::table('maecfg')->first();

        $titulo = "FALLAS";
        $fallas = DB::table('fallas')
        ->where('codcli','=',$codcli)
        ->get();

        if ($fallas)
            $fecha = date('d-m-Y', strtotime($fallas[0]->fecha)).' - RENGLONES: '.number_format($fallas->count(), 0, ',', '.');

        $cliente = DB::table('maecliente')
        ->where('codcli','=',$codcli)
        ->first();

        if ($cliente)
            $subtitulo = $cliente->nombre;
      
        $data = [
            "menu" => "Inventario",
            "tabla2" => $fallas, 
            "cfg" => $cfg,
            "fecha" => $fecha,
            "titulo" => $titulo,
            "subtitulo" => $subtitulo,
            "cliente" => $cliente
        ];

        return PDF::loadView('layouts.rptfallas', $data)
        ->download('fallas_'.$codcli.'.pdf');
    }

    public function store(Request $request) {
        set_time_limit(500); 
        $modalidad = $request->get('modalidad');
        $codcli = sCodigoClienteActivo();
        $id = -1;
        $criterio = $request->get('criterio');
        $preferencia = $request->get('preferencia');
        $provs = TablaMaecliproveActiva("");

        $tabla = DB::table('fallas')
        ->where('codcli','=',$codcli)
        ->get();
    
        if ($tabla->count()>0) {
            $primer = 0;
            foreach ($tabla as $sug) {
                $barra = $sug->barra;
                $tpmaestra = DB::table('tpmaestra')
                ->where('barra','=',$barra)
                ->first();
                if ($tpmaestra) {
                    $pedir = $sug->pedir;
                    $mejoropcion = BuscarMejorOpcion($barra, $criterio, $preferencia, $pedir,$provs);

                    if ($mejoropcion != null) {

                        if ($primer==0) {
                            $primer = 1;
                            $id = iCrearPedidoNuevo('', 'N', '', 7, 0);
                            DB::table('pedren')
                            ->where('id', '=', $id)
                            ->delete();

                            DB::table('fallas')
                            ->where('codcli', '=', $codcli)
                            ->delete();

                            DB::table('pedido')
                            ->where('id', '=', $id)
                            ->update(array("estado" => 'NUEVO',
                                           'fecha' => date("Y-m-d H:i:s"),
                                           'fecenviado' => date("Y-m-d H:i:s")
                            ));
                        }
                        $pedirx = $pedir;
                        $contprov = count($mejoropcion);
                        for ($x=0; $x < $contprov; $x++ ) {

                            $cantidad = $mejoropcion[$x]['cantidad'];
                            if ($pedirx <= $cantidad) {
                                $pedirx = $pedir;
                                $pedir = $pedir - $pedirx; 
                            } else {
                                $pedirx = $cantidad;
                                $pedir = $pedir - $cantidad;
                            }

                            $precio = $mejoropcion[$x]['precio'];
                            $da = $mejoropcion[$x]['da'];
                            $codprod = $mejoropcion[$x]['codprod'];
                            $fechafalla = $mejoropcion[$x]['fechafalla'];
                            $lote = $mejoropcion[$x]['lote'];
                            $fecvence = $mejoropcion[$x]['fecvence'];
                            $desprod = $mejoropcion[$x]['desprod'];
                            $codprove = $mejoropcion[$x]['codprove'];
                            $dcredito = $mejoropcion[$x]['dias'];

                            $maeclieprove = DB::table('maeclieprove')
                            ->where('codcli','=',$codcli)
                            ->where('codprove','=',$codprove)
                            ->first();
                            $dc = $maeclieprove->dcme;
                            $di = $maeclieprove->di;
                            $pp = $maeclieprove->ppme;
                            $neto = CalculaPrecioNeto($precio, $da, $di, $dc, $pp, 0.00);
                            $ahorro = dBuscarMontoAhorro($barra, $neto, $codcli);
                            $subtotal = floatval($neto) * intval($pedirx);

                            DB::table('pedren')->insert([
                                'id' => $id, 
                                'codprod' => $codprod, 
                                'desprod' => $desprod, 
                                'cantidad' => $pedirx, 
                                'precio' => $precio, 
                                'barra' => $barra,
                                'codprove' => $codprove,                  
                                'regulado' => $tpmaestra ->regulado,
                                'tipo' => $tpmaestra->tipo,
                                'pvp' => $precio,
                                'iva' => $tpmaestra->iva,
                                'da' => $da,
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
                                "ranking" => "1-1",
                                "bulto" => $tpmaestra->bulto
                            ]);

                            if ($pedir <= 0)
                                break;
                            $pedirx = $pedir;

                        }
                        
                        if ($pedirx > 0) {


                            log::info("MEJOR OPCION -> FALLAS(c): ".$sug->codprod. ' - '.$pedir.' - '.$barra);

                            DB::table('fallas')->insertGetId([
                                'codprod' => $sug->codprod,
                                'pedir' => $pedirx,
                                'desprod' => $sug->desprod,
                                'barra' => $barra,
                                'codcli' => $codcli,
                                'fecha' => date('Y-m-d H:i:s')
                            ]);

                        }   
                                                 
                    } else {

                        log::info("MEJOR OPCION -> FALLAS: ".$sug->codprod. ' - '.$pedir.' - '.$barra);

                        DB::table('fallas')->insertGetId([
                            'codprod' => $sug->codprod,
                            'pedir' => $pedir,
                            'desprod' => $sug->desprod,
                            'barra' => $barra,
                            'codcli' => $codcli,
                            'fecha' => date('Y-m-d H:i:s')
                        ]);
                    }
                }
            }
            if ($primer > 0) {
                CalculaTotalesPedido($id);
                session()->flash('message','Fallas -> Pedido '.$id.' creado satisfactoriamente');
            } else {
                session()->flash('error','Pedido no se pudo crear');
            }
        }
        return Redirect::to('/invfallas');
    }

    public function procesar(Request $request) {
        $codcli = sCodigoClienteActivo();
        $subtitulo = "PROCESAR FALLAS";
        return view('isacom.invfallas.procesar' ,["menu" => "Inventario",
                                                  "cfg" => DB::table('maecfg')->first(),
                                                  "codcli" => $codcli,
                                                  "subtitulo" => $subtitulo]);
    }

    public function modificaritem(Request $request) {

        $id = $request->get('id');
        $pedir = $request->get('pedir');

        DB::table('fallas')
        ->where('id', '=', $id)
        ->update(array("pedir" => $pedir, 'fecha' => date('Y-m-d H:i:s') ));
        return response()->json(['msg' => $msg ]);
    }



 }
