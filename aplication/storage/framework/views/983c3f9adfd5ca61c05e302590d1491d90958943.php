<?php echo e(Form::Open(array('action'=>array('PedidodirectoController@destroy',$t->id),'method'=>'delete'))); ?>

<div class="modal fade modal-slide-in-right" 
	aria-hidden="true" 
	role="dialog" 
	tabindex="-1" 
	id="modal-tomar-<?php echo e($t->id); ?>">
	<input hidden="" type="text" name="accion" value="TOMAR" >
	<input hidden="" type="text" name="codcli" value="<?php echo e($codcli); ?>" >
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header colorTitulo" >
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">x</span>
				</button>
				<h4 class="modal-title">TOMAR PEDIDO DIRECTO</h4>
			</div>
			<div class="modal-body">
				<p>Pedido: <?php echo e($t->id); ?></p>
				<p>ID grupo: <?php echo e($t->idpedgrupo); ?></p>
				<p>Marca: <?php echo e($t->marca); ?></p>
			</div>
			<br>
			<div class="modal-footer" >
				<div class="col-xs-12">
					<button type="button" class="btn-normal" data-dismiss="modal">Regresar</button>
					<button type="submit" class="btn-confirmar">Confirmar</button>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo e(Form::Close()); ?>




<?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/isacom/pedidodirecto/tomar.blade.php ENDPATH**/ ?>