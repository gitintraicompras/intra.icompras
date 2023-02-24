<?php
namespace App\Http\Controllers\Canales;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Maecanales;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\MaecanalesFormRequest;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use DB;

class ConfigController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function edit($id) {
        return view("canales.canal.edit",["menu" => "Canales",
                                          "subtitulo" => "EDITAR CANAL",
                                          "canal" => Maecanales::findOrFail($id)]);
    }

    public function update(MaecanalesFormRequest $request, $id) {
        $regs = Maecanales::findOrFail($id);
        $regs->descrip = $request->get('descrip');
        $regs->fecha = $request->get('fecha');
        $regs->estado = $request->get('estado');
        $regs->rif = $request->get('rif');
        $regs->direccion = $request->get('direccion');
        $regs->telefono = $request->get('telefono');
        $regs->contacto = $request->get('contacto');
        $regs->correo = $request->get('correo');
        $regs->zona = $request->get('zona');
        $regs->opc1 = $request->get('opc1');
        $regs->opc2 = $request->get('opc2');
        $regs->opc3 = $request->get('opc3');
        $regs->opc4 = $request->get('opc4');
        $regs->opc5 = $request->get('opc5');
        // GENERA EL CODIGO DEL CANAL
        $sId = "0000$id";
        $largo = strlen($sId);
        $sCodcanal = substr($sId, $largo-5, 5);
        $regs->codcanal = $sCodcanal;
        $regs->update();
        return Redirect::to('/');
    }
    
}
