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
use Illuminate\Support\Facades\Log;
 
class ProdcaractController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth');
    }
 
    public function index(Request $request) {
    	if ($request) {

            $marca = DB::table('marca')
            ->orderBy('descrip','asc')
            ->get();
            
            $categoria = DB::table('maecategoria')
            ->orderBy('descrip','asc')
            ->get();

            $molecula = DB::table('molecula')
            ->orderBy('descrip','asc')
            ->get();
            
       		$filtro = trim($request->get('filtro'));
            $campo = explode("*", $filtro);
            $contador = count($campo);
            if ($contador == 1) {
                $tabla = DB::table('tpmaestra')
                ->Orwhere('desprod','LIKE','%'.$filtro.'%')
    		    ->Orwhere('barra','LIKE','%'.$filtro.'%')
                ->Orwhere('marca','LIKE','%'.$filtro.'%')
                ->Orwhere('nomprov','LIKE','%'.$filtro.'%')
                ->Orwhere('categoria','LIKE','%'.$filtro.'%')
                ->Orwhere('molecula','LIKE','%'.$filtro.'%')
                ->Orwhere('metadata','LIKE','%'.$filtro.'%')
                ->Orwhere('pactivo','LIKE','%'.$filtro.'%')
                ->orderBy('desprod','asc')
                ->paginate(100);
                $reg = DB::table('tpmaestra')
                ->selectRaw('count(*) as contador')
                ->Orwhere('desprod','LIKE','%'.$filtro.'%')
                ->Orwhere('barra','LIKE','%'.$filtro.'%')
                ->Orwhere('marca','LIKE','%'.$filtro.'%')
                ->Orwhere('nomprov','LIKE','%'.$filtro.'%')
                ->Orwhere('categoria','LIKE','%'.$filtro.'%')
                ->Orwhere('molecula','LIKE','%'.$filtro.'%')
                ->Orwhere('metadata','LIKE','%'.$filtro.'%')
                ->Orwhere('pactivo','LIKE','%'.$filtro.'%')
                ->first();
            } else {
                $filtro1 = $campo[0];
                $filtro2 = $campo[1];
                $tabla = DB::table('tpmaestra')
                ->Orwhere(function ($q) use ($filtro1, $filtro2) {
                    $q->where('desprod','LIKE','%'.$filtro1.'%')
                    ->where('desprod','LIKE','%'.$filtro2.'%');
                })
                ->Orwhere(function ($q) use ($filtro1, $filtro2) {
                    $q->where('desprod','LIKE','%'.$filtro1.'%')
                    ->where('marca','LIKE','%'.$filtro2.'%');
                })
                ->orderBy('desprod','asc')
                ->paginate(100);
                $reg = DB::table('tpmaestra')
                ->selectRaw('count(*) as contador')
                ->Orwhere(function ($q) use ($filtro1, $filtro2) {
                    $q->where('desprod','LIKE','%'.$filtro1.'%')
                    ->where('desprod','LIKE','%'.$filtro2.'%');
                })
                ->Orwhere(function ($q) use ($filtro1, $filtro2) {
                    $q->where('desprod','LIKE','%'.$filtro1.'%')
                    ->where('marca','LIKE','%'.$filtro2.'%');
                })
                ->first();
            }
            $cont = $reg->contador;
            $subtitulo = "CARACTERISTICAS (".number_format($cont,0, '.', ',')." productos)";
    		return view('isacom.prodcaract.index' ,["menu" => "Caracteristicas",
                                                    "tabla" => $tabla, 
     				   	                            "filtro" => $filtro,
                                                    "marca" => $marca,
                                                    "categoria" => $categoria,
                                                    "molecula" => $molecula,
                                                    "cfg" => DB::table('maecfg')->first(),
    								                "subtitulo" => $subtitulo]);
    	}
    }
 
    public function modcaract(Request $request) {
        $barra = trim(strtoupper($request->get('barra')));
        $campo = trim(strtoupper($request->get('campo')));
        $valor = trim(strtoupper($request->get('valor')));
        if ($campo == 'unidadmolecula' && ($valor == '0' || empty($valor))) {
            $valor = 1;
        }
        //log::info("DATA -> ".$barra ." - ".$campo .' - '. $valor);

        $tpmaestra = DB::table('tpmaestra')
        ->where('barra', '=', $barra)
        ->first();
        if ($tpmaestra) {
          
            $existe = 0;
            $prodcaract = DB::table('prodcaract')
            ->where('barra', '=', $barra)
            ->first();
            if ($prodcaract) 
                $existe = 1;
            
            if ($existe == 1) {
                DB::table('prodcaract')
                ->where('barra', '=', $barra)
                ->update(array($campo => $valor));   
            } else {
                DB::table('prodcaract')->insert([
                'barra' => $barra,
                $campo => $valor ]);
            }
            DB::table('tpmaestra')
            ->where('barra', '=', $barra)
            ->update(array($campo => $valor));   

            $nombre = Auth::user()->name;
            $codcli = Auth::user()->codcli;
            $cliente = DB::table('maecliente')
            ->where('codcli','=',$codcli)
            ->first();
            if ($cliente)
                $nombre = $cliente->nombre; 

            DB::table('logtrans')->insert([
                'codcli' => $codcli,
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'tipo' => Auth::user()->tipo,
                'contVisita' => 0,
                'fecha' => date("Y-m-d H:i:s"),
                'operacion' => "PROD CARACT. ".$barra .":  ".$campo." -> ".$valor,
                'nombre' => $nombre
            ]);

        }
        return response()->json(['msg' => '' ]);
    }
    
}
