@extends('layouts.menu')
@section ('contenido')
@php
$moneda = Session::get('moneda', 'BSS');
$factor = RetornaFactorCambiario("", $moneda);
@endphp 
<div class="col-xs-12">
	<div class="row">
		<div class="btn-toolbar" role="toolbar" style="margin-top: 12px;margin-bottom: 3px;">
			<div class="btn-group" role="group" style="width: 100%;">
				@include('isacom.invdirectoclie.search')
			</div>
		</div> 
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="table-responsive">
					<table class="table table-striped table-bordered table-condensed table-hover">

						<thead style="background-color: #b7b7b7;">
							<th colspan="7">
		                        <center>INVENTARIO MAESTRO DE PRODUCTOS</center>
		                    </th>
		                    <th style="width: 200; 
		                    	background-color: #FEE3CB;
		                    	color: black;" 
		                    	colspan="2">
		                        <center>
		                        &nbsp;&nbsp;&nbsp;&nbsp;PROVEEDOR&nbsp;&nbsp;&nbsp;&nbsp;
		                    	</center>
		                    </th>
		                    <th colspan="8" 
		                    	style="background-color: {{$confcli->backcolor}};
		                		color: {{$confcli->forecolor}};">
		                        <center>DATOS DEL CLIENTE</center>
		                    </th>
						</thead>

						<thead style="background-color: #b7b7b7;">
							<th style="width: 10px;">
		                        <center>#</center>
		                    </th>
		                    <th style="width: 120px;">
		                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;IMAGEN&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		                    </th>
							<th style="width: 150px;"
								title="Descripción del producto">
								PRODUCTO
							</th>
							<th style="width: 100px;">
								REFERENCIA
							</th>

							<th>MARCA</th>
		                    <th style="width: 60px;">BULTO</th>
		                    <th style="width: 60px;">IVA</th>


		                    <!-- PROVEEDOR -->
		                    <th style="background-color: #FEE3CB;
		                    	color: black;">
		                    	&nbsp;&nbsp;&nbsp;&nbsp;MPP&nbsp;&nbsp;&nbsp;&nbsp;
		                    </th>
		                    <th style="background-color: #FEE3CB;
		                    	color: black;">
		                    	&nbsp;&nbsp;&nbsp;&nbsp;INV.&nbsp;&nbsp;&nbsp;&nbsp;
		                    </th>

		                	<th style="background-color: {{$confcli->backcolor}};
		                		color: {{$confcli->forecolor}};"
		           				title="Precio de Venta del producto">
								PRECIO({{$cliente->usaprecio}})
							</th>
							<th style="background-color: {{$confcli->backcolor}};
		                		color: {{$confcli->forecolor}};"
								title="Cantidad del producto en transito">
								TRAN.
							</th>
							<th style="background-color: {{$confcli->backcolor}};
		                		color: {{$confcli->forecolor}};"
								title="Inventario del cliente">
								INV.
							</th>
							<th style="background-color: {{$confcli->backcolor}};
		                		color: {{$confcli->forecolor}};"
								title="Costo del producto">
								COSTO
							</th>
							<th style="background-color: {{$confcli->backcolor}};
		                		color: {{$confcli->forecolor}};"
								title="Venta Media Diaria">
								VMD
							</th>
							<th style="background-color: {{$confcli->backcolor}};
		                		color: {{$confcli->forecolor}};"
								title="Dias de Inventario">
								DIAS
							</th>
							<th style="background-color: {{$confcli->backcolor}};
		                		color: {{$confcli->forecolor}};"
								title="Código del producto">
								CODIGO
							</th>
						</thead>

						@if (isset($invent))
							@foreach ($invent as $t)
								@php 
								$tipoprecio = $cliente->usaprecio;
								if ($tipoprecio == "" || is_null($tipoprecio))
									$tipoprecio = '1';
								if ($tipoprecio > 5)
									$tipoprecio = '1';
								$precio = 'precio'.$tipoprecio;
							    $transito = verificarProdTransito($t->barra, $codcli, "");
								$invConsolProv = iCantidadConsolidadoProv($t->barra);
								$backcolor = "#FFFFFF";
			                    $forecolor = "#000000"; 
			                    $title = "";
			                    if ($invConsolProv > 0 && $t->cantidad <= 0) {
			                    	// ROJO -> ALERTA
			                    	$title = "ALerta -> se debe pedir este producto";
			                    	$backcolor = "#FF0000";
			                    	$forecolor = "#FFFFFF";
			                	}
			                	if ($invConsolProv > 0 && $t->cantidad > 0) {
			                		// VERDE -> IDEAL
			                    	$title = "Ideal -> tienes inventario, al igual que los proveedores";
			                		$backcolor = "#00B621";
			                    	$forecolor = "#FFFFFF"; 
			                	}
			                	if ($invConsolProv <= 0 && $t->cantidad > 0) {
			                		// AMARILLO -> WARNING
			                    	$title = "Advertencia -> tienes inventario, pero los proveedores no tienen";
			                		$backcolor = "#FFD800";
			                    	$forecolor = "#000000"; 
			                	}
			                	if ($invConsolProv <= 0 && $t->cantidad < 0) {
			                		// GRAVE -> NARANJA
			                    	$title = "Grave -> Sin inventario, igual los proveedores ";
			                		$backcolor = "#FF6A00";
			                    	$forecolor = "#FFFFFF"; 
			                	}

			                	// MEJOR PRECIO PROVEEDOR
		                        $mpp = 0.00;
		                        $pedir = 1;
		                        $criterio = 'PRECIO';
		                        $preferencia = 'NINGUNA';
		                        $provs = TablaMaecliproveActiva("");
		                        $mejoropcion = BuscarMejorOpcion($t->barra, $criterio, $preferencia, $pedir, $provs);
		                        if ($mejoropcion != null) {
		                            $precioprove = $mejoropcion[0]['precio'];
		                            $daprove = $mejoropcion[0]['da'];
		                            $codprove = $mejoropcion[0]['codprove'];
		                            $maeclieprove = DB::table('maeclieprove')
		                            ->where('codcli','=',$codcli)
		                            ->where('codprove','=',$codprove)
		                            ->first();
		                            $dc = $maeclieprove->dcme;
		                            $di = $maeclieprove->di;
		                            $pp = $maeclieprove->ppme;
		                            $mpp = CalculaPrecioNeto($precioprove, $daprove, $di, $dc, $pp, 0.00);
		                        }
		                        $pedir = VerificarSugerido($t->codprod, $codcli);
								@endphp 
								<tr>
									<td style="width: 10px;">
		                                <center>
		                               	{{$loop->iteration}}
		                                <i class="fa fa-thumbs-up" 
		                                    aria-hidden="true"
		                                    style="font-size: 20px;
		                                    color: {{$backcolor}}; "
		                                    title="{{$title}}" >
		                                </i>
		                                </center>
			                        </td>

									<td class="hidden-xs">
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
									<td style="width: 150px;">
										{{$t->desprod}}
									</td>

									<td style="width: 100px;"
		                                title="CODIGO DE BARRA">
		                                {{$t->barra}}
		                            </td>

		                            <td title="MARCA DEL PRODUCTO">
		                                {{$t->marca}}
		                            </td>

		                            <td align="right" 
		                                style="width: 60px;"
		                                title="UNIDAD DE MANEJO">
		                                {{$t->bulto}}
		                            </td>

		                            <td align="right"
		                            	title="IVA">
		                            	{{number_format($t->iva, 2, '.', ',')}}
		                            </td>
						
									<!-- PROVEEDOR-->
			                        <td align="right" 
		                                style="background-color: #FEE3CB;"
		                                title="MEJOR PRECIO DEL PROVEEDOR">
			                            {{number_format($mpp, 2, '.', ',')}}
			                        </td>
			                        <td align="right" 
		                                style="background-color: #FEE3CB;"
		                                title="CONSOLIDADO DEL PROVEEDOR">
			                        	{{number_format($invConsolProv, 0, '.', ',')}}
			                        </td> 

			                        <td align="right"
			                        	title="PRECIO"
			                        	style="background-color: {{$confcli->backcolor}};
		                				color: {{$confcli->forecolor}};">
										{{number_format($t->$precio/$factor, 2, '.', ',')}}
									</td>
									<td align="right"
										title="TRANSITO"
										style="background-color: {{$confcli->backcolor}};
		                				color: {{$confcli->forecolor}};">
										{{number_format($transito, 0, '.', ',')}}
									</td>
									<td align="right"
										title="INVENTARIO"
										style="background-color: {{$confcli->backcolor}};
		                				color: {{$confcli->forecolor}};">
										{{number_format($t->cantidad, 0, '.', ',')}}
									</td>
									<td align="right"
										title="COSTO"
										style="background-color: {{$confcli->backcolor}};
		                				color: {{$confcli->forecolor}};">
										{{number_format($t->costo/$factor, 2, '.', ',')}}
									</td>
									<td align="right"
										title="VMD"
										style="background-color: {{$confcli->backcolor}};
		                				color: {{$confcli->forecolor}};">
										{{number_format($t->vmd, 4, '.', ',')}}
									</td>
									<td align="right"
										title="DIAS"
										style="background-color: {{$confcli->backcolor}};
		                				color: {{$confcli->forecolor}};">
										@if ($t->vmd > 0)
		                            	  {{number_format($t->cantidad/$t->vmd, 0, '.', ',')}}
		                                @else
		                                  0
		                                @endif
									</td>
									<td title="CODIGO PRODUCTO"
										style="background-color: {{$confcli->backcolor}};
		                				color: {{$confcli->forecolor}};">
										{{$t->codprod}}
									</td>
								</tr>
							@endforeach
						@endif

					</table>

					<div align='left'>
						@if (isset($invent))
		                	{{$invent->appends(["filtro" => $filtro])->links()}}
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