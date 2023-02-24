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
use Barryvdh\DomPDF\Facade as PDF;
use DB;
  
class InvGrupoController extends Controller
{
    public function __construct() {
    	$this->middleware('auth');
    } 
 
    public function index(Request $request) {
        $filtro = trim($request->get('filtro'));
        $cfg = DB::table('maecfg')->first();
        $codgrupo = Auth::user()->codcli;
        $subtitulo = "";
        $tabla = 'tcmaestra'.$codgrupo;
        if (!VerificaTabla($tabla)) {
            return back()->with('warning', 'Inventario del grupo no disponible!');
        }
        $grupo = DB::table('grupo')
        ->where('id','=',$codgrupo)
        ->first();
        if ($grupo) {
            $subtitulo = $grupo->nomgrupo;
            $gruporen =  DB::table('gruporen')
            ->where('id','=',$codgrupo)
            ->where('status','=','ACTIVO')
            ->orderBy('preferencia','asc')
            ->get();
            $catalogo = DB::table($tabla)
            ->orwhere('desprod','LIKE','%'.$filtro.'%')
            ->orwhere('barra','LIKE','%'.$filtro.'%')
            ->Orwhere('pactivo','LIKE','%'.$filtro.'%')
            ->orderBy('desprod','asc')
            ->paginate(100);
        } else { 
            return back()->with('warning', 'Código grupo no disponible!');           
        }
        $subtitulo2 = "INVENTARIO DEL GRUPO, FECHA: ".date('d-m-Y H:i', strtotime($cfg->actualizadoInv));
        //$codcli = sCodigoClienteActivo();
        $codcli = sCodigoPredetGrupo($grupo->id);
        return view("isacom.invgrupo.index",["menu" => "Grupo",
                                             "cfg" => $cfg,
                                             "codgrupo" => $codgrupo,
                                             "nomtcmaestra" => 'tcmaestra'.$codgrupo,
                                             "catalogo" => $catalogo, 
                                             "filtro" => $filtro,
                                             "gruporen" => $gruporen,
                                             "codcliente" => $codcli,
                                             "subtitulo" => $subtitulo,
                                             "subtitulo2" => $subtitulo2 ]);
    }
   
 }
