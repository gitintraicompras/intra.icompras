@extends('layouts.menu')
@section ('contenido') 
@php
  $moneda = Session::get('moneda', 'BSS');
  $factor = RetornaFactorCambiario('', $moneda);
@endphp
 
<div class="row" style="margin-bottom: 5px;">
	
	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
		@include('isacom.pedidodirecto.search')
	</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead class="colorTitulo">
					<th>#</th>
					<th style="width:140px;">OPCION</th>
					<th title="IDENTIFICADOR UNICO DEL PEDIDO">ID</th>
					<th title="IDENTIFICADOR UNICO DEL PEDIDO DEL GRUPO">IDGRP</th>
					<th title="FECHA DEL PEDIDO">FECHA</th>
					<th title="FECHA DE ENVIO DEL PEDIDO">ENVIADO</th>
					<th title="ESTATUS DEL PEDIDO">ESTADO</th>
					<th title="TIPO DE PEDIDO">TIPO</th>
					<th title="MARCA O LABORATORIO DEL PEDIDO">MARCA</th>
					<th title="CANTIDAD DE RENGLONES DEL PEDIDO">RENGLON</th>
					<th title="CANTIDAD DE UNIDADES DEL PEDIDO">UNIDADES</th>
					<th title="MONTO TOTAL DEL PEDIDO">TOTAL</th>
					<th title="FACTOR CAMBIARIO DEL PEDIDO">FACTOR</th>
				</thead>
				@foreach ($tabla as $t)
				<tr>
			
					<td>{{$loop->iteration}}</td>
					<td>
						<!-- CONSULTA DE PEDIDO --> 
                        <a href="{{URL::action('PedidodirectoController@show',$t->id)}}">
                        	<button class="btn btn-pedido fa fa-file-o" data-toggle="tooltip" title="Consultar pedido">
                        	</button>
                        </a>

						<!-- ELIMINAR PEDIDO -->
						<a href="" 
							data-target="#modal-delete-{{$t->id}}" 
							data-toggle="modal">
							<button class="btn btn-pedido fa fa-trash-o" 
								data-toggle="tooltip" 
								title="Eliminar pedido directo">
							</button>
						</a>

						@if ($t->estado == 'ABIERTO')
						<a href="" data-target="#modal-tomar-{{$t->id}}" data-toggle="modal">
							<button class="btn btn-pedido fa fa-check" 
								data-toggle="tooltip" 
								title="Tomar pedido directo">
							</button>
						</a>
						@endif
						
                        @if ($t->estado == 'NUEVO') 
							<!-- MODIFICAR PEDIDO -->
							<a href="{{URL::action('PedidodirectoController@edit',$t->id)}}">
								<button class="btn btn-pedido fa fa-pencil" 
									data-toggle="tooltip" 
									title="Modificar pedido directo">
								</button>
							</a>
						@endif

					</td>
					<td>{{$t->id}}</td>
					<td>{{$t->idpedgrupo}}</td>
					<td>{{date('d-m-Y H:i', strtotime($t->fecha))}}</td>
					<td>{{date('d-m-Y H:i', strtotime($t->fecenviado))}}</td>
					@if ($t->estado == 'NUEVO')
						<td style="color: red;">{{$t->estado}}</td>
					@else
						<td>{{$t->estado}}</td>
					@endif
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
						<td align="right">{{number_format($t->total/$factorPed, 2, '.', ',')}}</td>
						<td align="right">{{number_format($factorPed, 2, '.', ',')}}</td>
					@else
						<td align="right">{{number_format($t->total/$factor, 2, '.', ',')}}</td>
						<td align="right">{{number_format($factor, 2, '.', ',')}}</td>
					@endif
				</tr>
				@include('isacom.pedidodirecto.delete')
				@include('isacom.pedidodirecto.tomar')
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