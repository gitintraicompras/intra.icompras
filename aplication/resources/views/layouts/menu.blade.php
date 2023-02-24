@php 
  $info = Session::get('info', '0');
  $tipedido = Session::get('tipedido', 'N');
  $sidebarMode = Session::get('sidebarMode', '');
  $moneda = Session::get('moneda', 'BSS');
  $factor = RetornaFactorCambiario('', $moneda);
  $codcli = sCodigoClienteActivo();
  $cliente = DB::table('maecliente')
  ->where('codcli','=', $codcli)
  ->first();
  $restandias = iValidarLicencia($codcli); 
  if ($tipedido == 'D') {
    $rutapedido = '/pedidodirecto/';
    $id = iIdUltPedAbierto($codcli, 'D');
  } else {
    $rutapedido = '/pedido/';
    $id = iIdUltPedAbierto($codcli, 'N');
  }
  $contreng = iContRengPedido($id); 
  $cfg = DB::table('maecfg')->first();
  $provs = TablaMaecliproveActiva("");
  $tabla = "inventario_".$codcli;
@endphp
 
<!DOCTYPE html> 
<html>
  <head>
    <meta charset="UTF-8">
    <title>intra.icompras360 | B2B - Intranet de gestión de compras electronicas entre empresas</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <meta name="keywords" content="Mauricio Blanco,  intra.icompras, icompras, icompras360, iventas, iventas360, ISB, SISTEMAS, ISAweb, ISACOM, DROLAGO, DROGUESUR, PULSO, METROMEDICA, DROSOLVECA, ISAsoft, ISBcliente, Compras, SAINT, PROFIT, Proveedores, Mauricio Blanco, ISAAP, ISABUSCAR, SAINT, DROEXCA, DROANDINA, DROSALUD, DRCLINICA, FARMACEUTICA24, MARAPLUS, FARMALIADAS, ISACOMMERCE, droguerias, farmacias, medicinas, medicamentos, gestor de compras, drofarzuca, drosolveca, rodalvan, dromarko, dromarca, compras, emmanuelle, drocerca, droplus, droplusve, drogueria365, biogenetoca, B2B">
    <link rel="shortcut icon" href="{{asset('images/favicon.ico')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Style personalizado -->
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />   
    <link rel="stylesheet" href="{{asset('css/bootstrap-select.min.css')}}">
    <link href="{{asset('css/style.css')}}" rel="stylesheet" type="text/css" />     

    <!-- Bootstrap 3.3.2 
    <link href="{{asset('bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />   -->  
    <!-- FontAwesome 4.3.0 -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
 
    <!-- Ionicons 2.0.0 -->
    <link href="http://code.ionicframework.com/ionicons/2.0.0/css/ionicons.min.css" rel="stylesheet" type="text/css" />    
    <!-- Theme style 
    <link href="{{asset('dist/css/AdminLTE.min.css')}}" rel="stylesheet" type="text/css" /> -->
    <link href="{{asset('dist/css/AdminLTE.css')}}" rel="stylesheet" type="text/css" /> 

    <!-- AdminLTE Skins. Choose a skin from the css/skins 
         folder instead of downloading all of them to reduce the load. -->
    <link href="{{asset('dist/css/skins/_all-skins.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- iCheck -->
    <link href="{{asset('plugins/iCheck/flat/blue.css')}}" rel="stylesheet" type="text/css" />
    <!-- Morris chart -->
    <link href="{{asset('plugins/morris/morris.css')}}" rel="stylesheet" type="text/css" />
    <!-- jvectormap 
    <link href="{{asset('plugins/jvectormap/jquery-jvectormap-1.2.2.css')}}" rel="stylesheet" type="text/css" /> -->
    <!-- Date Picker -->
    <link href="{{asset('plugins/datepicker/datepicker3.css')}}" rel="stylesheet" type="text/css" />
    <!-- Daterange picker -->
    <link href="{{asset('plugins/daterangepicker/daterangepicker-bs3.css')}}" rel="stylesheet" type="text/css" />
    <!-- Morris charts -->
    <link href="{{asset('css/plugins/morris/morris.css')}}" rel="stylesheet" type="text/css" />
  </head>
  <body class="skin-blue @if ( $sidebarMode == '2') sidebar-collapse @endif" 
    style="background-color: #ECF0F5;">
    <div class="wrapper color3" >
      
      <header class="main-header color6">
        <span style="font-size: 28px;" class="color6 navbar-fixed-top">
          <!-- Logo -->
          <a href="{{url('/')}}" class="logo">
            <b>ICOMPRAS</b><small>360</small> 
            @if (Auth::user()->versionLight == 1)
              <font face="Comic Sans MS,arial,verdana">
                <i style="font-size: 16px;">Light</i>
              </font>
            @endif
          </a>
        </span>
        <nav class="navbar navbar-static-top navbar-fixed-top color6" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" 
            class="sidebar-toggle" 
            data-widget="collapse" 
            data-toggle="offcanvas" 
            role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>

          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">


              <!-- Messages: style can be found in dropdown.less-->
              <li class="dropdown messages-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Cambio de moneda">
                  @if ($moneda == 'BSS')
                    <img src="{{asset('images/flagVzla.png')}}" style="width: 30px;" alt="VZLA"/>
                  @endif
                  @if ($moneda == 'USD')
                    <img src="{{asset('images/flagUsd.png')}}" style="width: 30px;" alt="USD"/>
                  @endif
                  @if ($moneda == 'EUR')
                    <img src="{{asset('images/flagEuro.png')}}" style="width: 30px;" alt="EUR"/>
                  @endif
                </a>
                <ul class="dropdown-menu">
                  <li class="header">Selección de Moneda</li>
                  <li>
                    <ul class="menu">
                      <li>
                        <a href="{{url('/moneda/BSS')}}">
                          <div class="pull-left">
                            <img src="{{asset('images/flagVzla.png')}}"  alt="VZLA"/>
                          </div>
                          <h4>
                            Bolivares
                          </h4>
                          <p>Predeterminada</p>
                        </a>
                      </li>
                      <li>
                        <a href="{{url('/moneda/USD')}}">
                          <div class="pull-left">
                            <img src="{{asset('images/flagUsd.png')}}"  alt="USD"/>
                          </div>
                          <h4>
                            Dolares
                          </h4>
                          <p>Otra Moneda 1</p>
                        </a>
                      </li>
                      <li>
                        <a href="{{url('/moneda/EUR')}}">
                          <div class="pull-left">
                            <img src="{{asset('images/flagEuro.png')}}"  alt="EURO"/>
                          </div>
                          <h4>
                            Euro
                          </h4>
                          <p>Otra Moneda 2</p>
                        </a>
                      </li>
                    </ul>
                  </li>
                  <li class="footer">
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  TODAY: {{number_format($cfg->factorToday,2, '.', ',')}}
                  &nbsp;&nbsp; 
                  {{date('d-m-y H:i', strtotime($cfg->fechaToday))}}
                  </li>
                  <li class="footer">
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  BCV (USD): {{number_format($cfg->factorBcvUSD,2, '.', ',')}} 
                  &nbsp;&nbsp; 
                  {{date('d-m-y H:i', strtotime($cfg->fechaBcvUSD))}}
                  </li>
                  <li class="footer">
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  BCV (EUR): {{number_format($cfg->factorBcvEUR,2, '.', ',')}} 
                  &nbsp;&nbsp; 
                  {{date('d-m-y H:i', strtotime($cfg->fechaBcvEUR))}}
                  </li>
                </ul>
              </li>

              @if (Auth::user()->tipo != "O") 
              <!-- Tasks: style can be found in dropdown.less -->
              <li class="dropdown tasks-menu hidden-xs">
                <a href="#" title="Fecha de sincronizacón del catálogo">
                  <i class="fa fa-calendar"></i>
                  <span>
                    SINCRONIZADO
                  </span>
                  <span>  <br>
                    {{ date('d-m-Y H:i', strtotime($cfg->actualizado)) }}
                  </span>
                </a>
              </li>
              @endif

              @if ($id > 0)
                @if ($menu != "Grupo")
                  @if ($restandias > 0) 
                    @if (Auth::user()->tipo == "C" || Auth::user()->tipo == "G")
                      <li class="dropdown tasks-menu">
                        <a href="{{url($rutapedido.$id.'/edit')}}"   
                          title="Ver carrito de compra">
                          <i class="fa fa-shopping-cart" 
                            style="font-size:30px;">
                          </i>
                          @if ($contreng>0)
                            <span id="contreng" class="label label-danger" style="font-size:14px;">
                              {{ $contreng }}
                            </span>
                          @endif
                        </a>
                      </li>
                      @if ($contreng>0)
                        <li class="dropdown tasks-menu">
                          <a href="{{url($rutapedido.$id.'/edit')}}" title="Monto total del pedido, click para ver carrito de compra">
                              <span id="totpedido" style="font-size: 18px;"> 
                                {{$moneda}} {{number_format(dTotalPedido($id), 2, '.', ',')}} 
                              </span> 
                          </a>
                        </li>
                      @endif
                    @endif
                  @endif
                @endif
              @endif
              <li class="dropdown tasks-menu" >
              </li>


              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="{{url('/')}}" class="dropdown-toggle" data-toggle="dropdown">
                  <img src="{{asset('images/userAdmin.png')}}" class="user-image" alt="User Image"/>
                  <span class="hidden-xs">
                    {{ Auth::user()->name }} {{ (Auth::user()->userAdmin == 1 ? "(A)" : "") }}
                  </span> 
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <img src="{{asset('images/userAdmin.png')}}" class="img-circle" alt="User Image" />
                    <p>
                      Código: {{Auth::user()->codcli}} <br>    
                      <span class="hidden-xs">{{ Auth::user()->email }}</span>
                    </p>
                  </li>


                  <!-- Menu Body -->
                  @if (Auth::user()->tipo != "N") 
                  <li class="user-body" style="background-color: #ffffff;">
                    <div class="col-xs-4 text-center">
                      <a href="" data-target="#modal-registrar" data-toggle="modal" title="Registro de licencia">
                      Registrar
                      </a>
                    </div>
                    <div class="col-xs-4 text-center" >
                      <a href="{{url('/informes/auditoria/verauditoria')}}" 
                        title="Ver auditoria">
                        Auditoria
                      </a>
                    </div>
                    @if (Auth::user()->tipo != "N") 
                      @if ($cliente->SinInvConFrec <= 0)
                      <div class="col-xs-4 text-center">
                        <a href="{{url('/sincinventario')}}" title="Forzar sincronización de inventario del cliente">
                        Inventario
                        </a>
                      </div>
                      @endif
                    @endif

                    @if (Auth::user()->email == "prueba@isacom.net")
                      <div class="col-xs-4 text-center">
                        <a href="{{url('/prueba')}}" title="prueba">
                        Prueba
                        </a>
                      </div>
                    @endif
                    
                  </li>
                  @endif

                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-left">
                      <a href="" 
                        data-target="#modal-acerca" 
                        data-toggle="modal" 
                        class="btn btn-default btn-flat" 
                        title="Acerca del icompras360">
                        Acerca
                      </a>
                    </div>
                    <div class="pull-right">
                      <a href="{{ route('logout') }}" 
                        onclick="event.preventDefault(); 
                        document.getElementById('logout-form').submit();" 
                        class="btn btn-default btn-flat" 
                        title="Salir del icompras360">
                        Cerrar sesión
                      </a>
                      <form id="logout-form" 
                        action="{{ route('logout') }}" 
                        method="POST" 
                        style="display: none;">
                        {{ csrf_field() }}
                      </form>
                    </div>
                  </li>
                </ul>
              </li>

            </ul>
          </div>
        </nav>
      </header>

      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar color3">
        <!-- sidebar: style can be found in sidebar.less -->
        <br>
        <section class="sidebar color3" >
          <!-- Sidebar user panel -->
          <div class="user-panel">
            <center>
              <img style="width: 100px; 
                height: 100px;
                background-size: cover; 
                border-radius: 50%;
                margin: 0 auto;"
                src="{{asset('images/favicon2.png')}}" 
                alt="favicon" />
            </center> 
          </div>
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">

            @if (Auth::user()->tipo == 'C')
              @if ($tipedido == 'D')
                <li style="color: #ffffff; margin-top: 4px; margin-bottom: 8px;">
                  <center>
                    CLIENTE (PEDIDO DIRECTO)
                  </center>
                </li>
             
                <!-- HOME -->
                <li @if ($menu=='Inicio') class='active' @endif >
                    <a href="{{url('/')}}">
                      <i class="fa fa-home"></i> <span>Inicio</span>
                    </a>
                </li>
    
                <!-- EDIDOS -->
                <li @if ($menu=='Pedidos') class='active' @endif >
                    <a @if ($restandias <= 0) class="disabled" @endif
                      href="{{url('/pedidodirecto')}}">
                      <i class="fa fa-shopping-cart"></i> 
                      <span>Pedidos</span>
                    </a>
                </li>
                <!-- INVENTARIO -->
                @if (VerificaTabla($tabla)) 
                <li @if ($menu=='Inventario') class='active' @endif >
                    <a @if ($restandias <= 0) class="disabled" @endif 
                      href="{{url('/invdirectoclie')}}">
                      <i class="fa fa-table"></i> 
                      <span>Inventario</span>
                    </a>
                </li>
                @endif
              @else  
                <li style="color: #ffffff; margin-top: 4px; margin-bottom: 8px;">
                  <center>
                    CLIENTE
                  </center>
                </li>
             
                <!-- HOME -->
                <li @if ($menu=='Inicio') class='active' @endif >
                    <a href="{{url('/')}}">
                      <i class="fa fa-home"></i> <span>Inicio</span>
                    </a>
                </li>
    
                <!-- CATALOGO -->
                @if ($provs->count() > 0)
                <li @if ($menu=='Catalogo') class='active' @endif >
                    <a @if ($restandias <= 0) class="disabled" @endif
                      href="{{URL::action('PedidoController@catalogo','TOP')}}"  >
                      <i class="fa fa-cubes"></i> 
                      <span>Catálogo</span>
                    </a>
                </li>
                @endif

                <!-- EDIDOS -->
                <li @if ($menu=='Pedidos') class='active' @endif >
                    <a @if ($restandias <= 0) class="disabled" @endif
                      href="{{url('/pedido')}}">
                      <i class="fa fa-shopping-cart"></i> 
                      <span>Pedidos</span>
                    </a>
                </li>

                <!-- TRANSITO -->
                <li @if ($menu=='Transito') class='active' @endif >
                    <a @if ($restandias <= 0) class="disabled" @endif 
                      href="{{url('/transito')}}">
                      <i class="fa fa-truck"></i> 
                      <span>Transito</span>
                    </a>
                </li>

                <!-- PROVEEDORES -->
                <li @if ($menu=='Proveedores') class='active' @endif >
                    <a @if ($restandias <= 0) class="disabled" @endif 
                      href="{{url('/proveedor')}}">
                      <i class="fa fa-user"></i> 
                      <span>Proveedores</span>
                    </a>
                </li>

                <!-- CARACTERISTICAS  -->
                <li @if ($menu=='Caracteristicas') class='active' @endif >
                    <a @if ($restandias <= 0) class="disabled" @endif 
                      href="{{url('/prodcaract')}}">
                      <i class="fa fa-comments"></i> 
                      <span>Caracteristicas</span>
                    </a>
                </li>

                @if (Auth::user()->botonMolecula == 1)
                  @if (Auth::user()->userAdmin == 1)
                  <!-- MENU MOLECULAS -->
                  <li @if ($menu=='Moleculas') class='active' @endif >
                      <a @if ($restandias <= 0) class="disabled" @endif 
                        href="{{url('/menumolecula')}}">
                        <i class="fa fa-share-alt"></i> 
                        <span>Moleculas</span>
                      </a>
                  </li>
                  @endif
                @endif

                @if (Auth::user()->versionLight == 0)
                  <!-- FACTURAS -->
                  <li @if ($menu=='Facturas') class='active' @endif>
                    <a @if ($restandias <= 0) class="disabled" @endif  
                      href="{{URL::action('FacturaController@index')}}">
                      <i class="fa fa-building-o"></i> 
                      <span>Facturas</span>
                    </a>
                  </li>
              
                  <!-- INVENTARIO -->
                  @if (VerificaTabla($tabla)) 
                  <li @if ($menu=='Inventario') class='active' @endif >
                      <a @if ($restandias <= 0) class="disabled" @endif 
                        href="{{url('/inventario')}}">
                        <i class="fa fa-table"></i> 
                        <span>Inventario</span>
                      </a>
                  </li>
                  @endif
    
                  <!-- DESCARGAR -->
                  <li @if ($menu=='Descargar') class='active' @endif>
                    <a href="{{url('/descargar')}}">
                      <i class="fa fa-download"></i> <span>Descargar</span>
                    </a>
                  </li>
                @endif

                <!-- INFORMES -->
                <li @if ($menu=='Informes') class='active' @endif >
                    <a @if ($restandias <= 0) class="disabled" @endif 
                      href="{{url('/informes')}}">
                      <i class="fa fa-pie-chart"></i> 
                      <span>Informes</span>
                    </a>
                </li>

                <!-- CONFIGURACION -->
                @if (Auth::user()->userAdmin == '1') 
                <li @if ($menu=='Configuracion') class='active' @endif>
                  <a href="{{URL::action('ConfigController@edit',$codcli)}}">
                    <i class="fa fa-gear"></i> <span>Configuración</span>
                  </a>
                </li>
                @endif
              @endif
            @elseif (Auth::user()->tipo == 'G')
              @if ($tipedido == 'D')
                <li style="color: #ffffff; margin-top: 4px; margin-bottom: 8px;">
                  <center>
                    GRUPO (PEDIDO DIRECTO)
                  </center>
                </li>
      
                <!-- HOME -->
                <li @if ($menu=='Inicio') class='active' @endif >
                    <a href="{{url('/')}}">
                      <i class="fa fa-home"></i> <span>Inicio</span>
                    </a>
                </li>

                <li @if ($menu=='Pedidos') class='active' @endif >
                    <a @if ($restandias <= 0) class="disabled" @endif
                      href="{{url('/pedidodirecto')}}">
                      <i class="fa fa-shopping-cart"></i> 
                      <span>Pedidos</span>
                    </a>
                </li>

                <!-- INVENTARIO -->
                @if (VerificaTabla($tabla)) 
                <li @if ($menu=='Inventario') class='active' @endif >
                    <a @if ($restandias <= 0) class="disabled" @endif 
                      href="{{url('/invdirectoclie')}}">
                      <i class="fa fa-table"></i> 
                      <span>Inventario</span>
                    </a>
                </li>
                @endif

                <!-- GRUPO -->
                <li @if ($menu=='Grupo') class='active' @endif >
                    <a @if ($restandias <= 0) class="disabled" @endif
                      href="{{url('/grupo')}}">
                      <i class="fa fa-users"></i> 
                      <span>Grupo</span>
                    </a>
                </li>
              @else
                <li style="color: #ffffff; margin-top: 4px; margin-bottom: 8px;">
                  <center>
                    GRUPO
                  </center>
                </li>
      
                <!-- HOME -->
                <li @if ($menu=='Inicio') class='active' @endif >
                    <a href="{{url('/')}}">
                      <i class="fa fa-home"></i> <span>Inicio</span>
                    </a>
                </li>

                <!-- CATALOGO -->
                @if ($provs->count() > 0)
                <li @if ($menu=='Catalogo') class='active' @endif >
                    <a @if ($restandias <= 0) class="disabled" @endif
                    href="{{URL::action('PedidoController@catalogo','TOP')}}">
                      <i class="fa fa-cubes"></i> 
                      <span>Catálogo</span>
                    </a>
                </li>
                @endif

                <li @if ($menu=='Pedidos') class='active' @endif >
                    <a @if ($restandias <= 0) class="disabled" @endif
                      href="{{url('/pedido')}}">
                      <i class="fa fa-shopping-cart"></i> 
                      <span>Pedidos</span>
                    </a>
                </li>

                <!-- TRANSITO -->
                <li @if ($menu=='Transito') class='active' @endif >
                    <a @if ($restandias <= 0) class="disabled" @endif
                      href="{{url('/transito')}}">
                      <i class="fa fa-truck"></i> 
                      <span>Transito</span>
                    </a>
                </li>

                <!-- PROVEEDORES -->
                <li @if ($menu=='Proveedores') class='active' @endif >
                    <a @if ($restandias <= 0) class="disabled" @endif
                      href="{{url('/proveedor')}}">
                      <i class="fa fa-user"></i> 
                      <span>Proveedores</span>
                    </a>
                </li>

                <!-- CARACTERISTICAS  -->
                <li @if ($menu=='Caracteristicas') class='active' @endif >
                    <a @if ($restandias <= 0) class="disabled" @endif 
                      href="{{url('/prodcaract')}}">
                      <i class="fa fa-comments"></i>
                      <span>Caracteristicas</span>
                    </a>
                </li>

                @if (Auth::user()->botonMolecula == 1)
                  @if (Auth::user()->userAdmin == 1)
                  <!-- MENU MOLECULAS -->
                  <li @if ($menu=='Moleculas') class='active' @endif >
                      <a @if ($restandias <= 0) class="disabled" @endif 
                        href="{{url('/menumolecula')}}">
                        <i class="fa fa-share-alt"></i> 
                        <span>Moleculas</span>
                      </a>
                  </li>
                  @endif
                @endif
               
                <!-- GRUPO -->
                <li @if ($menu=='Grupo') class='active' @endif >
                    <a @if ($restandias <= 0) class="disabled" @endif
                      href="{{url('/grupo')}}">
                      <i class="fa fa-users"></i> 
                      <span>Grupo</span>
                    </a>
                </li>

                @if (Auth::user()->versionLight == 0)
                  <!-- FACTURAS -->
                  <li @if ($menu=='Facturas') class='active' @endif>
                    <a @if ($restandias <= 0) class="disabled" @endif  
                      href="{{URL::action('FacturaController@index')}}">
                      <i class="fa fa-building-o"></i> 
                      <span>Facturas</span>
                    </a>
                  </li>
              
                  <!-- INVENTARIO -->
                  @if (VerificaTabla($tabla)) 
                  <li @if ($menu=='Inventario') class='active' @endif >
                      <a @if ($restandias <= 0) class="disabled" @endif
                        href="{{url('/inventario')}}">
                        <i class="fa fa-table"></i> 
                        <span>Inventario</span>
                      </a>
                  </li>
                  @endif

                  <!-- DESCARGAR -->
                  <li @if ($menu=='Descargar') class='active' @endif>
                    <a href="{{url('/descargar')}}">
                      <i class="fa fa-download"></i> <span>Descargar</span>
                    </a>
                  </li>
                @endif

                <!-- INFORMES -->
                <li @if ($menu=='Informes') class='active' @endif >
                  <a @if ($restandias <= 0) class="disabled" @endif 
                    href="{{url('/informes')}}">
                    <i class="fa fa-pie-chart"></i> 
                    <span>Informes</span>
                  </a>
                </li>


                <!-- CONFIGURACION -->
                @if (Auth::user()->userAdmin == '1') 
                <li @if ($menu=='Configuracion') class='active' @endif>
                  <a href="{{URL::action('ConfigController@edit',$codcli)}}">
                    <i class="fa fa-gear"></i> <span>Configuración</span>
                  </a>
                </li>
                @endif
              @endif
            @elseif (Auth::user()->tipo == 'P')

              <li style="color: #ffffff; margin-top: 4px; margin-bottom: 8px;">
                <center>PROVEEDOR</center>
              </li>
           
              <!-- HOME -->
              <li @if ($menu=='Inicio') class='active' @endif >
                  <a href="{{url('/')}}">
                    <i class="fa fa-home"></i> <span>Inicio</span>
                  </a>
              </li>

              <!-- EDIDOS -->
              <li @if ($menu=='Pedidos') class='active' @endif >
                  <a href="{{url('/provpedido')}}">
                    <i class="fa fa-shopping-cart"></i> <span>Pedidos</span>
                  </a>
              </li>

              <!-- CATALOGO -->
              <li @if ($menu=='Catalogo') class='active' @endif >
                  <a href="{{url('/provcatalogo')}}">
                    <i class="fa fa-cubes"></i> <span>Catálogo</span>
                  </a>
              </li>

              <!-- CARACTERISTICAS  -->
              <li @if ($menu=='Caracteristicas') class='active' @endif >
                  <a @if ($restandias <= 0) class="disabled" @endif 
                    href="{{url('/prodcaract')}}">
                    <i class="fa fa-comments"></i>
                    <span>Caracteristicas</span>
                  </a>
              </li>
           
              <!-- CONFIGURACION -->
              @if (Auth::user()->userAdmin == '1') 
              <li @if ($menu=='Configuracion') class='active' @endif>
                @php $codprove = Auth::user()->ultcodcli; @endphp
                <a @if ($restandias <= 0) class="disabled" @endif
                  href="{{URL::action('ProvConfigController@edit',$codprove)}}">
                  <i class="fa fa-gear"></i> 
                  <span>Configuración</span>
                </a>
              </li>
              @endif
            @elseif (Auth::user()->tipo == 'O')

              <li style="color: #ffffff; margin-top: 4px; margin-bottom: 8px;">
                <center>GESTOR DE OFERTAS</center>
              </li>
           
              <!-- HOME -->
              <li @if ($menu=='Inicio') class='active' @endif >
                  <a href="{{url('/')}}">
                    <i class="fa fa-home"></i> <span>Inicio</span>
                  </a>
              </li>

              <!-- REGISTRO -->
              <li @if ($menu=='Registro') class='active' @endif >
                  <a @if ($restandias <= 0) class="disabled" @endif
                    href="{{URL::action('RegistroController@index')}}">
                    <i class="fa fa-bars"></i> 
                    <span>Registro</span>
                  </a>
              </li> 

              <!-- PROVEEDORES -->
              <li @if ($menu=='Proveedores') class='active' @endif >
                  <a @if ($restandias <= 0) class="disabled" @endif 
                    href="{{url('/proveedor')}}">
                    <i class="fa fa-user"></i> 
                    <span>Proveedores</span>
                  </a>
              </li>

              <!-- CARACTERISTICAS  -->
              <li @if ($menu=='Caracteristicas') class='active' @endif >
                  <a @if ($restandias <= 0) class="disabled" @endif 
                    href="{{url('/prodcaract')}}">
                    <i class="fa fa-comments"></i> 
                    <span>Caracteristicas</span>
                  </a>
              </li>

              <!-- EXCLUSIVOS  -->
              <li @if ($menu=='Exclusivos') class='active' @endif >
                  <a @if ($restandias <= 0) class="disabled" @endif 
                    href="{{url('/prodexclu')}}">
                    <i class="fa fa-star"></i> 
                    <span>Exclusivos</span>
                  </a>
              </li>

              <!-- OFERTAS -->
              <li @if ($menu=='Ofertas') class='active' @endif >
                  <a @if ($restandias <= 0) class="disabled" @endif 
                    href="{{URL::action('OfertasController@index')}}">
                    <i class="fa fa-tags"></i> 
                    <span>Ofertas</span>
                  </a>
              </li>

              <!-- CONFIGURACION -->
              @if (Auth::user()->userAdmin == '1') 
              <li @if ($menu=='Configuracion') class='active' @endif>
                <a @if ($restandias <= 0) class="disabled" @endif
                  href="{{URL::action('ConfigController@edit',$codcli)}}">
                  <i class="fa fa-gear"></i> 
                  <span>Configuración</span>
                </a>
              </li>
              @endif
            @elseif (Auth::user()->tipo == 'N')

              <li style="color: #ffffff; margin-top: 4px; margin-bottom: 8px;">
                <center>CANALES</center>
              </li>
           
              <!-- HOME -->
              <li @if ($menu=='Inicio') class='active' @endif >
                  <a href="{{url('/')}}">
                    <i class="fa fa-home"></i> <span>Inicio</span>
                  </a>
              </li>

              <!-- ACTIVACION -->
              <li @if ($menu=='Activacion') class='active' @endif >
                  <a href="{{url('/canales/activacion/create')}}">
                    <i class="fa fa-bars"></i> 
                    <span>Activación</span>
                  </a>
              </li> 

              <!-- LICENCIAS -->
              <li @if ($menu=='Licencias') class='active' @endif >
                  <a href="{{URL::action('Canales\LicenciaController@index')}}">
                    <i class="fa fa-newspaper-o"></i> 
                    <span>Licencias</span>
                  </a>
              </li> 

              @if (Auth::user()->userAdmin == '1') 
              <!-- VENDEDORES -->
              <li @if ($menu=='Vendedores') class='active' @endif >
                  <a href="{{URL::action('Canales\VendedorController@index')}}">
                    <i class="fa fa-user-plus"></i> 
                    <span>Vendedores</span>
                  </a>
              </li> 
              <!-- CONFIGURACION -->
              <li>
                <a href="{{URL::action('Canales\ConfigController@edit',Auth::user()->codcli)}}">
                  <i class="fa fa-gear"></i> 
                  <span>Configuración</span>
                </a>
              </li>
              @endif
            @endif

            @if (Auth::user()->userPedDirecto == '1')
              @if ($tipedido == 'D')
                <li>
                  <a @if ($restandias <= 0) class="disabled" @endif
                    href="{{url('/tipedido/N')}}">
                    <i class="fa fa-reply-all"></i> 
                    <span>Ir a Pedidos Normal</span>
                  </a>
                </li>
              @else
                <li>
                  <a @if ($restandias <= 0) class="disabled" @endif
                    href="{{url('/tipedido/D')}}">
                    <i class="fa fa-reply-all"></i> 
                    <span>Ir a Pedidos Directo</span>
                  </a>
                </li>
              @endif
            @endif

            <li>
              <a href="#" data-target="#modal-soporte" data-toggle="modal">
                <span>Soporte técnico</span>
                <small class="label pull-right bg-yellow"> 
                  <i class="fa fa-phone-square"></i>
                </small>
              </a>
            </li>

            @if (Auth::user()->tipo == 'O')
              <li style="margin-top: 20px;">
                  <span class="header hidden-xs" style="color: #ffffff; font-size: 18px; margin-left: 20px;">
                    <i>Sistema de Gestión </i>
                  </span><br>
                  <span class="header hidden-xs" style="color: #ffffff; font-size: 18px; margin-left: 20px;">
                    <i>de Ofertas</i>
                  </span>
                  <br>
              </li>
            @else
              <li style="margin-top: 20px;">
                  <span class="header hidden-xs" style="color: #ffffff; font-size: 18px; margin-left: 20px;">
                    <i>Sistema de Gestión </i>
                  </span><br>
                  <span class="header hidden-xs" style="color: #ffffff; font-size: 18px; margin-left: 20px;">
                    <i>de Compras</i>
                  </span>
                  <br>
              </li>
            @endif

          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>

      <!-- Right side column. Contains the navbar and content of the page -->
      <div class="content-wrapper" >
        <!-- Content Header (Page header) -->
        <br><br>
        <section class="content-header" >
          <h1 id="titulo">
            {{ NombreCliente() }}
          </h1>

          @if ($menu=="Inicio")
            @if (Auth::user()->tipo == 'C' || Auth::user()->tipo == 'G')
                @if ($cliente->MostrarGraAho == '1')
                  @php
                  $histAhorro = $cliente->histAhorro;
                  $campo = explode("|", $histAhorro);
                  array_pop($campo);
                  $contador = count($campo);
                  @endphp
                  @if ($contador > 0)
                  <ol class="breadcrumb hidden-xs" style=" padding: 0px;">
                    <div class="table-responsive" style="border-radius: 10px 10px 10px 10px;">
                      <a href="{{url('/informes/ahorro/verahorro')}}" style="color: black;">
                        <table class="table 
                          table-striped 
                          table-bordered 
                          table-condensed 
                          table-hover" >
                            <thead style="background-color: #0061A8; color: #ffffff;">
                                <th>PERIODO</th>
                                @for ($x=0; $x < $contador; $x++)
                                  @php
                                  $mes = $campo[$x];
                                  $campox = explode(";", $mes);
                                  @endphp
                                  <th>{{$campox[0]}}</th>
                                @endfor
                            </thead>
                            <tr>
                                <td>AHORRO</td>
                                @for ($x=0; $x < $contador; $x++)
                                  @php
                                  $mes = $campo[$x];
                                  $campox = explode(";", $mes);
                                  @endphp
                                  <td align="right">
                                    {{number_format($campox[1]/$factor, 2, '.', ',')}}
                                  </td>
                                @endfor
                            </tr>
                        </table>
                      </a>
                    </div>
                  </ol>
                  @endif
                @endif
            @endif
          @endif

        </section> 

        <!-- Main content -->
        <section class="content">
          @if (Session::has('message'))
            <div class="alert alert-info alert-dismissable" role="alert">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <strong> {!! Session::get("message") !!} </strong>
            </div>
          @endif

          @if (Session::has('warning'))
            <div class="alert alert-warning alert-dismissable" role="alert">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <strong> {!! Session::get("warning") !!} </strong>
            </div>
          @endif
          
          @if (Session::has('error'))
            <div class="alert alert-error alert-dismissable" role="alert">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <strong> {!! Session::get("error") !!} </strong>
            </div>
          @endif
          
          <div class="row">
            <div class="col-md-12">
              <div class="box" >

                  <div class="box-header with-border" style="background-color: #F7F7F7;">
                    <center><h3 id="subtitulo" class="box-title"></h3></center>
                    <div class="box-tools pull-right"></div>
                  </div>
                  <!-- /.box-header -->
                  <div class="box-body" >
                      <div class="row">
                        <div class="col-md-12">
                           <!--Contenido-->
                           @yield('contenido')
                           <!--Fin Contenido-->
                       </div>
                      </div>
                  </div>
              </div>
            </div>
          </div>
        </section>
      </div>

      @if ($menu=='Inicio')
      <footer class="main-footer navbar navbar-fixed-bottom">
        <div class="pull-right hidden-xs">
          <b>Version</b> 2.0.0
        </div>
        <strong>Copyright © 2013-<script>document.write(new Date().getFullYear());</script>
            <a href="http://www.isbsistemas.com">
               <img src="{{asset('images/isb.ico')}}" alt="ISB" style="width:16px; height: 16px;">
               ISB SISTEMAS, C.A. 
            </a>
        </strong>
      </footer>
      @endif

    </div>

    <script src="{{asset('js/jquery-3.5.1.min.js')}}"></script>
    <script type="text/javascript">
      $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
    </script>
    <script type="text/javascript">
      function number_format(number, decimals, dec_point, thousands_sep) { 
        number = (number + '').replace(',', '').replace(' ', ''); 
        var n = !isFinite(+number) ? 0 : +number, 
          prec = !isFinite(+decimals) ? 0 : Math.abs(decimals), 
          sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep, 
          dec = (typeof dec_point === 'undefined') ? '.' : dec_point, 
          s = '', 
          toFixedFix = function (n, prec) { 
           var k = Math.pow(10, prec); 
           return '' + Math.round(n * k)/k; 
          }; 
        // Fix for IE parseFloat(0.55).toFixed(0) = 0; 
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.'); 
        if (s[0].length > 3) { 
         s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep); 
        } 
        if ((s[1] || '').length < prec) { 
         s[1] = s[1] || ''; 
         s[1] += new Array(prec - s[1].length + 1).join('0'); 
        } 
        return s.join(dec); 
      }
    </script>     
    <!-- Bootstrap 3.3.5 -->
    <script src="{{asset('js/bootstrap.min.js')}}"></script>
    <script src="{{asset('js/bootstrap-select.min.js')}}"></script>
    <!--  <script>
      $.widget.bridge('uibutton', $.ui.button);
    </script> -->
    <!-- Sparkline -->
    <script src="{{asset('plugins/sparkline/jquery.sparkline.min.js')}}" type="text/javascript"></script>
    <!-- jvectormap -->
    <script src="{{asset('plugins/jvectormap/jquery-jvectormap-1.2.2.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('plugins/jvectormap/jquery-jvectormap-world-mill-en.js')}}" type="text/javascript"></script>
    <!-- jQuery Knob Chart -->
    <script src="{{asset('plugins/knob/jquery.knob.js')}}" type="text/javascript"></script>
    <!-- daterangepicker -->
    <script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}" type="text/javascript"></script>
    <!-- bootstrap color picker -->
    <script src="{{asset('js/jscolor.min.js')}}"></script>
    <!-- datepicker -->
    <script src="{{asset('plugins/datepicker/bootstrap-datepicker.js')}}" type="text/javascript"></script>
    <!-- Bootstrap WYSIHTML5 -->
    <script src="{{asset('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js')}}" type="text/javascript"></script>
    <!-- iCheck -->
    <script src="{{asset('plugins/iCheck/icheck.min.js')}}" type="text/javascript"></script>
    <!-- Slimscroll 
    <script src="{{asset('plugins/slimScroll/jquery.slimscroll.min.js')}}" type="text/javascript"></script>-->
    <!-- FastClick -->
    <script src="{{asset('plugins/fastclick/fastclick.min.js')}}"></script>
    <!-- AdminLTE App -->
    <script src="{{asset('dist/js/app.min.js')}}" type="text/javascript"></script>
    <!-- Morris.js charts -->
    <script type="text/javascript" src="{{asset('js/raphael-min.js')}}"></script>
    <script type="text/javascript" src="{{asset('css/plugins/morris/morris.min.js')}}"></script>

    <script type="text/javascript" src="{{asset('js/raphael-min.js')}}"></script>
    <script type="text/javascript" src="{{asset('css/plugins/morris/morris.min.js')}}"></script>


 

    @stack('scripts') 
  </body>
