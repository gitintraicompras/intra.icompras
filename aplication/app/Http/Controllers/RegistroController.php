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
   
 
class RegistroController extends Controller
{
    public function __construct() {
    	$this->middleware('auth');
    }

    public function index(Request $request) {
        $subtitulo = "REGISTRO DE OFERTAS";
        $codcli = sCodigoClienteActivo();
        $desde=trim($request->get('desde'));
        $hasta=trim($request->get('hasta'));
        if ($desde=='' || $hasta=='') {
            $hasta = date('Y-m-d');
            $desde = date('Y-m-d', strtotime('-7 day', strtotime($hasta)));
        }
        $desde = $desde.' 00:00:00';
        $hasta = $hasta.' 23:59:00';
        $tabla = DB::table('regofertas')
        ->where('codcli','=',$codcli)
        ->whereBetween('fecha', array($desde, $hasta))
        ->orderBy('id','desc')
        ->paginate(100);
        return view('ofertas.registros.index' ,["menu" => "Registro",
                                                 "cfg" => DB::table('maecfg')->first(),
                                                 "codcli" => $codcli,
                                                 "tabla" => $tabla,
                                                 "desde"=>$desde,
                                                 "hasta"=>$hasta,
                                                 "subtitulo" => $subtitulo ]);
    }
   
    public function show($id) {
        $subtitulo = "CONSULTA DE OFERTAS";
        $reg = DB::table('regofertas')
        ->where('id','=',$id)
        ->first();
        $tabla = DB::table('regoferen')
        ->where('id','=',$id)
        ->orderBy('desprod','asc')
        ->get();
        return view('ofertas.registros.show',["menu" => "Registro",
                                               "cfg" => DB::table('maecfg')->first(),
                                               "reg" => $reg, 
                                               "tabla" => $tabla, 
                                               "subtitulo" => $subtitulo ]);
    }

