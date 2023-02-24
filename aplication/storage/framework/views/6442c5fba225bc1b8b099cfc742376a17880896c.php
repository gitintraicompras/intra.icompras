
<?php $__env->startSection('contenido'); ?>
<?php 
$moneda = Session::get('moneda', 'BSS');
$factor = RetornaFactorCambiario($cliente->codcli, $moneda);
?> 
<div class="btn-toolbar" role="toolbar" style="margin-top: 12px; margin-bottom: 3px;">
    <div class="btn-group" role="group" style="width: 100%;">
        <?php echo $__env->make('ofertas.registros.catasearch', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <a href="<?php echo e(url('/ofertas/sugofertas')); ?>">
            <button style="margin-right: 3px; height: 35px; " 
                type="button" 
                data-toggle="tooltip" 
                title="Crear sugerido de ofertas de productos para el gestor de Ofertas" 
                class="btn-confirmar">
                Crear
            </button>
        </a>  
    </div>
</div>
<div class="row" 
    style="width: 100%; 
    float: left; 
    margin: 0px;
    padding: 0px;
    height: 38px;">
    <table class="table table-striped table-bordered table-condensed table-hover">
        <tr>
            <td style="width: 100px;
                margin: 10px;">
                UTILIDAD MINIMA                
            </td>
            <td style="background-color: #b7b7b7; 
                color: #000000;
                width: 50px;
                margin: 10px;"
                align="right" >
                <?php echo e(number_format($cliente->utilm, 2, '.', ',')); ?>       
            </td> 
            <td align="right"
                style="width: 70px;
                margin: 10px;">
                DA MINIMO                
            </td> 
            <td style="background-color: #b7b7b7; 
                color: #000000;
                width: 50px;
                margin: 10px;"
                align="right" >
                <?php echo e(number_format($cliente->damin, 2, '.', ',')); ?>                
            </td> 
            <td align="right"
                style="width: 70px;
                margin: 10px;">
                DA MAXIMO                
            </td> 
            <td style="background-color: #b7b7b7; 
                color: #000000;
                width: 50px;
                margin: 10px;" 
                align="right">
                <?php echo e(number_format($cliente->damax, 2, '.', ',')); ?> 
            </td>
            <td align="right"
                style="width: 30px;
                margin: 10px;">
                DC                
            </td>
            <td style="background-color: #b7b7b7; 
                color: #000000;
                width: 40px;
                margin: 10px;" 
                align="right">
                <?php echo e(number_format($cliente->dc, 2, '.', ',')); ?> 
            </td> 
            <td align="right"
                style="width: 30px;
                margin: 10px;">
                DI                
            </td>
            <td style="background-color: #b7b7b7; 
                color: #000000;
                width: 40px;
                margin: 10px;" 
                align="right">
                <?php echo e(number_format($cliente->di, 2, '.', ',')); ?> 
            </td> 
            <td align="right"
                style="width: 30px;
                margin: 10px;">
                PP                
            </td>
            <td style="background-color: #b7b7b7; 
                color: #000000;
                width: 40px;
                margin: 10px;" 
                align="right">
                <?php echo e(number_format($cliente->pp, 2, '.', ',')); ?> 
            </td> 
        </tr>            
    </table>
</div>

<!-- TABLA -->
<div class="row" style="margin-top: 5px;">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

        <div class="table-responsive">

            <table id="idTabla" class="table table-striped table-bordered table-condensed table-hover" >

                <thead style="background-color: #b7b7b7;">
                    <th colspan="14">
                        <center>CATALOGO MAESTRO DE PRODUCTOS</center>
                    </th>
                    <?php $__currentLoopData = $provs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prov): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <?php 
                        if (!VerificaCampoTabla('tpmaestra', $prov->codprove))
                            continue;
                        $confprov = LeerProve($prov->codprove); 
                        if (is_null($confprov))
                            continue;
                        $fechaHoy = trim(date("Y-m-d"));
                        $fechaCat = trim(date('Y-m-d', strtotime($confprov->fechacata)));
                        ?>
                        
                        <th colspan="2" style="background-color: <?php echo e($confprov->backcolor); ?>; color: <?php echo e($confprov->forecolor); ?>; width: 400px; word-wrap: break-word; ">
                            <a href="<?php echo e(URL::action('ProveedorController@verprov',$prov->codprove)); ?>">
                                <center>
                                    <button type="button" 
                                        data-toggle="tooltip" 
                                        title="<?php echo e(strtoupper($confprov->nombre)); ?> &#10 (<?php echo e(date('d-m-Y H:i:s', strtotime($confprov->fechacata) )); ?>)" 
                                        style="background-color: <?php echo e($confprov->backcolor); ?>;
                                        <?php if($fechaCat != $fechaHoy): ?>
                                            color: red;
                                        <?php else: ?> 
                                            color: <?php echo e($confprov->forecolor); ?>; 
                                        <?php endif; ?>
                                        width: 100%;">
                                        <?php echo e($confprov->descripcion); ?>

                                    </button>
                                </center>
                            </a>
                        </th>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </thead>

                <thead style="background-color: #b7b7b7;">
                    <th>#</th>
                    <th style="width: 100px;">&nbsp;&nbsp;&nbsp;IMAGEN&nbsp;&nbsp;&nbsp;</th>
                    <th>DESCRIPCION</th>
                    <th>CODIGO</th>
                    <th>REFERENCIA</th>
                    <th>MARCA</th>
                    <th style="background-color: #FEE3CB;" title="Unidades del inventario del cliente">
                        INV.
                    </th>
                    <th style="background-color: #FED7B2;" title="Unidades consolidadas de la competencia">
                        C(PROV)
                    </th>
                    <th style="background-color: #FECC9E;">COSTO</th>
                    <th style="background-color: #FDBF87;"
                        title="Porcentaje de Utilidad">
                        UTIL(%)
                    </th>
                    <th style="background-color: #FEB370;">PRECIO(<?php echo e($cliente->usaprecio); ?>)</th>
                    <th style="background-color: #FEA95C;"
                        title="Porcentaje de Descuento Adicional Actual y eL porcentaje de Oferta">
                        DA(%)
                    </th>
                    <th style="background-color: #FD9E46;
                        color: #ffffff;">
                        PS
                    </th>
                    <th title="Mejor precio de la competencia" 
                        style="background-color: #FE9232; 
                               width: 120px;
                               color: #ffffff;">
                               MPC
                    </th>
                    <?php $__currentLoopData = $provs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prov): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php 
                        if (!VerificaCampoTabla('tpmaestra', $prov->codprove))
                            continue;
                        $confprov = LeerProve($prov->codprove); 
                        if (is_null($confprov))
                            continue;
                        ?>
                        <th style="background-color: <?php echo e($confprov->backcolor); ?>; color: <?php echo e($confprov->forecolor); ?>; width: 200px; word-wrap: break-word; ">
                            PRECIO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        </th> 
                        <th style="background-color: <?php echo e($confprov->backcolor); ?>; color: <?php echo e($confprov->forecolor); ?>; width: 200px; word-wrap: break-word; ">
                            CANTIDAD
                        </th>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </thead>

                <?php
                $iFila = 1;
                $invent = 'inventario_'.$codcli;
                ?>
                <?php $__currentLoopData = $sugoferen; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sug): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php

                    $cat = DB::table('tpmaestra as tpm')
                    ->select('*', 'tpm.desprod as descrip')
                    ->join($invent.' as tcm', 'tcm.barra','=', 'tpm.barra')
                    ->where('codisb','=',$codcli)
                    ->where('tpm.barra','=',$sug->barra)
                    ->first();
                    if (empty($cat))
                        continue;

                    $dataprod = obtenerColorProd($cat, $cliente, $provs);
                    $util = $dataprod['util'];
                    ?>
                    <tr>
                        <td style="background-color: <?php echo e($dataprod['backcolor']); ?>; 
                            color: <?php echo e($dataprod['forecolor']); ?>;"
                            title = "<?php echo e($dataprod['title']); ?>" >
                            <?php echo e($iFila++); ?>

                        </td>
                        <td style="width: 100px;">
                            <div align="center">
                                <a href="#">

                                    <img src="http://isaweb.isbsistemas.com/public/storage/prod/<?php echo e(NombreImagen($cat->barra)); ?>" 
                                    class="img-responsive" width="100%" height="100%" 
                                    alt="isacom" >
                    
                                </a> 
                            </div>
                        </td>
                        <td><?php echo e($cat->descrip); ?></td>
                        <td><?php echo e($cat->codprod); ?></td>
                        <td><?php echo e($cat->barra); ?></td>
                        <td><?php echo e($sug->marca); ?></td>
                        <!-- INVENTARIO -->
                        <td align="right" 
                            Style="background-color: #FEE3CB;"
                            title="IMVENTARIO">
                            <?php echo e(number_format($cat->cantidad, 0, '.', ',')); ?>

                        </td>

                        <!-- CONSOILIDADO  -->
                        <td align="right" 
                            style="background-color: #FED7B2;"
                            title="CONSOLIDADO DE LA COMPETENCIA">
                            <?php echo e(number_format($dataprod['invConsol'], 0, '.', ',')); ?>

                        </td>

                        <!-- COSTO -->
                        <td align="right" 
                            style="background-color: #FECC9E;"
                            title="COSTO">
                            <?php echo e(number_format($dataprod['costo']/$factor, 2, '.', ',')); ?>

                            <?php if($dataprod['costo2'] > 0): ?>
                                <br>
                                <?php echo e(number_format($dataprod['costo2']/$factor, 2, '.', ',')); ?>

                            <?php endif; ?>
                        </td>

                        <!-- UTILIDAD -->
                        <td align="right" 
                            style="background-color: #FDBF87;
                            <?php if($util < $cliente->utilm): ?> color: red; <?php endif; ?> "
                            title="MARGEN DE UTILIDAD">
                            <?php echo e(number_format($util, 2, '.', ',')); ?>%
                        </td>

                        <!-- PRECIO -->
                        <td align="right" 
                            style="background-color: #FEB370;"
                            title="PRECIO">
                            <?php echo e(number_format($dataprod['precioInv']/$factor, 2, '.', ',')); ?>

                        </td>
                 
                        <!-- DA -->
                        <td style="padding: 0px;
                            height: 100%;
                            background-color: #EEEEEE;">
                            <div style="width: 100px;">
                                <span class="input-group-btn" 
                                      style="width: 100px;" >
                                    <div class="col-xs-12 input-group">
                                        <input style="text-align: right; 
                                            background-color: #FEA95C;
                                            color: #000000;
                                            height: 50%;
                                            font-size: 20px; 
                                            width: 100px;"
                                            readonly="" 
                                            value="<?php echo e(number_format($sug->da, 0, '.', ',')); ?> %"
                                            class="form-control" >
                                    </div>
                                    <div class="col-xs-12 input-group" 
                                         id="2idAgregar-<?php echo e($iFila); ?>">
                                         <input style="text-align: right; 
                                            color: #000000; 
                                            font-size: 20px;
                                            height: 50%;
                                            width: 60px;" 
                                            readonly="" 
                                            id="idPedir-<?php echo e($iFila); ?>" 
                                            class="form-control" 
                                            value="<?php echo e(number_format($sug->dasug, 0, '.', ',')); ?>"
                                                    >
                                         <button type="button" 
                                                 class="BtnAgregar btn btn-pedido" 
                                                 data-toggle="tooltip" 
                                                 style="font-size: 20px;
                                                 height: 50%;
                                                 text-align: center;"
                                                 id="idBtnAgregar-<?php echo e($iFila); ?>" 
                                                 disabled >
                                                 <span 
                                                    <?php if($sug->dasug == $sug->da): ?>
                                                        class="fa fa-times-circle-o"
                                                    <?php else: ?>
                                                        <?php if($sug->dasug > $sug->da): ?>
                                                            class="fa fa-thumbs-o-up"
                                                        <?php else: ?>
                                                            class="fa fa-thumbs-o-down"
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                    aria-hidden="true">
                                                 </span>
                                         </button>
                                    </div>
                                </span>
                            </div>
                        </td>
     
                        <!-- PS -->
                        <td align="right" 
                            style="background-color: #FD9E46;
                            color: #ffffff;
                            font-size: 20px;
                            vertical-align: middle;
                            <?php if(($dataprod['precioSug']/$factor) > ($dataprod['mpcFactor'])): ?> color: red; <?php endif; ?>"
                            title="PRECIO SUGERIDO">
                            <?php echo e(number_format($dataprod['precioSug']/$factor, 2, '.', ',')); ?>

                        </td>

                        <!-- MPC -->
                        <td style="padding: 0px; 
                            height: 100%;
                            background-color: #FE9232;">
                            <div style="width: 100px; ">
                                <span class="input-group-btn" 
                                    style="width: 100px;
                                    height: 100%;">
                                    <div class="col-xs-12 input-group" >
                                        <input style="text-align: right; 
                                            width: 100px;
                                            color: #ffffff;
                                            height: 50%;
                                            font-size: 20px;
                                            background-color: #FE9232;"
                                            class="form-control"  
                                            readonly="" 
                                            value="<?php echo e(number_format($dataprod['mpcFactor'], 2, '.', ',')); ?>" >
                                    </div>
                                    <div style="margin-left: 0px; 
                                        margin-right: 0px; 
                                        width: 100px;
                                        height: 50%;">
                                        <?php
                                        $arrayRnk = $dataprod['arrayRnk'];
                                        $cont = count($arrayRnk);
                                        ?>
                                        <?php if($cont == 1): ?>
                                            <div class="form-control" 
                                                 style="width: 100px;
                                                 color: #ffffff;
                                                 font-size: 20px;
                                                 height: 50%;
                                                 background-color: #FE9232;">
                                                 <?php if(Auth::user()->versionLight == 0): ?>
                                                    <?php echo e($dataprod['tpselect']); ?>

                                                 <?php else: ?>
                                                    TP**
                                                 <?php endif; ?>
                                            </div>
                                        <?php else: ?> 
                                            <select id="idProv-<?php echo e($iFila); ?>" 
                                                    class="form-control"
                                                    style="width: 100px;
                                                    color: #ffffff;
                                                    font-size: 20px;
                                                    height: 50%;
                                                    background-color: #FE9232;">
                                                    <?php for($x=0; $x < $cont; $x++): ?> 
                                                        <?php if(Auth::user()->versionLight == 0): ?>
                                                            <?php if($dataprod['tpselect']==$arrayRnk[$x]['codprove']): ?> 
                                                                <option selected value=
                                                                "<?php echo e($arrayRnk[$x]['codprove']); ?>">   
                                                                <?php echo e($arrayRnk[$x]['codprove']); ?>

                                                                </option>
                                                            <?php else: ?> 
                                                                <option value=
                                                                   "<?php echo e($arrayRnk[$x]['codprove']); ?>">
                                                                    <?php echo e($arrayRnk[$x]['codprove']); ?>

                                                                </option>
                                                            <?php endif; ?>
                                                        <?php else: ?>
                                                            <?php if($dataprod['tpselect']==$arrayRnk[$x]['codprove']): ?> 
                                                                <option selected value=
                                                                "<?php echo e($arrayRnk[$x]['codprove']); ?>">   
                                                                <?php echo e(sCodprovCifrado($arrayRnk[$x]['codprove'])); ?>

                                                                </option>
                                                            <?php else: ?> 
                                                                <option value=
                                                                   "<?php echo e($arrayRnk[$x]['codprove']); ?>">
                                                                    <?php echo e(sCodprovCifrado($arrayRnk[$x]['codprove'])); ?>

                                                                </option>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    <?php endfor; ?>
                                            </select>
                                        <?php endif; ?>
                                    </div>
                                </span>
                            </div>
                        </td>
                        
                        <?php $__currentLoopData = $provs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prov): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $codprove = strtolower($prov->codprove);
                                $factor = RetornaFactorCambiario($codprove, $moneda);
                                if (!VerificaCampoTabla('tpmaestra', $prov->codprove))
                                    continue;
                                try {
                                    $campos = $cat->$codprove;
                                    $campo = explode("|", $campos);
                                } catch (Exception $e) {
                                    continue;
                                }
                                $cantidad = $campo[1];
                                $codprod = $campo[3];
                                $fechafalla = $campo[4];

                                $lote = $campo[7];
                                $fecvence = $campo[8];
                                $fecvence = str_replace("12:00:00 AM", "", $fecvence);
                                $confprov = LeerProve($prov->codprove); 
                                if (is_null($confprov))
                                    continue;
                                $tipoprecio = $prov->tipoprecio;
                                $actualizado = date('d-m-Y H:i:s', strtotime($prov->fechasinc));
                                $dc = $prov->dcme;
                                $di = $prov->di;
                                $pp = $prov->ppme;
                                $da = $campo[2];
                                switch ($tipoprecio) {
                                    case 1:
                                        $precio = $campo[0]/$factor;
                                        break;
                                    case 2:
                                        $precio = $campo[5]/$factor;
                                        break;
                                    case 3:
                                        $precio = $campo[6]/$factor;
                                        break;
                                    default:
                                        $precio = $campo[0]/$factor;
                                        break;
                                }
                                $neto = CalculaPrecioNeto($precio, $da, $di, $dc, $pp);
                                $liquida = $neto + (($neto * $cat->iva)/100);
                                $ranking = obtenerRanking($liquida, $arrayRnk);
                            ?>

                            <!--- PRECIO DEL PROVEEDOR -->
                            <td align='right' style="width: 200px; word-wrap: break-word; background-color: <?php echo e($confprov->backcolor); ?>;
                             color: <?php echo e($confprov->forecolor); ?>; " 
                             
                             <?php if(Auth::user()->versionLight == 0): ?>
                             title = "<?php echo e($confprov->descripcion); ?> &#10 ======================== &#10 PRECIO: <?php echo e(number_format($precio, 2, '.', ',')); ?> &#10 TIPO: <?php echo e($tipoprecio); ?> <?php if($factor > 1): ?> &#10 TASA: <?php echo e(number_format($factor, 2, '.', ',')); ?> <?php endif; ?> &#10 DA: <?php echo e(number_format($da, 2, '.', ',')); ?> &#10 DC: <?php echo e(number_format($dc, 2, '.', ',')); ?> &#10 DI: <?php echo e(number_format($di, 2, '.', ',')); ?> &#10 PP: <?php echo e(number_format($pp, 2, '.', ',')); ?> &#10 IVA: <?php echo e(number_format($cat->iva, 2, '.', ',')); ?> &#10 RANKING: <?php echo e($ranking); ?> &#10 LOTE: <?php echo e($lote); ?> &#10 VENCE: <?php echo e($fecvence); ?> &#10 CODIGO: <?php echo e($codprod); ?> &#10 ACTUALIZADO: <?php echo e($actualizado); ?> &#10 ======================== &#10 LIQUIDA: <?php echo e(number_format($liquida, 2, '.', ',')); ?> &#10 "
                             <?php endif; ?>

                             >
                             <?php if(Auth::user()->versionLight == 0): ?>
                                 <?php if($liquida == $dataprod['mpcFactor']): ?>
                                    <i class="fa fa-check"></i>
                                    <?php echo e(number_format($liquida, 2, '.', ',')); ?> 
                                 <?php else: ?>
                                    <?php echo e(number_format($liquida, 2, '.', ',')); ?>

                                 <?php endif; ?>
                             <?php else: ?>
                                 <?php echo e(sPrecioCifrado(number_format($liquida, 2, '.', ','))); ?>

                             <?php endif; ?>

                             <?php if(Auth::user()->versionLight == 0): ?>
                                 <?php if($ranking): ?>
                                    &#10 <div>Rnk:<?php echo e($ranking); ?></div>
                                 <?php endif; ?>
                             <?php endif; ?>
                            </td>

                            <!--- CANTIDAD DEL PROVEEDOR -->
                            <td align='right' style="width: 200px; word-wrap: break-word; background-color: <?php echo e($confprov->backcolor); ?>; color: <?php echo e($confprov->forecolor); ?>;" 
                                title=" <?php echo e($confprov->descripcion); ?>">
                                <?php if($dataprod['mayorInv'] == $cantidad): ?>
                                    <i class="fa fa-check"></i>
                                    <?php echo e(number_format($cantidad, 0, '.', ',')); ?>

                                <?php else: ?>
                                    <?php echo e(number_format($cantidad, 0, '.', ',')); ?>

                                <?php endif; ?>
                            </td>
                    
                            <td style="display:none;"><?php echo e($da); ?></td>
                            <td style="display:none;"><?php echo e($codprod); ?></td>
                            <td style="display:none;"><?php echo e($fechafalla); ?></td>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </table>

            <div align='left'>
                <?php echo e($sugoferen->appends(["filtro" => $filtro])->links()); ?>

            </div><br>
            
        </div>
    </div>
</div>
<?php if($sugoferen->count() == 0): ?>
    <div class="row">
        <?php if($tipo=="C"): ?>
            <center><h2>Catálogo de productos vacio</h2></center>
        <?php endif; ?>
        <br><br><br><br><br><br>
    </div>
<?php endif; ?>
<?php $__env->startPush('scripts'); ?>

<script>
$('#subtitulo').text('<?php echo e($subtitulo); ?>');
var accion = document.getElementById("idaccion").value;

$(document).keypress(function(e) {
   if(e.which == 13) {
        alert('Accion');
   }
});


</script>

<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/ofertas/registros/catalogo.blade.php ENDPATH**/ ?>