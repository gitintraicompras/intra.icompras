@extends ('layouts.menu')
@section ('contenido')
   
<div class="row">
	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
		@include('ofertas.prodexclu.search')
	</div>
</div>
 
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table id="datos" class="table table-striped table-bordered table-condensed table-hover">
				<thead class="colorTitulo">
					<th>#</th>
					<th>DESCRIPCION</th>
					<th>CODIGO</th>
					<th>BARRA</th>
					<th title="Marca de Producto">MARCA</th>
					<th title="Cantidad de Producto">CANTIDAD</th>
					<th title="Costo del Producto">COSTO</th>
					<th title="Utilidad del Producto">UTIL</th>
					<th title="Precio del Producto">PRECIO</th>
					<th title="Descuento Adicional">DA</th>
					<th title="Precio Liquida">LIQUIDA</th>
				</thead>
				@foreach ($tabla as $t)
				@php
				$valor1 = $t->precio1 - ($t->precio1*$t->da/100);
				if ($valor1 <= 0)
					continue;
				$valor = $t->costo/$valor1;
				$util = (-1)* (( $valor *100)-100);
				$liquida = $t->precio1 - ($t->precio1*$t->da/100);
				@endphp
				<tr>
					<td>{{$loop->iteration}}</td>
					<td>{{$t->desprod}}</td>
					<td>{{$t->codprod}}</td>
					<td>{{$t->barra}}</td>
					<td>{{$t->marca}}</td>
					<td align="right">{{number_format($t->cantidad, 0, '.', ',')}}</td>
					<td align="right">{{number_format($t->costo, 2, '.', ',')}}</td>
					<td align="right">{{number_format($util, 2, '.', ',')}}%</td>
					<td align="right">{{number_format($t->precio1, 2, '.', ',')}}</td>
					<td align="right">{{number_format($t->da, 2, '.', ',')}}</td>
					<td align="right">{{number_format($liquida, 2, '.', ',')}}</td>
	   			</tr>
				@endforeach
			</table>
		</div>
	</div>
</div>

@push ('scripts')
<script>
$('#subtitulo').text('{{$subtitulo}}');

function tdclick(e) {
    var id = e.target.id.split('_');
    var barra = id[1];
    var campo = id[2];
    var	valor = $('#id' + campo + '_'+barra).val();
    //alert('Barra: ' + barra + ' Campo: ' + campo + ' Valor: ' + valor);
    $.ajax({
	  type:'POST',
	  url:'./prodcaract/caract/modcaract',
	  dataType: 'json', 
	  encode  : true,
	  data: {barra:barra, campo:campo, valor:valor },
	  success:function(data) {
	    if (data.msg != "") {
	        alert(data.msg);
	    } 
	  }
  	});
}

</script>
@endpush

@endsection