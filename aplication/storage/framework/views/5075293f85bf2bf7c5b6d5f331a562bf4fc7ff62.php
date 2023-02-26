
<?php $__env->startSection('contenido'); ?>
 
<?php
  $moneda = Session::get('moneda', 'BSS');
  $factorInv = RetornaFactorCambiario('', $moneda);
  $rutalogoprov = 'http://isaweb.isbsistemas.com/public/storage/prov/';
?>  

<!-- ENCABEZADO -->
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="form-group">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 input-group input-group-sm">
                
                <span class="input-group-addon">Pedido:</span>
                <B><input readonly 
                    type="text" 
                    class="form-control" 
                    value="<?php echo e($tabla->id); ?> - <?php echo e($tabla->tipedido); ?>" 
                    style="color: #000000"></B>

                <span class="input-group-addon hidden-xs" style="border:0px; "></span>
                <span class="input-group-addon hidden-xs">Estado:</span>
                <input readonly 
                    type="text" 
                    class="form-control hidden-xs" 
                    value="<?php echo e($tabla->estado); ?> | <?php echo e($tabla->origen); ?>" 
                    style="color: #000000">

                <span class="input-group-addon hidden-xs" style="border:0px; "></span>
                <span class="input-group-addon hidden-xs">Fecha:</span>
                <input readonly type="text" class="hidden-xs form-control" value="<?php echo e(date('d-m-Y H:i', strtotime($tabla->fecha))); ?>" style="color: #000000">

                <span class="input-group-addon hidden-xs" style="border:0px; "></span>
                <span class="input-group-addon hidden-xs">Enviado:</span>
                <input readonly type="text" class="form-control hidden-xs" value="<?php echo e(date('d-m-Y H:i', strtotime($tabla->fecenviado))); ?>" style="color:#000000" >    

                <span class="input-group-addon" style="border:0px; "></span>
                <span class="input-group-addon">Ahorro:</span>
                <input readonly 
                    type="text" 
                    class="form-control" 
                    value="<?php echo e(number_format($tabla->ahorro/$factorInv, 2, '.', ',')); ?>" 
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
                    value="<?php echo e(number_format($tabla->descuento/$factorInv, 2, '.', ',')); ?>" 
                    style="color: #000000; text-align: right;" 
                    id="idDescuento">

                <span class="input-group-addon" style="border:0px; "></span>
                <span class="input-group-addon">Subtotal:</span>
                <input readonly 
                    type="text" 
                    class="form-control" 
                    value="<?php echo e(number_format($tabla->subtotal/$factorInv, 2, '.', ',')); ?>" 
                    style="color: #000000; text-align: right;" 
                    id="idSubtotal">

                <span class="input-group-addon" style="border:0px; "></span>
                <span class="input-group-addon">Impuesto:</span>
                <input readonly 
                    type="text" 
                    class="form-control" 
                    value="<?php echo e(number_format($tabla->impuesto/$factorInv, 2, '.', ',')); ?>" 
                    style="color: #000000; text-align: right;" 
                    id="idImpuesto">

                <span class="input-group-addon" style="border:0px; "></span>
                <span class="input-group-addon">Total:</span>
                <b><input readonly 
                    type="text" 
                    class="form-control" 
                    value="<?php echo e(number_format($tabla->total/$factorInv, 2, '.', ',')); ?>" 
                    style="color: #000000; text-align: right;" 
                    id="idTotal"></b>
            </div>
        </div>
    </div>
</div>

