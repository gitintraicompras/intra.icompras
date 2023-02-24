
<?php $__env->startSection('contenido'); ?>
 
<section class="content" >
	 <!-- Info boxes -->
	<div class="row" >

	    <div class="col-md-6 col-sm-12 col-xs-12">
	      <div class="info-box" style="background-color: #f7f7f7;">
	      	<a href="<?php echo e(url('/invcliente')); ?>">
	        	<span class="info-box-icon colorTitulo" >
	        		<i class="fa fa-align-justify"></i>
	        	</span>
	    	</a>
	        <div class="info-box-content">
	          <span class="info-box-text">PRODUCTOS</span>
	          <span class="info-box-number">
	          	Inventario de productos
	          	<br>
	          	<?php if($contadorInv > 0): ?>
	          		<small>
	          		 Fecha: <?php echo e(date('d-m-Y H:i', strtotime($fechaInv))); ?>, Renglones: <?php echo e(number_format($contadorInv, 0, '.', ',')); ?> 
	          		</small>
	          	<?php else: ?>
	          		<small></small>
	          	<?php endif; ?>
	          </span>
	        </div>
	      </div>
	    </div>

	    <div class="col-md-6 col-sm-12 col-xs-12">
	      <div class="info-box" style="background-color: #f7f7f7;">
	      	<a href="<?php echo e(url('/invsugerido')); ?>">
	        	<span class="info-box-icon colorTitulo">
	        		<i class="fa fa-check-square-o"></i>
	        	</span>
	    	</a>
	        <div class="info-box-content">
	          <span class="info-box-text">SUGERIDOS</span>
	          <span class="info-box-number">
	          	Pedidos Sugeridos   
	          	<br>
	          	<?php if($contadorSug > 0): ?>
	          		<small>
	          		Fecha: <?php echo e(date('d-m-Y H:i', strtotime($fechaSug))); ?>, Renglones: <?php echo e(number_format($contadorSug, 0, '.', ',')); ?> 
	          		</small>
	          	<?php else: ?>
	          		<small></small>
	          	<?php endif; ?>
	          </span>
	        </div>
	      </div>
	    </div>

	    <div class="col-md-6 col-sm-12 col-xs-12">
	      <div class="info-box" style="background-color: #f7f7f7;">
	      	<a href="<?php echo e(url('/invfallas')); ?>">
	        	<span class="info-box-icon colorTitulo" >
	        		<i class="fa fa-thumbs-o-down"></i>
	        	</span>
	    	</a>
	        <div class="info-box-content">
	          <span class="info-box-text">FALLAS</span>
	          <span class="info-box-number">
	          	Ver fallas  
	          	<br>
	          	<?php if($contadorFalla > 0): ?>
	          		<small>
	          		Fecha: <?php echo e(date('d-m-Y H:i', strtotime($fechaFalla))); ?>, Renglones: <?php echo e(number_format($contadorFalla, 0, '.', ',')); ?> 
	          		</small>
	          	<?php else: ?>
	          		<small></small>
	          	<?php endif; ?>
	          </span>
	        </div>
	      </div>
	    </div>

	    <div class="col-md-6 col-sm-12 col-xs-12">
	      <div class="info-box" style="background-color: #f7f7f7;">
	      	<a href="<?php echo e(url('/analisis')); ?>">
	        	<span class="info-box-icon colorTitulo" >
	        		<i class="fa fa-list-alt"></i>
	        	</span>
	    	</a>
	        <div class="info-box-content">
	          <span class="info-box-text">ANALISIS</span>
	          <span class="info-box-number">
	          	Costos vs. Catálogo de proveedor  
	          	<br>
	      		<small></small>
	          </span>
	        </div>
	      </div>
	    </div>

	    <div class="col-md-6 col-sm-12 col-xs-12">
	      <div class="info-box" style="background-color: #f7f7f7;">
	      	<a href="<?php echo e(url('/invminmax')); ?>">
	        	<span class="info-box-icon colorTitulo" >
	        		<i class="fa fa-adjust"></i>
	        	</span>
	    	</a>
	        <div class="info-box-content">
	          <span class="info-box-text">DIAS MINIMOS Y MAXIMOS</span>
	          <span class="info-box-number">
	          	Dias Minimos y Maxmos de productos   
	          	<br>
	      		<small></small>
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
<?php echo $__env->make('layouts.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/isacom/inventario/index.blade.php ENDPATH**/ ?>