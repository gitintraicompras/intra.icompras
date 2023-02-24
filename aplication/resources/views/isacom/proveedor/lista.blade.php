@extends ('layouts.menu')
@section ('contenido')
<div id="page-wrapper">
   
    <div class="container" style="width: 100%;">

  		<?php $x=0; ?>
    	@foreach ($provs as $prov)
            @if (verificarProveNuevo($prov->codprove)==0)
        	@php 
        		$confprov = LeerProve($prov->codprove); 
                if (is_null($confprov))
                    continue;
        		if ($x > 2) {
        			echo "<div class='clearfix'></div>"; 
        			$x=0;
        		}
        		$x++;
        	@endphp 
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12" >
                <div class="panel" 
                    style="background-color: {{$confprov->backcolor}}; 
                    color: {{$confprov->forecolor}}; border-radius: 15px;">
                    <div class="panel-heading" 
                        style="height: 160px;">
                        <div class="row">
                            <a href="http://{{$prov->web}}">
                                <div class="col-xs-3">
                                    
                                    <div align="center">
                                        <a href="">
                                            <img src="http://isaweb.isbsistemas.com/public/storage/prov/{{$prov->rutalogo1}}" 
                                            width="100%" 
                                            height="100%" 
                                            class="img-responsive" 
                                            alt="icompras360"
                                            style="border: 2px solid #D2D6DE;"
                                            oncontextmenu="return false" >
                                        </a>
                                    </div>
                                    <div data-toggle="tooltip" title="Contador de productos">
                                    	iTEM: {{number_format(ObtenerContadorProd($prov->codprove), 0, '.', ',')}}<br>
                                        {{substr($prov->region,4, strlen($prov->region)-4)}}
                                    </div>

                   
                                </div>
                            </a>
                            <div class="col-xs-9 text-right">
                                <div style="font-size: 20px;" data-toggle="tooltip" title="Nombre del proveedor"> 
                                	{{ strtoupper($prov->descripcion) }}
                                </div>

                                <div data-toggle="tooltip" title="Código del proveedor">
                                    {{ $prov->codprove }}</div>
                                <div style="font-size: 20px;" data-toggle="tooltip" title="Sede destino">
                                	SEDE: {{$prov->codsede}}
                                </div>
                                <div style="font-size: 20px;" data-toggle="tooltip" title="Origen de creación">
                                    ORIGEN: {{$prov->origen}}
                                </div>
                                <div data-toggle="tooltip" title="Fecha sincronización del catálogo">
                                	{{date('d-m-Y H:i', strtotime($prov->fechasinc))}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href=""http://{{$prov->web}}">
                        <div class="panel-footer" 
                            style="height: 260px; color: #000000; border-radius: 0 0 15px 15px;" >
                            <div> <cener> <b> {{ strtoupper($prov->nombre) }} </b> </cener> </div>
                            <div>DIRECCION: <b> {{ strtoupper($prov->direccion) }} </b> </div>
                            <div>LOCALIDAD: <b> {{ strtoupper($prov->localidad) }} </b> </div>
                            <div>CONTACTO: <b> {{ strtoupper($prov->contacto) }} </b> </div>
                            <div>TELEFONO: <b> {{$prov->telefono}} </b> </div>
                            <div>CORREO: <b> {{$prov->correo}} </b> </div>
                            <div>WEB: 
                                <a href="http://{{$prov->web}}"> <b>{{$prov->web}}</b> </a>      
    			            </div> 

			            	{{Form::open(array('action' => array('ProveedorController@agregar', $prov->codprove)))}}
			            	<button style="margin-top: 10px; border-radius: 5px;" 
                                type="submit" 
                                class="btn-normal" 
                                data-dismiss="modal" 
                                data-toggle="tooltip" 
                                title="Agregar a mi lista de proveedores">
                                Agregar
			            	</button>
							{{Form::Close()}}
			
                        </div>
                    </a>
                </div>
            </div>
            @endif
        @endforeach

    </div>

</div>


@push ('scripts')
<script>
$('#subtitulo').text('{{$subtitulo}}');
</script>
@endpush

@endsection