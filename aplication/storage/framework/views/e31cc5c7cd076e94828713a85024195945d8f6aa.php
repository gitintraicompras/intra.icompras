<?php echo e(Form::Open(array('action'=>array('PedGrupoController@deleprod',$id),'method'=>'get'))); ?>

<?php
$confcli = LeerCliente($codsuc);
$nomcli = $confcli->nombre;
?>
<input hidden type="text" name="barra" value="<?php echo e($p->barra); ?>">
<input hidden type="text" name="codsuc" value="<?php echo e($gr->codcli); ?>">
<div class="modal fade modal-slide-in-right" 
	aria-hidden="true" 
	role="dialog" 
	tabindex="-1" 
	id="modal_delete_<?php echo e($gr->codcli); ?>_<?php echo e($p->barra); ?>">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header colorTitulo" >
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">x</span>
				</button>
				<h4 class="modal-title">ELIMINAR PRODUCTO</h4>
			</div>
			<div class="modal-body">
				<p>Sucursal: <?php echo e($gr->codcli); ?> - <?php echo e($nomcli); ?></p>
				<p>Barra: <?php echo e($p->barra); ?> - <?php echo e($p->desprod); ?></p>
				<p>Confirme si desea eliminar el producto ?</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn-normal" data-dismiss="modal">Regresar</button>
				<button type="submit" class="btn-confirmar">Confirmar</button>
			</div>
		</div>
	</div>
</div>
<?php echo e(Form::Close()); ?>

<?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/isacom/pedgrupo/deleprod.blade.php ENDPATH**/ ?>