<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Log;

class MinicpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Verificacion del Key de Minicp
        $keys = $request->get('keys');
        $maelicencia = DB::table('maelicencias')
        ->where('cod_lic','=',$keys)
        ->first();
        if ($maelicencia) {
            $retorno = 1;
            $status = $maelicencia->status;
            $restandias = $maelicencia->diaLicencia - DiferenciaDias($maelicencia->fec_act);
            if ($restandias <= 0) {
                $status = "INACTIVO";
                $retorno = 0;
            }
            DB::table('maelicencias')
            ->where('cod_lic','=',$keys)
            ->update(array("status" => $status,
                           "ultPing" => date('Y-m-d H:i:s')
            ));
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // VERIFICACION DE SUPEROFERTA
        $modo = $request->get('modo');
        switch ($modo) {
            case "V":
                $retornox[] = '';
                $keys = $request->get('keys');
                $prods = $request->get('prods');
                $maelicencia = DB::table('maelicencias')
                ->where('cod_lic','=',$keys)
                ->first();
                if ($maelicencia) {
                    $cadprove = $maelicencia->cadprove;
                    if (!empty($cadprove)) {
                        $s2 = explode(",", $cadprove);
                        for ($i = 0; $i < count($s2); $i++) {
                            $arrayCampo[] = trim($s2[$i]);
                        }
                        for ($i = 0; $i < count($prods); $i++) {
                            $barra = $prods[$i];
                            $mejorprecio = BuscarMejorPrecio($barra, $arrayCampo);
                            if ($mejorprecio != null) {
                                $retornox[] = $barra;
                            }
                        }
                    }
                }
                $retorno = json_encode($retornox);
                break;
            case "C":
                $keys = $request->get('keys');
                $barra = $request->get('barra');
                $maelicencia = DB::table('maelicencias')
                ->where('cod_lic','=',$keys)
                ->first();
                if ($maelicencia) {
                    $cadprove = $maelicencia->cadprove;
                    if (!empty($cadprove)) {
                        $s2 = explode(",", $cadprove);
                        for ($i = 0; $i < count($s2); $i++) {
                            $arrayCampo[] = trim($s2[$i]);
                        }
                        $tpmaestra = DB::table('tpmaestra')
                        ->select($arrayCampo)
                        ->where('barra','=',$barra)
                        ->first();
                        if (!empty($tpmaestra)) {
                            $retornox = $tpmaestra;
                        } else  {
                            $retornox = ""; 
                        }
                    }
                }
                $retorno = json_encode($retornox);
                break;
            case "CP":
                $codprove = $request->get('codprove');
                $retornox = DB::table('maeprove')
                ->where('codprove','=',$codprove)
                ->first();
                if (!empty($retornox)) {
                    $retorno = json_encode($retornox);
                    //log::info("MINCP OK-> codprove: ".$codprove);
                }
                else {
                    $retorno = json_encode("");
                    //log::info("MINCP ERROR-> codprove: ".$codprove);
                }
                break;
            case "LP":
                $retornox = "";
                $keys = trim($request->get('keys'));
                $maelicencia = DB::table('maelicencias')
                ->where('cod_lic','=',$keys)
                ->first();
                if ($maelicencia) {
                    $retornox = $maelicencia->cadprove;
                }
                $retorno = json_encode($retornox);
                break;
        }
        return $retorno;
    }
 
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
