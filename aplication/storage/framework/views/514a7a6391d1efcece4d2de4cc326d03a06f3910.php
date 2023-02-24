
<?php $__env->startSection('contenido'); ?>
<?php echo Form::open(array('url'=>'/canales/activacion','method'=>'POST','autocomplete'=>'off')); ?>

<?php echo e(Form::token()); ?>

<div class="row">
	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
		<div class="form-group">
			<label>Rif (** J######### **)</label>
			<input name="rif" 
				type="text" 
				autofocus 
				class="form-control" 
				style="background-color: #FFE7E5">
		</div>
	</div>
</div>
<div class="form-group">
	<a href="<?php echo e(URL::action('Canales\ActivacionController@index')); ?>">
	    <button type="button" 
	    	class="btn-normal" 
	    	data-toggle="tooltip" 
	    	title="Regresar">
	    	Regresar
	    </button>
	</a>
	<button class="btn-confirmar" 
		type="submit" 
		data-toggle="tooltip" 
		title="Verificar rif">
		Verificar
	</button>
</div>
<br>
<?php echo e(Form::close()); ?>

<?php $__env->startPush('scripts'); ?>
<script>
$('#subtitulo').text('<?php echo e($subtitulo); ?>');
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/canales/activacion/create.blade.php ENDPATH**/ ?>