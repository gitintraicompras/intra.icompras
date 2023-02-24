
<?php $__env->startSection('contenido'); ?>
 
<?php
  $moneda = Session::get('moneda', 'BSS');
  $factorInv = RetornaFactorCambiario('', $moneda);
  $rutalogoprov = 'http://isaweb.isbsistemas.com/public/storage/prov/';
?>
 
<!-- TABLA -->
<div class="row" 
    id="idaccion"
    style="margin-top: 5px;">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

        <?php echo $__env->make('isacom.pedidodirecto.invsearch', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <div class="table-responsive">

            <table id="idTabla" 
                style="margin-bottom: 0px;" 
                class="table table-striped table-bordered table-condensed table-hover">

                <thead style="background-color: #b7b7b7;">
                    <th colspan="8" >
                        <center>
                            <button type="button" 
                                data-toggle="tooltip" 
                                title="Ver información del proveedor" 
                                style="border: none; background-color: #b7b7b7;">
                                INVENTARIO, ACTUALIZADO: 
                                <?php if( date('d-m-Y', strtotime($fechainv) ) == date('d-m-Y') ): ?>
                                    <span>
                                        <?php echo e(date('d-m-Y H:i', strtotime($fechainv))); ?> 
                                    </span>
                                <?php else: ?>
                                    <span style="color: red;">
                                        <?php echo e(date('d-m-Y H:i', strtotime($fechainv))); ?> 
                                    </span>
                                <?php endif; ?>
                            </button>
                        </center>
                    </th>
                    <th colspan="9" 
                        style="background-color: #FEE3CB;"> 
                        <center>
                            CLIENTE
                        </center>
                    </th>
                    <th colspan="2" 
                        style="background-color: #FCD0C7;"> 
                        <center>
                           PROVEEDOR
                        </center>
                    </th>
                </thead>

                <thead style="background-color: #b7b7b7;">
            
                    <th>#</th>
                    <th>
                        &nbsp;&nbsp;&nbsp;&nbsp;IMAGEN&nbsp;&nbsp;&nbsp;&nbsp;
                    </th>
                    <th style="width: 120px;">
                        PEDIR
                    </th>
                    <th>
                        PRODUCTO
                    </th>
                    <th>
                        REFERENCIA
                    </th>
                    <th>
                        MARCA
                    </th>
                    <th>
                        BULTO
                    </th>
                    <th>
                        IVA
                    </th>
                
                    <th title="Unidades en Transito del cliente" 
                        style="background-color: #FEE3CB;">
                        TRAN.
                    </th>
                    <th title="Unidades del inventario del cliente" 
                        style="background-color: #FEE3CB;"> 
                        INV.&nbsp;&nbsp;&nbsp;&nbsp;
                    </th>
                    <th style="display:none;">TP</th>
                    <th style="display:none;">RNK1</th>
                    <th title="Costo del producto" 
                        style="background-color: #FEE3CB"> 
                        COSTO&nbsp;&nbsp;&nbsp;&nbsp;
                    </th>
                    <th title="Precio del producto" 
                        style="background-color: #FEE3CB"> 
                        PRECIO(<?php echo e($usaprecio); ?>)
                    </th>
                    <th title="Unidad de venta media diaria" 
                        style="background-color: #FEE3CB"> 
                        VMD
                    </th>
                    <th title="Dias de inventarios" 
                        style="background-color: #FEE3CB"> 
                        DIAS
                    </th>
                    <th title="Sugerido=(VMD*15-(INVENTARIO+TRANSITO))"
                        style="background-color: #FEE3CB;">
                        15
                    </th>
                    <th title="Sugerido=(VMD*30-(INVENTARIO+TRANSITO))"
                        style="background-color: #FEE3CB;">
                        30
                    </th>
                    <th title="Sugerido=(VMD*60-(INVENTARIO+TRANSITO)"
                        style="background-color: #FEE3CB;">
                        60
                    </th>
 
                    <th title="Mejpr Precio del proveedor"
                        style="background-color: #FCD0C7;">
                        &nbsp;&nbsp;&nbsp;&nbsp;MPP&nbsp;&nbsp;&nbsp;&nbsp;
                    </th>                    
                    <th title="Consolidado del inventario de proveedores"
                        style="background-color: #FCD0C7;">
                        CONSOL.
                    </th>

                  </thead>

                <?php
                $iFila = 0;
                ?>
                <?php if(isset($invent)): ?>
                    <?php $__currentLoopData = $invent; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                        $preciox = 'precio'.$usaprecio;
                        $precio = $inv->$preciox;
                        $precio = $precio/$factorInv;
                        $costo = $inv->costo/$factorInv;
                        $cantidad = $inv->cantidad;
                        $vmd = 0.0000;
                        $dias = 0;
                        $cant = 0;
                        $tendencia = "";
                        $sug15 = 0;
                        $sug30 = 0;
                        $sug60 = 0;
                        $transito = verificarProdTransito($inv->barra,  "", "");
                        $vmd = $inv->vmd;
                        $cant = $inv->cantidad;
                        if ($vmd > 0)
                            $dias = $cant/$vmd;
                        $tendencia = MostrarTendenciaProd($codcli, $inv->codprod, $cfg->TendTolerancia);
                        $sug15 = ($vmd*15)-($cant + $transito);
                        $sug30 = ($vmd*30)-($cant + $transito);
                        $sug60 = ($vmd*60)-($cant + $transito);
                        if ($sug15 < 0)
                            $sug15 = 0;
                        if ($sug30 < 0)
                            $sug30 = 0;
                        if ($sug60 < 0)
                            $sug60 = 0;
                        $respDias = analisisSobreStock($dias, $cliente->sobrestockBajo, $cliente->sobrestockAlto);
                        $iFila = $iFila + 1;

                        $mpp = 0.00;
                        $invconsol = 0;
                        $tpmaestra = DB::table('tpmaestra')
                        ->where('barra','=',$inv->barra)
                        ->first();
                        if ($tpmaestra) {
                            $dataprod = obtenerDataTpmaestra($tpmaestra, $provs, 0);
                            if (!is_null($dataprod)) {
                                $mpp = $dataprod['mpp'];
                                $invconsol = $dataprod['invConsol'];
                            }
                        }
                        ?>
                        <tr>
                            <td>
                                <?php echo e($iFila); ?>

                                <?php if(isset($inv->codprod)): ?>
                                <a href="<?php echo e(URL::action('PedidoController@tendencia',$inv->codprod)); ?>">
                                    <h4>
                                        <i class="<?php echo e($tendencia); ?>" aria-hidden="true"></i>
                                    </h4>
                                </a>
                                <?php endif; ?>
                            </td>
                            <td style="width: 60px;">
                                <div align="center">
                                    <a href="<?php echo e(URL::action('PedidoController@verprod',$inv->barra)); ?>">

                                        <img src="http://isaweb.isbsistemas.com/public/storage/prod/<?php echo e(NombreImagen($inv->barra)); ?>" 
                                        width="100%" height="100%" class="img-responsive" 
                                        alt="isacom" >
                        
                                    </a>
                                </div>
                            </td>
                            <td>
                                <!-- AGREGAR A CARRO DE COMPRA -->
                                <div class="col-xs-12 input-group" id="idAgregar-<?php echo e($iFila); ?>" >
                                    <input style="text-align: center; color: #000000; width: 71px;" 
                                        id="idPedir-<?php echo e($iFila); ?>" 
                                        value="" 
                                        class="form-control" >
                                    <span class="input-group-btn BtnAgregar">
                                        
                                        <button type="button" class="btn btn-pedido

                                        <?php if(VerificarCarrito($inv->barra)): ?>
                                            colorResaltado
                                        <?php endif; ?>

                                        " data-toggle="tooltip" title="Agregar al carrito"
                                        id="idBtnAgregar-<?php echo e($iFila); ?>" >
                                            <span class="fa fa-cart-plus" 
                                                id="idAgregar-<?php echo e($iFila); ?>" 
                                                aria-hidden="true">
                                            </span>
                                        </button>
                                    
                                    </span>
                                </div>
                            </td>
                            <td><?php echo e($inv->desprod); ?></td>
                            <td><?php echo e($inv->barra); ?></td>
                            <td><?php echo e($inv->marca); ?></td>
                            <td align="right"><?php echo e($inv->bulto); ?></td>
                            <td align="right"><?php echo e(number_format($inv->iva, 2, '.', ',')); ?></td>
                       
                            <td align="right"
                                style="background-color: #FEE3CB;"
                                title = "TRANSITO">
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
                                title = "PRFECIO">
                                <?php echo e(number_format($precio, 2, '.', ',')); ?>

                            </td>
                            <td align="right"
                                style="background-color: #FEE3CB;"
                                title = "VMD">
                                <?php echo e(number_format($vmd, 4, '.', ',')); ?>

                            </td>
                            <td align="right"
                                style="background-color: #FEE3CB;
                                color: <?php echo e($respDias['color']); ?>"
                                title="<?php echo e($respDias['title']); ?>" >
                                <?php echo e(number_format($dias, 0, '.', ',')); ?>

                            </td>
                            <td align="right"
                                style="background-color: #FEE3CB; color: #000000"
                                title="SUGERIDO PARA 15 DIAS" >
                                <?php echo e(number_format($sug15, 0, '.', ',')); ?>

                            </td> 
                            <td align="right"
                                style="background-color: #FEE3CB; color: #000000"
                                title="SUGERIDO PARA 30 DIAS" >
                                <?php echo e(number_format($sug30, 0, '.', ',')); ?>

                            </td> 
                            <td align="right"
                                style="background-color: #FEE3CB; color: #000000"
                                title="SUGERIDO PARA 60 DIAS" >
                                <?php echo e(number_format($sug60, 0, '.', ',')); ?>

                            </td> 
                       
                            <td align='right'
                                style="background-color: #FCD0C7;">
                                <?php echo e(number_format($mpp, 2, '.', ',')); ?>

                            </td>
                            <td align='right' 
                                style="background-color: #FCD0C7;">
                                <?php echo e(number_format($invconsol, 0, '.', ',')); ?>

                            </td>
                     
                        </tr>
                   <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </table>
            <i class="fa fa-arrow-up text-blue" 
                style="font-size: 16px;">
            </i>&nbsp;tendencia a la alta&nbsp;&nbsp;
            <i class="fa fa-arrow-down text-red" 
                style="font-size: 16px;">
            </i>&nbsp;tendencia a la baja &nbsp;&nbsp;
            <i class="fa fa-circle text-yellow" 
                style="font-size: 16px;">
            </i>&nbsp;tendencia estable

            <div align='left'>
                <?php echo e($invent->appends(["filtro" => $filtro])->links()); ?>

            </div><br>
      
        </div>
    </div>
</div>

<?php if( isset($invent) ): ?>
    <?php if($invent->count() == 0): ?>
        <div class="row">
            <center><h2>Inventario de productos vacio</h2></center>
            <br><br><br><br><br><br>
        </div>
    <?php endif; ?>
<?php else: ?>
    <div class="row">
        <center><h2>Inventario de productos vacio</h2></center>
        <br><br><br><br><br><br>
    </div>
<?php endif; ?>
<?php $__env->startPush('scripts'); ?>

<script>
$('#subtitulo').text('<?php echo e($subtitulo); ?>');
window.onload = function() {
    $('.BtnAgregar').on('click',function(e) {
        var idpedido = e.target.id.split('-');
        var loop = idpedido[1].trim();
        var pedir = $('#idPedir-'+loop).val();
        var id = '<?php echo e($tabla->id); ?>';
        var codprove = '<?php echo e($marca); ?>';
        var tprnk1 = 'D';
        var contItem = '<?php echo e($contItem); ?>';
        var item = 0;
        var simboloMoneda = '<?php echo e($cfg->simboloMoneda); ?>';
        var simboloOM = '<?php echo e($cfg->simboloOM); ?>';
        var tasacambiaria = '<?php echo e($cfg->tasacambiaria); ?>';
        var elementStyles = window.getComputedStyle(document.getElementById('idaccion'));
        var color5 = elementStyles.getPropertyValue("--main-color5").trim();
        var Lcolor5 = elementStyles.getPropertyValue("--main-lcolor5").trim();
        var tableReg = document.getElementById('idTabla');
        cellsOfRow = tableReg.rows[ parseInt(loop)+1].getElementsByTagName('td');
        var barra = cellsOfRow[4].innerHTML;
        //alert("CLICK: " + barra + " - FILA: " + loop + " - PEDIR: " + pedir + " - CODPROVE: " + codprove + " -TPRNK1: " + tprnk1 + " ID: " + id);
        if (parseInt(pedir) <= 0) {
            alert("CANTIDAD A PEDIR NO PUEDE SER MENOR O IGUAL CERO");
            $('#idPedir-'+loop).val(' ');
        } else {
            var jqxhr = $.ajax({
                type:'POST',
                url: '../agregar',
                dataType: 'json', 
                encode  : true,
                data: {barra:barra, id:id, pedir:pedir, codprove:codprove, tprnk1:tprnk1 },
                success:function(data) {
                    if (data.resp != "") {
                        alert(data.msg);
                    } else {
                        if (data.msg != "") 
                            alert(data.msg);
                        $('#idPedir-'+loop).val('');
                        $("#totpedido").text(simboloMoneda + ' ' + number_format(data.total, 2, '.', ','));
                        $("#totpedidoDolar").text(simboloOM + number_format(data.total/tasacambiaria, 2, '.', ','));
                        $("#contreng").text(number_format(data.item, 0, '.', ','));
                        var y = 'idBtnAgregar-'+loop;
                        var btn = document.getElementById(y);
                        btn.style.backgroundColor = color5;
                        btn.style.color = Lcolor5;
                        item = data.item; 
                    }
               }
            });
            //alert( contItem + ' - ' + item );
            jqxhr.always(function() {
                if (contItem == 0 && item > 0) {
                    window.location.reload(); 
                }
            });
        }
    });
}

$(document).keypress(function(e) {
   if(e.which == 13) {
        vAceptar();
   }
});

function vAceptar() {
    var tableReg = document.getElementById('idTabla');
    var id = '<?php echo e($tabla->id); ?>';
    var contItem = '<?php echo e($contItem); ?>';
    var resultado = 0;
    var simboloMoneda = '<?php echo e($cfg->simboloMoneda); ?>';
    var simboloOM = '<?php echo e($cfg->simboloOM); ?>';
    var tasacambiaria = '<?php echo e($cfg->tasacambiaria); ?>';
    var elementStyles = window.getComputedStyle(document.getElementById('idaccion'));
    var color5 = elementStyles.getPropertyValue("--main-color5").trim();
    var Lcolor5 = elementStyles.getPropertyValue("--main-lcolor5").trim();
    var codprove = '<?php echo e($marca); ?>';
    //alert("TIPO: " + tipo + " - TPACTIVO: " + tpactivo);
    for (var fila = 1; fila < tableReg.rows.length; fila++) {
        var pedir = $('#idPedir-'+fila).val();
        if (parseInt(pedir) > 0) {
            var cellsOfRow = tableReg.rows[fila+1].getElementsByTagName('td');
            var barra = cellsOfRow[4].innerHTML;
            var tprnk1 =  'D';
            var loop = fila;
            //alert("CLICK: " + barra + " - FILA: " + fila + " - PEDIR: " + pedir + " - CODPROVE: " + codprove + " -TPRNK1: " + tprnk1);
            resultado = 0;
            var jqxhr = $.ajax({
                type:'POST',
                url: '../agregar',
                dataType: 'json', 
                encode  : true,
                data: {barra:barra, id:id, pedir:pedir, codprove:codprove, tprnk1:tprnk1 },
                success:function(data) {
                    if (data.resp != "") {
                        alert(data.msg);
                        resultado = 0;
                    } else {
                        if (data.msg != "") 
                            alert(data.msg);
                        $('#idPedir-'+loop).val(' ');
                        $("#totpedido").text(simboloMoneda + ' ' + number_format(data.total, 2, '.', ','));
                        $("#totpedidoDolar").text(simboloOM + number_format(data.total/tasacambiaria, 2, '.', ','));
                        $("#contreng").text(number_format(data.item, 0, '.', ','));
                        resultado=1;
                    }
                }
            });
            $('#idPedir-'+loop).val(' ');
            var y = 'idBtnAgregar-'+loop;
            var btn = document.getElementById(y);
            btn.style.backgroundColor = color5;
            btn.style.color = Lcolor5;
        }
    }
    jqxhr.always(function() {
        if (parseInt(contItem) == 0) {
            location.reload(true);
        }
    });
}
</script>

<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/isacom/pedidodirecto/inventario.blade.php ENDPATH**/ ?>