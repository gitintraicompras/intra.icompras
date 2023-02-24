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
        font-size: 8px;
        border: 0;
        margin: 4px;
        padding: 4px;
    }
    </style>
</head>


<body>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <img src="http://isaweb.isbsistemas.com/public/storage/prov/icompras.png" 
        class="img-responsive" 
        alt="icompras360" 
        style="width: 150px; vertical-align: middle;"
        oncontextmenu="return false">
    </div>

    <br>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <center>
            <span style="font-size: 20px;">
                {{$tituloppal}}
            </span>
        </center>
    </div>

    <br>
    <div style="width: 100%;" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <div class="row" style="width: 100%;">
                <div class="input-group input-group-sm">
                    <span class="input-group-addon">PEDIDO:</span>
                    <input readonly type="text" value="{{$id}}" style="width: 80px; border: 0px; padding-top: 0px;">

                    <span class="input-group-addon" style="border:0px;">&nbsp;&nbsp;</span>
                    <span class="input-group-addon">CODIGO:</span>
                    <input readonly type="text" value="{{$cliente->codcli}}" style="width: 80px; border: 0px; padding-top: 0px;">

                    <span class="input-group-addon" style="border:0px;">&nbsp;&nbsp;</span>
                    <span class="input-group-addon">CLIENTE:</span>
                    <input readonly type="text" value="{{$cliente->nombre}}" style="width: 450px; border: 0px;padding-top: 0px;">

                </div>
            </div>
        </div>
    </div>

    <div style="width: 100%;" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <div class="row" style="width: 100%;">
                <div class="input-group input-group-sm">
                    <span class="input-group-addon">RIF:</span>
                    <input readonly type="text" value="{{$cliente->rif}}" style="width: 120px; border: 0px; padding-top: 0px;">

                    <span class="input-group-addon" style="border:0px;">&nbsp;&nbsp;</span>              
                    <span class="input-group-addon">TELEFONO:</span>
                    <input readonly type="text" value="{{$cliente->telefono}}" style="width: 150px; border: 0px; padding-top: 0px;">

                    <span class="input-group-addon" style="border:0px;">&nbsp;&nbsp;</span>
                    <span class="input-group-addon">CONTACTO:</span>
                    <input readonly type="text" value="{{$cliente->contacto}}" style="width: 150px; border: 0px; padding-top: 0px;">      

                    <span class="input-group-addon" style="border:0px;">&nbsp;&nbsp;</span>
                    <span class="input-group-addon">ZONA:</span>
                    <input readonly type="text" value="{{$cliente->zona}}" style="width: 16%; border: 0px; padding-top: 0px;">           
                </div>
            </div>
        </div>
    </div>

    <div style="width: 100%;" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group" style="width: 100%; ">
            <div class="row">
                <div class="input-group input-group-sm">
                    <span class="input-group-addon">DIRECCION:</span>
                    <input readonly type="text" value="{{$cliente->direccion}}" style="width: 700px; border: 0px;">
                </div>
            </div>
        </div>
    </div>

    <div style="width: 100%;" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <div class="row" style="width: 100%;">
                <div class="input-group input-group-sm">
                    <span class="input-group-addon">FECHA:</span>
                    <input readonly type="text" value="{{date('d-m-Y H:i', strtotime($tabla->fecenviado))}}" style="width: 150px; border: 0px; padding-top: 0px;">

                    <span class="input-group-addon" style="border:0px;">&nbsp;&nbsp;</span>                    
                    <span class="input-group-addon">IMPUESTO:</span>
                    <input readonly type="text" 
                        value="{{number_format($impuesto, 2, '.', ',')}}" 
                        style=" text-align: right; width: 150px; border: 0px; padding-top: 0px;">

                    <span class="input-group-addon" style="border:0px;">&nbsp;&nbsp;</span>
                    <span class="input-group-addon">TOTAL:</span>
                    <input readonly type="text" 
                        value="{{$moneda}} {{number_format($total, 2, '.', ',')}}" 
                        style="text-align: right; width: 150px; border: 0px; padding-top: 0px;">      

                    <span class="input-group-addon" style="border:0px;">&nbsp;&nbsp;</span>
                    <span class="input-group-addon">ESTADO:</span>
                    <input readonly type="text" value="{{$tabla->estado}}" style="width: 12%; border: 0px; padding-top: 0px;">  
               </div>
            </div>
        </div>
    </div>

    <div class="table-responsive" 
        style="width: 95%; padding-right: 5px;" > 
        <table style="width: 100%; margin-right: 16px;" 
            border="1" 
            cellpadding="4" 
            cellspacing="0" 
            height="20px" >    
            <tr style="width: 100%; background-color: #C3C3C3; color: #000000;">
                <th align='left' style='width: 2%;  '>#</th>
                <th align='left' style='width: 30%; '>DESCRIPCION</th>
                <th align='left' style='width: 7%;  '>CODIGO</th>
                <th align='left' style='width: 7%;  '>BARRA</th>
                <th align='left' style='width: 7%;  '>CODPROV</th>
                <th align='right' style='width: 6%; '>EXIST.</th>
                <th align='right' style='width: 6%; '>CANT</th>
                <th align='right' style='width: 8%; '>PRECIO</th>
                <th align='right' style='width: 5%; '>IVA</th>
                <th align='right' style='width: 5%; '>DA</th>
                <th align='right' style='width: 5%; '>DI</th>
                <th align='right' style='width: 5%; '>DC</th>
                <th align='right' style='width: 5%; '>PP</th>
                <th align='right' style='width: 5%; '>NETO</th>
                <th align='right' style='width: 10%;'>SUBTOTAL</th>
            </tr>
            @foreach ($tabla2 as $t)
            <tr>

                @php
                $neto = CalculaPrecioNeto($t->precio, $t->da, $t->di, $t->dc, $t->pp, $t->dp);
                $subtotal = $neto * $t->cantidad;
                $exist = 0;
                $invent = verificarProdInventario($t->barra, $codcli);
                if (!is_null($invent)) {
                    $exist = $invent->cantidad;
                }
                @endphp

                @if ($t->estado == "ENVIADO" || $t->estado == "RECIBIDO" )
                    <td style="background-color: #C3C3C3; color: #000000;" >{{$loop->iteration}}</td>
                @else
                    <td>{{$loop->iteration}}</td>
                @endif

                <td>{{$t->desprod}}</td>
                <td>{{$t->codprod}}</td>
                <td>{{$t->barra}}</td>
                <td>{{$t->codprove}}</td>
                <td align="right">{{number_format($exist, 0, '.', ',')}}</td>
                <td align="right">{{number_format($t->cantidad, 0, '.', ',')}}</td>
                <td align="right">{{number_format($t->precio/$factor, 2, '.', ',')}}</td>
                <td align="right">{{number_format($t->iva, 2, '.', ',')}}</td>
                <td align="right">{{number_format($t->da, 2, '.', ',')}}</td>
                <td align="right">{{number_format($t->di, 2, '.', ',')}}</td>
                <td align="right">{{number_format($t->dc, 2, '.', ',')}}</td>
                <td align="right">{{number_format($t->pp, 2, '.', ',')}}</td>
                <td align="right">{{number_format($neto/$factor, 2, '.', ',')}}</td>
                <td align="right">{{number_format($subtotal/$factor, 2, '.', ',')}}</td>
            </tr>
            @endforeach
        </table>
    </div>
 
    <h4 style="margin-top: 10px;">
        <center>{{$cfg->nombre}} | RIF: {{$cfg->rif}}</center>
    </h4>

    <h5>
        <center>{{$cfg->direccion}}</center>
    </h5>

    <h5>
        <center>TELEFONO: {{$cfg->telefono}} | CONTACTO: {{$cfg->contacto}}</center>
    </h5>
    <h5 style="margin-top: 5px;">
        <center>Desarrollado por: ISB SISTEMAS, C.A.</center>
    </h5>
</body>