    public function catalogo(Request $request, $tipo) {
        set_time_limit(500);  
        $filtro = trim($request->get('filtro'));
        $campo = explode("*", $filtro);
        $contador = count($campo);
        $codcli = sCodigoClienteActivo();
        $cliente = DB::table('maecliente')
        ->where('codcli','=',$codcli)
        ->first();
        $provs = TablaMaecliproveActivaOfertas();
        $subtitulo = "MAESTRO DE PRODUCTOS";
        $invent = 'inventario_'.$codcli;
        if ($contador == 1) {
            switch ($tipo) {
                case 'T':
                    $subtitulo = "ANALISIS DE TODOS LOS PRODUCTOS";
                    $catalogox = tpmaestra::select('*', 'tcm.desprod as descrip', 'tpmaestra.marca as marca')
                    ->join($invent.' as tcm', 'tcm.barra','=', 'tpmaestra.barra')
                    ->where('tcm.costo','>',0)
                    ->where('tcm.cantidad','>',0)
                    ->where('tcm.cuarentena','=',0)
                    ->where(function ($q) use ($filtro) {
                        $q->orwhere('tcm.desprod','LIKE','%'.$filtro.'%')
                        ->orwhere('tcm.barra','LIKE','%'.$filtro.'%')
                        ->orwhere('tpmaestra.marca','LIKE','%'.$filtro.'%')
                        ->orwhere('tcm.pactivo','LIKE','%'.$filtro.'%')
                        ->orwhere('tcm.descorta','LIKE','%'.$filtro.'%')
                        ->orwhere('tcm.codprod','LIKE','%'.$filtro.'%');
                    })
                    ->orderBy('tcm.desprod','asc')
                    ->get();
                    foreach ($catalogox as $cat) {
                        $dataprod = obtenerColorProd($cat, $cliente, $provs);
                        if ($dataprod['invConsol'] > 0 ) {
                            $catalogo[] = $cat;
                        }
                    }
                    break;
                case 'A':
                    $subtitulo = "ANALISIS DE SOLO LOS PRODUCTOS AMARILLOS";
                    $catalogox = tpmaestra::select('*', 'tcm.desprod as descrip', 'tpmaestra.marca as marca')
                    ->join($invent.' as tcm', 'tcm.barra','=', 'tpmaestra.barra')
                    ->where('tcm.costo','>',0) 
                    ->where('tcm.cantidad','>',0)
                    ->where('tcm.cuarentena','=',0)
                   ->where(function ($q) use ($filtro) {
                        $q->orwhere('tcm.desprod','LIKE','%'.$filtro.'%')
                        ->orwhere('tcm.barra','LIKE','%'.$filtro.'%')
                        ->orwhere('tpmaestra.marca','LIKE','%'.$filtro.'%')
                        ->orwhere('tcm.pactivo','LIKE','%'.$filtro.'%')
                        ->orwhere('tcm.descorta','LIKE','%'.$filtro.'%')
                        ->orwhere('tcm.codprod','LIKE','%'.$filtro.'%');
                    })
                    ->orderBy('tcm.desprod','asc')
                    ->get();
                    foreach ($catalogox as $cat) {
                        $dataprod = obtenerColorProd($cat, $cliente, $provs);
                        if ($dataprod['colorprod'] == 'A' && $dataprod['invConsol'] > 0 ) {
                            $catalogo[] = $cat;
                        }
                    } 
                    break;
                case 'V':
                    $subtitulo = "ANALISIS DE SOLO LOS PRODUCTOS VERDES";
                    $catalogox = tpmaestra::select('*', 'tcm.desprod as descrip', 'tpmaestra.marca as marca')
                    ->join($invent.' as tcm', 'tcm.barra','=', 'tpmaestra.barra')
                    ->where('tcm.costo','>',0)
                    ->where('tcm.cantidad','>',0)
                    ->where('tcm.cuarentena','=',0)
                    ->where(function ($q) use ($filtro) {
                        $q->orwhere('tcm.desprod','LIKE','%'.$filtro.'%')
                        ->orwhere('tcm.barra','LIKE','%'.$filtro.'%')
                        ->orwhere('tpmaestra.marca','LIKE','%'.$filtro.'%')
                        ->orwhere('tcm.pactivo','LIKE','%'.$filtro.'%')
                        ->orwhere('tcm.descorta','LIKE','%'.$filtro.'%')
                        ->orwhere('tcm.codprod','LIKE','%'.$filtro.'%');
                    })
                    ->orderBy('tcm.desprod','asc')
                    ->get();
                    foreach ($catalogox as $cat) {
                        $dataprod = obtenerColorProd($cat, $cliente, $provs);
                        if ($dataprod['colorprod'] == 'V' && $dataprod['invConsol'] > 0
                            && $dataprod['precioInv'] > 0) {
                            $catalogo[] = $cat;
                            $contador++;
                        }
                    }
                    break;
                case 'R':
                    $subtitulo = "ANALISIS DE SOLO LOS PRODUCTOS ROJO";
                    $catalogox = tpmaestra::select('*', 'tcm.desprod as descrip', 'tpmaestra.marca as marca')
                    ->join($invent.' as tcm', 'tcm.barra','=', 'tpmaestra.barra')
                    ->where('tcm.costo','>',0)
                    ->where('tcm.cantidad','>',0)
                    ->where('tcm.cuarentena','=',0)
                    ->where(function ($q) use ($filtro) {
                        $q->orwhere('tcm.desprod','LIKE','%'.$filtro.'%')
                        ->orwhere('tcm.barra','LIKE','%'.$filtro.'%')
                        ->orwhere('tpmaestra.marca','LIKE','%'.$filtro.'%')
                        ->orwhere('tcm.pactivo','LIKE','%'.$filtro.'%')
                        ->orwhere('tcm.descorta','LIKE','%'.$filtro.'%')
                        ->orwhere('tcm.codprod','LIKE','%'.$filtro.'%');
                    })
                    ->orderBy('tcm.desprod','asc')
                    ->get();
                    foreach ($catalogox as $cat) {
                        $dataprod = obtenerColorProd($cat, $cliente, $provs);
                        if ($dataprod['colorprod'] == 'R' && $dataprod['invConsol'] > 0
                            && $dataprod['precioInv'] > 0) {
                            $catalogo[] = $cat;
                        }
                    }
                    break;
            }
        } else {
            // BUSQUEDA COMPUESTA (FILTRO)
            $filtro1 = $campo[0];
            $filtro2 = $campo[1];
            switch ($tipo) {
                case 'T':
                    $subtitulo = "ANALISIS DE TODOS LOS PRODUCTOS";
                    $catalogox = tpmaestra::select('*', 'tcm.desprod as descrip')
                    ->join($invent.' as tcm', 'tcm.barra','=', 'tpmaestra.barra')
                    ->where('tcm.costo','>',0)
                    ->where('tcm.cantidad','>',0)
                    ->where('tcm.cuarentena','=',0)
                    ->where(function ($q) use ($filtro1, $filtro2) {
                        $q->where('tcm.desprod','LIKE','%'.$filtro1.'%')
                        ->where('tcm.desprod','LIKE','%'.$filtro2.'%')
                        ->Orwhere(function ($q) use ($filtro1, $filtro2) {
                            $q->where('tcm.desprod','LIKE','%'.$filtro1.'%')
                            ->where('tcm.marca','LIKE','%'.$filtro2.'%');
                        });
                    })
                    ->orderBy('tcm.desprod','asc')
                    ->get();
                    foreach ($catalogox as $cat) {
                        $dataprod = obtenerColorProd($cat, $cliente, $provs);
                        if ($dataprod['invConsol'] > 0 ) {
                            $catalogo[] = $cat;
                        }
                    }
                    foreach ($catalogox as $cat) {
                        $dataprod = obtenerColorProd($cat, $cliente, $provs);
                        if ($dataprod['invConsol'] > 0 ) {
                            $catalogo[] = $cat;
                        }
                    }
                    break;
                case 'A':
                    $subtitulo = "ANALISIS DE SOLO LOS PRODUCTOS AMARILLOS";
                    $catalogox = tpmaestra::select('*', 'tcm.desprod as descrip')
                    ->join($invent.' as tcm', 'tcm.barra','=', 'tpmaestra.barra')
                    ->where('tcm.costo','>',0) 
                    ->where('tcm.cantidad','>',0)
                    ->where('tcm.cuarentena','=',0)
                    ->where(function ($q) use ($filtro1, $filtro2) {
                        $q->where('tcm.desprod','LIKE','%'.$filtro1.'%')
                        ->where('tcm.desprod','LIKE','%'.$filtro2.'%')
                        ->Orwhere(function ($q) use ($filtro1, $filtro2) {
                            $q->where('tcm.desprod','LIKE','%'.$filtro1.'%')
                            ->where('tcm.marca','LIKE','%'.$filtro2.'%');
                        });
                    })
                    ->orderBy('tcm.desprod','asc')
                    ->get();
                    foreach ($catalogox as $cat) {
                        $dataprod = obtenerColorProd($cat, $cliente, $provs);
                        if ($dataprod['colorprod'] == 'A' && $dataprod['invConsol'] > 0 ) {
                            $catalogo[] = $cat;
                        }
                    } 
                    break;
                case 'V': 
                    $subtitulo = "ANALISIS DE SOLO LOS PRODUCTOS VERDES";
                    $catalogox = tpmaestra::select('*', 'tcm.desprod as descrip')
                    ->join($invent.' as tcm', 'tcm.barra','=', 'tpmaestra.barra')
                    ->where('tcm.costo','>',0)
                    ->where('tcm.cantidad','>',0)
                    ->where('tcm.cuarentena','=',0)
                    ->where(function ($q) use ($filtro1, $filtro2) {
                        $q->where('tcm.desprod','LIKE','%'.$filtro1.'%')
                        ->where('tcm.desprod','LIKE','%'.$filtro2.'%')
                        ->Orwhere(function ($q) use ($filtro1, $filtro2) {
                            $q->where('tcm.desprod','LIKE','%'.$filtro1.'%')
                            ->where('tcm.marca','LIKE','%'.$filtro2.'%');
                        });
                    })
                    ->orderBy('tcm.desprod','asc')
                    ->get();
                    foreach ($catalogox as $cat) {
                        $dataprod = obtenerColorProd($cat, $cliente, $provs);
                        if ($dataprod['colorprod'] == 'V' && $dataprod['invConsol'] > 0
                            && $dataprod['precioInv'] > 0) {
                            $catalogo[] = $cat;
                        }
                    }
                    break;
                case 'R':
                    $subtitulo = "ANALISIS DE SOLO LOS PRODUCTOS ROJO";
                    $catalogox = tpmaestra::select('*', 'tcm.desprod as descrip')
                    ->join($invent.' as tcm', 'tcm.barra','=', 'tpmaestra.barra')
                    ->where('tcm.costo','>',0)
                    ->where('tcm.cantidad','>',0)
                    ->where('tcm.cuarentena','=',0)
                    ->where(function ($q) use ($filtro1, $filtro2) {
                        $q->where('tcm.desprod','LIKE','%'.$filtro1.'%')
                        ->where('tcm.desprod','LIKE','%'.$filtro2.'%')
                        ->Orwhere(function ($q) use ($filtro1, $filtro2) {
                            $q->where('tcm.desprod','LIKE','%'.$filtro1.'%')
                            ->where('tcm.marca','LIKE','%'.$filtro2.'%');
                        });
                    })
                    ->orderBy('tcm.desprod','asc')
                    ->get();
                    foreach ($catalogox as $cat) {
                        $dataprod = obtenerColorProd($cat, $cliente, $provs);
                        if ($dataprod['colorprod'] == 'R' && $dataprod['invConsol'] > 0
                            && $dataprod['precioInv'] > 0) {
                            $catalogo[] = $cat;
                        }
                    }
                    break;
            }
        }
        DB::table('sugoferen')
        ->where('codcli','=', $codcli)
        ->delete();
        if (isset($catalogo)) {
            foreach ($catalogo as $sug) {
                $cat = DB::table('tpmaestra as tpm')
                ->select('*', 'tpm.desprod as descrip', 'tpm.marca as marca')
                ->join($invent.' as tcm', 'tcm.barra','=', 'tpm.barra')
                ->where('tpm.barra','=',$sug->barra)
                ->first();
                if (empty($cat))
                    continue;
                $dataprod = obtenerColorProd($cat, $cliente, $provs);
                //if ($cat->da == 0 && $dataprod['da'] == 0) {
                //    continue;
                //}
                DB::table('sugoferen')->insert([
                    'codcli' => $codcli,
                    'barra' => $cat->barra,
                    'codprod' => $cat->codprod,
                    'desprod' => $cat->desprod,
                    'da' => $cat->da,
                    'dasug' => $dataprod['da'],
                    'ps' => $dataprod['precioSug'],
                    'costo' => $dataprod['costo'],
                    'util' => $dataprod['util'],
                    'precio' => $dataprod['precioInv'],
                    'mpc' => $dataprod['mpcFactor'],
                    'invClie' => $cat->cantidad,
                    'invProv' => $dataprod['invConsol'],
                    'codmpc' => $dataprod['tpselect'],
                    'backcolor' => $dataprod['backcolor'],
                    'forecolor' => $dataprod['forecolor'],
                    'title' => $dataprod['title'],
                    'marca' => $cat->marca
                ]);
            }
        }
        $sugoferen = DB::table('sugoferen')
        ->where('codcli','=',$codcli)
        ->orderBy('desprod','asc')
        ->paginate(100);
        return view("ofertas.registros.catalogo",["menu" => "Registro",
                                                   "cfg" => DB::table('maecfg')->first(),
                                                   "sugoferen" => $sugoferen, 
                                                   "filtro" => $filtro,
                                                   "tipo" => $tipo,
                                                   "subtitulo" => $subtitulo,
                                                   "cliente" => $cliente,
                                                   "provs" => $provs,
                                                   "codcli" => $codcli
                                                   ]);
    }

