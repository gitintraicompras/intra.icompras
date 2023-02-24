@extends ('layouts.menu')
@section ('contenido')

@php
  $moneda = Session::get('moneda', 'BSS');
  $rutalogoprov = 'http://isaweb.isbsistemas.com/public/storage/prov/';
@endphp 

<!-- ENCABEZADO -->
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="form-group">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 input-group input-group-sm">
                
                <span class="input-group-addon">Pedido:</span>
                <input readonly type="text" class="form-control" value="{{$tabla->id}}" style="color: #000000">

                <span class="input-group-addon hidden-xs" style="border:0px; "></span>
                <span class="input-group-addon hidden-xs">Estado:</span>
                <input readonly type="text" class="form-control hidden-xs" value="{{$tabla->estado}}" style="color: #000000">

                <span class="input-group-addon hidden-xs" style="border:0px; "></span>
                <span class="input-group-addon hidden-xs">Fecha:</span>
                <input readonly type="text" class="hidden-xs form-control" value="{{date('d-m-Y H:i', strtotime($tabla->fecha))}}" style="color: #000000">

                <span class="input-group-addon hidden-xs" style="border:0px; "></span>
                <span class="input-group-addon hidden-xs">Enviado:</span>
                <input readonly type="text" class="form-control hidden-xs" value="{{date('d-m-Y H:i', strtotime($tabla->fecenviado))}}" style="color:#000000" >    

            </div>
        </div>
        <div class="row hidden-sm hidden-xs" style="margin-top: 4px;">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 input-group input-group-sm">
                
                <span class="input-group-addon">Subtotal:</span>
                <input readonly 
                    type="text" 
                    class="form-control" 
                    value="{{number_format($tabla->subtotal, 2, '.', ',')}}" 
                    style="color: #000000; text-align: right;" 
                    id="idSubtotal">

                <span class="input-group-addon" style="border:0px; "></span>
                <span class="input-group-addon">Impuesto:</span>
                <input readonly 
                    type="text" 
                    class="form-control" 
                    value="{{number_format($tabla->impuesto, 2, '.', ',')}}" 
                    style="color: #000000; text-align: right;" 
                    id="idImpuesto">

                <span class="input-group-addon" style="border:0px; "></span>
                <span class="input-group-addon">Total:</span>
                <input readonly 
                    type="text" 
                    class="form-control" 
                    value="{{number_format($tabla->total, 2, '.', ',')}}" 
                    style="color: #000000; text-align: right;" 
                    id="idTotal">
            </div>
        </div>
    </div>
</div>
 
<!-- BOTONES BUSCAR, GUARDAR, ENVIAR -->
<div class="btn-toolbar" role="toolbar" style="margin-top: 12px; margin-bottom: 3px;">
    <div class="btn-group" role="group" style="width: 100%;">

        @include('isacom.pedidodirecto.editsearch')

        <!-- AGREGAR PRODUCTO NUEVO -->
        <a href="" 
            data-target="#modal-agregar-{{$id}}" 
            data-toggle="modal">
            <button style="width: 90px; height: 34px; border-radius: 5px;" 
                type="button" 
                data-toggle="tooltip" 
                title="Agregar producto nuevo al pedido" 
                class="btn-catalogo">
                Agregar
            </button>
        </a>
   

        @if ($contItem > 0)
            @if ($btnguardar > 0)
                <!-- GUARDAR PEDIDO -->
                <a href="" 
                    data-target="#modal-guardar-{{$tabla->id}}" 
                    data-toggle="modal">
                    <button style="width: 90px; height: 34px; border-radius: 5px;" 
                        type="button" 
                        data-toggle="tooltip" 
                        title="Guardar pedido" 
                        class="btn-catalogo">
                        Guardar
                    </button>
                </a>
            @else
                <!-- ENVIA PEDIDO -->
                <a href="" 
                    data-target="#modal-enviar-{{$tabla->id}}" 
                    data-toggle="modal">
                    <button style="margin-left: 2px; width: 90px; height: 34px; border-radius: 5px;" 
                        type="button" 
                        data-toggle="tooltip" 
                        title="Enviar pedido" 
                        class="btn-confirmar">
                        Enviar
                    </button>
                </a>
            @endif
        @endif
       
    </div>
</div>
@include('isacom.pedidodirecto.agregar') 
@include('isacom.pedidodirecto.guardar')  
@include('isacom.pedidodirecto.enviar')  

