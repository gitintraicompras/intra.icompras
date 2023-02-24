@extends ('layouts.menu')
@section ('contenido')

<div class="row">
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
		<a href="vendedor/create">
			<button class="btn-normal" data-toggle="tooltip" title="Nuevo proveedor">
			Nuevo
			</button>
		</a>
		<button type="button" class="btn-normal" onclick="history.back(-1)" data-toggle="tooltip" title="Regresar">
			Regresar
		</button>
	</div>

	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
		@include('canales.vendedor.search')
	</div>
</div>


<div class="clearfix"></div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead class="colorTitulo">
					<th style="width: 30px;">#</th>
					<th style="width: 110px;">OPCION</th>
					<th>CODIGO</th>
					<th>NOMBRE</th>
				</thead>
				@foreach ($regs as $reg)
				<tr>
					<td>{{$loop->iteration}}</td>
					<td>
						<a href="{{URL::action('Canales\VendedorController@edit',$reg->codvendedor)}}">
							<button class="btn btn-default fa fa-pencil" 
								data-toggle="tooltip" 
								title="Editar vendedor">
							</button>
						</a>

						<a href="" 
							data-target="#modal-delete-{{$reg->codvendedor}}" 
							data-toggle="modal">
							<button class="btn btn-default fa fa-trash-o" 
								data-toggle="tooltip" 
								title="Eliminar vendedor">
							</button>
						</a>

					</td>
					<td>{{$reg->codvendedor}}</td>
					<td>{{$reg->nombre}}</td>
				</tr>
				@include('canales.vendedor.delete')
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
