
<?php $__env->startSection('contenido'); ?>
   
<div class="row">
	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
		<?php echo $__env->make('isacom.prodcaract.search', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
	</div>
</div> 
 
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive" >
			<table id="datos" 
				class="table table-striped table-bordered table-condensed table-hover">
				<thead class="colorTitulo">
					<th>#</th>
					<th style="width: 100px;">
						&nbsp;&nbsp;&nbsp;&nbsp;IMAGEN&nbsp;&nbsp;&nbsp;&nbsp;
					</th>
					<th>PRODUCTO</th>
					<th style="width:110px;">BARRA</th>
					<th style="width:120px;" title="Marca de Producto">MARCA</th>
					<th style="width:200px;" title="Categoria de Producto">CATEGORIA</th>
					<th style="width:300px;" title="Molecula del Producto">MOLECULA</th>
				</thead>
				<?php $__currentLoopData = $tabla; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<?php
				$unidadmolecula = LeerProdcaract($t->barra, 'unidadmolecula', '1')
				?>
				<tr>
					<td><?php echo e($loop->iteration); ?></td>
					<td style="width: 100px;">
                        <div align="center" >
                        	<a href="<?php echo e(URL::action('PedidoController@verprod',$t->barra)); ?>">
	                            
	                            <img src="http://isaweb.isbsistemas.com/public/storage/prod/<?php echo e(NombreImagen($t->barra)); ?>" 
	                            class="img-responsive" 
	                            alt="icompras360" 
	                            width="100%" 
	                            height="100%"
	                            style="border: 2px solid #D2D6DE;"
	                            oncontextmenu="return false" >
                            
                            </a>
                 		</div>
                    </td>
					<td>
						<span title="<?php echo e($t->nomprov); ?>-<?php echo e($t->metadata); ?>">
							<b><?php echo e($t->desprod); ?><b>
						</span>

						<div class="col-xs-12 input-group" >

					    	<input type="text" 
					    		placeholder="principio activo" 
					    	    id="idpactivo_<?php echo e($t->barra); ?>"
                                value="<?php echo e($t->pactivo); ?>"
                                class="form-control" >

						    <span class="input-group-btn" 
						    	onclick='tdclick(event);'>
						        <button id="idpactivo1_<?php echo e($t->barra); ?>_pactivo" 
						        	type="button" 
						        	class="btn btn-pedido" 
						        	data-toggle="tooltip"  
						      		title="Grabar principio activo" >
						            <span id="idpactivo2_<?php echo e($t->barra); ?>_pactivo" 
						            	class="fa fa-check" 
						            	aria-hidden="true">
						            </span>
						        </button>
						    </span>
						</div>
					</td>
					<td><?php echo e($t->barra); ?></td>
	   				<td>
						<div class="col-xs-12 input-group" >
						    <select id="idmarca_<?php echo e($t->barra); ?>" 
						    	style="width: 120px;" 
						    	class="form-control">
						    	<?php 
						    		$existe=0;
						    		$codmarca = trim(($t->marca)); 
						    		foreach ($marca as $mar) {
						    			if ( $codmarca == trim($mar->descrip)) {
						    				$existe=1;
						    			}
						    		}
						    		if ($existe==0)
						    			$codmarca = 'POR DEFINIR';
						    	?>
					    		<?php $__currentLoopData = $marca; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					    			<?php if($codmarca == trim($mar->descrip)): ?>
					    				<option selected value="<?php echo e($mar->descrip); ?>">
											<?php echo e($mar->descrip); ?>

										</option>
						    		<?php else: ?>
					    				<option value="<?php echo e($mar->descrip); ?>">
											<?php echo e($mar->descrip); ?>

										</option>
					    			<?php endif; ?>
					    		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					    		
					    	</select>
						    <span class="input-group-btn" onclick='tdclick(event);'>
						        <button id="idmarca1_<?php echo e($t->barra); ?>_marca" 
						        	type="button" 
						        	class="btn btn-pedido" 
						        	data-toggle="tooltip"
						        	title="Grabar marca" >
						            <span id="idmarca2_<?php echo e($t->barra); ?>_marca" class="fa fa-check" aria-hidden="true">
						            </span>
						        </button>
						    </span>

						</div>
					</td>
					<td>
						<div class="col-xs-12 input-group" >
						    <select id="idcategoria_<?php echo e($t->barra); ?>" 
						    	style="width: 200px;" 
						    	class="form-control">
					
					    		<?php $__currentLoopData = $categoria; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					    			<?php if($t->categoria == $cat->descrip): ?>
										<option selected value="<?php echo e($cat->descrip); ?>">
											<?php echo e($cat->descrip); ?>

										</option>
						    		<?php else: ?>
					    				<option value="<?php echo e($cat->descrip); ?>">
											<?php echo e($cat->descrip); ?>

										</option>
					    			<?php endif; ?>
					    		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

					    	</select>
						    <span class="input-group-btn" onclick='tdclick(event);'>
						        <button id="idcategoria1_<?php echo e($t->barra); ?>_categoria" 
						        	type="button" 
						        	class="btn btn-pedido" 
						        	data-toggle="tooltip"  
						        	title="Grabar categoria" >
						            <span id="idcategoria2_<?php echo e($t->barra); ?>_categoria" 
						            	class="fa fa-check" 
						            	aria-hidden="true">
						            </span>
						        </button>
						    </span>

						</div>
					</td>
					<td style="width: 300px;">
						<div class="col-xs-12 input-group" >
						    <select id="idmolecula_<?php echo e($t->barra); ?>" 
						    	class="form-control">
					
					    		<?php $__currentLoopData = $molecula; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mol): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					    			<?php if($t->molecula == $mol->descrip): ?>
										<option selected value="<?php echo e($mol->descrip); ?>">
											<?php echo e($mol->descrip); ?>

										</option>
						    		<?php else: ?>
					    				<option value="<?php echo e($mol->descrip); ?>">
											<?php echo e($mol->descrip); ?>

										</option>
					    			<?php endif; ?>
					    		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

					    	</select>

						    <span class="input-group-btn" onclick='tdclick(event);'>
						        <button id="idmolecula1_<?php echo e($t->barra); ?>_molecula" 
						        	type="button" 
						        	class="btn btn-pedido" 
						        	data-toggle="tooltip"  
						        	title="Grabar molecula" >
						            <span id="idmolecula2_<?php echo e($t->barra); ?>_molecula" 
						            	class="fa fa-check" 
						            	aria-hidden="true">
						            </span>
						        </button>
						    </span>
						</div>
						<div class="col-xs-12 input-group" >

					    	<input type="text" 
					    		style="text-align: right;" 
                                id="idunidadmolecula_<?php echo e($t->barra); ?>"
                                value="<?php echo e($unidadmolecula); ?>"
                                class="form-control" >

						    <span class="input-group-btn" 
						    	onclick='tdclick(event);'>
						        <button id="idmolecula1_<?php echo e($t->barra); ?>_unidadmolecula" 
						        	type="button" 
						        	class="btn btn-pedido" 
						        	data-toggle="tooltip"  
						      		title="Grabar unidad" >
						            <span id="idmolecula2_<?php echo e($t->barra); ?>_unidadmolecula" 
						            	class="fa fa-check" 
						            	aria-hidden="true">
						            </span>
						        </button>
						    </span>
						</div>
					</td>
				</tr>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</table>
			<div align='right'>
				<?php echo e($tabla->appends(["filtro" => $filtro])->links()); ?>

			</div><br>
		</div>
	</div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
$('#subtitulo').text('<?php echo e($subtitulo); ?>');
function tdclick(e) {
    var id = e.target.id.split('_');
    var barra = id[1];
    var campo = id[2];
    var	valor = $('#id' + campo + '_'+barra).val();
    //alert('Barra: ' + barra + ' Campo: ' + campo + ' Valor: ' + valor);
    $.ajax({
	  type:'POST',
	  url:'./prodcaract/caract/modcaract',
	  dataType: 'json', 
	  encode  : true,
	  data: {barra:barra, campo:campo, valor:valor },
	  success:function(data) {
	    if (data.msg != "") {
	        alert(data.msg);
	    } 
	  }
  	});
}
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/isacom/prodcaract/index.blade.php ENDPATH**/ ?>