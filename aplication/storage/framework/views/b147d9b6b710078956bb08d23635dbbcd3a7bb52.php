
<?php $__env->startSection('contenido'); ?>
<?php echo Form::model($reg,['method'=>'PATCH','route'=>['vendedor.update',$reg->codvendedor]]); ?>

<?php echo e(Form::token()); ?>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
<div class="row">
	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
		<div class="form-group">
			<label>Código</label>
			<input readonly value="<?php echo e($reg->codvendedor); ?>" 
				type="text" 
				name="codvendedor" 
				class="form-control">
		</div>
	</div>
	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
		<div class="form-group">
			<label>Nombre</label>
			<input value="<?php echo e($reg->nombre); ?>" 
				type="text" 
				name="nombre" 
				class="form-control">
		</div>
	</div>
</div>
</div>
<!-- BOTON REGRESAR/GUARDAR -->
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

<?php echo $__env->make('layouts.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/canales/vendedor/edit.blade.php ENDPATH**/ ?>