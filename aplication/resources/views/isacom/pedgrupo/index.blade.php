@extends('layouts.menu')
@section ('contenido') 
@php
  $moneda = Session::get('moneda', 'BSS');
  $factor = RetornaFactorCambiario('', $moneda);
  $tipedido = (Auth::user()->userPedDirecto == 1 ) ? "D" : "N";
@endphp

<div class="row" style="margin-bottom: 5px;">

	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
		@include('isacom.pedgrupo.create')
	</div>

	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
		@include('isacom.pedgrupo.search')
	</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead class="colorTitulo">
					<th>#</th>
					<th style="width:180px;">OPCION</th>
					<th>ID</th>
					<th>FECHA</th>
					<th>ENVIADO</th>
					<th>ESTADO</th>
					<th>MARCA</th>
					<th>REPOSICION</th>
					<th>GRUPO</th>
				</thead>
				@foreach ($tabla as $t)
				<tr>
			
					<td>{{$loop->iteration}}</td>
					<td>
						<!-- CONSULTA DE PEDIDO --> 
                        <a href="{{URL::action('PedGrupoController@show',$t->pgid)}}">
                        	<button class="btn btn-pedido fa fa-file-o" 
                        		data-toggle="tooltip" 
                        		title="Consultar pedido">
                        	</button>
                        </a>

                  		<!-- ELIMINAR PEDIDO -->
						<a href="" 
							data-target="#modal-delete-{{$t->pgid}}" 
							data-toggle="modal">
							<button class="btn btn-pedido fa fa-trash-o" 
								data-toggle="tooltip" 
								title="Eliminar pedido del grupo">
							</button>
						</a>

			            @if ($t->estado == 'NUEVO' || $t->estado == 'GUARDADO') 
							<!-- MODIFICAR PEDIDO -->
							<a href="{{URL::action('PedGrupoController@edit',$t->pgid)}}">
								<button class="btn btn-pedido fa fa-pencil" 
									data-toggle="tooltip" 
									title="Modificar pedido directo">
								</button>
							</a>
						@endif

					</td>
					<td>{{$t->pgid}}</td>
					<td>{{date('d-m-Y H:i', strtotime($t->fecha))}}</td>
					<td>{{date('d-m-Y H:i', strtotime($t->enviado))}}</td>
					@if ($t->estado == 'NUEVO')
						<td style="color: red;">{{$t->estado}}</td>
					@else
						<td>{{$t->estado}}</td>
					@endif
					<td>{{$t->marca}}</td>
					<td align="right">{{number_format($t->reposicion, 0, '.', ',')}}</td>
					<td>{{$t->codgrupo}}-{{$t->nomgrupo}}</td>
				</tr>
				@include('isacom.pedgrupo.delete')
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
$('#titulo').text('{{$subtitulo}}');
$('#subtitulo').text('{{$subtitulo2}}');
</script>
@endpush

@endsection