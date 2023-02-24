{!! Form::open(array('url'=>'/pedgrupo','method'=>'POST','autocomplete'=>'off', 'id' => 'form' )) !!}
{{ Form::token() }}
<a href="" 
	data-target="#modal-create" 
	data-toggle="modal">
	<button style="width: 120px;" 
		class="btn-normal" 
		data-toggle="tooltip" 
		title="Crear pedido directo nuevo">
		Pedido Nuevo
	</button>
</a>
<div class="modal fade modal-slide-in-right" 
	aria-hidden="true" 
	role="dialog" 
	tabindex="-1" 
	id="modal-create">
	<div class="modal-dialog">
        <input hidden="" type="text" name="codgrupo" value="{{$codgrupo}}" >
		<div style="height: 290px;" 
			class="modal-content">
			<div class="modal-header colorTitulo" >
				<button type="button" 
					class="close" 
					data-dismiss="modal" 
					aria-label="Close">
					<span aria-hidden="true">x</span>
				</button>
				<h4 class="modal-title">CREAR PEDIDO NUEVO</h4>
			</div>
			<div class="modal-body">
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
			</div>
			<div style="margin-top: 110px; margin-right: 15px;" 
				class="modal-footer">
    			<button type="button" class="btn-normal" data-dismiss="modal">Regresar</button>
                <button type="submit" class="btn-confirmar">Confirmar</button>
    		</div>
		</div>
	</div>
</div>
{{ Form::close() }}




