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

    <div class="row">
        <div width="100%">
            <div width="30%" style="float: left; margin-left: 15px;">
                <img src="http://isaweb.isbsistemas.com/public/storage/favisb.png" 
                alt="icompras360" 
                width="80"
                oncontextmenu="return false">
            </div> 
            <div width="70%">
                <CENTER><h2 style="margin-top: 5px;">{{$titulo}}</h2></CENTER>
                <CENTER><h2 style="margin-top: 5px;">{{$subtitulo}}</h2></CENTER>
                <CENTER><h3 style="margin-top: 2px;">FECHA: {{$fecha}}</h3></CENTER>
            </div>    
        </div>
    </div>
    <br><br>
    <div width="100%" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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

    <div width="100%" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group" style="width: 100%; ">
            <div class="row">
                <div class="input-group input-group-sm">
                    <span class="input-group-addon">DIRECCION:</span>
                    <input readonly type="text" value="{{$cliente->direccion}}" style="width: 700px; border: 0px;">
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div width="100%" class="table-responsive"> 

            <table width="100%" border="1" cellpadding="4" cellspacing="0" height="20px" >    
           
                <tr style="background-color: #C3C3C3; color: #000000;">
                    <th align='left' style='width: 2%;  '>#</th>
                    <th align='left' style='width: 68%; '>DESCRIPCION</th>
                    <th align='left' style='width: 10%; '>CODIGO</th>
                    <th align='left' style='width: 10%; '>BARRA</th>
                    <th align='right' style='width: 10%; '>CANTIDAD</th>
                 </tr>

                @foreach ($tabla2 as $t)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$t->desprod}}</td>
                    <td>{{$t->codprod}}</td>
                    <td>{{$t->barra}}</td>
                    <td align="right">{{number_format($t->pedir, 0, '.', ',')}}</td>
                </tr>
                @endforeach
            </table>

        </div>
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

