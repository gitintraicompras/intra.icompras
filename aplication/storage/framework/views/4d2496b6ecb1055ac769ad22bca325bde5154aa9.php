<?php echo Form::open(array('url'=>'/analisis','method'=>'GET','autocomplete'=>'off','role'=>'search')); ?>

<div class="input-group md-form form-sm form-2 pl-0" style="margin-right: 15px;">
  <input class="form-control my-0 py-1 red-border" 
  	type="text" 
  	name="filtro" 
  	value="<?php echo e($filtro); ?>" 
  	placeholder="Buscar" 
  	aria-label="Search">
    <span class="input-group-btn">
       	<button type="submit" 
       	class="btn btn-buscar" 
       	data-toggle="tooltip" 
       	title="Buscar producto">
            <span class="fa fa-search" aria-hidden="true"></span>
        </button>
    </span>
</div>
<?php echo e(Form::Close()); ?>



<?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/isacom/analisis/buscar.blade.php ENDPATH**/ ?>