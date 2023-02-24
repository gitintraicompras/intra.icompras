<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modal-delete-{{$prov->codprove}}">
{{Form::Open(array('action'=>array('ProveedorController@destroy',$prov->codprove),'method'=>'delete'))}}
<div class="modal-dialog">
	<input hidden value="{{$codcli}}" type="text" name="codcli">
	<div class="modal-content">
		<div class="modal-header colorTitulo">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">x</span>
			</button>
			<h4 class="modal-title">ELIMINAR PROVEEDOR</h4>
		</div>
		<div class="modal-body">
			<p>Cliente: {{$codcli}}</p>
			<p>Proveedor: {{$prov->codprove}}</p>
			<p>Confirme si desea eliminar el proveedor ?</p>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn-normal" data-dismiss="modal">Regresar</button>
			<button type="submit" class="btn-confirmar">Confirmar</button>
		</div>
	</div>
</div>
{{Form::Close()}}
</div>