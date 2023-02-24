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
use App\tpmaestra;
use App\CustomClasses\ColectionPaginate;
   
 
class OfertasController extends Controller
{
    public function __construct() {
    	$this->middleware('auth');
    }

    public function index(Request $request) {
        $subtitulo = "LISTADO DE OFERTAS ACTIVAS";
        $codcli = sCodigoClienteActivo();
        $filtro = trim($request->get('filtro'));
        $contador=0;
        $tabla = 'inventario_'.$codcli;
        if (VerificaTabla($tabla)) {
            $invent = DB::table($tabla)
            ->where('da', '>', 0.00)
            ->where('cantidad', '>', 0)
            ->where(function ($q) use ($filtro) {
                $q->orwhere('desprod','LIKE','%'.$filtro.'%')
                ->orwhere('codprod','LIKE','%'.$filtro.'%');
            })
            ->orderBy('da','desc')
            ->get();
            //dd($invent);
        }
        return view('ofertas.ofertas.index' ,["menu" => "Ofertas",
                                              "cfg" => DB::table('maecfg')->first(),
                                              "codcli" => $codcli,
                                              "invent" => $invent,
                                              "filtro" => $filtro,
                                              "subtitulo" => $subtitulo ]);
    }

    public function destroy(Request $request, $codprod) {
        try {
            DB::beginTransaction();
            $id = $request->get('id');
            $desprod = $request->get('desprod');
            $codcli = sCodigoClienteActivo();
            $id = DB::table('regofertas')->insertGetId([
                'codcli' => $codcli,
                'fecha' => date('Y-m-d H:i:s'), 
                'usuario' => Auth::user()->name, 
                'status' => '0',
                'desde' => date('Y-m-d H:i:s'),
                'hasta' => date('Y-m-d H:i:s'), 
                'observ' => "ELIMINAR OFERTA: ".$codprod,
                'ppsa' => 0
            ]);
            DB::table('regoferen')->insert([
                'id' => $id,
                'codcli' => $codcli,
                'codprod' => $codprod,
                'desprod' => $desprod,
                'da' => 0.00,
                'ps' => 0.00,
                'precio' => 0.00
            ]);
            $tabla = 'inventario_'.$codcli;
            if (VerificaTabla($tabla)) {
                DB::table($tabla)
                ->where('codprod','=',$codprod)
                ->update(array("da" => 0.00));
            }
            //dd('codprod: '.$codprod.' - ID: '.$id);
            DB::commit();
            session()->flash('message', 'Ofertas '.$id.' eliminado satisfactoriamente');
        } catch (Exception $e) {
            DB::rollBack();
            session()->flash('error', $e);
        }
        return Redirect::to('/ofertas/ofertas');
    }
}
