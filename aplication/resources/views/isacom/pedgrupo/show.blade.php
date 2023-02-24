@extends('layouts.menu')
@section ('contenido')
@php
$moneda = Session::get('moneda', 'BSS');
$factor = RetornaFactorCambiario("", $moneda);
$x=0;
@endphp 

<!-- ENCABEZADO -->
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="form-group">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 input-group input-group-sm">
                
                <span class="input-group-addon">ID:</span>
                <input readonly type="text" 
                    class="form-control"
                    value="{{$id}}" 
                    style="color: #000000; padding: 2px;">

                <span class="input-group-addon hidden-xs" style="border:0px; "></span>
                <span class="input-group-addon hidden-xs">Estado:</span>
                <input readonly 
                    type="text" 
                    class="form-control hidden-xs" 
                    value="{{$pedgrupo->estado}}" 
                    style="color: #000000">

                <span class="input-group-addon hidden-xs" style="border:0px; "></span>
                <span class="input-group-addon hidden-xs">Fecha:</span>
                <input readonly 
                    type="text" 
                    class="form-control hidden-xs" 
                    value="{{date('d-m-Y H:i', strtotime($pedgrupo->fecha))}}" 
                    style="color: #000000">

                <span class="input-group-addon hidden-xs" style="border:0px; "></span>
                <span class="input-group-addon hidden-xs">Enviado:</span>
                <input readonly 
                    type="text" 
                    class="form-control hidden-xs" 
                    value="{{date('d-m-Y H:i', strtotime($pedgrupo->enviado))}}" 
                    style="color:#000000" >

                <span class="input-group-addon" style="border:0px; "></span>
                <span class="input-group-addon">Marca:</span>
                <input readonly 
                    type="text" 
                    class="form-control" 
                    value="{{$pedgrupo->marca}}"
                    style="color:#000000" >
    
                <span class="input-group-addon" style="border:0px; "></span>
                <span class="input-group-addon">Reposición:</span>
                <input readonly 
                    type="text" 
                    class="form-control" 
                    value="{{$pedgrupo->reposicion}}"
                    style="color:#000000" >
               
            </div>
        </div> 
    </div>
</div>

