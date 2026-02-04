<div class="blog-sidebar">
    {{-- <div class="search-form">
        <h4>Search</h4>
        <form action="#">
            <input type="text" placeholder="Search . . .  ">
            <button type="submit"><i class="fa fa-search"></i></button>
        </form>
    </div> --}}
    <div class="blog-catagory">
        <h4>Categories</h4>
        <ul>
            @foreach($categories as $category)
                @if($category->blogs_count > 0)
                    <li><a href="{{ route('blog.index', ['category' => $category->slug]) }}">{{ $category->name }}
                            ({{ $category->blogs_count }})</a></li>
                @endif
            @endforeach
        </ul>
    </div>
    <div class="recent-post">
        <h4>Recent Post</h4>
        <div class="recent-blog">
            @foreach($recent_blogs as $rb)
                <a href="{{ route('blog.details', $rb->slug) }}" class="rb-item">
                    <div class="rb-pic">
                        <img src="{{ asset($rb->thumbnail) }}" alt="{{ $rb->title }}"
                            style="width: 70px; height: 70px; object-fit: cover;">
                    </div>
                    <div class="rb-text">
                        <h6 style="font-size: 14px; line-height: 1.4;">{{ Str::limit($rb->title, 40) }}</h6>
                        <p>{{ $rb->category->name }} <span>- {{ $rb->created_date }}</span></p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    {{-- <div class="blog-tags">
        <h4>Product Tags</h4>
        <div class="tag-item">
            <a href="#">Towel</a>
            <a href="#">Shoes</a>
            <a href="#">Coat</a>
        </div>
    </div> --}}
</div>