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
            <i class="fa fa-male" >
              <span style="font-size: 20px; margin-top: 0px;">&nbsp;Datos del Proveedor</span>
            </i>
          </div>

          <div class="box-body">
            <div class="table-responsive" style="padding: 1px;">
              <table class="table table-striped table-bordered" >
                  <tr>
                    <td>
                      <span style="color: #000000; font-size: 14px;">
                      Código del proveedor
                      </span>
                    </td>
                    <td align='right'>
                      <span style="color: #000000; font-size: 14px;">
                      {{$codcli}}
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

      <div class="col-md-6" >
        <!-- AREA CHART -->
        <div class="box box-primary" >
          <div class="box-header">
            <h3 class="box-title">Pedidos ({{$moneda}})</h3>
          </div>
          <div class="box-body chart-responsive">
            <div class="chart" id="line-chart" style="height: 250px; width: 100%;"></div>
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
              @if ( date('d-m-Y', strtotime($fechaCat)) == date('d-m-Y') )
              <span>
                 {{ date('d-m-Y H:i', strtotime($fechaCat)) }}
              </span>
              @else
              <span style="color: red;">
                {{ date('d-m-Y H:i', strtotime($fechaCat)) }}
              </span>
              @endif
            </a>
        </div>
      </div><!-- ./col -->

      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
          <div class="inner">
            <h3>{{number_format($contPedido,0, '.', ',')}}<sup style="font-size: 20px"></sup></h3>
            <p>Pedidos</p>
          </div>
          <div class="icon">
            <i class="fa fa-shopping-cart"></i>
          </div>
          <p class="small-box-footer">&nbsp</p>
        </div>
      </div><!-- ./col -->
  
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <a href="" data-target="#modal-acerca" data-toggle="modal" title="Acerca del iCompras">
          <div class="small-box bg-yellow">
            <div class="inner">
                <h3>Acerca</h3>
                <p>&nbsp</p>
            </div>
            <div class="icon">
              <i class="fa fa-info"></i>
            </div>
            <p class="small-box-footer">&nbsp</p>
          </div>
        </a>
      </div>

      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
          <div class="small-box bg-red">
            <div class="inner">
              <h3>Cerrar</h3>
              <p>&nbsp</p>
            </div>
            <div class="icon">
              <i class="fa fa-sign-out"></i>
            </div>
            <p class="small-box-footer">&nbsp</p>
          </div>
        </a>
      </div>
    </div>
    
  </div>

</div>

@push ('scripts')
<script>
$('#subtitulo').text('{{$subtitulo}}');
setTimeout('document.location.reload()',60000);

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