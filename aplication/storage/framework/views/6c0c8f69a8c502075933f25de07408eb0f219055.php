<?php $__env->startSection('contenido'); ?>

<div id="page-wrapper">
 	<div class="form-group">
        <div style="margin-top: 4px;" class="input-group input-group-sm">
            <span class="input-group-addon">Factura:</span>
            <input readonly type="text" class="form-control" value="<?php echo e($tabla->factnum); ?>" style="color: #000000; background: #F7F7F7;">

            <span class="input-group-addon" style="border:0px; "></span>
		    <span class="input-group-addon hidden-xs">Fecha:</span>
            <input readonly type="text" class="form-control" value="<?php echo e(date('d-m-Y H:i', strtotime($tabla->fecha))); ?>" style="color: #000000; background: #F7F7F7;">

            <span class="input-group-addon hidden-xs" style="border:0px; "></span>
            <span class="input-group-addon hidden-xs">Monto:</span>
            <input readonly type="text" class="form-control hidden-xs" value="<?php echo e(number_format($tabla->monto, 2, '.', ',')); ?>" style="color: #000000; text-align: right; background: #F7F7F7;">

            <span class="input-group-addon" style="border:0px; "></span>
            <span class="input-group-addon hidden-xs">Iva:</span>
            <input readonly type="text"
                class="form-control"
                value="<?php echo e(number_format($tabla->iva, 2, '.', ',')); ?>"
                style="color: #000000; text-align: right; background: #F7F7F7;">

        </div>

        <div style="margin-top: 4px;" class="input-group input-group-sm">

            <span class="input-group-addon hidden-xs">Gravable:</span>
            <input readonly type="text" class="hidden-xs form-control" value="<?php echo e(number_format($tabla->gravable, 2, '.', ',')); ?>" style="color: #000000; text-align: right; background: #F7F7F7;">

            <span class="input-group-addon hidden-xs" style="border:0px; "></span>
            <span class="input-group-addon">Descuento:</span>
            <input readonly type="text" class="form-control" value="<?php echo e(number_format($tabla->descuento, 2, '.', ',')); ?>" style="color: #000000; text-align: right; background: #F7F7F7;">

            <span class="input-group-addon" style="border:0px; "></span>
            <span class="input-group-addon">Total:</span>
            <b><input readonly
                type="text"
                class="form-control"
                value="<?php echo e(number_format($tabla->total, 2, '.', ',')); ?>"
                style="color: #000000; text-align: right; background: #F7F7F7;"></b>
        </div>

        <div style="margin-top: 4px;">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 input-group input-group-sm">
                <span class="input-group-addon">Observación:</span>
                <input id="idobs" readonly type="text" class="form-control" value="<?php echo e($tabla->observacion); ?>" style="color: #000000; background: #F7F7F7;">
            </div>
        </div>
    </div>
    <div  class="table-responsive">
        <table class="table table-striped table-bordered table-condensed table-hover">
            <thead class="colorTitulo">
                <th style="vertical-align:middle;">#</th>
                    <th style="width: 90px; vertical-align:middle;">
                    &nbsp;&nbsp;&nbsp;IMAGEN&nbsp;&nbsp;&nbsp;
                </th>
                <th>
                    PRODUCTO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </th>
                <th title="Referencias del producto">
                    REFERENCIAS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </th>
                <th title="CANTIDAD">CANT</th>
                <th>PRECIO</th>
                <th>IVA</th>
                <th>SUBTOTAL</th>
            </thead>

            <?php $__currentLoopData = $tabla2; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $iva = 0;
                if ($t->impuesto > 0) {
                    $iva = $cfg->valoriva;
                }
            ?>
            <tr>
                <td><?php echo e($loop->iteration); ?></td>
                <td>
                    <div align="center">

                        <a href="<?php echo e(URL::action('PedidoController@verprod',$t->referencia)); ?>">

                            <img src="http://isaweb.isbsistemas.com/public/storage/prod/<?php echo e(NombreImagen($t->referencia)); ?>"
                            width="100%"
                            height="100%"
                            class="img-responsive"
                            alt="icompras360"
                            style="border: 2px solid #D2D6DE;"
                            oncontextmenu="return false">

                        </a>

                    </div>
                </td>
                <td>
                    <b><?php echo e($t->desprod); ?></b>
                </td>
                <td>
                    <span title="CODIGO DE BARRA">
                        <i class="fa fa-barcode">
                            <?php echo e($t->referencia); ?>

                        </i><br>
                    </span>
                    <span title="CODIGO DEL PRODUCTO">
                        <i class="fa fa-cube">
                            <?php echo e($t->codprod); ?>

                        </i><br>
                    </span>
                    <span title="MARCA DEL PRODUCTO">
                        <i class="fa fa-shield">
                            <?php echo e(LeerProdcaract($t->referencia, 'marca', 'POR DEFINIR')); ?>

                        </i>
                    </span>
                </td>
                <td align="right"><?php echo e(number_format($t->cantidad, 0, '.', ',')); ?></td>
                <td align="right"><?php echo e(number_format($t->precio, 2, '.', ',')); ?></td>
                <td align="right"><?php echo e(number_format($iva, 2, '.', ',')); ?></td>
                <td align="right"><?php echo e(number_format($t->subtotal, 2, '.', ',')); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </table>
        <span title="TASA CAMBIARIA">
            <b> TASA: *** <?php echo e(number_format($tabla->factorcambiario, 2, '.', ',')); ?> *** </b>
        </span>
    </div>
</div>
<!-- REGRESAR -->
<br>
<button type="button" class="btn-normal" onclick="history.back(-1)">Regresar</button>

<?php $__env->startPush('scripts'); ?>
<script>
$('#subtitulo').text('<?php echo e($subtitulo); ?>');
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/isacom/factura/show.blade.php ENDPATH**/ ?>