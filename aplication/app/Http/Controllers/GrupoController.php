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
use App\Maecliente;
use App\Maeprove;
use App\Maeclieprove;
use Illuminate\Support\Facades\Config;
use DB;
 
class GrupoController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index(Request $request) {
        if ($request) {
            $filtro=trim($request->get('filtro'));
            $codcli = sCodigoClienteActivo();
            $codgrupo = Auth::user()->codcli;
            $tipedido = Session::get('tipedido', 'N');
            $grupo = DB::table('grupo')
            ->where('id','=',$codgrupo)
            ->first();
            if (is_null($grupo))
                return back()->with('warning', 'Grupo no existe!');
            $subtitulo = $grupo->nomgrupo;
            $subtitulo2 = "LISTADO DE SUCURSALES DEL GRUPO";
            ReordenarPrefereciaCliente();
            $gruporen = DB::table('gruporen')
            ->where('id','=',$codgrupo)
            ->where(function ($q) use ($filtro) {
                $q->where('id','LIKE','%'.$filtro.'%')
                ->orwhere('codcli','LIKE','%'.$filtro.'%')
                ->orwhere('nomcli','LIKE','%'.$filtro.'%');
            })
            ->orderBy("preferencia","asc")
            ->get();
            return view('isacom.grupo.index',["menu" => "Grupo",
                                              "cfg" => DB::table('maecfg')->first(),
                                              "grupo" => $grupo,
                                              "gruporen" => $gruporen,
                                              "codcli" => $codcli,
                                              "filtro" => $filtro,
                                              "tipedido" => $tipedido,
                                              "subtitulo" => $subtitulo,
                                              "subtitulo2" => $subtitulo2]);
        }
    }

    public function show($id) {
        $subtitulo = "CONSULTAR CONFIGURACION";
        $cfg = DB::table('maecfg')->first();
        $codcli = $id;
        $cliente = DB::table('maecliente')
        ->where('codcli','=',$codcli)
        ->first();
        return view("isacom.grupo.show",["cliente" => $cliente,
                                             "menu" => "Grupo",
                                             "subtitulo" => $subtitulo,
                                             "cfg" => $cfg ]);
    }

    public function sumarpref(Request $request) {
        try {
            $codcli = $request->get('codcli');
            $pref = $request->get('pref');
            $codgrupo = Auth::user()->codcli;
            $msg = "OK";

            $cont = intval($pref) + 1;
            $sig = "00".$cont;
            $sig = substr($sig,strlen($sig)-2,2);

            $reg = DB::table('gruporen')
            ->where('id', '=', $codgrupo)
            ->where('preferencia', '=', $sig)
            ->first();

            if ($reg) {

                DB::table('gruporen')
                ->where('id', '=', $codgrupo)
                ->where('preferencia', '=', $sig)
                ->update(array("preferencia" => $pref));

                DB::table('gruporen')
                ->where('id', '=', $codgrupo)
                ->where('preferencia', '=', $pref)
                ->where('codcli', '=', $codcli)
                ->update(array("preferencia" => $sig));

            }
        } catch (Exception $e) {
            log::info("ERROR: $e - LINEA: ".$e->getLine());
        }
        return response()->json(['msg' => $msg ]);
    }

    public function restarpref(Request $request) {
        try {
            $codcli = $request->get('codcli');
            $pref = $request->get('pref');
            $codgrupo = Auth::user()->codcli;
            $msg = "OK";
    
            $cont = intval($pref) - 1;
            $ant = "00".$cont;
            $ant = substr($ant,strlen($ant)-2,2);

            $reg = DB::table('gruporen')
            ->where('id', '=', $codgrupo)
            ->where('preferencia', '=', $ant)
            ->first();

            if ($reg) {
        
                DB::table('gruporen')
                ->where('id','=', $codgrupo)
                ->where('preferencia', '=', $ant)
                ->update(array("preferencia" => $pref));

                DB::table('gruporen')
                ->where('id', '=', $codgrupo)
                ->where('preferencia', '=', $pref)
                ->where('codcli', '=', $codcli)
                ->update(array("preferencia" => $ant));

            }
        } catch (Exception $e) {
            log::info("ERROR: $e - LINEA: ".$e->getLine());
        }
        return response()->json(['msg' => $msg ]);
    }

    public function modstatus(Request $request) {
        $codgrupo = $request->get('codgrupo');
        $codcli = $request->get('codcli');
        $gruporen = DB::table('gruporen')
        ->where('id', '=', $codgrupo)
        ->where('codcli', '=', $codcli)
        ->first();
        if ($gruporen) {
            $status = $gruporen->status;
            if ($status == "ACTIVO")
                $status = "INACTIVO";
            else
                $status = "ACTIVO";
            DB::table('gruporen')
            ->where('id', '=', $codgrupo)
            ->where('codcli', '=', $codcli)
            ->update(array("status" => $status));
        }
        return response()->json(['msg' => '' ]);
    }

    public function modpredet(Request $request) {
        $codgrupo = $request->get('codgrupo');
        $codcli = $request->get('codcli');
        $gruporen = DB::table('gruporen')
        ->where('id', '=', $codgrupo)
        ->get();
        foreach ($gruporen as $gr) { 
            $predet = 0;
            if ($gr->codcli == $codcli) 
                $predet = 1;
            DB::table('gruporen')
            ->where('id', '=', $codgrupo)
            ->where('codcli', '=', $gr->codcli)
            ->update(array("predet" => $predet));
        }
        return response()->json(['msg' => '1' ]);
    }

 }
 
