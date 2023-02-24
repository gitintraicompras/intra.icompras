@extends ('layouts.menu')
@section ('contenido')

<head>
	<style type="text/css"> 

	#header-fixed { 
	    position: fixed; 
	    top: 70px; 
	    display: none;
	    background-color:white;
	}
	
	#tr-3 { 
	    position: fixed; 
	    top: 70px; 
	    display: none;
	}

	#tr-4  { 
	    position: fixed; 
	    top: 90px; 
	    display: none;
	}

    </style>
</head>


<section class="content" >
<h1>Fixed Table Header Demo: Page Level</h1>

<h2>Column Headers</h2>


<div id="table-container">
  <table id="table-1" style="background-color: pink;">
    <thead class="table">
      <tr style="background-color: blue;" >
        <th id="col1-1" style="width: 300px;">colunn 1-1</th>
        <th id="col1-2" style="width: 250px;">colunn 1-2</th>
        <th id="col1-3" style="width: 100px;">colunn 1-3</th>
        <th id="col1-4" style="width: 200px;">colunn 1-4</th>
      </tr>
      <tr style="background-color: blue;">
        <th id="col2-1" style="width: 300px;">colunn 2-1</th>
        <th id="col2-2" style="width: 250px;">colunn 2-2</th>
        <th id="col2-3" style="width: 100px;">colunn 2-3</th>
        <th id="col2-4" style="width: 200px;">colunn 2-4</th>
      </tr>

      <tr id="tr-3" style="background-color: red;" >
        <th id="col3-1">colunn 3-1</th>
        <th id="col3-2">colunn 3-2</th>
        <th id="col3-3">colunn 3-3</th>
        <th id="col3-4">colunn 3-4</th>
      </tr>
      <tr id="tr-4" style="background-color: red;">
        <th id="col4-1">colunn 4-1</th>
        <th id="col4-2">colunn 4-2</th>
        <th id="col4-3">colunn 4-3</th>
        <th id="col4-4">colunn 4-4</th>
      </tr>

    </thead>
    <tbody>
      <tr>
        <td>Example 1A</td>
        <td>Example 2</td>
        <td>Example 3</td>
        <td>Example 4</td>
       </tr>
       <tr>
        <td>Example 1B</td>
        <td>Example 2</td>
        <td>Example 3</td>
        <td>Example 4</td>
       </tr>
       <tr>
        <td>Example 1C</td>
        <td>Example 2</td>
        <td>Example 3</td>
        <td>Example 4</td>
       </tr>
       <tr>
        <td>Example 1D</td>
        <td>Example 2</td>
        <td>Example 3</td>
        <td>Example 4</td>
       </tr>
       <tr>
        <td>Example 1E</td>
        <td>Example 2</td>
        <td>Example 3</td>
        <td>Example 4</td>
       </tr>
       <tr>
        <td>Example 1F</td>
        <td>Example 2</td>
        <td>Example 3</td>
        <td>Example 4</td>
       </tr>
       <tr>
        <td>Example 1</td>
        <td>Example 2</td>
        <td>Example 3</td>
        <td>Example 4</td>
       </tr>
       <tr>
        <td>Example 1</td>
        <td>Example 2</td>
        <td>Example 3</td>
        <td>Example 4</td>
       </tr>
       <tr>
        <td>Example 1</td>
        <td>Example 2</td>
        <td>Example 3</td>
        <td>Example 4</td>
       </tr>
       <tr>
        <td>Example 1</td>
        <td>Example 2</td>
        <td>Example 3</td>
        <td>Example 4</td>
       </tr>
       <tr>
        <td>Example 1</td>
        <td>Example 2</td>
        <td>Example 3</td>
        <td>Example 4</td>
       </tr>
       <tr>
        <td>Example 1</td>
        <td>Example 2</td>
        <td>Example 3</td>
        <td>Example 4</td>
       </tr>
       <tr>
        <td>Example 1</td>
        <td>Example 2</td>
        <td>Example 3</td>
        <td>Example 4</td>
       </tr>
       <tr>
        <td>Example 1</td>
        <td>Example 2</td>
        <td>Example 3</td>
        <td>Example 4</td>
       </tr>
       <tr>
        <td>Example 1</td>
        <td>Example 2</td>
        <td>Example 3</td>
        <td>Example 4</td>
       </tr>
       <tr>
        <td>Example 1</td>
        <td>Example 2</td>
        <td>Example 3</td>
        <td>Example 4</td>
       </tr>
       <tr>
        <td>Example 1</td>
        <td>Example 2</td>
        <td>Example 3</td>
        <td>Example 4</td>
       </tr>
       <tr>
        <td>Example 1</td>
        <td>Example 2</td>
        <td>Example 3</td>
        <td>Example 4</td>
       </tr>
       <tr>
        <td>Example 1</td>
        <td>Example 2</td>
        <td>Example 3</td>
        <td>Example 4</td>
       </tr>
       <tr>
        <td>Example 1</td>
        <td>Example 2</td>
        <td>Example 3</td>
        <td>Example 4</td>
       </tr>
       <tr>
        <td>Example 1</td>
        <td>Example 2</td>
        <td>Example 3</td>
        <td>Example 4</td>
       </tr>
       <tr>
        <td>Example 1</td>
        <td>Example 2</td>
        <td>Example 3</td>
        <td>Example 4</td>
       </tr>
       <tr>
        <td>Example 1</td>
        <td>Example 2</td>
        <td>Example 3</td>
        <td>Example 4</td>
       </tr>
       <tr>
        <td>Example 1</td>
        <td>Example 2</td>
        <td>Example 3</td>
        <td>Example 4</td>
       </tr>
       <tr>
        <td>Example 1</td>
        <td>Example 2</td>
        <td>Example 3</td>
        <td>Example 4</td>
       </tr>
       <tr>
        <td>Example 1</td>
        <td>Example 2</td>
        <td>Example 3</td>
        <td>Example 4</td>
       </tr>
       <tr>
        <td>Example 1</td>
        <td>Example 2</td>
        <td>Example 3</td>
        <td>Example 4</td>
       </tr>
       <tr>
        <td>Example 1</td>
        <td>Example 2</td>
        <td>Example 3</td>
        <td>Example 4</td>
       </tr>
       <tr>
        <td>Example 1</td>
        <td>Example 2</td>
        <td>Example 3</td>
        <td>Example 4</td>
       </tr>
       <tr>
        <td>Example 1</td>
        <td>Example 2</td>
        <td>Example 3</td>
        <td>Example 4</td>
       </tr>
       <tr>
        <td>Example 1</td>
        <td>Example 2</td>
        <td>Example 3</td>
        <td>Example 4</td>
       </tr>
       <tr>
        <td>Example 1</td>
        <td>Example 2</td>
        <td>Example 3</td>
        <td>Example 4</td>
       </tr>
       <tr>
        <td>Example 1</td>
        <td>Example 2</td>
        <td>Example 3</td>
        <td>Example 4</td>
       </tr>
       <tr>
        <td>Example 1</td>
        <td>Example 2</td>
        <td>Example 3</td>
        <td>Example 4</td>
       </tr>
       <tr>
        <td>Example 1</td>
        <td>Example 2</td>
        <td>Example 3</td>
        <td>Example 4</td>
       </tr>
    </tbody>
  </table>
  <table class="table" id="header-fixed"></table>
