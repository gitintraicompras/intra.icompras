@extends ('layouts.menu')
@section ('contenido')
{!!Form::model($reg,['method'=>'PATCH','route'=>['vendedor.update',$reg->codvendedor]])!!}
{{Form::token()}}
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	<div class="row">
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
			<div class="form-group">
				<label>Código</label>
				<input readonly value="{{$reg->codvendedor}}" 
					type="text" 
					name="codvendedor" 
					class="form-control">
			</div>
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
			<div class="form-group">
				<label>Nombre</label>
				<input value="{{$reg->nombre}}" 
					type="text" 
					name="nombre" 
					class="form-control">
			</div>
		</div>
	</div>
</div>
<!-- BOTON REGRESAR/GUARDAR -->
<div class="form-group" style="margin-top: 20px; margin-left: 15px;">
    <button type="button" class="btn-normal" onclick="history.back(-1)">Regresar</button>
   	<button class="btn-confirmar" type="submit">Guardar</button>
</div>
{{Form::close()}}
@push ('scripts')
<script>
$('#subtitulo').text('{{$subtitulo}}');
</script>
@endpush

@endsection
