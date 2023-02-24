@extends('layouts.menu')
@section ('contenido')
<div class="row">
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
		<a href="molecula/create">
			<button class="btn btn-normal" data-toggle="tooltip" title="Agregar Nueva molecula">
			Agregar
			</button>
		</a>
		<button type="button" class="btn btn-normal" onclick="history.back(-1)" data-toggle="tooltip" title="Regresar">Regresar</button>
	</div>

	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
		@include('isacom.molecula.search')
	</div>
</div>

<div class="clearfix"></div>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead class="colorTitulo">
					<th style="width: 30px;">#</th>
					<th style="width: 140px;">OPCION</th>
					<th>DESCRIPCION</th>
				</thead>
				@foreach ($tabla as $t)
				<tr>
					<td>{{$loop->iteration}}</td>
					<td>
						<a href="{{URL::action('MoleculaController@show',$t->id)}}">
							<button class="btn btn-pedido fa fa-file-o" 
								title="Consultar ...">
							</button>
						</a>

						<a href="{{URL::action('MoleculaController@edit',$t->id)}}">
							<button class="btn btn-pedido fa fa-pencil" 
								title="Modificar ...">
							</button>
						</a>

						<a href="" data-target="#modal-delete-{{$t->id}}" data-toggle="modal">
							<button class="btn btn-pedido fa fa-trash-o" 
								data-toggle="tooltip" 
								title="Eliminar ...">
							</button>
						</a>
					</td>
					<td>{{$t->descrip}}</td>
				</tr>
				@include('isacom.molecula.delete')
				@endforeach
			</table>
		</div>
	</div>
</div>

@push ('scripts')
<script>
$('#subtitulo').text('{{$subtitulo}}');
</script>
@endpush

@endsection


