<div class="container">

    @if (Session::has('message'))
    <div class="alert alert-info alert-dismissable" role="alert">
      <button type="button" class="close" data-dismiss="alert">&times;</button>
      <strong> {!! Session::get("message") !!} </strong>
    </div>
    @endif

    @if (Session::has('error'))
    <div class="alert alert-warning alert-dismissable" role="alert">
      <button type="button" class="close" data-dismiss="alert">&times;</button>
      <strong> {!! Session::get("error") !!} </strong>
    </div>
    @endif

    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default" style="border-radius: 15px;" >
                <div class="panel-heading colorTitulo" 
                    style="color: #ffffff; border-radius: 15px 15px 0px 0px;">
                    <span>
                        <img src="{{asset('images/userCliente.png')}}" alt="seped" style="width:20px; height: 20px;">
                    </span>
                    Inicio de Sesión
                </div>
                
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}
                        <div class="col-md-12" style="width: 100%;">
                            <div class="pull-left image" style="width: 50%;">
                                <center>
                                    <img src="{{asset('images/favicon3.png')}}" 
                                        alt="seped" >
                                </center>
                            </div>
                    
                            <div class="pull-right info" style="width: 50%;">
                                <div class="col-md-4" style="width: 100%;">
                     
                                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                        <label for="email" class="control-label">Usuario:</label>
                                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" >

                                        @if ($errors->has('email'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                        <label for="password" class="control-label">Clave:</label>
                                        <input id="password" type="password" class="form-control" name="password" required>
                                        @if ($errors->has('password'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                        @endif
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
