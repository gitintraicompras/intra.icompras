
<?php $__env->startSection('contenido'); ?>
  
<section class="content" >
	 <!-- Info boxes -->
	<div class="row" >

	    <div class="col-md-6 col-sm-12 col-xs-12">
	      <div class="info-box" style="background-color: #f7f7f7;">
	      	<a href="<?php echo e(url('/informes/pedidocli/line')); ?>">
	        	<span class="info-box-icon colorTitulo" >
	        		<i class="fa fa-line-chart"></i>
	        	</span>
	    	</a>
	        <div class="info-box-content">
	          <span class="info-box-text">PEDIDOS</span>
	          <span class="info-box-number">
	          	Monto, ultimos 30 dias
	          	<br>
	          		<small>iCOMPRAS360</small>
	          </span>
	        </div>
	      </div>
	    </div>

	    <div class="col-md-6 col-sm-12 col-xs-12">
	      <div class="info-box" style="background-color: #f7f7f7;">
	      	<a href="<?php echo e(url('/informes/pedidocli/barra')); ?>">
	        	<span class="info-box-icon colorTitulo" >
	        		<i class="fa fa-bar-chart"></i>
	        	</span>
	    	</a>
	        <div class="info-box-content">
	          <span class="info-box-text">PEDIDOS</span>
	          <span class="info-box-number">
	          	Monto por proveedor, ultimos 30 dias
	          	<br>
	          		<small>ICOMPRAS360</small>
	          </span>
	        </div>
	      </div>
	    </div>

	    <div class="col-md-6 col-sm-12 col-xs-12">
	      <div class="info-box" style="background-color: #f7f7f7;">
	      	<a href="<?php echo e(url('/informes/uniprov/barra')); ?>">
	        	<span class="info-box-icon colorTitulo" >
	        		<i class="fa fa-bar-chart"></i>
	        	</span>
	    	</a>
	        <div class="info-box-content">
	          <span class="info-box-text">UNIDADES</span>
	          <span class="info-box-number">
	          	Mejor Inventario productos
	          	<br>
	          	<small>PROVEEDORES</small>
	          </span>
	        </div>
	      </div>
	    </div>

	    <div class="col-md-6 col-sm-12 col-xs-12">
	      <div class="info-box" style="background-color: #f7f7f7;">
	      	<a href="<?php echo e(url('/informes/renprov/barra')); ?>">
	        	<span class="info-box-icon colorTitulo" >
	        		<i class="fa fa-bar-chart"></i>
	        	</span>
	    	</a>
	        <div class="info-box-content">
	          <span class="info-box-text">RENGLONES</span>
	          <span class="info-box-number">
	          	Mayor Variedad de productos 
	          	<br>
	          	<small>PROVEEDORES</small>
	          </span>
	        </div>
	      </div>
	    </div>

  	    <div class="col-md-6 col-sm-12 col-xs-12">
	      <div class="info-box" style="background-color: #f7f7f7;">
	      	<a href="<?php echo e(url('/informes/rnk1prov/barra')); ?>">
	        	<span class="info-box-icon colorTitulo" >
	        		<i class="fa fa-bar-chart"></i>
	        	</span>
	    	</a>
	        <div class="info-box-content">
	          <span class="info-box-text">RANK-1</span>
	          <span class="info-box-number">
	          	Cantidad de productos con mejor precio
	          	<br>
	          		<small>PROVEEDORES</small>
	          </span>
	        </div>
	      </div>
	    </div>

	    <div class="col-md-6 col-sm-12 col-xs-12">
	      <div class="info-box" style="background-color: #f7f7f7;">
	      	<a href="<?php echo e(url('/informes/mejoropcion/table')); ?>">
	        	<span class="info-box-icon colorTitulo" >
	        		<i class="fa fa-table"></i>
	        	</span>
	    	</a>
	        <div class="info-box-content">
	          <span class="info-box-text">MEJOR OPCION</span>
	          <span class="info-box-number">
	          	Mejor opcion entre los proveedores
	          	<br>
	          	<small>PROVEEDORES</small>
	          </span>
	        </div>
	      </div>
	    </div>

        <div class="col-md-6 col-sm-12 col-xs-12">
	      <div class="info-box" style="background-color: #f7f7f7;">
	      	<a href="<?php echo e(url('/informes/inventario/valor')); ?>">
	        	<span class="info-box-icon colorTitulo" >
	        		<i class="fa fa-money"></i>
	        	</span>
	    	</a>
	        <div class="info-box-content">
	          <span class="info-box-text">INVENTARIO</span>
	          <span class="info-box-number">
	          	Valor del Inventario
	          	<br>
	          	<small>CLIENTE</small>
	          </span>
	        </div>
	      </div>
	    </div>

	    <div class="col-md-6 col-sm-12 col-xs-12">
	      <div class="info-box" style="background-color: #f7f7f7;">
	      	<a href="<?php echo e(url('/informes/productos/vendidoisacom')); ?>">
	        	<span class="info-box-icon colorTitulo" >
	        		<i class="fa fa-cubes"></i>
	        	</span>
	    	</a>
	        <div class="info-box-content">
	          <span class="info-box-text">PRODUCTOS</span>
	          <span class="info-box-number">
	          	Los 100 más vendidos
	          	<br>
	          	<small>ICOMPRAS360</small>
	          </span>
	        </div>
	      </div>
	    </div>

	    <div class="col-md-6 col-sm-12 col-xs-12">
	      <div class="info-box" style="background-color: #f7f7f7;">
	      	<a href="<?php echo e(url('/informes/ahorro/verahorro')); ?>">
	        	<span class="info-box-icon colorTitulo" >
	        		<i class="fa fa-tag" aria-hidden="true"></i>
	        	</span>
	    	</a>
	        <div class="info-box-content">
	          <span class="info-box-text">AHORROS</span>
	          <span class="info-box-number">
	          	Ver ultimos 12 meses
	          	<br>
	          	<small>ICOMPRAS360</small>
	          </span>
	        </div>
	      </div>
	    </div>

	    <div class="col-md-6 col-sm-12 col-xs-12">
	      <div class="info-box" style="background-color: #f7f7f7;">
	      	<a href="<?php echo e(url('/informes/auditoria/verauditoria')); ?>">
	        	<span class="info-box-icon colorTitulo" >
	        		<i class="fa fa-file-text-o" aria-hidden="true"></i>
	        	</span>
	    	</a>
	        <div class="info-box-content">
	          <span class="info-box-text">AUDITORIA 1</span>
	          <span class="info-box-number">
	          	Log de transacciones
	          	<br>
	          	<small>ICOMPRAS360</small>
	          </span>
	        </div>
	      </div>
	    </div>

	    <div class="col-md-6 col-sm-12 col-xs-12">
	      <div class="info-box" style="background-color: #f7f7f7;">
	      	<a href="<?php echo e(url('/informes/auditoria/desvped')); ?>">
	        	<span class="info-box-icon colorTitulo" >
	        		<i class="fa fa-file-text-o" aria-hidden="true"></i>
	        	</span>
	    	</a>
	        <div class="info-box-content">
	          <span class="info-box-text">AUDITORIA 2</span>
	          <span class="info-box-number">
	          	Reporte de desvió de pedidos
	          	<br>
	          	<small>ICOMPRAS360</small>
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
<?php echo $__env->make('layouts.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/isacom/informes/index.blade.php ENDPATH**/ ?>