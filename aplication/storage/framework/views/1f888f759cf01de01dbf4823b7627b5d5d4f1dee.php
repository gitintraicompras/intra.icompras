
<?php $__env->startSection('contenido'); ?>

<div class="row">
    <div class="col-md-12">
        <div class="tab-content">
            <div class="tab-pane active" id="tab_1">
                <div class="box box-primary" >
                    <div class="box-body chart-responsive">
                        <div class="chart" id="bar-chart" style="height: 250px; width: 100%;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="margin: 0px; padding: 0px;">
                  <center><h4 class="box-title">PERIODO</h4></center>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- BOTON REGRESAR -->
<div class="form-group" style="margin-top: 20px; margin-left: 15px;">
    <button type="button" class="btn-normal" onclick="history.back(-1)">Regresar</button>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
$('#subtitulo').text('<?php echo e($subtitulo); ?>');
$(function () {
  config = {
    data: [ <?php echo $chart_data; ?> ],
    xkey: 'y',
    ykeys: ['a'],
    labels: ['Monto '],
    fillOpacity: 0.6,
    behaveLikeLine: true,
    resize: true,

    pointFillColors:['#ffffff'],
    pointStrokeColors: ['black'],
    lineColors:['red'],
    hideHover: 'auto'
  };
  config.element = 'bar-chart';
  Morris.Bar(config);
  config.element = 'stacked';
  config.stacked = true;
  Morris.Bar(config);
});


</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/isacom/informes/verahorro.blade.php ENDPATH**/ ?>