<!-- TABLA -->
<div class="row" style="margin-top: 5px;">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="table-responsive">
            <table id="myTable" 
                class="table table-striped table-bordered table-condensed table-hover">
                <thead style="background-color: #b7b7b7;">
                    <th style="vertical-align:middle;">#</th>
                    <th style="width: 90px; vertical-align:middle;" >
                        &nbsp;&nbsp;&nbsp;IMAGEN&nbsp;&nbsp;&nbsp;
                    </th>
                    <th style="width: 170px; vertical-align:middle;">
                        PEDIR
                    </th>
                    <th>
                        <center>
                        <div style="width: 70px;">
                            <span class="input-group-btn">
                                <div class="col-xs-12 input-group" >
                                    <input type="checkbox" 
                                        id="selectall"
                                        title="marcar/desmarcar todos los producto"
                                        style="width: 50%; vertical-align:middle;">

                                    <a href="" data-target="#modal-deleteAll" data-toggle="modal">
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
                    <th style="vertical-align:middle;"
                        title="DESCRIPCION DEL PRODUCTO">
                        PRODUCTO
                    </th>
                    <th style="vertical-align:middle;"
                        title="BARRA DEL PRODUCTO">
                        BARRA
                    </th>
                    <th style="vertical-align:middle;"
                        title="CODIGO DEL PRODUCTO">
                        CODIGO
                    </th>
                    <th style="vertical-align:middle;"
                        title="IMPUESTO AL VALOR AGREGADO DEL PRODUCTO">
                        IVA
                    </th>
                    <th style="vertical-align:middle;"
                        title="COSTO DEL PRODUCTO">
                        COSTO
                    </th>
                    <th style="vertical-align:middle;"
                        title="SUBTOTAL COSTO DEL PRODUCTO">
                        SUBTOTAL
                    </th>

                    <th style="vertical-align:middle;"
                        title="UNIDADES EN TRANSITO DEL PRODUCTO">
                        TRAN.
                    </th>
                    <th style="vertical-align:middle;"
                        title="INVENTARIO DEL PRODUCTO">
                        INV.
                    </th>
                    <th style="vertical-align:middle;"
                        title="VENTA MEDIA DIARIA DEL PRODUCTO">
                        VMD
                    </th>
                    <th style="vertical-align:middle;"
                        title="DIAS DE INVENTARIO DEL PRODUCTO">
                        DIAS
                    </th>
                    
                    <th style="display: none;">ITEM</th>
                </thead>
                @foreach ($tabla2 as $t)
                    @php
                    $dias = 0;
                    $vmd = 0;
                    $cant = 0;
                    $transito = verificarProdTransito($t->barra, $codcli, "");
                    $invent = verificarProdInventario($t->barra, $codcli);
                    if (!is_null($invent)) {
                        $vmd = $invent->vmd;
                        $cant = $invent->cantidad;
                        if ($vmd > 0)
                            $dias = $cant/$vmd;
                    }
                    $confcli = LeerCliente($codcli); 
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
                            </td>
                        @else
                            <td>
                                {{$loop->iteration}}
                            </td>
                        @endif

                        <td>
                            <div align="center">

                                <a href="{{URL::action('PedidoController@verprod',$t->barra)}}">
                        
                                    <img src="http://isaweb.isbsistemas.com/public/storage/prod/{{NombreImagen($t->barra)}}" 
                                    width="100%" 
                                    height="100%" 
                                    class="img-responsive" 
                                    alt="icompras360"
                                    style="border: 2px solid #D2D6DE;"
                                    oncontextmenu="return false" >
                        
                                </a>

                            </div>
                        </td>
                        
                        <td>
                            <span class="input-group-addon" style="margin: 0px; width: 140px;">
                                <div class="col-xs-12 input-group input-group-sm" style="margin: 0px; width: 140px;">
                                    <input 

                                    @if ($t->estado == "RECIBIDO")
                                        readonly
                                    @endif

                                    style="text-align: center; color: #000000; width: 60px;" id="idPedir-{{$t->item}}" value="{{number_format($t->cantidad, 0, '.', ',')}}" class="form-control" >
                                    <button 

                                    @if ($t->estado == "RECIBIDO") disabled @endif

                                    type="button" class="btn btn-pedido BtnModificar" id="idModificar-{{$t->item}}" data-toggle="tooltip" title="Modificar cantidad">
                                        <span 

                                            @if ($t->estado == "RECIBIDO") style="color: #000000;" @endif

                                            class="fa fa-check" id="idModificar-{{$t->item}}" aria-hidden="true" >
                                        </span>
                                        <a href="" data-target="#modal-delete-{{$t->item}}" data-toggle="modal">
                                            <button

                                                @if ($t->estado == "RECIBIDO") 
                                                    disabled style="color: #000000;"
                                                @endif

                                            class="btn btn-pedido fa fa-trash-o" style="height: 2pc;" data-toggle="tooltip" title="Eliminar producto"></button>
                                        </a>
                                    </button>
                                </div>
                            </span>
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

                        <td>{{$t->desprod}}</td>
                    
                        <td>{{$t->barra}}</td>

                        <td>{{$t->codprod}}</td>
                       
                        <!-- IVA -->
                        <td align="right">
                            {{number_format($t->iva, 2, '.', ',')}}
                        </td>

                        <!-- 8.- COSTO -->
                        <td align="right"
                            style="background-color: {{$confcli->backcolor}}; 
                            color: {{$confcli->forecolor}};">
                            {{number_format($t->precio/RetornaFactorCambiario($t->codprove, $moneda), 2, '.', ',')}}
                        </td>
                       
                        <!-- SUBTOTAL -->
                        <td align="right"
                            style="background-color: {{$confcli->backcolor}}; 
                            color: {{$confcli->forecolor}};">
                            {{number_format($t->subtotal/RetornaFactorCambiario($t->codprove, $moneda), 2, '.', ',')}}
                        </td>

                        <!-- TRANSITO -->
                        <td align="right"
                            title = "TRANSITO"
                            style="background-color: {{$confcli->backcolor}}; 
                            color: {{$confcli->forecolor}};">
                            {{number_format($transito, 0, '.', ',')}}
                        </td>
               
                        <!-- INVENTARIO -->
                        <td align="right"
                            style="background-color: {{$confcli->backcolor}}; 
                            color: {{$confcli->forecolor}};"
                            title = "INVENTARIO">
                            {{number_format($cant, 0, '.', ',')}}    
                        </td>

                        <!-- VMD -->
                        <td align="right"
                            style="background-color: {{$confcli->backcolor}}; 
                            color: {{$confcli->forecolor}};"
                            title = "VMD">
                            {{number_format($vmd, 4, '.', ',')}}
                        </td>

                        <!-- DIAS -->
                        <td align="right"
                            style="background-color: {{$confcli->backcolor}}; 
                            color: {{$confcli->forecolor}};"
                            title="DIAS DE INVENTARIO" >
                            {{number_format($dias, 0, '.', ',')}}
                        </td>
                        
                        <!-- 14.- ITEM  -->
                        <td style="display: none;">
                            {{$t->item}}
                        </td>
                    </tr>
                    @include('isacom.pedidodirecto.deleprod')
                @endforeach
                @include('isacom.pedidodirecto.deleteAll')
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
        var item = id[1];
        var pedir = $('#idPedir-'+item).val();
        var idpedido = '{{$tabla->id}}';
        //alert(item +'-'+ pedir +'-'+ idpedido);
        if (parseInt(pedir) <= 0) {
            alert("CANTIDAD A PEDIR NO PUEDE SER MENOR O IGUAL CERO");
            window.location.reload(); 
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
                    } else {
                        window.location.reload(); 
                    }   
                }
            });
        }
    });
   // refrescar();
}


