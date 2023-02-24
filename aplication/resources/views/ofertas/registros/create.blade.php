@extends ('layouts.menu')
@section ('contenido')
@php
  $moneda = Session::get('moneda', 'BSS');
  $factor = RetornaFactorCambiario($cliente->codcli, $moneda);
@endphp


<div class="row" style="margin-bottom: 5px;">
    <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
        @include('ofertas.registros.ofersearch')
    </div>
    {!! Form::open(array('url'=>'/ofertas/registros','method'=>'POST','autocomplete'=>'off')) !!}
    {{ Form::token() }}
    <div class="row">
        <div class="form-group">
            <button type="button" class="btn-normal" onclick="history.back(-1)">Regresar</button>
            @if ($moneda == 'BSS')
                <a href="" data-target="#modal-guardar" data-toggle="modal">
                    <button class="btn-confirmar" data-toggle="tooltip" title="Guardar oferta">
                    Guardar
                    </button>
                </a>
                @include('ofertas.registros.guardar')
            @endif
        </div>
    </div>
</div>

<div class="row" style="margin-top: 5px;">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="table-responsive">
            <table id="datos" class="table table-striped table-bordered table-condensed table-hover" >
                <thead style="background-color: #b7b7b7;">
                    <th>#</th>
                    <th style="width: 70%;">
                    	DESCRIPCION
                    </th>
                    <th>CODIGO</th>
                    <th>REFERENCIA</th>
                    <th style="background-color: #FECC9E;">COSTO</th>
                    <th style="background-color: #FDBF87;"
                        title="Porcentaje de Utilidad">
                        UTIL(%)
                    </th>
                    <th style="background-color: #FEB370;">PRECIO({{$cliente->usaprecio}})</th>
                    <th style="background-color: #FEA95C;
                    	width: 110px;"
                        title="Porcentaje de Descuento Adicional">
                        DA(%)
                    </th>

                    <th style="background-color: #FEA95C;">
                        <center>
                        <div style="width: 70px;">
                            <span class="input-group-btn">
                                <div class="col-xs-12 input-group" >
                                    <input type="checkbox" 
                                        id="selectall"
                                        title="marcar/desmarcar todos los producto"
                                        style="width: 50%; vertical-align:middle;">

                                    <button style="vertical-align:middle; 
                                        text-align: center;
                                        width: 50%; 
                                        height: 30px;" 
                                        type="button" 
                                        id="delall"
                                        class="btn btn-pedido" 
                                        title="Eliminar producto marcados" >
                                        <span class="fa fa-trash-o" >
                                        </span>
                                    </button>
                                </div>
                            </span>
                        </div>
                        </center>
                    </th>

                    <th style="background-color: #FD9E46;
                        color: #ffffff;
                        width: 10%;">
                        PS
                    </th>
                    <th title="Mejor precio de la competencia" 
                        style="background-color: #FE9232; 
                               width: 10%;
                               color: #ffffff;">
                               MPC
                    </th>
                </thead>
                @php
                $iFila = 0;
                $invent = 'inventario_'.$cliente->codcli;
                @endphp
                @foreach ($catalogo as $catx)
					@php
                    $cat = DB::table('tpmaestra as tpm')
                    ->select('*', 'tpm.desprod as descrip')
                    ->join($invent.' as tcm', 'tcm.barra','=', 'tpm.barra')
                    ->where('codisb','=',$cliente->codcli)
                    ->where('tpm.barra','=',$catx->barra)
                    ->first();
                    if (empty($cat))
                        continue;
                 	$dataprod = obtenerColorProd($cat, $cliente, $provs);
                    $iFila++;
					@endphp
                	<tr id="fila-{{$iFila}}">
	                    <td style="background-color: {{$dataprod['backcolor']}}; 
	                        color: {{$dataprod['forecolor']}};"
	                        title = "{{$dataprod['title']}}" >
	                        {{$iFila}}
	                    </td>
                        <td style="width: 300px;">
                        	{{$cat->descrip}}
                        	<input name="desprod[]"
                                   value="{{$cat->descrip}}"
                                   hidden="" >
                        </td>
                        <td>
                        	{{$cat->codprod}}
                        	<input name="codprod[]"
                                   value="{{$cat->codprod}}"
                                   hidden="" >
                        </td>
                        <td>
                            {{$cat->barra}}
                            <input name="refprod[]"
                                   value="{{$cat->barra}}"
                                   hidden="" >
                        </td>
                        <!-- COSTO -->
                        <td align="right" 
                            style="background-color: #FECC9E;"
                            title="COSTO"
                            id="costo-{{$iFila}}">
                            {{number_format($dataprod['costo']/$factor, 2, '.', ',')}}
                        </td>
                        <!-- UTILIDAD -->
                        <td align="right" 
                            style="background-color: #FDBF87;
                            @if ($dataprod['util'] < $cliente->utilm)
                                color: red; 
                            @endif"
                            title="MARGEN DE UTILIDAD"
                            id="util-{{$iFila}}">
                            {{number_format($dataprod['util'], 2, '.', ',')}}%
                        </td>
                        <!-- PRECIO -->
                        <td align="right" 
                            style="background-color: #FEB370;"
                            title="PRECIO"
                            id="precio-{{$iFila}}">
                            {{number_format($dataprod['precioInv']/$factor, 2, '.', ',')}}
                        </td>
                        <!-- DA -->
                     	<td>
                            <div class="col-xs-12 input-group"
                            	 style = "vertical-align: middle;" >
                                <input name="daprod[]"
                                	style="text-align: right; 
                                	color: #000000; 
                                	width: 60px;" 
                                	value="{{number_format($dataprod['da'], 0, '.', ',')}}"
                                	class="form-control"
                                    id="da-{{$iFila}}" >
                                <span class="input-group-btn" onclick='tdclickOferta(event);'>
                                    <button id="idBtn1-{{$iFila}}" 
                                    	type="button" 
                                    	class="btn btn-pedido" 
                                    	data-toggle="tooltip" 
                                    	title="Modificar oferta" >
                                        <span class="fa fa-check" 
                                            aria-hidden="true" 
                                            id="idBtn2-{{$iFila}}">
                                        </span>
                                    </button> 
                                </span>
                                <span class="input-group-btn" 
                                    onclick='tdclickDelete(event);'>
                                    <button type="button" 
                                        class="btn btn-pedido" 
                                        data-toggle="tooltip" 
                                        title="Eliminar oferta"
                                        id="id1-{{$iFila}}">
                                        <span class="fa fa-trash-o" 
                                            aria-hidden="true"
                                            id="id2-{{$iFila}}">
                                        </span>
                                    </button>
                                </span>
                            </div>
                        </td>
                        <td style="padding-top: 10px;  
                            text-align: center;
                            vertical-align: middle;">
                                <input onclick='case(event);'
                                    class="case"
                                    type="checkbox" 
                                    id='product-{{$iFila}}' />
                        </td>
                        <!-- PS -->
                        <td align="right" 
                            style="background-color: #FD9E46;
                            color: #ffffff;
                            font-size: 20px;
                            width: 10%;
                            vertical-align: middle;
                            @if (($dataprod['precioSug']/$factor) > $dataprod['mpcFactor'])
                                color: red; 
                            @endif"
                            title="PRECIO SUGERIDO"
                            id="ps1-{{$iFila}}">
                            {{number_format($dataprod['precioSug']/$factor, 2, '.', ',')}}
                        </td>
                        <!-- MPC -->
                  		<td align="right" 
                            style="background-color: #FE9232;
                            color: #ffffff;
                            font-size: 20px;
                            width: 10%;
                            vertical-align: middle;"
                            id="mpc-{{$iFila}}"
                            title="MEJOR PRECIO DE LA COMPETENCIA">
                            {{number_format($dataprod['mpcFactor'], 2, '.', ',')}}
                        </td>
                        <td>
                            <input name="psprod[]"
                               value="{{number_format($dataprod['precioSug'], 6, '.', '')}}"
                               hidden=""
                               id="ps2-{{$iFila}}">
                        </td>
                    </tr>
                @endforeach
                <tr class='noSearch hide'>
                    <td colspan="5"></td>
                </tr>
            </table>
        </div>
    </div>
