<a href="" data-target="#modal-insprod-{{$reg->id}}" data-toggle="modal">
    <button style="font-size: 18px; width: 200px;" 
    	title="Agregar ..." 
    	class="btn-normal">
        Agregar producto
    </button>
</a>
<div class="modal fade modal-slide-in-right" 
	aria-hidden="true" 
	role="dialog" 
	tabindex="-1" 
	id="modal-insprod-{{$reg->id}}">
 	{!!Form::model($reg,['method'=>'PATCH','route'=>['molecula.update',$reg->id]])!!}
    {{ Form::token() }}
    <input hidden="" type="text" name="modo" value="AGREGAR">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header colorTitulo">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">x</span>
				</button>
				<h4 class="modal-title">NUEVO PRODUCTO</h4>
			</div>
			<div class="modal-body">
				<div class="col-xs-12">
					<div class="form-group">
						<label>ID</label>
						<input type="text" 
							readonly 
							value="{{$reg->id}}" 
							class="form-control">
					</div>
				</div>
				<div class="col-xs-12">
					<div class="form-group">
						<label>Molecula</label>
						<input type="text" 
							readonly 
							name="descrip"
							value="{{$reg->descrip}}" 
							class="form-control">
					</div>
				</div>
				<div class="col-xs-12" >
				    <div class="form-group">
				    	<label>Productos</label>
				    	<select name="barra" 
				    		class="form-control selectpicker" 
				    		data-live-search="true">
				    		@foreach($prods as $p)
				    			<option style="width: 520px;" 
				    				value="{{$p->barra}}">
				    				{{$p->barra}}-{{$p->desprod}}
				    			</option>
				    		@endforeach
				    	</select>
				    </div>
				</div>
				<div class="col-xs-12">
					<div class="form-group">
						<label>Unidad Molecula</label>
						<input type="text" 
							name="unidadmolecula"
							value="10" 
							class="form-control">
					</div>
				</div>
			</div>
			<div class="modal-footer" style="margin-right: 20px;">
				<button type="button" class="btn-normal" data-dismiss="modal">Regresar</button>
				<button type="submit" class="btn-confirmar">Confirmar</button>
			</div>
		</div>
	</div>
	{{Form::Close()}}
</div>


