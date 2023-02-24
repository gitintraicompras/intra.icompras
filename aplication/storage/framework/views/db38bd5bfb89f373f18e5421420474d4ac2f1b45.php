<?php echo e(Form::Open(array('action'=>array('PedGrupoController@enviar',$id),'method'=>'get'))); ?>

<div align="left" 
    class="modal fade modal-slide-in-right" 
    aria-hidden="true" 
    role="dialog" 
    tabindex="-1" 
    id="modal-enviar-<?php echo e($id); ?>">
    <div class="modal-dialog" >
    	<div class="modal-content" >
    		<div class="modal-header colorTitulo" >
    			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
    				<span aria-hidden="true">x</span>
    			</button>
    			<h4 class="modal-title">ENVIAR PEDIDO DIRECTO</h4>
    		</div>
    		<div class="modal-body">
                <input hidden="" type="text" name="codmarca" value="<?php echo e($pedgrupo->marca); ?>" >
                <input hidden="" type="text" name="codgrupo" value="<?php echo e($grupo->nomgrupo); ?>" >
    			<p>Pedido: <b><?php echo e($id); ?></b></p>
                <p>Grupo : <b><?php echo e($grupo->nomgrupo); ?></b></p>
                <p>Marca : <b><?php echo e($pedgrupo->marca); ?></b></p>
              	<div class="row">
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label>Formato</label>
                            <select name="formato" class="form-control">
                                <option value="PDF" selected >PDF</option>
                                <option value="EXCEL" >EXCEL</option>
                                <option value="TEXTO" >TEXTO</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label>Correo destino</label>
                            <input type="text" 
                                name="pedcorreo" 
                                value="<?php echo e($pedcorreo); ?>" 
                                class="form-control">
                        </div>
                    </div>
                </div>
                <div style="font-size: 16px;">
                    Confirme si desea enviar el Pedido ?
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



<?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/isacom/pedgrupo/enviar.blade.php ENDPATH**/ ?>