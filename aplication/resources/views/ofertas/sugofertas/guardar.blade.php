{!! Form::open(array('url'=>'/ofertas/sugofertas','method'=>'POST','autocomplete'=>'off')) !!}
{{ Form::token() }}
@php
$desde = date('Y-m-d');
$hasta = date('Y-m-d', strtotime('7 day', strtotime($desde)));
$sugerido = DB::table('sugoferen')
->selectRaw('count(*) as cont')
->where('codcli','=',$codcli)
->first(); 
@endphp
<div class="modal fade modal-slide-in-right" 
	 aria-hidden="true" 
	 role="dialog" 
	 tabindex="-1" 
	 id="modal-guardar">

	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header colorTitulo" >
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">x</span>
				</button>
				<h4 class="modal-title">
					GUARDAR OFERTAS (CONTADOR: {{number_format($sugerido->cont, 0, '.', ',')}})
				</h4>
			</div>
			<div class="modal-body">
				<div class="col-xs-12">
                    <div class="form-group">
                        <label>Observación</label>
                        <input id="idobserv" type="text" name="observ" class="form-control">
                    </div>
                </div> 
                <div class="col-xs-12">
                    <div class="form-group">
                        <label>Desde:</label>
                        <input type="date" 
                        	name="desde" 
                        	class="form-control" 
                        	value="{{date('Y-m-d')}}">
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="form-group">
                        <label>Hasta:</label>
                        <input type="date" 
                        	name="hasta" 
                        	class="form-control" 
                        	value="{{date('Y-m-d', strtotime($hasta))}}">
                    </div>
                </div>
                <div class="col-xs-12">
	                <div class="form-check">
					    @if ($cliente->ppsa == '1')
					        <input checked name="ppsa" type="checkbox" class="form-check-input">
					    @else
					        <input name="ppsa" type="checkbox" class="form-check-input">
					    @endif
					    <label class="form-check-label">Procesar por el Sistema Administrativo de forma automatica</label>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn-normal" data-dismiss="modal">Regresar</button>
				@if ($sugerido->cont > 0)
					<button type="submit" class="btn-confirmar">Confirmar</button>
				@endif
			</div>
		</div>
	</div>
</div>
{{Form::Close()}}