    public function create(Request $request) {
        set_time_limit(500);  
        $tipo = trim($request->get('tipo'));
        $filtro = trim($request->get('filtro'));
        //dd("TIPO:".$tipo." FILTRO: ".$filtro);
        $codcli = sCodigoClienteActivo();
        $cliente = DB::table('maecliente')
        ->where('codcli','=',$codcli)
        ->first();
        $provs = TablaMaecliproveActivaOfertas();
        $subtitulo = "";
        $contador=0;
        $invent = 'inventario_'.$codcli;
        switch ($tipo) {
            case 'T':
                $subtitulo = "LISTA DE OFERTAS (TODOS)";
                $catalogox = tpmaestra::select('*', 'tcm.desprod as descrip')
                ->join($invent.' as tcm', 'tcm.barra','=', 'tpmaestra.barra')
                ->where('tcm.costo','>',0)
                ->where('tcm.cantidad','>',0)
                ->where('tcm.cuarentena','=',0)
                ->where(function ($q) use ($filtro) {
                    $q->orwhere('tcm.desprod','LIKE','%'.$filtro.'%')
                    ->orwhere('tcm.barra','LIKE','%'.$filtro.'%')
                    ->orwhere('tcm.marca','LIKE','%'.$filtro.'%')
                    ->orwhere('tcm.pactivo','LIKE','%'.$filtro.'%')
                    ->orwhere('tcm.descorta','LIKE','%'.$filtro.'%')
                    ->orwhere('tcm.codprod','LIKE','%'.$filtro.'%');
                })
                ->orderBy('tcm.desprod','asc')
                ->get();
                foreach ($catalogox as $cat) {
                    $dataprod = obtenerColorProd($cat, $cliente, $provs);
                    if ($dataprod['invConsol'] > 0 ) {
                        if ($dataprod['da'] == 0 && $cat->da == 0) 
                            continue;
                        $catalogo[] = $cat;
                        $contador++;
                    }
                } 
                if ( $contador == 0) {
                    return redirect()->to('/ofertas/registros')->with('warning', 'No existen productos');
                }
                $catalogo = collect($catalogo);
                //$catalogo = ColectionPaginate::paginate($catalogo, 100);
                break;
            case 'A':
                $subtitulo = "LISTA DE OFERTAS (AMARILLOS)";
                $catalogox = tpmaestra::select('*', 'tcm.desprod as descrip')
                ->join($invent.' as tcm', 'tcm.barra','=', 'tpmaestra.barra')
                ->where('tcm.costo','>',0)
                ->where('tcm.cantidad','>',0)
                ->where(function ($q) use ($filtro) {
                    $q->orwhere('tcm.desprod','LIKE','%'.$filtro.'%')
                    ->orwhere('tcm.barra','LIKE','%'.$filtro.'%')
                    ->orwhere('tcm.marca','LIKE','%'.$filtro.'%')
                    ->orwhere('tcm.pactivo','LIKE','%'.$filtro.'%')
                    ->orwhere('tcm.descorta','LIKE','%'.$filtro.'%')
                    ->orwhere('tcm.codprod','LIKE','%'.$filtro.'%');
                })
                ->orderBy('tcm.desprod','asc')
                ->get();
                foreach ($catalogox as $cat) {
                    $dataprod = obtenerColorProd($cat, $cliente, $provs);
                    if ($dataprod['colorprod'] == 'A' && $dataprod['da'] > 0 
                        && $dataprod['invConsol'] > 0 ) {
                        $catalogo[] = $cat;
                        $contador++;
                    }
                } 
                if ( $contador == 0) {
                    return redirect()->to('/ofertas/registros')->with('warning', 'No existen productos con color AMARILLO');
                }
                $catalogo = collect($catalogo);
                //$catalogo = ColectionPaginate::paginate($catalogo, 100);
                break;
            case 'V':
                $subtitulo = "LISTA DE OFERTAS (VERDES)";
                $catalogox = tpmaestra::select('*', 'tcm.desprod as descrip')
                ->join($invent.' as tcm', 'tcm.barra','=', 'tpmaestra.barra')
                ->where('tcm.costo','>',0)
                ->where('tcm.cantidad','>',0)
                ->where(function ($q) use ($filtro) {
                    $q->orwhere('tcm.desprod','LIKE','%'.$filtro.'%')
                    ->orwhere('tcm.barra','LIKE','%'.$filtro.'%')
                    ->orwhere('tcm.marca','LIKE','%'.$filtro.'%')
                    ->orwhere('tcm.pactivo','LIKE','%'.$filtro.'%')
                    ->orwhere('tcm.descorta','LIKE','%'.$filtro.'%')
                    ->orwhere('tcm.codprod','LIKE','%'.$filtro.'%');
                })
                ->orderBy('tcm.desprod','asc')
                ->get();
                foreach ($catalogox as $cat) {
                    $dataprod = obtenerColorProd($cat, $cliente, $provs);
                    if ($dataprod['colorprod'] == 'V' && $dataprod['invConsol'] > 0) {
                        $catalogo[] = $cat;
                        $contador++;
                    }
                }
                if ( $contador == 0) {
                    return redirect()->to('/ofertas/registros')->with('warning', 'No existen productos con color VERDE');
                }
                $catalogo = collect($catalogo);
                //$catalogo = ColectionPaginate::paginate($catalogo, 100);
                break;
            case 'R':
                $subtitulo = "LISTA DE OFERTAS (ROJOS)";
                $catalogox = tpmaestra::select('*', 'tcm.desprod as descrip')
                ->join($invent.' as tcm', 'tcm.barra','=', 'tpmaestra.barra')
                ->where('tcm.costo','>',0)
                ->where('tcm.cantidad','>',0)
                ->where(function ($q) use ($filtro) {
                    $q->orwhere('tcm.desprod','LIKE','%'.$filtro.'%')
                    ->orwhere('tcm.barra','LIKE','%'.$filtro.'%')
                    ->orwhere('tcm.marca','LIKE','%'.$filtro.'%')
                    ->orwhere('tcm.pactivo','LIKE','%'.$filtro.'%')
                    ->orwhere('tcm.descorta','LIKE','%'.$filtro.'%')
                    ->orwhere('tcm.codprod','LIKE','%'.$filtro.'%');
                })
                ->orderBy('tcm.desprod','asc')
                ->get();
                foreach ($catalogox as $cat) {
                    $dataprod = obtenerColorProd($cat, $cliente, $provs);
                    if ($dataprod['colorprod'] == 'R' && $dataprod['invConsol'] > 0 && $dataprod['da'] > 0) {
                        $catalogo[] = $cat;
                        $contador++;
                    }
                }
                if ( $contador == 0) {
                    return redirect()->to('/ofertas/registros')->with('warning', 'No existen productos con color ROJO');
                }
                $catalogo = collect($catalogo);
                //$catalogo = ColectionPaginate::paginate($catalogo, 100);
                break;
        }
        DB::table('sugoferen')
        ->where('codcli','=', $codcli)
        ->delete();
        foreach ($catalogo as $cat) {
            DB::table('sugoferen')->insert([
                'codcli' => $codcli,
                'barra' => $cat->barra,
                'codprod' => $cat->codprod,
                'desprod' => $cat->desprod,
                'da' => $cat->da,
                'ps' => $dataprod['precioSug']
            ]);
        }
        $catalogo = DB::table('sugoferen')
        ->where('codcli','=',$codcli)
        ->orderBy('desprod','asc')
        ->get();
        return view("ofertas.registros.create",["menu" => "Registro",
                                                 "cliente" => $cliente,
                                                 "utilm" => $cliente->utilm,
                                                 "catalogo" => $catalogo,
                                                 "provs" => $provs,
                                                 "tipo" => $tipo,
                                                 "filtro" => "",
                                                 "cfg" => DB::table('maecfg')->first(),
                                                 "subtitulo" => $subtitulo]);
    }

