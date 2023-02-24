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
        <center><span style="font-size: 20px;"><b>{{ $titulo }}</b></span></center>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <center><span style="font-size: 20px;"><b>{{ $marca }}</b></span></center>
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
                <th align='left' style='width: 7%;  '>BARRA</th>
                <th align='left' style='width: 7%;  '>MARCA</th>
                <th align='right' style='width: 6%; '>CANTIDAD</th>
            </tr>
            @foreach ($pg as $p)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$p->desprod}}</td>
                <td>{{$p->barra}}</td>
                <td>{{$p->codprove}}</td>
                <td align="right">{{number_format($p->cantgrp, 0, '.', ',')}}</td>
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
















