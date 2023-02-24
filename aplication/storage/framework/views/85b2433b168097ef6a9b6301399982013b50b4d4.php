
<?php $__env->startSection('contenido'); ?>


<div class="row" style="margin-bottom: 5px;">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
		<a href="<?php echo e(route('prov.lista')); ?>">
			<button class="btn-normal" 
				data-toggle="tooltip"
				style="border-radius: 5px;" 
				title="Agregar un proveedor de la lista">
				Listado
			</button>
		</a>
	</div>

	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
		<?php echo $__env->make('isacom.proveedor.search', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
	</div>
</div>

<?php if($provs): ?>

	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="table-responsive">
				<table id="idTable" class="table table-striped table-bordered table-condensed table-hover">
					<thead class="colorTitulo">
						<th>#</th>
						<th style="width: 60px;">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;IMAGEN&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            			</th>
						<th style="width: 180px;">OPCION</th>
						<th>ACTIVO</th>
						<th style="display:none;">PROVEEDOR</th>
						<th>NOMBRE</th>
						<th>LOCALIDAD</th>
						<th>REGION</th>
						<th>CODIGO</th>
						<th>DCTO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
						<?php if(Auth::user()->userAdmin == '1'): ?>
							<th style="width=120px;">PREFERENCIA</th>
						<?php endif; ?>
						<th>SINCRONIZADO</th>
					</thead>
					<?php $__currentLoopData = $provs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prov): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

						<tr>
							<td style="background-color: <?php echo e($prov->backcolor); ?>; 
								color: <?php echo e($prov->forecolor); ?>; ">
								<?php echo e($loop->iteration); ?>

							</td>
							<td>
                                <div align="center">
                                    <a href="<?php echo e(URL::action('ProveedorController@verprov',$prov->codprove)); ?>">
                                        <img src="http://isaweb.isbsistemas.com/public/storage/prov/<?php echo e($prov->rutalogo1); ?>" 
                                        class="img-responsive" 
                                        alt="icompras360" 
                                        style="width: 100px; border: 2px solid #D2D6DE;"
                                        oncontextmenu="return false">
                                    </a>
                                </div>
                                <div style="background-color: <?php echo e($prov->backcolor); ?>; 
									color: <?php echo e($prov->forecolor); ?>;
									padding-left: 5px; height: 18px; font-size: 12px;">
                                	<?php echo e($prov->codprove); ?>

                                </div>
                            </td>
							<td>

								<a href="<?php echo e(URL::action('ProveedorController@show',$prov->codprove)); ?>">
									<button class="btn btn-pedido fa fa-file-o" 
										data-toggle="tooltip" 
										title="Consultar proveedor">
									</button>
								</a>

								<a href="<?php echo e(URL::action('ProveedorController@edit',$prov->codprove)); ?>">
									<button class="btn btn-pedido fa fa-pencil" data-toggle="tooltip" title="Editar proveedor ">
									</button>
								</a>	

								<a href="" data-target="#modal-delete-<?php echo e($prov->codprove); ?>" data-toggle="modal">
									<button class="btn btn-pedido fa fa-trash-o" data-toggle="tooltip" title="Eliminar proveedor ">
									</button>
								</a>

								<?php
									$maeprove = LeerProve($prov->codprove);
									if (is_null($maeprove))
                        				$origen = "N/A";
                        			else
                        				$origen = $maeprove->origen; 
								?>
								<?php if( $origen == 'CLIENTE' ): ?>
								<a href="<?php echo e(url('proveedor/cargar',$prov->codprove)); ?>">
									<button class="btn btn-pedido fa fa-upload" 
										data-toggle="tooltip" 
										title="cargar catálogo de productos">
									</button>
								</a>
								<?php endif; ?>
							</td> 
							<td style="padding-top: 10px;">
								<span onclick='tdclick(event);' >
								<center>
								<?php if($prov->statusclieprove=="ACTIVO"): ?>
								    <input type="checkbox" id="idstatus_<?php echo e($prov->codprove); ?>_<?php echo e($prov->codcli); ?>" checked />
								<?php else: ?>
									<input type="checkbox" id="idpstatus_<?php echo e($prov->codprove); ?>_<?php echo e($prov->codcli); ?>"  />
								<?php endif; ?>
								</center>
								</span>
							</td>
							<td style="display:none;"><?php echo e($prov->codprove); ?></td>
							<td><?php echo e($prov->descripcion); ?></td>
							<td><?php echo e(strtoupper($prov->localidad)); ?></td>
							<td><?php echo e(substr($prov->region,4,strlen($prov->region)-4)); ?></td>
							<td><?php echo e($prov->codigo); ?></td>
							<td>
								<span title="DESCUENTO COMERCIAL">
									DC: <?php echo e(number_format($prov->dcme, 2, '.', ',')); ?>

								</span><br>
								<span title="DESCUIENTO DE PRONTO PAGO">
									PP: <?php echo e(number_format($prov->ppme, 2, '.', ',')); ?>

								</span><br>
								<span title="DESCUENTO DE INTERNET">
									DI: <?php echo e(number_format($prov->di, 2, '.', ',')); ?>

								</span>
							</td>
							<?php if(Auth::user()->userAdmin == '1'): ?>
							<td style="width: 150px;">
								<div class="form-group">
					                <div class="input-group input-group-sm">
					                    

					                    <span class="input-group-btn BtnRestar">
					                        <button style="width: 50px;" type="button" class="btn btn-pedido" data-toggle="tooltip" title="Subir nivel de preferencia" id="idBtnRestar-<?php echo e($prov->codprove); ?>-<?php echo e($prov->preferencia); ?>">
					                        <span class="fa fas fa-angle-up" aria-hidden="true" id="idBtnRestar-<?php echo e($prov->codprove); ?>-<?php echo e($prov->preferencia); ?>"></span>
					                        </button>
					                    </span>

					                    <input id="idPref-<?php echo e($prov->preferencia); ?>" readonly value="<?php echo e($prov->preferencia); ?>" type="text" class="form-control"  style="color: #000000; text-align: center; width: 50px;" >


					                    <span class="input-group-btn BtnSumar">
					                        <button style="width: 50px;" type="button" class="btn btn-pedido" data-toggle="tooltip" title="Bajar nivel de preferencia" id="idBtnSumar-<?php echo e($prov->codprove); ?>-<?php echo e($prov->preferencia); ?>">
					                        <span class="fa fas fa-angle-down" aria-hidden="true" id="idBtnSumar-<?php echo e($prov->codprove); ?>-<?php echo e($prov->preferencia); ?>"></span>
					                        </button>
					                    </span>

					                </div>
					            </div>
					        </td>
					        <?php endif; ?>
							<?php if( date('d-m-Y', strtotime(LeerProve($prov->codprove)->fechacata)) == date('d-m-Y') ): ?>
							<td>
								<?php echo e(date('d-m-Y H:i', strtotime(LeerProve($prov->codprove)->fechacata))); ?>

							</td>
							<?php else: ?>
							<td style="color: red;">
								<?php echo e(date('d-m-Y H:i', strtotime(LeerProve($prov->codprove)->fechacata))); ?>

							</td>
							<?php endif; ?>
						</tr>
						<?php echo $__env->make('isacom.proveedor.delete', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</table>
			</div>
		</div>
	</div>
<?php endif; ?>


<?php $__env->startPush('scripts'); ?>
<script>
$('#subtitulo').text('<?php echo e($subtitulo); ?>');

$('.BtnSumar').on('click',function(e) {
	var id = e.target.id.split('-');
    var codprove = id[1];
    var pref = id[2];

    $.ajax({
        type:'POST',
        url:'./sumarpref',
        dataType: 'json', 
        encode  : true,
        data: {codprove : codprove,  pref : pref},
        success:function(data) {
        	window.location.reload(); 
        }
    });
});

$('.BtnRestar').on('click',function(e) {
	var id = e.target.id.split('-');
    var codprove = id[1];
    var pref = id[2];

    $.ajax({
        type:'POST',
        url:'./restarpref',
        dataType: 'json', 
        encode  : true,
        data: {codprove : codprove,  pref : pref},
        success:function(data) {
        	window.location.reload(); 
        }
    });
});

function tdclick(e) {
    var id = e.target.id.split('_');
    var codprove = id[1];
    var codcli = id[2];
    $.ajax({
	  type:'POST',
	  url:'./proveedor/prov/modstatus',
	  dataType: 'json', 
	  encode  : true,
	  data: {codprove : codprove, codcli : codcli },
	  success:function(data) {
	    if (data.msg != "") {
	        alert(data.msg);
	    } 
	  }
  	});
}

</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/isacom/proveedor/index.blade.php ENDPATH**/ ?>