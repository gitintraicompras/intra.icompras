@extends ('layouts.menu')
@section ('contenido')

@php
  $moneda = Session::get('moneda', 'BSS');
  $factor = RetornaFactorCambiario('', $moneda);
  $contador = 0;
  $rutalogoprov = 'http://isaweb.isbsistemas.com/public/storage/prov/';
@endphp


<!-- ENCABEZADO -->
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="form-group">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 input-group input-group-sm">

                <span class="input-group-addon"
                    style="padding: 2px;">
                    ID:
                </span>
                <b><input
                    readonly
                    type="text"
                    class="form-control"
                    value="{{$tabla->id}} - {{ $tabla->tipedido }}"
                    style="color: #000000"></b>

                <span class="input-group-addon" style="border:0px; "></span>
                <span class="input-group-addon">Fecha:</span>
                <input readonly
                    type="text"
                    class="form-control"
                    value="{{date('d-m-Y H:i', strtotime($tabla->fecha))}}"
                    style="color: #000000">

            </div>
        </div>
    </div>
</div>
<!-- TABLAS -->
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
                    <div class="col-md-12">
                        <div class="row">
                            <!-- BOTONES CATALOGO -->
                            <div class="input-group mb-3">
                                <div class="input-group-prepend" id="button-addon3">
                                    @foreach ($arrayProv as $key => $val)
                                        @php
                                            $prov = $arrayProv[$key]['codprove'];
                                        @endphp
                                        @if ( $prov == "MAESTRO")
                                            <a href="{{url('/pedido/exportar/'.$tabla->id.'-MAESTRO')}}">
                                                @php
                                                    $r = DB::table('pedren')
                                                    ->selectRaw('count(*) as contitem')
                                                    ->where('id','=', $id)
                                                    ->where('exportado','=', '0')
                                                    ->first();
                                                    if (($r->contitem) == 0)
                                                        continue;
                                                    $contador++;
                                                @endphp
                                                <button style="width: 153px;
                                                    height: 32px;
                                                    color: #000000;
                                                    border: #b7b7b7;
                                                    background-color: #b7b7b7;"
                                                    class="btn btn-outline-secondary"
                                                    type="button"
                                                    data-toggle="tooltip"
                                                    title="Ver pedido maestro">
                                                    @if ($tpactivo == $prov)
                                                        <i class="fa fa-check"></i>
                                                        <b>MAESTRO ({{ number_format($r->contitem, 0, '.', ',') }})</b>
                                                    @else
                                                        MAESTRO ({{ number_format($r->contitem, 0, '.', ',') }})
                                                    @endif
                                                </button>
                                            </a>
                                        @else
                                            @php
                                                $confprov = LeerProve($prov);
                                                if (is_null($confprov))
                                                    continue;
                                                $r = DB::table('pedren')
                                                ->selectRaw('count(*) as contitem')
                                                ->where('id','=', $id)
                                                ->where('exportado','=', '0')
                                                ->where('codprove','=', $prov)
                                                ->first();
                                                if (($r->contitem) == 0)
                                                    continue;
                                                $contador++;
                                            @endphp
                                            <a href="{{url('/pedido/exportar/'.$tabla->id.'-'.$confprov->codprove)}}">
                                                <button style="width: 153px;
                                                    height: 32px;
                                                    color:{{$confprov->forecolor}};
                                                    border: {{$confprov->backcolor}};
                                                    background-color: {{$confprov->backcolor}};"
                                                    class="btn btn-outline-secondary"
                                                    type="button"
                                                    data-toggle="tooltip"
                                                    title="Ver pedido por proveedor">
                                                    @if ($tpactivo == $prov)
                                                        <i class="fa fa-check"></i>
                                                        <b>{{$confprov->descripcion}} ({{ number_format($r->contitem, 0, '.', ',') }})</b>
                                                    @else
                                                        {{$confprov->descripcion}} ({{ number_format($r->contitem, 0, '.', ',') }})
                                                    @endif
                                                </button>
                                            </a>
                                        @endif
                                    @endforeach

                                    <!-- EXPORTAR PEDIDO -->
                                    <a href="" data-target="#modal-exportar-{{$id}}" data-toggle="modal">
                                        <button class="btn-normal"
                                            data-toggle="tooltip"
                                            title="Exportar pedido"
                                            style="width: 153px; height: 32px;">
                                            Exportar
                                        </button>
                                    </a>

                                </div>
                            </div>
                            <!-- TABLA -->
                            <br>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="table-responsive">
                                        @php
                                        $fila = 0;
                                        @endphp
                                        <table id="idTabla"
                                            class="table table-striped table-bordered table-condensed table-hover">
                                            <thead class="colorTitulo">
                                                <th>
                                                    #
                                                </th>
                                                <th style="width: 100px; vertical-align:middle;">
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;IMAGEN&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                </th>
                                                <th title="DESCRIPCION PRODUCTO DEL CLIENTE">
                                                    PRODUCTO(C)
                                                </th>
                                                <th style="display:none;">
                                                    BARRA
                                                </th>
                                                <th title="Código alterno para cruzar con la ODC/FACTURA en la exportación"
                                                    style="width: 160px;">
                                                    ALTERNO
                                                </th>
                                                <th title="DESCRIPCION PRODUCTO DEL PROVEEDOR">
                                                    DESCRIPCION(P)
                                                </th>
                                                <th style="display:none;">CODIGO</th>
                                                <th style="display:none;">PROVEEDOR</th>
                                                <th>CANTIDAD</th>
                                                <th>PRECIO</th>
                                                <th>IVA</th>
                                                <th style="display:none;">DA</th>
                                                <th style="display:none;">DI</th>
                                                <th style="display:none;">DC</th>
                                                <th style="display:none;">PP</th>
                                                <th>NETO</th>
                                                <th>SUBTOTAL</th>
                                                <th style="display:none;">DESPROD</th>
                                            </thead>
                                            @foreach ($tabla2 as $t)
                                                @if ($t->codprove == $tpactivo || $tpactivo == "MAESTRO" )

                                                    @php
                                                        if ($t->exportado != 0)
                                                            continue;
                                                        $confprov = LeerProve($t->codprove);
                                                        $factor = RetornaFactorCambiario($t->codprove, $moneda);
                                                        $respAlterno = VerificarCodalterno($t->barra);
                                                        $invDesrip = $t->desprod;
                                                        $inv = verificarProdInventario($t->barra,"");
                                                        if ($inv)
                                                            $invDesrip = $inv->desprod;
                                                        else {
                                                            $inv = LeerInventarioCodigo($respAlterno['codalterno'],"");
                                                            if ($inv) {
                                                                $invDesrip = $inv->desprod;
                                                            }
                                                        }
                                                    @endphp

                                                    <tr>

                                                        @if ($t->estado == "ENVIADO" || $t->estado == "RECIBIDO")
                                                            <td style="background-color: #b7b7b7;
                                                                color: #000000;"
                                                                title = "PRODUCTO ENVIADO">
                                                                <a href=""
                                                                    style="color: #000000;">
                                                                    {{$fila++}}
                                                                </a>
                                                            </td>
                                                        @else
                                                            <td>{{$fila++}}</td>
                                                        @endif

                                                        <td>
                                                            <div align="center">
                                                                <a href="{{URL::action('PedidoController@verprod',$t->barra)}}">

                                                                    <img src="http://isaweb.isbsistemas.com/public/storage/prod/{{NombreImagen($t->barra)}}"
                                                                    class="img-responsive"
                                                                    alt="icompras360"
                                                                    style="width: 100px;
                                                                    border: 2px solid #D2D6DE;"
                                                                    oncontextmenu="return false">

                                                                </a>
                                                            </div>
                                                        </td>

                                                        <td title="DESCRIPCION DEL PRODUCTO DEL CLIENTE">
                                                            <b>{{$invDesrip}}</b><br>
                                                            <span title="CODIGO DE BARRA">
                                                                <i class="fa fa-barcode">
                                                                    {{$t->barra}}
                                                                </i>
                                                            </span><br>
                                                        </td>

                                                        <td style="display:none;"
                                                            id="idBarra-{{$t->item}}"
                                                            title="CODIGO DE BARRA">
                                                            {{$t->barra}}
                                                        </td>





                                                        <td style="width: 160px;">
                                                            <div class="col-xs-12 input-group" >
                                                                <input style="color: #000000;
                                                                    width: 120px;"
                                                                    value="{{$respAlterno['codalterno']}}"
                                                                    class="form-control"
                                                                    id="idCodalterno-{{$t->item}}" >
                                                                <span class="input-group-btn">
                                                                    <!-- MODIFICAR CODIGO ALTERMO -->
                                                                    <button
                                                                        style="background-color:{{$respAlterno['backcolor']}};
                                                                            color: {{$respAlterno['forecolor']}};"
                                                                        type="button"
                                                                        class="btn btn-pedido BtnModificar"
                                                                        title="{{$respAlterno['title']}}"
                                                                        id="idModificar-{{$t->item}}-{{$respAlterno['backcolor']}}"
                                                                        @if (!$respAlterno['activarBuscar'])
                                                                        disabled
                                                                        @endif >
                                                                        <span class="fa fa-check"
                                                                        id="idModificar-{{$t->item}}-{{$respAlterno['backcolor']}}"
                                                                        aria-hidden="true">
                                                                        </span>
                                                                    </button>

                                                                    <!-- BUSCAR CODIGO ALTERNO MANUAL -->
                                                                    <button
                                                                        style="background-color:{{$respAlterno['backcolor']}};
                                                                            color: {{$respAlterno['forecolor']}};"
                                                                        type="button"
                                                                        class="btn btn-pedido BtnBuscar"
                                                                        id="idFila1_{{$fila}}"
                                                                        title="Buscar código alternativo de forma manual"
                                                                        @if (!$respAlterno['activarBuscar'])
                                                                            disabled
                                                                        @endif >
                                                                        <span class="fa fa-search-plus"
                                                                            aria-hidden="true"
                                                                            id="idFila2_{{$fila}}">
                                                                        </span>
                                                                    </button>

                                                                </span>
                                                            </div>
                                                        </td>

                                                        <td style="background-color: {{$confprov->backcolor}};
                                                            color: {{$confprov->forecolor}};"
                                                            title="DESCRIPCION DEL PRODUCTO DEL PROVEEDOR">
                                                            {{$t->desprod}}<br>
                                                            <span title="CODIGO DEL PROVEEDOR">
                                                                <img style="width: 20px;
                                                                height: 20px;
                                                                margin-top: 6px;"
                                                                src="{{$rutalogoprov.$confprov->rutalogo1}}"
                                                                alt="icompras360">
                                                                {{$t->codprove}}
                                                            </span><br>
                                                            <span title="CODIGO DEL PRODUCTO">
                                                                <i style="margin-left: 4px;" class="fa fa-cube">
                                                                    {{$t->codprod}}
                                                                </i><br>
                                                            </span>
                                                        </td>

                                                        <td style="display:none;">
                                                            {{$t->codprod}}
                                                        </td>

                                                        <td style="display:none;">
                                                            {{$t->codprove}}
                                                        </td>

                                                        <td style="background-color: {{$confprov->backcolor}};
                                                            color: {{$confprov->forecolor}};"
                                                            align="right"
                                                            title="CANTIDAD DEL PEDIDO">
                                                            {{number_format($t->cantidad, 2, '.', ',')}}
                                                        </td>

                                                        <td style="background-color: {{$confprov->backcolor}};
                                                            color: {{$confprov->forecolor}};"
                                                            align="right"
                                                            title="PRECIO DEL PRODUCTO">
                                                            {{number_format($t->precio/$factor, 2, '.', ',')}}
                                                        </td>

                                                        <td style="background-color: {{$confprov->backcolor}};
                                                            color: {{$confprov->forecolor}};"
                                                            align="right"
                                                            title="IVA DEL PRODUCTO">
                                                            {{number_format($t->iva, 2, '.', ',')}}
                                                        </td>

                                                        @if ($t->da > 0)
                                                            <td style="background-color: {{$confprov->backcolor}};
                                                                display:none;
                                                                color: red;"
                                                                align="right"
                                                                title="DESCUENTO ADICIONAL DEL PRODUCTO">
                                                                {{number_format($t->da, 2, '.', ',')}}
                                                            </td>
                                                        @else
                                                            <td style="background-color: {{$confprov->backcolor}};
                                                                display:none;
                                                                color: {{$confprov->forecolor}};"
                                                                align="right"
                                                                title="DESCUENTO ADICIONAL DEL PRODUCTO">
                                                                {{number_format($t->da, 2, '.', ',')}}
                                                            </td>
                                                        @endif

                                                        @if ($t->di > 0)
                                                            <td style="background-color: {{$confprov->backcolor}};
                                                                color: red;
                                                                display:none;"
                                                                align="right"
                                                                title="DESCUENTO INTERNET">
                                                                {{number_format($t->di, 2, '.', ',')}}
                                                            </td>
                                                        @else
                                                            <td style="background-color: {{$confprov->backcolor}};
                                                                color: {{$confprov->forecolor}};
                                                                display:none;"
                                                                align="right"
                                                                title="DESCUENTO INTERNET">
                                                                {{number_format($t->di, 2, '.', ',')}}
                                                            </td>
                                                        @endif

                                                        @if ($t->dc > 0)
                                                            <td style="background-color: {{$confprov->backcolor}};
                                                                color: red;
                                                                display:none;"
                                                                align="right"
                                                                title="DESCUENTO COMERCIAL">
                                                                {{number_format($t->dc, 2, '.', ',')}}
                                                            </td>
                                                        @else
                                                            <td style="background-color: {{$confprov->backcolor}};
                                                                color: {{$confprov->forecolor}};
                                                                display:none;"
                                                                align="right"
                                                                title="DESCUENTO COMERCIAL">
                                                                {{number_format($t->dc, 2, '.', ',')}}
                                                            </td>
                                                        @endif

                                                        @if ($t->pp > 0)
                                                            <td style="background-color: {{$confprov->backcolor}};
                                                                color: red;
                                                                display:none;"
                                                                align="right"
                                                                title="DESCUENTO PRONTO PAGO">
                                                                {{number_format($t->pp, 2, '.', ',')}}
                                                            </td>
                                                        @else
                                                            <td style="background-color: {{$confprov->backcolor}};
                                                                color: {{$confprov->forecolor}};
                                                                display:none;"
                                                                align="right"
                                                                title="DESCUENTO PRONTO PAGO">
                                                                {{number_format($t->pp, 2, '.', ',')}}
                                                            </td>
                                                        @endif

                                                        <td style="background-color: {{$confprov->backcolor}};
                                                            color: {{$confprov->forecolor}};"
                                                           align="right"
                                                            title="PRECIO NETO DEL PRODUCTO">
                                                            {{number_format($t->neto/$factor, 2, '.', ',')}}
                                                        </td>

                                                        <td style="background-color: {{$confprov->backcolor}};
                                                            color: {{$confprov->forecolor}};"
                                                            align="right"
                                                            title="SUBTOTAL DEL PRODUCTO">
                                                            {{number_format($t->subtotal/$factor, 2, '.', ',')}}
                                                        </td>

                                                        <td style="display:none;">
                                                            {{$invDesrip}}
                                                        </td>

                                                    </tr>
                                                @endif
                                            @endforeach
                                        </table>
                                        @include('isacom.pedido.buscar')

                                    </div>
                                </div>
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
                                <table id="idTabla2"
                                    class="table table-striped table-bordered table-condensed table-hover">

                                    <thead class="colorTitulo">
                                        <th>#</th>
                                        <th style="width: 60px;" class="hidden-xs">IMAGEN</th>
                                        <th>CODIGO</th>
                                        <th>NOMBRE</th>
                                        <th>EXPORTADO</th>
                                        <th>FECHA</th>
                                        <th>MONEDA</th>
                                        <th>FACTOR</th>
                                        <th>MODALIDAD</th>
                                        <th>USUARIO</th>
                                    </thead>
                                    @foreach ($arrayProv as $key => $val)
                                    @php
                                        $prov = $arrayProv[$key]['codprove'];
                                    @endphp
                                    @if ( $prov != "MAESTRO")
                                        @php
                                            $confprov = LeerProve($prov);
                                            if (is_null($confprov))
                                                continue;
                                            $r = DB::table('pedren')
                                            ->where('id','=', $id)
                                            ->where('codprove','=', $prov)
                                            ->first();
                                            if (empty($r))
                                                continue;
                                            $exportado = "NO";
                                            $doc = LeerDocExportado($id, $codcli, "PED", $prov);

                                            if ($doc) {
                                                switch ($doc->exportado) {
                                                    case '0':
                                                        $exportado = 'NO';
                                                        break;
                                                    case '1':
                                                        $exportado = 'SI';
                                                        break;
                                                    case '2':
                                                        $exportado = 'PED';
                                                        break;
                                                    case '3':
                                                        $exportado = 'ANULADO';
                                                        break;
                                                    default:
                                                        $exportado = 'NO';
                                                        break;
                                                }
                                            }
                                        @endphp
                                        <tr>
                                            <td style="background-color: {{$confprov->backcolor}};
                                                color: {{$confprov->forecolor}}; ">
                                                {{$loop->iteration-1}}
                                            </td>
                                            <td class="hidden-xs">
                                                <div align="center">
                                                    <a href="{{URL::action('ProveedorController@verprov',$prov)}}">
                                                        <img src="http://isaweb.isbsistemas.com/public/storage/prov/{{$confprov->rutalogo1}}"
                                                        class="img-responsive"
                                                        alt="icompras360"
                                                        style="width: 100px;
                                                        border: 2px solid #D2D6DE;"
                                                        oncontextmenu="return false">
                                                    </a>
                                                </div>
                                            </td>
                                            <td>{{$confprov->codprove}}</td>
                                            <td>{{strtoupper($confprov->nombre)}}</td>
                                            <td>{{$exportado}}</td>
                                            <td>
                                                @if (isset($doc->fecha))
                                                    {{date('d-m-Y H:i', strtotime($doc->fecha))}}
                                                @else
                                                    {{date('d-m-Y H:i')}}
                                                @endif
                                            </td>
                                            <td>{{isset($doc->codmoneda) ? $doc->codmoneda : ""}}</td>
                                            <td align="right">
                                                {{number_format(isset($doc->factor) ? $doc->factor : 1.00, 2, '.', ',')}}
                                            </td>
                                            <td>{{isset($doc->modalidad) ? $doc->modalidad : ""}}</td>
                                            <td>{{isset($doc->usuario) ? $doc->usuario : "" }}</td>
                                        </tr>
                                    @endif
                                    @endforeach
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

