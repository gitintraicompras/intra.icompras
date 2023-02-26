
<?php $__env->startSection('contenido'); ?> 
<?php
  $moneda = Session::get('moneda', 'BSS');
  $factor = RetornaFactorCambiario('', $moneda);
?>
 
<div class="row" style="margin-bottom: 5px;">
	
	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
		<?php echo $__env->make('isacom.pedidodirecto.search', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
	</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead class="colorTitulo">
					<th>#</th>
					<th style="width:140px;">OPCION</th>
					<th title="IDENTIFICADOR UNICO DEL PEDIDO">ID</th>
					<th title="IDENTIFICADOR UNICO DEL PEDIDO DEL GRUPO">IDGRP</th>
					<th title="FECHA DEL PEDIDO">FECHA</th>
					<th title="FECHA DE ENVIO DEL PEDIDO">ENVIADO</th>
					<th title="ESTATUS DEL PEDIDO">ESTADO</th>
					<th title="TIPO DE PEDIDO">TIPO</th>
					<th title="MARCA O LABORATORIO DEL PEDIDO">MARCA</th>
					<th title="CANTIDAD DE RENGLONES DEL PEDIDO">RENGLON</th>
					<th title="CANTIDAD DE UNIDADES DEL PEDIDO">UNIDADES</th>
					<th title="MONTO TOTAL DEL PEDIDO">TOTAL</th>
					<th title="FACTOR CAMBIARIO DEL PEDIDO">FACTOR</th>
				</thead>
				<?php $__currentLoopData = $tabla; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<tr>
			
					<td><?php echo e($loop->iteration); ?></td>
					<td>
						<!-- CONSULTA DE PEDIDO --> 
                        <a href="<?php echo e(URL::action('PedidodirectoController@show',$t->id)); ?>">
                        	<button class="btn btn-pedido fa fa-file-o" data-toggle="tooltip" title="Consultar pedido">
                        	</button>
                        </a>

						<!-- ELIMINAR PEDIDO -->
						<a href="" 
							data-target="#modal-delete-<?php echo e($t->id); ?>" 
							data-toggle="modal">
							<button class="btn btn-pedido fa fa-trash-o" 
								data-toggle="tooltip" 
								title="Eliminar pedido directo">
							</button>
						</a>

						<?php if($t->estado == 'ABIERTO'): ?>
						<a href="" data-target="#modal-tomar-<?php echo e($t->id); ?>" data-toggle="modal">
							<button class="btn btn-pedido fa fa-check" 
								data-toggle="tooltip" 
								title="Tomar pedido directo">
							</button>
						</a>
						<?php endif; ?>
						
                        <?php if($t->estado == 'NUEVO'): ?> 
							<!-- MODIFICAR PEDIDO -->
							<a href="<?php echo e(URL::action('PedidodirectoController@edit',$t->id)); ?>">
								<button class="btn btn-pedido fa fa-pencil" 
									data-toggle="tooltip" 
									title="Modificar pedido directo">
								</button>
							</a>
						<?php endif; ?>

					</td>
					<td><?php echo e($t->id); ?></td>
					<td><?php echo e($t->idpedgrupo); ?></td>
					<td><?php echo e(date('d-m-Y H:i', strtotime($t->fecha))); ?></td>
					<td><?php echo e(date('d-m-Y H:i', strtotime($t->fecenviado))); ?></td>
					<?php if($t->estado == 'NUEVO'): ?>
						<td style="color: red;"><?php echo e($t->estado); ?></td>
					<?php else: ?>
						<td><?php echo e($t->estado); ?></td>
					<?php endif; ?>
					<td><?php echo e($t->tipedido); ?></td>
					<td><?php echo e(($t->marca == '') ? 'N/A' : $t->marca); ?></td>
					<td align="right"><?php echo e(number_format($t->numren, 0, '.', ',')); ?></td>
					<td align="right"><?php echo e(number_format($t->numund, 0, '.', ',')); ?></td>
					<?php if($moneda == "USD"): ?>
						<?php
						$factorPed = $t->factor;
						if ($factorPed == 1)
							$factorPed = $factor;
						?>
						<td align="right"><?php echo e(number_format($t->total/$factorPed, 2, '.', ',')); ?></td>
						<td align="right"><?php echo e(number_format($factorPed, 2, '.', ',')); ?></td>
					<?php else: ?>
						<td align="right"><?php echo e(number_format($t->total/$factor, 2, '.', ',')); ?></td>
						<td align="right"><?php echo e(number_format($factor, 2, '.', ',')); ?></td>
					<?php endif; ?>
				</tr>
				<?php echo $__env->make('isacom.pedidodirecto.delete', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
				<?php echo $__env->make('isacom.pedidodirecto.tomar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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
$('#subtitulo').text('<?php echo e($subtitulo); ?>');
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config', 'G-CE7C5GBHWG');
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/isacom/pedidodirecto/index.blade.php ENDPATH**/ ?>