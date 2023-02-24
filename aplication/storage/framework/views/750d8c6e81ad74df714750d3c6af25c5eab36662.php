<?php echo Form::open(array('url'=>'/grupo','method'=>'GET','autocomplete'=>'off','role'=>'search')); ?>

<div class="input-group md-form form-sm form-2 pl-0" 
  	style="width: 29%; margin-right: 3px;">
	<input type="text" 
		name="filtro" 
		class="form-control" 
		placeholder="Buscar"  
		value="<?php echo e($filtro); ?>">
	<span class="input-group-btn">
		<button type="submit" 
			class="btn btn-buscar" 
			data-toggle="tooltip" 
			title="Buscar cliente">
			<span class="fa fa-search" aria-hidden="true"></span>
		</button>
	</span>
</div>
<?php echo e(Form::close()); ?>

<?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/isacom/grupo/search.blade.php ENDPATH**/ ?>