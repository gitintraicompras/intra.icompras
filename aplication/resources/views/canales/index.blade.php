@extends('layouts.menu')
@section('contenido')
<head>
	<link href="{{asset('js/electro-master/slick.css')}}" rel="stylesheet" type="text/css" />
	<link href="{{asset('js/electro-master/slick-theme.css')}}" rel="stylesheet" type="text/css" />
	<link href="{{asset('js/electro-master/nouislider.min.css')}}" rel="stylesheet" type="text/css" />
	<link href="{{asset('js/electro-master/style.css')}}" rel="stylesheet" type="text/css" />
</head> 
<div class="col-md-12">
</div>
@push ('scripts')
<script type="text/javascript" src="{{asset('js/electro-master/slick.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/electro-master/nouislider.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/electro-master/jquery.zoom.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/electro-master/main.js')}}"></script>
<script>
$('#subtitulo').text('{{$subtitulo}}');
</script>
@endpush
@endsection