<div class="row" style="margin-top: 5px;">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="table-responsive">
            <table id="myTable" 
                class="table table-striped table-bordered table-condensed table-hover">
                <thead style="background-color: #b7b7b7;">
                    <th colspan="6">
                        <center>PRODUCTOS</center>
                    </th>

                    <th colspan="2"
                        style="width: 200; 
                        background-color: #FCD5B8;
                        color: black;">
                        <center>
                        &nbsp;&nbsp;&nbsp;&nbsp;GRUPO&nbsp;&nbsp;&nbsp;&nbsp;
                        </center>
                    </th>

                    @foreach ($gruporen as $gr)

                        @php 
                        $idped = '';
                        $codsuc = $gr->codcli;
                        $confcli = LeerCliente($codsuc); 
                        $actualizado = date('d-m-Y H:i', strtotime(LeerTablaFirst('inventario_'.$codsuc, 'feccatalogo')));
                        $fechaHoy = trim(date("Y-m-d"));
                        $fechaInv = trim(date('Y-m-d', strtotime($actualizado)));
                        $idped = '';
                        $pedido = ObtenerPedidoGrupo($id,$codsuc);
                        if (!empty($pedido)) 
                            $idped = '('.$pedido->id .'-'. $pedido->estado.')';
                        @endphp
                        
                        <th colspan="4" 
                            style="background-color: {{$confcli->backcolor}}; 
                            color: {{$confcli->forecolor}}; 
                            width: 400px; 
                            word-wrap: break-word; ">
                            <a href="{{URL::action('GrupoController@show',$codsuc)}}">
                                <center>
                                    <button type="button" 
                                        data-toggle="tooltip" 
                                        title="{{$confcli->nombre}} &#10 ({{$actualizado}})" 
                                        style="background-color: {{$confcli->backcolor}}; 
                                        border: none;
                                        color: {{$confcli->forecolor}};
                                        "> {{$confcli->descripcion}} {{$idped}}
                                    </button>
                                </center>
                            </a>
                        </th>
                    @endforeach
                </thead>
                <thead style="background-color: #b7b7b7;">
                    <th style="width: 10px;">
                        <center>#</center>
                    </th>
                    <th style="width: 120px;">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;IMAGEN&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </th>
                    <th style="width: 150px;"
                        title="Descripción del producto">
                        PRODUCTO
                    </th>
                    <th style="width: 100px;">
                        REFERENCIA
                    </th>
                    <th style="width: 60px;">BULTO</th>
                    <th style="width: 60px;">IVA</th>

                    <!-- GRUPO -->
                    <th style="background-color: #FCD5B8;
                        color: black;">
                        SUGERIDO
                    </th>
                    <th style="background-color: #FCD5B8;
                        color: black;">
                        SUBTOTAL
                    </th>

                    @foreach ($gruporen as $gr)

                        @php 
                        $codsuc = $gr->codcli;
                        $confcli = LeerCliente($codsuc); 
                        $actualizado = date('d-m-Y H:i', strtotime(LeerTablaFirst('inventario_'.$codsuc, 'feccatalogo')));
                        $fechaHoy = trim(date("Y-m-d"));
                        $fechaInv = trim(date('Y-m-d', strtotime($actualizado)));
                        @endphp
                        
                        <!-- DETALLES -->
                        <th style="background-color: {{$confcli->backcolor}};
                            color: {{$confcli->forecolor}};"
                            title="Cantidad sugerida">
                            SUGERIDO
                        </th>
                        <th style="background-color: {{$confcli->backcolor}};
                            color: {{$confcli->forecolor}};"
                            title="Costo del producto">
                            COSTO
                        </th>
                        <th style="background-color: {{$confcli->backcolor}};
                            color: {{$confcli->forecolor}};"
                            title="Subtotal costo x producto">
                            SUBTOTAL
                        </th>
                        <th style="background-color: {{$confcli->backcolor}};
                            color: {{$confcli->forecolor}};"
                            title="Código del producto">
                            CODIGO
                        </th>
                        
                    @endforeach
                </thead>
                @if (isset($pg))
                    @foreach ($pg as $p)
                        @php
                        $x = $x + 1;
                        @endphp
                        <tr>
                            <td style="width: 10px;">
                                <center>
                                {{$loop->iteration}}
                                </center>
                            </td>

                            <td>
                                <div align="center">
                                    <a href="{{URL::action('PedidoController@verprod',$p->barra)}}">

                                    <img src="http://isaweb.isbsistemas.com/public/storage/prod/{{NombreImagen($p->barra)}}" 
                                    width="100%" 
                                    height="100%" 
                                    class="img-responsive" 
                                    alt="icompras360" 
                                    style="border: 2px solid #D2D6DE;"
                                    oncontextmenu="return false">

                                    </a>
                                </div>
                            </td>

                            <td style="width: 150px;">
                                {{$p->desprod}}
                            </td>

                            <td style="width: 100px;"
                                title="CODIGO DE BARRA">
                                {{$p->barra}}
                            </td>

                            <td align="right" 
                                style="width: 60px;"
                                title="UNIDAD DE MANEJO">
                                {{$p->bulto}}
                            </td>

                            <td align="right"
                                title="IVA">
                                {{number_format($p->iva, 2, '.', ',')}}
                            </td>
                
                            <!-- GRUPO -->
                            <tH style="text-align: right;
                                background-color: #FCD5B8;"
                                title="TOTAL SUGERIDO DEL PRODUCTO X GRUPO"
                                id='suggrp_{{$x}}'>
                            </tH>
                            <tH style="text-align: right;
                                background-color: #FCD5B8;"
                                title="TOTAL COSTO DEL PRODUCTO X GRUPO"
                                id='cosgrp_{{$x}}'>
                            </tH>

                            @foreach ($gruporen as $gr)
                                @php
                                $codsuc = strtolower($gr->codcli);
                                $actualizado = date('d-m-Y H:i', strtotime(LeerTablaFirst('inventario_'.$codsuc, 'feccatalogo')));
                                $confcli = LeerCliente($codsuc); 
                                $cantidad = 0;
                                $precio = 0;
                                $subtotal = 0;
                                $codprod = "N/A";
                                $pedx = DB::table('pedido')
                                ->where('idpedgrupo','=',$id)
                                ->where('codcli','=',$codsuc)
                                ->first();
                                if ($pedx) {
                                    $prx = DB::table('pedren')
                                    ->where('id','=',$pedx->id)
                                    ->where('barra','=',$p->barra)
                                    ->first();
                                    if ($prx) {
                                        $cantidad = $prx->cantidad;
                                        $precio = $prx->precio;
                                        $codprod = $prx->codprod;
                                        $subtotal = $prx->subtotal;
                                    }
                                }
                                @endphp

                                {{ info($p->barra) }}
                                {{ info($cantidad) }}
                                {{ info($confcli->descripcion) }}
                         
                                <!-- DETALLES -->
                                <td align="right" 
                                    style="background-color: {{$confcli->backcolor}};
                                    color: {{$confcli->forecolor}};"
                                    title="Cantidad sugerida"
                                    id='sug_{{$codsuc}}_{{$x}}'>
                                    {{number_format($cantidad, 0, '.', ',')}}
                                </td>
                                <td align="right" 
                                    style="background-color: {{$confcli->backcolor}};
                                    color: {{$confcli->forecolor}};"
                                    title="Costo del producto"
                                    id='cos_{{$codsuc}}_{{$x}}'>
                                    {{number_format($precio/$factor, 2, '.', ',')}}
                                </td>
                                <td align="right" 
                                    style="background-color: {{$confcli->backcolor}};
                                    color: {{$confcli->forecolor}};"
                                    title="Subtotal Costo x producto"
                                    id='tot_{{$codsuc}}_{{$x}}'>
                                    {{number_format($subtotal/$factor, 2, '.', ',')}}
                                </td>
                                <td style="background-color: {{$confcli->backcolor}};
                                    color: {{$confcli->forecolor}};"
                                    title="Código del producto">
                                    {{$codprod}}
                                </td>
                                 
                            @endforeach

                            
                        </tr>
                    @endforeach
                    <thead>
                        <th colspan="6">
                            TOTALES DEL GRUPO
                        </th>

                        <!-- GRUPO-->
                        <th style="text-align: right;
                            background-color: #FCD5B8;"
                            title="Cantidad total sugerida del grupo"
                            id='suggrp' >
                        </th>
                        <th style="text-align: right;
                            background-color: #FCD5B8;"
                            title="Monto del Costo del grupo"
                            id='cosgrp'>
                        </th>
                       
                        @foreach ($gruporen as $gr)

                            @php 
                            $codsuc = $gr->codcli;
                            $confcli = LeerCliente($codsuc); 
                            @endphp

                            <!-- DETALLES -->
                            <th style="background-color: {{$confcli->backcolor}};
                                color: {{$confcli->forecolor}};
                                text-align: right;"
                                title="Cantidad total sugerida por cliente "
                                id='sug_{{$codsuc}}' >
                            </th>

                            <th colspan="3"
                                style="background-color: {{$confcli->backcolor}};
                                color: {{$confcli->forecolor}};
                                text-align: right;"
                                title="Monto del Costo por cliente"
                                id='cos_{{$codsuc}}'>
                            </th>
                            
                        @endforeach
                    </thead>
                    <thead style="background-color: #b7b7b7;">
                        <th colspan="6">
                            <center>PRODUCTOS</center>
                        </th>

                        <th colspan="2"
                            style="width: 200; 
                            background-color: #FCD5B8;
                            color: black;">
                            <center>
                            &nbsp;&nbsp;&nbsp;&nbsp;GRUPO&nbsp;&nbsp;&nbsp;&nbsp;
                            </center>
                        </th>

                        @foreach ($gruporen as $gr)

                            @php 
                            $idped = '';
                            $codsuc = $gr->codcli;
                            $confcli = LeerCliente($codsuc); 
                            $actualizado = date('d-m-Y H:i', strtotime(LeerTablaFirst('inventario_'.$codsuc, 'feccatalogo')));
                            $fechaHoy = trim(date("Y-m-d"));
                            $fechaInv = trim(date('Y-m-d', strtotime($actualizado)));
                            $idped = '';
                            $pedido = ObtenerPedidoGrupo($id,$codsuc);
                            if (!empty($pedido)) 
                                $idped = '('.$pedido->id .'-'. $pedido->estado.')';
                            @endphp
                            
                            <th colspan="4" 
                                style="background-color: {{$confcli->backcolor}}; 
                                color: {{$confcli->forecolor}}; 
                                width: 400px; 
                                word-wrap: break-word; ">
                                <a href="{{URL::action('GrupoController@show',$codsuc)}}">
                                    <center>
                                        <button type="button" 
                                            data-toggle="tooltip" 
                                            title="{{$confcli->nombre}} &#10 ({{$actualizado}})" 
                                            style="background-color: {{$confcli->backcolor}}; 
                                            border: none;
                                            color: {{$confcli->forecolor}};
                                            "> {{$confcli->descripcion}} {{$idped}}
                                        </button>
                                    </center>
                                </a>
                            </th>
                        @endforeach
                    </thead>
                @endif

            </table>

            @if ($moneda == "USD")
            <h4>
             *** {{$cfg->simboloOM}} {{number_format($factor, 2, '.', ',')}} ***
            </h4>  
            @endif

            <div align='left'>
                @if (isset($invent))
                    {{$invent->appends(["filtro" => $filtro])->links()}}
                @endif
            </div><br>
        </div>
    </div> 
