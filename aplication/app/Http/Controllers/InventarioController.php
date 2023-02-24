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


class InventarioController extends Controller
{
    public function __construct() {
    	$this->middleware('auth');
    }

    public function index(Request $request) {
        $subtitulo = "MODULO DE INVENTARIO DEL CLIENTE";
        $codcli = sCodigoClienteActivo();
        $tabla = "inventario_".$codcli;
        $contadorInv = 0;
        $fechaInv = date('Y-m-d H:i:s');
        $fechaSug = date('Y-m-d H:i:s');
        $fechaFalla = date('Y-m-d H:i:s');
        $fechaInvGrupo = date('Y-m-d H:i:s');
        if (VerificaTabla($tabla)) {
            $inv = DB::table($tabla)
            ->where('cuarentena', '=', '0')
            ->selectRaw('count(*) as contador')
            ->first(); 
            $contadorInv = $inv->contador;
            $invent = DB::table($tabla)->first();
            if ($invent) {
                $fechaInv = $invent->feccatalogo;
            }
        } 
   
        $sug = DB::table("sugerido")
        ->where('codcli','=',$codcli)
        ->selectRaw('count(*) as contador')
        ->first(); 
        $contadorSug = $sug->contador;
        $sug = DB::table('sugerido')
        ->where('codcli','=',$codcli)
        ->first();
        if ($sug) {
            $fechaSug = $sug->fecha;
        }

        $falla = DB::table("fallas")
        ->where('codcli','=',$codcli)
        ->selectRaw('count(*) as contador')
        ->first(); 
        $contadorFalla = $falla->contador;
        $falla = DB::table('fallas')
        ->where('codcli','=',$codcli)
        ->first();
        if ($falla) {
            $fechaFalla = $falla->fecha;
        }

        $contadorInvGrupo = 0;
        if (Auth::user()->tipo == "G") {
            $codgrupo = Auth::user()->codcli;
            $tabla = 'tcmaestra'.$codgrupo;
            if (VerificaTabla($tabla)) {
                $tcmaestra = DB::table($tabla)
                ->selectRaw('count(*) as contador')
                ->first(); 
                $contadorInvGrupo = $tcmaestra->contador;
            }
        } 
        return view('isacom.inventario.index' ,["menu" => "Inventario",
                                                "cfg" => DB::table('maecfg')->first(),
                                                "contadorInv" => $contadorInv,
                                                "contadorSug" => $contadorSug,
                                                "contadorInvGrupo" => $contadorInvGrupo, 
                                                "codcli" => $codcli,
                                                "subtitulo" => $subtitulo,
                                                "fechaInv" => $fechaInv,
                                                "fechaSug" => $fechaSug,
                                                "fechaFalla" => $fechaFalla,
                                                "fechaInvGrupo" => $fechaInvGrupo,
                                                "contadorFalla" => $contadorFalla ]);
    }


 }
