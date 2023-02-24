<?php echo Form::open(array('url'=>'/pedido/catalogo/'.$tipo.'-'.$tpactivo,'method'=>'GET','autocomplete'=>'off','role'=>'search')); ?>


<?php if($tipo == "M"): ?> 

  <div class="input-group md-form form-sm form-2 pl-0" 
    style="width: 29%; margin-right: 3px;">
      <select name="filtro"  
        class="form-control selectpicker" 
        data-live-search="true"
        style="width: calc(100% - 32px);" >

        <?php $__currentLoopData = $molecula; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mol): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <?php if($filtro == $mol->descrip): ?>
            <option selected value="<?php echo e($mol->descrip); ?>">
              <?php echo e($mol->descrip); ?>

            </option>
          <?php else: ?>
            <option value="<?php echo e($mol->descrip); ?>">
              <?php echo e($mol->descrip); ?>

            </option>
          <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </select>
      <span class="input-group-btn">
          <button type="submit" 
            class="btn btn-buscar" 
            data-toggle="tooltip" 
            style="border-radius: 0 5px 5px 0"
            title="Buscar producto">
              <span class="fa fa-search" aria-hidden="true"></span>
          </button>
      </span>
    </span>
  </div>

<?php else: ?> 

  <div class="input-group md-form form-sm form-2 pl-0" 
  	style="width: 29%; margin-right: 3px;">
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
<?php endif; ?>
<?php echo e(Form::close()); ?>


<?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/isacom/pedido/catasearch.blade.php ENDPATH**/ ?>