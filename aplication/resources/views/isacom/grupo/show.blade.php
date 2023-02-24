@extends ('layouts.menu')
@section ('contenido')

<div class="row">
    <div class="col-md-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" data-toggle="tab">BASICA</a></li>
              <li><a href="#tab_2" data-toggle="tab">DETALLES</a></li>
              <li><a href="#tab_3" data-toggle="tab">ICOMPRAS</a></li>
              <li class="pull-right"><a href="{{url('/')}}" class="text-muted">
                <i class="fa fa-times"></i></a>
              </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="tab_1">
                    <div class="row">

                        <!-- IMAGEN -->
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <img src="http://isaweb.isbsistemas.com/public/storage//{{$cliente->rutaimg}}" 
                            class="img-responsive" 
                            alt="icompras360" 
                            width="100%"
                            style="border: 2px solid #D2D6DE;"
                            oncontextmenu="return false">
                        </div>


                        <!-- CODIGO -->
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label for="codigo">Código</label>
                                <input readonly value="{{$cliente->codcli}}" type="text" name="codcli" class="form-control">
                            </div>
                        </div>

                        <!-- RIF -->
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label for="rif">Rif</label>
                                <input readonly value="{{$cliente->rif}}" type="text" name="rif" class="form-control">
                            </div>
                        </div>

                        <!-- CONTACTO -->
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label for="contacto">Contacto</label>
                                <input readonly value="{{$cliente->contacto}}" type="text" name="contacto" class="form-control">
                            </div>
                        </div>

                        <!-- ESTADO -->
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Estado</label>
                                <input readonly value="{{$cliente->estado}}" type="text" name="estado" class="form-control">
                            </div>
                        </div>

                        <!-- NOMBRE -->
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <label for="nombre">nombre</label>
                                <input readonly value="{{$cliente->nombre}}" type="text" name="nombre" class="form-control">
                            </div>
                        </div>
                    
                        <!-- DIRECCION -->
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <label for="direccion">Dirección</label>
                                <input readonly value="{{$cliente->direccion}}" type="text" name="direccion" class="form-control">
                            </div>
                        </div>

                        <!-- NOMBRE CORTP -->
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <div class="form-group">
                                <label for="direccion">ABREVIATURA</label>
                                <input readonly="" name="descripcion" value="{{$cliente->descripcion}}" type="text" name="direccion" class="form-control">
                            </div>
                        </div>

                        <!-- Color Picker -->
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group" style="float: left;">
                                <label>Color fondo:</label><br>
                                <input disabled="" name="backcolor" value="{{$cliente->backcolor}}" data-jscolor="{preset:'small dark', position:'right'}">
                            </div>
                            <div class="form-group" style="float: right;">
                                <label>Color letra:</label><br>
                                <input disabled="" name="forecolor" value="{{$cliente->forecolor}}" data-jscolor="{preset:'small dark', position:'right'}">
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
                                <input readonly value="{{$cliente->cadena}}"  type="text" name="cadena" class="form-control">
                            </div>
                        </div>

                        <!-- LINK PAGINA -->
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label>Link pagina</label>
                                <input readonly value="{{$cliente->linkpagina}}" type="text" name="linkpagina" class="form-control">
                            </div>
                        </div>

                        <!-- APLICACION -->
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label for="campo8">Aplicación</label>
                                <input readonly value="{{$cliente->campo8}}" type="text" name="campo8" class="form-control">
                            </div>
                        </div>

                        <!-- SECTOR -->
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label>Sector</label>
                                <input readonly value="{{$cliente->sector}}" type="text" name="sector" class="form-control">
                            </div>
                        </div>

                        <!-- RESERVADO -->
                        <div hidden class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label for="campo10">Reservado</label>
                                <input readonly value="{{$cliente->campo10}}" readonly type="text" name="campo10" class="form-control">
                            </div>
                        </div>

                        <!-- RESERVADO -->
                        <div hidden class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label for="campo7">Reservado</label>
                                <input readonly value="{{$cliente->campo7}}" readonly type="text" name="campo7" class="form-control">
                            </div>
                        </div>

                        <!-- PRIORIDAD -->
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label>Prioridad</label>
                                @if ($cliente->campo2 == "1")
                                    <input readonly value="ALTA" type="text" name="campo2"  class="form-control">
                                @elseif ($cliente->campo2 == "2")
                                    <input readonly value="MEDIA" type="text" name="campo2"  class="form-control">
                                @else
                                    <input readonly value="NORMAL" type="text" name="campo2"  class="form-control">
                                @endif
                            </div>
                        </div>
                        
                        <!-- CORREO -->
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label for="campo3">Correo</label>
                                <input readonly value="{{$cliente->campo3}}" type="mail" name="campo3" class="form-control">
                            </div>
                        </div>

                        <!-- USUARIO -->
                        <div hidden class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label for="usuario">Usuario</label>
                                <input readonly value="{{$cliente->usuario}}" type="text" name="usuario" class="form-control">
                            </div>
                        </div>

                        <!-- CLAVE -->
                        <div hidden class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label for="clave">Clave</label>
                                <input readonly value="{{$cliente->clave}}" type="text" name="clave" class="form-control">
                            </div>
                        </div>

                        <!-- ZONA -->
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label for="zona">Zona</label>
                                <input readonly value="{{$cliente->zona}}" type="text" name="zona" class="form-control">
                            </div>
                        </div>

                        <!-- TELEFONO -->   
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label for="telefono">Teléfono</label>
                                <input readonly value="{{$cliente->telefono}}" type="text" name="telefono" class="form-control">
                            </div>
                        </div>

                        <!-- FECHA -->
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label>Fecha registro</label>
                                <input readonly value="{{$cliente->fecha}}" type="date" name="fecha" class="form-control">
                            </div>
                        </div>

                        <!-- TIPO -->
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label>Tipo Cliente</label>
                                <input readonly value="{{$cliente->campo1}}" type="text" name="campo1"  class="form-control">
                            </div>
                        </div>

                    </div>
                </div>
                <div class="tab-pane" id="tab_3">
                    <div class="row">
    					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Modo de Notificación de productos en Transito</label>
                                <select disabled="" name="ModoNotiTrans" class="form-control">
                                    @if ($cliente->ModoNotiTrans == 0)
                                        <option value="0" selected>
                                        NOTIFICAR Y NO PERMITIR AGREGAR EL PRODUCTO
                                        </option>
                                        <option value="1">
                                        NOTIFICAR Y AGREGAR EL PRODUCTO
                                        </option>
                                    @endif
                                    @if ($cliente->ModoNotiTrans == 1)
                                        <option value="0">
                                        NOTIFICAR Y NO PERMITIR AGREGAR EL PRODUCTO
                                        </option>
                                        <option value="1" selected>
                                        NOTIFICAR Y AGREGAR EL PRODUCTO
                                        </option>
                                    @endif
                                </select>
                            </div>
                        </div>

                        <!-- DIAS DE TRANSITO -->
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Dias en Transito</label>
                                <input readonly value="{{$cliente->diasTransito}}" type="number" name="diasTransito" class="form-control">
                            </div>
                        </div>

                        <!-- ACTIVAR BOTON DE ENVIO DE PEDIDOS -->
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-check" style="margin-top: 30px;">
                                @if (Auth::user()->botonEnvio == 1)
                                    <input disabled="" checked name="botonEnvio" type="checkbox" class="form-check-input" id="materialUnchecked">
                                @else
                                    <input disabled="" name="botonEnvio" type="checkbox" class="form-check-input" id="materialUnchecked">
                                @endif
                                <label class="form-check-label" for="materialUnchecked">Activar boton de Envio de pedidos</label>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- BOTON REGRESAR -->
<div class="form-group" style="margin-top: 20px; margin-left: 15px;">
    <button type="button" class="btn-normal" onclick="history.back(-1)">Regresar</button>
</div>

@push ('scripts')
<script>
$('#subtitulo').text('{{$subtitulo}}');
</script>
@endpush
@endsection
