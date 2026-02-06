@extends('frontend.customer.layout')

@section('customer_content')
    <div class="wishlist-management">
        <h4 class="mb-4">Wishlist Management</h4>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="cart-table">
            <table class="table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th class="p-name">Product Name</th>
                        <th>Price</th>
                        <th>Action</th>
                        <th><i class="ti-close"></i></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($wishlist as $item)
                        <tr>
                            <td class="si-pic" style="width: 100px;">
                                <a href="{{ route('product.show', $item->product->slug) }}">
                                    <img src="{{ asset($item->product->thumbnail) }}" alt="{{ $item->product->name }}"
                                        style="width: 80px;">
                                </a>
                            </td>
                            <td class="si-text">
                                <a href="{{ route('product.show', $item->product->slug) }}">
                                    <h6>{{ $item->product->name }}</h6>
                                </a>
                            </td>
                            <td class="p-price">৳{{ number_format($item->product->final_price, 2) }}</td>
                            <td class="p-price">
                                <a href="javascript:void(0)" class="primary-btn px-3 py-2 add-to-cart"
                                    data-slug="{{ $item->product->slug }}" style="background: #e7ab3c; font-size: 12px;">Add To
                                    Cart</a>
                            </td>
                            <td class="si-close">
                                <form action="{{ route('customer.wishlist.destroy', $item->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-0 border-0 bg-transparent" style="cursor: pointer;">
                                        <i class="ti-close text-danger"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <p>Your wishlist is currently empty.</p>
                                <a href="{{ route('shop') }}" class="primary-btn mt-3" style="background: #252525;">Shop Now</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