</html>


<!-- REGISTRAR LICENCIA -->
@if (Auth::user()->tipo != 'N')
  {{Form::Open(array('action'=>array('ConfigController@registrar')))}}
  {{ Form::token() }}
  <div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modal-registrar">
    <div class="modal-dialog">
      <div class="modal-content">
    
        <div class="modal-header colorTitulo" >
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span style="color: #ffffff;" aria-hidden="true">X</span>
          </button>
          <h4 class="modal-title">REGISTRO DE LICENCIA</h4>
        </div>
     
       
        @php
        $cliente = DB::table('maecliente')
        ->where('codcli','=',$codcli)
        ->first();
        $KeyIsacom = $cliente->KeyIsacom;
        @endphp

        <div class="modal-body">
          <div class="col-md-12">
            <div class="box box-warning">
              <div class="box-body">
                <form role="form">

                  <div class="row">
                    <div class="col-md-8">

                      <div style="margin-bottom: 5px;">
                      <label>Key: </label>
                        @if ($KeyIsacom == "DEMO" || $KeyIsacom == "" )
                          <input name="keys" type="text" class="form-control" value="{{$KeyIsacom}}" />
                        @else
                          <input readonly="" name="keys" type="text" class="form-control" value="{{$KeyIsacom}}" />
                        @endif
                      </div>

                    </div>
                    <div class="col-md-4" style="margin-top: 7px;">
                      <center>
                      <img src="{{asset('images/Keys.jpg')}}" alt="icompras360" style="width:100px;" />
                      </center>
                    </div>
                  </div>

              
                </form>
              </div><!-- /.box-body -->
            </div>
          </div>
        </div>
       
        <div class="modal-footer">
          <div class="col-md-12">
            <button type="button" class="btn-normal" data-dismiss="modal">Regresar</button>
            @if ($KeyIsacom == "DEMO" || $KeyIsacom == "" )
              <button class="btn-confirmar" type="submit">Aceptar</button>
            @endif
          </div>
        </div>
   
      </div>
    </div>
  </div>
  {{Form::close()}}
