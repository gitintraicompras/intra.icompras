
<?php $__env->startSection('contenido'); ?>
<?php
$moneda = Session::get('moneda', 'BSS');
$factor = RetornaFactorCambiario("", $moneda);
$x=0;
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
                        background-color: #FCD5B8;
                        color: black;">
                        <center>
                        &nbsp;&nbsp;&nbsp;&nbsp;GRUPO&nbsp;&nbsp;&nbsp;&nbsp;
                        </center>
                    </th>

                    <?php $__currentLoopData = $gruporen; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <?php 
                        $idped = '';
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
                        
                        <th colspan="4" 
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
                                        color: <?php echo e($confcli->forecolor); ?>;
                                        "> <?php echo e($confcli->descripcion); ?> <?php echo e($idped); ?>

                                    </button>
                                </center>
                            </a>
                        </th>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </thead>
                <thead style="background-color: #b7b7b7;">
                    <th style="width: 10px;">
                        <center>#</center>
                    </th>
                    <th style="width: 120px;">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;IMAGEN&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </th>
                    <th style="width: 150px;"
                        title="Descripción del producto">
                        PRODUCTO
                    </th>
                    <th style="width: 100px;">
                        REFERENCIA
                    </th>
                    <th style="width: 60px;">BULTO</th>
                    <th style="width: 60px;">IVA</th>

                    <!-- GRUPO -->
                    <th style="background-color: #FCD5B8;
                        color: black;">
                        SUGERIDO
                    </th>
                    <th style="background-color: #FCD5B8;
                        color: black;">
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
                            color: <?php echo e($confcli->forecolor); ?>;"
                            title="Cantidad sugerida">
                            SUGERIDO
                        </th>
                        <th style="background-color: <?php echo e($confcli->backcolor); ?>;
                            color: <?php echo e($confcli->forecolor); ?>;"
                            title="Costo del producto">
                            COSTO
                        </th>
                        <th style="background-color: <?php echo e($confcli->backcolor); ?>;
                            color: <?php echo e($confcli->forecolor); ?>;"
                            title="Subtotal costo x producto">
                            SUBTOTAL
                        </th>
                        <th style="background-color: <?php echo e($confcli->backcolor); ?>;
                            color: <?php echo e($confcli->forecolor); ?>;"
                            title="Código del producto">
                            CODIGO
                        </th>
                        
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </thead>
                <?php if(isset($pg)): ?>
                    <?php $__currentLoopData = $pg; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                        $x = $x + 1;
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
                                ?>

                                <?php echo e(info($p->barra)); ?>

                                <?php echo e(info($cantidad)); ?>

                                <?php echo e(info($confcli->descripcion)); ?>

                         
                                <!-- DETALLES -->
                                <td align="right" 
                                    style="background-color: <?php echo e($confcli->backcolor); ?>;
                                    color: <?php echo e($confcli->forecolor); ?>;"
                                    title="Cantidad sugerida"
                                    id='sug_<?php echo e($codsuc); ?>_<?php echo e($x); ?>'>
                                    <?php echo e(number_format($cantidad, 0, '.', ',')); ?>

                                </td>
                                <td align="right" 
                                    style="background-color: <?php echo e($confcli->backcolor); ?>;
                                    color: <?php echo e($confcli->forecolor); ?>;"
                                    title="Costo del producto"
                                    id='cos_<?php echo e($codsuc); ?>_<?php echo e($x); ?>'>
                                    <?php echo e(number_format($precio/$factor, 2, '.', ',')); ?>

                                </td>
                                <td align="right" 
                                    style="background-color: <?php echo e($confcli->backcolor); ?>;
                                    color: <?php echo e($confcli->forecolor); ?>;"
                                    title="Subtotal Costo x producto"
                                    id='tot_<?php echo e($codsuc); ?>_<?php echo e($x); ?>'>
                                    <?php echo e(number_format($subtotal/$factor, 2, '.', ',')); ?>

                                </td>
                                <td style="background-color: <?php echo e($confcli->backcolor); ?>;
                                    color: <?php echo e($confcli->forecolor); ?>;"
                                    title="Código del producto">
                                    <?php echo e($codprod); ?>

                                </td>
                                 
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <thead>
                        <th colspan="6">
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

                            <th colspan="3"
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
                            background-color: #FCD5B8;
                            color: black;">
                            <center>
                            &nbsp;&nbsp;&nbsp;&nbsp;GRUPO&nbsp;&nbsp;&nbsp;&nbsp;
                            </center>
                        </th>

                        <?php $__currentLoopData = $gruporen; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <?php 
                            $idped = '';
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
                            
                            <th colspan="4" 
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
                                            color: <?php echo e($confcli->forecolor); ?>;
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
    var cont = '<?php echo e($gruporen->count()); ?>';
    var codgrupo = '<?php echo e($codgrupo); ?>';
    var resp = "";
    var tableReg = document.getElementById('myTable');
    var jqxhr = $.ajax({
        type:'POST',
        url: './obtenerCodcliGrupo',
        dataType: 'json', 
        encode  : true,
        data: { codgrupo:codgrupo },
        success:function(data) {
            resp = data.resp;
        }
    });
    jqxhr.always(function() {
        var arrCodsuc = resp.split('|');
        for (var z = 0; z < cont; z++) {
            var codsuc = arrCodsuc[z].toString().trim();
            var suger = 0;
            var costo = 0;   
            for (var i = 1; i < tableReg.rows.length-3; i++) {
                var x = i.toString().trim();
                var sugx = $('#sug_' + codsuc + "_" + x).text().replace(/,/g, '');
                var totx = $('#tot_' + codsuc + "_" + x).text().replace(/,/g, '');
                costo += parseFloat(totx);
                suger += parseFloat(sugx);
           }
            $("#cos_"+codsuc).text(number_format(costo, 2, '.', ','));
            $("#sug_"+codsuc).text(number_format(suger, 0, '.', ','));
        }
        for (var i = 1; i < tableReg.rows.length-3; i++) {
            var suger = 0;
            var costo = 0;   
            var codsuc = "";
            var x = i.toString().trim();
            for (var z = 0; z < cont; z++) {
                var codsuc = arrCodsuc[z].toString().trim();
                var sugx = $('#sug_' + codsuc + "_" + x).text().replace(/,/g, '');
                var totx = $('#tot_' + codsuc + "_" + x).text().replace(/,/g, '');
                costo += parseFloat(totx);
                suger += parseFloat(sugx);
            }  
            $("#cosgrp_"+x).text(number_format(costo, 2, '.', ','));
            $("#suggrp_"+x).text(number_format(suger, 0, '.', ','));
        }
        suger = 0;
        costo = 0;
        for (var i = 1; i < tableReg.rows.length-3; i++) {
            var x = i.toString().trim();
            var sugx = $('#suggrp_' + x).text().replace(/,/g, '');
            var totx = $('#cosgrp_' + x).text().replace(/,/g, '');
            costo += parseFloat(totx);
            suger += parseFloat(sugx);
        }
        $("#cosgrp").text(number_format(costo, 2, '.', ','));
        $("#suggrp").text(number_format(suger, 0, '.', ','));

    });
}
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/isacom/pedgrupo/show.blade.php ENDPATH**/ ?>