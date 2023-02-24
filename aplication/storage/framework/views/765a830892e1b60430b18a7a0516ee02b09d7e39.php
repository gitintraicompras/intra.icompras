
<?php $__env->startSection('contenido'); ?>

<section class="content" >
	<!-- Info boxes -->
	<div class="row" >
		<?php $__currentLoopData = $carga; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	    <div class="col-md-6 col-sm-12 col-xs-12">
	      <div class="info-box" style="background-color: #f7f7f7;">
	      	<a href="<?php echo e(URL::action('DescargaController@show',$c->id)); ?>">
	        	<span class="info-box-icon colorTitulo"><i class="fa fa-download"></i></span>
	    	</a>
	        <div class="info-box-content">
	          <span class="info-box-text"><?php echo e($c->ruta); ?></span>
	          <span class="info-box-number" style="font-size: 14px;">
	          	<?php echo e($c->descrip); ?>

	          	<br>
	          	<small>(<?php echo e(number_format($c->contdescarga, 0, '.', ',')); ?>) descargas</small>
	          </span>
	        </div>
	      </div>
	    </div>
	    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

		<?php if(Auth::user()->tipo=="C" || Auth::user()->tipo=="G"): ?>
	    <!-- DESCARGA DE CATALOGO DE PROVEEDORES -->
	    <div class="col-md-6 col-sm-12 col-xs-12">
	      <div class="info-box" style="background-color: #f7f7f7;">
	    	<a href="" data-target="#modal-descargarCat" data-toggle="modal">
			    <span class="info-box-icon colorTitulo"><i class="fa fa-download"></i></span>
			</a>
		    <div class="info-box-content">
	          <span class="info-box-text">Proveedores</span>
	          <span class="info-box-number" style="font-size: 14px;">
	          	CATALOGO DE PRODUCTOS
	          	<br>
	          	<small></small>
	          </span>
	        </div>
	      </div>
	    </div>

	    <!-- DESCARGA DE RNK1 DE PROVEEDORES -->
	    <div class="col-md-6 col-sm-12 col-xs-12">
	      <div class="info-box" style="background-color: #f7f7f7;">
	    	<a href="" data-target="#modal-descargarRnk1" data-toggle="modal">
			    <span class="info-box-icon colorTitulo"><i class="fa fa-download"></i></span>
			</a>
		    <div class="info-box-content">
	          <span class="info-box-text">Proveedores</span>
	          <span class="info-box-number" style="font-size: 14px;">
	          	RNK1 DE PRODUCTOS
	          	<br>
	          	<small></small>
	          </span>
	        </div>
	      </div>
	    </div>
  
	    <!-- DESCARGA DE INVENTARIO DE CLIENTES -->
	    <div class="col-md-6 col-sm-12 col-xs-12">
	      <div class="info-box" style="background-color: #f7f7f7;">
	    	<a href="" data-target="#modal-descargarInv" data-toggle="modal">
			    <span class="info-box-icon colorTitulo"><i class="fa fa-download"></i></span>
			</a>
		    <div class="info-box-content">
	          <span class="info-box-text">Clientes</span>
	          <span class="info-box-number" style="font-size: 14px;">
	          	INVENTARIO DE PRODUCTOS
	          	<br>
	          	<small></small>
	          </span>
	        </div>
	      </div>
	    </div>
	    <?php endif; ?>

	</div>
</section>

<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modal-descargarCat">
<?php echo Form::open(array('action'=>array('DescargaController@catalogo','method'=>'POST','autocomplete'=>'off'))); ?>

<?php echo e(Form::token()); ?>

<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header colorTitulo" >
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">X</span>
			</button>
			<h4 class="modal-title">DESCARGAR CATALOGO</h4>
		</div>

		<?php
		$provs = TablaMaecliproveActiva();
	   	?>

		<div class="modal-body">
		 	<div class="row">
			    <div class="col-xs-12">
					<div class="form-group">

						<div class="form-group">
							<label>Proveedor</label>
							<select name="codprove" class="form-control" >
								<option value="tpmaestra">
									TPMAESTRA
								</option>
								<?php $__currentLoopData = $provs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prov): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
									<option value="<?php echo e($prov->codprove); ?>">
										<?php echo e($prov->codprove); ?> - <?php echo e($prov->descripcion); ?>

									</option>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							</select>
						</div>

				    </div>
			    </div>
		    </div>
		</div>

		<div class="modal-footer" style="margin-right: 20px;">
			<button type="button" class="btn-normal" data-dismiss="modal">Regresar</button>
			<button type="submit" class="btn-confirmar btnAccion">Confirmar</button>
		</div>
	</div>
