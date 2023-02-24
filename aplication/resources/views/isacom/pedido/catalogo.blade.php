@extends ('layouts.menu')
@section ('contenido')
@php
  $moneda = Session::get('moneda', 'BSS');
  $factorInv = RetornaFactorCambiario('', $moneda);
  $rutalogoprov = 'http://isaweb.isbsistemas.com/public/storage/prov/';
@endphp 
 
<input hidden id="idaccion" type="text" value="{{$accion}}">
<!-- BOTONES PROVEEDORES-->
<div class="input-group mb-3">
    <div class="input-group-prepend" id="button-addon3">

        <a href="{{URL::action('PedidoController@catalogo','C-MAESTRO')}}">
            <button style="width: 153px; 
                height: 32px; 
                color: #000000; 
                border: #b7b7b7;  
                background-color: #b7b7b7;
                border-radius: 15px;" 
                data-toggle='tooltip' 
                title='VER CATALOGO MAESTRO'
                class='btn btn-outline-secondary' 
                type="button">


                @if ($tpactivo=="MAESTRO" && $tipo != 'TOP') 
                    <b>
                        <i class="fa fa-check"></i>
                        <u>MAESTRO</u>
                    </b>
                @else
                    <img style="width: 20px; height: 20px;" 
                    src="{{$rutalogoprov.'icompras360.png'}}" alt="icompras360">
                    &nbsp;MAESTRO
                @endif
            </button>
        </a>            
    
        @foreach ($provs as $prov)
        @php 
            $confprov = LeerProve($prov->codprove); 
            if (is_null($confprov))
                continue;
        @endphp
 
        <a href="{{URL::action('PedidoController@catalogo',$tipo.'-'.$confprov->codprove)}}">
            <button style="width: 153px; height: 32px; 
                color:{{$confprov->forecolor}}; 
                border: {{$confprov->backcolor}};  
                background-color: {{$confprov->backcolor}};
                border-radius: 15px;" 
                class="btn btn-outline-secondary" type="button" data-toggle="tooltip" 
                title="&nbsp;&nbsp;&nbsp;&nbsp; Ir al proveedor &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &#10 {{strtoupper($confprov->nombre)}}">
                @if ($tpactivo==$confprov->codprove) 
                    <b>
                        <i class="fa fa-check"></i>
                        <u>{{$confprov->descripcion}}</u>
                    </b>
                @else
                    <img style="width: 20px; height: 20px;" 
                    src="{{$rutalogoprov.$confprov->rutalogo1}}" alt="icompras360">
                    &nbsp;{{$confprov->descripcion}}
                @endif
            </button>
        </a>
 
        @endforeach
    </div>
</div>

<!-- BOTONES BUSCAR, CATALOGO, ENTRADAS, OFERTAS, MOLECULAS -->
<div class="btn-toolbar" role="toolbar" style="margin-top: 12px; margin-bottom: 3px;">
    <div class="btn-group" role="group" style="width: 100%;">
        <!-- BOTON BUSCAR/CATALOGO/ENVIAR/ELIMINAR -->
        @include('isacom.pedido.catasearch')
        <a href="{{URL::action('PedidoController@catalogo','C-'.$tpactivo)}}">
            <button style="width: 90px; height: 34px; border-radius: 5px;" 
                type="button" 
                data-toggle="tooltip" 
                title="Ver catálogo de productos" 
            @if ($tipo=='C')
                class="btn-catalogoX"> 
                <i class="fa fa-check"></i> Catálogo
            @else 
                class="btn-catalogo"> Catálogo
            @endif
            </button>
        </a>
        <!-- VER ENTRADAS -->
        <a href="{{URL::action('PedidoController@catalogo','E-'.$tpactivo)}}">
            <button style="width: 90px; height: 34px; border-radius: 5px;" 
                type="button" 
                data-toggle="tooltip" 
                title="Ver ultimas entradas" 
            @if ($tipo=='E')
                class="btn-catalogoX">
                <i class="fa fa-check"></i> Entradas
            @else 
                class="btn-catalogo"> Entradas
            @endif
            </button>
        </a>
        <!-- VER OFERTAS -->
        <a href="{{URL::action('PedidoController@catalogo','O-'.$tpactivo)}}">
            <button style="width: 90px; height: 34px; border-radius: 5px;" 
                type="button" 
                data-toggle="tooltip" 
                title="Ver ofertas del día" 
            @if ($tipo=='O')
                class="btn-catalogoX">
                <i class="fa fa-check"></i> Ofertas
            @else 
                class="btn-catalogo"> Ofertas
            @endif
            </button>
        </a>
        <!-- RANK1 -->
        <a href="{{URL::action('PedidoController@catalogo','R-'.$tpactivo)}}">
            <button style="width: 90px; height: 34px; border-radius: 5px;" 
                type="button" 
                data-toggle="tooltip" 
                title="Productos rank-1" 
            @if ($tipo=='R')
                class="btn-catalogoX">
                <i class="fa fa-check"></i> Rnk1
            @else 
                class="btn-catalogo"> Rnk1
            @endif
            </button>
        </a>
        <!-- MOLECULAS -->
        @if (Auth::user()->botonMolecula == 1)
        <a href="{{URL::action('PedidoController@catalogo','M-'.$tpactivo)}}">
            <button style="width: 90px; height: 34px; border-radius: 5px;" 
                type="button" 
                data-toggle="tooltip" 
                title="Productos por moleculas"  
            @if ($tipo=='M')
                class="btn-catalogoX">
                <i class="fa fa-check"></i> Moleculas
            @else 
                class="btn-catalogo"> Moleculas
            @endif
            </button>
        </a>
        @endif
    </div>
</div>

