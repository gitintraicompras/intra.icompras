
<?php $__env->startSection('contenido'); ?>
   
 
<div class="btn-toolbar" role="toolbar" style="margin-top: 12px; margin-bottom: 3px;">
    <div class="btn-group" role="group" style="width: 100%;">

		<?php echo $__env->make('isacom.grupo.search', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
	
		<a href="<?php echo e(url('/invgrupo')); ?>">
		    <button style="width: 170px; height: 34px; border-radius: 5px;" 
		        type="button" 
		        data-toggle="tooltip" 
		        title="Ver inventario del grupo" 
		        class="btn-catalogo">
		    	Ver Inventario grupo
		    </button>
		</a>

		<?php if($tipedido == 'D'): ?>
		<a href="<?php echo e(url('/pedgrupo')); ?>">
		    <button style="width: 170px; height: 34px; border-radius: 5px;" 
		        type="button" 
		        data-toggle="tooltip" 
		        title="Ver pedidos del grupo" 
		        class="btn-catalogo">
		    	Ver Pedidos grupo
		    </button>
		</a>
		<?php endif; ?>

	</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table id="idTable" class="table table-striped table-bordered table-condensed table-hover">
				<thead class="colorTitulo">
					<th>#</th>
					<th style="width: 60px;" class="hidden-xs">IMAGEN</th>
					<th style="width: 100px;">OPCION</th>
					<th title="SUCURSAL ACTIVA">ACTIVO</th>
					<th title="SUCURSAL PREDETERMINADA">PREDET</th>
					<th title="CODIGO DE LA SUCURSAL">CODIGO</th>
					<th title="NOMBRE DE LA SUCURSAL">NOMBRE</th>
					<th title="LOCALIDAD DE LA SUCURSAL">LOCALIDAD</th>
					<th title="ABREVIATURA DE LA SUCURSAL">ABREVIATURA</th>
					<th title="NIVEL DE PRFERENCIA DE LA SUCURSAL" style="width=120px;">PREFERENCIA</th>
					<th title="FECHA DE LA SINCRONIZACION DEL INVENTARIO">SINCRONIZADO</th>
				</thead>
				<?php $__currentLoopData = $gruporen; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<?php
					$tabla = 'inventario_'.$gr->codcli;
				  	$feccatalogo = date("Y-m-d H:i:s", strtotime("2000-01-01 00:00:00"));
	                if (VerificaTabla($tabla)) {
			            $inv = DB::table($tabla)->first();
		                if ($inv)
		                	$feccatalogo = $inv->feccatalogo;
	                }
	                $cliente = DB::table('maecliente')
                    ->where('codcli','=',$gr->codcli)
                    ->first();
                    $abreviatura = $cliente->descripcion;
                    $backcolor = $cliente->backcolor; 
                    $forecolor = $cliente->forecolor; 
					?>
					<tr>
						<td style="background-color: <?php echo e($backcolor); ?>; color: <?php echo e($forecolor); ?>; ">
							<?php echo e($loop->iteration); ?>

						</td>
						<td class="hidden-xs">
                            <div align="center">
                            	<img src="http://isaweb.isbsistemas.com/public/storage//<?php echo e($cliente->rutaimg); ?>" 
                            	class="img-responsive" 
                            	alt="icompras360" 
                            	width="100%"
                            	style="border: 2px solid #D2D6DE;"
                            	oncontextmenu="return false">
                            </div>
                        </td>
						<td>

							<a href="<?php echo e(URL::action('GrupoController@show',$gr->codcli)); ?>">
								<button class="btn btn-pedido fa fa-file-o" data-toggle="tooltip" title="Consultar cliente">
								</button>
							</a>

							<a href="<?php echo e(URL::action('ConfigController@edit',$gr->codcli)); ?>">
								<button class="btn btn-pedido fa fa-pencil" data-toggle="tooltip" title="Editar cliente ">
								</button>
							</a>	
						</td>
						<td style="padding-top: 10px;">
							<span onclick='tdclick(event);' >
							<center>
							<?php if($gr->status=="ACTIVO"): ?>
							    <input type="checkbox" id="idstatus_<?php echo e($gr->id); ?>_<?php echo e($gr->codcli); ?>" checked />
							<?php else: ?>
								<input type="checkbox" id="idpstatus_<?php echo e($gr->id); ?>_<?php echo e($gr->codcli); ?>"  />
							<?php endif; ?>
							</center>
							</span>
						</td>
						<td style="padding-top: 10px;">
							<span onclick='tdclickmodpredet(event);' >
							<center>
							<?php if($gr->predet==1): ?>
							    <input type="checkbox" id="idmodpredet_<?php echo e($gr->id); ?>_<?php echo e($gr->codcli); ?>" 
							    checked />
							<?php else: ?>
								<input type="checkbox" id="idmodpredet_<?php echo e($gr->id); ?>_<?php echo e($gr->codcli); ?>"  />
							<?php endif; ?>
							</center>
							</span>
						</td>
						<td><?php echo e($gr->codcli); ?></td>
						<td><?php echo e($gr->nomcli); ?></td>
						<td><?php echo e($cliente->zona); ?></td>
						<td><?php echo e($abreviatura); ?></td>
						<td style="width: 150px;">
							<div class="form-group">
				                <div class="input-group input-group-sm">

				                    <span class="input-group-btn BtnRestar">
				                        <button style="width: 50px;" type="button" class="btn btn-pedido" data-toggle="tooltip" title="Subir nivel de preferencia" id="idBtnRestar-<?php echo e($gr->codcli); ?>-<?php echo e($gr->preferencia); ?>">
				                        <span class="fa fas fa-angle-up" aria-hidden="true" id="idBtnRestar-<?php echo e($gr->codcli); ?>-<?php echo e($gr->preferencia); ?>"></span>
				                        </button>
				                    </span>

				                    <input id="idPref-<?php echo e($gr->preferencia); ?>" readonly value="<?php echo e($gr->preferencia); ?>" type="text" class="form-control"  style="color: #000000; text-align: center; width: 50px;" >

				                    <span class="input-group-btn BtnSumar">
				                        <button style="width: 50px;" type="button" class="btn btn-pedido" data-toggle="tooltip" title="Bajar nivel de preferencia" id="idBtnSumar-<?php echo e($gr->codcli); ?>-<?php echo e($gr->preferencia); ?>">
				                        <span class="fa fas fa-angle-down" aria-hidden="true" id="idBtnSumar-<?php echo e($gr->codcli); ?>-<?php echo e($gr->preferencia); ?>"></span>
				                        </button>
				                    </span>

				                </div>
				            </div>
				        </td>

						<?php if( date('d-m-Y', strtotime($feccatalogo)) == date('d-m-Y') ): ?>
							<td>
								<?php echo e(date('d-m-Y H:i', strtotime($feccatalogo))); ?>

							</td>
						<?php else: ?>
							<td style="color: red;">
								<?php echo e(date('d-m-Y H:i', strtotime($feccatalogo))); ?>

							</td>
						<?php endif; ?>
					</tr>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</table>
		</div>
	</div>
</div>


<?php $__env->startPush('scripts'); ?>
<script>
$('#titulo').text('<?php echo e($subtitulo); ?>');
$('#subtitulo').text('<?php echo e($subtitulo2); ?>');

$('.BtnSumar').on('click',function(e) {
	var id = e.target.id.split('-');
    var codcli = id[1];
    var pref = id[2];
    //alert(codcli + " - " + pref);
    $.ajax({
        type:'POST',
        url:'./grupo/cliente/sumarpref',
        dataType: 'json', 
        encode  : true,
        data: {codcli : codcli,  pref : pref},
        success:function(data) {
        	window.location.reload(); 
        }
    });
});

$('.BtnRestar').on('click',function(e) {
	var id = e.target.id.split('-');
    var codcli = id[1];
    var pref = id[2];
    //alert(codcli + " - " + pref);
    $.ajax({
        type:'POST',
        url:'./grupo/cliente/restarpref',
        dataType: 'json', 
        encode  : true,
        data: {codcli : codcli,  pref : pref},
        success:function(data) {
        	window.location.reload(); 
        }
    });
});

function tdclick(e) {
    var id = e.target.id.split('_');
    var codgrupo = id[1];
    var codcli = id[2];
    $.ajax({
	  type:'POST',
	  url:'./grupo/cliente/modstatus',
	  dataType: 'json', 
	  encode  : true,
	  data: {codgrupo : codgrupo, codcli : codcli },
	  success:function(data) {
	    if (data.msg != "") {
	        alert(data.msg);
	    } 
	  }
  	});
}

function tdclickmodpredet(e) {
    var id = e.target.id.split('_');
    var codgrupo = id[1];
    var codcli = id[2];
    $.ajax({
	  type:'POST',
	  url:'./grupo/cliente/modpredet',
	  dataType: 'json', 
	  encode  : true,
	  data: {codgrupo : codgrupo, codcli : codcli },
	  success:function(data) {
	    if (data.msg != "") {
	        window.location.reload(); 
	    } 
	  }
  	});
}

</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/isacom/grupo/index.blade.php ENDPATH**/ ?>