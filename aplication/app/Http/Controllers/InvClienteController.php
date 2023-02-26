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


class InvClienteController extends Controller
{
    public function __construct() {
    	$this->middleware('auth');
    }

    public function index(Request $request) {
        $filtro=trim($request->get('filtro'));
        $subtitulo = "INVENTARIO";
        $subtitulo2 = "";
        $codcli = sCodigoClienteActivo();
        $confcli = LeerCliente($codcli);
        $cliente = DB::table('maecliente')
        ->where('codcli','=',$codcli)
        ->first();
        $tabla = "inventario_".$codcli;
        $invent = null;
        if (VerificaTabla($tabla)) {
            $invent = DB::table($tabla)
            ->where('cuarentena', '=', '0')
            ->where(function ($q) use ($filtro) {
                $q->where('barra','LIKE','%'.$filtro.'%')
                ->orwhere('desprod','LIKE','%'.$filtro.'%')
                ->orwhere('codprod','LIKE','%'.$filtro.'%')
                ->orwhere('pactivo','LIKE','%'.$filtro.'%');
            })
            ->orderBy('desprod','asc')
            ->paginate(100);

            $inv = DB::table($tabla)
            ->where('cuarentena', '=', '0')
            ->selectRaw('count(*) as contador')->first();
            $contador =number_format($inv->contador,0);

            if ($invent->count()>0) {
                $fecha = date('d-m-Y H:i', strtotime($invent[0]->feccatalogo));
                $subtitulo2 = "INVENTARIO (FECHA: ".$fecha.", RENGLONES: ".$contador.")";
            }

        }
        return view('isacom.invcliente.index' ,["menu" => "Inventario",
                                                "cfg" => DB::table('maecfg')->first(),
                                                "codcli" => $codcli,
                                                "confcli" => $confcli,
                                                "cliente" => $cliente,
                                                "filtro" => $filtro,
                                                "invent" => $invent,
                                                "subtitulo2" => $subtitulo2,
                                                "subtitulo" => $subtitulo ]);
    }

