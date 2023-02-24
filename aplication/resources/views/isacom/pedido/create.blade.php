@extends ('layouts.menu')
@section ('contenido')

{!! Form::open(array('url'=>'/pedido','method'=>'POST','autocomplete'=>'off', 'id' => 'form' )) !!}
{{ Form::token() }}
<div class="row">

	@if (!empty($pedgrupo)) 
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
	    <div class="form-group">
	    	<label>Pedido</label>
	    	<select name="idpedgrupo" 
	    		class="form-control selectpicker" 
	    		data-live-search="true"
	    		id="SelClickTipo" >
	    		<option value="NUEVO">CREAR PEDIDO NUEVO</option>
	    		@foreach($pedgrupo as $pg)
	    			<option value="{{$pg->id}}">{{$pg->id}}-{{$pg->marca}}-{{$pg->fecha}}</option>
	    		@endforeach
	    	</select>
	    </div>
	</div>
	@endif
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
	    <div class="form-group">
	    	<label>Marca</label>
	    	<select name="codmarca" 
	    		class="form-control selectpicker" 
	    		data-live-search="true"
	    		id="idmarca" >
	    		@foreach($marca as $m)
	    			<option value="{{$m->descrip}}">{{$m->descrip}}</option>
	    		@endforeach
	    	</select>
	    </div>
	</div>
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <label>Dias de reposición</label>
			<input type="number" 
				name="reposicion" 
				value="7" 
				id="idreposicion"
				class="form-control">
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <label>Cantidad sugerida en caso de VMD=0</label>
			<input type="number" 
				name="cantsug" 
				value="0" 
				id="idcantsug"
				class="form-control">
        </div>
    </div>
	<br>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="form-group">
			<button type="button" class="btn-normal" onclick="history.back(-1)">Regresar</button>
			<button class="btn-confirmar" type="submit">Crear</button>
			
		</div>
	</div>
	<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
</div>
{{ Form::close() }}

@push ('scripts')
<script>
$('#subtitulo').text('{{$subtitulo}}');

$('#SelClickTipo').on('change', function() {
    var id = this.value;
    if (id == "NUEVO") {
    	$('#idmarca').removeAttr('disabled');
    	$('#idreposicion').removeAttr('disabled');
    }
   	else {
   		var item = 0;
   		var resp = "";
		var jqxhr = $.ajax({
            type:'POST',
            url: './leerpedgrupo',
            dataType: 'json', 
            encode  : true,
            data: { id:id },
            success:function(q) {
            	resp = q.data;
            }
        });
    	jqxhr.always(function() {
   			var combo = document.getElementById("idmarca");
   			for (var i = 0; i < combo.length; i++) {
			    var opt = combo[i];
			    var marcax = opt.value.trim();
			    var marcay = resp.marca.trim();
			    if (marcax == marcay) {
			    	break;
			    }
			    item = item + 1;
			}
			document.getElementById("idmarca").selectedIndex = item;
			$('#idmarca').change();
			$('#idreposicion').val(resp.reposicion);
			$('#idmarca').prop('disabled', true);
   			$('#idreposicion').prop('disabled', true);
        });
   	}
});

$("#form").on("submit" ,function()
{   
    $('#idmarca').removeAttr('disabled');
    $('#idreposicion').removeAttr('disabled');   
});


</script>
@endpush

@endsection