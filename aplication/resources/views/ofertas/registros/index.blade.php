@extends ('layouts.menu')
@section ('contenido')

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="btn-toolbar" role="toolbar" >
		    <div class="btn-group" role="group">
	            <a href="{{URL::action('RegistroController@catalogo','T') }}">
		            <button style="margin-right: 3px;" 
		            	data-toggle="tooltip" 
		            	title="Analizar todos los productos"
		            	class="btn-confirmar"> 
		      			Analizar
		            </button>
		        </a>
		        <a href="{{URL::action('RegistroController@catalogo','A') }}">
		            <button style="margin-right: 3px;" 
		            	data-toggle="tooltip" 
		            	title="Analizar solo los productos amarillos"
		            	class="btn-confirmar-a">
		      			Amarillos
		            </button>
		        </a>
		        <a href="{{URL::action('RegistroController@catalogo','V') }}">
		            <button style="margin-right: 3px;" 
		            	type="button" 
		            	data-toggle="tooltip" 
		            	title="Analizar solo los productos verdes"
		            	class="btn-confirmar-v">
		      			Verde
		            </button>
		        </a>
		        <a href="{{URL::action('RegistroController@catalogo','R') }}">
		            <button style="margin-right: 3px;" 
		            	type="button" 
		            	data-toggle="tooltip" 
		            	title="Analizar solo los productos rojos"
		            	class="btn-confirmar-r">
		      			Rojo
		            </button>
		        </a>
	       </div> 
	 	</div>
		@include('ofertas.registros.search')
	</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table id="idtabla" class="table table-striped table-bordered table-condensed table-hover">
                <thead class="colorTitulo">
                    <th style="width: 30px;">#</th>
               		<th style="width: 140px;">OPCION</th>
			        <th style="width: 120px;">FECHA</th>
                    <th style="width: 120px;">PROCESADO</th>
                    <th style="width: 170px;">USUARIO</th>
                    <th>OBSERVACION</th>
                    <th>STATUS</th>
                </thead>
	            @foreach ($tabla as $t)
	                <tr>
	                	<td>{{$t->id}}</td>
	                	<td>
							<!-- CONSULTA DE PEDIDO -->
	                        <a href="{{URL::action('RegistroController@show',$t->id)}}">
	                        	<button class="btn btn-pedido fa fa-file-o" data-toggle="tooltip" title="Consultar ofertas">
	                        	</button>
	                        </a>

	                       	<!-- ELIMINAR REGISTRO -->
							<a href="" data-target="#modal-delete-{{$t->id}}" data-toggle="modal">
								<button class="btn btn-pedido fa fa-trash-o" data-toggle="tooltip" title="Eliminar registro"></button>
							</a>

						     <a href="{{URL::action('RegistroController@descargar',$t->id)}}">
	                        	<button class="btn btn-pedido fa fa-download" data-toggle="tooltip" title="Descargar ofertas en excel">
	                        	</button>
	                        </a>


	                    </td>
	                    <td>{{date('d-m-Y H:i', strtotime($t->fecha))}}</td>
	                    <td>{{date('d-m-Y H:i', strtotime($t->fecprocesado))}}</td>
	                    <td>{{$t->usuario}}</td>
	                    <td>{{$t->observ}}</td>
	                    <td>{{$t->status}}</td>
	                </tr>
	            @include('ofertas.registros.delete')
                @endforeach 
            </table>
            <div align='right'>
            	{{$tabla->render()}}
            </div><br>
        </div><br>
	</div>
</div>

@push ('scripts')
<script>
$('#subtitulo').text('{{$subtitulo}}');
</script>
@endpush
@endsection