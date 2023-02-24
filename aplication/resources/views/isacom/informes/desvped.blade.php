@extends ('layouts.menu')
@section ('contenido')
@php
  $moneda = Session::get('moneda', 'BSS');
  $rutalogoprov = 'http://isaweb.isbsistemas.com/public/storage/prov/';
@endphp

<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
    <div class="form-group" style="margin-top: 10px;">
        @include('isacom.informes.desvpedsearch')
    </div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
            <table id="idTabla" 
                class="table table-striped table-bordered table-condensed table-hover">
                <thead style="background-color: #b7b7b7;">
                    <th style="vertical-align:middle;">#</th>
                    <th style="width: 100px; vertical-align:middle;">
                        &nbsp;&nbsp;&nbsp;&nbsp;IMAGEN&nbsp;&nbsp;&nbsp;&nbsp;
                    </th>
                    <th title="Descripción del producto">
                        PRODUCTO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </th>
                    <th title="Código de referencia del producto">
                        REFERENCIAS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </th>
                    <th title="Código del proveedor">
                        PROVEEDOR&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </th>
                    <th title="Cantidad solicitada">CANT</th>
                    <th title="Precio de venta al público">PRECIO</th>
                    <th title="Impuesto al valor agregado">IVA</th>
                    <th title="Descuento adicional">DA</th>
                    <th title="Descuento pre-empaque">DP</th>
                    <th title="Descuento internet">DI</th>
                    <th title="Descuento comercial">DC</th>
                    <th title="Descuento pronto pago">PP</th>
                    <th title="Neto del producto">NETO</th>
                    <th title="Subtotal del producto">SUBTOTAL</th>
                </thead>
                @foreach ($tabla as $t)
                    @php
                    $confprov = LeerProve($t->codprove); 
                    if (is_null($confprov))
                        continue;
                    $factor = RetornaFactorCambiario($t->codprove, $moneda);
                    @endphp
                    <tr>

                        <td style="background-color: #b7b7b7; 
                            color: #000000;" 
                            title = "PRODUCTO ENVIADO">
                            <a href="" 
                                style="color: #000000;" 
                                data-target="#modal-consulta-{{$t->item}}" data-toggle="modal">
                                {{$loop->iteration}}
                            </a>
                        </td>
     
                        <td style="width: 100px;">
                            <div align="center">

                                <a href="{{URL::action('PedidoController@verprod',$t->barra)}}">
                        
                                    <img src="http://isaweb.isbsistemas.com/public/storage/prod/{{NombreImagen($t->barra)}}" 
                                    width="100%" 
                                    height="100%" 
                                    class="img-responsive" 
                                    alt="icompras360" 
                                    style="border: 2px solid #D2D6DE;"
                                    oncontextmenu="return false">
                        
                                </a>

                            </div>
                        </td>

                        <td title="DESCRIPCION DEL PRODUCTO">
                            <B>{{$t->desprod}}</B>
                            <div style="margin-top: 5px;
                                font-size: 14px;
                                padding: 1px; "                             
                                title="ID DEL PEDIDO">
                                ID: {{$t->id}} <br> 
                                <span title="FECHA DE ENVIO DEL PEDIDO">
                                    E: {{date('d-m-Y', strtotime($t->fecenviado))}}
                                </span>
                            </div>
                            @if ($t->dcredito > 0)
                                <div style="margin-top: 5px;
                                    border-radius: 5px; 
                                    font-size: 14px;
                                    text-align: center;
                                    padding: 1px; 
                                    color: white;
                                    width: 100px;
                                    background-color: black;"
                                    title="DIAS DE CREDITO">
                                    DIAS: {{$t->dcredito}} 
                                </div>
                            @endif
                        </td>

                        <td>
                            <span title="CODIGO DE BARRA">
                                <i class="fa fa-barcode">
                                    {{$t->barra}}
                                </i><br>
                            </span>
                            <span title="MARCA DEL PRODUCTO">
                                <i class="fa fa-shield">
                                    {{LeerProdcaract($t->barra, 'marca', 'POR DEFINIR')}}    
                                </i>
                            </span><br> 
                            <span>
                                RNK: {{$t->ranking}}
                            </span>
                        </td>
 
                        <td style="background-color: {{$confprov->backcolor}}; 
                            color: {{$confprov->forecolor}};"
                            title="CODIGO DEL PROVEEDOR">
                            <img style="width: 20px; height: 20px;" 
                            src="{{$rutalogoprov.$confprov->rutalogo1}}" 
                            alt="icompras360">
                                {{$confprov->descripcion}}<br>
                            <span title="CODIGO DEL PRODUCTO">
                                <i class="fa fa-cube">
                                    {{$t->codprod}}
                                </i>
                            </span>
                        </td>

                        <td style="background-color: {{$confprov->backcolor}}; color: {{$confprov->forecolor}};" 
                            align="right"
                            title="CANTIDAD DEL PEDIDO">
                            {{number_format($t->cantidad, 0, '.', ',')}}
                        </td>
                        
                        <td style="background-color: {{$confprov->backcolor}}; color: {{$confprov->forecolor}};" 
                            align="right"
                            title="PRECIO DEL PRODUCTO">
                            {{number_format($t->precio/$factor, 2, '.', ',')}}
                        </td>
                        
                        <td style="background-color: {{$confprov->backcolor}}; color: {{$confprov->forecolor}};" 
                            align="right"
                            title="IVA DEL PRODUCTO">
                            {{number_format($t->iva, 2, '.', ',')}}
                        </td>
                        
                        @if ($t->da > 0)
                            <td style="background-color: {{$confprov->backcolor}}; color: red;" 
                                align="right" 
                                title="DESCUENTO ADICIONAL DEL PRODUCTO">
                                {{number_format($t->da, 2, '.', ',')}}
                            </td>
                        @else
                            <td style="background-color: {{$confprov->backcolor}}; color: {{$confprov->forecolor}};" 
                                align="right"
                                title="DESCUENTO ADICIONAL DEL PRODUCTO">
                                {{number_format($t->da, 2, '.', ',')}}
                            </td>
                        @endif

                        @if ($t->dp > 0)
                            <td style="background-color: {{$confprov->backcolor}}; color: red;" 
                                align="right" 
                                title="DESCUENTO PRE-EMPAQUE DEL PRODUCTO">
                                {{number_format($t->dp, 2, '.', ',')}}
                            </td>
                        @else
                            <td style="background-color: {{$confprov->backcolor}}; color: {{$confprov->forecolor}};" 
                                align="right"
                                title="DESCUENTO PRE-EMPAQUE DEL PRODUCTO">
                                {{number_format($t->dp, 2, '.', ',')}}
                            </td>
                        @endif

                        @if ($t->di > 0)
                            <td style="background-color: {{$confprov->backcolor}}; color: red;" 
                                align="right" 
                                title="DESCUENTO INTERNET">
                                {{number_format($t->di, 2, '.', ',')}}
                            </td>
                        @else
                            <td style="background-color: {{$confprov->backcolor}}; color: {{$confprov->forecolor}};" 
                                align="right"
                                title="DESCUENTO INTERNET">
                                {{number_format($t->di, 2, '.', ',')}}
                            </td>
                        @endif

                        @if ($t->dc > 0)
                            <td style="background-color: {{$confprov->backcolor}}; color: red;" 
                                align="right" 
                                title="DESCUENTO COMERCIAL">
                                {{number_format($t->dc, 2, '.', ',')}}
                            </td>
                        @else
                            <td style="background-color: {{$confprov->backcolor}}; color: {{$confprov->forecolor}};" 
                                align="right"
                                title="DESCUENTO COMERCIAL">
                                {{number_format($t->dc, 2, '.', ',')}}
                            </td>
                        @endif

                        @if ($t->pp > 0)
                            <td style="background-color: {{$confprov->backcolor}}; color: red;" 
                                align="right" 
                                title="DESCUENTO PRONTO PAGO">
                                {{number_format($t->pp, 2, '.', ',')}}
                            </td>
                        @else
                            <td style="background-color: {{$confprov->backcolor}}; color: {{$confprov->forecolor}};" 
                                align="right"
                                title="DESCUENTO PRONTO PAGO">
                                {{number_format($t->pp, 2, '.', ',')}}
                            </td>
                        @endif
                        
                        <td style="background-color: {{$confprov->backcolor}}; color: {{$confprov->forecolor}};" 
                            align="right"
                            title="PRECIO NETO DEL PRODUCTO">
                            {{number_format($t->neto/$factor, 2, '.', ',')}}
                        </td>

                        <td style="background-color: {{$confprov->backcolor}}; color: {{$confprov->forecolor}};" 
                            align="right"
                            title="SUBTOTAL DEL PRODUCTO"> 
                            {{number_format($t->subtotal/$factor, 2, '.', ',')}}
                        </td>

                    </tr>
                @endforeach
            </table>
        </div>
	</div> 
</div>

@push ('scripts')
<script>
$('#subtitulo').text('{{$subtitulo}}');
</script>
@endpush
@endsection