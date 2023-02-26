@extends ('layouts.menu')
@section ('contenido')

@php
  $moneda = Session::get('moneda', 'BSS');
  $factorInv = RetornaFactorCambiario('', $moneda);
  $rutalogoprov = 'http://isaweb.isbsistemas.com/public/storage/prov/';
@endphp

<!-- ENCABEZADO -->
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="form-group">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 input-group input-group-sm">

                <span class="input-group-addon">Pedido:</span>
                <B><input readonly
                    type="text"
                    class="form-control"
                    value="{{$tabla->id}} - {{$tabla->tipedido}}"
                    style="color: #000000"></B>

                <span class="input-group-addon hidden-xs" style="border:0px; "></span>
                <span class="input-group-addon hidden-xs">Estado:</span>
                <input readonly
                    type="text"
                    class="form-control hidden-xs"
                    value="{{$tabla->estado}} | {{$tabla->origen}}"
                    style="color: #000000">

                <span class="input-group-addon hidden-xs" style="border:0px; "></span>
                <span class="input-group-addon hidden-xs">Fecha:</span>
                <input readonly type="text" class="hidden-xs form-control" value="{{date('d-m-Y H:i', strtotime($tabla->fecha))}}" style="color: #000000">

                <span class="input-group-addon hidden-xs" style="border:0px; "></span>
                <span class="input-group-addon hidden-xs">Enviado:</span>
                <input readonly type="text" class="form-control hidden-xs" value="{{date('d-m-Y H:i', strtotime($tabla->fecenviado))}}" style="color:#000000" >

                <span class="input-group-addon" style="border:0px; "></span>
                <span class="input-group-addon">Ahorro:</span>
                <input readonly
                    type="text"
                    class="form-control"
                    value="{{number_format($tabla->ahorro/$factorInv, 2, '.', ',')}}"
                    style="color: red; text-align: right; "
                    id="idAhorro">
            </div>
        </div>
        <div class="row hidden-sm hidden-xs" style="margin-top: 4px;">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 input-group input-group-sm">

                <span class="input-group-addon">Descuento:</span>
                <input readonly
                    type="text"
                    class="form-control"
                    value="{{number_format($tabla->descuento/$factorInv, 2, '.', ',')}}"
                    style="color: #000000; text-align: right;"
                    id="idDescuento">

                <span class="input-group-addon" style="border:0px; "></span>
                <span class="input-group-addon">Subtotal:</span>
                <input readonly
                    type="text"
                    class="form-control"
                    value="{{number_format($tabla->subtotal/$factorInv, 2, '.', ',')}}"
                    style="color: #000000; text-align: right;"
                    id="idSubtotal">

                <span class="input-group-addon" style="border:0px; "></span>
                <span class="input-group-addon">Impuesto:</span>
                <input readonly
                    type="text"
                    class="form-control"
                    value="{{number_format($tabla->impuesto/$factorInv, 2, '.', ',')}}"
                    style="color: #000000; text-align: right;"
                    id="idImpuesto">

                <span class="input-group-addon" style="border:0px; "></span>
                <span class="input-group-addon">Total:</span>
                <b><input readonly
                    type="text"
                    class="form-control"
                    value="{{number_format($tabla->total/$factorInv, 2, '.', ',')}}"
                    style="color: #000000; text-align: right;"
                    id="idTotal"></b>
            </div>
        </div>
    </div>
</div>

@if ($contItem > 0)
<!-- BOTONES PROVEEDORES-->
<div class="input-group mb-3">
    <div class="input-group-prepend" id="button-addon3">
        @foreach ($arrayProv as $prov)
            @if ( $prov == "MAESTRO")
                <a href="{{url('/pedido/'.$tabla->id.'-MAESTRO/edit')}}">
                    <button style="width: 153px;
                        height: 32px;
                        color: #000000;
                        border: #b7b7b7;
                        background-color: #b7b7b7;
                        border-radius: 15px;"
                        class="btn btn-outline-secondary"
                        type="button"
                        data-toggle="tooltip"
                        title="Ver pedido maestro">
                        @if ($tpactivo==$prov)
                            <i class="fa fa-check"></i>
                            <b>MAESTRO</b>
                        @else
                            <img style="width: 20px; height: 20px;"
                            src="{{$rutalogoprov.'icompras360.png'}}"
                            alt="icompras360">
                            &nbsp;
                            MAESTRO
                        @endif
                    </button>
                </a>
            @else
                @php
                    $confprov = LeerProve($prov);
                    if (is_null($confprov))
                        continue;
                @endphp
                <a href="{{url('/pedido/'.$tabla->id.'-'.$confprov->codprove.'/edit')}}">
                    <button style="width: 153px;
                        height: 32px;
                        color:{{$confprov->forecolor}};
                        border: {{$confprov->backcolor}};
                        background-color: {{$confprov->backcolor}};
                        border-radius: 15px;"
                        class="btn btn-outline-secondary"
                        type="button"
                        data-toggle="tooltip"
                        title="Ver pedido por proveedor">
                        @if ($tpactivo==$prov)
                            <i class="fa fa-check"></i>
                            <b>{{$confprov->descripcion}}</b>
                        @else
                            <img style="width: 20px; height: 20px;"
                            src="{{$rutalogoprov.$confprov->rutalogo1}}" alt="icompras360">
                            &nbsp;
                            {{$confprov->descripcion}}
                        @endif
                    </button>
                </a>
            @endif
        @endforeach
    </div>
