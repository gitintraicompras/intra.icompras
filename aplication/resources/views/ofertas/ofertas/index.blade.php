@extends ('layouts.menu')
@section ('contenido')

<div class="row" style="margin-bottom: 5px;">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
		@include('ofertas.ofertas.search')
	</div>
</div>


<div class="row"> 
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table id="idtabla" class="table table-striped table-bordered table-condensed table-hover">
                <thead class="colorTitulo">
                    <th style="width: 30px;">#</th>
               		<th style="width: 40px;">OPCION</th>
               		<th>PRODUCTO</th>
			        <th>CODIGO</th>
                    <th>REFERENCIA</th>
                    <th>MARCA</th>
                    <th title="CANTIDAD">CANT</th>
                    <th>OFERTA</th>
                </thead>
	            @foreach ($invent as $inv)
	                <tr>
	                	<td>
	                	{{$loop->iteration}}
	                	</td>

	                	<td>
				           	<!-- ELIMINAR OFERTA -->
							<a href="" data-target="#modal-delete-{{$inv->codprod}}" data-toggle="modal">
								<button class="btn btn-pedido fa fa-trash-o" 
									data-toggle="tooltip" 
									title="Eliminar oferta del producto">
								</button>
							</a>
	                    </td>

	                    <td>{{$inv->desprod}}</td>
	                    <td>{{$inv->codprod}}</td>
	                    <td>{{$inv->barra}}</td>
	                    <td>{{$inv->marca}}</td>
	                    <td align='right'>{{number_format($inv->cantidad, 0, '.', ',')}}</td>
	                  	<td align='right'>{{number_format($inv->da, 2, '.', ',')}}</td>	
                    </tr>
                    @include('ofertas.ofertas.delete')
	            @endforeach 
            </table>
        </div><br>
	</div>
</div>

@push ('scripts')
<script>
$('#subtitulo').text('{{$subtitulo}}');
</script>
@endpush
@endsection