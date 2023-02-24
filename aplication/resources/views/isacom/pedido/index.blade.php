@extends('layouts.menu')
@section ('contenido') 
@php
  $moneda = Session::get('moneda', 'BSS');
  $factor = RetornaFactorCambiario('', $moneda);
  $tipedido = (Auth::user()->userPedDirecto == 1 ) ? "D" : "N";
@endphp

<div class="row" style="margin-bottom: 5px;">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
		<a href="{{url('/pedido/create')}}">
			<button class="btn-normal" 
				data-toggle="tooltip" 
				title="Pedido nuevo"
				style="width: 120px;">
				Pedido Nuevo
			</button> 
		</a>
	</div>
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
		@include('isacom.pedido.search')
	</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead class="colorTitulo">
					<th>#</th>
					@if ($botonExportar)
						<th style="width:180px;">OPCION</th>
					@else
						<th style="width:140px;">OPCION</th>
					@endif
					<th>ID</th>
					<th>FECHA</th>
					<th>ENVIADO</th>
					<th>ESTADO</th>
					<th>ORIGEN</th>
					<th>TIPO</th>
					<th>MARCA</th>
					<th>RENGLON</th>
					<th>UNIDADES</th>
					<th>AHORRO</th>
					<th>TOTAL</th>
					<th>FACTOR</th>
				</thead>
				@foreach ($tabla as $t)
				<tr>
			
					<td>{{$loop->iteration}}</td>
					<td>
						<!-- CONSULTA DE PEDIDO --> 
                        <a href="{{URL::action('PedidoController@show',$t->id)}}">
                        	<button class="btn btn-pedido fa fa-file-o" data-toggle="tooltip" title="Consultar pedido">
                        	</button>
                        </a>

						<!-- ELIMINAR PEDIDO -->
						<a href="" 
							data-target="#modal-delete-{{$t->id}}" 
							data-toggle="modal">
							<button class="btn btn-pedido fa fa-trash-o" 
								data-toggle="tooltip" 
								title="Eliminar pedido">
							</button>
						</a>

						@if ($tipedido == 'N')
							@if ($botonExportar)
								<!-- EXPORTAR PEDIDO -->
								<a href="{{URL::action('PedidoController@exportar',$t->id)}}">
		                        	<button class="btn btn-pedido fa fa-share-square-o" data-toggle="tooltip" title="Exportar pedido">
		                        	</button>
		                        </a>
	                        @endif
                        @endif
                
                        @if ($t->estado == 'NUEVO' || $t->estado == 'PARCIAL') 
							<!-- MODIFICAR PEDIDO -->
							<a href="{{URL::action('PedidoController@edit',$t->id)}}">
								<button class="btn btn-pedido fa fa-pencil" 
									data-toggle="tooltip" 
									title="Modificar pedido">
								</button>
							</a>
						@endif

					</td>
					<td>{{$t->id}}</td>
					<td>{{date('d-m-Y H:i', strtotime($t->fecha))}}</td>
					<td>{{date('d-m-Y H:i', strtotime($t->fecenviado))}}</td>
					@if ($t->estado == 'NUEVO')
						<td style="color: red;">{{$t->estado}}</td>
					@else
						<td>{{$t->estado}}</td>
					@endif
					<td>{{$t->origen}}</td>
					<td>{{$t->tipedido}}</td>
					<td>{{($t->marca == '') ? 'N/A' : $t->marca}}</td>
					<td align="right">{{number_format($t->numren, 0, '.', ',')}}</td>
					<td align="right">{{number_format($t->numund, 0, '.', ',')}}</td>
					@if ($moneda == "USD")
						@php
						$factorPed = $t->factor;
						if ($factorPed == 1)
							$factorPed = $factor;
						@endphp
						<td align="right">{{number_format($t->ahorro/$factorPed, 2, '.', ',')}}</td>
						<td align="right">{{number_format($t->total/$factorPed, 2, '.', ',')}}</td>
						<td align="right">{{number_format($factorPed, 2, '.', ',')}}</td>
					@else
						<td align="right">{{number_format($t->ahorro/$factor, 2, '.', ',')}}</td>
						<td align="right">{{number_format($t->total/$factor, 2, '.', ',')}}</td>
						<td align="right">{{number_format($factor, 2, '.', ',')}}</td>
					@endif
					<!--
					<td align="right">{{number_format(dTotalPedido($t->id), 2, '.', ',')}}</td>
					--!-->
				</tr>
				@include('isacom.pedido.delete')
				@endforeach
			</table>
			<div align='right'>
            	{{$tabla->render()}}
            </div><br>
		</div>
	</div>
</div>

@push ('scripts')
<script>
$('#subtitulo').text('{{$subtitulo}}');
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config', 'G-CE7C5GBHWG');
</script>
@endpush

@endsection