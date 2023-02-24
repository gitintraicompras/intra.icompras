@extends('layouts.menu')
@section ('contenido')

<section class="content" >
	<!-- Info boxes -->
	<div class="row" >
		@foreach ($carga as $c)
	    <div class="col-md-6 col-sm-12 col-xs-12">
	      <div class="info-box" style="background-color: #f7f7f7;">
	      	<a href="{{URL::action('DescargaController@show',$c->id)}}">
	        	<span class="info-box-icon colorTitulo"><i class="fa fa-download"></i></span>
	    	</a>
	        <div class="info-box-content">
	          <span class="info-box-text">{{$c->ruta}}</span>
	          <span class="info-box-number" style="font-size: 14px;">
	          	{{ $c->descrip }}
	          	<br>
	          	<small>({{  number_format($c->contdescarga, 0, '.', ',') }}) descargas</small>
	          </span>
	        </div>
	      </div>
	    </div>
	    @endforeach

		@if (Auth::user()->tipo=="C" || Auth::user()->tipo=="G")
	    <!-- DESCARGA DE CATALOGO DE PROVEEDORES -->
	    <div class="col-md-6 col-sm-12 col-xs-12">
	      <div class="info-box" style="background-color: #f7f7f7;">
	    	<a href="" data-target="#modal-descargarCat" data-toggle="modal">
			    <span class="info-box-icon colorTitulo"><i class="fa fa-download"></i></span>
			</a>
		    <div class="info-box-content">
	          <span class="info-box-text">Proveedores</span>
	          <span class="info-box-number" style="font-size: 14px;">
	          	CATALOGO DE PRODUCTOS
	          	<br>
	          	<small></small>
	          </span>
	        </div>
	      </div>
	    </div>

	    <!-- DESCARGA DE RNK1 DE PROVEEDORES -->
	    <div class="col-md-6 col-sm-12 col-xs-12">
	      <div class="info-box" style="background-color: #f7f7f7;">
	    	<a href="" data-target="#modal-descargarRnk1" data-toggle="modal">
			    <span class="info-box-icon colorTitulo"><i class="fa fa-download"></i></span>
			</a>
		    <div class="info-box-content">
	          <span class="info-box-text">Proveedores</span>
	          <span class="info-box-number" style="font-size: 14px;">
	          	RNK1 DE PRODUCTOS
	          	<br>
	          	<small></small>
	          </span>
	        </div>
	      </div>
	    </div>
  
	    <!-- DESCARGA DE INVENTARIO DE CLIENTES -->
	    <div class="col-md-6 col-sm-12 col-xs-12">
	      <div class="info-box" style="background-color: #f7f7f7;">
	    	<a href="" data-target="#modal-descargarInv" data-toggle="modal">
			    <span class="info-box-icon colorTitulo"><i class="fa fa-download"></i></span>
			</a>
		    <div class="info-box-content">
	          <span class="info-box-text">Clientes</span>
	          <span class="info-box-number" style="font-size: 14px;">
	          	INVENTARIO DE PRODUCTOS
	          	<br>
	          	<small></small>
	          </span>
	        </div>
	      </div>
	    </div>
	    @endif

	</div>
</section>

<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modal-descargarCat">
{!! Form::open(array('action'=>array('DescargaController@catalogo','method'=>'POST','autocomplete'=>'off'))) !!}
{{ Form::token() }}
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header colorTitulo" >
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">X</span>
			</button>
			<h4 class="modal-title">DESCARGAR CATALOGO</h4>
		</div>

		@php
		$provs = TablaMaecliproveActiva("");
	   	@endphp

		<div class="modal-body">
		 	<div class="row">
			    <div class="col-xs-12">
					<div class="form-group">

						<div class="form-group">
							<label>Proveedor</label>
							<select name="codprove" class="form-control" >
								<option value="tpmaestra">
									TPMAESTRA
								</option>
								@foreach($provs as $prov)
									<option value="{{$prov->codprove}}">
										{{$prov->codprove}} - {{$prov->descripcion}}
									</option>
								@endforeach
							</select>
						</div>

				    </div>
			    </div>
		    </div>
		</div>

		<div class="modal-footer" style="margin-right: 20px;">
			<button type="button" class="btn-normal" data-dismiss="modal">Regresar</button>
			<button type="submit" class="btn-confirmar btnAccion">Confirmar</button>
		</div>
	</div>
</div>
{{Form::Close()}}
</div>

<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modal-descargarRnk1">
{!! Form::open(array('action'=>array('DescargaController@rnk1','method'=>'POST','autocomplete'=>'off'))) !!}
{{ Form::token() }}
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header colorTitulo" >
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">X</span>
			</button>
			<h4 class="modal-title">DESCARGAR RNK1</h4>
		</div>

		@php
		$provs = TablaMaecliproveActiva("");
	   	@endphp

		<div class="modal-body">
		 	<div class="row">
			    <div class="col-xs-12">
					<div class="form-group">

						<div class="form-group">
							<label>Proveedor</label>
							<select name="codprove" class="form-control" >
								<option value="tpmaestra">
									TPMAESTRA
								</option>
								@foreach($provs as $prov)
									<option value="{{$prov->codprove}}">
										{{$prov->codprove}} - {{$prov->descripcion}}
									</option>
								@endforeach
							</select>
						</div>

				    </div>
			    </div>
		    </div>
		</div>

		<div class="modal-footer" style="margin-right: 20px;">
			<button type="button" class="btn-normal" data-dismiss="modal">Regresar</button>
			<button type="submit" class="btn-confirmar btnAccion">Confirmar</button>
		</div>
	</div>
</div>
{{Form::Close()}}
</div>

<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modal-descargarInv">
{!! Form::open(array('action'=>array('DescargaController@inventario','method'=>'POST','autocomplete'=>'off'))) !!}
{{ Form::token() }}
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header colorTitulo" >
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">X</span>
			</button>
			<h4 class="modal-title">DESCARGAR INVENTARIO</h4>
		</div>

		<div class="modal-body">
		 	<div class="row">
			    <div class="col-xs-12">
					<div class="form-group">

						<div class="form-group">
							<label>Cliente</label>
							@if (Auth::user()->tipo=="C")
								<select name="codcli" class="form-control" >
									<option value="{{Auth::user()->codcli}}">
										{{Auth::user()->codcli}}
									</option>
								</select>
							@else
								@php
								$codgrupo = Auth::user()->codcli;
								$gruporen = DB::table('gruporen')
								->where('status','=', 'ACTIVO')
			                    ->where('id','=',$codgrupo)
			                    ->get();
							   	@endphp
								<select name="codcli" class="form-control" >
									<option value="tcmaestra{{$codgrupo}}">
										TCMAESTRA{{$codgrupo}}
									</option>
									@if ($gruporen)
										@foreach($gruporen as $gr)
											@php
											$tabla = "inventario_".$gr->codcli;
					                        if (!VerificaTabla($tabla)) 
					                            continue;
					                        @endphp
											<option value="{{$gr->codcli}}">
												{{$gr->codcli}} - {{$gr->nomcli}} 
											</option>
										@endforeach
									@endif
								</select>
							@endif
						</div>

				    </div>
			    </div>
		    </div>
		</div>

		<div class="modal-footer" style="margin-right: 20px;">
			<button type="button" class="btn-normal" data-dismiss="modal">Regresar</button>
			<button type="submit" class="btn-confirmar btnAccion">Confirmar</button>
		</div>
	</div>
</div>
{{Form::Close()}}
</div>


@push ('scripts')
<script>
$('#subtitulo').text('{{$subtitulo}}');
</script>
@endpush

@endsection