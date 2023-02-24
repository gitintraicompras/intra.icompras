<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modal-delete-{{$t->item}}">
{{Form::Open(array('action'=>array('TransitoController@destroy',$t->item),'method'=>'delete'))}}
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header colorTitulo" >
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">x</span>
			</button>
			<h4 class="modal-title">LIBERAR PRODUCTO</h4>
		</div>
		<div class="modal-body">
			<p>ID: {{$t->id}}</p>
			<p>Item: {{$t->item}}</p>
			<p>Codprod: {{$t->codprod}}</p>
			<p>Barra: {{$t->barra}}</p>
			<p>Descripción: {{$t->desprod}}</p>
			<p>Confirme si desea liberar el producto ?</p>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn-normal" data-dismiss="modal">Regresar</button>
			<button type="submit" class="btn-confirmar">Confirmar</button>
		</div>
	</div>
</div>
{{Form::Close()}}
</div>