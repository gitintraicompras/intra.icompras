@extends ('layouts.menu')
@section ('contenido')

@php
$codprove = mb_strtoupper($arrayMejorRnk1[0]['codprove']);
$maeprovecont = count($arrayMejorRnk1);
$maeproveRnk = DB::table('maeprove')
->where('codprove','=',$codprove)
->first();

$codprove = mb_strtoupper($arrayMejorInv[0]['codprove']);
$maeproveMejorInv = DB::table('maeprove')
->where('codprove','=',$codprove)
->first();

$codprove = mb_strtoupper($arrayMayorVar[0]['codprove']);
$maeproveMayorVar = DB::table('maeprove')
->where('codprove','=',$codprove)
->first();

@endphp

<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="table-responsive">
      <table id="idTable" class="table table-striped table-bordered table-condensed table-hover">
        <thead class="colorTitulo">
          <th>#</th>
          <th style="width: 60px;" class="hidden-xs">IMAGEN</th>
          <th>CODIGO</th>
          <th>DESCRIPCION</th>
          <th>CATEGORIA</th>
          <th>VALOR</th>
        </thead>

        <tr>
          <td style="background-color: {{$maeproveRnk->backcolor}}; 
            color: {{$maeproveRnk->forecolor}}; ">
            1
          </td>
          <td class="hidden-xs">
              <div align="center">
                <img src="http://isaweb.isbsistemas.com/public/storage/prov/{{$maeproveRnk->rutalogo1}}" 
                class="img-responsive" 
                alt="icompras360" 
                style="width: 100px; border: 2px solid #D2D6DE;"
                oncontextmenu="return false">
              </div>
          </td>
          <td>{{$maeproveRnk->codprove}}</td>
          <td>{{$maeproveRnk->descripcion}}</td>
          <td>MEJOR PRECIO (1-{{$maeprovecont}})</td>
          <td>
            {{number_format($arrayMejorRnk1[0]['contador'], 0, '.', ',')}} productos con precio RNK-1
          </td>
        </tr>

        <tr>
          <td style="background-color: {{$maeproveMejorInv->backcolor}}; 
            color: {{$maeproveMejorInv->forecolor}}; ">
            2
          </td>
          <td class="hidden-xs">
              <div align="center">
                <img src="http://isaweb.isbsistemas.com/public/storage/prov/{{$maeproveMejorInv->rutalogo1}}" 
                class="img-responsive" 
                alt="icompras360"
                style="width: 100px; border: 2px solid #D2D6DE;"
                oncontextmenu="return false">
              </div>
          </td>
          <td>{{$maeproveMejorInv->codprove}}</td>
          <td>{{$maeproveMejorInv->descripcion}}</td>
          <td>MEJOR INVENTARIO (1-{{$maeprovecont}})</td>
          <td>
            {{number_format($arrayMejorInv[0]['contador'], 0, '.', ',')}} unidades de productos 
          </td>
        </tr>

        <tr>
          <td style="background-color: {{$maeproveMayorVar->backcolor}}; 
            color: {{$maeproveMayorVar->forecolor}}; ">
            3
          </td>
          <td class="hidden-xs">
              <div align="center">
                <img src="http://isaweb.isbsistemas.com/public/storage/prov/{{$maeproveMayorVar->rutalogo1}}" 
                class="img-responsive" 
                alt="icompras360"
                style="width: 100px; border: 2px solid #D2D6DE;"
                oncontextmenu="return false">
              </div>
          </td>
          <td>{{$maeproveMayorVar->codprove}}</td>
          <td>{{$maeproveMayorVar->descripcion}}</td>
          <td>MAYOR VARIEDAD DE PRODUCTOS (1-{{$maeprovecont}})</td>
          <td>
            {{number_format($arrayMayorVar[0]['contador'], 0, '.', ',')}} renglones de productos 
          </td>
        </tr>

      </table>
    </div>
  </div>
  <!-- BOTON REGRESAR -->
  <div class="form-group" style="margin-top: 20px; margin-left: 15px;">
      <button type="button" class="btn-normal" onclick="history.back(-1)">Regresar</button>
  </div>
</div>

@push ('scripts')
<script>
$('#subtitulo').text('{{$subtitulo}}');
</script>
@endpush
@endsection