	public function store(Request $request) {
        set_time_limit(500);
        $codcli = sCodigoClienteActivo();
        $formato = $request->get('formato');
        $rutainv = $request->linkarchivo;

        $extension = pathinfo($rutainv, PATHINFO_EXTENSION);
        if ($extension == 'txt')
            $formato = "2";
        if ($extension == 'csv')
            $formato = "1";

        if ( $formato == "0") {
            log::info("CONVERTIR-> EXTENSION NO COMPATIBLE: ".$rutainv );
            DB::table('maecliente')
            ->where('codcli','=',$codcli)
            ->update(array("cargarLog" => "CONVERTIR-> EXTENSION NO COMPATIBLE: ".$rutainv));
            return back()->with('error', "CONVERTIR-> EXTENSION NO COMPATIBLE: ".$rutainv);
        }




        if($request->hasfile('linkarchivo')) {
            //$BASE_PATH = env('INTRANET_RUTA_PUBLIC', base_path());
            $primer=0;
            $error = 0;
            $err = "";
            $separador = '|';
            $codcli = sCodigoClienteActivo();
            $file = $request->file('linkarchivo');
            $nominv = $file->getClientOriginalName();
            $file->move(public_path().'/public/storage/', $nominv);
            $rutainv = public_path().'/public/storage/'.$nominv;
            $lines = file($rutainv);
            if ( count($lines) <= 0) {
                $err = "CONVERTIR-> INVENTARIO VACIO: ".$rutainv;
                //log::info($err);
                DB::table('maecliente')
                ->where('codcli','=',$codcli)
                ->update(array("cargarLog" => $err));
                return back()->with('error', $err);
            }
            $tabla = 'inventario_'.$codcli;
            if (!VerificaTabla($tabla)) {
                DB::statement('CREATE TABLE '.$tabla.' (
                    `barra` varchar(50) NOT NULL,
                    `codprod` varchar(50) NOT NULL,
                    `desprod` varchar(150) NOT NULL,
                    `tipo` varchar(1) NOT NULL,
                    `iva` decimal(18,2) DEFAULT NULL,
                    `regulado` varchar(1) NOT NULL,
                    `codprov` varchar(50) NULL,
                    `precio1` decimal(18,2) DEFAULT NULL,
                    `cantidad` decimal(18,0) DEFAULT NULL,
                    `bulto` varchar(50) DEFAULT NULL,
                    `da` decimal(18,2) DEFAULT NULL,
                    `oferta` decimal(18,2) DEFAULT NULL,
                    `upre` int(11) NOT NULL,
                    `ppre` decimal(18,2) DEFAULT NULL,
                    `psugerido` decimal(18,2) DEFAULT NULL,
                    `pgris` varchar(60) NOT NULL,
                    `nuevo` varchar(1) NOT NULL,
                    `fechafalla` datetime DEFAULT NULL,
                    `tipocatalogo` varchar(20) NOT NULL,
                    `cuarentena` varchar(1) NOT NULL,
                    `dctoneto` varchar(100) NOT NULL,
                    `lote` varchar(100) NOT NULL,
                    `fecvence` varchar(100) NOT NULL,
                    `marca` varchar(100) NOT NULL,
                    `pactivo` varchar(100) NOT NULL,
                    `costo` varchar(100) NOT NULL,
                    `ubicacion` varchar(100) NOT NULL,
                    `descorta` varchar(100) NOT NULL,
                    `codisb` varchar(100) NOT NULL,
                    `feccatalogo` varchar(100) NOT NULL,
                    `categoria` varchar(100) NOT NULL,
                    `molecula` varchar(100) NOT NULL,
                    `subgrupo` varchar(100) NOT NULL,
                    `opc1` varchar(100) NOT NULL,
                    `opc2` varchar(100) NOT NULL,
                    `opc3` varchar(100) NOT NULL,
                    `precio2` decimal(18,2) DEFAULT NULL,
                    `precio3` decimal(18,2) DEFAULT NULL,
                    `precio4` decimal(18,2) DEFAULT NULL,
                    `precio5` decimal(18,2) DEFAULT NULL,
                    `vmd` decimal(18,4) DEFAULT NULL
                ) ENGINE=MyISAM DEFAULT CHARSET=latin1;');
                DB::statement('ALTER TABLE '.$tabla.' ADD PRIMARY KEY (`codprod`);');
                log::info("ICOMPRAS-> CREATE: ".$tabla." satisfactoriamente");
            }
            DB::table($tabla)->delete();
            if ($formato == 1) {
                $separador = ';';
                $linea = 0;
                $cliente = DB::table('maecliente')
                ->where('codcli','=',$codcli)
                ->first();
                if (is_null($cliente)) {
                    log::info("CODISB-> NO ENCONTRADO: ".$codcli );
                    DB::table('maecliente')
                    ->where('codcli','=',$codcli)
                    ->update(array("cargarLog" => "CODISB-> NO ENCONTRADO: ".$codcli));
                    return back()->with('error', 'Codigo cliente no existe!');
                }
                foreach ($lines as $line) {
                    try {
                        $linea++;
                        if ($linea < $cliente->LineaInicio)
                            continue;
                        $line = trim(QuitarCaracteres($line));
                        if (substr($line, -1) != $separador)
                            $line = $line.$separador;

                        $s1 = explode($separador, $line);
                        $codprod = trim($s1[ord($cliente->ColCodprod)-65]);
                        if (empty($codprod))
                            continue;

                        $barra = $s1[ord($cliente->ColRef)-65];
                        if (empty($barra))
                            $barra = $codprod;

                        $precio = $s1[ord($cliente->ColPrecio)-65];
                        if (empty($precio))
                            continue;

                        $precio = fGetfloat($precio);
                        if ($cliente->CodMoneda == "OM") {
                            $precio = $precio * $factor;
                        }

                        if (empty(trim($cliente->ColIva)))
                            $iva = '0.00';
                        else
                            $iva = $s1[ord($cliente->ColIva)-65];
                        if (empty($iva) || $iva == "(E)")
                            $iva = '0.00';
                        else
                            $iva = fGetfloat($iva);

                        if (empty(trim($cliente->ColDa)))
                            $da = '0.00';
                        else
                            $da = fGetfloat($s1[ord($cliente->ColDa)-65]);
                        $cant = fGetfloat($s1[ord($cliente->ColCantidad)-65]);
                        $posPnto = strpos($cant, '.');
                        if ($posPnto > 0  ) {
                            $cant = explode('.', $cant);
                            $cant = $cant[0];
                        }
                        if ($cant <= 0)
                            return;

                        log::info("CCP-> LINEA: ".$line );
                        $desprod = LetrasyNumeros($s1[ord($cliente->ColDesprod)-65]);
                        $precio1 = $precio;
                        if (empty(trim($cliente->ColLote)))
                            $lote = '01';
                        else
                            $lote = $s1[ord($cliente->ColLote)-65];
                        if (empty(trim($cliente->ColFechaLote)))
                            $fecvence = 'N/A';
                        else
                            $fecvence = $s1[ord($cliente->ColFechaLote)-65];
                        if (empty(trim($cliente->ColMarca)))
                            $marca = 'N/A';
                        else
                            $marca = $s1[ord($cliente->ColMarca)-65];
                        if (empty(trim($cliente->ColCosto)))
                            $costo = "0.00";
                        else
                            $costo = $s1[ord($cliente->ColCosto)-65];
                        if (empty(trim($cliente->ColVmd)))
                            $vmd = "1.0000";
                        else
                            $vmd = fGetfloat($s1[ord($cliente->ColVmd)-65]);
                        DB::table($tabla)->insert([
                            'barra' => $barra,
                            'codprod' => $codprod,
                            'desprod' => $desprod,
                            'tipo' => "N",
                            'iva' => $iva,
                            'regulado' => "N",
                            'codprov' => "",
                            'precio1' => $precio,
                            'cantidad' => $cant,
                            'bulto' => BuscarBulto($barra),
                            'da' => $da,
                            'oferta' => "0.00",
                            'upre' => "0",
                            'ppre' => "0.00",
                            'psugerido' => $precio,
                            'pgris' => "0.00",
                            'nuevo' => "0",
                            'fechafalla' => date('Y-m-j H:i:s'),
                            'tipocatalogo' => "PRINCIPAL",
                            'cuarentena' => "0",
                            'dctoneto' => "0.00",
                            'lote' => $lote,
                            'fecvence' => $fecvence,
                            'marca' => LeerProdcaract($barra, 'marca', 'POR DEFINIR'),
                            'pactivo' => "",
                            'costo' => $costo,
                            'ubicacion' => "N/A",
                            'descorta' => "",
                            'codisb' => $codcli,
                            'feccatalogo' => date('Y-m-j H:i:s'),
                            'categoria' => LeerProdcaract($barra, 'categoria', 'POR DEFINIR'),
                            'molecula' => LeerProdcaract($barra, 'molecula', 'POR DEFINIR'),
                            'subgrupo' => $marca,
                            'opc1' => "N/A",
                            'opc2' => "N/A",
                            'opc3' => "N/A",
                            'precio2' => $precio,
                            'precio3' => $precio,
                            'precio4' => $precio,
                            'precio5' => $precio,
                            'vmd' => $vmd
                        ]);
                    } catch (\Exception $e) {
                        $err = "CONVERTIR-> Warning: ".$rutainv." - ".$e->getMessage();
                        if ($error==0) {
                            $error++;
                            DB::table('maecliente')
                            ->where('codcli','=',$codcli)
                            ->update(array("cargarLog" => $err));
                        }
                        log::info($err);
                    }
                }

            } else {

                foreach ($lines as $line) {
                    try {

                        // 0        1       2       3    4      5     6     7     8
                        // codprod|descrip|cantidad|vmd|precio|marca|barra|costo|iva

                        $line = trim($line);
                        if ($primer==0) {
                            $primer=1;
                            $pos = strpos($line, ';');
                            if ($pos > 0) {
                                $separador = ';';
                            }
                        }

                        $s1 = explode($separador, $line);
                        DB::table($tabla)->insert([
                            'barra' => $s1[6],
                            'codprod' => $s1[0],
                            'desprod' => LetrasyNumeros($s1[1]),
                            'tipo' => "N",
                            'iva' => $s1[8],
                            'regulado' => "N",
                            'codprov' => $s1[5],
                            'precio1' => fGetfloat($s1[4]),
                            'cantidad' => $s1[2],
                            'bulto' => BuscarBulto($s1[6]),
                            'da' => "0.00",
                            'oferta' => "0.00",
                            'upre' => "0",
                            'ppre' => "0.00",
                            'psugerido' => fGetfloat($s1[4]),
                            'pgris' => "0.00",
                            'nuevo' => "0",
                            'fechafalla' => date('Y-m-j H:i:s'),
                            'tipocatalogo' => "PRINCIPAL",
                            'cuarentena' => "0",
                            'dctoneto' => "0.00",
                            'lote' => "N/A",
                            'fecvence' => "N/A",
                            'marca' => LeerProdcaract($s1[6], 'marca', 'POR DEFINIR'),
                            'pactivo' => "",
                            'costo' => fGetfloat($s1[7]),
                            'ubicacion' => "N/A",
                            'descorta' => "",
                            'codisb' => $codcli,
                            'feccatalogo' => date('Y-m-j H:i:s'),
                            'categoria' => LeerProdcaract($s1[6], 'categoria', 'POR DEFINIR'),
                            'molecula' => LeerProdcaract($s1[6], 'molecula', 'POR DEFINIR'),
                            'subgrupo' => $s1[5],
                            'opc1' => "N/A",
                            'opc2' => "N/A",
                            'opc3' => "N/A",
                            'precio2' => fGetfloat($s1[4]),
                            'precio3' => fGetfloat($s1[4]),
                            'precio4' => fGetfloat($s1[4]),
                            'precio5' => fGetfloat($s1[4]),
                            'vmd' => $s1[3]
                        ]);
                    } catch (\Exception $e) {
                        $err = "CONVERTIR-> Warning: ".$rutainv." - ".$e->getMessage();
                        if ($error==0) {
                            $error++;
                            DB::table('maecliente')
                            ->where('codcli','=',$codcli)
                            ->update(array("cargarLog" => $err));
                        }
                        log::info($err);
                    }
                }

            }
            if ($error == 0) {
                DB::table('maecliente')
                ->where('codcli','=',$codcli)
                ->update(array("cargarLog" => "OK, ".date('Y-m-j H:i:s')));
                session()->flash('message','Su Inventario ha sido cargado satisfactoriamente');
            } else {
                return back()->with('warning', $err);
            }
        } else {
            return back()->with('error', 'Debe seleccionar un archivo txt!');
        }
        return Redirect::to('/invcliente');
    }

