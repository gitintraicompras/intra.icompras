{{Form::Open(array('action'=>array('MoleculaController@delprod',$reg->id .'-'. $mr->barra),'method'=>'get'))}}
<div class="modal fade modal-slide-in-right" 
	aria-hidden="true" 
	role="dialog" 
	tabindex="-1" 
	id="modal-delete-{{$mr->barra}}">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header colorTitulo" >
				<button type="button" 
					class="close" 
					data-dismiss="modal" 
					aria-label="Close">
					<span aria-hidden="true">x</span>
				</button>
				<h4 class="modal-title">RETIRAR PRODUCTO</h4>
			</div>
			<div class="modal-body">
				<p>Molecula: {{$reg->id}} - {{$reg->descrip}}</p>
				<p>Producto: {{$mr->barra}} - {{LeerMaestra($mr->barra, 'desprod')}}</p>
				<p>Confirme si desea retirar el producto de la molecula ?</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn-normal" data-dismiss="modal">Regresar</button>
				<button type="submit" class="btn-confirmar">Confirmar</button>
			</div>
		</div>
	</div>
</div>
{{Form::Close()}}
