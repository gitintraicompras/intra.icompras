<div class="modal fade modal-slide-in-right" 
	aria-hidden="true" 
	role="dialog" 
	tabindex="-1" 
	id="modal-delete-<?php echo e($t->id); ?>">
<?php echo e(Form::Open(array('action'=>array('RegistroController@destroy',$t->id),'method'=>'delete'))); ?>

<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header colorTitulo" >
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">x</span>
			</button>
			<h4 class="modal-title">ELIMINAR REGISTRO</h4>
		</div>
		<div class="modal-body">
			<p>ID #: <?php echo e($t->id); ?></p>
			<p>Confirme si desea eliminar el registro de ofertas ?</p>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn-normal" data-dismiss="modal">Regresar</button>
			<button type="submit" class="btn-confirmar">Confirmar</button>
		</div>
	</div>
</div>
<?php echo e(Form::Close()); ?>

</div>

<?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/ofertas/registros/delete.blade.php ENDPATH**/ ?>