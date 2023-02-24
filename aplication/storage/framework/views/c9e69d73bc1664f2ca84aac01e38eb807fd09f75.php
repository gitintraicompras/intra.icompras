
<?php $__env->startSection('contenido'); ?>
<div id="page-wrapper">
   
    <div class="container" style="width: 100%;">

  		<?php $x=0; ?>
    	<?php $__currentLoopData = $provs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prov): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if(verificarProveNuevo($prov->codprove)==0): ?>
        	<?php 
        		$confprov = LeerProve($prov->codprove); 
                if (is_null($confprov))
                    continue;
        		if ($x > 2) {
        			echo "<div class='clearfix'></div>"; 
        			$x=0;
        		}
        		$x++;
        	?> 
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12" >
                <div class="panel" 
                    style="background-color: <?php echo e($confprov->backcolor); ?>; 
                    color: <?php echo e($confprov->forecolor); ?>; border-radius: 15px;">
                    <div class="panel-heading" 
                        style="height: 160px;">
                        <div class="row">
                            <a href="http://<?php echo e($prov->web); ?>">
                                <div class="col-xs-3">
                                    
                                    <div align="center">
                                        <a href="">
                                            <img src="http://isaweb.isbsistemas.com/public/storage/prov/<?php echo e($prov->rutalogo1); ?>" 
                                            width="100%" 
                                            height="100%" 
                                            class="img-responsive" 
                                            alt="icompras360"
                                            style="border: 2px solid #D2D6DE;"
                                            oncontextmenu="return false" >
                                        </a>
                                    </div>
                                    <div data-toggle="tooltip" title="Contador de productos">
                                    	iTEM: <?php echo e(number_format(ObtenerContadorProd($prov->codprove), 0, '.', ',')); ?><br>
                                        <?php echo e(substr($prov->region,4, strlen($prov->region)-4)); ?>

                                    </div>

                   
                                </div>
                            </a>
                            <div class="col-xs-9 text-right">
                                <div style="font-size: 20px;" data-toggle="tooltip" title="Nombre del proveedor"> 
                                	<?php echo e(strtoupper($prov->descripcion)); ?>

                                </div>

                                <div data-toggle="tooltip" title="Código del proveedor">
                                    <?php echo e($prov->codprove); ?></div>
                                <div style="font-size: 20px;" data-toggle="tooltip" title="Sede destino">
                                	SEDE: <?php echo e($prov->codsede); ?>

                                </div>
                                <div style="font-size: 20px;" data-toggle="tooltip" title="Origen de creación">
                                    ORIGEN: <?php echo e($prov->origen); ?>

                                </div>
                                <div data-toggle="tooltip" title="Fecha sincronización del catálogo">
                                	<?php echo e(date('d-m-Y H:i', strtotime($prov->fechasinc))); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                    <a href=""http://<?php echo e($prov->web); ?>">
                        <div class="panel-footer" 
                            style="height: 260px; color: #000000; border-radius: 0 0 15px 15px;" >
                            <div> <cener> <b> <?php echo e(strtoupper($prov->nombre)); ?> </b> </cener> </div>
                            <div>DIRECCION: <b> <?php echo e(strtoupper($prov->direccion)); ?> </b> </div>
                            <div>LOCALIDAD: <b> <?php echo e(strtoupper($prov->localidad)); ?> </b> </div>
                            <div>CONTACTO: <b> <?php echo e(strtoupper($prov->contacto)); ?> </b> </div>
                            <div>TELEFONO: <b> <?php echo e($prov->telefono); ?> </b> </div>
                            <div>CORREO: <b> <?php echo e($prov->correo); ?> </b> </div>
                            <div>WEB: 
                                <a href="http://<?php echo e($prov->web); ?>"> <b><?php echo e($prov->web); ?></b> </a>      
    			            </div> 

			            	<?php echo e(Form::open(array('action' => array('ProveedorController@agregar', $prov->codprove)))); ?>

			            	<button style="margin-top: 10px; border-radius: 5px;" 
                                type="submit" 
                                class="btn-normal" 
                                data-dismiss="modal" 
                                data-toggle="tooltip" 
                                title="Agregar a mi lista de proveedores">
                                Agregar
			            	</button>
							<?php echo e(Form::Close()); ?>

			
                        </div>
                    </a>
                </div>
            </div>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    </div>

</div>


<?php $__env->startPush('scripts'); ?>
<script>
$('#subtitulo').text('<?php echo e($subtitulo); ?>');
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/isacom/proveedor/lista.blade.php ENDPATH**/ ?>