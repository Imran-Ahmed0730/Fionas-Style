@extends('frontend.master')
@section('title', 'Product Comparison')
@push('css')
    <style>
        .comparison-container {
            margin: 40px 0;
        }

        .comparison-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding: 20px 25px;
            background: linear-gradient(135deg, #f8f8f8 0%, #ffffff 100%);
            border-radius: 5px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border: 1px solid #e8e8e8;
        }

        .comparison-header > div:first-child {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .comparison-header h2 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
            color: #333;
        }

        .comparison-actions {
            display: flex;
            gap: 10px;
        }

        .btn-clear-compare {
            background: #e7ab3c;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            line-height: 1;
        }

        .btn-clear-compare:hover {
            background: #d4952a;
            transform: translateY(-2px);
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }

        .btn-clear-compare i {
            font-size: 14px;
        }

        .empty-compare {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-compare i {
            font-size: 60px;
            color: #ddd;
            margin-bottom: 20px;
            display: inline-block;
        }

        .empty-compare h3 {
            font-size: 24px;
            color: #333;
            margin-bottom: 10px;
        }

        .empty-compare p {
            color: #999;
            margin-bottom: 20px;
        }

        .btn-continue-shopping {
            background: #e7ab3c;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 3px;
            text-decoration: none;
            display: inline-block;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-continue-shopping:hover {
            background: #d4952a;
            text-decoration: none;
            color: white;
        }

        .comparison-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .comparison-table thead th {
            background: #333;
            color: white;
            padding: 20px;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid #e7ab3c;
        }

        .comparison-table tbody td {
            padding: 20px;
            border-bottom: 1px solid #eee;
        }

        .comparison-table tbody tr:hover {
            background: #f9f9f9;
        }

        .attribute-name {
            font-weight: 600;
            width: 150px;
            background: #f8f8f8;
        }

        .product-image {
            text-align: center;
            padding: 15px !important;
            background: #fafafa;
            border-radius: 3px;
            margin-bottom: 8px;
        }

        .product-image img {
            max-width: 150px;
            height: auto;
            border-radius: 3px;
            object-fit: contain;
        }

        .product-name {
            text-align: center;
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 8px;
            color: #333;
            line-height: 1.3;
        }

        .product-price {
            text-align: center;
            font-size: 18px;
            font-weight: 600;
            color: #e7ab3c;
            margin-bottom: 10px;
        }

        .product-actions {
            text-align: center;
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-top: 12px;
        }

        .btn-add-to-cart {
            background: #333;
            color: white;
            border: none;
            padding: 12px 16px;
            border-radius: 3px;
            cursor: pointer;
            font-weight: 600;
            font-size: 13px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            width: 100%;
            line-height: 1;
        }

        .btn-add-to-cart:hover {
            background: #e7ab3c;
            text-decoration: none;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .btn-add-to-cart i {
            font-size: 14px;
        }

        .btn-remove-compare {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 10px 12px;
            border-radius: 3px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            line-height: 1;
        }

        .btn-remove-compare:hover {
            background: #c0392b;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .btn-remove-compare i {
            font-size: 12px;
        }

        .attribute-value {
            text-align: center;
            word-break: break-word;
        }

        .responsive-table {
            overflow-x: auto;
        }

        @media (max-width: 768px) {
            .comparison-table {
                font-size: 12px;
            }

            .comparison-table thead th,
            .comparison-table tbody td {
                padding: 10px 5px;
            }

            .product-image img {
                max-width: 80px;
            }

            .comparison-header {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }

            .comparison-header h2 {
                font-size: 20px;
            }
        }

        .comparison-count {
            background: #e7ab3c;
            color: white;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .comparison-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .comparison-header > div:last-child {
                width: 100%;
            }

            .comparison-header .btn-clear-compare {
                width: 100%;
            }

            .comparison-table {
                font-size: 13px;
            }

            .comparison-table td,
            .comparison-table th {
                padding: 12px 8px;
            }

            .product-image {
                height: 120px;
            }

            .product-image img {
                max-height: 100px;
            }

            .product-name {
                font-size: 14px;
            }

            .product-price {
                font-size: 13px;
            }

            .product-actions {
                gap: 8px;
            }

            .btn-add-to-cart,
            .btn-remove-compare {
                padding: 8px 12px;
                font-size: 12px;
            }
        }

        @media (max-width: 480px) {
            .comparison-header h2 {
                font-size: 22px;
            }

            .comparison-table {
                font-size: 12px;
            }

            .comparison-table td,
            .comparison-table th {
                padding: 10px 6px;
            }

            .product-image {
                height: 100px;
            }

            .product-image img {
                max-height: 80px;
            }

            .product-actions {
                flex-direction: column;
                gap: 6px;
            }

            .btn-add-to-cart,
            .btn-remove-compare {
                width: 100%;
                padding: 8px 10px;
                font-size: 11px;
            }
        }

        /* Empty State Styling */
        .empty-comparison {
            text-align: center;
            padding: 60px 20px;
            background: #f8f8f8;
            border-radius: 5px;
            margin-top: 40px;
        }

        .empty-comparison i {
            font-size: 64px;
            color: #ddd;
            margin-bottom: 20px;
            display: block;
        }

        .empty-comparison h3 {
            font-size: 22px;
            color: #666;
            margin-bottom: 10px;
        }

        .empty-comparison p {
            color: #999;
            margin-bottom: 20px;
        }

        .empty-comparison a {
            display: inline-block;
            padding: 12px 30px;
            background: #333;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .empty-comparison a:hover {
            background: #e7ab3c;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(231, 171, 60, 0.3);
        }

        .no-value {
            color: #ccc;
            font-style: italic;
        }
    </style>
@endpush

@section('content')

    <!-- Breadcrumb Section Begin -->
    <div class="breacrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-text product-more">
                        <a href="{{ route('home') }}"><i class="fa fa-home"></i> Home</a>
                        <a href="{{ route('shop') }}">Shop</a>
                        <span>Product Comparison</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Section End -->

    <!-- Comparison Section Begin -->
    <section class="comparison-container">
        <div class="container">
            @if ($isEmpty)
                <!-- Empty Comparison -->
                <div class="empty-compare">
                    <i class="fa fa-exchange"></i>
                    <h3>No Products to Compare</h3>
                    <p>You haven't added any products to compare yet. Start comparing by adding products from the shop.</p>
                    <a href="{{ route('shop') }}" class="btn-continue-shopping">Continue Shopping</a>
                </div>
            @else
                <!-- Comparison Header -->
                <div class="comparison-header">
                    <div>
                        <h2>Product Comparison</h2>
                        <span class="comparison-count">{{ $count }}/{{ $max }} products</span>
                    </div>
                    <div class="comparison-actions">
                        <button class="btn-clear-compare" onclick="comparisonClearAll()">
                            <i class="fa fa-trash-o"></i> Clear All
                        </button>
                    </div>
                </div>

                <!-- Comparison Table -->
                <div class="responsive-table">
                    <table class="comparison-table">
                        <thead>
                            <tr>
                                <th style="width: 150px;">Attributes</th>
                                @foreach ($products as $product)
                                    <th style="width: 200px;">
                                        <div class="product-image">
                                            <img src="{{ asset($product->thumbnail) }}" alt="{{ $product->name }}">
                                        </div>
                                        <div class="product-name">{{ $product->name }}</div>
                                        <div class="product-price">${{ number_format($product->final_price, 2) }}</div>
                                        <div class="product-actions">
                                            <button class="btn-add-to-cart" onclick="comparisonAddToCart('{{ $product->slug }}')">
                                                <i class="fa fa-shopping-cart"></i> Add to Cart
                                            </button>
                                            <button class="btn-remove-compare" onclick="comparisonRemoveProduct({{ $product->id }})">
                                                <i class="fa fa-times"></i> Remove
                                            </button>
                                        </div>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Brand -->
                            <tr>
                                <td class="attribute-name">{{ $attributes['brand']['label'] }}</td>
                                @foreach ($products as $product)
                                    <td class="attribute-value">
                                        @if ($product->brand)
                                            <strong>{{ $product->brand->name }}</strong>
                                        @else
                                            <span class="no-value">N/A</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>

                            <!-- Category -->
                            <tr>
                                <td class="attribute-name">{{ $attributes['category']['label'] }}</td>
                                @foreach ($products as $product)
                                    <td class="attribute-value">
                                        @if ($product->category)
                                            <a href="{{ route('category', $product->category->slug) }}" style="color: #e7ab3c; text-decoration: none;">
                                                {{ $product->category->name }}
                                            </a>
                                        @else
                                            <span class="no-value">N/A</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>

                            <!-- Stock -->
                            <tr>
                                <td class="attribute-name">{{ $attributes['stock_qty']['label'] }}</td>
                                @foreach ($products as $product)
                                    <td class="attribute-value">
                                        @if ($product->stock_qty > 0)
                                            <span style="color: #27ae60;">In Stock ({{ $product->stock_qty }})</span>
                                        @else
                                            <span style="color: #e74c3c;">Out of Stock</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>

                            <!-- Color -->
                            <tr>
                                <td class="attribute-name">{{ $attributes['color']['label'] }}</td>
                                @foreach ($products as $product)
                                    <td class="attribute-value">
                                        @if ($product->color)
                                            {{ $product->color }}
                                        @else
                                            <span class="no-value">N/A</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>

                            <!-- Weight -->
                            <tr>
                                <td class="attribute-name">{{ $attributes['weight']['label'] }}</td>
                                @foreach ($products as $product)
                                    <td class="attribute-value">
                                        @if ($product->weight)
                                            {{ $product->weight }} kg
                                        @else
                                            <span class="no-value">N/A</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>

                            <!-- Dimensions -->
                            <tr>
                                <td class="attribute-name">{{ $attributes['dimensions']['label'] }}</td>
                                @foreach ($products as $product)
                                    <td class="attribute-value">
                                        @if ($product->dimensions)
                                            {{ $product->dimensions }}
                                        @else
                                            <span class="no-value">N/A</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>

                            <!-- Description -->
                            <tr>
                                <td class="attribute-name">{{ $attributes['description']['label'] }}</td>
                                @foreach ($products as $product)
                                    <td class="attribute-value">
                                        @if ($product->description)
                                            {{ Illuminate\Support\Str::limit($product->description, 100) }}
                                        @else
                                            <span class="no-value">N/A</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endif

        </div>
    </section>
    <!-- Comparison Section End -->

@endsection
