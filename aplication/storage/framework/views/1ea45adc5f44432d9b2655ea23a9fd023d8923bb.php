
<?php $__env->startSection('contenido'); ?>
  

<?php echo Form::open(array('url'=>'/invsugerido','method'=>'POST','autocomplete'=>'off', 'enctype'=>'multipart/form-data')); ?>

<?php echo e(Form::token()); ?>

<input hidden name="modalidad" value="CREAR" type="text">
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
   
   	<div class="row">

   		<div class="col-xs-3">
			<div class="form-group">
				<label>Codigo</label>
				<input type="text"
					name="codcli" 
					readonly="" 	 
					value="<?php echo e($codcli); ?>" 
					class="form-control">
			</div>
		</div>

   		<div class="col-xs-3">
			<div class="form-group">
				<label>Dias de reposición</label>
				<input type="number" name="reposicion" value="7" class="form-control">
			</div>
		</div>

		<div class="col-xs-6">
			<div class="form-group">
				<label>Aplicar VMD por defecto a los productos con valor cero</label>
				<input type="text" name="vmd" value="<?php echo e(number_format(0.0000, 4, '.', ',')); ?>" class="form-control">
			</div>
		</div>


	    <div class="col-xs-4">
			<div class="form-group">
				<label>Descripción</label>
				<input type="text" name="desprod" value="" class="form-control">
			</div>
		</div>

		<div class="col-xs-4">
			<div class="form-group">
				<label>Marca</label>
				<select name="marca" 
					class="form-control selectpicker" 
		    		data-live-search="true" >
		    		<option select value="">&nbsp;</option>
		    		<?php $__currentLoopData = $marca; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
		   				<option value="<?php echo e($m->descrip); ?>">
							<?php echo e($m->descrip); ?>

						</option>
			 		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		    	</select>
			</div>
		</div>

		<div class="col-xs-4">
			<div class="form-group">
				<label>Categoria</label>
				<select name="categoria" 
					class="form-control selectpicker" 
		    		data-live-search="true" >
		    		<option select value="">&nbsp;</option>
		    		<?php $__currentLoopData = $categoria; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
		   				<option value="<?php echo e($c->descrip); ?>">
							<?php echo e($c->descrip); ?>

						</option>
			 		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		    	</select>
			</div>
		</div>

    </div>

</div>


<br><br><br><br><br><br><br><br>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	<div class="form-group">
		<div class="form-group">
		    <button type="button" class="btn-normal" onclick="history.back(-1)" data-toggle="tooltip" title="Regresar">Regresar</button>
			<button class="btn-confirmar" type="submit" data-toggle="tooltip" title="Confirmar proceso">
			Confirmar
			</button>
		</div>
	</div>
</div>
<?php echo e(Form::close()); ?>


<?php $__env->startPush('scripts'); ?>
<script>
$('#subtitulo').text('<?php echo e($subtitulo); ?>');
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/isacom/invsugerido/crear.blade.php ENDPATH**/ ?>