</div>

@push ('scripts')
<script>
window.onload = function() {
    $('#titulo').text('{{$grupo->nomgrupo}}');
    $('#subtitulo').text('{{$subtitulo}}');
    var cont = '{{$gruporen->count()}}';
    var codgrupo = '{{$codgrupo}}';
    var resp = "";
    var tableReg = document.getElementById('myTable');
    var jqxhr = $.ajax({
        type:'POST',
        url: './obtenerCodcliGrupo',
        dataType: 'json', 
        encode  : true,
        data: { codgrupo:codgrupo },
        success:function(data) {
            resp = data.resp;
        }
    });
    jqxhr.always(function() {
        var arrCodsuc = resp.split('|');
        for (var z = 0; z < cont; z++) {
            var codsuc = arrCodsuc[z].toString().trim();
            var suger = 0;
            var costo = 0;   
            for (var i = 1; i < tableReg.rows.length-3; i++) {
                var x = i.toString().trim();
                var sugx = $('#sug_' + codsuc + "_" + x).text().replace(/,/g, '');
                var totx = $('#tot_' + codsuc + "_" + x).text().replace(/,/g, '');
                costo += parseFloat(totx);
                suger += parseFloat(sugx);
           }
            $("#cos_"+codsuc).text(number_format(costo, 2, '.', ','));
            $("#sug_"+codsuc).text(number_format(suger, 0, '.', ','));
        }
        for (var i = 1; i < tableReg.rows.length-3; i++) {
            var suger = 0;
            var costo = 0;   
            var codsuc = "";
            var x = i.toString().trim();
            for (var z = 0; z < cont; z++) {
                var codsuc = arrCodsuc[z].toString().trim();
                var sugx = $('#sug_' + codsuc + "_" + x).text().replace(/,/g, '');
                var totx = $('#tot_' + codsuc + "_" + x).text().replace(/,/g, '');
                costo += parseFloat(totx);
                suger += parseFloat(sugx);
            }  
            $("#cosgrp_"+x).text(number_format(costo, 2, '.', ','));
            $("#suggrp_"+x).text(number_format(suger, 0, '.', ','));
        }
        suger = 0;
        costo = 0;
        for (var i = 1; i < tableReg.rows.length-3; i++) {
            var x = i.toString().trim();
            var sugx = $('#suggrp_' + x).text().replace(/,/g, '');
            var totx = $('#cosgrp_' + x).text().replace(/,/g, '');
            costo += parseFloat(totx);
            suger += parseFloat(sugx);
        }
        $("#cosgrp").text(number_format(costo, 2, '.', ','));
        $("#suggrp").text(number_format(suger, 0, '.', ','));

    });
}
</script>
@endpush

@endsection