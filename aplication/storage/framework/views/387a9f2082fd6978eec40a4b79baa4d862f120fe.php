
<?php $__env->startSection('contenido'); ?>

<div class="col-xs-12">
	<div class="row">

	<?php if(isset($sugerido)): ?>
	<!-- BARRA DE BOTONES -->
	<div class="btn-toolbar" role="toolbar" style="margin-top: 12px;margin-bottom: 3px;">
	    <div class="btn-group" role="group" style="width: 100%;">

			<?php if($sugerido->count() > 0): ?>
				<?php echo $__env->make('isacom.invsugerido.search', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

				<!-- LIMPIAR SUGERIDO -->
				<a href="" data-target="#modal-delete-<?php echo e($codcli); ?>" data-toggle="modal">
		            <button class="btn-normal" data-toggle="tooltip" title="Eliminar sugerido">Eliminar
		            </button>
				</a>
				<?php echo $__env->make('isacom.invsugerido.delete', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

				<!-- DESCARGAR SUGERIDO-->
		        <a href="<?php echo e(url('inventario/sugerido/descargar')); ?>">
		            <button style="margin-right: 3px;" type="button" data-toggle="tooltip" title="Descargar sugerido" class="btn-normal">
		                Descargar
		            </button>
		        </a>
	        <?php endif; ?>

	        <!-- CREAR SUGERIDO-->
	        <a href="<?php echo e(url('inventario/sugerido/crear')); ?>">
	            <button style="margin-right: 3px;" type="button" data-toggle="tooltip" title="Crear sugerido" class="btn-normal">
	                Crear
	            </button>
	        </a>

	        <!-- PROCESAR SUGERIDO-->
	        <?php if($sugerido->count() > 0): ?>
	        <a href="<?php echo e(url('inventario/sugerido/procesar')); ?>">
	            <button style="margin-right: 3px;" type="button" data-toggle="tooltip" title="Procesar sugerido y convertirlo en pedido" class="btn-confirmar">
	                Procesar
	            </button>
	        </a>
			<?php endif; ?>

		</div>
	</div>

	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-condensed table-hover">
					<thead class="colorTitulo">
			
						<th>#</th>
						<th style="width: 100px;" class="hidden-xs">
							&nbsp;&nbsp;IMAGEN&nbsp;&nbsp;
						</th>
						<th style="width: 170px;">
							SUGERIDO
						</th>
						<th>PRODUCTO</th>
						<th>CODIGO</th>
						<th>BARRA</th>
						<th>MARCA</th>
						<th>CATEGORIA</th>
					
					</thead>
					<?php $__currentLoopData = $sugerido; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<?php
					$cantidad = 0;
					$vmd = 0.0;
					$transito = verificarProdTransito($t->barra, $codcli, "");
					$minmax = LeerMinMax($codcli, $t->codprod);
   					$min = $minmax["min"];
   					$max = $minmax["max"];
   					$cendis = $minmax["cendis"];
					$inv = LeerInventarioCodigo($t->codprod, $codcli);
					if (!is_null($inv)) {
						$cantidad = $inv->cantidad;
						$vmd = $inv->vmd;
					}
					?>
					<tr>
					
						<td><?php echo e($loop->iteration); ?></td>
						<td class="hidden-xs">
	                        <div align="center">

	                            <a href="<?php echo e(URL::action('PedidoController@verprod',$t->barra)); ?>">
	                    
	                                <img src="http://isaweb.isbsistemas.com/public/storage/prod/<?php echo e(NombreImagen($t->barra)); ?>" 
                                    width="100%" 
                                    height="100%" 
                                    class="img-responsive" 
                                    alt="icompras360"
                                    style="border: 2px solid #D2D6DE;"
                                    oncontextmenu="return false" >
	                    
	                            </a>

	                        </div>
	                    </td>
				
						<td>
	                        <span class="input-group-addon" 
	                        	style="margin: 0px; width: 160px;">
	                            <div class="col-xs-12 input-group input-group-sm" 
	                            	style="margin: 0px; width: 160px;">
	                                <input type="number" 
	                                	style="text-align: center; color: #000000; width: 80px;" 
	                                	id="idPedir-<?php echo e($t->id); ?>" 
	                                	value="<?php echo e($t->pedir); ?>" 
	                                	class="form-control" 
	                                >
	                                <button type="button" 
	                                	class="btn btn-pedido BtnModificar" 
	                                	id="idModificar-<?php echo e($t->id); ?>" 
	                                	data-toggle="tooltip" 
	                                	title="Modificar cantidad">
	                                    <span 
	                                        class="fa fa-check" 
	                                        id="idModificar-<?php echo e($t->id); ?>" 
	                                        aria-hidden="true" >
	                                    </span>
	                                    <a href="" 
	                                    	data-target="#modal-deleteprod-<?php echo e($t->id); ?>" 
	                                    	data-toggle="modal">
	                                        <button class="btn btn-pedido fa fa-trash-o" 
	                                        	style="height: 2pc;" 
	                                        	data-toggle="tooltip" 
	                                        	title="Eliminar producto">
	                                       	</button>
	                                    </a>
	                                </button>
	                            </div>
	                        </span>
	                    </td>

	               		<td>
	               			<b><?php echo e($t->desprod); ?></b><br>
	               			<span>
	               				CANT: <?php echo e(number_format($cantidad, 0, '.', ',')); ?>&nbsp;&nbsp;&nbsp;
	               				TRAN: <?php echo e(number_format($transito, 0, '.', ',')); ?>&nbsp;&nbsp;&nbsp;
	               				VMD: <?php echo e(number_format($vmd, 4, '.', ',')); ?>

	               			</span><br>
	               			<span>
	               				MIN: <?php echo e(number_format($min, 0, '.', ',')); ?>&nbsp;&nbsp;&nbsp;
	               				MAX: <?php echo e(number_format($max, 0, '.', ',')); ?>&nbsp;&nbsp;&nbsp;
	               				<?php if($cendis >0): ?>
	               					<i class="fa fa-check-square-o" aria-hidden="true"></i>
	               					&nbsp;CENDIS
	               				<?php endif; ?>
	               			</span>
	               		</td>
						<td><?php echo e($t->codprod); ?></td>
						<td><?php echo e($t->barra); ?></td>
						<td><?php echo e(isset($inv->marca) ? $inv->marca : ""); ?></td>
						<td><?php echo e(isset($inv->categoria) ? $inv->categoria : ""); ?></td>
					</tr>
					<?php echo $__env->make('isacom.invsugerido.deleprod', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</table>

				<div align='left'>
					<?php if(isset($sugerido)): ?>
	                	<?php echo e($sugerido->appends(["filtro" => $filtro])->links()); ?>

	                <?php endif; ?>
	            </div><br>
	            
			</div>
		</div>
	</div>
	<?php endif; ?>
	</div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
$('#subtitulo').text('<?php echo e($subtitulo2); ?>');
window.onload = function() {
	$('.BtnModificar').on('click',function(e) {
        var idx = e.target.id.split('-');
        var id = idx[1];
        var pedir = $('#idPedir-'+id).val();
        if (parseInt(pedir) <= 0) {
            alert("CANTIDAD A PEDIR NO PUEDE SER MENOR O IGUAL CERO");
            $('#idPedir-'+id).val(pedir);
        } else {
            $.ajax({
                type:'POST',
                url:'./inventario/sugerido/modificaritem',
                dataType: 'json', 
                encode  : true,
                data: {id:id, pedir:pedir },
                success:function(data){
                	if (data.msg != "") {
                        alert(data.msg);
                        location.reload(true);
                    }   
                }
            });
        }
    });
}

</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/isacom/invsugerido/index.blade.php ENDPATH**/ ?>