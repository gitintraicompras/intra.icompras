<?php
  $cfg = DB::table('maecfg')->first();
?>

<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="<?php echo e(asset('images/favicon.ico')); ?>">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title>ICOMPRAS - Plataforma web de gestion de compras entre empresas</title>

    <!-- Styles -->
    <link href="<?php echo e(asset('css/app.css')); ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('css/style.css')); ?>"> 
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    
                    <a class="navbar-brand" href="<?php echo e(url('/')); ?>">
                        <span>
                            <img src="<?php echo e(asset('images/favicon.ico')); ?>" alt="seped" style="width:25px; height: 25px;">
                        </span>
                        <?php echo e(config('app.name', 'ICOMPRAS')); ?>

                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        <?php if(Auth::guest()): ?>
                            <li><a href="https://www.icompras360.com">Regresar</a></li>
                        <?php else: ?>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    <?php echo e(Auth::user()->name); ?> <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="<?php echo e(route('logout')); ?>"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Cerrar session
                                        </a>

                                        <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                                            <?php echo e(csrf_field()); ?>

                                        </form>
                                    </li>
                                </ul>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>

        <section class="content">
            <?php if(Session::has('message')): ?>
            <div class="alert alert-info alert-dismissable" role="alert">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <strong> <?php echo Session::get("message"); ?> </strong>
            </div>
            <?php endif; ?>

            <?php if(Session::has('error')): ?>
            <div class="alert alert-warning alert-dismissable" role="alert">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <strong> <?php echo Session::get("error"); ?> </strong>
            </div>
            <?php endif; ?>

            <?php echo $__env->yieldContent('content'); ?>

        </section>
        
    </div>

    <!-- Scripts -->
    <script src="<?php echo e(asset('js/app.js')); ?>"></script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/layouts/app.blade.php ENDPATH**/ ?>