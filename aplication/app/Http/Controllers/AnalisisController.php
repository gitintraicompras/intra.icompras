<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\UsuarioFormRequest;
use Illuminate\Validation\Rule;
//use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\MaeclieproveFormRequest;
use App\Http\Requests\MaeproveFormRequest;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;
use DB;
use Barryvdh\DomPDF\Facade as PDF;


class AnalisisController extends Controller
{
    public function __construct() {
    	$this->middleware('auth');
    }

    public function index(Request $request) {
        $filtro=trim($request->get('filtro'));
        $subtitulo = "ANALISIS";
        $criterio = 'PRECIO';
        $preferencia = "NINGUNA";
        $pedir = 1;
        $moneda = Session::get('moneda', 'BSS');
        $factor = RetornaFactorCambiario('', $moneda);
        $provs = TablaMaecliproveActiva("");
        $codcli = sCodigoClienteActivo();
        $cliente = DB::table('maecliente')
        ->where('codcli','=',$codcli)
        ->first();
        if ($cliente) {
            $mostrarprecio = ($cliente->mostrarprecio == 0) ? 1 : $cliente->mostrarprecio;  
        }
        $tabla = "inventario_".$codcli;
        $invent = null;
        if (VerificaTabla($tabla)) {
            $invent = DB::table($tabla)
            ->where('cuarentena', '=', '0')
            ->where('cantidad', '>', '0')
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
            ->where('cantidad', '>', '0')
            ->selectRaw('count(*) as contador')->first();
            $contador =number_format($inv->contador,0);

            if ($invent->count()>0) {
                $fecha = date('d-m-Y H:i', strtotime($invent[0]->feccatalogo));
                $subtitulo = "ANALISIS DE COSTO vs. CATALOGO (".$fecha." , RENGLONES: ".$contador.")";
            }
            
        } 
        return view('isacom.analisis.index' ,["menu" => "Inventario",
                                              "cfg" => DB::table('maecfg')->first(),
                                              "invent" => $invent, 
                                              "provs" => $provs,
                                              "codcli" => $codcli,
                                              "filtro" => $filtro,
                                              "criterio" => $criterio,
                                              "preferencia" => $preferencia,
                                              "pedir" => $pedir,
                                              "mostrarprecio" => $mostrarprecio,
                                              "factor" => $factor,
                                              "subtitulo" => $subtitulo ]);
    }

