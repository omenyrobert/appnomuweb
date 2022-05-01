<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-213861824-1">
</script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-213861824-1');
</script>@extends('layouts.website')
@section('content')
<div class="slider-area slider-bg1 hero-overly">
    <div class="slider-active">
        <div class="single-slider d-flex align-items-center slider-height">
            <div class="container">
                <div class="row align-items-center justify-content-between">
                    <div class="offset-xl-1 col-xxl-4 col-xl-5 col-lg-6 col-md-6">
                        <div class="hero__caption">
                            <h1 data-animation="fadeInLeft" data-delay=".10s "> Get  Loans From UGX.15,000 to UGX. 15,000,000</h1>
                            <p data-animation="fadeInLeft" data-delay=".8s"> Instantly On Your Smartphone</p>

                            <div class="slider-btns">
                                <a data-animation="fadeInLeft" data-delay="1s" href="/services" class="btn_02 hero-btn">Our Services</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-6 col-sm-10">
                        <div class="form-wrapper" data-animation="bounceIn" data-delay="0.2s">
                            <div class="form-tittle text-center">
                                <h2>SignIn</h2>
                                <p>SignIn Now To Get Yourself A Loan</p>
                            </div>
                            <center>
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <p class="card-description text-center" style="color:orange !important;">{{ $error }}</p>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            </center>
                            <form id="contact-form" action="/loginty" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="single-form">
                                            <div class=" mb-15">
                                                <label>Enter Your Email Here</label>
                                                <input type="email" placeholder="Enter Your Email Here" name="email" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="single-form">
                                            <div class=" mb-15">
                                                <label>Enter Your Password Here</label>
                                                <input type="password" placeholder="***************" name="password">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="submit-info text-center">
                                            <button class="submit-btn2 mb-15" type="submit">Apply Now</button>
                                            <p>Are you new here ? <a href="/register" style="color:red !important;">Create An Account Now</a></p>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- How To Proceed -->
<section class="our-services section-padding">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-7 col-lg-8 col-md-11">
                <div class="section-tittle text-center mb-70">
                    <h2>How Does It Work?</h2>
                    <p>
                        In a few steps, you could get your self a quick instant loan on your smartphone,Desktop/laptop and Internet connected devices
                    </p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-6 col-sm-6">
                <div class="single-services mb-30">
                    <div class="services-icon">
                        <img src="assets/img/6.jpeg" style="width:100px;" alt="" />
                    </div>
                    <div class="services-cap">
                    <span>Step One</span>
                        <h5><a href="/register">Registration</a></h5>
                        <p>Register with us  your account, verify your particulars, add alliances or guarantors and our compliance team will validate your account upon receiving your particulars with in 24 to 72 hours.</p>
                    </div>
                </div>
            </div>
            <div class="offset-xxl-1 col-xxl-3 col-xl-3 col-lg-3 col-md-6 col-sm-6">
                <div class="single-services mb-30">
                    <div class="services-icon">
                        <img src="assets/img/7.jpeg" style="width:100px;" alt="" />
                    </div>
                    <div class="services-cap">
                        <span>Step Two</span>
                        <h5><a href="#">Request A Loan</a></h5>
                        <p>Request a loan from the least amount (UGX 10,000) and your loan will be processed in less than 30 minutes.</br><strong>Note:</strong> loans are processed from 9:00 AM to 5:30 PM from Monday to Friday and will be sent to your registered mobile number  with us </p>
                    </div>
                </div>
            </div>
            <div class="offset-xxl-1 col-xxl-3 col-xl-3 col-lg-3 col-md-6 col-sm-6">
                <div class="single-services mb-30">
                    <div class="services-icon">
                        <img src="assets/img/2.jpeg" style="width:100px;" alt="" />
                    </div>
                    <div class="services-cap">
                        <span>Step Three</span>
                        <h5><a href="#">Make Withdraw</a></h5>
                        <p>Once your loan has been successfully approved, a loan number shall be generated and such a loan amount shall be sent on your mobile number that you registered with us on the platform and will be available on your mobile money for consumption.</p>
                    </div>
                </div>
            </div>
            <div class="offset-xxl-1 col-xxl-3 col-xl-3 col-lg-3 col-md-6 col-sm-6">
                <div class="single-services mb-30">
                    <div class="services-icon">
                        <img src="assets/img/logo4.png" style="width:100px;" alt="" />
                    </div>
                    <div class="services-cap">
                        <span>Step Three</span>
                        <h5><a href="#">RePay Back Loan </a></h5>
                        <p>RePay back your loan anytime to qualify for another loan by locating repay your loan.
                            You can pay back using Mobile money,Card payment,Paypal and savings/dashboard  and you will be growing your loan history.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


  <!-- Testimonials -->