<!-- MODAL EXPORT -->
<div class="modal fade modal-slide-in-right"
    aria-hidden="true"
    role="dialog"
    tabindex="-1"
    id="modal-exportar-{{$id}}">
{!! Form::open(array('url'=>'/pedido/procexportar','method'=>'POST','autocomplete'=>'off')) !!}
{{ Form::token() }}
<input id='id' hidden name="id" value="{{$tabla->id}}" type="text">
<input id='codcli' hidden name="codcli" value="{{$codcli}}" type="text">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header colorTitulo" >
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">x</span>
            </button>
            <h4 class="modal-title">EXPORTAR PEDIDO</h4>
        </div>
        <div class="modal-body">
            <p>Pedido #: {{$t->id}}</p>
            <p>Marque los proveedores para exportar el pedido ?</p>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive" style="height: 332px;">
                        <table id="idTabla3"
                            class="table table-striped table-bordered table-condensed table-hover">
                            <thead class="colorTitulo">
                                <th style="width: 20px;">#</th>
                                <th style="width: 60px;">IMAGEN</th>
                                <th style="width: 80px;">
                                    MARCAR
                                </th>
                                <th>MONEDA</th>
                                <th>FACTOR</th>
                                <th>MODALIDAD</th>
                                <th style="display:none;">CODPROVE</th>
                            </thead>

                            @php $fila = 0; @endphp
                            @foreach ($arrayProv as $key => $val)
                                @php
                                    if ($arrayProv[$key]['exportado'] != '0')
                                        continue;
                                    $codprove = $arrayProv[$key]['codprove'];
                                @endphp
                                @if ( $prov != "MAESTRO")
                                    @php
                                        $confprov = LeerProve($codprove);
                                        if (is_null($confprov))
                                            continue;
                                        $r = DB::table('pedren')
                                        ->selectRaw('count(*) as contitem')
                                        ->where('id','=', $id)
                                        ->where('codprove','=', $codprove)
                                        ->first();
                                    @endphp

                                    <tr>

                                        <td style="background-color: {{$confprov->backcolor}};
                                            color: {{$confprov->forecolor}}; ">
                                            {{$fila++}}
                                        </td>

                                        <td >
                                            <div align="center">
                                                <a href="">
                                                    <img src="http://isaweb.isbsistemas.com/public/storage/prov/{{$confprov->rutalogo1}}"
                                                    class="img-responsive"
                                                    alt="icompras360"
                                                    style="width: 100px;
                                                    border: 2px solid #D2D6DE;"
                                                    oncontextmenu="return false">
                                                </a>
                                            </div>
                                        </td>

                                        <td style="padding-top: 10px;
                                            text-align: center;
                                            vertical-align: middle;">
                                            <input name='exportar[]'
                                                class="case"
                                                type="checkbox"
                                                id='checkbox_{{$codprove}}' />
                                        </td>

                                        <td>
                                            <select name="codmoneda[]"
                                                class="form-control"
                                                data-live-search="true" >
                                                <option
                                                    @if ($moneda == 'BSS') selected @endif
                                                    value="BSS">
                                                    BSS
                                                </option>
                                                <option
                                                    @if ($moneda == 'USD') selected @endif
                                                    value="USD">
                                                    USD
                                                </option>
                                                <option
                                                    @if ($moneda == 'EUR') selected @endif
                                                    value="EUR">
                                                    EUR
                                                </option>
                                            </select>
                                        </td>

                                        <td>
                                            <input name='tasa[]'
                                                class="form-control"
                                                type="text"
                                                align="right"
                                                style="width: 100%; text-align: right;"
                                                value = "{{number_format($factor,2, '.', ',')}}" />
                                        </td>

                                        <td>
                                            <select name="modalidad[]"
                                                class="form-control"
                                                data-live-search="true" >
                                                <option selected value="LOCAL">
                                                    LOCAL
                                                </option>
                                                <option value="MATRIZ">
                                                    MATRIZ
                                                </option>
                                            </select>
                                        </td>

                                        <td style="display:none;">
                                            <input name='codprove[]'
                                                type="text"
                                                value = "{{$codprove}}" />
                                        </td>

                                    </tr>

                                @endif
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-normal" data-dismiss="modal">Regresar</button>
            @if ($contador > 0)
                <button type="submit" class="btn-confirmar">Confirmar</button>
            @endif
        </div>
    </div>
