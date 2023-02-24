@extends ('layouts.menu')
@section ('contenido')

<div class="row"> 
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
		<button type="button" class="btn-normal" onclick="history.back(-1)" data-toggle="tooltip" title="Regresar">
			Regresar
		</button>
	</div>

	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
		@include('canales.licencia.search')
	</div>
</div>

<div class="clearfix"></div>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead class="colorTitulo">
					<th style="width: 30px;">#</th>
					<th style="width: 120px;">OPCION&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
					<th>CODIGO</th>
					<th>CLIENTE</th>
					<th>LICENCIA</th>
					<th>TELEFONO</th>
					<th>CONTACTO</th>
					<th>CANAL</th>
					<th>VENDEDOR</th>
					<th>FECHA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
					<th>DIAS</th>
					<th>RESTAN</th>
				</thead>
				@foreach ($regs as $reg)
				<tr>
					<td>{{$loop->iteration}}</td>
					<td style="width: 120px;">
						<a href=""   
							data-target="#modal-delete-{{$reg->cod_lic}}" 
							data-toggle="modal">
							<button class="btn btn-default fa fa-trash-o" 
								data-toggle="tooltip" 
								title="Eliminar licencia y usuario">
							</button>
						</a>

						@if ($canal->super > 0)
						<button data-toggle="tooltip" 
							title="Resetear licencia" 
							class="btn btn-pedido fa fa-unlock-alt BtnReset" 
							id="idReset-{{$reg->cod_lic}}">
						</button>
						@endif


						
					</td>
					<td>{{$reg->codisb}}</td>
					<td>{{$reg->nombre}}</td>
					<td>{{$reg->cod_lic}}</td>
					<td>{{$reg->telefono}}</td>
					<td>{{$reg->contacto}}</td>
					<td>{{$reg->codcanal}}</td>
					<td>{{$reg->codvendedor}}</td>
					<td>{{date('d-m-y', strtotime($reg->fecha))}}</td>
					<td align="right">{{$reg->diaLicencia}}</td>
					<td align="right" @if ($reg->restan <= 5) style="color: red;" @endif>
						{{$reg->restan}}
					</td>
				</tr>
				@include('canales.licencia.delete')
				@endforeach
			</table>
		</div>
	</div>
</div>

<!-- MODAL RESETEAR CLAVE -->
<div class="modal fade" 
	id="ModalReset" 
	role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header colorTitulo" >
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">x</span>
                </button>
                <h4 class="modal-title">RESETEAR LICENCIA</h4>
            </div>

            <div class="modal-body">
                <div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <span style="width: 100px;" class="input-group-addon">Licencia:</span>
                    <input readonly 
                    	type="text" 
                    	class="form-control" 
                    	value="" 
                    	style="color:#000000" 
                    	id="idcodlic" 
                    	name="codlic">   
                </div>
		    </div>

            <div class="modal-footer">
                <div style="margin-top: 5px;" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <button type="button" 
                    	class="btn-normal" 
                    	data-dismiss="modal">
                    	Regresar
                    </button>
                    <button type="button" 
                    	class="btn-confirmar BtnResetear" 
                    	data-dismiss="modal">
                    	Confirmar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>


@push ('scripts')
<script>
$('#subtitulo').text('{{$subtitulo}}');

$('.BtnReset').on('click',function(e){
	var variable = e.target.id.split('-');
    var codlic = variable[1];
    $('#idCodlic').val(codlic);
    $('#ModalReset').modal({show:true});
});

</script>
@endpush

@endsection
