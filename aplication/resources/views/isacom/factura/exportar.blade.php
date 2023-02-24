@extends ('layouts.menu')
@section ('contenido')

@php
  $moneda = Session::get('moneda', 'BSS');
@endphp

 
<div id="page-wrapper">
 	<div class="form-group">
        <div style="margin-top: 4px;" class="input-group input-group-sm">
            <span class="input-group-addon">Factura:</span>
            <input readonly type="text" class="form-control" value="{{$tabla->factnum}}" style="color: #000000; background: #F7F7F7;">

            <span class="input-group-addon" style="border:0px; "></span>
		    <span class="input-group-addon hidden-xs">Fecha:</span>
            <input readonly type="text" class="form-control" value="{{date('d-m-Y H:i', strtotime($tabla->fecha))}}" style="color: #000000; background: #F7F7F7;">

            <span class="input-group-addon hidden-xs" style="border:0px; "></span>
            <span class="input-group-addon hidden-xs">Monto:</span>
            <input readonly type="text" class="form-control hidden-xs" value="{{number_format($tabla->monto, 2, '.', ',')}}" style="color: #000000; text-align: right; background: #F7F7F7;">

            <span class="input-group-addon" style="border:0px; "></span>
            <span class="input-group-addon hidden-xs">Iva:</span>
            <input readonly type="text" class="form-control" value="{{number_format($tabla->iva, 2, '.', ',')}}" style="color: #000000; text-align: right; background: #F7F7F7;">

        </div>

        <div style="margin-top: 4px;" class="input-group input-group-sm">

            <span class="input-group-addon hidden-xs">Gravable:</span>
            <input readonly type="text" class="hidden-xs form-control" value="{{number_format($tabla->gravable, 2, '.', ',')}}" style="color: #000000; text-align: right; background: #F7F7F7;">

            <span class="input-group-addon hidden-xs" style="border:0px; "></span>
            <span class="input-group-addon">Descuento:</span>
            <input readonly type="text" class="form-control" value="{{number_format($tabla->descuento, 2, '.', ',')}}" style="color: #000000; text-align: right; background: #F7F7F7;">

            <span class="input-group-addon" style="border:0px; "></span>
            <span class="input-group-addon">Total:</span>
            <input readonly type="text" class="form-control" value="{{number_format($tabla->total, 2, '.', ',')}}" style="color: #000000; text-align: right; background: #F7F7F7;">
        </div>

        <div style="margin-top: 4px;">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 input-group input-group-sm">
                <span class="input-group-addon">Observación:</span>
                <input id="idobs" readonly type="text" class="form-control" value="{{$tabla->observacion}}" style="color: #000000; background: #F7F7F7;">
            </div>
        </div>
    </div>
    <div  class="table-responsive">
        <table class="table table-striped table-bordered table-condensed table-hover">
            <thead class="colorTitulo">
                <th>#</th>
                <th>DESCRIPCION</th>
                <th>REFERENCIA</th>
                <th title="Código alterno para cruzar con la ODC/FACTURA en la exportación"
                    style="width: 160px;">
                    ALTERNO
                </th>
                <th>CODIGO</th>
                <th title="CANTIDAD">CANT</th>
                <th>PRECIO</th>
                <th>SUBTOTAL</th>
                
            </thead>
          
            @foreach ($tabla2 as $t)
            <tr>
                @php 
                    $factor = RetornaFactorCambiario($codprove, $moneda);
                    $respAlterno = VerificarCodalterno($t->referencia);
                @endphp
                <td>{{$loop->iteration}}</td>
                <td>{{$t->desprod}}</td>
                <td>{{$t->referencia}}</td>
                <td style="width: 160px;">
                    <!-- MODIFICAR CODIGO ALTERMO -->
                    <div class="col-xs-12 input-group" 
                        id="idAgregar-{{$t->renglon}}" >
                        <input style="text-align: center; 
                            color: #000000; 
                            width: 120px;" 
                            value="{{$respAlterno['codalterno']}}" 
                            class="form-control"
                            id="idCodalterno-{{$t->renglon}}" >
                        <span class="input-group-btn BtnModificar">
                            
                            <button 
                                style="background-color:{{$respAlterno['backcolor']}}; 
                                    color: {{$respAlterno['forecolor']}};"
                                type="button" 
                                class="btn btn-pedido" 
                                title="{{$respAlterno['title']}}"
                                id="idModificar-{{$t->renglon}}-{{$respAlterno['backcolor']}}" >
                                <span class="fa fa-check" 
                                id="idModificar-{{$t->renglon}}-{{$respAlterno['backcolor']}}"aria-hidden="true">
                                </span>
                            </button>


                            <a href="" data-target="#modal-buscar-{{$t->renglon}}" data-toggle="modal">
                            <button 
                                style="background-color:{{$respAlterno['backcolor']}}; 
                                    color: {{$respAlterno['forecolor']}};"
                                type="button" 
                                class="btn btn-pedido" 
                                title="Buscar código alternativo de forma manual">
                                <span class="fa fa-search-plus"aria-hidden="true">
                                </span>
                            </button>
                            </a>

                        </span>
                    </div>
                </td>

                <td>{{$t->codprod}}</td>
                <td align="right">{{number_format($t->cantidad, 0, '.', ',')}}</td>
                <td align="right">{{number_format($t->precio/$factor, 2, '.', ',')}}</td>
                <td align="right">{{number_format($t->subtotal/$factor, 2, '.', ',')}}</td>
            </tr>
            @endforeach
          
        </table>
    </div>
</div>

<!-- DESCARGAR FACTURA TXT-->
<a href="{{URL::action('FacturaController@descargartxt',$factnum.'_'.$codprove)}}">
    <button type="submit" class="btn-confirmar" title="Confirmar exportación de factura">Confirmar</button>
</a>

<!-- REGRESAR -->
<button type="button" class="btn-normal" onclick="history.back(-1)">Regresar</button>

@push ('scripts')
<script>
$('#subtitulo').text('{{$subtitulo}}');
</script>
@endpush
@endsection
