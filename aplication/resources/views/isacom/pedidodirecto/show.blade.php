@extends ('layouts.menu')
@section ('contenido')
@php
  $moneda = Session::get('moneda', 'BSS');
  $factor = RetornaFactorCambiario('', $moneda);
  if ($moneda == "USD") {
    if ($tabla->factor != 1)
        $factor = $tabla->factor;
  }
@endphp

<!-- ENCABEZADO -->
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="form-group">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 input-group input-group-sm">
                
                <span class="input-group-addon">ID:</span>
                <input readonly type="text" 
                    class="form-control"
                    value="{{$tabla->id}}" 
                    style="color: #000000; padding: 2px;">

                <span class="input-group-addon hidden-xs" style="border:0px; "></span>
                <span class="input-group-addon hidden-xs">Estado:</span>
                <input readonly type="text" class="form-control hidden-xs" value="{{$tabla->estado}}" style="color: #000000">

                <span class="input-group-addon hidden-xs" style="border:0px; "></span>
                <span class="input-group-addon hidden-xs">Fecha:</span>
                <input readonly type="text" class="form-control hidden-xs" value="{{date('d-m-Y H:i', strtotime($tabla->fecha))}}" style="color: #000000">

                <span class="input-group-addon hidden-xs" style="border:0px; "></span>
                <span class="input-group-addon hidden-xs">Enviado:</span>
                <input readonly type="text" class="form-control hidden-xs" value="{{date('d-m-Y H:i', strtotime($tabla->fecenviado))}}" style="color:#000000" >

            </div>
        </div> 
        <div class="row hidden-sm hidden-xs" style="margin-top: 4px;">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 input-group input-group-sm">
                
                <span class="input-group-addon">Descuento:</span>
                <input readonly 
                    type="text" 
                    class="form-control" 
                    value="{{number_format($tabla->descuento, 2, '.', ',')}}" 
                    style="color: #000000; text-align: right;" 
                    id="idDescuento">

                <span class="input-group-addon" style="border:0px; "></span>
                <span class="input-group-addon">Subtotal:</span>
                <input readonly 
                    type="text" 
                    class="form-control" 
                    value="{{number_format($tabla->subtotal, 2, '.', ',')}}" 
                    style="color: #000000; text-align: right;" 
                    id="idSubtotal">

                <span class="input-group-addon" style="border:0px; "></span>
                <span class="input-group-addon">Impuesto:</span>
                <input readonly 
                    type="text" 
                    class="form-control" 
                    value="{{number_format($tabla->impuesto, 2, '.', ',')}}" 
                    style="color: #000000; text-align: right;" 
                    id="idImpuesto">

                <span class="input-group-addon" style="border:0px; "></span>
                <span class="input-group-addon">Total:</span>
                <input readonly 
                    type="text" 
                    class="form-control" 
                    value="{{number_format($tabla->total, 2, '.', ',')}}" 
                    style="color:#000000; text-align: right; font-size: 20px;" 
                    id="idTotal">                 
            </div>
        </div>
    </div>
</div>

