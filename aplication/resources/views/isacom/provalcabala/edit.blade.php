@extends ('layouts.menu')
@section ('contenido')

{!!Form::model($tabla,['method'=>'PATCH','route'=>['provalcabala.update',$tabla->id]])!!}
{{Form::token()}}

<div class="row">

	<div class="col-xs-4">
		<div class="form-group">
			<label>ID</label>
			<input readonly type="text" name="id" value="{{$tabla->id}}" class="form-control">
		</div>
	</div>

	<div class="col-xs-8">
		<div class="form-group">
			<label>Cliente</label>
			<input readonly type="text" value="{{$tabla->nomcli}}" class="form-control">
		</div>
	</div>

	<div class="col-xs-12">
		<div class="form-group">
			<label>Dirección</label>
			<input readonly type="text" value="{{$cliente->direccion}}" class="form-control">
		</div>
	</div>

	<div class="col-xs-4">
		<div class="form-group">
			<label>Télefono</label>
			<input readonly type="text" value="{{$cliente->telefono}}" class="form-control">
		</div>
	</div>

	<div class="col-xs-4">
		<div class="form-group">
			<label>Contacto</label>
			<input readonly type="text" value="{{$cliente->contacto}}" class="form-control">
		</div>
	</div>

	<div class="col-xs-4">
		<div class="form-group">
			<label>Rif</label>
			<input readonly type="text" value="{{$cliente->rif}}" class="form-control">
		</div>
	</div>

	<div class="col-xs-4">
		<div class="form-group">
			<label>Correo</label>
			<input readonly type="text" value="{{$tabla->usuario}}" class="form-control">
		</div>
	</div>

	<div class="col-xs-4">
		<div class="form-group">
			<label>Enviado</label>
			<input readonly type="text" value="{{date('d-m-Y H:i', strtotime($tabla->fecenviado))}}" class="form-control">
		</div>
	</div>

	<div class="col-xs-4">
		<div class="form-group">
			<label>Monto del pedido</label>
			<input readonly style="text-align: right;" type="text" value="{{number_format($provped[0]['total'], 2, '.', ',')}}" class="form-control">
		</div>
	</div>

	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
		<div class="form-group">
			<label>Observación</label>
			<input type="text" name="observ" class="form-control" value="">
		</div>
	</div>

	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
	    <div class="form-group">
	    	<label>Acción</label>
	    	<select name="status" class="form-control">
	    		<option value="RECIBIDO">RECIBIDO</option>
	    		<option value="ANULADO">ANULADO</option>
	    	</select>
	    </div>
	</div>
	
</div>

<!-- BOTON GUARDAR/CANCELAR -->
<br>
<div class="form-group" style="margin-top: 20px;">
    <button type="button" class="btn-normal" onclick="history.back(-1)">Regresar</button>
	<button class="btn-confirmar" type="submit">Procesar</button>
</div>

{{Form::close()}}

@push ('scripts')
<script>
$('#subtitulo').text('{{$subtitulo}}');
</script>
@endpush

@endsection