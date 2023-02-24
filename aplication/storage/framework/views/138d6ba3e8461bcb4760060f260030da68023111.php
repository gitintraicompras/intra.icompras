
<?php $__env->startSection('contenido'); ?>
<?php
$moneda = Session::get('moneda', 'BSS');
$factor = RetornaFactorCambiario("", $moneda);
?> 
<div class="col-xs-12">
	<div class="row">
		<div class="btn-toolbar" role="toolbar" style="margin-top: 12px;margin-bottom: 3px;">
			<div class="btn-group" role="group" style="width: 100%;">
					<?php if(isset($invent) && $invent->count() > 0): ?>
					<?php echo $__env->make('isacom.invcliente.search', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
					<a href="" data-target="#modal-delete-<?php echo e($codcli); ?>" data-toggle="modal">
					    <button class="btn-normal" data-toggle="tooltip" title="Eliminar inventario">
					    	Eliminar
					    </button>
					</a>
					<?php echo $__env->make('isacom.invcliente.delete', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
				<?php endif; ?>
				<!-- CARGAR INVENTARIO -->
				<a href="<?php echo e(url('/inventario/cliente/cargar')); ?>">
				    <button style="margin-right: 3px;" type="button" data-toggle="tooltip" title="Cargar inventario manualmente" class="btn-confirmar">
				        Cargar
				    </button>
				</a>
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
		                    	style="background-color: <?php echo e($confcli->backcolor); ?>;
		                		color: <?php echo e($confcli->forecolor); ?>;">
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

		                    <!-- DETALLES -->
		           			<th style="background-color: <?php echo e($confcli->backcolor); ?>;
		                		color: <?php echo e($confcli->forecolor); ?>;"
		           				title="Precio de Venta del producto">
								PRECIO(<?php echo e($cliente->usaprecio); ?>)
							</th>
							<th style="background-color: <?php echo e($confcli->backcolor); ?>;
		                		color: <?php echo e($confcli->forecolor); ?>;"
								title="Cantidad del producto en transito">
								TRAN.
							</th>
							<th style="background-color: <?php echo e($confcli->backcolor); ?>;
		                		color: <?php echo e($confcli->forecolor); ?>;"
								title="Inventario del cliente">
								INV.
							</th>
							<th style="background-color: <?php echo e($confcli->backcolor); ?>;
		                		color: <?php echo e($confcli->forecolor); ?>;"
								title="Costo del producto">
								COSTO
							</th>
							<th style="background-color: <?php echo e($confcli->backcolor); ?>;
		                		color: <?php echo e($confcli->forecolor); ?>;"
								title="Venta Media Diaria">
								VMD
							</th>
							<th style="background-color: <?php echo e($confcli->backcolor); ?>;
		                		color: <?php echo e($confcli->forecolor); ?>;"
								title="Dias de Inventario">
								DIAS
							</th>
							<th style="background-color: <?php echo e($confcli->backcolor); ?>;
		                		color: <?php echo e($confcli->forecolor); ?>;"
								title="Código del producto">
								CODIGO
							</th>
						</thead>

						<?php if(isset($invent)): ?>
							<?php $__currentLoopData = $invent; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<?php 
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
								?> 
								<tr>
									<td style="width: 10px;">
		                                <center>
		                               	<?php echo e($loop->iteration); ?>

		                                <i class="fa fa-thumbs-up" 
		                                    aria-hidden="true"
		                                    style="font-size: 20px;
		                                    color: <?php echo e($backcolor); ?>; "
		                                    title="<?php echo e($title); ?>" >
		                                </i>
		                                </center>
			                        </td>

									<td class="hidden-xs">
		                                <div align="center">
				                            <a href="<?php echo e(URL::action('PedidoController@verprod',$t->barra)); ?>">

											<img src="http://isaweb.isbsistemas.com/public/storage/prod/<?php echo e(NombreImagen($t->barra)); ?>" 
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
										<b><?php echo e($t->desprod); ?></b>
									</td>

									<td style="width: 100px;"
		                                title="CODIGO DE BARRA">
		                                <?php echo e($t->barra); ?>

		                            </td>

		                            <td title="MARCA DEL PRODUCTO">
		                                <?php echo e($t->marca); ?>

		                            </td>

		                            <td align="right" 
		                                style="width: 60px;"
		                                title="UNIDAD DE MANEJO">
		                                <?php echo e($t->bulto); ?>

		                            </td>

		                            <td align="right"
		                            	title="IVA">
		                            	<?php echo e(number_format($t->iva, 2, '.', ',')); ?>

		                            </td>
						
									<!-- PROVEEDOR-->
			                        <td align="right" 
		                                style="background-color: #FEE3CB;"
		                                title="MEJOR PRECIO DEL PROVEEDOR">
			                            <?php echo e(number_format($mpp/$factor, 2, '.', ',')); ?>

			                        </td>
			                        <td align="right" 
		                                style="background-color: #FEE3CB;"
		                                title="CONSOLIDADO DEL PROVEEDOR">
			                        	<?php echo e(number_format($invConsolProv, 0, '.', ',')); ?>

			                        </td> 

			                        <!-- DATOS-->
			                        <td align="right"
			                        	title="PRECIO"
			                        	style="background-color: <?php echo e($confcli->backcolor); ?>;
		                				color: <?php echo e($confcli->forecolor); ?>;">
										<?php echo e(number_format($t->$precio/$factor, 2, '.', ',')); ?>

									</td>
									<td align="right"
										title="TRANSITO"
										style="background-color: <?php echo e($confcli->backcolor); ?>;
		                				color: <?php echo e($confcli->forecolor); ?>;">
										<?php echo e(number_format($transito, 0, '.', ',')); ?>

									</td>
									<td align="right"
										title="INVENTARIO"
										style="background-color: <?php echo e($confcli->backcolor); ?>;
		                				color: <?php echo e($confcli->forecolor); ?>;">
										<?php echo e(number_format($t->cantidad, 0, '.', ',')); ?>

									</td>
									<td align="right"
										title="COSTO"
										style="background-color: <?php echo e($confcli->backcolor); ?>;
		                				color: <?php echo e($confcli->forecolor); ?>;">
										<?php echo e(number_format($t->costo/$factor, 2, '.', ',')); ?>

									</td>
									<td align="right"
										title="VMD"
										style="background-color: <?php echo e($confcli->backcolor); ?>;
		                				color: <?php echo e($confcli->forecolor); ?>;">
										<?php echo e(number_format($t->vmd, 4, '.', ',')); ?>

									</td>
									<td align="right"
										title="DIAS"
										style="background-color: <?php echo e($confcli->backcolor); ?>;
		                				color: <?php echo e($confcli->forecolor); ?>;">
										<?php if($t->vmd > 0): ?>
		                            	  <?php echo e(number_format($t->cantidad/$t->vmd, 2, '.', ',')); ?>

		                                <?php else: ?>
		                                  0.00
		                                <?php endif; ?>
									</td>
									<td style="background-color: <?php echo e($confcli->backcolor); ?>;
		                				color: <?php echo e($confcli->forecolor); ?>;"
										title="CODIGO PRODUCTO">
										<?php echo e($t->codprod); ?>

									</td>
								</tr>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						<?php endif; ?>

					</table>

					<div align='left'>
						<?php if(isset($invent)): ?>
		                	<?php echo e($invent->appends(["filtro" => $filtro])->links()); ?>

		                <?php endif; ?>
		            </div><br>
				</div>
			</div>
		</div> 
	</div>
</div>
<?php $__env->startPush('scripts'); ?>
<script>
$('#subtitulo').text('<?php echo e($subtitulo2); ?>');
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/isacom/invcliente/index.blade.php ENDPATH**/ ?>