<?php echo Form::open(array('url'=>'/ofertas/registros','method'=>'GET','autocomplete'=>'off','role'=>'search')); ?>

<div class="input-group" style="margin-top: 5px; margin-bottom: 5px;" >
   <span class="input-group-addon hidden-xs" style="text-align: right; border: 0px;" >Fecha:</span>
   <span class="input-group-btn">
   	<input type="date" name="desde" class="form-control" value="<?php echo e(date('Y-m-d', strtotime($desde))); ?>">
   </span>
   <span class="input-group-addon" style="border:0px; "></span>
   <span class="input-group-btn">
   	<input type="date" name="hasta" class="form-control" value="<?php echo e(date('Y-m-d', strtotime($hasta))); ?>">
   </span>
   <span class="input-group-btn">
   	<button type="submit" class="btn btn-buscar" data-toggle="tooltip" title="Filtrar por fecha">
         <span class="fa fa-search" aria-hidden="true"></span>
      </button>
   </span>
</div>
<?php echo e(Form::close()); ?>

<?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/ofertas/registros/search.blade.php ENDPATH**/ ?>