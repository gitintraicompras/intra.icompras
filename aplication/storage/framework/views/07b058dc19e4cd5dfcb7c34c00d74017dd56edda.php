<?php $__env->startSection('contenido'); ?>
<?php
  $moneda = Session::get('moneda', 'BSS');
  $factor = RetornaFactorCambiario('', $moneda);
  $rutalogoprov = 'http://isaweb.isbsistemas.com/public/storage/prov/';
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
                <b><input readonly
                    type="text"
                    class="form-control"
                    value="<?php echo e($tabla->id); ?> - <?php echo e($tabla->tipedido); ?>"
                    style="color: #000000; padding: 2px;"></b>

                <span class="input-group-addon hidden-xs" style="border:0px; "></span>
                <span class="input-group-addon hidden-xs">Estado:</span>
                <input readonly
                    type="text"
                    class="form-control hidden-xs"
                    value="<?php echo e($tabla->estado); ?> | <?php echo e($tabla->origen); ?>"
                    style="color: #000000">

                <span class="input-group-addon hidden-xs" style="border:0px; "></span>
                <span class="input-group-addon hidden-xs">Fecha:</span>
                <input readonly type="text" class="form-control hidden-xs" value="<?php echo e(date('d-m-Y H:i', strtotime($tabla->fecha))); ?>" style="color: #000000">

                <span class="input-group-addon hidden-xs" style="border:0px; "></span>
                <span class="input-group-addon hidden-xs">Enviado:</span>
                <input readonly type="text" class="form-control hidden-xs" value="<?php echo e(date('d-m-Y H:i', strtotime($tabla->fecenviado))); ?>" style="color:#000000" >

                <span class="input-group-addon" style="border:0px; "></span>
                <span class="input-group-addon">Ahorro:</span>
                <input readonly
                    type="text"
                    class="form-control"
                    value="<?php echo e(number_format($tabla->ahorro/$factor, 2, '.', ',')); ?>"
                    style="color: red; text-align: right; "
                    id="idAhorro">
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
                    value="<?php echo e(number_format($tabla->subtotal/$factor, 2, '.', ',')); ?>"
                    style="color: #000000; text-align: right;"
                    id="idSubtotal">

                <span class="input-group-addon" style="border:0px; "></span>
                <span class="input-group-addon">Impuesto:</span>
                <input readonly
                    type="text"
                    class="form-control"
                    value="<?php echo e(number_format($tabla->impuesto/$factor, 2, '.', ',')); ?>"
                    style="color: #000000; text-align: right;"
                    id="idImpuesto">

                <span class="input-group-addon" style="border:0px; "></span>
                <span class="input-group-addon">Total:</span>
                <input readonly
                    type="text"
                    class="form-control"
                    value="<?php echo e(number_format($tabla->total/$factor, 2, '.', ',')); ?>"
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
                            <?php $__currentLoopData = $arrayProv; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prov): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if( $prov == "MAESTRO"): ?>
                                    <a href="<?php echo e(url('/pedido/'.$tabla->id.'-MAESTRO')); ?>">

                                        <?php
                                            $r = DB::table('pedren')
                                            ->selectRaw('count(*) as contitem')
                                            ->where('id','=', $id)
                                            ->first();
                                        ?>

                                        <button style="width: 153px;
                                            height: 32px;
                                            color: #000000;
                                            border: #b7b7b7;
                                            background-color: #b7b7b7;"
                                            class="btn btn-outline-secondary"
                                            type="button"
                                            data-toggle="tooltip"
                                            title="Ver pedido maestro">
                                            <?php if($tpactivo==$prov): ?>
                                                <i class="fa fa-check"></i>
                                                <b>&nbsp;MAESTRO (<?php echo e(number_format($r->contitem, 0, '.', ',')); ?>)</b>
                                            <?php else: ?>
                                                <img style="width: 20px; height: 20px;"
                                                src="<?php echo e($rutalogoprov.'icompras360.png'); ?>"
                                                alt="icompras360">
                                                &nbsp;MAESTRO (<?php echo e(number_format($r->contitem, 0, '.', ',')); ?>)
                                            <?php endif; ?>
                                        </button>
                                    </a>
                                <?php else: ?>
                                    <?php
                                        $confprov = LeerProve($prov);
                                        if (is_null($confprov))
                                            continue;
                                        $r = DB::table('pedren')
                                        ->selectRaw('count(*) as contitem')
                                        ->where('id','=', $id)
                                        ->where('codprove','=', $prov)
                                        ->first();
                                    ?>
                                    <a href="<?php echo e(url('/pedido/'.$tabla->id.'-'.$confprov->codprove)); ?>">
                                        <button style="width: 153px;
                                            height: 32px;
                                            color:<?php echo e($confprov->forecolor); ?>;
                                            border: <?php echo e($confprov->backcolor); ?>;
                                            background-color: <?php echo e($confprov->backcolor); ?>;"
                                            class="btn btn-outline-secondary"
                                            type="button"
                                            data-toggle="tooltip"
                                            title="Ver pedido por proveedor">
                                            <?php if($tpactivo == $prov): ?>
                                                <i class="fa fa-check"></i>
                                                <b>&nbsp;<?php echo e($confprov->descripcion); ?> (<?php echo e(number_format($r->contitem, 0, '.', ',')); ?>)
                                                </b>
                                            <?php else: ?>
                                                <img style="width: 20px; height: 20px;"
                                                src="<?php echo e($rutalogoprov.$confprov->rutalogo1); ?>" alt="icompras360">
                                                &nbsp;<?php echo e($confprov->descripcion); ?> (<?php echo e(number_format($r->contitem, 0, '.', ',')); ?>)
                                            <?php endif; ?>
                                        </button>
                                    </a>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e(url('pedido/descargar/pedidopdf/'.$id.'-'.$tpactivo)); ?>">
                                <button style="width: 153px; height: 32px;"  class="btn-normal" type="button" data-toggle="tooltip" title="Descargar pedido en pdf">
                                    <i class="fa fa-cloud-download" aria-hidden="true"></i>
                                    &nbsp;Descargar
                                </button>
                            </a>
                        </div>
                    </div>
                    <!-- TABLA -->
                    <br>
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
                                            PRODUCTO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        </th>
                                        <th title="Código de referencia del producto">BARRA</th>
                                        <th style="display:none;"
                                            title="Marca del laboratorio">
                                            MARCA
                                        </th>
                                        <th style="display:none;"
                                            title="Código del producto">
                                            CODIGO
                                        </th>
                                        <th title="Código del proveedor">
                                            PROVEEDOR&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        </th>
                                        <th title="Cantidad solicitada">CANTIDAD</th>
                                        <th title="Precio de venta al público">PRECIO</th>
                                        <th title="Impuesto al valor agregado">IVA</th>
                                        <th title="Descuento adicional">DA</th>
                                        <th title="Descuento pre-empaque">DP</th>
                                        <th title="Descuento internet">DI</th>
                                        <th title="Descuento comercial">DC</th>
                                        <th title="Descuento pronto pago">PP</th>
                                        <th title="Neto del producto">NETO</th>
                                        <th title="Subtotal del producto">SUBTOTAL</th>
                                        <th style="display:none;">ITEM</th>
                                        <th title="Monto en ahorro dal producto">AHORRO</th>
                                    </thead>
                                    <?php $__currentLoopData = $tabla2; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($t->codprove == $tpactivo || $tpactivo == "MAESTRO" ): ?>
                                            <?php
                                                $confprov = LeerProve($t->codprove);
                                                if (is_null($confprov))
                                                    continue;
                                                $factor = RetornaFactorCambiario($t->codprove, $moneda);
                                                $respAlterno = VerificarCodalterno($t->barra);
                                                $marca = LeerProdcaract($t->barra, 'marca', 'POR DEFINIR');
                                            ?>

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
                                                        <?php if($t->ranking == 1): ?>
                                                        <i title = "PRODUCTO ES LA PRIMERA OPCION"
                                                            class="fa fa-thumbs-o-up"
                                                            aria-hidden="true">
                                                        </i>
                                                        <?php endif; ?>
                                                    </td>
                                                <?php else: ?>
                                                    <td>
                                                        <?php echo e($loop->iteration); ?>

                                                        <?php if($t->ranking == 1): ?>
                                                        <i title = "PRODUCTO ES LA PRIMERA OPCION"
                                                            class="fa fa-thumbs-o-up"
                                                            aria-hidden="true">
                                                        </i>
                                                        <?php endif; ?>
                                                    </td>
                                                <?php endif; ?>

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
                                                    <b><?php echo e($t->desprod); ?></b><br>
                                                    <?php if($t->dcredito > 0): ?>
                                                        <span style="font-size: 10px;">
                                                            DIAS:
                                                        </span>
                                                        <span style="border-radius: 5px;
                                                            font-size: 16px;
                                                            text-align: center;
                                                            padding: 2px;
                                                            width: 70px;
                                                            color: white;
                                                            background-color: #26328C;
                                                            margin-right: 4px;"
                                                            title="DIAS DE CREDITO: <?php echo e($t->dcredito); ?>">
                                                            <?php echo e($t->dcredito); ?>

                                                        </span><br>
                                                    <?php endif; ?>
                                                    <spa title="RANKING DEL PRODUCTO CON RESPECTO AL PROVEEDOR">
                                                        RNK: <?php echo e($t->ranking); ?>

                                                    </span>
                                                </td>

                                                <td id="idBarra-<?php echo e($t->item); ?>"
                                                    title="BARRA DEL PRODUCTO">
                                                    <?php echo e($t->barra); ?>

                                                </td>

                                                <td style="display:none;"
                                                    title="MARCA DEL PRODUCTO">
                                                    <?php echo e($marca); ?>

                                                </td>

                                                <td style="display:none; background-color: <?php echo e($confprov->backcolor); ?>;
                                                    color: <?php echo e($confprov->forecolor); ?>";
                                                    title="CODIGO DEL PRODUCTO">
                                                    <?php echo e($t->codprod); ?>

                                                </td>

                                                <td style="background-color: <?php echo e($confprov->backcolor); ?>; color: <?php echo e($confprov->forecolor); ?>;"
                                                    title="CODIGO DEL PROVEEDOR">
                                                    <img style="width: 20px;
                                                        height: 20px;"
                                                        src="<?php echo e($rutalogoprov.$confprov->rutalogo1); ?>"
                                                        alt="icompras360">
                                                        &nbsp;<?php echo e($t->codprove); ?><br>
                                                    <span title="MARCA DEL PRODUCTO">
                                                        <i class="fa fa-shield">
                                                            <?php echo e($marca); ?>

                                                        </i>
                                                    </span><br>
                                                    <span title="CODIGO DEL PRODUCTO">
                                                        <i class="fa fa-cube">
                                                            <?php echo e($t->codprod); ?>

                                                        </i>
                                                    </span>
                                                </td>

                                                <td style="background-color: <?php echo e($confprov->backcolor); ?>; color: <?php echo e($confprov->forecolor); ?>;"
                                                    align="right"
                                                    title="CANTIDAD DEL PEDIDO">
                                                    <?php echo e(number_format($t->cantidad, 0, '.', ',')); ?>

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

                                                <td style="display:none;"><?php echo e($t->item); ?></td>
                                                <td style="background-color: <?php echo e($confprov->backcolor); ?>; color: <?php echo e($confprov->forecolor); ?>;"
                                                    align="right"
                                                    title="AHORRO DEL PRODUCTO">
                                                    <?php echo e(number_format(($t->ahorro*$t->cantidad)/$factor, 2, '.', ',')); ?>

                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <th colspan="14" >
                                        <th style="vertical-align:middle;
                                            background-color: #b7b7b7;
                                            text-align: right;"
                                            id="idtotped"
                                            title="Monto en total del pedido">
                                        </th>
                                        <th style="display: none;"></th>
                                        <th style="vertical-align:middle;
                                            background-color: #b7b7b7;
                                            text-align: right;"
                                            id="idahoped"
                                            title="Monto en ahorro del pedido">
                                        </th>
                                    </tr>
                                </table>
                                <?php if($moneda == "USD"): ?>

                                    <span title="TASA CAMBIARIA">
                                        <b> TASA: *** <?php echo e(number_format($factor, 2, '.', ',')); ?> *** </b>
                                    </span>

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
                                <table id="idTabla" class="table table-striped table-bordered table-condensed table-hover">
                                    <thead class="colorTitulo">
                                        <th>#</th>
                                        <th style="width: 60px;">IMAGEN</th>
                                        <th>CODIGO</th>
                                        <th>NOMBRE</th>
                                        <th>ESTADO</th>
                                        <th>APROBACION</th>
                                        <th>ENVIADO</th>
                                    </thead>
                                    <?php $__currentLoopData = $arrayProv; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prov): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if( $prov != "MAESTRO"): ?>
                                        <?php
                                            $confprov = LeerProve($prov);
                                            if (is_null($confprov))
                                                continue;
                                            $r = DB::table('pedren')
                                            ->where('id','=', $id)
                                            ->where('codprove','=', $prov)
                                            ->first();
                                        ?>
                                        <tr>
                                            <td style="background-color: <?php echo e($confprov->backcolor); ?>;
                                                color: <?php echo e($confprov->forecolor); ?>; ">
                                                <?php echo e($loop->iteration-1); ?>

                                            </td>
                                            <td>
                                                <div align="center">
                                                    <a href="<?php echo e(URL::action('ProveedorController@verprov',$prov)); ?>">

                                                        <img src="http://isaweb.isbsistemas.com/public/storage/prov/<?php echo e($confprov->rutalogo1); ?>"
                                                        class="img-responsive"
                                                        alt="icompras360"
                                                        style="width: 100px;
                                                        border: 2px solid #D2D6DE;"
                                                        oncontextmenu="return false">

                                                    </a>
                                                </div>
                                            </td>
                                            <td><?php echo e($confprov->codprove); ?></td>
                                            <td><?php echo e(strtoupper($confprov->nombre)); ?></td>
                                            <td><?php echo e($r->estado); ?></td>
                                            <td><?php echo e($r->aprobacion); ?></td>
                                            <td><?php echo e(date('d-m-Y H:i', strtotime($r->fecenviado))); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
window.onload = function() {
    var tableReg = document.getElementById('idTabla');
    var subtotal = 0.00;
    var ahorro = 0.00;
    for (var i = 1; i < tableReg.rows.length-1; i++) {
        cellsOfRow = tableReg.rows[i].getElementsByTagName('td');
        subtotal += parseFloat(cellsOfRow[16].innerHTML.replace(/,/g, ''));
        ahorro += parseFloat(cellsOfRow[18].innerHTML.replace(/,/g, ''));
    }
    $("#idtotped").text(number_format(subtotal, 2, '.', ','));
    $("#idahoped").text(number_format(ahorro, 2, '.', ','));
}
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/isacom/pedido/show.blade.php ENDPATH**/ ?>