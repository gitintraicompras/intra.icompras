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

<div class="row">
    <div width="100%">
        <div width="30%" style="float: left; margin-top: 15px; margin-left: 15px;">
 
            @php
            $logo = 'logoRpt'.$codprove.'.png';
            $logo = 'http://isaweb.isbsistemas.com/public/storage/'.$logo;
            @endphp

            <img src="{{$logo}}" class="img-responsive" alt="icompras360" style="width: 150px; vertical-align: middle;">
        
        </div> 
        <div width="70%">
            <CENTER><h2 style="margin-top: 5px;">{{$titulo}}</h2></CENTER>
            <CENTER><h3 style="margin-top: 5px;">{{$subtitulo}}</h3></CENTER>
        </div>    
    </div>
</div>
 
<body>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <div class="row" style="margin-top: 20px;">

                <table style="border: 0px; width: 100%;">
                    <tr style="border: 0px;">
                        <td align="right" style="border: 0px;" >EMISION:</td>
                        <td align="left" style="border: medium transparent" ><input type="text" value="{{date('d-m-Y', strtotime($tabla->fecha))}}" /></td>

                        <td align="right" style="border: 0px;" >VENCE:</td>
                        <td align="left" style="border: medium transparent" ><input type="text" value="{{date('d-m-Y', strtotime($tabla->fechav))}}" /></td>

                        <td align="right" style="border: 0px;">MONEDA:</td>
                        <td align="left" style="border: medium transparent;" ><input type="text" value="{{isset($tabla->codmoneda)}}" /></td>

                        <td align="right" style="border: 0px;">VENDEDOR:</td>
                        <td align="left" style="border: medium transparent; width: 100%;" ><input type="text" value="{{isset($tabla->codvend)}}"  /></td>
                    </tr>
                </table>

            </div>
        </div>
    </div>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <div class="row">

                <table style="border: 0px; width: 100%;">
                    <tr style="border: 0px;">
                   
                        <td align="right" style="border: 0px;">IMPUESTO:</td>
                        <td align="left" style="border: medium transparent" ><input type="text" value="{{number_format($tabla->iva, 2, '.', ',')}}"  /></td>

                        <td align="right" style="border: 0px;" >GRAVABLE:</td>
                        <td align="left" style="border: medium transparent" ><input type="text" value="{{number_format($tabla->gravable, 2, '.', ',')}}" /></td>

                        <td align="right" style="border: 0px;">DESCUENTO:</td>
                        <td align="left" style="border: medium transparent" ><input type="text" value="{{number_format($tabla->descuento, 2, '.', ',')}}" /></td>

                        <td align="right" style="border: 0px;">TOTAL:</td>
                        <td align="left" style="border: medium transparent; ; width: 100%;" ><input type="text" value="{{number_format($tabla->total, 2, '.', ',')}}"  /></td>
                    </tr>
                </table>

            </div>
        </div>
    </div>

    <table width="100%" border="1" cellpadding="4" cellspacing="0">   
        <tr style="background-color: #C3C3C3; color: #000000; height: 50px">
            <th align='left'  style='width: 2%;  '>#</th>
            <th align='left'  style='width: 38%; '>DESCRIPCION</th>
            <th align='left'  style='width: 10%; '>CODIGO</th>
            <th align='right' style='width: 10%; '>CANT</th>
            <th align='right' style='width: 10%; '>PRECIO</th>
            <th align='right' style='width: 15%; '>SUBTOTAL</th>
        </tr>
          
        @foreach ($tabla2 as $t)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$t->desprod}}</td>
            <td>{{$t->codprod}}</td>
            <td align="right">{{number_format($t->cantidad, 0, '.', ',')}}</td>
            <td align="right">{{number_format($t->precio, 2, '.', ',')}}</td>
            <td align="right">{{number_format($t->subtotal, 2, '.', ',')}}</td>
        </tr>
        @endforeach

    </table>

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

