<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// MENU PRINCIPAL
Auth::routes();
Route::get('/', 'HomeController@index');
Route::get('/sincinventario', function () {
    $codcli = sCodigoClienteActivo();
    $filename = 'inventario_' . $codcli . '.txt.zip';
    if (bCargarInvIndividual($filename) > 0)
        session()->flash('message', 'Inventario sincronizado satisfactoriamente');
    else
        session()->flash('warning', 'Imposible sincronizar inventario, intente mas tarde!!!');
    return Redirect::to('/');
});

Route::get('/prueba', 'HomeController@prueba');
Route::get('/cambiar/{id}', 'HomeController@cambiar');
Route::get('/moneda/{id}', 'HomeController@moneda');
Route::get('/tipedido/{id}', 'HomeController@tipedido');
Route::post('/isacom/modificar', 'HomeController@modificar');
Route::post('/isacom/info', 'HomeController@info');


/////////////////////
// MENU CANALES /////
/////////////////////
Route::resource('/canales/activacion', 'Canales\ActivacionController');
Route::post('/canales/activacion/verificar', 'Canales\ActivacionController@verificar');
Route::resource('/canales/canal', 'Canales\ConfigController');
Route::resource('/canales/vendedor', 'Canales\VendedorController');
Route::resource('/canales/licencia', 'Canales\LicenciaController');

/////////////////////
// MENU OFERTAS /////
/////////////////////
Route::resource('/ofertas/registros', 'RegistroController');
Route::get('/ofertas/registros/catalogo/{id}', 'RegistroController@catalogo');
Route::get('/ofertas/registros/descargar/{id}', 'RegistroController@descargar');
Route::resource('/ofertas/ofertas', 'OfertasController');
Route::resource('/ofertas/sugofertas', 'SugOfertasController');
Route::get('/ofertas/sugofertas/delete/{id}', 'SugOfertasController@deleprod');
Route::post('/ofertas/sugofertas/all/deleteAll', 'SugOfertasController@deleteAll');
Route::post('/ofertas/sugofertas/delsel', 'SugOfertasController@delsel');
Route::post('/ofertas/sugofertas/upddasug', 'SugOfertasController@upddasug');

///////////////////////
// MENU ICOMPRAS  /////
///////////////////////
Route::resource('/pedido', 'PedidoController');
Route::get('/pedido/verprod/{id}', 'PedidoController@verprod');
Route::get('/pedido/tendencia/{id}', 'PedidoController@tendencia');
Route::get('/pedido/exportar/{id}', 'PedidoController@exportar');
Route::get('/pedido/enviar/{id}', 'PedidoController@enviar');
Route::get('/pedido/guardar/{id}', 'PedidoController@guardar');
Route::get('/pedido/catalogo/{id}', 'PedidoController@catalogo');
Route::post('/pedido/agregar', 'PedidoController@agregar');
Route::post('/pedido/leerpedgrupo', 'PedidoController@leerpedgrupo');
Route::get('/pedido/deleprod/{id}', 'PedidoController@deleprod');
Route::post('/pedido/modificar', 'PedidoController@modificar');
Route::get('/pedido/descargar/pedidopdf/{id}', 'PedidoController@pedidopdf');
Route::post('/pedido/modcodalterno', 'PedidoController@modcodalterno');
Route::post('/pedido/procexportar', 'PedidoController@procexportar');
Route::post('/pedido/deleteAll', 'PedidoController@deleteAll');
Route::post('/pedido/marcaritem', 'PedidoController@marcaritem');
Route::post('/pedido/obtenerInvCliente', 'PedidoController@obtenerInvCliente');