</div>
{{Form::Close()}}
</div>

@push ('scripts')
<script>
$('#subtitulo').text('{{$subtitulo}}');

window.onload = function() {

    $('.BtnModificar').on('click',function(e) {
        var idpedido = e.target.id.split('-');
        var item = idpedido[1].trim();
        var color = idpedido[2].trim();
        var barra = $('#idBarra-'+item).text().trim();
        var codalterno = $('#idCodalterno-'+item).val().trim();
        //alert("Barra: " + barra + " - Codalterno: " + codalterno + " - Color: " + color);
        $.ajax({
            type:'POST',
            url: '../modcodalterno',
            dataType: 'json',
            encode  : true,
            data: {barra:barra, codalterno:codalterno },
            success:function(data) {
                if (data.msg != '')
                    alert(data.msg);
                location.reload(true);
            }
        });
    });

    $('.BtnBuscar').on('click',function(e) {
        var id = e.target.id.split('_');
        var fila = id[1].trim();
        //alert(fila);
        const tableReg = document.getElementById('idTabla');
        const cellsOfRow = tableReg.rows[fila].getElementsByTagName('td');

        var codprod = cellsOfRow[6].innerHTML.trim();

        var descripBuscar = cellsOfRow[17].innerHTML.trim();

        var barraBuscar = cellsOfRow[3].innerHTML.trim();
        //alert("CODPROD: " + codprod + "  BARRA: " + barraBuscar + " VAR17: " + descripBuscar );

        $("#idbarraBuscar").text(barraBuscar);
        $("#iddescripBuscar").text(descripBuscar);
        $('#modal-buscar').modal();
    });
}

