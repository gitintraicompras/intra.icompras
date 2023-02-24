
<?php $__env->startSection('contenido'); ?>
<?php
$rutalogoprov = 'http://isaweb.isbsistemas.com/public/storage/prov/';
?>
<div class="row" style="margin-bottom: 5px;">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
		<a href="" data-target="#modal-liberar" data-toggle="modal">
			<button style="width: 150px;" class="btn-normal" data-toggle="tooltip" title="Liberar todos los productos">Libarar todos</button>
		</a>
		<?php echo $__env->make('isacom.transito.liberar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
	</div>

	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
		<?php echo $__env->make('isacom.transito.search', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
	</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead class="colorTitulo">
					<th style="vertical-align:middle;">#</th>
            	 	<th style="width: 100px; vertical-align:middle;">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;IMAGEN&nbsp;&nbsp;&nbsp;&nbsp;&nbsp1;&nbsp;&nbsp;
                    </th>
					<th style="width:60px;">OPCION</th>
					<th>PEDIDO</th>
					<th>PRODUCTO</th>
					<th title="Referencias del producto">
						REFERENCIAS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					</th>
					<th>DIAS</th>
					<th title="CANTIDAD">CANT</th>
					<th>ENVIADO</th>
				</thead>
				<?php $__currentLoopData = $tabla; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

				<?php 
					$descripcion = $t->codprove;
					$backcolor = '#697A5A';
					$forecolor = '#FFFFFF';
					$confprov = LeerProve($t->codprove); 
					if ($confprov) {
						$backcolor = $confprov->backcolor;
						$forecolor = $confprov->forecolor;
						$descripcion = $confprov->descripcion;
					}
				?>
            	<tr>
					<td><?php echo e($loop->iteration); ?></td>
	            
	                <td>
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

						<!-- ELIMINAR PEDIDO -->
						<center>
						<a href="" data-target="#modal-delete-<?php echo e($t->item); ?>" data-toggle="modal">
							<button class="btn btn-pedido fa fa-trash-o" data-toggle="tooltip" title="Liberar producto"></button>
						</a>
						</center>
					</td>

					<td><?php echo e($t->id); ?></td>
					
					<td>
						<b><?php echo e($t->desprod); ?></b>
					</td>

					<td>
						<span title="NOMBRE PROVEEDOR">
							<img style="width: 20px; height: 20px;" 
                            src="<?php echo e($rutalogoprov.$confprov->rutalogo1); ?>" 
                            alt="icompras360">
                            <?php echo e($confprov->descripcion); ?><br>
                        </span>
						<span title="CODIGO DE BARRA">
                            <i class="fa fa-barcode">
                                <?php echo e($t->barra); ?>

                            </i><br>
                        </span>
                        <span title="CODIGO DEL PRODUCTO">
                            <i class="fa fa-cube">
                                <?php echo e($t->codprod); ?>    
                            </i><br>
                        </span>
                        <span title="MARCA DEL PRODUCTO">
                            <i class="fa fa-shield">
                                <?php echo e(LeerProdcaract($t->barra, 'marca', 'POR DEFINIR')); ?>    
                            </i>
                        </span> 
                    </td>

                    <td align="right">
						<?php echo e(DiferenciaDias($t->fecenviado)); ?> 
					</td>

					<td align="right">
						<?php echo e(number_format($t->cantidad, 0, '.', ',')); ?>

					</td>
					<td><?php echo e(date('d-m-Y H:i', strtotime($t->fecenviado))); ?></td>
				</tr>
				<?php echo $__env->make('isacom.transito.delete', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</table>
			<div align='right'>
            	<?php echo e($tabla->render()); ?>

            </div><br>
		</div>
	</div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
$('#subtitulo').text('<?php echo e($subtitulo); ?>');
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/isacom/transito/index.blade.php ENDPATH**/ ?>