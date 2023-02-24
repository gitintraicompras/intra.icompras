@extends ('layouts.menu')
@section ('contenido')

{!!Form::model($proveedor,['method'=>'PATCH','route'=>['provconfig.update',$proveedor->codprove]])!!}
{{Form::token()}}

<div class="row">
	@if (count($errors)>0)
	<div class="alert alert-danger">
		<ul>
			@foreach ($errors->all() as $error)
			<li>{{$error}}</li>
			@endforeach
		</ul>
	</div>
	@endif

	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 10px;">
		<ul class="nav nav-tabs" >
		    <li class="nav-item">
		        <a class="nav-link active" data-toggle="tab" href="#menu1">BASICA</a>
		    </li>
		    <li class="nav-item">
		        <a class="nav-link" data-toggle="tab" href="#menu2">CONFIGURACION</a>
		    </li>
		</ul>
	</div>

	<!-- Tab panes -->
	<div style="margin-top: 10px;" class="tab-content" >
	    <div id="menu1" class="container tab-pane active" style="width: 100%;">

			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<div class="form-group">
					<label for="codprove">Código</label>
					<input readonly value="{{$proveedor->codprove}}" type="text" name="codprove" class="form-control">
				</div>
			</div>

			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<div class="form-group">
					<label for="nombre">Nombre corto</label>
					<input value="{{$proveedor->descripcion}}" type="text" name="descripcion" class="form-control">
				</div>
			</div>

			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<div class="form-group">
					<label>Nombre largo</label>
					<input value="{{$proveedor->nombre}}" type="text" name="nombre" class="form-control">
				</div>
			</div>

			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<div class="form-group">
					<label>Dirección</label>
					<input value="{{$proveedor->direccion}}" type="text" name="direccion" class="form-control">
				</div>
			</div>

			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<div class="form-group">
					<label>Teléfono</label>
					<input value="{{$proveedor->telefono}}" type="text" name="telefono" class="form-control">
				</div>
			</div>

			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<div class="form-group">
					<label>Contacto</label>
					<input value="{{$proveedor->contacto}}" type="text" name="contacto" class="form-control">
				</div>
			</div>

			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<div class="form-group">
					<label>Correo</label>
					<input value="{{$proveedor->correo}}" type="text" name="correo" class="form-control">
				</div>
			</div>

			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<div class="form-group">
					<label>Pagina web</label>
					<input value="{{$proveedor->web}}" type="text" name="web" class="form-control">
				</div>
			</div>

			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<div class="form-group">
					<label>Localidad</label>
					<input value="{{$proveedor->localidad}}" type="text" name="localidad" class="form-control">
				</div>
			</div>
		
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<div class="form-group">
					<label >Status</label>
					<input readonly value="{{$proveedor->status}}" type="text" name="status" class="form-control">
				</div>
			</div>

			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<div class="form-group">
					<label>CORREO->nombre para los pedidos</label>
					<input type="text" name="correoEnvioPedido" class="form-control" value="{{$proveedor->correoEnvioPedido}}"
					title = "Clave del usuario de la base de datos">
				</div>
			</div>
		</div>

		<div id="menu2" class="container tab-pane" style="width: 100%;">

			<!-- Color Picker -->
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<div class="form-group">
                    <label>Color fondo:</label><br>
                    <input name="backcolor" value="{{$proveedor->backcolor}}" data-jscolor="{preset:'small dark', position:'right'}">
                </div>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<div class="form-group">
                    <label>Color letra:</label><br>
                    <input name="forecolor" value="{{$proveedor->forecolor}}" data-jscolor="{preset:'small dark', position:'right'}">
                </div>
            </div>

			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<div class="form-group">
					<label for="opc3">Ruta logo (blanco)</label>
					<input value="{{$proveedor->rutalogo1}}" type="text" name="rutalogo1" class="form-control">
				</div>
			</div>

			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<div class="form-group">
					<label for="opc4">Ruta logo (normal)</label>
					<input value="{{$proveedor->rutalogo2}}" type="text" name="rutalogo2" class="form-control">
				</div>
			</div>

			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<div class="form-group">
					<label>Código sede sucursal</label>
					<input value="{{$proveedor->codsede}}" type="text" name="codsede" class="form-control">
				</div>
			</div>

			<div class="row col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
					<div class="form-group">
						<label >Modo cambiario:</label>
						<input readonly name="factorModo" value="{{$proveedor->factorModo}}" type="text" class="form-control">
					</div>
				</div>

				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
					<div class="form-group">
						<label>factor Seleccion</label>
						<input readonly name="factorSeleccion" value="{{$proveedor->factorSeleccion}}" type="text"  class="form-control">
					</div>
				</div>

				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
					<div class="form-group">
						<label>Factor cambiario</label>
						<input name="FactorCambiario" 
							style="text-align: right; " 
							value="{{number_format($proveedor->FactorCambiario, 2, '.', ',')}}" 
							type="text"  
							class="form-control">
					</div>
				</div>
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
