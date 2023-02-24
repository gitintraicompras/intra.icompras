<head>
    <style>
    @page  {
        margin:2px; 
        padding:2px; 
    }
    span {
        vertical-align: middle;
    }
    table, th, td {
        border: 1px solid black;
    }
    h4, h5, h6 {
        margin: 0.5px;
        padding: 0.5px;
    }
    body {
        font-family: Times New Roman;
        font-size: 8px;
        border: 0;
        margin: 4px;
        padding: 4px;
    }
    </style>
</head>

<body>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <img src="http://isaweb.isbsistemas.com/public/storage/prov/icompras.png" class="img-responsive" alt="isacom" style="width: 150px; vertical-align: middle;">
    </div>

    <br>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <center><span style="font-size: 20px;"><b><?php echo e($marca); ?></b></span></center>
    </div>

  
 
    <h4 style="margin-top: 10px;">
        <center><?php echo e($cfg->nombre); ?> | RIF: <?php echo e($cfg->rif); ?></center>
    </h4>

    <h5>
        <center><?php echo e($cfg->direccion); ?></center>
    </h5>

    <h5>
        <center>TELEFONO: <?php echo e($cfg->telefono); ?> | CONTACTO: <?php echo e($cfg->contacto); ?></center>
    </h5>
    <h5 style="margin-top: 5px;">
        <center>Desarrollado por: ISB SISTEMAS, C.A.</center>
    </h5>
</body>
















<?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/layouts/rptpedidodirecto.blade.php ENDPATH**/ ?>