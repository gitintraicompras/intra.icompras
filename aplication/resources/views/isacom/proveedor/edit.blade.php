@extends ('layouts.menu')
@section ('contenido')
@if (count($errors)>0)
<div class="alert alert-danger">
	<ul>
		@foreach ($errors->all() as $error)
		<li>{{$error}}</li>
		@endforeach
	</ul>
</div> 
@endif
{!!Form::model($provs,['method'=>'PATCH','route'=>['proveedor.update',$provs->codprove]])!!}
{{Form::token()}}

<div class="row">
    <div class="col-md-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active" >
              	<a href="#tab_1" data-toggle="tab">BASICA</a>
              </li>
              <li>
              	<a href="#tab_2" data-toggle="tab">DESCUENTOS</a>
              </li>
              <li>
              	<a href="#tab_3" data-toggle="tab">PARAMETROS EXPORTAR (FAC/ODC)</a>
              </li>
              <li class="pull-right"><a href="{{url('/')}}" class="text-muted">
              	<i class="fa fa-times"></i></a>
              </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1">
                    <div class="row">

						<div class="row col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<!-- CODCLI -->
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label for="codcli">Cliente</label>
									<input readonly value="{{$provs->codcli}}" type="text" class="form-control">
								</div>
							</div>
							<!-- CODPROV -->
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label for="codcli">Proveedor</label>
									<input readonly 
										value="{{$provs->codprove}}-{{$provs->descripcion}}" 
										type="text"  
										class="form-control">
								</div>
							</div>
							<!-- CODDRO -->
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label for="codigo">Código</label>
									<input readonly name="codigo" value="{{$provs->codigo}}" type="text" class="form-control">
								</div>
							</div>
							<!-- TIPO DE PRECIO  -->
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label for="tipoprecio">Tipo de precio</label>
									<input name="tipoprecio" value="{{$provs->tipoprecio}}" type="number" class="form-control" style="text-align: right;">
								</div>
							</div>
						</div>
						<div class="row col-lg-12 col-md-12 col-sm-12 col-xs-12">

							<!-- SUB CARPETA FTP -->
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label >Sub carpeta Ftp</label>
									<input name="subcarpetaftp" value="{{$provs->subcarpetaftp}}" type="text" class="form-control">
								</div>
							</div>
							
							<!-- MCREDITO -->
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label for="mcredito">Monto credito</label>
									<input name="mcredito" value="{{$provs->mcredito}}" type="text" class="form-control" style="text-align: right;">
								</div>
							</div>
							<!-- CORTE -->
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label for="corte">Corte</label>
									<select name="corte" class="form-control">
										@if ($provs->corte == 'LUNES')
									        <option value="LUNES" selected >LUNES</option>
							    			<option value="MARTES">MARTES</option>
							    			<option value="MIERCOLES">MIERCOLES</option>
							    			<option value="JUEVES">JUEVES</option>
							    			<option value="VIERNES">VIERNES</option>
							    			<option value="SABADO">SABADO</option>
							    			<option value="DOMINGO">DOMINGO</option>
									   	@elseif ($provs->corte == 'MARTES')
									   		<option value="LUNES">LUNES</option>
							    			<option value="MARTES" selected>MARTES</option>
							    			<option value="MIERCOLES">MIERCOLES</option>
							    			<option value="JUEVES">JUEVES</option>
							    			<option value="VIERNES">VIERNES</option>
							    			<option value="SABADO">SABADO</option>
							    			<option value="DOMINGO">DOMINGO</option>
										@elseif ($provs->corte == 'MIERCOLES')
											<option value="LUNES">LUNES</option>
							    			<option value="MARTES">MARTES</option>
							    			<option value="MIERCOLES" selected>MIERCOLES</option>
							    			<option value="JUEVES">JUEVES</option>
							    			<option value="VIERNES">VIERNES</option>
							    			<option value="SABADO">SABADO</option>
							    			<option value="DOMINGO">DOMINGO</option>
								    	@elseif ($provs->corte == 'JUEVES')
								    		<option value="LUNES">LUNES</option>
							    			<option value="MARTES">MARTES</option>
							    			<option value="MIERCOLES">MIERCOLES</option>
							    			<option value="JUEVES" selected>JUEVES</option>
							    			<option value="VIERNES">VIERNES</option>
							    			<option value="SABADO">SABADO</option>
							    			<option value="DOMINGO">DOMINGO</option>
							    		@elseif ($provs->corte == 'VIERNES')
							    			<option value="LUNES">LUNES</option>
							    			<option value="MARTES">MARTES</option>
							    			<option value="MIERCOLES">MIERCOLES</option>
							    			<option value="JUEVES">JUEVES</option>
							    			<option value="VIERNES" selected>VIERNES</option>
							    			<option value="SABADO">SABADO</option>
							    			<option value="DOMINGO">DOMINGO</option>
							    		@elseif ($provs->corte == 'SABADO')
							    			<option value="LUNES">LUNES</option>
							    			<option value="MARTES">MARTES</option>
							    			<option value="MIERCOLES">MIERCOLES</option>
							    			<option value="JUEVES">JUEVES</option>
							    			<option value="VIERNES">VIERNES</option>
							    			<option value="SABADO" selected>SABADO</option>
							    			<option value="DOMINGO">DOMINGO</option>
							    		@elseif ($provs->corte == 'DOMINGO')
							    			<option value="LUNES">LUNES</option>
							    			<option value="MARTES">MARTES</option>
							    			<option value="MIERCOLES">MIERCOLES</option>
							    			<option value="JUEVES">JUEVES</option>
							    			<option value="VIERNES">VIERNES</option>
							    			<option value="SABADO">SABADO</option>
							    			<option value="DOMINGO" selected>DOMINGO</option>
										@endif
								    </select>
								</div>
							</div>
							<!-- ORDEN DE PREFERENCIA -->
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label for="codcli">Orden Preferecia</label>
									<input readonly 
										value="{{$provs->preferencia}}" 
										type="text"  
										class="form-control">
								</div>
							</div>
						</div>
						<div class="row col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<!-- USUARIO -->
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label for="usuario">Usuario</label>
									<input name="usuario" 
										style="background-color: #FFE7E5" 
										value="{{$provs->usuario}}" 
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
										value="{{$provs->clave}}" 
										type="text"  
										class="form-control">
								</div>
							</div>

							<!-- CORREO -->
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label title = "Solo aplica para aquellos proveedores que esten configurado para recibir el pedido por correo" for="clave">Correo recibe el pedido</label>
									<input name="correoEnvioPedido" 
										style="background-color: #FFE7E5" 
										value="{{$maeprove->correoEnvioPedido}}" 
										type="text"  
										class="form-control">
								</div>
							</div>

							<!-- STATUS -->
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label for="statusclieprove">Status</label>
									<select name="statusclieprove" class="form-control">
										@if ($provs->statusclieprove == 'ACTIVO')
									        <option value="ACTIVO" selected >ACTIVO</option>
							    			<option value="INACTIVO">INACTIVO</option>
							    		@else
							    			<option value="ACTIVO">ACTIVO</option>
							    			<option value="INACTIVO" selected>INACTIVO</option>
							    		@endif
						    	    </select>
								</div>
							</div>
						</div>
						<div class="row col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label >Modo cambiario:</label>
									<input readonly name="factorModo" value="{{$provs->factorModo}}" type="text" class="form-control">
								</div>
							</div>

							@if ($provs->factorModo == "PREDETERMINADO")
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
									<div class="form-group">
										<label>factor Seleccion</label>
										<input readonly name="factorSeleccion" value="{{$provs->factorSeleccion}}" type="text"  class="form-control">
									</div>
								</div>

								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
									<div class="form-group">
										<label>Factor cambiario</label>
										<input readonly name="FactorCambiario" value="{{number_format($provs->FactorCambiario, 2, '.', ',')}}" type="text"  class="form-control">
									</div>
								</div>
							@else

		                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
		                            <div class="form-group">
		                                <label>factor Seleccion</label>
		                                <select name="factorSeleccion" id="SelClick" class="form-control">
		                                    <option value="BCV" 
		                                    @if ($provs->factorSeleccion == "BCV") selected @endif
		                                    >
		                                    BCV
		                                    </option>
		                                    <option value="TODAY" 
		                                    @if ($provs->factorSeleccion == "TODAY") selected @endif
		                                    >
		                                    TODAY
		                                    </option>
											<option value="MANUAL" 
											@if ($provs->factorSeleccion == "MANUAL") selected @endif
		                                    >
		                                    MANUAL
		                                    </option>
		                                </select>
		                            </div>
		                        </div>

		                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
									<div class="form-group">
										<label>Factor cambiario2</label>
										<input id="FactorCambiario" 
											style="text-align: right;"
											readonly="" 
											name="FactorCambiario" 
											value="{{number_format($provs->FactorCambiario,2,'.', ',')}}"
											type="text"  
											class="form-control">
									</div>
								</div>

							@endif

						</div>

                    </div>
                </div>
                <div class="tab-pane" id="tab_2">
                    <div class="row">

						<div class="row col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<!-- DC -->
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label for="dcme">Dcto comercial</label>
									<input name="dcme" 
										value="{{$provs->dcme}}" 
										type="text" 
										class="form-control"
										style="text-align: right;"
										title="DESCUENTO COMERCIAL ASIGANDO POR EL PROVEEDOR" 
										@if ($provs->updCondComercial == 1) readonly @endif>
								</div>
							</div>
							<!-- PP -->
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label for="ppme">Dcto pronto pago</label>
									<input name="ppme" 
										value="{{$provs->ppme}}" 
										type="text" 
										class="form-control"
										title="DESCUENTO PRONTO PAGO, NEGOCIADO POR EL PROVEEDOR" 
										style="text-align: right;">
								</div>
							</div>
							<!-- Di -->
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label for="di">Dcto Internet</label>
									<input name="di" 
										value="{{$provs->di}}" 
										type="text" 
										class="form-control"
										title="DESCUENTO DE INTERNET, NEGOCIADO POR PROVEEDOR" 
										style="text-align: right;">
								</div>
							</div>
							<!-- DCREDITO -->
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label for="dcredito">Dias credito</label>
									<input name="dcredito" 
										value="{{$provs->dcredito}}" 
										type="number" 
										class="form-control"
										title="DIAS DE CREDITO ASIGNADO POR EL PROVEEDOR" 
										style="text-align: right;" >
								</div>
							</div>
						</div>
						<div class="row col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<!-- DCMER -->
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label for="dcmer">Dcto comercial regulados</label>
									<input readonly name="dcmer" value="{{$provs->dcmer}}" type="text" class="form-control" style="text-align: right;">
								</div>
							</div>
							<!-- DCMI -->
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label for="dcmi">Dcto comercial miscelaneos</label>
									<input readonly name="dcmi" value="{{$provs->dcmi}}" type="text" class="form-control" style="text-align: right;">
								</div>
							</div>
							<!-- DCMIR -->
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label for="dcmir">Dcto comercial misc. reg.</label>
									<input readonly name="dcmir" value="{{$provs->dcmir}}" type="text" class="form-control" style="text-align: right;">
								</div>
							</div>
							<!-- PPMER -->
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label for="ppmi">Dcto pronto pago misc.</label>
									<input readonly name="ppmi" value="{{$provs->ppmi}}" type="text"  class="form-control" style="text-align: right;">
								</div>
							</div>
							
							<!-- DO -->
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label for="dotro">Dcto Otros</label>
									<input readonly name="dotro" value="{{$provs->dotro}}" type="text" class="form-control" style="text-align: right;">
								</div>
							</div>

							<!-- ACTUALIZAR COND. COMERCIALES AUTOMATICAMENTE -->
							@if ($maeprove->modoEnvioPedido == 'MYSQL')
						    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
						        <div class="form-group" style="padding-top: 30px;">
						            <div class="form-check">
						                <input name="updCondComercial" 
						                	type="checkbox" 
						                	title="EL DECUENTO COMERCIAL SERA TOMADO AUTOMATICAMENTE DEL PORTAL WEB DEL PROVEEDOR" 
						                	@if ($provs->updCondComercial == 1) checked @endif
						                	class="form-check-input" >
						                <label class="form-check-label" 
						                        for="materialUnchecked">
						                    Act. Descuento Comercial Automatico
						                </label>
						            </div>
						        </div>
						    </div>
						    @endif

						</div>

                    </div>
                </div>
                <div class="tab-pane" id="tab_3">
                    <div class="row">
						<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
							<div class="form-group">
								<label>Código del proveedor</label>
								<input name="codprove_adm" value="{{$provs->codprove_adm}}" type="text" class="form-control" >
							</div>
						</div>
                    </div>
                </div>
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
{{Form::close()}}


@push ('scripts')
<script>
$('#subtitulo').text('{{$subtitulo}}');
$('#SelClick').on('change', function()
{
    var factorSeleccion = this.value;
  	//alert(factorSeleccion);  
  	if (factorSeleccion == "MANUAL")
  		document.getElementById('FactorCambiario').readOnly = false;
  	else
  		document.getElementById('FactorCambiario').readOnly = true;

});


</script>
@endpush

@endsection