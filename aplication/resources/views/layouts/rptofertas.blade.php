<head>
    <style>
    @page {
        margin:2px; 
        padding:2px; 
    }
    span {
        vertical-align: middle;
    }
    table, th, td {
        border: 1px solid black;
    }
    h4, h5, h6 {
        margin: 0.5px;
        padding: 0.5px;
    }
    body {
        font-family: Times New Roman;
        font-size: 10px;
        border: 0;
        margin: 4px;
        padding: 4px;
    }
    </style>
</head>

<body>
    <div class="row">
        <div width="100%">
            <div width="30%" style="float: left; margin-top: 15px; margin-left: 15px;">

                @if ($cfg->imagenPdfRutaAbsoluta == 1)
                    <img src="{{ public_path().'/public/storage/logoRpt.png' }}" width="150" >
                @else
                    <img src="{{'http://'.$cfg->nomsubdominio.'/public/storage/logoRpt.png'}}" width="150" > 
                @endif

            </div> 
            <div width="70%">
                <CENTER><h2 style="margin-top: 5px;">{{$subtitulo}}</h2></CENTER>
                <CENTER><h3 style="margin-top: 2px;">FECHA: {{$fecha}}</h3></CENTER>
                <CENTER><h4 style="margin-top: 2px;">{{ $cfg->LiteralTasaCambiaria }}: {{ number_format($cfg->tasacambiaria, 2, '.', ',') }} </h4></CENTER>
            </div>    
        </div>
    </div>

    @if ($orden == 'ALFABETICO')
        @if ($id == 'F') // FALLAS
            <table width="100%" border="1" cellpadding="4" cellspacing="0" style="margin-top: 20px;">    
           
                <tr style="background-color: #C3C3C3; color: #000000; height: 50px">
                    <th align='left' style='width: 5%; '>#</th>
                    <th align='left'  style='width: 7%;'>CODIGO</th>
                    <th align='left'  style='width: 50%;'>DESCRIPCION</th>
                    <th align='left'  style='width: 10%;'>BARRA</th>
                    <th align='left'  style='width: 10%;'>MARCA</th>
                </tr>

                @foreach ($catalogo as $t)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$t->codprod}}</td>
                    <td>{{$t->desprod}}</td>
                    <td>{{$t->barra}}</td>
                    <td>{{$t->marca}}</td>
                </tr>
                @endforeach
            </table>
        @else
            <table width="100%" border="1" cellpadding="4" cellspacing="0" style="margin-top: 20px;">    
           
                <tr style="background-color: #C3C3C3; color: #000000; height: 50px">
                    <th align='left' style='width: 5%; '>#</th>
                   
                    @if ($cfg->mostrarCodigo > 0)
                        <th align='left'  style='width: 10%;'>CODIGO</th>
                    @endif

                    @if ($mostrarCantidad > 0)
                        <th align='left'  style='width: 45%;'>DESCRIPCION</th>
                        <th align='right' style='width: 10%;'>EXISTENCIA</th>
                    @else
                         <th align='left'  style='width: 55%;'>DESCRIPCION</th>
                    @endif

                    @if ($cfg->mostrarDa > 0)
                        <th align='right' style='width: 5%;'>IVA</th>
                        <th align='right' style='width: 5% ;'>DA</th>
                    @else
                        <th align='right' style='width: 10%;'>IVA</th>
                    @endif

                    @if (Auth::user()->tipo=='C')
                        @if ( $cfg->mostrarPrecioOM > 0 )
                            <th align='right' style='width: 10%;'>NETO({{$cfg->simboloOM}})</th>
                        @endif
                        <th align='right' style='width: 10%;'>NETO</th>
                    @else
                        @if ( $cfg->mostrarPrecioOM > 0 )
                            <th align='right' style='width: 10%;'>NETO{{$tipoprecio}}({{$cfg->simboloOM}})</th>
                        @endif
                        <th align='right' style='width: 10%;'>
                            NETO{{$tipoprecio}}
                        </th>
                    @endif
                    
                </tr>

                @foreach ($catalogo as $t)
                <tr>
                	<td>{{$loop->iteration}}</td>
                    @if ($cfg->mostrarCodigo > 0)
                        <td>{{$t->codprod}}</td>
                    @endif
                	<td>{{$t->desprod}}</td>

                    @if ($mostrarCantidad > 0)
                	   <td align='right'>{{number_format($t->cantidad, 0, '.', ',')}}</td>	
                    @endif

                	<td align='right'>{{number_format($t->iva, 2, '.', ',')}}</td>
                    @if ($cfg->mostrarDa > 0)
               		   <td align='right'>{{number_format($t->da, 2, '.', ',')}}</td>
                    @endif

                    @if ($tipoprecio == 1)
                        @if ( $cfg->mostrarPrecioOM > 0 )
                            <?php 
                            $precio1 = $t->precio1/(($cfg->tasacambiaria > 0) ? $cfg->tasacambiaria:1);
                            $precio1 = $precio1 + ($precio1 * $t->da/100);
                            ?>
                            <td align='right'>
                                {{number_format($precio1, 2, '.', ',')}}
                            </td>
                        @endif
                        <td align='right'>{{number_format($t->precio1 + ($t->precio1 * $t->da/100), 2, '.', ',')}}</td>
                    @elseif ($tipoprecio == 2)
                        @if ( $cfg->mostrarPrecioOM > 0 )
                            <?php 
                            $precio2 = $t->precio2/(($cfg->tasacambiaria > 0) ? $cfg->tasacambiaria:1);
                            $precio2 = $precio2 + ($precio2 * $t->da/100);
                            ?>
                            <td align='right'>
                                {{number_format($precio2, 2, '.', ',')}}
                            </td>
                        @endif
                        <td align='right'>{{number_format($t->precio2 + ($t->precio2 * $t->da/100), 2, '.', ',')}}</td>
                    @elseif ($tipoprecio == 3)
                        @if ( $cfg->mostrarPrecioOM > 0 )
                            <?php 
                            $precio3 = $t->precio3/(($cfg->tasacambiaria > 0) ? $cfg->tasacambiaria:1);
                            $precio3 = $precio3 + ($precio3 * $t->da/100);
                            ?>
                            <td align='right'>
                                {{number_format($precio3, 2, '.', ',')}}
                            </td>
                        @endif
                        <td align='right'>{{number_format($t->precio3 + ($t->precio3 * $t->da/100), 2, '.', ',')}}</td>
                    @elseif ($tipoprecio == 4)
                        @if ( $cfg->mostrarPrecioOM > 0 )
                            <?php 
                            $precio4 = $t->precio4/(($cfg->tasacambiaria > 0) ? $cfg->tasacambiaria:1);
                            $precio4 = $precio4 + ($precio4 * $t->da/100);
                            ?>
                            <td align='right'>
                                {{number_format($precio4, 2, '.', ',')}}
                            </td>
                        @endif
                        <td align='right'>{{number_format($t->precio4 + ($t->precio4 * $t->da/100), 2, '.', ',')}}</td>
                    @elseif ($tipoprecio == 5)
                        @if ( $cfg->mostrarPrecioOM > 0 )
                            <?php 
                            $precio5 = $t->precio5/(($cfg->tasacambiaria > 0) ? $cfg->tasacambiaria:1);
                            $precio5 = $precio5 + ($precio5 * $t->da/100);
                            ?>
                            <td align='right'>
                                {{number_format($precio5, 2, '.', ',')}}
                            </td>
                        @endif
                        <td align='right'>{{number_format($t->precio5 + ($t->precio5 * $t->da/100), 2, '.', ',')}}</td>
                    @elseif ($tipoprecio == 6)
                        @if ( $cfg->mostrarPrecioOM > 0 )
                            <?php 
                            $precio6 = $t->precio6/(($cfg->tasacambiaria > 0) ? $cfg->tasacambiaria:1);
                            $precio6 = $precio6 + ($precio6 * $t->da/100);
                            ?>
                            <td align='right'>
                                {{number_format($precio6, 2, '.', ',')}}
                            </td>
                        @endif
                        <td align='right'>{{number_format($t->precio6 + ($t->precio6 * $t->da/100), 2, '.', ',')}}</td>
                    @endif
                    
                </tr>
                @endforeach
            </table>
        @endif
    @else
        @if ($cate)
            @foreach ($cate as $c)
            <div class="row">
            <b>CATEGORIA: {{ $c->nomcat }}</b>
            </div>
            <table width="100%" border="1" cellpadding="4" cellspacing="0">    

                <tr style="background-color: #C3C3C3; color: #000000; height: 50px">
                    <th align='left' style='width: 5%; '>#</th>
                   
                    @if ($cfg->mostrarCodigo > 0)
                        <th align='left'  style='width: 10%;'>CODIGO</th>
                    @endif

                    @if ($mostrarCantidad > 0)
                        <th align='left'  style='width: 45%;'>DESCRIPCION</th>
                        <th align='right' style='width: 10%;'>EXISTENCIA</th>
                    @else
                         <th align='left'  style='width: 55%;'>DESCRIPCION</th>
                    @endif
                    @if ($cfg->mostrarDa > 0)
                        <th align='right' style='width: 5%;'>IVA</th>
                        <th align='right' style='width: 5% ;'>DA</th>
                    @else
                        <th align='right' style='width: 10%;'>IVA</th>
                    @endif

                    @if (Auth::user()->tipo=='C')
                        @if ( $cfg->mostrarPrecioOM > 0 )
                            <th align='right' style='width: 10%;'>NETO({{$cfg->simboloOM}})</th>
                        @endif
                        <th align='right' style='width: 10%;'>NETO</th>
                    @else
                        @if ( $cfg->mostrarPrecioOM > 0 )
                            <th align='right' style='width: 10%;'>NETO{{$tipoprecio}}({{$cfg->simboloOM}})</th>
                        @endif
                        <th align='right' style='width: 10%;'>
                            NETO{{$tipoprecio}}
                        </th>
                    @endif

                </tr>

                <?php $linea=0; ?>
                @foreach ($catalogo as $t)
                @if (!is_null($t->opc1))
                    @if ($c->codcat == $t->opc1)
                    <tr>
                        <?php $linea++; ?>
                        <td>{{$linea}}</td>
                        @if ($cfg->mostrarCodigo > 0)
                            <td>{{$t->codprod}}</td>
                        @endif
                        <td>{{$t->desprod}}</td>

                        @if ($mostrarCantidad > 0)
                           <td align='right'>{{number_format($t->cantidad, 0, '.', ',')}}</td>  
                        @endif
                

                        <td align='right'>{{number_format($t->iva, 2, '.', ',')}}</td>
                        @if ($cfg->mostrarDa > 0)
                           <td align='right'>{{number_format($t->da, 2, '.', ',')}}</td>
                        @endif

                        @if ($tipoprecio == 1)
                            @if ( $cfg->mostrarPrecioOM > 0 )
                                <?php 
                                $precio1 = $t->precio1/(($cfg->tasacambiaria > 0) ? $cfg->tasacambiaria:1);
                                $precio1 = $precio1 + ($precio1 * $t->da/100);
                                ?>
                                <td align='right'>
                                    {{number_format($precio1, 2, '.', ',')}}
                                </td>
                            @endif
                            <td align='right'>{{number_format($t->precio1 + ($t->precio1 * $t->da/100), 2, '.', ',')}}</td>
                        @elseif ($tipoprecio == 2)
                            @if ( $cfg->mostrarPrecioOM > 0 )
                                <?php 
                                $precio2 = $t->precio2/(($cfg->tasacambiaria > 0) ? $cfg->tasacambiaria:1);
                                $precio2 = $precio2 + ($precio2 * $t->da/100);
                                ?>
                                <td align='right'>
                                    {{number_format($precio2, 2, '.', ',')}}
                                </td>
                            @endif
                            <td align='right'>{{number_format($t->precio2 + ($t->precio2 * $t->da/100), 2, '.', ',')}}</td>
                        @elseif ($tipoprecio == 3)
                            @if ( $cfg->mostrarPrecioOM > 0 )
                                <?php 
                                $precio3 = $t->precio3/(($cfg->tasacambiaria > 0) ? $cfg->tasacambiaria:1);
                                $precio3 = $precio3 + ($precio3 * $t->da/100);
                                ?>
                                <td align='right'>
                                    {{number_format($precio3, 2, '.', ',')}}
                                </td>
                            @endif
                            <td align='right'>{{number_format($t->precio3 + ($t->precio3 * $t->da/100), 2, '.', ',')}}</td>
                        @elseif ($tipoprecio == 4)
                            @if ( $cfg->mostrarPrecioOM > 0 )
                                <?php 
                                $precio4 = $t->precio4/(($cfg->tasacambiaria > 0) ? $cfg->tasacambiaria:1);
                                $precio4 = $precio4 + ($precio4 * $t->da/100);
                                ?>
                                <td align='right'>
                                    {{number_format($precio4, 2, '.', ',')}}
                                </td>
                            @endif
                            <td align='right'>{{number_format($t->precio4 + ($t->precio4 * $t->da/100), 2, '.', ',')}}</td>
                        @elseif ($tipoprecio == 5)
                            @if ( $cfg->mostrarPrecioOM > 0 )
                                <?php 
                                $precio5 = $t->precio5/(($cfg->tasacambiaria > 0) ? $cfg->tasacambiaria:1);
                                $precio5 = $precio5 + ($precio5 * $t->da/100);
                                ?>
                                <td align='right'>
                                    {{number_format($precio5, 2, '.', ',')}}
                                </td>
                            @endif
                            <td align='right'>{{number_format($t->precio5 + ($t->precio5 * $t->da/100), 2, '.', ',')}}</td>
                        @elseif ($tipoprecio == 6)
                            @if ( $cfg->mostrarPrecioOM > 0 )
                                <?php 
                                $precio6 = $t->precio6/(($cfg->tasacambiaria > 0) ? $cfg->tasacambiaria:1);
                                $precio6 = $precio6 + ($precio6 * $t->da/100);
                                ?>
                                <td align='right'>
                                    {{number_format($precio6, 2, '.', ',')}}
                                </td>
                            @endif
                            <td align='right'>{{number_format($t->precio6 + ($t->precio6 * $t->da/100), 2, '.', ',')}}</td>
                        @endif
                        

                    </tr>
                    @endif
                @endif
                
                @endforeach
            </table>
            <br>
            @endforeach
        @endif  
    @endif

    <h4 style="margin-top: 10px;">
        <center>{{$cfg->nombre}} | RIF: {{$cfg->rif}}</center>
    </h4>

    <h5>
        <center>{{$cfg->direccion}}</center>
    </h5>

    <h5>
        <center>TELEFONO: {{$cfg->telefono}} | CONTACTO: {{$cfg->contacto}}</center>
    </h5>
    
</body>

