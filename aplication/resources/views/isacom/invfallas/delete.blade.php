<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modal-delete-{{$codcli}}">
{{Form::Open(array('action'=>array('InvFallasController@destroy',$codcli),'method'=>'delete'))}}
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header colorTitulo" >
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">x</span>
			</button>
			<h4 class="modal-title">ELIMINAR FALLAS</h4>
		</div>
		<div class="modal-body">
			<input hidden id="id" type="text" name="id" value="{{$codcli}}">
			<p>Código: {{$codcli}}</p>
			<p>Confirme si desea eliminar la fallas ?</p>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn-normal" data-dismiss="modal">Regresar</button>
			<button type="submit" class="btn-confirmar">Confirmar</button>
		</div>
	</div>
</div>
{{Form::Close()}}
</div>
