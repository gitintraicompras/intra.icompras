
<?php $__env->startSection('contenido'); ?>

<div class="row">
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
		<a href="vendedor/create">
			<button class="btn-normal" data-toggle="tooltip" title="Nuevo proveedor">
			Nuevo
			</button>
		</a>
		<button type="button" class="btn-normal" onclick="history.back(-1)" data-toggle="tooltip" title="Regresar">
			Regresar
		</button>
	</div>

	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
		<?php echo $__env->make('canales.vendedor.search', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
	</div>
</div>


<div class="clearfix"></div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead class="colorTitulo">
					<th style="width: 30px;">#</th>
					<th style="width: 110px;">OPCION</th>
					<th>CODIGO</th>
					<th>NOMBRE</th>
				</thead>
				<?php $__currentLoopData = $regs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<tr>
					<td><?php echo e($loop->iteration); ?></td>
					<td>
						<a href="<?php echo e(URL::action('Canales\VendedorController@edit',$reg->codvendedor)); ?>">
							<button class="btn btn-default fa fa-pencil" 
								data-toggle="tooltip" 
								title="Editar vendedor">
							</button>
						</a>

						<a href="" 
							data-target="#modal-delete-<?php echo e($reg->codvendedor); ?>" 
							data-toggle="modal">
							<button class="btn btn-default fa fa-trash-o" 
								data-toggle="tooltip" 
								title="Eliminar vendedor">
							</button>
						</a>

					</td>
					<td><?php echo e($reg->codvendedor); ?></td>
					<td><?php echo e($reg->nombre); ?></td>
				</tr>
				<?php echo $__env->make('canales.vendedor.delete', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</table>
		</div>
	</div>
</div>




<?php $__env->startPush('scripts'); ?>
<script>
$('#subtitulo').text('<?php echo e($subtitulo); ?>');
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/canales/vendedor/index.blade.php ENDPATH**/ ?>