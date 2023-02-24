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
use App\Http\Requests\MaeclieproveFormRequest;
use App\Http\Requests\MaeproveFormRequest;
use Illuminate\Support\Facades\Session;
use App\Maecliente;
use App\Maeprove;
use App\Maeclieprove;
use Illuminate\Support\Facades\Config;
use DB; 
   
class ProveedorController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
 
    public function index(Request $request) {
        if ($request) {
            $filtro=trim($request->get('filtro'));
            $subtitulo = "PROVEEDORES";
            $codcli = sCodigoClienteActivo();
            $tabla = DB::table('maecliente')
            ->where('codcli','=',$codcli)
            ->first();
 
            $cliente = Maecliente::all();
            $provs = null;
            if ($codcli) {
                ReordenarPrefereciaProve();
                if (Auth::user()->tipo == 'O') {
                    $provs = Maeclieprove::select('*', 'maeclieprove.statusOfertas as statusclieprove')
                    ->leftjoin('maeprove', 'maeclieprove.codprove', '=', 'maeprove.codprove')
                    ->where("codcli", "=", $codcli)
                    ->where("maeprove.status", "=", 'ACTIVO')
                    ->where(function ($q) use ($filtro) {
                        $q->where('maeclieprove.codprove','LIKE','%'.$filtro.'%')
                        ->orwhere('descripcion','LIKE','%'.$filtro.'%')
                        ->orwhere('codigo','LIKE','%'.$filtro.'%');
                    })
                    ->orderBy("preferencia","asc")
                    ->get();
                }
                else {
                    $provs = Maeclieprove::select('*', 'maeclieprove.status as statusclieprove')
                    ->leftjoin('maeprove', 'maeclieprove.codprove', '=', 'maeprove.codprove')
                    ->where("codcli", "=", $codcli)
                    ->where("maeprove.status", "=", 'ACTIVO')
                    ->where(function ($q) use ($filtro) {
                        $q->where('maeclieprove.codprove','LIKE','%'.$filtro.'%')
                        ->orwhere('descripcion','LIKE','%'.$filtro.'%')
                        ->orwhere('codigo','LIKE','%'.$filtro.'%');
                    })
                    ->orderBy("preferencia","asc")
                    ->get();
                }
            }
            return view('isacom.proveedor.index',["cliente" => $cliente,
                                                  "menu" => "Proveedor",
                                                  "cfg" => DB::table('maecfg')->first(),
                                                  "provs" => $provs,
                                                  "tabla" => $tabla,
                                                  "codcli" => $codcli,
                                                  "filtro" => $filtro,
                                                  "subtitulo" => $subtitulo]);
        }
    }

    public function cargar($id) {
        $maeprove = DB::table('maeprove')
        ->where('codprove', '=', $id)
        ->first();
        $subtitulo = "CARGAR CATALOGO DE PROVEEDOR (".$maeprove->descripcion.")";
        return view('isacom.proveedor.create' ,["menu" => "Proveedor",
                                                "cfg" => DB::table('maecfg')->first(),
                                                "codprove" => $id,
                                                "maeprove" => $maeprove,
                                                "formato" => '1',
                                                "subtitulo" => $subtitulo]);
    } 

    public function lista() {
        $provs = array();
        $codcli = sCodigoClienteActivo();
        $proves = DB::table('maeprove')
        ->where('status','=','ACTIVO')
        ->orderBy('region','asc')
        ->get();
        return view("isacom.proveedor.lista",["provs" => $proves, 
                                              "menu" => "Proveedores",
                                              "cfg" => DB::table('maecfg')->first(),
                                              "subtitulo" => "LISTA DE PROVEEDORES",
                                              "codcli" => $codcli ]);
    }

    public function show($id) {
        $codcli = sCodigoClienteActivo();
        $provs=DB::table('maeclieprove as C')
        ->join('maeprove as P','C.codprove','=','P.codprove')
        ->select('*', 'P.descripcion AS nomprov')
        ->where('C.codprove','=',$id)
        ->where('codcli','=',$codcli)
        ->first();
        return view("isacom.proveedor.show",["provs" => $provs,
                                             "menu" => "Proveedores",
                                             "subtitulo" => "CONSULTA DE PROVEEDOR",
                                             "cfg" => DB::table('maecfg')->first(),
        ]);
    }

    public function destroy($id) {
        $codcli = sCodigoClienteActivo();
        $reg = DB::table('maeclieprove')
        ->where('codcli','=',$codcli)
        ->where('codprove','=',$id)
        ->delete();
        session()->flash('message','Proveedor eliminado satisfactoriamente');
        return Redirect::to('/proveedor');
    }

    public function store(Request $request) {
        $modalidad = $request->get('modalidad');
        if ($modalidad == "AGREGAR") {
            if ( empty($request->get('codigo'))) {
                return redirect()->action('ProveedorController@lista')->with('error', 'Provveedor no agregado, código del proveedor no puede ir en blanco');
            }
            $reg = new Maeclieprove();
            $reg->codcli = $request->get('codcli');
            $reg->codprove = $request->get('codprove');
            $reg->codigo = $request->get('codigo');
            $reg->subcarpetaftp = $request->get('subcarpetaftp');
            $reg->dcredito = $request->get('dcredito');
            $reg->mcredito = $request->get('mcredito');
            $reg->corte = $request->get('corte');
            $reg->dcme = $request->get('dcme');
            $reg->dcmer = $request->get('dcmer');
            $reg->dcmi = $request->get('dcmi');
            $reg->dcmir = $request->get('dcmir');
            $reg->ppme = $request->get('ppme');
            $reg->ppmi = $request->get('ppmi');
            $reg->di = $request->get('di');
            $reg->dotro = $request->get('dotro');
            $reg->usuario = $request->get('usuario');
            $reg->tipoprecio = $request->get('tipoprecio');
            $reg->clave = $request->get('clave');
            $reg->status = "ACTIVO";
            $reg->updCondComercial = ($request->get('updCondComercial')=='on') ? '1': '0';
            $reg->save();
            if ($request->get('updCondComercial')=='on')
                UpdateCondComercialCliente($request->get('codcli'), $request->get('codprove'));
            session()->flash('message','Proveedor agregado satisfactoriamente');
        } else {
            // CARGAR CATALOGO
            $codprove = $request->get('codprove');
            try {
                $tabla = strtolower($codprove);
                if (!VerificaTabla($tabla)) {
                    $reg = DB::statement('CREATE TABLE '.$tabla.' (
                        `codprod` varchar(20) NOT NULL,
                        `barra` varchar(20) NOT NULL,
                        `desprod` varchar(250) NOT NULL,
                        `iva` decimal(18,2) DEFAULT NULL,
                        `precio1` decimal(18,2) DEFAULT NULL,
                        `precio2` decimal(18,2) DEFAULT NULL,
                        `precio3` decimal(18,2) DEFAULT NULL,
                        `cantidad` decimal(18,2) DEFAULT NULL,
                        `bulto` varchar(50) DEFAULT NULL,
                        `da` decimal(18,2) DEFAULT NULL,
                        `marca` varchar(200) DEFAULT NULL,
                        `categoria` varchar(200) DEFAULT NULL,
                        `fechafalla` datetime DEFAULT NULL,
                        `fechacata` datetime DEFAULT NULL,
                        `tipo` varchar(1) NULL,
                        `regulado` varchar(1) NULL,
                        `nomprov` varchar(100) NULL,
                        `lote` varchar(50) NULL,
                        `fecvence` varchar(50) NULL,
                        `metadata` varchar(500) NULL
                    ) ENGINE=MyISAM DEFAULT CHARSET=latin1;');
                    $reg = DB::statement('ALTER TABLE '.$tabla.' ADD PRIMARY KEY (`codprod`);');
                    log::info("SCP-> CREATE: ".$tabla." satisfactoriamente");
                }
            } catch (\Exception $e) {
                log::info("SCP-> ERROR CREANDO TABLA: ".$tabla." - ".$e->getMessage());
            }
            $formato = $request->get('formato');

            if($request->hasfile('linkarchivo')) {
                $file = $request->file('linkarchivo');
                $nomcatalogo = $file->getClientOriginalName();
                $file->move(public_path().'/public/storage/', $nomcatalogo);

                $rutacatalogo = public_path().'/public/storage/'.$nomcatalogo;
                if (bConvCatalogoProvCatalogoISB($rutacatalogo, $codprove, $formato)) {
                    DB::table('maecfg')->update(array("forzarsinc" => 1));
                    session()->flash('message','Su Catálogo será procesado en un par de minutos');
                } else {
                    session()->flash('message','Catálogo cargado satisfactoriamente');
                }
            }
        }
        return Redirect::to('/');
        //return Redirect::back();
        //return Redirect::to('/proveedor');
    }

    public function edit($codprove) {
        $codcli = sCodigoClienteActivo();
        $provs = Maeclieprove::select('*', 'maeclieprove.status as statusclieprove')
        ->leftjoin('maeprove', 'maeclieprove.codprove', '=', 'maeprove.codprove')
        ->where("codcli","=", $codcli)
        ->where("maeclieprove.codprove","=", $codprove)
        ->orderBy('preferencia', 'asc')
        ->first();

        $maeprove=DB::table('maeprove')
        ->where('codprove','=',$codprove)
        ->first();
        return view("isacom.proveedor.edit",["provs" => $provs,
                                             "menu" => "Proveedores",
                                             "maeprove" => $maeprove,
                                             "cfg" => DB::table('maecfg')->first(),
                                             "subtitulo" => "EDITAR PROVEEDOR"
        ]);
    }
    
    public function sumarpref(Request $request) {

        $codprove = $request->get('codprove');
        $pref = $request->get('pref');

        $msg = "OK";
        $codcli = sCodigoClienteActivo();

        $cont = intval($pref) + 1;
        $sig = "00".$cont;
        $sig = substr($sig,strlen($sig)-2,2);

        $reg = DB::table('maeclieprove')
        ->where('codcli', '=', $codcli)
        ->where('preferencia', '=', $sig)
        ->first();

        if ($reg) {
       
            DB::table('maeclieprove')
            ->where('codcli', '=', $codcli)
            ->where('preferencia', '=', $sig)
            ->update(array("preferencia" => $pref));

            DB::table('maeclieprove')
            ->where('codcli', '=', $codcli)
            ->where('preferencia', '=', $pref)
            ->where('codprove', '=', $codprove)
            ->update(array("preferencia" => $sig));

        }
        return response()->json(['msg' => $msg ]);
    }

    public function restarpref(Request $request) {

        $codprove = $request->get('codprove');
        $pref = $request->get('pref');

        $msg = "OK";
        $codcli = sCodigoClienteActivo();

        $cont = intval($pref) - 1;
        $ant = "00".$cont;
        $ant = substr($ant,strlen($ant)-2,2);

        $reg = DB::table('maeclieprove')
        ->where('codcli', '=', $codcli)
        ->where('preferencia', '=', $ant)
        ->first();

        if ($reg) {
       
            DB::table('maeclieprove')
            ->where('codcli', '=', $codcli)
            ->where('preferencia', '=', $ant)
            ->update(array("preferencia" => $pref));

            DB::table('maeclieprove')
            ->where('codcli', '=', $codcli)
            ->where('preferencia', '=', $pref)
            ->where('codprove', '=', $codprove)
            ->update(array("preferencia" => $ant));

        }
        return response()->json(['msg' => $msg ]);
    }

    public function verprov($id) {
        $subtitulo = "VER PROVEEDOR";
        $codcli = sCodigoClienteActivo();
        $tabla = DB::table('maeprove')
        ->where('codprove','=',$id)
        ->first();

        $maeclieprove = DB::table('maeclieprove')
        ->where('codcli','=',$codcli)
        ->where('codprove','=',$id)
        ->first();

        $confprov = LeerProve($id); 
        return view('isacom.proveedor.verprov',["menu" =>"Proveedores",
                                                "tabla" => $tabla,
                                                "subtitulo" => $subtitulo,
                                                "confprov" => $confprov,
                                                "maeclieprove" => $maeclieprove,
                                                "cfg" => DB::table('maecfg')->first() ]);
    }

    public function agregar($id) {
        $s1 = explode('-', $id );
        $codcli = sCodigoClienteActivo();
        $codprove = $s1[0];
        $prove = DB::table('maeprove')
        ->where('codprove','=',$codprove)
        ->first();
        return view("isacom.proveedor.agregar",["prove" => $prove,
                                              "menu" => "Proveedores",
                                              "cfg" => DB::table('maecfg')->first(),
                                              "subtitulo" => "AGREGAR DE PROVEEDOR",
                                              "codcli" => $codcli ]);
    }
 
    public function update(Request $request, $id) {
        $s1 = explode('-', $id );
        $codcli = sCodigoClienteActivo();
        $codprove = $s1[0];
        DB::table('maeclieprove')
        ->where('codprove', $codprove)
        ->where('codcli', $codcli)
        ->update(
            array(
                'codigo' => $request->get('codigo'),
                'subcarpetaftp' => $request->get('subcarpetaftp'),
                'dcredito' => $request->get('dcredito'),
                'mcredito' => tofloat($request->get('mcredito')),
                'corte' => $request->get('corte'),
                'dcme' => tofloat($request->get('dcme')),
                'dcmer' => tofloat($request->get('dcmer')),
                'dcmi' => tofloat($request->get('dcmi')),
                'dcmir' => tofloat($request->get('dcmir')),
                'ppme' => tofloat($request->get('ppme')),
                'ppmi' => tofloat($request->get('ppmi')),
                'di' => tofloat($request->get('di')),
                'dotro' => tofloat($request->get('dotro')),
                'usuario' => $request->get('usuario'),
                'clave' => $request->get('clave'),
                'tipoprecio' => $request->get('tipoprecio'),
                'status' => $request->get('statusclieprove'),
                'codprove_adm' => $request->get('codprove_adm'),
                "updCondComercial" => ($request->get('updCondComercial')=='on') ? '1': '0'
            )
        );
        if ($request->get('updCondComercial')=='on')
            UpdateCondComercialCliente($codcli, $codprove);
        
        DB::table('maeprove')
        ->where('codprove', $codprove)
        ->update( array( 'correoEnvioPedido' => $request->get('correoEnvioPedido'),
                         'factorSeleccion' => $request->get('factorSeleccion'),
                         'FactorCambiario' => $request->get('FactorCambiario')
        ));
        return Redirect::to('/proveedor');
    }

    public function descarejemplo(Request $request) {
        try {
            $formato=trim($request->get('formato'));
            $archivo = "catalogoFormato1.csv";
            if ($formato=='2') 
                $archivo = "catalogoFormato2.txt";
            $rutaarc = public_path().'/public/storage/ejemplos/'.$archivo;
            if (file_exists($rutaarc)) {
                $headers = ['Content-type'=>'text/plain', 'test'=>'YoYo', 'Content-Disposition'=>sprintf('attachment; filename="%s"', $archivo),'X-BooYAH'=>'WorkyWorky'];
                return response()->download($rutaarc);
            }
        } catch (Exception $e) {
            session()->flash('error',$e);
        }
    } 

    public function modstatus(Request $request) {
        $codprove = $request->get('codprove');
        $codcli = $request->get('codcli');
        // log::info("CD -> codcli: ".$codcli);
        $maeclieprove = DB::table('maeclieprove')
        ->where('codcli', '=', $codcli)
        ->where('codprove', '=', $codprove)
        ->first();
        if ($maeclieprove) {
            if (Auth::user()->tipo == 'O') {
                $status = $maeclieprove->statusOfertas;
                //log::info("CD -> status: ".$status);
                if ($status == "ACTIVO")
                    $status = "INACTIVO";
                else
                    $status = "ACTIVO";
                DB::table('maeclieprove')
                ->where('codcli', '=', $codcli)
                ->where('codprove', '=', $codprove)
                ->update(array("statusOfertas" => $status));
            } else {
                $status = $maeclieprove->status;
                if ($status == "ACTIVO")
                    $status = "INACTIVO";
                else
                    $status = "ACTIVO";
                DB::table('maeclieprove')
                ->where('codcli', '=', $codcli)
                ->where('codprove', '=', $codprove)
                ->update(array("status" => $status));
            }
            
        }
        return response()->json(['msg' => '' ]);
    }

    public function modcol(Request $request) {
        $codprove = $request->get('codprove');
        $valor = $request->get('valor');
        $campo = $request->get('campo');
        $maeprove = DB::table('maeprove')
        ->where('codprove', '=', $codprove)
        ->first();
        if ($maeprove) {
            DB::table('maeprove')
            ->where('codprove', '=', $codprove)
            ->update(array($campo => $valor));
        }
        return response()->json(['msg' => 'OK' ]);
    }

 }
 
