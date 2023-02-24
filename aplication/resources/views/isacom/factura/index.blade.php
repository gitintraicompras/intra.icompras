@extends ('layouts.menu')
@section ('contenido')

<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <select id="SelClickProveedor"
            class="form-control selectpicker" 
            data-live-search="true" >
            @foreach($provs as $p)
                @if ($codprove == $p->codprove)
                    <option selected value="{{$p->codprove}}">
                        {{$p->codprove}}-{{$p->descripcion}}
                    </option>
                @else
                    <option value="{{$p->codprove}}">
                        {{$p->codprove}}-{{$p->descripcion}}
                    </option>
                @endif
            @endforeach
        </select>

        <div class="form-group" style="margin-top: 10px;">
            @include('isacom.factura.search')
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group">
            <div class="input-group input-group-sm">
                <span class="input-group-addon">Total:</span>
                <input readonly type="text" class="form-control" id="idtot" value="0.00" style="color: #000000; text-align: right; background: #F7F7F7;" placeholder="Monto total">
            </div>
        </div>
    </div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table id="idtabla" class="table table-striped table-bordered table-condensed table-hover">
         
                <thead class="colorTitulo">
                    <th>#</th>
                    <th style="width: 140px;">OPCION</th>
                    <th>FACTURA</th>
                    <th style="width: 100px;">EMISION</th>
                    <th style="width: 100px;">VENCE</th>
                    <th>MONTO</th>
                    <th>IVA</th>
                    <th>TOTAL</th>
	            </thead>
                @if (!empty($tabla))
    	            @foreach ($tabla as $t)
                    <tr>
                    	<td>{{$loop->iteration}}</td>
                    	<td>

                    		<!-- VER FACTURA -->
                            <a href="{{URL::action('FacturaController@show',$t->factnum.'_'.$codprove)}}">
                            	<button class="btn btn-pedido fa fa-file-o" 
                                    data-toggle="tooltip" 
                                    title="Consular Factura">
                            	</button>
                            </a>

                            <!-- DESCARGAR FACTURA PDF-->
                            <a href="{{URL::action('FacturaController@descargarpdf',$t->factnum.'_'.$codprove)}}">
                                <button class="btn btn-pedido fa fa-download" 
                                    data-toggle="tooltip" 
                                    title="Descargar Factura en pdf">
                                </button>
                            </a>

                            <!-- EXPORTAR FACTURA -->
                            <a href="{{URL::action('FacturaController@exportar',$t->factnum.'_'.$codprove)}}">
                                <button class="btn btn-pedido fa fa-share-square-o" data-toggle="tooltip" title="Exportar factura">
                                </button>
                            </a>
                

                    	</td>
                        <td>{{$t->factnum}}</td>
                        <td>{{date('d-m-Y', strtotime($t->fecha))}}</td>
                        <td>{{date('d-m-Y', strtotime($t->fechav))}}</td>
                    	<td align='right'>{{number_format($t->monto, 2, '.', ',')}}</td>	
                    	<td align='right'>{{number_format($t->iva, 2, '.', ',')}}</td>
                    	<td align='right'>{{number_format($t->total, 2, '.', ',')}}</td>
                    </tr>
                    @endforeach
                @endif
            </table><br>
        </div><br>
	</div>
</div>

@push ('scripts')
<script>
$('#subtitulo').text('{{$subtitulo}}');
window.onload = function(){
    var tableReg = document.getElementById('idtabla');
    var tot = 0.00;
    // Recorremos todas las filas con contenido de la tabla
    for (var i = 1; i < tableReg.rows.length; i++) {
        cellsOfRow = tableReg.rows[i].getElementsByTagName('td');
        var s1 = cellsOfRow[7].innerHTML;
        var s2 = s1.replace(/,/g, '');
        var inv = parseFloat(s2).toFixed(2);
        tot += parseFloat(inv);
    }
    $("#idtot").val(number_format(tot, 2, '.', ','));
}

$('#SelClickProveedor').on('change', function()
{
    var codigo = this.value;
    $('#idcodprove').val(codigo);
});


</script>
@endpush
@endsection