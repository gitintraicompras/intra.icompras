
<?php $__env->startSection('contenido'); ?>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="btn-toolbar" role="toolbar" >
		    <div class="btn-group" role="group">
	            <a href="<?php echo e(URL::action('RegistroController@catalogo','T')); ?>">
		            <button style="margin-right: 3px;" 
		            	data-toggle="tooltip" 
		            	title="Analizar todos los productos"
		            	class="btn-confirmar"> 
		      			Analizar
		            </button>
		        </a>
		        <a href="<?php echo e(URL::action('RegistroController@catalogo','A')); ?>">
		            <button style="margin-right: 3px;" 
		            	data-toggle="tooltip" 
		            	title="Analizar solo los productos amarillos"
		            	class="btn-confirmar-a">
		      			Amarillos
		            </button>
		        </a>
		        <a href="<?php echo e(URL::action('RegistroController@catalogo','V')); ?>">
		            <button style="margin-right: 3px;" 
		            	type="button" 
		            	data-toggle="tooltip" 
		            	title="Analizar solo los productos verdes"
		            	class="btn-confirmar-v">
		      			Verde
		            </button>
		        </a>
		        <a href="<?php echo e(URL::action('RegistroController@catalogo','R')); ?>">
		            <button style="margin-right: 3px;" 
		            	type="button" 
		            	data-toggle="tooltip" 
		            	title="Analizar solo los productos rojos"
		            	class="btn-confirmar-r">
		      			Rojo
		            </button>
		        </a>
	       </div> 
	 	</div>
		<?php echo $__env->make('ofertas.registros.search', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
	</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table id="idtabla" class="table table-striped table-bordered table-condensed table-hover">
                <thead class="colorTitulo">
                    <th style="width: 30px;">#</th>
               		<th style="width: 140px;">OPCION</th>
			        <th style="width: 120px;">FECHA</th>
                    <th style="width: 120px;">PROCESADO</th>
                    <th style="width: 170px;">USUARIO</th>
                    <th>OBSERVACION</th>
                    <th>STATUS</th>
                </thead>
	            <?php $__currentLoopData = $tabla; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	                <tr>
	                	<td><?php echo e($t->id); ?></td>
	                	<td>
							<!-- CONSULTA DE PEDIDO -->
	                        <a href="<?php echo e(URL::action('RegistroController@show',$t->id)); ?>">
	                        	<button class="btn btn-pedido fa fa-file-o" data-toggle="tooltip" title="Consultar ofertas">
	                        	</button>
	                        </a>

	                       	<!-- ELIMINAR REGISTRO -->
							<a href="" data-target="#modal-delete-<?php echo e($t->id); ?>" data-toggle="modal">
								<button class="btn btn-pedido fa fa-trash-o" data-toggle="tooltip" title="Eliminar registro"></button>
							</a>

						     <a href="<?php echo e(URL::action('RegistroController@descargar',$t->id)); ?>">
	                        	<button class="btn btn-pedido fa fa-download" data-toggle="tooltip" title="Descargar ofertas en excel">
	                        	</button>
	                        </a>


	                    </td>
	                    <td><?php echo e(date('d-m-Y H:i:s', strtotime($t->fecha))); ?></td>
	                    <td><?php echo e(date('d-m-Y H:i:s', strtotime($t->fecprocesado))); ?></td>
	                    <td><?php echo e($t->usuario); ?></td>
	                    <td><?php echo e($t->observ); ?></td>
	                    <td><?php echo e($t->status); ?></td>
	                </tr>
	            <?php echo $__env->make('ofertas.registros.delete', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> 
            </table>
            <div align='right'>
            	<?php echo e($tabla->render()); ?>

            </div><br>
        </div><br>
	</div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
$('#subtitulo').text('<?php echo e($subtitulo); ?>');
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/ofertas/registros/index.blade.php ENDPATH**/ ?>