<div class="testimonial-area">
    <div class="container">
        <div class="testimonial-wrapper">
            <div class="row justify-content-center">
                <div class="col-xl-8 col-lg-10">
                    <div class="h1-testimonial-active dot-style">
                        <?php
                            $testimonials = $auth::getTestimonials();
                            foreach ($testimonials as $key) {
                                # code...
                                if ($key['status']=='feature') {
                                    # code...
                                
                                $user = $auth::getUserById($key['user_id']);
                                $user_image = $auth::getIdentifications($key['user_id']);
                        ?>
                        <div class="single-testimonial position-relative">
                            <div class="testimonial-caption">
                                <img src="assets/img/icon/quotes-sign.png" alt="" class="quotes-sign" />
                                <p>
                                    "{{$key['text']}}"
                                </p>
                            </div>
                            <!-- <img class="img" src="{{$user_idnt['passport'] ?? '/assets/img/default-avatar.png'}}" /> -->
                            <div class="testimonial-founder d-flex align-items-center">
                                <div class="founder-img">
                                    <img src="{{ $user_image['passport'] ?? 'assets/img/icon/testimonial.png'}}" alt="" style="width: 100px; border-radius: 50%;" />
                                </div>
                                <div class="founder-text">
                                    <span>{{$user['name']}}</span>
                                    <p>{{$user['role'] ?? ' '}}</p>
                                </div>
                            </div>
                        </div>
                        <?php
                            }
                        }
                        ?>

                        <div class="single-testimonial position-relative">
                            <div class="testimonial-caption">
                                <img src="assets/img/icon/quotes-sign.png" alt="" class="quotes-sign" />
                                <p>
                                    "At APPNOMU, we love to develop together, we have so far given more than UGX350 millions since the start and we look forward to do better than that, we are helping thousand of the youths and businesses to develop."
                                </p>
                            </div>

                            <div class="testimonial-founder d-flex align-items-center">
                                <div class="founder-img">
                                    
                                <div class="founder-text">
                                    <span>LOAN CREDIT OFFICER</span>
                                    <p>VIVIANS</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </div>
</div>
  <!-- Testimonials -->

<div class="testimonial-area">
    <div class="container">
        <div class="testimonial-wrapper">
            <div class="row justify-content-center">
                <div class="col-xl-8 col-lg-10">
                    <div class="h1-testimonial-active dot-style">
                        <?php
                            $testimonials = $auth::getTestimonials();
                            foreach ($testimonials as $key) {
                                # code...
                                if ($key['status']=='feature') {
                                    # code...
                                
                                $user = $auth::getUserById($key['user_id']);
                                $user_image = $auth::getIdentifications($key['user_id']);
                        ?>
                        <div class="single-testimonial position-relative">
                            <div class="testimonial-caption">
                                <img src="assets/img/icon/quotes-sign.png" alt="" class="quotes-sign" />
                                <p>
                                    "{{$key['text']}}"
                                </p>
                            </div>
                            <!-- <img class="img" src="{{$user_idnt['passport'] ?? '/assets/img/default-avatar.png'}}" /> -->
                            <div class="testimonial-founder d-flex align-items-center">
                                <div class="founder-img">
                                    <img src="{{ $user_image['passport'] ?? 'assets/img/icon/testimonial.png'}}" alt="" style="width: 100px; border-radius: 50%;" />
                                </div>
                                <div class="founder-text">
                                    <span>{{$user['name']}}</span>
                                    <p>{{$user['role'] ?? ' '}}</p>
                                </div>
                            </div>
                        </div>
                        <?php
                            }
                        }
                        ?>

                        <div class="single-testimonial position-relative">
                            <div class="testimonial-caption">
                                <img src="assets/img/icon/quotes-sign.png" alt="" class="quotes-sign" />
                                <p>
                                    "Appnomu Has Been develeped with you the user in mind and to the best of our understanding, your money is very secure and safe and you can now begin getting loans as well as you save with us.The website currently receive 184 Million Monthly page views and over 20K weekly unique visitors of the website and we are capeable of sending bandwidth to 100 times the number current."
                                </p>
                            </div>

                            <div class="testimonial-founder d-flex align-items-center">
                                <div class="founder-img">
                                    
                                <div class="founder-text">
                                    <span>DEV.APP</span>
                                    <p>Developer Team</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
  <!-- Testimonials -->

