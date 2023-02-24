@extends('layouts.menu')
@section ('contenido') 
<div class="row"> 
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead class="colorTitulo">
					<th style="vertical-align:middle;">#</th>
                    <th style="width: 90px; vertical-align:middle;">
                    &nbsp;&nbsp;&nbsp;IMAGEN&nbsp;&nbsp;&nbsp;
                    </th>
					<th>PRODUCTO</th>
					<th title="Código del producto">CODIGO</th>
					<th title="Código de referencia">BARRA</th>
					<th title="Venta media diaria">VMD</th>
					<th title="Sugerido=(VMD*15-(INVENTARIO+TRANSITO)) para 10 dias">
                        15
                    </th>
                    <th title="Sugerido=(VMD*30-(INVENTARIO+TRANSITO)) para 30 dias">
                        30
                    </th>
                    <th title="Sugerido=(VMD*60-(INVENTARIO+TRANSITO)) para 60 dias">
                        60
                    </th>
					<th title="Inventario del cliente">INVENT.</th>
					<th title="Inventario consolidado de los proveedores">CONSOL.</th>
					<th title="Total de unidades vendidas">VENDIDAS</th>
				</thead>
				@foreach ($tabla as $t)
				@php
					$marcado = 0;
					$invent = 0;
					$invConsol = 0;
					$vmd = 0;
					$codprod = "";
					$sug15 = 0;
					$sug30 = 0;
					$sug60 = 0;
					$transito = verificarProdTransito($t->barra,  "", "");
				   	$inv = verificarProdInventario($t->barra, "");
                    if ($inv != "") {
                        $invent = $inv->cantidad;
                        $vmd = $inv->vmd;
                        $codprod = $inv->codprod;
                        $desprod = $inv->desprod;
                        $sug15 = ($vmd*15)-($invent + $transito);
						$sug30 = ($vmd*30)-($invent + $transito);
						$sug60 = ($vmd*60)-($invent + $transito);
						if ($sug15 < 0)
                            $sug15 = 0;
                        if ($sug30 < 0)
                            $sug30 = 0;
                        if ($sug60 < 0)
                            $sug60 = 0;
                    }
                    $cat = DB::table('tpmaestra')
		            ->where('barra','=',$t->barra)
		            ->first();
		       		if ($cat) {
		       		 	$desprod = $cat->desprod;
                        $dataprod = obtenerDataTpmaestra($cat, $provs, 0);
	                    if (!is_null($dataprod)) {
		                    $invConsol = $dataprod['invConsol'];
		                }
		            }
		            if ($invent <= 0 && $invConsol > 0) {
		            	$marcado = 1;
		        	}
				@endphp
				<tr>
					<td style="font-size:20px;">
						<span
					    @if ($marcado == 1) 
					    class="label label-danger"
						style="background-color: red; 
						color: #ffffff; 
						border-radius: 50%;" 
                        title = "PRODUCTO DEBE SER PEDIDO DE INMEDIATO"  
                        @endif>
						{{$loop->iteration}}
						</span>
					</td>

			
					<td>
                        <div align="center">

                            <a href="{{URL::action('PedidoController@verprod',$t->barra)}}">
                    
                                <img src="http://isaweb.isbsistemas.com/public/storage/prod/{{NombreImagen($t->barra)}}" 
                                width="100%" 
                                height="100%" 
                                class="img-responsive" 
                                alt="icompras360" 
                                style="border: 2px solid #D2D6DE;"
                                oncontextmenu="return false">
                    
                            </a>

                        </div>
                    </td>
					<td>
						<B>{{$desprod}}</B>
					</td>
					<td>{{$codprod}}</td>
					<td>{{$t->barra}}</td>
					<td align="right">{{number_format($vmd, 4, '.', ',')}}</td>
					<td align="right">{{number_format($sug15, 0, '.', ',')}}</td>
					<td align="right">{{number_format($sug30, 0, '.', ',')}}</td>
					<td align="right">{{number_format($sug60, 0, '.', ',')}}</td>
					<td align="right">{{number_format($invent, 0, '.', ',')}}</td>
					<td align="right">{{number_format($invConsol, 0, '.', ',')}}</td>
					<td align="right">{{number_format($t->total, 0, '.', ',')}}</td>
				</tr>
				@endforeach
			</table>
		</div>
	</div>
</div>
@push ('scripts')
<script>
$('#subtitulo').text('{{$subtitulo}}');
</script>
@endpush

@endsection