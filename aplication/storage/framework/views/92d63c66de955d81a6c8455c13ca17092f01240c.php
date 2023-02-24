<?php echo e(Form::Open(array('action'=>array('Canales\LicenciaController@deleteall',$reg->cod_lic),'method'=>'POST'))); ?>

<div class="modal fade modal-slide-in-right" 
	aria-hidden="true" 
	role="dialog" 
	tabindex="-1" 
	id="modal-deleteall-<?php echo e($reg->cod_lic); ?>">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header colorTitulo" >
				<button type="button" 
					class="close" 
					data-dismiss="modal" 
					aria-label="Close">
					<span aria-hidden="true">x</span>
				</button>
				<h4 class="modal-title">ELIMINAR CLIENTE, PROVEESORES, USUARIO Y LICENCIA</h4>
			</div>
			<div class="modal-body">
				<p>Nombre: <?php echo e($reg->cod_lic); ?></p>
				<p>Confirme si desea eliminar todo registro del cliente ?</p>
			</div>
			<div class="modal-footer">
				<!-- BOTON REGRESAR/CONFIRMAR -->
				<button type="button" class="btn-normal" data-dismiss="modal">Regresar</button>
				<button type="submit" class="btn-confirmar">Confirmar</button>
			</div>
		</div>
	</div>
</div>
<?php echo e(Form::Close()); ?>

<?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/canales/licencia/deleteall.blade.php ENDPATH**/ ?>