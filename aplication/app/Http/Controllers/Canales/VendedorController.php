<?php
namespace App\Http\Controllers\Canales;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use DB; 

class VendedorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
 
    public function index(Request $request) {
    	if ($request) {
        $codcanal = Auth::user()->codcli;
    		$filtro=trim($request->get('filtro'));
    		$regs = DB::table('vendedor')
        ->where('codcanal','=',$codcanal)
        ->where(function ($q) use ($filtro) {
            $q->where('codvendedor','LIKE','%'.$filtro.'%')
            ->orwhere('nombre','LIKE','%'.$filtro.'%');
        })
     		->orderBy('nombre','desc')
        ->get();
    		return view('canales.vendedor.index',["menu" => "Vendedores",
                                              "regs" => $regs,
                                              "subtitulo" => "VENDEDORES",
                                              "filtro" => $filtro]);
    	}
    }

    public function create() {
    	return view("canales.vendedor.create", ["menu" => "Vendedores",
                                              "subtitulo" => "NUEVO VENDEDOR",]);
    } 

    public function store(Request $request) {
        $codvendedor = strtoupper($request->get('codvendedor'));
        if (empty($codvendedor)) {
            return redirect()->action('Canales\VendedorController@index')->with('error', 'Código del vendedor no puede ir en blanco');
        }
        $nombre = strtolower($request->get('nombre'));
        if (empty($nombre)) {
            return redirect()->action('Canales\VendedorController@index')->with('error', 'Nombre no puede ir en blanco');
        }
        DB::table('vendedor')->insert([
           "codvendedor" => $codvendedor,
           "nombre" => strtoupper($nombre),
           "codcanal" => Auth::user()->codcli
        ]);
        session()->flash('message','Vendedor creado satisfactoriamente');
        return Redirect::to('/canales/vendedor');
    }

    public function edit($id) {
      $reg = DB::table('vendedor')
      ->where('codvendedor','=',$id)
      ->where('codcanal','=',Auth::user()->codcli)
      ->first();
    	return view("canales.vendedor.edit",["menu" => "Vendedores",
                                           "subtitulo" => "EDITAR VENDEDOR",
                                           "reg" => $reg]);
    }

    public function update(Request $request, $id) {
        DB::table('vendedor')
        ->where('codvendedor', '=', $id)
        ->where('codcanal','=',Auth::user()->codcli)
        ->update(array("nombre" => strtoupper($request->get('nombre'))));
   	    return Redirect::to('/canales/vendedor');
    }

    public function destroy($id) {
      DB::table('vendedor')
      ->where('codvendedor','=',$id)
      ->where('codcanal','=',Auth::user()->codcli)
      ->delete();
      session()->flash('message','Vendedor eliminado satisfactoriamente');
    	return Redirect::to('/canales/vendedor');
    }

}
