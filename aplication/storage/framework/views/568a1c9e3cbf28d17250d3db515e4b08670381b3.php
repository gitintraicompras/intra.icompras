
<?php $__env->startSection('contenido'); ?>
<?php
$moneda = Session::get('moneda', 'BSS');
$factor = RetornaFactorCambiario("", $moneda);
$x=0;
$y=0;
?> 
  
<!-- ENCABEZADO -->
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="form-group">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 input-group input-group-sm">
                
                <span class="input-group-addon">ID:</span>
                <input readonly type="text" 
                    class="form-control"
                    value="<?php echo e($id); ?>" 
                    style="color: #000000; padding: 2px;">

                <span class="input-group-addon hidden-xs" style="border:0px; "></span>
                <span class="input-group-addon hidden-xs">Estado:</span>
                <input readonly 
                    type="text" 
                    class="form-control hidden-xs" 
                    value="<?php echo e($pedgrupo->estado); ?>" 
                    style="color: #000000">

                <span class="input-group-addon hidden-xs" style="border:0px; "></span>
                <span class="input-group-addon hidden-xs">Fecha:</span>
                <input readonly 
                    type="text" 
                    class="form-control hidden-xs" 
                    value="<?php echo e(date('d-m-Y H:i:s', strtotime($pedgrupo->fecha))); ?>" 
                    style="color: #000000">

                <span class="input-group-addon hidden-xs" style="border:0px; "></span>
                <span class="input-group-addon hidden-xs">Enviado:</span>
                <input readonly 
                    type="text" 
                    class="form-control hidden-xs" 
                    value="<?php echo e(date('d-m-Y H:i:s', strtotime($pedgrupo->enviado))); ?>" 
                    style="color:#000000" >

                <span class="input-group-addon" style="border:0px; "></span>
                <span class="input-group-addon">Marca:</span>
                <input readonly 
                    type="text" 
                    class="form-control" 
                    value="<?php echo e($pedgrupo->marca); ?>"
                    style="color:#000000" >
    
                <span class="input-group-addon" style="border:0px; "></span>
                <span class="input-group-addon">Reposición:</span>
                <input readonly 
                    type="text" 
                    class="form-control" 
                    value="<?php echo e($pedgrupo->reposicion); ?>"
                    style="color:#000000" >
               
            </div>
        </div>
    </div>
</div> 

