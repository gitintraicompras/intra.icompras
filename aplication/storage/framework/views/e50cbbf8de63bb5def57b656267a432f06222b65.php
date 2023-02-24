
<?php $__env->startSection('contenido'); ?> 
<?php
  $moneda = Session::get('moneda', 'BSS');
  $factor = RetornaFactorCambiario('', $moneda);
  $tipedido = (Auth::user()->userPedDirecto == 1 ) ? "D" : "N";
?>

<div class="row" style="margin-bottom: 5px;">

	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
		<?php echo $__env->make('isacom.pedgrupo.create', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
	</div>

	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
		<?php echo $__env->make('isacom.pedgrupo.search', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
	</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead class="colorTitulo">
					<th>#</th>
					<th style="width:180px;">OPCION</th>
					<th>ID</th>
					<th>FECHA</th>
					<th>ENVIADO</th>
					<th>ESTADO</th>
					<th>MARCA</th>
					<th>REPOSICION</th>
					<th>GRUPO</th>
				</thead>
				<?php $__currentLoopData = $tabla; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<tr>
			
					<td><?php echo e($loop->iteration); ?></td>
					<td>
						<!-- CONSULTA DE PEDIDO --> 
                        <a href="<?php echo e(URL::action('PedGrupoController@show',$t->pgid)); ?>">
                        	<button class="btn btn-pedido fa fa-file-o" 
                        		data-toggle="tooltip" 
                        		title="Consultar pedido">
                        	</button>
                        </a>

                  		<!-- ELIMINAR PEDIDO -->
						<a href="" 
							data-target="#modal-delete-<?php echo e($t->pgid); ?>" 
							data-toggle="modal">
							<button class="btn btn-pedido fa fa-trash-o" 
								data-toggle="tooltip" 
								title="Eliminar pedido del grupo">
							</button>
						</a>

			            <?php if($t->estado == 'NUEVO' || $t->estado == 'GUARDADO'): ?> 
							<!-- MODIFICAR PEDIDO -->
							<a href="<?php echo e(URL::action('PedGrupoController@edit',$t->pgid)); ?>">
								<button class="btn btn-pedido fa fa-pencil" 
									data-toggle="tooltip" 
									title="Modificar pedido directo">
								</button>
							</a>
						<?php endif; ?>

					</td>
					<td><?php echo e($t->pgid); ?></td>
					<td><?php echo e(date('d-m-Y H:i:s', strtotime($t->fecha))); ?></td>
					<td><?php echo e(date('d-m-Y H:i:s', strtotime($t->enviado))); ?></td>
					<?php if($t->estado == 'NUEVO'): ?>
						<td style="color: red;"><?php echo e($t->estado); ?></td>
					<?php else: ?>
						<td><?php echo e($t->estado); ?></td>
					<?php endif; ?>
					<td><?php echo e($t->marca); ?></td>
					<td align="right"><?php echo e(number_format($t->reposicion, 0, '.', ',')); ?></td>
					<td><?php echo e($t->codgrupo); ?>-<?php echo e($t->nomgrupo); ?></td>
				</tr>
				<?php echo $__env->make('isacom.pedgrupo.delete', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</table>
			<div align='right'>
            	<?php echo e($tabla->render()); ?>

            </div><br>
		</div>
	</div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
$('#titulo').text('<?php echo e($subtitulo); ?>');
$('#subtitulo').text('<?php echo e($subtitulo2); ?>');
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/isacom/pedgrupo/index.blade.php ENDPATH**/ ?>