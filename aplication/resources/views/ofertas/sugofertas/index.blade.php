@extends ('layouts.menu')
@section ('contenido')
@php
  $moneda = Session::get('moneda', 'BSS');
  $factor = RetornaFactorCambiario($cliente->codcli, $moneda);
@endphp

<div class="row" style="margin-bottom: 5px;">
	<div class="col-lg-4 col-md-6 col-sm-6 col-xs-6">
		@include('ofertas.sugofertas.search')
	</div>
	<div class="row">
        <div class="form-group">
            <button type="button" class="btn-normal" onclick="history.back(-1)">Regresar</button>
            @if ($moneda == 'BSS')
                <a href="" data-target="#modal-guardar" data-toggle="modal">
                    <button class="btn-confirmar" data-toggle="tooltip" title="Guardar oferta">
                    Guardar
                    </button>
                </a>
                @include('ofertas.sugofertas.guardar')
            @endif
        </div>
    </div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table id="idtabla" class="table table-striped table-bordered table-condensed table-hover">
                <thead style="background-color: #b7b7b7;">
                    <th style="vertical-align: middle;"> 
                        #
                    </th>
                    <th style="width: 70%; vertical-align: middle;">
                    	DESCRIPCION
                    </th>
                    <th style="vertical-align: middle;">
                        CODIGO
                    </th>
                    <th style="vertical-align: middle;">
                        REFERENCIA
                    </th>
                    <th style="vertical-align: middle;">
                        MARCA
                    </th>
                    <th style="background-color: #FECC9E; 
                        vertical-align: middle;">
                        COSTO
                    </th>
                    <th style="background-color: #FDBF87;
                        vertical-align: middle;"
                        title="Porcentaje de Utilidad">
                        UTIL(%)
                    </th>
                    <th style="background-color: #FEB370; 
                        vertical-align: middle;">
                        PRECIO({{$cliente->usaprecio}})
                    </th>
                    <th style="background-color: #FEA95C;
                    	width: 110px; 
                        vertical-align: middle;"
                        title="Porcentaje de Descuento Adicional Sugerido">
                        DA SUGERIDO(%)
                    </th>

                    <th style="background-color: #FEA95C; vertical-align: middle;" >
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
                        width: 10%; vertical-align: middle;">
                        PS
                    </th>
                    <th title="Mejor precio de la competencia" 
                        style="background-color: #FE9232; 
                               width: 10%;
                               color: #ffffff; vertical-align: middle;">
                               MPC
                    </th>
                    <th style="background-color: #FD9E46;
                        color: #ffffff;
                        width: 10%; vertical-align: middle;">
                        PROVEEDOR
                    </th>
                </thead>
                @php
                $iFila = 0;
                @endphp
	            @foreach ($sugoferen as $sug)
		            @php
		            $iFila++;
		            @endphp
	                <tr>
	                	<td style="background-color: {{$sug->backcolor}}; 
	                        color: {{$sug->forecolor}};"
	                        title = "{{$sug->title}}" >
	                        {{$iFila}}
	                    </td>
	                    <td>{{$sug->desprod}}</td>
	                    <td>{{$sug->codprod}}</td>
	                    <td>{{$sug->barra}}</td>
                        <td>{{$sug->marca}}</td>
	                    <!-- COSTO -->
                        <td align="right" 
                            style="background-color: #FECC9E;"
                            title="COSTO"
                            id="costo-{{$sug->item}}">
                            {{number_format($sug->costo/$factor, 2, '.', ',')}}
                        </td>
                        <!-- UTILIDAD -->
                        <td align="right" 
                            style="background-color: #FDBF87;
                            @if ($sug->util < $cliente->utilm)
                                color: red; 
                            @endif"
                            title="MARGEN DE UTILIDAD"
                            id="util-{{$sug->item}}">
                            {{number_format($sug->util, 2, '.', ',')}}%
                        </td>
                        <!-- PRECIO -->
                        <td align="right" 
                            style="background-color: #FEB370;"
                            title="PRECIO"
                            id="precio-{{$sug->item}}">
                            {{number_format($sug->precio/$factor, 2, '.', ',')}}
                        </td>
                        <!-- DA -->
                     	<td>
                            <div class="col-xs-12 input-group"
                            	 style = "vertical-align: middle;" >
                                <input name="daprod[]"
                                	style="text-align: right; 
                                	color: #000000; 
                                	width: 60px;" 
                                	value="{{number_format($sug->dasug, 0, '.', ',')}}"
                                	class="form-control"
                                    id="da-{{$sug->item}}" >
                                <span class="input-group-btn" onclick='tdclickOferta(event);'>
                                    <button id="idBtn1-{{$sug->item}}" 
                                    	type="button" 
                                    	class="btn btn-pedido" 
                                    	data-toggle="tooltip" 
                                    	title="Modificar oferta" >
                                        <span class="fa fa-check" 
                                            aria-hidden="true" 
                                            id="idBtn2-{{$sug->item}}">
                                        </span>
                                    </button> 
                                </span>
                                <span class="input-group-btn" >
                                    <a href="" 
	                                	data-target="#modal-delete-{{$sug->item}}" 
	                                	data-toggle="modal">
	                                	<button id="idBtnDel1-{{$sug->item}}" 
	                                    	type="button" 
	                                    	class="btn btn-pedido" 
	                                    	data-toggle="tooltip" 
	                                    	title="Eliminar oferta" >
	                                        <span class="fa fa-trash-o" 
	                                            aria-hidden="true" 
	                                            id="idBtnDel2-{{$sug->item}}">
	                                        </span>
	                                    </button> 
									</a>
                                </span>
                            </div>
                        </td>
	                  	<td style="padding-top: 10px;  
                            text-align: center;
                            vertical-align: middle;">
                                <input onclick='case(event);'
                                    class="case"
                                    type="checkbox" 
                                    id='product-{{$sug->item}}' />
                        </td>
                        <!-- PS -->
                        <td align="right" 
                            style="background-color: #FD9E46;
                            color: #ffffff;
                            font-size: 20px;
                            width: 10%;
                            vertical-align: middle;
                            @if (($sug->ps/$factor) > $sug->mpc)
                                color: red; 
                            @endif"
                            title="PRECIO SUGERIDO"
                            id="ps1-{{$sug->item}}">
                            {{number_format($sug->ps/$factor, 2, '.', ',')}}
                        </td>
                        <!-- MPC -->
                  		<td align="right" 
                            style="background-color: #FE9232;
                            color: #ffffff;
                            font-size: 20px;
                            width: 10%;
                            vertical-align: middle;"
                            id="mpc-{{$sug->item}}"
                            title="MEJOR PRECIO DE LA COMPETENCIA">
                            {{number_format($sug->mpc, 2, '.', ',')}}
                        </td>
	                  	<td style="background-color: #FE9232;
                            color: #ffffff;
                            font-size: 14px;
                            width: 10%;
                            vertical-align: middle;">
	                  		{{sCodprovCifrado($sug->codmpc)}}
	                  	</td>		
                    </tr>
                    @include('ofertas.sugofertas.deleprod')
	            @endforeach 
            </table>
        </div><br>
	</div>
