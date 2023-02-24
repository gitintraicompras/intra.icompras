
<?php $__env->startSection('contenido'); ?>
<?php
  $moneda = Session::get('moneda', 'BSS');
  $factor = RetornaFactorCambiario('', $moneda);
  if ($moneda == "USD") {
    if ($tabla->factor != 1)
        $factor = $tabla->factor;
  }
?>

<!-- ENCABEZADO -->
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="form-group">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 input-group input-group-sm">
                
                <span class="input-group-addon">ID:</span>
                <input readonly type="text" 
                    class="form-control"
                    value="<?php echo e($tabla->id); ?>" 
                    style="color: #000000; padding: 2px;">

                <span class="input-group-addon hidden-xs" style="border:0px; "></span>
                <span class="input-group-addon hidden-xs">Estado:</span>
                <input readonly type="text" class="form-control hidden-xs" value="<?php echo e($tabla->estado); ?>" style="color: #000000">

                <span class="input-group-addon hidden-xs" style="border:0px; "></span>
                <span class="input-group-addon hidden-xs">Fecha:</span>
                <input readonly type="text" class="form-control hidden-xs" value="<?php echo e(date('d-m-Y H:i:s', strtotime($tabla->fecha))); ?>" style="color: #000000">

                <span class="input-group-addon hidden-xs" style="border:0px; "></span>
                <span class="input-group-addon hidden-xs">Enviado:</span>
                <input readonly type="text" class="form-control hidden-xs" value="<?php echo e(date('d-m-Y H:i:s', strtotime($tabla->fecenviado))); ?>" style="color:#000000" >

            </div>
        </div> 
        <div class="row hidden-sm hidden-xs" style="margin-top: 4px;">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 input-group input-group-sm">
                
                <span class="input-group-addon">Descuento:</span>
                <input readonly 
                    type="text" 
                    class="form-control" 
                    value="<?php echo e(number_format($tabla->descuento, 2, '.', ',')); ?>" 
                    style="color: #000000; text-align: right;" 
                    id="idDescuento">

                <span class="input-group-addon" style="border:0px; "></span>
                <span class="input-group-addon">Subtotal:</span>
                <input readonly 
                    type="text" 
                    class="form-control" 
                    value="<?php echo e(number_format($tabla->subtotal, 2, '.', ',')); ?>" 
                    style="color: #000000; text-align: right;" 
                    id="idSubtotal">

                <span class="input-group-addon" style="border:0px; "></span>
                <span class="input-group-addon">Impuesto:</span>
                <input readonly 
                    type="text" 
                    class="form-control" 
                    value="<?php echo e(number_format($tabla->impuesto, 2, '.', ',')); ?>" 
                    style="color: #000000; text-align: right;" 
                    id="idImpuesto">

                <span class="input-group-addon" style="border:0px; "></span>
                <span class="input-group-addon">Total:</span>
                <input readonly 
                    type="text" 
                    class="form-control" 
                    value="<?php echo e(number_format($tabla->total, 2, '.', ',')); ?>" 
                    style="color:#000000; text-align: right; font-size: 20px;" 
                    id="idTotal">                 
            </div>
        </div>
    </div>
</div>

