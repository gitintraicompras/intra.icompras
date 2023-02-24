
<?php $__env->startSection('contenido'); ?>
<?php echo Form::model($canal,['method'=>'PATCH','route'=>['canal.update',Auth::user()->codcli]]); ?>

<?php echo e(Form::token()); ?>

<div class="row">

	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
		<div class="form-group">
			<label for="codigo">Nombre</label>
			<input readonly="" value="<?php echo e($canal->descrip); ?>" type="text" name="descrip" class="form-control">
		</div>
	</div>

	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
		<div class="form-group">
			<label for="nombre">Fecha</label>
			<input readonly="" value="<?php echo e($canal->fecha); ?>" type="date" name="fecha" class="form-control fecha">
		</div>
	</div>

	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
		<div class="form-group">
	    	<label>Estado</label>
	    	<select name="estado" 
	    		readonly 
	    		class="form-control">
	    		<?php if($canal->estado == 'ACTIVO'): ?>
		    		<option value="ACTIVO" selected>ACTIVO</option>
		    		<option value="INACTIVO">INACTIVO</option>
	    		<?php else: ?>
		    		<option value="ACTIVO">ACTIVO</option>
		    		<option value="INACTIVO" selected>INACTIVO</option>
	    		<?php endif; ?>
	    	</select>
	    </div>
    </div>

	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
		<div class="form-group">
			<label for="rif">Rif</label>
			<input value="<?php echo e($canal->rif); ?>" type="text" name="rif" class="form-control">
		</div>
	</div>

	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
		<div class="form-group">
			<label for="direccion">Dirección</label>
			<input value="<?php echo e($canal->direccion); ?>" type="text" name="direccion" class="form-control">
		</div>
	</div>

	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
		<div class="form-group">
			<label for="telefono">Telefono</label>
			<input value="<?php echo e($canal->telefono); ?>" type="text" name="telefono" class="form-control">
		</div>
	</div>

	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
		<div class="form-group">
			<label for="contacto">Contacto</label>
			<input value="<?php echo e($canal->contacto); ?>" type="text" name="contacto" class="form-control">
		</div>
	</div>

	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
		<div class="form-group">
			<label for="correo">Correo</label>
			<input value="<?php echo e($canal->correo); ?>" type="mail" name="correo" class="form-control">
		</div>
	</div>

	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
		<div class="form-group">
			<label for="zona">Zona</label>
			<input value="<?php echo e($canal->zona); ?>" type="text" name="zona" class="form-control">
		</div>
	</div>

	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
		<div class="form-group">
			<label for="opc1">Opcional 1</label>
			<input value="<?php echo e($canal->opc1); ?>" type="text" name="opc1" class="form-control">
		</div>
	</div>

	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
		<div class="form-group">
			<label for="opc2">Opcional 2</label>
			<input value="<?php echo e($canal->opc2); ?>" type="text" name="opc2" class="form-control">
		</div>
	</div>

	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
		<div class="form-group">
			<label for="opc3">Opcional 3</label>
			<input value="<?php echo e($canal->opc3); ?>" type="text" name="opc3" class="form-control">
		</div>
	</div>

	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
		<div class="form-group">
			<label for="opc4">Opcional 4</label>
			<input value="<?php echo e($canal->opc4); ?>" type="text" name="opc4" class="form-control">
		</div>
	</div>

	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
		<div class="form-group">
			<label for="opc5">Opcional 5</label>
			<input value="<?php echo e($canal->opc5); ?>" type="text" name="opc5" class="form-control">
		</div>
	</div>

</div>

<!-- BOTON REGRESAR/GUARDAR -->
<div class="form-group" style="margin-top: 20px;">
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

<?php echo $__env->make('layouts.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/canales/canal/edit.blade.php ENDPATH**/ ?>