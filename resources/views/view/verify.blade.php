@extends('layouts.auth')
@section('content')

    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-8 ml-auto mr-auto">
                <div class="card card-login card-hidden">
                <div class="card-header card-header-rose text-center">
                    <h4 class="card-title">Verify Email</h4>
                    
                </div>
                <div class="card-body ">
                    <p class="card-description text-center">We have  Sent an email To your email  addess,please login to your email provider to confirm the email belongs to you .</p>
                    <p class="card-description text-center">If you have not recieved  such email, please resend the email or register again with a working email. You can as well contact us  on help@appnomu.net for Emergence assistance</p>
                    
                </div>
                <div class="card-footer justify-content-center">
                    <a href="" class="btn btn-warning btn-link btn-lg">Resend Email</a>
                </div>
                </div>
            </div>
        </div>
    </div>

@endsection