@extends ('layouts.menu')
@section ('contenido')

<div class="row">
	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
		@include('isacom.provpedido.search')
	</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead class="colorTitulo">
					<th style="width:140px;">OPCION</th>
					<th>PEDIDO</th>
					<th>CLIENTE</th>
					<th>ENVIADO</th>
					<th>CODIGO</th>
					<th>ORIGEN</th>
					<th>STATUS</th>
					<th>TOTAL</th>
				</thead>
				@foreach ($tabla as $t)
				<tr>
					<?php
					$estador = "";
					$pr = DB::table('pedren')
	                ->where('id','=',$t->id)
	                ->where('codprove','=',$codprove)
	                ->first();
	                if ($pr) {
	                	$estador = $pr->estado;
	                }
	                $provped[] = CalculaProvTotalesPedido($t->id, $codprove);
					?>
					<td>
						<!-- CONSULTA DE PEDIDO -->
                        <a href="{{URL::action('ProvalcabalaController@show',$t->id)}}">
                        	<button class="btn btn-default btn-pedido fa fa-file-o" data-toggle="tooltip" title="Consultar pedido">
                        	</button>
                        </a>

                         <!-- DESCARGAR PEDIDO -->
                        <a href="{{URL::action('ProvalcabalaController@descargar',$t->id)}}">
                        	<button class="btn btn-default btn-pedido fa fa-download" data-toggle="tooltip" title="Descargar pedido en pdf">
                        	</button>
                        </a>

           				<!-- PROCESAR PEDIDO -->
						<a href="{{URL::action('ProvalcabalaController@edit',$t->id)}}">
							<button class="btn btn-default btn-pedido fa fa-check" title="Procesar pedido">
							</button>
						</a>
			
					</td>
					<td>{{$t->id}}</td>
					<td>{{$t->nomcli}}</td>
					<td>{{date('d-m-Y H:i', strtotime($t->fecenviado))}}</td>
					<td>{{$t->codcli}}</td>
					<td>{{$t->origen}}</td>
					<td>{{$estador}}</td>
					<td align="right">{{number_format($provped[0]['total'], 2, '.', ',')}}</td>
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