</div>
<?php echo e(Form::Close()); ?>

</div>

<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modal-descargarRnk1">
<?php echo Form::open(array('action'=>array('DescargaController@rnk1','method'=>'POST','autocomplete'=>'off'))); ?>

<?php echo e(Form::token()); ?>

<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header colorTitulo" >
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">X</span>
			</button>
			<h4 class="modal-title">DESCARGAR RNK1</h4>
		</div>

		<?php
		$provs = TablaMaecliproveActiva();
	   	?>

		<div class="modal-body">
		 	<div class="row">
			    <div class="col-xs-12">
					<div class="form-group">

						<div class="form-group">
							<label>Proveedor</label>
							<select name="codprove" class="form-control" >
								<option value="tpmaestra">
									TPMAESTRA
								</option>
								<?php $__currentLoopData = $provs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prov): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
									<option value="<?php echo e($prov->codprove); ?>">
										<?php echo e($prov->codprove); ?> - <?php echo e($prov->descripcion); ?>

									</option>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							</select>
						</div>

				    </div>
			    </div>
		    </div>
		</div>

		<div class="modal-footer" style="margin-right: 20px;">
			<button type="button" class="btn-normal" data-dismiss="modal">Regresar</button>
			<button type="submit" class="btn-confirmar btnAccion">Confirmar</button>
		</div>
	</div>
</div>
<?php echo e(Form::Close()); ?>

</div>

<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modal-descargarInv">
<?php echo Form::open(array('action'=>array('DescargaController@inventario','method'=>'POST','autocomplete'=>'off'))); ?>

<?php echo e(Form::token()); ?>

<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header colorTitulo" >
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">X</span>
			</button>
			<h4 class="modal-title">DESCARGAR INVENTARIO</h4>
		</div>

		<div class="modal-body">
		 	<div class="row">
			    <div class="col-xs-12">
					<div class="form-group">

						<div class="form-group">
							<label>Cliente</label>
							<?php if(Auth::user()->tipo=="C"): ?>
								<select name="codcli" class="form-control" >
									<option value="<?php echo e(Auth::user()->codcli); ?>">
										<?php echo e(Auth::user()->codcli); ?>

									</option>
								</select>
							<?php else: ?>
								<?php
								$codgrupo = Auth::user()->codcli;
								$gruporen = DB::table('gruporen')
								->where('status','=', 'ACTIVO')
			                    ->where('id','=',$codgrupo)
			                    ->get();
							   	?>
								<select name="codcli" class="form-control" >
									<option value="tcmaestra<?php echo e($codgrupo); ?>">
										TCMAESTRA<?php echo e($codgrupo); ?>

									</option>
									<?php if($gruporen): ?>
										<?php $__currentLoopData = $gruporen; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											<?php
											$tabla = "inventario_".$gr->codcli;
					                        if (!VerificaTabla($tabla)) 
					                            continue;
					                        ?>
											<option value="<?php echo e($gr->codcli); ?>">
												<?php echo e($gr->codcli); ?> - <?php echo e($gr->nomcli); ?> 
											</option>
										<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
									<?php endif; ?>
								</select>
							<?php endif; ?>
						</div>

				    </div>
			    </div>
		    </div>
		</div>

		<div class="modal-footer" style="margin-right: 20px;">
			<button type="button" class="btn-normal" data-dismiss="modal">Regresar</button>
			<button type="submit" class="btn-confirmar btnAccion">Confirmar</button>
		</div>
	</div>
</div>
<?php echo e(Form::Close()); ?>

</div>


<?php $__env->startPush('scripts'); ?>
<script>
$('#subtitulo').text('<?php echo e($subtitulo); ?>');
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/isacom/descargas/index.blade.php ENDPATH**/ ?>