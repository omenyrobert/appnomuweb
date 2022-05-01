@extends('layouts.auth')
@section('content')

    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-8 ml-auto mr-auto">
            <form class="form" method="post" action="/loginty">
                @csrf
                <div class="card card-login card-hidden">
                <div class="card-header card-header-rose text-center">
                    <h4 class="card-title">Login With</h4>
                    <div class="social-line">
                    <a href="#pablo" class="btn btn-just-icon btn-link btn-white">
                        <i class="fa fa-facebook-square"></i>
                    </a>
                    <a href="#pablo" class="btn btn-just-icon btn-link btn-white">
                        <i class="fa fa-twitter"></i>
                    </a>
                    <a href="#pablo" class="btn btn-just-icon btn-link btn-white">
                        <i class="fa fa-google-plus"></i>
                    </a>
                    </div>
                </div>
                <div class="card-body ">
                    <center>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <p class="card-description text-center" style="color:white !important;">{{ $error }}</p>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </center>
                    <p class="card-description text-center">Or Login With Your Email And Password</p>
                    <span class="bmd-form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="material-icons">email</i>
                            </span>
                            </div>
                            <input type="email" name="email" class="form-control" placeholder="Email...">
                        </div>
                    </span>
                    <span class="bmd-form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="material-icons">lock_outline</i>
                            </span>
                            </div>
                            <input type="password" name="password" class="form-control" placeholder="Password...">
                        </div>
                    </span>
                </div>
                <div class="card-footer justify-content-center">
                    <button type="submit" class="btn btn-warning  btn-lg">Login Now</button>
                    <a href="/forgot" class="btn btn-primary btn-link btn-lg">forgot Password ?</a>
                </div>
                </div>
            </form>
            </div>
        </div>
    </div>

@endsection