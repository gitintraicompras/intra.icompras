@extends('layouts.menu')
@section ('contenido')

<div class="col-xs-12">
	<div class="row">

	@if (isset($sugerido))
	<!-- BARRA DE BOTONES -->
	<div class="btn-toolbar" role="toolbar" style="margin-top: 12px;margin-bottom: 3px;">
	    <div class="btn-group" role="group" style="width: 100%;">

			@if ($sugerido->count() > 0)
				@include('isacom.invsugerido.search')

				<!-- LIMPIAR SUGERIDO -->
				<a href="" data-target="#modal-delete-{{$codcli}}" data-toggle="modal">
		            <button class="btn-normal" data-toggle="tooltip" title="Eliminar sugerido">Eliminar
		            </button>
				</a>
				@include('isacom.invsugerido.delete')

				<!-- DESCARGAR SUGERIDO-->
		        <a href="{{url('inventario/sugerido/descargar')}}">
		            <button style="margin-right: 3px;" type="button" data-toggle="tooltip" title="Descargar sugerido" class="btn-normal">
		                Descargar
		            </button>
		        </a>
	        @endif

	        <!-- CREAR SUGERIDO-->
	        <a href="{{url('inventario/sugerido/crear')}}">
	            <button style="margin-right: 3px;" type="button" data-toggle="tooltip" title="Crear sugerido" class="btn-normal">
	                Crear
	            </button>
	        </a>

	        <!-- PROCESAR SUGERIDO-->
	        @if ($sugerido->count() > 0)
	        <a href="{{url('inventario/sugerido/procesar')}}">
	            <button style="margin-right: 3px;" type="button" data-toggle="tooltip" title="Procesar sugerido y convertirlo en pedido" class="btn-confirmar">
	                Procesar
	            </button>
	        </a>
			@endif

		</div>
	</div>

	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-condensed table-hover">
					<thead class="colorTitulo">
			
						<th>#</th>
						<th style="width: 100px;" class="hidden-xs">
							&nbsp;&nbsp;IMAGEN&nbsp;&nbsp;
						</th>
						<th style="width: 170px;">
							SUGERIDO
						</th>
						<th>PRODUCTO</th>
						<th>CODIGO</th>
						<th>BARRA</th>
						<th>MARCA</th>
						<th>CATEGORIA</th>
					
					</thead>
					@foreach ($sugerido as $t)
					@php
					$cantidad = 0;
					$vmd = 0.0;
					$transito = verificarProdTransito($t->barra, $codcli, "");
					$minmax = LeerMinMax($codcli, $t->codprod);
   					$min = $minmax["min"];
   					$max = $minmax["max"];
   					$cendis = $minmax["cendis"];
					$inv = LeerInventarioCodigo($t->codprod, $codcli);
					if (!is_null($inv)) {
						$cantidad = $inv->cantidad;
						$vmd = $inv->vmd;
					}
					@endphp
					<tr>
					
						<td>{{$loop->iteration}}</td>
						<td class="hidden-xs">
	                        <div align="center">

	                            <a href="{{URL::action('PedidoController@verprod',$t->barra)}}">
	                    
	                                <img src="http://isaweb.isbsistemas.com/public/storage/prod/{{NombreImagen($t->barra)}}" 
                                    width="100%" 
                                    height="100%" 
                                    class="img-responsive" 
                                    alt="icompras360"
                                    style="border: 2px solid #D2D6DE;"
                                    oncontextmenu="return false" >
	                    
	                            </a>

	                        </div>
	                    </td>
				
						<td>
	                        <span class="input-group-addon" 
	                        	style="margin: 0px; width: 160px;">
	                            <div class="col-xs-12 input-group input-group-sm" 
	                            	style="margin: 0px; width: 160px;">
	                                <input type="number" 
	                                	style="text-align: center; color: #000000; width: 80px;" 
	                                	id="idPedir-{{$t->id}}" 
	                                	value="{{$t->pedir}}" 
	                                	class="form-control" 
	                                >
	                                <button type="button" 
	                                	class="btn btn-pedido BtnModificar" 
	                                	id="idModificar-{{$t->id}}" 
	                                	data-toggle="tooltip" 
	                                	title="Modificar cantidad">
	                                    <span 
	                                        class="fa fa-check" 
	                                        id="idModificar-{{$t->id}}" 
	                                        aria-hidden="true" >
	                                    </span>
	                                    <a href="" 
	                                    	data-target="#modal-deleteprod-{{$t->id}}" 
	                                    	data-toggle="modal">
	                                        <button class="btn btn-pedido fa fa-trash-o" 
	                                        	style="height: 2pc;" 
	                                        	data-toggle="tooltip" 
	                                        	title="Eliminar producto">
	                                       	</button>
	                                    </a>
	                                </button>
	                            </div>
	                        </span>
	                    </td>

	               		<td>
	               			<b>{{$t->desprod}}</b><br>
	               			<span>
	               				CANT: {{number_format($cantidad, 0, '.', ',')}}&nbsp;&nbsp;&nbsp;
	               				TRAN: {{number_format($transito, 0, '.', ',')}}&nbsp;&nbsp;&nbsp;
	               				VMD: {{number_format($vmd, 4, '.', ',')}}
	               			</span><br>
	               			<span>
	               				MIN: {{number_format($min, 0, '.', ',')}}&nbsp;&nbsp;&nbsp;
	               				MAX: {{number_format($max, 0, '.', ',')}}&nbsp;&nbsp;&nbsp;
	               				@if ($cendis >0)
	               					<i class="fa fa-check-square-o" aria-hidden="true"></i>
	               					&nbsp;CENDIS
	               				@endif
	               			</span>
	               		</td>
						<td>{{$t->codprod}}</td>
						<td>{{$t->barra}}</td>
						<td>{{isset($inv->marca) ? $inv->marca : "" }}</td>
						<td>{{isset($inv->categoria) ? $inv->categoria : "" }}</td>
					</tr>
					@include('isacom.invsugerido.deleprod')
					@endforeach
				</table>

				<div align='left'>
					@if (isset($sugerido))
	                	{{$sugerido->appends(["filtro" => $filtro])->links()}}
	                @endif
	            </div><br>
	            
			</div>
		</div>
	</div>
	@endif
	</div>
</div>

@push ('scripts')
<script>
$('#subtitulo').text('{{$subtitulo2}}');
window.onload = function() {
	$('.BtnModificar').on('click',function(e) {
        var idx = e.target.id.split('-');
        var id = idx[1];
        var pedir = $('#idPedir-'+id).val();
        if (parseInt(pedir) <= 0) {
            alert("CANTIDAD A PEDIR NO PUEDE SER MENOR O IGUAL CERO");
            $('#idPedir-'+id).val(pedir);
        } else {
            $.ajax({
                type:'POST',
                url:'./inventario/sugerido/modificaritem',
                dataType: 'json', 
                encode  : true,
                data: {id:id, pedir:pedir },
                success:function(data){
                	if (data.msg != "") {
                        alert(data.msg);
                        location.reload(true);
                    }   
                }
            });
        }
    });
}

</script>
@endpush

@endsection