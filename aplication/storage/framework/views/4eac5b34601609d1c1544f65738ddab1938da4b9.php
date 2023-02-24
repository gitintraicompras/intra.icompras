
<?php $__env->startSection('contenido'); ?>

<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <select id="SelClickProveedor"
            class="form-control selectpicker" 
            data-live-search="true" >
            <?php $__currentLoopData = $provs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($codprove == $p->codprove): ?>
                    <option selected value="<?php echo e($p->codprove); ?>">
                        <?php echo e($p->codprove); ?>-<?php echo e($p->descripcion); ?>

                    </option>
                <?php else: ?>
                    <option value="<?php echo e($p->codprove); ?>">
                        <?php echo e($p->codprove); ?>-<?php echo e($p->descripcion); ?>

                    </option>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>

        <div class="form-group" style="margin-top: 10px;">
            <?php echo $__env->make('isacom.factura.search', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group">
            <div class="input-group input-group-sm">
                <span class="input-group-addon">Total:</span>
                <input readonly type="text" class="form-control" id="idtot" value="0.00" style="color: #000000; text-align: right; background: #F7F7F7;" placeholder="Monto total">
            </div>
        </div>
    </div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table id="idtabla" class="table table-striped table-bordered table-condensed table-hover">
         
                <thead class="colorTitulo">
                    <th>#</th>
                    <th style="width: 140px;">OPCION</th>
                    <th>FACTURA</th>
                    <th style="width: 100px;">EMISION</th>
                    <th style="width: 100px;">VENCE</th>
                    <th>MONTO</th>
                    <th>IVA</th>
                    <th>TOTAL</th>
	            </thead>
                <?php if(!empty($tabla)): ?>
    	            <?php $__currentLoopData = $tabla; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                    	<td><?php echo e($loop->iteration); ?></td>
                    	<td>

                    		<!-- VER FACTURA -->
                            <a href="<?php echo e(URL::action('FacturaController@show',$t->factnum.'_'.$codprove)); ?>">
                            	<button class="btn btn-pedido fa fa-file-o" 
                                    data-toggle="tooltip" 
                                    title="Consular Factura">
                            	</button>
                            </a>

                            <!-- DESCARGAR FACTURA PDF-->
                            <a href="<?php echo e(URL::action('FacturaController@descargarpdf',$t->factnum.'_'.$codprove)); ?>">
                                <button class="btn btn-pedido fa fa-download" 
                                    data-toggle="tooltip" 
                                    title="Descargar Factura en pdf">
                                </button>
                            </a>

                            <!-- EXPORTAR FACTURA -->
                            <a href="<?php echo e(URL::action('FacturaController@exportar',$t->factnum.'_'.$codprove)); ?>">
                                <button class="btn btn-pedido fa fa-share-square-o" data-toggle="tooltip" title="Exportar factura">
                                </button>
                            </a>
                

                    	</td>
                        <td><?php echo e($t->factnum); ?></td>
                        <td><?php echo e(date('d-m-Y', strtotime($t->fecha))); ?></td>
                        <td><?php echo e(date('d-m-Y', strtotime($t->fechav))); ?></td>
                    	<td align='right'><?php echo e(number_format($t->monto, 2, '.', ',')); ?></td>	
                    	<td align='right'><?php echo e(number_format($t->iva, 2, '.', ',')); ?></td>
                    	<td align='right'><?php echo e(number_format($t->total, 2, '.', ',')); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </table><br>
        </div><br>
	</div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
$('#subtitulo').text('<?php echo e($subtitulo); ?>');
window.onload = function(){
    var tableReg = document.getElementById('idtabla');
    var tot = 0.00;
    // Recorremos todas las filas con contenido de la tabla
    for (var i = 1; i < tableReg.rows.length; i++) {
        cellsOfRow = tableReg.rows[i].getElementsByTagName('td');
        var s1 = cellsOfRow[7].innerHTML;
        var s2 = s1.replace(/,/g, '');
        var inv = parseFloat(s2).toFixed(2);
        tot += parseFloat(inv);
    }
    $("#idtot").val(number_format(tot, 2, '.', ','));
}

$('#SelClickProveedor').on('change', function()
{
    var codigo = this.value;
    $('#idcodprove').val(codigo);
});


</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/isacom/factura/index.blade.php ENDPATH**/ ?>