@endif

<!--ACERCA PANTALLA MODAL -->
<div class="modal fade modal-slide-in-right" 
  aria-hidden="true" 
  role="dialog" 
  tabindex="-1" 
  id="modal-acerca">
  <div class="modal-dialog">
    <div class="modal-content">
  
      <div class="modal-header colorTitulo" >
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">x</span>
        </button>
        <h4 class="modal-title">ACERCA</h4>
      </div>
   
      <div class="modal-body">

        <div class="user-panel">
            <div class="pull-left image">
              <img style="width: 90px; height: 90px;" src="{{asset('images/favicon.png')}}" alt="icompras360" />
            </div>
            <div class="pull-left info">
              <br>
              <span style="font-size: 24px; color: #000000;">ICOMPRAS
              @if (Auth::user()->versionLight == 1)
                <font face="Comic Sans MS,arial,verdana">
                  <i style="font-size: 16px;">Light</i>
                </font>
              @endif
              (GESTOR DE COMPRA)</span> <br>
              @if (Auth::user()->tipo == "C" || Auth::user()->tipo == "G")
                <span style="font-size: 12px; color: #000000;">MODULO CLIENTES</span><br>
              @endif
              @if (Auth::user()->tipo == "O" )
                <span style="font-size: 12px; color: #000000;">MODULO GESTOR DE OFERTAS</span><br>
              @endif
              @if (Auth::user()->tipo == "P" )
                <span style="font-size: 12px; color: #000000;">MODULO PROVEEDORES</span><br>
              @endif
              @if (Auth::user()->tipo == "N" )
                <span style="font-size: 12px; color: #000000;">MODULO DE CANALES</span><br>
              @endif
              <span style="font-size: 10px; color: #000000;">Version {{$cfg->version}}</span><br>
            </div>
        </div>
        
        <br>
        ISB SISTEMAS, C.A.  Rif: J-40402421-2 <br>
        Es una empresa de alta tecnologia, dedicada al desarrollo de aplicaciones a la 
        medidas de las necesidades de nuestros clientes.<br>
        Para obtener más información acerca de ICOMPRAS y sus diferentes
        productos. <br> 
        Visitenos:
        <a href="http://www.isbsistemas.com">
          <img src="{{asset('images/isb.ico')}}" alt="ISB" style="width:16px; height: 16px;">
          www.isbsistemas.com
        </a> <br>
        Director Ejecutivo  : Ing. Gustavo Ferrer | +58 414-3727251 | 412-1637530 <br>
        Director Tecnológico: Ing. Mauricio Blanco | +58 414-6454965 | 412-1677774<br>
        Copyright ©2013-<script>document.write(new Date().getFullYear());</script>  |  todos los derechos reservados <br>
        Maracaibo-Venezuela <br>
        <span style="font-size: 18px;">Se autoriza el uso de este producto a:</span> <br>
        <span style="font-size: 22px;">{{ NombreCliente() }}</span><br>
      </div>
     
      <div class="modal-footer">
        <button type="button" class="btn-normal" data-dismiss="modal">Regresar</button>
      </div>
    </div>
  </div>