</div>
@endif

<!-- BOTONES BUSCAR, CATALOGO, ENTTRADAS, OFERTAS, MOLECULA -->
<div class="btn-toolbar" role="toolbar" style="margin-top: 12px; margin-bottom: 3px;">
    <div class="btn-group" role="group" style="width: 100%;">
        <!-- BOTON BUSCAR/CATALOGO/ENVIAR/ELIMINAR -->
        @include('isacom.pedido.catasearch')

        <!-- VER CATALOGO -->
        <a href="{{URL::action('PedidoController@catalogo','C')}}">
            <button style="width: 90px; height: 34px; border-radius: 5px;"
                type="button"
                data-toggle="tooltip"
                title="Ver catálogo de productos con entradas recientes"
                class="btn-catalogo">
                Catálogo
            </button>
        </a>
        <!-- VER ENTRADAS -->
        <a href="{{URL::action('PedidoController@catalogo','E')}}">
            <button style="width: 90px; height: 34px; border-radius: 5px;"
                type="button"
                data-toggle="tooltip"
                title="Ver catálogo de productos con entradas recientes"
                class="btn-catalogo">
                Entradas
            </button>
        </a>
        <!-- VER OFERTAS -->
        <a href="{{URL::action('PedidoController@catalogo','O')}}">
            <button style="width: 90px; height: 34px; border-radius: 5px;"
                type="button"
                data-toggle="tooltip"
                title="Ver catálogo de ofertas de productos"
                class="btn-catalogo">
                Ofertas
            </button>
        </a>
        @if (Auth::user()->botonMolecula == 1)
            <!-- MOLECULAS -->
            <a href="{{URL::action('PedidoController@catalogo','M')}}">
                <button style="width: 90px; height: 34px; border-radius: 5px;"
                    type="button"
                    data-toggle="tooltip"
                    title="Ver catálogo por moleculas"
                    class="btn-catalogo">
                    Moleculas
                </button>
            </a>
        @endif

        @if (Auth::user()->botonEnvio == 1)
            @if ($contItem > 0)
            <!-- ENVIA PEDIDO -->
            <a href=""
                data-target="#modal-enviar-{{$tabla->id}}"
                data-toggle="modal">
                <button style="width: 90px; height: 34px; border-radius: 5px;"
                    type="button"
                    data-toggle="tooltip"
                    title="Enviar pedido"
                    class="btn-confirmar">
                    Enviar
                </button>
            </a>
            @include('isacom.pedido.enviar')
            @endif
        @endif

    </div>
</div>

