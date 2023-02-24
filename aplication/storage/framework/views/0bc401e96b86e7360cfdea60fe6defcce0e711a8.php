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

<div class="row">
    <div width="100%">
        <div width="30%" style="float: left; margin-top: 15px; margin-left: 15px;">
 
            <?php
            $logo = 'logoRpt'.$codprove.'.png';
            $logo = 'http://isaweb.isbsistemas.com/public/storage/'.$logo;
            ?>

            <img src="<?php echo e($logo); ?>" class="img-responsive" alt="isacom" style="width: 150px; vertical-align: middle;">
        
        </div> 
        <div width="70%">
            <CENTER><h2 style="margin-top: 5px;"><?php echo e($titulo); ?></h2></CENTER>
            <CENTER><h3 style="margin-top: 5px;"><?php echo e($subtitulo); ?></h3></CENTER>
        </div>    
    </div>
</div>
 
<body>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <div class="row" style="margin-top: 20px;">

                <table style="border: 0px; width: 100%;">
                    <tr style="border: 0px;">
                        <td align="right" style="border: 0px;" >EMISION:</td>
                        <td align="left" style="border: medium transparent" ><input type="text" value="<?php echo e(date('d-m-Y', strtotime($tabla->fecha))); ?>" /></td>

                        <td align="right" style="border: 0px;" >VENCE:</td>
                        <td align="left" style="border: medium transparent" ><input type="text" value="<?php echo e(date('d-m-Y', strtotime($tabla->fechav))); ?>" /></td>

                        <td align="right" style="border: 0px;">MONEDA:</td>
                        <td align="left" style="border: medium transparent;" ><input type="text" value="<?php echo e(isset($tabla->codmoneda)); ?>" /></td>

                        <td align="right" style="border: 0px;">VENDEDOR:</td>
                        <td align="left" style="border: medium transparent; width: 100%;" ><input type="text" value="<?php echo e(isset($tabla->codvend)); ?>"  /></td>
                    </tr>
                </table>

            </div>
        </div>
    </div>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <div class="row">

                <table style="border: 0px; width: 100%;">
                    <tr style="border: 0px;">
                   
                        <td align="right" style="border: 0px;">IMPUESTO:</td>
                        <td align="left" style="border: medium transparent" ><input type="text" value="<?php echo e(number_format($tabla->iva, 2, '.', ',')); ?>"  /></td>

                        <td align="right" style="border: 0px;" >GRAVABLE:</td>
                        <td align="left" style="border: medium transparent" ><input type="text" value="<?php echo e(number_format($tabla->gravable, 2, '.', ',')); ?>" /></td>

                        <td align="right" style="border: 0px;">DESCUENTO:</td>
                        <td align="left" style="border: medium transparent" ><input type="text" value="<?php echo e(number_format($tabla->descuento, 2, '.', ',')); ?>" /></td>

                        <td align="right" style="border: 0px;">TOTAL:</td>
                        <td align="left" style="border: medium transparent; ; width: 100%;" ><input type="text" value="<?php echo e(number_format($tabla->total, 2, '.', ',')); ?>"  /></td>
                    </tr>
                </table>

            </div>
        </div>
    </div>

    <table width="100%" border="1" cellpadding="4" cellspacing="0">   
        <tr style="background-color: #C3C3C3; color: #000000; height: 50px">
            <th align='left'  style='width: 2%;  '>#</th>
            <th align='left'  style='width: 38%; '>DESCRIPCION</th>
            <th align='left'  style='width: 10%; '>CODIGO</th>
            <th align='right' style='width: 10%; '>CANT</th>
            <th align='right' style='width: 10%; '>PRECIO</th>
            <th align='right' style='width: 15%; '>SUBTOTAL</th>
        </tr>
          
        <?php $__currentLoopData = $tabla2; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td><?php echo e($loop->iteration); ?></td>
            <td><?php echo e($t->desprod); ?></td>
            <td><?php echo e($t->codprod); ?></td>
            <td align="right"><?php echo e(number_format($t->cantidad, 0, '.', ',')); ?></td>
            <td align="right"><?php echo e(number_format($t->precio, 2, '.', ',')); ?></td>
            <td align="right"><?php echo e(number_format($t->subtotal, 2, '.', ',')); ?></td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    </table>

    <h4 style="margin-top: 10px;">
        <center><?php echo e($cfg->nombre); ?> | RIF: <?php echo e($cfg->rif); ?></center>
    </h4>

    <h5>
        <center><?php echo e($cfg->direccion); ?></center>
    </h5>

    <h5>
        <center>TELEFONO: <?php echo e($cfg->telefono); ?> | CONTACTO: <?php echo e($cfg->contacto); ?></center>
    </h5>
  
</body>

<?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/layouts/rptfactura.blade.php ENDPATH**/ ?>