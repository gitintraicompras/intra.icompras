<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modal-deleteAll">
	{{Form::Open(array('action'=>array('PedidoController@deleteAll',$id),'method'=>'POST'))}}
	{{ Form::token() }}
	<input hidden name="id" value="{{$id}}" type="text">
	<input hidden name="tpactivo" value="{{$tpactivo}}" type="text">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header colorTitulo" >
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">x</span>
				</button>
				<h4 class="modal-title">ELIMINAR ITEM(s)</h4>
			</div>
			<div class="modal-body">
				<p>Pedido #: {{$id}}</p>
				<p>Confirme si desea eliminar los item(s) seleccionados ?</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn-normal" data-dismiss="modal">Regresar</button>
				<button type="submit" class="btn-confirmar">Confirmar</button>
			</div>
		</div>
	</div>
	{{Form::Close()}}
</div>

