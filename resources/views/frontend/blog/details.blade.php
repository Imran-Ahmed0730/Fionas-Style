@extends('frontend.master')
@section('title', $blog->title)
@section('meta_title', $blog->title)
@section('meta_description', Str::limit(strip_tags($blog->description), 160))
@section('meta_image', asset($blog->thumbnail))

@section('content')
    <!-- Blog Details Section Begin -->
    <section class="blog-details spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 order-1 order-lg-2">
                    <div class="blog-details-inner">
                        <div class="blog-detail-title">
                            <h2>{{ $blog->title }}</h2>
                            <p><a href="{{ route('blog.index', ['category' => $blog->category->slug]) }}"
                                    style="color: inherit;">{{ $blog->category->name }}</a> <span>-
                                    {{ $blog->created_date }}</span></p>
                        </div>
                        <div class="blog-large-pic">
                            <img src="{{ asset($blog->thumbnail) }}" alt="{{ $blog->title }}"
                                style="width: 100%; max-height: 500px; object-fit: cover; border-radius: 8px;">
                        </div>
                        <div class="blog-detail-desc">
                            {!! $blog->description !!}
                        </div>

                        {{-- <div class="tag-share">
                            <div class="details-tag">
                                <ul>
                                    <li><i class="fa fa-tags"></i></li>
                                    <li>Travel</li>
                                    <li>Beauty</li>
                                    <li>Fashion</li>
                                </ul>
                            </div>
                            <div class="blog-share">
                                <span>Share:</span>
                                <div class="social-links">
                                    <a href="#"><i class="fa fa-facebook"></i></a>
                                    <a href="#"><i class="fa fa-twitter"></i></a>
                                    <a href="#"><i class="fa fa-instagram"></i></a>
                                </div>
                            </div>
                        </div> --}}

                        {{-- <div class="leave-comment">
                            <h4>Leave A Comment</h4>
                            <form action="#" class="comment-form">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <input type="text" placeholder="Name">
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="text" placeholder="Email">
                                    </div>
                                    <div class="col-lg-12">
                                        <textarea placeholder="Messages"></textarea>
                                        <button type="submit" class="site-btn">Send message</button>
                                    </div>
                                </div>
                            </form>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Blog Details Section End -->
@endsection

@push('css')
    <style>
        .blog-detail-desc {
            font-size: 16px;
            line-height: 1.8;
            color: #636363;
            margin-top: 30px;
        }

        .blog-detail-desc p {
            margin-bottom: 20px;
        }

        .blog-detail-title h2 {
            color: #252525;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .blog-detail-title p {
            font-size: 14px;
            color: #b2b2b2;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 25px;
        }

        .blog-detail-title p span {
            color: #e7ab3c;
        }
    </style>
@endpush