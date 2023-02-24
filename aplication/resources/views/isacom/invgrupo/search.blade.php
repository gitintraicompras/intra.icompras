{!! Form::open(array('url'=>'/invgrupo','method'=>'GET','autocomplete'=>'off','role'=>'search')) !!}
<div class="row">
	<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
		<div class="input-group" style="margin-right: 3px;">
		  <input class="form-control" type="text" name="filtro" value="{{$filtro}}" placeholder="Buscar" aria-label="Search">
		    <span class="input-group-btn">
		        <button type="submit" class="btn btn-buscar" data-toggle="tooltip" title="Buscar producto">
		            <span class="fa fa-search" aria-hidden="true"></span>
		        </button>
		    </span>
		</div>
	</div>
</div>
{{ Form::close() }}



