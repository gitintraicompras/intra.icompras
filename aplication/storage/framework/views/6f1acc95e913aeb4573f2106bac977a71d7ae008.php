<?php echo Form::open(array('url'=>'/canales/licencia','method'=>'GET','autocomplete'=>'off','role'=>'search')); ?>

<div class="form-group">
	<div class="input-group">
		<input type="text" 
			name="filtro" 
			class="form-control"
			placeholder="Buscar" 
			value="<?php echo e($filtro); ?>">
		<span class="input-group-btn">
			<button type="submit" class="btn btn-buscar" >
				<span class="fa fa-search" aria-hidden="true"></span>
			</button>
		</span>
	</div>
</div>
<?php echo e(Form::close()); ?><?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/canales/licencia/search.blade.php ENDPATH**/ ?>