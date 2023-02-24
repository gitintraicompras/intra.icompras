
<?php $__env->startSection('contenido'); ?>


<?php echo Form::open(array('url'=>'/invsugerido','method'=>'POST','autocomplete'=>'off', 'enctype'=>'multipart/form-data')); ?>

<?php echo e(Form::token()); ?>

<input hidden name="modalidad" value="PROCESAR" type="text">
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
   
   	<div class="row">

   		<div class="col-xs-3">
			<div class="form-group">
				<label>Codigo</label>
				<input type="text" 
					name="codcli" 
					value="<?php echo e($codcli); ?>"
					readonly="" 
					class="form-control">
			</div>
		</div>

		<div class="col-xs-3">
			<div class="form-group">
				<label>Tipo pedido</label>
				<input type="text" 
					name="tipedido" 
					value="N"
					readonly="" 
					class="form-control">
			</div>
		</div>

   	    <div class="col-xs-6">
			<div class="form-group">
		    	<label>Criterio</label>
		    	<select name="criterio" class="form-control">
		    		<option value="PRECIO">PRECIO</option>
		    		<option value="INVENTARIO">INVENTARIO</option>
		    		<option value="DIAS">DIAS</option>
		    	</select>
		    </div>
	    </div>

	    <div class="col-xs-6">
			<div class="form-group">
		    	<label>Preferencia</label>
		    	<select name="preferencia" class="form-control">
		    		<option value="NINGUNA">NINGUNA</option>
		    		<option value="PRIMER">PRIMER PROVEEDOR</option>
		    	</select>
		    </div>
	    </div>

    </div>

</div>


<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	<br><br>
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
<?php echo $__env->make('layouts.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/isacom/invsugerido/procesar.blade.php ENDPATH**/ ?>