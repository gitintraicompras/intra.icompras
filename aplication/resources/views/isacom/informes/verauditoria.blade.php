@extends('layouts.menu')
@section ('contenido')
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead class="colorTitulo">
					<th>ID</th>
					<th title="Código del cliente">CODCLI</th>
					<th title="Usuario operación">USUARIO</th>
					<th title="Tipo de ususrio">TIPO</th>
					<th title="Contador de visitas">VISITAS</th>
					<th title="Fecha de la,operacion">FECHA</th>
					<th title="Descripción de la operación">OPERACION</th>
					<th title="Nombre del cliente">CLIENTE</th>
				</thead>
				@foreach ($tabla as $t)
				<tr>
					<td>{{$t->id}}</td>
					<td>{{$t->codcli}}</td>
					<td>{{$t->email}}</td>
					<td>{{$t->tipo}}</td>
					<td align="right">{{number_format($t->contVisita, 0, '.', ',')}}</td>
					<td>{{date('d-m-Y H:i', strtotime($t->fecha))}}</td>
					<td>{{$t->operacion}}</td>
					<td>{{$t->nombre}}</td>
					</tr>
				@endforeach
			</table>
			<div align='right'>
            	{{$tabla->render()}}
            </div><br>
		</div>
	</div>
</div>

@push ('scripts')
<script>
$('#subtitulo').text('{{$subtitulo}}');
</script>
@endpush

@endsection