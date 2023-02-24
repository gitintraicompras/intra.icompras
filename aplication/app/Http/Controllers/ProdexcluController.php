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

class ProdexcluController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth');
    }
 
    public function index(Request $request) {
    	if ($request) {
            set_time_limit(500);  
            $cont = 0;
            $filtro = trim($request->get('filtro'));
            $codcli = sCodigoClienteActivo();
            $cliente = DB::table('maecliente')
            ->where('codcli','=',$codcli)
            ->first();
            $provs = TablaMaecliproveActivaOfertas();
            $invent = DB::table('inventario_'.$codcli)
                     ->where('cuarentena', '=', '0')
                     ->where('costo', '>', 0)
                     ->where('cantidad','>',0)
                     ->where(function ($q) use ($filtro) {
                         $q->where('barra','LIKE','%'.$filtro.'%')
                         ->Orwhere('codprod','LIKE','%'.$filtro.'%')
                         ->Orwhere('desprod','LIKE','%'.$filtro.'%')
                         ->Orwhere('marca','LIKE','%'.$filtro.'%');
                     })
                     ->get();
            foreach ($invent as $inv) {
                $tpmaestra = DB::table('tpmaestra')
                ->where('barra','=',$inv->barra)
                ->first();
                if (empty($tpmaestra)) {
                    $catalogo[] = $inv;
                    $cont++;
                } else {
                    $invConsol = 0;
                    foreach ($provs as $prov) { 
                        $codprove = strtolower($prov->codprove);
                        $campos = $tpmaestra->$codprove;
                        $campo = explode("|", $campos);
                        $precio = $campo[0];
                        $cantidad = $campo[1];
                        if ($cantidad > 0 && $precio > 0 ) {
                            $invConsol = $invConsol + $cantidad;
                        }
                    }
                    if ($invConsol == 0 ) {
                        $catalogo[] = $inv;
                        $cont++;
                    }
                }
            }
            if (empty($catalogo))
                return Redirect::to('/prodexclu');
            $subtitulo = "EXCLUSIVOS (".number_format($cont,0, '.', ',')." productos)";
    		return view('ofertas.prodexclu.index' ,["menu" => "Exclusivos",
                                                    "tabla" => $catalogo, 
     				   	                            "filtro" => $filtro,
                                                    "cfg" => DB::table('maecfg')->first(),
    						   		                "subtitulo" => $subtitulo]);
    	}
    }
 
    
}
