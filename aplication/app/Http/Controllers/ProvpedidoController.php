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
use DB;
use Barryvdh\DomPDF\Facade as PDF;

class ProvpedidoController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth');
    }
 
    public function index(Request $request) {
        if ($request) {
            $cfg = DB::table('maecfg')->first();
            $codcli = sCodigoClienteActivo();
            $codprove = Auth::user()->ultcodcli;
            $subtitulo = "PEDIDOS";
            $filtro=trim($request->get('filtro'));
            $tabla=DB::table('provpedido')
            ->where('codprove','=',$codprove)
            ->where(function ($q) use ($filtro) {
                $q->where('nomcli','LIKE','%'.$filtro.'%')
                ->Orwhere('id','LIKE','%'.$filtro.'%')
                ->Orwhere('codcli','LIKE','%'.$filtro.'%');
            })
            ->orderBy('id','desc')
            ->paginate(100);
            return view('isacom.provpedido.index' ,["menu" => "Pedidos",
                                                    "cfg" => $cfg,
                                                    "tabla" => $tabla, 
                                                    "cfg" => $cfg,
                                                    "filtro" => $filtro,
                                                    "codprove" => $codprove,
                                                    "subtitulo" => $subtitulo]);
        }
    }

    public function show($id) {

        $cfg = DB::table('maecfg')->first();
        $codcli = sCodigoClienteActivo();
        $codprove = Auth::user()->ultcodcli;

        // TABLA DE PEDIDO
        $tabla = DB::table('provpedido')
        ->where('id','=',$id)
        ->where('codprove','=',$codprove)
        ->first();

        // TABLA DE RENGLONES DE PEDIDO
        $tabla2 = DB::table('provpedren')
        ->where('id','=',$id)
        ->where('codprove','=',$codprove)
        ->get();

        $subtitulo = "PEDIDO (".$tabla->nomcli.")";

        return view('isacom.provpedido.show',["menu" => "Pedidos",
                                              "cfg" => $cfg,
                                              "tabla" => $tabla, 
                                              "tabla2" => $tabla2, 
                                              "cfg" => $cfg,
                                              "codcli" => $codcli,
                                              "codprove" => $codprove,
                                              "subtitulo" => $subtitulo,
                                              "id" => $id]);
    }

    public function destroy($id) {
        $codprove = Auth::user()->ultcodcli;
        DB::table('provpedren')
        ->where('id','=',$id)
        ->where('codprove','=',$codprove)
        ->delete();
        DB::table('provpedido')
        ->where('id','=',$id)
        ->where('codprove','=',$codprove)
        ->delete();
        return Redirect::to('/provpedido');
    }

    public function descargar($id) {
        $codprove = Auth::user()->ultcodcli;

        $fechaHoy = date('j-m-Y');
        $FechaPedido = substr($fechaHoy, 0, 10);

        $tabla2 = DB::table('provpedren')
        ->where('id','=',$id)
        ->where('codprove','=',$codprove)
        ->get();

        // TABLA DE PEDIDO
        $tabla = DB::table('provpedido')
        ->where('id','=',$id)
        ->first();

        $titulo = "PEDIDO: ".$id;
        $subtitulo = $tabla->nomcli;
        $codcli = $tabla->codcli;

        $maeprove = LeerProve($codprove);

        $cliente = DB::table('maecliente')
        ->where('codcli','=',$codcli)
        ->first();
        $data = [
            "titulo" => $titulo,
            "subtitulo" => $subtitulo,
            "tabla" => $tabla, 
            "tabla2" => $tabla2, 
            "codprove" => $codprove,
            "cliente" => $cliente,
            "maeprove" => $maeprove,
            "cfg" => DB::table('maecfg')->first(),
            "id" => $id
        ];
        return PDF::loadView('layouts.rptprovpedido', $data)
        ->download('PED_'.$codprove.'_'.$codcli.'.pdf');
    }

}
