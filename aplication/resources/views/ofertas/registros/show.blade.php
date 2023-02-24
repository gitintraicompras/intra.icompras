@extends ('layouts.menu')
@section ('contenido')
 
@php
  $moneda = Session::get('moneda', 'BSS');
  $factor = RetornaFactorCambiario('', $moneda);
@endphp

<!-- ENCABEZADO -->
<div class="col-xs-12">
    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 input-group input-group-sm">
                <span class="input-group-addon">ID:</span>
                <input readonly type="text" class="form-control" value="{{$reg->id}}" style="color: #000000">

                <span class="input-group-addon" style="border:0px; "></span>
                <span class="input-group-addon">Estado:</span>
                <input readonly type="text" class="form-control" value="{{$reg->status}}" style="color: #000000">

                <span class="input-group-addon" style="border:0px; "></span>
                <span class="input-group-addon">Fecha:</span>
                <input readonly type="text" class="form-control" value="{{date('d-m-Y H:i', strtotime($reg->fecha))}}" style="color: #000000">

                <span class="input-group-addon" style="border:0px; "></span>
                <span class="input-group-addon">Procesado:</span>
                <input readonly type="text" class="form-control" value="{{date('d-m-Y H:i', strtotime($reg->fecprocesado))}}" style="color:#000000" >
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 input-group input-group-sm">
                <span class="input-group-addon">Desde:</span>
                <input readonly type="text" class="form-control" value="{{date('d-m-Y H:i', strtotime($reg->desde))}}" style="color: #000000">

                <span class="input-group-addon" style="border:0px; "></span>
                <span class="input-group-addon">Hasta:</span>
                <input readonly type="text" class="form-control" value="{{date('d-m-Y H:i', strtotime($reg->hasta))}}" style="color:#000000" >

                <span class="input-group-addon" style="border:0px; "></span>
                <span class="input-group-addon">
                @if ($reg->ppsa == '1')
                    <input disabled="" checked type="checkbox" >
                @else
                    <input disabled type="checkbox" >
                @endif
                <label class="form-check-label">Ppsa</label>
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 input-group input-group-sm">
                <span class="input-group-addon">Usuario:</span>
                <input readonly type="text" 
                    class="form-control" 
                    value="{{$reg->usuario}}"  >                 
           
                <span class="input-group-addon" style="border:0px; "></span>
                <span class="input-group-addon">Observación:</span>
                <input readonly type="text" 
                    class="form-control" 
                    value="{{$reg->observ}}">
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <div class="table-responsive">
            <table id="idTabla" class="table table-striped table-bordered table-condensed table-hover">
                <thead class="colorTitulo">
                    <th>#</th>
                    <th title="DESCRIPCION DEL PRODUCTO">PRODUCTO</th>
                    <th title="COPDIGO DEL PRODUCTO">CODIGO</th>
                    <th title="PRECIO SUGERIDO">PS</th>
                    <th title="DESCUENTO ADICIONAL">DA</th>
                </thead>
                @foreach ($tabla as $t)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$t->desprod}}</td>
                        <td>{{$t->codprod}}</td>
                        <td align="right">
                            {{number_format($t->ps/$factor, 2, '.', ',')}}
                        </td>
                        <td align="right">
                            {{number_format($t->da, 2, '.', ',')}}
                        </td>
                    </tr>
                @endforeach
            </table>
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