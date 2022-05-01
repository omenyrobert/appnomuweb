@extends('layouts.auth')
@section('content')

    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-8 ml-auto mr-auto">
            <form class="form" method="POST" action="/new_pass">
                @csrf
                <div class="card card-login card-hidden">
                <div class="card-header card-header-rose text-center">
                    <h4 class="card-title">Reset Password</h4>
                </div>
                <div class="card-body ">
                    <span class="bmd-form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="material-icons">pin</i>
                            </span>
                            </div>
                            <input type="password" name="code" class="form-control" placeholder="Enter Authentication Code..">
                        </div>
                    </span>
                    <span class="bmd-form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="material-icons">password</i>
                            </span>
                            </div>
                            <input type="password" name="new_password" class="form-control" placeholder="Enter New Password..">
                        </div>
                    </span>
                    <span class="bmd-form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="material-icons">lock</i>
                            </span>
                            </div>
                            <input type="password" name="repeat_password" class="form-control" placeholder="Repeat Password..">
                        </div>
                    </span>
                </div>
                <div class="card-footer justify-content-center">
                    <button type="submit" class="btn btn-warning btn-link btn-lg">Reset Your Password</a>
                </div>
                </div>
            </form>
            </div>
        </div>
    </div>

@endsection