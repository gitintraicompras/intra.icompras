@extends ('layouts.menu')
@section ('contenido')
  

{!! Form::open(array('url'=>'/invsugerido','method'=>'POST','autocomplete'=>'off', 'enctype'=>'multipart/form-data')) !!}
{{ Form::token() }}
<input hidden name="modalidad" value="CREAR" type="text">
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
   
   	<div class="row">

   		<div class="col-xs-3">
			<div class="form-group">
				<label>Codigo</label>
				<input type="text"
					name="codcli" 
					readonly="" 	 
					value="{{$codcli}}" 
					class="form-control">
			</div>
		</div>

   		<div class="col-xs-3">
			<div class="form-group">
				<label>Dias de reposición</label>
				<input type="number" name="reposicion" value="7" class="form-control">
			</div>
		</div>

		<div class="col-xs-6">
			<div class="form-group">
				<label>Aplicar VMD por defecto a los productos con valor cero</label>
				<input type="text" name="vmd" value="{{number_format(0.0000, 4, '.', ',')}}" class="form-control">
			</div>
		</div>


	    <div class="col-xs-4">
			<div class="form-group">
				<label>Descripción</label>
				<input type="text" name="desprod" value="" class="form-control">
			</div>
		</div>

		<div class="col-xs-4">
			<div class="form-group">
				<label>Marca</label>
				<select name="marca" 
					class="form-control selectpicker" 
		    		data-live-search="true" >
		    		<option select value="">&nbsp;</option>
		    		@foreach($marca as $m)
		   				<option value="{{$m->descrip}}">
							{{$m->descrip}}
						</option>
			 		@endforeach
		    	</select>
			</div>
		</div>

		<div class="col-xs-4">
			<div class="form-group">
				<label>Categoria</label>
				<select name="categoria" 
					class="form-control selectpicker" 
		    		data-live-search="true" >
		    		<option select value="">&nbsp;</option>
		    		@foreach($categoria as $c)
		   				<option value="{{$c->descrip}}">
							{{$c->descrip}}
						</option>
			 		@endforeach
		    	</select>
			</div>
		</div>

    </div>

</div>


<br><br><br><br><br><br><br><br>
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