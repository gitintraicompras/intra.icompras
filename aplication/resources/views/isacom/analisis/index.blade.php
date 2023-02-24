@extends('layouts.menu')
@section ('contenido')

<div class="row" style="margin-bottom: 5px;">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
		<a href="{{url('analisis/descargar/excel/'.$codcli)}}">
        <button style="width: 153px; height: 32px;"  
        	class="btn-normal" 
        	type="button" 
        	data-toggle="tooltip" 
        	title="Descargar analisis en excel">
            Descargar
        </button>
    </a>
	</div>
	@include('isacom.analisis.buscar')
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead class="colorTitulo" id="idtabla">
					<th colspan="10" >
              <center>
                  INVENTARIO DEL CLIENTE
              </center>
          </th>
          <th colspan="3" style="background-color: #FCD0C7; color: #000000;">
              <center>
                  PRECIO PROVEEDOR
              </center>
          </th>
				</thead>
				<thead class="colorTitulo" id="idtabla">
					<th>#</th>
					<th style="width: 90px; vertical-align:middle;">
              &nbsp;&nbsp;&nbsp;IMAGEN&nbsp;&nbsp;&nbsp;
          </th>
					<th>PRODUCTO</th>
					<th>
            REFERENCIAS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          </th>
					<th title="CANTIDAD">CANT</th>
					<th>COSTO</th>
					<th>UTIL</th>
					<th>PRECIO({{$mostrarprecio}})</th>
					<th style="background-color: #FCD0C7; color: #000000;">MENOR</th>
					<th style="background-color: #FCD0C7; color: #000000;">PROMEDIO</th>
					<th style="background-color: #FCD0C7; color: #000000;">MAYOR</th>
				</thead>
				@if (isset($invent))
  				@foreach ($invent as $t)
  				  @php
  					switch ($mostrarprecio) {
                  case 1:
                     $precio = $t->precio1;
                     break;
                  case 2:
                     $precio = $t->precio2;
                     break;
                  case 3:
                     $precio = $t->precio3;
                     break;
                  default:
                     $precio = $t->precio1;
                     break; 
            }
  					$menorpp = 0.00;
  					$mayorpp = 0.00;
  					$promepp = 0.00;
  					$util = 0;
  						if ($precio > 0) {
     						$util = -1*((( $t->costo / $precio )*100)-100);
  					}
  					$mejoropcion = BuscarMejorOpcion($t->barra, $criterio, $preferencia, $pedir, $provs);
  					if ($mejoropcion != null) {
  						$sumprecio = 0;
              $contprov = count($mejoropcion);
  					 	$codprove = $mejoropcion[0]['codprove'];
              $maeclieprove = DB::table('maeclieprove')
              ->where('codcli','=',$codcli)
              ->where('codprove','=',$codprove)
              ->first();
              $dc = $maeclieprove->dcme;
              $di = $maeclieprove->di;
              $pp = $maeclieprove->ppme;
              $da = $mejoropcion[0]['da'];
              $menorpp = CalculaPrecioNeto($mejoropcion[0]['precio'], $da, $di, $dc, $pp, 0.00);

              $codprove = $mejoropcion[$contprov-1]['codprove'];
              $maeclieprove = DB::table('maeclieprove')
              ->where('codcli','=',$codcli)
              ->where('codprove','=',$codprove)
              ->first();
              $dc = $maeclieprove->dcme;
              $di = $maeclieprove->di;
              $pp = $maeclieprove->ppme;
              $da = $mejoropcion[$contprov-1]['da'];
              $mayorpp = CalculaPrecioNeto($mejoropcion[$contprov-1]['precio'], $da, $di, $dc, $pp, 0.00);
              for ($x=0; $x < $contprov; $x++ ) {
                $codprove = $mejoropcion[$x]['codprove'];
                $maeclieprove = DB::table('maeclieprove')
                ->where('codcli','=',$codcli)
                ->where('codprove','=',$codprove)
                ->first();
                $dc = $maeclieprove->dcme;
                $di = $maeclieprove->di;
                $pp = $maeclieprove->ppme;
                $da = $mejoropcion[$x]['da'];
                $prec = CalculaPrecioNeto($mejoropcion[$x]['precio'], $da, $di, $dc, $pp, 0.00);
                $sumprecio += $prec;
              }
              $promepp = $sumprecio/$contprov;
         	  }
            $transito = verificarProdTransito($t->barra, $codcli, "");
            $minmax = LeerMinMax($codcli, $t->codprod);
            $min = $minmax["min"];
            $max = $minmax["max"];
            $cendis = $minmax["cendis"];
    				@endphp
    				<tr>
    					<td>{{$loop->iteration}}</td>
    					<td>
                  <div align="center">
                    <a href="{{URL::action('PedidoController@verprod',$t->barra)}}">
            
                        <img src="http://isaweb.isbsistemas.com/public/storage/prod/{{NombreImagen($t->barra)}}" 
                        width="100%" 
                        height="100%" 
                        class="img-responsive" 
                        alt="icompras360"
                        style="border: 2px solid #D2D6DE;" 
                        oncontextmenu="return false">
            
                    </a>
                  </div>
              </td>
              <td>
                <b>{{$t->desprod}}</b><br>
                <span>
                  CANT: {{number_format($t->cantidad, 0, '.', ',')}}&nbsp;&nbsp;&nbsp;
                  TRAN: {{number_format($transito, 0, '.', ',')}}&nbsp;&nbsp;&nbsp;
                  VMD: {{number_format($t->vmd, 4, '.', ',')}}
                </span><br>
                <span>
                  MIN: {{number_format($min, 0, '.', ',')}}&nbsp;&nbsp;&nbsp;
                  MAX: {{number_format($max, 0, '.', ',')}}&nbsp;&nbsp;&nbsp;
                  @if ($cendis >0)
                    <i class="fa fa-check-square-o" aria-hidden="true"></i>
                    &nbsp;CENDIS
                  @endif
                </span>
              </td>
            	<td>
                <span title="CODIGO PRODUCTO">
                  <i class="fa fa-cube">&nbsp;{{$t->codprod}}</i>
                </span><br>
                @if (!empty($t->barra))
                  <span title="CODIGO BARRA">
                    <i class="fa fa-barcode">&nbsp;{{$t->barra}}</i>
                  </span><br>
                @endif
                <span title="MARCA DEL PRODUCTO">
                  <i class="fa fa-shield">&nbsp;{{$t->marca}}</i>
                </span><br>
              </td>
           		<td align="right">{{number_format($t->cantidad, 0, '.', ',')}}</td>
    					<td align="right">{{number_format($t->costo/$factor, 2, '.', ',')}}</td>
    					<td align="right">{{number_format($util, 2, '.', ',')}}%</td>
    					<td align="right">{{number_format($precio/$factor, 2, '.', ',')}}</td>
    					<td align="right"
    						style="background-color: #FCD0C7; color: #000000;">
    						{{number_format($menorpp/$factor, 2, '.', ',')}}
    					</td>
    					<td align="right"
    						style="background-color: #FCD0C7; color: #000000;">
    						{{number_format($promepp/$factor, 2, '.', ',')}}
    					</td>
    					<td align="right"
    						style="background-color: #FCD0C7; color: #000000;">
    						{{number_format($mayorpp/$factor, 2, '.', ',')}}
    					</td>
    				</tr>
  				@endforeach
				@endif
			</table>
			<div align='right'>
				@if (isset($invent))
        	{{$invent->render()}}
        @endif
      </div><br>
		</div>
	</div>
</div>

@push ('scripts')
<script>
$('#subtitulo').text('{{$subtitulo}}');
</script>
@endpush

@endsection