<!-- TABLA -->
<div class="row" style="margin-top: 5px;">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="table-responsive">
            <table id="myTable"
                class="table table-striped table-bordered table-condensed table-hover">
                <thead style="background-color: #b7b7b7;">
                    <th style="vertical-align:middle;">
                        #
                    </th>
                    <th style="width: 100px; vertical-align:middle;">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;IMAGEN&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </th>
                    <th style="width: 170px; vertical-align:middle;">PEDIR</th>
                    <th>
                        <center>
                        <div style="width: 70px;">
                            <span class="input-group-btn">
                                <div class="col-xs-12 input-group" >
                                    <input type="checkbox"
                                        id="selectall"
                                        title="marcar/desmarcar todos los producto"
                                        style="width: 50%; vertical-align:middle;">

                                    <a href=""
                                        data-target="#modal-deleteAll"
                                        data-toggle="modal">
                                    <button style="vertical-align:middle;
                                        text-align: center;
                                        width: 50%;
                                        height: 30px;"
                                        type="button"
                                        class="BtnAgregar btn btn-pedido"
                                        title="Eliminar producto marcados" >
                                        <span class="fa fa-trash-o" >
                                        </span>
                                    </button>
                                    </a>
                                </div>
                            </span>
                        </div>
                        </center>
                    </th>
                    <th style="vertical-align:middle;">
                        PRODUCTO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </th>
                    @if (Auth::user()->versionLight == 0)
                        <th title="Unidades en Transito del producto"
                            style="background-color: #b7b7b7;
                                   vertical-align:middle;">
                            TRAN.
                        </th>
                        <th title="Unidades del inventario del producto"
                            style="background-color: #b7b7b7;
                                   vertical-align:middle;">
                            INV.
                        </th>
                        <th title="Costo del producto"
                            style="background-color: #b7b7b7;
                                   vertical-align:middle;">
                            COSTO
                        </th>
                        <th title="Venta Media Diaria"
                            style="background-color: #b7b7b7;
                                   vertical-align:middle;">
                            VMD
                        </th>
                    @else
                        <th style="display:none;">TRAN.</th>
                        <th style="display:none;">INV.</th>
                        <th style="display:none;">COSTO</th>
                        <th style="display:none;">VMD</th>
                    @endif

                    <th style="display:none; vertical-align:middle;"
                        title="Código del producto del proveedor">
                        CODIGO
                    </th>
                    <th style="vertical-align:middle;"
                        title="Código del Proveedor">
                        PROVEEDOR&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </th>
                    <th style="vertical-align:middle;"
                        title="Precio del producto">
                        PRECIO
                    </th>
                    <th style="vertical-align:middle;"
                        title="Impuesto al valor agregado">
                        IVA
                    </th>
                    <th style="vertical-align:middle;"
                        title="Descuento adicional del producto">
                        DA
                    </th>
                    <th style="vertical-align:middle;"
                        title="Descuento pre-empaque del producto">
                        DP
                    </th>
                    <th style="vertical-align:middle;"
                        title="Descuento internet">
                        DI
                    </th>
                    <th style="vertical-align:middle;"
                        title="Descuento comercial">
                        DC
                    </th>
                    <th style="vertical-align:middle;"
                        title="Descuento pronto pago">
                        PP
                    </th>
                    <th style="vertical-align:middle;"
                        title="Precio neto">
                        NETO
                    </th>
                    <th style="vertical-align:middle;"
                        title="Subtoatl del producto">
                        SUBTOTAL
                    </th>
                    <th style="display: none;">ITEM</th>
                    <th style="vertical-align:middle;"
                        title="Monto del ahorro">
                        AHORRO
                    </th>
                </thead>
                @foreach ($tabla2 as $t)
                    @if ($t->codprove == $tpactivo || $tpactivo == "MAESTRO" )
                        @php
                        $costo = 0;
                        $vmd = 0;
                        $cant = 0;
                        $confprov = LeerProve($t->codprove);
                        $transito = verificarProdTransito($t->barra,  "", "");
                        $invent = verificarProdInventario($t->barra,  "");
                        if (!is_null($invent)) {
                            $costo = $invent->costo/$factorInv;
                            $vmd = $invent->vmd;
                            $cant = $invent->cantidad;
                        }
                        $marca = LeerProdcaract($t->barra, 'marca', 'POR DEFINIR');
                        @endphp
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
                                    @if ($t->ranking == 1)
                                    <i title = "PRODUCTO ES LA PRIMERA OPCION"
                                        class="fa fa-thumbs-o-up"
                                        aria-hidden="true">
                                    </i>
                                    @endif
                                </td>
                            @else
                                <td>
                                    {{$loop->iteration}}
                                    @if ($t->ranking == 1)
                                    <i title = "PRODUCTO ES LA PRIMERA OPCION"
                                        class="fa fa-thumbs-o-up"
                                        aria-hidden="true">
                                    </i>
                                    @endif
                                </td>
                            @endif

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

                            <td>
                                <span class="input-group-addon"
                                    style="margin: 0px; width: 140px;">
                                    <div class="col-xs-12 input-group input-group-sm"
                                        style="margin: 0px; width: 140px;">
                                        <input type="number"
                                            style="text-align: center; color: #000000; width: 60px;"
                                            id="idPedir-{{$t->item}}"
                                            value="{{number_format($t->cantidad, 0, '.', ',')}}"
                                            class="form-control"
                                            @if ($t->estado == "RECIBIDO")
                                                readonly
                                            @endif
                                        >
                                        <button

                                        @if ($t->estado == "RECIBIDO") disabled @endif

                                        type="button" class="btn btn-pedido BtnModificar" id="idModificar-{{$t->item}}" data-toggle="tooltip" title="Modificar cantidad">
                                            <span

                                                @if ($t->estado == "RECIBIDO") style="color: #000000;" @endif

                                                class="fa fa-check" id="idModificar-{{$t->item}}" aria-hidden="true" >
                                            </span>
                                            <a href="" data-target="#modal-delete-{{$t->item}}"    data-toggle="modal">
                                                <button

                                                    @if ($t->estado == "RECIBIDO")
                                                        disabled style="color: #000000;"
                                                    @endif

                                                    class="btn btn-pedido fa fa-trash-o"  data-toggle="tooltip"
                                                    title="Eliminar producto">
                                                </button>
                                            </a>
                                        </button>
                                    </div>
                                </span>
                                <div style="background-color: {{$confprov->backcolor}};
                                    color: {{$confprov->forecolor}};
                                    width: 165px;
                                    height: 35px;"
                                    title = "CODIGO DEL PROVEEDOR">
                                    <img style="width: 20px;
                                        height: 20px;
                                        margin-left: 10px;
                                        margin-top: 6px;"
                                        src="{{$rutalogoprov.$confprov->rutalogo1}}"
                                        alt="icompras360">
                                        <span style="vertical-align:middle;">
                                        &nbsp;{{$t->codprove}}<br>
                                        </span>
                                </div>
                            </td>

                            <td style="padding-top: 10px;
                                text-align: center;
                                vertical-align: middle;">
                                    <input name='destino[]'
                                        class="case"
                                        type="checkbox"
                                        onclick='tdclick(event)'
                                        id='checkbox_{{$t->item}}'
                                        value = "{{$t->marcado}}"
                                        @if ($t->marcado == 1) checked @endif />
                            </td>

                            <td>
                                <b>{{$t->desprod}}</b><br>
                                @if ($t->dcredito > 0)
                                    <span style="font-size: 10px;">
                                        DIAS:
                                    </span>
                                    <span style="border-radius: 5px;
                                        font-size: 16px;
                                        text-align: center;
                                        padding: 2px;
                                        width: 70px;
                                        color: white;
                                        background-color: #26328C;
                                        margin-right: 4px;"
                                        title="DIAS DE CREDITO: {{$t->dcredito}}">
                                        {{$t->dcredito}}
                                    </span><br>
                                @endif
                                <spa title="RANKING DEL PRODUCTO CON RESPECTO AL PROVEEDOR">
                                    RNK: {{$t->ranking}}
                                </span>
                            </td>

                            @if (Auth::user()->versionLight == 0)
                                <td align="right"
                                    title = "TRANSITO"
                                    style="background-color: #FEE3CB;" >
                                    {{number_format($transito, 0, '.', ',')}}
                                </td>

                                <td align="right"
                                    style="background-color: #FEE3CB;"
                                    title = "INVENTARIO">
                                    {{number_format($cant, 0, '.', ',')}}
                                </td>
                                <td align="right"
                                    style="background-color: #FEE3CB;"
                                    title = "COSTO">
                                    {{number_format($costo, 2, '.', ',')}}
                                </td>
                                <td align="right"
                                    style="background-color: #FEE3CB;"
                                    title = "VMD">
                                    {{number_format($vmd, 4, '.', ',')}}
                                </td>
                            @else
                                <td style="display:none;">TRAN.</td>
                                <td style="display:none;">INV.</td>
                                <td style="display:none;">COSTO</td>
                                <td style="display:none;">VMD</td>
                            @endif

                            <td style="display:none;
                                background-color: {{$confprov->backcolor}};
                                color: {{$confprov->forecolor}}"
                                title = "CODIGO DEL PRODUCTO">
                                {{$t->codprod}}
                            </td>

                            <td style="background-color: {{$confprov->backcolor}};
                                color: {{$confprov->forecolor}};"
                                title = "DATOS DEL PROVEEDOR">

                                <span title="CODIGO DE BARRA">
                                    <i class="fa fa-barcode">
                                        {{$t->barra}}
                                    </i>
                                </span><br>

                                <span title="MARCA DEL PRODUCTO">
                                    <i class="fa fa-shield">
                                        {{$marca}}
                                    </i>
                                </span><br>

                                <span title="CODIGO DEL PRODUCTO">
                                    <i class="fa fa-cube">
                                        {{$t->codprod}}
                                    </i>
                                </span>
                            </td>

                            <td style="background-color: {{$confprov->backcolor}};
                                color: {{$confprov->forecolor}};"
                                align="right"
                                title = "PRECIO DEL PRODUCTO">
                                {{number_format($t->precio/RetornaFactorCambiario($t->codprove, $moneda), 2, '.', ',')}}
                            </td>

                            <td style="background-color: {{$confprov->backcolor}};
                                color: {{$confprov->forecolor}};"
                                align="right"
                                title = "IVA DEL PRODUCTO">
                                {{number_format($t->iva, 2, '.', ',')}}
                            </td>
                            @if ($t->da > 0)
                                <td style="background-color: {{$confprov->backcolor}};
                                    color: {{$confprov->forecolor}};"
                                    align="right"
                                    title = "DESCUENTO ADICIONAL">
                                    <b>{{number_format($t->da, 2, '.', ',')}}</b>
                                </td>
                            @else
                                <td style="background-color: {{$confprov->backcolor}};
                                    color: {{$confprov->forecolor}};"
                                    align="right"
                                    title = "DESCUENTO ADICIONAL">
                                    {{number_format($t->da, 2, '.', ',')}}
                                </td>
                            @endif
                            @if ($t->dp > 0)
                                <td style="background-color: {{$confprov->backcolor}};
                                    color: {{$confprov->forecolor}};"
                                    align="right"
                                    title = "DESCUENTO PRE-EMPAQUE">
                                    <b>{{number_format($t->dp, 2, '.', ',')}}</b>
                                </td>
                            @else
                                <td style="background-color: {{$confprov->backcolor}};
                                    color: {{$confprov->forecolor}};"
                                    align="right"
                                    title = "DESCUENTO PRE-EMPAQUE">
                                    {{number_format($t->dp, 2, '.', ',')}}
                                </td>
                            @endif
                            @if ($t->di > 0)
                                <td style="background-color: {{$confprov->backcolor}};
                                    color: {{$confprov->forecolor}};"
                                    align="right"
                                    title = "DESCUENTO INTERNET">
                                    <b>{{number_format($t->di, 2, '.', ',')}}</b>
                                </td>
                            @else
                                <td style="background-color: {{$confprov->backcolor}};
                                    color: {{$confprov->forecolor}};"
                                    align="right"
                                    title = "DESCUENTO INTERNET">
                                    {{number_format($t->di, 2, '.', ',')}}
                                </td>
                            @endif
                            @if ($t->dc > 0)
                                <td style="background-color: {{$confprov->backcolor}};
                                    color: {{$confprov->forecolor}};"
                                    align="right"
                                    title = "DESCUENTO COMERCIAL">
                                    <b>{{number_format($t->dc, 2, '.', ',')}}</b>
                                </td>
                            @else
                                <td style="background-color: {{$confprov->backcolor}};
                                    color: {{$confprov->forecolor}};"
                                    align="right"
                                    title = "DESCUENTO COMERCIAL">
                                    {{number_format($t->dc, 2, '.', ',')}}
                                </td>
                            @endif
                            @if ($t->pp > 0)
                                <td style="background-color: {{$confprov->backcolor}};
                                    color: {{$confprov->forecolor}};"
                                    align="right"
                                    title = "DESCUENTO PRONTO PAGO">
                                    <b>{{number_format($t->pp, 2, '.', ',')}}</b>
                                </td>
                            @else
                                <td style="background-color: {{$confprov->backcolor}};
                                    color: {{$confprov->forecolor}};"
                                    align="right"
                                    title = "DESCUENTO PRONTO PAGO">
                                    {{number_format($t->pp, 2, '.', ',')}}
                                </td>
                            @endif
                            <td style="background-color: {{$confprov->backcolor}};
                                color: {{$confprov->forecolor}};"
                                align="right"
                                title = "PRECIO NETO">
                                {{number_format($t->neto/RetornaFactorCambiario($t->codprove, $moneda), 2, '.', ',')}}
                            </td>
                            <td style="background-color: {{$confprov->backcolor}};
                                color: {{$confprov->forecolor}};"
                                align="right"
                                title = "SUBTOTAL DEL PRODUCTO">
                                {{number_format($t->subtotal/RetornaFactorCambiario($t->codprove, $moneda), 2, '.', ',')}}
                            </td>
                            <td style="display: none;">
                                {{$t->item}}
                            </td>
                            <td style="background-color: {{$confprov->backcolor}};
                                color: {{$confprov->forecolor}};"
                                align="right"
                                title = "MONTO AHORRO DEL PRODUCTO">
                                {{number_format(($t->ahorro*$t->cantidad)/RetornaFactorCambiario($t->codprove, $moneda), 2, '.', ',')}}
                            </td>
                        </tr>
                        @include('isacom.pedido.deleprod')
                    @endif
                @endforeach
                @include('isacom.pedido.deleteAll')
                <tr>
                    @if (Auth::user()->versionLight == 1)
                        <th colspan="14" >
                    @else
                        <th colspan="18" >
                    @endif
                    <th style="vertical-align:middle;
                        background-color: #b7b7b7;
                        text-align: right;"
                        id="idtotped"
                        title="Monto en total del pedido">
                    </th>
                    <th style="display: none;"></th>
                    <th style="vertical-align:middle;
                        background-color: #b7b7b7;
                        text-align: right;"
                        id="idahoped"
                        title="Monto en ahorro del pedido">
                    </th>
                </tr>
            </table>
        </div>
    </div>
