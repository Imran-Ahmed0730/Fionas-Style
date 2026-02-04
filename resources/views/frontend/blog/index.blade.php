@extends('frontend.master')
@section('title', 'Latest Blogs')
@section('meta_title', 'Latest Blogs')
@section('meta_description', 'Read our latest stories and news about fashion and lifestyle.')

@section('content')
    <!-- Breadcrumb Section Begin -->
    <div class="breacrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-text">
                        <a href="{{ route('home') }}"><i class="ti-home"></i> Home</a>
                        <span>Blog</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Section Begin -->

    <!-- Blog Section Begin -->
    <section class="blog-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-8 order-2 order-lg-1">
                    @include('frontend.blog.sidebar')
                </div>
                <div class="col-lg-9 order-1 order-lg-2">
                    <div class="row">
                        @forelse($blogs as $blog)
                        <div class="col-lg-6 col-sm-6">
                            <div class="blog-item">
                                <div class="bi-pic">
                                    <a href="{{ route('blog.details', $blog->slug) }}">
                                        <img src="{{ asset($blog->thumbnail) }}" alt="{{ $blog->title }}" 
                                             style="width: 100%; height: 260px; object-fit: cover; border-radius: 5px;">
                                    </a>
                                </div>
                                <div class="bi-text">
                                    <a href="{{ route('blog.details', $blog->slug) }}">
                                        <h4 style="font-size: 20px; font-weight: 700;">{{ $blog->title }}</h4>
                                    </a>
                                    <p>{{ $blog->category->name }} <span>- {{ $blog->created_date }}</span></p>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <p class="text-center">No blogs found.</p>
                        </div>
                        @endforelse
                        
                        <div class="col-lg-12">
                            <div class="pagination-section">
                                {{ $blogs->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Blog Section End -->
@endsection