</div>

{{Form::Close()}}
@push ('scripts')
<script>
$('#subtitulo').text('{{$subtitulo}}');
window.onload = function() {
    if ($(".case").length == $(".case:checked").length) {
        $("#selectall").prop("checked", true);
    } else {
        $("#selectall").prop("checked", false);
    }
}

$("#selectall").on("click", function(e) {
    var id = e.target.id.split('_');
    var item = id[1];
    var marcar;
    if ($(".case").length == $(".case:checked").length) {
        marcar = "0";
    } else {
        marcar = "1";
    }     
  
    $(".case").prop("checked", this.checked);
    try {
        var table = document.getElementById("datos");
        var rows = table.getElementsByTagName('tr');
        for (var ica = 1; ica < rows.length; ica++) {
            var cols = rows[ica].getElementsByTagName('td');
            var item = cols[1].innerHTML;
       }    
    } catch(e) { }
});
function tdclickDelete(e) {
    var idx = e.target.id.split('-');
    var id = idx[1];
    $('#fila-' + id).remove();
}
$("#delall").click(function() {
    checks = document.querySelectorAll(".case:checked");
    if(checks.length > 0) {
        x = confirm("Estas seguro de eliminar "+checks.length+" elemento(s) ?");
        if (x) {
            ids = "";
            checks = document.querySelectorAll(".case");
            for(i=0;i<checks.length;i++) {
                if (checks[i].checked) {
                    p = checks[i].id.split("-");
                    ids = p[1];
                    $('#fila-' + ids).remove();
                }
            }
        }
    } else {
        // Si no hay elementos seleccionados
        alert("No hay productos seleccionados");
    }
});
function tdclickOferta(e) {
    var id = e.target.id.split('-');
    var fila = id[1];
    var utilm = '{{$utilm}}';
    var da = $('#da-'+fila).val().trim();
    da = parseFloat(da.replace(/,/g, '') ).toFixed(2); 
    var costo = $('#costo-'+fila).text().trim();
    costo = parseFloat(costo.replace(/,/g, '') ).toFixed(2);
    var mpc = $('#mpc-'+fila).text().trim();
    mpc = parseFloat(mpc.replace(/,/g, '') ).toFixed(2);
    var precio = $('#precio-'+fila).text().trim();
    precio = parseFloat(precio.replace(/,/g, '') ).toFixed(2);
    var putil = (-1)*(((costo/(precio - (precio*da/100)) )*100)-100);
    var ps = costo/Math.abs((putil-100)/100);
    $("#util-"+fila).text(number_format(putil, 2, '.', ','));
    $("#ps1-"+fila).text(number_format(ps, 2, '.', ','));
    $("#ps2-"+fila).val(number_format(ps, 6, '.', ','));
    if (ps > mpc)  
        document.getElementById('ps1-'+fila).style.color = "red";
    else 
        document.getElementById('ps1-'+fila).style.color = "black";
    if (putil < utilm)  
        document.getElementById('util-'+fila).style.color = "red";
    else 
        document.getElementById('util-'+fila).style.color = "black";
    //alert("ID: " + fila + " COSTO: " + costo + " DA: " + da + " PRECIO: " + precio + " UTIL: " + putil + " PS: " + ps);
}


</script>
@endpush

@endsection