Route::resource('/pedidodirecto', 'PedidodirectoController');
Route::get('/pedidodirecto/guardar/{id}', 'PedidodirectoController@guardar');
Route::get('/pedidodirecto/enviar/{id}', 'PedidodirectoController@enviar');
Route::get('/pedidodirecto/deleprod/{id}', 'PedidodirectoController@deleprod');
Route::post('/pedidodirecto/deleteAll', 'PedidodirectoController@deleteAll');
Route::post('/pedidodirecto/modificar', 'PedidodirectoController@modificar');
Route::post('/pedidodirecto/marcaritem', 'PedidodirectoController@marcaritem');
Route::post('/pedidodirecto/leerpedgrupo', 'PedidodirectoController@leerpedgrupo');
Route::get('/pedidodirecto/descargar/pedidopdf/{id}', 'PedidodirectoController@pedidopdf');
Route::post('/pedidodirecto/obtenerTablaCliMaestra', 'PedidodirectoController@obtenerTablaCliMaestra');
Route::get('/pedidodirecto/agregar/prod/{id}', 'PedidodirectoController@agregar');

Route::resource('/transito', 'TransitoController');
Route::post('/transito/liberar', 'TransitoController@liberar');

Route::resource('/menumolecula', 'MenumoleculaController');
Route::resource('/molecula', 'MoleculaController');
Route::get('/molecula/delprod/{id}', 'MoleculaController@delprod');

Route::resource('/invgrupo', 'InvGrupoController');

Route::resource('/grupo', 'GrupoController');
Route::post('/grupo/cliente/sumarpref', 'GrupoController@sumarpref');
Route::post('/grupo/cliente/restarpref', 'GrupoController@restarpref');
Route::post('/grupo/cliente/modstatus', 'GrupoController@modstatus');
Route::post('/grupo/cliente/modpredet', 'GrupoController@modpredet');

Route::resource('/proveedor', 'ProveedorController');
Route::get('/proveedor/prov/lista', 'ProveedorController@lista')->name('prov.lista');
Route::post('/proveedor/agregar/{id}', 'ProveedorController@agregar');
Route::get('/proveedor/cargar/{id}', 'ProveedorController@cargar');
Route::get('/proveedor/verprov/{id}', 'ProveedorController@verprov');
Route::get('/proveedor/cargar/ejemplo/descarejemplo', 'ProveedorController@descarejemplo');
Route::post('/proveedor/prov/modstatus', 'ProveedorController@modstatus');
Route::post('/proveedor/cargar/modcol', 'ProveedorController@modcol');


Route::post('/sumarpref', 'ProveedorController@sumarpref');
Route::post('/restarpref', 'ProveedorController@restarpref');
Route::resource('/config', 'ConfigController');
Route::resource('/invminmax', 'InvminmaxController');
Route::post('/invminmax/caract/modcaract', 'InvminmaxController@modcaract');

Route::resource('/analisis', 'AnalisisController');
Route::get('/analisis/descargar/excel/{id}', 'AnalisisController@excel');

Route::post('/config/correo', 'ConfigController@correo');
Route::post('/config/registrar', 'ConfigController@registrar');
Route::get('/config/editar', 'ConfigController@editar');
Route::post('/config/grabar', 'ConfigController@grabar');

Route::post('/image', 'ConfigController@postImage');

Route::post('/upload', function () {
    if (Input::hasFile('archivo')) {
        Input::file('archivo')
            ->move('carpetarArchivos', 'NuevoNombre');
    }
    return Redirect::back('/');
});