<div class="testimonial-area">
    <div class="container">
        <div class="testimonial-wrapper">
            <div class="row justify-content-center">
                <div class="col-xl-8 col-lg-10">
                    <div class="h1-testimonial-active dot-style">
                        <?php
                            $testimonials = $auth::getTestimonials();
                            foreach ($testimonials as $key) {
                                # code...
                                if ($key['status']=='feature') {
                                    # code...
                                
                                $user = $auth::getUserById($key['user_id']);
                                $user_image = $auth::getIdentifications($key['user_id']);
                        ?>
                        <div class="single-testimonial position-relative">
                            <div class="testimonial-caption">
                                <img src="assets/img/icon/quotes-sign.png" alt="" class="quotes-sign" />
                                <p>
                                    "{{$key['text']}}"
                                </p>
                            </div>
                            <!-- <img class="img" src="{{$user_idnt['passport'] ?? '/assets/img/default-avatar.png'}}" /> -->
                            <div class="testimonial-founder d-flex align-items-center">
                                <div class="founder-img">
                                    <img src="{{ $user_image['passport'] ?? 'assets/img/icon/testimonial.png'}}" alt="" style="width: 100px; border-radius: 50%;" />
                                </div>
                                <div class="founder-text">
                                    <span>{{$user['name']}}</span>
                                    <p>{{$user['role'] ?? ' '}}</p>
                                </div>
                            </div>
                        </div>
                        <?php
                            }
                        }
                        ?>

                        <div class="single-testimonial position-relative">
                            <div class="testimonial-caption">
                                <img src="assets/img/icon/quotes-sign.png" alt="" class="quotes-sign" />
                                <p>
                                    "I love the consistency of savings and the stable network, i do not need to have to go in the banks to collect my money or savings, i use APPNOMU for my savings and my money is kept secure and with high securiyt.."
                                </p>
                            </div>

                            <div class="testimonial-founder d-flex align-items-center">
                                <div class="founder-img">
                                    
                                <div class="founder-text">
                                    <span>CLIENT</span>
                                    <p>Luube Benjamine</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
  <!-- Testimonials -->

<div class="testimonial-area">
    <div class="container">
        <div class="testimonial-wrapper">
            <div class="row justify-content-center">
                <div class="col-xl-8 col-lg-10">
                    <div class="h1-testimonial-active dot-style">
                        <?php
                            $testimonials = $auth::getTestimonials();
                            foreach ($testimonials as $key) {
                                # code...
                                if ($key['status']=='feature') {
                                    # code...
                                
                                $user = $auth::getUserById($key['user_id']);
                                $user_image = $auth::getIdentifications($key['user_id']);
                        ?>
                        <div class="single-testimonial position-relative">
                            <div class="testimonial-caption">
                                <img src="assets/img/icon/quotes-sign.png" alt="" class="quotes-sign" />
                                <p>
                                    "{{$key['text']}}"
                                </p>
                            </div>
                            <!-- <img class="img" src="{{$user_idnt['passport'] ?? '/assets/img/default-avatar.png'}}" /> -->
                            <div class="testimonial-founder d-flex align-items-center">
                                <div class="founder-img">
                                    <img src="{{ $user_image['passport'] ?? 'assets/img/icon/testimonial.png'}}" alt="" style="width: 100px; border-radius: 50%;" />
                                </div>
                                <div class="founder-text">
                                    <span>{{$user['name']}}</span>
                                    <p>{{$user['role'] ?? ' '}}</p>
                                </div>
                            </div>
                        </div>
                        <?php
                            }
                        }
                        ?>

                        <div class="single-testimonial position-relative">
                            <div class="testimonial-caption">
                                <img src="assets/img/icon/quotes-sign.png" alt="" class="quotes-sign" />
                                <p>
                                    "This said, our focus in the next couple of months will continue to be on growing our value added services to meet the ever changing demands of our customers. Currently, with more than 15000 customers, we recommit to ensure that we will provide each and every one of them with the best service and value-for-money prepositions that will ensure that they are well-equipped for their ever changing and dynamic lives.."
                                </p>
                            </div>

                            <div class="testimonial-founder d-flex align-items-center">
                                <div class="founder-img">
                                    
                                <div class="founder-text">
                                    <span>MANAGING DIRECTOR</span>
                                    <p>WALWASA.M.YAHAYA</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
  <!-- Testimonials -->

