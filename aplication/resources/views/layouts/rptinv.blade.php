@php
  $moneda = Session::get('moneda', 'BSS');
  $factor = RetornaFactorCambiario("", $moneda);
@endphp
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
            <div width="30%" style="float: left; margin-left: 15px;">
                <img src="http://isaweb.isbsistemas.com/public/storage/favisb.png" 
                alt="icompras360" 
                width="100"
                oncontextmenu="return false">
            </div> 
            <div width="70%">
                <CENTER><h2 style="margin-top: 5px;">{{$titulo}}</h2></CENTER>
                <CENTER><h2 style="margin-top: 5px;">{{$subtitulo}}</h2></CENTER>
                <CENTER><h3 style="margin-top: 2px;">FECHA: {{$fecha}}</h3></CENTER>
            </div>    
        </div>
    </div>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div width="100%" class="table-responsive"> 

            <table width="100%" border="1" cellpadding="4" cellspacing="0" height="20px" >    
    
   
                <tr style="background-color: #C3C3C3; color: #000000; height: 50px">
                    <th align='left' style='width: 5%; '>#</th>
                   
                    <th align='left'  style='width: 10%;'>CODIGO</th>
                    <th align='left'  style='width: 10%;'>BARRA</th>
                    <th align='left'  style='width: 45%;'>DESCRIPCION</th>
                    <th align='right' style='width: 10%;'>CANTIDAD</th>
                    <th align='right' style='width: 10%;'>IVA</th>
                    <th align='right' style='width: 10%;'>PRECIO({{$moneda}})</th>
                    
                </tr>

                @foreach ($catalogo as $t)
                <tr>
                	<td>{{$loop->iteration}}</td>
                    <td>{{$t->codprod}}</td>
                    <td>{{$t->barra}}</td>
                	<td>{{$t->desprod}}</td>
                    <td align='right'>{{number_format($t->cantidad, 0, '.', ',')}}</td>	
                	<td align='right'>{{number_format($t->iva, 2, '.', ',')}}</td>
                    <td align='right'>{{number_format($t->precio1/$factor, 2, '.', ',')}}</td>
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