<div class="col-md-12">
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <li class="active">
            <a href="#tab_1" data-toggle="tab">DETALLE</a>
          </li>
          <li >
            <a href="#tab_2" data-toggle="tab">RESUMEN</a>
          </li>
          <li class="pull-right"><a href="<?php echo e(url('/')); ?>" class="text-muted">
            <i class="fa fa-times"></i></a>
          </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane active" class="tab-pane" id="tab_1">
                <div class="row">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend" id="button-addon3">
                            <a href="<?php echo e(url('pedidodirecto/descargar/pedidopdf/'.$id.'-'.$tabla->marca)); ?>">
                                <button style="width: 153px; height: 32px;"  class="btn-normal" type="button" data-toggle="tooltip" title="Descargar pedido en pdf">
                                    Descargar
                                </button>
                            </a>
                        </div>
                    </div>
                    <!-- TABLA -->
                    <br>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <table id="idTabla" class="table table-striped table-bordered table-condensed table-hover">
                                    <thead class="colorTitulo">
                                        <th>#</th>
                                        <th>PRODUCTO</th>
                                        <th>BARRA</th>
                                        <th>MARCA</th> 
                                        <th>CODIGO</th> 
                                        <th>CANTIDAD</th>
                                        <th>COSTO</th>
                                        <th>IVA</th>
                                        <th>SUBTOTAL</th>
                                        <th style="display:none;">ITEM</th>
                                    </thead>
                                    <?php $__currentLoopData = $tabla2; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>

                                            <?php if($t->estado == "ENVIADO" || $t->estado == "RECIBIDO"): ?>
                                                <td style="background-color: #b7b7b7; 
                                                    color: #000000;" 
                                                    title = "PRODUCTO ENVIADO">
                                                    <a href="" 
                                                        style="color: #000000;" 
                                                        data-target="#modal-consulta-<?php echo e($t->item); ?>" data-toggle="modal">
                                                        <?php echo e($loop->iteration); ?>

                                                    </a>
                                                </td>
                                            <?php else: ?>
                                                <td>
                                                    <?php echo e($loop->iteration); ?>

                                                </td>
                                            <?php endif; ?>
                                            <td title="DESCRIPCION DEL PRODUCTO">
                                                <?php echo e($t->desprod); ?>

                                            </td>

                                            <td id="idBarra-<?php echo e($t->item); ?>"
                                                title="BARRA DEL PRODUCTO">
                                                <?php echo e($t->barra); ?>

                                            </td>

                                            <td title="MARCA DEL PRODUCTO">
                                                <?php echo e(LeerProdcaract($t->barra, 'marca', 'POR DEFINIR')); ?>

                                            </td>
                                           
                                            <td title="CODIGO DEL PRODUCTO">
                                                <?php echo e($t->codprod); ?>

                                            </td>

                                            <td align="right"
                                                title="CANTIDAD DEL PEDIDO">
                                                <?php echo e(number_format($t->cantidad, 2, '.', ',')); ?>

                                            </td>
                                            
                                            <td align="right"
                                                title="PRECIO DEL PRODUCTO">
                                                <?php echo e(number_format($t->precio/$factor, 2, '.', ',')); ?>

                                            </td>
                                            
                                            <td align="right"
                                                title="IVA DEL PRODUCTO">
                                                <?php echo e(number_format($t->iva, 2, '.', ',')); ?>

                                            </td>

                                            <td align="right"
                                                title="SUBTOTAL DEL PRODUCTO"> 
                                                <?php echo e(number_format($t->subtotal/$factor, 2, '.', ',')); ?>

                                            </td>

                                            <td style="display:none;"><?php echo e($t->item); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </table>
                                <?php if($moneda == "USD"): ?>
                                <h4>
                                 *** <?php echo e($cfg->simboloOM); ?> <?php echo e(number_format($factor, 2, '.', ',')); ?> ***
                                </h4>  
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" class="tab-pane" id="tab_2">
                <div class="row">
                    <!-- TABLA -->
                    <br>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-condensed table-hover">
                                    <thead class="colorTitulo">
                                        <th>#</th>
                                        <th>NOMBRE</th>
                                        <th>ESTADO</th>
                                        <th>APROBACION</th>
                                        <th>ENVIADO</th>
                                    </thead>
                                    <tr>
                                        <td>1</td>
                                        <td><?php echo e($tabla->marca); ?></td>
                                        <td><?php echo e($tabla->estado); ?></td>
                                        <td>
                                            <?php if($tabla->estado == "ENVIADO"): ?>
                                                OK->CORREO
                                            <?php else: ?>
                                                NO
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e(date('d-m-Y H:i:s', strtotime($tabla->fecenviado))); ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
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
<?php echo $__env->make('layouts.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/isacom/pedidodirecto/show.blade.php ENDPATH**/ ?>