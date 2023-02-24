
<?php $__env->startSection('contenido'); ?>
 
<?php
  $codcli = sCodigoClienteActivo();
  $restandias = iValidarLicencia($codcli); 
  $moneda = Session::get('moneda', 'BSS');
  $status = "ACTIVO";
  if ($restandias <= 0) {
     $status =  "INACTIVO";
     DB::table('maecliente')
    ->where('codcli', '=', $codcli)
    ->update(array("estado" => 'INACTIVO'));
  }
  $contacto =  $cliente->telefono .' - '.$cliente->contacto;
?>


<head>
	<link href="<?php echo e(asset('js/electro-master/slick.css')); ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo e(asset('js/electro-master/slick-theme.css')); ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo e(asset('js/electro-master/nouislider.min.css')); ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo e(asset('js/electro-master/style.css')); ?>" rel="stylesheet" type="text/css" />
</head> 


<div class="col-md-12">

	<?php if($tipo == "G"): ?>
	<div class="row">
		<div class="row" style="margin-bottom: 5px;">
			<div class="col-md-12">
				<div class="form-group">
					<?php echo e(Form::Open(array('action'=>array('HomeController@cambiar',$codcli),'method'=>'get'))); ?>

					<div class="input-group input-group-sm">
				    	<select name="codcli" 
				    		class="form-control selectpicker" 
				    		data-live-search="true"
				    		style="width: calc(100% - 32px);" >
				    		<?php $__currentLoopData = $grupo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				    			<?php if($codcli == $g->codcli): ?>
									<option selected value="<?php echo e($g->codcli); ?>">
										<?php echo e($g->codcli); ?>-<?php echo e($g->nomcli); ?>

									</option>
					    		<?php else: ?>
				    				<option value="<?php echo e($g->codcli); ?>">
				    					<?php echo e($g->codcli); ?>-<?php echo e($g->nomcli); ?>

				    				</option>
				    			<?php endif; ?>
				    		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				    	</select>
			  		    <span style="padding: 0;" class="input-group-addon input-group">
							<button type="submit" class="btn-normal" style="height: 32px;">Cambiar</button>
						</span>
					</div>
		    		<?php echo e(Form::close()); ?>

				</div>
			</div>
		</div>
	</div>
	<?php endif; ?>

	<div class="row">
	    <div class="nav-tabs-custom">
	        <ul class="nav nav-tabs" style="margin-top: -20px;">
	          <li class="active"><a href="#tab_1" data-toggle="tab">INICIO</a></li>
	          <?php if($prodestacado->count() > 0): ?>
	          	<li><a href="#tab_2" data-toggle="tab">DESTACADOS</a></li>
	          <?php endif; ?>
	          <?php if(false): ?>
	          <li><a href="#tab_3" data-toggle="tab">PUBLICIDAD</a></li>
	          <?php endif; ?>
	          <li class="pull-right"><a href="<?php echo e(url('/')); ?>" class="text-muted">
	            <i class="fa fa-times"></i></a>
	          </li>
	        </ul>
	        <div class="tab-content">
	            <div class="tab-pane active" id="tab_1">
					<div class="row">
						<div class="row">
				            <div class="col-md-6">
				              	<!-- Danger box -->
				             	<div class="box box-solid box-primary" 
				             		style="height: 290px; border-radius: 10px;">
				             		<div class="box-header colorTitulo" 
				             			style="height: 40px; border-radius: 10px 10px 0px 0px;"> 
										<i class="fa fa-male">
											<span style="font-size: 20px; margin-top: 0px;">
												&nbsp;Datos del Cliente
											</span>
										</i>
									</div>
					                <div class="box-body">
					                  	<div class="table-responsive" style="padding: 1px;">
							                <table class="table table-striped table-bordered" >
							                    <tr>
								                	<td>
							                			<span style="color: #000000; font-size: 14px;">
							                			Código del cliente
							                			</span>
								                	</td>
													<td align='right'>
														<span style="color: #000000; font-size: 14px;">
														<?php echo e($cliente->codcli); ?>

														</span>
													</td>
								                </tr>
								                <tr>
								                	<td>
							                			<span style="color: #000000; font-size: 14px;">
							                			Rif:
							                			</span>
								                	</td>
													<td align='right'>
														<span style="color: #000000; font-size: 14px;">
														<?php echo e($cliente->rif); ?>   
														</span>
													</td>
								                </tr>
								                <tr> 
								                	<td>
							                			<span style="color: #000000; font-size: 14px;">
							                			Contacto:
							                			</span>
								                	</td>
													<td align='right'>
														<span style="color: #000000; font-size: 14px;"
															title="<?php echo e($cliente->telefono); ?>-<?php echo e($cliente->contacto); ?>">
														<?php echo e(strlen($contacto)>25 ? substr($contacto, 0, 25) : $contacto); ?> 
														</span>
													</td>
								                </tr>

								                <tr> 
								                	<td>
							                			<span style="color: #000000; font-size: 14px;">
							                			Keys:
							                			</span>
								                	</td>
													<td align='right'>
														<span style="color: #000000; font-size: 14px;">
														<?php echo e($cliente->KeyIsacom); ?>

														</span>
													</td>
								                </tr>
								                <tr> 
								                	<td>
							                			<span style="color: #000000; font-size: 14px;">
							                			Restan Dias:
							                			</span>
								                	</td>
													<td align='right'>
														<span style="
														<?php if($restandias <= 0): ?> color: red; <?php else: ?> color: black; <?php endif; ?>
														font-size: 14px;">
														<?php echo e($restandias); ?>

														</span>
													</td>
								                </tr>
								                <tr> 
								                	<td>
							                			<span style="color: #000000; font-size: 14px;">
							                			Estado:
							                			</span>
								                	</td>
													<td align='right'>
														<span style="
														<?php if($restandias <= 0): ?> color: red; <?php else: ?> color: black; <?php endif; ?>
														font-size: 14px;">
														<?php echo e($status); ?>

														</span>
													</td>
								                </tr>
								            </table>
								        </div>
					                </div>
				              	</div>
				            </div>

							<div class="col-md-6" >
								<!-- AREA CHART -->
								<div class="box box-primary" >
									<div class="box-header">
										<h3 class="box-title">Pedidos (<?php echo e($moneda); ?>)</h3>
									</div>
									<div class="box-body chart-responsive">
										<div class="chart" 
											id="line-chart" 
											style="height: 230px; width: 95%;">
										</div>
									</div>
								</div>
							</div>
				    	</div>
						<div class="row">

							<div class="col-lg-3 col-xs-6">
						  		<!-- small box -->
							 	<div class="small-box bg-aqua" 
							 		style="border-radius: 10px;">
								    <div class="inner">
								      <h3><?php echo e(number_format($contCatalogo,0, '.', ',')); ?></h3>
								      <p>Catálogo</p>
								    </div>
								    <div class="icon">
								      <i class="fa fa-cubes"></i>
								    </div>
								    <a href="#" 
						              class="small-box-footer" 
						              title="Ultima sincronización del catalogo">
						              <?php if( date('d-m-Y', strtotime($cfg->actualizado)) == date('d-m-Y') ): ?>
						              <span>
						                 <?php echo e(date('d-m-Y H:i', strtotime($cfg->actualizado))); ?>

						              </span>
						              <?php else: ?>
						              <span style="color: red;">
						                <?php echo e(date('d-m-Y H:i', strtotime($cfg->actualizado))); ?>

						              </span>
						              <?php endif; ?>
						            </a>
							  	</div>
							</div>

							<div class="col-lg-3 col-xs-6">
							  	<!-- small box -->
								<div class="small-box bg-green"
									style="border-radius: 10px;">
									<div class="inner">
										<h3><?php echo e(number_format($contInv,0, '.', ',')); ?><sup style="font-size: 20px"></sup></h3>
									  	<p>Inventario</p>
									</div>
									<div class="icon">
									  <i class="fa fa-table"></i>
									</div>
									<a href="#" 
									  	class="small-box-footer "
									    title="Ultima sincronización del inventario">  
									    <?php if( date('d-m-Y', strtotime($fechaInv)) == date('d-m-Y') ): ?>
									    <span>
									         <?php echo e(date('d-m-Y H:i', strtotime($fechaInv))); ?>

									    </span>
									    <?php else: ?>
									    <span style="color: red;">
									        <?php echo e(date('d-m-Y H:i', strtotime($fechaInv))); ?>

									    </span>
									    <?php endif; ?>
									</a>
								</div>
							</div>
					
							<div class="col-lg-3 col-xs-6">
							  <!-- small box -->
							  <div class="small-box bg-yellow"
							  		style="border-radius: 10px;">
								    <div class="inner">
								      <h3><?php echo e(number_format($contProv,0, '.', ',')); ?><sup style="font-size: 20px"></sup></h3>
								      <p>Proveedores</p>
								    </div>
								    <div class="icon">
								      <i class="fa fa-user"></i>
								    </div>
								    <a href="#" 
							           class="small-box-footer ">
							           <span>&nbsp;&nbsp;</span>
							        </a>
							  </div>
							</div>

							<div class="col-lg-3 col-xs-6">
							  <!-- small box -->
							  <div class="small-box bg-red"
							  		style="border-radius: 10px;">
								    <div class="inner">
								      <h3><?php echo e(number_format($contPedido,0, '.', ',')); ?><sup style="font-size: 20px"></sup></h3>
								      <p>Pedidos</p>
								    </div>
								    <div class="icon">
								      <i class="fa fa-shopping-cart"></i>
								    </div>
								    <a href="#" 
							           class="small-box-footer ">
							           <span>&nbsp;&nbsp;</span>
							        </a>
							  </div>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane" id="tab_2">
					<div class="row">

					<!-- Products tab & slick -->
					<div class="col-md-12">
						<div class="row">
							<div class="products-tabs">
								<!-- tab -->
								<div id="tab1" class="tab-pane active">
									<div class="products-slick" data-nav="#slick-nav-1">

										<?php $__currentLoopData = $prodestacado; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
										<?php
										$tp = DB::table('tpmaestra')
									    ->where('barra','=',$pd->barra)
									    ->first();
									    if (empty($tp)) 
									    	continue;
									    if (strlen($tp->desprod) > 25)
									    	$desprod = substr($tp->desprod, 0, 25);

									    $maeprove = DB::table('maeprove')
									    ->where('codprove','=',$pd->codprov)
									    ->first();
									    if (empty($maeprove)) 
									    	continue;
									    $desprov = $maeprove->descripcion; 

									    $codprove = strtolower($pd->codprov);
								        $factor = RetornaFactorCambiario($pd->codprov, $moneda);
								        if (!VerificaCampoTabla('tpmaestra', $codprove))
								            continue;
								        try {
								            $campos = $tp->$codprove;
								            $campo = explode("|", $campos);
								        } catch (Exception $e) {
								            continue;
								        }
								        $maeclieprove = DB::table('maeclieprove')
								        ->where('codcli','=',$codcli)
									    ->where('codprove','=',$pd->codprov)
									    ->first();
									    if (empty($maeclieprove)) 
									    	continue;
									    $dc = $maeclieprove->dcme;
								        $di = $maeclieprove->di;
								        $pp = $maeclieprove->ppme;
								        $da = 0.00;
								        $dpe = 0.00;
								        $upe = 0;
								        $dcredito = 0;
								   	    $precio = 0.00;
								        $tipoprecio = $maeclieprove->tipoprecio;
								        if ($tipoprecio == $maeprove->aplicarDaPrecio) {
								            $da = $campo[2];
								            $dpe = $campo[10];
								            $upe = $campo[11];
								            $dcredito = $campo[12];
								        }
								      	switch ($tipoprecio) {
								            case 1:
								                $precio = $campo[0]/$factor;
								                break;
								            case 2:
								                $precio = $campo[5]/$factor;
								                break;
								            case 3:
								                $precio = $campo[6]/$factor;
								                break;
								            default:
								                $precio = $campo[0]/$factor;
								                break;
								        }
								        $liquida = CalculaPrecioNeto($precio, $da, $di, $dc, $pp, 0.00);
        								?>
										<!-- product -->
										<div class="product">
											<div class="product-img">
												<img src="http://isaweb.isbsistemas.com/public/storage/prod/<?php echo e(NombreImagen($pd->barra)); ?>" 
												alt="icompras360" 
												style="border: 2px solid #D2D6DE;"
												oncontextmenu="return false">
										   		<div class="product-label">
                                           		   	<?php if($da > 0): ?>
													<span class="sale" title="DESCUENTO ADICIONAL">
														-<?php echo e(number_format($da, 0, '.', ',')); ?>%
													</span>
													<?php endif; ?>
													<?php if($dcredito > 0): ?>
													<span class="new" title="DIAS DE CREDITO">
														<?php echo e(number_format($dcredito, 0, '.', ',')); ?> DIAS
													</span>
													<?php endif; ?>
												</div>
											</div>
											<div class="product-body">
												<p class="product-category"><?php echo e($desprov); ?> (<?php echo e($tp->marca); ?>)</p>
												<h3 class="product-name">
													<a href="#"><?php echo e($desprod); ?></a>
												</h3>
												<h4 class="product-price">
													<?php echo e(number_format($liquida, 2, '.', ',')); ?> 
													<del class="product-old-price">
														<?php echo e(number_format($precio, 2, '.', ',')); ?>

													</del>
												</h4>
											</div>

											<!-- AGREGAR A CARRO DE COMPRA -->
											<div class="add-to-cart">
		                                        <div class="col-xs-8 input-group" 
		                                        	id="idAgregar-<?php echo e($pd->barra); ?>-<?php echo e($pd->codprov); ?>"
		                                        	style="margin-left: 35px;" >
		                                            <input style="text-align: center; 
		                                            	color: #000000; 
		                                            	width: 100px;
		                                            	border-radius: 20px 0px 0px 20px !important;" 
		                                            	id="idPedir-<?php echo e($pd->barra); ?>-<?php echo e($pd->codprov); ?>" 
		                                            	value="" 
		                                            	class="form-control" >
		                                            <span class="input-group-btn BtnAgregar">
		                                                
		                                                <button type="button" 
		                                                class="btn btn-pedido

		                                                <?php if(VerificarCarrito($pd->barra, 'N')): ?>
		                                                    colorResaltado
		                                                <?php endif; ?>

		                                                " data-toggle="tooltip" 
		                                                title="AGREGAR AL CARRITO"
		                                                id="idBtnAgregar-<?php echo e($pd->barra); ?>-<?php echo e($pd->codprov); ?>" style="border-radius: 0px 20px 20px 0px !important;">
		                                                    <span class="fa fa-cart-plus" 
		                                                    id="idAgregar-<?php echo e($pd->barra); ?>-<?php echo e($pd->codprov); ?>" 
		                                                    aria-hidden="true">
		                                                    </span>
		                                                </button>
		                                            </span>
		                                        </div>
											</div>
										</div>
										<!-- /product -->
										<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
									</div>
									<br><br><br>
									<div id="slick-nav-1" class="products-slick-nav"></div>
								</div>
								<!-- /tab -->
							</div>
						</div>
					</div>
					<!-- Products tab & slick -->

			    	</div>
			    </div>
			    <div class="tab-pane" id="tab_3">
					<div class="row">
			    	</div>
			    </div>
			</div>
		</div>
	</div>
</div>

<?php $__env->startPush('scripts'); ?>

<script type="text/javascript" src="<?php echo e(asset('js/electro-master/slick.min.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('js/electro-master/nouislider.min.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('js/electro-master/jquery.zoom.min.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('js/electro-master/main.js')); ?>"></script>

<script>
$('#subtitulo').text('<?php echo e($subtitulo); ?>');
//setTimeout('document.location.reload()',60000);

$(function () {
Morris.Line({
  element: 'line-chart',
  data: [ <?php echo $chart_data; ?>],
  lineColors: ['#819C79'],
  xkey: 'periodo',
  ykeys: ['pedidos'],
  labels: ['PEDIDO'],
  xLabels: 'day',
  xLabelAngle: 45,
  xLabelFormat: function (d) {
  return ("0" + (d.getDate())).slice(-2) + '-' + ("0" + (d.getMonth() + 1)).slice(-2) + '-' + d.getFullYear(); 
  
  },
  resize: true
});
});

</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/isacom/index.blade.php ENDPATH**/ ?>