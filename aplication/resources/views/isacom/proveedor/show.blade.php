@extends ('layouts.menu')
@section ('contenido')


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
									<input readonly value="{{$provs->codprove}}-{{$provs->descripcion}}" type="text"  class="form-control">
								</div>
							</div>
							<!-- CODDRO -->
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label for="coddro">Código</label>
									<input readonly value="{{$provs->codigo}}" type="text" class="form-control">
								</div>
							</div>
							<!-- TIPO PRECIO -->
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label>Tipo de precio</label>
									<input readonly value="{{$provs->tipoprecio}}" type="text"  class="form-control" style="text-align: right;">
								</div>
							</div>
						</div>
						<div class="row col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<!-- SU CARPETA FTP -->
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label >Sub carpeta Ftp</label>
									<input readonly name="subcarpetaftp" value="{{$provs->subcarpetaftp}}" type="text" class="form-control">
								</div>
							</div>
							
							<!-- DCREDITO -->
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label for="dcredito">Dias credito</label>
									<input readonly value="{{$provs->dcredito}}" type="text"  class="form-control" style="text-align: right;">
								</div>
							</div>
							<!-- MCREDITO -->
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label for="mcredito">Monto credito</label>
									<input readonly value="{{$provs->mcredito}}" type="text" class="form-control" style="text-align: right;">
								</div>
							</div>
							<!-- CORTE -->
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label for="corte">Corte</label>
									<input readonly value="{{$provs->corte}}" type="text" class="form-control">
								</div>
							</div>
						</div>
						<div class="row col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<!-- USUARIO -->
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label for="usuario">Usuario</label>
									<input readonly value="{{$provs->usuario}}" type="text" class="form-control">
								</div>
							</div>
							<!-- CLAVE -->
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label for="clave">Clave</label>
									<input readonly value="{{$provs->clave}}" type="text"  class="form-control">
								</div>
							</div>

							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label >Modo cambiario:</label>
									<input readonly name="factorModo" value="{{$provs->factorModo}}" type="text" class="form-control">
								</div>
							</div>

							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label >Factor Seleccion:</label>
									<input readonly 
										name="factorSeleccion" 
										value="{{$provs->factorSeleccion}}" 
										type="text" 
										class="form-control">
								</div>
							</div>

							<!-- ACTUALIZAR COND. COMERCIALES AUTOMATICAMENTE -->
						    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
						        <div class="form-group" style="padding-top: 30px;">
						            <div class="form-check">
						                <input readonly="" 
						                	name="updCondComercial" 
						                	@if ($provs->updCondComercial == 1) checked @endif
						                	type="checkbox" 
						                	class="form-check-input" >
						                <label class="form-check-label" 
						                        for="materialUnchecked">
						                    Act. cond. Comerciales Automatica
						                </label>
						            </div>
						        </div>
						    </div>

						</div>

                    </div>
				</div>
				<div class="tab-pane" id="tab_2">
                    <div class="row">

						<div class="row col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<!-- DCME -->
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label for="dcme">Dcto comercial</label>
									<input readonly value="{{$provs->dcme}}" type="text" class="form-control" style="text-align: right;">
								</div>
							</div>
							<!-- DCMER -->
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label for="dcmer">Dcto comercial regulados</label>
									<input readonly value="{{$provs->dcmer}}" type="text"  class="form-control" style="text-align: right;">
								</div>
							</div>
							<!-- DCMI -->
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label for="dcmi">Dcto comercial miscelaneos</label>
									<input readonly value="{{$provs->dcmi}}" type="text" class="form-control" style="text-align: right;">
								</div>
							</div>
							<!-- DCMIR -->
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label for="dcmir">Dcto comercial misc. reg.</label>
									<input readonly value="{{$provs->dcmir}}" type="text" class="form-control" style="text-align: right;">
								</div>
							</div>
						</div>
						<div class="row col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<!-- DCME -->
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label for="ppme">Dcto pronto pago</label>
									<input readonly value="{{$provs->ppme}}" type="text" class="form-control" style="text-align: right;">
								</div>
							</div>
							<!-- DCMER -->
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label for="ppmi">Dcto pronto pago misc.</label>
									<input readonly value="{{$provs->ppmi}}" type="text"  class="form-control" style="text-align: right;">
								</div>
							</div>
							<!-- DCMI -->
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label for="di">Dcto Internet</label>
									<input readonly value="{{$provs->di}}" type="text" class="form-control" style="text-align: right;">
								</div>
							</div>
							<!-- DCMIR -->
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="form-group">
									<label for="dotro">Dcto Otros</label>
									<input readonly value="{{$provs->dotro}}" type="text" class="form-control" style="text-align: right;">
								</div>
							</div>
						</div>

                    </div>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="row col-lg-12 col-md-12 col-sm-12 col-xs-12">
	<button style="margin-left: 15px;" type="button" class="btn-normal" onclick="history.back(-1)" data-toggle="tooltip" title="Regresar">
		Regresar
	</button>
</div>


@push ('scripts')
<script>
$('#subtitulo').text('{{$subtitulo}}');
</script>
@endpush

@endsection