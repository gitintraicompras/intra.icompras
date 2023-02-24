
<?php $__env->startSection('contenido'); ?>

<div class="row"> 
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
		<button type="button" class="btn-normal" onclick="history.back(-1)" data-toggle="tooltip" title="Regresar">
			Regresar
		</button>
	</div>

	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
		<?php echo $__env->make('canales.licencia.search', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
	</div>
</div>

<div class="clearfix"></div>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead class="colorTitulo">
					<th style="width: 30px;">#</th>
					<th style="width: 120px;">OPCION</th>
					<th>CODIGO</th>
					<th>CLIENTE</th>
					<th>LICENCIA</th>
					<th>TELEFONO</th>
					<th>CONTACTO</th>
					<th>CANAL</th>
					<th>VENDEDOR</th>
					<th>FECHA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
					<th>DIAS</th>
					<th>RESTAN</th>
				</thead>
				<?php $__currentLoopData = $regs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<tr>
					<td><?php echo e($loop->iteration); ?></td>
					<td>
						<a href=""   
							data-target="#modal-delete-<?php echo e($reg->cod_lic); ?>" 
							data-toggle="modal">
							<button class="btn btn-default fa fa-trash-o" 
								data-toggle="tooltip" 
								title="Eliminar licencia y usuario">
							</button>
						</a>

						<?php if($canal->super > 0): ?>
						<button data-toggle="tooltip" 
							title="Resetear licencia" 
							class="btn btn-pedido fa fa-unlock-alt BtnReset" 
							id="idReset-<?php echo e($reg->cod_lic); ?>">
						</button>
						<?php endif; ?>


						
					</td>
					<td><?php echo e($reg->codisb); ?></td>
					<td><?php echo e($reg->nombre); ?></td>
					<td><?php echo e($reg->cod_lic); ?></td>
					<td><?php echo e($reg->telefono); ?></td>
					<td><?php echo e($reg->contacto); ?></td>
					<td><?php echo e($reg->codcanal); ?></td>
					<td><?php echo e($reg->codvendedor); ?></td>
					<td><?php echo e(date('d-m-y', strtotime($reg->fecha))); ?></td>
					<td align="right"><?php echo e($reg->diaLicencia); ?></td>
					<td align="right" <?php if($reg->restan <= 5): ?> style="color: red;" <?php endif; ?>>
						<?php echo e($reg->restan); ?>

					</td>
				</tr>
				<?php echo $__env->make('canales.licencia.delete', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</table>
		</div>
	</div>
</div>

<!-- MODAL RESETEAR CLAVE -->
<div class="modal fade" 
	id="ModalReset" 
	role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header colorTitulo" >
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">x</span>
                </button>
                <h4 class="modal-title">RESETEAR LICENCIA</h4>
            </div>

            <div class="modal-body">
                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <span style="width: 100px;" class="input-group-addon">Licencia:</span>
                    <input readonly 
                    	type="text" 
                    	class="form-control" 
                    	value="" 
                    	style="color:#000000" 
                    	id="idcodlic" 
                    	name="codlic">   
                </div>
		    </div>

            <div class="modal-footer">
                <div style="margin-top: 5px;" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <button type="button" 
                    	class="btn-normal" 
                    	data-dismiss="modal">
                    	Regresar
                    </button>
                    <button type="button" 
                    	class="btn-confirmar BtnResetear" 
                    	data-dismiss="modal">
                    	Confirmar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>


<?php $__env->startPush('scripts'); ?>
<script>
$('#subtitulo').text('<?php echo e($subtitulo); ?>');

$('.BtnReset').on('click',function(e){
	var variable = e.target.id.split('-');
    var codlic = variable[1];
    $('#idCodlic').val(codlic);
    $('#ModalReset').modal({show:true});
});

</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/canales/licencia/index.blade.php ENDPATH**/ ?>