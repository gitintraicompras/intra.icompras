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
 
class InvminmaxController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth');
    }
 
    public function index(Request $request) {
    	if ($request) {

            $codcli = sCodigoClienteActivo();
        
            $cliente = DB::table('maecliente')
            ->where('codcli','=',$codcli)
            ->first(); 
            if ($cliente) {
                if ($cliente->CampoMarcaInv == "MARCA") {
                    $marca = [];
                    $marcax = DB::table('marca')
                    ->orderBy('descrip','asc')
                    ->get();     
                    foreach ($marcax as $m) {
                        $marca[] = trim($m->descrip);
                    }  
                } else {
                    $marca = GeneraArrayMarcaAlterna($codcli);
                }
            }

            $filtro = trim($request->get('filtro'));
            if (empty($filtro)) { 
                if (count($marca) > 0) {
                    $filtro = $marca[0]; 
                }
            } 
            $tabla = "inventario_".$codcli;
            if (VerificaTabla($tabla)) {
                $tabla = DB::table($tabla)
                ->where(strtolower($cliente->CampoMarcaInv),'=',$filtro)
                ->orderBy('desprod','asc')
                ->paginate(50);
            }
            $subtitulo = "DIAS MINIMO Y MAXIMO ";
    		return view('isacom.invminmax.index' ,["menu" => "Inventario",
                                                   "tabla" => $tabla, 
     				   	                           "filtro" => $filtro,
                                                   "marca" => $marca,
                                                   "codcli" => $codcli,
                                                   "cliente" => $cliente,
                                                   "cfg" => DB::table('maecfg')->first(),
    								               "subtitulo" => $subtitulo]);
    	}
    }
    
    public function modcaract(Request $request) {
        $codprod = trim(strtoupper($request->get('codprod')));
        $campo = trim(strtoupper($request->get('campo')));
        $valor = trim(strtoupper($request->get('valor')));
        $codcli = trim(strtoupper($request->get('codcli')));
        $filtro = trim(strtoupper($request->get('filtro')));
        $CampoMarcaInv = trim(strtoupper($request->get('CampoMarcaInv')));
        $resp = "";
        if (!empty($codprod)) {
            if ($campo == "MIN" || $campo == "MAX" || $campo == "CENDIS") {
                $existe = 0;
                $minmax = DB::table('minmax')
                ->where('codcli', '=', $codcli)
                ->where('codprod', '=', $codprod)
                ->first();
                if ($minmax) 
                    $existe = 1;
                if ($campo == "CENDIS") {
                    if ($existe == 1) {
                        $valor = ($minmax->cendis==1) ? 0 : 1;
                        DB::table('minmax')
                        ->where('codcli', '=', $codcli)
                        ->where('codprod', '=', $codprod)
                        ->update(array('cendis' => $valor));   
                    } else {
                        DB::table('minmax')->insert([
                        'codcli' => $codcli,
                        'codprod' => $codprod,
                        'cendis' => 1 ]);
                    }
                } else { 
                    if ($existe == 1) {
                        DB::table('minmax')
                        ->where('codcli', '=', $codcli)
                        ->where('codprod', '=', $codprod)
                        ->update(array($campo => $valor));   
                    } else {
                        DB::table('minmax')->insert([
                        'codcli' => $codcli,
                        'codprod' => $codprod,
                        $campo => $valor ]);
                    }
                }
            } else {
                if ($campo == "CLIMIN" || $campo == "CLIMAX" || $campo == "CLICENDIS") {
                    $campo = str_replace("CLI", "", $campo );
                    //log::info("CAMPO: ".$campo);
                    $tabla = "inventario_".$codcli;
                    if (VerificaTabla($tabla)) {
                        $tabla = DB::table($tabla)
                        ->where($CampoMarcaInv,'=',$filtro)
                        ->get();
                        foreach ($tabla as $t) { 
                            //log::info("CODPROD: ".$t->codprod);
                            $existe = 0;
                            $minmax = DB::table('minmax')
                            ->where('codcli', '=', $codcli)
                            ->where('codprod', '=', $t->codprod)
                            ->first();
                            if ($minmax) 
                                $existe = 1;
                            if ($campo == "CENDIS") {
                                if ($existe == 1) {
                                    $valor = ($minmax->cendis==1) ? 0 : 1;
                                    DB::table('minmax')
                                    ->where('codcli', '=', $codcli)
                                    ->where('codprod', '=', $t->codprod)
                                    ->update(array('cendis' => $valor));   
                                } else {
                                    DB::table('minmax')->insert([
                                    'codcli' => $codcli,
                                    'codprod' => $t->codprod,
                                    'cendis' => 1 ]);
                                }
                            } else { 
                                if ($existe == 1) {
                                    $campo1 = strtolower($campo);
                                    //log::info("VALOR: ".$minmax->$campo1);
                                    //if ($minmax->$campo1 == 0) {
                                        DB::table('minmax')
                                        ->where('codcli', '=', $codcli)
                                        ->where('codprod', '=', $t->codprod)
                                        ->update(array($campo => $valor));   
                                    //}
                                } else {
                                    DB::table('minmax')->insert([
                                    'codcli' => $codcli,
                                    'codprod' => $t->codprod,
                                    $campo => $valor ]);
                                }
                            }

                        }
                    }
                } 
            }
        }
        return response()->json(['msg' => $resp ]);
    }
    
}
