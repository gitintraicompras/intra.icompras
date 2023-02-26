<?php $__env->startSection('contenido'); ?>

<?php
  $moneda = Session::get('moneda', 'BSS');
  $factor = RetornaFactorCambiario('', $moneda);
  $contador = 0;
  $rutalogoprov = 'http://isaweb.isbsistemas.com/public/storage/prov/';
?>


<!-- ENCABEZADO -->
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="form-group">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 input-group input-group-sm">

                <span class="input-group-addon"
                    style="padding: 2px;">
                    ID:
                </span>
                <b><input
                    readonly
                    type="text"
                    class="form-control"
                    value="<?php echo e($tabla->id); ?> - <?php echo e($tabla->tipedido); ?>"
                    style="color: #000000"></b>

                <span class="input-group-addon" style="border:0px; "></span>
                <span class="input-group-addon">Fecha:</span>
                <input readonly
                    type="text"
                    class="form-control"
                    value="<?php echo e(date('d-m-Y H:i', strtotime($tabla->fecha))); ?>"
                    style="color: #000000">

            </div>
        </div>
    </div>
</div>
<!-- TABLAS -->
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
                    <div class="col-md-12">
                        <div class="row">
                            <!-- BOTONES CATALOGO -->
                            <div class="input-group mb-3">
                                <div class="input-group-prepend" id="button-addon3">
                                    <?php $__currentLoopData = $arrayProv; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $prov = $arrayProv[$key]['codprove'];
                                        ?>
                                        <?php if( $prov == "MAESTRO"): ?>
                                            <a href="<?php echo e(url('/pedido/exportar/'.$tabla->id.'-MAESTRO')); ?>">
                                                <?php
                                                    $r = DB::table('pedren')
                                                    ->selectRaw('count(*) as contitem')
                                                    ->where('id','=', $id)
                                                    ->where('exportado','=', '0')
                                                    ->first();
                                                    if (($r->contitem) == 0)
                                                        continue;
                                                    $contador++;
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
                                                    <?php if($tpactivo == $prov): ?>
                                                        <i class="fa fa-check"></i>
                                                        <b>MAESTRO (<?php echo e(number_format($r->contitem, 0, '.', ',')); ?>)</b>
                                                    <?php else: ?>
                                                        MAESTRO (<?php echo e(number_format($r->contitem, 0, '.', ',')); ?>)
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
                                                ->where('exportado','=', '0')
                                                ->where('codprove','=', $prov)
                                                ->first();
                                                if (($r->contitem) == 0)
                                                    continue;
                                                $contador++;
                                            ?>
                                            <a href="<?php echo e(url('/pedido/exportar/'.$tabla->id.'-'.$confprov->codprove)); ?>">
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
                                                        <b><?php echo e($confprov->descripcion); ?> (<?php echo e(number_format($r->contitem, 0, '.', ',')); ?>)</b>
                                                    <?php else: ?>
                                                        <?php echo e($confprov->descripcion); ?> (<?php echo e(number_format($r->contitem, 0, '.', ',')); ?>)
                                                    <?php endif; ?>
                                                </button>
                                            </a>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                    <!-- EXPORTAR PEDIDO -->
                                    <a href="" data-target="#modal-exportar-<?php echo e($id); ?>" data-toggle="modal">
                                        <button class="btn-normal"
                                            data-toggle="tooltip"
                                            title="Exportar pedido"
                                            style="width: 153px; height: 32px;">
                                            Exportar
                                        </button>
                                    </a>

                                </div>
                            </div>
                            <!-- TABLA -->
                            <br>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="table-responsive">
                                        <?php
                                        $fila = 0;
                                        ?>
                                        <table id="idTabla"
                                            class="table table-striped table-bordered table-condensed table-hover">
                                            <thead class="colorTitulo">
                                                <th>
                                                    #
                                                </th>
                                                <th style="width: 100px; vertical-align:middle;">
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;IMAGEN&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                </th>
                                                <th title="DESCRIPCION PRODUCTO DEL CLIENTE">
                                                    PRODUCTO(C)
                                                </th>
                                                <th style="display:none;">
                                                    BARRA
                                                </th>
                                                <th title="Código alterno para cruzar con la ODC/FACTURA en la exportación"
                                                    style="width: 160px;">
                                                    ALTERNO
                                                </th>
                                                <th title="DESCRIPCION PRODUCTO DEL PROVEEDOR">
                                                    DESCRIPCION(P)
                                                </th>
                                                <th style="display:none;">CODIGO</th>
                                                <th style="display:none;">PROVEEDOR</th>
                                                <th>CANTIDAD</th>
                                                <th>PRECIO</th>
                                                <th>IVA</th>
                                                <th style="display:none;">DA</th>
                                                <th style="display:none;">DI</th>
                                                <th style="display:none;">DC</th>
                                                <th style="display:none;">PP</th>
                                                <th>NETO</th>
                                                <th>SUBTOTAL</th>
                                                <th style="display:none;">DESPROD</th>
                                            </thead>
                                            <?php $__currentLoopData = $tabla2; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if($t->codprove == $tpactivo || $tpactivo == "MAESTRO" ): ?>

                                                    <?php
                                                        if ($t->exportado != 0)
                                                            continue;
                                                        $confprov = LeerProve($t->codprove);
                                                        $factor = RetornaFactorCambiario($t->codprove, $moneda);
                                                        $respAlterno = VerificarCodalterno($t->barra);
                                                        $invDesrip = $t->desprod;
                                                        $inv = verificarProdInventario($t->barra,"");
                                                        if ($inv)
                                                            $invDesrip = $inv->desprod;
                                                        else {
                                                            $inv = LeerInventarioCodigo($respAlterno['codalterno'],"");
                                                            if ($inv) {
                                                                $invDesrip = $inv->desprod;
                                                            }
                                                        }
                                                    ?>

                                                    <tr>

                                                        <?php if($t->estado == "ENVIADO" || $t->estado == "RECIBIDO"): ?>
                                                            <td style="background-color: #b7b7b7;
                                                                color: #000000;"
                                                                title = "PRODUCTO ENVIADO">
                                                                <a href=""
                                                                    style="color: #000000;">
                                                                    <?php echo e($fila++); ?>

                                                                </a>
                                                            </td>
                                                        <?php else: ?>
                                                            <td><?php echo e($fila++); ?></td>
                                                        <?php endif; ?>

                                                        <td>
                                                            <div align="center">
                                                                <a href="<?php echo e(URL::action('PedidoController@verprod',$t->barra)); ?>">

                                                                    <img src="http://isaweb.isbsistemas.com/public/storage/prod/<?php echo e(NombreImagen($t->barra)); ?>"
                                                                    class="img-responsive"
                                                                    alt="icompras360"
                                                                    style="width: 100px;
                                                                    border: 2px solid #D2D6DE;"
                                                                    oncontextmenu="return false">

                                                                </a>
                                                            </div>
                                                        </td>

                                                        <td title="DESCRIPCION DEL PRODUCTO DEL CLIENTE">
                                                            <b><?php echo e($invDesrip); ?></b><br>
                                                            <span title="CODIGO DE BARRA">
                                                                <i class="fa fa-barcode">
                                                                    <?php echo e($t->barra); ?>

                                                                </i>
                                                            </span><br>
                                                        </td>

                                                        <td style="display:none;"
                                                            id="idBarra-<?php echo e($t->item); ?>"
                                                            title="CODIGO DE BARRA">
                                                            <?php echo e($t->barra); ?>

                                                        </td>





                                                        <td style="width: 160px;">
                                                            <div class="col-xs-12 input-group" >
                                                                <input style="color: #000000;
                                                                    width: 120px;"
                                                                    value="<?php echo e($respAlterno['codalterno']); ?>"
                                                                    class="form-control"
                                                                    id="idCodalterno-<?php echo e($t->item); ?>" >
                                                                <span class="input-group-btn">
                                                                    <!-- MODIFICAR CODIGO ALTERMO -->
                                                                    <button
                                                                        style="background-color:<?php echo e($respAlterno['backcolor']); ?>;
                                                                            color: <?php echo e($respAlterno['forecolor']); ?>;"
                                                                        type="button"
                                                                        class="btn btn-pedido BtnModificar"
                                                                        title="<?php echo e($respAlterno['title']); ?>"
                                                                        id="idModificar-<?php echo e($t->item); ?>-<?php echo e($respAlterno['backcolor']); ?>"
                                                                        <?php if(!$respAlterno['activarBuscar']): ?>
                                                                        disabled
                                                                        <?php endif; ?> >
                                                                        <span class="fa fa-check"
                                                                        id="idModificar-<?php echo e($t->item); ?>-<?php echo e($respAlterno['backcolor']); ?>"
                                                                        aria-hidden="true">
                                                                        </span>
                                                                    </button>

                                                                    <!-- BUSCAR CODIGO ALTERNO MANUAL -->
                                                                    <button
                                                                        style="background-color:<?php echo e($respAlterno['backcolor']); ?>;
                                                                            color: <?php echo e($respAlterno['forecolor']); ?>;"
                                                                        type="button"
                                                                        class="btn btn-pedido BtnBuscar"
                                                                        id="idFila1_<?php echo e($fila); ?>"
                                                                        title="Buscar código alternativo de forma manual"
                                                                        <?php if(!$respAlterno['activarBuscar']): ?>
                                                                            disabled
                                                                        <?php endif; ?> >
                                                                        <span class="fa fa-search-plus"
                                                                            aria-hidden="true"
                                                                            id="idFila2_<?php echo e($fila); ?>">
                                                                        </span>
                                                                    </button>

                                                                </span>
                                                            </div>
                                                        </td>

                                                        <td style="background-color: <?php echo e($confprov->backcolor); ?>;
                                                            color: <?php echo e($confprov->forecolor); ?>;"
                                                            title="DESCRIPCION DEL PRODUCTO DEL PROVEEDOR">
                                                            <?php echo e($t->desprod); ?><br>
                                                            <span title="CODIGO DEL PROVEEDOR">
                                                                <img style="width: 20px;
                                                                height: 20px;
                                                                margin-top: 6px;"
                                                                src="<?php echo e($rutalogoprov.$confprov->rutalogo1); ?>"
                                                                alt="icompras360">
                                                                <?php echo e($t->codprove); ?>

                                                            </span><br>
                                                            <span title="CODIGO DEL PRODUCTO">
                                                                <i style="margin-left: 4px;" class="fa fa-cube">
                                                                    <?php echo e($t->codprod); ?>

                                                                </i><br>
                                                            </span>
                                                        </td>

                                                        <td style="display:none;">
                                                            <?php echo e($t->codprod); ?>

                                                        </td>

                                                        <td style="display:none;">
                                                            <?php echo e($t->codprove); ?>

                                                        </td>

                                                        <td style="background-color: <?php echo e($confprov->backcolor); ?>;
                                                            color: <?php echo e($confprov->forecolor); ?>;"
                                                            align="right"
                                                            title="CANTIDAD DEL PEDIDO">
                                                            <?php echo e(number_format($t->cantidad, 2, '.', ',')); ?>

                                                        </td>

                                                        <td style="background-color: <?php echo e($confprov->backcolor); ?>;
                                                            color: <?php echo e($confprov->forecolor); ?>;"
                                                            align="right"
                                                            title="PRECIO DEL PRODUCTO">
                                                            <?php echo e(number_format($t->precio/$factor, 2, '.', ',')); ?>

                                                        </td>

                                                        <td style="background-color: <?php echo e($confprov->backcolor); ?>;
                                                            color: <?php echo e($confprov->forecolor); ?>;"
                                                            align="right"
                                                            title="IVA DEL PRODUCTO">
                                                            <?php echo e(number_format($t->iva, 2, '.', ',')); ?>

                                                        </td>

                                                        <?php if($t->da > 0): ?>
                                                            <td style="background-color: <?php echo e($confprov->backcolor); ?>;
                                                                display:none;
                                                                color: red;"
                                                                align="right"
                                                                title="DESCUENTO ADICIONAL DEL PRODUCTO">
                                                                <?php echo e(number_format($t->da, 2, '.', ',')); ?>

                                                            </td>
                                                        <?php else: ?>
                                                            <td style="background-color: <?php echo e($confprov->backcolor); ?>;
                                                                display:none;
                                                                color: <?php echo e($confprov->forecolor); ?>;"
                                                                align="right"
                                                                title="DESCUENTO ADICIONAL DEL PRODUCTO">
                                                                <?php echo e(number_format($t->da, 2, '.', ',')); ?>

                                                            </td>
                                                        <?php endif; ?>

                                                        <?php if($t->di > 0): ?>
                                                            <td style="background-color: <?php echo e($confprov->backcolor); ?>;
                                                                color: red;
                                                                display:none;"
                                                                align="right"
                                                                title="DESCUENTO INTERNET">
                                                                <?php echo e(number_format($t->di, 2, '.', ',')); ?>

                                                            </td>
                                                        <?php else: ?>
                                                            <td style="background-color: <?php echo e($confprov->backcolor); ?>;
                                                                color: <?php echo e($confprov->forecolor); ?>;
                                                                display:none;"
                                                                align="right"
                                                                title="DESCUENTO INTERNET">
                                                                <?php echo e(number_format($t->di, 2, '.', ',')); ?>

                                                            </td>
                                                        <?php endif; ?>

                                                        <?php if($t->dc > 0): ?>
                                                            <td style="background-color: <?php echo e($confprov->backcolor); ?>;
                                                                color: red;
                                                                display:none;"
                                                                align="right"
                                                                title="DESCUENTO COMERCIAL">
                                                                <?php echo e(number_format($t->dc, 2, '.', ',')); ?>

                                                            </td>
                                                        <?php else: ?>
                                                            <td style="background-color: <?php echo e($confprov->backcolor); ?>;
                                                                color: <?php echo e($confprov->forecolor); ?>;
                                                                display:none;"
                                                                align="right"
                                                                title="DESCUENTO COMERCIAL">
                                                                <?php echo e(number_format($t->dc, 2, '.', ',')); ?>

                                                            </td>
                                                        <?php endif; ?>

                                                        <?php if($t->pp > 0): ?>
                                                            <td style="background-color: <?php echo e($confprov->backcolor); ?>;
                                                                color: red;
                                                                display:none;"
                                                                align="right"
                                                                title="DESCUENTO PRONTO PAGO">
                                                                <?php echo e(number_format($t->pp, 2, '.', ',')); ?>

                                                            </td>
                                                        <?php else: ?>
                                                            <td style="background-color: <?php echo e($confprov->backcolor); ?>;
                                                                color: <?php echo e($confprov->forecolor); ?>;
                                                                display:none;"
                                                                align="right"
                                                                title="DESCUENTO PRONTO PAGO">
                                                                <?php echo e(number_format($t->pp, 2, '.', ',')); ?>

                                                            </td>
                                                        <?php endif; ?>

                                                        <td style="background-color: <?php echo e($confprov->backcolor); ?>;
                                                            color: <?php echo e($confprov->forecolor); ?>;"
                                                           align="right"
                                                            title="PRECIO NETO DEL PRODUCTO">
                                                            <?php echo e(number_format($t->neto/$factor, 2, '.', ',')); ?>

                                                        </td>

                                                        <td style="background-color: <?php echo e($confprov->backcolor); ?>;
                                                            color: <?php echo e($confprov->forecolor); ?>;"
                                                            align="right"
                                                            title="SUBTOTAL DEL PRODUCTO">
                                                            <?php echo e(number_format($t->subtotal/$factor, 2, '.', ',')); ?>

                                                        </td>

                                                        <td style="display:none;">
                                                            <?php echo e($invDesrip); ?>

                                                        </td>

                                                    </tr>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </table>
                                        <?php echo $__env->make('isacom.pedido.buscar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                                    </div>
                                </div>
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
                                <table id="idTabla2"
                                    class="table table-striped table-bordered table-condensed table-hover">

                                    <thead class="colorTitulo">
                                        <th>#</th>
                                        <th style="width: 60px;" class="hidden-xs">IMAGEN</th>
                                        <th>CODIGO</th>
                                        <th>NOMBRE</th>
                                        <th>EXPORTADO</th>
                                        <th>FECHA</th>
                                        <th>MONEDA</th>
                                        <th>FACTOR</th>
                                        <th>MODALIDAD</th>
                                        <th>USUARIO</th>
                                    </thead>
                                    <?php $__currentLoopData = $arrayProv; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $prov = $arrayProv[$key]['codprove'];
                                    ?>
                                    <?php if( $prov != "MAESTRO"): ?>
                                        <?php
                                            $confprov = LeerProve($prov);
                                            if (is_null($confprov))
                                                continue;
                                            $r = DB::table('pedren')
                                            ->where('id','=', $id)
                                            ->where('codprove','=', $prov)
                                            ->first();
                                            if (empty($r))
                                                continue;
                                            $exportado = "NO";
                                            $doc = LeerDocExportado($id, $codcli, "PED", $prov);

                                            if ($doc) {
                                                switch ($doc->exportado) {
                                                    case '0':
                                                        $exportado = 'NO';
                                                        break;
                                                    case '1':
                                                        $exportado = 'SI';
                                                        break;
                                                    case '2':
                                                        $exportado = 'PED';
                                                        break;
                                                    case '3':
                                                        $exportado = 'ANULADO';
                                                        break;
                                                    default:
                                                        $exportado = 'NO';
                                                        break;
                                                }
                                            }
                                        ?>
                                        <tr>
                                            <td style="background-color: <?php echo e($confprov->backcolor); ?>;
                                                color: <?php echo e($confprov->forecolor); ?>; ">
                                                <?php echo e($loop->iteration-1); ?>

                                            </td>
                                            <td class="hidden-xs">
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
                                            <td><?php echo e($exportado); ?></td>
                                            <td>
                                                <?php if(isset($doc->fecha)): ?>
                                                    <?php echo e(date('d-m-Y H:i', strtotime($doc->fecha))); ?>

                                                <?php else: ?>
                                                    <?php echo e(date('d-m-Y H:i')); ?>

                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo e(isset($doc->codmoneda) ? $doc->codmoneda : ""); ?></td>
                                            <td align="right">
                                                <?php echo e(number_format(isset($doc->factor) ? $doc->factor : 1.00, 2, '.', ',')); ?>

                                            </td>
                                            <td><?php echo e(isset($doc->modalidad) ? $doc->modalidad : ""); ?></td>
                                            <td><?php echo e(isset($doc->usuario) ? $doc->usuario : ""); ?></td>
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

<!-- MODAL EXPORT -->
<div class="modal fade modal-slide-in-right"
    aria-hidden="true"
    role="dialog"
    tabindex="-1"
    id="modal-exportar-<?php echo e($id); ?>">
<?php echo Form::open(array('url'=>'/pedido/procexportar','method'=>'POST','autocomplete'=>'off')); ?>

<?php echo e(Form::token()); ?>

<input id='id' hidden name="id" value="<?php echo e($tabla->id); ?>" type="text">
<input id='codcli' hidden name="codcli" value="<?php echo e($codcli); ?>" type="text">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header colorTitulo" >
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">x</span>
            </button>
            <h4 class="modal-title">EXPORTAR PEDIDO</h4>
        </div>
        <div class="modal-body">
            <p>Pedido #: <?php echo e($t->id); ?></p>
            <p>Marque los proveedores para exportar el pedido ?</p>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive" style="height: 332px;">
                        <table id="idTabla3"
                            class="table table-striped table-bordered table-condensed table-hover">
                            <thead class="colorTitulo">
                                <th style="width: 20px;">#</th>
                                <th style="width: 60px;">IMAGEN</th>
                                <th style="width: 80px;">
                                    MARCAR
                                </th>
                                <th>MONEDA</th>
                                <th>FACTOR</th>
                                <th>MODALIDAD</th>
                                <th style="display:none;">CODPROVE</th>
                            </thead>

                            <?php $fila = 0; ?>
                            <?php $__currentLoopData = $arrayProv; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    if ($arrayProv[$key]['exportado'] != '0')
                                        continue;
                                    $codprove = $arrayProv[$key]['codprove'];
                                ?>
                                <?php if( $prov != "MAESTRO"): ?>
                                    <?php
                                        $confprov = LeerProve($codprove);
                                        if (is_null($confprov))
                                            continue;
                                        $r = DB::table('pedren')
                                        ->selectRaw('count(*) as contitem')
                                        ->where('id','=', $id)
                                        ->where('codprove','=', $codprove)
                                        ->first();
                                    ?>

                                    <tr>

                                        <td style="background-color: <?php echo e($confprov->backcolor); ?>;
                                            color: <?php echo e($confprov->forecolor); ?>; ">
                                            <?php echo e($fila++); ?>

                                        </td>

                                        <td >
                                            <div align="center">
                                                <a href="">
                                                    <img src="http://isaweb.isbsistemas.com/public/storage/prov/<?php echo e($confprov->rutalogo1); ?>"
                                                    class="img-responsive"
                                                    alt="icompras360"
                                                    style="width: 100px;
                                                    border: 2px solid #D2D6DE;"
                                                    oncontextmenu="return false">
                                                </a>
                                            </div>
                                        </td>

                                        <td style="padding-top: 10px;
                                            text-align: center;
                                            vertical-align: middle;">
                                            <input name='exportar[]'
                                                class="case"
                                                type="checkbox"
                                                id='checkbox_<?php echo e($codprove); ?>' />
                                        </td>

                                        <td>
                                            <select name="codmoneda[]"
                                                class="form-control"
                                                data-live-search="true" >
                                                <option
                                                    <?php if($moneda == 'BSS'): ?> selected <?php endif; ?>
                                                    value="BSS">
                                                    BSS
                                                </option>
                                                <option
                                                    <?php if($moneda == 'USD'): ?> selected <?php endif; ?>
                                                    value="USD">
                                                    USD
                                                </option>
                                                <option
                                                    <?php if($moneda == 'EUR'): ?> selected <?php endif; ?>
                                                    value="EUR">
                                                    EUR
                                                </option>
                                            </select>
                                        </td>

                                        <td>
                                            <input name='tasa[]'
                                                class="form-control"
                                                type="text"
                                                align="right"
                                                style="width: 100%; text-align: right;"
                                                value = "<?php echo e(number_format($factor,2, '.', ',')); ?>" />
                                        </td>

                                        <td>
                                            <select name="modalidad[]"
                                                class="form-control"
                                                data-live-search="true" >
                                                <option selected value="LOCAL">
                                                    LOCAL
                                                </option>
                                                <option value="MATRIZ">
                                                    MATRIZ
                                                </option>
                                            </select>
                                        </td>

                                        <td style="display:none;">
                                            <input name='codprove[]'
                                                type="text"
                                                value = "<?php echo e($codprove); ?>" />
                                        </td>

                                    </tr>

                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-normal" data-dismiss="modal">Regresar</button>
            <?php if($contador > 0): ?>
                <button type="submit" class="btn-confirmar">Confirmar</button>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php echo e(Form::Close()); ?>

</div>

<?php $__env->startPush('scripts'); ?>
<script>
$('#subtitulo').text('<?php echo e($subtitulo); ?>');

window.onload = function() {

    $('.BtnModificar').on('click',function(e) {
        var idpedido = e.target.id.split('-');
        var item = idpedido[1].trim();
        var color = idpedido[2].trim();
        var barra = $('#idBarra-'+item).text().trim();
        var codalterno = $('#idCodalterno-'+item).val().trim();
        //alert("Barra: " + barra + " - Codalterno: " + codalterno + " - Color: " + color);
        $.ajax({
            type:'POST',
            url: '../modcodalterno',
            dataType: 'json',
            encode  : true,
            data: {barra:barra, codalterno:codalterno },
            success:function(data) {
                if (data.msg != '')
                    alert(data.msg);
                location.reload(true);
            }
        });
    });

    $('.BtnBuscar').on('click',function(e) {
        var id = e.target.id.split('_');
        var fila = id[1].trim();
        //alert(fila);
        const tableReg = document.getElementById('idTabla');
        const cellsOfRow = tableReg.rows[fila].getElementsByTagName('td');

        var codprod = cellsOfRow[6].innerHTML.trim();

        var descripBuscar = cellsOfRow[17].innerHTML.trim();

        var barraBuscar = cellsOfRow[3].innerHTML.trim();
        //alert("CODPROD: " + codprod + "  BARRA: " + barraBuscar + " VAR17: " + descripBuscar );

        $("#idbarraBuscar").text(barraBuscar);
        $("#iddescripBuscar").text(descripBuscar);
        $('#modal-buscar').modal();
    });
}

function tdclick(e) {
    var id = e.target.id.split('_');
    var barra = id[1];
    $(".case").prop("checked", false);
    $("#idcheck_" + barra).prop("checked", true);
    $('#idbarra').val(barra);
}

function cargarProd() {
    var codcli = '<?php echo e($codcli); ?>';
    var resp;
    var filtro = $('#idfiltro').val();
    //alert("CODCLI: " + codcli + " FILTRO: " + filtro);
    if (filtro == "") {
        alert("FALTAN PARAMETROS PARA REALIZAR LAS BUSQUEDA");
    } else {
        var jqxhr = $.ajax({
            type:'POST',
            url: '../obtenerInvCliente',
            dataType: 'json',
            encode  : true,
            data: { codcli:codcli, filtro:filtro },
            success:function(data) {
                $("#tbodyProducto").empty();
                $.each(data.resp, function(index, item){
                   var valor =
                    '<tr>' +
                      "<td style='padding-top: 10px;'>" +
                      "<span onclick='tdclick(event);'>" +
                      "<center>" +
                      "<input class='case' type='checkbox' id='idcheck_" + item.barra + "' />" +
                      "</center>" +
                      "</span>" +
                      "</td>" +
                      "<td>" + item.desprod + "</td>" +
                      "<td>" + item.barra + "</td>" +
                      "<td>" + item.marca + "</td>" +
                    "</tr>";
                    $(valor).appendTo("#tbodyProducto");
                });
            }
        });
    }
}


function tdclickProdBorrar(e) {
    var idx = e.target.id.split('_');
    var codalterno = idx[1];
    var barra = $('#idbarraBuscar').text().trim();
    //alert(barra + " - " + codalterno);

    $.ajax({
        type:'POST',
        url: '../modcodalterno',
        dataType: 'json',
        encode  : true,
        data: {barra:barra, codalterno:codalterno },
        success:function(data) {
            if (data.msg != '')
                alert(data.msg);
            location.reload(true);
        }
    });
}

function ejecutarAgregar() {
    var codalterno = $('#idbarra').val();
    var barra = $('#idbarraBuscar').text().trim();
    alert("BARRA: " + barra + " CODALT: " +codalterno);
    if (codalterno == '') {
        alert("FALTAN PARAMETROS PARA AGREGAR UN PRODUCTO");
    } else {
        var url = "<?php echo e(url('/pedgrupo/agregar/prod/X')); ?>";
        url = url.replace('X', ctipo);
        window.location.href=url;
    }
}

</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/isacom/pedido/exportar.blade.php ENDPATH**/ ?>