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
use Illuminate\Support\Facades\Log;
use Session;
use DB;

class DescargaController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth');
    }

    public function index(Request $request) {
        if ($request) {
            $subtitulo = "MODULO DE DESCARGAS";
            $filtro = trim($request->get('filtro'));
            $carga = DB::table('carga')
            ->Orwhere('descrip','LIKE','%'.$filtro.'%')
            ->Orwhere('ruta','LIKE','%'.$filtro.'%')
            ->orderBy('id','asc')
            ->get();
            return view('isacom.descargas.index' ,["menu" => "Descargar",
                                                  "carga" => $carga, 
                                                  "filtro" => $filtro,
                                                  "subtitulo" => $subtitulo]);
        }
    }

    public function show($id) {
        try {
            $carga = DB::table('carga')
            ->where('id','=',$id)
            ->first();
            if ($carga) {
                $cont = $carga->contdescarga;
                $cont = $cont + 1;
                DB::table('carga')
                ->where('id', '=', $id)
                ->update(array('contdescarga' => $cont ));
                $archivo = $carga->ruta;
                //$rutaarc = public_path().'/public/storage/'.$archivo;

                $rutaarc = "/home/qy9dy4z3xvjb/public_html/isaweb.isbsistemas.com/public/storage/".$archivo;

            //    dd($rutaarc);
                if (file_exists($rutaarc)) {
                    $headers = ['Content-type'=>'text/plain', 'test'=>'YoYo', 'Content-Disposition'=>sprintf('attachment; filename="%s"', $archivo),'X-BooYAH'=>'WorkyWorky'];
                    return response()->download($rutaarc);
                }
            }
        } catch (Exception $e) {
            session()->flash('error',$e);
        }
        return Redirect::to('/descargas');
    }

    public function catalogo(Request $request) {
        set_time_limit(500); 
        $codprove = strtolower($request->get('codprove'));
        $moneda = Session::get('moneda', 'BSS');
        try {
            if ($codprove == "tpmaestra") {

                if (VerificaTabla($codprove)) {
                    $archivo = $codprove.".csv";
                    $rutaarc = public_path().'/public/storage/'.$archivo;
                    $fs = fopen($rutaarc,"w");
                    $traza1 = "barra;desprod;iva;bulto;marca;tipo;regulado;nomprov;provdat;consolidado;";
                    $provs = TablaMaecliproveActiva("");
                    $cadprove = "";
                    foreach($provs as $prov) {
                         $cadprove = $cadprove.$prov->codprove.'_PRE;'.$prov->codprove.'_DA;'.$prov->codprove.'_DC;'.$prov->codprove.'_CANT;'.$prov->codprove.'_LOTE;'.$prov->codprove.'_VENCE;'.$prov->codprove.'_CODIGO;';
                    }
                    $traza = $traza1.$cadprove.PHP_EOL;
                    fwrite($fs,$traza);
                    $inv = DB::table(strtolower($codprove))->get();
                    foreach ($inv as $i) {
                        try {
                            $cadprove = "";
                            foreach($provs as $prov) {
                                $tipoprecio = $prov->tipoprecio;
                                $dc = $prov->dcme;
                                $nomcampo = strtolower($prov->codprove); 
                                $factor = RetornaFactorCambiario($prov->codprove, $moneda);
                                $valcampo = $i->$nomcampo;
                                $campo = explode("|", $valcampo);
                                switch ($tipoprecio) {
                                    case '2':
                                        $precio = $campo[5];
                                        break;
                                    case '3':
                                        $precio = $campo[6];
                                        break;
                                    default:
                                        $precio = $campo[0];
                                        break;
                                }
                                $precio = number_format($precio/$factor, 2, '.', ',');
                                $precio = str_replace(",", "", $precio);
                                $precio = str_replace(".", ",", $precio);
                                $da = $campo[2];
                                $da = str_replace(".", ",", $da);
                                $dc = str_replace(".", ",", $dc);
                                $cantidad = $campo[1];
                                $lote = $campo[7];
                                $fecvence = $campo[8];
                                $codprod = $campo[3];
                                $cadprove = $cadprove.$precio .";". $da .";". $dc .";". $cantidad .";". $lote .";". $fecvence .";". $codprod .";";
                            }
                            $iva = $i->iva;
                            $iva = str_replace(".", ",", $iva);
                            $traza1 = $i->barra .";". $i->desprod .";". $iva .";". $i->bulto .";". $i->marca .";". $i->tipo .";". $i->regulado .";". $i->nomprov .";". $i->provdat .";". $i->consolidado .";";       
                            $traza = $traza1 . $cadprove.PHP_EOL;
                            fwrite($fs,$traza);
                        } catch (\Exception $e) {
                            log::info("CONVERTIR-> Warning: ".$rutaarc." - ".$e->getMessage().' - LINEA: '.$e->getLine()  );
                            return FALSE;
                        }
                    }
                    fclose($fs);
                    if (file_exists($rutaarc)) {
                        $headers = ['Content-type'=>'text/plain', 'test'=>'YoYo', 'Content-Disposition'=>sprintf('attachment; filename="%s"', $archivo),'X-BooYAH'=>'WorkyWorky'];
                        return response()->download($rutaarc);
                    }
                } else {
                     session()->flash('error',"Catálogo no encontrado!");
                }
            } else {
                if (VerificaTabla($codprove)) {
                    $cliprov = LeerClieProve($codprove, "");
                    $tipoprecio = $cliprov->tipoprecio;
                    $dc = $cliprov->dcme;
                    $archivo = $codprove.".csv";
                    $factor = RetornaFactorCambiario($codprove, $moneda);
                    $rutaarc = public_path().'/public/storage/'.$archivo;
                    $fs = fopen($rutaarc,"w");
                    $traza =  "codprod;barra;desprod;iva;precio;cantidad;bulto;da;dc;marca;fechafalla;fechacata;tipo;regulado;nomprov;lote;fecvence;".PHP_EOL;
                    fwrite($fs,$traza);
                    $inv = DB::table($codprove)->get();
                    foreach ($inv as $i) {
                        try {
                            switch ($tipoprecio) {
                                case '2':
                                    $precio = number_format($i->precio2/$factor, 2, '.', ',');
                                    break;
                                case '3':
                                    $precio = number_format($i->precio3/$factor, 2, '.', ',');
                                    break;
                                default:
                                    $precio = number_format($i->precio1/$factor, 2, '.', ',');
                                    break;
                            }
                            $precio = str_replace(",", "", $precio);
                            $precio = str_replace(".", ",", $precio);
                            $cantidad = str_replace(".", ",",$i->cantidad);
                            $da = str_replace(".", ",",$i->da);
                            $dc = str_replace(".", ",",$dc);
                            $iva = str_replace(".", ",",$i->iva);
                            $traza = $i->codprod .";". $i->barra .";". $i->desprod .";". $iva .";". $precio .";". $cantidad .";". $i->bulto .";". $da .";".  $dc .";". $i->marca .";". $i->fechafalla .";". $i->fechacata .";". $i->tipo .";". $i->regulado .";". $i->nomprov .";". $i->lote .";". $i->fecvence .";".PHP_EOL;
                            fwrite($fs,$traza);
                        } catch (\Exception $e) {
                            log::info("CONVERTIR-> Warning: ".$rutaarc." - ".$e->getMessage());
                            return FALSE;
                        }
                    }
                    fclose($fs);
                    if (file_exists($rutaarc)) {
                        $headers = ['Content-type'=>'text/plain', 'test'=>'YoYo', 'Content-Disposition'=>sprintf('attachment; filename="%s"', $archivo),'X-BooYAH'=>'WorkyWorky'];
                        return response()->download($rutaarc);
                    }
                } else {
                     session()->flash('error',"Catálogo no encontrado!");
                }
            }
        } catch (Exception $e) {
            session()->flash('error',$e);
        }
    }

    public function rnk1(Request $request) {
        set_time_limit(500); 
        $codprove = strtolower($request->get('codprove'));
        $moneda = Session::get('moneda', 'BSS');
        $codcli = sCodigoClienteActivo();
        $provs = TablaMaecliproveActiva("");
        try {
            $criterio = 'PRECIO';
            $preferencia = 'NINGUNA';
            $pedir = 1;
            if ($codprove == "tpmaestra") {
                if (VerificaTabla($codprove)) {
                    $archivo = $codprove."_rnk1.csv";
                    $rutaarc = public_path().'/public/storage/'.$archivo;
                    $fs = fopen($rutaarc,"w");
                    $traza1 = "BARRA;DESPROD;IVA;BULTO;MARCA;TIPO;REGULADO;NOMPROV;PROVDAT;CONSOLIDADO;";
                    $cadprove = "PROVEEDOR;PRECIO;DA;DC;CANTIDAD;LOTE;VENCE;CODIGO;";
                    $traza = $traza1.$cadprove.PHP_EOL;
                    fwrite($fs,$traza);
                    $inv = DB::table(strtolower($codprove))->get();
                    foreach ($inv as $i) {
                        try {
                            $mejoropcion = BuscarMejorOpcion($i->barra, $criterio, $preferencia, $pedir, $provs);
                            if ($mejoropcion == null) {
                                continue;
                            }
                            $codprove = $mejoropcion[0]['codprove'];
                            $factor = RetornaFactorCambiario($codprove, $moneda);
                            try {
                                $codprovex = strtolower($codprove);
                                $campos = $i->$codprovex;
                                $campo = explode("|", $campos);
                            } catch (Exception $e) {
                                continue;
                            }
                            $precio = $campo[0];
                            $cantidad = $campo[1];
                            if ($precio <= 0 || $cantidad <= 0)
                                continue;
                            $maeclieprove = DB::table('maeclieprove')
                            ->where('codcli','=',$codcli)
                            ->where('codprove','=',$codprove)
                            ->where('status','=','ACTIVO')
                            ->first();
                            if (empty($maeclieprove))
                                continue;
                            $dc = $maeclieprove->dcme;
                            $tipoprecio = $maeclieprove->tipoprecio;
                            switch ($tipoprecio) {
                                case "1":
                                    $precio = $campo[0];
                                    break;
                                case "2":
                                    $precio = $campo[5];
                                    break;
                                case "3":
                                    $precio = $campo[6];
                                    break;
                                default:
                                    $precio = $campo[0];
                                    break;
                            }
                            $precio = number_format($precio/$factor, 2, '.', ',');
                            $precio = str_replace(",", "", $precio);
                            $precio = str_replace(".", ",", $precio);
                            $codprod = $campo[3];
                            $lote = $campo[7];
                            $fecvence = $campo[8];
                            $fecvence = str_replace("12:00:00 AM", "", $fecvence);
                            $da = $campo[2];
                            $da = str_replace(".", ",", $da);
                            $dc = str_replace(".", ",", $dc);
                            $iva = $i->iva;
                            $iva = str_replace(".", ",", $iva);
                            $traza1 = $i->barra .";". $i->desprod .";". $iva .";". $i->bulto .";". $i->marca .";". $i->tipo .";". $i->regulado .";". $i->nomprov .";". $i->provdat .";". $i->consolidado .";";     
                            $cadprove = $codprove .";". $precio .";". $da .";". $dc .";". $cantidad .";". $lote .";". $fecvence .";". $codprod .";";
                            $traza = $traza1 . $cadprove.PHP_EOL;
                            fwrite($fs,$traza);

                        } catch (\Exception $e) {
                            log::info("CONVERTIR-> Warning: ".$rutaarc." - ".$e->getMessage().' - LINEA: '.$e->getLine()  );
                            return FALSE;
                        }
                    }
                    fclose($fs);
                    if (file_exists($rutaarc)) {
                        $headers = ['Content-type'=>'text/plain', 'test'=>'YoYo', 'Content-Disposition'=>sprintf('attachment; filename="%s"', $archivo),'X-BooYAH'=>'WorkyWorky'];
                        return response()->download($rutaarc);
                    }
                } else {
                     session()->flash('error',"Rnk1 no encontrado!");
                }
            } else {
                if (VerificaTabla($codprove)) {
                    $cliprov = LeerClieProve($codprove, "");
                    $factor = RetornaFactorCambiario($codprove, $moneda);
                    $tipoprecio = $cliprov->tipoprecio;
                    $dc = $cliprov->dcme;
                    $archivo = $codprove.".csv";
                    $factor = RetornaFactorCambiario($codprove, $moneda);
                    $rutaarc = public_path().'/public/storage/'.$archivo;
                    $fs = fopen($rutaarc,"w");
                    $traza =  "CODPROD;BARRA;DESPROD;IVA;PRECIO;CANTIDAD;BULTO;DA;DC;MARCA;ENTRADA;FECHA;TIPO;REGULADO;NOMPROV;LOTE;VENCE;PROVEEDOR;".PHP_EOL;
                    fwrite($fs,$traza);
                    $inv = DB::table($codprove)->get();
                    foreach ($inv as $i) {
                        try {
                            $mejoropcion = BuscarMejorOpcion($i->barra, $criterio, $preferencia, $pedir, $provs);
                            if ($mejoropcion == null) {
                                continue;
                            }
                            $codprovex = $mejoropcion[0]['codprove'];
                            $codprovey = mb_strtoupper($codprove);
                            if ($codprovex != $codprovey)
                                continue;
                            switch ($tipoprecio) {
                                case '2':
                                    $precio = number_format($i->precio2/$factor, 2, '.', ',');
                                    break;
                                case '3':
                                    $precio = number_format($i->precio3/$factor, 2, '.', ',');
                                    break;
                                default:
                                    $precio = number_format($i->precio1/$factor, 2, '.', ',');
                                    break;
                            }
                            $precio = str_replace(",", "", $precio);
                            $precio = str_replace(".", ",", $precio);
                            $cantidad = str_replace(".", ",",$i->cantidad);
                            $da = str_replace(".", ",",$i->da);
                            $dc = str_replace(".", ",",$dc);
                            $iva = str_replace(".", ",",$i->iva);
                            $traza = $i->codprod .";". $i->barra .";". $i->desprod .";". $iva .";". $precio .";". $cantidad .";". $i->bulto .";". $da .";".  $dc .";". $i->marca .";". $i->fechafalla .";". $i->fechacata .";". $i->tipo .";". $i->regulado .";". $i->nomprov .";". $i->lote .";". $i->fecvence .";". $codprovex .';'. PHP_EOL;
                            fwrite($fs,$traza);
                        } catch (\Exception $e) {
                            log::info("CONVERTIR-> Warning: ".$rutaarc." - ".$e->getMessage());
                            return FALSE;
                        }
                    }
                    fclose($fs);
                    if (file_exists($rutaarc)) {
                        $headers = ['Content-type'=>'text/plain', 'test'=>'YoYo', 'Content-Disposition'=>sprintf('attachment; filename="%s"', $archivo),'X-BooYAH'=>'WorkyWorky'];
                        return response()->download($rutaarc);
                    }
                } else {
                     session()->flash('error',"Catálogo no encontrado!");
                }
            }
        } catch (Exception $e) {
            session()->flash('error',$e);
        }
    }

    public function inventario(Request $request) {
        set_time_limit(500); 
        $codcli = strtolower($request->get('codcli'));
        $moneda = Session::get('moneda', 'BSS');
        $factor = RetornaFactorCambiario("", $moneda);
        try {
            if (str_contains($codcli, 'tcmaestra')) {
                if (VerificaTabla($codcli)) {
                    $archivo = $codcli.".csv";
                    $rutaarc = public_path().'/public/storage/'.$archivo;
                    $fs = fopen($rutaarc,"w");
                    $cadcliente = "";
                    $codgrupo = Auth::user()->codcli;
                    $gruporen = DB::table('gruporen')
                    ->where('status','=', 'ACTIVO')
                    ->where('id','=',$codgrupo)
                    ->get();
                    foreach ($gruporen as $gr) {
                        $cliente = LeerCliente($gr->codcli); 
                        $nomcampo = $cliente->descripcion;
                        $gr_codcli = $gr->codcli;
                    //    $nomcampo = 'tc'.$gr_codcli;
                        $tabla = 'inventario_'.$gr_codcli;
                        if (!VerificaTabla($tabla)) 
                            continue;
                        $cadcliente = $cadcliente.$nomcampo.'_PRECIO;'.$nomcampo.'_CANT;'.$nomcampo.'_COD;'.$nomcampo.'_DESCRIP;'.$nomcampo.'_COSTO;'.$nomcampo.'_VMD;';
                    }
                    $traza1 = "barra;desprod;iva;bulto;marca;clidat;consolidado;";
                    $traza = $traza1.$cadcliente.PHP_EOL;
                    fwrite($fs,$traza);
                    $inv = DB::table(strtolower($codcli))->get();
                    foreach ($inv as $i) {
                        try {
                            $cadcliente = "";
                            foreach($gruporen as $gr) {
                                $gr_codcli = $gr->codcli;
                                $nomcampo = 'tc'.$gr_codcli;
                                $tabla = 'inventario_'.$gr_codcli;
                                if (!VerificaTabla($tabla)) 
                                    continue;
                                $valcampo = $i->$nomcampo;
                                $campo = explode("|", $valcampo);
                            
                                $precio = $campo[0];
                                $precio = number_format($precio/$factor, 2, '.', ',');
                                $precio = str_replace(",", "", $precio);
                                $precio = str_replace(".", ",", $precio);
                            
                                $cantidad = $campo[1];
                                $codprod = $campo[2];
                                $desprod = $campo[3];

                                $costo = $campo[4];
                                $costo = number_format($costo/$factor, 2, '.', ',');
                                $costo = str_replace(",", "", $costo);
                                $costo = str_replace(".", ",", $costo);
                            
                                $vmd = $campo[5];
                                $vmd = number_format($vmd, 4, '.', ',');
                                $vmd = str_replace(",", "", $vmd);
                                $vmd = str_replace(".", ",", $vmd);

                                $cadcliente = $cadcliente.$precio .";". $cantidad .";". $codprod .";". $desprod .";". $costo .";". $vmd .";";
                            }
                            $iva = $i->iva;
                            $iva = str_replace(".", ",", $iva);
                            $traza1 = $i->barra .";". $i->desprod .";". $iva .";". $i->bulto .";". $i->marca .";". $i->clidat .";". $i->consolidado .";";       
                            $traza = $traza1 . $cadcliente.PHP_EOL;
                            fwrite($fs,$traza);
                        } catch (\Exception $e) {
                            log::info("CONVERTIR-> Warning: ".$rutaarc." - ".$e->getMessage().' - LINEA: '.$e->getLine()  );
                            return FALSE;
                        }
                    }
                    fclose($fs);
                    if (file_exists($rutaarc)) {
                        $headers = ['Content-type'=>'text/plain', 'test'=>'YoYo', 'Content-Disposition'=>sprintf('attachment; filename="%s"', $archivo),'X-BooYAH'=>'WorkyWorky'];
                        return response()->download($rutaarc);
                    }
                } else {
                     session()->flash('error',"Inventario no encontrado!");
                }

            } else {
                $tabla = 'inventario_'.$codcli;
                if (VerificaTabla($tabla)) {
                    $archivo = $tabla.".csv";
                    $rutaarc = public_path().'/public/storage/'.$archivo;
                    $fs = fopen($rutaarc,"w");
                    $traza = "codprod;descrip;cantidad;vmd;precio;marca;barra;costo;iva;".PHP_EOL;
                    fwrite($fs,$traza);
                    $inv = DB::table($tabla)->get();
                    foreach ($inv as $i) {
                        try {

                            $codprod = $i->codprod; 
                            $descrip = $i->desprod;
                            $cantidad = $i->cantidad;
                            $vmd = number_format($i->vmd, 4, '.', ','); 
                            $vmd = str_replace(",", "", $vmd);
                            $vmd = str_replace(".", ",", $vmd);

                            $precio = number_format($i->precio1/$factor, 2, '.', ',');
                            $precio = str_replace(",", "", $precio);
                            $precio = str_replace(".", ",", $precio);

                            $marca = $i->codprov;
                            $barra = $i->barra;
                            
                            $costo = number_format($i->costo/$factor, 2, '.', ',');
                            $costo = str_replace(",", "", $costo);
                            $costo = str_replace(".", ",", $costo);
                        
                            $iva = $i->iva;
                            $iva = number_format($iva, 2, '.', ',');
                            $iva = str_replace(",", "", $iva);
                            $iva = str_replace(".", ",", $iva);

                            $traza = $codprod .";". $descrip .";". $cantidad .";". $vmd .";". $precio .";". $marca .";". $barra .";". $costo .";". $iva .";".PHP_EOL;


                            fwrite($fs,$traza);
                        } catch (\Exception $e) {
                            log::info("CONVERTIR-> Warning: ".$rutaarc." - ".$e->getMessage());
                            return FALSE;
                        }
                    }
                    fclose($fs);
                    if (file_exists($rutaarc)) {
                        $headers = ['Content-type'=>'text/plain', 'test'=>'YoYo', 'Content-Disposition'=>sprintf('attachment; filename="%s"', $archivo),'X-BooYAH'=>'WorkyWorky'];
                        return response()->download($rutaarc);
                    }
                } else {
                     session()->flash('error',"Inventario no encontrado!");
                }
            }

        } catch (Exception $e) {
            session()->flash('error',$e);
        }
    }

}
