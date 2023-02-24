@extends('layouts.menu')
@section('contenido')
 
<div class="row">
    <div class="col-md-12">
		<div class="modal-content" style="border-radius: 20px;" >
			<div class="row" style="">
				<div style="padding-right: 30px; 
					padding-top: 10px;" >
					<button type="button" 
						class="close"
						data-dismiss="modal" 
						aria-hidden="true">
						&times;
					</button>
				</div>
				<center><h4 class="box-title">{{ $invent->desprod }}</h4></center>
			</div>
			<div class="modal-body" >
				<!-- AREA CHART -->
				<div class="box box-primary">
					<center>
					<div class="box-body chart-responsive">
						<div class="chart" id="line-chart" 
							style="background-color: #f9f9f9">
						</div>
					</div>
					</center>
				</div>
			</div>
			<div class="modal-footer" style="margin: 0px; padding: 0px;">
				<center><h4 class="box-title">SEMANAS</h4></center>
			</div>
		</div>
	</div>
</div>
<br>
<div class="col-md-12">
    <button type="button" class="btn-normal" onclick="history.back(-1)">Regresar</button>
</div>
@push ('scripts')
<script>
$('#subtitulo').text('{{$subtitulo}}');
   Morris.Line({
      element: 'line-chart',
      data: [ <?php echo $chart_data; ?>],
      lineColors: ['#819C79'],
      xkey: ['semana'],
      ykeys: ['vmd'],
      labels: ['vmd'],
      parseTime : false,
      hideHover:'auto',
      resize: true
    }); 
</script>
@endpush
@endsection





