<div class="modal fade modal-slide-in-right" 
	aria-hidden="true" 
	role="dialog" tabindex="-1" 
	id="modal-agregar-<?php echo e($id); ?>" >
	<div class="modal-dialog">
		<div class="modal-content" >
			<div class="modal-header colorTitulo"  >
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">x</span>
				</button>
				<h4 class="modal-title">AGREGAR PRODUCTO NUEVO </h4>
			</div>
			<div class="modal-body">
				<div class="col-xs-6">
					<div class="form-group">
						<label>Cantidad</label>
						<input id="idcant" 
							type="text" 
							name="cantidad"
							value="10"  
							class="form-control"
							style="text-align: right;">
					</div>
				</div>
			
				<label hidden="" id="idbarra"></label>
				<div class="input-group md-form form-sm form-2 pl-0" 
				  	style="width: 47%; margin-right: 3px;">
				    	<input class="form-control my-0 py-1 red-border catserch" 
					    	type="text" 
					    	name="filtro" 
					    	value="<?php echo e($filtro); ?>"
					    	style="margin-top: 25px;" 
					    	placeholder="Buscar por descripción o referencia" 
					    	aria-label="Search"
					    	id="idfiltro">
				      	<span class="input-group-btn">
				      	  <a onclick="cargarProd()" >
					          <button class="btn btn-buscar" 
					          	data-toggle="tooltip" 
					            style="border-radius: 0 5px 5px 0; margin-top: 25px;"
					          	title="Buscar producto">
					              <span class="fa fa-search" aria-hidden="true"></span>
					          </button>
				          </a>
				      </span>
				</div>

				<div class="col-xs-12">
					<div class="table-responsive" 
						style="height: 300px; margin-bottom: 20px;">
						<table id="myTable1" 
			                class="table table-striped table-bordered table-condensed table-hover">
			                <thead style="background-color: #b7b7b7;">
			                	<th>MARCA</th>
			                    <th>PRODUCTO</th>
			                    <th>BARRA</th>
			                    <th>MARCA</th>
			                </thead>
			                <tbody id="tbodyProducto">
	            			</tbody>
				       </table>
			       </div>
		     	</div>
			</div>
			<div class="modal-footer" >
				<div class="col-xs-12">
					<button type="button" 
						class="btn-normal" 
						data-dismiss="modal">
						Regresar
					</button>
				 	<a onclick="ejecutarAgregar()">
	                    <button class="btn btn-confirmar" 
	                        data-toggle="tooltip" 
	                        title="Agregar producto nuevo">
	                        Confirmar
	                    </button>
	                </a>
				</div>
			</div>
		</div>
	</div>
</div>
<?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/isacom/pedidodirecto/agregar.blade.php ENDPATH**/ ?>