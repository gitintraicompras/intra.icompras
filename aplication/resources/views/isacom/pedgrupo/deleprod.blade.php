{{Form::Open(array('action'=>array('PedGrupoController@deleprod',$id),'method'=>'get'))}}
@php
$confcli = LeerCliente($codsuc);
$nomcli = $confcli->nombre;
@endphp
<input hidden type="text" name="barra" value="{{$p->barra}}">
<input hidden type="text" name="codsuc" value="{{$gr->codcli}}">
<div class="modal fade modal-slide-in-right" 
	aria-hidden="true" 
	role="dialog" 
	tabindex="-1" 
	id="modal_delete_{{$gr->codcli}}_{{$p->barra}}">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header colorTitulo" >
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">x</span>
				</button>
				<h4 class="modal-title">ELIMINAR PRODUCTO</h4>
			</div>
			<div class="modal-body">
				<p>Sucursal: {{$gr->codcli}} - {{$nomcli}}</p>
				<p>Barra: {{$p->barra}} - {{$p->desprod}}</p>
				<p>Confirme si desea eliminar el producto ?</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn-normal" data-dismiss="modal">Regresar</button>
				<button type="submit" class="btn-confirmar">Confirmar</button>
			</div>
		</div>
	</div>
</div>
{{Form::Close()}}
