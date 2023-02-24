@extends ('layouts.menu')
@section ('contenido')
   
<div class="row">
	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
		@include('isacom.prodcaract.search')
	</div>
</div> 
 
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive" >
			<table id="datos" 
				class="table table-striped table-bordered table-condensed table-hover">
				<thead class="colorTitulo">
					<th>#</th>
					<th style="width: 100px;">
						&nbsp;&nbsp;&nbsp;&nbsp;IMAGEN&nbsp;&nbsp;&nbsp;&nbsp;
					</th>
					<th>PRODUCTO</th>
					<th style="width:110px;">BARRA</th>
					<th style="width:120px;" title="Marca de Producto">MARCA</th>
					<th style="width:200px;" title="Categoria de Producto">CATEGORIA</th>
					<th style="width:300px;" title="Molecula del Producto">MOLECULA</th>
				</thead>
				@foreach ($tabla as $t)
				@php
				$unidadmolecula = LeerProdcaract($t->barra, 'unidadmolecula', '1')
				@endphp
				<tr>
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
						<span title="{{$t->nomprov}}-{{$t->metadata}}">
							<b>{{$t->desprod}}<b>
						</span>

						<div class="col-xs-12 input-group" >

					    	<input type="text" 
					    		placeholder="principio activo" 
					    	    id="idpactivo_{{$t->barra}}"
                                value="{{$t->pactivo}}"
                                class="form-control" >

						    <span class="input-group-btn" 
						    	onclick='tdclick(event);'>
						        <button id="idpactivo1_{{$t->barra}}_pactivo" 
						        	type="button" 
						        	class="btn btn-pedido" 
						        	data-toggle="tooltip"  
						      		title="Grabar principio activo" >
						            <span id="idpactivo2_{{$t->barra}}_pactivo" 
						            	class="fa fa-check" 
						            	aria-hidden="true">
						            </span>
						        </button>
						    </span>
						</div>
					</td>
					<td>{{$t->barra}}</td>
	   				<td>
						<div class="col-xs-12 input-group" >
						    <select id="idmarca_{{$t->barra}}" 
						    	style="width: 120px;" 
						    	class="form-control">
						    	@php 
						    		$existe=0;
						    		$codmarca = trim(($t->marca)); 
						    		foreach ($marca as $mar) {
						    			if ( $codmarca == trim($mar->descrip)) {
						    				$existe=1;
						    			}
						    		}
						    		if ($existe==0)
						    			$codmarca = 'POR DEFINIR';
						    	@endphp
					    		@foreach($marca as $mar)
					    			@if ($codmarca == trim($mar->descrip))
					    				<option selected value="{{$mar->descrip}}">
											{{$mar->descrip}}
										</option>
						    		@else
					    				<option value="{{$mar->descrip}}">
											{{$mar->descrip}}
										</option>
					    			@endif
					    		@endforeach
					    		
					    	</select>
						    <span class="input-group-btn" onclick='tdclick(event);'>
						        <button id="idmarca1_{{$t->barra}}_marca" 
						        	type="button" 
						        	class="btn btn-pedido" 
						        	data-toggle="tooltip"
						        	title="Grabar marca" >
						            <span id="idmarca2_{{$t->barra}}_marca" class="fa fa-check" aria-hidden="true">
						            </span>
						        </button>
						    </span>

						</div>
					</td>
					<td>
						<div class="col-xs-12 input-group" >
						    <select id="idcategoria_{{$t->barra}}" 
						    	style="width: 200px;" 
						    	class="form-control">
					
					    		@foreach($categoria as $cat)
					    			@if ($t->categoria == $cat->descrip)
										<option selected value="{{$cat->descrip}}">
											{{$cat->descrip}}
										</option>
						    		@else
					    				<option value="{{$cat->descrip}}">
											{{$cat->descrip}}
										</option>
					    			@endif
					    		@endforeach

					    	</select>
						    <span class="input-group-btn" onclick='tdclick(event);'>
						        <button id="idcategoria1_{{$t->barra}}_categoria" 
						        	type="button" 
						        	class="btn btn-pedido" 
						        	data-toggle="tooltip"  
						        	title="Grabar categoria" >
						            <span id="idcategoria2_{{$t->barra}}_categoria" 
						            	class="fa fa-check" 
						            	aria-hidden="true">
						            </span>
						        </button>
						    </span>

						</div>
					</td>
					<td style="width: 300px;">
						<div class="col-xs-12 input-group" >
						    <select id="idmolecula_{{$t->barra}}" 
						    	class="form-control">
					
					    		@foreach ($molecula as $mol)
					    			@if ($t->molecula == $mol->descrip)
										<option selected value="{{$mol->descrip}}">
											{{$mol->descrip}}
										</option>
						    		@else
					    				<option value="{{$mol->descrip}}">
											{{$mol->descrip}}
										</option>
					    			@endif
					    		@endforeach

					    	</select>

						    <span class="input-group-btn" onclick='tdclick(event);'>
						        <button id="idmolecula1_{{$t->barra}}_molecula" 
						        	type="button" 
						        	class="btn btn-pedido" 
						        	data-toggle="tooltip"  
						        	title="Grabar molecula" >
						            <span id="idmolecula2_{{$t->barra}}_molecula" 
						            	class="fa fa-check" 
						            	aria-hidden="true">
						            </span>
						        </button>
						    </span>
						</div>
						<div class="col-xs-12 input-group" >

					    	<input type="text" 
					    		style="text-align: right;" 
                                id="idunidadmolecula_{{$t->barra}}"
                                value="{{$unidadmolecula}}"
                                class="form-control" >

						    <span class="input-group-btn" 
						    	onclick='tdclick(event);'>
						        <button id="idmolecula1_{{$t->barra}}_unidadmolecula" 
						        	type="button" 
						        	class="btn btn-pedido" 
						        	data-toggle="tooltip"  
						      		title="Grabar unidad" >
						            <span id="idmolecula2_{{$t->barra}}_unidadmolecula" 
						            	class="fa fa-check" 
						            	aria-hidden="true">
						            </span>
						        </button>
						    </span>
						</div>
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
    var barra = id[1];
    var campo = id[2];
    var	valor = $('#id' + campo + '_'+barra).val();
    //alert('Barra: ' + barra + ' Campo: ' + campo + ' Valor: ' + valor);
    $.ajax({
	  type:'POST',
	  url:'./prodcaract/caract/modcaract',
	  dataType: 'json', 
	  encode  : true,
	  data: {barra:barra, campo:campo, valor:valor },
	  success:function(data) {
	    if (data.msg != "") {
	        alert(data.msg);
	    } 
	  }
  	});
}
</script>
@endpush

@endsection