@extends('layouts.menu')
@section('contenido') 

<div class="row">
    <div class="col-md-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" data-toggle="tab">BASICA</a></li>
              <li><a href="#tab_2" data-toggle="tab">DETALLADA</a></li>
              <li><a href="#tab_3" data-toggle="tab">DATOS COMERCIALES</a></li>
              <li class="pull-right"><a href="{{url('/')}}" class="text-muted">
                <i class="fa fa-times"></i></a>
              </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <center>
                                <img src="http://isaweb.isbsistemas.com/public/storage/prov/{{$tabla->rutalogo1}}" 
                                width="150px" 
                                class="img-responsive"
                                style="margin-top: 10px; border: 2px solid #D2D6DE;" 
                                alt="icpmpras">
                            </center>
                        </div>
                
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Descripción</label>
                                <input readonly  type="text" class="form-control" value="{{$tabla->descripcion}}" style="background-color: {{$confprov->backcolor}}; color: {{$confprov->forecolor}}; width: 100%;" >
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Nombre</label>
                                <input readonly  type="text" class="form-control" value="{{$tabla->nombre}}" style=" color: #000000; background: #F7F7F7;" >
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Contacto</label>
                                <input readonly  type="text" class="form-control" value="{{$tabla->contacto}}" style=" color: #000000; background: #F7F7F7;" >
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Teléfono</label>
                                <input readonly  type="text" class="form-control" value="{{$tabla->telefono}}" style=" color: #000000; background: #F7F7F7;" >
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Sede</label>
                                <input readonly  type="text" class="form-control" value="{{$tabla->codsede}}" style=" color: #000000; background: #F7F7F7;" >
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Localidad</label>
                                <input readonly  type="text" class="form-control" value="{{$tabla->localidad}}" style=" color: #000000; background: #F7F7F7;" >
                            </div>
                        </div>

                                           
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Fecha Sincronización</label>
                                <input readonly  type="text" class="form-control" 
                                value="{{date('d-m-Y H:i', strtotime($tabla->fechasinc))}}" 
                                style=" color: #000000; background: #F7F7F7;" >
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Fecha Catálogo</label>
                                <input readonly  type="text" class="form-control" 
                                value="{{date('d-m-Y H:i', strtotime($tabla->fechacata))}}" 
                                style=" color: #000000; background: #F7F7F7;" >
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Web</label>
                                <a href="{{'http://'.$tabla->web}}">
                                    <span style="color: #000000; background: #F7F7F7;" class="form-control">
                                        {{$tabla->web}}
                                    </span>
                                </a>  
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Dirección</label>
                                <input readonly  type="text" class="form-control" value="{{$tabla->direccion}}" style=" color: #000000; background: #F7F7F7;" >
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Correo</label>
                                <input readonly  type="text" class="form-control" value="{{$tabla->correo}}" style=" color: #000000; background: #F7F7F7;" >
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tab_2">
                    <div class="row">
                
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Código</label>
                                <input readonly  type="text" class="form-control" value="{{$tabla->codprove}}" style=" color: #000000; background: #F7F7F7;" >
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Rif</label>
                                <input readonly  type="text" class="form-control" value="{{$tabla->codisb}}" style=" color: #000000; background: #F7F7F7;" >
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Estado</label>
                                <input readonly  type="text" class="form-control" value="{{$tabla->status}}" style=" color: #000000; background: #F7F7F7;" >
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Cantidad producto (Renglones)</label>
                                <input readonly  type="text" class="form-control" value="{{$tabla->contprod}}" style="color: #000000; background: #F7F7F7; text-align: right;" >
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Cantidad de producto con mejor precio</label>
                                <input readonly  type="text" class="form-control" value="{{$tabla->contRnkp1}}" style="color: #000000; background: #F7F7F7; text-align: right;" >
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Porcentaje de producto con mejor precio</label>
                                <input readonly  type="text" class="form-control" value="{{$tabla->porcRnkp1}}%" style="color: #000000; background: #F7F7F7; text-align: right;" >
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Cantidad de producto con mejor inventario</label>
                                <input readonly  type="text" class="form-control" value="{{$tabla->contRnkc1}}" style="color: #000000; background: #F7F7F7; text-align: right;" >
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Porcentaje de producto con mejor inventario</label>
                                <input readonly  type="text" class="form-control" value="{{$tabla->porcRnkc1}}%" style="color: #000000; background: #F7F7F7; text-align: right;" >
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Origen de creación</label>
                                <input readonly  type="text" class="form-control" value="{{$tabla->origen}}" style="color: #000000; background: #F7F7F7;" >
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label >Modo cambiario:</label>
                                <input readonly value="{{$tabla->factorModo}}" type="text" class="form-control">
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label >Factor Seleccion:</label>
                                <input readonly value="{{$tabla->factorSeleccion}}" type="text" class="form-control">
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label >Factor:</label>
                                <input readonly value="{{number_format($tabla->FactorCambiario, 2, '.', ',')}}" type="text" class="form-control">
                            </div>
                        </div>
 
                    </div>
                </div>
                <div class="tab-pane" id="tab_3">
                    <div class="row">
                
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Código del cliente</label>
                                <input readonly  type="text" class="form-control" value="{{$maeclieprove->codigo}}" style="color: #000000; background: #F7F7F7;" >
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Usuario</label>
                                <input readonly  type="text" class="form-control" value="{{$maeclieprove->usuario}}" style="color: #000000; background: #F7F7F7; " >
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Clave</label>
                                <input readonly  type="text" class="form-control" value="{{$maeclieprove->clave}}" style="color: #000000; background: #F7F7F7; " >
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Estado</label>
                                <input readonly  type="text" class="form-control" value="{{$maeclieprove->status}}" style="color: #000000; background: #F7F7F7; " >
                            </div>
                        </div>


                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Dás de corte</label>
                                <input readonly  type="text" class="form-control" value="{{$maeclieprove->corte}}" style="color: #000000; background: #F7F7F7;" >
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Dás de crédito</label>
                                <input readonly  type="text" class="form-control" value="{{$maeclieprove->dcredito}}" style="color: #000000; background: #F7F7F7; text-align: right;" >
                            </div>
                        </div>
               
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Limite de crédito</label>
                                <input readonly  type="text" class="form-control" value="{{$maeclieprove->mcredito}}" style="color: #000000; background: #F7F7F7; text-align: right;" >
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>DC->Descuento Comercial</label>
                                <input readonly  type="text" class="form-control" value="{{$maeclieprove->dcme}}%" style="color: #000000; background: #F7F7F7; text-align: right;" >
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>PP->Descuento Pronto Pago</label>
                                <input readonly  type="text" class="form-control" value="{{$maeclieprove->ppme}}%" style="color: #000000; background: #F7F7F7; text-align: right;" >
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>DI->Descuento Internet</label>
                                <input readonly  type="text" class="form-control" value="{{$maeclieprove->di}}%" style="color: #000000; background: #F7F7F7; text-align: right;" >
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Tipo de precio</label>
                                <input readonly  type="text" class="form-control" value="{{$maeclieprove->tipoprecio}}" style="color: #000000; background: #F7F7F7; text-align: right;" >
                            </div>
                        </div>
               

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-md-12">
    <button type="button" class="btn-normal" onclick="history.back(-1)">Regresar</button>
</div>

@push ('scripts')
<script>
$('#subtitulo').text('{{$subtitulo}}');
</script>
@endpush
@endsection

