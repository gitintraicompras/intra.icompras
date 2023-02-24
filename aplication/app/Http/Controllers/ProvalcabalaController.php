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
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Barryvdh\DomPDF\Facade as PDF;
use DB;

class ProvalcabalaController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth');
    }

    public function index(Request $request) {
        if ($request) {
            $cfg = DB::table('maecfg')->first();
            $codprove = sCodigoClienteActivo();
            $subtitulo = "ALCABALA";
            $filtro=trim($request->get('filtro'));
            $tabla=DB::table('pedido as p')
            ->where(function ($q) use ($filtro) {
                $q->where('p.nomcli','LIKE','%'.$filtro.'%')
                ->Orwhere('p.codcli','LIKE','%'.$filtro.'%');
            })
            ->whereExists(function($query) use ($codprove)  {
                $query->select(DB::raw(1))
                    ->from('pedren as pr')
                    ->whereRaw('pr.id = p.id')
                    ->whereRaw("pr.estado = 'ENVIADO'")
                    ->where('pr.codprove', $codprove);
            })
            ->orderBy('p.id','desc')
            ->paginate(100);
            return view('isacom.provalcabala.index' ,["menu" => "Alcabala",
                                                      "cfg" => $cfg,
                                                      "tabla" => $tabla, 
                                                      "cfg" => $cfg,
                                                      "filtro" => $filtro,
                                                      "codprove" => $codprove,
                                                      "subtitulo" => $subtitulo]);
        }
    }

    public function show($id) {
        $cfg = DB::table('maecfg')->first();
        $codprove = sCodigoClienteActivo();
    
        // TABLA DE PEDIDO
        $tabla = DB::table('pedido')
                ->where('id','=',$id)
                ->first();

        // TABLA DE RENGLONES DE PEDIDO
        $tabla2 = DB::table('pedren')
                ->where('id','=',$id)
                ->where('codprove','=',$codprove)
                ->get();

        $provped[] = CalculaProvTotalesPedido($id, $codprove);
        $subtitulo = "PEDIDO (".$tabla->nomcli.")";
   
        return view('isacom.provalcabala.show',["menu" => "Alcabala",
                                                "cfg" => $cfg,
                                                "tabla" => $tabla, 
                                                "tabla2" => $tabla2, 
                                                "cfg" => $cfg,
                                                "subtitulo" => $subtitulo,
                                                "provped" => $provped,
                                                "id" => $id]);
    }

    public function edit($id) {
        $subtitulo = "PROCESAR PEDIDO";
        $tabla = DB::table('pedido')
        ->where('id','=',$id)
        ->first();

        $codcli = $tabla->codcli;
        $cliente = DB::table('maecliente')
        ->where('codcli','=',$codcli)
        ->first();
         
        $codprove = sCodigoClienteActivo();
        $provped[] = CalculaProvTotalesPedido($id, $codprove);

        return view("isacom.provalcabala.edit",["menu" => "Alcabala",
                                                "cfg" => DB::table('maecfg')->first(),
                                                "tabla" => $tabla, 
                                                "cliente" => $cliente,
                                                "provped" => $provped,
                                                "subtitulo" => $subtitulo]);
    }

    public function update(Request $request, $id) {
        try {
            DB::beginTransaction();
            $codprove = sCodigoClienteActivo();
            $status = $request->get('status'); 
            $observ = $request->get('observ'); 
      
            DB::table('pedren')
            ->where('id', '=', $id)
            ->where('codprove', '=', $codprove)
            ->update(array("estado" => $status,
                           "observ" => $observ,
                           "fecprocesado" => date('Y-m-j H:i:s')
            ));
      

            DB::commit();
            session()->flash('message', 'Pedido '.$id.'->('.$status.') procesado satisfactoriamente');
        } catch (Exception $e) {
            DB::rollBack();
            session()->flash('error', $e);
        }
        return Redirect::to('/provalcabala');
    }

    public function descargar($id) {

        $codprove = sCodigoClienteActivo();
     
        $fechaHoy = date('j-m-Y');
        $FechaPedido = substr($fechaHoy, 0, 10);

        $tabla2 = DB::table('pedren')
        ->where('id','=',$id)
        ->where('codprove','=',$codprove)
        ->get();

        // TABLA DE PEDIDO
        $tabla = DB::table('pedido')
                ->where('id','=',$id)
                ->first();

        $titulo = "PEDIDO: ".$id;
        $subtitulo = $tabla->nomcli;
        $codcli = $tabla->codcli;

        $maeprove = LeerProve($codprove);

        // TABLA DE RENGLONES DE PEDIDO
        $cliente = DB::table('maecliente')
                ->where('codcli','=',$codcli)
                ->first();

        // CALCULO DE TOTALES DEL PEDIDO
        $dSubrenglon = 0.00;
        $dDecuento = 0.00;
        $dSubtotal = 0.00;
        $dImpuesto = 0.00;
        $dTotal = 0.00;
        foreach ($tabla2 as $pr) {
            $neto = CalculaPrecioNeto($pr->precio, $pr->da, $pr->di, $pr->dc, $pr->pp, $pr->dp);
            $subtotal = $neto * $pr->cantidad;
            if ($pr->iva > 0) {
                $dImpuesto = $dImpuesto + (($subtotal * $pr->iva)/100);
            }
            $dSubtotal = $dSubtotal + $subtotal;
            $dSubrenglon = $dSubrenglon + ($pr->precio * $pr->cantidad);
        }
        $dDecuento = $dSubrenglon - $dSubtotal;
        $dTotal = $dSubtotal + $dImpuesto;

        $data = [
            "titulo" => $titulo,
            "subtitulo" => $subtitulo,
            "tabla" => $tabla, 
            "tabla2" => $tabla2, 
            "impuesto" => $dImpuesto,
            "total" => $dTotal,
            "maeprove" => $maeprove,
            "codprove" => $codprove,
            "cliente" => $cliente,
            "cfg" => DB::table('maecfg')->first(),
            "id" => $id
        ];
        return PDF::loadView('layouts.rptpedido', $data)
        ->download('pedido_'.$codprove.'_'.$codcli.'.pdf');
    }

}
