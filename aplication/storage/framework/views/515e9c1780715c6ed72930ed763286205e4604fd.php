
<?php $__env->startSection('contenido'); ?>
<?php
  $moneda = Session::get('moneda', 'BSS');
  $factor = RetornaFactorCambiario($cliente->codcli, $moneda);
?>

<div class="row" style="margin-bottom: 5px;">
	<div class="col-lg-4 col-md-6 col-sm-6 col-xs-6">
		<?php echo $__env->make('ofertas.sugofertas.search', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
	</div>
	<div class="row">
        <div class="form-group">
            <button type="button" class="btn-normal" onclick="history.back(-1)">Regresar</button>
            <?php if($moneda == 'BSS'): ?>
                <a href="" data-target="#modal-guardar" data-toggle="modal">
                    <button class="btn-confirmar" data-toggle="tooltip" title="Guardar oferta">
                    Guardar
                    </button>
                </a>
                <?php echo $__env->make('ofertas.sugofertas.guardar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table id="idtabla" class="table table-striped table-bordered table-condensed table-hover">
                <thead style="background-color: #b7b7b7;">
                    <th style="vertical-align: middle;"> 
                        #
                    </th>
                    <th style="width: 70%; vertical-align: middle;">
                    	DESCRIPCION
                    </th>
                    <th style="vertical-align: middle;">
                        CODIGO
                    </th>
                    <th style="vertical-align: middle;">
                        REFERENCIA
                    </th>
                    <th style="vertical-align: middle;">
                        MARCA
                    </th>
                    <th style="background-color: #FECC9E; 
                        vertical-align: middle;">
                        COSTO
                    </th>
                    <th style="background-color: #FDBF87;
                        vertical-align: middle;"
                        title="Porcentaje de Utilidad">
                        UTIL(%)
                    </th>
                    <th style="background-color: #FEB370; 
                        vertical-align: middle;">
                        PRECIO(<?php echo e($cliente->usaprecio); ?>)
                    </th>
                    <th style="background-color: #FEA95C;
                    	width: 110px; 
                        vertical-align: middle;"
                        title="Porcentaje de Descuento Adicional Sugerido">
                        DA SUGERIDO(%)
                    </th>

                    <th style="background-color: #FEA95C; vertical-align: middle;" >
                        <center>
                        <div style="width: 70px;">
                            <span class="input-group-btn">
                                <div class="col-xs-12 input-group" >
                                    <input type="checkbox" 
                                        id="selectall"
                                        title="marcar/desmarcar todos los producto"
                                        style="width: 50%; vertical-align:middle;">

                                    <button style="vertical-align:middle; 
                                        text-align: center;
                                        width: 50%; 
                                        height: 30px;" 
                                        type="button" 
                                        id="delall"
                                        class="btn btn-pedido" 
                                        title="Eliminar producto marcados" >
                                        <span class="fa fa-trash-o" >
                                        </span>
                                    </button>
                                </div>
                            </span>
                        </div>
                        </center>
                    </th>

                    <th style="background-color: #FD9E46;
                        color: #ffffff;
                        width: 10%; vertical-align: middle;">
                        PS
                    </th>
                    <th title="Mejor precio de la competencia" 
                        style="background-color: #FE9232; 
                               width: 10%;
                               color: #ffffff; vertical-align: middle;">
                               MPC
                    </th>
                    <th style="background-color: #FD9E46;
                        color: #ffffff;
                        width: 10%; vertical-align: middle;">
                        PROVEEDOR
                    </th>
                </thead>
                <?php
                $iFila = 0;
                ?>
	            <?php $__currentLoopData = $sugoferen; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sug): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
		            <?php
		            $iFila++;
		            ?>
	                <tr>
	                	<td style="background-color: <?php echo e($sug->backcolor); ?>; 
	                        color: <?php echo e($sug->forecolor); ?>;"
	                        title = "<?php echo e($sug->title); ?>" >
	                        <?php echo e($iFila); ?>

	                    </td>
	                    <td><?php echo e($sug->desprod); ?></td>
	                    <td><?php echo e($sug->codprod); ?></td>
	                    <td><?php echo e($sug->barra); ?></td>
                        <td><?php echo e($sug->marca); ?></td>
	                    <!-- COSTO -->
                        <td align="right" 
                            style="background-color: #FECC9E;"
                            title="COSTO"
                            id="costo-<?php echo e($sug->item); ?>">
                            <?php echo e(number_format($sug->costo/$factor, 2, '.', ',')); ?>

                        </td>
                        <!-- UTILIDAD -->
                        <td align="right" 
                            style="background-color: #FDBF87;
                            <?php if($sug->util < $cliente->utilm): ?>
                                color: red; 
                            <?php endif; ?>"
                            title="MARGEN DE UTILIDAD"
                            id="util-<?php echo e($sug->item); ?>">
                            <?php echo e(number_format($sug->util, 2, '.', ',')); ?>%
                        </td>
                        <!-- PRECIO -->
                        <td align="right" 
                            style="background-color: #FEB370;"
                            title="PRECIO"
                            id="precio-<?php echo e($sug->item); ?>">
                            <?php echo e(number_format($sug->precio/$factor, 2, '.', ',')); ?>

                        </td>
                        <!-- DA -->
                     	<td>
                            <div class="col-xs-12 input-group"
                            	 style = "vertical-align: middle;" >
                                <input name="daprod[]"
                                	style="text-align: right; 
                                	color: #000000; 
                                	width: 60px;" 
                                	value="<?php echo e(number_format($sug->dasug, 0, '.', ',')); ?>"
                                	class="form-control"
                                    id="da-<?php echo e($sug->item); ?>" >
                                <span class="input-group-btn" onclick='tdclickOferta(event);'>
                                    <button id="idBtn1-<?php echo e($sug->item); ?>" 
                                    	type="button" 
                                    	class="btn btn-pedido" 
                                    	data-toggle="tooltip" 
                                    	title="Modificar oferta" >
                                        <span class="fa fa-check" 
                                            aria-hidden="true" 
                                            id="idBtn2-<?php echo e($sug->item); ?>">
                                        </span>
                                    </button> 
                                </span>
                                <span class="input-group-btn" >
                                    <a href="" 
	                                	data-target="#modal-delete-<?php echo e($sug->item); ?>" 
	                                	data-toggle="modal">
	                                	<button id="idBtnDel1-<?php echo e($sug->item); ?>" 
	                                    	type="button" 
	                                    	class="btn btn-pedido" 
	                                    	data-toggle="tooltip" 
	                                    	title="Eliminar oferta" >
	                                        <span class="fa fa-trash-o" 
	                                            aria-hidden="true" 
	                                            id="idBtnDel2-<?php echo e($sug->item); ?>">
	                                        </span>
	                                    </button> 
									</a>
                                </span>
                            </div>
                        </td>
	                  	<td style="padding-top: 10px;  
                            text-align: center;
                            vertical-align: middle;">
                                <input onclick='case(event);'
                                    class="case"
                                    type="checkbox" 
                                    id='product-<?php echo e($sug->item); ?>' />
                        </td>
                        <!-- PS -->
                        <td align="right" 
                            style="background-color: #FD9E46;
                            color: #ffffff;
                            font-size: 20px;
                            width: 10%;
                            vertical-align: middle;
                            <?php if(($sug->ps/$factor) > $sug->mpc): ?>
                                color: red; 
                            <?php endif; ?>"
                            title="PRECIO SUGERIDO"
                            id="ps1-<?php echo e($sug->item); ?>">
                            <?php echo e(number_format($sug->ps/$factor, 2, '.', ',')); ?>

                        </td>
                        <!-- MPC -->
                  		<td align="right" 
                            style="background-color: #FE9232;
                            color: #ffffff;
                            font-size: 20px;
                            width: 10%;
                            vertical-align: middle;"
                            id="mpc-<?php echo e($sug->item); ?>"
                            title="MEJOR PRECIO DE LA COMPETENCIA">
                            <?php echo e(number_format($sug->mpc, 2, '.', ',')); ?>

                        </td>
	                  	<td style="background-color: #FE9232;
                            color: #ffffff;
                            font-size: 14px;
                            width: 10%;
                            vertical-align: middle;">
	                  		<?php echo e(sCodprovCifrado($sug->codmpc)); ?>

	                  	</td>		
                    </tr>
                    <?php echo $__env->make('ofertas.sugofertas.deleprod', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
	            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> 
            </table>
        </div><br>
	</div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
$('#subtitulo').text('<?php echo e($subtitulo); ?>');
window.onload = function() {
    if ($(".case").length == $(".case:checked").length) {
        $("#selectall").prop("checked", true);
    } else {
        $("#selectall").prop("checked", false);
    }
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
        var table = document.getElementById("datos");
        var rows = table.getElementsByTagName('tr');
        for (var ica = 1; ica < rows.length; ica++) {
            var cols = rows[ica].getElementsByTagName('td');
            var item = cols[1].innerHTML;
       }    
    } catch(e) { }
});
$("#delall").click(function() {
    checks = document.querySelectorAll(".case:checked");
    if(checks.length > 0) {
        x = confirm("Estas seguro de eliminar "+checks.length+" elemento(s) ?");
        if (x) {
            checks = document.querySelectorAll(".case");
            for(i=0; i < checks.length; i++) {
                if (checks[i].checked) {
                    p = checks[i].id.split("-");
                    var item = parseInt(p[1]);
                    var jqxhr = $.ajax({
		                type:'POST',
		                url: './sugofertas/delsel',
		                dataType: 'json', 
		                encode  : true,
		                data: {item : item },
      					always:function() {
		                }
		            });
                }
            } 
            jqxhr.always(function() {
			  	alert("ofertas seleccionados se han borrado!!!");
			    window.location.reload();
			});
        }
    } else {
        // Si no hay elementos seleccionados
        alert("No hay productos seleccionados");
    }
});
function tdclickOferta(e) {
    var id = e.target.id.split('-');
    var item = id[1];
    var utilm = '<?php echo e($utilm); ?>';
    var da = $('#da-'+item).val().trim();
    da = parseFloat(da.replace(/,/g, '') ).toFixed(2); 
    var costo = $('#costo-'+item).text().trim();
    costo = parseFloat(costo.replace(/,/g, '') ).toFixed(2);
    var mpc = $('#mpc-'+item).text().trim();
    mpc = parseFloat(mpc.replace(/,/g, '') ).toFixed(2);
    var precio = $('#precio-'+item).text().trim();
    precio = parseFloat(precio.replace(/,/g, '') ).toFixed(2);
    var putil = (-1)*(((costo/(precio - (precio*da/100)) )*100)-100);
    var ps = costo/Math.abs((putil-100)/100);
    var jqxhr = $.ajax({
        type:'POST',
        url: './sugofertas/upddasug',
        dataType: 'json', 
        encode  : true,
        data: {item:item, da:da, ps:ps, util:putil },
		done:function() {
	    }
    });
    jqxhr.always(function() {
	    $("#util-"+item).text(number_format(putil, 2, '.', ',') + '%');
	    $("#ps1-"+item).text(number_format(ps, 2, '.', ','));
	    $("#ps2-"+item).val(number_format(ps, 2, '.', ','));
	    if (ps > mpc)  
	        document.getElementById('ps1-'+item).style.color = "red";
	    else 
	        document.getElementById('ps1-'+item).style.color = "white";
	    if (putil < utilm)  
	        document.getElementById('util-'+item).style.color = "red";
	    else 
	        document.getElementById('util-'+item).style.color = "black";
	    alert("ITEM: " + item + " COSTO: " + costo + " DA: " + da + " PRECIO: " + precio + " UTIL: " + putil + " PS: " + ps);
	});
}


$('#modal-guardar').on('shown.bs.modal', function () {
  $('#idobserv').focus();
})

</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/ofertas/sugofertas/index.blade.php ENDPATH**/ ?>