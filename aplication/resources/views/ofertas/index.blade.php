@extends('layouts.menu')
@section('contenido')

@php
  $codcli = sCodigoClienteActivo();
  $restandias = iValidarLicencia($codcli); 
  $moneda = Session::get('moneda', 'BSS');
  $status = "ACTIVO";
  if ($restandias <= 0) 
      $status =  "INACTIVO";
@endphp 

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
                    {{$cliente->codcli}}
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
                      {{$cliente->rif}}   
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
                    {{$cliente->telefono}} - {{$cliente->contacto}} 
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
                    {{$cliente->KeyIsacom}}
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
                    @if ($restandias <= 0) color: red; @else color: black; @endif
                    font-size: 14px;">
                    {{$restandias}}
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
                    @if ($restandias <= 0) color: red; @else color: black; @endif
                    font-size: 14px;">
                    {{$status}}
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
              <h3>{{number_format($contCatalogo,0, '.', ',')}}</h3>
              <p>Catálogo</p>
            </div>
            <div class="icon">
              <i class="fa fa-cubes"></i>
            </div>
            <a href="#" 
              class="small-box-footer" 
              title="Ultima sincronización del catalogo">
              @if ( date('d-m-Y', strtotime($cfg->actualizado)) == date('d-m-Y') )
              <span>
                 {{ date('d-m-Y H:i', strtotime($cfg->actualizado)) }}
              </span>
              @else
              <span style="color: red;">
                {{ date('d-m-Y H:i', strtotime($cfg->actualizado)) }}
              </span>
              @endif
            </a>
        </div>
      </div>

      <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-green">
          <div class="inner">
            <h3 title="Cantidad de productos de su Inventario">
              {{number_format($contInv,0, '.', ',')}}
            </h3>
            <h4 title="Cantidad de productos con ofertas">{{$contDa}} Ofertas</h4>
          </div>
          <div class="icon">
            <i class="fa fa-table"></i>
          </div>
          <a href="#" 
              class="small-box-footer "
              title="Ultima sincronización del inventario">  
              @if ( date('d-m-Y', strtotime($fechaInv)) == date('d-m-Y') )
              <span>
                 {{ date('d-m-Y H:i', strtotime($fechaInv)) }}
              </span>
              @else
              <span style="color: red;">
                {{ date('d-m-Y H:i', strtotime($fechaInv)) }}
              </span>
              @endif
          </a>
        </div>
      </div>
  
      <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-yellow">
          <div class="inner">
            <h3>{{number_format($contProv,0, '.', ',')}}</h3>
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
            <h3>{{number_format($contOfertas,0, '.', ',')}}</h3>
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

@push ('scripts')
<script>
$('#subtitulo').text('{{$subtitulo}}');
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
@endpush
@endsection