<?php echo Form::open(array('url'=>'/proveedor','method'=>'GET','autocomplete'=>'off','role'=>'search')); ?>

<div class="input-group">
	<input type="text" name="filtro" class="form-control" placeholder="Buscar"  value="<?php echo e($filtro); ?>">
	<span class="input-group-btn">
		<button type="submit" 
			class="btn btn-buscar" 
			data-toggle="tooltip" 
			style="border-radius: 0 5px 5px 0;"
			title="Buscar proveedor">
			<span class="fa fa-search" 
				aria-hidden="true">
			</span>
		</button>
	</span>
</div>
<?php echo e(Form::close()); ?>



<?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/isacom/proveedor/search.blade.php ENDPATH**/ ?>