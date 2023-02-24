
<?php $__env->startSection('contenido'); ?>
  
<section class="content" style="height: 500px;" >
	 <!-- Info boxes -->
	<div class="row" >

	    <div class="col-md-6 col-sm-12 col-xs-12">
	      <div class="info-box" style="background-color: #f7f7f7;">
	      	<a href="<?php echo e(url('/molecula')); ?>">
	        	<span class="info-box-icon colorTitulo" >
	        		<i class="fa fa-share-alt"></i>
	        	</span>
	    	</a>
	        <div class="info-box-content">
	          <span class="info-box-text">REGISTRO</span>
	          <span class="info-box-number">
	          	Mantenimiento tabla de moleculas
	          	<br>
	          		<small>COMPRAS</small>
	          </span>
	        </div>
	      </div>
	    </div>

	

	</div>
</section>

<?php $__env->startPush('scripts'); ?>
<script>
$('#subtitulo').text('<?php echo e($subtitulo); ?>');
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/isacom/menumolecula/index.blade.php ENDPATH**/ ?>