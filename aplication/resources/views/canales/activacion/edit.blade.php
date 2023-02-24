@extends ('layouts.menu')
@section ('contenido')

{!!Form::model($cliente,['method'=>'PATCH','route'=>['activacion.update',$codisb]])!!}
{{Form::token()}}

<input hidden type="text" name="codisb" value="{{$codisb}}">
<input hidden type="text" name="codcanal" value="{{$codcanal}}">
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
      <li class="active"><a href="#tab_1" data-toggle="tab"><B>BASICA</B></a></li>
      <li><a href="#tab_2" data-toggle="tab"><B>DETALLES</B></a></li>
      <li><a href="#tab_3" data-toggle="tab"><B>PROVEEDORES</B></a></li>
      <li class="pull-right"><a href="{{url('/seped/config')}}" class="text-muted"><i class="fa fa-gear"></i></a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
            <div class="row">

                <!-- RIF -->
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label>Rif:</label>
                        <input type="text" 
                            name="rif"
                            value="{{$rif}}"
                            readonly="" 
                            required
                            class="form-control">
                    </div>
                </div>
    
                <!-- NOMBRE -->
                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label>Nombre: (*)</label>
                        <input type="text" 
                            name="nombre" 
                            @if ($cliente) value="{{$cliente->nombre}}" @endif
                            maxlength="100" 
                            required
                            class="form-control">
                    </div>
                </div>
            
                <!-- DIRECCION -->
                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label>Dirección: (*)</label>
                        <input type="text" 
                            name="direccion" 
                            @if ($cliente) value="{{$cliente->direccion}}" @else value="N/A" @endif
                            maxlength="120" 
                            required
                            class="form-control">
                    </div>
                </div>

                <!-- TELEFONO -->   
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label>Telefono: (*)</label>
                        <input type="text" 
                            name="telefono"
                            @if ($cliente) value="{{$cliente->telefono}}" @else value="N/A" @endif
                            maxlength="100" 
                            required 
                            class="form-control">
                    </div>
                </div>

                <!-- CONTACTO -->
                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label>Contacto: (*)</label>
                        <input type="text" 
                            name="contacto" 
                            maxlength="50" 
                            @if ($cliente) value="{{$cliente->contacto}}" @else value="N/A" @endif
                            required
                            class="form-control">
                    </div>
                </div>

                <!-- ZONA -->
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label>Localidad: (*)</label>
                        <input type="text" 
                            name="localidad" 
                            @if ($cliente) value="{{$cliente->zona}}" @else value="MARACAIBO-ZULIA" @endif
                            maxlength="50"
                            required 
                            class="form-control">
                    </div>
                </div>

            </div>
        </div>
        <div class="tab-pane" id="tab_2">
            <div class="row">

                <!-- APLICACION -->
                <div class="col-lg-6 col-md-6 col-sm-3 col-xs-12">
                    <div class="form-group">
                        <label>Aplicación (ERP)</label>
                        @if ($cliente) 
                            <select name="erp" class="form-control">
                                <option value="SAINT"
                                    @if ($cliente->campo8 == "SAINT") selected @endif>
                                    SAINT
                                </option>
                                <option value="A2"
                                    @if ($cliente->campo8 == "A2") selected @endif>
                                    A2
                                </option>
                                <option value="STELLAR"
                                    @if ($cliente->campo8 == "STELLAR") selected @endif>
                                    STELLAR
                                </option>
                                <option value="PROFIT"
                                    @if ($cliente->campo8 == "PROFIT") selected @endif>
                                    PROFIT
                                </option>
                                <option value="SMARTPHARMA"
                                    @if ($cliente->campo8 == "SMARTPHARMA") selected @endif>
                                    SMARTPHARMA
                                </option>
                                <option value="PREMIUM"
                                    @if ($cliente->campo8 == "PREMIUM") selected @endif>
                                    PREMIUM
                                </option>
                                <option value="HYBRID"
                                    @if ($cliente->campo8 == "HYBRID") selected @endif>
                                    HYBRID
                                </option>
                                <option value="EFICASIS"
                                    @if ($cliente->campo8 == "EFICASIS") selected @endif>
                                    EFICASIS
                                </option>
                                <option value="OTROS"
                                    @if ($cliente->campo8 == "OTROS") selected @endif>
                                    OTROS
                                </option>
                            </select>
                        @else
                            <select name="erp" class="form-control">
                                <option value="SAINT">SAINT</option>
                                <option value="A2">A2</option>
                                <option value="STELLAR">STELLAR</option>
                                <option value="PROFIT">PROFIT</option>
                                <option value="SMARTPHARMA">SMARTPHARMA</option>
                                <option value="PREMIUM">PREMIUM</option>
                                <option value="HYBRID">HYBRID</option>
                                <option value="EFICASIS">EFICASIS</option>
                                <option value="OTROS">OTROS</option>
                            </select>
                        @endif
                    </div>
                </div>

                <!-- SECTOR -->
                <div class="col-lg-6 col-md-6 col-sm-3 col-xs-12">
                    <div class="form-group">
                        <label>Sector</label>
                        @if ($cliente) 
                            <select name="sector" class="form-control">
                                <option value="FARMACIA"
                                    @if ($cliente->sector == "FARMACIA") selected @endif>
                                    FARMACIA
                                </option>
                                <option value="DROGUERIA"
                                    @if ($cliente->sector == "DROGUERIA") selected @endif>
                                    DROGUERIA
                                </option>
                                <option value="HOSPITAL"
                                    @if ($cliente->sector == "HOSPITAL") selected @endif>
                                    HOSPITAL
                                </option>
                                <option value="CONSUMO MASIVO"
                                    @if ($cliente->sector == "CONSUMO MASIVO") selected @endif>
                                    CONSUMO MASIVO
                                </option>
                                <option value="OTROS"
                                    @if ($cliente->sector == "OTROS") selected @endif>
                                    OTROS
                                </option>
                            </select>
                        @else
                            <select name="sector" class="form-control">
                                <option value="FARMACIA">FARMACIA</option>
                                <option value="DROGUERIA">DROGUERIA</option>
                                <option value="HOSPITAL">HOSPITAL</option>
                                <option value="CONSUMO MASIVO">CONSUMO MASIVO</option>
                                <option value="OTROS">OTROS</option>
                            </select>
                        @endif
                    </div>
                </div>

                <!-- VENDEDOR -->
                <div class="col-lg-6 col-md-6 col-sm-3 col-xs-12">
                    <div class="form-group">
                        <label>Vendedor</label>
                        @if (count($vendedor)>0)  
                            <select name="codvendedor" class="form-control">
                                @foreach ($vendedor as $v)
                                    <option value="{{$v->codvendedor}}">{{$v->codvendedor}}-{{$v->nombre}}
                                    </option>
                                @endforeach
                            </select>
                        @else
                            <input type="text" 
                                name="codvendedor"
                                value="DIRECTO"
                                readonly="" 
                                class="form-control">
                        @endif
                    </div>
                </div>

                <!-- VERSION DEL ICOMPRAS -->
                <div class="col-lg-6 col-md-6 col-sm-3 col-xs-12">
                    <div class="form-group">
                        <label>Version icompras</label>
                        @if ($cliente)  
                            <select name="vericompras" class="form-control">
                                <option value="LIGHT"
                                    @if ($cliente->campo1 == "LIGHT") selected @endif>
                                    LIGHT
                                </option>
                                <option value="FULL"
                                    @if ($cliente->campo1 == "FULL") selected @endif>
                                    FULL
                                </option>
                            </select>
                        @else
                            <select name="vericompras" class="form-control">
                                <option value="LIGHT">LIGHT</option>
                                <option value="FULL">FULL</option>
                            </select>
                        @endif
                    </div>
                </div>

                <!-- LINK PAGINA -->
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label">Link pagina: (*)</label>
                        <input type="text" 
                            name="linkpagina"
                            @if ($cliente) value="{{$cliente->linkpagina}}" @else value="N/A" @endif
                            maxlength="100"  
                            required
                            class="form-control">
                    </div>
                </div>

                <!-- CORREO -->
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label>Correo: (*)</label>
                        <input type="mail" 
                            name="correo"
                            @if ($cliente) value="{{$cliente->campo3}}" @else value="pedidos@demo.com" @endif
                            maxlength="100" 
                            required
                            class="form-control">
                    </div>
                </div>

            </div>
        </div>
        <div class="tab-pane" id="tab_3">
            <div class="row">

                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-condensed table-hover">

                        <thead class="colorTitulo">
                            <th colspan="5">
                                <center>PROVEEDOR</center>
                            </th>
                            <th colspan="4">
                                <center>DATOS DEL CLIENTE</center>
                            </th>
                            <th colspan="4">
                                <center>DESCUENTOS</center>
                            </th>
                        </thead>

                        <thead class="colorTitulo">
                            <th>ACTIVAR</th>
                            <th style="width: 60px;">IMAGEN</th>
                            <th style="width:120px;">DESCRIPCION</th>
                            <th>SEDE</th>
                            <th title="VERIFICAR DATOS DE CONEXION CON EL PROVEEDOR">
                                VERIF
                            </th>
                            <th style="width:210px;" 
                                title="CODIGO DEL CLIENTE OTORGADO POR EL PROVEEDOR">
                                CODIGO (*) &nbsp;&nbsp;&nbsp;&nbsp; 
                            </th>
                            <th style="width:150px;" 
                                title="USUARIO DEL CLIENTE OTORGADO POR EL PROVEEDOR">
                                USUARIO (*)
                            </th>
                            <th style="width:180px;" 
                                title="CLAVE DEL CLIENTE OTORGADO POR EL PROVEEDOR">
                                CLAVE (*)
                            </th>
                            <th style="width:140px;" 
                                title="DIAD DE CREDITO OTORGADO POR EL PROVEEDOR">
                                DIAS
                            </th>
                            <th style="width:150px;" 
                                title="DESCUENTO COMERCIAL">
                                COMERCIAL
                            </th>
                            <th style="width:180px;" 
                                title="DESCUENTO PRONTO PAGO">
                                P.PAGO
                            </th>
                            <th style="width:100px;" 
                                title="DESCUENTO INTERNET">
                                INTERNET
                            </th>
                            <th style="width:100px;" 
                                title="DESCUENTO OTROS">
                                OTROS
                            </th>
                        </thead>
                        @foreach ($maeprove as $p)
                        @php
                        $marcar = 0;
                        $codigo = "N/A";
                        $usuario = "N/A";
                        $clave = "N/A";
                        $dcredito = "7";
                        $dc = "0.00";
                        $pp = "0.00";
                        $di = "0.00";
                        $do = "0.00";
                        $maeclieprove = DB::table('maeclieprove')
                        ->where('codcli','=',$codisb)
                        ->where('codprove','=',$p->codprove)
                        ->first();
                        if ($maeclieprove) {
                            $marcar = 1;
                            $codigo = $maeclieprove->codigo;
                            $usuario = $maeclieprove->usuario;
                            $clave = $maeclieprove->clave;
                            $dcredito = $maeclieprove->dcredito;
                            $dc = $maeclieprove->dcme;
                            $pp = $maeclieprove->ppme;
                            $di = $maeclieprove->di;
                            $do = $maeclieprove->dotro;
                        }
                        @endphp
                        <tr>
                            <td style="padding-top: 10px;">
                                <span>
                                <center>
                                    <input type="checkbox"
                                        id="idmarcar-{{$p->codprove}}"
                                        @if ($marcar > 0) checked @endif 
                                        name="activar[{{$p->codprove}}-{{$loop->iteration}}]" />
                                </center>
                                </span>
                            </td>
                            <td>
                                <div align="center">
                                    <img src="http://isaweb.isbsistemas.com/public/storage/prov/{{$p->rutalogo1}}" 
                                    class="img-responsive" 
                                    alt="icompras360" 
                                    style="width: 100px; border: 2px solid #D2D6DE;"
                                    oncontextmenu="return false">
                                </div>
                            </td>
                            <td>{{$p->descripcion}}<br>
                                {{strtoupper($p->nombre)}}<br>
                                {{$p->codprove}}
                                <input type="text" 
                                    hidden="" 
                                    name="codprove[]"
                                    value="N/A">
                            </td>
                            <td>{{$p->codsede}}<br>
                                {{strtoupper($p->localidad)}} <br>
                                {{substr($p->region,4,strlen($p->region)-4)}}
                            </td>
                            <td>
                                <button data-toggle="tooltip" 
                                    title="VERIFICAR DATOS DE CONEXION AL PROVEEDOR" 
                                    class="btn btn-pedido fa fa-check BtnVerificar" 
                                    id="idverificar-{{$p->codprove}}">
                                </button>
                            </td>

                            <td class="hover"
                                title="CODIGO DEL CLIENTE OTORGADO POR EL PROVEEDOR !!!">
                                <input type="text" 
                                    name="codigo[]"
                                    value="{{$codigo}}"
                                    id="idcodigo-{{$p->codprove}}"
                                    class="form-control">
                            </td>
                            <td class="hover"
                                title="USUARIO DE ACCESO AL PORTAL WEB DEL PROVEEDOR !!!">
                                <input type="text" 
                                    name="usuario[]"
                                    value="{{$usuario}}"
                                    id="idusuario-{{$p->codprove}}"
                                    @if ($p->tipocata=='ISB' || $p->tipocata=='ISB2' || $p->tipocata=='DRONENA' || $p->tipocata=='DROLANCA')
                                    readonly="" 
                                    @endif
                                    class="form-control">
                            </td>
                            <td class="hover"
                                title="CLAVE DE ACCESO AL PORTAL WEB DEL PROVEEDOR !!!">
                                <input type="text" 
                                    name="clave[]"
                                    value="{{$clave}}"
                                    id="idclave-{{$p->codprove}}"
                                    @if ($p->tipocata=='ISB' || $p->tipocata=='ISB2' || $p->tipocata=='DRONENA' || $p->tipocata=='DROLANCA')
                                    readonly="" 
                                    @endif
                                    class="form-control">
                            </td>
                            <td class="hover"
                                title="DIAS DE CREDITO OTORGADOS POR EL PROVEEDOR !!!">
                                <input type="text" 
                                    style="text-align: right;" 
                                    name="dcredito[]"
                                    value="{{$dcredito}}"
                                    class="form-control">
                            </td>
                            <td class="hover"
                                title="DESCUENTO COMERCIAL OTORGADOS POR EL PROVEEDOR !!!">
                                <input type="text" 
                                    style="text-align: right;" 
                                    name="dc[]"
                                    value="{{$dc}}"
                                    class="form-control">
                            </td>
                            <td class="hover"
                                title="DESCUENTO PRONTO PAGO OTORGADOS POR EL PROVEEDOR !!!">
                                <input type="text" 
                                    style="text-align: right;" 
                                    name="pp[]"
                                    value="{{$pp}}"
                                    class="form-control">
                            </td>
                            <td class="hover"
                                title="DESCUENTO INTERNET OTORGADOS POR EL PROVEEDOR !!!">
                                <input type="text" 
                                    style="text-align: right;" 
                                    name="di[]"
                                    value="{{$di}}"
                                    class="form-control">
                            </td>
                            <td class="hover"
                                title="DESCUENTO OTROS OTORGADOS POR EL PROVEEDOR !!!">
                                <input type="text" 
                                    style="text-align: right;" 
                                    name="do[]"
                                    value="{{$do}}"
                                    class="form-control">
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>

        
            </div>
        </div>
        <span>(*) Campos de carater obligatorios</span>
    </div>
