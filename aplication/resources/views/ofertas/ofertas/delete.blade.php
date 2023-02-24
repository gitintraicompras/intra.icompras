{{Form::Open(array('action'=>array('OfertasController@destroy',$inv->codprod),'method'=>'delete'))}}
<div class="modal fade modal-slide-in-right" 
	aria-hidden="true" 
	role="dialog" 
	tabindex="-1" 
	id="modal-delete-{{$inv->codprod}}">
	<input hidden="" type="text" name="desprod" value="{{$inv->desprod}}" >
    <input hidden="" type="text" name="desprod" value="{{$inv->desprod}}" >          
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header colorTitulo" >
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">x</span>
				</button>
				<h4 class="modal-title">ELIMINAR OFERTA</h4>
			</div>
			<div class="modal-body">
				<p>Codprod: {{$inv->codprod}}</p>
				<p>Descripción: {{$inv->desprod}}</p>
				<p>Confirme si desea eliminar la Oferta del producto?</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn-normal" data-dismiss="modal">Regresar</button>
				<button type="submit" class="btn-confirmar">Confirmar</button>
			</div>
		</div>
	</div>
</div>
{{Form::Close()}}