    public function store(Request $request) {
        try {
            //ini_set('max_input_vars','3000' );
            $filtro = $request->get('filtro');
            $ppsa = ($request->get('ppsa')=='on') ? '1': '0';
            $daprod = $request->get('daprod');
            $refprod = $request->get('refprod');
            $psprod = $request->get('psprod');
            $desprod = $request->get('desprod');
            $codprod = $request->get('codprod');
            $desde = $request->get('desde');
            $hasta = $request->get('hasta');
            $observ = $request->get('observ');
            $codcli = sCodigoClienteActivo();
            $usuario = Auth::user()->name;
            DB::table('maecliente')
            ->where('codcli','=',$codcli)
            ->update(array('ppsa' => $ppsa ));
            $id = DB::table('regofertas')->insertGetId([
                'codcli' => $codcli,
                'fecha' => date('Y-m-d H:i:s'), 
                'usuario' => $usuario, 
                'status' => '0',
                'desde' => $desde,
                'hasta' => $hasta, 
                'observ' => $observ,
                'ppsa' => $ppsa
            ]);

            //log::info("LOG -> contador: ".count($codprod));
            //log::info("LOG -> filtro: ".$filtro);
            for ($i = 0; $i < count($codprod); $i++) {
                if (isset($psprod[$i])) {
                    //log::info("LOG -> codprod: ".$codprod[$i]);
                    //log::info("LOG -> refprod: ".$refprod[$i]);
                    //log::info("LOG -> desprod: ".$desprod[$i]);
                    if (str_contains(strtoupper($codprod[$i]), strtoupper($filtro))
                        || str_contains(strtoupper($refprod[$i]), strtoupper($filtro))
                        || str_contains(strtoupper($desprod[$i]), strtoupper($filtro)) ) {
                        DB::table('regoferen')->insert([
                            'id' => $id,
                            'codcli' => $codcli,
                            'codprod' => $codprod[$i],
                            'desprod' => $desprod[$i],
                            'da' => $daprod[$i],
                            'ps' => $psprod[$i]
                        ]);
                    }
                }
            }

        } catch (Exception $e) {
            session()->flash('error', $e);
        }
        return Redirect::to('/ofertas/registros');
    }

