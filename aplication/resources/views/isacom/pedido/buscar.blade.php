<div class="modal fade modal-slide-in-right" 
	aria-hidden="true" 
	role="dialog" 
	tabindex="-1" 
	id="modal-buscar">

<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header colorTitulo" >
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">x</span>
			</button>
            <h4 class="modal-title">BUSCAR MANUAL</h4>
		</div>
		<div class="modal-body">
			<p>Barra: <span id="idbarraBuscar">{{$t->barra}}</span></p>
            <p>Producto: <span id="iddescripBuscar">{{$t->desprod}}</span></p>
			Buscar <input autofocus name="filtro" 
				id="searchTerm" 
				type="text" 
				style="margin-bottom: 10px;" 
				onkeyup="doSearch()"  />
			<div class="table-responsive" style="height: 250px;">
				<table id="datos" class="table table-striped table-bordered table-condensed table-hover">
					<thead class="colorTitulo">
						<th style="width: 50px;"> </th>
						<th>DESCRIPCION</th>
						<th>BARRA</th>
						<th>CODIGO</th>
					</thead>
   			        @foreach ($invent as $inv)
		          	<tr>
	        			<td style="width: 50px;
	        				padding-top: 10px;  
                            text-align: center;
                            vertical-align: middle;">
                                <input 
                                    type="checkbox" 
                                    onclick='tdclickProd(event);'
                                    id="product_{{$inv->codprod}}" />

                        </td>
	                    <td>{{$inv->desprod}}</td>
	                    <td>{{$inv->barra}}</td>
	                    <td>{{$inv->codprod}}</td>
	                </tr>
   		            @endforeach
       		</table>
			</div>
			

		</div>
		<div class="modal-footer">
			<button type="button" class="btn-normal" data-dismiss="modal">Regresar</button>
		</div>
	</div>
</div>
</div>