</div>

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
$("#delall").click(function() {
    checks = document.querySelectorAll(".case:checked");
    if(checks.length > 0) {
        x = confirm("Estas seguro de eliminar "+checks.length+" elemento(s) ?");
        if (x) {
            checks = document.querySelectorAll(".case");
            for(i=0; i < checks.length; i++) {
                if (checks[i].checked) {
                    p = checks[i].id.split("-");
                    var item = parseInt(p[1]);
                    var jqxhr = $.ajax({
		                type:'POST',
		                url: './sugofertas/delsel',
		                dataType: 'json', 
		                encode  : true,
		                data: {item : item },
      					always:function() {
		                }
		            });
                }
            } 
            jqxhr.always(function() {
			  	alert("ofertas seleccionados se han borrado!!!");
			    window.location.reload();
			});
        }
    } else {
        // Si no hay elementos seleccionados
        alert("No hay productos seleccionados");
    }
});
function tdclickOferta(e) {
    var id = e.target.id.split('-');
    var item = id[1];
    var utilm = '{{$utilm}}';
    var da = $('#da-'+item).val().trim();
    da = parseFloat(da.replace(/,/g, '') ).toFixed(2); 
    var costo = $('#costo-'+item).text().trim();
    costo = parseFloat(costo.replace(/,/g, '') ).toFixed(2);
    var mpc = $('#mpc-'+item).text().trim();
    mpc = parseFloat(mpc.replace(/,/g, '') ).toFixed(2);
    var precio = $('#precio-'+item).text().trim();
    precio = parseFloat(precio.replace(/,/g, '') ).toFixed(2);
    var putil = (-1)*(((costo/(precio - (precio*da/100)) )*100)-100);
    var ps = costo/Math.abs((putil-100)/100);
    var jqxhr = $.ajax({
        type:'POST',
        url: './sugofertas/upddasug',
        dataType: 'json', 
        encode  : true,
        data: {item:item, da:da, ps:ps, util:putil },
		done:function() {
	    }
    });
    jqxhr.always(function() {
	    $("#util-"+item).text(number_format(putil, 2, '.', ',') + '%');
	    $("#ps1-"+item).text(number_format(ps, 2, '.', ','));
	    $("#ps2-"+item).val(number_format(ps, 2, '.', ','));
	    if (ps > mpc)  
	        document.getElementById('ps1-'+item).style.color = "red";
	    else 
	        document.getElementById('ps1-'+item).style.color = "white";
	    if (putil < utilm)  
	        document.getElementById('util-'+item).style.color = "red";
	    else 
	        document.getElementById('util-'+item).style.color = "black";
	    alert("ITEM: " + item + " COSTO: " + costo + " DA: " + da + " PRECIO: " + precio + " UTIL: " + putil + " PS: " + ps);
	});
}


$('#modal-guardar').on('shown.bs.modal', function () {
  $('#idobserv').focus();
})

</script>
@endpush
@endsection