</div>

<!--SOPORTE TECNICO PANTALLA MODAL -->
{{Form::Open(array('action'=>array('ConfigController@correo')))}}
{{ Form::token() }}
<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modal-soporte">
  <div class="modal-dialog">
    <div class="modal-content">
  
      <div class="modal-header colorTitulo" >
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span style="color: #ffffff;" aria-hidden="true">X</span>
        </button>
        <h4 class="modal-title">SOPORTE TECNICO</h4>
      </div>
   
      <div class="modal-body">
        <div class="col-md-12">
          <div class="box box-warning">
            <div class="box-body">
              <form role="form">

                <div class="row">
                  <div class="col-md-8">
                    <div style="margin-bottom: 5px;">
                      <input name="nomsoporte" type="text" class="form-control" value="{{$cfg->nomsoporte}}" disabled/>
                    </div>

                    <div style="margin-bottom: 5px;">
                      <input name="telsoporte" type="text" class="form-control" value="{{$cfg->telsoporte}}" disabled/>
                    </div>

                    <div style="margin-bottom: 5px;">
                      <input name="destino" type="text" class="form-control" value="{{$cfg->correosoporte}}" disabled/>
                    </div>

                  </div>
                  <div class="col-md-4" style="margin-top: 7px;">
                    <center>
                    <img src="{{asset('images/userAdmin.png')}}" alt="icompras360" style="width:100px;" />
                    </center>
                  </div>
                </div>

                <div class="form-group">
                  <label>Remitente (Correo)</label>
                  <input name="remite" type="text" class="form-control" value="" />
                </div>

                <div class="form-group">
                  <label>Asunto (Identificacón del cliente)</label>
                  <input name="asunto" type="text" class="form-control" value="" />
                </div>

                <!-- textarea -->
                <div class="form-group">
                  <label>Contenido (Descripción detallada)</label>
                  <textarea name="contenido" class="form-control" rows="3" placeholder="Enter ..."></textarea>
                </div>
    
              </form>
            </div><!-- /.box-body -->
          </div>
        </div>
      </div>
     
      <div class="modal-footer">
        <div class="col-md-12">
          <button type="button" class="btn-normal" data-dismiss="modal">Regresar</button>
          <button class="btn-confirmar" type="submit">Enviar</button>
        </div>
      </div>
    </div>
  </div>
