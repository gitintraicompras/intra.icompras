
<?php $__env->startSection('contenido'); ?> 
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead class="colorTitulo">
					<th style="vertical-align:middle;">#</th>
                    <th style="width: 90px; vertical-align:middle;">
                    &nbsp;&nbsp;&nbsp;IMAGEN&nbsp;&nbsp;&nbsp;
                    </th>
					<th>DESCRIPCION</th>
					<th title="Código del producto">CODIGO</th>
					<th title="Venta media diaria">BARRA</th>
					<th title="Venta media diaria">VMD</th>
					<th title="Sugerido=(VMD*15-(INVENTARIO+TRANSITO)) para 10 dias">
                        15
                    </th>
                    <th title="Sugerido=(VMD*30-(INVENTARIO+TRANSITO)) para 30 dias">
                        30
                    </th>
                    <th title="Sugerido=(VMD*60-(INVENTARIO+TRANSITO)) para 60 dias">
                        60
                    </th>
					<th title="Inventario del cliente">INVENT.</th>
					<th title="Inventario consolidado de los proveedores">CONSOL.</th>
					<th title="Total de unidades vendidas">VENDIDAS</th>
				</thead>
				<?php $__currentLoopData = $tabla; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<?php
					$marcado = 0;
					$invent = 0;
					$invConsol = 0;
					$vmd = 0;
					$codprod = "";
					$sug15 = 0;
					$sug30 = 0;
					$sug60 = 0;
					$transito = verificarProdTransito($t->barra,  "", "");
				   	$inv = verificarProdInventario($t->barra, "");
                    if ($inv != "") {
                        $invent = $inv->cantidad;
                        $vmd = $inv->vmd;
                        $codprod = $inv->codprod;
                        $desprod = $inv->desprod;
                        $sug15 = ($vmd*15)-($invent + $transito);
						$sug30 = ($vmd*30)-($invent + $transito);
						$sug60 = ($vmd*60)-($invent + $transito);
						if ($sug15 < 0)
                            $sug15 = 0;
                        if ($sug30 < 0)
                            $sug30 = 0;
                        if ($sug60 < 0)
                            $sug60 = 0;
                    }
                    $cat = DB::table('tpmaestra')
		            ->where('barra','=',$t->barra)
		            ->first();
		       		if ($cat) {
		       		 	$desprod = $cat->desprod;
                        $dataprod = obtenerDataTpmaestra($cat, $provs, 0);
	                    if (!is_null($dataprod)) {
		                    $invConsol = $dataprod['invConsol'];
		                }
		            }
		            if ($invent <= 0 && $invConsol > 0) {
		            	$marcado = 1;
		        	}
				?>
				<tr>
					<td style="font-size:20px;">
						<span
					    <?php if($marcado == 1): ?> 
					    class="label label-danger"
						style="background-color: red; 
						color: #ffffff; 
						border-radius: 50%;" 
                        title = "PRODUCTO DEBE SER PEDIDO DE INMEDIATO"  
                        <?php endif; ?>>
						<?php echo e($loop->iteration); ?>

						</span>
					</td>

			
					<td>
                        <div align="center">

                            <a href="<?php echo e(URL::action('PedidoController@verprod',$t->barra)); ?>">
                    
                                <img src="http://isaweb.isbsistemas.com/public/storage/prod/<?php echo e(NombreImagen($t->barra)); ?>" 
                                width="100%" 
                                height="100%" 
                                class="img-responsive" 
                                alt="icompras360" 
                                oncontextmenu="return false">
                    
                            </a>

                        </div>
                    </td>
					<td><?php echo e($desprod); ?></td>
					<td><?php echo e($codprod); ?></td>
					<td><?php echo e($t->barra); ?></td>
					<td align="right"><?php echo e(number_format($vmd, 4, '.', ',')); ?></td>
					<td align="right"><?php echo e(number_format($sug15, 0, '.', ',')); ?></td>
					<td align="right"><?php echo e(number_format($sug30, 0, '.', ',')); ?></td>
					<td align="right"><?php echo e(number_format($sug60, 0, '.', ',')); ?></td>
					<td align="right"><?php echo e(number_format($invent, 0, '.', ',')); ?></td>
					<td align="right"><?php echo e(number_format($invConsol, 0, '.', ',')); ?></td>
					<td align="right"><?php echo e(number_format($t->total, 0, '.', ',')); ?></td>
				</tr>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</table>
		</div>
	</div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
$('#subtitulo').text('<?php echo e($subtitulo); ?>');
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/isacom/informes/vendidoisacom.blade.php ENDPATH**/ ?>