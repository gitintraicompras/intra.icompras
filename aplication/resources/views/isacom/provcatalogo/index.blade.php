@extends('layouts.menu')
@section ('contenido')

@php
  $moneda = Session::get('moneda', 'BSS');
  $factor = RetornaFactorCambiario('', $moneda);
@endphp


<div class="row">

    <div class="col-md-12">
		<!-- BARRA DE BOTONES -->
		<div class="btn-toolbar" role="toolbar" style="margin-bottom: 8px;">
		    <div class="btn-group" role="group" style="width: 100%;">

				@include('isacom.provcatalogo.search')

				<!-- CARGAR CATALOGO -->
				<a href="{{url('proveedor/cargar',$maeprove->codprove)}}">
					<button class="btn-confirmar" 
						data-toggle="tooltip"
						title="cargar catálogo de productos">
					Cargar
					</button>
				</a>

				@if (false)
				<!-- DESCARGAR CATALOGO -->
		        <a href="{{url('provcatalogo/descargar/catalogo')}}">
		            <button style="margin-right: 3px;" type="button" data-toggle="tooltip" title="Descargar catálogo" class="btn-normal">
		                Descargar
		            </button>
		        </a>
		        @endif

			</div>
		</div>
	</div>

    <div class="col-md-12">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="table-responsive">
					<table class="table table-striped table-bordered table-condensed table-hover">
						<thead class="colorTitulo" id="idtabla">
							<th>#</th>
							<th style="width: 60px;" class="hidden-xs">IMAGEN</th>
							<th>REFERENCIA</th>
							<th>CODIGO</th>
							<th>DESCRIPCION</th>
							<th style="width: 120px;">CANTIDAD</th>
							<th>PRECIO</th>
							<th>IVA</th>
							<th>DA</th>
							<th>MARCA</th>
						</thead>
						@if (isset($catalogo))
						@foreach ($catalogo as $t)
						<tr>
							<td>{{$loop->iteration}}</td>
							<td class="hidden-xs">
	                            <div align="center">
	                                <a href="{{URL::action('PedidoController@verprod',$t->barra)}}">

	                                    <img src="http://isaweb.isbsistemas.com/public/storage/prod/{{NombreImagen($t->barra)}}" 
	                                    class="img-responsive" 
	                                    alt="icompras360" 
	                                    style="width: 60px; border: 2px solid #D2D6DE;"
	                                    oncontextmenu="return false">
	                    
	                                </a>
	                            </div>
	                        </td>
	   						<td>{{$t->barra}}</td>
	   						<td>{{$t->codprod}}</td>
							<td>{{$t->desprod}}</td>
							<td align="right">{{number_format($t->cantidad, 0, '.', ',')}}</td>
							<td align="right">{{number_format($t->precio1/$factor, 2, '.', ',')}}</td>
							<td align="right">{{number_format($t->iva, 2, '.', ',')}}</td>
							<td align="right">{{number_format($t->da, 2, '.', ',')}}</td>
							<td>{{$t->marca}}</td>
						</tr>
						@endforeach
						@endif
					</table>
					<div align='right'>
						@if (isset($catalogo))
		                	{{$catalogo->render()}}
		                @endif
		            </div><br>
				</div>
			</div>
		</div>
	</div>
			
</div>

@push ('scripts')
<script>
$('#subtitulo').text('{{$subtitulo}}');
</script>
@endpush

@endsection