@if ($tipo == "TOP")
    <!-- TOP 20 -->
    <div class="row"> 
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-condensed table-hover"
                    id="idTabla">
                    <thead class="colorTitulo">
                        <th style="vertical-align:middle;">#</th>
                        <th style="width: 100px;">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;IMAGEN&nbsp;&nbsp;&nbsp;&nbsp;&nbsp1;&nbsp;&nbsp;
                        </th>
                        <th title="Cantidad a pedir" 
                            style="width: 120px;">
                            PEDIR
                        </th>
                        <th>PRODUCTOS</th>
                        <th title="Código de referencias">
                            REFERENCIAS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        </th>
                        <th style="display:none;" >
                            REFERENCIA
                        </th>
                        <th title="Unidades en transito">TRAN.</th>
                        @if (Auth::user()->versionLight == 0)
                            <th title="Inventario del cliente">INV.</th>
                            <th title="Costo del producto">COSTO</th>
                            <th title="Venta media diaria">
                                VMD&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            </th>
                            <th title="Dias de Inventario y Sugerido de Unidades x Dias">
                                DIAS/SUG&nbsp;&nbsp;&nbsp;
                            </th>
                            <th style="display:none;">TP</th>
                            <th style="display:none;">RNK1</th>
                        @else
                            <th style="display:none;">INV.</th>
                            <th style="display:none;">COSTO</th>
                            <th style="display:none;">VMD</th>
                            <th style="display:none;">DIAS</th>
                            <th style="display:none;">TP</th>
                            <th style="display:none;">RNK1</th>
                        @endif
                        <th title="Inventario consolidado de los proveedores">CONSOL.</th>
                    </thead>
                    @php
                        $iFila = -1;
                        $xFila = 0; 
                    @endphp
                    @foreach ($catalogo as $t)
                    @php
                        $marcado = 0;
                        $invent = 0;
                        $invConsol = 0;
                        $vmd = 0;
                        $codprod = "";
                        $sug15 = 0;
                        $sug30 = 0;
                        $sug60 = 0;
                        $costo = 0.00;
                        $dias = 0;
                        $min = 0;
                        $max = 0;
                        $cat = DB::table('tpmaestra')
                        ->where('barra','=',$t->barra)
                        ->first();
                        if ($cat) {
                            $dataprod = obtenerDataTpmaestra($cat, $provs, 0);
                        }
                        if (is_null($dataprod))
                            continue;
                        $tprnk1 = $dataprod['tprnk1'];
                        $mpp = $dataprod['mpp'];
                        $invConsol = $dataprod['invConsol'];
                        $mayorInv = $dataprod['mayorInv'];
                        $proves = $dataprod['arrayProv'];
                        $arrayRnk = $dataprod['arrayRnk'];

                        $transito = verificarProdTransito($t->barra,  "", "");
                        $inv = verificarProdInventario($t->barra, "");
                        if ($inv != "") {
                            $invent = $inv->cantidad;
                            $vmd = $inv->vmd;
                            if ($vmd > 0)
                                $dias = $invent/$vmd;
                            
                            $codprod = $inv->codprod;
                            $desprod = $inv->desprod;
                            $costo = $inv->costo/$factorInv;
                            $sug15 = ($vmd*15)-($invent + $transito);
                            $sug30 = ($vmd*30)-($invent + $transito);
                            $sug60 = ($vmd*60)-($invent + $transito);
                            if ($sug15 < 0)
                                $sug15 = 0;
                            if ($sug30 < 0)
                                $sug30 = 0;
                            if ($sug60 < 0)
                                $sug60 = 0;
                            $minmax = LeerMinMax($codcli, $codprod);
                            $min = $minmax["min"];
                            $max = $minmax["max"];
                        }
                        $respDias = analisisSobreStock($dias, $min, $max);
                        $cat = DB::table('tpmaestra')
                        ->where('barra','=',$t->barra)
                        ->first();
                        if ($cat) {
                            $desprod = $cat->desprod;
                            $dataprod = obtenerDataTpmaestra($cat, $provs, 0);
                            if (!is_null($dataprod)) {
                                $invConsol = $dataprod['invConsol'];
                            }
                        }
                        if (Auth::user()->versionLight == 0) {
                            if ($invent <= 0 && $invConsol > 0) {
                                $marcado = 1;
                            }
                        }
                        $iFila++;
                        $xFila++;
                    @endphp
                    <tr>
                        <td style="font-size:20px;">
                            <span
                            @if ($marcado == 1) 
                            class="label label-danger"
                            style="background-color: red; 
                            color: #ffffff; 
                            border-radius: 50%;" 
                            title = "PRODUCTO DEBE SER PEDIDO DE INMEDIATO"  
                            @endif>
                            {{$xFila}}
                            </span>
                        </td>

                        <td style="width: 100px; ">
                            <div align="center" >
                                <a href="{{URL::action('PedidoController@verprod',$t->barra)}}">
                                    <img src="http://isaweb.isbsistemas.com/public/storage/prod/{{NombreImagen($t->barra)}}" 
                                    class="img-responsive" 
                                    alt="icompras360" 
                                    width="100%" 
                                    height="100%" 
                                    style="border: 2px solid #D2D6DE;"
                                    oncontextmenu="return false">
                                </a>
                            </div>
                        </td>

                        <td>
                            <!-- AGREGAR A CARRO DE COMPRA -->
                            <div >
                                <span class="input-group-btn">
                                    <div class="col-xs-12 input-group" 
                                        id="idAgregar-{{$iFila}}" >
                                        <input type="number" 
                                            style="text-align: center; 
                                            color: #000000; 
                                            width: 70px;" 
                                            id="idPedir-{{$iFila}}" 
                                            value=""
                                            class="form-control" 
                                            @if (Auth::user()->userAdmin == 0)
                                                @if ($respDias['color'] == '#CCBB00' || 
                                                    $invConsol <= 0)
                                                    readonly=""
                                                @endif
                                            @endif 
                                        >
                                        <button type="button" 
                                            @if (Auth::user()->userAdmin == 0)
                                                @if ($respDias['color'] == '#CCBB00')
                                                    disabled="" 
                                                @endif
                                            @endif 
                                            class="BtnAgregar btn btn-pedido

                                            @if (VerificarCarrito($t->barra, 'N'))
                                            colorResaltado
                                            @endif

                                            " data-toggle="tooltip" title="Agregar al carrito"

                                            id="idBtnAgregar-{{$iFila}}" >
                                            <span class="fa fa-cart-plus" 
                                                id="idAgregar-{{$iFila}}" 
                                                aria-hidden="true">
                                            </span>
                                        </button>

                                    </div>
                                    <div  style="margin-left: 0px; 
                                          margin-right: 0px; 
                                          width: 100px;">
                                        @php
                                            $cont = count($arrayRnk);
                                        @endphp
                                        @if ($cont == 1)
                                            @php
                                                $confprov = LeerProve($arrayRnk[0]['codprove']);
                                            @endphp
                                            <span style="width: 110px;">

                                                <img alt="icompras360"
                                                style="width: 28px; 
                                                float: left; 
                                                background-color: #F0F0F0; 
                                                margin-left: 2px;
                                                margin-top: 4px;
                                                "
                                                src="{{$rutalogoprov.$confprov->rutalogo1}}"><span 
                                                    style="background-color: #F0F0F0;
                                                    width: 110px;"
                                                    class="form-control" >
                                                    &nbsp;{{$tprnk1}}
                                                </span>

                                                <input id="idProv-{{$iFila}}" 
                                                    value="{{$tprnk1}}" 
                                                    style="background-color: #F0F0F0;
                                                    width: 80px;"
                                                    type="hidden"
                                                    readonly="" 
                                                    class="form-control" >
                                            </span>
                                        @else
                                            <select id="idProv-{{$iFila}}" 
                                                class="form-control"  
                                                style="width: 110px; ">
                                                @for ($x=0; $x < $cont; $x++) 
                                                    @if ($tprnk1==$arrayRnk[$x]['codprove']) 
                                                        <option selected
                        title="PRECIO: {{number_format($arrayRnk[$x]['liquida'], 2, '.', ',')}}"  
                        value="{{ $arrayRnk[$x]['codprove'] }}">   
                        {{ $arrayRnk[$x]['codprove'] }}
                                                        </option>
                                                    @else 
                                                        <option 
                        title="PRECIO: {{number_format($arrayRnk[$x]['liquida'], 2, '.', ',')}}  DIF: {{number_format($arrayRnk[$x]['liquida'] - $arrayRnk[0]['liquida'], 2, '.', ',')}}"  
                        value="{{ $arrayRnk[$x]['codprove'] }}">
                        {{ $arrayRnk[$x]['codprove'] }}
                                                        </option>
                                                    @endif
                                                @endfor
                                            </select>
                                        @endif
                                    </div>
                                </span>
                            </div>
                            <span style="text-align: right; 
                                background-color: #F0F0F0;
                                color: #000000; 
                                width: 110px;
                                height: 31px;"
                                title="MEJOR PRECIO LIQUIDA" 
                                class="form-control">
                                <i class="fa fa-thumbs-o-up" aria-hidden="true"></i>
                                {{number_format($arrayRnk[0]['liquida'], 2, '.', ',')}}
                            </span>
                        </td>

                        <td>
                            <b>{{$desprod}}</b>
                            <div style="margin-top: 5px;
                                font-size: 14px;
                                padding: 1px; "                             
                                title="UNIDADES VENDIDAS EN EL ICOMPRAS">
                                <b>UND. VENDIDAS: {{number_format($t->total, 0, '.', ',')}}<b>
                            </div>
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
                            </span> 
                        </td>
                        <td style="display:none;">
                            {{$t->barra}}
                        </td>
                        <td align="right"
                            style="background-color: #FEE3CB;">
                            {{number_format($transito, 0, '.', ',')}}
                        </td>

                        @if (Auth::user()->versionLight == 0)
                            <td align="right"
                                style="background-color: #FEE3CB;">
                                {{number_format($invent, 0, '.', ',')}}
                            </td>
                            <td align="right" 
                                style="background-color: #FEE3CB;">
                                {{number_format($costo, 2, '.', ',')}}
                            </td>
                            <td align="right"
                                style="background-color: #FEE3CB;">
                                {{number_format($vmd, 4, '.', ',')}}<br>
                                <span style="background-color: #FEE3CB;
                                    color: black;" 
                                    title="DIAS MINIMO x PRODUCTO" >
                                    MIN-> {{$min}}
                                </span><br>
                                <span style="background-color: #FEE3CB;
                                    color: black;" 
                                    title="DIAS MAXIMO x PRODUCTO" >
                                    MAX-> {{$max}}
                                </span><br>
                            </td>

                            <td style="display:none;">{{$tprnk1}}</td>
                            <td style="display:none;">{{$tprnk1}}</td>

                            <td align="right"
                                style="background-color: #FEE3CB;">
                                {{number_format($dias, 0, '.', ',')}}

                                <br>
                                <span style="background-color: #FEE3CB;
                                color: black;" 
                                title="SUGERIDO PARA 15 DIAS" >
                                15-> {{number_format($sug15, 0, '.', ',')}}
                                </span>
                                <br>
                                <span style="background-color: #FEE3CB;
                                color: black;" 
                                title="SUGERIDO PARA 30 DIAS" >
                                30-> {{number_format($sug30, 0, '.', ',')}}
                                </span>
                                <br>
                                <span style="background-color: #FEE3CB;
                                color: black;"
                                title="SUGERIDO PARA 60 DIAS" >
                                60-> {{number_format($sug60, 0, '.', ',')}}
                                </span>

                            </td>
                        @else
                            <td style="display:none;">
                                {{number_format($invent, 0, '.', ',')}}
                            </td>
                            <td style="display:none;">
                                {{number_format($costo, 2, '.', ',')}}
                            </td>
                            <td style="display:none;">
                                {{number_format($vmd, 4, '.', ',')}}
                            </td>
                            <td style="display:none;">{{$tprnk1}}</td>
                            <td style="display:none;">{{$tprnk1}}</td>
                            <td style="display:none;">
                                {{number_format($dias, 0, '.', ',')}}
                            </td>
                        @endif
                        <td align="right"
                            style="background-color: #FCD0C7;">
                            {{number_format($invConsol, 0, '.', ',')}}
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@else
    <!-- TABLA CATALOGO -->
    <div class="row" style="margin-top: 5px;">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="table-responsive">
                <table id="idTabla" 
                    style="margin-bottom: 0px;" 
                    class="table table-striped table-bordered table-condensed table-hover">

                    <thead style="background-color: #b7b7b7;">
                        @if ($tpactivo == 'MAESTRO')
                            @if ($tipo == "C")
                                @if (Auth::user()->versionLight == 0)
                                    <th colspan="5"
                                        style="vertical-align:middle;" >
                                        <center>
                                            <img style="width: 20px; height: 20px;" 
                                            src="{{$rutalogoprov.'icompras360.png'}}" 
                                            alt="icompras360" >
                                            CATALOGO MAESTRO DE PRODUCTOS
                                        </center>
                                    </th>
                                    <th colspan="5" 
                                        style="vertical-align:middle; 
                                        background-color: #FEE3CB;"> 
                                        <center>
                                            CLIENTE
                                        </center>
                                    </th>
                                    <th style="vertical-align:middle; 
                                        background-color: #FCD0C7;"> 
                                        <center>
                                           PROVEEDOR
                                        </center>
                                    </th>
                                @else
                                    <th colspan="6" 
                                        style="vertical-align:middle;">
                                        <center>
                                            <img style="width: 20px; height: 20px;" 
                                            src="{{$rutalogoprov.'icompras360.png'}}" 
                                            alt="icompras360">
                                            CATALOGO MAESTRO DE PRODUCTOS
                                        </center>
                                    </th>
                                    <th style="background-color: #FCD0C7;
                                        vertical-align:middle;"> 
                                        <center>
                                           PROVEEDOR
                                        </center>
                                    </th>
                                @endif
                                @foreach ($provs as $prov)

                                    @php 
                                    if (!VerificaCampoTabla('tpmaestra', $prov->codprove))
                                        continue;
                                    $confprov = LeerProve($prov->codprove); 
                                    if (is_null($confprov))
                                        continue;

                                    $maeclieprove = LeerClieProve($prov->codprove, "");
                                    $fechaHoy = trim(date("Y-m-d"));
                                    $fechacat = trim(date('Y-m-d', strtotime($confprov->fechacata)));
                                    @endphp
                                    
                                    <th colspan="2" 
                                        style="background-color: {{$confprov->backcolor}}; 
                                        color: {{$confprov->forecolor}}; 
                                        word-wrap: break-word; ">
                                        <a href="{{URL::action('ProveedorController@verprov',$prov->codprove)}}">
                                            <button type="button" 
                                                data-toggle="tooltip" 
                                                title="{{strtoupper($confprov->nombre)}} &#10 ({{ date('d-m-Y H:i', strtotime($confprov->fechacata) ) }})" 
                                                style="float: left; background-color: {{$confprov->backcolor}}; 
                                                @if ($fechacat != $fechaHoy)
                                                    color: red;
                                                @else
                                                    color: {{$confprov->forecolor}};
                                                @endif 
                                                border: none;">
                                                <img style="width: 20px; height: 20px;" 
                                                src="{{$rutalogoprov.$confprov->rutalogo1}}" 
                                                alt="icompras360">
                                                {{$confprov->descripcion}}
                                            </button>
                                            <span style="margin-top: 4px;
                                                margin-bottom: 4px; 
                                                float: right; 
                                                border-radius: 50%; 
                                                width: 20px;
                                                height: 20px;
                                                font-size: 12px;
                                                color: white;
                                                background-color: black;"
                                                title="DIAS DE CREDITO: {{$maeclieprove->dcredito}}">
                                                <center>
                                                    &nbsp;{{$maeclieprove->dcredito}}&nbsp;
                                                </center>
                                            </span>
                                            <div class="clearfix"></div>
                                            @if ($maeclieprove->dcme > 0)
                                                <span style="border-radius: 5px;
                                                    font-size: 12px;
                                                    padding: 2px; 
                                                    color: white;
                                                    background-color: #26328C;
                                                    margin-right: 4px;"
                                                    title="DESCUENTO COMERCIAL: {{$maeclieprove->dcme}} % ">
                                                    <span style="font-size: 9px;">DC:</span> 
                                                    {{QuitarCerosDecimales($maeclieprove->dcme)}} % 
                                                </span>
                                            @else
                                                <span style="border-radius: 5px;
                                                    font-size: 12px;
                                                    padding: 2px;">
                                                </span>
                                            @endif
                                            @if ($maeclieprove->ppme > 0)
                                                <span style="border-radius: 5px; 
                                                    font-size: 12px;
                                                    padding: 2px; 
                                                    color: white;
                                                    background-color: #26328C;
                                                    margin-right: 4px;"
                                                    title="DESCUENTO PRONTO PAGO: {{$maeclieprove->ppme}} % ">
                                                    <span style="font-size: 9px;">PP:</span>
                                                    {{QuitarCerosDecimales($maeclieprove->ppme)}} % 
                                                </span>
                                            @endif
                                            @if ($maeclieprove->di > 0)
                                                <span style="border-radius: 5px; 
                                                    font-size: 12px;
                                                    padding: 2px; 
                                                    color: white;
                                                    background-color: #26328C;
                                                    margin-right: 4px;"
                                                    title="DESCUENTO INTERNET: {{$maeclieprove->di}} %">
                                                    <span style="font-size: 9px;">DI:</span>
                                                    {{QuitarCerosDecimales($maeclieprove->di)}} % 
                                                </span>
                                            @endif
                                        </a>
                                    </th>
                    
                                @endforeach
                            @endif
                            @if ($tipo == "E")
                                @if (Auth::user()->versionLight == 0)
                                    <th colspan="5">
                                        <center>
                                            <img style="width: 20px; height: 20px;" 
                                            src="{{$rutalogoprov.'icompras360.png'}}" alt="icompras360">
                                            CATALOGO MAESTRO DE ENTRADAS
                                        </center>
                                    </th>
                                    <th colspan="5" 
                                        style="background-color: #FEE3CB;"> 
                                        <center>
                                            CLIENTE
                                        </center>
                                    </th>
                                    <th colspan="8" style="background-color: #A69FB5;"> 
                                        <center>
                                           DATOS DEL PROVEEDOR
                                        </center>
                                    </th>
                                @else
                                    <th colspan="6">
                                        <center>
                                            <img style="width: 20px; height: 20px;" 
                                            src="{{$rutalogoprov.'icompras360.png'}}" alt="icompras360">
                                            CATALOGO MAESTRO DE ENTRADAS
                                        </center>
                                    </th>
                                    <th colspan="8" style="background-color: #A69FB5;"> 
                                        <center>
                                           DATOS DEL PROVEEDOR
                                        </center>
                                    </th>
                                    
                                @endif
                            @endif
                            @if ($tipo == "O")
                                @if (Auth::user()->versionLight == 0)
                                    <th colspan="5">
                                        <center>
                                            <img style="width: 20px; height: 20px;" 
                                            src="{{$rutalogoprov.'icompras360.png'}}" alt="icompras360">
                                            CATALOGO MAESTRO DE OFERTAS
                                        </center>
                                    </th>
                                    <th colspan="5" 
                                        style="background-color: #FEE3CB;"> 
                                        <center>
                                            CLIENTE
                                        </center>
                                    </th>
                                    <th colspan="7" style="background-color: #A69FB5;"> 
                                        <center>
                                           DATOS DEL PROVEEDOR
                                        </center>
                                    </th>
                                @else
                                    <th colspan="6">
                                        <center>
                                            <img style="width: 20px; height: 20px;" 
                                            src="{{$rutalogoprov.'icompras360.png'}}" alt="icompras360">
                                            CATALOGO MAESTRO DE OFERTAS
                                        </center>
                                    </th>
                                    <th colspan="17" style="background-color: #A69FB5;">
                                        <center>
                                            DATOS DEL PROVEEDOR
                                        </center>
                                    </th>
                                @endif
                            @endif
                            @if ($tipo == "R")
                                @if (Auth::user()->versionLight == 0)
                                    <th colspan="5">
                                        <center>
                                            <img style="width: 20px; height: 20px;" 
                                            src="{{$rutalogoprov.'icompras360.png'}}" alt="icompras360">
                                            CATALOGO RNK1 DE PRODUCTOS
                                        </center>
                                    </th>
                                    <th colspan="5" 
                                        style="background-color: #FEE3CB;"> 
                                        <center>
                                            CLIENTE
                                        </center>
                                    </th>
                                    <th colspan="8" style="background-color: #A69FB5;"> 
                                        <center>
                                           DATOS DEL PROVEEDOR
                                        </center>
                                    </th>
                                @else
                                    <th colspan="6">
                                        <center>
                                            <img style="width: 20px; height: 20px;" 
                                            src="{{$rutalogoprov.'icompras360.png'}}" alt="icompras360">
                                            CATALOGO RNK1 DE PRODUCTOS
                                        </center>
                                    </th>
                                    <th colspan="8" style="background-color: #A69FB5;"> 
                                        <center>
                                           DATOS DEL PROVEEDOR
                                        </center>
                                    </th>
                                @endif
                            @endif
                            @if ($tipo == 'M') 
                                @if (Auth::user()->versionLight == 0) 
                                    <th colspan="5"> 
                                        <center>
                                        <img style="width: 20px; height: 20px;" 
                                        src="{{$rutalogoprov.'icompras360.png'}}" alt="icompras360">
                                        CATALOGO MAESTRO POR MOLECULAS
                                        </center>
                                    </th>
                                    <th colspan="5" 
                                        style="background-color: #FEE3CB;"> 
                                        <center>
                                            CLIENTE
                                        </center>
                                    </th>
                                    <th colspan="2" style="background-color: #FCD0C7;"> 
                                        <center>
                                           PROVEEDOR
                                        </center>
                                    </th>
                                @else
                                    <th colspan="6">
                                        <center>
                                        <img style="width: 20px; height: 20px;" 
                                        src="{{$rutalogoprov.'icompras360.png'}}" alt="icompras360">
                                        CATALOGO MAESTRO POR MOLECULAS
                                        </center>
                                    </th>
                                    <th colspan="2" style="background-color: #FCD0C7;"> 
                                        <center>
                                           PROVEEDOR
                                        </center>
                                    </th>
                                @endif

                                @foreach ($provs as $prov)

                                    @php 
                                    if (!VerificaCampoTabla('tpmaestra', $prov->codprove))
                                        continue;
                                    $confprov = LeerProve($prov->codprove); 
                                    if (is_null($confprov))
                                        continue;
                                    $fechaHoy = trim(date("Y-m-d"));
                                    $fechacat = trim(date('Y-m-d', strtotime($confprov->fechacata)));
                                    @endphp
                                    
                                    <th colspan="2" 
                                        style="background-color: {{$confprov->backcolor}}; 
                                        color: {{$confprov->forecolor}}; 
                                        width: 400px; 
                                        word-wrap: break-word; ">
                                        <a href="{{URL::action('ProveedorController@verprov',$prov->codprove)}}">
                                            <center>
                                                <button type="button" 
                                                    data-toggle="tooltip" 
                                                    title="{{strtoupper($confprov->nombre)}} &#10 ({{ date('d-m-Y H:i', strtotime($confprov->fechacata) ) }})" 
                                                    style="background-color: {{$confprov->backcolor}}; 
                                                    @if ($fechacat != $fechaHoy)
                                                        color: red;
                                                    @else
                                                        color: {{$confprov->forecolor}};
                                                    @endif 
                                                    border: none;">
                                                    {{$confprov->descripcion}}
                                                </button>
                                            </center>
                                        </a>
                                    </th>
                                @endforeach
                            @endif
                        @else
                            @php 
                                $confprov = LeerProve($tpactivo); 
                            @endphp
                            @if ($tipo == "C")
                                @if (Auth::user()->versionLight == 0)
                                    <th colspan="5" 
                                        style="background-color: {{$confprov->backcolor}}; 
                                        color: {{$confprov->forecolor}};">
                                        <a href="{{URL::action('ProveedorController@verprov',$tpactivo)}}">
                                        <center>
                                            <button type="button" data-toggle="tooltip" title="Ver información del proveedor &#10 {{strtoupper($confprov->nombre)}}" style="background-color: {{$confprov->backcolor}}; color: {{$confprov->forecolor}}; border: none;">
                                                <img style="width: 20px; height: 20px;" 
                                                src="{{$rutalogoprov.$confprov->rutalogo1}}" alt="icompras360">
                                                RENG: {{number_format($confprov->contprod, 0, '.', ',')}} - FECHA:  

                                                @if ( date('d-m-Y', strtotime($confprov->fechacata) ) == date('d-m-Y') )
                                                    <span>
                                                        {{date('d-m-Y H:i', strtotime($confprov->fechacata)) }} 
                                                    </span>
                                                @else
                                                    <span style="color: red;">
                                                        {{date('d-m-Y H:i', strtotime($confprov->fechacata)) }} 
                                                    </span>
                                                @endif
                                                - 
                                                @if ($provactivo->dcme > 0)
                                                    <span style="font-size: 10px;">
                                                        DC: 
                                                    </span>
                                                    <span style="border-radius: 5px; 
                                                        font-size: 14px;
                                                        text-align: center;
                                                        padding: 2px; 
                                                        width: 72px;
                                                        color: white;
                                                        background-color: #26328C;
                                                        margin-right: 4px;"
                                                        title="DESCUENTO COMERCIAL: {{$provactivo->dcme}} % ">
                                                        {{$provactivo->dcme}} % 
                                                    </span>
                                                @endif
                                                @if ($provactivo->ppme > 0)
                                                    <span style="font-size: 10px;">
                                                        PP: 
                                                    </span>
                                                    <span style="border-radius: 5px; 
                                                        font-size: 14px;
                                                        text-align: center;
                                                        padding: 2px; 
                                                        width: 72px;
                                                        color: white;
                                                        background-color: #26328C;
                                                        margin-right: 4px;"
                                                        title="DESCUENTO PRONTO PAGO: {{$provactivo->ppme}} % ">
                                                        {{$provactivo->ppme}} % 
                                                    </span>
                                                @endif
                                                @if ($provactivo->di > 0)
                                                    <span style="font-size: 10px;">
                                                        DI: 
                                                    </span>
                                                    <span style="border-radius: 5px; 
                                                        font-size: 14px;
                                                        text-align: center;
                                                        padding: 2px; 
                                                        width: 72px;
                                                        color: white;
                                                        background-color: #26328C;
                                                        margin-right: 4px;"
                                                        title="DESCUENTO INTERNET: {{$provactivo->di}} %">
                                                        {{$provactivo->di}} % 
                                                    </span>
                                                @endif
                                            </button>
                                        </center>
                                        </a>
                                    </th>
                                    <th colspan="5" style="background-color: #FEE3CB;"> 
                                        <center>
                                            CLIENTE
                                        </center>
                                    </th>
                                    <th colspan="7" 
                                        style="background-color: {{$confprov->backcolor}}; 
                                        color: {{$confprov->forecolor}};"> 
                                        <center>
                                           DATOS DEL PROVEEDOR
                                        </center>
                                    </th>
                                @else
                                    <th colspan="23" 
                                        style="background-color: {{$confprov->backcolor}}; 
                                        color: {{$confprov->forecolor}};">
                                        <a href="{{URL::action('ProveedorController@verprov',$tpactivo)}}">
                                        <center>
                                            <button type="button" data-toggle="tooltip" title="Ver información del proveedor &#10 {{strtoupper($confprov->nombre)}}" style="background-color: {{$confprov->backcolor}}; color: {{$confprov->forecolor}}; border: none;">
                                                <img style="width: 20px; height: 20px;" 
                                                src="{{$rutalogoprov.$confprov->rutalogo1}}" alt="icompras360">
                                                RENG: {{number_format($confprov->contprod, 0, '.', ',')}} - FECHA: 

                                                @if ( date('d-m-Y', strtotime($confprov->fechacata) ) == date('d-m-Y') )
                                                    <span>
                                                        {{date('d-m-Y H:i', strtotime($confprov->fechacata)) }} 
                                                    </span>
                                                @else
                                                    <span style="color: red;">
                                                        {{date('d-m-Y H:i', strtotime($confprov->fechacata)) }} 
                                                    </span>
                                                @endif
                                                - 
                                                @if ($provactivo->dcme > 0)
                                                    <span style="font-size: 10px;">
                                                        DC: 
                                                    </span>
                                                    <span style="border-radius: 5px; 
                                                        font-size: 14px;
                                                        text-align: center;
                                                        padding: 2px; 
                                                        width: 72px;
                                                        color: white;
                                                        background-color: #26328C;
                                                        margin-right: 4px;"
                                                        title="DESCUENTO COMERCIAL: {{$provactivo->dcme}} % ">
                                                        {{$provactivo->dcme}} % 
                                                    </span>
                                                @endif
                                                @if ($provactivo->ppme > 0)
                                                    <span style="font-size: 10px;">
                                                        PP: 
                                                    </span>
                                                    <span style="border-radius: 5px; 
                                                        font-size: 14px;
                                                        text-align: center;
                                                        padding: 2px; 
                                                        width: 72px;
                                                        color: white;
                                                        background-color: #26328C;
                                                        margin-right: 4px;"
                                                        title="DESCUENTO PRONTO PAGO: {{$provactivo->ppme}} % ">
                                                        {{$provactivo->ppme}} % 
                                                    </span>
                                                @endif
                                                @if ($provactivo->di > 0)
                                                    <span style="font-size: 10px;">
                                                        DI: 
                                                    </span>
                                                    <span style="border-radius: 5px; 
                                                        font-size: 14px;
                                                        text-align: center;
                                                        padding: 2px; 
                                                        width: 72px;
                                                        color: white;
                                                        background-color: #26328C;
                                                        margin-right: 4px;"
                                                        title="DESCUENTO INTERNET: {{$provactivo->di}} %">
                                                        {{$provactivo->di}} % 
                                                    </span>
                                                @endif
                                            </button>
                                        </center>
                                        </a>
                                    </th>
                                @endif
                            @endif
                            @if ($tipo == "E")
                                @if (Auth::user()->versionLight == 0)
                                    <th colspan="5" style="background-color: {{$confprov->backcolor}}; color: {{$confprov->forecolor}};">
                                        <a href="{{URL::action('ProveedorController@verprov',$tpactivo)}}">
                                        <center>
                                            <button type="button" data-toggle="tooltip" title="Ver información del proveedor &#10 {{strtoupper($confprov->nombre)}}" style="background-color: {{$confprov->backcolor}}; color: {{$confprov->forecolor}}; 
                                                border: none;">
                                                <center>
                                                    <img style="width: 20px; height: 20px;" 
                                                    src="{{$rutalogoprov.$confprov->rutalogo1}}" alt="icompras360">
                                                    FECHA: 
                                                    @if ( date('d-m-Y', strtotime($confprov->fechacata) ) == date('d-m-Y') )
                                                        <span>
                                                            {{date('d-m-Y H:i', strtotime($confprov->fechacata)) }} 
                                                        </span>
                                                    @else
                                                        <span style="color: red;">
                                                            {{date('d-m-Y H:i', strtotime($confprov->fechacata)) }} 
                                                        </span>
                                                    @endif
                                                    - 
                                                    @if ($provactivo->dcme > 0)
                                                        <span style="font-size: 10px;">
                                                            DC: 
                                                        </span>
                                                        <span style="border-radius: 5px; 
                                                            font-size: 14px;
                                                            text-align: center;
                                                            padding: 2px; 
                                                            width: 72px;
                                                            color: white;
                                                            background-color: #26328C;
                                                            margin-right: 4px;"
                                                            title="DESCUENTO COMERCIAL: {{$provactivo->dcme}} % ">
                                                            {{$provactivo->dcme}} % 
                                                        </span>
                                                    @endif
                                                    @if ($provactivo->ppme > 0)
                                                        <span style="font-size: 10px;">
                                                            PP: 
                                                        </span>
                                                        <span style="border-radius: 5px; 
                                                            font-size: 14px;
                                                            text-align: center;
                                                            padding: 2px; 
                                                            width: 72px;
                                                            color: white;
                                                            background-color: #26328C;
                                                            margin-right: 4px;"
                                                            title="DESCUENTO PRONTO PAGO: {{$provactivo->ppme}} % ">
                                                            {{$provactivo->ppme}} % 
                                                        </span>
                                                    @endif
                                                    @if ($provactivo->di > 0)
                                                        <span style="font-size: 10px;">
                                                            DI: 
                                                        </span>
                                                        <span style="border-radius: 5px; 
                                                            font-size: 14px;
                                                            text-align: center;
                                                            padding: 2px; 
                                                            width: 72px;
                                                            color: white;
                                                            background-color: #26328C;
                                                            margin-right: 4px;"
                                                            title="DESCUENTO INTERNET: {{$provactivo->di}} %">
                                                            {{$provactivo->di}} % 
                                                        </span>
                                                    @endif
                                                </center>
                                            </button>
                                        </center>
                                        </a>
                                    </th>
                                    <th colspan="5" 
                                        style="background-color: #FEE3CB;"> 
                                        <center>
                                            CLIENTE
                                        </center>
                                    </th>
                                    <th colspan="8" 
                                        style="background-color: {{$confprov->backcolor}}; 
                                        color: {{$confprov->forecolor}};"> 
                                        <center>
                                           DATOS DEL PROVEEDOR
                                        </center>
                                    </th>
                                @else
                                    <th colspan="24" style="background-color: {{$confprov->backcolor}}; color: {{$confprov->forecolor}};">
                                        <a href="{{URL::action('ProveedorController@verprov',$tpactivo)}}">
                                        <center>
                                            <button type="button" data-toggle="tooltip" title="Ver información del proveedor &#10 {{strtoupper($confprov->nombre)}}" style="background-color: {{$confprov->backcolor}}; color: {{$confprov->forecolor}}; border: none;">
                                                <center>
                                                    <img style="width: 20px; height: 20px;" 
                                                    src="{{$rutalogoprov.$confprov->rutalogo1}}" alt="icompras360">
                                                    FECHA: 
                                                    @if ( date('d-m-Y', strtotime($confprov->fechacata) ) == date('d-m-Y') )
                                                        <span>
                                                            {{date('d-m-Y H:i', strtotime($confprov->fechacata)) }} 
                                                        </span>
                                                    @else
                                                        <span style="color: red;">
                                                            {{date('d-m-Y H:i', strtotime($confprov->fechacata)) }} 
                                                        </span>
                                                    @endif
                                                    - 
                                                    @if ($provactivo->dcme > 0)
                                                        <span style="font-size: 10px;">
                                                            DC: 
                                                        </span>
                                                        <span style="border-radius: 5px; 
                                                            font-size: 14px;
                                                            text-align: center;
                                                            padding: 2px; 
                                                            width: 72px;
                                                            color: white;
                                                            background-color: #26328C;
                                                            margin-right: 4px;"
                                                            title="DESCUENTO COMERCIAL: {{$provactivo->dcme}} % ">
                                                            {{$provactivo->dcme}} % 
                                                        </span>
                                                    @endif
                                                    @if ($provactivo->ppme > 0)
                                                        <span style="font-size: 10px;">
                                                            PP: 
                                                        </span>
                                                        <span style="border-radius: 5px; 
                                                            font-size: 14px;
                                                            text-align: center;
                                                            padding: 2px; 
                                                            width: 72px;
                                                            color: white;
                                                            background-color: #26328C;
                                                            margin-right: 4px;"
                                                            title="DESCUENTO PRONTO PAGO: {{$provactivo->ppme}} % ">
                                                            {{$provactivo->ppme}} % 
                                                        </span>
                                                    @endif
                                                    @if ($provactivo->di > 0)
                                                        <span style="font-size: 10px;">
                                                            DI: 
                                                        </span>
                                                        <span style="border-radius: 5px; 
                                                            font-size: 14px;
                                                            text-align: center;
                                                            padding: 2px; 
                                                            width: 72px;
                                                            color: white;
                                                            background-color: #26328C;
                                                            margin-right: 4px;"
                                                            title="DESCUENTO INTERNET: {{$provactivo->di}} %">
                                                            {{$provactivo->di}} % 
                                                        </span>
                                                    @endif
                                                </center>
                                            </button>
                                        </center>
                                        </a>
                                    </th>
                                @endif
                            @endif
                            @if ($tipo == "O")
                                @if (Auth::user()->versionLight == 0)
                                    <th colspan="5" style="background-color: {{$confprov->backcolor}}; color: {{$confprov->forecolor}};">
                                        <a href="{{URL::action('ProveedorController@verprov',$tpactivo)}}">
                                        <center>
                                            <button type="button" data-toggle="tooltip" title="Ver información del proveedor &#10 {{strtoupper($confprov->nombre)}}" style="background-color: {{$confprov->backcolor}}; color: {{$confprov->forecolor}}; border: none;">
                                                <center>
                                                    <img style="width: 20px; height: 20px;" 
                                                    src="{{$rutalogoprov.$confprov->rutalogo1}}" alt="icompras360">
                                                    FECHA: 
                                                    @if ( date('d-m-Y', strtotime($confprov->fechacata) ) == date('d-m-Y') )
                                                        <span>
                                                            {{date('d-m-Y H:i', strtotime($confprov->fechacata)) }} 
                                                        </span>
                                                    @else
                                                        <span style="color: red;">
                                                            {{date('d-m-Y H:i', strtotime($confprov->fechacata)) }} 
                                                        </span>
                                                    @endif
                                                    - 
                                                    @if ($provactivo->dcme > 0)
                                                        <span style="font-size: 10px;">
                                                            DC: 
                                                        </span>
                                                        <span style="border-radius: 5px; 
                                                            font-size: 14px;
                                                            text-align: center;
                                                            padding: 2px; 
                                                            width: 72px;
                                                            color: white;
                                                            background-color: #26328C;
                                                            margin-right: 4px;"
                                                            title="DESCUENTO COMERCIAL: {{$provactivo->dcme}} % ">
                                                            {{$provactivo->dcme}} % 
                                                        </span>
                                                    @endif
                                                    @if ($provactivo->ppme > 0)
                                                        <span style="font-size: 10px;">
                                                            PP: 
                                                        </span>
                                                        <span style="border-radius: 5px; 
                                                            font-size: 14px;
                                                            text-align: center;
                                                            padding: 2px; 
                                                            width: 72px;
                                                            color: white;
                                                            background-color: #26328C;
                                                            margin-right: 4px;"
                                                            title="DESCUENTO PRONTO PAGO: {{$provactivo->ppme}} % ">
                                                            {{$provactivo->ppme}} % 
                                                        </span>
                                                    @endif
                                                    @if ($provactivo->di > 0)
                                                        <span style="font-size: 10px;">
                                                            DI: 
                                                        </span>
                                                        <span style="border-radius: 5px; 
                                                            font-size: 14px;
                                                            text-align: center;
                                                            padding: 2px; 
                                                            width: 72px;
                                                            color: white;
                                                            background-color: #26328C;
                                                            margin-right: 4px;"
                                                            title="DESCUENTO INTERNET: {{$provactivo->di}} %">
                                                            {{$provactivo->di}} % 
                                                        </span>
                                                    @endif 
                                                </center>
                                            </button>
                                        </center>
                                        </a>
                                    </th>
                                    <th colspan="5" 
                                        style="background-color: #FEE3CB;"> 
                                        <center>
                                            CLIENTE
                                        </center>
                                    </th>
                                    <th colspan="8" 
                                        style="background-color: {{$confprov->backcolor}}; 
                                        color: {{$confprov->forecolor}};"> 
                                        <center>
                                           DATOS DEL PROVEEDOR
                                        </center>
                                    </th>
                                @else
                                    <th colspan="23" style="background-color: {{$confprov->backcolor}}; color: {{$confprov->forecolor}};">
                                        <a href="{{URL::action('ProveedorController@verprov',$tpactivo)}}">
                                        <center>
                                            <button type="button" data-toggle="tooltip" title="Ver información del proveedor &#10 {{strtoupper($confprov->nombre)}}" style="background-color: {{$confprov->backcolor}}; color: {{$confprov->forecolor}}; border: none;">
                                                <center>
                                                    <img style="width: 20px; height: 20px;" 
                                                    src="{{$rutalogoprov.$confprov->rutalogo1}}" alt="icompras360">
                                                    FECHA: 
                                                    @if ( date('d-m-Y', strtotime($confprov->fechacata) ) == date('d-m-Y') )
                                                        <span>
                                                            {{date('d-m-Y H:i', strtotime($confprov->fechacata)) }} 
                                                        </span>
                                                    @else
                                                        <span style="color: red;">
                                                            {{date('d-m-Y H:i', strtotime($confprov->fechacata)) }} 
                                                        </span>
                                                    @endif
                                                    - 
                                                    @if ($provactivo->dcme > 0)
                                                        <span style="font-size: 10px;">
                                                            DC: 
                                                        </span>
                                                        <span style="border-radius: 5px; 
                                                            font-size: 14px;
                                                            text-align: center;
                                                            padding: 2px; 
                                                            width: 72px;
                                                            color: white;
                                                            background-color: #26328C;
                                                            margin-right: 4px;"
                                                            title="DESCUENTO COMERCIAL: {{$provactivo->dcme}} % ">
                                                            {{$provactivo->dcme}} % 
                                                        </span>
                                                    @endif
                                                    @if ($provactivo->ppme > 0)
                                                        <span style="font-size: 10px;">
                                                            PP: 
                                                        </span>
                                                        <span style="border-radius: 5px; 
                                                            font-size: 14px;
                                                            text-align: center;
                                                            padding: 2px; 
                                                            width: 72px;
                                                            color: white;
                                                            background-color: #26328C;
                                                            margin-right: 4px;"
                                                            title="DESCUENTO PRONTO PAGO: {{$provactivo->ppme}} % ">
                                                            {{$provactivo->ppme}} % 
                                                        </span>
                                                    @endif
                                                    @if ($provactivo->di > 0)
                                                        <span style="font-size: 10px;">
                                                            DI: 
                                                        </span>
                                                        <span style="border-radius: 5px; 
                                                            font-size: 14px;
                                                            text-align: center;
                                                            padding: 2px; 
                                                            width: 72px;
                                                            color: white;
                                                            background-color: #26328C;
                                                            margin-right: 4px;"
                                                            title="DESCUENTO INTERNET: {{$provactivo->di}} %">
                                                            {{$provactivo->di}} % 
                                                        </span>
                                                    @endif
                                                </center>
                                            </button>
                                        </center>
                                        </a>
                                    </th>
                                @endif
                            @endif
                            @if ($tipo == "R")
                                @if (Auth::user()->versionLight == 0)
                                    <th colspan="5" 
                                        style="background-color: {{$confprov->backcolor}}; 
                                        color: {{$confprov->forecolor}};">
                                        <a href="{{URL::action('ProveedorController@verprov',$tpactivo)}}">
                                        <center>
                                            <button type="button" data-toggle="tooltip" title="Ver información del proveedor &#10 {{strtoupper($confprov->nombre)}}" style="background-color: {{$confprov->backcolor}}; color: {{$confprov->forecolor}}; border: none;">
                                                <img style="width: 20px; height: 20px;" 
                                                src="{{$rutalogoprov.$confprov->rutalogo1}}" alt="icompras360">
                                                FECHA: 
                                                @if ( date('d-m-Y', strtotime($confprov->fechacata) ) == date('d-m-Y') )
                                                    <span>
                                                        {{date('d-m-Y H:i', strtotime($confprov->fechacata)) }} 
                                                    </span>
                                                @else
                                                    <span style="color: red;">
                                                        {{date('d-m-Y H:i', strtotime($confprov->fechacata)) }} 
                                                    </span>
                                                @endif
                                                - 
                                                @if ($provactivo->dcme > 0)
                                                    <span style="font-size: 10px;">
                                                        DC: 
                                                    </span>
                                                    <span style="border-radius: 5px; 
                                                        font-size: 14px;
                                                        text-align: center;
                                                        padding: 2px; 
                                                        width: 72px;
                                                        color: white;
                                                        background-color: #26328C;
                                                        margin-right: 4px;"
                                                        title="DESCUENTO COMERCIAL: {{$provactivo->dcme}} % ">
                                                        {{$provactivo->dcme}} % 
                                                    </span>
                                                @endif
                                                @if ($provactivo->ppme > 0)
                                                    <span style="font-size: 10px;">
                                                        PP: 
                                                    </span>
                                                    <span style="border-radius: 5px; 
                                                        font-size: 14px;
                                                        text-align: center;
                                                        padding: 2px; 
                                                        width: 72px;
                                                        color: white;
                                                        background-color: #26328C;
                                                        margin-right: 4px;"
                                                        title="DESCUENTO PRONTO PAGO: {{$provactivo->ppme}} % ">
                                                        {{$provactivo->ppme}} % 
                                                    </span>
                                                @endif
                                                @if ($provactivo->di > 0)
                                                    <span style="font-size: 10px;">
                                                        DI: 
                                                    </span>
                                                    <span style="border-radius: 5px; 
                                                        font-size: 14px;
                                                        text-align: center;
                                                        padding: 2px; 
                                                        width: 72px;
                                                        color: white;
                                                        background-color: #26328C;
                                                        margin-right: 4px;"
                                                        title="DESCUENTO INTERNET: {{$provactivo->di}} %">
                                                        {{$provactivo->di}} % 
                                                    </span>
                                                @endif
                                            </button>
                                        </center>
                                        </a>
                                    </th>
                                    <th colspan="5" 
                                        style="background-color: #FEE3CB;"> 
                                        <center>
                                            CLIENTE
                                        </center>
                                    </th>
                                    <th colspan="8" 
                                        style="background-color: {{$confprov->backcolor}}; 
                                        color: {{$confprov->forecolor}};"> 
                                        <center>
                                           DATOS DEL PROVEEDOR
                                        </center>
                                    </th>
                                @else
                                    <th colspan="23" 
                                        style="background-color: {{$confprov->backcolor}}; 
                                        color: {{$confprov->forecolor}};">
                                        <a href="{{URL::action('ProveedorController@verprov',$tpactivo)}}">
                                        <center>
                                            <button type="button" data-toggle="tooltip" title="Ver información del proveedor &#10 {{strtoupper($confprov->nombre)}}" style="background-color: {{$confprov->backcolor}}; color: {{$confprov->forecolor}}; border: none;">
                                                <img style="width: 20px; height: 20px;" 
                                                src="{{$rutalogoprov.$confprov->rutalogo1}}" alt="icompras360">
                                                FECHA:
                                                @if ( date('d-m-Y', strtotime($confprov->fechacata) ) == date('d-m-Y') )
                                                    <span>
                                                        {{date('d-m-Y H:i', strtotime($confprov->fechacata)) }} 
                                                    </span>
                                                @else
                                                    <span style="color: red;">
                                                        {{date('d-m-Y H:i', strtotime($confprov->fechacata)) }} 
                                                    </span>
                                                @endif
                                                - 
                                                @if ($provactivo->dcme > 0)
                                                    <span style="font-size: 10px;">
                                                        DC: 
                                                    </span>
                                                    <span style="border-radius: 5px; 
                                                        font-size: 14px;
                                                        text-align: center;
                                                        padding: 2px; 
                                                        width: 72px;
                                                        color: white;
                                                        background-color: #26328C;
                                                        margin-right: 4px;"
                                                        title="DESCUENTO COMERCIAL: {{$provactivo->dcme}} % ">
                                                        {{$provactivo->dcme}} % 
                                                    </span>
                                                @endif
                                                @if ($provactivo->ppme > 0)
                                                    <span style="font-size: 10px;">
                                                        PP: 
                                                    </span>
                                                    <span style="border-radius: 5px; 
                                                        font-size: 14px;
                                                        text-align: center;
                                                        padding: 2px; 
                                                        width: 72px;
                                                        color: white;
                                                        background-color: #26328C;
                                                        margin-right: 4px;"
                                                        title="DESCUENTO PRONTO PAGO: {{$provactivo->ppme}} % ">
                                                        {{$provactivo->ppme}} % 
                                                    </span>
                                                @endif
                                                @if ($provactivo->di > 0)
                                                    <span style="font-size: 10px;">
                                                        DI: 
                                                    </span>
                                                    <span style="border-radius: 5px; 
                                                        font-size: 14px;
                                                        text-align: center;
                                                        padding: 2px; 
                                                        width: 72px;
                                                        color: white;
                                                        background-color: #26328C;
                                                        margin-right: 4px;"
                                                        title="DESCUENTO INTERNET: {{$provactivo->di}} %">
                                                        {{$provactivo->di}} % 
                                                    </span>
                                                @endif
                                            </button>
                                        </center>
                                        </a>
                                    </th>
                                @endif
                            @endif
                            @if ($tipo == "M")
                                @if (Auth::user()->versionLight == 0)
                                    <th colspan="5" 
                                        style="background-color: {{$confprov->backcolor}}; 
                                        color: {{$confprov->forecolor}};">
                                        <a href="{{URL::action('ProveedorController@verprov',$tpactivo)}}">
                                        <center>
                                            <button type="button" 
                                                data-toggle="tooltip" 
                                                title="Ver información del proveedor &#10 {{strtoupper($confprov->nombre)}}" 
                                                style="background-color: {{$confprov->backcolor}}; 
                                                color: {{$confprov->forecolor}}; border: none;">

                                                <img style="width: 20px; height: 20px;" 
                                                src="{{$rutalogoprov.$confprov->rutalogo1}}" alt="icompras360">
                                                FECHA: 
                                                @if ( date('d-m-Y', strtotime($confprov->fechacata) ) == date('d-m-Y') )
                                                    <span>
                                                        {{date('d-m-Y H:i', strtotime($confprov->fechacata)) }} 
                                                    </span>
                                                @else
                                                    <span style="color: red;">
                                                        {{date('d-m-Y H:i', strtotime($confprov->fechacata)) }} 
                                                    </span>
                                                @endif
                                                - 
                                                @if ($provactivo->dcme > 0)
                                                    <span style="font-size: 10px;">
                                                        DC: 
                                                    </span>
                                                    <span style="border-radius: 5px; 
                                                        font-size: 14px;
                                                        text-align: center;
                                                        padding: 2px; 
                                                        width: 72px;
                                                        color: white;
                                                        background-color: #26328C;
                                                        margin-right: 4px;"
                                                        title="DESCUENTO COMERCIAL: {{$provactivo->dcme}} % ">
                                                        {{$provactivo->dcme}} % 
                                                    </span>
                                                @endif
                                                @if ($provactivo->ppme > 0)
                                                    <span style="font-size: 10px;">
                                                        PP: 
                                                    </span>
                                                    <span style="border-radius: 5px; 
                                                        font-size: 14px;
                                                        text-align: center;
                                                        padding: 2px; 
                                                        width: 72px;
                                                        color: white;
                                                        background-color: #26328C;
                                                        margin-right: 4px;"
                                                        title="DESCUENTO PRONTO PAGO: {{$provactivo->ppme}} % ">
                                                        {{$provactivo->ppme}} % 
                                                    </span>
                                                @endif
                                                @if ($provactivo->di > 0)
                                                    <span style="font-size: 10px;">
                                                        DI: 
                                                    </span>
                                                    <span style="border-radius: 5px; 
                                                        font-size: 14px;
                                                        text-align: center;
                                                        padding: 2px; 
                                                        width: 72px;
                                                        color: white;
                                                        background-color: #26328C;
                                                        margin-right: 4px;"
                                                        title="DESCUENTO INTERNET: {{$provactivo->di}} %">
                                                        {{$provactivo->di}} % 
                                                    </span>
                                                @endif    
                                            </button>
                                        </center>
                                        </a>
                                    </th>
                                    <th colspan="5" 
                                        style="background-color: #FEE3CB;"> 
                                        <center>
                                            CLIENTE
                                        </center>
                                    </th>
                                    <th colspan="8" 
                                        style="background-color: {{$confprov->backcolor}}; 
                                        color: {{$confprov->forecolor}};"> 
                                        <center>
                                           DATOS DEL PROVEEDOR
                                        </center>
                                    </th>
                                @else
                                    <th colspan="24" 
                                        style="background-color: {{$confprov->backcolor}}; 
                                        color: {{$confprov->forecolor}};">
                                        <a href="{{URL::action('ProveedorController@verprov',$tpactivo)}}">
                                        <center>
                                            <button type="button" 
                                                data-toggle="tooltip" 
                                                title="Ver información del proveedor &#10 {{strtoupper($confprov->nombre)}}" 
                                                style="background-color: {{$confprov->backcolor}}; 
                                                color: {{$confprov->forecolor}}; border: none;">

                                                <img style="width: 20px; height: 20px;" 
                                                src="{{$rutalogoprov.$confprov->rutalogo1}}" alt="icompras360">
                                                FECHA: 
                                                @if ( date('d-m-Y', strtotime($confprov->fechacata) ) == date('d-m-Y') )
                                                    <span>
                                                        {{date('d-m-Y H:i', strtotime($confprov->fechacata)) }} 
                                                    </span>
                                                @else
                                                    <span style="color: red;">
                                                        {{date('d-m-Y H:i', strtotime($confprov->fechacata)) }} 
                                                    </span>
                                                @endif
                                                - 
                                                @if ($provactivo->dcme > 0)
                                                    <span style="font-size: 10px;">
                                                        DC: 
                                                    </span>
                                                    <span style="border-radius: 5px; 
                                                        font-size: 14px;
                                                        text-align: center;
                                                        padding: 2px; 
                                                        width: 72px;
                                                        color: white;
                                                        background-color: #26328C;
                                                        margin-right: 4px;"
                                                        title="DESCUENTO COMERCIAL: {{$provactivo->dcme}} % ">
                                                        {{$provactivo->dcme}} % 
                                                    </span>
                                                @endif
                                                @if ($provactivo->ppme > 0)
                                                    <span style="font-size: 10px;">
                                                        PP: 
                                                    </span>
                                                    <span style="border-radius: 5px; 
                                                        font-size: 14px;
                                                        text-align: center;
                                                        padding: 2px; 
                                                        width: 72px;
                                                        color: white;
                                                        background-color: #26328C;
                                                        margin-right: 4px;"
                                                        title="DESCUENTO PRONTO PAGO: {{$provactivo->ppme}} % ">
                                                        {{$provactivo->ppme}} % 
                                                    </span>
                                                @endif
                                                @if ($provactivo->di > 0)
                                                    <span style="font-size: 10px;">
                                                        DI: 
                                                    </span>
                                                    <span style="border-radius: 5px; 
                                                        font-size: 14px;
                                                        text-align: center;
                                                        padding: 2px; 
                                                        width: 72px;
                                                        color: white;
                                                        background-color: #26328C;
                                                        margin-right: 4px;"
                                                        title="DESCUENTO INTERNET: {{$provactivo->di}} %">
                                                        {{$provactivo->di}} % 
                                                    </span>
                                                @endif    
                                            </button>
                                        </center>
                                        </a>
                                    </th>
                                @endif
                            @endif
                        @endif
                    </thead>

                    <thead style="background-color: #b7b7b7;">
                        @if ($tpactivo == 'MAESTRO')
                            <th>#</th>
                            <th style="width: 100px;">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;IMAGEN&nbsp;&nbsp;&nbsp;&nbsp;&nbsp1;&nbsp;&nbsp;
                            </th>
                            <th title="Cantidad a pedir" style="width: 120px;">PEDIR</th>

                            <th title="Descripción del producto">
                                PRODUCTO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            </th>
                            <th title="Referencias del producto">
                                REFERENCIAS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            </th>

                            <th style="display: none;"
                                title="Código de barra del producto">
                                BARRA
                            </th>
                            <th style="display: none;"
                                title="Unidad del bulto original">
                                BULTO
                            </th>
                            <th style="display: none;"
                                title="Impuesto al valor agregado">
                                IVA
                            </th>
                            
                            @if (Auth::user()->versionLight == 0)
                                <th title="Unidades en Transito del producto" 
                                    style="background-color: #FEE3CB;">
                                    TRAN.
                                </th>
                                <th title="Unidades del inventario del producto"
                                    style="background-color: #FEE3CB;">
                                    INV.
                                </th>
                                <th style="display:none;">TP</th>
                                <th style="display:none;">RNK1</th>
                                <th title="Costo del producto"
                                    style="background-color: #FEE3CB;">
                                    COSTO
                                </th>
                                <th title="Venta Media Diaria"
                                    style="background-color: #FEE3CB;">
                                    VMD&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                </th>
                                <th title="Dias de inventario y Sugeridos de Unidades x Dias"
                                    style="background-color: #FEE3CB;">
                                    DIAS/SUG&nbsp;&nbsp;&nbsp;
                                </th>
                                <th title="Sugerido=(VMD*15-(INVENTARIO+TRANSITO))"
                                    style="display:none; background-color: #FEE3CB;">
                                    15
                                </th>
                                <th title="Sugerido=(VMD*30-(INVENTARIO+TRANSITO))"
                                    style="display:none; background-color: #FEE3CB;">
                                    30
                                </th>
                                <th title="Sugerido=(VMD*60-(INVENTARIO+TRANSITO)"
                                    style="display:none; background-color: #FEE3CB;">
                                    60
                                </th>
                            @else
                                <th title="Unidades en Transito del producto" >TRAN.</th>
                                <th style="display:none;">INV.</th>
                                <th style="display:none;">TP</th>
                                <th style="display:none;">RNK1</th>
                                <th style="display:none;">COSTO</th>
                                <th style="display:none;">VMD</th>
                                <th style="display:none;">DIAS</th>
                            @endif
                            @if ($tipo == "C")
                                <th title="Unidades consolidadas del inventario de proveedores"
                                    style="background-color: #FCD0C7;">
                                    CONSOL.
                                </th>
                                @foreach ($provs as $prov)
                                    @php 
                                    if (!VerificaCampoTabla('tpmaestra', $prov->codprove))
                                        continue;
                                    $confprov = LeerProve($prov->codprove); 
                                    if (is_null($confprov))
                                        continue;
                                    @endphp
                                    <th style="background-color: {{$confprov->backcolor}}; 
                                        color: {{$confprov->forecolor}}; 
                                        width: 240px; 
                                        word-wrap: 
                                        break-word; ">
                                        PRECIO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    </th> 
                                    <th style="background-color: {{$confprov->backcolor}}; 
                                        color: {{$confprov->forecolor}}; 
                                        width: 240px; 
                                        word-wrap: break-word; ">
                                        CANTIDAD&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    </th>
                                    <th style="display:none;">DA</th>
                                    <th style="display:none;">CODPROD</th>
                                    <th style="display:none;">ENTRADA</th>
                                @endforeach
                            @endif
                            @if ($tipo == "E")
                                <th style="background-color: #A69FB5;">
                                    PRECIO
                                </th>                    
                                <th style="background-color: #A69FB5;">
                                    CANT.
                                </th>
                                <th title="Descuento Adicional del proveedor"
                                    style="display: none; background-color: #A69FB5;">
                                    DA
                                </th>
                                <th title="Código del producto del proveedor"
                                    style="background-color: #A69FB5;">
                                    CODIGO
                                </th>
                                <th style="width: 100px; background-color: #A69FB5;">
                                    &nbsp;&nbsp;&nbsp;ENTRADA&nbsp;&nbsp;&nbsp;
                                </th>

                                <th style="display: none; background-color: #A69FB5;">
                                    PROVEEDOR&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                </th>
                                <th style="display:none;">CODPROV</th>
                                <th style="background-color: #A69FB5;">
                                    LOTE
                                </th>
                                <th style="display: none; background-color: #A69FB5;">
                                    VENCE
                                </th>
                            @endif
                            @if ($tipo == "O")
                                <th style="background-color: #A69FB5;">
                                    PRECIO
                                </th>                    
                                <th style="background-color: #A69FB5;">
                                    CANT.
                                </th>
                                <th title="Descuento Adicional del proveedor"
                                    style="display: none; background-color: #A69FB5;">
                                    DA
                                </th>
                                <th title="Código del producto del proveedor"
                                    style="background-color: #A69FB5;">
                                    CODIGO
                                </th>
                                <th style="display:none;">ENTRADA</th>
                                <th style="display:none; background-color: #A69FB5;">
                                    PROVEEDOR&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                </th>
                                <th style="display:none;">CODPROV</th>
                                <th style="background-color: #A69FB5;">
                                    LOTE
                                </th>
                                <th style="display: none; background-color: #A69FB5;">
                                    VENCE
                                </th>
                            @endif
                            @if ($tipo == "R")
                                <th style="background-color: #A69FB5;">
                                    PRECIO
                                </th>                    
                                <th style="background-color: #A69FB5;">
                                    CANT.
                                </th>
                                <th title="Descuento Adicional del proveedor"
                                    style="display: none; background-color: #A69FB5;">
                                    DA
                                </th>
                                <th title="Código del producto del proveedor"
                                    style="background-color: #A69FB5;">
                                    CODIGO
                                </th>
                                <th style="display:none; 
                                    width: 100px; 
                                    background-color: #A69FB5;" >
                                    &nbsp;&nbsp;&nbsp;ENTRADA&nbsp;&nbsp;&nbsp;
                                </th>
                                <th style="display:none; background-color: #A69FB5;">
                                    PROVEEDOR&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                </th>
                                <th style="display:none;">CODPROV</th>
                                <th style="background-color: #A69FB5;">
                                    LOTE
                                </th>
                                <th style="display: none; background-color: #A69FB5;">
                                    VENCE
                                </th>
                            @endif
                            @if ($tipo == "M")
                                <th title="Unidades consolidadas del inventario de proveedores"
                                    style="background-color: #FCD0C7;">
                                    CONSOL.
                                </th>
                                <th title="Precio liquida la molecula"
                                    style="background-color: #FCD0C7;">
                                    MOLECULA
                                </th>
                                @foreach ($provs as $prov)
                                    @php 
                                    if (!VerificaCampoTabla('tpmaestra', $prov->codprove))
                                        continue;
                                    $confprov = LeerProve($prov->codprove); 
                                    if (is_null($confprov))
                                        continue;
                                    @endphp
                                    @if ($tipo == 'M')
                                        <th style="background-color: {{$confprov->backcolor}}; 
                                            color: {{$confprov->forecolor}}; 
                                            width: 300px; 
                                            word-wrap: 
                                            break-word; ">
                                            PRECIO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        </th> 
                                    @else
                                        <th style="background-color: {{$confprov->backcolor}}; 
                                            color: {{$confprov->forecolor}}; 
                                            width: 200px; 
                                            word-wrap: 
                                            break-word; ">
                                            PRECIO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        </th> 
                                    @endif
                                    <th style="background-color: {{$confprov->backcolor}}; 
                                        color: {{$confprov->forecolor}}; 
                                        width: 200px; 
                                        word-wrap: break-word; ">
                                        CANTIDAD&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    </th>
                                    <th style="display:none;">DA</th>
                                    <th style="display:none;">CODPROD</th>
                                    <th style="display:none;">ENTRADA</th>
                                @endforeach
                            @endif
                        @else
                            @php 
                                $confprov = LeerProve($tpactivo); 
                            @endphp

                            <th style="background-color: {{$confprov->backcolor}}; 
                                color: {{$confprov->forecolor}};">
                                #
                            </th>
                            <th style="width: 100px; 
                                background-color: {{$confprov->backcolor}}; 
                                color: {{$confprov->forecolor}};" >
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;IMAGEN&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            </th>
                            <th style="width: 120px; 
                                background-color: {{$confprov->backcolor}}; 
                                color: {{$confprov->forecolor}};">
                                PEDIR
                            </th>
                            <th style="background-color: {{$confprov->backcolor}}; 
                                color: {{$confprov->forecolor}};">
                                PRODUCTO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            </th>
                            <th style="background-color: {{$confprov->backcolor}}; 
                                color: {{$confprov->forecolor}};">
                                REFERENCIAS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            </th>
                            <th style="background-color: {{$confprov->backcolor}}; 
                                color: {{$confprov->forecolor}};
                                display: none;">
                                BARRA
                            </th>
                            <th style="background-color: {{$confprov->backcolor}}; 
                                color: {{$confprov->forecolor}};
                                display: none;">
                                BULTO
                            </th>
                            <th style="background-color: {{$confprov->backcolor}}; 
                                color: {{$confprov->forecolor}};
                                display: none;">
                                IVA
                            </th>
                            @if (Auth::user()->versionLight == 0)
                                <th title="Unidades en Transito del cliente" 
                                    style="background-color: #FEE3CB;">
                                    TRAN.
                                </th>
                                <th title="Unidades del inventario del cliente" 
                                    style="background-color: #FEE3CB;"> 
                                    INV.
                                </th>
                                <th style="display:none;">TP</th>
                                <th style="display:none;">RNK1</th>
                                <th title="Costo del producto" 
                                    style="background-color: #FEE3CB"> 
                                    COSTO
                                </th>
                                <th title="Unidad de venta media diaria" 
                                    style="background-color: #FEE3CB"> 
                                    VMD&nbsp;&nbsp;&nbsp;
                                </th>

                                <th title="Dias de inventario y Sugeridos de Unidades x Dias" 
                                    style="background-color: #FEE3CB"> 
                                    DIAS/SUG&nbsp;&nbsp;&nbsp;
                                </th>
                                <th title="Sugerido=(VMD*15-(INVENTARIO+TRANSITO))"
                                    style="display:none; background-color: #FEE3CB;">
                                    15
                                </th>
                                <th title="Sugerido=(VMD*30-(INVENTARIO+TRANSITO))"
                                    style="display:none; background-color: #FEE3CB;">
                                    30
                                </th>
                                <th title="Sugerido=(VMD*60-(INVENTARIO+TRANSITO)"
                                    style="display:none; background-color: #FEE3CB;">
                                    60
                                </th>
                            @else
                                <th title="Unidades en Transito del cliente" 
                                    style="background-color: {{$confprov->backcolor}}; 
                                    color: {{$confprov->forecolor}};">
                                    TRAN.
                                </th>
                                <th style="display:none;">INV,</th>
                                <th style="display:none;">TP</th>
                                <th style="display:none;">RNK1</th>
                                <th style="display:none;">COSTO</th>
                                <th style="display:none;">VMD</th>
                                <th style="display:none;">DIAS</th>
                            @endif
                            @if ($tipo == "C")
                                <th style="background-color: {{$confprov->backcolor}}; 
                                    color: {{$confprov->forecolor}};">
                                    PRECIO
                                </th>                    
                                <th style="background-color: {{$confprov->backcolor}}; 
                                    color: {{$confprov->forecolor}};">
                                    CANT.
                                </th>
                                <th style="display:none;
                                    background-color: {{$confprov->backcolor}}; 
                                    color: {{$confprov->forecolor}};">
                                    DA
                                </th>
                                <th style="background-color: {{$confprov->backcolor}}; 
                                    color: {{$confprov->forecolor}};">
                                    CODIGO
                                </th>
                                <th style="display:none;">ENTRADA</th>
                                <th style="display:none; background-color: {{$confprov->backcolor}}; 
                                    color: {{$confprov->forecolor}};">
                                    PROVEEDOR&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                </th>
                                <th style="display:none;">CODPROV</th>
                                <th style="background-color: {{$confprov->backcolor}}; 
                                    color: {{$confprov->forecolor}};">
                                    LOTE
                                </th>
                                <th style="display:none; 
                                    background-color: {{$confprov->backcolor}}; 
                                    color: {{$confprov->forecolor}};">
                                    VENCE
                                </th>
                            @endif
                            @if ($tipo == "E")
                                <th style="background-color: {{$confprov->backcolor}}; 
                                    color: {{$confprov->forecolor}};">
                                    PRECIO
                                </th>                    
                                <th style="background-color: {{$confprov->backcolor}}; 
                                    color: {{$confprov->forecolor}};">
                                    CANT.
                                </th>
                                <th style="display:none; 
                                    background-color: {{$confprov->backcolor}}; 
                                    color: {{$confprov->forecolor}};">DA
                                </th>
                                <th style="background-color: {{$confprov->backcolor}}; 
                                    color: {{$confprov->forecolor}};">
                                    CODIGO
                                </th>
                                <th style="width: 100px; 
                                    background-color: {{$confprov->backcolor}}; 
                                    color: {{$confprov->forecolor}};">
                                    &nbsp;&nbsp;&nbsp;ENTRADA&nbsp;&nbsp;&nbsp;
                                </th>
                                <th style="display:none; background-color: {{$confprov->backcolor}}; 
                                    color: {{$confprov->forecolor}};">
                                    PROVEEDOR&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                </th>
                                <th style="display:none;">CODPROV</th>
                                <th style="background-color: {{$confprov->backcolor}}; 
                                    color: {{$confprov->forecolor}};">
                                    LOTE
                                </th>
                                <th style="display:none; 
                                    background-color: {{$confprov->backcolor}}; 
                                    color: {{$confprov->forecolor}};">
                                    VENCE
                                </th>
                            @endif
                            @if ($tipo == "O")
                                <th style="background-color: {{$confprov->backcolor}}; 
                                    color: {{$confprov->forecolor}};">
                                    PRECIO
                                </th>                    
                                <th style="background-color: {{$confprov->backcolor}}; 
                                    color: {{$confprov->forecolor}};">
                                    CANT.
                                </th>
                                <th style="display: none; 
                                    background-color: {{$confprov->backcolor}}; 
                                    color: {{$confprov->forecolor}};">
                                    DA
                                </th>
                                <th style="background-color: {{$confprov->backcolor}}; 
                                    color: {{$confprov->forecolor}};">
                                    CODIGO
                                </th>
                                <th style="display:none;">ENTRADA</th>
                                <th style="display:none; 
                                    background-color: {{$confprov->backcolor}}; 
                                    color: {{$confprov->forecolor}};">
                                    PROVEEDOR&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                </th>
                                <th style="display:none;">CODPROV</th>
                                <th style="background-color: {{$confprov->backcolor}}; 
                                    color: {{$confprov->forecolor}};">
                                    LOTE
                                </th>
                                <th style="display:none; 
                                    background-color: {{$confprov->backcolor}}; 
                                    color: {{$confprov->forecolor}};">
                                    VENCE
                                </th>
                            @endif
                            @if ($tipo == "R")
                                <th style="background-color: {{$confprov->backcolor}}; 
                                    color: {{$confprov->forecolor}};">
                                    PRECIO
                                </th>                    
                                <th style="background-color: {{$confprov->backcolor}}; 
                                    color: {{$confprov->forecolor}};">
                                    CANT.
                                </th>
                                <th style="display:none;
                                    background-color: {{$confprov->backcolor}}; 
                                    color: {{$confprov->forecolor}};">
                                    DA
                                </th>
                                <th style="background-color: {{$confprov->backcolor}}; 
                                    color: {{$confprov->forecolor}};">
                                    CODIGO
                                </th>
                                <th style="display:none;">ENTRADA</th>
                                <th style="display:none; background-color: {{$confprov->backcolor}}; 
                                    color: {{$confprov->forecolor}};">
                                    PROVEEDOR&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                </th>
                                <th style="display:none;">CODPROV</th>
                                <th style="background-color: {{$confprov->backcolor}}; 
                                    color: {{$confprov->forecolor}};">
                                    LOTE
                                </th>
                                <th style="display:none; background-color: {{$confprov->backcolor}}; 
                                    color: {{$confprov->forecolor}};">
                                    VENCE
                                </th>
                            @endif
                            @if ($tipo == "M")
                                <th style="background-color: {{$confprov->backcolor}}; 
                                    color: {{$confprov->forecolor}};">
                                    MOLECULA
                                </th>   
                                <th style="background-color: {{$confprov->backcolor}}; 
                                    color: {{$confprov->forecolor}};">
                                    PRECIO
                                </th>                    
                                <th style="background-color: {{$confprov->backcolor}}; 
                                    color: {{$confprov->forecolor}};">
                                    CANT.
                                </th>
                                <th style="display:none; 
                                    background-color: {{$confprov->backcolor}}; 
                                    color: {{$confprov->forecolor}};">
                                    DA
                                </th>
                                <th style="background-color: {{$confprov->backcolor}}; 
                                    color: {{$confprov->forecolor}};">
                                    CODIGO
                                </th>
                                <th style="display:none;">ENTRADA</th>
                                <th style="display:none; 
                                    background-color: {{$confprov->backcolor}}; 
                                    color: {{$confprov->forecolor}};">
                                    PROVEEDOR&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                </th>
                                <th style="display:none;">CODPROV</th>
                                <th style="background-color: {{$confprov->backcolor}}; 
                                    color: {{$confprov->forecolor}};">
                                    LOTE
                                </th>
                                <th style="display:none; 
                                    background-color: {{$confprov->backcolor}}; 
                                    color: {{$confprov->forecolor}};">
                                    VENCE
                                </th>
                            @endif
                        @endif
                    </thead>

                    @php
                    $iFila = 0;
                    $fechaHoy = date('Y-m-d');
                    $fechax = strtotime('-7 day', strtotime($fechaHoy));
                    $desde = date('Y-m-d', $fechax).' 00:00:00';
                    $hasta = $fechaHoy.' 23:59:00';
                    @endphp
                    @if (isset($catalogo))
                        @foreach ($catalogo as $cat)
                            @if ($tpactivo == 'MAESTRO')
                                @if ($tipo == "C")
                                    @php
                                    $dataprod = obtenerDataTpmaestra($cat, $provs, 0);
                                    if (is_null($dataprod))
                                        continue;
                                    $chart_data = "";
                                    $tprnk1 = $dataprod['tprnk1'];
                                    $mpp = $dataprod['mpp'];
                                    $invConsol = $dataprod['invConsol'];
                                    $mayorInv = $dataprod['mayorInv'];
                                    $proves = $dataprod['arrayProv'];
                                    $arrayRnk = $dataprod['arrayRnk'];
                                    $costo = 0.00;
                                    $vmd = 0.0000;
                                    $dias = 0;
                                    $cant = 0;
                                    $tendencia = "";
                                    $sug15 = 0;
                                    $sug30 = 0;
                                    $sug60 = 0;
                                    $min = 0;
                                    $max = 0;
                                    $transito = verificarProdTransito($cat->barra,  "", "");
                                    $invent = verificarProdInventario($cat->barra,  "");
                                    if (!is_null($invent)) {
                                        $costo = $invent->costo/$factorInv;
                                        $vmd = $invent->vmd;
                                        $cant = $invent->cantidad;
                                        if ($vmd > 0)
                                            $dias = $cant/$vmd;
                                        $tendencia = MostrarTendenciaProd($codcli, $invent->codprod, $cfg->TendTolerancia);
                                        $sug15 = ($vmd*15)-($cant + $transito);
                                        $sug30 = ($vmd*30)-($cant + $transito);
                                        $sug60 = ($vmd*60)-($cant + $transito);
                                        if ($sug15 < 0)
                                            $sug15 = 0;
                                        if ($sug30 < 0)
                                            $sug30 = 0;
                                        if ($sug60 < 0)
                                            $sug60 = 0;
                                        $minmax = LeerMinMax($codcli, $invent->codprod);
                                        $min = $minmax["min"];
                                        $max = $minmax["max"];
                                    }
                                    $respDias = analisisSobreStock($dias, $min, $max);
                                    $iFila = $iFila + 1;
                                    @endphp
                                    <tr>
                                        <td>
                                            {{$iFila}}
                                            @if (isset($invent->codprod))
                                            <a href="{{URL::action('PedidoController@tendencia',$invent->codprod)}}">
                                                <h4>
                                                    <i class="{{$tendencia}}" aria-hidden="true"></i>
                                                </h4>
                                            </a>
                                            @endif
                                        </td>
                                        <td style="width: 100px; ">
                                            <div align="center" >
                                                <a href="{{URL::action('PedidoController@verprod',$cat->barra)}}">
                                                    <img src="http://isaweb.isbsistemas.com/public/storage/prod/{{NombreImagen($cat->barra)}}" 
                                                    class="img-responsive" 
                                                    alt="icompras360" width="100%" height="100%"
                                                    style="border: 2px solid #D2D6DE;" 
                                                    oncontextmenu="return false">
                                                </a>
                                            </div>
                                        </td>
                                        <td>
                                            <!-- AGREGAR A CARRO DE COMPRA -->
                                            <div style="width: 100px;">
                                                <span class="input-group-btn" style="width: 100px;">
                                                    <div class="col-xs-12 input-group" 
                                                        id="idAgregar-{{$iFila}}" >
                                                        <input type="number" 
                                                            style="text-align: center; 
                                                            color: #000000; 
                                                            width: 60px;" 
                                                            id="idPedir-{{$iFila}}" 
                                                            value=""
                                                            class="form-control" 
                                                            @if (Auth::user()->userAdmin == 0)
                                                                @if ($respDias['color'] == '#CCBB00')
                                                                    readonly=""
                                                                @endif
                                                            @endif 
                                                        >
                                                        
                                                        <button type="button" 
                                                            @if (Auth::user()->userAdmin == 0)
                                                                @if ($respDias['color'] == '#CCBB00')
                                                                    disabled="" 
                                                                @endif
                                                            @endif 
                                                            class="BtnAgregar btn btn-pedido

                                                            @if (VerificarCarrito($cat->barra, 'N'))
                                                            colorResaltado
                                                            @endif

                                                            " data-toggle="tooltip" title="Agregar al carrito"

                                                            id="idBtnAgregar-{{$iFila}}" >
                                                            <span class="fa fa-cart-plus" 
                                                                id="idAgregar-{{$iFila}}" 
                                                                aria-hidden="true">
                                                            </span>
                                                        </button>

                                                    </div>
                                                    <div  style="margin-left: 0px; 
                                                          margin-right: 0px; 
                                                          width: 100px;">
                                                        @php
                                                            $cont = count($arrayRnk);
                                                        @endphp
                                                        @if ($cont == 1)
                                                            @php
                                                                $confprov = LeerProve($arrayRnk[0]['codprove']);
                                                            @endphp
                                                            <span class="form-group" 
                                                                style="width: 100px;">

                                                                <img alt="icompras360"
                                                                style="width: 28px; 
                                                                float: left; 
                                                                background-color: #F0F0F0; 
                                                                margin-left: 2px;
                                                                margin-top: 4px;"
                                                                src="{{$rutalogoprov.$confprov->rutalogo1}}"> 

                                                                <span 
                                                                    style="background-color: #F0F0F0;
                                                                    width: 100px;"
                                                                    class="form-control" >
                                                                    &nbsp;{{$tprnk1}}
                                                                </span>

                                                                <input id="idProv-{{$iFila}}" 
                                                                    value="{{$tprnk1}}" 
                                                                    style="background-color: #F0F0F0; 
                                                                    width: 70px;"
                                                                    type="hidden" 
                                                                    readonly="" 
                                                                    class="form-control" >

                                                            </span>
                                                        @else
                                                            <select id="idProv-{{$iFila}}" 
                                                                class="form-control"  
                                                                style="width: 100px; ">
                                                                @for ($x=0; $x < $cont; $x++) 
                                                                    @if ($tprnk1==$arrayRnk[$x]['codprove']) 
                                                                        <option selected
                                        title="PRECIO: {{number_format($arrayRnk[$x]['liquida'], 2, '.', ',')}}"  
                                        value="{{ $arrayRnk[$x]['codprove'] }}" >

                                        {{ $arrayRnk[$x]['codprove'] }}
                                                                        </option>
                                                                    @else 
                                                                        <option 
                                        title="PRECIO: {{number_format($arrayRnk[$x]['liquida'], 2, '.', ',')}}  DIF: {{number_format($arrayRnk[$x]['liquida'] - $arrayRnk[0]['liquida'], 2, '.', ',')}}"  
                                        value="{{ $arrayRnk[$x]['codprove'] }}" >

                                        {{ $arrayRnk[$x]['codprove'] }}
                                                                        </option>
                                                                    @endif
                                                                @endfor
                                                            </select>
                                                        @endif
                                                    </div>
                                                </span>
                                            </div>
                                            <span style="text-align: right; 
                                                background-color: #F0F0F0;
                                                color: #000000; 
                                                width: 100px;
                                                height: 31px;"
                                                title="MEJOR PRECIO LIQUIDA" 
                                                class="form-control">
                                                <i class="fa fa-thumbs-o-up" aria-hidden="true"></i>
                                                {{number_format($arrayRnk[0]['liquida'], 2, '.', ',')}}
                                            </span>
                                        </td>
                                        <td style="width: 300px;"
                                            title="PRINCIPIO ACTIVO &#10============ &#10{{$cat->pactivo}}">
                                            <b>{{$cat->desprod}}</b>
                                            @if ($respDias['color'] == '#CCBB00')
                                            <br>
                                            <span style="color: red; font-size: 10px;">
                                            SOBRESTOCK!!
                                            </span>
                                            @endif
                                        </td>

                                        <td>
                                            <span title="CODIGO DE BARRA">
                                                <i class="fa fa-barcode">
                                                    {{$cat->barra}}
                                                </i>
                                            </span><br>
                                            <span title="MARCA DEL PRODUCTO">
                                                <i class="fa fa-shield">
                                                    {{$cat->marca}}    
                                                </i>
                                            </span><br>
                                            @if ($cat->bulto > 0)
                                            <span title="UNIDAD DEl BULTO">
                                                BULTO: {{$cat->bulto}}<br>
                                            </span>
                                            @endif
                                            @if ($cat->iva > 0)
                                            <span title="VALOR DEL IVA" >
                                                IVA: {{number_format($cat->iva, 2, '.', ',')}} %
                                            </span>
                                            @endif
                                        </td>
                                        <td style="display: none;">
                                            {{$cat->barra}}
                                        </td>
                                        <td style="display: none;" 
                                            align="right">
                                            {{$cat->bulto}}
                                        </td>
                                        <td style="display: none;"
                                            align="right">
                                            {{number_format($cat->iva, 2, '.', ',')}}
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
                                            <td style="display:none;">{{$tprnk1}}</td>
                                            <td style="display:none;">{{$tprnk1}}</td>
                                            @if ($mpp < $costo)
                                                <td align="right"
                                                    style="background-color: #FEE3CB; 
                                                    color: red;"
                                                    title = "EL MPP ES MENOR AL COSTO">
                                                    {{number_format($costo, 2, '.', ',')}}
                                                </td>
                                            @else
                                                <td align="right"
                                                    style="background-color: #FEE3CB;"
                                                    title = "COSTO">
                                                    {{number_format($costo, 2, '.', ',')}}
                                                </td>
                                            @endif
                                            <td align="right"
                                                style="background-color: #FEE3CB;"
                                                title = "VMD">
                                                {{number_format($vmd, 4, '.', ',')}}<br>
                                                <span style="background-color: #FEE3CB;
                                                    color: black;" 
                                                    title="DIAS MINIMO x PRODUCTO" >
                                                    MIN-> {{$min}}
                                                </span><br>
                                                <span style="background-color: #FEE3CB;
                                                    color: black;" 
                                                    title="DIAS MAXIMO x PRODUCTO" >
                                                    MAX-> {{$max}}
                                                </span><br>
                                            </td>
                                            <td align="right"
                                                style="background-color: #FEE3CB;
                                                color: {{$respDias['color']}}"
                                                title="{{$respDias['title']}}" >
                                                {{number_format($dias, 0, '.', ',')}}
                                                <br>
                                                <span style="background-color: #FEE3CB;
                                                color: black;" 
                                                title="SUGERIDO PARA 15 DIAS" >
                                                15-> {{number_format($sug15, 0, '.', ',')}}
                                                </span>
                                                <br>
                                                <span style="background-color: #FEE3CB;
                                                color: black;" 
                                                title="SUGERIDO PARA 30 DIAS" >
                                                30-> {{number_format($sug30, 0, '.', ',')}}
                                                </span>
                                                <br>
                                                <span style="background-color: #FEE3CB;
                                                color: black;"
                                                title="SUGERIDO PARA 60 DIAS" >
                                                60-> {{number_format($sug60, 0, '.', ',')}}
                                                </span>
                                            </td>
                                            <td align="right"
                                                style="background-color: #FEE3CB; 
                                                color: #000000;
                                                display: none;"
                                                title="SUGERIDO PARA 15 DIAS" >
                                                {{number_format($sug15, 0, '.', ',')}}
                                            </td> 
                                            <td align="right"
                                                style="background-color: #FEE3CB; 
                                                color: #000000;
                                                display: none;"
                                                title="SUGERIDO PARA 30 DIAS" >
                                                {{number_format($sug30, 0, '.', ',')}}
                                            </td> 
                                            <td align="right"
                                                style="background-color: #FEE3CB; 
                                                color: #000000;
                                                display: none;"
                                                title="SUGERIDO PARA 60 DIAS" >
                                                {{number_format($sug60, 0, '.', ',')}}
                                            </td> 
                                        @else
                                            <td align="right"
                                                title = "TRANSITO">
                                                {{number_format($transito, 0, '.', ',')}}
                                            </td>
                                            <td style="display:none;">
                                                {{number_format($cant, 0, '.', ',')}}    
                                            </td>
                                            <td style="display:none;">{{$tprnk1}}</td>
                                            <td style="display:none;">{{$tprnk1}}</td>
                                            <td style="display:none;">
                                                {{number_format($costo, 2, '.', ',')}}
                                            </td>
                                            <td style="display:none;">
                                                {{number_format($vmd, 4, '.', ',')}}
                                            </td>
                                            <td style="display:none;">
                                                {{number_format($dias, 0, '.', ',')}}
                                            </td>
                                        @endif    
                                        <td align="right"
                                            style="background-color: #FCD0C7;"
                                            title = "CONSOLIDADO">
                                            {{number_format($invConsol, 0, '.', ',')}}
                                        </td>
                                        @foreach ($proves as $prov)
                                            @php
                                                $codprove = strtolower($prov['codprove']);
                                                $factor = RetornaFactorCambiario($codprove, $moneda);
                                                $cantidad = $prov['cantidad'];
                                                $codprod = $prov['codprod'];;
                                                $fechafalla = $prov['fechafalla'];
                                                $lote = $prov['lote'];;
                                                $fecvence = $prov['fecvence'];
                                                $fecvence = str_replace("12:00:00 AM", "", $fecvence);
                                                $confprov = $prov['confprov']; 
                                                $tipoprecio = $prov['tipoprecio'];
                                                $actualizado = date('d-m-Y H:i', strtotime($prov['actualizado']));
                                                $dc = $prov['dc'];
                                                $di = $prov['di'];
                                                $pp = $prov['pp'];
                                                $dcred = $prov['dcredito'];
                                                $da = $prov['da'];
                                                $dpe = $prov['dpe']; 
                                                $upe = $prov['upe'];
                                                $precio = $prov['precio'];
                                                $liquida = $prov['liquida'];
                                                $ranking = obtenerRanking($liquida, $arrayRnk);
                                            @endphp

                                            <!--- PRECIO DEL PROVEEDOR -->
                                            <td align='right' 
                                                style="width: 380px; 
                                                word-wrap: break-word; 
                                                background-color: {{$confprov->backcolor}};
                                                color: {{$confprov->forecolor}}; " 
                                             
                                             @if ($liquida > 0)
                                             title = "{{$confprov->descripcion}} &#10 ======================== &#10 PRECIO: {{number_format($precio, 2, '.', ',')}} @if ($factor > 1) &#10 TASA: {{number_format($factor, 2, '.', ',')}} @endif &#10 TIPO: {{$tipoprecio}} &#10 DA: {{number_format($da, 2, '.', ',')}} &#10 DC: {{number_format($dc, 2, '.', ',')}} &#10 DI: {{number_format($di, 2, '.', ',')}} &#10 DP: {{number_format($dpe, 2, '.', ',')}} &#10 UP: {{$upe}} &#10 PP: {{number_format($pp, 2, '.', ',')}} &#10 DIAS CREDITO: {{$dcred}} &#10 IVA: {{number_format($cat->iva, 2, '.', ',')}} &#10 RANKING: {{$ranking}} &#10 LOTE: {{$lote}} &#10 VENCE: {{$fecvence}} &#10 CODIGO: {{$codprod}} &#10 ACTUALIZADO: {{$actualizado}} &#10 ======================== &#10 LIQUIDA: {{number_format($liquida, 2, '.', ',')}} &#10 "
                                             @endif >

                                             @if ($liquida == $mpp)
                                                <span style="font-size: 18px;">
                                                    {{number_format($liquida, 2, '.', ',')}} 
                                                </span>
                                             @else
                                                {{number_format($liquida, 2, '.', ',')}}
                                             @endif
                                             @if ($ranking)
                                                <div>
                                                    @if ($liquida == $mpp)
                                                        <span style="font-size: 18px;">
                                                            <i class="fa fa-thumbs-o-up"></i>
                                                        </span>
                                                    @endif
                                                    Rnk:{{$ranking}}
                                                </div>
                                             @endif
                                            </td>
         
                                            <!--- CANTIDAD DEL PROVEEDOR -->
                                            <td align='right' style="width: 200px; word-wrap: break-word; background-color: {{$confprov->backcolor}}; color: {{$confprov->forecolor}};" 

                                                title=" {{$confprov->descripcion}}&#10->CANTIDAD">
                                                @if ($mayorInv == $cantidad)
                                                    <span style="font-size: 18px;">
                                                        <i class="fa fa-thumbs-o-up"></i>
                                                        {{number_format($cantidad, 0, '.', ',')}}
                                                    </span>
                                                @else
                                                    {{number_format($cantidad, 0, '.', ',')}}
                                                @endif
                                                <span style="margin-top: 5px;">
                                                @if ($dcred > 0)
                                                    <div style="margin-bottom: 1px;
                                                        border-radius: 5px; 
                                                        font-size: 10px;
                                                        text-align: center;
                                                        padding: 1px; 
                                                        color: white;
                                                        background-color: black;"
                                                        title="DIAS DE CREDITO: {{$dcred}}">
                                                        DIAS: {{$dcred}} 
                                                    </div>
                                                @else
                                                    <div style="margin-bottom: 1px;
                                                        border-radius: 5px; 
                                                        font-size: 12px;
                                                        text-align: center;
                                                        padding: 1px; 
                                                        color: white;">
                                                        &nbsp;
                                                    </div>
                                                @endif
                                                @if ($dpe > 0)
                                                    <div style="margin-bottom: 1px;
                                                        border-radius: 5px; 
                                                        font-size: 10px;
                                                        text-align: center;
                                                        padding: 1px; 
                                                        color: white;
                                                        background-color: black;"
                                                        title="DESCUENTO DE PRE-EMPAQUE: {{QuitarCerosDecimales($dpe)}}% => {{$upe}}">
                                                        DP: {{QuitarCerosDecimales($dpe)}} % => {{$upe}}
                                                    </div>
                                                @else
                                                    <div style="margin-bottom: 1px;
                                                        border-radius: 5px; 
                                                        font-size: 12px;
                                                        text-align: center;
                                                        padding: 1px; 
                                                        color: white;">
                                                        &nbsp;
                                                    </div>
                                                @endif
                                                @if ($fecvence != "N/A")
                                                    @if (validarVence($fecvence, $cliente->mesNotVence))
                                                    <div style="
                                                        border-radius: 5px; 
                                                        font-size: 10px;
                                                        text-align: center;
                                                        padding: 1px; 
                                                        color: white;
                                                        background-color: black;"
                                                        title="FECHA DE VENCIMIENTO: {{$fecvence}}">
                                                        {{$fecvence}}
                                                    </div>
                                                    @endif
                                                @endif
                                                </span>
                                            </td>
                                    
                                            <td style="display:none;">{{$da}}</td>
                                            <td style="display:none;">{{$codprod}}</td>
                                            <td style="display:none;">{{$fechafalla}}</td>
                                        @endforeach
                                    </tr>
                                @endif
                                @if ($tipo == "E")
                                    @foreach ($provs as $prov) 
                                        @php
                                        $codprove = strtolower($prov->codprove);
                                        $factor = RetornaFactorCambiario($codprove, $moneda);
                                        try {
                                            $campos = $cat->$codprove;
                                            $campo = explode("|", $campos);
                                        } catch (Exception $e) {
                                            continue;
                                        }
                                        $confprov = LeerProve($prov->codprove);
                                        if (is_null($confprov))
                                            continue;
                                        $precio = $campo[0];
                                        $cantidad = $campo[1];
                                        $dc = $prov->dcme;
                                        $di = $prov->di;
                                        $pp = $prov->ppme;
                                        $da = 0.00;
                                        $dcredito = $campo[12];
                                        $dp = $campo[10];
                                        $up = $campo[11];
                                        $tipoprecio = $prov->tipoprecio;
                                        if ($tipoprecio == $confprov->aplicarDaPrecio)
                                            $da = $campo[2];
                                        switch ($tipoprecio) {
                                            case 1:
                                                $precio = $campo[0]/$factor;
                                                break;
                                            case 2:
                                                $precio = $campo[5]/$factor;
                                                break;
                                            case 3:
                                                $precio = $campo[6]/$factor;
                                                break;
                                            default:
                                                $precio = $campo[0]/$factor;
                                                break;
                                        }
                                        $neto = CalculaPrecioNeto($precio, $da, $di, $dc, $pp, 0.00);
                                        $liquida = $neto + (($neto * $cat->iva)/100);
                                        $arrayRnk[] = [
                                            'liquida' => $liquida,
                                            'codprove' => $codprove
                                        ];
                                        $codprod = $campo[3];
                                        $lote = $campo[7];
                                        $fecvence = $campo[8];
                                        $fecvence = str_replace("12:00:00 AM", "", $fecvence);
                                        $fechafalla = date('Y-m-d H:i:s', strtotime($campo[4])); 
                                        $oferta = str_replace('.00', '', $da);
                                        $oferta = str_replace('.0', '', $oferta);
                                        $dpe = str_replace('.00', '', $dp);
                                        $dpe = str_replace('.0', '', $dpe); 
                                        @endphp

                                        @if ($fechafalla >= $desde && $fechafalla <= $hasta)
                                            <tr>
                                                @php 
                                                $tpselect = $prov->codprove;
                                                $tprnk1 = obtenerCodprovRnk1($cat->barra,$provs);
                                                $costo = 0.00;
                                                $vmd = 0.0000;
                                                $dias = 0;
                                                $min = 0;
                                                $max = 0;
                                                $cant = 0;
                                                $tendencia = "";
                                                $sug15 = 0;
                                                $sug30 = 0;
                                                $sug60 = 0;
                                                $transito = verificarProdTransito($cat->barra,  "", "");
                                                $invent = verificarProdInventario($cat->barra,  "");
                                                if (!is_null($invent)) {
                                                    $costo = $invent->costo/$factorInv;
                                                    $vmd = $invent->vmd;
                                                    $cant = $invent->cantidad;
                                                    if ($vmd > 0)
                                                        $dias = $cant/$vmd;
                                                    $tendencia = MostrarTendenciaProd($codcli, $invent->codprod, $cfg->TendTolerancia);
                                                    $sug15 = ($vmd*15)-($cant + $transito);
                                                    $sug30 = ($vmd*30)-($cant + $transito);
                                                    $sug60 = ($vmd*60)-($cant + $transito);
                                                    if ($sug15 < 0)
                                                        $sug15 = 0;
                                                    if ($sug30 < 0)
                                                        $sug30 = 0;
                                                    if ($sug60 < 0)
                                                        $sug60 = 0;
                                                    $minmax = LeerMinMax($codcli, $invent->codprod);
                                                    $min = $minmax["min"];
                                                    $max = $minmax["max"];
                                                }
                                                $respDias = analisisSobreStock($dias, $min, $max); 
                                                $iFila = $iFila + 1;
                                                @endphp
                                                <td>
                                                    {{$iFila}}
                                                    @if (isset($invent->codprod))
                                                    <a href="{{URL::action('PedidoController@tendencia',$invent->codprod)}}">
                                                        <h4>
                                                            <i class="{{$tendencia}}" aria-hidden="true"></i>
                                                        </h4>
                                                    </a>
                                                    @endif
                                                </td>
                                                <td style="width: 60px;">
                                                    <div align="center">
                                                        <a href="{{URL::action('PedidoController@verprod',$cat->barra)}}">

                                                            <img src="http://isaweb.isbsistemas.com/public/storage/prod/{{NombreImagen($cat->barra)}}" 
                                                            width="100%" height="100%" 
                                                            class="img-responsive" 
                                                            alt="icompras360" 
                                                            style="border: 2px solid #D2D6DE;"
                                                            oncontextmenu="return false">
                                            
                                                        </a>
                                                    </div>
                                                </td>
                                                <td>
                                                    <!-- AGREGAR A CARRO DE COMPRA -->
                                                    <div class="col-xs-12 input-group" 
                                                        id="idAgregar-{{$iFila}}" >
                                                        <input type="number" 
                                                            style="text-align: 
                                                            center; color: #000000; width: 71px;" 
                                                            id="idPedir-{{$iFila}}" 
                                                            value="" 
                                                            class="form-control" >
                                                        <span class="input-group-btn BtnAgregar">
                                                            
                                                            <button type="button" class="btn btn-pedido

                                                            @if (VerificarCarrito($cat->barra, 'N'))
                                                                colorResaltado
                                                            @endif

                                                            " data-toggle="tooltip" title="Agregar al carrito" id="idBtnAgregar-{{$iFila}}" >
                                                                <span class="fa fa-cart-plus" id="idAgregar-{{$iFila}}" aria-hidden="true"></span>
                                                            </button>
                                                        
                                                        </span>
                                                    </div>
                                                    
                                                    <img alt="icompras360"
                                                        style="width: 28px; 
                                                        float: left; 
                                                        background-color: #F0F0F0; 
                                                        margin-left: 2px;
                                                        margin-top: 4px;"
                                                        src="{{$rutalogoprov.$confprov->rutalogo1}}"> 
                                                    <span 
                                                        style="background-color: #F0F0F0;
                                                        width: 110px;"
                                                        class="form-control" >
                                                        &nbsp;{{$confprov->codprove}}
                                                    </span>

                                                </td>
                                                <td style="width: 300px;"
                                                    title="PRINCIPIO ACTIVO &#10============ &#10{{$cat->pactivo}}">
                                                    <b>{{$cat->desprod}}</b>
                                                    <div style="margin-top: 5px;"></biv>
                                                    @if ($da > 0)
                                                        <span style="font-size: 10px;">
                                                            DA: 
                                                        </span>
                                                        <span style="border-radius: 5px; 
                                                            font-size: 14px;
                                                            text-align: center;
                                                            padding: 2px; 
                                                            width: 70px;
                                                            color: white;
                                                            background-color: #26328C;
                                                            margin-right: 4px;"
                                                            title="DESCUENTO ADICIONAL: {{$oferta}} %">
                                                            {{$oferta}} %
                                                        </span>
                                                    @endif
                                                    @if ($dcredito > 0)
                                                        <span style="font-size: 10px;">
                                                            DIAS: 
                                                        </span>
                                                        <span style="border-radius: 5px; 
                                                            font-size: 14px;
                                                            text-align: center;
                                                            padding: 2px; 
                                                            width: 70px;
                                                            color: white;
                                                            background-color: #26328C;
                                                            margin-right: 4px;"
                                                            title="DIAS DE CREDITO: {{$dcredito}}">
                                                            {{$dcredito}} 
                                                        </span>
                                                    @endif
                                                    <div style="margin-top: 5px;"></biv>
                                                    @if ($dpe > 0)
                                                        <span style="font-size: 10px;">
                                                            DP: 
                                                        </span>
                                                        <span style="border-radius: 5px; 
                                                            font-size: 14px;
                                                            text-align: center;
                                                            padding: 2px;  
                                                            width: 70px;
                                                            color: white;
                                                            background-color: #0061A8;
                                                            margin-right: 4px;"
                                                            title="DESCUENTO POR PRE-EMPAQUE: {{QuitarCerosDecimales($dpe)}} %">
                                                            {{QuitarCerosDecimales($dpe)}} %
                                                        </span>
                                                        <span style="font-size: 10px;">
                                                            UP: 
                                                        </span>
                                                        <span style="border-radius: 5px; 
                                                            font-size: 14px;
                                                            text-align: center;
                                                            padding: 2px; 
                                                            width: 70px;
                                                            color: white;
                                                            background-color: #0061A8;
                                                            margin-right: 4px;"
                                                            title="UNIDAD DE PRE-EMPAQUE: {{$up}}">
                                                            {{$up}} 
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span title="CODIGO DE BARRA">
                                                        <i class="fa fa-barcode">
                                                            {{$cat->barra}}
                                                        </i>
                                                    </span><br>
                                                    <span title="MARCA DEL PRODUCTO">
                                                        <i class="fa fa-shield">
                                                            {{$cat->marca}}    
                                                        </i>
                                                    </span><br>
                                                    @if ($cat->bulto > 0)
                                                    <span title="UNIDAD DEl BULTO">
                                                        BULTO: {{$cat->bulto}}<br>
                                                    </span>
                                                    @endif
                                                    @if ($cat->iva > 0)
                                                    <span title="VALOR DEL IVA" >
                                                        IVA: {{number_format($cat->iva, 2, '.', ',')}} %
                                                    </span>
                                                    @endif
                                                </td>
                                                <td style="display: none;">
                                                    {{$cat->barra}}
                                                </td>
                                                <td style="display: none;"
                                                    align="right">
                                                    {{$cat->bulto}}
                                                </td>
                                                <td style="display: none;"
                                                    align="right">
                                                    {{number_format($cat->iva, 2, '.', ',')}}
                                                </td>
                                                @if (Auth::user()->versionLight == 0)
                                                    <td align="right"
                                                        style="background-color: #FEE3CB;"
                                                        title = "TRANSITO">
                                                        {{number_format($transito, 0, '.', ',')}}
                                                    </td>
                                                    <td align="right"
                                                        style="background-color: #FEE3CB;"
                                                        title = "INVENTARIO">
                                                        {{number_format($cant, 0, '.', ',')}}
                                                    </td>
                                                    <td style="display:none;">
                                                        {{$tpselect}}
                                                    </td>
                                                    <td style="display:none;">
                                                        {{$tprnk1}}
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
                                                    <td align="right"
                                                        style="background-color: #FEE3CB;
                                                        color: {{$respDias['color']}}"
                                                        title="{{$respDias['title']}}" >
                                                        {{number_format($dias, 0, '.', ',')}}

                                                        <br>
                                                        <span style="background-color: #FEE3CB;
                                                        color: black;" 
                                                        title="SUGERIDO PARA 15 DIAS" >
                                                        15-> {{number_format($sug15, 0, '.', ',')}}
                                                        </span>
                                                        <br>
                                                        <span style="background-color: #FEE3CB;
                                                        color: black;" 
                                                        title="SUGERIDO PARA 30 DIAS" >
                                                        30-> {{number_format($sug30, 0, '.', ',')}}
                                                        </span>
                                                        <br>
                                                        <span style="background-color: #FEE3CB;
                                                        color: black;"
                                                        title="SUGERIDO PARA 60 DIAS" >
                                                        60-> {{number_format($sug60, 0, '.', ',')}}
                                                        </span>

                                                    </td> 
                                                    <td align="right"
                                                        style="background-color: #FEE3CB; 
                                                        color: #000000;
                                                        display:none;"
                                                        title="SUGERIDO PARA 15 DIAS" >
                                                        {{number_format($sug15, 0, '.', ',')}}
                                                    </td> 
                                                    <td align="right"
                                                        style="background-color: #FEE3CB; 
                                                        color: #000000;
                                                        display:none;"
                                                        title="SUGERIDO PARA 30 DIAS" >
                                                        {{number_format($sug30, 0, '.', ',')}}
                                                    </td> 
                                                    <td align="right"
                                                        style="background-color: #FEE3CB; 
                                                        color: #000000;
                                                        display:none;"
                                                        title="SUGERIDO PARA 60 DIAS" >
                                                        {{number_format($sug60, 0, '.', ',')}}
                                                    </td> 
                                                @else
                                                    <td align="right"
                                                        title = "TRANSITO">
                                                        {{number_format($transito, 0, '.', ',')}}
                                                    </td>
                                                    <td style="display:none;">
                                                        {{number_format($cant, 0, '.', ',')}}
                                                    </td>
                                                    <td style="display:none;">
                                                        {{$tpselect}}
                                                    </td>
                                                    <td style="display:none;">
                                                        {{$tprnk1}}
                                                    </td>
                                                    <td style="display:none;">
                                                        {{number_format($costo, 2, '.', ',')}}
                                                    </td>
                                                    <td style="display:none;">
                                                        {{number_format($vmd, 4, '.', ',')}}
                                                    </td>
                                                    <td style="display:none;">
                                                        {{number_format($dias, 0, '.', ',')}}
                                                    </td>
                                                @endif      
                                                <td align='right' 
                                                    style="background-color: {{$confprov->backcolor}}; 
                                                    color: {{$confprov->forecolor}};"
                                                    title="{{$confprov->descripcion}}&#10->PRECIO">{{number_format($liquida, 2, '.', ',')}}
                                                </td>
                                                <td align='right' 
                                                    style="background-color: {{$confprov->backcolor}}; 
                                                    color: {{$confprov->forecolor}};" 
                                                    title="{{$confprov->descripcion}}&#10->CANTIDAD">{{number_format($cantidad, 0, '.', ',')}}
                                                </td>
                                                <td align='right'
                                                    style="display: none; 
                                                    background-color: {{$confprov->backcolor}}; 
                                                    color: {{$confprov->forecolor}};" 
                                                    title="{{$confprov->descripcion}}&#10->DA">
                                                    @if ($da > 0)
                                                        <b>{{number_format($da, 2, '.', ',')}}</b>
                                                    @else
                                                        {{number_format($da, 2, '.', ',')}}
                                                    @endif
                                                </td>
                                                <td style="background-color: {{$confprov->backcolor}}; 
                                                    color: {{$confprov->forecolor}};"
                                                    title="{{$confprov->descripcion}}&#10->CODIGO">
                                                    {{$codprod}}
                                                </td>
                                                <td align='center' 
                                                    style="width: 100px; 
                                                    background-color: {{$confprov->backcolor}}; 
                                                    color: {{$confprov->forecolor}};"
                                                    title="{{$confprov->descripcion}}&#10->ENTRADA">
                                                    {{date('d-m-Y', strtotime($fechafalla))}}
                                                </td>
                                                <td style="display:none; background-color: {{$confprov->backcolor}}; 
                                                    color: {{$confprov->forecolor}};"
                                                    title="{{$confprov->descripcion}}&#10->PROVEEDOR">
                                                    <img style="width: 20px; 
                                                    height: 20px; float: left;" 
                                                    src="{{$rutalogoprov.$confprov->rutalogo1}}" 
                                                    alt="icompras360">
                                                    &nbsp;{{$confprov->descripcion}}
                                                </td>
                                                <td style="display:none;">
                                                    {{$prov->codprove}}
                                                </td>
                                                <td style="background-color: {{$confprov->backcolor}}; 
                                                    color: {{$confprov->forecolor}};"
                                                    title="{{$confprov->descripcion}}&#10->LOTE">
                                                    {{$lote}}<br>
                                                    <span title="VENCIMIENTO DEL PRODUCTO">
                                                        {{$fecvence}}
                                                    </span>
                                                </td>
                                                <td style="display: none; 
                                                    width: 80px; 
                                                    background-color: {{$confprov->backcolor}}; 
                                                    color: {{$confprov->forecolor}};"
                                                    title="{{$confprov->descripcion}}&#10->VENCE">
                                                    {{$fecvence}}
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endif
                                @if ($tipo == "O")
                                    @foreach ($provs as $prov) 
                                        @php
                                        $codprove = strtolower($prov->codprove);
                                        $factor = RetornaFactorCambiario($codprove, $moneda);
                                        try {
                                            $campos = $cat->$codprove;
                                            $campo = explode("|", $campos);
                                        } catch (Exception $e) {
                                            continue;
                                        }
                                        $confprov = LeerProve($prov->codprove); 
                                        if (is_null($confprov))
                                            continue;
                                        $precio = $campo[0];
                                        $cantidad = $campo[1];
                                        $dc = $prov->dcme;
                                        $di = $prov->di;
                                        $pp = $prov->ppme;
                                        $da = 0.00;
                                        $dcredito = $campo[12];
                                        $dp = $campo[10];
                                        $up = $campo[11];
                                        $tipoprecio = $prov->tipoprecio;
                                        if ($tipoprecio == $confprov->aplicarDaPrecio)
                                            $da = $campo[2];
                                        switch ($tipoprecio) {
                                            case 1:
                                                $precio = $campo[0]/$factor;
                                                break;
                                            case 2:
                                                $precio = $campo[5]/$factor;
                                                break;
                                            case 3:
                                                $precio = $campo[6]/$factor;
                                                break;
                                            default:
                                                $precio = $campo[0]/$factor;
                                                break;
                                        }
                                        $neto = CalculaPrecioNeto($precio, $da, $di, $dc, $pp, 0.00);
                                        $liquida = $neto + (($neto * $cat->iva)/100);
                                        $codprod = $campo[3];
                                        $lote = $campo[7];
                                        $fecvence = $campo[8];
                                        $fecvence = str_replace("12:00:00 AM", "", $fecvence);
                                        $fechafalla = date('Y-m-d H:i:s', strtotime($campo[4])); 
                                        $oferta = str_replace('.00', '', $da);
                                        $oferta = str_replace('.0', '', $oferta);
                                        $dpe = str_replace('.00', '', $dp);
                                        $dpe = str_replace('.0', '', $dpe); 
                                        @endphp
                                        @if (($cantidad > 0) && ($da > 0)) 
                                            <tr>
                                                @php 
                                                $tpselect = $prov->codprove;
                                                $tprnk1 = obtenerCodprovRnk1($cat->barra,$provs);
                                                $costo = 0.00;
                                                $vmd = 0.0000;
                                                $dias = 0;
                                                $min = 0;
                                                $max = 0;
                                                $cant = 0;
                                                $tendencia = "";
                                                $sug15 = 0;
                                                $sug30 = 0;
                                                $sug60 = 0;
                                                $transito = verificarProdTransito($cat->barra,  "", "");
                                                $invent = verificarProdInventario($cat->barra,  "");
                                                if (!is_null($invent)) {
                                                    $costo = $invent->costo;
                                                    $vmd = $invent->vmd;
                                                    $cant = $invent->cantidad;
                                                    if ($vmd > 0)
                                                        $dias = $cant/$vmd;
                                                    $tendencia = MostrarTendenciaProd($codcli, $invent->codprod, $cfg->TendTolerancia);
                                                    $sug15 = ($vmd*15)-($cant + $transito);
                                                    $sug30 = ($vmd*30)-($cant + $transito);
                                                    $sug60 = ($vmd*60)-($cant + $transito);
                                                    if ($sug15 < 0)
                                                        $sug15 = 0;
                                                    if ($sug30 < 0)
                                                        $sug30 = 0;
                                                    if ($sug60 < 0)
                                                        $sug60 = 0;
                                                    $minmax = LeerMinMax($codcli, $invent->codprod);
                                                    $min = $minmax["min"];
                                                    $max = $minmax["max"];
                                                }
                                                $respDias = analisisSobreStock($dias, $min, $max); 
                                                $iFila = $iFila + 1;
                                                @endphp
                                                <td>
                                                    {{$iFila}}
                                                    @if (isset($invent->codprod))
                                                    <a href="{{URL::action('PedidoController@tendencia',$invent->codprod)}}">
                                                        <h4>
                                                            <i class="{{$tendencia}}" aria-hidden="true"></i>
                                                        </h4>
                                                    </a>
                                                    @endif
                                                </td>
                                                <td style="width: 60px;">
                                                    <div align="center">
                                                        <a href="{{URL::action('PedidoController@verprod',$cat->barra)}}">

                                                            <img src="http://isaweb.isbsistemas.com/public/storage/prod/{{NombreImagen($cat->barra)}}" 
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
                                                    <!-- AGREGAR A CARRO DE COMPRA -->
                                                    <div class="col-xs-12 input-group" 
                                                        id="idAgregar-{{$iFila}}" >
                                                        <input type="number" 
                                                            style="text-align: center; 
                                                            color: #000000; 
                                                            width: 71px;" 
                                                            id="idPedir-{{$iFila}}" 
                                                            value="" 
                                                            class="form-control" 
                                                        >
                                                        <span class="input-group-btn BtnAgregar">
                                                            
                                                            <button type="button" class="btn btn-pedido

                                                            @if (VerificarCarrito($cat->barra, 'N'))
                                                                colorResaltado
                                                            @endif

                                                            " data-toggle="tooltip" title="Agregar al carrito"
                                                            id="idBtnAgregar-{{$iFila}}" >
                                                                <span class="fa fa-cart-plus" id="idAgregar-{{$iFila}}" aria-hidden="true"></span>
                                                            </button>
                                                        
                                                        </span>
                                                    </div>
                                                    <img alt="icompras360"
                                                        style="width: 28px; 
                                                        float: left; 
                                                        background-color: #F0F0F0; 
                                                        margin-left: 2px;
                                                        margin-top: 4px;"
                                                        src="{{$rutalogoprov.$confprov->rutalogo1}}"> 
                                                    <span 
                                                        style="background-color: #F0F0F0;
                                                        width: 110px;"
                                                        class="form-control" >
                                                        &nbsp;{{$confprov->codprove}}
                                                    </span>
                                                </td>
                                                <td style="width: 300px;"
                                                    title="PRINCIPIO ACTIVO &#10============ &#10{{$cat->pactivo}}">
                                                    <b>{{$cat->desprod}}</b>
                                                    <div style="margin-top: 5px;"></biv>
                                                    @if ($da > 0)
                                                        <span style="font-size: 10px;">
                                                            DA: 
                                                        </span>
                                                        <span style="border-radius: 5px; 
                                                            font-size: 14px;
                                                            text-align: center;
                                                            padding: 2px; 
                                                            width: 70px;
                                                            color: white;
                                                            background-color: #26328C;
                                                            margin-right: 4px;"
                                                            title="DESCUENTO ADICIONAL: {{$oferta}} %">
                                                            {{$oferta}} %
                                                        </span>
                                                    @endif
                                                    @if ($dcredito > 0)
                                                        <span style="font-size: 10px;">
                                                            DIAS: 
                                                        </span>
                                                        <span style="border-radius: 5px; 
                                                            font-size: 14px;
                                                            text-align: center;
                                                            padding: 2px; 
                                                            width: 70px;
                                                            color: white;
                                                            background-color: #26328C;
                                                            margin-right: 4px;"
                                                            title="DIAS DE CREDITO: {{$dcredito}}">
                                                            {{$dcredito}} 
                                                        </span>
                                                    @endif
                                                    <div style="margin-top: 5px;"></biv>
                                                    @if ($dpe > 0)
                                                        <span style="font-size: 10px;">
                                                            DP: 
                                                        </span>
                                                        <span style="border-radius: 5px; 
                                                            font-size: 14px;
                                                            text-align: center;
                                                            padding: 2px;  
                                                            width: 70px;
                                                            color: white;
                                                            background-color: #0061A8;
                                                            margin-right: 4px;"
                                                            title="DESCUENTO POR PRE-EMPAQUE: {{QuitarCerosDecimales($dpe)}} %">
                                                            {{QuitarCerosDecimales($dpe)}} %
                                                        </span>
                                                        <span style="font-size: 10px;">
                                                            UP: 
                                                        </span>
                                                        <span style="border-radius: 5px; 
                                                            font-size: 14px;
                                                            text-align: center;
                                                            padding: 2px; 
                                                            width: 70px;
                                                            color: white;
                                                            background-color: #0061A8;
                                                            margin-right: 4px;"
                                                            title="UNIDAD DE PRE-EMPAQUE: {{$up}}">
                                                            {{$up}} 
                                                        </span>
                                                    @endif
                                                </td>
                                                
                                                <td>
                                                    <span title="CODIGO DE BARRA">
                                                        <i class="fa fa-barcode">
                                                            {{$cat->barra}}
                                                        </i>
                                                    </span><br>
                                                    <span title="MARCA DEL PRODUCTO">
                                                        <i class="fa fa-shield">
                                                            {{$cat->marca}}    
                                                        </i>
                                                    </span><br>
                                                    @if ($cat->bulto > 0)
                                                    <span title="UNIDAD DEl BULTO">
                                                        BULTO: {{$cat->bulto}}<br>
                                                    </span>
                                                    @endif
                                                    @if ($cat->iva > 0)
                                                    <span title="VALOR DEL IVA" >
                                                        IVA: {{number_format($cat->iva, 2, '.', ',')}} %
                                                    </span>
                                                    @endif
                                                </td>
                                                <td style="display: none;">
                                                    {{$cat->barra}}
                                                </td>
                                                <td style="display: none;"
                                                    align="right">
                                                    {{$cat->bulto}}
                                                </td>
                                                <td style="display: none;"
                                                    align="right">
                                                    {{number_format($cat->iva, 2, '.', ',')}}
                                                </td>

                                                @if (Auth::user()->versionLight == 0)
                                                    <td align="right"
                                                        style="background-color: #FEE3CB;"
                                                        title = "TRANSITO">
                                                        {{number_format($transito, 0, '.', ',')}}
                                                    </td>
                                                    <td align="right"
                                                        style="background-color: #FEE3CB;"
                                                        title = "INVENTARIO">
                                                        {{number_format($cant, 0, '.', ',')}}
                                                    </td>
                                                    <td style="display:none;">
                                                        {{$tpselect}}
                                                    </td>
                                                    <td style="display:none;">
                                                        {{$tprnk1}}
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
                                                    <td align="right"
                                                        style="background-color: #FEE3CB;
                                                        color: {{$respDias['color']}}"
                                                        title="{{$respDias['title']}}" >
                                                        {{number_format($dias, 0, '.', ',')}}

                                                        <br>
                                                        <span style="background-color: #FEE3CB;
                                                        color: black;" 
                                                        title="SUGERIDO PARA 15 DIAS" >
                                                        15-> {{number_format($sug15, 0, '.', ',')}}
                                                        </span>
                                                        <br>
                                                        <span style="background-color: #FEE3CB;
                                                        color: black;" 
                                                        title="SUGERIDO PARA 30 DIAS" >
                                                        30-> {{number_format($sug30, 0, '.', ',')}}
                                                        </span>
                                                        <br>
                                                        <span style="background-color: #FEE3CB;
                                                        color: black;"
                                                        title="SUGERIDO PARA 60 DIAS" >
                                                        60-> {{number_format($sug60, 0, '.', ',')}}
                                                        </span>

                                                    </td>     
                                                    <td align="right"
                                                        style="background-color: #FEE3CB; 
                                                        color: #000000;
                                                        display:none;"
                                                        title="SUGERIDO PARA 15 DIAS" >
                                                        {{number_format($sug15, 0, '.', ',')}}
                                                    </td> 
                                                    <td align="right"
                                                        style="background-color: #FEE3CB; 
                                                        color: #000000;
                                                        display:none;"
                                                        title="SUGERIDO PARA 30 DIAS" >
                                                        {{number_format($sug30, 0, '.', ',')}}
                                                    </td> 
                                                    <td align="right"
                                                        style="background-color: #FEE3CB; 
                                                        color: #000000;
                                                        display:none;"
                                                        title="SUGERIDO PARA 60 DIAS" >
                                                        {{number_format($sug60, 0, '.', ',')}}
                                                    </td> 
                                                @else
                                                    <td align="right"
                                                        title = "TRANSITO">
                                                        {{number_format($transito, 0, '.', ',')}}
                                                    </td>
                                                    <td style="display:none;">
                                                        {{number_format($cant, 0, '.', ',')}}
                                                    </td>
                                                    <td style="display:none;">
                                                        {{$tpselect}}
                                                    </td>
                                                    <td style="display:none;">
                                                        {{$tprnk1}}
                                                    </td>
                                                    <td style="display:none;">
                                                        {{number_format($costo, 2, '.', ',')}}
                                                    </td>
                                                    <td style="display:none;">
                                                        {{number_format($vmd, 4, '.', ',')}}
                                                    </td>
                                                    <td style="display:none;">
                                                        {{number_format($dias, 0, '.', ',')}}
                                                    </td>  
                                                @endif
                                                <td align='right' 
                                                    style="background-color: {{$confprov->backcolor}}; 
                                                    color: {{$confprov->forecolor}};"
                                                    title="{{$confprov->descripcion}}&#10->PRECIO">{{number_format($liquida, 2, '.', ',')}}
                                                </td>
                                                <td align='right' 
                                                    style="background-color: {{$confprov->backcolor}}; 
                                                    color: {{$confprov->forecolor}};" 
                                                    title="{{$confprov->descripcion}}&#10->CANTIDAD">{{number_format($cantidad, 0, '.', ',')}}
                                                </td>
                                                <td align="right" 
                                                    style="display: none; 
                                                    background-color: {{$confprov->backcolor}}; 
                                                    color: {{$confprov->forecolor}};"
                                                    title="{{$confprov->descripcion}}&#10->DA">
                                                    <b>{{number_format($da, 2, '.', ',')}}</b>
                                                </td>
                                                <td style="background-color: {{$confprov->backcolor}}; 
                                                    color: {{$confprov->forecolor}};"
                                                    title="{{$confprov->descripcion}}&#10->CODIGO">
                                                    {{$codprod}}
                                                </td>
                                                <td style="display:none;">
                                                    {{date('d-m-Y', strtotime($fechafalla))}}
                                                </td>
                                                <td style="display:none; background-color: {{$confprov->backcolor}}; 
                                                    color: {{$confprov->forecolor}};"
                                                    title="{{$confprov->descripcion}}&#10->PROVEEDOR">
                                                    <img style="width: 20px; 
                                                    height: 20px; float: left;" 
                                                    src="{{$rutalogoprov.$confprov->rutalogo1}}" 
                                                    alt="icompras360">
                                                    &nbsp;{{$confprov->descripcion}}
                                                </td>
                                                <td style="display:none;">
                                                    {{$prov->codprove}}
                                                </td>
                                                <td style="background-color: {{$confprov->backcolor}}; 
                                                    color: {{$confprov->forecolor}};"
                                                    title="{{$confprov->descripcion}}&#10->LOTE">
                                                    {{$lote}}<br>
                                                    <span title="VENCIMIENTO DEL PRODUCTO">
                                                        {{$fecvence}}
                                                    </span>
                                                </td>
                                                <td style="display: none; 
                                                    background-color: {{$confprov->backcolor}}; 
                                                    color: {{$confprov->forecolor}};"
                                                    title="{{$confprov->descripcion}}&#10->VENCE">
                                                    {{$fecvence}}
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endif
                                @if ($tipo == "R")
                                    @php
                                    $pedir = 1;
                                    $criterio = 'PRECIO';
                                    $preferencia = 'NINGUNA';
                                    $mejoropcion = BuscarMejorOpcion($cat->barra, $criterio, $preferencia, $pedir, $provs);
                                    if ($mejoropcion == null) {
                                        continue;
                                    }
                                    $codprove = $mejoropcion[0]['codprove'];
                                    $confprov = LeerProve($codprove); 
                                    if (is_null($confprov))
                                        continue;
                                    $factor = RetornaFactorCambiario($codprove, $moneda);
                                    try {
                                        $codprovex = strtolower($codprove);
                                        $campos = $cat->$codprovex;
                                        $campo = explode("|", $campos);
                                    } catch (Exception $e) {
                                        continue;
                                    }
                                    $precio = $campo[0];
                                    $cantidad = $campo[1];
                                    $maeclieprove = DB::table('maeclieprove')
                                    ->where('codcli','=',$codcli)
                                    ->where('codprove','=',$codprove)
                                    ->where('status','=','ACTIVO')
                                    ->first();
                                    if (empty($maeclieprove))
                                        continue;
                                    $dc = $maeclieprove->dcme;
                                    $di = $maeclieprove->di;
                                    $pp = $maeclieprove->ppme;
                                    $da = 0.00;
                                    $dcredito = $campo[12];
                                    $dp = $campo[10];
                                    $up = $campo[11];
                                    $tipoprecio = $maeclieprove->tipoprecio;
                                    if ($tipoprecio == $confprov->aplicarDaPrecio)
                                        $da = $campo[2];
                                    switch ($tipoprecio) {
                                        case 1:
                                            $precio = $campo[0]/$factor;
                                            break;
                                        case 2:
                                            $precio = $campo[5]/$factor;
                                            break;
                                        case 3:
                                            $precio = $campo[6]/$factor;
                                            break;
                                        default:
                                            $precio = $campo[0]/$factor;
                                            break;
                                    }
                                    $neto = CalculaPrecioNeto($precio, $da, $di, $dc, $pp, 0.00);
                                    $liquida = $neto + (($neto * $cat->iva)/100);
                                    $codprod = $campo[3];
                                    $lote = $campo[7];
                                    $fecvence = $campo[8];
                                    $fecvence = str_replace("12:00:00 AM", "", $fecvence);
                                    $fechafalla = date('Y-m-d H:i:s', strtotime($campo[4])); 
                                    $oferta = str_replace('.00', '', $da);
                                    $oferta = str_replace('.0', '', $oferta);
                                    $dpe = str_replace('.00', '', $dp);
                                    $dpe = str_replace('.0', '', $dpe); 
                                    @endphp
                                    @if ($cantidad > 0) 
                                        <tr>
                                            @php 
                                            $tpselect = $codprove;
                                            $tprnk1 = $codprove;
                                            $costo = 0.00;
                                            $vmd = 0.0000;
                                            $dias = 0;
                                            $min = 0;
                                            $max = 0;
                                            $cant = 0;
                                            $tendencia = "";
                                            $sug15 = 0;
                                            $sug30 = 0;
                                            $sug60 = 0;
                                            $transito = verificarProdTransito($cat->barra,  "", "");
                                            $invent = verificarProdInventario($cat->barra,  "");
                                            if (!is_null($invent)) {
                                                $costo = $invent->costo;
                                                $vmd = $invent->vmd;
                                                $cant = $invent->cantidad;
                                                if ($vmd > 0)
                                                    $dias = $cant/$vmd;
                                                $tendencia = MostrarTendenciaProd($codcli, $invent->codprod, $cfg->TendTolerancia);
                                                $sug15 = ($vmd*15)-($cant + $transito);
                                                $sug30 = ($vmd*30)-($cant + $transito);
                                                $sug60 = ($vmd*60)-($cant + $transito);
                                                if ($sug15 < 0)
                                                    $sug15 = 0;
                                                if ($sug30 < 0)
                                                    $sug30 = 0;
                                                if ($sug60 < 0)
                                                    $sug60 = 0;
                                                $minmax = LeerMinMax($codcli, $invent->codprod);
                                                $min = $minmax["min"];
                                                $max = $minmax["max"];
                                            }
                                            $respDias = analisisSobreStock($dias, $min, $max);
                                            $iFila = $iFila + 1;
                                            @endphp
                                            <td>
                                                {{$iFila}}
                                                @if (isset($invent->codprod))
                                                <a href="{{URL::action('PedidoController@tendencia',$invent->codprod)}}">
                                                    <h4>
                                                        <i class="{{$tendencia}}" aria-hidden="true"></i>
                                                    </h4>
                                                </a>
                                                @endif
                                            </td>
                                            <td style="width: 60px;">
                                                <div align="center">
                                                    <a href="{{URL::action('PedidoController@verprod',$cat->barra)}}">

                                                        <img src="http://isaweb.isbsistemas.com/public/storage/prod/{{NombreImagen($cat->barra)}}" 
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
                                                <!-- AGREGAR A CARRO DE COMPRA -->
                                                <div class="col-xs-12 input-group" 
                                                    id="idAgregar-{{$iFila}}" >
                                                    <input type="number" 
                                                        style="text-align: center; 
                                                        color: #000000; 
                                                        width: 71px;" 
                                                        id="idPedir-{{$iFila}}" 
                                                        value="" 
                                                        class="form-control" 
                                                    >
                                                    <span class="input-group-btn BtnAgregar">
                                                        
                                                        <button type="button" class="btn btn-pedido

                                                        @if (VerificarCarrito($cat->barra, 'N'))
                                                            colorResaltado
                                                        @endif

                                                        " data-toggle="tooltip" title="Agregar al carrito" id="idBtnAgregar-{{$iFila}}" >
                                                            <span class="fa fa-cart-plus" id="idAgregar-{{$iFila}}" aria-hidden="true"></span>
                                                        </button>
                                                    
                                                    </span>
                                                </div>
                                                <img alt="icompras360"
                                                    style="width: 28px; 
                                                    float: left; 
                                                    background-color: #F0F0F0; 
                                                    margin-left: 2px;
                                                    margin-top: 4px;"
                                                    src="{{$rutalogoprov.$confprov->rutalogo1}}"> 
                                                <span 
                                                    style="background-color: #F0F0F0;
                                                    width: 110px;"
                                                    class="form-control" >
                                                    &nbsp;{{$confprov->codprove}}
                                                </span>

                                            </td>
                                            <td style="width: 300px;"
                                                title="PRINCIPIO ACTIVO &#10============ &#10{{$cat->pactivo}}">
                                                <b>{{$cat->desprod}}</b>
                                                <div style="margin-top: 5px;"></biv>
                                                @if ($da > 0)
                                                    <span style="font-size: 10px;">
                                                        DA: 
                                                    </span>
                                                    <span style="border-radius: 5px; 
                                                        font-size: 14px;
                                                        text-align: center;
                                                        padding: 2px; 
                                                        width: 70px;
                                                        color: white;
                                                        background-color: #26328C;
                                                        margin-right: 4px;"
                                                        title="DESCUENTO ADICIONAL: {{$oferta}} %">
                                                        {{$oferta}} %
                                                    </span>
                                                @endif
                                                @if ($dcredito > 0)
                                                    <span style="font-size: 10px;">
                                                        DIAS: 
                                                    </span>
                                                    <span style="border-radius: 5px; 
                                                        font-size: 14px;
                                                        text-align: center;
                                                        padding: 2px; 
                                                        width: 70px;
                                                        color: white;
                                                        background-color: #26328C;
                                                        margin-right: 4px;"
                                                        title="DIAS DE CREDITO: {{$dcredito}}">
                                                        {{$dcredito}} 
                                                    </span>
                                                @endif
                                                <div style="margin-top: 5px;"></biv>
                                                @if ($dpe > 0)
                                                    <span style="font-size: 10px;">
                                                        DP: 
                                                    </span>
                                                    <span style="border-radius: 5px; 
                                                        font-size: 14px;
                                                        text-align: center;
                                                        padding: 2px;  
                                                        width: 70px;
                                                        color: white;
                                                        background-color: #0061A8;
                                                        margin-right: 4px;"
                                                        title="DESCUENTO POR PRE-EMPAQUE: {{QuitarCerosDecimales($dpe)}} %">
                                                        {{QuitarCerosDecimales($dpe)}} %
                                                    </span>
                                                    <span style="font-size: 10px;">
                                                        UP: 
                                                    </span>
                                                    <span style="border-radius: 5px; 
                                                        font-size: 14px;
                                                        text-align: center;
                                                        padding: 2px; 
                                                        width: 70px;
                                                        color: white;
                                                        background-color: #0061A8;
                                                        margin-right: 4px;"
                                                        title="UNIDAD DE PRE-EMPAQUE: {{$up}}">
                                                        {{$up}} 
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <span title="CODIGO DE BARRA">
                                                    <i class="fa fa-barcode">
                                                        {{$cat->barra}}
                                                    </i>
                                                </span><br>
                                                <span title="MARCA DEL PRODUCTO">
                                                    <i class="fa fa-shield">
                                                        {{$cat->marca}}    
                                                    </i>
                                                </span><br>
                                                @if ($cat->bulto > 0)
                                                <span title="UNIDAD DEl BULTO">
                                                    BULTO: {{$cat->bulto}}<br>
                                                </span>
                                                @endif
                                                @if ($cat->iva > 0)
                                                <span title="VALOR DEL IVA" >
                                                    IVA: {{number_format($cat->iva, 2, '.', ',')}} %
                                                </span>
                                                @endif
                                            </td>
                                            <td style="display: none;">
                                                {{$cat->barra}}
                                            </td>
                                            <td style="display: none;"
                                                align="right">
                                                {{$cat->bulto}}
                                            </td>
                                            <td style="display: none;"
                                                align="right">
                                                {{number_format($cat->iva, 2, '.', ',')}}
                                            </td>
                                            @if (Auth::user()->versionLight == 0)
                                                <td align="right"
                                                    style="background-color: #FEE3CB;"
                                                    title = "TRANSITO">
                                                    {{number_format($transito, 0, '.', ',')}}
                                                </td>
                                                <td align="right"
                                                    style="background-color: #FEE3CB;"
                                                    title = "INVENTARIO">
                                                    {{number_format($cant, 0, '.', ',')}}
                                                </td>
                                                <td style="display:none;">
                                                    {{$tpselect}}
                                                </td>
                                                <td style="display:none;">
                                                    {{$tprnk1}}
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
                                                <td align="right"
                                                    style="background-color: #FEE3CB;
                                                    color: {{$respDias['color']}}"
                                                    title="{{$respDias['title']}}" >
                                                    {{number_format($dias, 0, '.', ',')}}

                                                    <br>
                                                    <span style="background-color: #FEE3CB;
                                                    color: black;" 
                                                    title="SUGERIDO PARA 15 DIAS" >
                                                    15-> {{number_format($sug15, 0, '.', ',')}}
                                                    </span>
                                                    <br>
                                                    <span style="background-color: #FEE3CB;
                                                    color: black;" 
                                                    title="SUGERIDO PARA 30 DIAS" >
                                                    30-> {{number_format($sug30, 0, '.', ',')}}
                                                    </span>
                                                    <br>
                                                    <span style="background-color: #FEE3CB;
                                                    color: black;"
                                                    title="SUGERIDO PARA 60 DIAS" >
                                                    60-> {{number_format($sug60, 0, '.', ',')}}
                                                    </span>

                                                </td> 
                                                <td align="right"
                                                    style="background-color: #FEE3CB;
                                                    color: #000000;
                                                    display:none;"
                                                    title="SUGERIDO PARA 15 DIAS" >
                                                    {{number_format($sug15, 0, '.', ',')}}
                                                </td> 
                                                <td align="right"
                                                    style="background-color: #FEE3CB; 
                                                    color: #000000;
                                                    display:none;"
                                                    title="SUGERIDO PARA 30 DIAS" >
                                                    {{number_format($sug30, 0, '.', ',')}}
                                                </td> 
                                                <td align="right"
                                                    style="background-color: #FEE3CB; 
                                                    color: #000000;
                                                    display:none;"
                                                    title="SUGERIDO PARA 60 DIAS" >
                                                    {{number_format($sug60, 0, '.', ',')}}
                                                </td> 
                                            @else
                                                <td align="right"
                                                    title = "TRANSITO">
                                                    {{number_format($transito, 0, '.', ',')}}
                                                </td>
                                                <td style="display:none;">
                                                    {{number_format($cant, 0, '.', ',')}}
                                                </td>
                                                <td style="display:none;">
                                                    {{$tpselect}}
                                                </td>
                                                <td style="display:none;">
                                                    {{$tprnk1}}
                                                </td>
                                                <td style="display:none;">
                                                    {{number_format($costo, 2, '.', ',')}}
                                                </td>
                                                <td style="display:none;">
                                                    {{number_format($vmd, 4, '.', ',')}}
                                                </td>
                                                <td style="display:none;" >
                                                    {{number_format($dias, 0, '.', ',')}}
                                                </td>
                                            @endif      
                                            <td align='right' 
                                                style="background-color: {{$confprov->backcolor}}; 
                                                color: {{$confprov->forecolor}};"
                                                title="{{$confprov->descripcion}}&#10->PRECIO">
                                                {{number_format($liquida, 2, '.', ',')}}
                                            </td>
                                            <td align='right' 
                                                style="background-color: {{$confprov->backcolor}}; 
                                                color: {{$confprov->forecolor}};" 
                                                title="{{$confprov->descripcion}}&#10->CANTIDAD">
                                                {{number_format($cantidad, 0, '.', ',')}}
                                            </td>
                                            <td align='right'
                                                style="display: none; 
                                                background-color: {{$confprov->backcolor}}; 
                                                color: {{$confprov->forecolor}};"
                                                title="{{$confprov->descripcion}}&#10->DA">
                                                @if ($da > 0)
                                                    <b>{{number_format($da, 2, '.', ',')}}</b>
                                                @else
                                                    {{number_format($da, 2, '.', ',')}}
                                                @endif
                                            </td>
                                            <td style="background-color: {{$confprov->backcolor}}; 
                                                color: {{$confprov->forecolor}};"
                                                title="{{$confprov->descripcion}}&#10->CODIGO">
                                                {{$codprod}}
                                            </td>
                                            <td align='center' 
                                                style="display:none; 
                                                width: 100px; 
                                                background-color: {{$confprov->backcolor}}; 
                                                color: {{$confprov->forecolor}};"
                                                title="{{$confprov->descripcion}}&#10->ENTRADA">
                                                {{date('d-m-Y', strtotime($fechafalla))}}
                                            </td>
                                            <td style="display:none; background-color: {{$confprov->backcolor}}; 
                                                color: {{$confprov->forecolor}};"
                                                title="{{$confprov->descripcion}}&#10->PROVEEDOR">
                                                <img style="width: 20px; 
                                                height: 20px; float: left;" 
                                                src="{{$rutalogoprov.$confprov->rutalogo1}}" 
                                                alt="icompras360">
                                                &nbsp;{{$confprov->descripcion}}
                                            </td>
                                            <td style="display:none;">{{$codprove}}</td>
                                            <td style="background-color: {{$confprov->backcolor}}; 
                                                color: {{$confprov->forecolor}};"
                                                title="{{$confprov->descripcion}}&#10->LOTE">
                                                {{$lote}}<br>
                                                <span title="VENCIMIENTO DEL PRODUCTO">
                                                    {{$fecvence}}
                                                </span>
                                            </td>
                                            <td style="display: none; 
                                                width: 80px; 
                                                background-color: {{$confprov->backcolor}}; 
                                                color: {{$confprov->forecolor}};"
                                                title="{{$confprov->descripcion}}&#10->VENCE">
                                                {{$fecvence}}
                                            </td>
                                        </tr>
                                    @endif
                                @endif
                                @if ($tipo == "M")
                                    @php
                                    $dataprod = obtenerDataTpmaestra($cat, $provs, 1);
                                    if (is_null($dataprod))
                                        continue;
                                    $chart_data = "";
                                    $tprnk1 = $dataprod['tprnk1'];
                                    $mpp = $dataprod['mpp'];
                                    $invConsol = $dataprod['invConsol'];
                                    $mayorInv = $dataprod['mayorInv'];
                                    $proves = $dataprod['arrayProv'];
                                    $arrayRnk = $dataprod['arrayRnk'];
                                    $costo = 0.00;
                                    $vmd = 0.0000;
                                    $dias = 0;
                                    $min = 0;
                                    $max = 0;
                                    $cant = 0;
                                    $tendencia = "";
                                    $sug15 = 0;
                                    $sug30 = 0;
                                    $sug60 = 0;
                                    $transito = verificarProdTransito($cat->barra,  "", "");
                                    $invent = verificarProdInventario($cat->barra,  "");
                                    if (!is_null($invent)) {
                                        $costo = $invent->costo/$factorInv;
                                        $vmd = $invent->vmd;
                                        $cant = $invent->cantidad;
                                        if ($vmd > 0)
                                            $dias = $cant/$vmd;
                                        $tendencia = MostrarTendenciaProd($codcli, $invent->codprod, $cfg->TendTolerancia);
                                        $sug15 = ($vmd*15)-($cant + $transito);
                                        $sug30 = ($vmd*30)-($cant + $transito);
                                        $sug60 = ($vmd*60)-($cant + $transito);
                                        if ($sug15 < 0)
                                            $sug15 = 0;
                                        if ($sug30 < 0)
                                            $sug30 = 0;
                                        if ($sug60 < 0)
                                            $sug60 = 0;
                                        $minmax = LeerMinMax($codcli, $invent->codprod);
                                        $min = $minmax["min"];
                                        $max = $minmax["max"];
                                    }
                                    $respDias = analisisSobreStock($dias, $min, $max);
                                    $iFila = $iFila + 1;
                                    @endphp
                                    <tr>
                                        <td>
                                            {{$iFila}}
                                            @if (isset($invent->codprod))
                                            <a href="{{URL::action('PedidoController@tendencia',$invent->codprod)}}">
                                                <h4>
                                                    <i class="{{$tendencia}}" aria-hidden="true"></i>
                                                </h4>
                                            </a>
                                            @endif
                                        </td>
                                        <td style="width: 100px; ">
                                            <div align="center" >
                                                <a href="{{URL::action('PedidoController@verprod',$cat->barra)}}">
                                                    <img src="http://isaweb.isbsistemas.com/public/storage/prod/{{NombreImagen($cat->barra)}}" 
                                                    class="img-responsive" 
                                                    alt="icompras360" 
                                                    width="100%" 
                                                    height="100%" 
                                                    style="border: 2px solid #D2D6DE;"
                                                    oncontextmenu="return false">
                                                </a>
                                            </div>
                                        </td>
                                        <td>
                                            <!-- AGREGAR A CARRO DE COMPRA -->
                                            <div style="width: 100px;">
                                                <span class="input-group-btn" style="width: 100px;">
                                                    <div class="col-xs-12 input-group" 
                                                        id="idAgregar-{{$iFila}}" >
                                                        <input type="number"  
                                                            style="text-align: center; 
                                                            color: #000000; width: 60px;" 
                                                            id="idPedir-{{$iFila}}" 
                                                            value="" 
                                                            class=" form-control" 
                                                        >
                                                        
                                                        <button type="button" class="BtnAgregar btn btn-pedido

                                                         @if (VerificarCarrito($cat->barra, 'N'))
                                                            colorResaltado
                                                         @endif

                                                         " data-toggle="tooltip" title="Agregar al carrito"

                                                         id="idBtnAgregar-{{$iFila}}" >
                                                            <span class="fa fa-cart-plus" id="idAgregar-{{$iFila}}" aria-hidden="true"></span>
                                                        </button>

                                                    </div>
                                                    <div style="margin-left: 0px; 
                                                          margin-right: 0px; 
                                                          width: 100px;">
                                                        @php
                                                            $cont = count($arrayRnk);
                                                        @endphp
                                                        @if ($cont == 1)
                                                            @php
                                                                $confprov = LeerProve($arrayRnk[0]['codprove']);
                                                            @endphp
                                                            <div style="width: 100px;">

                                                                <img alt="icompras360"
                                                                style="width: 28px; 
                                                                float: left; 
                                                                background-color: #F0F0F0; 
                                                                margin-left: 2px;
                                                                margin-top: 4px;"
                                                                src="{{$rutalogoprov.$confprov->rutalogo1}}"> 

                                                                <span 
                                                                    style="background-color: #F0F0F0;
                                                                    width: 100px;"
                                                                    class="form-control" >
                                                                    &nbsp;{{$tprnk1}}
                                                                </span>
                                                                <input id="idProv-{{$iFila}}" 
                                                                    value="{{$tprnk1}}" 
                                                                    style="background-color: #F0F0F0;
                                                                    width: 70px;"
                                                                    readonly=""
                                                                    type="hidden" 
                                                                    class="form-control" >
                                                            </div>
                                                        @else
                                                            <select id="idProv-{{$iFila}}" 
                                                                class="form-control"  
                                                                style="width: 100px; ">
                                                                @for ($x=0; $x < $cont; $x++) 
                                                                    @if ($tprnk1==$arrayRnk[$x]['codprove']) 
                                                                        <option selected
                                        title="PRECIO: {{number_format($arrayRnk[$x]['liquida'], 2, '.', ',')}}"  
                                        value="{{ $arrayRnk[$x]['codprove'] }}">   
                                        {{ $arrayRnk[$x]['codprove'] }}
                                                                        </option>
                                                                    @else 
                                                                        <option 
                                        title="PRECIO: {{number_format($arrayRnk[$x]['liquida'], 2, '.', ',')}}  DIF: {{number_format($arrayRnk[$x]['liquida'] - $arrayRnk[0]['liquida'], 2, '.', ',')}}"  
                                        value="{{ $arrayRnk[$x]['codprove'] }}">
                                        {{ $arrayRnk[$x]['codprove'] }}
                                                                        </option>
                                                                    @endif
                                                                @endfor
                                                            </select>
                                                        @endif
                                                    </div>
                                                </span>
                                            </div>
                                        </td>
                                        <td style="width: 300px;"
                                            title="PRINCIPIO ACTIVO &#10============ &#10{{$cat->pactivo}}">
                                            <b>{{$cat->desprod}}</b>
                                        </td>
                                        <td>
                                            <span title="CODIGO DE BARRA">
                                                <i class="fa fa-barcode">
                                                    {{$cat->barra}}
                                                </i>
                                            </span><br>
                                            <span title="MARCA DEL PRODUCTO">
                                                <i class="fa fa-shield">
                                                    {{$cat->marca}}    
                                                </i>
                                            </span><br>
                                            @if ($cat->bulto > 0)
                                            <span title="UNIDAD DEl BULTO">
                                                BULTO: {{$cat->bulto}}<br>
                                            </span>
                                            @endif
                                            @if ($cat->iva > 0)
                                            <span title="VALOR DEL IVA" >
                                                IVA: {{number_format($cat->iva, 2, '.', ',')}} %
                                            </span>
                                            @endif
                                        </td>
                                        <td style="display: none;">
                                            {{$cat->barra}}
                                        </td>
                                        <td style="display: none;"
                                            align="right">
                                            {{$cat->bulto}}
                                        </td>
                                        <td style="display: none;"
                                            align="right">
                                            {{number_format($cat->iva, 2, '.', ',')}}
                                        </td>
                                        @if (Auth::user()->versionLight == 0)
                                            <td align="right"
                                                style="background-color: #FEE3CB;"
                                                title = "TRANSITO">
                                                {{number_format($transito, 0, '.', ',')}}
                                            </td>
                                            <td align="right"
                                                style="background-color: #FEE3CB;"
                                                title = "INVENTARIO">
                                                {{number_format($cant, 0, '.', ',')}}    
                                            </td>
                                            <td style="display:none;">{{$tprnk1}}</td>
                                            <td style="display:none;">{{$tprnk1}}</td>
                                            @if ($mpp < $costo)
                                                <td align="right"
                                                    style="background-color: #FEE3CB; color: red;"
                                                    title = "EL MPP ES MENOR AL COSTO">
                                                    {{number_format($costo, 2, '.', ',')}}
                                                </td>
                                            @else
                                                <td align="right"
                                                    style="background-color: #FEE3CB;"
                                                    title = "COSTO">
                                                    {{number_format($costo, 2, '.', ',')}}
                                                </td>
                                            @endif
                                            <td align="right"
                                                style="background-color: #FEE3CB;"
                                                title = "VMD">
                                                {{number_format($vmd, 4, '.', ',')}}
                                            </td>
                                            <td align="right"
                                                style="background-color: #FEE3CB;
                                                color: {{$respDias['color']}}"
                                                title="{{$respDias['title']}}" >
                                                {{number_format($dias, 0, '.', ',')}}

                                                <br>
                                                <span style="background-color: #FEE3CB;
                                                color: black;" 
                                                title="SUGERIDO PARA 15 DIAS" >
                                                15-> {{number_format($sug15, 0, '.', ',')}}
                                                </span>
                                                <br>
                                                <span style="background-color: #FEE3CB;
                                                color: black;" 
                                                title="SUGERIDO PARA 30 DIAS" >
                                                30-> {{number_format($sug30, 0, '.', ',')}}
                                                </span>
                                                <br>
                                                <span style="background-color: #FEE3CB;
                                                color: black;"
                                                title="SUGERIDO PARA 60 DIAS" >
                                                60-> {{number_format($sug60, 0, '.', ',')}}
                                                </span>

                                            </td>
                                            <td align="right"
                                                style="background-color: #FEE3CB; 
                                                color: #000000;
                                                display:none;"
                                                title="SUGERIDO PARA 15 DIAS" >
                                                {{number_format($sug15, 0, '.', ',')}}
                                            </td> 
                                            <td align="right"
                                                style="background-color: #FEE3CB; 
                                                color: #000000;
                                                display:none;"
                                                title="SUGERIDO PARA 30 DIAS" >
                                                {{number_format($sug30, 0, '.', ',')}}
                                            </td> 
                                            <td align="right"
                                                style="background-color: #FEE3CB; 
                                                color: #000000;
                                                display:none;"
                                                title="SUGERIDO PARA 60 DIAS" >
                                                {{number_format($sug60, 0, '.', ',')}}
                                            </td> 
                                        @else
                                            <td align="right"
                                                title = "TRANSITO">
                                                {{number_format($transito, 0, '.', ',')}}
                                            </td>
                                            <td style="display:none;">
                                                {{number_format($cant, 0, '.', ',')}}    
                                            </td>
                                            <td style="display:none;">{{$tprnk1}}</td>
                                            <td style="display:none;">{{$tprnk1}}</td>
                                            <td style="display:none;">
                                                {{number_format($costo, 2, '.', ',')}}
                                            </td>
                                            <td style="display:none;">
                                                {{number_format($vmd, 4, '.', ',')}}
                                            </td>
                                            <td style="display:none;">
                                                {{number_format($dias, 0, '.', ',')}}
                                            </td>
                                        @endif    
                                        <td align="right"
                                            style="background-color: #FCD0C7;"
                                            title = "CONSOLIDADO">
                                            {{number_format($invConsol, 0, '.', ',')}}
                                        </td>
                                        <td align="right"
                                            style="background-color: #FCD0C7;" >
                                            <span style="font-size: 18px;"
                                                title="PRECIO LIQUIDA LA MOLECULA">
                                                {{number_format($mpp, 2, '.', ',')}}
                                            </span>
                                            <br>
                                            <span title="UNIDAD DE LA MOLECULA"> 
                                                Und: {{$cat->unidadmolecula}}
                                            </span>
                                            <br>
                                            <span title="PRECIO LIQUIDA EL PRODUCTO">
                                                {{number_format($cat->mppliq, 2, '.', ',')}}
                                            </span>
                                        </td>
                                        @foreach ($proves as $prov)
                                            @php
                                                $codprove = strtolower($prov['codprove']);
                                                $factor = RetornaFactorCambiario($codprove, $moneda);
                                                $cantidad = $prov['cantidad'];
                                                $codprod = $prov['codprod'];;
                                                $fechafalla = $prov['fechafalla'];
                                                $lote = $prov['lote'];;
                                                $fecvence = $prov['fecvence'];
                                                $fecvence = str_replace("12:00:00 AM", "", $fecvence);
                                                $confprov = $prov['confprov']; 
                                                $tipoprecio = $prov['tipoprecio'];
                                                $actualizado = date('d-m-Y H:i', strtotime($prov['actualizado']));
                                                $dc = $prov['dc'];
                                                $di = $prov['di'];
                                                $pp = $prov['pp'];
                                                $da = 0.00;
                                                $dcred = $prov['dcredito'];
                                                $dpe = $prov['dpe']; 
                                                $upe = $prov['upe'];
                                                if ($tipoprecio == $confprov->aplicarDaPrecio)
                                                    $da = $prov['da'];
                                                $precio = $prov['precio'];
                                                $liquida = $prov['liquida'];
                                                $liqmolecula = $prov['liqmolecula'];
                                                $ranking = obtenerRanking($liqmolecula, $arrayRnk);
                                            @endphp

                                            <!--- PRECIO DEL PROVEEDOR -->
                                            <td align='right' 
                                                style="width: 200px; 
                                                word-wrap: break-word; 
                                                background-color: {{$confprov->backcolor}};
                                                color: {{$confprov->forecolor}}; " 
                                             
                                                @if ($liquida > 0)
                                                 title = "{{$confprov->descripcion}} &#10 ======================== &#10 PRECIO: {{number_format($precio, 2, '.', ',')}} @if ($factor > 1) &#10 TASA: {{number_format($factor, 2, '.', ',')}} @endif &#10 TIPO: {{$tipoprecio}} &#10 DA: {{number_format($da, 2, '.', ',')}} &#10 DC: {{number_format($dc, 2, '.', ',')}} &#10 DI: {{number_format($di, 2, '.', ',')}} &#10 PP: {{number_format($pp, 2, '.', ',')}} &#10 IVA: {{number_format($cat->iva, 2, '.', ',')}} &#10 UND MOLECULA: {{$cat->unidadmolecula}} &#10 RANKING: {{$ranking}} &#10 LOTE: {{$lote}} &#10 VENCE: {{$fecvence}} &#10 CODIGO: {{$codprod}} &#10 ACTUALIZADO: {{$actualizado}} &#10 ======================== &#10 LIQUIDA: {{number_format($liquida, 2, '.', ',')}} &#10 "
                                                @endif >

                                                <span>
                                                    @if ($liquida == 0)
                                                        {{number_format($liquida, 2, '.', ',')}}
                                                    @else
                                                        @if ($liqmolecula == $mpp)
                                                            <span style="font-size: 18px;">    
                                                                {{number_format($liquida, 2, '.', ',')}}
                                                            </span>
                                                            <br>
                                                            <span>
                                                                <i style="font-size: 18px;" 
                                                                    class="fa fa-thumbs-o-up">
                                                                </i>
                                                                Rnk:{{$ranking}}
                                                            </span>
                                                            <br>
                                                            <span style="font-size: 18px;">
                                                                {{number_format($liqmolecula, 2, '.', ',')}}
                                                            </span>
                                                        @else
                                                            <span>    
                                                                {{number_format($liquida, 2, '.', ',')}}
                                                            </span>
                                                            <br>
                                                            @if ($ranking)
                                                            <span>
                                                                Rnk:{{$ranking}}
                                                            </span>
                                                            <br>
                                                            <span>
                                                                {{number_format($liqmolecula, 2, '.', ',')}}
                                                            </span>
                                                            @endif
                                                        @endif
                                                    @endif
                                                </span>
                                        
                                            </td>
         
                                            <!--- CANTIDAD DEL PROVEEDOR -->
                                            <td align='right' style="width: 200px; word-wrap: break-word; background-color: {{$confprov->backcolor}}; color: {{$confprov->forecolor}};" 
                                                title=" {{$confprov->descripcion}}&#10->CANTIDAD">
                                                @if ($mayorInv == $cantidad)
                                                    <span style="font-size: 18px;">
                                                        {{number_format($cantidad, 0, '.', ',')}}
                                                    </span>
                                                @else
                                                    {{number_format($cantidad, 0, '.', ',')}}
                                                @endif
                                                @if ($mayorInv == $cantidad)
                                                    <br>
                                                    <i style="font-size: 18px;" 
                                                        class="fa fa-thumbs-o-up">
                                                    </i>
                                                @endif
                                                <span style="margin-top: 5px;">
                                                @if ($dcred > 0)
                                                    <div style="margin-bottom: 1px;
                                                        border-radius: 5px; 
                                                        font-size: 10px;
                                                        text-align: center;
                                                        padding: 1px; 
                                                        color: white;
                                                        background-color: black;"
                                                        title="DIAS DE CREDITO:  {{$dcred}}">
                                                        DIAS: {{$dcred}} 
                                                    </div>
                                                @else
                                                    <div style="margin-bottom: 1px;
                                                        border-radius: 5px; 
                                                        font-size: 12px;
                                                        text-align: center;
                                                        padding: 1px; 
                                                        color: white;">
                                                        &nbsp;
                                                    </div>
                                                @endif 
                                                @if ($dpe > 0)
                                                    <div style="margin-bottom: 1px;
                                                        border-radius: 5px; 
                                                        font-size: 10px;
                                                        text-align: center;
                                                        padding: 1px; 
                                                        color: white;
                                                        background-color: black;"
                                                        title="DESCUENTO DE PRE-EMPAQUE: {{QuitarCerosDecimales($dpe)}} % => {{$upe}}">
                                                        DP: {{QuitarCerosDecimales($dpe)}} % => {{$upe}}
                                                    </div>
                                                @else
                                                    <div style="margin-bottom: 1px;
                                                        border-radius: 5px; 
                                                        font-size: 12px;
                                                        text-align: center;
                                                        padding: 1px; 
                                                        color: white;">
                                                        &nbsp;
                                                    </div>
                                                @endif
                                                @if ($fecvence != "N/A")
                                                    @if (validarVence($fecvence, $cliente->mesNotVence))
                                                    <div style="
                                                        border-radius: 5px; 
                                                        font-size: 10px;
                                                        text-align: center;
                                                        padding: 1px; 
                                                        color: white;
                                                        background-color: black;"
                                                        title="FECHA DE VENCIMIENTO: {{$fecvence}}">
                                                        {{$fecvence}}
                                                    </div>
                                                    @endif
                                                @endif
                                                </span>
                                            </td>
                                    
                                            <td style="display:none;">{{$da}}</td>
                                            <td style="display:none;">{{$codprod}}</td>
                                            <td style="display:none;">{{$fechafalla}}</td>
                                        @endforeach
                                    </tr>
                                @endif
                            @else
                                @php
                                $codprove = strtolower($tpactivo);
                                $factor = RetornaFactorCambiario($codprove, $moneda);
                                $precio = $cat->precio1;
                                $confprov = LeerProve($codprove); 
                                if (is_null($confprov))
                                    continue;
                                $tipoprecio = $provactivo->tipoprecio;
                                $fechafalla = date('Y-m-d H:i:s', strtotime($cat->fechafalla));
                                switch ($tipoprecio) {
                                    case 1:
                                        $precio = $cat->precio1/$factor;
                                        break;
                                    case 2:
                                        $precio = $cat->precio2/$factor;
                                        break;
                                    case 3:
                                        $precio = $cat->precio3/$factor;
                                        break;
                                    default:
                                        $precio = $cat->precio1/$factor;
                                        break; 
                                }
                                $cantidad = $cat->cantidad;
                                $fecvence = str_replace("12:00:00 AM", "", $cat->fecvence);
                                $dc = $provactivo->dcme;
                                $di = $provactivo->di;
                                $pp = $provactivo->ppme;
                                $da = 0.00;
                                if ($tipoprecio == $confprov->aplicarDaPrecio)
                                    $da = $cat->da;
                                $neto = CalculaPrecioNeto($precio, $da, $di, $dc, $pp, 0.00);
                                $liquida = $neto + (($neto * $cat->iva)/100);
                                if ($cantidad <= 0 || $liquida <= 0)
                                    continue;
                                $tpactivo = strtoupper($tpactivo);
                                $tpselect = $tpactivo;
                                $tprnk1 = obtenerCodprovRnk1($cat->barra,$provs);
                                $costo = 0.00;
                                $vmd = 0.0000;
                                $dias = 0;
                                $min = 0;
                                $max = 0;
                                $cant = 0;
                                $tendencia = "";
                                $sug15 = 0;
                                $sug30 = 0;
                                $sug60 = 0;
                                $transito = verificarProdTransito($cat->barra,  "", "");
                                $invent = verificarProdInventario($cat->barra,  "");
                                if (!is_null($invent)) {
                                    $costo = $invent->costo;
                                    $vmd = $invent->vmd;
                                    $cant = $invent->cantidad;
                                    if ($vmd > 0)
                                        $dias = $cant/$vmd;
                                    $tendencia = MostrarTendenciaProd($codcli, $invent->codprod, $cfg->TendTolerancia);
                                    $sug15 = ($vmd*15)-($cant + $transito);
                                    $sug30 = ($vmd*30)-($cant + $transito);
                                    $sug60 = ($vmd*60)-($cant + $transito);
                                    if ($sug15 < 0)
                                        $sug15 = 0;
                                    if ($sug30 < 0)
                                        $sug30 = 0;
                                    if ($sug60 < 0)
                                        $sug60 = 0; 
                                    $minmax = LeerMinMax($codcli, $invent->codprod);
                                    $min = $minmax["min"];
                                    $max = $minmax["max"];   
                                }
                                $respDias = analisisSobreStock($dias, $min, $max);
                                $oferta = str_replace('.00', '', $da);
                                $oferta = str_replace('.0', '', $oferta);
                                $dpe = str_replace('.00', '', $cat->dp);
                                $dpe = str_replace('.0', '', $dpe);
                                @endphp
                                @if ($tipo == "C")
                                    @php
                                    $iFila = $iFila + 1;
                                    @endphp
                                    <tr>
                                        <td>
                                            {{$iFila}}
                                            @if (isset($invent->codprod))
                                            <a href="{{URL::action('PedidoController@tendencia',$invent->codprod)}}">
                                                <h4>
                                                    <i class="{{$tendencia}}" aria-hidden="true"></i>
                                                </h4>
                                            </a>
                                            @endif
                                        </td>
                                        <td style="width: 60px;">
                                            <div align="center">
                                                <a href="{{URL::action('PedidoController@verprod',$cat->barra)}}">

                                                    <img src="http://isaweb.isbsistemas.com/public/storage/prod/{{NombreImagen($cat->barra)}}" 
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
                                            <!-- AGREGAR A CARRO DE COMPRA -->
                                            <div class="col-xs-12 input-group" 
                                                id="idAgregar-{{$iFila}}" >
                                                <input type="number" 
                                                    style="text-align: center; 
                                                    color: #000000; 
                                                    width: 71px;" 
                                                    id="idPedir-{{$iFila}}" 
                                                    value="" 
                                                    class="form-control" 
                                                >
                                                <span class="input-group-btn BtnAgregar">
                                                    
                                                    <button type="button" class="btn btn-pedido

                                                    @if (VerificarCarrito($cat->barra, 'N'))
                                                        colorResaltado
                                                    @endif

                                                    " data-toggle="tooltip" title="Agregar al carrito"
                                                    id="idBtnAgregar-{{$iFila}}" >
                                                        <span class="fa fa-cart-plus" id="idAgregar-{{$iFila}}" aria-hidden="true"></span>
                                                    </button>
                                                </span>
                                            </div>

                                            <img alt="icompras360"
                                                style="width: 28px; 
                                                float: left; 
                                                background-color: #F0F0F0; 
                                                margin-left: 2px;
                                                margin-top: 4px;"
                                                src="{{$rutalogoprov.$confprov->rutalogo1}}"> 
                                            <span 
                                                style="background-color: #F0F0F0;
                                                width: 110px;"
                                                class="form-control" >
                                                &nbsp;{{$confprov->codprove}}
                                            </span>

                                        </td>
                                        <td style="width: 300px;"
                                            title="PRINCIPIO ACTIVO &#10============ &#10{{$cat->pactivo}}">
                                            <b>{{$cat->desprod}}</b>
                                            <div style="margin-top: 5px;"></biv>
                                            @if ($da > 0)
                                                <span style="font-size: 10px;">
                                                    DA: 
                                                </span>
                                                <span style="border-radius: 5px; 
                                                    font-size: 14px;
                                                    text-align: center;
                                                    padding: 2px; 
                                                    width: 70px;
                                                    color: white;
                                                    background-color: #26328C;
                                                    margin-right: 4px;"
                                                    title="DESCUENTO ADICIONAL: {{$oferta}} %">
                                                    {{$oferta}} %
                                                </span>
                                            @endif
                                            @if ($cat->dcredito > 0)
                                                <span style="font-size: 10px;">
                                                    DIAS: 
                                                </span>
                                                <span style="border-radius: 5px; 
                                                    font-size: 14px;
                                                    text-align: center;
                                                    padding: 2px; 
                                                    width: 70px;
                                                    color: white;
                                                    background-color: #26328C;
                                                    margin-right: 4px;"
                                                    title="DIAS DE CREDITO: {{$cat->dcredito}}">
                                                    {{$cat->dcredito}} 
                                                </span>
                                            @endif
                                            <div style="margin-top: 5px;"></biv>
                                            @if ($cat->dp > 0)
                                                <span style="font-size: 10px;">
                                                    DP: 
                                                </span>
                                                <span style="border-radius: 5px; 
                                                    font-size: 14px;
                                                    text-align: center;
                                                    padding: 2px;  
                                                    width: 70px;
                                                    color: white;
                                                    background-color: #0061A8;
                                                    margin-right: 4px;"
                                                    title="DESCUENTO POR PRE-EMPAQUE: {{QuitarCerosDecimales($dpe)}} %">
                                                    {{QuitarCerosDecimales($dpe)}} %
                                                </span>
                                                <span style="font-size: 10px;">
                                                    UP: 
                                                </span>
                                                <span style="border-radius: 5px; 
                                                    font-size: 14px;
                                                    text-align: center;
                                                    padding: 2px; 
                                                    width: 70px;
                                                    color: white;
                                                    background-color: #0061A8;
                                                    margin-right: 4px;"
                                                    title="UNIDAD DE PRE-EMPAQUE: {{$cat->up}}">
                                                    {{$cat->up}} 
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <span title="CODIGO DE BARRA">
                                                <i class="fa fa-barcode">
                                                    {{$cat->barra}}
                                                </i>
                                            </span><br>
                                            <span title="MARCA DEL PRODUCTO">
                                                <i class="fa fa-shield">
                                                    {{$cat->marca}}    
                                                </i>
                                            </span><br>
                                            @if ($cat->bulto > 0)
                                            <span title="UNIDAD DEl BULTO">
                                                BULTO: {{$cat->bulto}}<br>
                                            </span>
                                            @endif
                                            @if ($cat->iva > 0)
                                            <span title="VALOR DEL IVA" >
                                                IVA: {{number_format($cat->iva, 2, '.', ',')}} %
                                            </span>
                                            @endif
                                        </td>
                                        <td style="display: none;">
                                            {{$cat->barra}}
                                        </td>
                                        <td style="display: none;" 
                                            align="right">
                                            {{$cat->bulto}}
                                        </td>
                                        <td style="display: none;" 
                                            align="right">
                                            {{number_format($cat->iva, 2, '.', ',')}}
                                        </td>
                                        @if (Auth::user()->versionLight == 0)
                                            <td align="right"
                                                style="background-color: #FEE3CB;"
                                                title = "TRANSITO">
                                                {{number_format($transito, 0, '.', ',')}}
                                            </td>
                                            <td align="right"
                                                style="background-color: #FEE3CB;"
                                                title = "INVENTARIO">
                                                {{number_format($cant, 0, '.', ',')}}
                                            </td>
                                            <td style="display:none;">{{$tpselect}}</td>
                                            <td style="display:none;">{{$tprnk1}}</td>
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
                                            <td align="right"
                                                style="background-color: #FEE3CB;
                                                color: {{$respDias['color']}}"
                                                title="{{$respDias['title']}}" >
                                                {{number_format($dias, 0, '.', ',')}}

                                                <br>
                                                <span style="background-color: #FEE3CB;
                                                color: black;" 
                                                title="SUGERIDO PARA 15 DIAS" >
                                                15-> {{number_format($sug15, 0, '.', ',')}}
                                                </span>
                                                <br>
                                                <span style="background-color: #FEE3CB;
                                                color: black;" 
                                                title="SUGERIDO PARA 30 DIAS" >
                                                30-> {{number_format($sug30, 0, '.', ',')}}
                                                </span>
                                                <br>
                                                <span style="background-color: #FEE3CB;
                                                color: black;"
                                                title="SUGERIDO PARA 60 DIAS" >
                                                60-> {{number_format($sug60, 0, '.', ',')}}
                                                </span>

                                            </td>
                                            <td align="right"
                                                style="background-color: #FEE3CB; 
                                                color: #000000;
                                                display:none;"
                                                title="SUGERIDO PARA 15 DIAS" >
                                                {{number_format($sug15, 0, '.', ',')}}
                                            </td> 
                                            <td align="right"
                                                style="background-color: #FEE3CB; 
                                                color: #000000;
                                                display:none;"
                                                title="SUGERIDO PARA 30 DIAS" >
                                                {{number_format($sug30, 0, '.', ',')}}
                                            </td> 
                                            <td align="right"
                                                style="background-color: #FEE3CB; 
                                                color: #000000;
                                                display:none;"
                                                title="SUGERIDO PARA 60 DIAS" >
                                                {{number_format($sug60, 0, '.', ',')}}
                                            </td> 
                                        @else
                                            <td align="right"
                                                title = "TRANSITO">
                                                {{number_format($transito, 0, '.', ',')}}
                                            </td>
                                            <td style="display:none;">
                                                {{number_format($cant, 0, '.', ',')}}
                                            </td>
                                            <td style="display:none;">{{$tpselect}}</td>
                                            <td style="display:none;">{{$tprnk1}}</td>
                                            <td style="display:none;">
                                                {{number_format($costo, 2, '.', ',')}}
                                            </td>
                                            <td style="display:none;">
                                                {{number_format($vmd, 4, '.', ',')}}
                                            </td>
                                            <td style="display:none;" >
                                                {{number_format($dias, 0, '.', ',')}}
                                            </td>
                                        @endif
                                        <td align='right' 
                                            style="background-color: {{$confprov->backcolor}}; 
                                            color: {{$confprov->forecolor}};">
                                            {{number_format($liquida, 2, '.', ',')}}
                                        </td>
                                        <td align='right' 
                                            style="background-color: {{$confprov->backcolor}}; 
                                            color: {{$confprov->forecolor}};" 
                                            title="{{$confprov->descripcion}}">
                                            {{number_format($cat->cantidad, 0, '.', ',')}}
                                        </td>
                                        <td align="right" 
                                            style="display:none;
                                                background-color: {{$confprov->backcolor}}; 
                                                color: {{$confprov->forecolor}};">
                                            @if ($da > 0)
                                                <b>{{number_format($da, 2, '.', ',')}}</b>
                                            @else
                                                {{number_format($da, 2, '.', ',')}}
                                            @endif
                                        </td>
                                        <td style="background-color: {{$confprov->backcolor}}; 
                                            color: {{$confprov->forecolor}};">
                                            {{$cat->codprod}}
                                        </td>
                                        <td style="width: 100px; 
                                            display:none;">
                                            {{$cat->fechafalla}}
                                        </td>
                                        <td style="display:none; 
                                            background-color: {{$confprov->backcolor}}; 
                                            color: {{$confprov->forecolor}};">
                                            <img style="width: 20px; 
                                            height: 20px; float: left;" 
                                            src="{{$rutalogoprov.$confprov->rutalogo1}}" 
                                            alt="icompras360">
                                            &nbsp;{{$confprov->descripcion}}
                                        </td>
                                        <td style="display:none;">
                                            {{$provactivo->codprove}}
                                        </td>
                                        <td style="background-color: {{$confprov->backcolor}}; 
                                            color: {{$confprov->forecolor}};">
                                            {{$cat->lote}}<br>
                                            <span title="FECHA DE VENCIMIENTO">{{$fecvence}}</span>
                                        </td>
                                        <td style="display:none;
                                            background-color: {{$confprov->backcolor}}; 
                                            color: {{$confprov->forecolor}};">
                                            {{$fecvence}}
                                        </td>
                                    </tr>
                                @endif
                                @if ($tipo == "E")
                                    @if ($fechafalla >= $desde && $fechafalla <= $hasta)
                                        @php
                                        $iFila = $iFila + 1;
                                        @endphp
                                        <tr>
                                            <td>
                                                {{$iFila}}
                                                @if (isset($invent->codprod))
                                                <a href="{{URL::action('PedidoController@tendencia',$invent->codprod)}}">
                                                    <h4>
                                                        <i class="{{$tendencia}}" aria-hidden="true"></i>
                                                    </h4>
                                                </a>
                                                @endif
                                            </td>
                                            <td style="width: 60px;">
                                                <div align="center">
                                                    <a href="{{URL::action('PedidoController@verprod',$cat->barra)}}">

                                                        <img src="http://isaweb.isbsistemas.com/public/storage/prod/{{NombreImagen($cat->barra)}}" 
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
                                                <!-- AGREGAR A CARRO DE COMPRA -->
                                                <div class="col-xs-12 input-group" id="idAgregar-{{$iFila}}" >
                                                    <input type="number" 
                                                        style="text-align: center; 
                                                        color: #000000; 
                                                        width: 71px;" 
                                                        id="idPedir-{{$iFila}}" 
                                                        value="" 
                                                        class="form-control" 
                                                    >
                                                    <span class="input-group-btn BtnAgregar">
                                                        
                                                        <button type="button" class="btn btn-pedido

                                                        @if (VerificarCarrito($cat->barra, 'N'))
                                                            colorResaltado
                                                        @endif

                                                        " data-toggle="tooltip" title="Agregar al carrito"
                                                         id="idBtnAgregar-{{$iFila}}"  >
                                                            <span class="fa fa-cart-plus" id="idAgregar-{{$iFila}}" aria-hidden="true"></span>
                                                        </button>
                                                    
                                                    </span>
                                                </div>

                                                <img alt="icompras360"
                                                    style="width: 28px; 
                                                    float: left; 
                                                    background-color: #F0F0F0; 
                                                    margin-left: 2px;
                                                    margin-top: 4px;"
                                                    src="{{$rutalogoprov.$confprov->rutalogo1}}"> 
                                                <span 
                                                    style="background-color: #F0F0F0;
                                                    width: 110px;"
                                                    class="form-control" >
                                                    &nbsp;{{$confprov->codprove}}
                                                </span>
                                            </td>
                                            <td style="width: 300px;"
                                                title="PRINCIPIO ACTIVO &#10============ &#10{{$cat->pactivo}}">
                                                <b>{{$cat->desprod}}</b>
                                                <div style="margin-top: 5px;"></biv>
                                                @if ($da > 0)
                                                    <span style="font-size: 10px;">
                                                        DA: 
                                                    </span>
                                                    <span style="border-radius: 5px; 
                                                        font-size: 14px;
                                                        text-align: center;
                                                        padding: 2px; 
                                                        width: 70px;
                                                        color: white;
                                                        background-color: #26328C;
                                                        margin-right: 4px;"
                                                        title="DESCUENTO ADICIONAL: {{$oferta}} %">
                                                        {{$oferta}} %
                                                    </span>
                                                @endif
                                                @if ($cat->dcredito > 0)
                                                    <span style="font-size: 10px;">
                                                        DIAS: 
                                                    </span>
                                                    <span style="border-radius: 5px; 
                                                        font-size: 14px;
                                                        text-align: center;
                                                        padding: 2px; 
                                                        width: 70px;
                                                        color: white;
                                                        background-color: #26328C;
                                                        margin-right: 4px;"
                                                        title="DIAS DE CREDITO: {{$cat->dcredito}}">
                                                        {{$cat->dcredito}} 
                                                    </span>
                                                @endif
                                                <div style="margin-top: 5px;"></biv>
                                                @if ($cat->dp > 0)
                                                    <span style="font-size: 10px;">
                                                        DP: 
                                                    </span>
                                                    <span style="border-radius: 5px; 
                                                        font-size: 14px;
                                                        text-align: center;
                                                        padding: 2px;  
                                                        width: 70px;
                                                        color: white;
                                                        background-color: #0061A8;
                                                        margin-right: 4px;"
                                                        title="DESCUENTO POR PRE-EMPAQUE: {{QuitarCerosDecimales($dpe)}} %">
                                                        {{QuitarCerosDecimales($dpe)}} %
                                                    </span>
                                                    <span style="font-size: 10px;">
                                                        UP: 
                                                    </span>
                                                    <span style="border-radius: 5px; 
                                                        font-size: 14px;
                                                        text-align: center;
                                                        padding: 2px; 
                                                        width: 70px;
                                                        color: white;
                                                        background-color: #0061A8;
                                                        margin-right: 4px;"
                                                        title="UNIDAD DE PRE-EMPAQUE: {{$cat->up}}">
                                                        {{$cat->up}} 
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <span title="CODIGO DE BARRA">
                                                    <i class="fa fa-barcode">
                                                        {{$cat->barra}}
                                                    </i>
                                                </span><br>
                                                <span title="MARCA DEL PRODUCTO">
                                                    <i class="fa fa-shield">
                                                        {{$cat->marca}}    
                                                    </i>
                                                </span><br>
                                                @if ($cat->bulto > 0)
                                                <span title="UNIDAD DEl BULTO">
                                                    BULTO: {{$cat->bulto}}<br>
                                                </span>
                                                @endif
                                                @if ($cat->iva > 0)
                                                <span title="VALOR DEL IVA" >
                                                    IVA: {{number_format($cat->iva, 2, '.', ',')}} %
                                                </span>
                                                @endif
                                            </td>
                                            <td style="display: none;">
                                                {{$cat->barra}}
                                            </td>
                                            <td style="display: none;"
                                                align="right">
                                                {{$cat->bulto}}
                                            </td>
                                            <td style="display: none;"
                                                align="right">
                                                {{number_format($cat->iva, 2, '.', ',')}}
                                            </td>
                                            @if (Auth::user()->versionLight == 0)
                                                <td align="right"
                                                    style="background-color: #FEE3CB;">
                                                    {{number_format($transito, 0, '.', ',')}}
                                                </td>
                                                <td align="right"
                                                    title="INVENTARIO"
                                                    style="background-color: #FEE3CB;">
                                                    {{number_format($cant, 0, '.', ',')}}
                                                </td>
                                                <td style="display:none;">{{$tpselect}}</td>
                                                <td style="display:none;">{{$tprnk1}}</td>
                                                <td align="right"
                                                    title="COSTO"
                                                    style="background-color: #FEE3CB;">
                                                    {{number_format($costo, 2, '.', ',')}}
                                                </td>
                                                <td align="right"
                                                    title="VMD"
                                                    style="background-color: #FEE3CB;">
                                                    {{number_format($vmd, 4, '.', ',')}}
                                                </td>
                                                <td align="right"
                                                    style="background-color: #FEE3CB;
                                                    color: {{$respDias['color']}}"
                                                    title="{{$respDias['title']}}" >
                                                    {{number_format($dias, 0, '.', ',')}}

                                                    <br>
                                                    <span style="background-color: #FEE3CB;
                                                    color: black;" 
                                                    title="SUGERIDO PARA 15 DIAS" >
                                                    15-> {{number_format($sug15, 0, '.', ',')}}
                                                    </span>
                                                    <br>
                                                    <span style="background-color: #FEE3CB;
                                                    color: black;" 
                                                    title="SUGERIDO PARA 30 DIAS" >
                                                    30-> {{number_format($sug30, 0, '.', ',')}}
                                                    </span>
                                                    <br>
                                                    <span style="background-color: #FEE3CB;
                                                    color: black;"
                                                    title="SUGERIDO PARA 60 DIAS" >
                                                    60-> {{number_format($sug60, 0, '.', ',')}}
                                                    </span>

                                                </td>
                                                <td align="right"
                                                    style="background-color: #FEE3CB; 
                                                    color: #000000;
                                                    display:none;"
                                                    title="SUGERIDO PARA 15 DIAS" >
                                                    {{number_format($sug15, 0, '.', ',')}}
                                                </td> 
                                                <td align="right"
                                                    style="background-color: #FEE3CB; 
                                                    color: #000000;
                                                    display:none;"
                                                    title="SUGERIDO PARA 30 DIAS" >
                                                    {{number_format($sug30, 0, '.', ',')}}
                                                </td> 
                                                <td align="right"
                                                    style="background-color: #FEE3CB; 
                                                    color: #000000;
                                                    display:none;"
                                                    title="SUGERIDO PARA 60 DIAS" >
                                                    {{number_format($sug60, 0, '.', ',')}}
                                                </td>  
                                            @else
                                                <td align="right">
                                                    {{number_format($transito, 0, '.', ',')}}
                                                </td>
                                                <td style="display:none;">
                                                    {{number_format($cant, 0, '.', ',')}}
                                                </td>
                                                <td style="display:none;">{{$tpselect}}</td>
                                                <td style="display:none;">{{$tprnk1}}</td>
                                                <td style="display:none;">
                                                    {{number_format($costo, 2, '.', ',')}}
                                                </td>
                                                <td style="display:none;">
                                                    {{number_format($vmd, 4, '.', ',')}}
                                                </td>
                                                <td style="display:none;">
                                                    {{number_format($dias, 0, '.', ',')}}
                                                </td>
                                            @endif
                                            <td align='right' 
                                                style="background-color: {{$confprov->backcolor}}; 
                                                color: {{$confprov->forecolor}};"
                                                title=" {{$confprov->descripcion}}&#10->PRECIO">
                                                {{number_format($liquida, 2, '.', ',')}}
                                            </td>
                                            <td align='right' 
                                                style="background-color: {{$confprov->backcolor}}; 
                                                color: {{$confprov->forecolor}};" 
                                                title=" {{$confprov->descripcion}}&#10->CANTIDAD">{{number_format($cat->cantidad, 0, '.', ',')}}
                                            </td>
                                            <td align="right" 
                                                style="display:none;
                                                background-color: {{$confprov->backcolor}}; 
                                                color: {{$confprov->forecolor}};"
                                                title=" {{$confprov->descripcion}}&#10->DA">
                                                @if ($da > 0)
                                                    <b>{{number_format($da, 2, '.', ',')}}</b>
                                                @else
                                                    {{number_format($da, 2, '.', ',')}}
                                                @endif
                                            </td>
                                            <td style="background-color: {{$confprov->backcolor}}; 
                                                color: {{$confprov->forecolor}};"
                                                title=" {{$confprov->descripcion}}&#10->CODIGO">
                                                {{$cat->codprod}}
                                            </td>
                                            <td align='center' 
                                                style="background-color: {{$confprov->backcolor}}; 
                                                color: {{$confprov->forecolor}};"
                                                title=" {{$confprov->descripcion}}&#10->ENTRADA">
                                                {{date('d-m-Y', strtotime($cat->fechafalla))}}
                                            </td>
                                            <td style="display:none; 
                                                background-color: {{$confprov->backcolor}}; 
                                                color: {{$confprov->forecolor}};"
                                                title=" {{$confprov->descripcion}}&#10->PROVEEDOR">
                                                <img style="width: 20px; 
                                                height: 20px; float: left;" 
                                                src="{{$rutalogoprov.$confprov->rutalogo1}}" 
                                                alt="icompras360">
                                                &nbsp;{{$confprov->descripcion}}
                                            </td>
                                            <td style="display:none;">{{$tpactivo}}</td>
                                            <td style="background-color: {{$confprov->backcolor}}; 
                                                color: {{$confprov->forecolor}};"
                                                title=" {{$confprov->descripcion}}&#10->LOTE">
                                                {{$cat->lote}}<br>
                                                <span title="FECHA DE VENCIMIENTO">
                                                    {{$fecvence}}
                                                </span>
                                            </td>
                                            <td style="display:none; 
                                                background-color: {{$confprov->backcolor}}; 
                                                color: {{$confprov->forecolor}};"
                                                title=" {{$confprov->descripcion}}&#10->VENCE">
                                                {{$fecvence}}
                                            </td>
                                        </tr>
                                    @endif
                                @endif
                                @if ($tipo == "O")
                                    @if ($da > 0) 
                                        @php
                                        $iFila = $iFila + 1;
                                        @endphp
                                        <tr>
                                            <td>
                                                {{$iFila}}
                                                @if (isset($invent->codprod))
                                                <a href="{{URL::action('PedidoController@tendencia',$invent->codprod)}}">
                                                    <h4>
                                                        <i class="{{$tendencia}}" aria-hidden="true"></i>
                                                    </h4>
                                                </a>
                                                @endif
                                            </td>
                                            <td style="width: 60px;">
                                                <div align="center">
                                                    <a href="{{URL::action('PedidoController@verprod',$cat->barra)}}">

                                                        <img src="http://isaweb.isbsistemas.com/public/storage/prod/{{NombreImagen($cat->barra)}}" 
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
                                                <!-- AGREGAR A CARRO DE COMPRA -->
                                                <div class="col-xs-12 input-group" 
                                                    id="idAgregar-{{$iFila}}" >
                                                    <input type="number" 
                                                        style="text-align: center; 
                                                        color: #000000; 
                                                        width: 71px;" 
                                                        id="idPedir-{{$iFila}}" 
                                                        value="" 
                                                        class="form-control" 
                                                    >
                                                    <span class="input-group-btn BtnAgregar">
                                                        
                                                        <button type="button" class="btn btn-pedido 

                                                        @if (VerificarCarrito($cat->barra, 'N'))
                                                            colorResaltado
                                                        @endif

                                                        " data-toggle="tooltip" title="Agregar al carrito"
                                                         id="idBtnAgregar-{{$iFila}}" >
                                                        <span class="fa fa-cart-plus" id="idAgregar-{{$iFila}}" aria-hidden="true">
                                                            
                                                        </span>
                                                        </button>
                                                    
                                                    </span>
                                                </div>

                                                <img alt="icompras360"
                                                    style="width: 28px; 
                                                    float: left; 
                                                    background-color: #F0F0F0; 
                                                    margin-left: 2px;
                                                    margin-top: 4px;"
                                                    src="{{$rutalogoprov.$confprov->rutalogo1}}"> 
                                                <span 
                                                    style="background-color: #F0F0F0;
                                                    width: 110px;"
                                                    class="form-control" >
                                                    &nbsp;{{$confprov->codprove}}
                                                </span>

                                            </td>
                                        
                                            <td style="width: 300px;"
                                                title="PRINCIPIO ACTIVO &#10============ &#10{{$cat->pactivo}}">
                                                <b>{{$cat->desprod}}</b>
                                                <div style="margin-top: 5px;"></biv>
                                                @if ($da > 0)
                                                    <span style="font-size: 10px;">
                                                        DA: 
                                                    </span>
                                                    <span style="border-radius: 5px; 
                                                        font-size: 14px;
                                                        text-align: center;
                                                        padding: 2px; 
                                                        width: 70px;
                                                        color: white;
                                                        background-color: #26328C;
                                                        margin-right: 4px;"
                                                        title="DESCUENTO ADICIONAL: {{$oferta}} %">
                                                        {{$oferta}} %
                                                    </span>
                                                @endif
                                                @if ($cat->dcredito > 0)
                                                    <span style="font-size: 10px;">
                                                        DIAS: 
                                                    </span>
                                                    <span style="border-radius: 5px; 
                                                        font-size: 14px;
                                                        text-align: center;
                                                        padding: 2px; 
                                                        width: 70px;
                                                        color: white;
                                                        background-color: #26328C;
                                                        margin-right: 4px;"
                                                        title="DIAS DE CREDITO: {{$cat->dcredito}}">
                                                        {{$cat->dcredito}} 
                                                    </span>
                                                @endif
                                                <div style="margin-top: 5px;"></biv>
                                                @if ($cat->dp > 0)
                                                    <span style="font-size: 10px;">
                                                        DP: 
                                                    </span>
                                                    <span style="border-radius: 5px; 
                                                        font-size: 14px;
                                                        text-align: center;
                                                        padding: 2px;  
                                                        width: 70px;
                                                        color: white;
                                                        background-color: #0061A8;
                                                        margin-right: 4px;"
                                                        title="DESCUENTO POR PRE-EMPAQUE: {{QuitarCerosDecimales($dpe)}} %">
                                                        {{QuitarCerosDecimales($dpe)}} %
                                                    </span>
                                                    <span style="font-size: 10px;">
                                                        UP: 
                                                    </span>
                                                    <span style="border-radius: 5px; 
                                                        font-size: 14px;
                                                        text-align: center;
                                                        padding: 2px; 
                                                        width: 70px;
                                                        color: white;
                                                        background-color: #0061A8;
                                                        margin-right: 4px;"
                                                        title="UNIDAD DE PRE-EMPAQUE: {{$cat->up}}">
                                                        {{$cat->up}} 
                                                    </span>
                                                @endif
                                            </td>

                                            <td>
                                                <span title="CODIGO DE BARRA">
                                                    <i class="fa fa-barcode">
                                                        {{$cat->barra}}
                                                    </i>
                                                </span><br>
                                                <span title="MARCA DEL PRODUCTO">
                                                    <i class="fa fa-shield">
                                                        {{$cat->marca}}    
                                                    </i>
                                                </span><br>
                                                @if ($cat->bulto > 0)
                                                <span title="UNIDAD DEl BULTO">
                                                    BULTO: {{$cat->bulto}}<br>
                                                </span>
                                                @endif
                                                @if ($cat->iva > 0)
                                                <span title="VALOR DEL IVA" >
                                                    IVA: {{number_format($cat->iva, 2, '.', ',')}} %
                                                </span>
                                                @endif
                                            </td>
                                            <td style="display: none;">
                                                {{$cat->barra}}
                                            </td>
                                            <td style="display: none;"
                                                align="right">
                                                {{$cat->bulto}}
                                            </td>
                                            <td style="display: none;"
                                                align="right">
                                                {{number_format($cat->iva, 2, '.', ',')}}
                                            </td>
                                            @if (Auth::user()->versionLight == 0)
                                                <td align="right"
                                                    style="background-color: #FEE3CB;">
                                                    {{number_format($transito, 0, '.', ',')}}
                                                </td>
                                                <td align="right"
                                                    title="INVENTARIO" 
                                                    style="background-color: #FEE3CB;">
                                                     {{number_format($cant, 0, '.', ',')}}    
                                                </td>
                                                <td style="display:none;">{{$tpselect}}</td>
                                                <td style="display:none;">{{$tprnk1}}</td>
                                                <td align="right"
                                                    title="COSTO"
                                                    style="background-color: #FEE3CB;">
                                                    {{number_format($costo, 2, '.', ',')}}
                                                </td>
                                                <td align="right"
                                                    title="VMD"
                                                    style="background-color: #FEE3CB;">
                                                    {{number_format($vmd, 4, '.', ',')}}
                                                </td>
                                                <td align="right"
                                                    style="background-color: #FEE3CB;
                                                    color: {{$respDias['color']}}"
                                                    title="{{$respDias['title']}}" >
                                                    {{number_format($dias, 0, '.', ',')}}

                                                    <br>
                                                    <span style="background-color: #FEE3CB;
                                                    color: black;" 
                                                    title="SUGERIDO PARA 15 DIAS" >
                                                    15-> {{number_format($sug15, 0, '.', ',')}}
                                                    </span>
                                                    <br>
                                                    <span style="background-color: #FEE3CB;
                                                    color: black;" 
                                                    title="SUGERIDO PARA 30 DIAS" >
                                                    30-> {{number_format($sug30, 0, '.', ',')}}
                                                    </span>
                                                    <br>
                                                    <span style="background-color: #FEE3CB;
                                                    color: black;"
                                                    title="SUGERIDO PARA 60 DIAS" >
                                                    60-> {{number_format($sug60, 0, '.', ',')}}
                                                    </span>

                                                </td>
                                                <td align="right"
                                                    style="background-color: #FEE3CB; 
                                                    color: #000000;
                                                    display:none;"
                                                    title="SUGERIDO PARA 15 DIAS" >
                                                    {{number_format($sug15, 0, '.', ',')}}
                                                </td> 
                                                <td align="right"
                                                    style="background-color: #FEE3CB; 
                                                    color: #000000;
                                                    display:none;"
                                                    title="SUGERIDO PARA 30 DIAS" >
                                                    {{number_format($sug30, 0, '.', ',')}}
                                                </td> 
                                                <td align="right"
                                                    style="background-color: #FEE3CB; 
                                                    color: #000000;
                                                    display:none;"
                                                    title="SUGERIDO PARA 60 DIAS" >
                                                    {{number_format($sug60, 0, '.', ',')}}
                                                </td>  
                                            @else
                                                <td align="right">
                                                    {{number_format($transito, 0, '.', ',')}}
                                                </td>
                                                <td style="display:none;">
                                                    {{number_format($cant, 0, '.', ',')}}    
                                                </td>
                                                <td style="display:none;">{{$tpselect}}</td>
                                                <td style="display:none;">{{$tprnk1}}</td>
                                                <td style="display:none;">
                                                    {{number_format($costo, 2, '.', ',')}}
                                                </td>
                                                <td style="display:none;">
                                                    {{number_format($vmd, 4, '.', ',')}}
                                                </td>
                                                <td style="display:none;" >
                                                    {{number_format($dias, 0, '.', ',')}}
                                                </td>
                                            @endif
                                            <td align='right' 
                                                style="background-color: {{$confprov->backcolor}}; 
                                                color: {{$confprov->forecolor}};"
                                                title=" {{$confprov->descripcion}}&#10->PRECIO">
                                                {{number_format($liquida, 2, '.', ',')}}
                                            </td>
                                            <td align='right' 
                                                style="background-color: {{$confprov->backcolor}}; 
                                                color: {{$confprov->forecolor}};" 
                                                title=" {{$confprov->descripcion}}&#10->CANTIDAD">{{number_format($cat->cantidad, 0, '.', ',')}}
                                            </td>
                                            <td align="right" 
                                                style="display: none; 
                                                background-color: {{$confprov->backcolor}}; 
                                                color: {{$confprov->forecolor}};"
                                                title=" {{$confprov->descripcion}}&#10->DA">
                                                <b>{{number_format($da, 2, '.', ',')}}</b>
                                            </td>
                                            <td style="background-color: {{$confprov->backcolor}}; 
                                                color: {{$confprov->forecolor}};"
                                                title=" {{$confprov->descripcion}}&#10->CODIGO">
                                                {{$cat->codprod}}
                                            </td>
                                            <td style="display:none;">
                                                {{date('d-m-Y', strtotime($cat->fechafalla))}}
                                            </td>
                                            <td style="display:none; 
                                                background-color: {{$confprov->backcolor}}; 
                                                color: {{$confprov->forecolor}};"
                                                title=" {{$confprov->descripcion}}&#10->PROVEEDOR">
                                                <img style="width: 20px; 
                                                height: 20px; float: left;" 
                                                src="{{$rutalogoprov.$confprov->rutalogo1}}" 
                                                alt="icompras360">
                                                &nbsp;{{$confprov->descripcion}}
                                            </td>
                                            <td style="display:none;">{{$provactivo->codprove}}</td>
                                            <td style="background-color: {{$confprov->backcolor}}; 
                                                color: {{$confprov->forecolor}};"
                                                title=" {{$confprov->descripcion}}&#10->LOTE">
                                                {{$cat->lote}}<br>
                                                <span title="FECHA DE VENCIMIENTO">
                                                    {{$fecvence}}
                                                </span>
                                            </td>
                                            <td style="display:none; 
                                                background-color: {{$confprov->backcolor}}; 
                                                color: {{$confprov->forecolor}};"
                                                title=" {{$confprov->descripcion}}&#10->VENCE">
                                                {{$fecvence}}
                                            </td>
                                        </tr>
                                    @endif
                                @endif
                                @if ($tipo == "R")
                                    @php
                                    $pedir = 1;
                                    $criterio = 'PRECIO';
                                    $preferencia = 'NINGUNA';
                                    $mejoropcion = BuscarMejorOpcion($cat->barra, $criterio, $preferencia, $pedir, $provs);
                                    if ($mejoropcion == null) {
                                        continue;
                                    }
                                    $codprovex = $mejoropcion[0]['codprove'];
                                    $confprov = LeerProve($codprovex); 
                                    if (is_null($confprov))
                                        continue;

                                    if ($codprovex != $tpactivo) 
                                        continue;
                                    @endphp
                                    @if ($codprovex == $tpactivo)
                                    @php
                                    $iFila = $iFila + 1;
                                    @endphp
                                    <tr>
                                        <td>
                                            {{$iFila}}
                                            @if (isset($invent->codprod))
                                            <a href="{{URL::action('PedidoController@tendencia',$invent->codprod)}}">
                                                <h4>
                                                    <i class="{{$tendencia}}" aria-hidden="true"></i>
                                                </h4>
                                            </a>
                                            @endif
                                        </td>
                                        <td style="width: 60px;">
                                            <div align="center">
                                                <a href="{{URL::action('PedidoController@verprod',$cat->barra)}}">

                                                    <img src="http://isaweb.isbsistemas.com/public/storage/prod/{{NombreImagen($cat->barra)}}" 
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
                                            <!-- AGREGAR A CARRO DE COMPRA -->
                                            <div class="col-xs-12 input-group" 
                                                id="idAgregar-{{$iFila}}" >
                                                <input type="number" 
                                                    style="text-align: center; 
                                                    color: #000000; 
                                                    width: 71px;" 
                                                    id="idPedir-{{$iFila}}" 
                                                    value="" 
                                                    class="form-control" 
                                                >
                                                <span class="input-group-btn BtnAgregar">
                                                    
                                                    <button type="button" class="btn btn-pedido

                                                    @if (VerificarCarrito($cat->barra, 'N'))
                                                        colorResaltado
                                                    @endif

                                                    " data-toggle="tooltip" title="Agregar al carrito"
                                                    id="idBtnAgregar-{{$iFila}}" >
                                                        <span class="fa fa-cart-plus" id="idAgregar-{{$iFila}}" aria-hidden="true"></span>
                                                    </button>
                                                
                                                </span>
                                            </div>

                                            <img alt="icompras360"
                                                style="width: 28px; 
                                                float: left; 
                                                background-color: #F0F0F0; 
                                                margin-left: 2px;
                                                margin-top: 4px;"
                                                src="{{$rutalogoprov.$confprov->rutalogo1}}"> 
                                            <span 
                                                style="background-color: #F0F0F0;
                                                width: 110px;"
                                                class="form-control" >
                                                &nbsp;{{$confprov->codprove}}
                                            </span>
                                            
                                        </td>
                                        
                                        <td style="width: 300px;"
                                            title="PRINCIPIO ACTIVO &#10============ &#10{{$cat->pactivo}}">
                                            <b>{{$cat->desprod}}</b>
                                            <div style="margin-top: 5px;"></biv>
                                            @if ($da > 0)
                                                <span style="font-size: 10px;">
                                                    DA: 
                                                </span>
                                                <span style="border-radius: 5px; 
                                                    font-size: 14px;
                                                    text-align: center;
                                                    padding: 2px; 
                                                    width: 70px;
                                                    color: white;
                                                    background-color: #26328C;
                                                    margin-right: 4px;"
                                                    title="DESCUENTO ADICIONAL: {{$oferta}} %">
                                                    {{$oferta}} %
                                                </span>
                                            @endif
                                            @if ($cat->dcredito > 0)
                                                <span style="font-size: 10px;">
                                                    DIAS: 
                                                </span>
                                                <span style="border-radius: 5px; 
                                                    font-size: 14px;
                                                    text-align: center;
                                                    padding: 2px; 
                                                    width: 70px;
                                                    color: white;
                                                    background-color: #26328C;
                                                    margin-right: 4px;"
                                                    title="DIAS DE CREDITO: {{$cat->dcredito}}">
                                                    {{$cat->dcredito}} 
                                                </span>
                                            @endif
                                            <div style="margin-top: 5px;"></biv>
                                            @if ($cat->dp > 0)
                                                <span style="font-size: 10px;">
                                                    DP: 
                                                </span>
                                                <span style="border-radius: 5px; 
                                                    font-size: 14px;
                                                    text-align: center;
                                                    padding: 2px;  
                                                    width: 70px;
                                                    color: white;
                                                    background-color: #0061A8;
                                                    margin-right: 4px;"
                                                    title="DESCUENTO POR PRE-EMPAQUE: {{QuitarCerosDecimales($dpe)}} %">
                                                    {{QuitarCerosDecimales($dpe)}} %
                                                </span>
                                                <span style="font-size: 10px;">
                                                    UP: 
                                                </span>
                                                <span style="border-radius: 5px; 
                                                    font-size: 14px;
                                                    text-align: center;
                                                    padding: 2px; 
                                                    width: 70px;
                                                    color: white;
                                                    background-color: #0061A8;
                                                    margin-right: 4px;"
                                                    title="UNIDAD DE PRE-EMPAQUE: {{$cat->up}}">
                                                    {{$cat->up}} 
                                                </span>
                                            @endif
                                        </td>

                                        <td>
                                            <span title="CODIGO DE BARRA">
                                                <i class="fa fa-barcode">
                                                    {{$cat->barra}}
                                                </i>
                                            </span><br>
                                            <span title="MARCA DEL PRODUCTO">
                                                <i class="fa fa-shield">
                                                    {{$cat->marca}}    
                                                </i>
                                            </span><br>
                                            @if ($cat->bulto > 0)
                                            <span title="UNIDAD DEl BULTO">
                                                BULTO: {{$cat->bulto}}<br>
                                            </span>
                                            @endif
                                            @if ($cat->iva > 0)
                                            <span title="VALOR DEL IVA" >
                                                IVA: {{number_format($cat->iva, 2, '.', ',')}} %
                                            </span>
                                            @endif
                                        </td>
                                        <td style="display: none;">
                                            {{$cat->barra}}
                                        </td>
                                        <td style="display: none;"
                                            align="right">
                                            {{$cat->bulto}}
                                        </td>
                                        <td style="display: none;"
                                            align="right">
                                            {{number_format($cat->iva, 2, '.', ',')}}
                                        </td>
                                        @if (Auth::user()->versionLight == 0)
                                            <td align="right"
                                                style="background-color: #FEE3CB;">
                                                {{number_format($transito, 0, '.', ',')}}
                                            </td>
                                            <td align="right"
                                                title="INVENTARIO"
                                                style="background-color: #FEE3CB;">
                                                {{number_format($cant, 0, '.', ',')}}
                                            </td>
                                            <td style="display:none;">
                                                {{$tpselect}}
                                            </td>
                                            <td style="display:none;">
                                                {{$tprnk1}}
                                            </td>
                                            <td align="right"
                                                title="COSTO"
                                                style="background-color: #FEE3CB;">
                                                {{number_format($costo, 2, '.', ',')}}
                                            </td>
                                            <td align="right"
                                                title="VMD"
                                                style="background-color: #FEE3CB;">
                                                {{number_format($vmd, 4, '.', ',')}}
                                            </td>
                                            <td align="right"
                                                style="background-color: #FEE3CB;
                                                color: {{$respDias['color']}}"
                                                title="{{$respDias['title']}}" >
                                                {{number_format($dias, 0, '.', ',')}}

                                                <br>
                                                <span style="background-color: #FEE3CB;
                                                color: black;" 
                                                title="SUGERIDO PARA 15 DIAS" >
                                                15-> {{number_format($sug15, 0, '.', ',')}}
                                                </span>
                                                <br>
                                                <span style="background-color: #FEE3CB;
                                                color: black;" 
                                                title="SUGERIDO PARA 30 DIAS" >
                                                30-> {{number_format($sug30, 0, '.', ',')}}
                                                </span>
                                                <br>
                                                <span style="background-color: #FEE3CB;
                                                color: black;"
                                                title="SUGERIDO PARA 60 DIAS" >
                                                60-> {{number_format($sug60, 0, '.', ',')}}
                                                </span>

                                            </td>
                                            <td align="right"
                                                style="background-color: #FEE3CB; 
                                                color: #000000;
                                                display:none;"
                                                title="SUGERIDO PARA 15 DIAS" >
                                                {{number_format($sug15, 0, '.', ',')}}
                                            </td> 
                                            <td align="right"
                                                style="background-color: #FEE3CB; 
                                                color: #000000;
                                                display:none;"
                                                title="SUGERIDO PARA 30 DIAS" >
                                                {{number_format($sug30, 0, '.', ',')}}
                                            </td> 
                                            <td align="right"
                                                style="background-color: #FEE3CB; 
                                                color: #000000;
                                                display:none;"
                                                title="SUGERIDO PARA 60 DIAS" >
                                                {{number_format($sug60, 0, '.', ',')}}
                                            </td>  
                                        @else
                                            <td align="right">
                                                {{number_format($transito, 0, '.', ',')}}
                                            </td>
                                            <td style="display:none;">
                                                {{number_format($cant, 0, '.', ',')}}
                                            </td>
                                            <td style="display:none;">
                                                {{$tpselect}}
                                            </td>
                                            <td style="display:none;">
                                                {{$tprnk1}}
                                            </td>
                                            <td style="display:none;">
                                                {{number_format($costo, 2, '.', ',')}}
                                            </td>
                                            <td style="display:none;">
                                                {{number_format($vmd, 4, '.', ',')}}
                                            </td>
                                            <td style="display:none;">
                                                {{number_format($dias, 0, '.', ',')}}
                                            </td>
                                        @endif
                                        <td align='right' 
                                            style="background-color: {{$confprov->backcolor}}; 
                                            color: {{$confprov->forecolor}};"
                                            title=" {{$confprov->descripcion}}&#10->PRECIO">
                                            {{number_format($liquida, 2, '.', ',')}}
                                        </td>
                                        <td align='right' 
                                            style="background-color: {{$confprov->backcolor}}; 
                                            color: {{$confprov->forecolor}};" 
                                            title=" {{$confprov->descripcion}}&#10->CANTIDAD">
                                            {{number_format($cat->cantidad, 0, '.', ',')}}
                                        </td>
                                        <td align="right" 
                                            style="display:none; 
                                            background-color: {{$confprov->backcolor}}; 
                                            color: {{$confprov->forecolor}};"
                                            title=" {{$confprov->descripcion}}&#10->DA">
                                            @if ($da > 0)
                                                <b>{{number_format($da, 2, '.', ',')}}</b>
                                            @else
                                                {{number_format($da, 2, '.', ',')}}
                                            @endif
                                        </td>
                                        <td style="background-color: {{$confprov->backcolor}}; 
                                            color: {{$confprov->forecolor}};"
                                            title=" {{$confprov->descripcion}}&#10->CODIGO">
                                            {{$cat->codprod}}
                                        </td>
                                        <td style="width: 100px; 
                                            display:none;">
                                            {{$cat->fechafalla}}
                                        </td>
                                        <td style="display:none; 
                                            background-color: {{$confprov->backcolor}}; 
                                            color: {{$confprov->forecolor}};"
                                            title=" {{$confprov->descripcion}}&#10->PROVEEDOR">
                                            <img style="width: 20px; 
                                            height: 20px; float: left;" 
                                            src="{{$rutalogoprov.$confprov->rutalogo1}}" 
                                            alt="icompras360">
                                            &nbsp;{{$confprov->descripcion}}
                                        </td>
                                        <td style="display:none;">{{$provactivo->codprove}}</td>
                                        <td style="background-color: {{$confprov->backcolor}}; 
                                            color: {{$confprov->forecolor}};"
                                            title=" {{$confprov->descripcion}}&#10->LOTE">
                                            {{$cat->lote}}<br>
                                            <span title="FECHA DE VENCIMIENTO">
                                                {{$fecvence}}
                                            </span>
                                        </td>
                                        <td style="display:none;
                                            width: 90px; 
                                            background-color: {{$confprov->backcolor}}; 
                                            color: {{$confprov->forecolor}};"
                                            title=" {{$confprov->descripcion}}&#10->VENCE">
                                            {{$fecvence}}
                                        </td>
                                    </tr>
                                    @endif
                                @endif
                                @if ($tipo == "M")
                                    @php
                                    $iFila = $iFila + 1;
                                    @endphp
                                    <tr>
                                        <td>
                                            {{$iFila}}
                                            @if (isset($invent->codprod))
                                            <a href="{{URL::action('PedidoController@tendencia',$invent->codprod)}}">
                                                <h4>
                                                    <i class="{{$tendencia}}" aria-hidden="true"></i>
                                                </h4>
                                            </a>
                                            @endif
                                        </td>
                                        <td style="width: 60px;">
                                            <div align="center">
                                                <a href="{{URL::action('PedidoController@verprod',$cat->barra)}}">

                                                    <img src="http://isaweb.isbsistemas.com/public/storage/prod/{{NombreImagen($cat->barra)}}" 
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
                                            <!-- AGREGAR A CARRO DE COMPRA -->
                                            <div class="col-xs-12 input-group" 
                                                id="idAgregar-{{$iFila}}" >
                                                <input type="number" 
                                                    style="text-align: center; 
                                                    color: #000000; 
                                                    width: 71px;" 
                                                    id="idPedir-{{$iFila}}" 
                                                    value="" 
                                                    class="form-control" 
                                                >
                                                <span class="input-group-btn BtnAgregar">
                                                    
                                                    <button type="button" class="btn btn-pedido

                                                    @if (VerificarCarrito($cat->barra, 'N'))
                                                        colorResaltado
                                                    @endif

                                                    " data-toggle="tooltip" title="Agregar al carrito"
                                                    id="idBtnAgregar-{{$iFila}}" >
                                                        <span class="fa fa-cart-plus" id="idAgregar-{{$iFila}}" aria-hidden="true"></span>
                                                    </button>
                                                
                                                </span>
                                            </div>

                                            <img alt="icompras360"
                                                style="width: 28px; 
                                                float: left; 
                                                background-color: #F0F0F0; 
                                                margin-left: 2px;
                                                margin-top: 4px;"
                                                src="{{$rutalogoprov.$confprov->rutalogo1}}"> 
                                            <span 
                                                style="background-color: #F0F0F0;
                                                width: 110px;"
                                                class="form-control" >
                                                &nbsp;{{$confprov->codprove}}
                                            </span>

                                        </td>

                                        <td style="width: 300px;"
                                            title="PRINCIPIO ACTIVO &#10============ &#10{{$cat->pactivo}}">
                                            <b>{{$cat->desprod}}</b>
                                            <div style="margin-top: 5px;"></biv>
                                            @if ($da > 0)
                                                <span style="font-size: 10px;">
                                                    DA: 
                                                </span>
                                                <span style="border-radius: 5px; 
                                                    font-size: 14px;
                                                    text-align: center;
                                                    padding: 2px; 
                                                    width: 70px;
                                                    color: white;
                                                    background-color: #26328C;
                                                    margin-right: 4px;"
                                                    title="DESCUENTO ADICIONAL: {{$oferta}} %">
                                                    {{$oferta}} %
                                                </span>
                                            @endif
                                            @if ($cat->dcredito > 0)
                                                <span style="font-size: 10px;">
                                                    DIAS: 
                                                </span>
                                                <span style="border-radius: 5px; 
                                                    font-size: 14px;
                                                    text-align: center;
                                                    padding: 2px; 
                                                    width: 70px;
                                                    color: white;
                                                    background-color: #26328C;
                                                    margin-right: 4px;"
                                                    title="DIAS DE CREDITO: {{$cat->dcredito}}">
                                                    {{$cat->dcredito}} 
                                                </span>
                                            @endif
                                            <div style="margin-top: 5px;"></biv>
                                            @if ($cat->dp > 0)
                                                <span style="font-size: 10px;">
                                                    DP: 
                                                </span>
                                                <span style="border-radius: 5px; 
                                                    font-size: 14px;
                                                    text-align: center;
                                                    padding: 2px;  
                                                    width: 70px;
                                                    color: white;
                                                    background-color: #0061A8;
                                                    margin-right: 4px;"
                                                    title="DESCUENTO POR PRE-EMPAQUE: {{QuitarCerosDecimales($dpe)}} %">
                                                    {{QuitarCerosDecimales($dpe)}} %
                                                </span>
                                                <span style="font-size: 10px;">
                                                    UP: 
                                                </span>
                                                <span style="border-radius: 5px; 
                                                    font-size: 14px;
                                                    text-align: center;
                                                    padding: 2px; 
                                                    width: 70px;
                                                    color: white;
                                                    background-color: #0061A8;
                                                    margin-right: 4px;"
                                                    title="UNIDAD DE PRE-EMPAQUE: {{$cat->up}}">
                                                    {{$cat->up}} 
                                                </span>
                                            @endif
                                        </td>

                                        <td>
                                            <span title="CODIGO DE BARRA">
                                                <i class="fa fa-barcode">
                                                    {{$cat->barra}}
                                                </i>
                                            </span><br>
                                            <span title="MARCA DEL PRODUCTO">
                                                <i class="fa fa-shield">
                                                    {{$cat->marca}}    
                                                </i>
                                            </span><br>
                                            @if ($cat->bulto > 0)
                                            <span title="UNIDAD DEl BULTO">
                                                BULTO: {{$cat->bulto}}<br>
                                            </span>
                                            @endif
                                            @if ($cat->iva > 0)
                                            <span title="VALOR DEL IVA" >
                                                IVA: {{number_format($cat->iva, 2, '.', ',')}} %
                                            </span>
                                            @endif
                                        </td>
                                        <td style="display: none;">
                                            {{$cat->barra}}
                                        </td>
                                        <td style="display: none;"
                                            align="right">
                                            {{$cat->bulto}}
                                        </td>
                                        <td style="display: none;"
                                            align="right">
                                            {{number_format($cat->iva, 2, '.', ',')}}
                                        </td>
                                        @if (Auth::user()->versionLight == 0)
                                            <td align="right"
                                                style="background-color: #FEE3CB;"
                                                title = "TRANSITO">
                                                {{number_format($transito, 0, '.', ',')}}
                                            </td>
                                            <td align="right"
                                                style="background-color: #FEE3CB;"
                                                title = "INVENTARIO">
                                                {{number_format($cant, 0, '.', ',')}}
                                            </td>
                                            <td style="display:none;">{{$tpselect}}</td>
                                            <td style="display:none;">{{$tprnk1}}</td>
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
                                            <td align="right"
                                                style="background-color: #FEE3CB;
                                                color: {{$respDias['color']}}"
                                                title="{{$respDias['title']}}" >
                                                {{number_format($dias, 0, '.', ',')}}

                                                <br>
                                                <span style="background-color: #FEE3CB;
                                                color: black;" 
                                                title="SUGERIDO PARA 15 DIAS" >
                                                15-> {{number_format($sug15, 0, '.', ',')}}
                                                </span>
                                                <br>
                                                <span style="background-color: #FEE3CB;
                                                color: black;" 
                                                title="SUGERIDO PARA 30 DIAS" >
                                                30-> {{number_format($sug30, 0, '.', ',')}}
                                                </span>
                                                <br>
                                                <span style="background-color: #FEE3CB;
                                                color: black;"
                                                title="SUGERIDO PARA 60 DIAS" >
                                                60-> {{number_format($sug60, 0, '.', ',')}}
                                                </span>

                                            </td>
                                            <td align="right"
                                                style="background-color: #FEE3CB; 
                                                color: #000000;
                                                display:none;"
                                                title="SUGERIDO PARA 15 DIAS" >
                                                {{number_format($sug15, 0, '.', ',')}}
                                            </td> 
                                            <td align="right"
                                                style="background-color: #FEE3CB; 
                                                color: #000000;
                                                display:none;"
                                                title="SUGERIDO PARA 30 DIAS" >
                                                {{number_format($sug30, 0, '.', ',')}}
                                            </td> 
                                            <td align="right"
                                                style="background-color: #FEE3CB; 
                                                color: #000000;
                                                display:none;"
                                                title="SUGERIDO PARA 60 DIAS" >
                                                {{number_format($sug60, 0, '.', ',')}}
                                            </td> 
                                        @else
                                            <td align="right"
                                                title = "TRANSITO">
                                                {{number_format($transito, 0, '.', ',')}}
                                            </td>
                                            <td style="display:none;">
                                                {{number_format($cant, 0, '.', ',')}}
                                            </td>
                                            <td style="display:none;">{{$tpselect}}</td>
                                            <td style="display:none;">{{$tprnk1}}</td>
                                            <td style="display:none;">
                                                {{number_format($costo, 2, '.', ',')}}
                                            </td>
                                            <td style="display:none;">
                                                {{number_format($vmd, 4, '.', ',')}}
                                            </td>
                                            <td style="display:none;" >
                                                {{number_format($dias, 0, '.', ',')}}
                                            </td>
                                        @endif
                                        <td align='right' 
                                            style="background-color: {{$confprov->backcolor}}; 
                                            color: {{$confprov->forecolor}};">
                                            <span style="font-size: 18px;"
                                                title="PRECIO POR MOLECULA">
                                                {{number_format($cat->liqmolecula, 2, '.', ',')}}
                                            </span>
                                            <br>
                                            <span title="UNIDAD DE LA MOLECULA">
                                                Und: {{$cat->unidadmolecula}}
                                            </span>
                                            <br>
                                            <span title="PRECIO LIQUIDA EL PRODUCTO">
                                                {{number_format($liquida, 2, '.', ',')}}
                                            </span>
                                        </td>
                                        <td align='right' 
                                            style="background-color: {{$confprov->backcolor}}; 
                                            color: {{$confprov->forecolor}};"
                                            title=" {{$confprov->descripcion}}&#10->PRECIO">
                                            {{number_format($precio, 2, '.', ',')}}
                                        </td>
                                        <td align='right' 
                                            style="background-color: {{$confprov->backcolor}}; 
                                            color: {{$confprov->forecolor}};" 
                                            title=" {{$confprov->descripcion}}&#10->CANTIDAD">
                                            {{number_format($cat->cantidad, 0, '.', ',')}}
                                        </td>
                                        <td align="right" 
                                            style="display:none;
                                            background-color: {{$confprov->backcolor}}; 
                                            color: {{$confprov->forecolor}};"
                                            title=" {{$confprov->descripcion}}&#10->DA">
                                            @if ($da > 0)
                                                <b>{{number_format($da, 2, '.', ',')}}</b>
                                            @else
                                                {{number_format($da, 2, '.', ',')}}
                                            @endif
                                        </td>
                                        <td style="background-color: {{$confprov->backcolor}}; 
                                            color: {{$confprov->forecolor}};"
                                            title=" {{$confprov->descripcion}}&#10->CODIGO">
                                            {{$cat->codprod}}
                                        </td>
                                        <td style="width: 100px; 
                                            display:none;">
                                            {{$cat->fechafalla}}
                                        </td>
                                        <td style="display:none; 
                                            background-color: {{$confprov->backcolor}}; 
                                            color: {{$confprov->forecolor}};"
                                            title=" {{$confprov->descripcion}}&#10->PROVEEDOR">
                                            {{$confprov->descripcion}}
                                        </td>
                                        <td style="display:none;">
                                            {{$provactivo->codprove}}
                                        </td>
                                        <td style="background-color: {{$confprov->backcolor}}; 
                                            color: {{$confprov->forecolor}};"
                                            title=" {{$confprov->descripcion}}&#10->LOTE">
                                            {{$cat->lote}}<br>
                                            <span title="FECHA DE VENCIMIENTO">
                                                {{$fecvence}}
                                            </span>
                                        </td>
                                        <td style="display:none;
                                            width: 90px; 
                                            background-color: {{$confprov->backcolor}}; 
                                            color: {{$confprov->forecolor}};"
                                            title=" {{$confprov->descripcion}}&#10->VENCE">
                                            {{$fecvence}}
                                        </td>
                                    </tr>
                                @endif
                            @endif
                        @endforeach
                    @endif
                </table>
                @if (Auth::user()->versionLight == 0)
                    <i class="fa fa-arrow-up text-blue" 
                        style="font-size: 16px;">
                    </i>&nbsp;tendencia a la alta&nbsp;&nbsp;
                    <i class="fa fa-arrow-down text-red" 
                        style="font-size: 16px;">
                    </i>&nbsp;tendencia a la baja &nbsp;&nbsp;
                    <i class="fa fa-circle text-yellow" 
                        style="font-size: 16px;">
                    </i>&nbsp;tendencia estable
                @endif
                <div align='left'>
                    {{$catalogo->appends(["filtro" => $filtro])->links()}}
                </div><br>
            </div>
        </div>
    </div>
    @if ( isset($catalogo) )
        @if ($catalogo->count() == 0)
            <div class="row">
                @if ($tipo=="C")
                    <center><h2>Catálogo de productos sin información</h2></center>
                @endif
                @if ($tipo=="E")
                    <center><h2>Sin Entradas recientes</h2></center>
                @endif
                @if ($tipo=="O")
                    <center><h2>Sin Ofertas de productos</h2></center>
                @endif      
                @if ($tipo=="R")
                    <center><h2>Sin RNK1 de productos</h2></center>
                @endif      
                <br><br><br><br><br><br>
            </div>
        @endif
    @else
        <div class="row">
            <center><h2>Catálogo de productos sin información</h2></center>
            <br><br><br><br><br><br>
        </div>
    @endif
