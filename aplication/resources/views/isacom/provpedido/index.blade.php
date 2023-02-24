@extends ('layouts.menu')
@section ('contenido')

@php
  $moneda = Session::get('moneda', 'BSS');
  $factor = RetornaFactorCambiario($codprove, $moneda);
@endphp


<div class="row">
	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
		@include('isacom.provpedido.search')
	</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead class="colorTitulo">
					<th>#</th>
					<th style="width:140px;">OPCION</th>
					<th>PEDIDO</th>
					<th>CLIENTE</th>
					<th>ENVIADO</th>
					<th>CODCLI</th>
					<th>ORIGEN</th>
					<th>STATUS</th>
					<th>TOTAL</th>
				</thead>
				@foreach ($tabla as $t)
				<tr>
					<td>{{$loop->iteration}}</td>
					<td>
						<!-- CONSULTA DE PEDIDO -->
                        <a href="{{URL::action('ProvpedidoController@show',$t->id)}}">
                        	<button class="btn btn-pedido fa fa-file-o" data-toggle="tooltip" title="Consultar pedido">
                        	</button>
                        </a>

                        <!-- DESCARGAR PEDIDO -->
                        <a href="{{URL::action('ProvpedidoController@descargar',$t->id)}}">
                        	<button class="btn btn-pedido fa fa-download" data-toggle="tooltip" title="Descargar pedido en pdf">
                        	</button>
                        </a>

                        <!-- ELIMINAR PEDIDO -->
            			<a href="" data-target="#modal-delete-{{$t->id}}" data-toggle="modal">
							<button class="btn btn-pedido fa fa-trash-o" data-toggle="tooltip" title="Eliminar pedido"></button>
						</a>
				
					</td>
					<td>{{$t->id}}</td>
					<td>{{$t->nomcli}}</td>
					<td>{{date('d-m-Y H:i', strtotime($t->fecenviado))}}</td>
					<td>{{$t->codcli}}</td>
					<td>{{$t->origen}}</td>
					<td>{{$t->estado}}</td>
					<td align="right">
						{{number_format($t->total/$factor , 2, '.', ',')}}
					</td>
				</tr>
				@include('isacom.provpedido.delete')
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
</script>
@endpush

@endsection