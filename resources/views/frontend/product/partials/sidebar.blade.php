<div class="col-lg-3 col-md-6 col-sm-8 order-2 order-lg-1 produts-sidebar-filter sticky-sidebar">
    <form action="{{ $route }}" method="GET" id="filterForm">
        @if(request('search'))
            <input type="hidden" name="search" value="{{ request('search') }}">
        @endif
        @if(request('type'))
            <input type="hidden" name="type" value="{{ request('type') }}">
        @endif

        <div class="filter-accordion" id="sidebarAccordion">
            {{-- Categories --}}
            <div class="filter-widget border-bottom pb-2">
                <h4 class="fw-title m-0 d-flex justify-content-between align-items-center cursor-pointer" 
                    data-toggle="collapse" data-target="#collapseCategories" 
                    aria-expanded="{{ request('category') ? 'true' : 'false' }}" 
                    aria-controls="collapseCategories">
                    Categories
                    <i class="fa fa-angle-down transition"></i>
                </h4>
                <div id="collapseCategories" class="collapse {{ request('category') ? 'show' : '' }}" 
                     data-parent="#sidebarAccordion">
                    <ul class="filter-catagories mt-3">
                        @foreach($sidebar['categories'] as $category)
                            @php
                                $isActive = request('category') == $category->slug || (request()->routeIs('category') && request()->route('slug') == $category->slug);
                                $url = request()->routeIs('category') 
                                    ? route('category', $category->slug) 
                                    : route($route == 'search' ? 'search' : 'shop', array_merge(request()->all(), ['category' => $category->slug]));
                            @endphp
                            <li>
                                <a href="{{ $url }}" 
                                   class="{{ $isActive ? 'text-primary font-weight-bold' : '' }}">
                                    {{ $category->name }} ({{ $category->products_count }})
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            {{-- Brands --}}
            <div class="filter-widget border-bottom pb-2 mt-4">
                <h4 class="fw-title m-0 d-flex justify-content-between align-items-center cursor-pointer" 
                    data-toggle="collapse" data-target="#collapseBrands" 
                    aria-expanded="{{ request('brand') ? 'true' : 'false' }}" 
                    aria-controls="collapseBrands">
                    Brand
                    <i class="fa fa-angle-down transition"></i>
                </h4>
                <div id="collapseBrands" class="collapse {{ request('brand') ? 'show' : '' }}" 
                     data-parent="#sidebarAccordion">
                    <div class="fw-brand-check mt-3">
                        @foreach($sidebar['brands'] as $brand)
                            <div class="bc-item">
                                <label for="bc-{{ $brand->id }}">
                                    {{ $brand->name }}
                                    <input type="checkbox" id="bc-{{ $brand->id }}" name="brand[]" value="{{ $brand->slug }}"
                                        {{ in_array($brand->slug, (array) request('brand', [])) ? 'checked' : '' }}
                                        onchange="this.form.submit()">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Price --}}
            <div class="filter-widget mt-4">
                <h4 class="fw-title">Price Filter</h4>
                <div class="filter-range-wrap">
                    <div class="range-slider">
                        <div class="price-input">
                            <input type="text" id="minamount" name="min_price" value="{{ request('min_price', 0) }}">
                            <input type="text" id="maxamount" name="max_price" value="{{ request('max_price', 1000) }}">
                        </div>
                    </div>
                    <div class="price-range ui-slider ui-corner-all ui-slider-horizontal ui-widget ui-widget-content"
                        data-min="0" 
                        data-max="50000"
                        data-start-min="{{ request('min_price', 0) }}"
                        data-start-max="{{ request('max_price', 50000) }}">
                        <div class="ui-slider-range ui-corner-all ui-widget-header"></div>
                        <span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default"></span>
                        <span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default"></span>
                    </div>
                </div>
                <button type="submit" class="filter-btn">Filter</button>
            </div>
        </div>

        {{-- Sorting (Hidden fields to persist sort) --}}
        @if(request('sort'))
             <input type="hidden" name="sort" value="{{ request('sort') }}">
        @endif
    </form>
</div>

<style>
.sticky-sidebar {
    position: -webkit-sticky;
    position: sticky;
    top: 20px;
    height: fit-content;
    max-height: calc(100vh - 40px);
    overflow-y: auto;
    padding-right: 15px;
}
.sticky-sidebar::-webkit-scrollbar {
    width: 4px;
}
.sticky-sidebar::-webkit-scrollbar-thumb {
    background: #e7ab3c;
    border-radius: 10px;
}
.cursor-pointer {
    cursor: pointer;
}
.transition {
    transition: transform 0.3s ease;
}
[aria-expanded="true"] .transition {
    transform: rotate(180deg);
}
.filter-widget .fw-title {
    font-size: 18px;
    color: #252525;
    text-transform: uppercase;
}
</style>
