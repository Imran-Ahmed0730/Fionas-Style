<!-- Footer Section Begin -->
<footer class="footer-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="footer-left">
                    <div class="footer-logo">
                        <a href="{{ route('home') }}">
                            @if($settings->logo && file_exists(public_path($settings->logo)))
                                <img src="{{asset($settings->logo)}}" alt="">
                            @else
                                <img src="{{asset('frontend')}}/assets/img/footer-logo.png" alt="">
                            @endif
                        </a>
                    </div>
                    <ul>
                        <li>Address: {{ $settings->address }}</li>
                        <li>Phone: {{ $settings->phone }}</li>
                        <li>Email: {{ $settings->email }}</li>
                    </ul>
                    <div class="footer-social">
                        <a href="{{ $settings->facebook_url }}"><i class="ti-facebook"></i></a>
                        <a href="{{ $settings->instagram_url }}"><i class="ti-instagram"></i></a>
                        <a href="{{ $settings->x_url }}"><i class="ti-twitter-alt"></i></a>
                        <a href="{{ $settings->pinterest_url }}"><i class="ti-pinterest"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 offset-lg-1">
                <div class="footer-widget">
                    <h5>Information</h5>
                    <ul>
                        <li><a href="{{ route('page.about') }}">About Us</a></li>
                        <li><a href="{{ route('contact') }}">Contact</a></li>
                        <li><a href="{{ route('faq') }}">FAQ</a></li>
                        <li><a href="{{ route('page.privacy') }}">Privacy Policy</a></li>
                        <li><a href="{{ route('page.terms') }}">Terms & Conditions</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="footer-widget">
                    <h5>My Account</h5>
                    <ul>
                        <li><a href="#">My Account</a></li>
                        <li><a href="{{ route('contact') }}">Contact</a></li>
                        <li><a href="{{ route('cart.index') }}">Shopping Cart</a></li>
                        <li><a href="{{ route('shop') }}">Shop</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="newslatter-item">
                    <h5>Join Our Newsletter Now</h5>
                    <p>Get E-mail updates about our latest shop and special offers.</p>
                    <form action="{{ route('subscribe') }}" class="subscribe-form" method="POST">
                        @csrf
                        <input type="text" placeholder="Enter Your Mail" class="@error('email') is-invalid @enderror"
                            name="email" required>
                        <button type="submit">Subscribe</button>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="copyright-reserved">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="copyright-text">
                        {!! $settings->copyright_text !!}
                    </div>
                    <div class="payment-pic">
                        <img src="{{asset('frontend')}}/assets/img/payment-method.png" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- Footer Section End -->