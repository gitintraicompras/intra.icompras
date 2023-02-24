@extends ('layouts.menu')
@section ('contenido')


{!! Form::open(array('url'=>'/provcatalogo','method'=>'POST','autocomplete'=>'off', 'enctype'=>'multipart/form-data')) !!}
{{ Form::token() }}
<input hidden name="modalidad" value="CARGAR" type="text">
<input hidden name="codprove" value="{{$codprove}}" type="text">
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="row" >
		<div class="col-lg-3 col-md-3 col-sm-3" align="center">
		    <i class="fa fa-upload" style="font-size: 78px; color: #C1272D" ></i> 
    	</div>
		<div class="col-lg-9 col-md-9 col-sm-9">
			<div class="col-lg-9 col-md-9 col-sm-9">
				<div >
					<img src="{{asset('images/linea.png')}}" width="90%" height="50" class="img-responsive">
				</div>
				<p><strong>SUBIR ARCHIVO</strong></p>
		  		<p align="justify" class="Estilo4 Estilo3">Seleccione el nombre del archivo txt que desea subir al portal web</p> 
			</div>   
			<div class="col-lg-9 col-md-9 col-sm-9">
				<div class="form-group">
					<input type="file" name="linkarchivo">
				</div>
			</div>
		</div>

	</div>

	<div class="row">
		<div class="row">
			<center>
				<b>TABLA INFORMATIVA CON LA ESTRUCTURA DEL ARCHIVO TXT</b>
			</center>
			<center>
				<b>ES UN ARCHIVO TEXTO, CARACTER ( | ) SEPARADOR. (NO EXCEL)</b>
			</center>
		</div>	
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="table-responsive" style="width: 100%;">
				<table class="table table-striped table-bordered table-condensed table-hover">
					<thead class="colorTitulo">
						<th></th>
						<th>REFERENCIA</th>
						<th>CODIGO</th>
						<th>DESCRIPCION</th>
						<th title="CANTIDAD">CANT</th>
						<th>PRECIO</th>
						<th>IVA</th>
						<th>DA</th>
						<th>MARCA</th>
					</thead>
					<tr>
						<td class="colorTitulo">DETALLE</td>
						<td>CODIGO DE BARRA</td>
						<td>CODIGO DEL PRODUCTO</td>
						<td>DESCRIPCION DEL PRODUCTO</td>
						<td>CANTIDAD DEL INVENTARIO</td>
						<td>PRECIO DEL PRODUCTO</td>
						<td>IMPUESTO</td>
						<td>DESCUENTO ADICIONAL</td>
						<td>MARCA DEL PRODUCTO</td>
					</tr>
					<tr>
						<td class="colorTitulo">TIPO DE DATO</td>
						<td>TEXTO (MAX. 13 CARACTERES)</td>
						<td>TEXTO (MAX. 20 CARACTERES)</td>
						<td>TEXTO (MAX. 150 CARACTERES)</td>
						<td>ENTERO</td>
						<td>DECIMAL SIN COMA SOLO PUNTO (.) DECIMAL</td>
						<td>DECIMAL, PORCENTAJE DEL IVA</td>
						<td>DECIMAL, PORCENTAJE DE DESCUENTO</td>
						<td>TEXTO (MAX. 100 CARACTERES)</td>
					</tr>
					<tr>
						<td class="colorTitulo">EJEMPLO</td>
						<td>7591235657874</td>
						<td>0000001</td>
						<td>ACETAMINOFEN DE 500MG X 10 COMP</td>
						<td>1000</td>
						<td>1000000.00</td>
						<td>16.00 ó 0.00</td>
						<td>2.00 ó 0.00</td>
						<td>GENVEN</td>
					</tr>
					<tr>
						<td class="colorTitulo">IMPORTANCIA</td>
						<td>OBLIGATORIO</td>
						<td>OBLIGATORIO</td>
						<td>OBLIGATORIO</td>
						<td>OBLIGATORIO</td>
						<td>OBLIGATORIO</td>
						<td>OBLIGATORIO</td>
						<td>NO OBLIGATORIO</td>
						<td>NO OBLIGATORIO</td>
					</tr>
				</table>
			</div>
		</div>
	</div>

</div>

<br><br><br><br>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	<div class="form-group">
		<div class="form-group">

            <a href="{{URL::action('ProveedorController@descarejemplo')}}">
				<button type="button" class="btn-normal" onclick="history.back(-1)" data-toggle="tooltip" title="Regresar">Ver ejenplo
				</button>
			</a>

		    <button type="button" class="btn-normal" onclick="history.back(-1)" data-toggle="tooltip" title="Regresar">Regresar</button>
			<button class="btn-confirmar" type="submit" data-toggle="tooltip" title="Subir archivo">Subir</button>
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