@extends('layouts.website')
@section('content')
    <div class="slider-area">
        <div class="slider-height2 slider-bg2 hero-overly d-flex align-items-center">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12 offset-sm-1">
                        <div class="hero__caption hero__caption2">
                            <h2>Knowledge Base</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="blog_area section-padding">
        <div class="container">
            <div class="row">
                <?php
                    $articles = $auth::getknowlegebase();
                    foreach ($articles as $key) {
                        # code...
                        if ($key['status']=='Live') {
                ?>
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <div class="blog_left_sidebar">
                        
                        <article class="blog_item">
                            <div class="blog_item_img">
                                <img class="card-img rounded-0" src="{{$key['url'] ?? '/assets/img/banner.png'}}" alt="" />
                                <a href="#" class="blog_item_date">
                                    <h3>{{ date('d',strtotime($key['created_at']))}}</h3>
                                    <p>{{ date('M',strtotime($key['created_at']))}}</p>
                                </a>
                            </div>
                            <div class="blog_details">
                                <a class="d-inline-block" href="/knowl-base?id={{$key['id'] ?? ' '}}">
                                    <h2 class="blog-head" style="color: #2d2d2d;">{{$key['title']}}</h2>
                                    <h2 class="blog-head" style="color: #2d2d2d;">{{$key['sub-title']}}</h2>
                                </a>
                                <ul class="blog-info-link">
                                    <li>
                                        <a href="#"><i class="fa fa-user"></i> {{$key['title']}}, {{$key['sub-title']}}</a>
                                    </li>
                                </ul>
                            </div>
                        </article>

                    </div>
                </div>
                <?php
                    } 
                }
                ?>
            </div>
        </div>
    </section>
@endsection