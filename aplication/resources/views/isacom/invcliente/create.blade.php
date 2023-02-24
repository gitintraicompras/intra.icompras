@extends ('layouts.menu')
@section ('contenido')


{!! Form::open(array('url'=>'/invcliente','method'=>'POST','autocomplete'=>'off', 'enctype'=>'multipart/form-data')) !!}
{{ Form::token() }}
<input id='idformato' hidden name="formato" value="{{$formato}}" type="text">
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="row" >
		<div class="col-lg-3 col-md-3 col-sm-3" align="center">
			<!-- IMAGEN -->
				<img src="http://isaweb.isbsistemas.com/public/storage//{{$cliente->rutaimg}}" 
				class="img-responsive" 
				alt="icompras360" 
				width="100%"
				style="border: 2px solid #D2D6DE;"
				oncontextmenu="return false">
     	</div>
		<div class="col-lg-9 col-md-9 col-sm-9">
			<div class="col-lg-9 col-md-9 col-sm-9">
				<div >
					<img src="{{asset('images/linea.png')}}" width="90%" height="50" class="img-responsive">
				</div>
				<p><strong>SUBIR ARCHIVO</strong></p>
		  		<p align="justify" class="Estilo4 Estilo3">Seleccione el nombre del archivo txt que desea subir al portal web</p> 
			</div>   
			<div class="col-lg-9 col-md-9 col-sm-9">
				<div class="form-group">
					<input type="file" name="linkarchivo">
				</div>
			</div>
		</div>

	</div>

	<br>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="form-group">
			<div class="form-group">

				<!-- VER LOG -->
				<a href="" data-target="#modal-log" data-toggle="modal">
					<button type="button" class="btn-normal" data-toggle="tooltip" title="Ver log de errores de la carga">
					Ver Log
					</button>
				</a>

	            <a href="{{URL::action('InvClienteController@ejemplo1')}}">
					<button type="button" class="btn-normal" onclick="history.back(-1)" data-toggle="tooltip" title="Descargar ejemplo1">Ver ejemplo1
					</button>
				</a>

				 <a href="{{URL::action('InvClienteController@ejemplo2')}}">
					<button type="button" class="btn-normal" onclick="history.back(-1)" data-toggle="tooltip" title="Descargar ejemplo2">Ver ejemplo2
					</button>
				</a>

			    <button type="button" class="btn-normal" onclick="history.back(-1)" data-toggle="tooltip" title="Regresar">Regresar</button>
				<button class="btn-confirmar" type="submit" data-toggle="tooltip" title="Subir archivo">Subir</button>
			</div>
		</div>
	</div>
	<br><br><br><br>
	<div class="row">
	    <div class="col-md-12">
	        <div class="nav-tabs-custom">
	            <ul class="nav nav-tabs">
	              <li class="active" onclick='tab1click(event);'>
	              	<a href="#tab_1" data-toggle="tab">FORMATO VARIABLE</a>
	              </li>
	              <li onclick='tab2click(event);'>
	              	<a href="#tab_2" data-toggle="tab">FORMATO FIJO</a>
	              </li>
	              <li class="pull-right"><a href="{{url('/')}}" class="text-muted">
	              	<i class="fa fa-times"></i></a>
	              </li>
	            </ul>
	            <div class="tab-content">
	                <div class="tab-pane active" id="tab_1">
	                    <div class="row">

	                    	<div class="row">
								<center>
									<b>TABLA CON LA ESTRUCTURA DEL ARCHIVO CSV</b>
								</center>
								<center>
									<b>CARACTER SEPARADOR ( ';' ) </b>
								</center>
							</div>	
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="table-responsive" style="width: 100%;">
									<table class="table table-striped table-bordered table-condensed table-hover">
										<thead class="colorTitulo">
											<th style="width: 10%;">CAMPOS</th>
											<th style="width: 100px;">UBICACION</th>
											<th style="width: 90%;">DESCRIPCION</th>
										</thead>
										<tr>
											<td class="colorTitulo" style="width: 10%;">LINEA INICIO</td>
											<td>
												<div class="col-xs-12 input-group">
									                <select id="id_LineaInicio" class="form-control" style="width: 100px;">
	                                                    @for ($i = 1; $i < 21; $i++)
															<option 
															@if ($cliente->LineaInicio == $i) selected @endif value="{{ $i }}">{{ $i }}
															</option>
														@endfor
	                                                </select>
	                                                <span class="input-group-btn" onclick='tdclick(event);'>
												        <button id="id1_LineaInicio" type="button" class="btn btn-pedido" data-toggle="tooltip" title="Aceptar valor" >
												            <span id="id2_LineaInicio" class="fa fa-check" aria-hidden="true">
												            </span>
												        </button>
												    </span>
	                                            </div>
	                                        </td>
	                                        <td>LINEA DE INICIO DEL ARCHIVO</td>
										</tr>
										<tr>
											<td class="colorTitulo" style="width: 10%;">MONEDA</td>
											<td>
												<div class="col-xs-12 input-group">
									                <select id="id_CodMoneda" class="form-control" style="width: 100px;">
	                                                	<option value="BSS" @if ($cliente->CodMoneda == 'BSS') selected @endif>BSS</option>
	                                                	<option value="OM" @if ($cliente->CodMoneda == 'OM') selected @endif>OM</option>
	                                                </select>
	                                                <span class="input-group-btn" onclick='tdclick(event);'>
												        <button id="id1_CodMoneda" type="button" class="btn btn-pedido" data-toggle="tooltip" title="Aceptar valor" >
												            <span id="id2_CodMoneda" class="fa fa-check" aria-hidden="true">
												            </span>
												        </button>
												    </span>
	                                            </div>
	                                        </td>
	                                        <td>TIPO DE MONEDA EN QUE ESTA EXPRESADO EL PRECIO</td>
										</tr>
										<tr>
											<td class="colorTitulo" style="width: 10%;">FACTOR</td>
											<td>
												<div class="col-xs-12 input-group">
									                <input id="id_FactorCambiario" class="form-control" style="width: 100px;" value="{{$cliente->FactorCambiario}}">
									             	<span class="input-group-btn" onclick='tdclick(event);'>
												        <button id="id1_FactorCambiario" type="button" class="btn btn-pedido" data-toggle="tooltip" title="Aceptar valor" >
												            <span id="id2_FactorCambiario" class="fa fa-check" aria-hidden="true">
												            </span>
												        </button>
												    </span>
											   	</div>
									        </td>
	                                        <td>FACTOR CAMBIARIO DEL TIPO OTRA MONEDA</td>
										</tr>

										<tr>
											<td class="colorTitulo" style="width: 10%;">REFERENCIA</td>
											<td>
												<div class="col-xs-12 input-group">
									                <select id="id_ColRef" class="form-control" style="width: 100px;">
	                                                    @php $miletra = "A";
												        for ($i=0; $i < 26; $i++) { @endphp
												     
												            <option 
															@if ($cliente->ColRef == $miletra) selected @endif value="{{ $miletra }}">{{ $miletra }}
															</option>

												        @php $miletra++;
												        }
												        @endphp
	                                                </select>
	                                                <span class="input-group-btn" onclick='tdclick(event);'>
												        <button id="id1_ColRef" type="button" class="btn btn-pedido" data-toggle="tooltip" title="Aceptar valor" >
												            <span id="id2_ColRef" class="fa fa-check" aria-hidden="true">
												            </span>
												        </button>
												    </span>
                                            	</div>
	                                        </td>
	                                        <td>LETRA DE UBICACION DE LA REFERENCIA O BARRA (OBLIGATORIO)</td>
										</tr>
										<tr>
											<td class="colorTitulo" style="width: 10%;">CODIGO</td>
											<td>
												<div class="col-xs-12 input-group">
									                <select id="id_ColCodprod" class="form-control" style="width: 100px;">
	                                                    @php $miletra = "A";
												        for ($i=0; $i < 26; $i++) { @endphp
												     
												            <option 
															@if ($cliente->ColCodprod == $miletra) selected @endif value="{{ $miletra }}">{{ $miletra }}
															</option>

												        @php $miletra++;
												        }
												        @endphp
	                                                </select>
	                                                <span class="input-group-btn" onclick='tdclick(event);'>
												        <button id="id1_ColCodprod" type="button" class="btn btn-pedido" data-toggle="tooltip" title="Aceptar valor" >
												            <span id="id2_ColCodprod" class="fa fa-check" aria-hidden="true">
												            </span>
												        </button>
												    </span>
                                            	</div>
	                                        </td>
	                                        <td>LETRA DE UBICACION DEL CODIGO DEL PRODUCTO (OBLIGATORIO)</td>
										</tr>
										<tr>
											<td class="colorTitulo" style="width: 10%;">DESCRIPCION</td>
											<td>
												<div class="col-xs-12 input-group">
									                <select id="id_ColDesprod" class="form-control" style="width: 100px;">
	                                                    @php $miletra = "A";
												        for ($i=0; $i < 26; $i++) { @endphp
												     
												            <option 
															@if ($cliente->ColDesprod == $miletra ) selected @endif value="{{ $miletra }}">{{ $miletra }}
															</option>

												        @php $miletra++;
												        }
												        @endphp
	                                                </select>
	                                                <span class="input-group-btn" onclick='tdclick(event);'>
												        <button id="id1_ColDesprod" type="button" class="btn btn-pedido" data-toggle="tooltip" title="Aceptar valor" >
												            <span id="id2_ColDesprod" class="fa fa-check" aria-hidden="true">
												            </span>
												        </button>
												    </span>
                                            	</div>
	                                        </td>
	                                        <td>LETRA DE UBICACION DE LA DESCRIPCION DEL PRODUCTO ((OBLIGATORIO)</td>
										</tr>
										<tr>
											<td class="colorTitulo" style="width: 10%;">CANTIDAD</td>
											<td>
												<div class="col-xs-12 input-group">
									                <select id="id_ColCantidad" class="form-control" style="width: 100px;">
	                                                    @php $miletra = "A";
												        for ($i=0; $i < 26; $i++) { @endphp
												     
												            <option 
															@if ($cliente->ColCantidad == $miletra) selected @endif value="{{ $miletra }}">{{ $miletra }}
															</option>

												        @php $miletra++;
												        }
												        @endphp
	                                                </select>
	                                                <span class="input-group-btn" onclick='tdclick(event);'>
												        <button id="id1_ColCantidad" type="button" class="btn btn-pedido" data-toggle="tooltip" title="Aceptar valor" >
												            <span id="id2_ColCantidad" class="fa fa-check" aria-hidden="true">
												            </span>
												        </button>
												    </span>
	                                            </div>
	                                        </td>
	                                        <td>LETRA DE UBICACION DE LA CANTIDAD (OBLIGATORIO) </td>
										</tr>
										<tr>
											<td class="colorTitulo" style="width: 10%;">PRECIO</td>
											<td>
												<div class="col-xs-12 input-group">
									                <select id="id_ColPrecio" class="form-control" style="width: 100px;">
	                                                    @php $miletra = "A";
												        for ($i=0; $i < 26; $i++) { @endphp
												     
												            <option 
															@if ($cliente->ColPrecio == $miletra ) selected @endif value="{{ $miletra }}">{{ $miletra }}
															</option>

												        @php $miletra++;
												        }
												        @endphp
	                                                </select>
	                                                <span class="input-group-btn" onclick='tdclick(event);'>
												        <button id="id1_ColPrecio" type="button" class="btn btn-pedido" data-toggle="tooltip" title="Aceptar valor" >
												            <span id="id2_ColPrecio" class="fa fa-check" aria-hidden="true">
												            </span>
												        </button>
												    </span>
	                                            </div>
	                                        </td>
	                                        <td>LETRA DE UBICACION DEL PRECIO DEL PRODUCTO (OBLIGATORIO) </td>
										</tr>
										<tr>
											<td class="colorTitulo" style="width: 10%;">IVA</td>
											<td>
												<div class="col-xs-12 input-group">
									                <select id="id_ColIva" class="form-control" style="width: 100px;">

									                	<option 
															@if ($cliente->ColIva == '') selected @endif 
															value="">
														</option>

	                                                    @php $miletra = "A";
												        for ($i=0; $i < 26; $i++) { @endphp
												     
												            <option 
															@if ($cliente->ColIva == $miletra) selected @endif value="{{ $miletra }}">{{ $miletra }}
															</option>

												        @php $miletra++;
												        }
												        @endphp
	                                                </select>
	                                                <span class="input-group-btn" onclick='tdclick(event);'>
												        <button id="id1_ColIva" type="button" class="btn btn-pedido" data-toggle="tooltip" title="Aceptar valor" >
												            <span id="id2_ColIva" class="fa fa-check" aria-hidden="true">
												            </span>
												        </button>
												    </span>
	                                            </div>
	                                        </td>
	                                        <td>LETRA DE UBICACION DEL IVA DEL PRODUCTO (SI NO APLICA COLOQUELO EN BLANCO, POR DEFECTO 0.00)</td>
										</tr>
										<tr>
											<td class="colorTitulo" style="width: 10%;">DA</td>
											<td>
												<div class="col-xs-12 input-group">
									                <select id="id_ColDa" class="form-control" style="width: 100px;">

									                	<option 
															@if ($cliente->ColDa == '') selected @endif 
															value="">
														</option>

	                                                    @php $miletra = "A";
												        for ($i=0; $i < 26; $i++) { @endphp
												     
												            <option 
															@if ($cliente->ColDa == $miletra) selected @endif 
															value="{{ $miletra }}">{{ $miletra }}
															</option>

												        @php $miletra++;
												        }
												        @endphp
	                                                </select>
	                                                <span class="input-group-btn" onclick='tdclick(event);'>
												        <button id="id1_ColDa" type="button" class="btn btn-pedido" data-toggle="tooltip" title="Aceptar valor" >
												            <span id="id2_ColDa" class="fa fa-check" aria-hidden="true">
												            </span>
												        </button>
												    </span>
	                                            </div>
	                                        </td>
	                                        <td>LETRA DE UBICACION DEL DESCUENTO ADICIONAL DEL PRODUCTO ( SI NO APLICA COLOQUELO EN BLANCO) </td>
										</tr>
										<tr>
											<td class="colorTitulo" style="width: 10%;">MARCA</td>
											<td>
												<div class="col-xs-12 input-group">
									                <select id="id_ColMarca" class="form-control" style="width: 100px;">

									                	<option 
															@if ($cliente->ColMarca == '') selected @endif 
															value="">
														</option>

	                                                    @php $miletra = "A";
												        for ($i=0; $i < 26; $i++) { @endphp
												     
												            <option 
															@if ($cliente->ColMarca == $miletra) selected @endif value="{{ $miletra }}">{{ $miletra }}
															</option>

												        @php $miletra++;
												        }
												        @endphp
	                                                </select>
	                                                <span class="input-group-btn" onclick='tdclick(event);'>
												        <button id="id1_ColMarca" type="button" class="btn btn-pedido" data-toggle="tooltip" title="Aceptar valor" >
												            <span id="id2_ColMarca" class="fa fa-check" aria-hidden="true">
												            </span>
												        </button>
												    </span>
	                                            </div>
	                                        </td>
	                                        <td>LETRA DE UBICACION DE LA MARCA DEL PRODUCTO (SI NO APLICA COLOQUELO EN BLANCO)</td>
										</tr>
										<tr>
											<td class="colorTitulo" style="width: 10%;">LOTE</td>
											<td>
												<div class="col-xs-12 input-group">
									                <select id="id_ColLote" class="form-control" style="width: 100px;">

									                	<option 
															@if ($cliente->ColLote == '') selected @endif 
															value="">
														</option>

	                                                    @php $miletra = "A";
												        for ($i=0; $i < 26; $i++) { @endphp
												     
												            <option 
															@if ($cliente->ColLote == $miletra ) selected @endif value="{{ $miletra }}">{{ $miletra }}
															</option>

												        @php $miletra++;
												        }
												        @endphp
	                                                </select>
	                                                <span class="input-group-btn" onclick='tdclick(event);'>
												        <button id="id1_ColLote" type="button" class="btn btn-pedido" data-toggle="tooltip" title="Aceptar valor" >
												            <span id="id2_ColLote" class="fa fa-check" aria-hidden="true">
												            </span>
												        </button>
												    </span>
	                                            </div>
	                                        </td>
	                                        <td>LETRA DE UBICACION DEL LOTE DEL PRODUCTO (SI NO APLICA COLOQUELO EN BLANCO)</td>
										</tr>
										<tr>
											<td class="colorTitulo" style="width: 10%;">FECHA LOTE</td>
											<td>
												<div class="col-xs-12 input-group">
									                <select id="id_ColFechaLote" class="form-control" style="width: 100px;">

									                	<option 
															@if ($cliente->ColFechaLote == '') selected @endif 
															value="">
														</option>
														

	                                                    @php $miletra = "A";
												        for ($i =0; $i < 26; $i++) { @endphp
												     
												            <option 
															@if ($cliente->ColFechaLote == $miletra) selected @endif value="{{ $miletra }}">{{ $miletra }}
															</option>

												        @php $miletra++;
												        }
												        @endphp
	                                                </select>
	                                                <span class="input-group-btn" onclick='tdclick(event);'>
												        <button id="id1_ColFechaLote" type="button" class="btn btn-pedido" data-toggle="tooltip" title="Aceptar valor" >
												            <span id="id2_ColFechaLote" class="fa fa-check" aria-hidden="true">
												            </span>
												        </button>
												    </span>
	                                            </div>
	                                        </td>
	                                        <td>LETRA DE UBICACION DE LA FECHA DEL LOTE DEL PRODUCTO (SI NO APLICA COLOQUELO EN BLANCO)</td>
										</tr>
										
										<tr>
											<td class="colorTitulo" style="width: 10%;">COSTO</td>
											<td>
												<div class="col-xs-12 input-group">
									                <select id="id_ColCosto" class="form-control" style="width: 100px;">

									                	<option 
															@if ($cliente->ColCosto == '') selected @endif 
															value="">
														</option>
														

	                                                    @php $miletra = "A";
												        for ($i =0; $i < 26; $i++) { @endphp
												     
												            <option 
															@if ($cliente->ColCosto == $miletra) selected @endif value="{{ $miletra }}">{{ $miletra }}
															</option>

												        @php $miletra++;
												        }
												        @endphp
	                                                </select>
	                                                <span class="input-group-btn" onclick='tdclick(event);'>
												        <button id="id1_ColCosto" type="button" class="btn btn-pedido" data-toggle="tooltip" title="Aceptar valor" >
												            <span id="id2_ColCosto" class="fa fa-check" aria-hidden="true">
												            </span>
												        </button>
												    </span>
	                                            </div>
	                                        </td>
	                                        <td>LETRA DE UBICACION DEL COSTO DEL PRODUCTO (SI NO APLICA COLOQUELO EN BLANCO)</td>
										</tr>
										<tr>
											<td class="colorTitulo" style="width: 10%;">VMD</td>
											<td>
												<div class="col-xs-12 input-group">
									                <select id="id_ColVmd" class="form-control" style="width: 100px;">

									                	<option 
															@if ($cliente->ColVmd == '') selected @endif 
															value="">
														</option>
														

	                                                    @php $miletra = "A";
												        for ($i =0; $i < 26; $i++) { @endphp
												     
												            <option 
															@if ($cliente->ColVmd == $miletra) selected @endif value="{{ $miletra }}">{{ $miletra }}
															</option>

												        @php $miletra++;
												        }
												        @endphp
	                                                </select>
	                                                <span class="input-group-btn" onclick='tdclick(event);'>
												        <button id="id1_ColVmd" type="button" class="btn btn-pedido" data-toggle="tooltip" title="Aceptar valor" >
												            <span id="id2_ColVmd" class="fa fa-check" aria-hidden="true">
												            </span>
												        </button>
												    </span>
	                                            </div>
	                                        </td>
	                                        <td>LETRA DE UBICACION DE LA VENTA MEDIA DIARIA (SI NO APLICA COLOQUELO EN BLANCO)</td>
										</tr>
									</table>
								</div>
							</div>

						</div>
					</div>
					<div class="tab-pane" id="tab_2">
	                    <div class="row">
				   			<div class="row">
								<center>
									<b>TABLA INFORMATIVA CON LA ESTRUCTURA DEL ARCHIVO TXT o CSV</b>
								</center>
								<center>
									<b>CARACTER SEPARADOR ( '|' o ';' ) </b>
								</center>
							</div>	
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="table-responsive" style="width: 100%;">
									<table class="table table-striped table-bordered table-condensed table-hover">
										<thead class="colorTitulo">
											<th></th>
											<th>CODIGO</th>
											<th>DESCRIPCION</th>
											<th>CANTIDAD</th>
											<th>VMD</th>
											<th>PRECIO</th>
											<th>MARCA</th>
											<th>REFERENCIA</th>
											<th>COSTO</th>
											<th>IVA</th>
										</thead>
										<tr>
											<td class="colorTitulo">DETALLE</td>
											<td>CODIGO DEL PRODUCTO</td>
											<td>DESCRIPCION DEL PRODUCTO</td>
											<td>CANTIDAD DEL INVENTARIO</td>
											<td>VENTA MEDIA DIARIA</td>
											<td>PRECIO DEL PRODUCTO</td>
											<td>MARCA DEL PRODUCTO</td>
											<td>CODIGO DE BARRA</td>
											<td>COSTO DEL PRODUCTO</td>
											<td>IMPUESTO </td>
										</tr>
										<tr>
											<td class="colorTitulo">TIPO DE DATO</td>
											<td>TEXTO (MAX. 20 CARACTERES)</td>
											<td>TEXTO (MAX. 150 CARACTERES)</td>
											<td>ENTERO</td>
											<td>NUMERO (4 DECIMALES)</td>
											<td>NUMERO, SIN COMA SOLO PUNTO (.) DECIMAL</td>
											<td>TEXTO (MAX. 100 CARACTERES)</td>
											<td>TEXTO (MAX. 13 CARACTERES)</td>
											<td>NUMERO, SIN COMA SOLO PUNTO (.) DECIMAL</td>
											<td>NUMERO, PORCENTAJE DEL IVA</td>
										</tr>
										<tr>
											<td class="colorTitulo">EJEMPLO</td>
											<td>0000001</td>
											<td>ACETAMINOFEN DE 500MG X 10 COMP</td>
											<td>1000</td>
											<td>2.0000 ó 0.0000</td>
											<td>2500000.00</td>
											<td>GENVEN</td>
											<td>7591235657874</td>
											<td>1900000.00 ó 0.00</td>
											<td>16.00 ó 0.00</td>
										</tr>
										<tr>
											<td class="colorTitulo">IMPORTANCIA</td>
											<td>OBLIGATORIO</td>
											<td>OBLIGATORIO</td>
											<td>OBLIGATORIO</td>
											<td>NO OBLIGATORIO</td>
											<td>OBLIGATORIO</td>
											<td>NO OBLIGATORIO</td>
											<td>OBLIGATORIO</td>
											<td>NO OBLIGATORIO</td>
											<td>OBLIGATORIO</td>
										</tr>
									</table>
								</div>
							</div>
	          			</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

{{ Form::close() }}

<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modal-log">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header colorTitulo" >
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">x</span>
				</button>
				<h4 class="modal-title">VER LOG</h4>
			</div>
			<div class="modal-body">
				<TEXTAREA readonly style="width: 100%;">{{$cliente->cargarLog}}</TEXTAREA>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn-normal" data-dismiss="modal">Regresar</button>
			</div>
		</div>
	</div>
</div>


@push ('scripts')
<script>
$('#subtitulo').text('{{$subtitulo}}');

function tdclick(e) {
    var id = e.target.id.split('_');
    var campo = id[1];
    var valor = $('#id_'+campo).val();

    //alert(' campo: ' + campo + ' valor: ' + valor);
    $.ajax({
	  type:'POST',
	  url:'././modcol',
	  dataType: 'json', 
	  encode  : true,
	  data: {valor : valor, campo : campo },
	  success:function(data) {
	    if (data.msg = "OK") {
    	    //alert('Cambio satisfactorio: codprove: ' + codprove + ' campo: ' + campo + ' valor: ' + valor);
    	} 
	  }
  	});
}

function tab1click(e) {
	$('#idformato').val('1');
}
function tab2click(e) {
	$('#idformato').val('2');
}

</script>
@endpush

@endsection