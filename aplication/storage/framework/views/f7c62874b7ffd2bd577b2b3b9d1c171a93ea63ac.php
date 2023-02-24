
<?php $__env->startSection('contenido'); ?>

<?php
  $codcli = sCodigoClienteActivo();
  $restandias = iValidarLicencia($codcli); 
  $moneda = Session::get('moneda', 'BSS');
  $status = "ACTIVO";
  if ($restandias <= 0) 
      $status =  "INACTIVO";
?> 

<div class="col-md-12">

  <div class="row">
    
    <div class="row">
      <div class="col-md-6">
        <div class="box box-solid box-primary" style="height: 290px;">
          <div class="box-header colorTitulo" style="height: 40px;"> 
            <i class="fa fa-user" >
              <span style="font-size: 20px; margin-top: 0px;">&nbsp;Datos del cliente</span>
            </i>
          </div>
          <div class="box-body">
            <div class="table-responsive" style="padding: 1px;">
              <table class="table table-striped table-bordered" >
                <tr>
                  <td>
                    <span style="color: #000000; font-size: 14px;">
                    Código del cliente
                    </span>
                  </td>
                  <td align='right'>
                    <span style="color: #000000; font-size: 14px;">
                    <?php echo e($cliente->codcli); ?>

                    </span>
                  </td>
                </tr> 
                <tr>
                  <td>
                    <span style="color: #000000; font-size: 14px;">
                      Rif:
                    </span>
                  </td>
                  <td align='right'>
                    <span style="color: #000000; font-size: 14px;">
                      <?php echo e($cliente->rif); ?>   
                    </span>
                  </td>
                </tr>
                <tr> 
                  <td>
                    <span style="color: #000000; font-size: 14px;">
                    Contacto:
                    </span>
                  </td>
                  <td align='right'>
                    <span style="color: #000000; font-size: 14px;">
                    <?php echo e($cliente->telefono); ?> - <?php echo e($cliente->contacto); ?> 
                    </span>
                  </td>
                </tr>
                <tr> 
                  <td>
                    <span style="color: #000000; font-size: 14px;">
                    Keys:
                    </span>
                  </td>
                  <td align='right'>
                    <span style="color: #000000; font-size: 14px;">
                    <?php echo e($cliente->KeyIsacom); ?>

                    </span>
                  </td>
                </tr>
                <tr> 
                  <td>
                    <span style="color: #000000; font-size: 14px;">
                    Dias:
                    </span>
                  </td>
                  <td align='right'>
                    <span style="
                    <?php if($restandias <= 0): ?> color: red; <?php else: ?> color: black; <?php endif; ?>
                    font-size: 14px;">
                    <?php echo e($restandias); ?>

                    </span>
                  </td>
                </tr>
                <tr> 
                  <td>
                    <span style="color: #000000; font-size: 14px;">
                    Estado:
                    </span>
                  </td>
                  <td align='right'>
                    <span style="
                    <?php if($restandias <= 0): ?> color: red; <?php else: ?> color: black; <?php endif; ?>
                    font-size: 14px;">
                    <?php echo e($status); ?>

                    </span>
                  </td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6">
          <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
                  <div class="box box-primary" >
                      <div class="box-header">
                        <center><h3 class="box-title">MAYOR VARIEDAD (PROVEEDORES)</h3></center>
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
   
    <div class="row">

      <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?php echo e(number_format($contCatalogo,0, '.', ',')); ?></h3>
              <p>Catálogo</p>
            </div>
            <div class="icon">
              <i class="fa fa-cubes"></i>
            </div>
            <a href="#" 
              class="small-box-footer" 
              title="Ultima sincronización del catalogo">
              <?php if( date('d-m-Y', strtotime($cfg->actualizado)) == date('d-m-Y') ): ?>
              <span>
                 <?php echo e(date('d-m-Y H:i:s', strtotime($cfg->actualizado))); ?>

              </span>
              <?php else: ?>
              <span style="color: red;">
                <?php echo e(date('d-m-Y H:i:s', strtotime($cfg->actualizado))); ?>

              </span>
              <?php endif; ?>
            </a>
        </div>
      </div>

      <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-green">
          <div class="inner">
            <h3 title="Cantidad de productos de su Inventario">
              <?php echo e(number_format($contInv,0, '.', ',')); ?>

            </h3>
            <h4 title="Cantidad de productos con ofertas"><?php echo e($contDa); ?> Ofertas</h4>
          </div>
          <div class="icon">
            <i class="fa fa-table"></i>
          </div>
          <a href="#" 
              class="small-box-footer "
              title="Ultima sincronización del inventario">  
              <?php if( date('d-m-Y', strtotime($fechaInv)) == date('d-m-Y') ): ?>
              <span>
                 <?php echo e(date('d-m-Y H:i:s', strtotime($fechaInv))); ?>

              </span>
              <?php else: ?>
              <span style="color: red;">
                <?php echo e(date('d-m-Y H:i:s', strtotime($fechaInv))); ?>

              </span>
              <?php endif; ?>
          </a>
        </div>
      </div>
  
      <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-yellow">
          <div class="inner">
            <h3><?php echo e(number_format($contProv,0, '.', ',')); ?></h3>
            <p>Proveedores</p>
          </div>
          <div class="icon">
            <i class="fa fa-user"></i>
          </div>
          <a href="#" 
              class="small-box-footer ">
              <span>&nbsp;&nbsp;</span>
          </a>
        </div>
      </div>

      <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-red">
          <div class="inner">
            <h3><?php echo e(number_format($contOfertas,0, '.', ',')); ?></h3>
            <p>Registros</p>
          </div>
          <div class="icon">
            <i class="fa fa-bars"></i>
          </div>
          <a href="#" 
              class="small-box-footer ">  
              <span>&nbsp;&nbsp;</span>
          </a>
        </div>
      </div>
    </div>
    
  </div>

</div>

<?php $__env->startPush('scripts'); ?>
<script>
$('#subtitulo').text('<?php echo e($subtitulo); ?>');
setTimeout('document.location.reload()',60000);
var $arrColors = [ <?php echo $chart_color; ?> ];
$(function () {
  config = {
    data: [ <?php echo $chart_data; ?> ],
    xkey: 'y',
    ykeys: ['a'],
    labels: ['Total Renglones: '],
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
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/ofertas/index.blade.php ENDPATH**/ ?>