</div>

<!-- BOTON REGRESAR -->
<div class="form-group" style="margin-top: 20px; margin-left: 15px;">
    <button type="button" class="btn-normal" onclick="history.back(-1)">Regresar</button>
</div>

</section>

@push ('scripts')
<script>
$('#subtitulo').text('{{$subtitulo}}');

var tableOffset = $("#table-1").offset().top;
var $header = $("#table-1 > thead");
var $fixedHeader = $("#header-fixed").append($header.clone());
$(window).bind("scroll", function() {
    var offset = $(this).scrollTop();
    if (offset >= tableOffset && $fixedHeader.is(":hidden")) {
   
        document.getElementById("col3-1").style.width = document.getElementById("col1-1").style.width;
        document.getElementById("col3-2").style.width = document.getElementById("col1-2").style.width;
        document.getElementById("col3-3").style.width = document.getElementById("col1-3").style.width;
        document.getElementById("col3-4").style.width = document.getElementById("col1-4").style.width;
		document.getElementById("tr-3").style.display = 'block';

		document.getElementById("col4-1").style.width = document.getElementById("col2-1").style.width;
        document.getElementById("col4-2").style.width = document.getElementById("col2-2").style.width;
        document.getElementById("col4-3").style.width = document.getElementById("col2-3").style.width;
        document.getElementById("col4-4").style.width = document.getElementById("col2-4").style.width;
		document.getElementById("tr-4").style.display = 'block';

    }
    else if (offset < tableOffset) {
       	document.getElementById("tr-3").style.display = 'none';
       	document.getElementById("tr-4").style.display = 'none';
    }
});

</script>
@endpush
@endsection