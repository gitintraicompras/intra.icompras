
<?php $__env->startSection('contenido'); ?>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">	
		
		<?php echo Form::open(array('url'=>'/seped/reclamo','method'=>'POST','autocomplete'=>'off')); ?>

		<?php echo e(Form::token()); ?>


		<label>Factura:</label>
		<div class="form-group">
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 input-group input-group-sm">
		    	<select name="factnum" class="form-control selectpicker " data-live-search="true">
		    		<?php $__currentLoopData = $tabla; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
		   				<option value="<?php echo e($t->factnum); ?>"><?php echo e($t->factnum); ?>  -  <?php echo e(date('d-m-Y', strtotime($t->fecha))); ?></option>
		    		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		    	</select>
			</div>
		</div>

		<div class="form-group">
			<a href="<?php echo e(URL::action('AdreclamoController@index')); ?>">
			    <button type="button" class="btn-normal" data-toggle="tooltip" title="Regresar">Regresar</button>
			</a>
			<button class="btn-confirmar" type="submit" data-toggle="tooltip" title="Crear reclamo">Crear</button>
		</div>
	
		<?php echo e(Form::close()); ?>

	</div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
$('#subtitulo').text('<?php echo e($subtitulo); ?>');
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/canales/licencia/create.blade.php ENDPATH**/ ?>