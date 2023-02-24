<?php 
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\UsuarioFormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use DB; 
  
class ConfigController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth');
    }
 
    public function edit($id)  { 
        $subtitulo = "EDITAR CONFIGURACION";
        $cfg = DB::table('maecfg')->first();
        $codcli = $id;
        $cliente = DB::table('maecliente')
        ->where('codcli','=',$codcli)
        ->first();
        $aplicainv = false;
        $tabla = "inventario_".$codcli;
        if (VerificaTabla($tabla)) {
            $aplicainv = true;
        }
        return view("isacom.config.edit",["menu" => "Configuracion",
                                          "cfg" => $cfg,
                                          "cliente" => $cliente,
                                          "codcli" => $codcli,
                                          "aplicainv" => $aplicainv,
                                          "subtitulo" => $subtitulo]);
    }

    public function update(Request $request, $codcli) {
        try {
            DB::beginTransaction();
            $descrip = trim($request->get('descripcion'));
            $descrip = str_replace(" ", "", $descrip);
            $descrip = mb_strtoupper($descrip);
            if (strlen($descrip) > 20)
                $descrip = substr($descrip, 0, 20);
    		DB::table('maecliente')
                ->where('codcli','=',$codcli)
            	->update(array("direccion" => $request->get('direccion'),
                "dc" => $request->get('dc'),
                "di" => $request->get('di'),
                "pp" => $request->get('pp'),
                "utilm" => $request->get('utilm'),
                "damax" => $request->get('damax'),
                "damin" => $request->get('damin'),
                "usaprecio" => $request->get('usaprecio'),
                "telefono" => $request->get('telefono'),
                "contacto" => $request->get('contacto'),
                "usuario" => $request->get('usuario'),
                "clave" => $request->get('clave'),
                "zona" => $request->get('zona'),
                "fecha" => $request->get('fecha'),
                "estado" => $request->get('estado'),
                "campo1" => $request->get('campo1'),
                "campo3" => $request->get('campo3'),
                "campo4" => ($request->get('campo4')=='on') ? '1': '2',
                "campo5" => ($request->get('campo5')=='on') ? '1': '2',
                "campo6" => ($request->get('campo6')=='on') ? '1': '2',
                "campo7" => $request->get('campo7'),
                "campo8" => $request->get('campo8'),
                "campo9" => $request->get('campo9'), 
                "campo10" => $request->get('campo10'),
                "cadena" => $request->get('cadena'),
                "sector" => $request->get('sector'),
                "forecolor" => $request->get('forecolor'),
                "backcolor" => $request->get('backcolor'),
                "descripcion" => $descrip,
                "SinInvConFrec" => ($request->get('SinInvConFrec')=='on') ? '1': '0',
                "diasTransito" => $request->get('diasTransito'),
                "ModoNotiTrans" => $request->get('ModoNotiTrans'),
                "min" => $request->get('min'),
                "max" => $request->get('max'),
                "usaprecio" => $request->get('usaprecio'),
                "PreCosMoneda" => $request->get('PreCosMoneda'),
                "mesNotVence" => $request->get('mesNotVence'),
                "GenPedAuto" => ($request->get('GenPedAuto')=='on') ? '1': '0',
                "CampoMarcaInv" => $request->get('CampoMarcaInv'),
                "CriPedAuto" => $request->get('CriPedAuto'),
                "PrePedAuto" => $request->get('PrePedAuto'),
                "MostrarGraAho" => ($request->get('MostrarGraAho')=='on') ? '1': '0'
           	));

            $file = $request->file('rutaimg');
            if ($file) {
                $rutaimg = $file->getClientOriginalName();
                $file->move('/home/qy9dy4z3xvjb/public_html/isaweb.isbsistemas.com/public/storage', $rutaimg);
                DB::table('maecliente')
                ->where('codcli','=',$codcli)
                ->update(array("rutaimg" => $rutaimg));
            }

            $id = Auth::user()->id;
            $reg = User::findOrFail($id);
            $reg->botonEnvio = ($request->get('botonEnvio')=='on') ? '1': '0';
            $reg->update();

            DB::commit();
            session()->flash('message', 'CONFIGURACION GUARDADA SATISFACTORIAMENTE');
        } catch (Exception $e) {
            DB::rollBack();
            session()->flash('error', $e);
        }
		return Redirect::to('/');
	}
     
    public function correo(Request $request) {
        try {
            $asunto = $request->get('asunto');
            $remite = $request->get('remite');
            $contenido = $request->get('contenido');
            $cfg = DB::table('maecfg')->first();
            $destino = $cfg->correosoporte;
            if (bEnviaCorreo($asunto, $remite, $destino, $contenido)) 
                session()->flash('message', 'Correo enviado satisfactoriamente');
            else
                session()->flash('error', 'Correo no enviado');
        } catch (Exception $e) {
            DB::rollBack();
            session()->flash('error', $e);
        }
        return Redirect::to('/');
    }

    public function registrar(Request $request) {
        try {
            $keys = $request->get('keys');
            $maelicencia = DB::table('maelicencias')
            ->where('cod_lic','=',$keys)
            ->first();
            if (empty($maelicencia)) {
                return back()->with('error', 'Código Keys no encontrado!');  
            }
            else {
                $codcli = sCodigoClienteActivo();
                //dd("CODISB: ".$codcli." ESTADO: ".$maelicencia->estado);
                if ($maelicencia->estado == "P") {
                    return back()->with('warning', 'Código Keys ya se encuentra activa!');  
                } else {
                    DB::table('maecliente')
                    ->where('codcli','=',$codcli)
                    ->update(array("KeyIsacom" => $keys ));

                    DB::table('maelicencias')
                    ->where('cod_lic','=',$keys)
                    ->update(array("codisb" => $codcli, 
                                   "serial" => "N/A", 
                                   "estado" => "P",
                                   "version" => "1.0.0.0", 
                                   "fec_act" =>  date('Y-m-d H:i:s') ));
                    return back()->with('message', 'Licencia registrada satisfactoriamente');
                }
            }
        } catch (Exception $e) {
            return back()->with('error', $e);  
        }
    }
    
    public function postImage(Request $request) {
        try {

            //log::info("PASO0");

            $BASE_PATH = "/home/qy9dy4z3xvjb/public_html/isaweb.isbsistemas.com/public/storage/";      
            $this->validate($request, [
                'photo' => 'required|image'
            ]);

            //log::info("PASO1");
       
            $extension = $request->file('photo')->getClienteOriginalExtension();
            $file_name = "suc_".$codcli.".".$extension;

            $path = $BASE_PATH.$file_name;

            //log::info("PATH:".$path);

            Image::make($request->file('photo'))
            ->fit(270, 270)
            ->save($path);

            //log::info("PASO3");

            $codcli = $request->get('codcli');
            $cliente = DB::table('maecliente')
            ->where('codcli','=',$codcli)
            ->first();
            if ($cliente) {
                DB::table('maecliente')
                ->where('codcli','=',$codcli)
                ->update(array("rutaimg" => $file_name ));
            }

            $date['success'] = true;
            $data['path'] = $path;
            return $data;
        } catch (Exception $e) {
               log::info("ERROR: ".$e); 
        }
    }
}
