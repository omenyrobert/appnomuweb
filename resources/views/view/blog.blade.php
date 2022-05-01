@extends('layouts.website')
@section('content')
    <div class="slider-area">
        <div class="slider-height2 slider-bg2 hero-overly d-flex align-items-center">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12 offset-sm-1">
                        <div class="hero__caption hero__caption2">
                            <h2>{{$articles[0]['title'] ?? ' '}}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="blog_area section-padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mb-5 mb-lg-0">
                    <div class="blog_left_sidebar">
                        <article class="blog_item">
                            <div class="blog_item_img">
                                <img class="card-img rounded-0" src="{{ $articles[0]['url'] ?? 'assets/img/blog/single_blog_1.jpg'}}" alt="" />
                                <a href="#" class="blog_item_date">
                                    <h3>{{ date('d',strtotime($articles[0]['created_at'])) ?? ' ' }}</h3>
                                    <p>{{ date('M',strtotime($articles[0]['created_at'])) ?? ' ' }}</p>
                                </a>
                            </div>
                            <div class="blog_details">
                                <a class="d-inline-block" href="blog_details.html">
                                    <h2 class="blog-head" style="color: #2d2d2d;">{{$articles[0]['title'] ?? ' '}}</h2>
                                    <h4 class="blog-head" style="color: #2d2d2d;">{{$articles[0]['sub-title	'] ?? ' '}}</h4>
                                </a>
                                <p>{{$articles[0]['article'] ?? ' '}}</p>
                                <?php
                                    foreach ($paras as $key) {
                                        # code...
                                ?>
                                <div class="row">
                                    <div class="col-md-4">
                                        <img class="card-img rounded-0" src="{{ $key['url'] ?? 'assets/img/blog/single_blog_1.jpg'}}" alt="" />
                                    </div>

                                    <div class="col-md-8">
                                        <h2 class="blog-head" style="color: #2d2d2d;">{{ $key['title'] ?? ' '}}</h2>
                                        <p>{{ $key['text'] ?? ' ' }}</p>
                                    </div>
                                </div>
                                <?php
                                    }
                                ?>

                            </div>
                        </article>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="blog_right_sidebar">
                        <aside class="single_sidebar_widget popular_post_widget">
                            <h3 class="widget_title" style="color: #2d2d2d;">All Articles</h3>
                            <?php
                                $all_articles = $auth::getknowlegebase();
                                foreach ($all_articles as $key2) {
                                    if ($key2['status']=='Live') {
                                        # code...
                                    
                            ?>
                            <div class="media post_item">
                                <img src="{{ $key2['url'] ?? '/assets/img/post/post_1.jpg' }}" alt="post" style="width: 100px;" />
                                <div class="media-body">
                                    <a href="/knowl-base?id={{$key2['id'] ?? ' '}}">
                                        <h3 style="color: #2d2d2d;">{{ $key2['title'] ?? ' ' }}</h3>
                                    </a>
                                    <p>{{ date('M d , Y',strtotime($key2['created_at'])) ?? ' ' }}</p>
                                </div>
                            </div>
                            <?php
                                }
                                }
                            ?>
                        </aside>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection