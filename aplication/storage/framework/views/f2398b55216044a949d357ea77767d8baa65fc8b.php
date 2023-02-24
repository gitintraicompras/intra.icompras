<?php echo Form::open(array('url'=>'/invminmax','method'=>'GET','autocomplete'=>'off','role'=>'search')); ?>

<div class="input-group">

	<select name="filtro"  
        class="form-control selectpicker" 
        data-live-search="true"
        style="width: calc(100% - 32px);" >
		<?php $__currentLoopData = $marca; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ma): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	      <?php if($filtro == $ma): ?>
	        <option selected value="<?php echo e($ma); ?>">
	          <?php echo e($ma); ?>

	        </option>
	      <?php else: ?>
	        <option value="<?php echo e($ma); ?>">
	          <?php echo e($ma); ?>

	        </option>
	      <?php endif; ?>
	    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	</select>
	
	<span class="input-group-btn">
		<button type="submit" 
			class="btn btn-buscar" 
			data-toggle="tooltip" 
			title="Buscar producto">
			<span class="fa fa-search" aria-hidden="true"></span>
		</button>
	</span>
</div>
<?php echo e(Form::close()); ?>



<?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/isacom/invminmax/search.blade.php ENDPATH**/ ?>