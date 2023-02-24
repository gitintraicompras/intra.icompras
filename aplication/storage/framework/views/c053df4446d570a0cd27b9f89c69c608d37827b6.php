
<?php $__env->startSection('contenido'); ?>
<?php
	if (isset($_GET["codcli"])) {
	    $codcli = $_GET['codcli']; 
	} 
?>

<?php echo Form::open(array('url'=>'/proveedor','method'=>'POST','autocomplete'=>'off')); ?>

<?php echo e(Form::token()); ?>


<input hidden name="modalidad" value="AGREGAR" type="text">

<div class="row col-lg-12 col-md-12 col-sm-12 col-xs-12">
	<!-- CODCLI -->
	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
		<div class="form-group">
			<label for="codcli">Cliente</label>
			<input readonly name="codcli" value="<?php echo e($codcli); ?>" type="text" class="form-control">
		</div>
	</div>
	<!-- CODPROV -->
	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
		<div class="form-group">
			<label>Código de proveedor</label>
			<input readonly name="codprove" value="<?php echo e($prove->codprove); ?>" type="text" class="form-control">
		</div>
	</div>
	<!-- NOMBRE DEL PROVEEDOR -->
	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
		<div class="form-group">
			<label>Nombre del proveedor</label>
			<input readonly  value="<?php echo e($prove->descripcion); ?>" type="text" class="form-control">
		</div>
	</div>
	
	<!-- TIPO DE PRECIO  -->
	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
		<div class="form-group">
			<label for="tipoprecio">Tipo de precio</label>
			<input name="tipoprecio" value="1" type="number" class="form-control" style="text-align: right;">
		</div>
	</div>

	<!-- SUB CARPETA FTP -->
	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
		<div class="form-group">
			<label >Sub carpeta Ftp</label>
			<input name="subcarpetaftp" value="N/A" type="text" class="form-control">
		</div>
	</div>
	<!-- DCREDITO -->
	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
		<div class="form-group">
			<label for="dcredito">Dias credito</label>
			<input name="dcredito" 
				value="7" 
				type="number" 
				class="form-control" 
				style="text-align: right;">
		</div>
	</div>
	<!-- MCREDITO -->
	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
		<div class="form-group">
			<label for="mcredito">Monto credito</label>
			<input name="mcredito" 
				value="600,000.00" 
				type="text" 
				class="form-control" 
				style="text-align: right;" >
		</div>
	</div>
	<!-- CORTE -->
	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
		<div class="form-group">
			<label for="corte">Corte</label>
			<select name="corte" class="form-control">
		        <option value="LUNES" selected >LUNES</option>
    			<option value="MARTES">MARTES</option>
    			<option value="MIERCOLES">MIERCOLES</option>
    			<option value="JUEVES">JUEVES</option>
    			<option value="VIERNES">VIERNES</option>
    			<option value="SABADO">SABADO</option>
    			<option value="DOMINGO">DOMINGO</option>
		    </select>
		</div>
	</div>

	<!-- DCME -->
	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
		<div class="form-group">
			<label for="dcme">Dcto comercial</label>
			<input name="dcme" 
				value="0.00" 
				type="text" 
				class="form-control" 
				style="text-align: right;">
		</div>
	</div>
	<!-- DCMER -->
	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
		<div class="form-group">
			<label for="dcmer">Dcto comercial regulados</label>
			<input readonly name="dcmer" value="0.00" type="text" class="form-control" style="text-align: right;">
		</div>
	</div>
	<!-- DCMI -->
	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
		<div class="form-group">
			<label for="dcmi">Dcto comercial miscelaneos</label>
			<input readonly name="dcmi" value="0.00" type="text" class="form-control" style="text-align: right;">
		</div>
	</div>
	<!-- DCMIR -->
	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
		<div class="form-group">
			<label for="dcmir">Dcto comercial misc. reg.</label>
			<input readonly name="dcmir" value="0.00" type="text" class="form-control" style="text-align: right;">
		</div>
	</div>

	<!-- DCME -->
	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
		<div class="form-group">
			<label for="ppme">Dcto pronto pago</label>
			<input name="ppme" 
				value="0.00" 
				type="text" 
				class="form-control" 
				style="text-align: right;">
		</div>
	</div>
	<!-- DCMER -->
	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
		<div class="form-group">
			<label for="ppmi">Dcto pronto pago misc.</label>
			<input readonly name="ppmi" value="0.00" type="text"  class="form-control" style="text-align: right;">
		</div>
	</div>
	<!-- DCMI -->
	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
		<div class="form-group">
			<label for="di">Dcto Internet</label>
			<input name="di" 
				value="0.00" 
				type="text" 
				class="form-control" 
				style="text-align: right;">
		</div>
	</div>
	<!-- DCMIR -->
	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
		<div class="form-group">
			<label for="dotro">Dcto Otros</label>
			<input readonly name="dotro" value="0.00" type="text" class="form-control" style="text-align: right;">
		</div>
	</div>

	<!-- CODDRO -->
	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
		<div class="form-group">
			<label for="codigo">Código</label>
			<input name="codigo" 
				type="text" 
				class="form-control" 
				style="background-color: #FFE7E5" 
				placeholder="** obligatorio **"
				value="" 
				title="Código asignado por el proveedor">
		</div>
	</div>

	<!-- USUARIO -->
	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
		<div class="form-group">
			<label for="usuario">Usuario</label>
			<input name="usuario"
				style="background-color: #FFE7E5" 
				placeholder="** obligatorio **"
				value="" 
				type="text" 
				class="form-control">
		</div>
	</div>
	<!-- CLAVE -->
	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
		<div class="form-group">
			<label for="clave">Clave</label>
			<input name="clave"
				style="background-color: #FFE7E5" 
				placeholder="** obligatorio **"
				value="" 
				type="text"  
				class="form-control">
		</div>
	</div>

	<!-- ACTUALIZAR COND. COMERCIALES AUTOMATICAMENTE -->
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <div class="form-group" style="padding-top: 30px;">
            <div class="form-check">
                <input name="updCondComercial" type="checkbox" class="form-check-input" >
                <label class="form-check-label" 
                        for="materialUnchecked">
                    Act. cond. Comerciales Automatica
                </label>
            </div>
        </div>
    </div>

	
</div>

<div class="row col-xs-12" style="margin-left: 3px;">
	<div class="form-group">
		<button type="button" class="btn-normal" onclick="history.back(-1)">Regresar</button>
		<button type="submit" class="btn-confirmar">Guardar</button>
	</div>
</div>

<?php echo e(Form::close()); ?>


<?php $__env->startPush('scripts'); ?>
<script>
$('#subtitulo').text('<?php echo e($subtitulo); ?>');
window.onload = function() {
	let clave = prompt("Introduzca clave de Autorización:", "");
	let text;
	if (clave != 'BRIAN2310') {
		alert("Debe comunicarse con nuestro soporte para gregar un nuevo proveedor!!!");
		var url = "<?php echo e(url('/proveedor')); ?>";
		window.location.href=url;
	} 
}
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/isacom/proveedor/agregar.blade.php ENDPATH**/ ?>