<?php if($contItem > 0): ?>
<!-- BOTONES PROVEEDORES-->
<div class="input-group mb-3">
    <div class="input-group-prepend" id="button-addon3">
        <?php $__currentLoopData = $arrayProv; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prov): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if( $prov == "MAESTRO"): ?>
                <a href="<?php echo e(url('/pedido/'.$tabla->id.'-MAESTRO/edit')); ?>">
                    <button style="width: 153px; 
                        height: 32px; 
                        color: #000000; 
                        border: #b7b7b7; 
                        background-color: #b7b7b7;
                        border-radius: 15px;"  
                        class="btn btn-outline-secondary" 
                        type="button" 
                        data-toggle="tooltip" 
                        title="Ver pedido maestro">
                        <?php if($tpactivo==$prov): ?> 
                            <i class="fa fa-check"></i>
                            <b>MAESTRO</b>
                        <?php else: ?>
                            <img style="width: 20px; height: 20px;" 
                            src="<?php echo e($rutalogoprov.'icompras360.png'); ?>" 
                            alt="icompras360">
                            &nbsp;
                            MAESTRO
                        <?php endif; ?>
                    </button>
                </a>
            <?php else: ?>
                <?php 
                    $confprov = LeerProve($prov); 
                    if (is_null($confprov))
                        continue;
                ?>
                <a href="<?php echo e(url('/pedido/'.$tabla->id.'-'.$confprov->codprove.'/edit')); ?>">
                    <button style="width: 153px; 
                        height: 32px; 
                        color:<?php echo e($confprov->forecolor); ?>; 
                        border: <?php echo e($confprov->backcolor); ?>;  
                        background-color: <?php echo e($confprov->backcolor); ?>;
                        border-radius: 15px;" 
                        class="btn btn-outline-secondary" 
                        type="button" 
                        data-toggle="tooltip" 
                        title="Ver pedido por proveedor">
                        <?php if($tpactivo==$prov): ?> 
                            <i class="fa fa-check"></i>
                            <b><?php echo e($confprov->descripcion); ?></b>
                        <?php else: ?>
                            <img style="width: 20px; height: 20px;" 
                            src="<?php echo e($rutalogoprov.$confprov->rutalogo1); ?>" alt="icompras360">
                            &nbsp;
                            <?php echo e($confprov->descripcion); ?>

                        <?php endif; ?>
                    </button>
                </a>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php endif; ?>

