@extends ('layouts.menu')
@section ('contenido')

<div class="row">
	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
		<div class="form-group">
	        <label>ID</label>
            <input readonly type="text" 
            	class="form-control" 
            	value="{{$reg->id}}">
	    </div>
    </div>

    @if ($moleren->count() == 0)
        {!!Form::model($reg,['method'=>'PATCH','route'=>['molecula.update',$reg->id]])!!}
        {{Form::token()}}
        <input hidden="" type="text" name="modo" value="EDIT">
    	<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
            <div class="input-group">
                <label>Descripción</label>
                <input type="text" 
                    class="form-control" 
                    name="descrip" 
                    value="{{$reg->descrip}}" >
                <span class="input-group-btn">
                    <button type="submit" 
                        class="btn btn-buscar" 
                        data-toggle="tooltip" 
                        title="Guardar descripción"
                        style="margin-top: 22px;">
                        <span class="fa fa-save" style="font-size:20px" aria-hidden="true"></span>
                    </button>
                </span>
            </div>
        </div>
        {{Form::close()}}
    @else
        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
            <label>Descripción</label>
            <input type="text" 
                readonly="" 
                class="form-control" 
                name="descrip" 
                value="{{$reg->descrip}}" >
        </div>
    @endif

</div>

<div class="row" style="margin-bottom: 10px;"> 
    <div class="col-xs-12">
        @include('isacom.molecula.insprod')
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-condensed table-hover">
                <thead class="colorTitulo">
                    <th style="width: 50px;">#</th>
                    <th style="width: 50px;"></th>
                    <th style="width: 50px;">BARRA</th>
                    <th>PRODUCTO</th>
                    <th>UNIDAD</th>
                    <th>MARCA</th>
                </thead>
                @foreach ($moleren as $mr)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>
                        <a href="" data-target="#modal-delete-{{$mr->barra}}" data-toggle="modal">
                            <button class="btn btn-pedido fa fa-trash-o" 
                            	title="Eliminar ...">
                            </button>
                        </a>
                    </td>
                    <td>{{$mr->barra}}</td>
                    <td>{{LeerMaestra($mr->barra, 'desprod')}}</td>
                    <td align='right'>{{$mr->unidadmolecula}}</td>
                    <td>{{$mr->marca}}</td>
                </tr>
                @include('isacom.molecula.delprod')
                @endforeach
            </table>
        </div>
    </div>
</div>

@push ('scripts')
<script>
$('#subtitulo').text('{{$subtitulo}}');
</script>
@endpush

@endsection


