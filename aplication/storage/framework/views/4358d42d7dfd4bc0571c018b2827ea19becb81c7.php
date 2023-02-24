
<?php $__env->startSection('contenido'); ?>
<?php if(count($errors)>0): ?>
<div class="alert alert-danger">
	<ul>
		<?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
		<li><?php echo e($error); ?></li>
		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	</ul>
</div> 
<?php endif; ?>
<?php echo Form::model($provs,['method'=>'PATCH','route'=>['proveedor.update',$provs->codprove]]); ?>

<?php echo e(Form::token()); ?>


<div class="row">
    <div class="col-md-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active" >
              	<a href="#tab_1" data-toggle="tab">BASICA</a>
              </li>
              <li>
              	<a href="#tab_2" data-toggle="tab">DESCUENTOS</a>
              </li>
              <li>
              	<a href="#tab_3" data-toggle="tab">PARAMETROS EXPORTAR (FAC/ODC)</a>
              </li>
              <li class="pull-right"><a href="<?php echo e(url('/')); ?>" class="text-muted">
              	<i class="fa fa-times"></i></a>
              </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1">
                    <div class="row">

						<div class="row col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<!-- CODCLI -->
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label for="codcli">Cliente</label>
									<input readonly value="<?php echo e($provs->codcli); ?>" type="text" class="form-control">
								</div>
							</div>
							<!-- CODPROV -->
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label for="codcli">Proveedor</label>
									<input readonly 
										value="<?php echo e($provs->codprove); ?>-<?php echo e($provs->descripcion); ?>" 
										type="text"  
										class="form-control">
								</div>
							</div>
							<!-- CODDRO -->
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label for="codigo">Código</label>
									<input readonly name="codigo" value="<?php echo e($provs->codigo); ?>" type="text" class="form-control">
								</div>
							</div>
							<!-- TIPO DE PRECIO  -->
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label for="tipoprecio">Tipo de precio</label>
									<input name="tipoprecio" value="<?php echo e($provs->tipoprecio); ?>" type="number" class="form-control" style="text-align: right;">
								</div>
							</div>
						</div>
						<div class="row col-lg-12 col-md-12 col-sm-12 col-xs-12">

							<!-- SUB CARPETA FTP -->
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label >Sub carpeta Ftp</label>
									<input name="subcarpetaftp" value="<?php echo e($provs->subcarpetaftp); ?>" type="text" class="form-control">
								</div>
							</div>
							
							<!-- MCREDITO -->
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label for="mcredito">Monto credito</label>
									<input name="mcredito" value="<?php echo e($provs->mcredito); ?>" type="text" class="form-control" style="text-align: right;">
								</div>
							</div>
							<!-- CORTE -->
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label for="corte">Corte</label>
									<select name="corte" class="form-control">
										<?php if($provs->corte == 'LUNES'): ?>
									        <option value="LUNES" selected >LUNES</option>
							    			<option value="MARTES">MARTES</option>
							    			<option value="MIERCOLES">MIERCOLES</option>
							    			<option value="JUEVES">JUEVES</option>
							    			<option value="VIERNES">VIERNES</option>
							    			<option value="SABADO">SABADO</option>
							    			<option value="DOMINGO">DOMINGO</option>
									   	<?php elseif($provs->corte == 'MARTES'): ?>
									   		<option value="LUNES">LUNES</option>
							    			<option value="MARTES" selected>MARTES</option>
							    			<option value="MIERCOLES">MIERCOLES</option>
							    			<option value="JUEVES">JUEVES</option>
							    			<option value="VIERNES">VIERNES</option>
							    			<option value="SABADO">SABADO</option>
							    			<option value="DOMINGO">DOMINGO</option>
										<?php elseif($provs->corte == 'MIERCOLES'): ?>
											<option value="LUNES">LUNES</option>
							    			<option value="MARTES">MARTES</option>
							    			<option value="MIERCOLES" selected>MIERCOLES</option>
							    			<option value="JUEVES">JUEVES</option>
							    			<option value="VIERNES">VIERNES</option>
							    			<option value="SABADO">SABADO</option>
							    			<option value="DOMINGO">DOMINGO</option>
								    	<?php elseif($provs->corte == 'JUEVES'): ?>
								    		<option value="LUNES">LUNES</option>
							    			<option value="MARTES">MARTES</option>
							    			<option value="MIERCOLES">MIERCOLES</option>
							    			<option value="JUEVES" selected>JUEVES</option>
							    			<option value="VIERNES">VIERNES</option>
							    			<option value="SABADO">SABADO</option>
							    			<option value="DOMINGO">DOMINGO</option>
							    		<?php elseif($provs->corte == 'VIERNES'): ?>
							    			<option value="LUNES">LUNES</option>
							    			<option value="MARTES">MARTES</option>
							    			<option value="MIERCOLES">MIERCOLES</option>
							    			<option value="JUEVES">JUEVES</option>
							    			<option value="VIERNES" selected>VIERNES</option>
							    			<option value="SABADO">SABADO</option>
							    			<option value="DOMINGO">DOMINGO</option>
							    		<?php elseif($provs->corte == 'SABADO'): ?>
							    			<option value="LUNES">LUNES</option>
							    			<option value="MARTES">MARTES</option>
							    			<option value="MIERCOLES">MIERCOLES</option>
							    			<option value="JUEVES">JUEVES</option>
							    			<option value="VIERNES">VIERNES</option>
							    			<option value="SABADO" selected>SABADO</option>
							    			<option value="DOMINGO">DOMINGO</option>
							    		<?php elseif($provs->corte == 'DOMINGO'): ?>
							    			<option value="LUNES">LUNES</option>
							    			<option value="MARTES">MARTES</option>
							    			<option value="MIERCOLES">MIERCOLES</option>
							    			<option value="JUEVES">JUEVES</option>
							    			<option value="VIERNES">VIERNES</option>
							    			<option value="SABADO">SABADO</option>
							    			<option value="DOMINGO" selected>DOMINGO</option>
										<?php endif; ?>
								    </select>
								</div>
							</div>
							<!-- ORDEN DE PREFERENCIA -->
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label for="codcli">Orden Preferecia</label>
									<input readonly 
										value="<?php echo e($provs->preferencia); ?>" 
										type="text"  
										class="form-control">
								</div>
							</div>
						</div>
						<div class="row col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<!-- USUARIO -->
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label for="usuario">Usuario</label>
									<input name="usuario" 
										style="background-color: #FFE7E5" 
										value="<?php echo e($provs->usuario); ?>" 
										type="text" 
										class="form-control">
								</div>
							</div>
							<!-- CLAVE -->
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label for="clave">Clave</label>
									<input name="clave" 
										style="background-color: #FFE7E5" 
										value="<?php echo e($provs->clave); ?>" 
										type="text"  
										class="form-control">
								</div>
							</div>

							<!-- CORREO -->
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label title = "Solo aplica para aquellos proveedores que esten configurado para recibir el pedido por correo" for="clave">Correo recibe el pedido</label>
									<input name="correoEnvioPedido" 
										style="background-color: #FFE7E5" 
										value="<?php echo e($maeprove->correoEnvioPedido); ?>" 
										type="text"  
										class="form-control">
								</div>
							</div>

							<!-- STATUS -->
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label for="statusclieprove">Status</label>
									<select name="statusclieprove" class="form-control">
										<?php if($provs->statusclieprove == 'ACTIVO'): ?>
									        <option value="ACTIVO" selected >ACTIVO</option>
							    			<option value="INACTIVO">INACTIVO</option>
							    		<?php else: ?>
							    			<option value="ACTIVO">ACTIVO</option>
							    			<option value="INACTIVO" selected>INACTIVO</option>
							    		<?php endif; ?>
						    	    </select>
								</div>
							</div>
						</div>
						<div class="row col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label >Modo cambiario:</label>
									<input readonly name="factorModo" value="<?php echo e($provs->factorModo); ?>" type="text" class="form-control">
								</div>
							</div>

							<?php if($provs->factorModo == "PREDETERMINADO"): ?>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
									<div class="form-group">
										<label>factor Seleccion</label>
										<input readonly name="factorSeleccion" value="<?php echo e($provs->factorSeleccion); ?>" type="text"  class="form-control">
									</div>
								</div>

								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
									<div class="form-group">
										<label>Factor cambiario</label>
										<input readonly name="FactorCambiario" value="<?php echo e(number_format($provs->FactorCambiario, 2, '.', ',')); ?>" type="text"  class="form-control">
									</div>
								</div>
							<?php else: ?>

		                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
		                            <div class="form-group">
		                                <label>factor Seleccion</label>
		                                <select name="factorSeleccion" id="SelClick" class="form-control">
		                                    <option value="BCV" 
		                                    <?php if($provs->factorSeleccion == "BCV"): ?> selected <?php endif; ?>
		                                    >
		                                    BCV
		                                    </option>
		                                    <option value="TODAY" 
		                                    <?php if($provs->factorSeleccion == "TODAY"): ?> selected <?php endif; ?>
		                                    >
		                                    TODAY
		                                    </option>
											<option value="MANUAL" 
											<?php if($provs->factorSeleccion == "MANUAL"): ?> selected <?php endif; ?>
		                                    >
		                                    MANUAL
		                                    </option>
		                                </select>
		                            </div>
		                        </div>

		                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
									<div class="form-group">
										<label>Factor cambiario2</label>
										<input id="FactorCambiario" 
											style="text-align: right;"
											readonly="" 
											name="FactorCambiario" 
											value="<?php echo e(number_format($provs->FactorCambiario,2,'.', ',')); ?>"
											type="text"  
											class="form-control">
									</div>
								</div>

							<?php endif; ?>

						</div>

                    </div>
                </div>
                <div class="tab-pane" id="tab_2">
                    <div class="row">

						<div class="row col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<!-- DC -->
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label for="dcme">Dcto comercial</label>
									<input name="dcme" 
										value="<?php echo e($provs->dcme); ?>" 
										type="text" 
										class="form-control"
										style="text-align: right;"
										title="DESCUENTO COMERCIAL ASIGANDO POR EL PROVEEDOR" 
										<?php if($provs->updCondComercial == 1): ?> readonly <?php endif; ?>>
								</div>
							</div>
							<!-- PP -->
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label for="ppme">Dcto pronto pago</label>
									<input name="ppme" 
										value="<?php echo e($provs->ppme); ?>" 
										type="text" 
										class="form-control"
										title="DESCUENTO PRONTO PAGO, NEGOCIADO POR EL PROVEEDOR" 
										style="text-align: right;">
								</div>
							</div>
							<!-- Di -->
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label for="di">Dcto Internet</label>
									<input name="di" 
										value="<?php echo e($provs->di); ?>" 
										type="text" 
										class="form-control"
										title="DESCUENTO DE INTERNET, NEGOCIADO POR PROVEEDOR" 
										style="text-align: right;">
								</div>
							</div>
							<!-- DCREDITO -->
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label for="dcredito">Dias credito</label>
									<input name="dcredito" 
										value="<?php echo e($provs->dcredito); ?>" 
										type="number" 
										class="form-control"
										title="DIAS DE CREDITO ASIGNADO POR EL PROVEEDOR" 
										style="text-align: right;" >
								</div>
							</div>
						</div>
						<div class="row col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<!-- DCMER -->
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label for="dcmer">Dcto comercial regulados</label>
									<input readonly name="dcmer" value="<?php echo e($provs->dcmer); ?>" type="text" class="form-control" style="text-align: right;">
								</div>
							</div>
							<!-- DCMI -->
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label for="dcmi">Dcto comercial miscelaneos</label>
									<input readonly name="dcmi" value="<?php echo e($provs->dcmi); ?>" type="text" class="form-control" style="text-align: right;">
								</div>
							</div>
							<!-- DCMIR -->
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label for="dcmir">Dcto comercial misc. reg.</label>
									<input readonly name="dcmir" value="<?php echo e($provs->dcmir); ?>" type="text" class="form-control" style="text-align: right;">
								</div>
							</div>
							<!-- PPMER -->
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label for="ppmi">Dcto pronto pago misc.</label>
									<input readonly name="ppmi" value="<?php echo e($provs->ppmi); ?>" type="text"  class="form-control" style="text-align: right;">
								</div>
							</div>
							
							<!-- DO -->
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label for="dotro">Dcto Otros</label>
									<input readonly name="dotro" value="<?php echo e($provs->dotro); ?>" type="text" class="form-control" style="text-align: right;">
								</div>
							</div>

							<!-- ACTUALIZAR COND. COMERCIALES AUTOMATICAMENTE -->
							<?php if($maeprove->modoEnvioPedido == 'MYSQL'): ?>
						    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
						        <div class="form-group" style="padding-top: 30px;">
						            <div class="form-check">
						                <input name="updCondComercial" 
						                	type="checkbox" 
						                	title="EL DECUENTO COMERCIAL SERA TOMADO AUTOMATICAMENTE DEL PORTAL WEB DEL PROVEEDOR" 
						                	<?php if($provs->updCondComercial == 1): ?> checked <?php endif; ?>
						                	class="form-check-input" >
						                <label class="form-check-label" 
						                        for="materialUnchecked">
						                    Act. Descuento Comercial Automatico
						                </label>
						            </div>
						        </div>
						    </div>
						    <?php endif; ?>

						</div>

                    </div>
                </div>
                <div class="tab-pane" id="tab_3">
                    <div class="row">
						<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
							<div class="form-group">
								<label>Código del proveedor</label>
								<input name="codprove_adm" value="<?php echo e($provs->codprove_adm); ?>" type="text" class="form-control" >
							</div>
						</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row col-xs-12" style="margin-left: 3px;">
	<div class="form-group">
		<button type="button" class="btn-normal" onclick="history.back(-1)">Regresar</button>
		<button type="submit" class="btn-confirmar">Guardar</button>
	</div>
</div>
<?php echo e(Form::close()); ?>



<?php $__env->startPush('scripts'); ?>
<script>
$('#subtitulo').text('<?php echo e($subtitulo); ?>');
$('#SelClick').on('change', function()
{
    var factorSeleccion = this.value;
  	//alert(factorSeleccion);  
  	if (factorSeleccion == "MANUAL")
  		document.getElementById('FactorCambiario').readOnly = false;
  	else
  		document.getElementById('FactorCambiario').readOnly = true;

});


</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/isacom/proveedor/edit.blade.php ENDPATH**/ ?>