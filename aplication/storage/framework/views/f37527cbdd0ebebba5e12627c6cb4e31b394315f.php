<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modal-delete-<?php echo e($t->item); ?>">
<?php echo e(Form::Open(array('action'=>array('TransitoController@destroy',$t->item),'method'=>'delete'))); ?>

<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header colorTitulo" >
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">x</span>
			</button>
			<h4 class="modal-title">LIBERAR PRODUCTO</h4>
		</div>
		<div class="modal-body">
			<p>ID: <?php echo e($t->id); ?></p>
			<p>Item: <?php echo e($t->item); ?></p>
			<p>Codprod: <?php echo e($t->codprod); ?></p>
			<p>Barra: <?php echo e($t->barra); ?></p>
			<p>Descripción: <?php echo e($t->desprod); ?></p>
			<p>Confirme si desea liberar el producto ?</p>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn-normal" data-dismiss="modal">Regresar</button>
			<button type="submit" class="btn-confirmar">Confirmar</button>
		</div>
	</div>
</div>
<?php echo e(Form::Close()); ?>

</div><?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/isacom/transito/delete.blade.php ENDPATH**/ ?>