<!-- Header Section Begin -->
<header class="header-section">
    <div class="header-top">
        <div class="container">
            <div class="ht-left">
                <div class="mail-service">
                    <i class=" fa fa-envelope"></i>
                    <a href="mailto:{{ $settings->email }}">{{ $settings->email }}</a>
                </div>
                <div class="phone-service">
                    <i class=" fa fa-phone"></i>
                    <a href="tel:{{ $settings->phone }}">{{ $settings->phone }}</a>
                </div>
            </div>
            <div class="ht-right">
                <a href="#" class="login-panel"><i class="fa fa-user"></i>Login</a>
                <div class="top-social">
                    <a href="{{ $settings->facebook_url }}"><i class="ti-facebook"></i></a>
                    <a href="{{ $settings->x_url }}"><i class="ti-twitter-alt"></i></a>
                    <a href="{{ $settings->linkedin_url }}"><i class="ti-linkedin"></i></a>
                    <a href="{{ $settings->pinterest_url }}"><i class="ti-pinterest"></i></a>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="inner-header">
            <div class="row">
                <div class="col-lg-2 col-md-2">
                    <div class="logo">
                        <a href="{{ route('home') }}">
                            @if($settings->logo && file_exists(public_path($settings->logo)))
                                <img src="{{asset($settings->logo)}}" alt="" style="max-width: 60% !important;">
                            @endif
                        </a>
                    </div>
                </div>
                <div class="col-lg-7 col-md-7">
                    <form action="{{ route('search') }}" method="GET">
                        <div class="advanced-search">
                            <select name="type" class="category-btn">
                                <option value="product" {{ request('type') == 'product' ? 'selected' : '' }}>All Products
                                </option>
                                <option value="category" {{ request('type') == 'category' ? 'selected' : '' }}>All Categories
                                </option>
                                <option value="brand" {{ request('type') == 'brand' ? 'selected' : '' }}>All Brands
                                </option>
                                <option value="tag" {{ request('type') == 'tag' ? 'selected' : '' }}> Tags
                                </option>
                            </select>
                            <div class="input-group">
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="What do you need?">
                                <button type="submit"><i class="ti-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-lg-3 text-right col-md-3">
                    <ul class="nav-right">
                        <li class="heart-icon">
                            <a href="#">
                                //TODO implement wishlist
                                <i class="icon_heart_alt"></i>
                                <span>1</span>
                            </a>
                        </li>
                        <li class="cart-icon">
                            <a href="#">
                                <i class="icon_bag_alt"></i>
                                <span id="cartTotalQuantity">{{Cart::getTotalQuantity()}}</span>
                            </a>
                            <div class="cart-hover">
                                <div class="select-items">
                                    <table>
                                        <tbody id="headerCartItems">
                                            @forelse(Cart::getContent() as $cartItem)
                                                <tr class="cart_item cart-item-{{ $cartItem->id }}"
                                                    data-sku="{{ $cartItem->id }}">
                                                    <td class="si-pic"><img
                                                            src="{{ $cartItem->attributes['image'] && file_exists($cartItem->attributes['image']) ? asset($cartItem->attributes['image']) : asset('backend/assets/img/default-150x150.png') }}"
                                                            alt="{{ $cartItem->name }}"></td>
                                                    <td class="si-text">
                                                        <div class="product-selected">
                                                            <p>{{ $currency['symbol'] ?? '৳' }}{{ $cartItem->price }} x
                                                                {{ $cartItem->quantity }}
                                                            </p>
                                                            <h6>{{ $cartItem->name }}</h6>
                                                            @if($cartItem->attributes->variant)
                                                                <p><small>{{ $cartItem->attributes->variant }}</small></p>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td class="si-close">
                                                        <i class="ti-close remove-from-cart"
                                                            data-sku="{{ $cartItem->id }}"></i>
                                                    </td>
                                                </tr>
                                            @empty
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="select-total">
                                    <span>total:</span>
                                    <h5>{{ $currency['symbol'] ?? '৳' }} <span
                                            class="total_price headerSubTotal">{{Cart::getSubTotal()}}</span></h5>
                                </div>
                                <div class="select-button">
                                    <a href="{{ route('cart.index') }}" class="primary-btn view-card">VIEW CART</a>
                                    <a href="#" class="primary-btn checkout-btn">CHECK OUT</a>
                                </div>
                            </div>
                        </li>
                        <li class="cart-price">{{ $currency['symbol'] ?? '৳' }} <span id="navbarTotalPrice"
                                class="total_price navbarTotalPrice">{{Cart::getSubTotal()}}</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="nav-item">
        <div class="container">
            <div class="nav-depart">
                <div class="depart-btn">
                    <i class="ti-menu"></i>
                    <span>Top Categories</span>
                    <ul class="depart-hover">
                        @foreach($categories as $category)
                            <li><a href="{{ route('category', $category->slug) }}">{{ $category->name }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <nav class="nav-menu mobile-menu">
                <ul>
                    <li class="{{ request()->routeIs('home') ? 'active' : '' }}"><a href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="{{ request()->routeIs('shop') ? 'active' : '' }}"><a href="{{ route('shop') }}">Shop</a>
                    </li>
                    <li class="{{ request()->routeIs('categories') ? 'active' : '' }}"><a
                            href="{{ route('categories') }}">Categories</a></li>
                    <li class="{{ request()->routeIs('blog.index') ? 'active' : '' }}"><a
                            href="{{ route('blog.index') }}">Blog</a></li>
                    <li class="{{ request()->routeIs('page.about') ? 'active' : '' }}"><a
                            href="{{ route('page.about') }}">About Us</a></li>
                    <li class="{{ request()->routeIs('contact') ? 'active' : '' }}"><a
                            href="{{ route('contact') }}">Contact</a></li>
                </ul>
            </nav>
            <div id="mobile-menu-wrap"></div>
        </div>
    </div>
</header>
<!-- Header End -->