<div class="col-md-12">
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <li class="active">
            <a href="#tab_1" data-toggle="tab">DETALLE</a>
          </li>
          <li >
            <a href="#tab_2" data-toggle="tab">RESUMEN</a>
          </li>
          <li class="pull-right"><a href="{{url('/')}}" class="text-muted">
            <i class="fa fa-times"></i></a>
          </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane active" class="tab-pane" id="tab_1">
                <div class="row">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend" id="button-addon3">
                            <a href="{{url('pedidodirecto/descargar/pedidopdf/'.$id.'-'.$tabla->marca)}}">
                                <button style="width: 153px; height: 32px;"  class="btn-normal" type="button" data-toggle="tooltip" title="Descargar pedido en pdf">
                                    Descargar
                                </button>
                            </a>
                        </div>
                    </div>
                    <!-- TABLA -->
                    <br>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <table id="idTabla" class="table table-striped table-bordered table-condensed table-hover">
                                    <thead class="colorTitulo">
                                        <th>#</th>
                                        <th>PRODUCTO</th>
                                        <th>BARRA</th>
                                        <th>MARCA</th> 
                                        <th>CODIGO</th> 
                                        <th title="CANTIDAD">CANT</th>
                                        <th>COSTO</th>
                                        <th>IVA</th>
                                        <th>SUBTOTAL</th>
                                        <th style="display:none;">ITEM</th>
                                    </thead>
                                    @foreach ($tabla2 as $t)
                                        <tr>

                                            @if ($t->estado == "ENVIADO" || $t->estado == "RECIBIDO")
                                                <td style="background-color: #b7b7b7; 
                                                    color: #000000;" 
                                                    title = "PRODUCTO ENVIADO">
                                                    <a href="" 
                                                        style="color: #000000;" 
                                                        data-target="#modal-consulta-{{$t->item}}" data-toggle="modal">
                                                        {{$loop->iteration}}
                                                    </a>
                                                </td>
                                            @else
                                                <td>
                                                    {{$loop->iteration}}
                                                </td>
                                            @endif
                                            <td title="DESCRIPCION DEL PRODUCTO">
                                                {{$t->desprod}}
                                            </td>

                                            <td id="idBarra-{{$t->item}}"
                                                title="BARRA DEL PRODUCTO">
                                                {{$t->barra}}
                                            </td>

                                            <td title="MARCA DEL PRODUCTO">
                                                {{LeerProdcaract($t->barra, 'marca', 'POR DEFINIR')}}
                                            </td>
                                           
                                            <td title="CODIGO DEL PRODUCTO">
                                                {{$t->codprod}}
                                            </td>

                                            <td align="right"
                                                title="CANTIDAD DEL PEDIDO">
                                                {{number_format($t->cantidad, 2, '.', ',')}}
                                            </td>
                                            
                                            <td align="right"
                                                title="PRECIO DEL PRODUCTO">
                                                {{number_format($t->precio/$factor, 2, '.', ',')}}
                                            </td>
                                            
                                            <td align="right"
                                                title="IVA DEL PRODUCTO">
                                                {{number_format($t->iva, 2, '.', ',')}}
                                            </td>

                                            <td align="right"
                                                title="SUBTOTAL DEL PRODUCTO"> 
                                                {{number_format($t->subtotal/$factor, 2, '.', ',')}}
                                            </td>

                                            <td style="display:none;">{{$t->item}}</td>
                                        </tr>
                                    @endforeach
                                </table>
                                @if ($moneda == "USD")
                                <h4>
                                 *** {{$cfg->simboloOM}} {{number_format($factor, 2, '.', ',')}} ***
                                </h4>  
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" class="tab-pane" id="tab_2">
                <div class="row">
                    <!-- TABLA -->
                    <br>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-condensed table-hover">
                                    <thead class="colorTitulo">
                                        <th>#</th>
                                        <th>NOMBRE</th>
                                        <th>ESTADO</th>
                                        <th>APROBACION</th>
                                        <th>ENVIADO</th>
                                    </thead>
                                    <tr>
                                        <td>1</td>
                                        <td>{{$tabla->marca}}</td>
                                        <td>{{$tabla->estado}}</td>
                                        <td>
                                            @if ($tabla->estado == "ENVIADO")
                                                OK->CORREO
                                            @else
                                                NO
                                            @endif
                                        </td>
                                        <td>{{date('d-m-Y H:i', strtotime($tabla->fecenviado))}}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- REGRESAR -->
<div class="form-group" style="margin-left: 15px; margin-top: 20px;">
    <button type="button" class="btn-normal" onclick="history.back(-1)">Regresar</button>
</div>

@push ('scripts')
<script>
$('#subtitulo').text('{{$subtitulo}}');
</script>
@endpush

@endsection