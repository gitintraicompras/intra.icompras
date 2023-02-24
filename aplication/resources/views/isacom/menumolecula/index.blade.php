@extends('layouts.menu')
@section('contenido')
  
<section class="content" style="height: 500px;" >
	 <!-- Info boxes -->
	<div class="row" >

	    <div class="col-md-6 col-sm-12 col-xs-12">
	      <div class="info-box" style="background-color: #f7f7f7;">
	      	<a href="{{url('/molecula')}}">
	        	<span class="info-box-icon colorTitulo" >
	        		<i class="fa fa-share-alt"></i>
	        	</span>
	    	</a>
	        <div class="info-box-content">
	          <span class="info-box-text">REGISTRO</span>
	          <span class="info-box-number">
	          	Mantenimiento tabla de moleculas
	          	<br>
	          		<small>COMPRAS</small>
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