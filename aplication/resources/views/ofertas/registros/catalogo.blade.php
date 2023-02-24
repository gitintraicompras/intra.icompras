@extends ('layouts.menu')
@section ('contenido')
@php 
$moneda = Session::get('moneda', 'BSS');
$factor = RetornaFactorCambiario($cliente->codcli, $moneda);
@endphp 
<div class="btn-toolbar" role="toolbar" style="margin-top: 12px; margin-bottom: 3px;">
    <div class="btn-group" role="group" style="width: 100%;">
        @include('ofertas.registros.catasearch')
        <a href="{{url('/ofertas/sugofertas')}}">
            <button style="margin-right: 3px; height: 35px; " 
                type="button" 
                data-toggle="tooltip" 
                title="Crear sugerido de ofertas de productos para el gestor de Ofertas" 
                class="btn-confirmar">
                Crear
            </button>
        </a>  
    </div>
</div>
<div class="row" 
    style="width: 100%; 
    float: left; 
    margin: 0px;
    padding: 0px;
    height: 38px;">
    <table class="table table-striped table-bordered table-condensed table-hover">
        <tr>
            <td style="width: 100px;
                margin: 10px;">
                UTILIDAD MINIMA                
            </td>
            <td style="background-color: #b7b7b7; 
                color: #000000;
                width: 50px;
                margin: 10px;"
                align="right" >
                {{number_format($cliente->utilm, 2, '.', ',')}}       
            </td> 
            <td align="right"
                style="width: 70px;
                margin: 10px;">
                DA MINIMO                
            </td> 
            <td style="background-color: #b7b7b7; 
                color: #000000;
                width: 50px;
                margin: 10px;"
                align="right" >
                {{number_format($cliente->damin, 2, '.', ',')}}                
            </td> 
            <td align="right"
                style="width: 70px;
                margin: 10px;">
                DA MAXIMO                
            </td> 
            <td style="background-color: #b7b7b7; 
                color: #000000;
                width: 50px;
                margin: 10px;" 
                align="right">
                {{number_format($cliente->damax, 2, '.', ',')}} 
            </td>
            <td align="right"
                style="width: 30px;
                margin: 10px;">
                DC                
            </td>
            <td style="background-color: #b7b7b7; 
                color: #000000;
                width: 40px;
                margin: 10px;" 
                align="right">
                {{number_format($cliente->dc, 2, '.', ',')}} 
            </td> 
            <td align="right"
                style="width: 30px;
                margin: 10px;">
                DI                
            </td>
            <td style="background-color: #b7b7b7; 
                color: #000000;
                width: 40px;
                margin: 10px;" 
                align="right">
                {{number_format($cliente->di, 2, '.', ',')}} 
            </td> 
            <td align="right"
                style="width: 30px;
                margin: 10px;">
                PP                
            </td>
            <td style="background-color: #b7b7b7; 
                color: #000000;
                width: 40px;
                margin: 10px;" 
                align="right">
                {{number_format($cliente->pp, 2, '.', ',')}} 
            </td> 
        </tr>            
    </table>
</div>

