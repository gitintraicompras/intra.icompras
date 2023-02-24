
<?php $__env->startSection('contenido'); ?>
<head>
	<link href="<?php echo e(asset('js/electro-master/slick.css')); ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo e(asset('js/electro-master/slick-theme.css')); ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo e(asset('js/electro-master/nouislider.min.css')); ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo e(asset('js/electro-master/style.css')); ?>" rel="stylesheet" type="text/css" />
</head> 
<div class="col-md-12">
</div>
<?php $__env->startPush('scripts'); ?>
<script type="text/javascript" src="<?php echo e(asset('js/electro-master/slick.min.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('js/electro-master/nouislider.min.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('js/electro-master/jquery.zoom.min.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('js/electro-master/main.js')); ?>"></script>
<script>
$('#subtitulo').text('<?php echo e($subtitulo); ?>');
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/canales/index.blade.php ENDPATH**/ ?>