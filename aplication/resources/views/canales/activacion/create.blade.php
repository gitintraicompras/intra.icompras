@extends ('layouts.menu')
@section ('contenido')
{!! Form::open(array('url'=>'/canales/activacion','method'=>'POST','autocomplete'=>'off')) !!}
{{ Form::token() }}
<div class="row">
	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
		<div class="form-group">
			<label>Rif (** J######### **)</label>
			<input name="rif" 
				type="text" 
				autofocus 
				class="form-control" 
				style="background-color: #FFE7E5">
		</div>
	</div>
</div>
<div class="form-group">
	<a href="{{URL::action('Canales\ActivacionController@index')}}">
	    <button type="button" 
	    	class="btn-normal" 
	    	data-toggle="tooltip" 
	    	title="Regresar">
	    	Regresar
	    </button>
	</a>
	<button class="btn-confirmar" 
		type="submit" 
		data-toggle="tooltip" 
		title="Verificar rif">
		Verificar
	</button>
</div>
<br>
{{ Form::close() }}
@push ('scripts')
<script>
$('#subtitulo').text('{{$subtitulo}}');
</script>
@endpush
@endsection