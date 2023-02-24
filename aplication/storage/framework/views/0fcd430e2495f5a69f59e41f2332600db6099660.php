
<?php $__env->startSection('contenido'); ?>

<?php echo Form::model($cliente,['method'=>'PATCH','route'=>['activacion.update',$codisb]]); ?>

<?php echo e(Form::token()); ?>


<input hidden type="text" name="codisb" value="<?php echo e($codisb); ?>">
<input hidden type="text" name="codcanal" value="<?php echo e($codcanal); ?>">
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
      <li class="active"><a href="#tab_1" data-toggle="tab"><B>BASICA</B></a></li>
      <li><a href="#tab_2" data-toggle="tab"><B>DETALLES</B></a></li>
      <li><a href="#tab_3" data-toggle="tab"><B>PROVEEDORES</B></a></li>
      <li class="pull-right"><a href="<?php echo e(url('/seped/config')); ?>" class="text-muted"><i class="fa fa-gear"></i></a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
            <div class="row">

                <!-- RIF -->
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label>Rif:</label>
                        <input type="text" 
                            name="rif"
                            value="<?php echo e($rif); ?>"
                            readonly="" 
                            required
                            class="form-control">
                    </div>
                </div>
    
                <!-- NOMBRE -->
                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label>Nombre: (*)</label>
                        <input type="text" 
                            name="nombre" 
                            <?php if($cliente): ?> value="<?php echo e($cliente->nombre); ?>" <?php endif; ?>
                            maxlength="100" 
                            required
                            class="form-control">
                    </div>
                </div>
            
                <!-- DIRECCION -->
                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label>Dirección: (*)</label>
                        <input type="text" 
                            name="direccion" 
                            <?php if($cliente): ?> value="<?php echo e($cliente->direccion); ?>" <?php else: ?> value="N/A" <?php endif; ?>
                            maxlength="120" 
                            required
                            class="form-control">
                    </div>
                </div>

                <!-- TELEFONO -->   
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label>Telefono: (*)</label>
                        <input type="text" 
                            name="telefono"
                            <?php if($cliente): ?> value="<?php echo e($cliente->telefono); ?>" <?php else: ?> value="N/A" <?php endif; ?>
                            maxlength="100" 
                            required 
                            class="form-control">
                    </div>
                </div>

                <!-- CONTACTO -->
                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label>Contacto: (*)</label>
                        <input type="text" 
                            name="contacto" 
                            maxlength="50" 
                            <?php if($cliente): ?> value="<?php echo e($cliente->contacto); ?>" <?php else: ?> value="N/A" <?php endif; ?>
                            required
                            class="form-control">
                    </div>
                </div>

                <!-- ZONA -->
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label>Localidad: (*)</label>
                        <input type="text" 
                            name="localidad" 
                            <?php if($cliente): ?> value="<?php echo e($cliente->zona); ?>" <?php else: ?> value="MARACAIBO-ZULIA" <?php endif; ?>
                            maxlength="50"
                            required 
                            class="form-control">
                    </div>
                </div>

            </div>
        </div>
        <div class="tab-pane" id="tab_2">
            <div class="row">

                <!-- APLICACION -->
                <div class="col-lg-6 col-md-6 col-sm-3 col-xs-12">
                    <div class="form-group">
                        <label>Aplicación (ERP)</label>
                        <?php if($cliente): ?> 
                            <select name="erp" class="form-control">
                                <option value="SAINT"
                                    <?php if($cliente->campo8 == "SAINT"): ?> selected <?php endif; ?>>
                                    SAINT
                                </option>
                                <option value="A2"
                                    <?php if($cliente->campo8 == "A2"): ?> selected <?php endif; ?>>
                                    A2
                                </option>
                                <option value="STELLAR"
                                    <?php if($cliente->campo8 == "STELLAR"): ?> selected <?php endif; ?>>
                                    STELLAR
                                </option>
                                <option value="PROFIT"
                                    <?php if($cliente->campo8 == "PROFIT"): ?> selected <?php endif; ?>>
                                    PROFIT
                                </option>
                                <option value="SMARTPHARMA"
                                    <?php if($cliente->campo8 == "SMARTPHARMA"): ?> selected <?php endif; ?>>
                                    SMARTPHARMA
                                </option>
                                <option value="PREMIUM"
                                    <?php if($cliente->campo8 == "PREMIUM"): ?> selected <?php endif; ?>>
                                    PREMIUM
                                </option>
                                <option value="HYBRID"
                                    <?php if($cliente->campo8 == "HYBRID"): ?> selected <?php endif; ?>>
                                    HYBRID
                                </option>
                                <option value="EFICASIS"
                                    <?php if($cliente->campo8 == "EFICASIS"): ?> selected <?php endif; ?>>
                                    EFICASIS
                                </option>
                                <option value="OTROS"
                                    <?php if($cliente->campo8 == "OTROS"): ?> selected <?php endif; ?>>
                                    OTROS
                                </option>
                            </select>
                        <?php else: ?>
                            <select name="erp" class="form-control">
                                <option value="SAINT">SAINT</option>
                                <option value="A2">A2</option>
                                <option value="STELLAR">STELLAR</option>
                                <option value="PROFIT">PROFIT</option>
                                <option value="SMARTPHARMA">SMARTPHARMA</option>
                                <option value="PREMIUM">PREMIUM</option>
                                <option value="HYBRID">HYBRID</option>
                                <option value="EFICASIS">EFICASIS</option>
                                <option value="OTROS">OTROS</option>
                            </select>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- SECTOR -->
                <div class="col-lg-6 col-md-6 col-sm-3 col-xs-12">
                    <div class="form-group">
                        <label>Sector</label>
                        <?php if($cliente): ?> 
                            <select name="sector" class="form-control">
                                <option value="FARMACIA"
                                    <?php if($cliente->sector == "FARMACIA"): ?> selected <?php endif; ?>>
                                    FARMACIA
                                </option>
                                <option value="DROGUERIA"
                                    <?php if($cliente->sector == "DROGUERIA"): ?> selected <?php endif; ?>>
                                    DROGUERIA
                                </option>
                                <option value="HOSPITAL"
                                    <?php if($cliente->sector == "HOSPITAL"): ?> selected <?php endif; ?>>
                                    HOSPITAL
                                </option>
                                <option value="CONSUMO MASIVO"
                                    <?php if($cliente->sector == "CONSUMO MASIVO"): ?> selected <?php endif; ?>>
                                    CONSUMO MASIVO
                                </option>
                                <option value="OTROS"
                                    <?php if($cliente->sector == "OTROS"): ?> selected <?php endif; ?>>
                                    OTROS
                                </option>
                            </select>
                        <?php else: ?>
                            <select name="sector" class="form-control">
                                <option value="FARMACIA">FARMACIA</option>
                                <option value="DROGUERIA">DROGUERIA</option>
                                <option value="HOSPITAL">HOSPITAL</option>
                                <option value="CONSUMO MASIVO">CONSUMO MASIVO</option>
                                <option value="OTROS">OTROS</option>
                            </select>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- VENDEDOR -->
                <div class="col-lg-6 col-md-6 col-sm-3 col-xs-12">
                    <div class="form-group">
                        <label>Vendedor</label>
                        <?php if(count($vendedor)>0): ?>  
                            <select name="codvendedor" class="form-control">
                                <?php $__currentLoopData = $vendedor; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($v->codvendedor); ?>"><?php echo e($v->codvendedor); ?>-<?php echo e($v->nombre); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        <?php else: ?>
                            <input type="text" 
                                name="codvendedor"
                                value="DIRECTO"
                                readonly="" 
                                class="form-control">
                        <?php endif; ?>
                    </div>
                </div>

                <!-- VERSION DEL ICOMPRAS -->
                <div class="col-lg-6 col-md-6 col-sm-3 col-xs-12">
                    <div class="form-group">
                        <label>Version icompras</label>
                        <?php if($cliente): ?>  
                            <select name="vericompras" class="form-control">
                                <option value="LIGHT"
                                    <?php if($cliente->campo1 == "LIGHT"): ?> selected <?php endif; ?>>
                                    LIGHT
                                </option>
                                <option value="FULL"
                                    <?php if($cliente->campo1 == "FULL"): ?> selected <?php endif; ?>>
                                    FULL
                                </option>
                            </select>
                        <?php else: ?>
                            <select name="vericompras" class="form-control">
                                <option value="LIGHT">LIGHT</option>
                                <option value="FULL">FULL</option>
                            </select>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- LINK PAGINA -->
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label">Link pagina: (*)</label>
                        <input type="text" 
                            name="linkpagina"
                            <?php if($cliente): ?> value="<?php echo e($cliente->linkpagina); ?>" <?php else: ?> value="N/A" <?php endif; ?>
                            maxlength="100"  
                            required
                            class="form-control">
                    </div>
                </div>

                <!-- CORREO -->
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label>Correo: (*)</label>
                        <input type="mail" 
                            name="correo"
                            <?php if($cliente): ?> value="<?php echo e($cliente->campo3); ?>" <?php else: ?> value="pedidos@demo.com" <?php endif; ?>
                            maxlength="100" 
                            required
                            class="form-control">
                    </div>
                </div>

            </div>
        </div>
        <div class="tab-pane" id="tab_3">
            <div class="row">

                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-condensed table-hover">

                        <thead class="colorTitulo">
                            <th colspan="5">
                                <center>PROVEEDOR</center>
                            </th>
                            <th colspan="4">
                                <center>DATOS DEL CLIENTE</center>
                            </th>
                            <th colspan="4">
                                <center>DESCUENTOS</center>
                            </th>
                        </thead>

                        <thead class="colorTitulo">
                            <th>ACTIVAR</th>
                            <th style="width: 60px;">IMAGEN</th>
                            <th style="width:120px;">DESCRIPCION</th>
                            <th>SEDE</th>
                            <th title="VERIFICAR DATOS DE CONEXION CON EL PROVEEDOR">
                                VERIF
                            </th>
                            <th style="width:210px;" 
                                title="CODIGO DEL CLIENTE OTORGADO POR EL PROVEEDOR">
                                CODIGO (*) &nbsp;&nbsp;&nbsp;&nbsp; 
                            </th>
                            <th style="width:150px;" 
                                title="USUARIO DEL CLIENTE OTORGADO POR EL PROVEEDOR">
                                USUARIO (*)
                            </th>
                            <th style="width:180px;" 
                                title="CLAVE DEL CLIENTE OTORGADO POR EL PROVEEDOR">
                                CLAVE (*)
                            </th>
                            <th style="width:140px;" 
                                title="DIAD DE CREDITO OTORGADO POR EL PROVEEDOR">
                                DIAS
                            </th>
                            <th style="width:150px;" 
                                title="DESCUENTO COMERCIAL">
                                COMERCIAL
                            </th>
                            <th style="width:180px;" 
                                title="DESCUENTO PRONTO PAGO">
                                P.PAGO
                            </th>
                            <th style="width:100px;" 
                                title="DESCUENTO INTERNET">
                                INTERNET
                            </th>
                            <th style="width:100px;" 
                                title="DESCUENTO OTROS">
                                OTROS
                            </th>
                        </thead>
                        <?php $__currentLoopData = $maeprove; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                        $marcar = 0;
                        $codigo = "N/A";
                        $usuario = "N/A";
                        $clave = "N/A";
                        $dcredito = "7";
                        $dc = "0.00";
                        $pp = "0.00";
                        $di = "0.00";
                        $do = "0.00";
                        $maeclieprove = DB::table('maeclieprove')
                        ->where('codcli','=',$codisb)
                        ->where('codprove','=',$p->codprove)
                        ->first();
                        if ($maeclieprove) {
                            $marcar = 1;
                            $codigo = $maeclieprove->codigo;
                            $usuario = $maeclieprove->usuario;
                            $clave = $maeclieprove->clave;
                            $dcredito = $maeclieprove->dcredito;
                            $dc = $maeclieprove->dcme;
                            $pp = $maeclieprove->ppme;
                            $di = $maeclieprove->di;
                            $do = $maeclieprove->dotro;
                        }
                        ?>
                        <tr>
                            <td style="padding-top: 10px;">
                                <span>
                                <center>
                                    <input type="checkbox"
                                        id="idmarcar-<?php echo e($p->codprove); ?>"
                                        <?php if($marcar > 0): ?> checked <?php endif; ?> 
                                        name="activar[<?php echo e($p->codprove); ?>-<?php echo e($loop->iteration); ?>]" />
                                </center>
                                </span>
                            </td>
                            <td>
                                <div align="center">
                                    <img src="http://isaweb.isbsistemas.com/public/storage/prov/<?php echo e($p->rutalogo1); ?>" 
                                        class="img-responsive" 
                                        alt="icompras" style="width: 100px;">
                                </div>
                            </td>
                            <td><?php echo e($p->descripcion); ?><br>
                                <?php echo e(strtoupper($p->nombre)); ?><br>
                                <?php echo e($p->codprove); ?>

                                <input type="text" 
                                    hidden="" 
                                    name="codprove[]"
                                    value="N/A">
                            </td>
                            <td><?php echo e($p->codsede); ?><br>
                                <?php echo e(strtoupper($p->localidad)); ?> <br>
                                <?php echo e(substr($p->region,4,strlen($p->region)-4)); ?>

                            </td>
                            <td>
                                <button data-toggle="tooltip" 
                                    title="VERIFICAR DATOS DE CONEXION AL PROVEEDOR" 
                                    class="btn btn-pedido fa fa-check BtnVerificar" 
                                    id="idverificar-<?php echo e($p->codprove); ?>">
                                </button>
                            </td>

                            <td class="hover"
                                title="CODIGO DEL CLIENTE OTORGADO POR EL PROVEEDOR !!!">
                                <input type="text" 
                                    name="codigo[]"
                                    value="<?php echo e($codigo); ?>"
                                    id="idcodigo-<?php echo e($p->codprove); ?>"
                                    class="form-control">
                            </td>
                            <td class="hover"
                                title="USUARIO DE ACCESO AL PORTAL WEB DEL PROVEEDOR !!!">
                                <input type="text" 
                                    name="usuario[]"
                                    value="<?php echo e($usuario); ?>"
                                    id="idusuario-<?php echo e($p->codprove); ?>"
                                    <?php if($p->tipocata=='ISB' || $p->tipocata=='ISB2' || $p->tipocata=='DRONENA' || $p->tipocata=='DROLANCA'): ?>
                                    readonly="" 
                                    <?php endif; ?>
                                    class="form-control">
                            </td>
                            <td class="hover"
                                title="CLAVE DE ACCESO AL PORTAL WEB DEL PROVEEDOR !!!">
                                <input type="text" 
                                    name="clave[]"
                                    value="<?php echo e($clave); ?>"
                                    id="idclave-<?php echo e($p->codprove); ?>"
                                    <?php if($p->tipocata=='ISB' || $p->tipocata=='ISB2' || $p->tipocata=='DRONENA' || $p->tipocata=='DROLANCA'): ?>
                                    readonly="" 
                                    <?php endif; ?>
                                    class="form-control">
                            </td>
                            <td class="hover"
                                title="DIAS DE CREDITO OTORGADOS POR EL PROVEEDOR !!!">
                                <input type="text" 
                                    style="text-align: right;" 
                                    name="dcredito[]"
                                    value="<?php echo e($dcredito); ?>"
                                    class="form-control">
                            </td>
                            <td class="hover"
                                title="DESCUENTO COMERCIAL OTORGADOS POR EL PROVEEDOR !!!">
                                <input type="text" 
                                    style="text-align: right;" 
                                    name="dc[]"
                                    value="<?php echo e($dc); ?>"
                                    class="form-control">
                            </td>
                            <td class="hover"
                                title="DESCUENTO PRONTO PAGO OTORGADOS POR EL PROVEEDOR !!!">
                                <input type="text" 
                                    style="text-align: right;" 
                                    name="pp[]"
                                    value="<?php echo e($pp); ?>"
                                    class="form-control">
                            </td>
                            <td class="hover"
                                title="DESCUENTO INTERNET OTORGADOS POR EL PROVEEDOR !!!">
                                <input type="text" 
                                    style="text-align: right;" 
                                    name="di[]"
                                    value="<?php echo e($di); ?>"
                                    class="form-control">
                            </td>
                            <td class="hover"
                                title="DESCUENTO OTROS OTORGADOS POR EL PROVEEDOR !!!">
                                <input type="text" 
                                    style="text-align: right;" 
                                    name="do[]"
                                    value="<?php echo e($do); ?>"
                                    class="form-control">
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </table>
                </div>

        
            </div>
        </div>
        <span>(*) Campos de carater obligatorios</span>
    </div>
