<div class="modal fade modal-slide-in-right" 
	aria-hidden="true" 
	role="dialog" 
	tabindex="-1" 
	id="modal-delete-{{$sug->item}}">
{{Form::Open(array('action'=>array('SugOfertasController@deleprod',$sug->item),'method'=>'get'))}}
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header colorTitulo" >
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">x</span>
			</button>
			<h4 class="modal-title">ELIMINAR OFERTA</h4>
		</div>
		<div class="modal-body">
			<input hidden id="item" type="text" name="item" value="{{$sug->item}}">
			<p>Código: {{$sug->codprod}}</p>
			<p>Producto: {{$sug->desprod}}</p>
			<p>Confirme si desea eliminar la oferta ?</p>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn-normal" data-dismiss="modal">Regresar</button>
			<button type="submit" class="btn-confirmar">Confirmar</button>
		</div>
	</div>
</div>
{{Form::Close()}}
</div>