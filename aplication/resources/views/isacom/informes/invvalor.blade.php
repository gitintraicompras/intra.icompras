@extends ('layouts.menu')
@section ('contenido')

<div class="row">
    <center>
    <div  class="box-body chart-responsive" 
      style="width: 95%; margin: 0px; padding: 0px;">
      <div class="chart" id="line-chart" style="background-color: #f9f9f9"></div>
    </div>
    </center>
    <center><h4 class="box-title">MESES</h4></center>
</div>

<!-- BOTON REGRESAR -->
<div class="form-group" style="margin-top: 20px; margin-left: 15px;">
    <button type="button" class="btn-normal" onclick="history.back(-1)">Regresar</button>
</div>

@push ('scripts')
<script>
$('#subtitulo').text('{{$subtitulo}}');
$(function () {
  Morris.Line({
    element: 'line-chart',
        data: [ <?php echo $chart_data; ?>],
        lineColors: ['#819C79'],
        xkey: ['mes'],
        ykeys: ['valor'],
        labels: ['valor'],
        parseTime : false,
        hideHover:'auto',
        resize: true
  });
});
</script>
@endpush
@endsection
