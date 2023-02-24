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
use Illuminate\Support\Facades\Response;
use DB;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;


class FacturaController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth');
    }

    public function index(Request $request) {
    	if ($request) {
            $codprove = $request->get('codprove');
            $codcli = sCodigoClienteActivo();
            $provs = TablaMaecliproveActivaMysql();
            if (empty($codprove)) {
                foreach ($provs as $prov) { 
                    $codprove = $prov->codprove;
                    $codigo = $prov->codigo;
                    break;
                }
            } else {
                foreach ($provs as $prov) {
                    if ($prov->codprove == $codprove) {
                        $codigo = $prov->codigo;
                        break;
                    }
                }
            }
            $desde = trim($request->get('desde'));
            $hasta = trim($request->get('hasta'));
            if ($desde=='' || $hasta=='') {
                $hasta = date('Y-m-d');
                $desde = date('Y-m-d', strtotime('-7 day', strtotime($hasta)));
            }
            $desde = $desde.' 00:00:00';
            $hasta = $hasta.' 23:59:00';

            $maeprove = LeerProve($codprove);
            if (is_null($maeprove)) {
                return back()->with('error', 'No existen proveedores!');
            }
            $host = $maeprove->host; 
            $basedato = $maeprove->basedato; 
            $username = $maeprove->username; 
            $clave = $maeprove->password; 

            //log::info("FACTURA -> ** CODPROVE=".$codprove);
            //log::info("FACTURA -> ** BDD=".$basedato);
            //log::info("FACTURA -> ** USUARIO=".$username);
            //log::info("FACTURA -> ** CLAVE=".$clave);
            //log::info("FACTURA -> ** CODCLI=".$codcli);
            //log::info("FACTURA -> ** CODIGO=".$codigo);
	        
            // TABLA DE FACTURAS
            Config::set("database.connections.mysql2", [
                "driver" => "mysql",
                "host" => $host,
                "database" => $basedato,
                "username" => $username,
                "password" => $clave
            ]);
            Config::set('database.default', 'mysql2');
            DB::reconnect('mysql2');

            $tabla = DB::table('fact')
            ->where('codcli','=',$codigo)
            ->whereBetween('fecha', array($desde, $hasta))
            ->orderBy('fecha','desc')
            ->get();
            $reg = DB::table('fact')
            ->selectRaw('count(*) as contador')
            ->where('codcli','=',$codigo)
            ->first();

            DB::purge('mysql2');
            Config::set('database.default', 'mysql');
            DB::reconnect('mysql');

            $cont = $reg->contador;
            $subtitulo = "FACTURAS (".number_format($cont,0, '.', ',').")";
            return view('isacom.factura.index',["menu" => "Facturas",
                                                "provs" => $provs,
                                                "tabla" => $tabla, 
                                                "subtitulo" => $subtitulo,
	                                            "desde" => $desde,
                                                "hasta" => $hasta,
                                                "cfg" => DB::table('maecfg')->first(),
                                                "codprove" => $codprove,
	                                            "$codcli" => $codcli]);
    	}
    }
	
	public function show($id) {
        $s1 = explode('_', $id );
        $factnum = $s1[0];
        $codprove = $s1[1];
        $maeprove = LeerProve($codprove);
        if (is_null($maeprove)) {
            return back()->with('error', 'No existen proveedores!');
        }
        $host = $maeprove->host; 
        $basedato = $maeprove->basedato; 
        $username = $maeprove->username; 
        $clave = $maeprove->password; 
        Config::set("database.connections.mysql2", [
            "driver" => "mysql",
            "host" => $host,
            "database" => $basedato,
            "username" => $username,
            "password" => $clave
        ]);
        Config::set('database.default', 'mysql2');
        DB::reconnect('mysql2');

        $subtitulo = "FACTURA (".strtoupper($maeprove->nombre).")";
        $tabla = DB::table('fact')
        ->where('factnum','=',$factnum)
        ->first();
        $tabla2 = DB::table('factren')
        ->where('factnum','=',$factnum)
        ->orderBy('renglon','asc')
        ->get();
        
        DB::purge('mysql2');
        Config::set('database.default', 'mysql');
        DB::reconnect('mysql');
        return view('isacom.factura.show',["menu" => "Facturas",
                                            "tabla" => $tabla, 
                                            "tabla2" => $tabla2, 
                                            "subtitulo" => $subtitulo,
                                            "cfg" => DB::table('maecfg')->first(),
                                            "codprove" => $codprove,
                                            "factnum" => $id]);
    }

    public function exportar($id) {
        $s1 = explode('_', $id );
        $factnum = $s1[0];
        $codprove = $s1[1];
        $maeprove = LeerProve($codprove);
        if (is_null($maeprove)) {
            return back()->with('error', 'No existen proveedores!');
        }
        $host = $maeprove->host; 
        $basedato = $maeprove->basedato; 
        $username = $maeprove->username; 
        $clave = $maeprove->password; 
        Config::set("database.connections.mysql2", [
            "driver" => "mysql",
            "host" => $host,
            "database" => $basedato,
            "username" => $username,
            "password" => $clave
        ]);
        Config::set('database.default', 'mysql2');
        DB::reconnect('mysql2');

        $subtitulo = "FACTURA (".strtoupper($maeprove->nombre).")";
        $tabla = DB::table('fact')
        ->where('factnum','=',$factnum)
        ->first();
        $tabla2 = DB::table('factren')
        ->where('factnum','=',$factnum)
        ->orderBy('renglon','asc')
        ->get();
        
        DB::purge('mysql2');
        Config::set('database.default', 'mysql');
        DB::reconnect('mysql');
        return view('isacom.factura.exportar',["menu" => "Facturas",
                                              "tabla" => $tabla, 
                                              "tabla2" => $tabla2, 
                                              "subtitulo" => $subtitulo,
                                              "cfg" => DB::table('maecfg')->first(),
                                              "codprove" => $codprove,
                                              "factnum" => $id]);
    }

    public function descargartxt($id) {
        $s1 = explode('_', $id );
        $factnum = $s1[0];
        $codprove = $s1[1];
        $maeprove = LeerProve($codprove);
        if (is_null($maeprove)) {
            return back()->with('error', 'No existen proveedores!');
        }
        $host = $maeprove->host; 
        $basedato = $maeprove->basedato; 
        $username = $maeprove->username; 
        $clave = $maeprove->password; 
        Config::set("database.connections.mysql2", [
            "driver" => "mysql",
            "host" => $host,
            "database" => $basedato,
            "username" => $username,
            "password" => $clave
        ]);
        Config::set('database.default', 'mysql2');
        DB::reconnect('mysql2');

        $archivo = 'FAC_'.$codprove.'_'.$factnum.'.txt';
        $BASE_PATH = env('INTRANET_RUTA_PUBLIC', base_path());
        $rutaarc = $BASE_PATH.'/public/storage/'.$archivo;
        $fs = fopen($rutaarc,"w");
        $unidades = 0;
        $renglones = 0;
        $bultos = 1;
        $factren = DB::table('factren')
        ->where('factnum','=',$factnum)
        ->get();
        foreach ($factren as $fr) {
            $unidades = $unidades + $fr->cantidad;
            $renglones++;
        }

        $f = DB::table('fact')
        ->where('factnum','=',$factnum)
        ->first();
        $codcli = $f->codcli;

        DB::purge('mysql2');
        Config::set('database.default', 'mysql');
        DB::reconnect('mysql');

        $cp = DB::table('maeclieprove')
        ->where('codigo','=',$codcli)
        ->where('codprove','=',$codprove)
        ->first();
 
        $fileText = 
             $cp->codcli."|"         // 0
            .$cp->codprove."|"       // 1
            .$cp->codigo."|"         // 2
            .$cp->subcarpetaftp."|"  // 3
            .$cp->dcredito."|"       // 4
            .$cp->mcredito."|"       // 5
            .$cp->corte."|"          // 6
            .$cp->dcme."|"           // 7
            .$cp->dcmer."|"          // 8
            .$cp->dcmi."|"           // 9
            .$cp->dcmir."|"          // 10
            .$cp->ppme."|"           // 11
            .$cp->ppmi."|"           // 12
            .$cp->di."|"             // 13
            .$cp->dotro."|"          // 14
            .$cp->usuario."|"        // 15
            .$cp->clave."|"          // 16
            .$cp->status."|"         // 17
            .$cp->tipoprecio."|"     // 18
            .$cp->codprove_adm."|"   // 19
            .PHP_EOL; 
        fwrite($fs,$fileText);
       
        $fileText = 
             $f->factnum."|"         // 0
            .$f->fecha."|"           // 1
            .$f->codcli."|"          // 2
            .$f->descrip."|"         // 3
            .$f->monto."|"           // 4
            .$f->iva."|"             // 5
            .$f->gravable."|"        // 6
            .$f->descuento."|"       // 7
            .$f->total."|"           // 8
            .$f->tipofac."|"         // 9
            .$f->codesta."|"         // 10
            .$f->codusua."|"         // 11
            .$f->codvend."|"         // 12
            .$f->fechav."|"          // 13
            .$f->nroctrol."|"        // 14
            .$f->rif."|"             // 15
            .$f->codisb."|"          // 16
            .$f->observacion."|"     // 17
            .$f->codmoneda."|"       // 18
            .$f->factorcambiario."|" // 19
            .$f->origen."|"          // 20
            .PHP_EOL;
        fwrite($fs,$fileText);

        foreach ($factren as $fr) {
            $TIPO = ($fr->impuesto == 0) ? 'M' : 'C';
            $IVA = $fr->impuesto;
            $DCTOS = $fr->descto;
            $LOTE = $fr->nrolote;
            $VENCE = strtotime($fr->fechal);
            $VENCE = date('d/m/Y', $VENCE);
            $VENCE =  substr($VENCE, 0, 10);
            $PROV = "";
            $REGULADO = "0";
            $traza = 
                 $factnum."|"        // 0
                .$fr->codprod."|"    // 1
                .$TIPO."|"           // 2
                .$REGULADO."|"       // 3
                .$fr->desprod."|"    // 4
                .$fr->cantidad."|"   // 5
                .$fr->precio."|"     // 6 
                .$fr->subtotal."|"   // 7
                .$IVA."|"            // 8
                .$DCTOS."|"          // 9 
                .$fr->referencia."|" // 10
                .$LOTE."|"           // 11
                .$VENCE."|"          // 12
                .$PROV."|"           // 13
                .PHP_EOL;
            fwrite($fs,$traza);
        }
        fclose($fs);

        $headers = ['Content-type'=>'text/plain', 'test'=>'YoYo', 'Content-Disposition'=>sprintf('attachment; filename="%s"', $archivo),'X-BooYAH'=>'WorkyWorky'];
        return response()->download($rutaarc);
    }

    public function descargarpdf($id) {
        $s1 = explode('_', $id );
        $factnum = $s1[0];
        $codprove = $s1[1];
        $maeprove = LeerProve($codprove);
        if (is_null($maeprove)) {
            return back()->with('error', 'No existen proveedores!');
        }
        $host = $maeprove->host; 
        $basedato = $maeprove->basedato; 
        $username = $maeprove->username; 
        $clave = $maeprove->password; 
        Config::set("database.connections.mysql2", [
            "driver" => "mysql",
            "host" => $host,
            "database" => $basedato,
            "username" => $username,
            "password" => $clave
        ]);
        Config::set('database.default', 'mysql2');
        DB::reconnect('mysql2');


        $titulo = "FACTURA: ".$factnum;
        $cfg = DB::table('cfg')->first();
        // TABLA DE FACTURAS
        $tabla = DB::table('fact')
                ->where('factnum','=',$factnum)
                ->first();
        $subtitulo = $tabla->descrip;
        $codcli = $tabla->codcli;
        // TABLA DE CLIENTE
        $cliente = DB::table('cliente')
        ->where('codcli','=',$codcli)
        ->first();

        // TABLA DE RENGLONES DE FACTURAS
        $tabla2 = DB::table('factren')
        ->where('factnum','=',$factnum)
        ->orderBy('renglon','asc')
        ->get();

        $numreng = 0;
        $numund = 0;
        foreach ($tabla2 as $t) {
            $numund += $t->cantidad;
            $numreng++;
        }
        $data = [
            "menu"=>"Facturas",
            "tabla" => $tabla, 
            "tabla2" => $tabla2, 
            "titulo" => $titulo,
            "subtitulo" => $subtitulo,
            "cfg" => $cfg,
            "cliente" => $cliente,
            "numreng" => $numreng,
            "numund" => $numund,
            "codprove" => $codprove,
            "factnum" => $id
        ];
        DB::purge('mysql2');
        Config::set('database.default', 'mysql');
        DB::reconnect('mysql');

        $pdf = PDF::loadView('layouts.rptfactura', $data);
        return $pdf->download('FAC_'.$codprove.'_'.$factnum.'.pdf');
    }
    
}