<!-- BOTONES BUSCAR, CATALOGO, ENTTRADAS, OFERTAS, MOLECULA -->
<div class="btn-toolbar" role="toolbar" style="margin-top: 12px; margin-bottom: 3px;">
    <div class="btn-group" role="group" style="width: 100%;">
        <!-- BOTON BUSCAR/CATALOGO/ENVIAR/ELIMINAR -->
        <?php echo $__env->make('isacom.pedido.catasearch', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <!-- VER CATALOGO -->
        <a href="<?php echo e(URL::action('PedidoController@catalogo','C')); ?>">
            <button style="width: 90px; height: 34px; border-radius: 5px;"
                type="button" 
                data-toggle="tooltip" 
                title="Ver catálogo de productos con entradas recientes" 
                class="btn-catalogo">
                Catálogo
            </button>
        </a>
        <!-- VER ENTRADAS -->
        <a href="<?php echo e(URL::action('PedidoController@catalogo','E')); ?>">
            <button style="width: 90px; height: 34px; border-radius: 5px;" 
                type="button" 
                data-toggle="tooltip" 
                title="Ver catálogo de productos con entradas recientes" 
                class="btn-catalogo">
                Entradas
            </button>
        </a>
        <!-- VER OFERTAS -->
        <a href="<?php echo e(URL::action('PedidoController@catalogo','O')); ?>">
            <button style="width: 90px; height: 34px; border-radius: 5px;" 
                type="button" 
                data-toggle="tooltip" 
                title="Ver catálogo de ofertas de productos" 
                class="btn-catalogo">
                Ofertas
            </button>
        </a>
        <?php if(Auth::user()->botonMolecula == 1): ?>
            <!-- MOLECULAS -->
            <a href="<?php echo e(URL::action('PedidoController@catalogo','M')); ?>">
                <button style="width: 90px; height: 34px; border-radius: 5px;" 
                    type="button" 
                    data-toggle="tooltip" 
                    title="Ver catálogo por moleculas" 
                    class="btn-catalogo">
                    Moleculas
                </button>
            </a>
        <?php endif; ?>

        <?php if(Auth::user()->botonEnvio == 1): ?>
            <?php if($contItem > 0): ?>
            <!-- ENVIA PEDIDO -->
            <a href="" 
                data-target="#modal-enviar-<?php echo e($tabla->id); ?>" 
                data-toggle="modal">
                <button style="width: 90px; height: 34px; border-radius: 5px;" 
                    type="button" 
                    data-toggle="tooltip" 
                    title="Enviar pedido" 
                    class="btn-confirmar">
                    Enviar
                </button>
            </a>
            <?php echo $__env->make('isacom.pedido.enviar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>  
            <?php endif; ?>
        <?php endif; ?>
       
    </div>
</div>

<!-- TABLA -->
<div class="row" style="margin-top: 5px;">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="table-responsive">
            <table id="myTable" 
                class="table table-striped table-bordered table-condensed table-hover">
                <thead style="background-color: #b7b7b7;">
                    <th style="vertical-align:middle;">#</th>
                    <th style="width: 100px; vertical-align:middle;">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;IMAGEN&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </th>
                    <th style="width: 170px; vertical-align:middle;">PEDIR</th>
                    <th>
                        <center>
                        <div style="width: 70px;">
                            <span class="input-group-btn">
                                <div class="col-xs-12 input-group" >
                                    <input type="checkbox" 
                                        id="selectall"
                                        title="marcar/desmarcar todos los producto"
                                        style="width: 50%; vertical-align:middle;">

                                    <a href="" 
                                        data-target="#modal-deleteAll" 
                                        data-toggle="modal">
                                    <button style="vertical-align:middle; 
                                        text-align: center;
                                        width: 50%; 
                                        height: 30px;" 
                                        type="button" 
                                        class="BtnAgregar btn btn-pedido" 
                                        title="Eliminar producto marcados" >
                                        <span class="fa fa-trash-o" >
                                        </span>
                                    </button>
                                    </a>
                                </div>
                            </span>
                        </div> 
                        </center>
                    </th>
                    <th style="vertical-align:middle;">
                        PRODUCTO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </th>
                    <?php if(Auth::user()->versionLight == 0): ?>
                        <th title="Unidades en Transito del producto" 
                            style="background-color: #b7b7b7;
                                   vertical-align:middle;">
                            TRAN.
                        </th>
                        <th title="Unidades del inventario del producto"
                            style="background-color: #b7b7b7;
                                   vertical-align:middle;">
                            INV.
                        </th>
                        <th title="Costo del producto"
                            style="background-color: #b7b7b7;
                                   vertical-align:middle;">
                            COSTO
                        </th>
                        <th title="Venta Media Diaria"
                            style="background-color: #b7b7b7;
                                   vertical-align:middle;">
                            VMD
                        </th>
                    <?php else: ?>
                        <th style="display:none;">TRAN.</th>
                        <th style="display:none;">INV.</th>
                        <th style="display:none;">COSTO</th>
                        <th style="display:none;">VMD</th>
                    <?php endif; ?>

                    <th style="display:none; vertical-align:middle;"
                        title="Código del producto del proveedor">
                        CODIGO
                    </th>
                    <th style="vertical-align:middle;"
                        title="Código del Proveedor">
                        PROVEEDOR&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </th>
                    <th style="vertical-align:middle;"
                        title="Precio del producto">
                        PRECIO
                    </th>
                    <th style="vertical-align:middle;"
                        title="Impuesto al valor agregado">
                        IVA
                    </th>
                    <th style="vertical-align:middle;"
                        title="Descuento adicional del producto">
                        DA
                    </th>
                    <th style="vertical-align:middle;"
                        title="Descuento pre-empaque del producto">
                        DP
                    </th>
                    <th style="vertical-align:middle;"
                        title="Descuento internet">
                        DI
                    </th>
                    <th style="vertical-align:middle;"
                        title="Descuento comercial">
                        DC
                    </th>
                    <th style="vertical-align:middle;"
                        title="Descuento pronto pago">
                        PP
                    </th>
                    <th style="vertical-align:middle;"
                        title="Precio neto">
                        NETO
                    </th>
                    <th style="vertical-align:middle;"
                        title="Subtoatl del producto">
                        SUBTOTAL
                    </th>
                    <th style="display: none;">ITEM</th>
                    <th style="vertical-align:middle;"
                        title="Monto del ahorro">
                        AHORRO
                    </th>
                </thead>
                <?php $__currentLoopData = $tabla2; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($t->codprove == $tpactivo || $tpactivo == "MAESTRO" ): ?>
                        <?php
                        $costo = 0;
                        $vmd = 0;
                        $cant = 0;
                        $confprov = LeerProve($t->codprove); 
                        $transito = verificarProdTransito($t->barra,  "", "");
                        $invent = verificarProdInventario($t->barra,  "");
                        if (!is_null($invent)) {
                            $costo = $invent->costo/$factorInv;
                            $vmd = $invent->vmd;
                            $cant = $invent->cantidad;
                        }
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
                            
                            <td>
                                <span class="input-group-addon" 
                                    style="margin: 0px; width: 140px;">
                                    <div class="col-xs-12 input-group input-group-sm" 
                                        style="margin: 0px; width: 140px;">
                                        <input type="number" 
                                            style="text-align: center; color: #000000; width: 60px;" 
                                            id="idPedir-<?php echo e($t->item); ?>" 
                                            value="<?php echo e(number_format($t->cantidad, 0, '.', ',')); ?>"
                                            class="form-control"  
                                            <?php if($t->estado == "RECIBIDO"): ?>
                                                readonly
                                            <?php endif; ?> 
                                        >
                                        <button 

                                        <?php if($t->estado == "RECIBIDO"): ?> disabled <?php endif; ?>

                                        type="button" class="btn btn-pedido BtnModificar" id="idModificar-<?php echo e($t->item); ?>" data-toggle="tooltip" title="Modificar cantidad">
                                            <span 

                                                <?php if($t->estado == "RECIBIDO"): ?> style="color: #000000;" <?php endif; ?>

                                                class="fa fa-check" id="idModificar-<?php echo e($t->item); ?>" aria-hidden="true" >
                                            </span>
                                            <a href="" data-target="#modal-delete-<?php echo e($t->item); ?>"    data-toggle="modal">
                                                <button

                                                    <?php if($t->estado == "RECIBIDO"): ?> 
                                                        disabled style="color: #000000;"
                                                    <?php endif; ?>

                                                    class="btn btn-pedido fa fa-trash-o"  data-toggle="tooltip" 
                                                    title="Eliminar producto">
                                                </button>
                                            </a>
                                        </button>
                                    </div>
                                </span>
                                <div style="background-color: <?php echo e($confprov->backcolor); ?>; 
                                    color: <?php echo e($confprov->forecolor); ?>;
                                    width: 165px; 
                                    height: 35px;"
                                    title = "CODIGO DEL PROVEEDOR">
                                    <img style="width: 20px; 
                                        height: 20px;
                                        margin-left: 10px;
                                        margin-top: 6px;" 
                                        src="<?php echo e($rutalogoprov.$confprov->rutalogo1); ?>" 
                                        alt="icompras360">
                                        <span style="vertical-align:middle;">
                                        &nbsp;<?php echo e($t->codprove); ?><br>
                                        </span>
                                </div>
                            </td>

                            <td style="padding-top: 10px;  
                                text-align: center;
                                vertical-align: middle;">
                                    <input name='destino[]' 
                                        class="case" 
                                        type="checkbox" 
                                        onclick='tdclick(event)'
                                        id='checkbox_<?php echo e($t->item); ?>' 
                                        value = "<?php echo e($t->marcado); ?>"
                                        <?php if($t->marcado == 1): ?> checked <?php endif; ?> />
                            </td> 

                            <td>
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

                            <?php if(Auth::user()->versionLight == 0): ?>
                                <td align="right"
                                    title = "TRANSITO"
                                    style="background-color: #FEE3CB;" >
                                    <?php echo e(number_format($transito, 0, '.', ',')); ?>

                                </td>
                       
                                <td align="right"
                                    style="background-color: #FEE3CB;"
                                    title = "INVENTARIO">
                                    <?php echo e(number_format($cant, 0, '.', ',')); ?>    
                                </td>
                                <td align="right"
                                    style="background-color: #FEE3CB;"
                                    title = "COSTO">
                                    <?php echo e(number_format($costo, 2, '.', ',')); ?>

                                </td>
                                <td align="right"
                                    style="background-color: #FEE3CB;"
                                    title = "VMD">
                                    <?php echo e(number_format($vmd, 4, '.', ',')); ?>

                                </td>
                            <?php else: ?>
                                <td style="display:none;">TRAN.</td>
                                <td style="display:none;">INV.</td>
                                <td style="display:none;">COSTO</td>
                                <td style="display:none;">VMD</td>
                            <?php endif; ?>

                            <td style="display:none; 
                                background-color: <?php echo e($confprov->backcolor); ?>; 
                                color: <?php echo e($confprov->forecolor); ?>"
                                title = "CODIGO DEL PRODUCTO">
                                <?php echo e($t->codprod); ?>

                            </td>

                            <td style="background-color: <?php echo e($confprov->backcolor); ?>; 
                                color: <?php echo e($confprov->forecolor); ?>;"
                                title = "DATOS DEL PROVEEDOR">

                                <span title="CODIGO DE BARRA">
                                    <i class="fa fa-barcode">
                                        <?php echo e($t->barra); ?>

                                    </i>
                                </span><br>
                              
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
                           
                            <td style="background-color: <?php echo e($confprov->backcolor); ?>; 
                                color: <?php echo e($confprov->forecolor); ?>;" 
                                align="right"
                                title = "PRECIO DEL PRODUCTO">
                                <?php echo e(number_format($t->precio/RetornaFactorCambiario($t->codprove, $moneda), 2, '.', ',')); ?>

                            </td>
                           
                            <td style="background-color: <?php echo e($confprov->backcolor); ?>; 
                                color: <?php echo e($confprov->forecolor); ?>;" 
                                align="right"
                                title = "IVA DEL PRODUCTO">
                                <?php echo e(number_format($t->iva, 2, '.', ',')); ?>

                            </td>
                            <?php if($t->da > 0): ?>
                                <td style="background-color: <?php echo e($confprov->backcolor); ?>; 
                                    color: <?php echo e($confprov->forecolor); ?>;" 
                                    align="right" 
                                    title = "DESCUENTO ADICIONAL">
                                    <b><?php echo e(number_format($t->da, 2, '.', ',')); ?></b>
                                </td>
                            <?php else: ?>
                                <td style="background-color: <?php echo e($confprov->backcolor); ?>; 
                                    color: <?php echo e($confprov->forecolor); ?>;" 
                                    align="right"
                                    title = "DESCUENTO ADICIONAL">
                                    <?php echo e(number_format($t->da, 2, '.', ',')); ?>

                                </td>
                            <?php endif; ?>
                            <?php if($t->dp > 0): ?>
                                <td style="background-color: <?php echo e($confprov->backcolor); ?>; 
                                    color: <?php echo e($confprov->forecolor); ?>;" 
                                    align="right" 
                                    title = "DESCUENTO PRE-EMPAQUE">
                                    <b><?php echo e(number_format($t->dp, 2, '.', ',')); ?></b>
                                </td>
                            <?php else: ?>
                                <td style="background-color: <?php echo e($confprov->backcolor); ?>; 
                                    color: <?php echo e($confprov->forecolor); ?>;" 
                                    align="right"
                                    title = "DESCUENTO PRE-EMPAQUE">
                                    <?php echo e(number_format($t->dp, 2, '.', ',')); ?>

                                </td>
                            <?php endif; ?>
                            <?php if($t->di > 0): ?>
                                <td style="background-color: <?php echo e($confprov->backcolor); ?>; 
                                    color: <?php echo e($confprov->forecolor); ?>;" 
                                    align="right"
                                    title = "DESCUENTO INTERNET">
                                    <b><?php echo e(number_format($t->di, 2, '.', ',')); ?></b>
                                </td>
                            <?php else: ?>
                                <td style="background-color: <?php echo e($confprov->backcolor); ?>; 
                                    color: <?php echo e($confprov->forecolor); ?>;" 
                                    align="right"
                                    title = "DESCUENTO INTERNET">
                                    <?php echo e(number_format($t->di, 2, '.', ',')); ?>

                                </td>
                            <?php endif; ?>
                            <?php if($t->dc > 0): ?>
                                <td style="background-color: <?php echo e($confprov->backcolor); ?>; 
                                    color: <?php echo e($confprov->forecolor); ?>;" 
                                    align="right"
                                    title = "DESCUENTO COMERCIAL">
                                    <b><?php echo e(number_format($t->dc, 2, '.', ',')); ?></b>
                                </td>
                            <?php else: ?>
                                <td style="background-color: <?php echo e($confprov->backcolor); ?>; 
                                    color: <?php echo e($confprov->forecolor); ?>;" 
                                    align="right"
                                    title = "DESCUENTO COMERCIAL">
                                    <?php echo e(number_format($t->dc, 2, '.', ',')); ?>

                                </td>
                            <?php endif; ?>
                            <?php if($t->pp > 0): ?>
                                <td style="background-color: <?php echo e($confprov->backcolor); ?>; 
                                    color: <?php echo e($confprov->forecolor); ?>;" 
                                    align="right"
                                    title = "DESCUENTO PRONTO PAGO">
                                    <b><?php echo e(number_format($t->pp, 2, '.', ',')); ?></b>
                                </td>
                            <?php else: ?>
                                <td style="background-color: <?php echo e($confprov->backcolor); ?>; 
                                    color: <?php echo e($confprov->forecolor); ?>;" 
                                    align="right"
                                    title = "DESCUENTO PRONTO PAGO">
                                    <?php echo e(number_format($t->pp, 2, '.', ',')); ?>

                                </td>
                            <?php endif; ?>
                            <td style="background-color: <?php echo e($confprov->backcolor); ?>; 
                                color: <?php echo e($confprov->forecolor); ?>;" 
                                align="right"
                                title = "PRECIO NETO">
                                <?php echo e(number_format($t->neto/RetornaFactorCambiario($t->codprove, $moneda), 2, '.', ',')); ?>

                            </td>
                            <td style="background-color: <?php echo e($confprov->backcolor); ?>; 
                                color: <?php echo e($confprov->forecolor); ?>;" 
                                align="right"
                                title = "SUBTOTAL DEL PRODUCTO">
                                <?php echo e(number_format($t->subtotal/RetornaFactorCambiario($t->codprove, $moneda), 2, '.', ',')); ?>

                            </td>
                            <td style="display: none;">
                                <?php echo e($t->item); ?>

                            </td>
                            <td style="background-color: <?php echo e($confprov->backcolor); ?>; 
                                color: <?php echo e($confprov->forecolor); ?>;" 
                                align="right"
                                title = "MONTO AHORRO DEL PRODUCTO">
                                <?php echo e(number_format(($t->ahorro*$t->cantidad)/RetornaFactorCambiario($t->codprove, $moneda), 2, '.', ',')); ?>

                            </td>
                        </tr>
                        <?php echo $__env->make('isacom.pedido.deleprod', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php echo $__env->make('isacom.pedido.deleteAll', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <tr>
                    <?php if(Auth::user()->versionLight == 1): ?>
                        <th colspan="14" >
                    <?php else: ?>
                        <th colspan="18" >
                    <?php endif; ?>
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
        </div>
    </div>
</div>

<?php if($contItem == 0): ?>
    <div class="row">
        <center><h2>Carro de compra vacio</h2></center>
        <br><br><br><br><br><br><br>
    </div>
<?php endif; ?>

<?php $__env->startPush('scripts'); ?>
<script>
$('#subtitulo').text('<?php echo e($subtitulo); ?>');
window.onload = function() {
    if ($(".case").length == $(".case:checked").length) {
        $("#selectall").prop("checked", true);
    } else {
        $("#selectall").prop("checked", false);
    }

    $('.BtnModificar').on('click',function(e) {
        var id = e.target.id.split('-');
        var item= id[1];
        var pedir = $('#idPedir-'+item).val();
        var idpedido = '<?php echo e($tabla->id); ?>';
        if (parseInt(pedir) <= 0) {
            alert("CANTIDAD A PEDIR NO PUEDE SER MENOR O IGUAL CERO");
            $('#idPedir-'+item).val(pedir);
        } else {
            $.ajax({
                type:'POST',
                url:'../modificar',
                dataType: 'json', 
                encode  : true,
                data: {item:item, pedir:pedir, idpedido:idpedido },
                success:function(data){
                    if (data.msg != "") {
                        alert(data.msg);
                        $('#idPedir-'+item).val(data.pedirOri);
                    } 
                    window.location.reload(); 
                }
            });
        }
    });
    refrescar();
}
function refrescar() {
    var tableReg = document.getElementById('myTable');
    var subtotal = 0.00;
    var ahorro = 0.00;
    for (var i = 1; i < tableReg.rows.length-1; i++) {
        cellsOfRow = tableReg.rows[i].getElementsByTagName('td');
        subtotal += parseFloat(cellsOfRow[19].innerHTML.replace(/,/g, ''));
        ahorro += parseFloat(cellsOfRow[21].innerHTML.replace(/,/g, ''));
    }
    $("#idtotped").text(number_format(subtotal, 2, '.', ','));
    $("#idahoped").text(number_format(ahorro, 2, '.', ','));
}
$("#selectall").on("click", function(e) {
    var id = e.target.id.split('_');
    var item = id[1];
    var marcar;
    if ($(".case").length == $(".case:checked").length) {
        marcar = "0";
    } else {
        marcar = "1";
    }     
    $(".case").prop("checked", this.checked);
    try {
        var jqxhr;
        var resp;
        var table = document.getElementById("myTable");
        var rows = table.getElementsByTagName('tr');
        for (var ica = 1; ica < rows.length; ica++) {
            var cols = rows[ica].getElementsByTagName('td');
            var item = cols[20].innerHTML;
            //alert(item);
            jqxhr = $.ajax({
              type:'POST',
              url:'../marcaritem',
              dataType: 'json', 
              encode  : true,
              data: {item : item, marcar : marcar },
              success:function(data) {
                resp = data.msg; 
              }
            });
        }
        jqxhr.always(function() {
            if (resp != "")
                alert(resp);
        });    
    } catch(e) {
    }
});
$(".case").on("click", function(e) {
    var id = e.target.id.split('_');
    var item = id[1];
    $.ajax({
      type:'POST',
      url:'../marcaritem',
      dataType: 'json', 
      encode  : true,
      data: {item : item, marcar : '' },
      success:function(data) {
        if (data.msg != "") {
            alert(data.msg);
        } 
      }
    });
    if ($(".case").length == $(".case:checked").length) {
        $("#selectall").prop("checked", true);
    } else {
        $("#selectall").prop("checked", false);
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/isacom/pedido/edit.blade.php ENDPATH**/ ?>