</div>
{{Form::close()}}

<!-- INFORMATIVO MODAL -->
@if (Auth::user()->tipo != 'N')
<div class="modal fade modal-slide-in-right" 
  aria-hidden="true" 
  role="dialog" 
  tabindex="-1" 
  id="modal-info">
  @php
  $cliente = DB::table('maecliente')
  ->where('codcli','=',$codcli)
  ->first();
  @endphp
  
  <div class="modal-dialog">
    <div class="modal-content">
  
      <div class="modal-header colorTitulo" >
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">x</span>
        </button>
        <h4 class="modal-title">
        <span class="fa fa-info-circle" style="font-size: 24px;"> 
        INFORMATIVO
        </span>
        </h4>
      </div>
   
      <div class="modal-body">
        @if (Auth::user()->tipo == "C" || Auth::user()->tipo == "O" || Auth::user()->tipo == "P"  )
          <span style="font-size: 20px; ">Código: <b>{{$cliente->codcli}}</b></span><br>
          <span style="font-size: 20px; ">Cliente: <b>{{$cliente->nombre}}</b></span><br>
          <span style="font-size: 20px; ">Keys: <b>{{$cliente->KeyIsacom}}</b></span><br>
          <span style="font-size: 20px; ">
          Visitas: <b>{{number_format($cliente->contVisita,0, '.', ',')}}</b>
          </span><br>
          <span style="font-size: 20px; ">
          Ultima visita: <b>{{date('d-m-Y H:i', strtotime($cliente->ultVisita))}}</b>
          </span><br>
          <span style="font-size: 20px; ">Restan dias: <b>{{$restandias}}</b></span><br> 
        @else
          @php
            $codgrupo = Auth::user()->codcli;
            $grupo = DB::table('grupo')
            ->where('id','=',$codgrupo)
            ->first();
            if ($grupo) {
              $gruporen = DB::table('gruporen')
              ->where('id','=',$codgrupo)
              ->where('status','=', 'ACTIVO')
              ->get();
            }
          @endphp
          <span style="font-size: 12px; ">Grupo: <b>{{$grupo->nomgrupo}}</b></span><br>

          @foreach ($gruporen as $gr)
            @php
            $restandias = iValidarLicencia($gr->codcli); 
            @endphp
            <span style="font-size: 12px; ">
            <b>{{$gr->nomcli}}</b> - Restan dias: <b>{{$restandias}}</b>
            </span><br>
          @endforeach

          <span style="font-size: 12px; ">
          Visitas: <b>{{number_format($grupo->contVisita,0, '.', ',')}}</b>
          </span><br>
          <span style="font-size: 12px; ">
          Ultima visita: <b>{{date('d-m-Y H:i', strtotime($grupo->ultVisita))}}</b>
          </span><br>
        @endif
      </div>
     
      <div class="modal-footer">
        <button type="button" class="btn-normal" data-dismiss="modal">Aceptar</button>
      </div>
    </div>
  </div>
</div>
@endif


<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-75J25BVMLZ"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-CE7C5GBHWG');
</script>

<script>
$("[data-widget='collapse']").click(function() {
  var ancho = screen.width;
  var modo = '{{$sidebarMode}}';
  if (ancho > 540 || modo == '2') { 
    $.ajax({
      type:'POST',
     url:'/isacom/modificar',
      dataType: 'json', 
      encode  : true,
      data: {modo : modo},
      success:function(data) {
  //          window.location.reload(); 
      }
    });

  } 
});
$( document ).ready(function() {
    var info = '{{$info}}';
    if (info == '0') {
      $('#modal-info').modal('show');
        $.ajax({
            type:'POST',
            url:'./isacom/info',
            dataType: 'json', 
            encode  : true,
            data: {info : '1'},
            success:function(data) { }
        });
    }
});
</script>

