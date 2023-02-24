@extends('layouts.menu')
@section('contenido')
 
<section class="content" >
	 <!-- Info boxes -->
	<div class="row" >

	    <div class="col-md-6 col-sm-12 col-xs-12">
	      <div class="info-box" style="background-color: #f7f7f7;">
	      	<a href="{{url('/invcliente')}}">
	        	<span class="info-box-icon colorTitulo" >
	        		<i class="fa fa-align-justify"></i>
	        	</span>
	    	</a>
	        <div class="info-box-content">
	          <span class="info-box-text">PRODUCTOS</span>
	          <span class="info-box-number">
	          	Inventario de productos
	          	<br>
	          	@if ($contadorInv > 0)
	          		<small>
	          		 Fecha: {{date('d-m-Y H:i', strtotime($fechaInv))}}, Renglones: {{number_format($contadorInv, 0, '.', ',')}} 
	          		</small>
	          	@else
	          		<small></small>
	          	@endif
	          </span>
	        </div>
	      </div>
	    </div>

	    <div class="col-md-6 col-sm-12 col-xs-12">
	      <div class="info-box" style="background-color: #f7f7f7;">
	      	<a href="{{url('/invsugerido')}}">
	        	<span class="info-box-icon colorTitulo">
	        		<i class="fa fa-check-square-o"></i>
	        	</span>
	    	</a>
	        <div class="info-box-content">
	          <span class="info-box-text">SUGERIDOS</span>
	          <span class="info-box-number">
	          	Pedidos Sugeridos   
	          	<br>
	          	@if ($contadorSug > 0)
	          		<small>
	          		Fecha: {{date('d-m-Y H:i', strtotime($fechaSug))}}, Renglones: {{number_format($contadorSug, 0, '.', ',')}} 
	          		</small>
	          	@else
	          		<small></small>
	          	@endif
	          </span>
	        </div>
	      </div>
	    </div>

	    <div class="col-md-6 col-sm-12 col-xs-12">
	      <div class="info-box" style="background-color: #f7f7f7;">
	      	<a href="{{url('/invfallas')}}">
	        	<span class="info-box-icon colorTitulo" >
	        		<i class="fa fa-thumbs-o-down"></i>
	        	</span>
	    	</a>
	        <div class="info-box-content">
	          <span class="info-box-text">FALLAS</span>
	          <span class="info-box-number">
	          	Ver fallas  
	          	<br>
	          	@if ($contadorFalla > 0)
	          		<small>
	          		Fecha: {{date('d-m-Y H:i', strtotime($fechaFalla))}}, Renglones: {{number_format($contadorFalla, 0, '.', ',')}} 
	          		</small>
	          	@else
	          		<small></small>
	          	@endif
	          </span>
	        </div>
	      </div>
	    </div>

	    <div class="col-md-6 col-sm-12 col-xs-12">
	      <div class="info-box" style="background-color: #f7f7f7;">
	      	<a href="{{url('/analisis')}}">
	        	<span class="info-box-icon colorTitulo" >
	        		<i class="fa fa-list-alt"></i>
	        	</span>
	    	</a>
	        <div class="info-box-content">
	          <span class="info-box-text">ANALISIS</span>
	          <span class="info-box-number">
	          	Costos vs. Catálogo de proveedor  
	          	<br>
	      		<small></small>
	          </span>
	        </div>
	      </div>
	    </div>

	    <div class="col-md-6 col-sm-12 col-xs-12">
	      <div class="info-box" style="background-color: #f7f7f7;">
	      	<a href="{{url('/invminmax')}}">
	        	<span class="info-box-icon colorTitulo" >
	        		<i class="fa fa-adjust"></i>
	        	</span>
	    	</a>
	        <div class="info-box-content">
	          <span class="info-box-text">DIAS MINIMOS Y MAXIMOS</span>
	          <span class="info-box-number">
	          	Dias Minimos y Maxmos de productos   
	          	<br>
	      		<small></small>
	          </span>
	        </div>
	      </div>
	    </div>

   	    

	</div>
</section>

@push ('scripts')
<script>
$('#subtitulo').text('{{$subtitulo}}');
</script>
@endpush

@endsection