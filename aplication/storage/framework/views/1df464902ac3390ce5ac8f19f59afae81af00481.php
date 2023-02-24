<?php $__env->startSection('contenido'); ?>

<?php echo e(Form::Open(array('action'=>'Canales\ActivacionController@grabar'))); ?>

<?php echo e(Form::token()); ?>


  	<div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab_1" data-toggle="tab"><B>BASICA</B></a></li>
          <li><a href="#tab_2" data-toggle="tab"><B>DETALLES</B></a></li>
          <li><a href="#tab_3" data-toggle="tab"><B>PROVEEDORES</B></a></li>
          <li class="pull-right"><a href="<?php echo e(url('/seped/config')); ?>" class="text-muted"><i class="fa fa-gear"></i></a></li>
        </ul>
        <div class="tab-content">
          	<div class="tab-pane active" id="tab_1">
			        <div class="row">

	 	        	<!-- RIF -->
					<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
						<div class="form-group">
							<label>Rif: (*)</label>
							<input type="text" 
								name="rif"
								maxlength="50" 
								class="form-control">
						</div>
					</div>
		
					<!-- NOMBRE -->
					<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
						<div class="form-group">
							<label>Nombre: (*)</label>
							<input type="text" 
								name="nombre" 
								maxlength="100" 
								class="form-control">
						</div>
					</div>
				
					<!-- DIRECCION -->
					<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
						<div class="form-group">
							<label>Dirección: (*)</label>
							<input type="text" 
								name="direccion" 
								maxlength="120" 
								class="form-control" 
								value="N/A">
						</div>
					</div>

					<!-- TELEFONO -->	
					<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
						<div class="form-group">
							<label>Telefono: (*)</label>
							<input type="text" 
								name="telefono"
								maxlength="100"  
								class="form-control" 
								value="N/A">
						</div>
					</div>

					<!-- CONTACTO -->
					<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
						<div class="form-group">
							<label>Contacto: (*)</label>
							<input type="text" 
								name="contacto" 
								maxlength="50" 
								class="form-control" 
								value="N/A">
						</div>
					</div>

					<!-- ZONA -->
					<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
						<div class="form-group">
							<label>Localidad: (*)</label>
							<input type="text" 
								name="localidad" 
								maxlength="50" 
								class="form-control" 
								value="MARACAIBO-ZULIA">
						</div>
					</div>

				</div>
          	</div>
	        <div class="tab-pane" id="tab_2">
				<div class="row">

					<!-- APLICACION -->
					<div class="col-lg-6 col-md-6 col-sm-3 col-xs-12">
						<div class="form-group">
							<label>Aplicación (ERP)</label>
							<select name="erp" class="form-control">
					    		<option value="SAINT">SAINT</option>
					    		<option value="A2">A2</option>
					    		<option value="STELLAR">STELLAR</option>
					    		<option value="PROFIT">PROFIT</option>
					    		<option value="SMARTPHARMA">SMARTPHARMA</option>
					    		<option value="PREMIUM">PREMIUM</option>
					    		<option value="HYBRID">HYBRID</option>
					    		<option value="EFICASIS">EFICASIS</option>
					    		<option value="OTROS">OTROS</option>
					    	</select>
						</div>
					</div>

			        <!-- SECTOR -->
					<div class="col-lg-6 col-md-6 col-sm-3 col-xs-12">
					    <div class="form-group">
					    	<label>Sector</label>
					    	<select name="sector" class="form-control">
					    		<option value="FARMACIA">FARMACIA</option>
					    		<option value="DROGUERIA">DROGUERIA</option>
					    		<option value="HOSPITAL">HOSPITAL</option>
					    		<option value="CONSUMO MASIVO">CONSUMO MASIVO</option>
					    		<option value="OTROS">OTROS</option>
					    	</select>
					    </div>
				    </div>

				    <!-- VENDEDOR -->
					<div class="col-lg-6 col-md-6 col-sm-3 col-xs-12">
					    <div class="form-group">
					    	<label>Vendedor</label>
					    	<select name="codvendedor" class="form-control">
								<?php $__currentLoopData = $vendedor; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					    			<option value="<?php echo e($v->codvendedor); ?>"><?php echo e($v->codvendedor); ?>-<?php echo e($v->nombre); ?>

					    			</option>
					    		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					    	</select>
					    </div>
				    </div>

				    <!-- VERSION DEL ICOMPRAS -->
					<div class="col-lg-6 col-md-6 col-sm-3 col-xs-12">
					    <div class="form-group">
					    	<label>Version icompras</label>
					    	<select name="vericompras" class="form-control">
					    		<option value="LIGHT">LIGHT</option>
					    		<option value="FULL">FULL</option>
					    	</select>
					    </div>
				    </div>

				    <!-- LINK PAGINA -->
					<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
						<div class="form-group">
							<label">Link pagina: (*)</label>
							<input type="text" 
								name="linkpagina"
								maxlength="100"  
								class="form-control" 
								value="N/A">
						</div>
					</div>

					<!-- CORREO -->
					<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
						<div class="form-group">
							<label>Correo: (*)</label>
							<input type="mail" 
								name="correo"
								maxlength="100" 
								class="form-control">
						</div>
					</div>

				</div>
	        </div>
	        <div class="tab-pane" id="tab_3">
				<div class="row">

					<div class="table-responsive">
						<table class="table table-striped table-bordered table-condensed table-hover">

							<thead class="colorTitulo">
								<th colspan="4">
									<center>PROVEEDOR</center>
								</th>
								<th colspan="4">
									<center>DATOS DEL CLIENTE</center>
								</th>
								<th colspan="3">
									<center>DESCUENTOS</center>
								</th>
							</thead>

							<thead class="colorTitulo">
								<th>ACTIVAR</th>
								<th style="width: 60px;">IMAGEN</th>
								<th style="width:120px;">DESCRIPCION</th>
								<th >SEDE</th>
								<th style="width:140px;" title="">CODIGO (*)</th>
								<th style="width:140px;" title="">USUARIO (*)</th>
								<th style="width:140px;" title="">CLAVE (*)</th>
								<th style="width:140px;" title="">DIAS CREDITO</th>
								<th style="width:150px;" title="">COMERCIAL</th>
								<th style="width:180px;" title="">PRONTO PAGO</th>
								<th style="width:100px;" title="">INTERNET</th>
							</thead>
							<?php $__currentLoopData = $maeprove; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<tr>
								<td style="padding-top: 10px;">
									<span>
									<center>
									    <input type="checkbox" 
									    	name="activar[<?php echo e($p->codprove); ?>-<?php echo e($loop->iteration); ?>]" />
									</center>
									</span>
								</td>
								<td>
				                    <div align="center">
				                        <img src="http://isaweb.isbsistemas.com/public/storage/prov/<?php echo e($p->rutalogo1); ?>" 
				                            class="img-responsive" 
				                            alt="icompras" style="width: 100px;">
				                    </div>
				                </td>
								<td><?php echo e($p->descripcion); ?><br>
									<?php echo e(strtoupper($p->nombre)); ?><br>
									<?php echo e($p->codprove); ?>

									<input type="text" 
										hidden="" 
							            name="codprove[]"
			                            value="N/A">
								</td>
								<td><?php echo e($p->codsede); ?><br>
									<?php echo e(strtoupper($p->localidad)); ?>

								</td>

								<td class="hover"
						            title="CODIGO DEL CLIENTE OTORGADO POR EL PROVEEDOR !!!">
									<input type="text" 
							            name="codigo[]"
							            value="N/A"
			                            class="form-control">
								</td>
								<td class="hover"
									title="USUARIO DE ACCESO AL PORTAL WEB DEL PROVEEDOR !!!">
									<input type="text" 
							            name="usuario[]"
			                            value="N/A"
			                            <?php if($p->tipocata=='ISB' || $p->tipocata=='ISB2' || $p->tipocata=='DRONENA' || $p->tipocata=='DROLANCA'): ?>
			                            readonly="" 
			                            <?php endif; ?>
			                            class="form-control">
								</td>
								<td class="hover"
									title="CLAVE DE ACCESO AL PORTAL WEB DEL PROVEEDOR !!!">
									<input type="text" 
							            name="clave[]"
			                            value="N/A"
			                            <?php if($p->tipocata=='ISB' || $p->tipocata=='ISB2' || $p->tipocata=='DRONENA' || $p->tipocata=='DROLANCA'): ?>
			                            readonly="" 
			                            <?php endif; ?>
			                            class="form-control">
								</td>
								<td class="hover"
									title="DIAS DE CREDITO OTORGADOS POR EL PROVEEDOR !!!">
									<input type="text" 
								 		style="text-align: right;" 
			                            name="dcredito[]"
			                            value="7"
			                            class="form-control">
								</td>
								<td class="hover"
									title="DESCUENTO COMERCIAL OTORGADOS POR EL PROVEEDOR !!!">
									<input type="text" 
								 		style="text-align: right;" 
			                            name="dc[]"
			                            value="0.00"
			                            class="form-control">
								</td>
								<td class="hover"
									title="DESCUENTO PRONTO PAGO OTORGADOS POR EL PROVEEDOR !!!">
									<input type="text" 
								 		style="text-align: right;" 
			                            name="pp[]"
			                            value="0.00"
			                            class="form-control">
								</td>
								<td class="hover"
									title="DESCUENTO INTERNET OTORGADOS POR EL PROVEEDOR !!!">
									<input type="text" 
								 		style="text-align: right;" 
			                            name="di[]"
			                            value="0.00"
			                            class="form-control">
								</td>

							</tr>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</table>
					</div>

			
				</div>
	        </div>
	        <span>(*) Campos de carater obligatorios</span>
        </div>
    </div>

  	<!-- BOTON GUARDAR/CANCELAR -->
	<div class="form-group" style="margin-top: 20px; margin-left: 15px;">
		<button type="button" class="btn-normal" onclick="history.back(-1)">Regresar</button>
		<button class="btn-confirmar" type="submit">Guardar</button>
	</div>
<?php echo e(Form::close()); ?>


<?php $__env->startPush('scripts'); ?>
<script>
$('#subtitulo').text('<?php echo e($subtitulo); ?>');
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/canales/activacion/index.blade.php ENDPATH**/ ?>