@endif
@push ('scripts')

<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config', 'G-CE7C5GBHWG');
$('#subtitulo').text('{{$subtitulo}}');
var accion = document.getElementById("idaccion").value;
window.onload = function() {
    $('.BtnAgregar').on('click',function(e) {
        var idpedido = e.target.id.split('-');
        var loop = idpedido[1].trim();
        var pedir = $('#idPedir-'+loop).val();
        var id = '{{$tabla->id}}';
        var tipo = '{{$tipo}}';
        var tpactivo = '{{$tpactivo}}';
        var contItem = '{{$contItem}}';
        var item = 0;
        var simboloMoneda = '{{$cfg->simboloMoneda}}';
        var simboloOM = '{{$cfg->simboloOM}}';
        var tasacambiaria = '{{$cfg->tasacambiaria}}';
        var elementStyles = window.getComputedStyle(document.getElementById('idaccion'));
        var color5 = elementStyles.getPropertyValue("--main-color5").trim();
        var Lcolor5 = elementStyles.getPropertyValue("--main-lcolor5").trim();

        var tableReg = document.getElementById('idTabla');
        cellsOfRow = tableReg.rows[ parseInt(loop)+1].getElementsByTagName('td');
        var barra = cellsOfRow[5].innerHTML;
        var tprnk1 =  cellsOfRow[11].innerHTML;
        var codprove = cellsOfRow[10].innerHTML;

        if (tpactivo == "MAESTRO") {
            if (tipo == "C" || tipo == "TOP")  {
                codprove = $('#idProv-'+loop).val();
            } 
        } 
        //alert("TIPO: " + tipo + " - TPACTIVO: " + tpactivo);
        //alert("CLICK: " + barra + " - FILA: " + loop + " - PEDIR: " + pedir + " - CODPROVE: " + codprove + " -TPRNK1: " + tprnk1);
        if (parseInt(pedir) <= 0) {
            alert("CANTIDAD A PEDIR NO PUEDE SER MENOR O IGUAL CERO");
            $('#idPedir-'+loop).val(' ');
        } else {
            
            var jqxhr = $.ajax({
                type:'POST',
                url: '../agregar',
                dataType: 'json', 
                encode  : true,
                data: {barra:barra, id:id, pedir:pedir, codprove:codprove, tprnk1:tprnk1 },
                success:function(data) {
                    if (data.resp != "") {
                        alert(data.msg);
                    } else {
                        if (data.msg != "") 
                            alert(data.msg);
                        $('#idPedir-'+loop).val('');
                        $("#totpedido").text(simboloMoneda + ' ' + number_format(data.total, 2, '.', ','));
                        $("#totpedidoDolar").text(simboloOM + number_format(data.total/tasacambiaria, 2, '.', ','));
                        $("#contreng").text(number_format(data.item, 0, '.', ','));
                        var y = 'idBtnAgregar-'+loop;
                        var btn = document.getElementById(y);
                        btn.style.backgroundColor = color5;
                        btn.style.color = Lcolor5;
                        item = data.item; 
                    }
               }
            });
            //alert( contItem + ' - ' + item );
            jqxhr.always(function() {
                if (contItem == 0 && item > 0) {
                    window.location.reload(); 
                }
            });
        }
    });
}
$(document).keypress(function(e) {
   if(e.which == 13) {
        vAceptar();
   }
});
function vAceptar() {
    var tableReg = document.getElementById('idTabla');
    var id = '{{$tabla->id}}';
    var tipo = '{{$tipo}}';
    var tpactivo = '{{$tpactivo}}';
    var contItem = '{{$contItem}}';
    var resultado = 0;
    var simboloMoneda = '{{$cfg->simboloMoneda}}';
    var simboloOM = '{{$cfg->simboloOM}}';
    var tasacambiaria = '{{$cfg->tasacambiaria}}';
    var elementStyles = window.getComputedStyle(document.getElementById('idaccion'));
    var color5 = elementStyles.getPropertyValue("--main-color5").trim();
    var Lcolor5 = elementStyles.getPropertyValue("--main-lcolor5").trim();
    var codprove = "";
    //alert("TIPO: " + tipo + " - TPACTIVO: " + tpactivo);
    for (var fila = 1; fila < tableReg.rows.length; fila++) {
        var pedir = $('#idPedir-'+fila).val();
        if (parseInt(pedir) > 0) {
            var cellsOfRow = tableReg.rows[fila+1].getElementsByTagName('td');
            var barra = cellsOfRow[5].innerHTML;
            var tprnk1 =  cellsOfRow[11].innerHTML;
            var codprove = cellsOfRow[10].innerHTML;
            if (tpactivo == "MAESTRO") {
                if (tipo == "C" || tipo == "TOP")  {
                    codprove = $('#idProv-'+fila).val();
                } 
            } 
            var loop = fila;
            //alert("CLICK: " + barra + " - FILA: " + fila + " - PEDIR: " + pedir + " - CODPROVE: " + codprove + " -TPRNK1: " + tprnk1);
            resultado = 0;
            var jqxhr = $.ajax({
                type:'POST',
                url: '../agregar',
                dataType: 'json', 
                encode  : true,
                data: {barra:barra, id:id, pedir:pedir, codprove:codprove, tprnk1:tprnk1 },
                success:function(data) {
                    if (data.resp != "") {
                        alert(data.msg);
                        resultado = 0;
                    } else {
                        if (data.msg != "") 
                            alert(data.msg);
                        $('#idPedir-'+loop).val(' ');
                        $("#totpedido").text(simboloMoneda + ' ' + number_format(data.total, 2, '.', ','));
                        $("#totpedidoDolar").text(simboloOM + number_format(data.total/tasacambiaria, 2, '.', ','));
                        $("#contreng").text(number_format(data.item, 0, '.', ','));
                        resultado=1;
                    }
                }
            });
            $('#idPedir-'+loop).val(' ');
            var y = 'idBtnAgregar-'+loop;
            var btn = document.getElementById(y);
            btn.style.backgroundColor = color5;
            btn.style.color = Lcolor5;
        }
    }
    jqxhr.always(function() {
        if (parseInt(contItem) == 0) {
            location.reload(true);
        }
    });
}
</script>
@endpush
@endsection

