
<?php $__env->startSection('contenido'); ?>

<div class="row" style="margin-bottom: 5px;">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
		<?php echo $__env->make('ofertas.ofertas.search', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
	</div>
</div>


<div class="row"> 
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table id="idtabla" class="table table-striped table-bordered table-condensed table-hover">
                <thead class="colorTitulo">
                    <th style="width: 30px;">#</th>
               		<th style="width: 40px;">OPCION</th>
               		<th>PRODUCTO</th>
			        <th>CODIGO</th>
                    <th>REFERENCIA</th>
                    <th>MARCA</th>
                    <th>CANTIDAD</th>
                    <th>OFERTA</th>
                </thead>
	            <?php $__currentLoopData = $invent; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	                <tr>
	                	<td>
	                	<?php echo e($loop->iteration); ?>

	                	</td>

	                	<td>
				           	<!-- ELIMINAR OFERTA -->
							<a href="" data-target="#modal-delete-<?php echo e($inv->codprod); ?>" data-toggle="modal">
								<button class="btn btn-pedido fa fa-trash-o" 
									data-toggle="tooltip" 
									title="Eliminar oferta del producto">
								</button>
							</a>
	                    </td>

	                    <td><?php echo e($inv->desprod); ?></td>
	                    <td><?php echo e($inv->codprod); ?></td>
	                    <td><?php echo e($inv->barra); ?></td>
	                    <td><?php echo e($inv->marca); ?></td>
	                    <td align='right'><?php echo e(number_format($inv->cantidad, 0, '.', ',')); ?></td>
	                  	<td align='right'><?php echo e(number_format($inv->da, 2, '.', ',')); ?></td>	
                    </tr>
                    <?php echo $__env->make('ofertas.ofertas.delete', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
	            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> 
            </table>
        </div><br>
	</div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
$('#subtitulo').text('<?php echo e($subtitulo); ?>');
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/ofertas/ofertas/index.blade.php ENDPATH**/ ?>