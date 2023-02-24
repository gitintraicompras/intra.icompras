<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\UsuarioFormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\MaeclieproveFormRequest;
use App\Http\Requests\MaeproveFormRequest;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;
use DB;
use Barryvdh\DomPDF\Facade as PDF;


class ProvcatalogoController extends Controller
{
    public function __construct() {
    	$this->middleware('auth');
    }

    public function index(Request $request) {
        $filtro=trim($request->get('filtro'));
        $subtitulo = "CATALOGO";
   
        $codcli = sCodigoClienteActivo();
        $codprove = Auth::user()->ultcodcli;

        $maeprove = DB::table('maeprove')
        ->where('codprove','=',$codprove)
        ->first();

        $tabla = strtolower($codprove);
        $catalogo = DB::table($tabla)
        ->orwhere('barra','LIKE','%'.$filtro.'%')
        ->orwhere('desprod','LIKE','%'.$filtro.'%')
        ->orwhere('codprod','LIKE','%'.$filtro.'%')
        ->orderBy('desprod','asc')
        ->paginate(100);

        $cat = DB::table($tabla)
        ->selectRaw('count(*) as contador')->first(); 
        $contador =number_format($cat->contador,0);
     
        if ($catalogo->count()>0) {
            $fecha = date('d-m-Y H:i', strtotime($catalogo[0]->fechacata));
            $subtitulo = "CATALOGO (".$fecha." , RENGLONES: ".$contador.")";
        }
        return view('isacom.provcatalogo.index' ,["menu" => "Catalogo",
                                                  "cfg" => DB::table('maecfg')->first(),
                                                  "catalogo" => $catalogo, 
                                                  "codprove" => $codprove,
                                                  "maeprove" => $maeprove,
                                                  "filtro" => $filtro,
                                                  "subtitulo" => $subtitulo]);
    }
   
    public function descargar(Request $request) {
        set_time_limit(500); 

        $codprove = sCodigoClienteActivo();
        $tabla = strtolower($codprove);
        $cfg = DB::table('maecfg')->first();

        $titulo = "CATALOGO DE PRODUCTOS";
        $proveedor = DB::table('maeprove')
        ->where('codprove','=',$codprove)
        ->first();

        if ($proveedor)
            $subtitulo = $proveedor->nombre;

        if (VerificaTabla($tabla)) {
            $cat = DB::table($tabla)
            ->where('cantidad','>',0)
            ->orderBy('desprod','asc')
            ->get();

            if ($cat)
                $fecha = date('d-m-Y', strtotime($cat[0]->fechacata)).' - RENGLONES: '.number_format($cat->count(), 0, ',', '.');
          
            $data = [
                "menu" => "Catalogo",
                "catalogo" => $cat, 
                "cfg" => $cfg,
                "fecha" => $fecha,
                "titulo" => $titulo,
                "subtitulo" => $subtitulo
            ];

            return PDF::loadView('layouts.rptinv', $data)
            ->download('catalogo_'.$codprove.'.pdf');
        } else {
            session()->flash('error',"tabla de inventario vacia");   
        }
    }

 }
