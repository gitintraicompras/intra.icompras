{!! Form::open(array('url'=>'/pedido','method'=>'GET','autocomplete'=>'off','role'=>'search')) !!}
<div class="input-group">
	<input type="text" name="filtro" class="form-control" placeholder="Buscar"  value="{{$filtro}}">
	<span class="input-group-btn">
		<button type="submit" class="btn btn-buscar" data-toggle="tooltip" title="Buscar pedido">
			<span class="fa fa-search" aria-hidden="true"></span>
		</button>
	</span>
</div>
{{ Form::close() }}


