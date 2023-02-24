<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modal-delete-<?php echo e($codcli); ?>">
<?php echo e(Form::Open(array('action'=>array('InvClienteController@destroy',$codcli),'method'=>'delete'))); ?>

<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header colorTitulo" >
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">x</span>
			</button>
			<h4 class="modal-title">ELIMINAR INVENTARIO</h4>
		</div>
		<div class="modal-body">
			<input hidden id="id" type="text" name="id" value="<?php echo e($codcli); ?>">
			<p>Código: <?php echo e($codcli); ?></p>
			<p>Confirme si desea eliminar el inventario ?</p>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn-normal" data-dismiss="modal">Regresar</button>
			<button type="submit" class="btn-confirmar">Confirmar</button>
		</div>
	</div>
</div>
<?php echo e(Form::Close()); ?>

</div>
<?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/isacom/invcliente/delete.blade.php ENDPATH**/ ?>