</div>
<!-- BOTON GUARDAR/CANCELAR -->
<div class="form-group" style="margin-top: 20px; margin-left: 15px;">
    <button type="button" 
        class="btn-normal" 
        onclick="history.back(-1)">
        Regresar
    </button>
    <button class="btn-confirmar" 
        type="submit" 
        data-toggle="tooltip" 
        title="Guardar activación">
        Guardar
    </button>
</div>
<?php echo e(Form::close()); ?>


<?php $__env->startPush('scripts'); ?>
<script>
$('#subtitulo').text('<?php echo e($subtitulo); ?>');

$('.BtnVerificar').on('click',function(e) {
    var variable = e.target.id.split('-');
    var codprove = variable[1].trim();
    var codigo = $('#idcodigo-'+codprove).val().trim();
    var usuario = $('#idusuario-'+codprove).val().trim();
    var clave = $('#idclave-'+codprove).val().trim();
    $.ajax({
      type:'POST',
      url:'./activacion/verificar',
      dataType: 'json', 
      encode  : true,
      data: {codprove : codprove, codigo : codigo, usuario : usuario, clave : clave },
      success:function(data) {
        if (data.resp == true) {
            $("#idmarcar-"+codprove).prop("checked", true);
            alert("VERIFICACION EXITOSA !!!");
        }
        else {
            $("#idmarcar-"+codprove).prop("checked", false);
            alert("NO SE PUDO VERIFICAR, INTENTE MAS TARDE !!!");
        }
      }
    });
    e.preventDefault();
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/canales/activacion/edit.blade.php ENDPATH**/ ?>