</div>
<!-- BOTON GUARDAR/CANCELAR -->
<div class="form-group" style="margin-top: 20px; margin-left: 15px;">
    <button type="button" 
        class="btn-normal" 
        onclick="history.back(-1)">
        Regresar
    </button>
    <button class="btn-confirmar" 
        type="submit" 
        data-toggle="tooltip" 
        title="Guardar activación">
        Guardar
    </button>
</div>
{{Form::close()}}

@push ('scripts')
<script>
$('#subtitulo').text('{{$subtitulo}}');

$('.BtnVerificar').on('click',function(e) {
    var variable = e.target.id.split('-');
    var codprove = variable[1].trim();
    var codigo = $('#idcodigo-'+codprove).val().trim();
    var usuario = $('#idusuario-'+codprove).val().trim();
    var clave = $('#idclave-'+codprove).val().trim();
    $.ajax({
      type:'POST',
      url:'./activacion/verificar',
      dataType: 'json', 
      encode  : true,
      data: {codprove : codprove, codigo : codigo, usuario : usuario, clave : clave },
      success:function(data) {
        if (data.resp == true) {
            $("#idmarcar-"+codprove).prop("checked", true);
            alert("VERIFICACION EXITOSA !!!");
        }
        else {
            $("#idmarcar-"+codprove).prop("checked", false);
            alert("NO SE PUDO VERIFICAR, INTENTE MAS TARDE !!!");
        }
      }
    });
    e.preventDefault();
});
</script>
@endpush
@endsection