function tdclick(e) {
    var id = e.target.id.split('_');
    var barra = id[1];
    $(".case").prop("checked", false);
    $("#idcheck_" + barra).prop("checked", true);
    $('#idbarra').val(barra);
}

function cargarProd() {
    var codcli = '{{$codcli}}';
    var resp;
    var filtro = $('#idfiltro').val();
    //alert("CODCLI: " + codcli + " FILTRO: " + filtro);
    if (filtro == "") {
        alert("FALTAN PARAMETROS PARA REALIZAR LAS BUSQUEDA");
    } else {
        var jqxhr = $.ajax({
            type:'POST',
            url: '../obtenerInvCliente',
            dataType: 'json',
            encode  : true,
            data: { codcli:codcli, filtro:filtro },
            success:function(data) {
                $("#tbodyProducto").empty();
                $.each(data.resp, function(index, item){
                   var valor =
                    '<tr>' +
                      "<td style='padding-top: 10px;'>" +
                      "<span onclick='tdclick(event);'>" +
                      "<center>" +
                      "<input class='case' type='checkbox' id='idcheck_" + item.barra + "' />" +
                      "</center>" +
                      "</span>" +
                      "</td>" +
                      "<td>" + item.desprod + "</td>" +
                      "<td>" + item.barra + "</td>" +
                      "<td>" + item.marca + "</td>" +
                    "</tr>";
                    $(valor).appendTo("#tbodyProducto");
                });
            }
        });
    }
}


function tdclickProdBorrar(e) {
    var idx = e.target.id.split('_');
    var codalterno = idx[1];
    var barra = $('#idbarraBuscar').text().trim();
    //alert(barra + " - " + codalterno);

    $.ajax({
        type:'POST',
        url: '../modcodalterno',
        dataType: 'json',
        encode  : true,
        data: {barra:barra, codalterno:codalterno },
        success:function(data) {
            if (data.msg != '')
                alert(data.msg);
            location.reload(true);
        }
    });
}

function ejecutarAgregar() {
    var codalterno = $('#idbarra').val();
    var barra = $('#idbarraBuscar').text().trim();
    alert("BARRA: " + barra + " CODALT: " +codalterno);
    if (codalterno == '') {
        alert("FALTAN PARAMETROS PARA AGREGAR UN PRODUCTO");
    } else {
        var url = "{{url('/pedgrupo/agregar/prod/X')}}";
        url = url.replace('X', ctipo);
        window.location.href=url;
    }
}

</script>
@endpush

@endsection
