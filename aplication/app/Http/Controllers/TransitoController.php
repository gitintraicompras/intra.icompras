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
use App\Maecliente;
use App\Maeprove;
use App\Maeclieprove;
use DB;

class TransitoController extends Controller
{
    public function __construct() {
    	$this->middleware('auth');
    }
 
    public function index(Request $request) {
    	if ($request) {
            $codcli = sCodigoClienteActivo();

            $cliente = DB::table('maecliente')
            ->where('codcli','=',$codcli)
            ->first();
            if ($cliente) {
                if ($cliente->diasTransito > 0) {
                    $transito = DB::table('transito')
                    ->where('codcli','=',$codcli)
                    ->get();
                    foreach ($transito as $tra) {
                        $diasTransitox = DiferenciaDias($tra->fecenviado);
                        if ($diasTransitox > $cliente->diasTransito) {
                            DB::table('transito')
                            ->where('item', '=', $tra->item)
                            ->delete();
                        }
                    }
                }
            }
            $subtitulo = "PRODUCTOS EN TRANSITO";
            $filtro = trim($request->get('filtro'));
            $tabla = DB::table('transito')
            ->where('codcli','=',$codcli)
                ->where(function ($q) use ($filtro) {
                    $q->where('desprod','LIKE','%'.$filtro.'%')
                    ->orwhere('codprove','LIKE','%'.$filtro.'%')
                    ->orwhere('barra','LIKE','%'.$filtro.'%');
                })
            ->orderBy('fecenviado','desc')
            ->paginate(100);
            return view('isacom.transito.index' ,["tabla" => $tabla, 
                                                  "menu" => "Transito",
                                                  "cfg" => DB::table('maecfg')->first(),
                                                  "filtro" => $filtro,
                                                  "subtitulo" => $subtitulo]);
    	}
    }
	
	public function destroy($item) {
		DB::table('transito')
        ->where('item', '=', $item)
        ->delete();
		return Redirect::to('/transito');
	}

    public function liberar() {
        $codcli = sCodigoClienteActivo();
        DB::table('transito')
        ->where('codcli', '=', $codcli)
        ->delete();
        return Redirect::to('/transito');
    }
 
 }
