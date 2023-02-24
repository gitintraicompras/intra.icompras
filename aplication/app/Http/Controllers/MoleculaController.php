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
   
class MoleculaController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
 
    public function index(Request $request) {
        if ($request) {
            $filtro = $request->get('filtro');
            $tabla = DB::table('molecula')
            ->where('descrip','LIKE','%'.$filtro.'%')
            ->orderBy('descrip','asc')
            ->get();
            return view('isacom.molecula.index',["tabla" => $tabla, 
                                                  "menu" => "Moleculas",
                                                  "subtitulo" => "MOLECULAS",
                                                  "filtro" => $filtro]);
        }
    }

    public function show($id) {
        $reg = DB::table('molecula')
        ->where('ID','=',$id)
        ->first();

        $moleren = DB::table('prodcaract')
        ->where('molecula','=',$reg->descrip)
        ->get();

        return view("isacom.molecula.show",["menu" => "Moleculas",
                                            "subtitulo" => "CONSULTA DE MOLECULAS",
                                            "moleren" => $moleren,
                                            "reg" => $reg]);
    }

    public function create() {
        return view("isacom.molecula.create",["menu" => "Moleculas",
                                              "subtitulo" => "CREAR MOLECULA NUEVA" ]);
    }
  
    public function store(Request $request) {
        try {
            $existe = 0;
            $descrip = mb_strtoupper($request->get('descrip'));
            $tabla = DB::table('molecula')->get();
            foreach ($tabla as $t) {
                $descripx = trim(mb_strtoupper($t->descrip));
                if ($descripx == $descrip) {
                    $existe = 1;
                    break;
                }
            }
            if ($existe > 0) {
               session()->flash('error','MOLECULA YA EXISTE!!!');
            } else {
                DB::table('molecula')->insert([
                    'descrip' => $descrip
                ]);
                session()->flash('message','GUARDADA SATISFACTORIAMENTE!!!');
            }
        } catch (Exception $e) {
            session()->flash('error','MOLECULA YA EXISTE!!!');
        }  
        return Redirect::to('/molecula');
    }

    public function destroy($id) {
        try {
            DB::table('molecula')
            ->where('id','=',$id)
            ->delete();
            session()->flash('message','ELIMINACION SATISFACTORIA!!!');
        } catch (Exception $e) {
            session()->flash('error', $e);
        }
        return Redirect::to('/molecula');
    }

    public function edit($id) {
        $reg = DB::table('molecula')
        ->where('ID','=',$id)
        ->first();

        $moleren = DB::table('prodcaract')
        ->where('molecula','=',$reg->descrip)
        ->get();
 
        $arrayprod = array();
        $prodtemp = DB::table('tpmaestra')
        ->orderBy('desprod','desc')
        ->get();
        foreach ($prodtemp as $prod) {
            $prodcaract = DB::table('prodcaract')
            ->where('barra','=',$prod->barra)
            ->first();
            if ($prodcaract) {
                if ($prodcaract->molecula != 'POR DEFINIR')
                    continue;
            }
            $arrayprod[] = $prod;
        }
        $prods = collect($arrayprod);
        //dd($prods);
        //$catalogo = ColectionPaginate::paginate($catalogo, 1000);
        return view("isacom.molecula.edit",["menu" => "Molecula",
                                            "subtitulo" => "EDITAR MOLECULA",
                                            "prods" => $prods,
                                            "moleren" => $moleren,
                                            "reg" => $reg]);
    }
 
    public function update(Request $request, $id) {
        $modo = $request->get('modo');
        $descrip = $request->get('descrip');
        if ($modo == 'AGREGAR') {
            $barra = $request->get('barra');
            $unidadmolecula = $request->get('unidadmolecula');
            $reg = DB::table('prodcaract')
            ->where('barra','=',$barra)
            ->first();
            if (empty($reg)) {
                DB::table('prodcaract')->insert([
                'barra' => $barra,
                'marca' => 'POR DEFINIR',
                'molecula' => $descrip,
                'unidadmolecula' => $unidadmolecula,
                'categoria' => 'POR DEFINIR' ]);
            } else {
                DB::table('prodcaract')
                ->where('barra','=',$barra)
                ->update(array('molecula' => $descrip,
                               'unidadmolecula' => $unidadmolecula
                ));
            }
            $reg = DB::table('tpmaestra')
            ->where('barra','=',$barra)
            ->first();
            if (!empty($reg)) {
                DB::table('tpmaestra')
                ->where('barra','=',$barra)
                ->update(array('molecula' => $descrip));
            }
        } else {
            DB::table('molecula')
            ->where('id', '=', $id)
            ->update(array("descrip" => $descrip ));
        }
        return Redirect::to('/molecula/'.$id.'/edit');
    }

    public function delprod($id) {
        $s1 = explode('-', $id );
        $id = $s1[0];
        $barra = $s1[1];
        DB::table('prodcaract')
        ->where('barra','=',$barra)
        ->update(array('molecula' => 'POR DEFINIR'));
        DB::table('tpmaestra')
        ->where('barra','=',$barra)
        ->update(array('molecula' => 'POR DEFINIR'));
        return Redirect::to('/molecula/'.$id.'/edit');
    }

 }
 
