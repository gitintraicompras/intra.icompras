{!! Form::open(array('url'=>'/ofertas/sugofertas','method'=>'GET','autocomplete'=>'off','role'=>'search')) !!}
<div class="input-group">
   <input type="text" name="filtro" class="form-control" placeholder="Buscar"  value="{{$filtro}}">
   <span class="input-group-btn">
      <button type="submit" class="btn btn-buscar" data-toggle="tooltip" title="Buscar producto">
         <span class="fa fa-search" aria-hidden="true"></span>
      </button>
   </span>
</div>
{{ Form::close() }}



