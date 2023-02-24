
<?php $__env->startSection('contenido'); ?>
<?php
$factor = RetornaFactorCambiario($cliente->codcli, 'USD');
?> 

<?php echo Form::model($cliente,['method'=>'PATCH','route'=>['config.update',$cliente->codcli],'enctype'=>'multipart/form-data']); ?>

<?php echo e(Form::token()); ?>

<div class="row">
    <div class="col-md-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" data-toggle="tab">BASICA</a></li>
              <li><a href="#tab_2" data-toggle="tab">PARAMETROS</a></li>
              <?php if(Auth::user()->tipo == 'C' || Auth::user()->tipo == 'G' || Auth::user()->tipo == 'A'): ?> 
                <li><a href="#tab_3" data-toggle="tab">USUARIO</a></li>
              <?php endif; ?>
              <?php if(Auth::user()->tipo == 'O'): ?> 
                <li><a href="#tab_4" data-toggle="tab">GESTOR OFERTAS</a></li>
              <?php endif; ?>
              <?php if($aplicainv): ?> 
                <li><a href="#tab_5" data-toggle="tab">INVENTARIO</a></li>
              <?php endif; ?>
              <li class="pull-right"><a href="<?php echo e(url('/')); ?>" class="text-muted">
                <i class="fa fa-times"></i></a>
              </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1">
                    <div class="row">

                        <!-- IMAGEN -->
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <img src="http://isaweb.isbsistemas.com/public/storage//<?php echo e($cliente->rutaimg); ?>" class="img-responsive" 
                            title="Click para cambiar imagen" 
                            id="avatarImage" 
                            alt="icompras360" 
                            width="100%"
                            style="border: 2px solid #D2D6DE;"
                            oncontextmenu="return false">
                            <div class="form-group">
                                <input type="file" name="rutaimg" >
                            </div>
                        </div>
                            
                  

                        <!-- CODIGO -->
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label for="codigo">Código</label>
                                <input readonly value="<?php echo e($cliente->codcli); ?>" type="text" name="codcli" class="form-control">
                            </div>
                        </div>

                        <!-- RIF -->
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label for="rif">Rif</label>
                                <input <?php if(Auth::user()->tipo != 'A'): ?> readonly <?php endif; ?> value="<?php echo e($cliente->rif); ?>" type="text" name="rif" class="form-control">
                            </div>
                        </div>

                        <!-- CONTACTO -->
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label for="contacto">Contacto</label>
                                <input value="<?php echo e($cliente->contacto); ?>" type="text" name="contacto" class="form-control">
                            </div>
                        </div>

                        <!-- ESTADO -->
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Estado</label>
                                <?php if(Auth::user()->tipo != 'A'): ?>
                                    <input readonly value="<?php echo e($cliente->estado); ?>" type="text" name="estado" class="form-control">
                                <?php else: ?>
                                <select name="estado" class="form-control">
                                    <?php if($cliente->estado == "ACTIVO"): ?>
                                        <option value="ACTIVO" selected>ACTIVO</option>
                                        <option value="INACTIVO">INACTIVO</option>
                                    <?php else: ?>
                                        <option value="ACTIVO">ACTIVO</option>
                                        <option value="INACTIVO" selected="">INACTIVO</option>
                                    <?php endif; ?>
                                </select>
                                <?php endif; ?> 
                            </div>
                        </div>

                        <!-- NOMBRE -->
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <label for="nombre">nombre</label>
                                <input <?php if(Auth::user()->tipo != 'A'): ?> readonly <?php endif; ?> value="<?php echo e($cliente->nombre); ?>" type="text" name="nombre" class="form-control">
                            </div>
                        </div>
                    
                        <!-- DIRECCION -->
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <label for="direccion">Dirección</label>
                                <input value="<?php echo e($cliente->direccion); ?>" type="text" name="direccion" class="form-control">
                            </div>
                        </div>

                        <!-- NOMBRE CORTP -->
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <div class="form-group">
                                <label for="direccion">Abreviatura</label>
                                <input name="descripcion" value="<?php echo e($cliente->descripcion); ?>" type="text" name="direccion" class="form-control">
                            </div>
                        </div>

                        <!-- Color Picker -->
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group" style="float: left;">
                                <label>Color fondo:</label><br>
                                <input name="backcolor" value="<?php echo e($cliente->backcolor); ?>" data-jscolor="{preset:'small dark', position:'right'}">
                            </div>
                            <div class="form-group" style="float: right;">
                                <label>Color letra:</label><br>
                                <input name="forecolor" value="<?php echo e($cliente->forecolor); ?>" data-jscolor="{preset:'small dark', position:'right'}">
                            </div>
                        </div>

                    </div>
                </div>
                <div class="tab-pane" id="tab_2">
                    <div class="row">

                        <!-- CADENA -->
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label>Cadena</label>
                                <input value="<?php echo e($cliente->cadena); ?>"  type="text" name="cadena" class="form-control">
                            </div>
                        </div>

                        <!-- LINK PAGINA -->
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label>Link pagina</label>
                                <input value="<?php echo e($cliente->linkpagina); ?>" type="text" name="linkpagina" class="form-control">
                            </div>
                        </div>

                        <!-- APLICACION -->
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label for="campo8">Aplicación</label>
                                <input value="<?php echo e($cliente->campo8); ?>" type="text" name="campo8" class="form-control">
                            </div>
                        </div>

                        <!-- SECTOR -->
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label>Sector</label>
                                <input value="<?php echo e($cliente->sector); ?>" type="text" name="sector" class="form-control">
                            </div>
                        </div>

                        <!-- RESERVADO -->
                        <div hidden class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label for="campo10">Reservado</label>
                                <input <?php if(Auth::user()->tipo != 'A'): ?> disabled <?php endif; ?> value="<?php echo e($cliente->campo10); ?>" readonly type="text" name="campo10" class="form-control">
                            </div>
                        </div>

                        <!-- RESERVADO -->
                        <div hidden class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label for="campo7">Reservado</label>
                                <input <?php if(Auth::user()->tipo != 'A'): ?> disabled <?php endif; ?> value="<?php echo e($cliente->campo7); ?>" readonly type="text" name="campo7" class="form-control">
                            </div>
                        </div>

                        <!-- PRIORIDAD -->
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label>Prioridad</label>
                                <?php if($cliente->campo2 == "1"): ?>
                                    <input readonly value="ALTA" type="text" name="campo2"  class="form-control">
                                <?php elseif($cliente->campo2 == "2"): ?>
                                    <input readonly value="MEDIA" type="text" name="campo2"  class="form-control">
                                <?php else: ?>
                                    <input readonly value="NORMAL" type="text" name="campo2"  class="form-control">
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- CORREO -->
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label for="campo3">Correo</label>
                                <input value="<?php echo e($cliente->campo3); ?>" type="mail" name="campo3" class="form-control">
                            </div>
                        </div>

                        <!-- USUARIO -->
                        <div hidden class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label for="usuario">Usuario</label>
                                <input value="<?php echo e($cliente->usuario); ?>" type="text" name="usuario" class="form-control">
                            </div>
                        </div>

                        <!-- CLAVE -->
                        <div hidden class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label for="clave">Clave</label>
                                <input value="<?php echo e($cliente->clave); ?>" type="text" name="clave" class="form-control">
                            </div>
                        </div>

                        <!-- ZONA -->
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label for="zona">Zona</label>
                                <input value="<?php echo e($cliente->zona); ?>" type="text" name="zona" class="form-control">
                            </div>
                        </div>

                        <!-- TELEFONO -->   
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label for="telefono">Teléfono</label>
                                <input value="<?php echo e($cliente->telefono); ?>" type="text" name="telefono" class="form-control">
                            </div>
                        </div>

                        <!-- FECHA -->
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label>Fecha registro</label>
                                <input readonly value="<?php echo e($cliente->fecha); ?>" type="date" name="fecha" class="form-control">
                            </div>
                        </div>

                        <!-- TIPO -->
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label>Tipo Cliente</label>
                                <input readonly value="<?php echo e($cliente->campo1); ?>" type="text" name="campo1"  class="form-control">
                            </div>
                        </div>

                        <!-- USA PRECIO -->
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label>Usa precio (1-3)</label>
                                <input value="<?php echo e($cliente->usaprecio); ?>" 
                                    type="text" 
                                    name="usaprecio" 
                                    style="text-align: right;" 
                                    class="form-control">
                            </div>
                        </div>

                        <!-- PRECIO/COSTO MONEDA DEL INVENTARIO (SINCRONIZACION) -->
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label>Moneda Inventario(Sinc)</label>
                                <select name="PreCosMoneda" class="form-control">
                                    <?php if($cliente->PreCosMoneda == "BS"): ?>
                                        <option value="BS" selected>BS</option>
                                        <option value="USD">USD</option>
                                    <?php else: ?>
                                        <option value="BS">BS</option>
                                        <option value="USD" selected="">USD</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>

                        <!-- TASA -->
                        <?php if(Auth::user()->tipo == "O"): ?>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label>Tasa (Factor cambiario)</label>
                                <input readonly 
                                 value="<?php echo e(number_format($factor, 2, '.', ',')); ?>"
                                 type="text"
                                 style="text-align: right;"  
                                 class="form-control">
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- DIAS DE TRANSITO -->
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label>Dias en Transito</label>
                                <input value="<?php echo e($cliente->diasTransito); ?>" type="number" name="diasTransito" class="form-control">
                            </div>
                        </div>

                        <!-- MESES MINIMO DE NOTIFICACION DE VENCIMIENTO -->
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label>Meses minimo de noti. vencimiento</label>
                                <input value="<?php echo e($cliente->mesNotVence); ?>" 
                                    type="text" 
                                    name="mesNotVence" 
                                    style="text-align: right;" 
                                    class="form-control">
                            </div>
                        </div>

                        <!-- MODO DE NOTIFICACION DE PRODUCTOS EN TRANSITO -->
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label>Modo de Noti de prod. en Transito</label>
                                <select name="ModoNotiTrans" class="form-control">
                                    <?php if($cliente->ModoNotiTrans == 0): ?>
                                        <option value="0" selected>
                                        NOTIFICAR Y NO PERMITIR AGREGAR EL PRODUCTO
                                        </option>
                                        <option value="1">
                                        NOTIFICAR Y AGREGAR EL PRODUCTO
                                        </option>
                                    <?php endif; ?>
                                    <?php if($cliente->ModoNotiTrans == 1): ?>
                                        <option value="0">
                                        NOTIFICAR Y NO PERMITIR AGREGAR EL PRODUCTO
                                        </option>
                                        <option value="1" selected>
                                        NOTIFICAR Y AGREGAR EL PRODUCTO
                                        </option>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>

                        <!-- MOSTRAR GRAFICA DE AHORRO  -->
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group" style="padding-top: 30px;">
                                <div class="form-check">
                                    <?php if($cliente->MostrarGraAho): ?>
                                        <input checked 
                                            name="MostrarGraAho" 
                                            type="checkbox" 
                                            class="form-check-input" >
                                    <?php else: ?>
                                        <input name="MostrarGraAho" 
                                            type="checkbox" 
                                            class="form-check-input" >
                                    <?php endif; ?>
                                    <label class="form-check-label" 
                                            for="materialUnchecked">
                                            Mostrar grafica de Ahorro Inicio
                                    </label>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>

                <?php if(Auth::user()->tipo == 'C' || Auth::user()->tipo == 'G' || Auth::user()->tipo == 'A'): ?> 
                    <div class="tab-pane" id="tab_3">
                        <div class="row">
                            
                            <!-- ACTIVAR BOTON DE ENVIO DE PEDIDOS -->
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <div class="form-check" style="margin-top: 30px;">
                                    <?php if(Auth::user()->botonEnvio == 1): ?>
                                        <input checked name="botonEnvio" type="checkbox" class="form-check-input" id="materialUnchecked">
                                    <?php else: ?>
                                        <input name="botonEnvio" type="checkbox" class="form-check-input" id="materialUnchecked">
                                    <?php endif; ?>
                                    <label class="form-check-label" for="materialUnchecked">Activar boton de Envio de pedidos</label>
                                </div>
                            </div>

                        </div>
                    </div>
                <?php endif; ?>

                <?php if(Auth::user()->tipo == 'O'): ?>
                    <div class="tab-pane" id="tab_4">
                        <div class="row">

                            <!-- TIPO PRECIO -->
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <div class="form-group">
                                    <label>Indique el tipo precio</label>
                                    <input type="text" 
                                        class="form-control" 
                                        name="usaprecio" 
                                        value="<?php echo e($cliente->usaprecio); ?>" 
                                        style="text-align: right;"  >
                                </div>
                            </div>

                            <!-- UTILIDAD MINIMA -->
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <div class="form-group">
                                    <label>Utilidad minima</label>
                                    <input value="<?php echo e($cliente->utilm); ?>" 
                                        type="text" 
                                        name="utilm"
                                        style="text-align: right;" 
                                        class="form-control">
                                </div>
                            </div>

                            <!-- DA MINIMA -->
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <div class="form-group">
                                    <label>DA minima (Oferta)</label>
                                    <input value="<?php echo e($cliente->damin); ?>" 
                                        type="text" 
                                        name="damin" 
                                        style="text-align: right;"
                                        class="form-control">
                                </div>
                            </div>

                            <!-- DA MAXIMA -->
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <div class="form-group">
                                    <label>DA maxima (Oferta)</label>
                                    <input value="<?php echo e($cliente->damax); ?>" 
                                        type="text" 
                                        name="damax" 
                                        style="text-align: right;"
                                        class="form-control">
                                </div>
                            </div>

                            <!-- DC -->
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <div class="form-group">
                                    <label>DC (Descuento comercial)</label>
                                    <input value="<?php echo e($cliente->dc); ?>" 
                                        type="text" 
                                        name="dc" 
                                        style="text-align: right;"
                                        class="form-control">
                                </div>
                            </div>

                            <!-- DI -->
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <div class="form-group">
                                    <label>DI (Descuento internet)</label>
                                    <input value="<?php echo e($cliente->di); ?>" 
                                        type="text" 
                                        name="di" 
                                        style="text-align: right;"
                                        class="form-control">
                                </div>
                            </div>

                            <!-- DI -->
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <div class="form-group">
                                    <label>PP (Descuento pronto pago)</label>
                                    <input value="<?php echo e($cliente->pp); ?>" 
                                        type="text" 
                                        name="pp" 
                                        style="text-align: right;"
                                        class="form-control">
                                </div>
                            </div>

                            <!-- SINCRONIZAR IVENTARIO CON FECUENCIA -->
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group" style="padding-top: 30px;">
                                    <div class="form-check">
                                        <?php if($cliente->SinInvConFrec): ?>
                                            <input checked name="SinInvConFrec" type="checkbox" class="form-check-input" >
                                        <?php else: ?>
                                            <input name="SinInvConFrec" type="checkbox" class="form-check-input" >
                                        <?php endif; ?>
                                        <label class="form-check-label" 
                                                for="materialUnchecked">
                                            Sincronizar Inventario con Frecuencia del Cliente
                                        </label>
                                    </div>
                                </div>
                            </div>
            
                        </div>
                    </div>
                <?php endif; ?>

                <div class="tab-pane" id="tab_5">
                    <div class="row">
                        
                        <!-- BAJO -->
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Valor bajo (Dias de inventario)</label>
                                <input type="number" 
                                    min="1" max="100" step="1"
                                    class="form-control" 
                                    name="min" 
                                    value="<?php echo e($cliente->min); ?>" 
                                    style="text-align: right;"  >
                            </div>
                        </div>
                    
                        <!-- ALTO -->
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Valor alto (Dias de inventario)</label>
                                <input type="number" 
                                    min="1" max="1000" step="1"
                                    class="form-control" 
                                    name="max" 
                                    value="<?php echo e($cliente->max); ?>" 
                                    style="text-align: right;"  >
                            </div>
                        </div>   

                        <!-- CAMPO PARA MARCA EN EL INVENTARIO -->
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Campo para Marca en el Inventario</label>
                                <select name="CampoMarcaInv" class="form-control">
                                    <?php if($cliente->CampoMarcaInv == "MARCA"): ?>
                                        <option value="MARCA" selected>
                                        MARCA
                                        </option>
                                        <option value="SUBGRUPO">
                                        SUBGRUPO
                                        </option>
                                    <?php endif; ?>
                                    <?php if($cliente->CampoMarcaInv == "SUBGRUPO"): ?>
                                        <option value="MARCA">
                                        MARCA
                                        </option>
                                        <option value="SUBGRUPO" selected>
                                        SUBGRUPO
                                        </option>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>

                        <!-- CRITERIO PARA PEDIDO AUTOMATICO -->
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Criterio del pedido automatico</label>
                                <select name="CriPedAuto" class="form-control">
                                    <option value="PRECIO" 
                                    <?php if($cliente->CriPedAuto == "PRECIO"): ?> selected <?php endif; ?>
                                    >PRECIO</option>
                                    <option value="INVENTARIO"
                                    <?php if($cliente->CriPedAuto == "INVENTARIO"): ?> selected <?php endif; ?>
                                    >INVENTARIO</option>
                                    <option value="DIAS"
                                    <?php if($cliente->CriPedAuto == "DIAS"): ?> selected <?php endif; ?>
                                    >DIAS</option>
                                </select>
                            </div>
                        </div>

                        <!-- PREFERENCIA PARA PEDIDO AUTOMATICO -->
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Preferencia del pedido automatico</label>
                                <select name="PrePedAuto" class="form-control">
                                    <option value="NINGUNA" 
                                    <?php if($cliente->PrePedAuto == "NINGUNA"): ?> selected <?php endif; ?>
                                    >NINGUNA</option>
                                    <option value="PRIMER"
                                    <?php if($cliente->PrePedAuto == "PRIMER"): ?> selected <?php endif; ?>
                                    >PRIMER PROVEEDOR</option>
                                </select>
                            </div>
                        </div>

                        <!-- SINCRONIZAR IVENTARIO CON FECUENCIA -->
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group" style="padding-top: 30px;">
                                <div class="form-check">
                                    <?php if($cliente->SinInvConFrec): ?>
                                        <input checked name="SinInvConFrec" type="checkbox" class="form-check-input" >
                                    <?php else: ?>
                                        <input name="SinInvConFrec" type="checkbox" class="form-check-input" >
                                    <?php endif; ?>
                                    <label class="form-check-label" 
                                            for="materialUnchecked">
                                        Sinc. Inv. con Frecuencia del Cliente
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- GENERAR PEDIDO AUTOMATICO  -->
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group" style="padding-top: 30px;">
                                <div class="form-check">
                                    <?php if($cliente->GenPedAuto): ?>
                                        <input checked 
                                            name="GenPedAuto" 
                                            type="checkbox" 
                                            class="form-check-input" >
                                    <?php else: ?>
                                        <input name="GenPedAuto" 
                                            type="checkbox" 
                                            class="form-check-input" >
                                    <?php endif; ?>
                                    <label class="form-check-label" 
                                            for="materialUnchecked">
                                            Ped. Auto. todos los dias a las 6PM
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div> 
        </div>
    </div>
</div>
<!-- BOTON GUARDAR/CANCELAR -->
<div class="form-group" style="margin-top: 20px; margin-left: 15px;">
    <button type="button" class="btn-normal" onclick="history.back(-1)">Regresar</button>
    <?php if(Auth::user()->userAdmin == '1'): ?> 
        <button class="btn-confirmar" type="submit">Guardar</button>
    <?php endif; ?>
</div>
<?php echo e(Form::close()); ?>


<?php $__env->startPush('scripts'); ?>
<script>
$('#subtitulo').text('<?php echo e($subtitulo); ?>');
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/isacom/config/edit.blade.php ENDPATH**/ ?>