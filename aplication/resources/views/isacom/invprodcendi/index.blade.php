@extends ('layouts.menu')
@section ('contenido')
   
<div class="row">
	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
		@include('isacom.invminmax.search')
	</div>

	<div class="row col-xs-6" style="float: right;">
		<span class="input-group" 
			style="float: left; width: 200px;" >
		    <span style="border:0px;" class="input-group-addon">MINIMO:</span>
        	<input type="number" 
	    		placeholder="MINIMO" 
	    	    id="idclimin_{{$codcli}}"
                value="{{$cliente->min}}"
                class="form-control"
                title="VALOR MINIMO x DEFECTO">
		    <span class="input-group-btn" 
		    	onclick='tdclick(event);'>
		        <button id="idclimin1_{{$codcli}}_climin" 
		        	type="button" 
		        	class="btn btn-pedido" 
		        	data-toggle="tooltip"  
		      		title="GRABAR MINIMO x DEFECTO" >
		            <span id="idclimin2_{{$codcli}}_climin" 
		            	class="fa fa-check" 
		            	aria-hidden="true">
		            </span>
		        </button>
		    </span>
		</span>
		<span class="input-group" 
			style="float: right; width: 200px; margin-right: 15px;">
			<span style="border:0px;" class="input-group-addon">MAXIMO:</span>
	    	<input type="number" 
	    		placeholder="MAXIMO" 
	    	    id="idclimax_{{$codcli}}"
                value="{{$cliente->max}}"
                class="form-control"
                title="VALOR MAXIMO x DEFECTO" 
                style="margin-left: 5px;">
		    <span class="input-group-btn" 
		    	onclick='tdclick(event);'>
		        <button id="idclimax1_{{$codcli}}_climax" 
		        	type="button" 
		        	class="btn btn-pedido" 
		        	data-toggle="tooltip"  
		      		title="GRABAR MAXIMO x DEFECTO" >
		            <span id="idclimax2_{{$codcli}}_climax" 
		            	class="fa fa-check" 
		            	aria-hidden="true">
		            </span>
		        </button>
		    </span>
		</span>
	</div>
</div> 
 
<div class="row" style="margin-top: 10px;">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive" >
			<table id="datos" 
				class="table table-striped table-bordered table-condensed table-hover">
				<thead class="colorTitulo">
					<th>#</th>
					<th style="width: 100px;">
						&nbsp;&nbsp;&nbsp;&nbsp;IMAGEN&nbsp;&nbsp;&nbsp;&nbsp;
					</th>
					<th>DESCRIPCION</th>
					<th style="width:150px;">REFERENCIAS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
				</thead>
				@foreach ($tabla as $t)
				<tr>
					@php
					$minmax = LeerMinMax($codcli, $t->codprod);
					$min = $minmax["min"];
					$max = $minmax["max"];
					@endphp
					<td>{{$loop->iteration}}</td>
					<td style="width: 100px;">
                        <div align="center" >
                        	<a href="{{URL::action('PedidoController@verprod',$t->barra)}}">
	                            
	                            <img src="http://isaweb.isbsistemas.com/public/storage/prod/{{NombreImagen($t->barra)}}" 
	                            class="img-responsive" 
	                            alt="icompras360" 
	                            width="100%" 
	                            height="100%"
	                            style="border: 2px solid #D2D6DE;"
	                            oncontextmenu="return false" >
                            
                            </a>
                 		</div>
                    </td>
					<td>
						<b>{{$t->desprod}}<b>

						<div class="row col-xs-12" style="margin-top: 2px;">
							<span class="col-xs-6 input-group"
								style="float: left; margin-left: 0px;" >
						    	<input type="number" 
						    		placeholder="MINIMO" 
						    	    id="idmin_{{$t->codprod}}"
	                                value="{{$min}}"
	                                title="VALOR MINIMO x PRODDUCTO"
	                                class="form-control" >
							    <span class="input-group-btn" 
							    	onclick='tdclick(event);'>
							        <button id="idmin1_{{$t->codprod}}_min" 
							        	type="button" 
							        	class="btn btn-pedido" 
							        	data-toggle="tooltip"  
							      		title="GRABAR MINIMO" >
							            <span id="idmin2_{{$t->codprod}}_min" 
							            	class="fa fa-check" 
							            	aria-hidden="true">
							            </span>
							        </button>
							    </span>
							</span>
							<span class="col-xs-6 input-group" style="float: right;">
						    	<input type="number" 
						    		placeholder="MAXIMO" 
						    	    id="idmax_{{$t->codprod}}"
	                                value="{{$max}}"
	                                title="VALOR MAXIMO x PRODDUCTO"
	                                class="form-control" 
	                                style="margin-left: 5px;">
							    <span class="input-group-btn" 
							    	onclick='tdclick(event);'>
							        <button id="idmax1_{{$t->codprod}}_max" 
							        	type="button" 
							        	class="btn btn-pedido" 
							        	data-toggle="tooltip"  
							      		title="GRABAR MAXIMO" >
							            <span id="idmax2_{{$t->codprod}}_max" 
							            	class="fa fa-check" 
							            	aria-hidden="true">
							            </span>
							        </button>
							    </span>
							</span>
						</div>

					</td>
					<td>
						<span title="CODIGO DE BARRA">
							<i class="fa fa-barcode">
								{{$t->barra}}
							</i>
						</span><br>
						<span title="CODIGO DEL PRODUCTO">
                            <i class="fa fa-cube">
                                {{$t->codprod}}    
                            </i>
                        </span>
                        <span title="MARCA ALTERNA">
                            <i class="fa fa-shield">
                                {{$t->subgrupo}}    
                            </i>
                        </span>
					</td>
	  			</tr>
				@endforeach
			</table>
			<div align='right'>
				{{$tabla->appends(["filtro" => $filtro])->links()}}
			</div><br>
		</div>
	</div>
</div>

@push ('scripts')
<script>
$('#subtitulo').text('{{$subtitulo}}');
function tdclick(e) {
    var id = e.target.id.split('_');
    var barra = id[1].trim();
    var campo = id[2];
    var	valor = $('#id' + campo + '_'+barra).val();
    var codcli = '{{$codcli}}';
    var filtro = '{{$filtro}}';
    if (barra == "") {
    	alert("CODIGO DE BARRA NO PUEDE IR EN BLANCO");
    }
    alert('Barra: ' + barra + ' Campo: ' + campo + ' Valor: ' + valor + ' Codcli: ' + codcli + ' Filtro: ' + filtro);
    $.ajax({
	  type:'POST',
	  url:'./invminmax/caract/modcaract',
	  dataType: 'json', 
	  encode  : true,
	  data: {barra:barra, campo:campo, valor:valor, codcli:codcli, filtro:filtro },
	  success:function(data) {
	    if (data.msg != "") {
	        alert(data.msg);
	    } 
	  }
  	});
  	if (campo == "climin" || campo == "climax" ) {
  		window.location.reload(); 
  	}
}
</script>
@endpush

@endsection