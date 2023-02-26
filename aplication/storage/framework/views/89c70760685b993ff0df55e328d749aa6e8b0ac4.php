<?php $__env->startSection('contenido'); ?>
<?php
  $moneda = Session::get('moneda', 'BSS');
  $factor = RetornaFactorCambiario('', $moneda);
  $tipedido = (Auth::user()->userPedDirecto == 1 ) ? "D" : "N";
?>

<div class="row" style="margin-bottom: 5px;">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
		<a href="<?php echo e(url('/pedido/create')); ?>">
			<button class="btn-normal"
				data-toggle="tooltip"
				title="Pedido nuevo"
				style="width: 120px;">
				Pedido Nuevo
			</button>
		</a>
	</div>
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
		<?php echo $__env->make('isacom.pedido.search', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
	</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead class="colorTitulo">
					<th>#</th>
					<?php if($botonExportar): ?>
						<th style="width:180px;">OPCION</th>
					<?php else: ?>
						<th style="width:140px;">OPCION</th>
					<?php endif; ?>
					<th>ID</th>
					<th>FECHA</th>
					<th>ENVIADO</th>
					<th>ESTADO</th>
					<th>ORIGEN</th>
					<th>TIPO</th>
					<th>MARCA</th>
					<th>RENGLON</th>
					<th>UNIDADES</th>
					<th>AHORRO</th>
					<th>TOTAL</th>
					<th>FACTOR</th>
				</thead>
				<?php $__currentLoopData = $tabla; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<tr>

					<td><?php echo e($loop->iteration); ?></td>
					<td>
						<!-- CONSULTA DE PEDIDO -->
                        <a href="<?php echo e(URL::action('PedidoController@show',$t->id)); ?>">
                        	<button class="btn btn-pedido fa fa-file-o" data-toggle="tooltip" title="Consultar pedido">
                        	</button>
                        </a>

						<!-- ELIMINAR PEDIDO -->
						<a href=""
							data-target="#modal-delete-<?php echo e($t->id); ?>"
							data-toggle="modal">
							<button class="btn btn-pedido fa fa-trash-o"
								data-toggle="tooltip"
								title="Eliminar pedido">
							</button>
						</a>

                        <?php if($botonExportar): ?>
                            <?php if($t->tipedido == 'N' ): ?>
								<!-- EXPORTAR PEDIDO -->
								<a href="<?php echo e(URL::action('PedidoController@exportar',$t->id)); ?>">
		                        	<button class="btn btn-pedido fa fa-share-square-o"
                                        data-toggle="tooltip"
                                        title="Exportar pedido">
		                        	</button>
		                        </a>
	                        <?php endif; ?>
                        <?php endif; ?>

                        <?php if($t->estado == 'NUEVO' || $t->estado == 'PARCIAL'): ?>
							<!-- MODIFICAR PEDIDO -->
							<a href="<?php echo e(URL::action('PedidoController@edit',$t->id)); ?>">
								<button class="btn btn-pedido fa fa-pencil"
									data-toggle="tooltip"
									title="Modificar pedido">
								</button>
							</a>
						<?php endif; ?>

					</td>
					<td><?php echo e($t->id); ?></td>
					<td><?php echo e(date('d-m-Y H:i', strtotime($t->fecha))); ?></td>
					<td><?php echo e(date('d-m-Y H:i', strtotime($t->fecenviado))); ?></td>
					<?php if($t->estado == 'NUEVO'): ?>
						<td style="color: red;"><?php echo e($t->estado); ?></td>
					<?php else: ?>
						<td><?php echo e($t->estado); ?></td>
					<?php endif; ?>
					<td><?php echo e($t->origen); ?></td>
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
						<td align="right"><?php echo e(number_format($t->ahorro/$factorPed, 2, '.', ',')); ?></td>
						<td align="right"><?php echo e(number_format($t->total/$factorPed, 2, '.', ',')); ?></td>
						<td align="right"><?php echo e(number_format($factorPed, 2, '.', ',')); ?></td>
					<?php else: ?>
						<td align="right"><?php echo e(number_format($t->ahorro/$factor, 2, '.', ',')); ?></td>
						<td align="right"><?php echo e(number_format($t->total/$factor, 2, '.', ',')); ?></td>
						<td align="right"><?php echo e(number_format($factor, 2, '.', ',')); ?></td>
					<?php endif; ?>
					<!--
					<td align="right"><?php echo e(number_format(dTotalPedido($t->id), 2, '.', ',')); ?></td>
					--!-->
				</tr>
				<?php echo $__env->make('isacom.pedido.delete', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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

<?php echo $__env->make('layouts.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/isacom/pedido/index.blade.php ENDPATH**/ ?>