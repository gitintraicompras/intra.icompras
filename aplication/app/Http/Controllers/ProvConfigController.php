<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Maeprove;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\MaeproveFormRequest;
use DB; 

class ProvConfigController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
 
 
    public function edit($codprove) {
	     return view("isacom.provconfig.edit",["menu" => "Configuracion",
                                           "subtitulo" => "EDITAR PROVEEDOR",
                                           "proveedor"=>Maeprove::findOrFail($codprove)]);
    }

    public function update(Request $request, $codprove) {
        DB::table('maeprove')
        ->where('codprove', '=', $codprove)
        ->update(array("descripcion" => $request->get('descripcion'),
                       'backcolor' => $request->get('backcolor'),
                       'forecolor' => $request->get('forecolor'),
                       'rutalogo1' => $request->get('rutalogo1'),
                       'rutalogo2' => $request->get('rutalogo2'),
                       'codsede' => $request->get('codsede'),
                       'status' => $request->get('status'),
                       'nombre' => $request->get('nombre'),
                       'direccion' => $request->get('direccion'),
                       'telefono' => $request->get('telefono'),
                       'contacto' => $request->get('contacto'),
                       'correo' => $request->get('correo'),
                       'web' => $request->get('web'),
                       'localidad' => $request->get('localidad'),
                       'factorModo' => 'PREDETERMINADO',
                       'factorSeleccion' => 'MANUAL',
                       'FactorCambiario' => $request->get('FactorCambiario'),
                       'correoEnvioPedido' => $request->get('correoEnvioPedido')
        ));
   	    return Redirect::to('/');
    }

   
}
