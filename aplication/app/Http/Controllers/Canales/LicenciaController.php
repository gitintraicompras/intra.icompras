<?php
namespace App\Http\Controllers\Canales;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use App\CustomClasses\ColectionPaginate;
use DB;  

class LicenciaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
 
    public function index(Request $request) {
    	if ($request) {
            $subtitulo = "LICENCIAS";
            $codcanal = Auth::user()->codcli;
    		$filtro = trim($request->get('filtro'));
            $canal = DB::table('maecanales')
            ->where('codcanal','=',$codcanal)
            ->first();
            if ($canal) {
                // VEISAAP0000100100192
                // VE    => PAIS
                // ISAAP => SOFTWARE
                // 00001 => CANAL
                if ($canal->super > 0) {
                    $subtitulo = "LICENCIAS SUPERVISOR";
                    $regs = DB::table('maelicencias as l')
                    ->leftjoin('maecliente as c', 'c.codcli', '=', 'l.codisb')
                    ->where('l.estado','=','P')
                    ->where('l.codisb','!=','')
                    ->where(function ($q) use ($filtro) {
                        $q->where('c.codcli','LIKE','%'.$filtro.'%')
                        ->orwhere('nombre','LIKE','%'.$filtro.'%')
                        ->orwhere('cod_lic','LIKE','%'.$filtro.'%');
                    })
                    ->get();
                    $reg_array = array();
                    foreach ($regs as $reg) { 
                      $codcanalx = substr($reg->cod_lic, 7, 5);
                      $codsoftx = substr($reg->cod_lic, 2, 5);
                      if ($codsoftx == 'ISACO') {
                        $reg->restan = iValidarLicencia($reg->codisb);
                        $reg->codcanal = $codcanalx;
                        $reg_array[] = $reg;
                      }
                    }
                    $tab = collect($reg_array);
                    $tab = $tab->sortBy('restan');
                    $regs = ColectionPaginate::paginate($tab, 100);
                } else {
                	$regs = DB::table('maelicencias as l')
                    ->leftjoin('maecliente as c', 'c.codcli', '=', 'l.codisb')
                    ->where('l.estado','=','P')
                    ->where('l.codisb','!=','')
                    ->where(function ($q) use ($filtro) {
                        $q->where('c.codcli','LIKE','%'.$filtro.'%')
                        ->orwhere('nombre','LIKE','%'.$filtro.'%')
                        ->orwhere('cod_lic','LIKE','%'.$filtro.'%');
                    })
                    ->get();
                    $reg_array = array();
                    foreach ($regs as $reg) { 
                      $codcanalx = substr($reg->cod_lic, 7, 5);
                      $codsoftx = substr($reg->cod_lic, 2, 5);
                      if ($codcanalx == $codcanal && $codsoftx == 'ISACO') {
                        $reg->restan = iValidarLicencia($reg->codisb);
                        if ($reg->restan <= 30) {
                            $reg->codcanal = $codcanalx;
                            $reg_array[] = $reg;
                        }
                      }
                    }
                    $tab = collect($reg_array);
                    $tab = $tab->sortBy('restan');
                    $regs = ColectionPaginate::paginate($tab, 100);
                }
        		return view('canales.licencia.index',["menu" => "Licencias",
                                                      "regs" => $regs,
                                                      "canal" => $canal,
                                                      "subtitulo" => $subtitulo,
                                                      "filtro" => $filtro]);
            }
    	}
    }

    public function destroy($id) {
      $tabla = DB::table('maelicencias')
      ->where('cod_lic','=',$id)
      ->first();
      if ($tabla) {
        $codcli = $tabla->codisb;

        // ELIMINA EL USUARIO
        DB::table('users')
        ->where('codcli','=',$codcli)
        ->where('tipo','=','C')
        ->delete();

        // ELIMINA LA LICECNIA
        DB::table('maelicencias')
        ->where('cod_lic','=',$id)
        ->delete();
        session()->flash('message','Vendedor eliminado satisfactoriamente');
      }
    	return Redirect::to('/canales/licencia');
    }

    
}
