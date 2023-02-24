@extends ('layouts.menu')
@section ('contenido')
 
<div class="row">
	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
		<div class="form-group">
	        <label>ID</label>
            <input readonly type="text" 
            	class="form-control" 
            	name="name" 
            	value="{{$reg->id}}">
	    </div>
    </div>

	<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
	    <div class="form-group">
	        <label>Estatus</label>
            <input readonly type="text" 
            	class="form-control" 
            	name="status" 
            	value="{{$reg->descrip}}" >
	    </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-condensed table-hover">
                <thead class="colorTitulo">
                    <th style="width: 50px;">#</th>
                    <th style="width: 50px;">BARRA</th>
                    <th>PRODUCTO</th>
                    <th>UNIDAD</th>
                    <th>MARCA</th>
                </thead>
                @foreach ($moleren as $mr)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$mr->barra}}</td>
                    <td>{{LeerMaestra($mr->barra, 'desprod')}}</td>
                    <td align='right'>{{$mr->unidadmolecula}}</td>
                    <td>{{$mr->marca}}</td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>


<!-- REGRESAR -->
<div class="form-group" style="margin-top: 20px;">
    <button type="button" class="btn-normal" onclick="history.back(-1)">Regresar</button>
</div>


@push ('scripts')
<script>
$('#subtitulo').text('{{$subtitulo}}');
</script>
@endpush

@endsection


