{!! Form::open(array('url'=>'/pedido/catalogo/'.$tipo.'-'.$tpactivo,'method'=>'GET','autocomplete'=>'off','role'=>'search')) !!}

@if ($tipo == "M") 

  <div class="input-group md-form form-sm form-2 pl-0" 
    style="width: 29%; margin-right: 3px;">
      <select name="filtro"  
        class="form-control selectpicker" 
        data-live-search="true"
        style="width: calc(100% - 32px);" >

        @foreach($molecula as $mol)
          @if ($filtro == $mol->descrip)
            <option selected value="{{$mol->descrip}}">
              {{$mol->descrip}}
            </option>
          @else
            <option value="{{$mol->descrip}}">
              {{$mol->descrip}}
            </option>
          @endif
        @endforeach
      </select>
      <span class="input-group-btn">
          <button type="submit" 
            class="btn btn-buscar" 
            data-toggle="tooltip" 
            style="border-radius: 0 5px 5px 0"
            title="Buscar producto">
              <span class="fa fa-search" aria-hidden="true"></span>
          </button>
      </span>
    </span>
  </div>

@else 

  <div class="input-group md-form form-sm form-2 pl-0" 
  	style="width: 29%; margin-right: 3px;">
    <input class="form-control my-0 py-1 red-border catserch" 
    	type="text" 
    	name="filtro" 
    	value="{{$filtro}}" 
    	placeholder="Buscar por descripción o referencia" 
    	aria-label="Search">
      <span class="input-group-btn">
          <button type="submit" 
          	class="btn btn-buscar" 
          	data-toggle="tooltip" 
            style="border-radius: 0 5px 5px 0"
          	title="Buscar producto">
              <span class="fa fa-search" aria-hidden="true"></span>
          </button>
      </span>
  </div>
@endif
{{ Form::close() }}

