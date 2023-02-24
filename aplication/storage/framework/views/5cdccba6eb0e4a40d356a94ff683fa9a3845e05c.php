<?php echo e(Form::Open(array('action'=>array('PedidoController@enviar',$tabla->id),'method'=>'get'))); ?>

<div align="left" 
    class="modal fade modal-slide-in-right" 
    aria-hidden="true" 
    role="dialog" 
    tabindex="-1" 
    id="modal-enviar-<?php echo e($tabla->id); ?>">
    <div class="modal-dialog">
    	<div class="modal-content">
    		<div class="modal-header colorTitulo" >
    			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
    				<span aria-hidden="true">x</span>
    			</button>
    			<h4 class="modal-title">ENVIAR PEDIDO</h4>
    		</div>
    		<div class="modal-body">
                <input hidden="" 
                    type="text" 
                    name="tipedido" 
                    value="<?php echo e($tabla->tipedido); ?>" >
    			<p>Pedido: <b><?php echo e($tabla->id); ?> - <?php echo e($tabla->tipedido); ?></b></p>
                <p>Cliente: <b><?php echo e($tabla->codcli); ?> - <?php echo e($tabla->nomcli); ?></b></p>
                <p>Ahorro:
                    <b style="color: red;">
                        <?php echo e(number_format($tabla->ahorro, 2, '.', ',')); ?>

                    </b> 
                    &nbsp;&nbsp;&nbsp;&nbsp;Monto total: 
                    <b><?php echo e(number_format($tabla->total, 2, '.', ',')); ?></b>
                </p>
    			<p>Marque los proveedores y confirme si desea enviar el Pedido ?</p>

    			<!-- BOTONES PROVEEDORES-->
                <div style="padding: 0px; margin: 0px;">
                	<center>
                    <span class="input-group-addon">
                    	<?php 
                            $contador = 0; 
                            $arrayProvPedido = obtenerProvPedido($tabla->id);
                        ?>
                        <?php $__currentLoopData = $arrayProvPedido; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prov): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php 
                                $confprov = LeerProve($prov); 
                                if (is_null($confprov))
                                    continue;
                            ?>
                            <input name="check-<?php echo e($confprov->codprove); ?>" <?php if($tpactivo==$confprov->codprove || $tpactivo=='MAESTRO'): ?> checked  <?php endif; ?> style="margin-left: 5px; color: <?php echo e($confprov->backcolor); ?>;"  type="checkbox" class="form-check-input"  >
                            <button style="width: 120px; height: 32px; 
                                color: <?php echo e($confprov->forecolor); ?>; 
                                border: <?php echo e($confprov->backcolor); ?>;  
                                background-color: <?php echo e($confprov->backcolor); ?>;" >   <?php echo e($confprov->descripcion); ?>

                            </button>
                            <?php $contador++; ?>
                            <?php if($contador > 2): ?>
                            	<div class="clearfix"></div>
                            	<?php $contador = 0; ?>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </span>
                	</center>
                </div>
    			
    		</div>
    		<div class="modal-footer">
    			<button type="button" class="btn-normal" data-dismiss="modal">Regresar</button>
                <button type="submit" class="btn-confirmar">Confirmar</button>
    		</div>
    	</div>
    </div>
</div>
<?php echo e(Form::Close()); ?>



<?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/isacom/pedido/enviar.blade.php ENDPATH**/ ?>