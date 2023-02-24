@extends('layouts.menu')
@section ('contenido')
@php
$rutalogoprov = 'http://isaweb.isbsistemas.com/public/storage/prov/';
@endphp
<div class="row" style="margin-bottom: 5px;">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
		<a href="" data-target="#modal-liberar" data-toggle="modal">
			<button style="width: 150px;" class="btn-normal" data-toggle="tooltip" title="Liberar todos los productos">Libarar todos</button>
		</a>
		@include('isacom.transito.liberar')
	</div>

	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
		@include('isacom.transito.search')
	</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead class="colorTitulo">
					<th style="vertical-align:middle;">#</th>
            	 	<th style="width: 100px; vertical-align:middle;">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;IMAGEN&nbsp;&nbsp;&nbsp;&nbsp;&nbsp1;&nbsp;&nbsp;
                    </th>
					<th style="width:60px;">OPCION</th>
					<th>PEDIDO</th>
					<th>PRODUCTO</th>
					<th title="Referencias del producto">
						REFERENCIAS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					</th>
					<th>DIAS</th>
					<th title="CANTIDAD">CANT</th>
					<th>ENVIADO</th>
				</thead>
				@foreach ($tabla as $t)

				@php 
					$descripcion = $t->codprove;
					$backcolor = '#697A5A';
					$forecolor = '#FFFFFF';
					$confprov = LeerProve($t->codprove); 
					if ($confprov) {
						$backcolor = $confprov->backcolor;
						$forecolor = $confprov->forecolor;
						$descripcion = $confprov->descripcion;
					}
				@endphp
            	<tr>
					<td>{{$loop->iteration}}</td>
	            
	                <td>
	                    <div align="center">

	                        <a href="{{URL::action('PedidoController@verprod',$t->barra)}}">
	                
	                            <img src="http://isaweb.isbsistemas.com/public/storage/prod/{{NombreImagen($t->barra)}}" 
                                width="100%" 
                                height="100%" 
                                class="img-responsive" 
                                alt="icompras360"
                                style="border: 2px solid #D2D6DE;"
                                oncontextmenu="return false" >
	                
	                        </a>

	                    </div>
	                </td>
				
					<td>

						<!-- ELIMINAR PEDIDO -->
						<center>
						<a href="" data-target="#modal-delete-{{$t->item}}" data-toggle="modal">
							<button class="btn btn-pedido fa fa-trash-o" data-toggle="tooltip" title="Liberar producto"></button>
						</a>
						</center>
					</td>

					<td>{{$t->id}}</td>
					
					<td>
						<b>{{$t->desprod}}</b>
					</td>

					<td>
						<span title="NOMBRE PROVEEDOR">
							<img style="width: 20px; height: 20px;" 
                            src="{{$rutalogoprov.$confprov->rutalogo1}}" 
                            alt="icompras360">
                            {{$confprov->descripcion}}<br>
                        </span>
						<span title="CODIGO DE BARRA">
                            <i class="fa fa-barcode">
                                {{$t->barra}}
                            </i><br>
                        </span>
                        <span title="CODIGO DEL PRODUCTO">
                            <i class="fa fa-cube">
                                {{$t->codprod}}    
                            </i><br>
                        </span>
                        <span title="MARCA DEL PRODUCTO">
                            <i class="fa fa-shield">
                                {{LeerProdcaract($t->barra, 'marca', 'POR DEFINIR')}}    
                            </i>
                        </span> 
                    </td>

                    <td align="right">
						{{ DiferenciaDias($t->fecenviado) }} 
					</td>

					<td align="right">
						{{number_format($t->cantidad, 0, '.', ',')}}
					</td>
					<td>{{date('d-m-Y H:i', strtotime($t->fecenviado))}}</td>
				</tr>
				@include('isacom.transito.delete')
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