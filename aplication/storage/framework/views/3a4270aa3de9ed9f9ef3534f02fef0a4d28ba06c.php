
<?php $__env->startSection('contenido'); ?>

<?php echo Form::open(array('url'=>'/pedido','method'=>'POST','autocomplete'=>'off', 'id' => 'form' )); ?>

<?php echo e(Form::token()); ?>

<div class="row">

	<?php if(!empty($pedgrupo)): ?> 
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
	    <div class="form-group">
	    	<label>Pedido</label>
	    	<select name="idpedgrupo" 
	    		class="form-control selectpicker" 
	    		data-live-search="true"
	    		id="SelClickTipo" >
	    		<option value="NUEVO">CREAR PEDIDO NUEVO</option>
	    		<?php $__currentLoopData = $pedgrupo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	    			<option value="<?php echo e($pg->id); ?>"><?php echo e($pg->id); ?>-<?php echo e($pg->marca); ?>-<?php echo e($pg->fecha); ?></option>
	    		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	    	</select>
	    </div>
	</div>
	<?php endif; ?>
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
	    <div class="form-group">
	    	<label>Marca</label>
	    	<select name="codmarca" 
	    		class="form-control selectpicker" 
	    		data-live-search="true"
	    		id="idmarca" >
	    		<?php $__currentLoopData = $marca; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	    			<option value="<?php echo e($m->descrip); ?>"><?php echo e($m->descrip); ?></option>
	    		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	    	</select>
	    </div>
	</div>
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <label>Dias de reposición</label>
			<input type="number" 
				name="reposicion" 
				value="7" 
				id="idreposicion"
				class="form-control">
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <label>Cantidad sugerida en caso de VND=0</label>
			<input type="number" 
				name="cantsug" 
				value="0" 
				id="idcantsug"
				class="form-control">
        </div>
    </div>
	<br>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="form-group">
			<button type="button" class="btn-normal" onclick="history.back(-1)">Regresar</button>
			<button class="btn-confirmar" type="submit">Crear</button>
			
		</div>
	</div>
	<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
</div>
<?php echo e(Form::close()); ?>


<?php $__env->startPush('scripts'); ?>
<script>
$('#subtitulo').text('<?php echo e($subtitulo); ?>');

$('#SelClickTipo').on('change', function() {
    var id = this.value;
    if (id == "NUEVO") {
    	$('#idmarca').removeAttr('disabled');
    	$('#idreposicion').removeAttr('disabled');
    }
   	else {
   		var item = 0;
   		var resp = "";
		var jqxhr = $.ajax({
            type:'POST',
            url: './leerpedgrupo',
            dataType: 'json', 
            encode  : true,
            data: { id:id },
            success:function(q) {
            	resp = q.data;
            }
        });
    	jqxhr.always(function() {
   			var combo = document.getElementById("idmarca");
   			for (var i = 0; i < combo.length; i++) {
			    var opt = combo[i];
			    var marcax = opt.value.trim();
			    var marcay = resp.marca.trim();
			    if (marcax == marcay) {
			    	break;
			    }
			    item = item + 1;
			}
			document.getElementById("idmarca").selectedIndex = item;
			$('#idmarca').change();
			$('#idreposicion').val(resp.reposicion);
			$('#idmarca').prop('disabled', true);
   			$('#idreposicion').prop('disabled', true);
        });
   	}
});

$("#form").on("submit" ,function()
{   
    $('#idmarca').removeAttr('disabled');
    $('#idreposicion').removeAttr('disabled');   
});


</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/isacom/pedido/create.blade.php ENDPATH**/ ?>