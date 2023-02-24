<?php echo e(Form::Open(array('action'=>array('PedidodirectoController@deleteAll',$id),'method'=>'POST'))); ?>

<?php echo e(Form::token()); ?>

<div class="modal fade modal-slide-in-right" 
	aria-hidden="true" 
	role="dialog" 
	tabindex="-1" 
	id="modal-deleteAll">
	<input hidden name="id" value="<?php echo e($id); ?>" type="text">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header colorTitulo" >
				<button type="button" 
					class="close" 
					data-dismiss="modal" 
					aria-label="Close">
					<span aria-hidden="true">x</span>
				</button>
				<h4 class="modal-title">ELIMINAR ITEM(s)</h4>
			</div>
			<div class="modal-body">
				<p>Pedido #: <?php echo e($id); ?></p>
				<p>Confirme si desea eliminar los item(s) seleccionados ?</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn-normal" data-dismiss="modal">Regresar</button>
				<button type="submit" class="btn-confirmar">Confirmar</button>
			</div>
		</div>
	</div>
</div>
<?php echo e(Form::Close()); ?>


<?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/isacom/pedidodirecto/deleteAll.blade.php ENDPATH**/ ?>