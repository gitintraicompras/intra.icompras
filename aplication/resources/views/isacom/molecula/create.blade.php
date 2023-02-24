@extends ('layouts.menu')
@section ('contenido')

{!! Form::open(array('url'=>'/molecula','method'=>'POST','autocomplete'=>'off')) !!}
{{ Form::token() }}
<div class="row">

	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
		<div class="form-group">
	        <label>ID</label>
            <input readonly type="text" 
            	class="form-control" 
            	value="*">
	    </div>
    </div>

	<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
		<div class="form-group">
			<label>Descripción</label>
			<input type="text" name="descrip" class="form-control">
		</div>
	</div>
</div>

<!-- BOTON REGRESAR/GUARDAR -->
<div class="form-group" style="margin-top: 20px;">
    <button type="button" class="btn-normal" onclick="history.back(-1)">Regresar</button>
 	<button class="btn-confirmar" type="submit">Guardar</button>
</div>
{{ Form::close() }}

@push ('scripts')
<script>
$('#subtitulo').text('{{$subtitulo}}');
</script>
@endpush

@endsection