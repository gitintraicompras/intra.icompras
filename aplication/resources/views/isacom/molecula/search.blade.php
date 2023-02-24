{!! Form::open(array('url'=>'/molecula','method'=>'GET','autocomplete'=>'off','role'=>'search')) !!}
<div class="form-group" style="float: right;">
	<div class="input-group">
		<input type="text" name="filtro" class="form-control" placeholder="Buscar"  value="{{$filtro}}">
		<span class="input-group-btn">
			<button type="submit" class="btn btn-buscar" data-toggle="tooltip" title="Buscar">
				<span class="fa fa-search" aria-hidden="true"></span>
			</button>
		</span>
	</div>
</div>
{{ Form::close() }}