</div>

@if ($contItem == 0)
    <div class="row">
        <center><h2>Carro de compra vacio</h2></center>
        <br><br><br><br><br><br><br>
    </div>
@endif

@push ('scripts')
<script>
$('#subtitulo').text('{{$subtitulo}}');
window.onload = function() {
    if ($(".case").length == $(".case:checked").length) {
        $("#selectall").prop("checked", true);
    } else {
        $("#selectall").prop("checked", false);
    }

    $('.BtnModificar').on('click',function(e) {
        var id = e.target.id.split('-');
        var item= id[1];
        var pedir = $('#idPedir-'+item).val();
        var idpedido = '{{$tabla->id}}';
        if (parseInt(pedir) <= 0) {
            alert("CANTIDAD A PEDIR NO PUEDE SER MENOR O IGUAL CERO");
            $('#idPedir-'+item).val(pedir);
        } else {
            $.ajax({
                type:'POST',
                url:'../modificar',
                dataType: 'json',
                encode  : true,
                data: {item:item, pedir:pedir, idpedido:idpedido },
                success:function(data){
                    if (data.msg != "") {
                        alert(data.msg);
                        $('#idPedir-'+item).val(data.pedirOri);
                    }
                    window.location.reload();
                }
            });
        }
    });
    refrescar();
}
function refrescar() {
    var tableReg = document.getElementById('myTable');
    var subtotal = 0.00;
    var ahorro = 0.00;
    for (var i = 1; i < tableReg.rows.length-1; i++) {
        cellsOfRow = tableReg.rows[i].getElementsByTagName('td');
        subtotal += parseFloat(cellsOfRow[19].innerHTML.replace(/,/g, ''));
        ahorro += parseFloat(cellsOfRow[21].innerHTML.replace(/,/g, ''));
    }
    $("#idtotped").text(number_format(subtotal, 2, '.', ','));
    $("#idahoped").text(number_format(ahorro, 2, '.', ','));
}
$("#selectall").on("click", function(e) {
    var id = e.target.id.split('_');
    var item = id[1];
    var marcar;
    if ($(".case").length == $(".case:checked").length) {
        marcar = "0";
    } else {
        marcar = "1";
    }
    $(".case").prop("checked", this.checked);
    try {
        var jqxhr;
        var resp;
        var table = document.getElementById("myTable");
        var rows = table.getElementsByTagName('tr');
        for (var ica = 1; ica < rows.length; ica++) {
            var cols = rows[ica].getElementsByTagName('td');
            var item = cols[20].innerHTML;
            //alert(item);
            jqxhr = $.ajax({
              type:'POST',
              url:'../marcaritem',
              dataType: 'json',
              encode  : true,
              data: {item : item, marcar : marcar },
              success:function(data) {
                resp = data.msg;
              }
            });
        }
        jqxhr.always(function() {
            if (resp != "")
                alert(resp);
        });
    } catch(e) {
    }
});
$(".case").on("click", function(e) {
    var id = e.target.id.split('_');
    var item = id[1];
    $.ajax({
      type:'POST',
      url:'../marcaritem',
      dataType: 'json',
      encode  : true,
      data: {item : item, marcar : '' },
      success:function(data) {
        if (data.msg != "") {
            alert(data.msg);
        }
      }
    });
    if ($(".case").length == $(".case:checked").length) {
        $("#selectall").prop("checked", true);
    } else {
        $("#selectall").prop("checked", false);
    }
});
</script>
@endpush

@endsection
