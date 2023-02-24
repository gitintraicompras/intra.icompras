
<?php $__env->startSection('contenido'); ?>
 
<?php
  $moneda = Session::get('moneda', 'BSS');
  $factor = RetornaFactorCambiario("", $moneda);
  $x=0;
?> 
 
<!-- BOTONES BUSCAR -->
<div class="btn-toolbar" role="toolbar" style="margin-top: 12px; margin-bottom: 3px;">
    <div class="btn-group" role="group" style="width: 100%;">
        <!-- BOTON BUSCAR -->
        <?php echo $__env->make('isacom.invgrupo.search', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
</div>

<!-- TABLA -->
<div class="row" style="margin-top: 5px;">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="table-responsive">

            <table id="table-1" class="table table-bordered table-condensed" >

                <thead style="background-color: #b7b7b7;">
                    <tr>
                        <th id="col1-1" style="width: 1130;" colspan="7">
                            <center>INVENTARIO MAESTRO DE PRODUCTOS</center>
                        </th>

                        <th id="col1-2" style="width: 200; background-color: #FEE3CB;" colspan="2">
                            <center>&nbsp;&nbsp;&nbsp;&nbsp;PROVEEDOR&nbsp;&nbsp;&nbsp;&nbsp;</center>
                        </th>

                        <th id="col1-3" style="width: 200; background-color: #FCD0C7;" colspan="2">
                            <center>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;GRUPO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            </center>
                        </th>
                        <?php $__currentLoopData = $gruporen; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <?php 
                            $x=$x+1;
                            if (!VerificaCampoTabla($nomtcmaestra, 'tc'.$gr->codcli))
                                continue;
                            $confcli = LeerCliente($gr->codcli); 
                            $actualizado = date('d-m-Y H:i:s', strtotime(LeerTablaFirst('inventario_'.$gr->codcli, 'feccatalogo')));
                            $fechaHoy = trim(date("Y-m-d"));
                            $fechaInv = trim(date('Y-m-d', strtotime($actualizado)));
                            ?>
                            
                            <th id="col1-<?php echo e($x); ?>" 
                                colspan="6" 
                                style="background-color: <?php echo e($confcli->backcolor); ?>; 
                                color: <?php echo e($confcli->forecolor); ?>; 
                                width: 400px; 
                                word-wrap: break-word; ">
                                <a href="<?php echo e(URL::action('GrupoController@show',$gr->codcli)); ?>">
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
                                            "> <?php echo e($confcli->descripcion); ?>

                                        </button>
                                    </center>
                                </a>
                            </th>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tr>
                    <tr>
                        <th style="width: 10px;">
                            <center>#</center>
                        </th>
                        <th style="width: 120px;">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;IMAGEN&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        </th>
                        <th style="width: 300px;">PRODUCTO</th>
                        <th style="width: 100px;">REFERENCIA</th>
                        <th style="width: 100px;">MARCA</th>
                        <th style="width: 60px;">BULTO</th>
                        <th style="width: 60px;">IVA</th>

                        <!-- PROVEEDOR -->                        
                        <th style="background-color: #FEE3CB;" 
                            title="Mejor precio del proveedor">
                            MPP &nbsp;&nbsp;&nbsp;&nbsp;
                        </th>
                        <th style="background-color: #FEE3CB;"
                            title="Unidades consolidada del inventario de los proveedores">
                            INV.
                        </th>

                        <!-- GRUPO  -->
                        <th style="background-color: #FCD0C7;"
                            title="Unidades en transito">
                            TRAN.
                        </th>
                        <th style="background-color: #FCD0C7;"
                            title="Unidades consolidada">
                            INV.&nbsp;&nbsp;&nbsp;&nbsp;
                        </th>

                        <?php $__currentLoopData = $gruporen; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php 
                            if (!VerificaCampoTabla($nomtcmaestra, 'tc'.$gr->codcli))
                                continue;
                            $confcli = LeerCliente($gr->codcli); 
                            ?>
                            <th style="background-color: <?php echo e($confcli->backcolor); ?>; 
                                color: <?php echo e($confcli->forecolor); ?>; 
                                width: 200px; word-wrap: break-word; "
                                title="Precio de venta del producto">
                                PRECIO(<?php echo e($confcli->usaprecio); ?>)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            </th> 
                            <th style="background-color: <?php echo e($confcli->backcolor); ?>; 
                                color: <?php echo e($confcli->forecolor); ?>; 
                                width: 200px; word-wrap: break-word; "
                                title="Unidades en transito">
                                TRAN.
                            </th>
                            <th style="background-color: <?php echo e($confcli->backcolor); ?>; 
                                color: <?php echo e($confcli->forecolor); ?>; 
                                width: 200px; word-wrap: break-word; "
                                title="Inventario del producto">
                                INV.
                            </th>
                            <th style="background-color: <?php echo e($confcli->backcolor); ?>; 
                                color: <?php echo e($confcli->forecolor); ?>; 
                                width: 200px; word-wrap: break-word; "
                                title="Costo del producto">
                                COSTO
                            </th>
                            <th style="background-color: <?php echo e($confcli->backcolor); ?>; 
                                color: <?php echo e($confcli->forecolor); ?>; 
                                width: 200px; word-wrap: break-word; "
                                title="Venta Media Diaria del producto">
                                VMD
                            </th>
                            <th style="background-color: <?php echo e($confcli->backcolor); ?>; 
                                color: <?php echo e($confcli->forecolor); ?>; 
                                width: 200px; word-wrap: break-word; "
                                title="Dias de Inventario">
                                DIAS
                            </th>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </TR>
                </thead>
                  
                <?php
                $iFila = 0;
                $fechaHoy = date('Y-m-d');
                ?>
 
                <?php if( isset($catalogo) ): ?>
                    <?php $__currentLoopData = $catalogo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
	                    <?php
						if ($cat->barra == "")
							continue;
	                    $arrayRnk = [];
	                    $entra = FALSE;
	                    $menor = 100000000000000;
	                    $mayorInv = 0;
	                    $tpselect = "";
	                    $contcli = 0;
	                    $invConsolGrupo = 0;

                        // MEJOR PRECIO PROVEEDOR
                        $mpp = 0.00;
                        $pedir = 1;
                        $criterio = 'PRECIO';
                        $preferencia = 'NINGUNA';
                        $provs = TablaMaecliproveActiva("");
                        $mejoropcion = BuscarMejorOpcion($cat->barra, $criterio, $preferencia, $pedir, $provs);
                        if ($mejoropcion != null) {

                            $precio = $mejoropcion[0]['precio'];
                            $da = $mejoropcion[0]['da'];
                            $codprove = $mejoropcion[0]['codprove'];
                            $maeclieprove = DB::table('maeclieprove')
                            ->where('codcli','=',$codcliente)
                            ->where('codprove','=',$codprove)
                            ->first();
                            $dc = $maeclieprove->dcme;
                            $di = $maeclieprove->di;
                            $pp = $maeclieprove->ppme;
                            $mpp = CalculaPrecioNeto($precio, $da, $di, $dc, $pp);
                        }


	                    foreach ($gruporen as $gr) { 
	                        if (!VerificaCampoTabla($nomtcmaestra, 'tc'.$gr->codcli))
                            	continue;
                        	$confcli = LeerCliente($gr->codcli);
	                        $codcli = strtolower($gr->codcli);
	                        try {
	                        	$tc = 'tc'.$codcli;
	                            $campos = $cat->$tc;
	                            $campo = explode("|", $campos);
	                        } catch (Exception $e) {
	                            continue;
	                        }
	                        $precio = $campo[0]/$factor;
	                        $cantidad = $campo[1];
                            $entra = TRUE; 
                            $contcli++;
                            $invConsolGrupo = $invConsolGrupo + $cantidad;
                            $liquida = $precio + (($precio * $cat->iva)/100);

                            if ($liquida > 0) {
                                $arrayRnk[] = [
                                    'liquida' => $liquida,
                                    'codcli' => $codcli
                                ];
                                if ($liquida < $menor) {
                                    $menor = $liquida; 
                                }
                            }

                            if ($cantidad > $mayorInv) {
                                 $mayorInv = $cantidad;          
                            }
	                    }
	                    if ($entra == FALSE) 
	                        continue;
	                    $aux = array();
                        foreach ($arrayRnk as $key => $row) {
                            $aux[$key] = $row['liquida'];
                        }
                        if (count($aux) > 1)
                            array_multisort($aux, SORT_ASC, $arrayRnk);

	                    $iFila++;
                        $title = "Sin Inventario";
	                    $invConsolProv = iCantidadConsolidadoProv($cat->barra);

	                    $backcolor = "#FFFFFF";
	                    $forecolor = "#000000"; 
                        $alerta = 0;
	                    if ($invConsolProv > 0 && $invConsolGrupo <= 0) {
	                    	// ROJO -> ALERTA
	                    	$title = "Alerta -> se debe pedir este producto";
	                    	$backcolor = "#FF0000";
	                    	$forecolor = "#FFFFFF";
                            $alerta = 1;
	                	}
	                	if ($invConsolProv > 0 && $invConsolGrupo > 0) {
	                		// VERDE -> IDEAL
	                    	$title = "Ideal -> tienes inventario, al igual que los proveedores";
	                		$backcolor = "#00B621";
	                    	$forecolor = "#FFFFFF"; 
                            $alerta = 2;
	                	}
	                	if ($invConsolProv <= 0 && $invConsolGrupo > 0) {
	                		// AMARILLO -> WARNING
	                    	$title = "Advertencia -> tienes inventario, pero los proveedores no tienen";
	                		$backcolor = "#FFD800";
	                    	$forecolor = "#000000"; 
                            $alerta = 3;
	                	}
	                	if ($invConsolProv <= 0 && $invConsolGrupo < 0) {
	                		// GRAVE -> NARANJA
	                    	$title = "Grave -> Sin inventario, igual los proveedores ";
	                		$backcolor = "#FF6A00";
	                    	$forecolor = "#FFFFFF"; 
                            $alerta = 4;
	                	}
	                    ?>
                        <tr>
	                        <td style="width: 10px;">
                                <center>
                               	<?php echo e($iFila); ?>

                                <i class="fa fa-thumbs-up" 
                                    aria-hidden="true"
                                    style="font-size: 20px;
                                    color: <?php echo e($backcolor); ?>; "
                                    title="<?php echo e($title); ?>" >
                                </i>
                                </center>
	                        </td>

	                        <td style="width: 120px;"
                                title="IMAGEN DE REFERENCIA">
	                            <div align="center">
	                            	<a href="<?php echo e(URL::action('PedidoController@verprod',$cat->barra)); ?>">

	                                    <img src="http://isaweb.isbsistemas.com/public/storage/prod/<?php echo e(NombreImagen($cat->barra)); ?>" 
	                                    class="img-responsive" 
	                                    alt="icompra" width="100%" height="100%" >
	                    
	                                </a>
	                            </div>
	                        </td>

	                        <td style="width: 300px;"
                                title="DESCRIPCION DEL PRODUCTO">
                                <?php echo e($cat->desprod); ?>

                            </td>

	                        <td style="width: 100px;"
                                title="CODIGO DE BARRA">
                                <?php echo e($cat->barra); ?>

                            </td>

	                        <td style="width: 300px;"
                                title="MARCA DEL PRODUCTO">
                                <?php echo e($cat->marca); ?>

                            </td>

	                        <td align="right" 
                                style="width: 60px;"
                                title="UNIDAD DE MANEJO">
                                <?php echo e($cat->bulto); ?>

                            </td>

	                        <td align="right" 
                                style="width: 60px;"
                                title="IVA PRODUCTO">
                                <?php echo e(number_format($cat->iva, 2, '.', ',')); ?>

                            </td>

	     
                            <!-- PROVEEDOR-->
	                        <td align="right" 
                                style="width: 60px; background-color: #FEE3CB;"
                                title="MEJOR PRECIO DEL PROVEEDOR">
	                            <?php echo e(number_format($mpp, 2, '.', ',')); ?>

	                        </td>
	                        <td align="right" 
                                style="width: 60px; background-color: #FEE3CB;"
                                title="CONSOLIDADO DEL PROVEEDOR">
	                        	<?php echo e(number_format($invConsolProv, 0, '.', ',')); ?>

	                        </td>

                            <!-- GRUPO -->
                            <td align="right" 
                                style="width: 60px; background-color: #FCD0C7;"
                                title="UNIDADES EN TANSITO">
                                <?php echo e(number_format(verificarProdTransito($cat->barra,  "", $codgrupo), 0, '.', ',')); ?>

                            </td>
                            <td align="right" 
                                style="width: 60px; background-color: #FCD0C7;"
                                title="CONSOLIDADO DEL GRUPO">
                                <?php echo e(number_format($invConsolGrupo, 0, '.', ',')); ?>

                            </td>

       		                <?php $__currentLoopData = $gruporen; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	                            <?php
	                                $codcli = strtolower($gr->codcli);
	                                if (!VerificaCampoTabla($nomtcmaestra, 'tc'.$gr->codcli))
	                                    continue;
	                                try {
	                                    $tc = 'tc'.$codcli;
	                            		$campos = $cat->$tc;
	                            		$campo = explode("|", $campos);
	                                } catch (Exception $e) {
	                                    continue;
	                                }
	                                $actualizado = date('d-m-Y H:i:s', strtotime(LeerTablaFirst('inventario_'.$codcli, 'feccatalogo')));
	                                $precio = $campo[0]/$factor;
	                                $cantidad = $campo[1];
	                                $codprod = $campo[2];
	                                $desprod = $campo[3];
	                                $costo = $campo[4]/$factor;
	                                $vmd = $campo[5];
	                                $confcli = LeerCliente($gr->codcli); 
	                                $liquida = $precio + (($precio * $cat->iva)/100);
	                                $ranking = obtenerRanking($liquida, $arrayRnk);
	                            ?>

	                            <?php if($liquida == 0): ?>
	                                <td align='right' 
                                        title="<?php echo e($confcli->descripcion); ?> -> PRECIO" 
                                        style="width: 200px; 
                                        word-wrap: break-word; 
                                        background-color: <?php echo e($confcli->backcolor); ?>;
	                                    color: <?php echo e($confcli->forecolor); ?>; ">
	                                    <?php echo e(number_format($liquida, 2, '.', ',')); ?>

	                                </td>
	                            <?php else: ?>
                                    <td align='right' style="width: 200px; word-wrap: break-word; background-color: <?php echo e($confcli->backcolor); ?>;
                                     color: <?php echo e($confcli->forecolor); ?>; " 
                                     
                                     title = "<?php echo e($confcli->descripcion); ?> &#10 ======================== &#10 PRECIO: <?php echo e(number_format($precio, 2, '.', ',')); ?> &#10 TIPO: 1 &#10 DA: 0.00 &#10 IVA: <?php echo e(number_format($cat->iva, 2, '.', ',')); ?> &#10 RANKING: <?php echo e($ranking); ?> &#10 CODIGO: <?php echo e($codprod); ?> &#10 ACTUALIZADO: <?php echo e($actualizado); ?> &#10 ======================== &#10 LIQUIDA: <?php echo e(number_format($liquida, 2, '.', ',')); ?> &#10 ">
                                    <?php if($liquida == $menor): ?>
                                    	<i class="fa fa-check"></i>
                                    	<?php echo e(number_format($liquida, 2, '.', ',')); ?>

                                    <?php else: ?>
                                    	<?php echo e(number_format($liquida, 2, '.', ',')); ?> 
                                    <?php endif; ?>
                                    <?php if($ranking): ?>
                                    	&#10 <div>Rnk:<?php echo e($ranking); ?></div> 
                                    <?php endif; ?>
                                    </td>
	                            <?php endif; ?>

                                <td align='right' 
                                    style="width: 200px; 
                                    word-wrap: break-word; 
                                    background-color: <?php echo e($confcli->backcolor); ?>; 
                                    color: <?php echo e($confcli->forecolor); ?>;" 
                                    title="<?php echo e($confcli->descripcion); ?> -> TRANSITO">
                                    <?php echo e(number_format(verificarProdTransito($cat->barra,  $codcli, ""), 0, '.', ',')); ?>

                                </td>
       
                                <td align='right' 
                                    style="width: 200px; 
                                    word-wrap: break-word; 
                                    background-color: <?php echo e($confcli->backcolor); ?>; 
                                    color: <?php echo e($confcli->forecolor); ?>;" 
                                    title="<?php echo e($confcli->descripcion); ?> -> INVENTARIO">
                                	<?php if($mayorInv == $cantidad && $mayorInv != 0 ): ?>
                                	    <i class="fa fa-check"></i>
                                		<?php echo e(number_format($cantidad, 0, '.', ',')); ?>

                                	<?php else: ?>
                                		<?php echo e(number_format($cantidad, 0, '.', ',')); ?>

                                	<?php endif; ?>
                                </td>
	                     
	                            <td align='right' 
                                    style="width: 200px; 
                                    word-wrap: break-word; 
                                    background-color: <?php echo e($confcli->backcolor); ?>; 
	                            	color: <?php echo e($confcli->forecolor); ?>;" 
	                            	title="<?php echo e($confcli->descripcion); ?> -> COSTO">
	                            	<?php echo e(number_format($costo, 2, '.', ',')); ?>

	                            </td>

	                            <td align='right' 
                                    style="width: 200px; 
                                    word-wrap: break-word; 
                                    background-color: <?php echo e($confcli->backcolor); ?>; 
	                            	color: <?php echo e($confcli->forecolor); ?>;" 
	                            	title="<?php echo e($confcli->descripcion); ?> -> VMD">
	                            	<?php echo e(number_format($vmd, 4, '.', ',')); ?>

	                            </td>

	                            <td align='right' 
                                    style="width: 200px; 
                                    word-wrap: break-word; 
                                    background-color: <?php echo e($confcli->backcolor); ?>; 
	                            	color: <?php echo e($confcli->forecolor); ?>;" 
	                            	title="<?php echo e($confcli->descripcion); ?> -> DIAS">
                                    <?php if($vmd > 0): ?>
	                            	  <?php echo e(number_format($cantidad/$vmd, 2, '.', ',')); ?>

                                    <?php else: ?>
                                      0.00
                                    <?php endif; ?>
	                            </td>
	                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            
            </table>

            <div align='left'>
                <?php if(isset($catalogo)): ?>
                    <?php echo e($catalogo->appends(["filtro" => $filtro])->links()); ?>

                <?php endif; ?>
            </div><br>
        
        </div>
    </div>
</div>

<?php if( $catalogo->count() <= 0 ): ?>
<div class="row">
    <center><h2>Catálogo de productos vacio</h2></center>
    <br><br><br><br><br><br>
</div>
<?php endif; ?>

<?php $__env->startPush('scripts'); ?>

<script>
$('#titulo').text('<?php echo e($subtitulo); ?>');
$('#subtitulo').text('<?php echo e($subtitulo2); ?>');
</script>

<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/isacom/invgrupo/index.blade.php ENDPATH**/ ?>