    public function destroy($id) {
        try {
            DB::beginTransaction();
            $tabla = 'inventario_'.$id;
            if (VerificaTabla($tabla)) {
                DB::table('inventario_'.$id)
                ->delete();
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return Redirect::back()->with('error', 'Inventario '.$id.' '.$e);
        }
        return Redirect::back()->with('message', 'Inventario '.$id.' eliminado satisfactoriamente');
    }

    public function cargar() {
        $subtitulo = "CARGAR INVENTARIO";
        $codcli = sCodigoClienteActivo();
        $cliente = DB::table("maecliente")
        ->where('codcli','=',$codcli)
        ->first();
        return view('isacom.invcliente.create' ,["menu" => "Inventario",
                                                 "cfg" => DB::table('maecfg')->first(),
                                                 "cliente" => $cliente,
                                                 "formato" => '1',
                                                 "subtitulo" => $subtitulo]);
    }

    public function modcol(Request $request) {
        $codcli = sCodigoClienteActivo();
        $valor = $request->get('valor');
        $campo = $request->get('campo');
        $cliente = DB::table('maecliente')
        ->where('codcli', '=', $codcli)
        ->first();
        if ($cliente) {
            DB::table('maecliente')
            ->where('codcli', '=', $codcli)
            ->update(array($campo => $valor));
        }
        return response()->json(['msg' => 'OK' ]);
    }

    public function ejemplo1(Request $request) {
        try {
            $archivo = "inventario.csv";
            $rutaarc = public_path().'/public/storage/ejemplos/'.$archivo;
            if (file_exists($rutaarc)) {
                $headers = ['Content-type'=>'text/plain', 'test'=>'YoYo', 'Content-Disposition'=>sprintf('attachment; filename="%s"', $archivo),'X-BooYAH'=>'WorkyWorky'];
                return response()->download($rutaarc);
            }
        } catch (Exception $e) {
            session()->flash('error',$e);
        }
    }

    public function ejemplo2(Request $request) {
        try {
            $archivo = "inventario.txt";
            $rutaarc = public_path().'/public/storage/ejemplos/'.$archivo;
            if (file_exists($rutaarc)) {
                $headers = ['Content-type'=>'text/plain', 'test'=>'YoYo', 'Content-Disposition'=>sprintf('attachment; filename="%s"', $archivo),'X-BooYAH'=>'WorkyWorky'];
                return response()->download($rutaarc);
            }
        } catch (Exception $e) {
            session()->flash('error',$e);
        }
    }

 }