<!-- TABLA -->
<div class="row" style="margin-top: 5px;">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

        <div class="table-responsive">

            <table id="idTabla" class="table table-striped table-bordered table-condensed table-hover" >

                <thead style="background-color: #b7b7b7;">
                    <th colspan="14">
                        <center>CATALOGO MAESTRO DE PRODUCTOS</center>
                    </th>
                    @foreach ($provs as $prov)

                        @php 
                        if (!VerificaCampoTabla('tpmaestra', $prov->codprove))
                            continue;
                        $confprov = LeerProve($prov->codprove); 
                        if (is_null($confprov))
                            continue;
                        $fechaHoy = trim(date("Y-m-d"));
                        $fechaCat = trim(date('Y-m-d', strtotime($confprov->fechacata)));
                        @endphp
                        
                        <th colspan="2" style="background-color: {{$confprov->backcolor}}; color: {{$confprov->forecolor}}; width: 400px; word-wrap: break-word; ">
                            <a href="{{URL::action('ProveedorController@verprov',$prov->codprove)}}">
                                <center>
                                    <button type="button" 
                                        data-toggle="tooltip" 
                                        title="{{strtoupper($confprov->nombre)}} &#10 ({{ date('d-m-Y H:i', strtotime($confprov->fechacata) ) }})" 
                                        style="background-color: {{$confprov->backcolor}};
                                        @if ($fechaCat != $fechaHoy)
                                            color: red;
                                        @else 
                                            color: {{$confprov->forecolor}}; 
                                        @endif
                                        width: 100%;">
                                        {{$confprov->descripcion}}
                                    </button>
                                </center>
                            </a>
                        </th>
                    @endforeach
                </thead>

                <thead style="background-color: #b7b7b7;">
                    <th>#</th>
                    <th style="width: 100px;">&nbsp;&nbsp;&nbsp;IMAGEN&nbsp;&nbsp;&nbsp;</th>
                    <th>DESCRIPCION</th>
                    <th>CODIGO</th>
                    <th>REFERENCIA</th>
                    <th>MARCA</th>
                    <th style="background-color: #FEE3CB;" title="Unidades del inventario del cliente">
                        INV.
                    </th>
                    <th style="background-color: #FED7B2;" title="Unidades consolidadas de la competencia">
                        C(PROV)
                    </th>
                    <th style="background-color: #FECC9E;">COSTO</th>
                    <th style="background-color: #FDBF87;"
                        title="Porcentaje de Utilidad">
                        UTIL(%)
                    </th>
                    <th style="background-color: #FEB370;">PRECIO({{$cliente->usaprecio}})</th>
                    <th style="background-color: #FEA95C;"
                        title="Porcentaje de Descuento Adicional Actual y eL porcentaje de Oferta">
                        DA(%)
                    </th>
                    <th style="background-color: #FD9E46;
                        color: #ffffff;">
                        PS
                    </th>
                    <th title="Mejor precio de la competencia" 
                        style="background-color: #FE9232; 
                               width: 120px;
                               color: #ffffff;">
                               MPC
                    </th>
                    @foreach ($provs as $prov)
                        @php 
                        if (!VerificaCampoTabla('tpmaestra', $prov->codprove))
                            continue;
                        $confprov = LeerProve($prov->codprove); 
                        if (is_null($confprov))
                            continue;
                        @endphp
                        <th style="background-color: {{$confprov->backcolor}}; color: {{$confprov->forecolor}}; width: 200px; word-wrap: break-word; ">
                            PRECIO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        </th> 
                        <th style="background-color: {{$confprov->backcolor}}; color: {{$confprov->forecolor}}; width: 200px; word-wrap: break-word; ">
                            CANTIDAD
                        </th>
                    @endforeach
                </thead>

                @php
                $iFila = 1;
                $invent = 'inventario_'.$codcli;
                @endphp
                @foreach ($sugoferen as $sug)
                    @php

                    $cat = DB::table('tpmaestra as tpm')
                    ->select('*', 'tpm.desprod as descrip')
                    ->join($invent.' as tcm', 'tcm.barra','=', 'tpm.barra')
                    ->where('codisb','=',$codcli)
                    ->where('tpm.barra','=',$sug->barra)
                    ->first();
                    if (empty($cat))
                        continue;

                    $dataprod = obtenerColorProd($cat, $cliente, $provs);
                    $util = $dataprod['util'];
                    @endphp
                    <tr>
                        <td style="background-color: {{$dataprod['backcolor']}}; 
                            color: {{$dataprod['forecolor']}};"
                            title = "{{$dataprod['title']}}" >
                            {{$iFila++}}
                        </td>
                        <td style="width: 100px;">
                            <div align="center">
                                <a href="#">

                                    <img src="http://isaweb.isbsistemas.com/public/storage/prod/{{NombreImagen($cat->barra)}}" 
                                    class="img-responsive" 
                                    width="100%" 
                                    height="100%" 
                                    alt="icompras360" 
                                    style="border: 2px solid #D2D6DE;"
                                    oncontextmenu="return false">
                    
                                </a> 
                            </div>
                        </td>
                        <td>{{$cat->descrip}}</td>
                        <td>{{$cat->codprod}}</td>
                        <td>{{$cat->barra}}</td>
                        <td>{{$sug->marca}}</td>
                        <!-- INVENTARIO -->
                        <td align="right" 
                            Style="background-color: #FEE3CB;"
                            title="IMVENTARIO">
                            {{number_format($cat->cantidad, 0, '.', ',')}}
                        </td>

                        <!-- CONSOILIDADO  -->
                        <td align="right" 
                            style="background-color: #FED7B2;"
                            title="CONSOLIDADO DE LA COMPETENCIA">
                            {{number_format($dataprod['invConsol'], 0, '.', ',')}}
                        </td>

                        <!-- COSTO -->
                        <td align="right" 
                            style="background-color: #FECC9E;"
                            title="COSTO">
                            {{number_format($dataprod['costo']/$factor, 2, '.', ',')}}
                            @if ($dataprod['costo2'] > 0)
                                <br>
                                {{number_format($dataprod['costo2']/$factor, 2, '.', ',')}}
                            @endif
                        </td>

                        <!-- UTILIDAD -->
                        <td align="right" 
                            style="background-color: #FDBF87;
                            @if ($util < $cliente->utilm) color: red; @endif "
                            title="MARGEN DE UTILIDAD">
                            {{number_format($util, 2, '.', ',')}}%
                        </td>

                        <!-- PRECIO -->
                        <td align="right" 
                            style="background-color: #FEB370;"
                            title="PRECIO">
                            {{number_format($dataprod['precioInv']/$factor, 2, '.', ',')}}
                        </td>
                 
                        <!-- DA -->
                        <td style="padding: 0px;
                            height: 100%;
                            background-color: #EEEEEE;">
                            <div style="width: 100px;">
                                <span class="input-group-btn" 
                                      style="width: 100px;" >
                                    <div class="col-xs-12 input-group">
                                        <input style="text-align: right; 
                                            background-color: #FEA95C;
                                            color: #000000;
                                            height: 50%;
                                            font-size: 20px; 
                                            width: 100px;"
                                            readonly="" 
                                            value="{{number_format($sug->da, 0, '.', ',')}} %"
                                            class="form-control" >
                                    </div>
                                    <div class="col-xs-12 input-group" 
                                         id="2idAgregar-{{$iFila}}">
                                         <input style="text-align: right; 
                                            color: #000000; 
                                            font-size: 20px;
                                            height: 50%;
                                            width: 60px;" 
                                            readonly="" 
                                            id="idPedir-{{$iFila}}" 
                                            class="form-control" 
                                            value="{{number_format($sug->dasug, 0, '.', ',')}}"
                                                    >
                                         <button type="button" 
                                                 class="BtnAgregar btn btn-pedido" 
                                                 data-toggle="tooltip" 
                                                 style="font-size: 20px;
                                                 height: 50%;
                                                 text-align: center;"
                                                 id="idBtnAgregar-{{$iFila}}" 
                                                 disabled >
                                                 <span 
                                                    @if ($sug->dasug == $sug->da)
                                                        class="fa fa-times-circle-o"
                                                    @else
                                                        @if ($sug->dasug > $sug->da)
                                                            class="fa fa-thumbs-o-up"
                                                        @else
                                                            class="fa fa-thumbs-o-down"
                                                        @endif
                                                    @endif
                                                    aria-hidden="true">
                                                 </span>
                                         </button>
                                    </div>
                                </span>
                            </div>
                        </td>
     
                        <!-- PS -->
                        <td align="right" 
                            style="background-color: #FD9E46;
                            color: #ffffff;
                            font-size: 20px;
                            vertical-align: middle;
                            @if (($dataprod['precioSug']/$factor) > ($dataprod['mpcFactor'])) color: red; @endif"
                            title="PRECIO SUGERIDO">
                            {{number_format($dataprod['precioSug']/$factor, 2, '.', ',')}}
                        </td>

                        <!-- MPC -->
                        <td style="padding: 0px; 
                            height: 100%;
                            background-color: #FE9232;">
                            <div style="width: 100px; ">
                                <span class="input-group-btn" 
                                    style="width: 100px;
                                    height: 100%;">
                                    <div class="col-xs-12 input-group" >
                                        <input style="text-align: right; 
                                            width: 100px;
                                            color: #ffffff;
                                            height: 50%;
                                            font-size: 20px;
                                            background-color: #FE9232;"
                                            class="form-control"  
                                            readonly="" 
                                            value="{{number_format($dataprod['mpcFactor'], 2, '.', ',')}}" >
                                    </div>
                                    <div style="margin-left: 0px; 
                                        margin-right: 0px; 
                                        width: 100px;
                                        height: 50%;">
                                        @php
                                        $arrayRnk = $dataprod['arrayRnk'];
                                        $cont = count($arrayRnk);
                                        @endphp
                                        @if ($cont == 1)
                                            <div class="form-control" 
                                                 style="width: 100px;
                                                 color: #ffffff;
                                                 font-size: 20px;
                                                 height: 50%;
                                                 background-color: #FE9232;">
                                                 @if (Auth::user()->versionLight == 0)
                                                    {{ $dataprod['tpselect'] }}
                                                 @else
                                                    TP**
                                                 @endif
                                            </div>
                                        @else 
                                            <select id="idProv-{{$iFila}}" 
                                                    class="form-control"
                                                    style="width: 100px;
                                                    color: #ffffff;
                                                    font-size: 20px;
                                                    height: 50%;
                                                    background-color: #FE9232;">
                                                    @for ($x=0; $x < $cont; $x++) 
                                                        @if (Auth::user()->versionLight == 0)
                                                            @if ($dataprod['tpselect']==$arrayRnk[$x]['codprove']) 
                                                                <option selected value=
                                                                "{{ $arrayRnk[$x]['codprove'] }}">   
                                                                {{ $arrayRnk[$x]['codprove'] }}
                                                                </option>
                                                            @else 
                                                                <option value=
                                                                   "{{ $arrayRnk[$x]['codprove'] }}">
                                                                    {{ $arrayRnk[$x]['codprove'] }}
                                                                </option>
                                                            @endif
                                                        @else
                                                            @if ($dataprod['tpselect']==$arrayRnk[$x]['codprove']) 
                                                                <option selected value=
                                                                "{{ $arrayRnk[$x]['codprove'] }}">   
                                                                {{ sCodprovCifrado($arrayRnk[$x]['codprove']) }}
                                                                </option>
                                                            @else 
                                                                <option value=
                                                                   "{{ $arrayRnk[$x]['codprove'] }}">
                                                                    {{ sCodprovCifrado($arrayRnk[$x]['codprove']) }}
                                                                </option>
                                                            @endif
                                                        @endif
                                                    @endfor
                                            </select>
                                        @endif
                                    </div>
                                </span>
                            </div>
                        </td>
                        
                        @foreach ($provs as $prov)
                            @php
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
                                $cantidad = $campo[1];
                                $codprod = $campo[3];
                                $fechafalla = $campo[4];

                                $lote = $campo[7];
                                $fecvence = $campo[8];
                                $fecvence = str_replace("12:00:00 AM", "", $fecvence);
                                $confprov = LeerProve($prov->codprove); 
                                if (is_null($confprov))
                                    continue;
                                $tipoprecio = $prov->tipoprecio;
                                $actualizado = date('d-m-Y H:i', strtotime($prov->fechasinc));
                                $dc = $prov->dcme;
                                $di = $prov->di;
                                $pp = $prov->ppme;
                                $da = $campo[2];
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
                                $neto = CalculaPrecioNeto($precio, $da, $di, $dc, $pp, 0.00);
                                $liquida = $neto + (($neto * $cat->iva)/100);
                                $ranking = obtenerRanking($liquida, $arrayRnk);
                            @endphp

                            <!--- PRECIO DEL PROVEEDOR -->
                            <td align='right' style="width: 200px; word-wrap: break-word; background-color: {{$confprov->backcolor}};
                             color: {{$confprov->forecolor}}; " 
                             
                             @if (Auth::user()->versionLight == 0)
                             title = "{{$confprov->descripcion}} &#10 ======================== &#10 PRECIO: {{number_format($precio, 2, '.', ',')}} &#10 TIPO: {{$tipoprecio}} @if ($factor > 1) &#10 TASA: {{number_format($factor, 2, '.', ',')}} @endif &#10 DA: {{number_format($da, 2, '.', ',')}} &#10 DC: {{number_format($dc, 2, '.', ',')}} &#10 DI: {{number_format($di, 2, '.', ',')}} &#10 PP: {{number_format($pp, 2, '.', ',')}} &#10 IVA: {{number_format($cat->iva, 2, '.', ',')}} &#10 RANKING: {{$ranking}} &#10 LOTE: {{$lote}} &#10 VENCE: {{$fecvence}} &#10 CODIGO: {{$codprod}} &#10 ACTUALIZADO: {{$actualizado}} &#10 ======================== &#10 LIQUIDA: {{number_format($liquida, 2, '.', ',')}} &#10 "
                             @endif

                             >
                             @if (Auth::user()->versionLight == 0)
                                 @if ($liquida == $dataprod['mpcFactor'])
                                    <i class="fa fa-check"></i>
                                    {{number_format($liquida, 2, '.', ',')}} 
                                 @else
                                    {{number_format($liquida, 2, '.', ',')}}
                                 @endif
                             @else
                                 {{ sPrecioCifrado(number_format($liquida, 2, '.', ',')) }}
                             @endif

                             @if (Auth::user()->versionLight == 0)
                                 @if ($ranking)
                                    &#10 <div>Rnk:{{$ranking}}</div>
                                 @endif
                             @endif
                            </td>

                            <!--- CANTIDAD DEL PROVEEDOR -->
                            <td align='right' style="width: 200px; word-wrap: break-word; background-color: {{$confprov->backcolor}}; color: {{$confprov->forecolor}};" 
                                title=" {{$confprov->descripcion}}">
                                @if ($dataprod['mayorInv'] == $cantidad)
                                    <i class="fa fa-check"></i>
                                    {{number_format($cantidad, 0, '.', ',')}}
                                @else
                                    {{number_format($cantidad, 0, '.', ',')}}
                                @endif
                            </td>
                    
                            <td style="display:none;">{{$da}}</td>
                            <td style="display:none;">{{$codprod}}</td>
                            <td style="display:none;">{{$fechafalla}}</td>
                        @endforeach
                    </tr>
                @endforeach
            </table>

            <div align='left'>
                {{$sugoferen->appends(["filtro" => $filtro])->links()}}
            </div><br>
            
        </div>
    </div>
</div>
@if ($sugoferen->count() == 0)
    <div class="row">
        @if ($tipo=="C")
            <center><h2>Catálogo de productos vacio</h2></center>
        @endif
        <br><br><br><br><br><br>
    </div>
@endif
@push ('scripts')

<script>
$('#subtitulo').text('{{$subtitulo}}');
var accion = document.getElementById("idaccion").value;

$(document).keypress(function(e) {
   if(e.which == 13) {
        alert('Accion');
   }
});


</script>

@endpush
@endsection