Route::resource('/provconfig', 'ProvConfigController');
Route::resource('/provpedido', 'ProvpedidoController');
Route::get('/provpedido/descargar/pedido/{id}', 'ProvpedidoController@descargar');
Route::resource('/provalcabala', 'ProvalcabalaController');
Route::get('/provalcabala/descargar/pedido/{id}', 'ProvalcabalaController@descargar');
Route::resource('/provcatalogo', 'ProvcatalogoController');
Route::get('/provcatalogo/descargar/catalogo', 'ProvcatalogoController@descargar');
Route::resource('/descargar', 'DescargaController');
Route::post('/proveedor/catalogo/descargar', 'DescargaController@catalogo');
Route::post('/proveedor/catalogo/rnk1', 'DescargaController@rnk1');
Route::post('/cliente/inventario/descargar', 'DescargaController@inventario');
Route::resource('/inventario', 'InventarioController');
Route::resource('/invcliente', 'InvClienteController');
Route::get('/inventario/cliente/cargar', 'InvClienteController@cargar');
Route::post('/inventario/cliente/modcol', 'InvClienteController@modcol');
Route::get('/inventario/cliente/ejemplo1', 'InvClienteController@ejemplo1');
Route::get('/inventario/cliente/ejemplo2', 'InvClienteController@ejemplo2');
Route::resource('/invsugerido', 'InvSugeridoController');
Route::get('/inventario/sugerido/crear', 'InvSugeridoController@crear');
Route::get('/inventario/sugerido/procesar', 'InvSugeridoController@procesar');
Route::get('/inventario/sugerido/eliminar/{id}', 'InvSugeridoController@eliminar');
Route::get('/inventario/sugerido/deleprod/{id}', 'InvSugeridoController@deleprod');
Route::get('/inventario/sugerido/descargar', 'InvSugeridoController@descargar');
Route::post('/inventario/sugerido/modificaritem', 'InvSugeridoController@modificaritem');
Route::resource('/invfallas', 'InvFallasController');
Route::get('/inventario/fallas/procesar', 'InvFallasController@procesar');
Route::get('/inventario/fallas/eliminar/{id}', 'InvFallasController@eliminar');
Route::get('/inventario/fallas/deleprod/{id}', 'InvFallasController@deleprod');
Route::get('/inventario/fallas/descargar', 'InvFallasController@descargar');
Route::post('/inventario/fallas/modificaritem', 'InvFallasController@modificaritem');
Route::resource('/informes', 'InformesController');
Route::get('/informes/inventario/valor', 'InformesController@invvalor');
Route::get('/informes/inventario/valor', 'InformesController@invvalor');
Route::get('/informes/productos/vendidoisacom', 'InformesController@vendidoisacom');
Route::get('/informes/renprov/barra', 'InformesController@renprovbarra');
Route::get('/informes/uniprov/barra', 'InformesController@uniprovbarra');
Route::get('/informes/pedidocli/line', 'InformesController@pedidocliline');
Route::get('/informes/pedidocli/barra', 'InformesController@pedidoclibarra');
Route::get('/informes/rnk1prov/barra', 'InformesController@rnk1provbarra');
Route::get('/informes/mejoropcion/table', 'InformesController@mejoropciontable');

Route::get('/informes/facturas/modfact', 'InformesController@modfact');
Route::get('/informes/ahorro/verahorro', 'InformesController@verahorro');
Route::get('/informes/auditoria/verauditoria', 'InformesController@verauditoria');
Route::get('/informes/ejemplo/table', 'InformesController@ejemplotable');
Route::get('/informes/auditoria/desvped', 'InformesController@desvped');

Route::get('/factura/exportar/{id}', 'FacturaController@exportar');
Route::get('/factura/descargartxt/{id}', 'FacturaController@descargartxt');
Route::get('/factura/descargarpdf/{id}', 'FacturaController@descargarpdf');
Route::resource('/factura', 'FacturaController');

Route::post('/prodcaract/caract/modcaract', 'ProdcaractController@modcaract');
Route::resource('/prodcaract', 'ProdcaractController');
Route::resource('/prodexclu', 'ProdexcluController');

Route::resource('/pedgrupo', 'PedGrupoController');
Route::post('/pedgrupo/obtenerCodcliGrupo', 'PedGrupoController@obtenerCodcliGrupo');
Route::post('/pedgrupo/obtenerTablaCliMaestra', 'PedGrupoController@obtenerTablaCliMaestra');
Route::get('/pedgrupo/enviar/{id}', 'PedGrupoController@enviar');
Route::get('/pedgrupo/agregar/prod/{id}', 'PedGrupoController@agregar');
Route::post('/pedgrupo/modificar', 'PedGrupoController@modificar');
Route::get('/pedgrupo/deleprod/{id}', 'PedGrupoController@deleprod');
Route::resource('/invdirectoclie', 'invdirectoclieController');