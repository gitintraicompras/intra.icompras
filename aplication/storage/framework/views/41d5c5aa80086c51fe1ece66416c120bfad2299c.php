
<?php $__env->startSection('contenido'); ?>
 
<?php
  $moneda = Session::get('moneda', 'BSS');
  $factor = RetornaFactorCambiario('', $moneda);
?>

<!-- ENCABEZADO -->
<div class="col-xs-12">
    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 input-group input-group-sm">
                <span class="input-group-addon">ID:</span>
                <input readonly type="text" class="form-control" value="<?php echo e($reg->id); ?>" style="color: #000000">

                <span class="input-group-addon" style="border:0px; "></span>
                <span class="input-group-addon">Estado:</span>
                <input readonly type="text" class="form-control" value="<?php echo e($reg->status); ?>" style="color: #000000">

                <span class="input-group-addon" style="border:0px; "></span>
                <span class="input-group-addon">Fecha:</span>
                <input readonly type="text" class="form-control" value="<?php echo e(date('d-m-Y H:i:s', strtotime($reg->fecha))); ?>" style="color: #000000">

                <span class="input-group-addon" style="border:0px; "></span>
                <span class="input-group-addon">Procesado:</span>
                <input readonly type="text" class="form-control" value="<?php echo e(date('d-m-Y H:i:s', strtotime($reg->fecprocesado))); ?>" style="color:#000000" >
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 input-group input-group-sm">
                <span class="input-group-addon">Desde:</span>
                <input readonly type="text" class="form-control" value="<?php echo e(date('d-m-Y H:i:s', strtotime($reg->desde))); ?>" style="color: #000000">

                <span class="input-group-addon" style="border:0px; "></span>
                <span class="input-group-addon">Hasta:</span>
                <input readonly type="text" class="form-control" value="<?php echo e(date('d-m-Y H:i:s', strtotime($reg->hasta))); ?>" style="color:#000000" >

                <span class="input-group-addon" style="border:0px; "></span>
                <span class="input-group-addon">
                <?php if($reg->ppsa == '1'): ?>
                    <input disabled="" checked type="checkbox" >
                <?php else: ?>
                    <input disabled type="checkbox" >
                <?php endif; ?>
                <label class="form-check-label">Ppsa</label>
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 input-group input-group-sm">
                <span class="input-group-addon">Usuario:</span>
                <input readonly type="text" 
                    class="form-control" 
                    value="<?php echo e($reg->usuario); ?>"  >                 
           
                <span class="input-group-addon" style="border:0px; "></span>
                <span class="input-group-addon">Observación:</span>
                <input readonly type="text" 
                    class="form-control" 
                    value="<?php echo e($reg->observ); ?>">
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <div class="table-responsive">
            <table id="idTabla" class="table table-striped table-bordered table-condensed table-hover">
                <thead class="colorTitulo">
                    <th>#</th>
                    <th title="DESCRIPCION DEL PRODUCTO">PRODUCTO</th>
                    <th title="COPDIGO DEL PRODUCTO">CODIGO</th>
                    <th title="PRECIO SUGERIDO">PS</th>
                    <th title="DESCUENTO ADICIONAL">DA</th>
                </thead>
                <?php $__currentLoopData = $tabla; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($loop->iteration); ?></td>
                        <td><?php echo e($t->desprod); ?></td>
                        <td><?php echo e($t->codprod); ?></td>
                        <td align="right">
                            <?php echo e(number_format($t->ps/$factor, 2, '.', ',')); ?>

                        </td>
                        <td align="right">
                            <?php echo e(number_format($t->da, 2, '.', ',')); ?>

                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </table>
        </div>
    </div>
</div>


<!-- REGRESAR -->
<div class="form-group" style="margin-left: 15px; margin-top: 20px;">
    <button type="button" class="btn-normal" onclick="history.back(-1)">Regresar</button>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
$('#subtitulo').text('<?php echo e($subtitulo); ?>');
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/ofertas/registros/show.blade.php ENDPATH**/ ?>