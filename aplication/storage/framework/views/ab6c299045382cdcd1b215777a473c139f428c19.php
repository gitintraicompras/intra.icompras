
<?php $__env->startSection('contenido'); ?>
<?php
  $moneda = Session::get('moneda', 'BSS');
  $rutalogoprov = 'http://isaweb.isbsistemas.com/public/storage/prov/';
?>

<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
    <div class="form-group" style="margin-top: 10px;">
        <?php echo $__env->make('isacom.informes.desvpedsearch', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
            <table id="idTabla" 
                class="table table-striped table-bordered table-condensed table-hover">
                <thead style="background-color: #b7b7b7;">
                    <th style="vertical-align:middle;">#</th>
                    <th style="width: 100px; vertical-align:middle;">
                        &nbsp;&nbsp;&nbsp;&nbsp;IMAGEN&nbsp;&nbsp;&nbsp;&nbsp;
                    </th>
                    <th title="Descripción del producto">
                        PRODUCTO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </th>
                    <th title="Código de referencia del producto">
                        REFERENCIAS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </th>
                    <th title="Código del proveedor">
                        PROVEEDOR&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </th>
                    <th title="Cantidad solicitada">CANT</th>
                    <th title="Precio de venta al público">PRECIO</th>
                    <th title="Impuesto al valor agregado">IVA</th>
                    <th title="Descuento adicional">DA</th>
                    <th title="Descuento pre-empaque">DP</th>
                    <th title="Descuento internet">DI</th>
                    <th title="Descuento comercial">DC</th>
                    <th title="Descuento pronto pago">PP</th>
                    <th title="Neto del producto">NETO</th>
                    <th title="Subtotal del producto">SUBTOTAL</th>
                </thead>
                <?php $__currentLoopData = $tabla; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                    $confprov = LeerProve($t->codprove); 
                    if (is_null($confprov))
                        continue;
                    $factor = RetornaFactorCambiario($t->codprove, $moneda);
                    ?>
                    <tr>

                        <td style="background-color: #b7b7b7; 
                            color: #000000;" 
                            title = "PRODUCTO ENVIADO">
                            <a href="" 
                                style="color: #000000;" 
                                data-target="#modal-consulta-<?php echo e($t->item); ?>" data-toggle="modal">
                                <?php echo e($loop->iteration); ?>

                            </a>
                        </td>
     
                        <td style="width: 100px;">
                            <div align="center">

                                <a href="<?php echo e(URL::action('PedidoController@verprod',$t->barra)); ?>">
                        
                                    <img src="http://isaweb.isbsistemas.com/public/storage/prod/<?php echo e(NombreImagen($t->barra)); ?>" 
                                    width="100%" 
                                    height="100%" 
                                    class="img-responsive" 
                                    alt="icompras360" 
                                    style="border: 2px solid #D2D6DE;"
                                    oncontextmenu="return false">
                        
                                </a>

                            </div>
                        </td>

                        <td title="DESCRIPCION DEL PRODUCTO">
                            <B><?php echo e($t->desprod); ?></B>
                            <div style="margin-top: 5px;
                                font-size: 14px;
                                padding: 1px; "                             
                                title="ID DEL PEDIDO">
                                ID: <?php echo e($t->id); ?> <br> 
                                <span title="FECHA DE ENVIO DEL PEDIDO">
                                    E: <?php echo e(date('d-m-Y', strtotime($t->fecenviado))); ?>

                                </span>
                            </div>
                            <?php if($t->dcredito > 0): ?>
                                <div style="margin-top: 5px;
                                    border-radius: 5px; 
                                    font-size: 14px;
                                    text-align: center;
                                    padding: 1px; 
                                    color: white;
                                    width: 100px;
                                    background-color: black;"
                                    title="DIAS DE CREDITO">
                                    DIAS: <?php echo e($t->dcredito); ?> 
                                </div>
                            <?php endif; ?>
                        </td>

                        <td>
                            <span title="CODIGO DE BARRA">
                                <i class="fa fa-barcode">
                                    <?php echo e($t->barra); ?>

                                </i><br>
                            </span>
                            <span title="MARCA DEL PRODUCTO">
                                <i class="fa fa-shield">
                                    <?php echo e(LeerProdcaract($t->barra, 'marca', 'POR DEFINIR')); ?>    
                                </i>
                            </span><br> 
                            <span>
                                RNK: <?php echo e($t->ranking); ?>

                            </span>
                        </td>

                        <td style="background-color: <?php echo e($confprov->backcolor); ?>; 
                            color: <?php echo e($confprov->forecolor); ?>;"
                            title="CODIGO DEL PROVEEDOR">
                            <img style="width: 20px; height: 20px;" 
                            src="<?php echo e($rutalogoprov.$confprov->rutalogo1); ?>" 
                            alt="icompras360">
                                <?php echo e($confprov->descripcion); ?><br>
                            <span title="CODIGO DEL PRODUCTO">
                                <i class="fa fa-cube">
                                    <?php echo e($t->codprod); ?>

                                </i>
                            </span>
                        </td>

                        <td style="background-color: <?php echo e($confprov->backcolor); ?>; color: <?php echo e($confprov->forecolor); ?>;" 
                            align="right"
                            title="CANTIDAD DEL PEDIDO">
                            <?php echo e(number_format($t->cantidad, 2, '.', ',')); ?>

                        </td>
                        
                        <td style="background-color: <?php echo e($confprov->backcolor); ?>; color: <?php echo e($confprov->forecolor); ?>;" 
                            align="right"
                            title="PRECIO DEL PRODUCTO">
                            <?php echo e(number_format($t->precio/$factor, 2, '.', ',')); ?>

                        </td>
                        
                        <td style="background-color: <?php echo e($confprov->backcolor); ?>; color: <?php echo e($confprov->forecolor); ?>;" 
                            align="right"
                            title="IVA DEL PRODUCTO">
                            <?php echo e(number_format($t->iva, 2, '.', ',')); ?>

                        </td>
                        
                        <?php if($t->da > 0): ?>
                            <td style="background-color: <?php echo e($confprov->backcolor); ?>; color: red;" 
                                align="right" 
                                title="DESCUENTO ADICIONAL DEL PRODUCTO">
                                <?php echo e(number_format($t->da, 2, '.', ',')); ?>

                            </td>
                        <?php else: ?>
                            <td style="background-color: <?php echo e($confprov->backcolor); ?>; color: <?php echo e($confprov->forecolor); ?>;" 
                                align="right"
                                title="DESCUENTO ADICIONAL DEL PRODUCTO">
                                <?php echo e(number_format($t->da, 2, '.', ',')); ?>

                            </td>
                        <?php endif; ?>

                        <?php if($t->dp > 0): ?>
                            <td style="background-color: <?php echo e($confprov->backcolor); ?>; color: red;" 
                                align="right" 
                                title="DESCUENTO PRE-EMPAQUE DEL PRODUCTO">
                                <?php echo e(number_format($t->dp, 2, '.', ',')); ?>

                            </td>
                        <?php else: ?>
                            <td style="background-color: <?php echo e($confprov->backcolor); ?>; color: <?php echo e($confprov->forecolor); ?>;" 
                                align="right"
                                title="DESCUENTO PRE-EMPAQUE DEL PRODUCTO">
                                <?php echo e(number_format($t->dp, 2, '.', ',')); ?>

                            </td>
                        <?php endif; ?>

                        <?php if($t->di > 0): ?>
                            <td style="background-color: <?php echo e($confprov->backcolor); ?>; color: red;" 
                                align="right" 
                                title="DESCUENTO INTERNET">
                                <?php echo e(number_format($t->di, 2, '.', ',')); ?>

                            </td>
                        <?php else: ?>
                            <td style="background-color: <?php echo e($confprov->backcolor); ?>; color: <?php echo e($confprov->forecolor); ?>;" 
                                align="right"
                                title="DESCUENTO INTERNET">
                                <?php echo e(number_format($t->di, 2, '.', ',')); ?>

                            </td>
                        <?php endif; ?>

                        <?php if($t->dc > 0): ?>
                            <td style="background-color: <?php echo e($confprov->backcolor); ?>; color: red;" 
                                align="right" 
                                title="DESCUENTO COMERCIAL">
                                <?php echo e(number_format($t->dc, 2, '.', ',')); ?>

                            </td>
                        <?php else: ?>
                            <td style="background-color: <?php echo e($confprov->backcolor); ?>; color: <?php echo e($confprov->forecolor); ?>;" 
                                align="right"
                                title="DESCUENTO COMERCIAL">
                                <?php echo e(number_format($t->dc, 2, '.', ',')); ?>

                            </td>
                        <?php endif; ?>

                        <?php if($t->pp > 0): ?>
                            <td style="background-color: <?php echo e($confprov->backcolor); ?>; color: red;" 
                                align="right" 
                                title="DESCUENTO PRONTO PAGO">
                                <?php echo e(number_format($t->pp, 2, '.', ',')); ?>

                            </td>
                        <?php else: ?>
                            <td style="background-color: <?php echo e($confprov->backcolor); ?>; color: <?php echo e($confprov->forecolor); ?>;" 
                                align="right"
                                title="DESCUENTO PRONTO PAGO">
                                <?php echo e(number_format($t->pp, 2, '.', ',')); ?>

                            </td>
                        <?php endif; ?>
                        
                        <td style="background-color: <?php echo e($confprov->backcolor); ?>; color: <?php echo e($confprov->forecolor); ?>;" 
                            align="right"
                            title="PRECIO NETO DEL PRODUCTO">
                            <?php echo e(number_format($t->neto/$factor, 2, '.', ',')); ?>

                        </td>

                        <td style="background-color: <?php echo e($confprov->backcolor); ?>; color: <?php echo e($confprov->forecolor); ?>;" 
                            align="right"
                            title="SUBTOTAL DEL PRODUCTO"> 
                            <?php echo e(number_format($t->subtotal/$factor, 2, '.', ',')); ?>

                        </td>

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
<?php echo $__env->make('layouts.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/isacom/informes/desvped.blade.php ENDPATH**/ ?>