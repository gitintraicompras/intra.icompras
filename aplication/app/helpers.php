<?php
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Console\Command;
use App\User;
use App\Maeprove;
use App\Maeclieprove;
use Barryvdh\DomPDF\Facade as PDF;
use App\CustomClasses\ColectionPaginate;

function BuscarRanking($tpmaestra, $provs, $precio)
{
    $arrayRnk = [];
    $dataprod = obtenerDataTpmaestra($tpmaestra, $provs, 0);
    if (!is_null($dataprod)) {
        $arrayRnk = $dataprod['arrayRnk'];
    }
    //log::info($dataprod['arrayRnk']);
    //log::info("Precio: ".$precio);
    $precio = $precio + (($precio * $tpmaestra->iva) / 100);
    $ranking = obtenerRanking($precio, $arrayRnk);
    if (empty($ranking)) {
        $ranking = "1-" . count($arrayRnk);
    }
    return $ranking;
}
function GenerarPedidoAutomatico($codcli, $criterio, $preferencia, $tipedido)
{
    $id = -1;
    $primer = 0;
    $provs = TablaMaecliproveActiva($codcli);
    $tabla = DB::table('sugerido')
        ->where('codcli', '=', $codcli)
        ->get();
    if ($tabla->count() > 0) {
        foreach ($tabla as $sug) {
            $barra = $sug->barra;
            $tpmaestra = DB::table('tpmaestra')
                ->where('barra', '=', $barra)
                ->first();
            if ($tpmaestra) {
                $pedir = $sug->pedir;
                $mejoropcion = BuscarMejorOpcion($barra, $criterio, $preferencia, $pedir, $provs);

                if ($mejoropcion != null) {

                    if ($primer == 0) {
                        $primer = 1;
                        $id = iCrearPedidoNuevo($codcli, $tipedido, '', 7, 0);
                        DB::table('pedren')
                            ->where('id', '=', $id)
                            ->delete();

                        DB::table('fallas')
                            ->where('codcli', '=', $codcli)
                            ->delete();

                        DB::table('pedido')
                            ->where('id', '=', $id)
                            ->update(
                                array(
                                    "estado" => 'NUEVO',
                                    'fecha' => date("Y-m-d H:i:s"),
                                    'fecenviado' => date("Y-m-d H:i:s")
                                )
                            );
                    }
                    $pedirx = $pedir;
                    $contprov = count($mejoropcion);
                    for ($x = 0; $x < $contprov; $x++) {

                        $cantidad = $mejoropcion[$x]['cantidad'];
                        if ($pedirx <= $cantidad) {
                            $pedirx = $pedir;
                            $pedir = $pedir - $pedirx;
                        } else {
                            $pedirx = $cantidad;
                            $pedir = $pedir - $cantidad;
                        }

                        $precio = $mejoropcion[$x]['precio'];
                        $da = $mejoropcion[$x]['da'];
                        $codprod = $mejoropcion[$x]['codprod'];
                        $fechafalla = $mejoropcion[$x]['fechafalla'];
                        $lote = $mejoropcion[$x]['lote'];
                        $fecvence = $mejoropcion[$x]['fecvence'];
                        $desprod = $mejoropcion[$x]['desprod'];
                        $codprove = $mejoropcion[$x]['codprove'];
                        $dcredito = $mejoropcion[$x]['dias'];
                        $ranking = $mejoropcion[$x]['ranking'];

                        $maeclieprove = DB::table('maeclieprove')
                            ->where('codcli', '=', $codcli)
                            ->where('codprove', '=', $codprove)
                            ->first();
                        $dc = $maeclieprove->dcme;
                        $di = $maeclieprove->di;
                        $pp = $maeclieprove->ppme;
                        $neto = CalculaPrecioNeto($precio, $da, $di, $dc, $pp, 0.00);
                        $ahorro = dBuscarMontoAhorro($barra, $neto, $codcli);
                        $subtotal = floatval($neto) * intval($pedirx);

                        DB::table('pedren')->insert([
                            'id' => $id,
                            'codprod' => $codprod,
                            'desprod' => $desprod,
                            'cantidad' => $pedirx,
                            'precio' => $precio,
                            'barra' => $barra,
                            'codprove' => $codprove,
                            'regulado' => $tpmaestra->regulado,
                            'tipo' => $tpmaestra->tipo,
                            'pvp' => $precio,
                            'iva' => $tpmaestra->iva,
                            'da' => $da,
                            'di' => $di,
                            'dc' => $dc,
                            'pp' => $pp,
                            'neto' => $neto,
                            'codcli' => $codcli,
                            'ahorro' => $ahorro,
                            'subtotal' => $subtotal,
                            'aprobacion' => "NO",
                            'estado' => "NUEVO",
                            "fecha" => date("Y-m-d H:i:s"),
                            "fecenviado" => date("Y-m-d H:i:s"),
                            "ranking" => $ranking,
                            "bulto" => $tpmaestra->bulto
                        ]);

                        if ($pedir <= 0)
                            break;
                        $pedirx = $pedir;

                    }

                    if ($pedirx > 0) {

                        //log::info("MEJOR OPCION -> FALLAS(c): ".$sug->codprod. ' - '.$pedir.' - '.$barra);

                        DB::table('fallas')->insertGetId([
                            'codprod' => $sug->codprod,
                            'pedir' => $pedirx,
                            'desprod' => $sug->desprod,
                            'barra' => $barra,
                            'codcli' => $codcli,
                            'fecha' => date('Y-m-d H:i:s')
                        ]);

                    }

                } else {

                    //log::info("MEJOR OPCION -> FALLAS: ".$sug->codprod. ' - '.$pedir.' - '.$barra);

                    DB::table('fallas')->insertGetId([
                        'codprod' => $sug->codprod,
                        'pedir' => $pedir,
                        'desprod' => $sug->desprod,
                        'barra' => $barra,
                        'codcli' => $codcli,
                        'fecha' => date('Y-m-d H:i:s')
                    ]);
                }
            }
        }
        if ($primer > 0) {
            CalculaTotalesPedido($id);
        }
    }
    return $id;
}
function GenerarSugeridoAutomatico()
{

    $cliente = DB::table('maecliente')
        ->where('GenPedAuto', '=', 1)
        ->where('estado', '=', 'ACTIVO')
        ->get();
    foreach ($cliente as $cli) {
        $codcli = $cli->codcli;
        $tabla = "inventario_" . $codcli;
        if (VerificaTabla($tabla)) {

            //log::info("CODCLI: ".$codcli);

            $sugerido = DB::table('sugerido')
                ->where('codcli', '=', $codcli)
                ->delete();

            $invent = DB::table($tabla)
                ->where('cuarentena', '=', '0')
                ->where('vmd', '>', '0')
                ->orderBy('desprod', 'asc')
                ->get();

            $contador = 0;
            foreach ($invent as $inv) {

                $minmax = LeerMinMax($codcli, $inv->codprod);
                $min = $minmax["min"];
                $max = $minmax["max"];
                $cendis = $minmax["cendis"];


                if ($cendis == 0) {
                    continue;
                }

                //log::info("CENDIS: ".$cendis." MIN: ".$min." MAX: ".$max." MARCA: ".$inv->subgrupo);


                $transito = verificarProdTransito($inv->barra, $codcli, "");
                $existencia = $inv->cantidad + $transito;

                $dias = abs($existencia / $inv->vmd);

                // MEJORA
                if ($dias > $max) {
                    continue;
                }

                if ($dias <= $min) {

                    $pedir = ($max * $inv->vmd) - $existencia;

                    if ($pedir > 0) {
                        DB::table('sugerido')->insertGetId([
                            'codprod' => $inv->codprod,
                            'pedir' => $pedir,
                            'desprod' => $inv->desprod,
                            'barra' => $inv->barra,
                            'codcli' => $codcli,
                            'fecha' => date('Y-m-d H:i:s')
                        ]);
                        $contador++;
                    }
                }

            }
            //log::info("SUGERIDO: ".$codcli." CONTADOR: ".$contador);
            if ($contador > 0) {
                $criterio = $cli->CriPedAuto;
                $preferencia = $cli->PrePedAuto;
                $tipedido = "A";
                $id = GenerarPedidoAutomatico($codcli, $criterio, $preferencia, $tipedido);
                if ($id > 0) {
                    log::info("PEDIDO AUTO: " . $id . " CLIENTE: " . $codcli . " - " . $cli->nombre . " - CREADO OK");
                }
            }

        }
    }
}
function GeneraArrayMarcaAlterna($codcli)
{
    $array = [];
    try {
        $tabla = 'inventario_' . $codcli;
        if (VerificaTabla($tabla)) {
            $prod = DB::table($tabla)->get();
            foreach ($prod as $p) {
                $marcaAlterna = trim($p->subgrupo);
                $existe = 0;
                foreach ($array as $a) {
                    if ($a == $marcaAlterna) {
                        $existe = 1;
                        break;
                    }
                }
                if ($existe == 0) {
                    $array[] = $marcaAlterna;
                }
            }
        }
    } catch (\Exception $e) {
        log::info("TABLA MARCA ALTERNA-> ERROR: " . $e->getMessage());
    }
    return $array;
}
function LeerMinMax($codcli, $codprod)
{
    if ($codcli == "")
        $codcli = sCodigoClienteActivo();
    $minmax = DB::table('minmax')
        ->where('codcli', '=', $codcli)
        ->where('codprod', '=', $codprod)
        ->first();
    if (empty($minmax)) {
        $minmax = ["min" => 0, "max" => 0, "cendis" => 0];
    } else {
        $minmax = ["min" => $minmax->min, "max" => $minmax->max, "cendis" => $minmax->cendis];
    }
    return $minmax;
}
function bValida_Preempaque($pedir, $up, $dp)
{
    //log::info("PEDIR: ".$pedir." UP: ".$up." DP: ".$dp);
    if ($pedir == 0)
        return FALSE;
    if ($dp == 0)
        return FALSE;
    if ($up == 0)
        return FALSE;
    $aplica = FALSE;
    if ($pedir >= $up) {
        $n = $pedir;
        while ($n > 0) {
            $n = $n - $up;
        }
        if ($n == 0)
            $aplica = TRUE;
    }
    //log::info("APLICA: ".$aplica);
    return $aplica;
}
function VerificarCodprove($codprove, $codigo, $user, $pass)
{
    $maeprove = LeerProve($codprove);
    $modoEnvioPedido = $maeprove->modoEnvioPedido;
    $tipocata = $maeprove->tipocata;
    $resp = FALSE;
    //log::info("tipo: ".$modoEnvioPedido);
    switch ($modoEnvioPedido) {
        case 'MYSQL':
            try {
                $host = $maeprove->host;
                $basedato = $maeprove->basedato;
                $username = $maeprove->username;
                $clave = $maeprove->password;
                if (empty($host) || empty($basedato) || empty($username) || empty($clave)) {
                    log::info("resp: " . $codprove . " ,FALTAN DATOS DE CONEXION");
                } else {
                    Config::set("database.connections.mysql2", [
                        "driver" => "mysql",
                        "host" => $host,
                        "database" => $basedato,
                        "username" => $username,
                        "password" => $clave
                    ]);
                    Config::set('database.default', 'mysql2');
                    DB::reconnect('mysql2');
                    log::info("CONEXION REMOTA (MYSQL1)     -> OK: " . $maeprove->descripcion);
                    $cliente = DB::table('cliente')
                        ->where('codcli', '=', $codigo)
                        ->where('estado', '=', 'ACTIVO')
                        ->first();
                    if (!empty($cliente)) {
                        $resp = TRUE;
                    }
                    DB::purge('mysql2');
                    Config::set('database.default', 'mysql');
                    DB::reconnect('mysql');
                }
            } catch (\Exception $e) {
                log::info("resp: " . $e);
            }
            break;
        case 'SIAD':
            // COBECA
            try {
                $url = "http://www.drogueriascobeca.com/SIC/Account/LoginRedirect?us=" . $user . "&ps=" . $pass;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($ch, CURLOPT_TIMEOUT, 60);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_exec($ch);
                $resp = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                log::info("resp: " . $resp);
                $resp = ($resp == 302) ? TRUE : FALSE;
            } catch (\Exception $e) {
                log::info("resp: " . $e);
            }
            break;
        case "CORREO":
            try {
                $resp = TRUE;
            } catch (\Exception $e) {
                log::info("resp: " . $e);
            }
            break;
        case 'FTP':
            switch ($tipocata) {
                case "DRONENA":
                    try {
                        $ftp = $maeprove->ftpserver;
                        $ftpuser = $maeprove->ftpuser;
                        $ftppass = $maeprove->ftppass;
                        $ftppasv = $maeprove->ftppasv;
                        $rutbase = $maeprove->ftprutapedido;
                        $ruta = $rutbase . "/" . $codigo;
                        log::info("PING PROVEEDOR DRONENA");
                        log::info("ftp         : " . $ftp);
                        log::info("ftpuser     : " . $ftpuser);
                        log::info("ftppass     : " . $ftppass);
                        log::info("ftppasv     : " . $ftppasv);
                        log::info("ftpruta     : " . $ruta);
                        $resp = iListDirectoryFtp($ftp, $ftpuser, $ftppass, $ftppasv, $ruta);
                        if ($resp == 0) {
                            $resp = TRUE;
                        }
                    } catch (\Exception $e) {
                        log::info("resp: " . $e);
                    }
                    break;
                case 'DROLANCA':
                    try {
                        $ftp = $maeprove->ftpserver;
                        $ftpuser = $codigo;
                        $ftppass = $codigo;
                        $ftppasv = $maeprove->ftppasv;
                        $ruta = $maeprove->ftprutapedido;
                        log::info("PING PROVEEDOR DROLANCA");
                        log::info("ftp         : " . $ftp);
                        log::info("ftpuser     : " . $ftpuser);
                        log::info("ftppass     : " . $ftppass);
                        log::info("ftppasv     : " . $ftppasv);
                        log::info("ftpruta     : " . $ruta);
                        $resp = iListDirectoryFtp($ftp, $ftpuser, $ftppass, $ftppasv, $ruta);
                        if ($resp == 0) {
                            $resp = TRUE;
                        }
                    } catch (\Exception $e) {
                        log::info("resp: " . $e);
                    }
                    break;
                case 'DROCERCA':
                    try {
                        $ftp = $maeprove->ftpserver;
                        $ftpuser = $user;
                        $ftppass = $pass;
                        $ftppasv = $maeprove->ftppasv;
                        $rutbase = $maeprove->ftprutapedido;
                        $ruta = $maeprove->ftprutapedido;
                        log::info("PING PROVEEDOR DROCERCA");
                        log::info("ftp         : " . $ftp);
                        log::info("ftpuser     : " . $ftpuser);
                        log::info("ftppass     : " . $ftppass);
                        log::info("ftppasv     : " . $ftppasv);
                        log::info("ftpruta     : " . $ruta);
                        $resp = iListDirectoryFtp($ftp, $ftpuser, $ftppass, $ftppasv, $ruta);
                        if ($resp == 0) {
                            $resp = TRUE;
                        }
                    } catch (\Exception $e) {
                        log::info("resp: " . $e);
                    }
                    break;
            }
    }
    return $resp;
}
function UpdateCondComercialCliente($codcli, $codprove)
{
    $maecliente = DB::table('maecliente')
        ->where('codcli', '=', $codcli)
        ->first();
    if ($maecliente) {
        $maeprove = LeerProve($codprove);
        if ($maeprove) {
            if ($maeprove->modoEnvioPedido == 'MYSQL') {
                $maeclieprove = LeerClieProve($codprove, $codcli);
                if ($maeclieprove) {
                    if ($maeclieprove->updCondComercial == 1) {
                        Config::set("database.connections.mysql2", [
                            "driver" => "mysql",
                            "host" => $maeprove->host,
                            "database" => $maeprove->basedato,
                            "username" => $maeprove->username,
                            "password" => $maeprove->password
                        ]);
                        Config::set('database.default', 'mysql2');
                        DB::reconnect('mysql2');
                        $cliente = DB::table('cliente')
                            ->where('codcli', '=', $maeclieprove->codigo)
                            ->first();
                        DB::purge('mysql2');
                        Config::set('database.default', 'mysql');
                        DB::reconnect('mysql');
                        if ($cliente) {
                            $resp = $cliente;
                            if ($resp) {
                                DB::table('maeclieprove')
                                    ->where('codprove', $codprove)
                                    ->where('codcli', $codcli)
                                    ->update(
                                        array(
                                            'dcme' => $resp->dcomercial,
                                            'dcredito' => $resp->dcredito
                                        )
                                    );
                                log::info("ACT. COND. COMERCIAL -> CODCLI: " . $codcli . " - CODPROV: " . $codprove);
                            }
                        }
                    }
                }
            }
        }
    }
    return;
}
function validarVence($fecvence, $mesNotVence)
{
    $hoy = date('Y-m-d');
    if (strlen($fecvence) == 7) {
        $fve = date('Y-m-d', strtotime($fecvence . '-01'));
    } else {
        $fve = date('Y-m-d', strtotime($fecvence));
    }
    $fechainicial = new DateTime($hoy);
    $fechafinal = new DateTime($fve);
    $diferencia = $fechainicial->diff($fechafinal);
    $meses = ($diferencia->y * 12) + $diferencia->m;
    try {
        if ($meses < $mesNotVence)
            return $fecvence;
    } catch (Exception $e) {
        return "";
    }
    return "";
}
function tofloat($num)
{
    $dotPos = strrpos($num, '.');
    $commaPos = strrpos($num, ',');
    $sep = (($dotPos > $commaPos) && $dotPos) ? $dotPos :
        ((($commaPos > $dotPos) && $commaPos) ? $commaPos : false);

    if (!$sep) {
        return floatval(preg_replace("/[^0-9]/", "", $num));
    }

    return floatval(
        preg_replace("/[^0-9]/", "", substr($num, 0, $sep)) . '.' .
        preg_replace("/[^0-9]/", "", substr($num, $sep + 1, strlen($num)))
    );
}
function LeerMaestra($barra, $campo)
{
    $resp = "";
    $tp = DB::table('tpmaestra')
        ->where('barra', '=', $barra)
        ->first();
    if (!empty($tp))
        $resp = $tp->$campo;
    return $resp;
}
function sPrecioCifrado($precio)
{
    $precio = str_replace("0", "#", $precio);
    $precio = str_replace("1", "#", $precio);
    $precio = str_replace("2", "#", $precio);
    $precio = str_replace("3", "#", $precio);
    $precio = str_replace("4", "#", $precio);
    $precio = str_replace("5", "#", $precio);
    $precio = str_replace("6", "#", $precio);
    $precio = str_replace("7", "#", $precio);
    $precio = str_replace("8", "#", $precio);
    $precio = str_replace("9", "#", $precio);
    return $precio;
}
function sCodprovCifrado($codprov)
{
    $cont = strlen($codprov) - 2;
    $resp = "TP";
    for ($i = 0; $i < $cont; $i++) {
        $resp .= '*';
    }
    return $resp;
}
function LineaTendenciaProd($codcli, $codprod)
{
    //log::info("CODCLI -> ".$codcli.' - CODPROD:'.$codprod);
    $tendprod = DB::table('tendencia')
        ->where('codcli', '=', $codcli)
        ->where('codprod', '=', $codprod)
        ->first();
    if (empty($tendprod)) {
        // NO TIENE DATA
        return "";
    }
    $sumvmd = 0;
    $entra = 0;
    $sem = "";
    $chart_data = '';
    $fecha = date('Ymd');
    $fecHoy = substr($fecha, 0, 8);
    $semana = date('W', strtotime($fecHoy));
    $contador = strval($semana);
    for ($i = 1; $i <= $contador; $i++) {
        switch ($i) {
            case '1':
                $vmd = $tendprod->sem_01;
                $sem = "SEM-01";
                break;
            case '2':
                $vmd = $tendprod->sem_02;
                $sem = "SEM-02";
                break;
            case '3':
                $vmd = $tendprod->sem_03;
                $sem = "SEM-03";
                break;
            case '4':
                $vmd = $tendprod->sem_04;
                $sem = "SEM-04";
                break;
            case '5':
                $vmd = $tendprod->sem_05;
                $sem = "SEM-05";
                break;
            case '6':
                $vmd = $tendprod->sem_06;
                $sem = "SEM-06";
                break;
            case '7':
                $vmd = $tendprod->sem_07;
                $sem = "SEM-07";
                break;
            case '8':
                $vmd = $tendprod->sem_08;
                $sem = "SEM-08";
                break;
            case '9':
                $vmd = $tendprod->sem_09;
                $sem = "SEM-09";
                break;
            case '10':
                $vmd = $tendprod->sem_10;
                $sem = "SEM-10";
                break;

            case '11':
                $vmd = $tendprod->sem_11;
                $sem = "SEM-11";
                break;
            case '12':
                $vmd = $tendprod->sem_12;
                $sem = "SEM-12";
                break;
            case '13':
                $vmd = $tendprod->sem_13;
                $sem = "SEM-13";
                break;
            case '14':
                $vmd = $tendprod->sem_14;
                $sem = "SEM-14";
                break;
            case '15':
                $vmd = $tendprod->sem_15;
                $sem = "SEM-15";
                break;
            case '16':
                $vmd = $tendprod->sem_16;
                $sem = "SEM-16";
                break;
            case '17':
                $vmd = $tendprod->sem_17;
                $sem = "SEM-17";
                break;
            case '18':
                $vmd = $tendprod->sem_18;
                $sem = "SEM-18";
                break;
            case '19':
                $vmd = $tendprod->sem_19;
                $sem = "SEM-19";
                break;
            case '20':
                $vmd = $tendprod->sem_20;
                $sem = "SEM-20";
                break;

            case '21':
                $vmd = $tendprod->sem_21;
                $sem = "SEM-21";
                break;
            case '22':
                $vmd = $tendprod->sem_22;
                $sem = "SEM-22";
                break;
            case '23':
                $vmd = $tendprod->sem_23;
                $sem = "SEM-23";
                break;
            case '24':
                $vmd = $tendprod->sem_24;
                $sem = "SEM-24";
                break;
            case '25':
                $vmd = $tendprod->sem_25;
                $sem = "SEM-25";
                break;
            case '26':
                $vmd = $tendprod->sem_26;
                $sem = "SEM-26";
                break;
            case '27':
                $vmd = $tendprod->sem_27;
                $sem = "SEM-27";
                break;
            case '28':
                $vmd = $tendprod->sem_28;
                $sem = "SEM-28";
                break;
            case '29':
                $vmd = $tendprod->sem_29;
                $sem = "SEM-29";
                break;
            case '30':
                $vmd = $tendprod->sem_30;
                $sem = "SEM-30";
                break;

            case '31':
                $vmd = $tendprod->sem_31;
                $sem = "SEM-31";
                break;
            case '32':
                $vmd = $tendprod->sem_32;
                $sem = "SEM-32";
                break;
            case '33':
                $vmd = $tendprod->sem_33;
                $sem = "SEM-33";
                break;
            case '34':
                $vmd = $tendprod->sem_34;
                $sem = "SEM-34";
                break;
            case '35':
                $vmd = $tendprod->sem_35;
                $sem = "SEM-35";
                break;
            case '36':
                $vmd = $tendprod->sem_36;
                $sem = "SEM-36";
                break;
            case '37':
                $vmd = $tendprod->sem_37;
                $sem = "SEM-37";
                break;
            case '38':
                $vmd = $tendprod->sem_38;
                $sem = "SEM-38";
                break;
            case '39':
                $vmd = $tendprod->sem_39;
                $sem = "SEM-39";
                break;
            case '40':
                $vmd = $tendprod->sem_40;
                $sem = "SEM-40";
                break;

            case '41':
                $vmd = $tendprod->sem_41;
                $sem = "SEM-41";
                break;
            case '42':
                $vmd = $tendprod->sem_42;
                $sem = "SEM-42";
                break;
            case '43':
                $vmd = $tendprod->sem_43;
                $sem = "SEM-43";
                break;
            case '44':
                $vmd = $tendprod->sem_44;
                $sem = "SEM-44";
                break;
            case '45':
                $vmd = $tendprod->sem_45;
                $sem = "SEM-45";
                break;
            case '46':
                $vmd = $tendprod->sem_46;
                $sem = "SEM-46";
                break;
            case '47':
                $vmd = $tendprod->sem_47;
                $sem = "SEM-47";
                break;
            case '48':
                $vmd = $tendprod->sem_48;
                $sem = "SEM-48";
                break;
            case '49':
                $vmd = $tendprod->sem_49;
                $sem = "SEM-49";
                break;
            case '50':
                $vmd = $tendprod->sem_50;
                $sem = "SEM-50";
                break;

            case '51':
                $vmd = $tendprod->sem_51;
                $sem = "SEM-51";
                break;
            case '52':
                $vmd = $tendprod->sem_52;
                $sem = "SEM-52";
                break;
        }
        $sumvmd = $sumvmd + $vmd;
        if ($sumvmd > 0 || $entra > 0) {
            $chart_data .= "{semana:'" . $sem . "',vmd:" . $vmd . "},";
            $entra = 1;
        }
    }
    return $chart_data = substr($chart_data, 0, -1);
}
function LineaTendenciaProdBorrar($codcli, $codprod)
{
    //log::info("CODCLI -> ".$codcli.' - CODPROD:'.$codprod);
    $tendprod = DB::table('tendprod')
        ->where('codcli', '=', $codcli)
        ->where('codprod', '=', $codprod)
        ->first();
    if (empty($tendprod)) {
        // NO TIENE DATA
        return "";
    }
    $dat = explode("|", $tendprod->data);
    array_pop($dat);
    $contador = count($dat);
    if ($contador <= 0) {
        // POCAS SEMANAS PARA DAR UNA TENDENCIA
        return "";
    }
    $chart_data = '';
    for ($i = 0; $i < $contador; $i++) {
        $arrayvmd = explode(";", $dat[$i]);
        $sem = $arrayvmd[0];
        $vmd = $arrayvmd[1];
        $chart_data .= "{semana:'" . $sem . "',vmd:" . $vmd . "},";
    }
    //log::info("DATA -> ".$chart_data);
    return $chart_data = substr($chart_data, 0, -1);
}
function LeerDocExportado($coddoc, $codcli, $tipo, $codprove)
{

    // dd($coddoc ."-". $codcli ."-". $tipo ."-". $codprove);
    $docexportado = DB::table('docexportado')
        ->where('coddoc', '=', $coddoc)
        ->where('codcli', '=', $codcli)
        ->where('tipo', '=', $tipo)
        ->where('codprove', '=', $codprove)
        ->first();
    if (empty($docexportado)) {
        // NO TIENE DATA
        return "";
    }
    return $docexportado;
}
function MostrarTendenciaProd($codcli, $codprod, $tolerancia)
{
    // NOCOLOR -> NO TIENE TENDECNIA
    // BLUE    -> UP
    // RED     -> DOWN
    // YELLOW  -> ESTABLE
    $tendprod = DB::table('tendencia')
        ->where('codcli', '=', $codcli)
        ->where('codprod', '=', $codprod)
        ->first();
    if (empty($tendprod)) {
        // NO TIENE DATA
        return "";
    }
    $sumavmd = 0;
    $array = array();
    $sem = "";
    $fecha = date('Ymd');
    $fecHoy = substr($fecha, 0, 8);
    $semana = date('W', strtotime($fecHoy));
    $contador = strval($semana);
    for ($i = 1; $i <= $contador; $i++) {
        switch ($i) {
            case '1':
                $vmd = $tendprod->sem_01;
                break;
            case '2':
                $vmd = $tendprod->sem_02;
                break;
            case '3':
                $vmd = $tendprod->sem_03;
                break;
            case '4':
                $vmd = $tendprod->sem_04;
                break;
            case '5':
                $vmd = $tendprod->sem_05;
                break;
            case '6':
                $vmd = $tendprod->sem_06;
                break;
            case '7':
                $vmd = $tendprod->sem_07;
                break;
            case '8':
                $vmd = $tendprod->sem_08;
                break;
            case '9':
                $vmd = $tendprod->sem_09;
                break;
            case '10':
                $vmd = $tendprod->sem_10;
                break;
            case '11':
                $vmd = $tendprod->sem_11;
                break;
            case '12':
                $vmd = $tendprod->sem_12;
                break;
            case '13':
                $vmd = $tendprod->sem_13;
                break;
            case '14':
                $vmd = $tendprod->sem_14;
                break;
            case '15':
                $vmd = $tendprod->sem_15;
                break;
            case '16':
                $vmd = $tendprod->sem_16;
                break;
            case '17':
                $vmd = $tendprod->sem_17;
                break;
            case '18':
                $vmd = $tendprod->sem_18;
                break;
            case '19':
                $vmd = $tendprod->sem_19;
                break;
            case '20':
                $vmd = $tendprod->sem_20;
                break;
            case '21':
                $vmd = $tendprod->sem_21;
                break;
            case '22':
                $vmd = $tendprod->sem_22;
                break;
            case '23':
                $vmd = $tendprod->sem_23;
                break;
            case '24':
                $vmd = $tendprod->sem_24;
                break;
            case '25':
                $vmd = $tendprod->sem_25;
                break;
            case '26':
                $vmd = $tendprod->sem_26;
                break;
            case '27':
                $vmd = $tendprod->sem_27;
                break;
            case '28':
                $vmd = $tendprod->sem_28;
                break;
            case '29':
                $vmd = $tendprod->sem_29;
                break;
            case '30':
                $vmd = $tendprod->sem_30;
                break;
            case '31':
                $vmd = $tendprod->sem_31;
                break;
            case '32':
                $vmd = $tendprod->sem_32;
                break;
            case '33':
                $vmd = $tendprod->sem_33;
                break;
            case '34':
                $vmd = $tendprod->sem_34;
                break;
            case '35':
                $vmd = $tendprod->sem_35;
                break;
            case '36':
                $vmd = $tendprod->sem_36;
                break;
            case '37':
                $vmd = $tendprod->sem_37;
                break;
            case '38':
                $vmd = $tendprod->sem_38;
                break;
            case '39':
                $vmd = $tendprod->sem_39;
                break;
            case '40':
                $vmd = $tendprod->sem_40;
                break;
            case '41':
                $vmd = $tendprod->sem_41;
                break;
            case '42':
                $vmd = $tendprod->sem_42;
                break;
            case '43':
                $vmd = $tendprod->sem_43;
                break;
            case '44':
                $vmd = $tendprod->sem_44;
                break;
            case '45':
                $vmd = $tendprod->sem_45;
                break;
            case '46':
                $vmd = $tendprod->sem_46;
                break;
            case '47':
                $vmd = $tendprod->sem_47;
                break;
            case '48':
                $vmd = $tendprod->sem_48;
                break;
            case '49':
                $vmd = $tendprod->sem_49;
                break;
            case '50':
                $vmd = $tendprod->sem_50;
                break;
            case '51':
                $vmd = $tendprod->sem_51;
                break;
            case '52':
                $vmd = $tendprod->sem_52;
                break;
        }
        $array[] = $vmd;
        $sumavmd += floatval($vmd);
    }
    if ($contador < 6 || $sumavmd <= 0) {
        // POCAS SEMANAS PARA DAR UNA TENDENCIA
        return "";
    }
    $sumavmd = 0;
    $vez = 0;
    $AntUltvmd = 0;
    for ($i = $contador - 6; $i < $contador - 1; $i++) {
        $vmd = $array[$i];
        if ($vez < 4) {
            $sumavmd = $sumavmd + floatval($vmd);
            $vez++;
        }
        $AntUltvmd = floatval($vmd);
    }
    $Ultvmd = floatval($array[$contador - 1]);
    $vmdprom = number_format(floatval($sumavmd) / 4, 4, '.', '');
    $vmdpromUp = $vmdprom + (($vmdprom * $tolerancia) / 100);
    $vmdpromDown = $vmdprom - (($vmdprom * $tolerancia) / 100);
    if ($Ultvmd > $vmdpromUp && $Ultvmd > $AntUltvmd) {
        // TENDECNIA ALTA
        return "fa fa-arrow-up text-blue";
    }
    if ($Ultvmd < $vmdpromDown && $Ultvmd < $AntUltvmd) {
        // TENDECNIA BAJA
        return "fa fa-arrow-down text-red";
    }
    if ($AntUltvmd > $vmdpromUp) {
        // TENDECNIA ALTA
        return "fa fa-arrow-up text-blue";
    }
    if ($AntUltvmd < $vmdpromDown) {
        // TENDECNIA BAJA
        return "fa fa-arrow-down text-red";
    }
    // TENDENCIA ESTABLE
    return "fa fa-circle text-yellow";
}
function analisisSobreStock($dias, $bajo, $alto)
{
    if ($bajo == 0 && $alto == 0) {
        $restorno = [
            'color' => 'black',
            'title' => ''
        ];
        return $restorno;
    }
    if ($dias <= $bajo) {
        $restorno = [
            'color' => 'red',
            'title' => 'DIAS DE INVENTARIO BAJO'
        ];
    } else {
        if ($dias > $bajo && $dias <= $alto) {
            $restorno = [
                'color' => 'green',
                'title' => 'DIAS DE INVENTARIO OPTIMO'
            ];
        } else {
            if ($dias > $alto) {
                $restorno = [
                    'color' => '#CCBB00',
                    'title' => 'DIAS DE INVENTARIO ALTO'
                ];
            }
        }
    }
    return $restorno;
}
function obtenerDataTpmaestra($cat, $provs, $modo)
{
    // modo = 0 => NORMAL
    // modo = 1 => MOLECULA
    $invConsol = 0;
    $arrayRnk = [];
    $mpp = 100000000000000;
    $mppliq = 100000000000000;
    $mppliqmol = 100000000000000;
    $mayorInv = 0;
    $tprnk1 = "";
    $contprov = 0;
    $moneda = Session::get('moneda', 'BSS');
    foreach ($provs as $prov) {
        $codprove = strtolower($prov->codprove);
        $factor = RetornaFactorCambiario($codprove, $moneda);
        if (!VerificaCampoTabla('tpmaestra', $prov->codprove))
            continue;
        try {
            $campos = $cat->$codprove;
            $campo = explode("|", $campos);
        } catch (Exception $e) {
            continue;
        }

        $unidadmolecula = 1;
        $prodcaract = DB::table('prodcaract')
            ->where('barra', '=', $cat->barra)
            ->first();
        if ($prodcaract)
            $unidadmolecula = $prodcaract->unidadmolecula;

        $precio = $campo[0];
        $precioBs = $campo[0];
        $cantidad = $campo[1];
        $codprod = $campo[3];
        $fechafalla = $campo[4];
        $lote = $campo[7];
        $fecvence = $campo[8];

        $actualizado = date('d-m-Y H:i', strtotime($prov->fechasinc));
        $confprov = LeerProve($codprove);
        $tipoprecio = $prov->tipoprecio;
        switch ($tipoprecio) {
            case 1:
                $precio = $campo[0] / $factor;
                $precioBs = $campo[0];
                break;
            case 2:
                $precio = $campo[5] / $factor;
                $precioBs = $campo[5];
                break;
            case 3:
                $precio = $campo[6] / $factor;
                $precioBs = $campo[6];
                break;
            default:
                $precio = $campo[0] / $factor;
                $precioBs = $campo[0];
                break;
        }
        $invConsol = $invConsol + $cantidad;
        $dc = $prov->dcme;
        $di = $prov->di;
        $pp = $prov->ppme;
        $da = 0.00;
        $dpe = 0.00;
        $upe = 0;
        $dcredito = 0;
        if ($tipoprecio == $confprov->aplicarDaPrecio) {
            $da = $campo[2];
            if (isset($campo[10])) {
                $dpe = $campo[10];
            }
            if (isset($campo[11])) {
                $upe = $campo[11];
            }
            if (isset($campo[12])) {
                $dcredito = $campo[12];
            }
            //log::info("INV -> aplicarDaPrecio = ".$confprov->aplicarDaPrecio);
        }
        $netoBs = CalculaPrecioNeto($precioBs, $da, $di, $dc, $pp, 0.00);
        $liquidaBs = $netoBs + (($netoBs * $cat->iva) / 100);

        $neto = CalculaPrecioNeto($precio, $da, $di, $dc, $pp, 0.00);
        $liquida = $neto + (($neto * $cat->iva) / 100);
        $liqmolecula = $liquida / $unidadmolecula;
        if ($cantidad > 0 && ($liquida > 0 || $liqmolecula > 0)) {
            $contprov++;
            if ($modo == 0) {
                $arrayRnk[] = [
                    'liquida' => $liquida,
                    'codprove' => $prov->codprove,
                    'liquidaBs' => $liquidaBs
                ];
                if ($liquida < $mpp) {
                    $mpp = $liquida;
                    $tprnk1 = $prov->codprove;
                }
            } else {
                $arrayRnk[] = [
                    'liquida' => $liqmolecula,
                    'codprove' => $prov->codprove,
                    'liquidaBs' => $liquidaBs
                ];
                if ($liqmolecula < $mpp) {
                    $mpp = $liqmolecula;
                    $tprnk1 = $prov->codprove;
                }
            }
            if ($liquida < $mppliq) {
                $mppliq = $liquida;
            }
            if ($liqmolecula < $mppliqmol) {
                $mppliqmol = $liqmolecula;
            }
            if ($cantidad > $mayorInv) {
                $mayorInv = $cantidad;
            }
        }
        $arrayProv[] = [
            'codprove' => $prov->codprove,
            'liquida' => $liquida,
            'liqmolecula' => $liqmolecula,
            'precio' => $precio,
            'cantidad' => $cantidad,
            'tipoprecio' => $tipoprecio,
            'dc' => $dc,
            'di' => $di,
            'pp' => $pp,
            'da' => $da,
            'codprod' => $codprod,
            'fechafalla' => $fechafalla,
            'lote' => $lote,
            'fecvence' => $fecvence,
            'actualizado' => $actualizado,
            'confprov' => $confprov,
            'dpe' => $dpe,
            'upe' => $upe,
            'dcredito' => $dcredito
        ];
    }
    if ($contprov == 0)
        return null;
    $aux = array();
    foreach ($arrayRnk as $key => $row) {
        $aux[$key] = $row['liquida'];
    }
    if (count($aux) > 1)
        array_multisort($aux, SORT_ASC, $arrayRnk);
    $arrayRetorno = [
        'mayorInv' => $mayorInv,
        'mpp' => $mpp,
        'mppliq' => $mppliq,
        'mppliqmol' => $mppliqmol,
        'invConsol' => $invConsol,
        'tprnk1' => $tprnk1,
        'arrayRnk' => $arrayRnk,
        'arrayProv' => $arrayProv,
        'unidadmolecula' => $unidadmolecula
    ];
    return $arrayRetorno;
}
function verificarDataProd($cat, $provs)
{
    $retorno = 0;
    foreach ($provs as $prov) {
        $codprove = strtolower($prov->codprove);
        if (!VerificaCampoTabla('tpmaestra', $prov->codprove))
            continue;
        try {
            $campos = $cat->$codprove;
            $campo = explode("|", $campos);
        } catch (Exception $e) {
            continue;
        }
        $precio = $campo[0];
        $cantidad = $campo[1];
        $tipoprecio = $prov->tipoprecio;
        switch ($tipoprecio) {
            case 1:
                $precio = $campo[0];
                break;
            case 2:
                $precio = $campo[5];
                break;
            case 3:
                $precio = $campo[6];
                break;
            default:
                $precio = $campo[0];
                break;
        }
        if ($cantidad > 0 && $precio > 0) {
            $retorno = 1;
            break;
        }
    }
    //log::info("INV -> ** retorno=".$retorno);
    return $retorno;
}
function obtenerColorProd($cat, $cliente, $provs)
{
    $moneda = Session::get('moneda', 'BSS');
    $costo = number_format($cat->costo, 2, '.', '');
    $costo2 = number_format($cat->oferta, 2, '.', '');
    //log::info("INV -> ** COSTO=".$costo);
    $precioInv = 'precio' . $cliente->usaprecio;
    $precioInv = $cat->$precioInv;
    $precioInv = $precioInv;
    $precioInv = number_format(CalculaPrecioNeto($precioInv, 0.00, $cliente->di, $cliente->dc, $cliente->pp, 0.00), 2, '.', '');
    $precioSug = number_format($precioInv - (($precioInv * $cat->da) / 100), 6, '.', '');
    //log::info("INV -> ** PRECIOSUG=".$precioSug);
    if ($precioInv <= 0)
        $util = 0;
    else
        $util = number_format((-1) * ((($costo / ($precioInv - ($precioInv * $cat->da / 100))) * 100) - 100), 2, '.', '');
    $entra = FALSE;
    $mpc = 100000000000000;
    $mpcFactor = 100000000000000;
    $da = 0;
    $tpselect = '';
    $mayorInv = 0;
    $invConsol = 0;
    foreach ($provs as $prov) {
        $codprove = strtolower($prov->codprove);
        $factor = RetornaFactorCambiario($codprove, $moneda);
        $campos = $cat->$codprove;
        $campo = explode("|", $campos);
        $precio = $campo[0];
        $cantidad = $campo[1];
        if ($cantidad > 0) {
            $invConsol = $invConsol + $cantidad;
            $tipoprecio = $prov->tipoprecio;
            switch ($tipoprecio) {
                case 1:
                    $precio = $campo[0];
                    break;
                case 2:
                    $precio = $campo[5];
                    break;
                case 3:
                    $precio = $campo[6];
                    break;
                default:
                    $precio = $campo[0];
                    break;
            }
            $entra = TRUE;
            $dc = $prov->dcme;
            $di = $prov->di;
            $pp = $prov->ppme;
            $da = $campo[2];
            $neto = CalculaPrecioNeto($precio, $da, $di, $dc, $pp, 0.00);
            $liquida = $neto + (($neto * $cat->iva) / 100);
            $arrayRnk[] = [
                'liquida' => $liquida,
                'codprove' => $prov->codprove
            ];
            if ($liquida < $mpc) {
                $mpc = $liquida;
                $mpcFactor = $liquida / $factor;
                $tpselect = $prov->codprove;
            }
            if ($cantidad > $mayorInv) {
                $mayorInv = $cantidad;
            }
        }
    }
    $colorprod = "";
    $backcolor = '';
    $forecolor = '';
    $title = "";
    if ($entra == FALSE) {
        $arrayRnk[] = [
            'liquida' => 0,
            'codprove' => ''
        ];
    }
    $aux = array();
    foreach ($arrayRnk as $key => $row) {
        $aux[$key] = $row['liquida'];
    }
    if (count($aux) > 1)
        array_multisort($aux, SORT_ASC, $arrayRnk);
    if (floatval($costo) > floatval($mpc)) {
        // ROJO
        $colorprod = "R";
        $backcolor = '#FF0000';
        $forecolor = '#FFFFFF';
        $title = "EL COSTO ES MAYOR AL MEJOR PRECIO DE LA COMPETENCIA";
        $da = number_format(algoritmoOferta($costo, $precioInv, $mpc, $cliente->damin, $cliente->damax, $cliente->utilm, $cat->da, 1), 2, '.', '');
        if (floatval($da) > 0) {
            $precioSug = number_format($precioInv - (($precioInv * $da) / 100), 6, '.', '');
            $util = number_format((-1) * ((($costo / ($precioInv - ($precioInv * $da / 100))) * 100) - 100), 2, '.', '');
        }
    } else {
        if (floatval($precioSug) < floatval($mpc)) {
            // VERDE
            $colorprod = "V";
            $backcolor = '#007F0E';
            $forecolor = '#FFFFFF';
            $title = "SU PRECIO ES MEJOR AL DE LA COMPETENCIA";
            $da = number_format(algoritmoOferta($costo, $precioInv, $mpc, $cliente->damin, $cliente->damax, $cliente->utilm, $cat->da, 1), 2, '.', '');
            $precioSug = number_format($precioInv - (($precioInv * $da) / 100), 6, '.', '');
            //log::info("da: ".$da);
            //log::info("precioInv: ".$precioInv);
            //log::info("costo: ".$costo);
            //log::info("precioSug: ".$precioSug);
            if ($precioInv <= 0)
                $util = 0;
            else
                $util = number_format((-1) * ((($costo / ($precioInv - ($precioInv * $da / 100))) * 100) - 100), 2, '.', '');
        } else {
            // AMARILLO
            if (floatval($precioSug) > floatval($mpc) && floatval($mpc) > floatval($costo)) {
                $da = number_format(algoritmoOferta($costo, $precioInv, $mpc, $cliente->damin, $cliente->damax, $cliente->utilm, $cat->da, 1), 2, '.', '');

                $precioSug = number_format($precioInv - (($precioInv * $da) / 100), 2, '.', '');
                $util = number_format((-1) * ((($costo / ($precioInv - ($precioInv * $da / 100))) * 100) - 100), 2, '.', '');

                $colorprod = "A";
                $backcolor = '#FFD800';
                $forecolor = '#000000';
                $title = "SU PRECIO ES MAYOR AL DE LA COMPETENCIA";
            }
        }
    }
    return [
        'colorprod' => $colorprod,
        'costo' => floatval($costo),
        'costo2' => floatval($costo2),
        'precioInv' => floatval($precioInv),
        'precioSug' => floatval($precioSug),
        'da' => floatval($da),
        'util' => floatval($util),
        "mpc" => floatval($mpc),
        "backcolor" => $backcolor,
        "forecolor" => $forecolor,
        "title" => $title,
        "invConsol" => $invConsol,
        "tpselect" => $tpselect,
        "arrayRnk" => $arrayRnk,
        "mayorInv" => $mayorInv,
        "mpcFactor" => $mpcFactor
    ];
}
function algoritmoOferta($costo, $precioInv, $mpc, $damin, $damax, $utilm, $daInv)
{
    $retorno = -1;
    if ($precioInv <= 0 || $costo <= 0)
        return $retorno;
    $damin = floatval(number_format($damin, 2, '.', ''));
    $damax = floatval(number_format($damax, 2, '.', ''));
    $utilm = floatval(number_format($utilm, 2, '.', ''));
    $daInv = floatval(number_format($daInv, 2, '.', ''));
    $costo = floatval(number_format($costo, 2, '.', ''));
    $precioInv = floatval(number_format($precioInv, 2, '.', ''));
    $mpc = floatval(number_format($mpc, 2, '.', ''));
    $precioSug = floatval(number_format($precioInv - (($precioInv * $daInv) / 100), 2, '.', ''));
    if ($precioSug < $mpc) {
        // VERDE
        $entra = 0;
        for ($da = $daInv; $da >= 0; $da--) {
            $putil = floatval(number_format((-1) * ((($costo / ($precioInv - ($precioInv * $da / 100))) * 100) - 100), 2, '.', ''));
            $ps = floatval(number_format($costo / ABS(($putil - 100) / 100), 2, '.', ''));
            $retorno = $da;
            if ($ps >= $mpc) {
                $retorno = $da + 1;
                $entra = 1;
                break;
            }
        }
        if ($entra == 0)
            $retorno = 0;
    } else {
        if ($costo > $mpc) {
            // ROJO
            $dax = -1;
            for ($da = $damin; $da <= $damax; $da++) {
                $putil = floatval(number_format((-1) * ((($costo / ($precioInv - ($precioInv * $da / 100))) * 100) - 100), 2, '.', ''));
                $ps = floatval(number_format($costo / ABS(($putil - 100) / 100), 2, '.', ''));
                if ($putil < $utilm) {
                    if ($da > 0)
                        $retorno = $da - 1;
                    break;
                }
                $dax = $da;
                ;
            }
            if ($retorno < 0)
                $retorno = $dax;

        } else {
            // AMARILLO
            $dax = -1;
            for ($da = $damin; $da <= $damax; $da++) {
                $putil = floatval(number_format((-1) * ((($costo / ($precioInv - ($precioInv * $da / 100))) * 100) - 100), 2, '.', ''));
                $ps = floatval(number_format($costo / ABS(($putil - 100) / 100), 2, '.', ''));
                if ($ps < $mpc || $putil < $utilm) {
                    $retorno = $da;
                    if ($putil < $utilm)
                        $retorno = $da - 1;
                    break;
                }
                $dax = $da;
            }
            if ($retorno < 0)
                $retorno = $dax;
        }
    }
    return $retorno;
}
function obtenerCodprovRnk1($barra, $provs)
{
    $menor = 100000000000000;
    $tprnk1 = "TPNODEF";
    $tpmaestra = DB::table('tpmaestra')
        ->where('barra', '=', $barra)
        ->first();
    if (empty($tpmaestra))
        return $tprnk1;
    foreach ($provs as $prov) {
        $codprove = strtolower($prov->codprove);
        if (!VerificaCampoTabla('tpmaestra', $prov->codprove))
            continue;
        try {
            $campos = $tpmaestra->$codprove;
            $campo = explode("|", $campos);
        } catch (Exception $e) {
            continue;
        }
        $precio = $campo[0];
        $cantidad = $campo[1];
        $tipoprecio = $prov->tipoprecio;
        switch ($tipoprecio) {
            case 1:
                $precio = $campo[0];
                break;
            case 2:
                $precio = $campo[5];
                break;
            case 3:
                $precio = $campo[6];
                break;
            default:
                $precio = $campo[0];
                break;
        }
        if ($cantidad > 0) {
            $dc = $prov->dcme;
            $di = $prov->di;
            $pp = $prov->ppme;
            $da = $campo[2];
            $neto = CalculaPrecioNeto($precio, $da, $di, $dc, $pp, 0.00);
            $liquida = $neto + (($neto * $tpmaestra->iva) / 100);
            if ($liquida < $menor) {
                $menor = $liquida;
                $tprnk1 = $prov->codprove;
            }
        }
    }
    return $tprnk1;
}
function ContadorVisitas()
{
    // CONFIGURACION
    $cfg = DB::table('maecfg')->first();
    $cntvisitas = $cfg->cntvisitas + 1;
    DB::table('maecfg')
        ->update(
            array(
                'cntvisitas' => $cntvisitas,
                'ultVisita' => date('Y-m-j H:i:s')
            )
        );
    return $cntvisitas;
}
function BuscarMejorPrecio($barra, $provs)
{
    set_time_limit(500);
    $contren = 0;
    $tpmaestra = DB::table('tpmaestra')
        ->where('barra', '=', $barra)
        ->first();
    if (!empty($tpmaestra)) {
        for ($i = 0; $i < count($provs); $i++) {
            $codprove = strtolower($provs[$i]);
            if ($i == 0)
                $codprove1 = $codprove;
            if (!VerificaCampoTabla('tpmaestra', $codprove))
                continue;
            $data = $tpmaestra->$codprove;
            $campo = explode("|", $data);
            $cantidad = floatval($campo[1]);
            $da = floatval($campo[2]);
            $codprod = $campo[3];
            $fechafalla = $campo[4];
            $lote = $campo[7];
            $fecvence = $campo[8];
            $desprod = $campo[9];
            $precio = floatval($campo[0]);
            if ($cantidad <= 0 && $precio <= 0.00 && $i <= 0) {
                // EN EL CASO DE QUE EL PROVEEDOR Q SE ESTA COMPARANDO NO TIENE EL PRODUCTO
                // DEVUELVE NULL
                return null;
            }
            if ($cantidad > 0 && $precio > 0.00) {
                $contren++;
                // PRECIO1 + CANTIDAD + DA + CODIGO + FECHAFALLA + PRECIO2 + PRECIO3 + LOTE +FECVENCE + DESPROD

                $dc = 0.00;
                $di = 0.00;
                $pp = 0.00;
                $neto = CalculaPrecioNeto($precio, $da, $di, $dc, $pp, 0.00);
                $liquida = $neto + (($neto * $tpmaestra->iva) / 100);

                $arrayProv[] = array(
                    "precio" => $precio,
                    "cantidad" => $cantidad,
                    "da" => $da,
                    "codprod" => $codprod,
                    "fechafalla" => $fechafalla,
                    "lote" => $lote,
                    "fecvence" => $fecvence,
                    "desprod" => $desprod,
                    "codprove" => $codprove,
                    "liquida" => $liquida
                );

            }
        }
        if ($contren == 0) {
            return null;
        }
        $contProv = 0;
        foreach ($arrayProv as $key => $row) {
            $aux[$key] = $row['liquida'];
            if ($aux[$key] > 0) {
                $contProv++;
            }
        }
        if ($contProv == 0) {
            // NO HAY UN SOLO PROVEEDOR CON EL PRODUCTO
            return null;
        }

        array_multisort($aux, SORT_ASC, $arrayProv);
        if ($codprove1 == $arrayProv[0]["codprove"] && $contProv == 1) {
            // SOLO LO TIENE EL PROVEEDOR PRINCIPAL
            return null;
        }
        if ($codprove1 != $arrayProv[0]["codprove"]) {
            // NO ES UNA SUPER OFERTA
            //dd('barra: '.$barra.' = '.$codprove1 .' - '. $arrayProv[0]["codprove"] );
            return null;
        }
        return $arrayProv;
    }
    return null;
}
function RetornaFactorCambiario($codprove, $moneda)
{
    if ($moneda == "BSS")
        return 1.00;
    $cfg = DB::table('maecfg')->first();
    if ($codprove == "") {
        if ($moneda == "EUR") {
            $factor = $cfg->factorBcvEUR;
            if ($factor <= 0)
                $factor = 1.00;
            return $factor;
        } else {
            $factor = $cfg->factorBcvUSD;
            if ($factor <= 0)
                $factor = 1.00;
            return $factor;
        }
    }
    if ($moneda == "EUR") {
        $factor = $cfg->factorBcvEUR;
        if ($factor <= 0)
            $factor = 1.00;
        return $factor;
    }
    if (Auth::user()->tipo == 'O') {
        // GESTOR DE OFERTAS, BUSCAR FACTOR EN MAEPROVE CAMPO CLAVE CODISB
        $maeprove = DB::table('maeprove')
            ->where('codisb', '=', $codprove)
            ->first();
        if ($maeprove) {
            $factor = $maeprove->FactorCambiario;
        } else {
            $factor = $cfg->factorBcvUSD;
        }
        if ($factor <= 0)
            $factor = 1.00;
        return $factor;
    } else {
        $codprove = mb_strtoupper($codprove);
        $factor = 1.00;
        $factorPRE = 1.00;
        $factorBCV = 1.00;
        $factorTODAY = 1.00;
        $maeprove = DB::table('maeprove')
            ->where('codprove', '=', $codprove)
            ->first();
        if ($maeprove) {
            //log::info("CODPROVE: ".$codprove." MONEDA: ".$moneda. " FACTORMODO: ".$maeprove->factorModo." FACTOR: ".$maeprove->FactorCambiario.' FACTORSELECCION: '.$maeprove->factorSeleccion);
            if ($maeprove->factorModo == "PREDETERMINADO" || $maeprove->factorSeleccion == "MANUAL") {
                $factor = $maeprove->FactorCambiario;
                $factorPRE = $factor;
            }
            if ($factor <= 1.00) {
                if ($maeprove->factorSeleccion == "BCV") {
                    $factor = $cfg->factorBcvUSD;
                    $factorBCV = $factor;
                }
                if ($maeprove->factorSeleccion == "TODAY") {
                    $factor = $cfg->factorToday;
                    $factorTODAY = $factor;
                }
            }
            if ($factor <= 1.00) {
                if ($factorBCV != 0.00 && $factorBCV != 1.00)
                    $factor = $factorBCV;
            }
            if ($factor <= 1.00) {
                if ($factorTODAY != 0.00 && $factorTODAY != 1.00)
                    $factor = $factorTODAY;
            }
            return $factor;
        } else {
            $factor = $cfg->factorBcvUSD;
            if ($factor <= 0)
                $factor = 1.00;
            return $factor;
        }
    }
    return 1.00;
}
function QuitarCaracteres($str)
{
    $retorno = str_replace("\xB0", "", $str);
    $retorno = str_replace("\xD1", "", $retorno);
    $retorno = str_replace("\xD1", "", $retorno);
    $retorno = str_replace("\xC9", "", $retorno);
    $retorno = str_replace("\\", "", $retorno);
    $retorno = str_replace("\xD1", "", $retorno);
    $retorno = str_replace("\n", " ", $retorno);
    $retorno = str_replace("\t", " ", $retorno);
    $retorno = str_replace('Ñ', 'N', $retorno);
    $retorno = str_replace('ñ', 'N', $retorno);
    $retorno = str_replace('é', 'e', $retorno);
    $retorno = str_replace('á', 'a', $retorno);
    $retorno = str_replace('í', 'i', $retorno);
    $retorno = str_replace('ó', 'o', $retorno);
    $retorno = str_replace('ú', 'u', $retorno);
    $retorno = str_replace('�', '', $retorno);
    $retorno = str_replace('\xD', '', $retorno);
    $retorno = str_replace('CPEÑ', '', $retorno);
    $retorno = str_replace('CPE�', '', $retorno);
    $retorno = str_replace('CPE', '', $retorno);
    $retorno = str_replace('� ', '', $retorno);
    return $retorno;
}
function bEnviaCorreo($asunto, $remite, $destino, $contenido)
{
    $retorno = FALSE;
    try {
        if (
            strlen($asunto) == 0 ||
            strlen($remite) == 0 ||
            strlen($destino) == 0 ||
            strlen($contenido) == 0
        ) {
            return FALSE;
        }
        $fechaHoy = date('j-m-Y');
        $FechaVenta = substr($fechaHoy, 0, 10);
        $cfg = DB::table('maecfg')->first();

        // FORMULARIO DEL CORREO
        $subject = $asunto;
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8\r\n";
        $headers .= "Content-Transfer-Encoding: 8bit\r\n";
        $headers .= "X-Priority: 1\r\n";
        $headers .= "X-MSMail-Priority: High\r\n";
        $headers .= "From: <" . $remite . ">\r\n";
        $headers .= "Reply-To: <" . $destino . ">\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
        $headers .= "X-originating-IP: \r\n";
        // ENCABEZADO
        $message = "
        <html>
        <head>
        <title>HTML</title>
        </head>
        <body>
        <center><h2>SOPORTE TECNICO</h2></center>
        <center><h2>" . $asunto . "</h2></center>";

        $message .= "<div><br>";

        $message .= "<center> FECHA: " . $fechaHoy . "</center>";
        $message .= "<span>" . $contenido . "</span>";
        $message .= "</div><br>";

        // PIE DEL FORMULARIO
        $message .= "<h4>
            <center>
                " . $cfg->nombre . " | RIF: " . $cfg->rif . "
            </center>
        </h4>";

        $message .= "<h5>
            <center>
                " . $cfg->direccion . "
            </center>
        </h5>";

        $message .= "<h5>
            <center>
                TELEFONO: " . $cfg->telefono . " CONTACTO: " . $cfg->contacto . "
            </center>
        </h5>
        </body>
        </html>";
        if (mail($destino, $subject, $message, $headers)) {
            log::info("SOPORTE TECNICO CORREO (OK): " . $remite . " ASUNTO: " . $subject);
            $retorno = TRUE;
        }
    } catch (Exception $e) {
        log::info("SOPORTE TECNICO CORREO (ERROR): " . $remite . " ASUNTO: " . $subject . '\n' . $e);
    }
    return $retorno;
}
function BuscarMejorOpcion($barra, $criterio, $preferencia, $pedir, $provs)
{
    set_time_limit(1000);
    $contren = 0;
    $tpmaestra = DB::table('tpmaestra')
        ->where('barra', '=', $barra)
        ->first();
    if ($tpmaestra) {

        $arrayRnk = [];
        $dataprod = obtenerDataTpmaestra($tpmaestra, $provs, 0);
        if (!is_null($dataprod)) {
            $arrayRnk = $dataprod['arrayRnk'];
        }

        foreach ($provs as $prov) {
            $codprove = strtolower($prov->codprove);
            if (!VerificaCampoTabla('tpmaestra', $codprove))
                continue;

            $aplicarDaPrecio = 1;
            $maeprove = LeerProve($codprove);
            if ($maeprove) {
                $aplicarDaPrecio = $maeprove->aplicarDaPrecio;
            }

            $pref = $prov->preferencia;
            $data = $tpmaestra->$codprove;
            $campo = explode("|", $data);
            $cantidad = floatval($campo[1]);
            $da = 0.00;
            if ($prov->tipoprecio == $aplicarDaPrecio) {
                $da = floatval($campo[2]);
            }
            $codprod = $campo[3];
            $fechafalla = $campo[4];
            $lote = $campo[7];
            $fecvence = $campo[8];
            $desprod = $campo[9];
            switch ($prov->tipoprecio) {
                case 1:
                    $precio = floatval($campo[0]);
                    break;
                case 2:
                    $precio = floatval($campo[5]);
                    break;
                case 3:
                    $precio = floatval($campo[6]);
                    break;
                default:
                    $precio = floatval($campo[0]);
                    break;
            }
            if ($cantidad > 0 && $precio > 0.00) {
                $contren++;

                // PRECIO1 + CANTIDAD + DA + CODIGO + FECHAFALLA + PRECIO2 + PRECIO3 + LOTE +FECVENCE + DESPROD

                $dc = $prov->dcme;
                $di = $prov->di;
                $pp = $prov->ppme;
                $neto = CalculaPrecioNeto($precio, $da, $di, $dc, $pp, 0.00);
                $liquida = $neto + (($neto * $tpmaestra->iva) / 100);
                $ranking = obtenerRanking($liquida, $arrayRnk);
                if (empty($ranking)) {
                    $ranking = "1-" . count($arrayRnk);
                }

                //if ($barra == '7590027002079') {
                //log::info($arrayRnk);
                //log::info($ranking);
                //log::info("MEJOR-> BARRA: ".$barra.' - PROVE: '.$prov->codprove.' - LIQUIDA: '.$liquida. " PREFERENCIA: ".$preferencia." RNK: ".$ranking." DA: ".$da);
                //}

                $arrayProv[] = array(
                    "precio" => $precio,
                    "cantidad" => $cantidad,
                    "da" => $da,
                    "codprod" => $codprod,
                    "fechafalla" => $fechafalla,
                    "lote" => $lote,
                    "fecvence" => $fecvence,
                    "desprod" => $desprod,
                    "codprove" => $prov->codprove,
                    "dias" => $prov->dcredito,
                    "liquida" => $liquida,
                    "ranking" => $ranking,
                    "preferencia" => $pref
                );
            }
        }

        if ($contren == 0) {
            return null;
        }
        $conpreferencia = 0;
        if ($preferencia == "PRIMER") {
            foreach ($arrayProv as $key => $row) {
                $aux[$key] = $row['preferencia'];
                if ($aux[$key] == "01") {
                    $conpreferencia = 1;
                    break;
                }
            }
            if ($conpreferencia == 1) {
                $vez = 0;
                switch ($criterio) {
                    case 'PRECIO':
                        foreach ($arrayProv as $key => $row) {
                            if ($vez == 0) {
                                $vez = 1;
                                continue;
                            }
                            $aux[$key] = $row['liquida'];
                        }
                        array_multisort($aux, SORT_ASC, $arrayProv);
                        break;
                    case 'INVENTARIO':
                        foreach ($arrayProv as $key => $row) {
                            if ($vez == 0) {
                                $vez = 1;
                                continue;
                            }
                            $aux[$key] = $row['cantidad'];
                        }
                        array_multisort($aux, SORT_DESC, $arrayProv);
                        break;
                    case 'DIAS':
                        foreach ($arrayProv as $key => $row) {
                            if ($vez == 0) {
                                $vez = 1;
                                continue;
                            }
                            $aux[$key] = $row['dias'];
                        }
                        array_multisort($aux, SORT_DESC, $arrayProv);
                        break;
                }
            }

        }
        if ($conpreferencia == 0) {
            switch ($criterio) {
                case 'PRECIO':
                    foreach ($arrayProv as $key => $row) {
                        $aux[$key] = $row['liquida'];
                    }
                    array_multisort($aux, SORT_ASC, $arrayProv);
                    break;
                case 'INVENTARIO':
                    foreach ($arrayProv as $key => $row) {
                        $aux[$key] = $row['cantidad'];
                    }
                    array_multisort($aux, SORT_DESC, $arrayProv);
                    break;
                case 'DIAS':
                    foreach ($arrayProv as $key => $row) {
                        $aux[$key] = $row['dias'];
                    }
                    array_multisort($aux, SORT_DESC, $arrayProv);
                    break;
            }
        }

        //if ($barra == '7591585279057') {
        //    log::info($arrayProv);
        //}
        return $arrayProv;
    }
    return null;
}
function bConvCatalogoProvCatalogoISB($rutacatalogo, $codprove, $formato)
{
    $BASE_PATH = env('INTRANET_RUTA_PUBLIC', base_path());
    log::info("CCP-> CARGA CATALOGO PROVEEDOR (INICIO): " . $codprove);
    $lines = file($rutacatalogo);
    if (count($lines) <= 0) {
        log::info("CCP-> CATALOGO VACIO " . $rutacatalogo);
        DB::table('maeprove')
            ->where('codprove', '=', $codprove)
            ->update(array("cargarLog" => "CCP-> CATALOGO VACIO " . $rutacatalogo));
        return FALSE;
    }
    $formato = "0";
    $extension = pathinfo($rutacatalogo, PATHINFO_EXTENSION);
    if ($extension == 'txt')
        $formato = "2";
    if ($extension == 'csv')
        $formato = "1";

    if ($formato == "0") {
        log::info("CCP-> EXTENSION NO COMPATIBLE: " . $rutacatalogo);
        DB::table('maeprove')
            ->where('codprove', '=', $codprove)
            ->update(array("cargarLog" => "CCP-> EXTENSION NO COMPATIBLE: " . $rutacatalogo));
        return FALSE;
    }
    log::info("CCP-> RUTA: " . $rutacatalogo . ' EXT: ' . $extension . ' FORMATO: ' . $formato);

    DB::table('maeprove')
        ->where('codprove', '=', $codprove)
        ->update(array("cargarLog" => "OK, " . date('Y-m-j H:i:s')));

    $maeprove = LeerProve($codprove);
    if (is_null($maeprove))
        return FALSE;
    $tabla = strtolower($codprove);
    $codisb = $maeprove->codisb;
    $codsede = $maeprove->codsede;
    $rutaarc = $maeprove->localfile;
    $factor = fGetfloat($maeprove->FactorCambiario);
    $error = 0;
    $insertado = 0;
    DB::table($tabla)->delete();
    if ($formato == '1') {
        // FORMATO EXCEL (CSV)
        $linea = 0;
        $primer = 0;
        $separador = ';';
        foreach ($lines as $line) {
            try {
                $linea++;
                if ($linea < $maeprove->LineaInicio)
                    continue;
                $line = trim(QuitarCaracteres($line));
                if (substr($line, -1) != $separador)
                    $line = $line . $separador;
                $s1 = explode($separador, $line);
                $barra = $s1[ord($maeprove->ColRef) - 65];
                $barra = QuitarCaracteres($barra);
                if (empty($barra))
                    continue;

                $codprod = trim($s1[ord($maeprove->ColCodprod) - 65]);
                $codprod = QuitarCaracteres($codprod);
                if (empty($codprod))
                    $codprod = $barra;

                if (strlen($barra) > 20 || strlen($codprod) > 20)
                    continue;

                $preciox = $s1[ord($maeprove->ColPrecio) - 65];
                if (empty($preciox))
                    continue;

                $preciox = fGetfloat($preciox);
                if ($maeprove->CodMoneda == "OM") {
                    $preciox = $preciox * $factor;
                }

                if (empty(trim($maeprove->ColIva)))
                    $ivax = '0.00';
                else
                    $ivax = $s1[ord($maeprove->ColIva) - 65];
                if (empty($ivax) || $ivax == "(E)")
                    $ivax = '0.00';
                else
                    $ivax = fGetfloat($ivax);

                if (empty(trim($maeprove->ColDa)))
                    $dax = '0.00';
                else
                    $dax = fGetfloat($s1[ord($maeprove->ColDa) - 65]);

                if (
                    empty($s1[ord($maeprove->ColCantidad) - 65]) ||
                    is_null($s1[ord($maeprove->ColCantidad) - 65]) ||
                    !isset($s1[ord($maeprove->ColCantidad) - 65]) ||
                    $s1[ord($maeprove->ColCantidad) - 65] <= 0
                )
                    continue;
                $cantx = fGetfloat($s1[ord($maeprove->ColCantidad) - 65]);
                if (empty($cantx) || is_null($cantx) || !isset($cantx))
                    continue;

                $posPnto = strpos($cantx, '.');
                if ($posPnto > 0) {
                    $cantx = explode('.', $cantx);
                    $cantx = $cantx[0];
                }
                if ($cantx <= 0)
                    continue;

                log::info("CCP-> LINEA(" . $linea . "): " . $line);
                $desprod = LetrasyNumeros($s1[ord($maeprove->ColDesprod) - 65]);
                $desprod = QuitarCaracteres($desprod);

                $tipo = "N";
                $iva = $ivax;
                $regulado = "0";
                $codprov = "";
                $precio1 = $preciox;
                $cantidad = $cantx;
                $bulto = BuscarBulto($barra);
                $da = $dax;
                $oferta = "0.00";
                $upre = "0";
                $ppre = "0.00";
                $psugerido = $preciox;
                $pgris = "0.00";
                $nuevo = "0";
                $fechafalla = date('Y-m-j H:i:s');
                $tipocatalogo = $codsede;
                $cuarentena = "0";
                $dctoneto = "0.00";
                if (empty(trim($maeprove->ColLote)))
                    $lote = '01';
                else
                    $lote = $s1[ord($maeprove->ColLote) - 65];
                if (empty(trim($maeprove->ColFechaLote)))
                    $fecvence = 'N/A';
                else
                    $fecvence = $s1[ord($maeprove->ColFechaLote) - 65];
                if (empty(trim($maeprove->ColMarca)))
                    $nomprov = 'N/A';
                else
                    $nomprov = $s1[ord($maeprove->ColMarca) - 65];
                $pactivo = "";
                $costo = "0.00";
                $ubicacion = "";
                $descorta = "";
                $codisb = $codisb;
                $feccatalogo = date('Y-m-j H:i:s');
                $marca = LeerProdcaract($barra, 'marca', 'POR DEFINIR');
                $categoria = LeerProdcaract($barra, 'categoria', 'POR DEFINIR');
                $molecula = LeerProdcaract($barra, 'molecula', 'POR DEFINIR');
                $opc1 = "N/A";
                $opc2 = "N/A";
                $opc3 = "N/A";
                $precio2 = $preciox;
                $precio3 = $preciox;
                $precio4 = $preciox;
                $precio5 = $preciox;
                $precio6 = $preciox;
                if (strlen($desprod) > 250)
                    $desprod = subsetr($desprod, 0, 250);

                $prod = DB::table($tabla)
                    ->where('codprod', '=', $codprod)
                    ->first();
                if ($prod)
                    continue;
                DB::table($tabla)->insert([
                    'codprod' => $codprod,
                    'barra' => $barra,
                    'desprod' => $desprod,
                    'iva' => $iva,
                    'precio1' => $precio1,
                    'precio2' => $precio2,
                    'precio3' => $precio3,
                    'cantidad' => $cantidad,
                    'bulto' => $bulto,
                    'da' => $da,
                    'marca' => $marca,
                    'categoria' => $categoria,
                    'molecula' => $molecula,
                    'fechafalla' => $fechafalla,
                    'fechacata' => $feccatalogo,
                    'tipo' => $tipo,
                    'regulado' => $regulado,
                    'nomprov' => $nomprov,
                    'lote' => $lote,
                    'fecvence' => $fecvence,
                    "metadata" => ""
                ]);
                $insertado++;
            } catch (\Exception $e) {
                $err = "CONVERTIR-> Warning: " . $rutacatalogo . " - " . $e->getMessage() . " - LINEA: " . $e->getLine();
                log::info($err);
                if ($error == 0) {
                    $error++;
                    try {
                        DB::table('maeprove')
                            ->where('codprove', '=', $codprove)
                            ->update(array("cargarLog" => $err));
                    } catch (\Exception $e) {
                    }
                }
            }
        }
    } else {
        // FORMATO TXT (FIJO)
        // barra|codigo|descrip|cantidad|precio|iva|da|marca
        // 0       1          2    3       4     5  6  7
        $primer = 0;
        $separador = '|';
        foreach ($lines as $line) {
            try {

                $line = QuitarCaracteres($line);
                $line = trim($line);
                if ($primer == 0) {
                    $primer = 1;
                    $pos = strpos($line, ';');
                    //log::info("pos: ".$pos);
                    if ($pos > 0) {
                        $separador = ';';
                    }
                }

                if (substr($line, -1) != $separador)
                    $line = $line . $separador;

                $s1 = explode($separador, $line);
                if (count($s1) < 9)
                    continue;

                $codprod = trim($s1[1]);
                $codprod = QuitarCaracteres($codprod);
                if (empty($codprod))
                    continue;

                $barra = $s1[0];
                $barra = QuitarCaracteres($barra);
                if (empty($barra))
                    $barra = $codprod;

                if (strlen($barra) > 20 || strlen($codprod) > 20)
                    continue;

                $preciox = fGetfloat($s1[4]);
                $ivax = fGetfloat($s1[5]);
                $dax = fGetfloat($s1[6]);
                $posComa = strpos($dax, ',');
                $cantx = $s1[3];
                $posComa = strpos($cantx, ',');
                if ($posComa > 0)
                    $cantx = str_replace(",", "", $cantx);
                $posPnto = strpos($cantx, '.');
                if ($posPnto > 0) {
                    $cantx = explode('.', $cantx);
                    $cantx = $cantx[0];
                }
                if ($cantx <= 0)
                    continue;

                log::info("CCP-> LINEA: " . $line);
                $desprod = LetrasyNumeros($s1[2]);
                $desprod = QuitarCaracteres($desprod);
                if (strlen($desprod) > 250)
                    $desprod = substr($desprod, 0, 250);

                $tipo = "N";
                $iva = $ivax;
                $regulado = "0";
                $precio1 = $preciox;
                $cantidad = $cantx;
                $bulto = BuscarBulto($barra);
                $da = $dax;
                $oferta = "0.00";
                $upre = "0";
                $ppre = "0.00";
                $psugerido = $preciox;
                $pgris = "0.00";
                $nuevo = "0";
                $fechafalla = date('Y-m-j H:i:s');
                $tipocatalogo = $codsede;
                $cuarentena = "0";
                $dctoneto = "0.00";
                $lote = "N/A";
                $fecvence = "N/A";
                $nomprov = $s1[7];
                $pactivo = "";
                $costo = "0.00";
                $ubicacion = "";
                $descorta = "";
                $codisb = $codisb;
                $feccatalogo = date('Y-m-j H:i:s');
                $marca = LeerProdcaract($barra, 'marca', 'POR DEFINIR');
                $categoria = LeerProdcaract($barra, 'categoria', 'POR DEFINIR');
                $molecula = LeerProdcaract($barra, 'molecula', 'POR DEFINIR');
                $opc1 = "N/A";
                $opc2 = "N/A";
                $opc3 = "N/A";
                $precio2 = $preciox;
                $precio3 = $preciox;
                $precio4 = $preciox;
                $precio5 = $preciox;
                $precio6 = $preciox;

                if (strlen($desprod) > 250)
                    $desprod = subsetr($desprod, 0, 250);

                $prod = DB::table($tabla)
                    ->where('codprod', '=', $codprod)
                    ->first();
                if ($prod)
                    continue;

                DB::table($tabla)->insert([
                    'codprod' => $codprod,
                    'barra' => $barra,
                    'desprod' => $desprod,
                    'iva' => $iva,
                    'precio1' => $precio1,
                    'precio2' => $precio2,
                    'precio3' => $precio3,
                    'cantidad' => $cantidad,
                    'bulto' => $bulto,
                    'da' => $da,
                    'marca' => $marca,
                    'categoria' => $categoria,
                    'molecula' => $molecula,
                    'fechafalla' => $fechafalla,
                    'fechacata' => $feccatalogo,
                    'tipo' => $tipo,
                    'regulado' => $regulado,
                    'nomprov' => $nomprov,
                    'lote' => $lote,
                    'fecvence' => $fecvence,
                    "metadata" => ""
                ]);
                $insertado++;
            } catch (\Exception $e) {
                $err = "CONVERTIR-> Warning: " . $rutacatalogo . " - " . $e->getMessage() . " - LINEA: " . $e->getLine();
                log::info($err);
                if ($error == 0) {
                    $error++;
                    try {
                        DB::table('maeprove')
                            ->where('codprove', '=', $codprove)
                            ->update(array("cargarLog" => $err));
                    } catch (\Exception $e) {
                    }
                }
            }
        }
    }
    DB::table('maeprove')
        ->where('codprove', '=', $codprove)
        ->update(
            array(
                'fechasinc' => date('Y-m-j H:i:s'),
                'contprod' => $insertado,
                'fechacata' => date('Y-m-j H:i:s')
            )
        );
    log::info("CCP-> INSERTADO: " . $insertado);
    log::info("CCP-> CARGA CATALOGO PROVEEDOR (FINAL): " . $codprove);
    return TRUE;
}
function ObtenerPedidoGrupo($idpedgrupo, $codcli)
{
    $retorno = "";
    $pedido = DB::table('pedido')
        ->where('idpedgrupo', '=', $idpedgrupo)
        ->where('codcli', '=', $codcli)
        ->first();
    if ($pedido) {
        $retorno = $pedido;
    }
    return $retorno;
}
function sCodigoPredetGrupo($codgrupo)
{
    $actualizar = 1;
    $loop = 0;
    $gr = DB::table('gruporen')
        ->where('id', '=', $codgrupo)
        ->get();
    foreach ($gr as $g) {
        if ($loop == 0) {
            $codcli = $g->codcli;
        }
        if ($g->predet == 1) {
            $codcli = $g->codcli;
            $actualizar = 0;
            break;
        }
        $loop++;
    }
    if ($actualizar == 1) {
        DB::table('gruporen')
            ->where('id', '=', $codgrupo)
            ->where('codcli', '=', $codcli)
            ->update(array("predet" => 1));
    }
    return $codcli;
}
function sCodigoClienteActivo()
{
    $tipo = Auth::user()->tipo;
    if (!Session::has('codcli')) {
        if ($tipo == "G") {
            $codcli = "";
            $ultcodcli = Auth::user()->ultcodcli;
            $idgrupo = Auth::user()->codcli;
            $gr = DB::table('gruporen')
                ->where('id', '=', $idgrupo)
                ->where('status', '=', 'ACTIVO')
                ->where('codcli', '=', $ultcodcli)
                ->first();
            if ($gr) {
                $cliente = DB::table('maecliente')
                    ->where('codcli', '=', $ultcodcli)
                    ->first();
                if ($cliente) {
                    $codcli = $ultcodcli;
                }
            }
            if ($codcli == "") {
                $gr = DB::table('gruporen')
                    ->where('id', '=', $idgrupo)
                    ->where('status', '=', 'ACTIVO')
                    ->first();
                if ($gr) {
                    $codcli = $gr->codcli;
                }
            }
        } else {
            $codcli = Auth::user()->codcli;
        }
        Session::put('codcli', $codcli);
    }
    $codcli = Session::get('codcli', "");
    return $codcli;
}
function vEliminarPedidoBlanco($codcli)
{
    // ELIMINA LOS PEDIDOS EN BLANCO
    $peds = DB::table('pedido')
        ->where('codcli', '=', $codcli)
        ->where('estado', '=', 'NUEVO')
        ->get();
    foreach ($peds as $p) {
        $r = DB::table('pedren')
            ->selectRaw('count(*) as contitem')
            ->where('id', '=', $p->id)
            ->first();
        if ($r->contitem == 0) {
            // PEDIDO EN BLANCO
            $regs = DB::table('pedido')
                ->where('id', '=', $p->id)
                ->delete();
        }
    }
}
function TablaMaecliproveActivaMysql()
{
    $codcli = sCodigoClienteActivo();
    return Maeclieprove::select('*', 'maeclieprove.status as status1', 'maeprove.status as status2')
        ->leftjoin('maeprove', 'maeclieprove.codprove', '=', 'maeprove.codprove')
        ->where("codcli", "=", $codcli)
        ->where("maeclieprove.status", "=", 'ACTIVO')
        ->where("maeprove.status", "=", 'ACTIVO')
        ->where("maeprove.modoEnvioPedido", "=", 'MYSQL')
        ->orderBy("preferencia", "asc")
        ->get();
}
function TablaMaecliproveActiva($codcli)
{
    if ($codcli == "")
        $codcli = sCodigoClienteActivo();
    return Maeclieprove::select('*', 'maeclieprove.status as status1', 'maeprove.status as status2')
        ->leftjoin('maeprove', 'maeclieprove.codprove', '=', 'maeprove.codprove')
        ->where("codcli", "=", $codcli)
        ->where("maeclieprove.status", "=", 'ACTIVO')
        ->where("maeprove.status", "=", 'ACTIVO')
        ->orderBy("preferencia", "asc")
        ->get();
}
function TablaMaecliproveActivaOfertas()
{
    $codcli = sCodigoClienteActivo();
    return Maeclieprove::select('*', 'maeclieprove.statusOfertas as status1', 'maeprove.status as status2')
        ->leftjoin('maeprove', 'maeclieprove.codprove', '=', 'maeprove.codprove')
        ->where("codcli", "=", $codcli)
        ->where("maeclieprove.statusOfertas", "=", 'ACTIVO')
        ->where("maeprove.status", "=", 'ACTIVO')
        ->orderBy("preferencia", "asc")
        ->get();
}
function iValidarLicencia($codcli)
{
    $restandias = 7;
    $status = "ACTIVO";
    $cfg = DB::table('maecfg')->first();
    //$codisb = sCodigoClienteActivo();
    $cliente = DB::table('maecliente')
        ->where('codcli', '=', $codcli)
        ->first();
    if ($cliente) {
        if ($cliente->KeyIsacom != "DEMO") {
            $maelicencia = DB::table('maelicencias')
                ->where('cod_lic', '=', $cliente->KeyIsacom)
                ->first();
            if ($maelicencia) {
                $status = $maelicencia->status;
                $restandias = $maelicencia->diaLicencia - DiferenciaDias($maelicencia->fec_act);
                if ($restandias <= 0)
                    $status = "INACTIVO";
                DB::table('maelicencias')
                    ->where('cod_lic', '=', $cliente->KeyIsacom)
                    ->update(
                        array(
                            "status" => $status,
                            "ultPing" => date('Y-m-d H:i:s'),
                            "version" => $cfg->version
                        )
                    );
            } else {
                DB::table('maecliente')
                    ->where('codcli', '=', $cliente->codcli)
                    ->update(array("KeyIsacom" => "DEMO"));
            }
        }
    }
    return $restandias;
}
function NombreCliente()
{
    $codisb = sCodigoClienteActivo();
    $tipo = Auth::user()->tipo;
    $resp = "";
    if ($tipo == 'N') {
        $reg = DB::table('maecanales')
            ->select('descrip')
            ->where('codcanal', '=', $codisb)
            ->first();
        if (!empty($reg))
            $resp = $reg->descrip;
    } else {
        $reg = DB::table('maecliente')
            ->select('nombre')
            ->where('codcli', '=', $codisb)
            ->first();
        if (!empty($reg))
            $resp = $reg->nombre;
    }
    return strtoupper($resp);
}
function LeerProve($codprove)
{
    return DB::table('maeprove')
        ->where('codprove', '=', $codprove)
        ->first();
}
function LeerCliente($codcli)
{
    return DB::table('maecliente')
        ->where('codcli', '=', $codcli)
        ->first();
}
function LeerTablaFirst($tabla, $campo)
{
    try {
        $retorno = DB::table($tabla)->first();
        if ($retorno) {
            return $retorno->$campo;
        }
    } catch (Exception $e) {
        return "";
    }
    return "";
}
function obtenerProvPedido($id)
{
    $provs = TablaMaecliproveActiva("");
    $arrayProv = array();
    foreach ($provs as $prov) {
        $pedren = DB::table('pedren')
            ->where('id', '=', $id)
            ->where('codprove', '=', $prov->codprove)
            ->where('estado', '=', "NUEVO")
            ->first();
        if ($pedren) {
            $arrayProv[] = $prov->codprove;
        }
    }
    return $arrayProv;
}
function NombreImagen($barra)
{
    $reg = DB::table('maeprodimg')
        ->select('nomimagen')
        ->where('barra', '=', $barra)
        ->first();
    if (empty($reg)) {
        $nombre = "noimagen.jpg";
        //$mi_imagen = public_path().'/public/storage/prod/'.$barra.'.jpg';
        $path = '/home/qy9dy4z3xvjb/public_html/isaweb.isbsistemas.com';
        $mi_imagen = $path . '/public/storage/prod/' . $barra . '.jpg';
        if (file_exists($mi_imagen)) {
            $nombre = $barra . '.jpg';
        }
    } else {
        $nombre = $reg->nomimagen;
    }
    return $nombre;
}
function CalculaPrecioNeto($precio, $da, $di, $dc, $pp, $dp)
{
    $base = $precio;
    try {
        if ($base > 0) {
            // DA
            if ($da > 0) {
                $base = $base - ($base * ($da / 100.00));
            }
            // DI
            if ($di > 0) {
                $base = $base - ($base * ($di / 100.00));
            }
            // DC
            if ($dc > 0) {
                $base = $base - ($base * ($dc / 100.00));
            }
            // PP
            if ($pp > 0) {
                $base = $base - ($base * ($pp / 100.00));
            }
            // DP
            if ($dp > 0) {
                $base = $base - ($base * ($dp / 100.00));
            }
        }
    } catch (Exception $e) {
        return $precio;
    }
    return $base;
}
function obtenerRanking($precio, $arrayRnk)
{
    $ranking = "";
    $cont = count($arrayRnk);
    for ($x = 0; $x < $cont; $x++) {
        if ($precio == $arrayRnk[$x]['liquida'] || $precio == $arrayRnk[$x]['liquidaBs']) {
            //log::info("Precio: ".$precio." Liquida: ".$arrayRnk[$x]['liquida']);
            $ranking = ($x + 1) . '-' . ($cont);
            break;
        }
    }
    return $ranking;
}
function iIdUltPedAbierto($codcli, $tipedido)
{
    $id = -1;
    if ($tipedido == "D") {
        $reg = DB::table('pedido')
            ->where('estado', '=', 'NUEVO')
            ->where('codcli', '=', $codcli)
            ->where('tipedido', '=', $tipedido)
            ->orderBy('id', 'desc')
            ->first();
    } else {
        $reg = DB::table('pedido')
            ->where('estado', '=', 'NUEVO')
            ->where('codcli', '=', $codcli)
            ->where('tipedido', '!=', 'D')
            ->orderBy('id', 'desc')
            ->first();
    }
    if ($reg) {
        $id = $reg->id;
    }
    return $id;
}
function iCrearPedidoNuevo($codcli, $tipedido, $marca, $reposicion, $idpedgrupo)
{
    if ($codcli == "")
        $codcli = sCodigoClienteActivo();
    $id = iIdUltPedAbierto($codcli, $tipedido);
    $origen = 'C-WEB';
    if ($tipedido == "A") {
        $usuario = "ADMIN";
        $origen = 'A-WEB';
    } else {
        $usuario = Auth::user()->email;
        $origen = 'C-WEB';
    }
    if ($id < 0 || $tipedido == 'D') {
        //log::info("NEW: " . $id);
        $cliente = DB::table('maecliente')
            ->where('codcli', '=', $codcli)
            ->first();
        $id = DB::table('pedido')->insertGetId([
            'codcli' => $codcli,
            'fecha' => date('Y-m-d H:i:s'),
            'estado' => ($tipedido == 'D') ? 'ABIERTO' : 'NUEVO',
            'fecenviado' => date('Y-m-d H:i:s'),
            'origen' => $origen,
            'codvend' => 'ICOMPRAS',
            'usuario' => $usuario,
            'tipedido' => $tipedido,
            'nomcli' => $cliente->nombre,
            'rif' => $cliente->rif,
            'dcredito' => 0,
            'di' => 0.00,
            'dc' => 0.00,
            'pp' => 0.00,
            'subrenglon' => 0.00,
            'descuento' => 0.00,
            'subtotal' => 0.00,
            'impuesto' => 0.00,
            'total' => 0.00,
            'numren' => 0,
            'numund' => 0,
            'marca' => $marca,
            'reposicion' => $reposicion,
            'idpedgrupo' => $idpedgrupo
        ]);
    } else {
        //log::info("UPD: ".$id);
        DB::table('pedido')
            ->where('id', '=', $id)
            ->update(
                array(
                    'tipedido' => $tipedido,
                    'origen' => $origen,
                    'usuario' => $usuario,
                    'fecenviado' => date('Y-m-d H:i:s'),
                    'fecha' => date('Y-m-d H:i:s')
                )
            );
    }
    return $id;
}
function iContRengPedido($id)
{
    $cont = 0;
    $reg = DB::table('pedren')
        ->selectRaw('count(*) as contador')
        ->where('id', '=', $id)
        ->first();
    if ($reg)
        $cont = $reg->contador;
    return $cont;
}
function VerificaTabla($tabla)
{
    // REDUNDANTE
    $retorno = FALSE;
    $tables_in_db = DB::select('SHOW TABLES');
    $tables = array_map('current', $tables_in_db);
    foreach ($tables as $table) {
        if (strtoupper($table) == strtoupper($tabla)) {
            $retorno = TRUE;
            break;
        }
    }
    return $retorno;
}
function VerificaCampoTabla($tabla, $campo)
{
    $retorno = FALSE;
    $columns = DB::select('SHOW COLUMNS FROM ' . $tabla);
    foreach ($columns as $col) {
        if (strtoupper($col->Field) == strtoupper($campo)) {
            $retorno = TRUE;
            break;
        }
    }
    return $retorno;
}
function dBuscarMontoAhorro($barra, $preciosel, $codcli)
{
    //$codcli = sCodigoClienteActivo();
    $ahorro = 0;
    $provmayor = '';
    $mayor = 0;
    $mayorAnt = 0;
    $preciosel = floatval($preciosel);
    $catalogo = DB::table('tpmaestra')
        ->where('barra', '=', $barra)
        ->first();
    if ($catalogo) {
        $provs = TablaMaecliproveActiva($codcli);
        $mayor = 0;
        foreach ($provs as $prov) {
            $codprove = strtolower($prov->codprove);
            if (!VerificaCampoTabla('tpmaestra', $codprove))
                continue;
            $data = $catalogo->$codprove;

            //log::info($data);

            $campo = explode("|", $data);
            $cantidad = $campo[1];
            $da = $campo[2];
            if (floatval($cantidad) > 0) {
                switch ($prov->tipoprecio) {
                    case 1:
                        $precio = floatval($campo[0]);
                        break;
                    case 2:
                        $precio = floatval($campo[5]);
                        $da = 0.00;
                        break;
                    case 3:
                        $precio = floatval($campo[6]);
                        $da = 0.00;
                        break;
                    default:
                        $precio = floatval($campo[0]);
                        $da = 0.00;
                        break;
                }
                $maeclieprove = DB::table('maeclieprove')
                    ->where('codcli', '=', $codcli)
                    ->where('codprove', '=', strtoupper($codprove))
                    ->first();
                if ($maeclieprove) {
                    $dc = $maeclieprove->dcme;
                    $di = $maeclieprove->di;
                    $pp = $maeclieprove->ppme;
                    $precio = CalculaPrecioNeto($precio, $da, $di, $dc, $pp, 0.00);
                    //log::info("PRFECIO1: ".$precio1.' - '.$codprove);
                    //log::info("PRFECIO: ".$precio.' - '.$codprove);
                    //log::info("DA: ".$da);
                    //log::info("DI: ".$di);
                    //log::info("DC: ".$dc);
                    //log::info("PP: ".$pp);
                    if ($precio > $mayor) {
                        $mayor = $precio;
                        $provmayor = $codprove;
                    }
                }
            }
        }
        //log::info("PRFECIO SEL: ".$preciosel);
        //log::info("PRFECIO MAY: ".$mayor);
        //log::info("PROVEER MAY: ".$provmayor);
        if ($preciosel < $mayor) {
            $dif = $mayor - $preciosel;
            $porc = ($dif * $preciosel) / 100;
            if ($porc > 99)
                $ahorro = 0;
            else
                $ahorro = $mayor - $preciosel;
        }
    }
    return $ahorro;
}
function CalculaTotalesPedido($idpedido)
{

    $pedren = DB::table('pedren')
        ->where('id', '=', $idpedido)
        ->get();
    foreach ($pedren as $pr) {
        $neto = CalculaPrecioNeto($pr->precio, $pr->da, $pr->di, $pr->dc, $pr->pp, $pr->dp);
        DB::table('pedren')
            ->where('item', '=', $pr->item)
            ->update(
                array(
                    "neto" => $neto,
                    "subtotal" => ($neto * $pr->cantidad)
                )
            );
    }

    // MONTO TOTAL EN AHORRO DEL PEDIDO
    $reg = DB::table('pedren')
        ->where('id', '=', $idpedido)
        ->selectRaw('SUM(ahorro * cantidad) as ahorro')
        ->first();
    $ahorro = 0;
    if ($reg->ahorro)
        $ahorro = $reg->ahorro;

    // SUBRENGLON DEL PEDIDO
    $reg = DB::table('pedren')
        ->where('id', '=', $idpedido)
        ->selectRaw('SUM(precio * cantidad) as subrenglon')
        ->first();
    $subrenglon = 0;
    if ($reg->subrenglon)
        $subrenglon = $reg->subrenglon;

    // SUBTOTAL NETO DEL PEDIDO
    $reg = DB::table('pedren')
        ->where('id', '=', $idpedido)
        ->selectRaw('SUM(subtotal) as subtotal')
        ->first();
    $subtotal = 0;
    if ($reg->subtotal)
        $subtotal = $reg->subtotal;

    // CALCULO DEL IMPUESTO DEL PEDIDO
    $reg = DB::table('pedren')
        ->where('id', '=', $idpedido)
        ->where('iva', '>', '0')
        ->selectRaw('SUM((subtotal * iva)/100) as imp')
        ->first();
    $impuesto = 0;
    if ($reg->imp)
        $impuesto = $reg->imp;

    // CONTADOR DE ITEM DEL PEDIDO
    $reg = DB::table('pedren')
        ->where('id', '=', $idpedido)
        ->selectRaw('count(*) as item')
        ->first();
    $item = 0;
    if ($reg->item)
        $item = $reg->item;

    // TOTAL DE UNIDADES
    $reg = DB::table('pedren')
        ->where('id', '=', $idpedido)
        ->selectRaw('SUM(cantidad) as und')
        ->first();
    $und = 0;
    if ($reg->und)
        $und = $reg->und;

    $total = $subtotal + $impuesto;

    DB::table('pedido')
        ->where('id', '=', $idpedido)
        ->update(
            array(
                "numren" => $item,
                "numund" => $und,
                "subrenglon" => $subrenglon,
                "descuento" => $subrenglon - $subtotal,
                "subtotal" => $subtotal,
                "impuesto" => $impuesto,
                "ahorro" => $ahorro,
                "total" => $total
            )
        );
}
function dTotalPedido($id)
{
    $moneda = Session::get('moneda', 'BSS');
    $pedido = DB::table('pedido')
        ->where('id', '=', $id)
        ->first();
    if ($pedido) {
        $pedren = DB::table('pedren')
            ->where('id', '=', $id)
            ->get();
        $subtotal = 0.00;
        $impuesto = 0.00;
        foreach ($pedren as $pr) {
            try {
                $codprove = $pr->codprove;
                $factor = RetornaFactorCambiario($codprove, $moneda);
                $precio = $pr->precio / $factor;
                $neto = CalculaPrecioNeto($precio, $pr->da, $pr->di, $pr->dc, $pr->pp, $pr->dp);
                $st = $neto * $pr->cantidad;
                if ($pr->iva > 0.00) {
                    $impuesto = $impuesto + (($st * $pr->iva) / 100);
                }
                $subtotal = $subtotal + $st;
            } catch (\Exception $e) {
            }
        }
        return $subtotal + $impuesto;
    }
    return 0.00;
}
function VerificarCarrito($barra, $tipedido)
{
    $resp = FALSE;
    $codcli = sCodigoClienteActivo();
    $id = iIdUltPedAbierto($codcli, $tipedido);
    if ($id > 0) {
        $pr = DB::table('pedren')
            ->where('id', '=', $id)
            ->where('barra', '=', $barra)
            ->first();
        if ($pr) {
            $resp = TRUE;
        }
    }
    return $resp;
}
function VerificarCodalterno($barra)
{
    $arrayResp = array();
    $codcli = sCodigoClienteActivo();
    $existe1 = 0;
    $tabla1 = 'inventario_' . $codcli;
    if (VerificaTabla($tabla1)) {
        $tabla1 = DB::table($tabla1)
            ->where('barra', '=', $barra)
            ->where('cuarentena', '=', '0')
            ->first();
        if ($tabla1)
            $existe1 = 1;
    }
    $existe2 = 0;
    $tabla2 = DB::table('prodalterno')
        ->where('codcli', '=', $codcli)
        ->where('barra', '=', $barra)
        ->first();
    if ($tabla2) {
        $existe2 = 1;
    }
    if ($existe2 == 0 && $existe1 == 0) {
        // INVENTARIO NO EXISTE, NO MATH CODALTERNO
        $arrayResp = [
            'backcolor' => "red",
            "forecolor" => "white",
            "title" => "Producto no hace math, agregar código alternativo",
            "codalterno" => "",
            "activarBuscar" => 1
        ];
        return $arrayResp;
    }
    if ($existe2 == 0 && $existe1 == 1) {
        // INVENTARIO EXISTE, NO MATH CODLATERNO
        $arrayResp = [
            'backcolor' => "green",
            "forecolor" => "white",
            "title" => "Producto hace math con su inventario",
            "codalterno" => $tabla1->codprod,
            "activarBuscar" => 0
        ];
        return $arrayResp;
    }
    if ($existe2 == 1 && $existe1 == 0) {
        // NO INVENTARIO, SI MATH CODALTERNO
        $arrayResp = [
            'backcolor' => "yellow",
            "forecolor" => "black",
            "title" => "Producto hace math con su código alterno, Si lo desea podria modificar el código alternativo",
            "codalterno" => $tabla2->codalterno,
            "activarBuscar" => 1
        ];
        return $arrayResp;
    }
    if ($existe2 == 1 && $existe1 == 1) {
        // SI INVENTARIO Y MATH EN CODALTERNO
        DB::table('prodalterno')
            ->where('codcli', '=', $codcli)
            ->where('barra', '=', $barra)
            ->delete();
        $arrayResp = [
            'backcolor' => "green",
            "forecolor" => "black",
            "title" => "Producto hace math con su código alterno, Si lo desea podria modificar el código alternativo",
            "codalterno" => $tabla2->codalterno,
            "activarBuscar" => 0
        ];
        return $arrayResp;
    }
}
function LeerClieProve($codprove, $codcli)
{
    if ($codcli == "")
        $codcli = sCodigoClienteActivo();
    return DB::table('maeclieprove')
        ->where('codcli', '=', $codcli)
        ->where('codprove', '=', $codprove)
        ->first();
}
function BuscarBulto($ref)
{
    $retorno = "1";
    $maeprove = DB::table('maeprove')
        ->where('status', '=', "ACTIVO")
        ->get();
    foreach ($maeprove as $prov) {
        try {
            $tabla = strtolower($prov->codprove);
            $tipocata = $prov->tipocata;
            if (VerificaTabla($tabla)) {
                if ($tipocata == "DRONENA") {
                    $prod = DB::table($tabla)
                        ->where('barra', '=', $ref)
                        ->first();
                    if ($prod) {
                        $retorno = $prod->bulto;
                        break;
                    }
                }
            }
        } catch (\Exception $e) {
        }
    }
    return $retorno;
}
function verificarProdTransito($barra, $codcli, $codgrupo)
{
    if ($codcli == "")
        $codcli = sCodigoClienteActivo();
    $contador = 0;
    if ($codgrupo != "") {
        $gruporen = DB::table('gruporen')
            ->where('id', '=', $codgrupo)
            ->get();
        foreach ($gruporen as $gr) {
            $contcli = 0;
            $codcli = $gr->codcli;
            $cliente = DB::table('maecliente')
                ->where('codcli', '=', $codcli)
                ->first();
            if ($cliente) {
                if ($cliente->diasTransito > 0) {

                    $regs = DB::table('transito')
                        ->where('barra', '=', $barra)
                        ->where('codcli', '=', $codcli)
                        ->get();

                    foreach ($regs as $reg) {
                        $diasTransitox = DiferenciaDias($reg->fecenviado);
                        if ($diasTransitox > $cliente->diasTransito) {

                            DB::table('pedren')
                                ->where('item', '=', $reg->item)
                                ->delete();

                        }
                    }

                    $reg = DB::table('transito')
                        ->where('barra', '=', $barra)
                        ->where('codcli', '=', $codcli)
                        ->selectRaw('SUM(cantidad) as contador')
                        ->first();
                    if ($reg->contador)
                        $contcli = $reg->contador;
                }
            }
            $contador = $contador + $contcli;
        }
    } else {
        $cliente = DB::table('maecliente')
            ->where('codcli', '=', $codcli)
            ->first();
        if ($cliente) {
            if ($cliente->diasTransito > 0) {

                $regs = DB::table('transito')
                    ->where('barra', '=', $barra)
                    ->where('codcli', '=', $codcli)
                    ->get();

                foreach ($regs as $reg) {
                    $diasTransitox = DiferenciaDias($reg->fecenviado);
                    if ($diasTransitox > $cliente->diasTransito) {

                        DB::table('transito')
                            ->where('item', '=', $reg->item)
                            ->delete();

                    }
                }

                $reg = DB::table('transito')
                    ->where('barra', '=', $barra)
                    ->where('codcli', '=', $codcli)
                    ->selectRaw('SUM(cantidad) as contador')
                    ->first();
                if ($reg->contador)
                    $contador = $reg->contador;
            }
        }
    }
    return $contador;
}
function vGrabarAhorroHistorial($codcli, $ahorro)
{
    set_time_limit(300);
    if ($ahorro <= 0)
        return;
    $fechaHoy = date("Y-m-d H:i:s");
    $mes = date("m", strtotime($fechaHoy));
    //log::info("LOG -> MES: ".$mes);

    $anio = date("Y", strtotime($fechaHoy));
    //log::info("LOG -> ANIO: ".$anio);

    $idhist = $mes . '-' . $anio;
    //log::info("LOG -> idhist: ".$idhist);

    $cliente = DB::table('maecliente')
        ->where('codcli', '=', $codcli)
        ->first();
    if (is_null($cliente))
        return;
    $ultreg = '';
    $cadena = '';
    $histAhorro = $cliente->histAhorro;
    if ($histAhorro == "" || is_null($histAhorro)) {
        //log::info("LOG -> histAhorro: ".$histAhorro);

        DB::table('maecliente')
            ->where('codcli', '=', $codcli)
            ->update(array("histAhorro" => $idhist . ';' . strval($ahorro) . '|'));
        return "PRIMER";
    }
    $inicio = 0;

    $campo = explode("|", $histAhorro);
    array_pop($campo);
    $contador = count($campo);
    //log::info($campo);
    //log::info("LOG -> contador: ".$contador);
    for ($i = 0; $i < $contador; $i++) {
        $ultreg = $campo[$i];
        //$cadena = $cadena.$campo[$i].'|';
    }
    $campox = explode(";", $ultreg);
    $idhistx = $campox[0];
    $ahorrox = $campox[1];
    if ($idhistx == $idhist) {
        if ($contador > 1) {
            for ($i = $inicio; $i < $contador - 1; $i++) {
                $cadena = $cadena . $campo[$i] . '|';
            }
            //log::info("LOG -> cadena: ".$cadena);
        }

        $ahorro = floatval($ahorrox) + floatval($ahorro);
        //log::info("LOG -> ahorro: ".$ahorro);
        $cadena = $cadena . $idhist . ';' . strval($ahorro) . '|';
        //log::info("LOG -> cadenanew: ".$cadena);
        DB::table('maecliente')
            ->where('codcli', '=', $codcli)
            ->update(array("histAhorro" => $cadena));
        return "ADD";
    } else {
        if ($contador >= 12)
            $inicio = 1;

        for ($i = $inicio; $i < $contador; $i++) {
            $cadena = $cadena . $campo[$i] . '|';
        }
        //log::info("LOG -> cadena: ".$cadena);
        $cadena = $cadena . $idhist . ';' . strval($ahorro) . '|';
        DB::table('maecliente')
            ->where('codcli', '=', $codcli)
            ->update(array("histAhorro" => $cadena));
        return "NEW";
    }
}
function vCopiaProvPedido($id, $codprove)
{
    // CALCULO DE TOTALES DEL PEDIDO
    set_time_limit(300);
    $numren = 0;
    $numund = 0;
    $ahorro = 0;
    $dSubrenglon = 0.00;
    $dDecuento = 0.00;
    $dSubtotal = 0.00;
    $dImpuesto = 0.00;
    $dTotal = 0.00;
    $pedren = DB::table('pedren')
        ->where('id', '=', $id)
        ->where('codprove', '=', $codprove)
        ->get();
    foreach ($pedren as $pr) {
        try {
            if ($pr->cantidad > 0) {
                DB::table('provpedren')->insert([
                    'id' => $pr->id,
                    'codprod' => $pr->codprod,
                    'desprod' => $pr->desprod,
                    'cantidad' => $pr->cantidad,
                    'precio' => $pr->precio,
                    'barra' => $pr->barra,
                    'codprove' => $pr->codprove,
                    'regulado' => $pr->regulado,
                    'tipo' => $pr->tipo,
                    'pvp' => $pr->pvp,
                    'iva' => $pr->iva,
                    'da' => $pr->da,
                    'di' => $pr->di,
                    'dc' => $pr->dc,
                    'pp' => $pr->pp,
                    'neto' => $pr->neto,
                    'subtotal' => $pr->subtotal,
                    'codcli' => $pr->codcli,
                    'ahorro' => $pr->ahorro,
                    'aprobacion' => $pr->aprobacion,
                    'estado' => 'ENVIADO',
                    'fecha' => $pr->fecha,
                    'fecenviado' => date("Y-m-d H:i:s"),
                    'ranking' => $pr->ranking,
                    'bulto' => $pr->bulto,
                    'fecprocesado' => $pr->fecprocesado,
                    'marcado' => $pr->marcado,
                    'codprove_adm' => $pr->codprove_adm,
                    'factor' => $pr->factor,
                    'codalterno' => $pr->codalterno,
                    'tprnk1' => $pr->tprnk1,
                    'exportado' => $pr->exportado,
                    'usuarioCreador' => $pr->usuarioCreador,
                    'obsdocumento' => $pr->obsdocumento,
                    'coddocumento' => $pr->coddocumento,
                    'fecdocumento' => $pr->fecdocumento
                ]);
                $neto = CalculaPrecioNeto($pr->precio, $pr->da, $pr->di, $pr->dc, $pr->pp, $pr->dp);
                $subtotal = $neto * $pr->cantidad;
                if ($pr->iva > 0) {
                    $dImpuesto = $dImpuesto + (($subtotal * $pr->iva) / 100);
                }
                $dSubtotal = $dSubtotal + $subtotal;
                $dSubrenglon = $dSubrenglon + ($pr->precio * $pr->cantidad);
                $numren++;
                $numund = $numund + $pr->cantidad;
                $ahorro = $ahorro + $pr->ahorro;
            }
        } catch (Exception $e) {
            log::info("vCopiaProvPedido -> WARNING1: " . $e->getMessage());
            return FALSE;
        }
    }
    $dDecuento = $dSubrenglon - $dSubtotal;
    $dTotal = $dSubtotal + $dImpuesto;
    if ($dTotal > 0) {
        $ped = DB::table('pedido')
            ->where('id', '=', $id)
            ->first();
        if ($ped) {
            try {
                DB::table('provpedido')->insert([
                    'id' => $ped->id,
                    'codprove' => $codprove,
                    'codcli' => $ped->codcli,
                    'fecha' => $ped->fecha,
                    'estado' => 'ENVIADO',
                    'fecenviado' => date("Y-m-d H:i:s"),
                    'origen' => $ped->origen,
                    'usuario' => $ped->usuario,
                    'codvend' => $ped->codvend,
                    'tipedido' => $ped->tipedido,
                    'nomcli' => $ped->nomcli,
                    'rif' => $ped->rif,
                    'dcredito' => $ped->dcredito,
                    'di' => $ped->di,
                    'dc' => $ped->dc,
                    'pp' => $ped->pp,
                    'subrenglon' => $dSubrenglon,
                    'descuento' => $dDecuento,
                    'subtotal' => $dSubtotal,
                    'impuesto' => $dImpuesto,
                    'total' => $dTotal,
                    'numren' => $numren,
                    'numund' => $numund,
                    'ahorro' => $ahorro
                ]);
            } catch (Exception $e) {
                log::info("vCopiaProvPedido -> WARNING2: " . $e->getMessage());
                return FALSE;
            }
        }
    }
    log::info("vCopiaProvPedido -> ID: " . $id . " CODPROVE: " . $codprove . " ->OK ");
    return TRUE;
}
function vGrabarPedRenEnviado($id, $codprove, $aprobacion)
{
    try {
        set_time_limit(300);
        DB::table('pedido')
            ->where('id', '=', $id)
            ->update(array('fecenviado' => date("Y-m-d H:i:s")));

        DB::table('pedren')
            ->where('id', '=', $id)
            ->where('codprove', '=', $codprove)
            ->update(
                array(
                    "aprobacion" => $aprobacion,
                    "estado" => "ENVIADO",
                    "fecenviado" => date("Y-m-d H:i:s")
                )
            );
    } catch (Exception $e) {
        log::info("vGrabarPedRenEnviado(1) -> WARNING: " . $e->getMessage());
        return -1;
    }
    // COLOCA PRODUCTOS EN TRANSITO
    $pedren = DB::table('pedren')
        ->where('id', '=', $id)
        ->where('codprove', '=', $codprove)
        ->get();
    foreach ($pedren as $pr) {
        try {
            if ($pr->cantidad > 0) {
                DB::table('transito')->insert([
                    'id' => $id,
                    'codprod' => $pr->codprod,
                    'desprod' => $pr->desprod,
                    'cantidad' => $pr->cantidad,
                    'barra' => $pr->barra,
                    'codprove' => $codprove,
                    'codcli' => $pr->codcli,
                    "fecha" => $pr->fecenviado,
                    "fecenviado" => date("Y-m-d H:i:s")
                ]);
                log::info("TRANSITO     : ID: " . $id . " CODPROV: " . $codprove . " CODPROD: " . $pr->codprod . " CANTIDAD: " . $pr->cantidad);
            }
        } catch (Exception $e) {
            log::info("vGrabarPedRenEnviadovGrabarPedRenEnviado(2) -> WARNING: " . $e->getMessage());
            return -1;
        }
    }
    return 0;
}
function vUpdateEstadoPedido($id)
{
    set_time_limit(300);
    $contGral = 0;
    $contNuevo = 0;
    $contEnviado = 0;
    $factor = RetornaFactorCambiario("", "USD");
    $pedren = DB::table('pedren')
        ->where('id', '=', $id)
        ->get();
    foreach ($pedren as $pr) {
        $contGral++;
        switch ($pr->estado) {
            case 'NUEVO':
                $contNuevo++;
                break;
            case 'RECIBIDO':
                $contEnviado++;
                break;
        }
    }
    $estado = "";
    if ($contNuevo == $contGral) {
        $estado = "NUEVO";
    } else {
        if ($contEnviado == $contGral)
            $estado = "ENVIADO";
        else
            $estado = "PARCIAL";
        try {
            DB::table('pedido')
                ->where('id', '=', $id)
                ->update(
                    array(
                        "estado" => $estado,
                        "factor" => $factor,
                        'fecenviado' => date("Y-m-d H:i:s")
                    )
                );
        } catch (Exception $e) {
            log::info("vUpdateEstadoPedido(1) -> WARNING: " . $e->getMessage());
            return -1;
        }
    }
    return 0;
}
function DiferenciaDias($fechav)
{
    $fechoy = strtotime(date('d-m-Y'));
    $fecven = strtotime($fechav);
    $dif = $fechoy - $fecven;
    $dias = ((($dif / 60) / 60) / 24);
    if ($dias < 0.00)
        $dias = 0;
    return ceil($dias);
}
function ReordenarPrefereciaCliente()
{
    $contador = 1;
    $reordenar = false;
    $codgrupo = Auth::user()->codcli;
    $gruporen = DB::table('gruporen')
        ->where('id', '=', $codgrupo)
        ->orderBy("preferencia", "asc")
        ->get();

    foreach ($gruporen as $gr) {
        $pref = $gr->preferencia;
        $prefx = "00" . $contador;
        $prefx = substr($prefx, strlen($prefx) - 2, 2);
        if ($prefx != $pref) {
            $reordenar = true;
            break;
        }
        $contador++;
    }

    if ($reordenar) {
        $contador = 1;
        foreach ($gruporen as $gr) {

            $codcli = $gr->codcli;
            $pref = "00" . $contador;
            $pref = substr($pref, strlen($pref) - 2, 2);

            DB::table('gruporen')
                ->where('id', '=', $codgrupo)
                ->where('codcli', '=', $codcli)
                ->update(array("preferencia" => $pref));
            $contador++;

        }
    }
    return $reordenar;
}
function ReordenarPrefereciaProve()
{
    $contador = 1;
    $reordenar = false;
    $codcli = sCodigoClienteActivo();
    if (Auth::user()->tipo == 'O') {
        $provs = Maeclieprove::select('*', 'maeclieprove.statusOfertas as statusclieprove')
            ->leftjoin('maeprove', 'maeclieprove.codprove', '=', 'maeprove.codprove')
            ->where("codcli", "=", $codcli)
            ->orderBy("preferencia", "asc")
            ->get();
    } else {
        $provs = Maeclieprove::select('*', 'maeclieprove.status as statusclieprove')
            ->leftjoin('maeprove', 'maeclieprove.codprove', '=', 'maeprove.codprove')
            ->where("codcli", "=", $codcli)
            ->orderBy("preferencia", "asc")
            ->get();
    }
    foreach ($provs as $prov) {
        $pref = $prov->preferencia;
        $prefx = "00" . $contador;
        $prefx = substr($prefx, strlen($prefx) - 2, 2);
        if ($prefx != $pref) {
            $reordenar = true;
            break;
        }
        $contador++;
    }

    if ($reordenar) {
        $contador = 1;
        foreach ($provs as $prov) {

            $codprove = $prov->codprove;
            $pref = "00" . $contador;
            $pref = substr($pref, strlen($pref) - 2, 2);

            DB::table('maeclieprove')
                ->where('codcli', '=', $codcli)
                ->where('codprove', '=', $codprove)
                ->update(array("preferencia" => $pref));

            $contador++;

        }
    }
    return $reordenar;
}
function ObtenerContadorProd($codprove)
{
    $contador = 0;
    $codprove = strtolower($codprove);
    try {
        $prov = DB::table($codprove)
            ->selectRaw('count(*) as contador')->first();
        $contador = $prov->contador;
    } catch (Exception $e) {
    }
    return $contador;
}
function verificarProveNuevo($codprove)
{
    $contador = 0;
    $codprove = strtolower($codprove);
    $codcli = sCodigoClienteActivo();
    try {
        $reg = DB::table('maeclieprove')
            ->where('codcli', '=', $codcli)
            ->where('codprove', '=', $codprove)
            ->selectRaw('count(*) as contador')->first();
        $contador = $reg->contador;
    } catch (Exception $e) {
    }
    return $contador;
}
function iCantidadConsolidadoProv($barra)
{
    $cantidad = 0;
    $catalogo = DB::table('tpmaestra')
        ->where('barra', '=', $barra)
        ->first();
    if ($catalogo) {
        $provs = TablaMaecliproveActiva("");
        $mayor = 0;
        foreach ($provs as $prov) {
            $codprove = strtolower($prov->codprove);
            if (VerificaTabla($codprove)) {
                if (!VerificaCampoTabla('tpmaestra', $codprove))
                    continue;
                $data = $catalogo->$codprove;
                $campo = explode("|", $data);
                $cantidad += $campo[1];
            }
        }
    }
    return $cantidad;
}
function VerificarSugerido($codprod, $codcli)
{
    $retorno = 0;
    if ($codcli == "")
        $codcli = sCodigoClienteActivo();
    $sugerido = DB::table('sugerido')
        ->where('codprod', '=', $codprod)
        ->where('codcli', '=', $codcli)
        ->first();
    if ($sugerido) {
        $retorno = $sugerido->pedir;
    }
    return $retorno;
}
function LeerInventarioCodigo($codprod, $codcli)
{
    if ($codcli == "")
        $codcli = sCodigoClienteActivo();
    $inv = null;
    $tabla = 'inventario_' . $codcli;
    if (VerificaTabla($tabla)) {
        $inv = DB::table($tabla)
            ->where('codprod', '=', $codprod)
            ->where('cuarentena', '=', '0')
            ->first();
    }
    return $inv;
}
function verificarProdInventario($barra, $codcli)
{
    if ($codcli == "")
        $codcli = sCodigoClienteActivo();
    $retorno = null;
    $tabla = 'inventario_' . $codcli;
    if (VerificaTabla($tabla)) {
        $inv = DB::table($tabla)
            ->where('barra', '=', $barra)
            ->where('cuarentena', '=', '0')
            ->first();
        if ($inv)
            $retorno = $inv;
    }
    return $retorno;
}
function bComprimir($origen, $destino)
{
    $resp = FALSE;
    $x = 0;
    while ($x < 3) {
        if (file_exists($origen)) {
            try {
                $fp = fopen($origen, "r");
                $data = fread($fp, filesize($origen));
                fclose($fp);
                $zp = gzopen($destino, "w9");
                gzwrite($zp, $data);
                gzclose($zp);
                $resp = TRUE;
                break;
            } catch (Exception $e) {
                log::info("CD -> ERROR: " . $e->getMessage());
            }
        }
        $x++;
    }
    return $resp;
}
function fGetfloat($str)
{
    $num = '0.00';
    if ($str == '' || $str == '0') {
        return floatval($num);
    }
    if (strstr($str, ',') || strstr($str, '.')) {
        $invertida = strrev($str);
        for ($x = 0; $x < strlen($invertida); $x++) {
            $cardec = substr($invertida, $x, 1);
            if ($cardec == '.' || $cardec == ',') {
                break;
            }
        }
        if ($cardec == '.') {
            $str = str_replace(",", "", $str);
        }
        if ($cardec == ',') {
            $str = str_replace(".", "", $str);
            $str = str_replace(",", ".", $str);
        }
    }
    if (preg_match("#([0-9\.]+)#", $str, $match)) {
        return floatval($match[0]);
    } else {
        return floatval($str);
    }
}
function LetrasyNumeros($texto)
{
    $textoLimpio = preg_replace('/[^a-zA-Z0-9\s]/', '', $texto);
    $textoLimpio = str_replace('Ñ', 'N', $textoLimpio);
    $textoLimpio = str_replace('ñ', 'N', $textoLimpio);
    $textoLimpio = str_replace('é', 'e', $textoLimpio);
    $textoLimpio = str_replace('á', 'a', $textoLimpio);
    $textoLimpio = str_replace('í', 'i', $textoLimpio);
    $textoLimpio = str_replace('ó', 'o', $textoLimpio);
    $textoLimpio = str_replace('ú', 'u', $textoLimpio);
    return $textoLimpio;
}
function QuitarCerosDecimales($numero)
{
    $numero = str_replace('.00', '', $numero);
    $numero = str_replace('.0', '', $numero);
    return trim($numero);
}
function BuscarNomprov($ref)
{
    $retorno = "";
    $maeprove = DB::table('maeprove')
        ->where('status', '=', "ACTIVO")
        ->get();
    foreach ($maeprove as $prov) {
        try {
            $tabla = strtolower($prov->codprove);
            $tipocata = $prov->tipocata;
            if (VerificaTabla($tabla)) {
                if ($tipocata == "DROLANCA") {
                    $prod = DB::table($tabla)
                        ->where('barra', '=', $ref)
                        ->first();
                    if ($prod) {
                        $retorno = $prod->nomprov;
                        break;
                    }
                }
            }
        } catch (\Exception $e) {
        }
    }
    return $retorno;
}
function iEnvioPedidoDirectoxCorreo($id, $pedcorreo, $formato, $marca)
{
    $fechaHoy = date('j-m-Y');
    $FechaPedido = substr($fechaHoy, 0, 10);
    $correoUsuario = Auth::user()->email;
    $cfg = DB::table('maecfg')->first();
    $correoRemitente = $cfg->correoRemitente;
    if (empty($correoRemitente))
        return 0;
    if (empty($pedcorreo))
        return 0;
    $titulo = "PEDIDO DIRECTO: " . $id;

    $pedgrupo = DB::table('pedgrupo')
        ->where('id', '=', $id)
        ->first();

    $gruporen = DB::table('gruporen')
        ->where('id', '=', $pedgrupo->codgrupo)
        ->orderBy("preferencia", "asc")
        ->get();

    $pgarray = array();
    $pedido = DB::table('pedido')
        ->where('idpedgrupo', '=', $id)
        ->get();
    foreach ($pedido as $ped) {

        $pedren = DB::table('pedren')
            ->where('id', '=', $ped->id)
            ->get();
        foreach ($pedren as $pr) {

            if (empty($pgarray)) {
                $pgarray[] = $pr;
            } else {

                $found_key = trim(array_search($pr->barra, array_column($pgarray, 'barra')));
                if ($found_key == "")
                    $pgarray[] = $pr;

            }

        }
    }
    $pedgrpren = array();
    foreach ($pgarray as $p) {
        $cantidad = 0;
        foreach ($gruporen as $gr) {
            $codsuc = strtolower($gr->codcli);
            $cant = 0;
            $pedidox = DB::table('pedido')
                ->where('idpedgrupo', '=', $id)
                ->where('codcli', '=', $codsuc)
                ->first();
            if ($pedidox) {
                $pedrenx = DB::table('pedren')
                    ->where('id', '=', $pedidox->id)
                    ->where('barra', '=', $p->barra)
                    ->first();
                if ($pedrenx) {
                    $cant = $pedrenx->cantidad;
                }
            }
            $cantidad += $cant;
        }
        $p->cantgrp = $cantidad;
        $pedgrpren[] = $p;
    }
    $pg = collect($pedgrpren);
    $pg = $pg->sortBy('desprod');
    $data = [
        "titulo" => $titulo,
        "marca" => $marca,
        "cfg" => $cfg,
        "pg" => $pg,
        "id" => $id
    ];
    $pdf = PDF::loadView('layouts.rptpedidodirecto', $data);
    try {
        Mail::send(
            'layouts.rptpedidodirecto',
            $data,
            function ($mail) use ($pdf, $data, $correoRemitente, $pedcorreo, $id, $titulo) {
                $mail->from($correoRemitente, $titulo);
                $mail->to($pedcorreo)->subject('Orden de Pedido Directo');
                $mail->attachData($pdf->output(), 'pedido' . $id . '.pdf');
            }
        );
        return 1;
    } catch (Exception $e) {
        log::info("ERROR: " . $e);
        return 0;
    }
}
function EnvioPedidoxCorreo($id, $codprove)
{
    $fechaHoy = date('j-m-Y');
    $FechaPedido = substr($fechaHoy, 0, 10);
    $correoUsuario = Auth::user()->email;
    $cfg = DB::table('maecfg')->first();
    $correoRemitente = $cfg->correoRemitente;
    if (empty($correoRemitente))
        return;

    $maeprove = LeerProve($codprove);
    if (is_null($maeprove))
        return;
    $correoDestino = $maeprove->correoEnvioPedido;
    if (empty($correoDestino))
        return;

    $tituloppal = "PEDIDO MAESTRO";
    // TABLA DE PEDIDO
    $tabla = DB::table('pedido')
        ->where('id', '=', $id)
        ->first();

    $titulo = "PEDIDO: " . $id;
    $subtitulo = $tabla->nomcli;
    $codcli = $tabla->codcli;

    $tabla2 = DB::table('pedren')
        ->where('id', '=', $id)
        ->where('codprove', '=', $codprove)
        ->get();

    // TABLA DE RENGLONES DE PEDIDO
    $cliente = DB::table('maecliente')
        ->where('codcli', '=', $codcli)
        ->first();

    $correoCliente = $cliente->campo3;
    $users = DB::table('users')
        ->where('codcli', '=', $codcli)
        ->where('tipo', '=', 'C')
        ->first();

    if ($users)
        $correoCliente = $users->email;
    $correoRemitente = $correoCliente;

    // CALCULO DE TOTALES DEL PEDIDO
    $dSubrenglon = 0.00;
    $dDecuento = 0.00;
    $dSubtotal = 0.00;
    $dImpuesto = 0.00;
    $dTotal = 0.00;
    $moneda = Session::get('moneda', 'BSS');
    foreach ($tabla2 as $pr) {
        $factor = RetornaFactorCambiario($pr->codprove, $moneda);

        $neto = CalculaPrecioNeto($pr->precio, $pr->da, $pr->di, $pr->dc, $pr->pp, $pr->dp);
        $subtotal = $neto * $pr->cantidad;
        if ($pr->iva > 0) {
            $dImpuesto = $dImpuesto + (($subtotal * $pr->iva) / 100);
        }
        $dSubtotal = $dSubtotal + $subtotal;
        $dSubrenglon = $dSubrenglon + ($pr->precio * $pr->cantidad);
    }
    $dDecuento = $dSubrenglon - $dSubtotal;
    $dTotal = $dSubtotal + $dImpuesto;



    $data = [
        "tituloppal" => $tituloppal,
        "titulo" => $titulo,
        "subtitulo" => $subtitulo,
        "tabla" => $tabla,
        "tabla2" => $tabla2,
        "cfg" => $cfg,
        "impuesto" => $dImpuesto,
        "total" => $dTotal,
        "maeprove" => $maeprove,
        "codprove" => $codprove,
        "cliente" => $cliente,
        "correoUsuario" => $correoUsuario,
        "id" => $id,
        "factor" => $factor,
        "moneda" => $moneda,
        "codcli" => $codcli
    ];

    //$pdf = PDF::loadView('EnvioPedidoxCorreo', $data);
    $pdf = PDF::loadView('layouts.rptpedido', $data);
    try {
        Mail::send('layouts.rptpedido', $data, function ($mail) use ($pdf, $data, $correoRemitente, $correoDestino, $id, $subtitulo, $correoCliente) {
            $mail->from($correoRemitente, $subtitulo);
            $mail->to($correoDestino)->subject('Orden de pedido');
            $mail->cc($correoCliente);
            $mail->attachData($pdf->output(), 'pedido' . $id . '.pdf');
        });
        return "";
    } catch (Exception $e) {
        return $e;
    }
}
function ProvTotalPedido($id, $codprove)
{
    $reg = DB::table('pedren')
        ->where('id', '=', $id)
        ->where('codprove', '=', $codprove)
        ->selectRaw('SUM(subtotal) as subtotal')
        ->first();
    return $reg->subtotal;
}
function CalculaProvTotalesPedido($id, $codprove)
{

    // SUBRENGLON DEL PEDIDO
    $reg = DB::table('pedren')
        ->where('id', '=', $id)
        ->where('codprove', '=', $codprove)
        ->selectRaw('SUM(precio * cantidad) as subrenglon')
        ->first();
    $subrenglon = 0;
    if ($reg->subrenglon)
        $subrenglon = $reg->subrenglon;

    // SUBTOTAL NETO DEL PEDIDO
    $reg = DB::table('pedren')
        ->where('id', '=', $id)
        ->where('codprove', '=', $codprove)
        ->selectRaw('SUM(subtotal) as subtotal')
        ->first();
    $subtotal = 0;
    if ($reg->subtotal)
        $subtotal = $reg->subtotal;

    // CALCULO DEL IMPUESTO DEL PEDIDO
    $reg = DB::table('pedren')
        ->where('id', '=', $id)
        ->where('codprove', '=', $codprove)
        ->where('iva', '>', '0')
        ->selectRaw('SUM((subtotal * iva)/100) as imp')
        ->first();
    $impuesto = 0;
    if ($reg->imp)
        $impuesto = $reg->imp;

    // CONTADOR DE ITEM DEL PEDIDO
    $reg = DB::table('pedren')
        ->where('id', '=', $id)
        ->where('codprove', '=', $codprove)
        ->selectRaw('count(*) as item')
        ->first();
    $item = 0;
    if ($reg->item)
        $item = $reg->item;

    // TOTAL DE UNIDADES
    $reg = DB::table('pedren')
        ->where('id', '=', $id)
        ->where('codprove', '=', $codprove)
        ->selectRaw('SUM(cantidad) as und')
        ->first();
    $und = 0;
    if ($reg->und)
        $und = $reg->und;

    $total = $subtotal + $impuesto;

    return array(
        'numren' => $item,
        'numund' => $und,
        'subrenglon' => $subrenglon,
        'descuento' => $subrenglon - $subtotal,
        'subtotal' => $subtotal,
        'impuesto' => $impuesto,
        'total' => $total
    );
}
function iExisteArchivoFtp($ftp, $user, $pass, $pasv, $carpeta, $archivo)
{
    $retorno = 0;
    try {
        $conn_id = ftp_connect($ftp);
        if ($conn_id) {
            $login_result = ftp_login($conn_id, $user, $pass);
            if ($login_result) {
                if ($pasv == "PASIVO")
                    ftp_pasv($conn_id, true);
                $files = ftp_nlist($conn_id, $carpeta);
                if (count($files)) {
                    foreach ($files as $file) {
                        $archivox = basename($file);
                        if ($archivox == $archivo) {
                            $retorno = 1;
                            log::info("iExisteFtp  : OK");
                            break;
                        }
                    }
                }
            } else {
                $retorno = -1;
            }
            ftp_close($conn_id);
        }
    } catch (Exception $e) {
        $retorno = -1;
        log::info("iExisteFtp  : ERROR(-1) " . $e);
    }
    return $retorno;
}
function iDeleteArchivoFtp($ftp, $user, $pass, $pasv, $archivo)
{
    $retorno = 0;
    try {
        $conn_id = ftp_connect($ftp);
        if ($conn_id) {
            $login_result = ftp_login($conn_id, $user, $pass);
            if ($login_result) {
                if ($pasv == "PASIVO")
                    ftp_pasv($conn_id, true);
                if (ftp_delete($conn_id, $archivo)) {
                    $retorno = 1;
                    log::info("iDeleteFtp  : OK");
                } else {
                    $retorno = -1;
                    log::info("iDeleteFtp  : ERROR");
                }
            } else {
                $retorno = -1;
            }
            ftp_close($conn_id);
        }
    } catch (Exception $e) {
        $retorno = -1;
        log::info("iDeleteFtp  : ERROR(-1)");
    }
    return $retorno;
}
function iEnviarArchivoFtp($ftp, $user, $pass, $pasv, $origen, $destino, $archivo)
{
    $retorno = 1;
    try {
        $rutadestino = $destino . $archivo;
        if (iExisteArchivoFtp($ftp, $user, $pass, $pasv, $destino, $archivo) == 1) {
            iDeleteArchivoFtp($ftp, $user, $pass, $pasv, $rutadestino);
        }
        $conn_id = ftp_connect($ftp);
        if ($conn_id) {
            $login_result = ftp_login($conn_id, $user, $pass);
            if ($login_result) {
                ftp_set_option($conn_id, FTP_USEPASVADDRESS, false);
                ftp_set_option($conn_id, FTP_TIMEOUT_SEC, 180);
                if ($pasv == "PASIVO")
                    ftp_pasv($conn_id, true);
                $resp = ftp_put($conn_id, $rutadestino, $origen, FTP_ASCII);
                if ($resp) {
                    $retorno = 0;
                    log::info("iEnviarFtp  : OK");
                }
            } else {
                $retorno = -1;
            }
            ftp_close($conn_id);
        }
    } catch (Exception $e) {
        $retorno = -1;
        log::info("iEnviarFtp  : ERROR(-1)");
    }
    return $retorno;
}
function iRenameArchivoFtp($ftp, $user, $pass, $pasv, $destino, $old, $new)
{
    $retorno = 1;
    try {
        $conn_id = ftp_connect($ftp);
        if ($conn_id) {
            $login_result = ftp_login($conn_id, $user, $pass);
            if ($login_result) {
                if ($pasv == "PASIVO") {
                    ftp_pasv($conn_id, true);
                }
                $rutaold = $destino . $old;
                $rutanew = $destino . $new;
                if (ftp_rename($conn_id, $rutaold, $rutanew)) {
                    $retorno = 0;
                    log::info("iRenameFtp  : OK");
                } else {
                    log::info("iRenameFtp  : ERROR(-1)");
                    $retorno = -1;
                }
            } else {
                $retorno = -1;
            }
            ftp_close($conn_id);
        }
    } catch (Exception $e) {
        $retorno = -1;
        log::info("iRenameFtp  : ERROR(-2)");
    }
    //log::info("retorno: ".$retorno);
    return $retorno;
}
function iListDirectoryFtp($ftp, $user, $pass, $pasv, $ruta)
{
    $retorno = 1;
    try {
        $conn_id = ftp_connect($ftp);
        if ($conn_id) {
            $login_result = ftp_login($conn_id, $user, $pass);
            if ($login_result) {
                if ($pasv == "PASIVO") {
                    ftp_pasv($conn_id, true);
                }
                $file_list = ftp_nlist($conn_id, $ruta);
                foreach ($file_list as $file) {
                    $retorno = 0;
                    log::info("iListDirectoryFtp  : OK");
                    break;
                }
            } else {
                $retorno = -1;
            }
            ftp_close($conn_id);
        }
    } catch (Exception $e) {
        $retorno = -1;
        log::info("iListDirectoryFtp  : ERROR(-2)");
    }
    //log::info("retorno: ".$retorno);
    return $retorno;
}
function LeerProdcaract($barra, $campo, $defecto)
{
    // REDUNDANTE
    $prodcaract = DB::table('prodcaract')
        ->where('barra', '=', $barra)
        ->first();
    if ($prodcaract)
        return $prodcaract->$campo;
    else
        return $defecto;
}
function bCargarInvIndividual($filename)
{
    // REDUNDANTE
    $BASE_PATH = "/home/qy9dy4z3xvjb/public_ftp/inv";
    date_default_timezone_set("America/Caracas");
    set_time_limit(2000);
    try {
        $continua = 0;
        $origen = $BASE_PATH . '/' . $filename;
        $destino = $BASE_PATH . '/' . substr($filename, 0, strlen($filename) - 4);
        if (!file_exists($origen))
            return 0;
        if (str_contains($filename, '.zip')) {

            if (is_readable($origen)) {
                if (Descomprimir($origen, $destino)) {
                    log::info("INV -> Descomprimiendo: " . $filename . ' OK');
                    $continua = 1;
                }
            }
            if ($continua == 0) {
                log::info("INV -> here was a problem: " . $filename . ' ERROR');
                return 0;
            }
            $archivo = explode("_", $filename);
            $codcli = substr($archivo[1], 0, strlen($archivo[1]) - 8);
            $cliente = DB::table('maecliente')
                ->where('codcli', '=', $codcli)
                ->where('estado', '=', 'ACTIVO')
                ->first();
            if (empty($cliente))
                return 0;
            log::info("INV -> ** INICIO " . $codcli . "-" . $cliente->nombre . " **");
            //if (CreaTablaInv($codcli))
            //    log::info("INV -> ** TABLA CREADA (INVENTARIO) **");
            $tabla = 'inventario_' . $codcli;
            if (!VerificaTabla($tabla))
                return 0;
            DB::table($tabla)->update(array('nuevo' => '3'));
            $contador = 0;
            $lines = file($destino);
            foreach ($lines as $line) {
                try {
                    $s1 = explode("|", $line);
                    if (count($s1) <> 42) {
                        log::info("INV -> **  s1(" . $tabla . "): " . count($s1) . ' line: ' . $line);
                        continue;
                    }
                    $codprod = trim($s1[1]);
                    $regulado = trim($s1[5]);
                    $codprov = trim($s1[6]);
                    if (strlen($regulado) > 1) {
                        if (strlen($codprov) > 0) {
                            $regulado = substr($codprov, 0, 1);
                            $codprov = $regulado;
                        }
                    }
                    if ($contador == 0) {
                        log::info("INV -> s1(" . $tabla . "): " . count($s1) . ' line: ' . $line);
                        log::info("INV -> barra: " . $s1[0]);
                        log::info("INV -> codprod: " . $codprod);
                        log::info("INV -> desprod: " . $s1[2]);
                        log::info("INV -> tipo: " . $s1[3]);
                        log::info("INV -> iva: " . $s1[4]);
                        log::info("INV -> regulado: " . $regulado);
                        log::info("INV -> codprov: " . $codprov);
                        log::info("INV -> precio1: " . $s1[7]);
                        log::info("INV -> cantidad: " . $s1[8]);
                        log::info("INV -> bulto: " . $s1[9]);
                        log::info("INV -> da: " . $s1[10]);
                        log::info("INV -> oferta: " . $s1[11]);
                        log::info("INV -> upre: " . $s1[12]);
                        log::info("INV -> ppre: " . $s1[13]);
                        log::info("INV -> psugerido: " . $s1[14]);
                        log::info("INV -> pgris: " . $s1[15]);
                        log::info("INV -> nuevo: " . $s1[16]);
                        log::info("INV -> fechafalla: " . $s1[17]);
                        log::info("INV -> tipocatalogo: " . $s1[18]);
                        log::info("INV -> cuarentena: " . $s1[19]);
                        log::info("INV -> dctoneto: " . $s1[20]);
                        log::info("INV -> lote: " . $s1[21]);
                        log::info("INV -> fecvence: " . $s1[22]);
                        log::info("INV -> marca: " . $s1[23]);
                        log::info("INV -> pactivo: " . $s1[24]);
                        log::info("INV -> costo: " . $s1[25]);
                        log::info("INV -> ubicacion: " . $s1[26]);
                        log::info("INV -> descorta: " . $s1[27]);
                        log::info("INV -> codisb: " . $s1[28]);
                        log::info("INV -> feccatalogo: " . $s1[29]);
                        log::info("INV -> categoria: " . $s1[30]);
                        log::info("INV -> grupo: " . $s1[31]);
                        log::info("INV -> subgrupo: " . $s1[32]);
                        log::info("INV -> opc1: " . $s1[33]);
                        log::info("INV -> opc2: " . $s1[34]);
                        log::info("INV -> opc3: " . $s1[35]);
                        log::info("INV -> precio2: " . $s1[36]);
                        log::info("INV -> precio3: " . $s1[37]);
                        log::info("INV -> precio4: " . $s1[38]);
                        log::info("INV -> precio5: " . $s1[39]);
                        log::info("INV -> vmd: " . $s1[40]);
                    }
                    if (strlen($codprod) == 0)
                        continue;
                    $contador++;
                    $inv = DB::table($tabla)
                        ->where('codprod', '=', $codprod)
                        ->first();
                    if ($inv) {
                        try {
                            DB::table($tabla)
                                ->where('codprod', '=', $codprod)
                                ->update(
                                    array(
                                        'barra' => $s1[0],
                                        'desprod' => QuitarCaracteres($s1[2]),
                                        'tipo' => $s1[3],
                                        'iva' => $s1[4],
                                        'regulado' => $regulado,
                                        'codprov' => QuitarCaracteres($codprov),
                                        'precio1' => $s1[7],
                                        'cantidad' => $s1[8],
                                        'bulto' => BuscarBulto($s1[0]),
                                        'da' => $s1[10],
                                        'oferta' => $s1[11],
                                        'upre' => $s1[12],
                                        'ppre' => $s1[13],
                                        'psugerido' => $s1[14],
                                        'pgris' => $s1[15],
                                        'nuevo' => "2",
                                        'fechafalla' => $s1[17],
                                        'tipocatalogo' => $s1[18],
                                        'cuarentena' => $s1[19],
                                        'dctoneto' => $s1[20],
                                        'lote' => $s1[21],
                                        'fecvence' => $s1[22],
                                        'marca' => LeerProdcaract($s1[0], 'marca', 'POR DEFINIR'),
                                        'pactivo' => QuitarCaracteres($s1[24]),
                                        'costo' => $s1[25],
                                        'ubicacion' => QuitarCaracteres($s1[26]),
                                        'descorta' => QuitarCaracteres($s1[27]),
                                        'codisb' => $s1[28],
                                        'feccatalogo' => date('Y-m-j H:i:s'),
                                        'categoria' => LeerProdcaract($s1[0], 'categoria', 'POR DEFINIR'),
                                        'molecula' => LeerProdcaract($s1[0], 'molecula', 'POR DEFINIR'),
                                        'subgrupo' => $s1[23],
                                        'opc1' => $s1[33],
                                        'opc2' => $s1[34],
                                        'opc3' => $s1[35],
                                        'precio2' => $s1[36],
                                        'precio3' => $s1[37],
                                        'precio4' => $s1[38],
                                        'precio5' => $s1[39],
                                        'vmd' => $s1[40]
                                    )
                                );
                        } catch (Exception $e) {
                            log::info("INV -> ERROR EN UPDATE CODPROD: " . $codprod . " - " . $e->getMessage());
                        }
                    } else {
                        try {
                            DB::table($tabla)->insert([
                                'barra' => $s1[0],
                                'codprod' => $codprod,
                                'desprod' => QuitarCaracteres($s1[2]),
                                'tipo' => $s1[3],
                                'iva' => $s1[4],
                                'regulado' => $regulado,
                                'codprov' => QuitarCaracteres($codprov),
                                'precio1' => $s1[7],
                                'cantidad' => $s1[8],
                                'bulto' => BuscarBulto($s1[0]),
                                'da' => $s1[10],
                                'oferta' => $s1[11],
                                'upre' => $s1[12],
                                'ppre' => $s1[13],
                                'psugerido' => $s1[14],
                                'pgris' => $s1[15],
                                'nuevo' => "1",
                                'fechafalla' => $s1[17],
                                'tipocatalogo' => $s1[18],
                                'cuarentena' => $s1[19],
                                'dctoneto' => $s1[20],
                                'lote' => $s1[21],
                                'fecvence' => $s1[22],
                                'marca' => LeerProdcaract($s1[0], 'marca', 'POR DEFINIR'),
                                'pactivo' => QuitarCaracteres($s1[24]),
                                'costo' => $s1[25],
                                'ubicacion' => QuitarCaracteres($s1[26]),
                                'descorta' => QuitarCaracteres($s1[27]),
                                'codisb' => $s1[28],
                                'feccatalogo' => date('Y-m-j H:i:s'),
                                'categoria' => LeerProdcaract($s1[0], 'categoria', 'POR DEFINIR'),
                                'molecula' => LeerProdcaract($s1[0], 'molecula', 'POR DEFINIR'),
                                'subgrupo' => $s1[23],
                                'opc1' => $s1[33],
                                'opc2' => $s1[34],
                                'opc3' => $s1[35],
                                'precio2' => $s1[36],
                                'precio3' => $s1[37],
                                'precio4' => $s1[38],
                                'precio5' => $s1[39],
                                'vmd' => $s1[40]
                            ]);
                        } catch (Exception $e) {
                            log::info("INV -> ERROR EN UPDATE CODPROD: " . $codprod . " - " . $e->getMessage());
                        }
                    }
                } catch (\Exception $e) {
                    log::info("INV -> ERROR EN CODPROD: " . $codprod . " - " . $e->getMessage());
                }
            }
            @unlink($origen);
            @unlink($destino);
            DB::table($tabla)->where('nuevo', '=', '3')->delete();
            DB::table($tabla)->update(array('feccatalogo' => date('Y-m-j H:i:s')));
            log::info("INV -> ** TABLA=" . $tabla . " REG=" . $contador . " **");
            return 1;
        }
    } catch (Exception $e) {
        log::info("INV -> ERROR GENERAL UPLOAD: " . $e->getMessage());
    }
}
function Descomprimir($origen, $destino)
{
    // REDUNDANTE
    $resp = FALSE;
    $x = 0;
    while ($x < 3) {
        if (function_exists('gzwrite')) {
            if (file_exists($origen)) {
                try {
                    $string = implode("", gzfile($origen));
                    $fp = fopen($destino, "w");
                    fwrite($fp, $string, strlen($string));
                    fclose($fp);
                    @unlink($origen);
                    $resp = TRUE;
                    break;
                } catch (Exception $e) {
                    log::info("CD -> ERROR: " . $e->getMessage());
                }
            }
            $x++;
        }
    }
    return $resp;
}
?>
