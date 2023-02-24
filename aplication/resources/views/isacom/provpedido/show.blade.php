@extends ('layouts.menu')
@section ('contenido')

@php
    $moneda = Session::get('moneda', 'BSS');
    $factor = RetornaFactorCambiario($codprove, $moneda);

    $reg = DB::table('maeclieprove')
    ->where('codcli','=',$tabla->codcli)
    ->where('codprove','=',$codprove)
    ->first();
    $codigo = $reg->codigo;
@endphp

<!-- ENCABEZADO -->  
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="form-group">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 input-group input-group-sm">
                
                <span class="input-group-addon">Pedido:</span>
                <input readonly type="text" class="form-control" value="{{$tabla->id}}" style="color: #000000">

                <span class="input-group-addon hidden-xs" style="border:0px; "></span>
                <span class="input-group-addon hidden-xs">Código:</span>
                <input readonly type="text" class="form-control hidden-xs" value="{{$tabla->codcli}}" style="color: #000000">

                <span class="input-group-addon hidden-xs" style="border:0px; "></span>
                <span class="input-group-addon hidden-xs">Estado:</span>
                <input readonly type="text" class="form-control hidden-xs" value="{{$tabla->estado}}" style="color: #000000">

                <span class="input-group-addon" style="border:0px; "></span>
                <span class="input-group-addon">Fecha:</span>
                <input readonly type="text" class="form-control" value="{{date('d-m-Y H:i', strtotime($tabla->fecha))}}" style="color: #000000">

                <span class="input-group-addon hidden-xs" style="border:0px; "></span>
                <span class="input-group-addon hidden-xs">Enviado:</span>
                <input readonly type="text" class="form-control hidden-xs" value="{{date('d-m-Y H:i', strtotime($tabla->fecenviado))}}" style="color:#000000" >

            </div>
        </div>
        <div class="row hidden-sm hidden-xs" style="margin-top: 4px;">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 input-group input-group-sm">
                
                <span class="input-group-addon">Subrenglon:</span>
                <input readonly type="text" 
                    class="form-control" 
                    value="{{number_format($tabla->subrenglon/$factor, 2, '.', ',')}}" 
                    style="color: #000000; text-align: right;" 
                    id="idSubrenglon">

                <span class="input-group-addon" style="border:0px; "></span>
                <span class="input-group-addon">Descuento:</span>
                <input readonly type="text" 
                    class="form-control" 
                    value="{{number_format($tabla->descuento/$factor, 2, '.', ',')}}" 
                    style="color: #000000; text-align: right;" 
                    id="idDescuento">

                <span class="input-group-addon" style="border:0px; "></span>
                <span class="input-group-addon">Subtotal:</span>
                <input readonly type="text" 
                    class="form-control" 
                    value="{{number_format($tabla->subtotal/$factor, 2, '.', ',')}}" 
                    style="color: #000000; text-align: right;" 
                    id="idSubtotal">

                <span class="input-group-addon" style="border:0px; "></span>
                <span class="input-group-addon">Impuesto:</span>
                <input readonly type="text" 
                    class="form-control" 
                    value="{{number_format($tabla->impuesto/$factor, 2, '.', ',')}}" 
                    style="color: #000000; text-align: right;" 
                    id="idImpuesto">

                <span class="input-group-addon" style="border:0px; "></span>
                <span class="input-group-addon">Total:</span>
                <input readonly type="text" 
                    class="form-control" 
                    value="{{number_format($tabla->total/$factor, 2, '.', ',')}}" 
                    style="color:#000000; text-align: right; font-size: 20px;" 
                    id="idTotal">                 
            </div>
        </div>
    </div>
</div>

<!-- TABLA -->
<br>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="table-responsive">
            <table id="idTabla" class="table table-striped table-bordered table-condensed table-hover">
                <thead style="background-color: #b7b7b7;">
                    <th>#</th>
                    <th>PRODUCTO</th>
                    <th class="hidden-xs">CODIGO</th>
                    <th title="CANTIDAD">CANT</th>
                    <th>PRECIO</th>
                    <th class="hidden-xs">IVA</th>
                    <th class="hidden-xs">DA</th>
                    <th class="hidden-xs">DI</th>
                    <th class="hidden-xs">DC</th>
                    <th class="hidden-xs">PP</th>
                    <th class="hidden-xs">NETO</th>
                    <th>SUBTOTAL</th>
                </thead>
                @foreach ($tabla2 as $t)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$t->desprod}}</td>
                    <td class="hidden-xs">{{$t->codprod}}</td>
                    <td align="right">{{number_format($t->cantidad, 0, '.', ',')}}</td>
                    <td align="right">{{number_format($t->precio/$factor, 2, '.', ',')}}</td>
                    <td class="hidden-xs" align="right">{{number_format($t->iva, 2, '.', ',')}}</td>
                    <td class="hidden-xs" align="right">{{number_format($t->da, 2, '.', ',')}}</td>
                    <td class="hidden-xs" align="right">{{number_format($t->di, 2, '.', ',')}}</td>
                    <td class="hidden-xs" align="right">{{number_format($t->dc, 2, '.', ',')}}</td>
                    <td class="hidden-xs" align="right">{{number_format($t->pp, 2, '.', ',')}}</td>
                    <td class="hidden-xs" align="right">{{number_format($t->neto/$factor, 2, '.', ',')}}</td>
                    <td align="right">{{number_format($t->subtotal/$factor, 2, '.', ',')}}</td>
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