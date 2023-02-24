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
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;
use DB;
use Barryvdh\DomPDF\Facade as PDF;
use App\tpmaestra;
use App\CustomClasses\ColectionPaginate;
   
 
class SugOfertasController extends Controller
{
    public function __construct() {
    	$this->middleware('auth');
    }
 
    public function index(Request $request) {
        set_time_limit(500);  
        $filtro = trim($request->get('filtro'));
        $campo = explode("*", $filtro);
        $contador = count($campo);
        $codcli = sCodigoClienteActivo();
        $cliente = DB::table('maecliente')
        ->where('codcli','=',$codcli)
        ->first();
        $provs = TablaMaecliproveActivaOfertas();
        $subtitulo = "SUGERIDO DE OFERTAS";
        if ($contador == 1) {
            $sugoferen = DB::table('sugoferen')
            ->where('codcli','=',$codcli)
            ->where(function ($q) use ($filtro) {
                $q->orwhere('desprod','LIKE','%'.$filtro.'%')
                ->orwhere('barra','LIKE','%'.$filtro.'%')
                ->orwhere('marca','LIKE','%'.$filtro.'%')
                ->orwhere('codprod','LIKE','%'.$filtro.'%');
            })
            ->orderBy('desprod','asc')
            ->get();
        } else {
            // BUSQUEDA COMPUESTA (FILTRO)
            $filtro1 = $campo[0];
            $filtro2 = $campo[1];
            $sugoferen = DB::table('sugoferen')
            ->where('codcli','=',$codcli)
            ->where(function ($q) use ($filtro1, $filtro2) {
                $q->where('desprod','LIKE','%'.$filtro1.'%')
                ->where('desprod','LIKE','%'.$filtro2.'%')
                ->Orwhere(function ($q) use ($filtro1, $filtro2) {
                    $q->where('desprod','LIKE','%'.$filtro1.'%')
                    ->where('marca','LIKE','%'.$filtro2.'%');
                });
            })
            ->orderBy('desprod','asc')
            ->get();
        }
        return view("ofertas.sugofertas.index",["menu" => "Registro",
                                                 "cliente" => $cliente,
                                                 "utilm" => $cliente->utilm,
                                                 "sugoferen" => $sugoferen,
                                                 "provs" => $provs,
                                                 "filtro" => $filtro,
                                                 "codcli" => $codcli,
                                                 "cfg" => DB::table('maecfg')->first(),
                                                 "subtitulo" => $subtitulo]);
    }

    public function store(Request $request) {
        try {
            $ppsa = ($request->get('ppsa')=='on') ? '1': '0';
            $desde = $request->get('desde');
            $hasta = $request->get('hasta');
            $observ = $request->get('observ');
            $codcli = sCodigoClienteActivo();
            $usuario = Auth::user()->name;
            DB::table('maecliente')
            ->where('codcli','=',$codcli)
            ->update(array('ppsa' => $ppsa ));
            $id = DB::table('regofertas')->insertGetId([
                'codcli' => $codcli,
                'fecha' => date('Y-m-d H:i:s'), 
                'usuario' => $usuario, 
                'status' => '0',
                'desde' => $desde,
                'hasta' => $hasta, 
                'observ' => $observ,
                'ppsa' => $ppsa
            ]);
            $sugoferen = DB::table('sugoferen')
            ->where('codcli','=',$codcli)
            ->orderBy('desprod','asc')
            ->get();
            foreach ($sugoferen as $sug) { 
                DB::table('regoferen')->insert([
                    'id' => $id,
                    'codcli' => $codcli,
                    'codprod' => $sug->codprod,
                    'desprod' => $sug->desprod,
                    'da' => $sug->dasug,
                    'ps' => $sug->ps,
                    'precio' => $sug->precio
                ]);
            }
        } catch (Exception $e) {
            session()->flash('error', $e);
        }
        return Redirect::to('/ofertas/registros');
    }

    public function deleprod(Request $request) {
        $item = trim($request->get('item'));
        DB::table('sugoferen')
        ->where('item','=',$item)
        ->delete();
        return redirect()->back()->with('result');
    }

    public function delsel(Request $request) {
        $item = trim($request->get('item'));
        DB::table('sugoferen')
        ->where('item','=',$item)
        ->delete();
        return response()->json(['resp' => 'OK']);
    }

    public function upddasug(Request $request) {
        $item = trim($request->get('item'));
        $da = trim($request->get('da'));
        $ps = trim($request->get('ps'));
        $util = trim($request->get('util'));
        DB::table('sugoferen')
        ->where('item','=',$item)
        ->update(array('dasug' => $da,
                       'util' => $util,
                       'ps' => $ps));
        return response()->json(['resp' => 'OK']);
    }
  
 }
