@extends ('layouts.menu')
@section ('contenido')

<div class="row">
    <div class="col-md-12">
        <div class="tab-content">
            <div class="tab-pane active" id="tab_1">
                <div class="box box-primary" >
                    <div class="box-header">
                      <center><h3 class="box-title">MEJOR INVENTARIO</h3></center>
                    </div>
                    <div class="box-body chart-responsive">
                        <div class="chart" id="bar-chart" style="height: 250px; width: 100%;">
                        </div>
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
var $arrColors = [ <?php echo $chart_color; ?> ];
$(function () {
  config = {
    data: [ <?php echo $chart_data; ?> ],
    xkey: 'y',
    ykeys: ['a'],
    labels: ['Total Unidades: '],
    fillOpacity: 0.6,
    behaveLikeLine: true,
    resize: true,

    pointFillColors:['#ffffff'],
    pointStrokeColors: ['black'],
    lineColors:['red'],

    barColors: function (row, series, type) {
        return $arrColors[row.x];
    }, 
    hideHover: 'auto'
  };
  config.element = 'bar-chart';
  Morris.Bar(config);
  config.element = 'stacked';
  config.stacked = true;
  Morris.Bar(config);
});
 

</script>
@endpush
@endsection
