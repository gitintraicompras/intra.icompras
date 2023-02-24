<?php echo Form::open(array('url'=>'/pedido/catalogo/TD','method'=>'GET','autocomplete'=>'off','role'=>'search')); ?>

<div class="input-group md-form form-sm form-2 pl-0" 
	style="width: 29%; margin-right: 3px; margin-bottom: 5px;">
  <input class="form-control my-0 py-1 red-border catserch" 
  	type="text" 
  	name="filtro" 
  	value="<?php echo e($filtro); ?>" 
  	placeholder="Buscar por descripción o referencia" 
  	aria-label="Search">
    <span class="input-group-btn">
        <button type="submit" 
        	class="btn btn-buscar" 
        	data-toggle="tooltip" 
          style="border-radius: 0 5px 5px 0"
        	title="Buscar producto">
            <span class="fa fa-search" aria-hidden="true"></span>
        </button>
    </span>
</div>
<?php echo e(Form::close()); ?> 

<?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/isacom/pedidodirecto/invsearch.blade.php ENDPATH**/ ?>