{{Form::Open(array('action'=>array('PedidoController@enviar',$tabla->id),'method'=>'get'))}}
<div align="left" 
    class="modal fade modal-slide-in-right" 
    aria-hidden="true" 
    role="dialog" 
    tabindex="-1" 
    id="modal-enviar-{{$tabla->id}}">
    <div class="modal-dialog">
    	<div class="modal-content">
    		<div class="modal-header colorTitulo" >
    			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
    				<span aria-hidden="true">x</span>
    			</button>
    			<h4 class="modal-title">ENVIAR PEDIDO</h4>
    		</div>
    		<div class="modal-body">
                <input hidden="" 
                    type="text" 
                    name="tipedido" 
                    value="{{$tabla->tipedido}}" >
    			<p>Pedido: <b>{{$tabla->id}} - {{$tabla->tipedido}}</b></p>
                <p>Cliente: <b>{{$tabla->codcli}} - {{$tabla->nomcli}}</b></p>
                <p>Ahorro:
                    <b style="color: red;">
                        {{number_format($tabla->ahorro, 2, '.', ',')}}
                    </b> 
                    &nbsp;&nbsp;&nbsp;&nbsp;Monto total: 
                    <b>{{number_format($tabla->total, 2, '.', ',')}}</b>
                </p>
    			<p>Marque los proveedores y confirme si desea enviar el Pedido ?</p>

    			<!-- BOTONES PROVEEDORES-->
                <div style="padding: 0px; margin: 0px;">
                	<center>
                    <span class="input-group-addon">
                    	<?php 
                            $contador = 0; 
                            $arrayProvPedido = obtenerProvPedido($tabla->id);
                        ?>
                        @foreach ($arrayProvPedido as $prov)
                            @php 
                                $confprov = LeerProve($prov); 
                                if (is_null($confprov))
                                    continue;
                            @endphp
                            <input name="check-{{$confprov->codprove}}" @if ($tpactivo==$confprov->codprove || $tpactivo=='MAESTRO') checked  @endif style="margin-left: 5px; color: {{$confprov->backcolor}};"  type="checkbox" class="form-check-input"  >
                            <button style="width: 120px; height: 32px; 
                                color: {{$confprov->forecolor}}; 
                                border: {{$confprov->backcolor}};  
                                background-color: {{$confprov->backcolor}};" >   {{$confprov->descripcion}}
                            </button>
                            <?php $contador++; ?>
                            @if ($contador > 2)
                            	<div class="clearfix"></div>
                            	<?php $contador = 0; ?>
                            @endif
                        @endforeach
                    </span>
                	</center>
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


