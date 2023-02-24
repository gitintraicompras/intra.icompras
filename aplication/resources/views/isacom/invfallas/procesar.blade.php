@extends ('layouts.menu')
@section ('contenido')


{!! Form::open(array('url'=>'/invfallas','method'=>'POST','autocomplete'=>'off', 'enctype'=>'multipart/form-data')) !!}
{{ Form::token() }}
<input hidden name="modalidad" value="PROCESAR" type="text">
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
   
   	<div class="row">

   	    <div class="col-xs-6">
			<div class="form-group">
		    	<label>Criterio</label>
		    	<select name="criterio" class="form-control">
		    		<option value="PRECIO">PRECIO</option>
		    		<option value="INVENTARIO">INVENTARIO</option>
		    		<option value="DIAS">DIAS</option>
		    	</select>
		    </div>
	    </div>

	    <div class="col-xs-6">
			<div class="form-group">
		    	<label>Preferencia</label>
		    	<select name="preferencia" class="form-control">
		    		<option value="NINGUNA">NINGUNA</option>
		    		<option value="PRIMER">PRIMER PROVEEDOR</option>
		    	</select>
		    </div>
	    </div>

    </div>

</div>

<br><br><br><br><br>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	<div class="form-group">
		<div class="form-group">
		    <button type="button" class="btn-normal" onclick="history.back(-1)" data-toggle="tooltip" title="Regresar">Regresar</button>
			<button class="btn-confirmar" type="submit" data-toggle="tooltip" title="Confirmar proceso">
			Confirmar
			</button>
		</div>
	</div>
</div>
{{ Form::close() }}

@push ('scripts')
<script>
$('#subtitulo').text('{{$subtitulo}}');
</script>
@endpush

@endsection