    public function excel(Request $request) {
        try {
            $archivo = 'analisis.csv';
            $BASE_PATH = env('INTRANET_RUTA_PUBLIC', base_path());
            $rutaarc = $BASE_PATH.'/public/storage/'.$archivo;

            $subtitulo = "ANALISIS";
            $criterio = 'PRECIO';
            $preferencia = "NINGUNA";
            $pedir = 1;
            $moneda = Session::get('moneda', 'BSS');
            $factor = RetornaFactorCambiario('', $moneda);
            $provs = TablaMaecliproveActiva("");
            $codcli = sCodigoClienteActivo();
            $cliente = DB::table('maecliente')
            ->where('codcli','=',$codcli)
            ->first();
            if ($cliente) {
                $mostrarprecio = ($cliente->mostrarprecio == 0) ? 1 : $cliente->mostrarprecio;  
            }
            $tabla = "inventario_".$codcli;
            $invent = null;
            if (VerificaTabla($tabla)) {
                $invent = DB::table($tabla)
                ->where('cuarentena', '=', '0')
                ->where('cantidad', '>', '0')
                ->orderBy('desprod','asc')
                ->get();

                $inv = DB::table($tabla)
                ->where('cuarentena', '=', '0')
                ->where('cantidad', '>', '0')
                ->selectRaw('count(*) as contador')->first();
                $contador =number_format($inv->contador,0);

                if ($invent->count()>0) {
                    $fecha = date('d-m-Y H:i', strtotime($invent[0]->feccatalogo));
                    $subtitulo = "ANALISIS DE COSTO vs. CATALOGOINVENTARIO (".$fecha." , RENGLONES: ".$contador.")";
                }
                
            }
            $fs = fopen($rutaarc,"w");
            $cfg = DB::table('cfg')->first();
            $traza = $subtitulo.PHP_EOL;
            fwrite($fs,$traza);
            $traza="#;PRODUCTO;CODIGO;BARRA;MARCA;CANTIDAD;COSTO;UTIL;PRECIO;MENOR;PROMEDIO;MAYOR;".PHP_EOL;
            fwrite($fs,$traza);
            $item=0;
            foreach ($invent as $t) {
                switch ($mostrarprecio) {
                      case 1:
                         $precio = $t->precio1;
                         break;
                      case 2:
                         $precio = $t->precio2;
                         break;
                      case 3:
                         $precio = $t->precio3;
                         break;
                      default:
                         $precio = $t->precio1;
                         break; 
                }
                $menorpp = 0.00;
                $mayorpp = 0.00;
                $promepp = 0.00;
                $util = 0;
                    if ($precio > 0) {
                    $util = -1*((( $t->costo / $precio )*100)-100);
                }
                $mejoropcion = BuscarMejorOpcion($t->barra, $criterio, $preferencia, $pedir, $provs);
                if ($mejoropcion != null) {
                    $sumprecio = 0;
                    $contprov = count($mejoropcion);
                 
                    $codprove = $mejoropcion[0]['codprove'];
                    $maeclieprove = DB::table('maeclieprove')
                    ->where('codcli','=',$codcli)
                    ->where('codprove','=',$codprove)
                    ->first();
                    $dc = $maeclieprove->dcme;
                    $di = $maeclieprove->di;
                    $pp = $maeclieprove->ppme;
                    $da = $mejoropcion[0]['da'];
                    $menorpp = CalculaPrecioNeto($mejoropcion[0]['precio'], $da, $di, $dc, $pp, 0.00);

                    $codprove = $mejoropcion[$contprov-1]['codprove'];
                    $maeclieprove = DB::table('maeclieprove')
                    ->where('codcli','=',$codcli)
                    ->where('codprove','=',$codprove)
                    ->first();
                    $dc = $maeclieprove->dcme;
                    $di = $maeclieprove->di;
                    $pp = $maeclieprove->ppme;
                    $da = $mejoropcion[$contprov-1]['da'];
                    $mayorpp = CalculaPrecioNeto($mejoropcion[$contprov-1]['precio'], $da, $di, $dc, $pp, 0.00);
                    for ($x=0; $x < $contprov; $x++ ) {

                        $codprove = $mejoropcion[$x]['codprove'];
                        $maeclieprove = DB::table('maeclieprove')
                        ->where('codcli','=',$codcli)
                        ->where('codprove','=',$codprove)
                        ->first();
                        $dc = $maeclieprove->dcme;
                        $di = $maeclieprove->di;
                        $pp = $maeclieprove->ppme;
                        $da = $mejoropcion[$x]['da'];
                        $prec = CalculaPrecioNeto($mejoropcion[$x]['precio'], $da, $di, $dc, $pp, 0.00);
                        $sumprecio += $prec;

                    }
                    $promepp = $sumprecio/$contprov;
                }
                $barra = strval( (float) $t->barra);
                
                $costo = number_format($t->costo/$factor, 2, '.', ',');                
                $costo = str_replace(".", ",", $costo);

                $util = number_format($util, 2, '.', ',');
                $util = str_replace(".", ",", $util);

                $precio = number_format($precio/$factor, 2, '.', ',');
                $precio = str_replace(".", ",", $precio);

                $menorpp = number_format($menorpp/$factor, 2, '.', ',');
                $menor = str_replace(".", ",", $menorpp);

                $promepp = number_format($promepp/$factor, 2, '.', ',');
                $promedio = str_replace(".", ",", $promepp);

                $mayorpp = number_format($mayorpp/$factor, 2, '.', ',');
                $mayor = str_replace(".", ",", $mayorpp);

                $item++;
                $traza = $item.";".$t->desprod.";".$t->codprod.";".$barra.";".$t->marca.";".$t->cantidad.";".$costo.";".$util.";".$precio.";".$menor.";".$promedio.";".$mayor.";".PHP_EOL;
                fwrite($fs,$traza);
            }
            fclose($fs);
            $headers = ['Content-type'=>'text/plain', 'test'=>'YoYo', 'Content-Disposition'=>sprintf('attachment; filename="%s"', $archivo),'X-BooYAH'=>'WorkyWorky'];
            return response()->download($rutaarc);
        } catch (Exception $e) {
            session()->flash('error',$e);
        }
    }
  
 }
