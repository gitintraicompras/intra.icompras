{!! Form::open(array('url'=>'/invminmax','method'=>'GET','autocomplete'=>'off','role'=>'search')) !!}
<div class="input-group">

	<select name="filtro"  
        class="form-control selectpicker" 
        data-live-search="true"
        style="width: calc(100% - 32px);" >
		@foreach ($marca as $ma)
	      @if ($filtro == $ma)
	        <option selected value="{{$ma}}">
	          {{$ma}}
	        </option>
	      @else
	        <option value="{{$ma}}">
	          {{$ma}}
	        </option>
	      @endif
	    @endforeach
	</select>
	
	<span class="input-group-btn">
		<button type="submit" 
			class="btn btn-buscar" 
			data-toggle="tooltip" 
			title="Buscar producto">
			<span class="fa fa-search" aria-hidden="true"></span>
		</button>
	</span>
</div>
{{ Form::close() }}