<div class="btn-toolbar" role="toolbar" style="margin-top: 12px; margin-bottom: 3px;">
    <div class="btn-group" role="group" style="width: 100%;">

        <?php echo $__env->make('isacom.pedgrupo.editsearch', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <!-- AGREGAR PRODUCTO NUEVO -->
        <a href="" 
            data-target="#modal-agregar-<?php echo e($id); ?>" 
            data-toggle="modal">
            <button style="width: 90px; height: 34px; border-radius: 5px;" 
                type="button" 
                data-toggle="tooltip" 
                title="Agregar producto nuevo al pedido" 
                class="btn-catalogo">
                Agregar
            </button>
        </a>
   
        <!-- ENVIA PEDIDO -->
        <a href="" 
            data-target="#modal-enviar-<?php echo e($id); ?>" 
            data-toggle="modal">
            <button style="width: 90px; height: 34px; border-radius: 5px;" 
                type="button" 
                data-toggle="tooltip" 
                title="Enviar pedido" 
                class="btn-confirmar">
                Enviar
            </button>
        </a>

    </div> 
</div> 
<?php echo $__env->make('isacom.pedgrupo.agregar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('isacom.pedgrupo.enviar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>  

<div class="row" style="margin-top: 5px;">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="table-responsive">
            <table id="myTable" 
                class="table table-striped table-bordered table-condensed table-hover">

                <thead style="background-color: #b7b7b7;">
                    <th colspan="6">
                        <center>PRODUCTOS</center>
                    </th>

                    <th colspan="2"
                        style="width: 200; 
                        background-color: #FEE3CB;
                        color: black;">
                        <center>
                        &nbsp;&nbsp;&nbsp;&nbsp;PROVEEDOR&nbsp;&nbsp;&nbsp;&nbsp;
                        </center>
                    </th>
                    
                    <th colspan="2"
                        style="width: 200; 
                        background-color: #FCD5B8;
                        color: black;">
                        <center>
                        &nbsp;&nbsp;&nbsp;&nbsp;GRUPO&nbsp;&nbsp;&nbsp;&nbsp;
                        </center>
                    </th>

                    <?php $__currentLoopData = $gruporen; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <?php 
                        $codsuc = $gr->codcli;
                        $confcli = LeerCliente($codsuc); 
                        $actualizado = date('d-m-Y H:i:s', strtotime(LeerTablaFirst('inventario_'.$codsuc, 'feccatalogo')));
                        $fechaHoy = trim(date("Y-m-d"));
                        $fechaInv = trim(date('Y-m-d', strtotime($actualizado)));
                        $idped = '';
                        $pedido = ObtenerPedidoGrupo($id,$codsuc);
                        if (!empty($pedido)) 
                            $idped = '('.$pedido->id .'-'. $pedido->estado.')';
                        ?>
                        
                        <th colspan="8" 
                            style="background-color: <?php echo e($confcli->backcolor); ?>; 
                            color: <?php echo e($confcli->forecolor); ?>; 
                            width: 400px; 
                            word-wrap: break-word; ">
                            <a href="<?php echo e(URL::action('GrupoController@show',$codsuc)); ?>">
                                <center>
                                    <button type="button" 
                                        data-toggle="tooltip" 
                                        title="<?php echo e($confcli->nombre); ?> &#10 (<?php echo e($actualizado); ?>)" 
                                        style="background-color: <?php echo e($confcli->backcolor); ?>; 
                                        border: none;
                                        <?php if($fechaInv != $fechaHoy): ?>
                                            color: red;
                                        <?php else: ?>
                                            color: <?php echo e($confcli->forecolor); ?>;
                                        <?php endif; ?> 
                                        "> <?php echo e($confcli->descripcion); ?> <?php echo e($idped); ?>

                                    </button>
                                </center>
                            </a>
                        </th>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </thead>

                <thead style="background-color: #b7b7b7;">
                    <th style="width: 10px;
                        vertical-align:middle;">
                        <center>#</center>
                    </th>
                    <th style="width: 120px;
                        vertical-align:middle;">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;IMAGEN&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </th>
                    <th style="width: 150px;
                        vertical-align:middle;"
                        title="Descripción del producto">
                        PRODUCTO
                    </th>
                    <th style="width: 100px;
                        vertical-align:middle;">
                        REFERENCIA
                    </th>
                    <th style="width: 60px;
                        vertical-align:middle;">
                        BULTO
                    </th>
                    <th style="width: 60px;
                        vertical-align:middle;">
                        IVA
                    </th>

                    <!-- PROVEEDOR -->
                    <th style="background-color: #FEE3CB;
                        color: black;
                        vertical-align:middle;">
                        &nbsp;&nbsp;&nbsp;&nbsp;MPP&nbsp;&nbsp;&nbsp;&nbsp;
                    </th>
                    <th style="background-color: #FEE3CB;
                        color: black;
                        vertical-align:middle;">
                        &nbsp;&nbsp;&nbsp;&nbsp;INV.&nbsp;&nbsp;&nbsp;&nbsp;
                    </th>

                    <!-- GRUPO -->
                    <th style="background-color: #FCD5B8;
                        color: black;
                        vertical-align:middle;">
                        SUGERIDO
                    </th>
                    <th style="background-color: #FCD5B8;
                        color: black;
                        vertical-align:middle;">
                        SUBTOTAL
                    </th>

                    <?php $__currentLoopData = $gruporen; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <?php
                        $codsuc = $gr->codcli;
                        $confcli = LeerCliente($codsuc); 
                        $actualizado = date('d-m-Y H:i:s', strtotime(LeerTablaFirst('inventario_'.$codsuc, 'feccatalogo')));
                        $fechaHoy = trim(date("Y-m-d"));
                        $fechaInv = trim(date('Y-m-d', strtotime($actualizado)));
                        ?>

                        <!-- DETALLES -->
                        <th style="background-color: <?php echo e($confcli->backcolor); ?>;
                            color: <?php echo e($confcli->forecolor); ?>;
                            vertical-align:middle; "
                            title="Cantidad sugerida">
                            SUGERIDO
                        </th>
                        <th style="background-color: <?php echo e($confcli->backcolor); ?>;
                            color: <?php echo e($confcli->forecolor); ?>;
                            vertical-align:middle;"
                            title="Costo del producto">
                            COSTO
                        </th>
                        <th style="background-color: <?php echo e($confcli->backcolor); ?>;
                            color: <?php echo e($confcli->forecolor); ?>;
                            vertical-align:middle;"
                            title="Subtotal costo x producto">
                            SUBTOTAL
                        </th>
                        <th style="background-color: <?php echo e($confcli->backcolor); ?>;
                            color: <?php echo e($confcli->forecolor); ?>;
                            vertical-align:middle;"
                            title="Inventario en transito del producto">
                            TRAN.
                        </th>
                        <th style="background-color: <?php echo e($confcli->backcolor); ?>;
                            color: <?php echo e($confcli->forecolor); ?>;
                            vertical-align:middle;"
                            title="Inventario del producto">
                            INV.
                        </th>
                        <th style="background-color: <?php echo e($confcli->backcolor); ?>;
                            color: <?php echo e($confcli->forecolor); ?>;
                            vertical-align:middle;"
                            title="Venta Media Diaria del producto">
                            VMD
                        </th>
                        <th style="background-color: <?php echo e($confcli->backcolor); ?>;
                            color: <?php echo e($confcli->forecolor); ?>;
                            vertical-align:middle;"
                            title="Dias de Inventario del producto">
                            DIAS
                        </th>
                        <th style="background-color: <?php echo e($confcli->backcolor); ?>;
                            color: <?php echo e($confcli->forecolor); ?>;
                            vertical-align:middle;"
                            title="Código del producto">
                            CODIGO
                        </th>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </thead>

                <?php if(isset($pg)): ?>
                    <?php $__currentLoopData = $pg; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php

                        $x = $x + 1;
                        $invConsolProv = iCantidadConsolidadoProv($p->barra);

                        // MEJOR PRECIO PROVEEDOR
                        $suggrp = 0.00;
                        $cosgrp = 0.00;
                        $mpp = 0.00;
                        $provs = TablaMaecliproveActiva($codcli);
                        $mejoropcion = BuscarMejorOpcion($p->barra, 'PRECIO', 'NINGUNA', 1, $provs);
                        if ($mejoropcion != null) {
                            $precioprove = $mejoropcion[0]['precio'];
                            $daprove = $mejoropcion[0]['da'];
                            $codprove = $mejoropcion[0]['codprove'];
                            $maeclieprove = DB::table('maeclieprove')
                            ->where('codcli','=',$codcli)
                            ->where('codprove','=',$codprove)
                            ->first();
                            if (empty($maeclieprove)) {
                                continue;
                            }
                            $dc = $maeclieprove->dcme;
                            $di = $maeclieprove->di;
                            $pp = $maeclieprove->ppme;
                            $mpp = CalculaPrecioNeto($precioprove, $daprove, $di, $dc, $pp);
                        }
                        ?>
                        <tr>
                            <td style="width: 10px;">
                                <center>
                                <?php echo e($loop->iteration); ?>

                                </center>
                            </td>

                            <td>
                                <div align="center">
                                    <a href="<?php echo e(URL::action('PedidoController@verprod',$p->barra)); ?>">

                                    <img src="http://isaweb.isbsistemas.com/public/storage/prod/<?php echo e(NombreImagen($p->barra)); ?>" 
                                    width="100%" height="100%" class="img-responsive" 
                                    alt="ICOMPRAS" >

                        
                                    </a>
                                </div>
                            </td>

                            <td style="width: 150px;">
                                <?php echo e($p->desprod); ?>

                            </td>

                            <td style="width: 100px;"
                                title="CODIGO DE BARRA">
                                <?php echo e($p->barra); ?>

                            </td>

                            <td align="right" 
                                style="width: 60px;"
                                title="UNIDAD DE MANEJO">
                                <?php echo e($p->bulto); ?>

                            </td>

                            <td align="right"
                                title="IVA">
                                <?php echo e(number_format($p->iva, 2, '.', ',')); ?>

                            </td>
                
                            <!-- PROVEEDOR-->
                            <td align="right" 
                                style="background-color: #FEE3CB;"
                                title="MEJOR PRECIO DEL PROVEEDOR">
                                <?php echo e(number_format($mpp/$factor, 2, '.', ',')); ?>

                            </td>
                            <td align="right" 
                                style="background-color: #FEE3CB;"
                                title="CONSOLIDADO DEL PROVEEDOR">
                                <?php echo e(number_format($invConsolProv, 0, '.', ',')); ?>

                            </td>

                            <!-- GRUPO -->
                            <tH style="text-align: right;
                                background-color: #FCD5B8;"
                                title="TOTAL SUGERIDO DEL PRODUCTO X GRUPO"
                                id='suggrp_<?php echo e($x); ?>'>
                            </tH>
                            <tH style="text-align: right;
                                background-color: #FCD5B8;"
                                title="TOTAL COSTO DEL PRODUCTO X GRUPO"
                                id='cosgrp_<?php echo e($x); ?>'>
                            </tH>

                            <?php $__currentLoopData = $gruporen; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                $codsuc = strtolower($gr->codcli);
                                $actualizado = date('d-m-Y H:i:s', strtotime(LeerTablaFirst('inventario_'.$codsuc, 'feccatalogo')));
                                $confcli = LeerCliente($codsuc); 
                                $cantidad = 0;
                                $precio = 0;
                                $subtotal = 0;
                                $codprod = "N/A";
                                $vmd = 0.000;
                                $cant = 0;
                                $dias = 0;
                                $pedx = DB::table('pedido')
                                ->where('idpedgrupo','=',$id)
                                ->where('codcli','=',$codsuc)
                                ->first();
                                if ($pedx) {
                                    $prx = DB::table('pedren')
                                    ->where('id','=',$pedx->id)
                                    ->where('barra','=',$p->barra)
                                    ->first();
                                    if ($prx) {
                                        $cantidad = $prx->cantidad;
                                        $precio = $prx->precio;
                                        $codprod = $prx->codprod;
                                        $subtotal = $prx->subtotal;
                                    }
                                }
                                $transito = verificarProdTransito($p->barra,  $codsuc, "");
                                $inv = verificarProdInventario($p->barra,  $codsuc);
                                if (!is_null($inv)) {
                                    $codprod = $inv->codprod; 
                                    $vmd = $inv->vmd;
                                    $cant = $inv->cantidad;
                                    if ($vmd > 0)
                                        $dias = $cant/$vmd;
                                }
                                ?>

                                <!-- DETALLES -->
                                <td align="right" 
                                    style="background-color: <?php echo e($confcli->backcolor); ?>;
                                    color: <?php echo e($confcli->forecolor); ?>;"
                                    title="Cantidad sugerida">
                                    <span class="input-group-addon" 
                                        style="margin: 0px; 
                                        width: 140px;">
                                        <div class="col-xs-12 
                                            input-group
                                            input-group-sm" 
                                            style="margin: 0px; 
                                            width: 140px;">
                                            
                                            <input style="text-align: center; 
                                                color: #000000; 
                                                width: 60px;" 
                                                id="sug_<?php echo e($codsuc); ?>_<?php echo e($p->barra); ?>" 
                                                value="<?php echo e(number_format($cantidad, 0, '.', ',')); ?>" 
                                                class="form-control" >

                                            <button type="button" 
                                                class="btn btn-pedido BtnModificar"
                                                id="idModificar_<?php echo e($codsuc); ?>_<?php echo e($p->barra); ?>_<?php echo e($x); ?>" 
                                                data-toggle="tooltip" 
                                                title="Modificar cantidad">
                                                
                                                <span 
                                                    class="fa fa-check" 
                                                    id="idModificar_<?php echo e($codsuc); ?>_<?php echo e($p->barra); ?>_<?php echo e($x); ?>" aria-hidden="true" >
                                                </span>
                                                
                                                <a href="" 
                                                    data-target="#modal_delete_<?php echo e($codsuc); ?>_<?php echo e($p->barra); ?>" data-toggle="modal">
                                                    <button
                                                    class="btn btn-pedido fa fa-trash-o" 
                                                    style="height: 2pc;" 
                                                    data-toggle="tooltip" 
                                                    title="Eliminar producto">
                                                    </button>
                                                </a>
                                            </button>
                                        </div>
                                    </span>
                                    <span id="sug_<?php echo e($codsuc); ?>_<?php echo e($x); ?>"
                                        style="color: <?php echo e($confcli->backcolor); ?>;">
                                        <?php echo e(number_format($cantidad, 0, '.', ',')); ?>

                                    </span>
                                </td>

                                <!-- COSTO -->
                                <td align="right" 
                                    style="background-color: <?php echo e($confcli->backcolor); ?>;
                                    color: <?php echo e($confcli->forecolor); ?>;"
                                    title="Costo del producto"
                                    id='cos_<?php echo e($codsuc); ?>_<?php echo e($x); ?>'>
                                    <?php echo e(number_format($precio/$factor, 2, '.', ',')); ?>

                                </td>
                                <!-- SUBTOTAL -->
                                <td align="right" 
                                    style="background-color: <?php echo e($confcli->backcolor); ?>;
                                    color: <?php echo e($confcli->forecolor); ?>;"
                                    title="Subtotal Costo x producto"
                                    id='tot_<?php echo e($codsuc); ?>_<?php echo e($x); ?>'>
                                    <?php echo e(number_format($subtotal/$factor, 2, '.', ',')); ?>

                                </td>

                                <!-- TRANSITO -->
                                <td align="right" 
                                    style="background-color: <?php echo e($confcli->backcolor); ?>;
                                    color: <?php echo e($confcli->forecolor); ?>;"
                                    title="Inventario en transito del producto"
                                    id='tran_<?php echo e($codsuc); ?>_<?php echo e($x); ?>'>
                                    <?php echo e(number_format($transito, 0, '.', ',')); ?> 
                                </td>
                                <!-- INVENTARIO -->
                                <td align="right"  
                                    style="background-color: <?php echo e($confcli->backcolor); ?>;
                                    color: <?php echo e($confcli->forecolor); ?>;"
                                    title="Inventario del producto"
                                    id='inv_<?php echo e($codsuc); ?>_<?php echo e($x); ?>'>
                                    <?php echo e(number_format($cant, 0, '.', ',')); ?>

                                </td>
                                <!-- VMD -->
                                <td align="right" 
                                    style="background-color: <?php echo e($confcli->backcolor); ?>;
                                    color: <?php echo e($confcli->forecolor); ?>;"
                                    title="Venta Media Diaria del producto"
                                    id='vmd_<?php echo e($codsuc); ?>_<?php echo e($x); ?>' >
                                    <?php echo e(number_format($vmd, 4, '.', ',')); ?>

                                </td>

                                <!-- DIAS -->
                                <td align="right"
                                    style="background-color: <?php echo e($confcli->backcolor); ?>;
                                    color: <?php echo e($confcli->forecolor); ?>;"
                                    title="Dias de inventario del producto"
                                    id='dias_<?php echo e($codsuc); ?>_<?php echo e($x); ?>'>
                                    <?php echo e(number_format($dias, 0, '.', ',')); ?> 
                                </td>

                                <!-- CODPROD -->
                                <td style="background-color: <?php echo e($confcli->backcolor); ?>;
                                    color: <?php echo e($confcli->forecolor); ?>;"
                                    title="Código del producto"
                                    id='codprod_<?php echo e($codsuc); ?>_<?php echo e($x); ?>'>
                                    <?php echo e($codprod); ?>

                                </td>
                                <?php echo $__env->make('isacom.pedgrupo.deleprod', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <thead>
                        <th colspan="8">
                            TOTALES DEL GRUPO
                        </th>

                        <!-- GRUPO-->
                        <th style="text-align: right;
                            background-color: #FCD5B8;"
                            title="Cantidad total sugerida del grupo"
                            id='suggrp' >
                        </th>
                        <th style="text-align: right;
                            background-color: #FCD5B8;"
                            title="Monto del Costo del grupo"
                            id='cosgrp'>
                        </th>
                       
                        <?php $__currentLoopData = $gruporen; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <?php 
                            $codsuc = $gr->codcli;
                            $confcli = LeerCliente($codsuc); 
                            ?>

                            <!-- DETALLES -->
                            <th style="background-color: <?php echo e($confcli->backcolor); ?>;
                                color: <?php echo e($confcli->forecolor); ?>;
                                text-align: right;"
                                title="Cantidad total sugerida por cliente "
                                id='sug_<?php echo e($codsuc); ?>' >
                            </th>

                            <th colspan="7"
                                style="background-color: <?php echo e($confcli->backcolor); ?>;
                                color: <?php echo e($confcli->forecolor); ?>;
                                text-align: right;"
                                title="Monto del Costo por cliente"
                                id='cos_<?php echo e($codsuc); ?>'>
                            </th>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </thead>
                    <thead style="background-color: #b7b7b7;">
                        <th colspan="6">
                            <center>PRODUCTOS</center>
                        </th>

                        <th colspan="2"
                            style="width: 200; 
                            background-color: #FEE3CB;
                            color: black;">
                            <center>
                            &nbsp;&nbsp;&nbsp;&nbsp;PROVEEDOR&nbsp;&nbsp;&nbsp;&nbsp;
                            </center>
                        </th>
                        
                        <th colspan="2"
                            style="width: 200; 
                            background-color: #FCD5B8;
                            color: black;">
                            <center>
                            &nbsp;&nbsp;&nbsp;&nbsp;GRUPO&nbsp;&nbsp;&nbsp;&nbsp;
                            </center>
                        </th>

                        <?php $__currentLoopData = $gruporen; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <?php 
                            $codsuc = $gr->codcli;
                            $confcli = LeerCliente($codsuc); 
                            $actualizado = date('d-m-Y H:i:s', strtotime(LeerTablaFirst('inventario_'.$codsuc, 'feccatalogo')));
                            $fechaHoy = trim(date("Y-m-d"));
                            $fechaInv = trim(date('Y-m-d', strtotime($actualizado)));
                            $idped = '';
                            $pedido = ObtenerPedidoGrupo($id,$codsuc);
                            if (!empty($pedido)) 
                                $idped = '('.$pedido->id .'-'. $pedido->estado.')';
                            ?>
                            
                            <th colspan="8" 
                                style="background-color: <?php echo e($confcli->backcolor); ?>; 
                                color: <?php echo e($confcli->forecolor); ?>; 
                                width: 400px; 
                                word-wrap: break-word; ">
                                <a href="<?php echo e(URL::action('GrupoController@show',$codsuc)); ?>">
                                    <center>
                                        <button type="button" 
                                            data-toggle="tooltip" 
                                            title="<?php echo e($confcli->nombre); ?> &#10 (<?php echo e($actualizado); ?>)" 
                                            style="background-color: <?php echo e($confcli->backcolor); ?>; 
                                            border: none;
                                            <?php if($fechaInv != $fechaHoy): ?>
                                                color: red;
                                            <?php else: ?>
                                                color: <?php echo e($confcli->forecolor); ?>;
                                            <?php endif; ?> 
                                            "> <?php echo e($confcli->descripcion); ?> <?php echo e($idped); ?>

                                        </button>
                                    </center>
                                </a>
                            </th>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </thead>
                <?php endif; ?>
            </table>

            <?php if($moneda == "USD"): ?>
            <h4>
             *** <?php echo e($cfg->simboloOM); ?> <?php echo e(number_format($factor, 2, '.', ',')); ?> ***
            </h4>  
            <?php endif; ?>

            <div align='left'>
                <?php if(isset($invent)): ?>
                    <?php echo e($invent->appends(["filtro" => $filtro])->links()); ?>

                <?php endif; ?>
            </div><br>
        </div>
    </div> 
</div>

<?php $__env->startPush('scripts'); ?>
<script>
window.onload = function() {
    $('#titulo').text('<?php echo e($grupo->nomgrupo); ?>');
    $('#subtitulo').text('<?php echo e($subtitulo); ?>');
    Refrescar();
}

$('.BtnModificar').on('click',function(e) {
    var s1 = e.target.id.split('_');
    var codsuc = s1[1];
    var barra = s1[2];
    var x = s1[3];
    var pedir = $('#sug_' + codsuc + '_' + barra).val();
    var idpgrp = '<?php echo e($id); ?>';
    var codgrupo = '<?php echo e($codgrupo); ?>';
    var reg;
    //alert('PEDIR: ' + pedir + ' BARRA: ' + barra + ' CODSUC: ' + codsuc + ' IDGRP: ' + idpgrp + ' CODGRP: ' + codgrupo);
    var resp = 0;
    var jqxhr = $.ajax({
        type:'POST',
        url:'../modificar',
        dataType: 'json', 
        encode  : true,
        data: {barra:barra, pedir:pedir, codsuc:codsuc, idpgrp:idpgrp, codgrupo:codgrupo },
        success:function(data) {
            resp = data.msg;
            //reg = data.reg; 
            $('#sug_' + codsuc + '_' + x).text(pedir);
            $('#cos_' + codsuc + '_' + x).text(data.reg.precio);                  
            $('#tot_' + codsuc + '_' + x).text(data.reg.subtotal);
            $('#codprod_' + codsuc + '_' + x).text(data.reg.codprod);

            $('#inv_' + codsuc + '_' + x).text(data.inv);
            $('#vmd_' + codsuc + '_' + x).text(data.vmd);
            $('#dias_' + codsuc + '_' + x).text(data.dias);
            $('#tran_' + codsuc + '_' + x).text(data.tran);
    
        }
    });
    jqxhr.always(function() {
        if (resp == 1) {
            window.location.reload();
        } else {
            Refrescar();
        } 
    });
});

function Refrescar() {
    var cont = '<?php echo e($gruporen->count()); ?>';
    var codgrupo = '<?php echo e($codgrupo); ?>';
    var resp = "";
    var tableReg = document.getElementById('myTable');
    var jqxhr = $.ajax({
        type:'POST',
        url: '../obtenerCodcliGrupo',
        dataType: 'json', 
        encode  : true,
        data: {codgrupo:codgrupo },
        success:function(data) {
            resp = data.resp;
        }
    });
    jqxhr.always(function() {
        var arrCodsuc = resp.split('|');
        for (var z = 0; z < cont; z++) {
            var codsuc = arrCodsuc[z];
            var suger = 0;
            var costo = 0;   
            for (var i = 1; i < tableReg.rows.length-2; i++) {
                var sugx = $('#sug_' + codsuc + "_" + i).text().replace(/,/g, '');
                var totx = $('#tot_' + codsuc + "_" + i).text().replace(/,/g, '');
                costo += parseFloat(totx);
                suger += parseFloat(sugx);
            }
            $("#cos_"+codsuc).text(number_format(costo, 2, '.', ','));
            $("#sug_"+codsuc).text(number_format(suger, 0, '.', ','));
        }
        for (var i = 1; i < tableReg.rows.length-2; i++) {
            var suger = 0;
            var costo = 0;   
            var codsuc = "";
            for (var z = 0; z < cont; z++) {
                var codsuc = arrCodsuc[z];
                var sugx = $('#sug_' + codsuc + "_" + i).text().replace(/,/g, '');
                var totx = $('#tot_' + codsuc + "_" + i).text().replace(/,/g, '');
                costo += parseFloat(totx);
                suger += parseFloat(sugx);
            }  
            $("#cosgrp_"+i).text(number_format(costo, 2, '.', ','));
            $("#suggrp_"+i).text(number_format(suger, 0, '.', ','));
        }
        suger = 0;
        costo = 0;
        for (var i = 1; i < tableReg.rows.length-2; i++) {
            var sugx = $('#suggrp_' + i).text().replace(/,/g, '');
            var totx = $('#cosgrp_' + i).text().replace(/,/g, '');
            costo += parseFloat(totx);
            suger += parseFloat(sugx);
        }
        $("#cosgrp").text(number_format(costo, 2, '.', ','));
        $("#suggrp").text(number_format(suger, 0, '.', ','));
    });
}

function ejecutarAgregar() {
    var id = '<?php echo e($id); ?>';
    var codgrupo = '<?php echo e($codgrupo); ?>';
    var cant = $('#idcant').val();
    var codsuc = $('#idcodsuc').val();
    var barra = $('#idbarra').val();
    var ctipo = id +"_"+ codgrupo +"_"+ cant +"_"+ codsuc +"_"+ barra;
    //alert(ctipo);
    if (cant == 0 || barra == '') {
        alert("FALTAN PARAMETROS PARA AGREGAR UN PRODUCTO");
    } else {
        var url = "<?php echo e(url('/pedgrupo/agregar/prod/X')); ?>";
        url = url.replace('X', ctipo);
        window.location.href=url;
    }
}

function cargarProd() {
    var codgrupo = '<?php echo e($codgrupo); ?>';
    var resp;
    var filtro = $('#idfiltro').val();
    if (filtro == "") {
        alert("FALTAN PARAMETROS PARA REALIZAR LAS BUSQUEDA");
    } else {
        var jqxhr = $.ajax({
            type:'POST',
            url: '../obtenerTablaCliMaestra',
            dataType: 'json', 
            encode  : true,
            data: { codgrupo:codgrupo, filtro:filtro },
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

function tdclick(e) {
    var id = e.target.id.split('_');
    var barra = id[1];
    $(".case").prop("checked", false);
    $("#idcheck_" + barra).prop("checked", true);
    $('#idbarra').val(barra);
}
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/isacom/pedgrupo/edit.blade.php ENDPATH**/ ?>