<div class="testimonial-area">
    <div class="container">
        <div class="testimonial-wrapper">
            <div class="row justify-content-center">
                <div class="col-xl-8 col-lg-10">
                    <div class="h1-testimonial-active dot-style">
                        <?php
                            $testimonials = $auth::getTestimonials();
                            foreach ($testimonials as $key) {
                                # code...
                                if ($key['status']=='feature') {
                                    # code...
                                
                                $user = $auth::getUserById($key['user_id']);
                                $user_image = $auth::getIdentifications($key['user_id']);
                        ?>
                        <div class="single-testimonial position-relative">
                            <div class="testimonial-caption">
                                <img src="assets/img/icon/quotes-sign.png" alt="" class="quotes-sign" />
                                <p>
                                    "{{$key['text']}}"
                                </p>
                            </div>
                            <!-- <img class="img" src="{{$user_idnt['passport'] ?? '/assets/img/default-avatar.png'}}" /> -->
                            <div class="testimonial-founder d-flex align-items-center">
                                <div class="founder-img">
                                    <img src="{{ $user_image['passport'] ?? 'assets/img/icon/testimonial.png'}}" alt="" style="width: 100px; border-radius: 50%;" />
                                </div>
                                <div class="founder-text">
                                    <span>{{$user['name']}}</span>
                                    <p>{{$user['role'] ?? ' '}}</p>
                                </div>
                            </div>
                        </div>
                        <?php
                            }
                        }
                        ?>

                        <div class="single-testimonial position-relative">
                            <div class="testimonial-caption">
                                <img src="assets/img/icon/quotes-sign.png" alt="" class="quotes-sign" />
                                <p>
                                    "I am excited to join APPNOMU at such a time as the company continues to grow and consolidate its market leadership in the microfinance industry.

I have worked with Appnomu since the beginning and through that experience I have entrenched the company’s values into the way I interact with both staff and our customers. I am a strong believer in the values of APPNOMU and have pledged that I will interact with all staff and stakeholders with integrity, provide leadership, inspire innovation,cultivate relationships, while endeavouring to maintain a ‘can-do’ attitude."
                                </p>
                            </div>

                            <div class="testimonial-founder d-flex align-items-center">
                                <div class="founder-img">
                                    
                                <div class="founder-text">
                                    <span>CEO FOUNDER</span>
                                    <p>BAHATI ASHER</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
  <!-- Testimonials -->
  <div class="involveme_embed" data-project="feedback">
<script src="https://appnomu-savings-and-loans.involve.me/embed"></script>
</div>

<div class="testimonial-area">
    <div class="container">
        <div class="testimonial-wrapper">
            <div class="row justify-content-center">
                <div class="col-xl-8 col-lg-10">
                    <div class="h1-testimonial-active dot-style">
                        <?php
                            $testimonials = $auth::getTestimonials();
                            foreach ($testimonials as $key) {
                                # code...
                                if ($key['status']=='feature') {
                                    # code...
                                
                                $user = $auth::getUserById($key['user_id']);
                                $user_image = $auth::getIdentifications($key['user_id']);
                        ?>
                        <div class="single-testimonial position-relative">
                            <div class="testimonial-caption">
                                <img src="assets/img/icon/quotes-sign.png" alt="" class="quotes-sign" />
                                <p>
                                    "{{$key['text']}}"
                                </p>
                            </div>
                            <!-- <img class="img" src="{{$user_idnt['passport'] ?? '/assets/img/default-avatar.png'}}" /> -->
                            <div class="testimonial-founder d-flex align-items-center">
                                <div class="founder-img">
                                    <img src="{{ $user_image['passport'] ?? 'assets/img/icon/testimonial.png'}}" alt="" style="width: 100px; border-radius: 50%;" />
                                </div>
                                <div class="founder-text">
                                    <span>{{$user['name']}}</span>
                                    <p>{{$user['role'] ?? ' '}}</p>
                                </div>
                            </div>
                        </div>
                        <?php
                            }
                        }
                        ?>

                        <div class="single-testimonial position-relative">
                            <div class="testimonial-caption">
                                <img src="assets/img/icon/quotes-sign.png" alt="" class="quotes-sign" />
                                <p>
                                    "We are passionate and committed to work and provide you with the best services ever, Our major goal is to see that we are able to meet the requirements of clients and solve their financial needs. On top, Appnomu has skilled my skill as a leader and i love to develop sustaining products to enrich the lives of Ugandans in terms of finance.."
                                </p>
                            </div>

                            <div class="testimonial-founder d-flex align-items-center">
                                <div class="founder-img">
                                    
                                <div class="founder-text">
                                    <span>GM. CUSTOMER EXPERIENCE</span>
                                    <p>ROSEMARY K</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection