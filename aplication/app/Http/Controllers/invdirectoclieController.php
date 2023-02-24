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

class invdirectoclieController extends Controller
{
    public function __construct() {
    	$this->middleware('auth');
    }
    public function index(Request $request) {
        $filtro=trim($request->get('filtro'));
        $subtitulo = "";
        $codcli = sCodigoClienteActivo();
        $confcli = LeerCliente($codcli); 
        $cliente = DB::table('maecliente')
        ->where('codcli','=',$codcli)
        ->first();
        $tabla = "inventario_".$codcli;
        $invent = null;
        if (VerificaTabla($tabla)) {
            $invent = DB::table($tabla)
            ->where('cuarentena', '=', '0')
            ->where(function ($q) use ($filtro) {
                $q->where('barra','LIKE','%'.$filtro.'%')
                ->orwhere('desprod','LIKE','%'.$filtro.'%')
                ->orwhere('codprod','LIKE','%'.$filtro.'%')
                ->orwhere('pactivo','LIKE','%'.$filtro.'%');
            })
            ->orderBy('desprod','asc')
            ->paginate(100); 

            $inv = DB::table($tabla)
            ->where('cuarentena', '=', '0')
            ->selectRaw('count(*) as contador')->first(); 
            $contador =number_format($inv->contador,0);

            if ($invent->count()>0) {
                $fecha = date('d-m-Y H:i', strtotime($invent[0]->feccatalogo));
                $subtitulo = "FECHA: ".$fecha.", RENGLONES: ".$contador;
            }
        }  
        return view('isacom.invdirectoclie.index' ,["menu" => "Inventario",
                                                    "cfg" => DB::table('maecfg')->first(),
                                                    "codcli" => $codcli,
                                                    "confcli" => $confcli,
                                                    "cliente" => $cliente,
                                                    "filtro" => $filtro,
                                                    "invent" => $invent,
                                                    "subtitulo" => $subtitulo ]);
    }
 }