    public function destroy($id) {
        try {
            DB::beginTransaction();
            $regs = DB::table('regofertas')
            ->where('id','=',$id)
            ->delete();
            $regs = DB::table('regoferen')
            ->where('id','=',$id)
            ->delete();
            DB::commit();
            session()->flash('message', 'Ofertas '.$id.' eliminado satisfactoriamente');
        } catch (Exception $e) {
            DB::rollBack();
            session()->flash('error', $e);
        }
        return Redirect::to('/ofertas/registros');
    }

    public function descargar($id) {
        //set_time_limit(1000); 
        $codcli = sCodigoClienteActivo();
        //log::info("CD -> FORMATO: ".$formato);
        $archivo = 'ofertas_'.$codcli.'.csv';

        $BASE_PATH = env('INTRANET_RUTA_PUBLIC', base_path());
        $rutaarc = $BASE_PATH.'/public/storage/'.$archivo;

        //$rutaarc = $archivo;
        $fs = fopen($rutaarc,"w");
      
        $regoferen = DB::table('regoferen')
        ->where('codcli','=',$codcli)
        ->where('id','=',$id)
        ->orderBy('desprod','asc')
        ->get();
        $traza = "DESCRIPCION;CODIGO;OFERTA;SUGERIDO;".PHP_EOL;
        fwrite($fs,$traza);
        foreach ($regoferen as $ro) {
            $ps = str_replace(".", ",", $ro->ps);
            $da = str_replace(".", ",", $ro->da);
            $traza = $ro->desprod.";".$ro->codprod.";".$da.";".$ps.";".PHP_EOL;
            fwrite($fs,$traza);
        }
        fclose($fs);
        $headers = ['Content-type'=>'text/plain', 'test'=>'YoYo', 'Content-Disposition'=>sprintf('attachment; filename="%s"', $archivo),'X-BooYAH'=>'WorkyWorky'];
        return response()->download($rutaarc);
    }

 }
