@extends('layouts.menu')
@section('contenido')
@php
    $moneda = Session::get('moneda', 'BSS');
    $mayorInv = 0;
    $costoprom = 0.00;
    $consolida = 0;
    $contprov = 0;
    $factor = RetornaFactorCambiario("", $moneda);
    $rutalogoprov = 'http://isaweb.isbsistemas.com/public/storage/prov/';
@endphp

<div class="row">
    <div class="col-md-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" data-toggle="tab">BASICA</a></li>
              @if (Auth::user()->tipo != 'P')
                  <li><a href="#tab_2" data-toggle="tab">PROVEEDORES</a></li>
                  <li><a href="#tab_3" data-toggle="tab">INVENTARIO</a></li>
                  @if (count($pedren) > 0)
                    <li><a href="#tab_4" data-toggle="tab">COMPRAS</a></li>
                  @endif
                  @if (Auth::user()->mostrarCatIsaBuscar == 1)
                    <li><a href="#tab_5" data-toggle="tab">ISABUSCAR</a></li>
                  @endif
              @endif
              <li class="pull-right"><a href="{{url('/')}}" class="text-muted">
                <i class="fa fa-times"></i></a>
              </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1">

                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <center>

                            <img src="http://isaweb.isbsistemas.com/public/storage/prod/{{NombreImagen($barra)}}" 
                            class="img-responsive" 
                            alt="icompras360" 
                            style="padding-top: 10px; border: 2px solid #D2D6DE;"
                            alt="icompra" 
                            width="100%" 
                            height="100%"
                            oncontextmenu="return false">

                            </center>
                        </div>
                   
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <div class="form-group">
                                <label>Referencia</label>
                                <span class="form-control" 
                                    style="color: #000000; background: #F7F7F7;">
                                    <i class="fa fa-barcode" aria-hidden="true"></i>
                                    &nbsp;{{$barra}} 
                                </span>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label>Marca</label>
                                @if (isset($tpmaestra->marca))
                                    <span class="form-control" 
                                        style="color: #000000; background: #F7F7F7;">
                                        <i class="fa fa-shield" aria-hidden="true"></i>
                                        &nbsp;{{$tpmaestra->marca}}
                                    </span>
                                @else
                                    @if (isset($inv->marca))
                                        <span class="form-control" 
                                            style="color: #000000; background: #F7F7F7;">
                                            <i class="fa fa-shield" aria-hidden="true"></i>
                                            &nbsp;{{$inv->marca}}
                                        </span>
                                    @else
                                        <span class="form-control" 
                                            style="color: #000000; background: #F7F7F7;">
                                            <i class="fa fa-shield" aria-hidden="true"></i>
                                            &nbsp;{{$tcmaestra->marca}}
                                        </span>
                                    @endif
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label>Categoria</label>
                                @if (isset($tpmaestra->categoria))
                                    <input readonly  type="text" class="form-control" value="{{$tpmaestra->categoria}}" style=" color: #000000; background: #F7F7F7;" >
                                @else
                                    @if (isset($inv->categoria))
                                        <input readonly  type="text" class="form-control" value="{{$inv->categoria}}" style=" color: #000000; background: #F7F7F7;" >
                                    @else
                                        <input readonly  type="text" class="form-control" value="{{$tcmaestra->categoria}}" style=" color: #000000; background: #F7F7F7;" >
                                    @endif
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <label>Descripción</label><b>
                                @if (isset($tpmaestra->desprod))
                                    <input readonly  
                                        type="text" 
                                        class="form-control" 
                                        value="{{$tpmaestra->desprod}}" 
                                        style="color: #000000; background: #F7F7F7;" >
                                @else
                                    @if (isset($inv->desprod))
                                        <input readonly  
                                            type="text" 
                                            class="form-control" 
                                            value="{{$inv->desprod}}" 
                                            style="color: #000000; background: #F7F7F7;" >
                                    @else
                                        <input readonly  
                                            type="text" 
                                            class="form-control" 
                                            value="{{isset($tcmaestra->desprod)}}" 
                                            style="color: #000000; background: #F7F7F7;" >
                                    @endif
                                @endif</b>
                            </div>
                        </div>

                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <label>Principio activo</label>
                                @if (isset($tpmaestra->pactivo))
                                    <input readonly  type="text" 
                                        class="form-control" 
                                        value="{{$tpmaestra->pactivo}}" 
                                        style="color: #000000; background: #F7F7F7;" >
                                @else
                                    @if (isset($inv->pactivo))
                                        <input readonly  
                                            type="text" 
                                            class="form-control" 
                                            value="{{$inv->pactivo}}" 
                                            style=" color: #000000; background: #F7F7F7;" >
                                    @else
                                        <input readonly  
                                            type="text" 
                                            class="form-control" 
                                            value="{{isset($tcmaestra->activo)}}" 
                                            style=" color: #000000; background: #F7F7F7;" >
                                    @endif
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <div class="form-group">
                                <label>Iva</label>
                                @if (isset($tpmaestra->iva))
                                    <input readonly  type="text" class="form-control" value="{{number_format($tpmaestra->iva,2, '.', ',')}}" style="color: #000000; background: #F7F7F7; text-align: right;" >
                                @else
                                    @if (isset($tcmaestra->iva))
                                        <input readonly  type="text" class="form-control" value="{{number_format($tcmaestra->iva,2, '.', ',')}}" style="color: #000000; background: #F7F7F7; text-align: right;" >
                                    @else
                                        <input readonly  type="text" class="form-control" value="{{number_format($inv->iva,2, '.', ',')}}" style="color: #000000; background: #F7F7F7; text-align: right;" >
                                    @endif
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <div class="form-group">
                                <label>Bulto</label>
                                @if (isset($tpmaestra->bulto))
                                    <input readonly  type="text" class="form-control" value="{{number_format($tpmaestra->bulto,0, '.', ',')}}" style="color: #000000; background: #F7F7F7; text-align: right;" >
                                @else
                                    @if (isset($inv->bulto))
                                        <input readonly  type="text" class="form-control" value="{{$inv->bulto}}" style="color: #000000; background: #F7F7F7; text-align: right;" >
                                    @else
                                        <input readonly  type="text" class="form-control" value="{{$tcmaestra->bulto}}" style="color: #000000; background: #F7F7F7; text-align: right;" >
                                    @endif
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <div class="form-group">
                                <label>Tipo</label>
                                @if (isset($tpmaestra->tipo))
                                    <input readonly  type="text" class="form-control" value="{{$tpmaestra->tipo}}" style=" color: #000000; background: #F7F7F7;" >
                                @else
                                    @if (isset($inv->tipo))
                                        <input readonly  type="text" class="form-control" value="{{$inv->tipo}}" style=" color: #000000; background: #F7F7F7;" >
                                    @else
                                        <input readonly  type="text" class="form-control" value="M" style=" color: #000000; background: #F7F7F7;" >
                                    @endif
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <div class="form-group">
                                <label>Regulado</label>
                                @if (isset($tpmaestra->regulado))
                                    <input readonly  type="text" class="form-control" value="{{$tpmaestra->regulado}}" style=" color: #000000; background: #F7F7F7;" >
                                @else
                                    @if (isset($tpmaestra->regulado))
                                        <input readonly  type="text" class="form-control" value="{{$inv->regulado}}" style=" color: #000000; background: #F7F7F7;" >
                                    @else
                                        <input readonly  type="text" class="form-control" value="N" style=" color: #000000; background: #F7F7F7;" >
                                    @endif
                                @endif
                            </div>
                        </div>
 
                    </div>
                </div>
                <div class="tab-pane" id="tab_2">
                    <div class="table-responsive">
                        <table 
                            class="table table-striped table-bordered table-condensed table-hover">
                            <thead class="colorTitulo">
                                <th title="Orden de preferencia">#</th>
                                <th style="width: 60px;">
                                    &nbsp;&nbsp;&nbsp;&nbsp;IMAGEN&nbsp;&nbsp;&nbsp;&nbsp;
                                </th>
                                <th>NOMBRE</th>
                                <th>PRODUCTO</th>
                                <th style="display:none;">CODIGO</th>
                                <th>PRECIO</th>
                                <th title="Descuento Adicional">DA</th>
                                <th title="Descuento Internet">DI</th>
                                <th title="Descuento Comercial">DC</th>
                                <th title="Descuento pronto pago">PP</th>
                                <th>IVA</th>
                                <th>LIQUIDA</th>
                                <th title="CANTIDAD">CANT</th>
                                <th>LOTE</th>
                                <th>VENCE</th>
                                <th>RANKING</th>
                                <th>ACTUALIZADO</th>
                            </thead>

                            @foreach ($provs as $prov)
                                @php
                                    $menor = 100000000000000;
                                    $mayorInv = 0;
                                    $costoprom = 0.00;
                                    $consolida = 0;
                                    $contprov = 0;
                                    $arrayRnk = [];
                                    $entra = FALSE;
                                    foreach ($provs as $prov) { 
                                        $codprove = strtolower($prov->codprove);
                                        $factor = RetornaFactorCambiario($codprove, $moneda);
                                        try {
                                            $campos = $tpmaestra->$codprove;
                                            $campo = explode("|", $campos);
                                        } catch (Exception $e) {
                                            continue;
                                        }
                                        $precio = $campo[0];
                                        $cantidad = $campo[1];
                                        $confprov = LeerProve($codprove); 
                                        if (is_null($confprov))
                                            continue;
                                        $tipoprecio = $prov->tipoprecio;
                                        switch ($tipoprecio) {
                                            case 1:
                                                $precio = $campo[0]/$factor;
                                                $precioBs = $campo[0];
                                                break;
                                            case 2:
                                                $precio = $campo[5]/$factor;
                                                $precioBs = $campo[5];
                                                break;
                                            case 3:
                                                $precio = $campo[6]/$factor;
                                                $precioBs = $campo[6];
                                                break;
                                            default:
                                                $precio = $campo[0]/$factor;
                                                $precioBs = $campo[0];
                                                break;
                                        }
                                        if ($cantidad > 0) {
                                           $entra = TRUE; 
                                           $dc = $prov->dcme;
                                           $di = $prov->di;
                                           $pp = $prov->ppme;
                                           $da = 0.00;
                                           if ($tipoprecio == $confprov->aplicarDaPrecio) {
                                              $da = $campo[2];
                                           }
                                           $neto = CalculaPrecioNeto($precio, $da, $di, $dc, $pp, 0.00);
                                           $liquida = $neto + (($neto * $tpmaestra->iva)/100);

                                           $netoBs = CalculaPrecioNeto($precioBs, $da, $di, $dc, $pp, 0.00);
                                           $liquidaBs = $netoBs + (($netoBs * $tpmaestra->iva)/100);

                                           $arrayRnk[] = [
                                                'liquida' => $liquida,
                                                'codprove' => $prov->codprove,
                                                'liquidaBs' => $liquidaBs
                                           ];
                                           $consolida += $cantidad;
                                           $costoprom += $liquida;
                                           $contprov++;
                                           if ($liquida < $menor) {
                                                $menor=$liquida; 
                                           }
                                           if ($cantidad > $mayorInv) {
                                                $mayorInv = $cantidad;          
                                           }
                                        }
                                    }
                                    if ($entra == FALSE) 
                                        continue;
                                    $aux = array();
                                    foreach ($arrayRnk as $key => $row) {
                                        $aux[$key] = $row['liquida'];
                                    }
                                    if (count($aux) > 1)
                                        array_multisort($aux, SORT_ASC, $arrayRnk);
                                @endphp
                            @endforeach
                            
                            @foreach ($provs as $prov)
                                @php 

                                $codprove = strtolower($prov->codprove);
                                $factor = RetornaFactorCambiario($codprove, $moneda);
                                $confprov = LeerProve($codprove); 
                                if (is_null($confprov))
                                    continue;
                                try {
                                    $campos = $tpmaestra->$codprove;
                                    $campo = explode("|", $campos);
                                    $cantidad = $campo[1];
                                    if ($cantidad <= 0)
                                        continue;

                                    $dc = $prov->dcme;
                                    $di = $prov->di;
                                    $pp = $prov->ppme;
                                    $tipoprecio = $prov->tipoprecio;
                                    switch ($tipoprecio) {
                                        case 1:
                                            $precio = $campo[0]/$factor;
                                            break;
                                        case 2:
                                            $precio = $campo[5]/$factor;
                                            break;
                                        case 3:
                                            $precio = $campo[6]/$factor;
                                            break;
                                        default:
                                            $precio = $campo[0]/$factor;
                                            break;
                                    }
                                    $da = 0.00;
                                    if ($tipoprecio == $confprov->aplicarDaPrecio) {
                                         $da = $campo[2];
                                    }
                                    $codprod = $campo[3];
                                    $desprod = $campo[9];
                                    $lote = $campo[7];
                                    $vence = $campo[8];
                                    $vence = str_replace("12:00:00 AM", "", $vence);
                                    $neto = CalculaPrecioNeto($precio, $da, $di, $dc, $pp, 0.00);
                                    $liquida = $neto + (($neto * $tpmaestra->iva)/100);
                                    $ranking = obtenerRanking($liquida, $arrayRnk);
                                    $fechaHoy = trim(date("Y-m-d"));
                                    $fechaCat = trim(date('Y-m-d', strtotime($confprov->fechacata)));
                                } catch (Exception $e) {
                                    continue;
                                }
                                @endphp

                                <tr>
                                    <td style="background-color: {{$confprov->backcolor}}; 
                                        color: {{$confprov->forecolor}}; ">
                                        {{$prov->preferencia}}
                                    </td>
                                    <td>
                                        <div align="center">
                                            <a href="{{URL::action('ProveedorController@verprov',$prov->codprove)}}">
                                                <img src="http://isaweb.isbsistemas.com/public/storage/prov/{{$prov->rutalogo1}}" 
                                                class="img-responsive" 
                                                alt="icompras360" 
                                                style="width: 100px; 
                                                border: 2px solid #D2D6DE;"
                                                oncontextmenu="return false">
                                            </a>
                                        </div>
                                        <div style="background-color: {{$prov->backcolor}}; 
                                            color: {{$prov->forecolor}};
                                            padding-left: 5px; height: 18px; font-size: 12px;">
                                            {{$prov->codprove}}
                                        </div>
                                    </td>
                                    <td>{{$confprov->descripcion}}</td>
                                    <td>
                                        <b>{{$desprod}}</b><br>
                                        <span title="CODIGO DEL PRODUCTO">
                                            <i class="fa fa-cube">
                                                {{$codprod}}
                                            </i><br>
                                        </span>
                                    </td>
                                    <td style="display:none;">{{$codprod}}</td>
                                    <td align='right'>{{number_format($precio, 2, '.', ',')}}</td>  
                                    <td align='right'>{{number_format($da, 2, '.', ',')}}</td>  

                                    <td align='right'>{{number_format($di, 2, '.', ',')}}</td>  
                                    <td align='right'>{{number_format($dc, 2, '.', ',')}}</td>  
                                    <td align='right'>{{number_format($pp, 2, '.', ',')}}</td>  
                           
                                    <td align='right'>{{number_format($tpmaestra->iva, 2, '.', ',')}}</td>  
                                    @if ($menor == $liquida)
                                        <td align='right' 
                                            style="color: black; font-size: 18px;">
                                            <b>{{number_format($liquida, 2, '.', ',')}}</b>
                                        </td>  
                                    @else
                                        <td align='right'>{{number_format($liquida, 2, '.', ',')}}</td>  
                                    @endif
                                    @if ($mayorInv == $cantidad)
                                        <td align='right' 
                                            style="color: black; font-size: 18px;">
                                            <b>{{number_format($cantidad, 0, '.', ',')}}</b>
                                        </td>
                                    @else
                                        <td align='right'>
                                            {{number_format($cantidad, 0, '.', ',')}}
                                        </td>
                                    @endif
                                    <td>{{$lote}}</td>
                                    <td>{{$vence}}</td>
                                    <td>{{$ranking}}</td>
                                    <td @if ($fechaCat != $fechaHoy) style="color: red" @endif >
                                        {{date('d-m-Y H:i', strtotime($confprov->fechacata))}}
                                    </td>
                                </tr>
                            @endforeach

                        </table>
                    </div>

                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Precio promedio</label>
                                <input readonly  type="text" class="form-control" value="{{number_format($costoprom/ ( ($contprov == 0) ?  1 : $contprov ),2, '.', ',')}}" style="color: #000000; background: #F7F7F7; text-align: right;" >
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Consolidado</label>
                                <input readonly  type="text" class="form-control" value="{{number_format($consolida,0, '.', ',')}}" style="color: #000000; background: #F7F7F7; text-align: right;" >
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tab_3">
                    <div class="table-responsive">
                        <table 
                            class="table table-striped table-bordered table-condensed table-hover">
                            <thead class="colorTitulo">
                                <th title="Orden de preferencia">#</th>
                                <th style="width: 60px;">
                                    &nbsp;&nbsp;&nbsp;&nbsp;IMAGEN&nbsp;&nbsp;&nbsp;&nbsp;
                                </th>
                                <th>NOMBRE</th>
                                <th>PRODUCTO</th>
                                <th style="display:none;">CODIGO</th>
                                <th>COSTO</th>
                                <th>PRECIO</th>
                                <th>INVENTARIO</th>
                                <th>TRANSITO</th>
                                <th>VMD</th>
                                <th>ACTUALIZADO</th>
                            </thead>

                            @php 
                                $contcli = 0;
                                $costoprom = 0.00;
                                $precioprom = 0.00;
                                $invgrupo = 0;
                                $invtran = 0;
                                $factor = RetornaFactorCambiario("", $moneda);
                            @endphp

                            @if ($tipo == "C")
                                @php 
                                $inv = verificarProdInventario($barra, $codcli); 
                                $confcli = LeerCliente($codcli); 
                                $contcli = 1;
                                $costoprom = 0.00;
                                $precioprom = 0.00;
                                $invgrupo = 0;
                                $invtran = 0;
                                if ($inv) { 
                                    $contcli = 1;
                                    $costoprom = $inv->costo/$factor;
                                    $precioprom = $inv->precio1/$factor;
                                    $invgrupo = $inv->cantidad;
                                    $invtran = verificarProdTransito($barra, $codcli, "");
                                    $fechaHoy = trim(date("Y-m-d"));
                                    $fechaInv = trim(date('Y-m-d', strtotime($inv->feccatalogo)));
                                }
                                @endphp
                                @if ($inv)
                                <tr>
                                    <td style="background-color: {{$confcli->backcolor}}; 
                                        color: {{$confcli->forecolor}}; ">
                                        01
                                    </td>
                                    <td >
                                        <div align="center">
                                            <img src="http://isaweb.isbsistemas.com/public/storage//{{$cliente->rutaimg}}" 
                                            class="img-responsive" 
                                            alt="icompras360" 
                                            width="100%"
                                            style="border: 2px solid #D2D6DE;"
                                            oncontextmenu="return false">
                                        </div>
                                        <div style="background-color: {{$confcli->backcolor}}; 
                                            color: {{$confcli->forecolor}};
                                            padding-left: 5px; height: 18px; font-size: 12px;">
                                            {{$confcli->codcli}}
                                        </div>
                                    </td>
                                    <td>{{$cliente->nombre}}</td>
                                    <td>
                                        <b>{{$inv->desprod}}</b><br>
                                        <span title="CODIGO DEL PRODUCTO">
                                            <i class="fa fa-cube">
                                                {{$inv->codprod}}
                                            </i><br>
                                        </span>
                                    </td>
                                    <td style="display:none;">{{$inv->codprod}}</td>
                                    <td align='right'>
                                        {{number_format($costoprom, 2, '.', ',')}}
                                    </td> 
                                    <td align='right'>
                                        {{number_format($precioprom, 2, '.', ',')}}
                                    </td>  
                                    <td align='right'>
                                        {{number_format($invgrupo,0, '.', ',')}}
                                    </td>  
                                    <td align='right'>
                                        {{number_format($invtran,0, '.', ',')}}
                                    </td>  
                                    <td align='right'>
                                        {{number_format($inv->vmd, 4, '.', ',')}}
                                    </td> 
                                    <td @if ($fechaInv != $fechaHoy) style="color: red" @endif >
                                        {{date('d-m-Y H:i', strtotime($inv->feccatalogo))}}
                                    </td>
                                 </tr>   
                                 @endif
                            @else
                                @foreach ($grupo as $gr)
                                    @php
                                    $codcli = $gr->codcli;
                                    $inv = verificarProdInventario($barra, $codcli);
                                    if ($inv == null)
                                        continue;
                                    $fechaHoy = trim(date("Y-m-d"));
                                    $fechaInv = trim(date('Y-m-d', strtotime($inv->feccatalogo)));
                                    $confcli = LeerCliente($codcli); 
                                    $contcli++;
                                    $costoprom += floatval($inv->costo)/$factor;
                                    $precioprom += $inv->precio1/$factor;
                                    $invgrupo += $inv->cantidad;
                                    $invtransito = verificarProdTransito($barra, $codcli, "");
                                    $invtran += $invtransito;
                                    $cliente = DB::table('maecliente')
                                    ->where('codcli','=',$codcli)
                                    ->first();
                                    @endphp

                                    @if ($inv)
                                    <tr>
                                        <td style="background-color: {{$confcli->backcolor}}; 
                                            color: {{$confcli->forecolor}}; ">
                                            {{$gr->preferencia}}
                                        </td>
                                        <td class="hidden-xs">
                                            <div align="center">
                                                <img src="http://isaweb.isbsistemas.com/public/storage//{{$cliente->rutaimg}}" 
                                                class="img-responsive" 
                                                alt="icompras360" 
                                                width="100%"
                                                style="border: 2px solid #D2D6DE;"
                                                oncontextmenu="return false">
                                            </div>
                                            <div style="background-color: {{$confcli->backcolor}}; 
                                                color: {{$confcli->forecolor}};
                                                padding-left: 5px; height: 18px; font-size: 12px;">
                                                {{$confcli->codcli}}
                                            </div>
                                        </td>
                                        <td>{{$gr->nomcli}}</td>
                                        <td>
                                            <b>{{$inv->desprod}}</b><br>
                                            <span title="CODIGO DEL PRODUCTO">
                                                <i class="fa fa-cube">
                                                    {{$inv->codprod}}
                                                </i><br>
                                            </span>
                                        </td>
                                        <td style="display:none;">{{$inv->codprod}}</td>
                                        <td align='right'>
                                            {{number_format($inv->costo/$factor, 2, '.', ',')}}
                                        </td> 
                                        <td align='right'>
                                            {{number_format($inv->precio1/$factor, 2, '.', ',')}}
                                        </td>  
                                        <td align='right'>
                                            {{number_format($inv->cantidad,0, '.', ',')}}
                                        </td>  
                                        <td align='right'>
                                            {{number_format($invtransito,0, '.', ',')}}
                                        </td>  
                                        <td align='right'>
                                            {{number_format($inv->vmd, 4, '.', ',')}}
                                        </td> 
                                        <td @if ($fechaInv != $fechaHoy) style="color: red" @endif >
                                            {{date('d-m-Y H:i', strtotime($inv->feccatalogo))}}
                                        </td>
                                     </tr>   
                                     @endif
                                @endforeach
                            @endif

                        </table>
                    </div>

                    @if ($contcli > 1)
                    <div class="row">

                        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Costo promedio</label>
                                <input readonly  type="text" class="form-control" value="{{number_format($costoprom/$contcli,2, '.', ',')}}" style="color: #000000; background: #F7F7F7; text-align: right;" >
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Precio promedio</label>
                                <input readonly  type="text" class="form-control" value="{{number_format($precioprom/$contcli,2, '.', ',')}}" style="color: #000000; background: #F7F7F7; text-align: right;" >
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Inventario grupo</label>
                                <input readonly  type="text" class="form-control" value="{{number_format($invgrupo,0, '.', ',')}}" style="color: #000000; background: #F7F7F7; text-align: right;" >
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Transito grupo</label>
                                <input readonly  type="text" class="form-control" value="{{number_format($invtran,0, '.', ',')}}" style="color: #000000; background: #F7F7F7; text-align: right;" >
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="tab-pane" id="tab_4">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-condensed table-hover">
                            <thead class="colorTitulo">
                                <th>#</th>
                                <th>PRODUCTO</th>
                                <th>CANTIDAD</th>
                                <th>COSTO</th>
                                <th>SUBTOTAL</th>
                                <th style="display:none;">PROVEEDOR</th>
                                <th>PEDIDO</th>
                                <th>FECHA</th>
                                <th>FACTOR</th>
                            </thead>
                            @foreach ($pedren as $pr)
                            @php 
                                $confprov = LeerProve($pr->codprove); 
                                if (is_null($confprov))
                                    continue;

                                $factor = RetornaFactorCambiario("", $moneda);
                                $pedidox = DB::table('pedido')
                                ->where('id','=',$pr->id)
                                ->first();
                                if ($pedidox) {
                                    if ($moneda != "BSS")
                                        $factor = $pedidox->factor; 
                                }
                            @endphp
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>
                                    <b>{{$pr->desprod}}</b><br>
                                    <span title="CODIGO DEL PROVEEDOR">
                                        <img style="width: 20px; height: 20px;" 
                                        src="{{$rutalogoprov.$confprov->rutalogo1}}" 
                                        alt="icompras360">
                                        &nbsp;{{$pr->codprove}}<br>
                                    </span>

                                    <span style="margin-left: 3px;" title="CODIGO DEL PRODUCTO">
                                        <i  class="fa fa-cube">
                                            {{$pr->codprod}}
                                        </i><br>
                                    </span>
                                </td>
                                <td align='right'>
                                    {{number_format($pr->cantidad, 0, '.', ',')}}
                                </td> 
                                <td align='right'>
                                    {{number_format($pr->neto/$factor, 2, '.', ',')}}
                                </td> 
                                <td align='right'>
                                    {{number_format($pr->subtotal/$factor, 2, '.', ',')}}
                                </td> 
                                <td style="display:none;">
                                    <img alt="icompras360"
                                        style="width: 28px; 
                                        float: left; 
                                        background-color: #F0F0F0; 
                                        margin-left: 2px;
                                        margin-top: 4px;"
                                        src="{{$rutalogoprov.$confprov->rutalogo1}}">
                                    &nbsp;&nbsp;{{$pr->codprove}}
                                </td>
                                <td>{{$pr->id}}</td>
                                <td>
                                    {{date('d-m-Y H:i', strtotime($pr->fecenviado))}}
                                </td>
                                <td align='right'>
                                    {{number_format($factor, 2, '.', ',')}}
                                </td> 
                            </tr>   
                            @endforeach
                        </table>
                    </div>
                </div>
                <div class="tab-pane" id="tab_5">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-condensed table-hover">
                            <thead class="colorTitulo">
                                <th>NOMBRE</th>
                                <th>PRODUCTO</th>
                                <th>CODIGO</th>
                                <th>PRECIO</th>
                                <th>CANTIDAD</th>
                                <th>ACTUALIZADO</th>
                            </thead>
                            @php
                            $factor = RetornaFactorCambiario("", $moneda);
                            @endphp
                            @foreach ($maecatalogo as $mc)
                                @php  
                                $codcli = $mc->campo9;
                                $cli = DB::table('maecliente')
                                ->where('codcli','=',$codcli)
                                ->first();
                                @endphp
                                @if ($cli)
                                <tr>
                                    <td> {{$cli->nombre}} </td>
                                    <td> {{$mc->desprod}} </td>
                                    <td> {{$mc->codprod}} </td>
                                    <td align='right'>
                                       {{number_format(fGetfloat($mc->precio)/$factor,2, '.', ',')}}
                                    </td>  
                                    <td align='right'> {{$mc->cantidad}} </td>  
                                    <td> {{$mc->campo10}} </td>
                                 </tr>   
                                 @endif
                            @endforeach

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-md-12">
    <button type="button" class="btn-normal" onclick="history.back(-1)">Regresar</button>
</div>
@push ('scripts')
<script>
$('#subtitulo').text('{{$subtitulo}}');
</script>
@endpush
@endsection

