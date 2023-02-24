@extends('layouts.menu')
@section ('contenido')
 
@php
  $moneda = Session::get('moneda', 'BSS');
  $factor = RetornaFactorCambiario("", $moneda);
  $x=0;
@endphp 
 
<!-- BOTONES BUSCAR -->
<div class="btn-toolbar" role="toolbar" style="margin-top: 12px; margin-bottom: 3px;">
    <div class="btn-group" role="group" style="width: 100%;">
        <!-- BOTON BUSCAR -->
        @include('isacom.invgrupo.search')
    </div>
</div>

<!-- TABLA -->
<div class="row" style="margin-top: 5px;">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="table-responsive">

            <table id="table-1" class="table table-bordered table-condensed" >

                <thead style="background-color: #b7b7b7;">
                    <tr>
                        <th id="col1-1" style="width: 1130;" colspan="7">
                            <center>INVENTARIO MAESTRO DE PRODUCTOS</center>
                        </th>

                        <th id="col1-2" style="width: 200; background-color: #FEE3CB;" colspan="2">
                            <center>&nbsp;&nbsp;&nbsp;&nbsp;PROVEEDOR&nbsp;&nbsp;&nbsp;&nbsp;</center>
                        </th>

                        <th id="col1-3" style="width: 200; background-color: #FCD0C7;" colspan="2">
                            <center>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;GRUPO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            </center>
                        </th>
                        @foreach ($gruporen as $gr)

                            @php 
                            $x=$x+1;
                            if (!VerificaCampoTabla($nomtcmaestra, 'tc'.$gr->codcli))
                                continue;
                            $confcli = LeerCliente($gr->codcli); 
                            $actualizado = date('d-m-Y H:i', strtotime(LeerTablaFirst('inventario_'.$gr->codcli, 'feccatalogo')));
                            $fechaHoy = trim(date("Y-m-d"));
                            $fechaInv = trim(date('Y-m-d', strtotime($actualizado)));
                            @endphp
                            
                            <th id="col1-{{$x}}" 
                                colspan="6" 
                                style="background-color: {{$confcli->backcolor}}; 
                                color: {{$confcli->forecolor}}; 
                                width: 400px; 
                                word-wrap: break-word; ">
                                <a href="{{URL::action('GrupoController@show',$gr->codcli)}}">
                                    <center>
                                        <button type="button" 
                                            data-toggle="tooltip" 
                                            title="{{$confcli->nombre}} &#10 ({{$actualizado}})" 
                                            style="background-color: {{$confcli->backcolor}}; 
                                            border: none;
                                            @if ($fechaInv != $fechaHoy)
                                                color: red;
                                            @else
                                                color: {{$confcli->forecolor}};
                                            @endif 
                                            "> {{$confcli->descripcion}}
                                        </button>
                                    </center>
                                </a>
                            </th>
                        @endforeach
                    </tr>
                    <tr>
                        <th style="width: 10px;">
                            <center>#</center>
                        </th>
                        <th style="width: 120px;">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;IMAGEN&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        </th>
                        <th style="width: 300px;">PRODUCTO</th>
                        <th style="width: 100px;">REFERENCIA</th>
                        <th style="width: 100px;">MARCA</th>
                        <th style="width: 60px;">BULTO</th>
                        <th style="width: 60px;">IVA</th>

                        <!-- PROVEEDOR -->                        
                        <th style="background-color: #FEE3CB;" 
                            title="Mejor precio del proveedor">
                            MPP &nbsp;&nbsp;&nbsp;&nbsp;
                        </th>
                        <th style="background-color: #FEE3CB;"
                            title="Unidades consolidada del inventario de los proveedores">
                            INV.
                        </th>

                        <!-- GRUPO  -->
                        <th style="background-color: #FCD0C7;"
                            title="Unidades en transito">
                            TRAN.
                        </th>
                        <th style="background-color: #FCD0C7;"
                            title="Unidades consolidada">
                            INV.&nbsp;&nbsp;&nbsp;&nbsp;
                        </th>

                        @foreach ($gruporen as $gr)
                            @php 
                            if (!VerificaCampoTabla($nomtcmaestra, 'tc'.$gr->codcli))
                                continue;
                            $confcli = LeerCliente($gr->codcli); 
                            @endphp
                            <th style="background-color: {{$confcli->backcolor}}; 
                                color: {{$confcli->forecolor}}; 
                                width: 200px; word-wrap: break-word; "
                                title="Precio de venta del producto">
                                PRECIO({{$confcli->usaprecio}})&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            </th> 
                            <th style="background-color: {{$confcli->backcolor}}; 
                                color: {{$confcli->forecolor}}; 
                                width: 200px; word-wrap: break-word; "
                                title="Unidades en transito">
                                TRAN.
                            </th>
                            <th style="background-color: {{$confcli->backcolor}}; 
                                color: {{$confcli->forecolor}}; 
                                width: 200px; word-wrap: break-word; "
                                title="Inventario del producto">
                                INV.
                            </th>
                            <th style="background-color: {{$confcli->backcolor}}; 
                                color: {{$confcli->forecolor}}; 
                                width: 200px; word-wrap: break-word; "
                                title="Costo del producto">
                                COSTO
                            </th>
                            <th style="background-color: {{$confcli->backcolor}}; 
                                color: {{$confcli->forecolor}}; 
                                width: 200px; word-wrap: break-word; "
                                title="Venta Media Diaria del producto">
                                VMD
                            </th>
                            <th style="background-color: {{$confcli->backcolor}}; 
                                color: {{$confcli->forecolor}}; 
                                width: 200px; word-wrap: break-word; "
                                title="Dias de Inventario">
                                DIAS
                            </th>
                        @endforeach
                    </TR>
                </thead>
                  
                @php
                $iFila = 0;
                $fechaHoy = date('Y-m-d');
                @endphp
 
                @if ( isset($catalogo) )
                    @foreach ($catalogo as $cat) 
	                    @php
						if ($cat->barra == "")
							continue;
	                    $arrayRnk = [];
	                    $entra = FALSE;
	                    $menor = 100000000000000;
	                    $mayorInv = 0;
	                    $tpselect = "";
	                    $contcli = 0;
	                    $invConsolGrupo = 0;

                        // MEJOR PRECIO PROVEEDOR
                        $mpp = 0.00;
                        $pedir = 1;
                        $criterio = 'PRECIO';
                        $preferencia = 'NINGUNA';
                        $provs = TablaMaecliproveActiva("");
                        $mejoropcion = BuscarMejorOpcion($cat->barra, $criterio, $preferencia, $pedir, $provs);
                        if ($mejoropcion != null) {

                            $precio = $mejoropcion[0]['precio'];
                            $da = $mejoropcion[0]['da'];
                            $codprove = $mejoropcion[0]['codprove'];
                            $maeclieprove = DB::table('maeclieprove')
                            ->where('codcli','=',$codcliente)
                            ->where('codprove','=',$codprove)
                            ->first();
                            if ($maeclieprove) {
                                $dc = $maeclieprove->dcme;
                                $di = $maeclieprove->di;
                                $pp = $maeclieprove->ppme;
                                $mpp = CalculaPrecioNeto($precio, $da, $di, $dc, $pp, 0.00);
                            } 
                        }


	                    foreach ($gruporen as $gr) { 
	                        if (!VerificaCampoTabla($nomtcmaestra, 'tc'.$gr->codcli))
                            	continue;
                        	$confcli = LeerCliente($gr->codcli);
	                        $codcli = strtolower($gr->codcli);
	                        try {
	                        	$tc = 'tc'.$codcli;
	                            $campos = $cat->$tc;
	                            $campo = explode("|", $campos);
	                        } catch (Exception $e) {
	                            continue;
	                        }
	                        $precio = $campo[0]/$factor;
	                        $cantidad = $campo[1];
                            $entra = TRUE; 
                            $contcli++;
                            $invConsolGrupo = $invConsolGrupo + $cantidad;
                            $liquida = $precio + (($precio * $cat->iva)/100);

                            if ($liquida > 0) {
                                $arrayRnk[] = [
                                    'liquida' => $liquida,
                                    'codcli' => $codcli
                                ];
                                if ($liquida < $menor) {
                                    $menor = $liquida; 
                                }
                            }

                            if ($cantidad > $mayorInv) {
                                 $mayorInv = $cantidad;          
                            }
	                    }
	                    if ($entra == FALSE) 
	                        continue;
	                    $aux = array();
                        foreach ($arrayRnk as $key => $row) {
                            $aux[$key] = $row['liquida'];
                        }
                        if (count($aux) > 1)
                            array_multisort($aux, SORT_ASC, $arrayRnk);

	                    $iFila++;
                        $title = "Sin Inventario";
	                    $invConsolProv = iCantidadConsolidadoProv($cat->barra);

	                    $backcolor = "#FFFFFF";
	                    $forecolor = "#000000"; 
                        $alerta = 0;
	                    if ($invConsolProv > 0 && $invConsolGrupo <= 0) {
	                    	// ROJO -> ALERTA
	                    	$title = "Alerta -> se debe pedir este producto";
	                    	$backcolor = "#FF0000";
	                    	$forecolor = "#FFFFFF";
                            $alerta = 1;
	                	}
	                	if ($invConsolProv > 0 && $invConsolGrupo > 0) {
	                		// VERDE -> IDEAL
	                    	$title = "Ideal -> tienes inventario, al igual que los proveedores";
	                		$backcolor = "#00B621";
	                    	$forecolor = "#FFFFFF"; 
                            $alerta = 2;
	                	}
	                	if ($invConsolProv <= 0 && $invConsolGrupo > 0) {
	                		// AMARILLO -> WARNING
	                    	$title = "Advertencia -> tienes inventario, pero los proveedores no tienen";
	                		$backcolor = "#FFD800";
	                    	$forecolor = "#000000"; 
                            $alerta = 3;
	                	}
	                	if ($invConsolProv <= 0 && $invConsolGrupo < 0) {
	                		// GRAVE -> NARANJA
	                    	$title = "Grave -> Sin inventario, igual los proveedores ";
	                		$backcolor = "#FF6A00";
	                    	$forecolor = "#FFFFFF"; 
                            $alerta = 4;
	                	}
	                    @endphp
                        <tr>
	                        <td style="width: 10px;">
                                <center>
                               	{{$iFila}}
                                <i class="fa fa-thumbs-up" 
                                    aria-hidden="true"
                                    style="font-size: 20px;
                                    color: {{$backcolor}}; "
                                    title="{{$title}}" >
                                </i>
                                </center>
	                        </td>

	                        <td style="width: 120px;"
                                title="IMAGEN DE REFERENCIA">
	                            <div align="center">
	                            	<a href="{{URL::action('PedidoController@verprod',$cat->barra)}}">

	                                    <img src="http://isaweb.isbsistemas.com/public/storage/prod/{{NombreImagen($cat->barra)}}" 
	                                    class="img-responsive" 
	                                    alt="icompra" 
                                        width="100%" 
                                        height="100%"
                                        style="border: 2px solid #D2D6DE;"
                                        oncontextmenu="return false" >
	                    
	                                </a>
	                            </div>
	                        </td>

	                        <td style="width: 300px;"
                                title="DESCRIPCION DEL PRODUCTO">
                                {{$cat->desprod}}
                            </td>

	                        <td style="width: 100px;"
                                title="CODIGO DE BARRA">
                                {{$cat->barra}}
                            </td>

	                        <td style="width: 300px;"
                                title="MARCA DEL PRODUCTO">
                                {{$cat->marca}}
                            </td>

	                        <td align="right" 
                                style="width: 60px;"
                                title="UNIDAD DE MANEJO">
                                {{$cat->bulto}}
                            </td>

	                        <td align="right" 
                                style="width: 60px;"
                                title="IVA PRODUCTO">
                                {{number_format($cat->iva, 2, '.', ',')}}
                            </td>

	     
                            <!-- PROVEEDOR-->
	                        <td align="right" 
                                style="width: 60px; background-color: #FEE3CB;"
                                title="MEJOR PRECIO DEL PROVEEDOR">
	                            {{number_format($mpp, 2, '.', ',')}}
	                        </td>
	                        <td align="right" 
                                style="width: 60px; background-color: #FEE3CB;"
                                title="CONSOLIDADO DEL PROVEEDOR">
	                        	{{number_format($invConsolProv, 0, '.', ',')}}
	                        </td>

                            <!-- GRUPO -->
                            <td align="right" 
                                style="width: 60px; background-color: #FCD0C7;"
                                title="UNIDADES EN TANSITO">
                                {{number_format(verificarProdTransito($cat->barra,  "", $codgrupo), 0, '.', ',')}}
                            </td>
                            <td align="right" 
                                style="width: 60px; background-color: #FCD0C7;"
                                title="CONSOLIDADO DEL GRUPO">
                                {{number_format($invConsolGrupo, 0, '.', ',')}}
                            </td>

       		                @foreach ($gruporen as $gr)
	                            @php
	                                $codcli = strtolower($gr->codcli);
	                                if (!VerificaCampoTabla($nomtcmaestra, 'tc'.$gr->codcli))
	                                    continue;
	                                try {
	                                    $tc = 'tc'.$codcli;
	                            		$campos = $cat->$tc;
	                            		$campo = explode("|", $campos);
	                                } catch (Exception $e) {
	                                    continue;
	                                }
	                                $actualizado = date('d-m-Y H:i', strtotime(LeerTablaFirst('inventario_'.$codcli, 'feccatalogo')));
	                                $precio = $campo[0]/$factor;
	                                $cantidad = $campo[1];
	                                $codprod = $campo[2];
	                                $desprod = $campo[3];
	                                $costo = $campo[4]/$factor;
	                                $vmd = $campo[5];
	                                $confcli = LeerCliente($gr->codcli); 
	                                $liquida = $precio + (($precio * $cat->iva)/100);
	                                $ranking = obtenerRanking($liquida, $arrayRnk);
	                            @endphp

	                            @if ($liquida == 0)
	                                <td align='right' 
                                        title="{{$confcli->descripcion}} -> PRECIO" 
                                        style="width: 200px; 
                                        word-wrap: break-word; 
                                        background-color: {{$confcli->backcolor}};
	                                    color: {{$confcli->forecolor}}; ">
	                                    {{number_format($liquida, 2, '.', ',')}}
	                                </td>
	                            @else
                                    <td align='right' style="width: 200px; word-wrap: break-word; background-color: {{$confcli->backcolor}};
                                     color: {{$confcli->forecolor}}; " 
                                     
                                     title = "{{$confcli->descripcion}} &#10 ======================== &#10 PRECIO: {{number_format($precio, 2, '.', ',')}} &#10 TIPO: 1 &#10 DA: 0.00 &#10 IVA: {{number_format($cat->iva, 2, '.', ',')}} &#10 RANKING: {{$ranking}} &#10 CODIGO: {{$codprod}} &#10 ACTUALIZADO: {{$actualizado}} &#10 ======================== &#10 LIQUIDA: {{number_format($liquida, 2, '.', ',')}} &#10 ">
                                    @if ($liquida == $menor)
                                    	<i class="fa fa-check"></i>
                                    	{{number_format($liquida, 2, '.', ',')}}
                                    @else
                                    	{{number_format($liquida, 2, '.', ',')}} 
                                    @endif
                                    @if ($ranking)
                                    	&#10 <div>Rnk:{{$ranking}}</div> 
                                    @endif
                                    </td>
	                            @endif

                                <td align='right' 
                                    style="width: 200px; 
                                    word-wrap: break-word; 
                                    background-color: {{$confcli->backcolor}}; 
                                    color: {{$confcli->forecolor}};" 
                                    title="{{$confcli->descripcion}} -> TRANSITO">
                                    {{number_format(verificarProdTransito($cat->barra,  $codcli, ""), 0, '.', ',')}}
                                </td>
       
                                <td align='right' 
                                    style="width: 200px; 
                                    word-wrap: break-word; 
                                    background-color: {{$confcli->backcolor}}; 
                                    color: {{$confcli->forecolor}};" 
                                    title="{{$confcli->descripcion}} -> INVENTARIO">
                                	@if ($mayorInv == $cantidad && $mayorInv != 0 )
                                	    <i class="fa fa-check"></i>
                                		{{number_format($cantidad, 0, '.', ',')}}
                                	@else
                                		{{number_format($cantidad, 0, '.', ',')}}
                                	@endif
                                </td>
	                     
	                            <td align='right' 
                                    style="width: 200px; 
                                    word-wrap: break-word; 
                                    background-color: {{$confcli->backcolor}}; 
	                            	color: {{$confcli->forecolor}};" 
	                            	title="{{$confcli->descripcion}} -> COSTO">
	                            	{{number_format($costo, 2, '.', ',')}}
	                            </td>

	                            <td align='right' 
                                    style="width: 200px; 
                                    word-wrap: break-word; 
                                    background-color: {{$confcli->backcolor}}; 
	                            	color: {{$confcli->forecolor}};" 
	                            	title="{{$confcli->descripcion}} -> VMD">
	                            	{{number_format($vmd, 4, '.', ',')}}
	                            </td>

	                            <td align='right' 
                                    style="width: 200px; 
                                    word-wrap: break-word; 
                                    background-color: {{$confcli->backcolor}}; 
	                            	color: {{$confcli->forecolor}};" 
	                            	title="{{$confcli->descripcion}} -> DIAS">
                                    @if ($vmd > 0)
	                            	  {{number_format($cantidad/$vmd, 2, '.', ',')}}
                                    @else
                                      0.00
                                    @endif
	                            </td>
	                        @endforeach
	                    </tr>
                    @endforeach
                @endif
            
            </table>

            <div align='left'>
                @if (isset($catalogo))
                    {{$catalogo->appends(["filtro" => $filtro])->links()}}
                @endif
            </div><br>
        
        </div>
    </div>
</div>

@if ( $catalogo->count() <= 0 )
<div class="row">
    <center><h2>Catálogo de productos vacio</h2></center>
    <br><br><br><br><br><br>
</div>
@endif

@push ('scripts')

<script>
$('#titulo').text('{{$subtitulo}}');
$('#subtitulo').text('{{$subtitulo2}}');
</script>

@endpush
@endsection

