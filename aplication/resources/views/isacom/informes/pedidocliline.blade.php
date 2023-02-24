@extends ('layouts.menu')
@section ('contenido')

<div class="row">
    <div class="col-md-12">
        <div class="tab-content">
            <div class="tab-pane active" id="tab_1">
                <div class="box box-primary" >
                    <div class="box-header">
                        <CENTER><h3 class="box-title">MONTO ULTIMOS 30 DIAS</h3></CENTER>
                    </div>
                    <div class="box-body chart-responsive">
                        <div class="chart" id="line-chart" style="height: 250px; width: 100%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
  xkey: 'periodo',
  ykeys: ['pedidos'],
  labels: ['PEDIDO'],
  xLabels: 'day',
  xLabelAngle: 45,
  xLabelFormat: function (d) {
  return ("0" + (d.getDate())).slice(-2) + '-' + ("0" + (d.getMonth() + 1)).slice(-2) + '-' + d.getFullYear(); 
  
  },
  resize: true
});
});


</script>
@endpush
@endsection
