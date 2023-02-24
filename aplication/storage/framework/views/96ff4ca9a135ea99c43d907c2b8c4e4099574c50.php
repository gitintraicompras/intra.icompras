<div class="container">

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

    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default" style="border-radius: 15px;" >
                <div class="panel-heading colorTitulo" 
                    style="color: #ffffff; border-radius: 15px 15px 0px 0px;">
                    <span>
                        <img src="<?php echo e(asset('images/userCliente.png')); ?>" alt="seped" style="width:20px; height: 20px;">
                    </span>
                    Inicio de Sesión
                </div>
                
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="<?php echo e(route('login')); ?>">
                        <?php echo e(csrf_field()); ?>

                        <div class="col-md-12" style="width: 100%;">
                            <div class="pull-left image" style="width: 50%;">
                                <center>
                                    <img src="<?php echo e(asset('images/favicon3.png')); ?>" 
                                        alt="seped" >
                                </center>
                            </div>
                    
                            <div class="pull-right info" style="width: 50%;">
                                <div class="col-md-4" style="width: 100%;">
                     
                                    <div class="form-group<?php echo e($errors->has('email') ? ' has-error' : ''); ?>">
                                        <label for="email" class="control-label">Usuario:</label>
                                        <input id="email" type="email" class="form-control" name="email" value="<?php echo e(old('email')); ?>" >

                                        <?php if($errors->has('email')): ?>
                                            <span class="help-block">
                                                <strong><?php echo e($errors->first('email')); ?></strong>
                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <div class="form-group<?php echo e($errors->has('password') ? ' has-error' : ''); ?>">
                                        <label for="password" class="control-label">Clave:</label>
                                        <input id="password" type="password" class="form-control" name="password" required>
                                        <?php if($errors->has('password')): ?>
                                            <span class="help-block">
                                                <strong><?php echo e($errors->first('password')); ?></strong>
                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn-normal">
                                            Ingresar
                                        </button>
                                    </div>

                                </div>
                    
                            </div>
                        </div>
            
                    </form>
                </div>
      
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\intra.icompras\aplication\resources\views/layouts/login.blade.php ENDPATH**/ ?>