function ejecutarAgregar() {
    var id = '{{$id}}';
    var codcli = '{{$codcli}}';
    var marca = '{{$marca}}';
    var cant = $('#idcant').val();
    var barra = $('#idbarra').val();
    var ctipo = id +"_"+ codcli +"_"+ cant +"_"+ barra +"_"+ marca;
    //alert(ctipo);
    if (cant == 0 || barra == '') {
        alert("FALTAN PARAMETROS PARA AGREGAR UN PRODUCTO");
    } else {
        var url = "{{url('/pedidodirecto/agregar/prod/X')}}";
        url = url.replace('X', ctipo);
        window.location.href=url;
    }
}

function cargarProd() {
    var codcli = '{{$codcli}}';
    var resp;
    var filtro = $('#idfiltro').val();
    if (filtro == "") {
        alert("FALTAN PARAMETROS PARA REALIZAR LAS BUSQUEDA");
    } else {
        var jqxhr = $.ajax({
            type:'POST',
            url: '../obtenerTablaCliMaestra',
            dataType: 'json', 
            encode  : true,
            data: { codcli:codcli, filtro:filtro },
            success:function(data) {
                $("#tbodyProducto").empty();
                $.each(data.resp, function(index, item){
                   var valor = 
                    '<tr>' +
                      "<td style='padding-top: 10px;'>" +
                      "<span onclick='tdclicktabla(event);'>" +
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

function tdclicktabla(e) {
    var id = e.target.id.split('_');
    var barra = id[1];
    $(".case").prop("checked", false);
    $("#idcheck_" + barra).prop("checked", true);
    $('#idbarra').val(barra);
}

// add multiple select/unselect functionality
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
        var resp;
        var jqxhr;
        var table = document.getElementById("myTable");
        var rows = table.getElementsByTagName('tr');
        for (var ica = 1; ica < rows.length; ica++) {
            var cols = rows[ica].getElementsByTagName('td');
            var item = cols[14].innerHTML;
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
     //  alert(e);
    }
});

// if all checkbox are selected, check the selectall checkbox and viceversa
$(".case").on("click", function(e) {
    var id = e.target.id.split('_');
    var item = id[1];
    var resp;
    var jqxhr = $.ajax({
      type:'POST',
      url:'../marcaritem',
      dataType: 'json', 
      encode  : true,
      data: {item : item, marcar : '' },
      success:function(data) {
        resp = data.msg; 
      }
    });
    jqxhr.always(function() {
        if (resp != "")
            alert(resp);
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