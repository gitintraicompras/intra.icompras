{{Form::Open(array('action'=>array('PedidodirectoController@guardar',$tabla->id),'method'=>'get'))}}
<div 
    align="left" 
    class="modal fade modal-slide-in-right" 
    aria-hidden="true" 
    role="dialog" 
    tabindex="-1" 
    id="modal-guardar-{{$tabla->id}}">
    <div class="modal-dialog">
    	<div class="modal-content">
    		<div class="modal-header colorTitulo" >
    			<button type="button" 
                    class="close" 
                    data-dismiss="modal" 
                    aria-label="Close">
    				<span aria-hidden="true">x</span>
    			</button>
    			<h4 class="modal-title">GUARDAR PEDIDO DIRECTO</h4>
    		</div>
    		<div class="modal-body">
                <input hidden="" type="text" name="tipedido" value="D" >
                <input hidden="" type="text" name="marca" value="{{$tabla->marca}}" >
    			<p>Pedido: <b>{{$tabla->id}}</b></p>
                <p>Marca: <b>{{$tabla->marca}}</b></p>
                <p>Monto total: <b>{{number_format($tabla->total, 2, '.', ',')}}</b></p>
            	<div style="font-size: 16px;">
                    Confirme si desea guardar el Pedido ?
                </div>
    		</div>
    		<div class="modal-footer">
    			<button type="button" class="btn-normal" data-dismiss="modal">Regresar</button>
                <button type="submit" class="btn-confirmar">Confirmar</button>
    		</div>
    	</div>
    </div>
</div>
{{Form::Close()}}


