@extends('layouts.app')

@section('title', 'Главная - Gaming Periphery')

@section('content')
<div class="container-fluid">
    <!-- Swiper баннеры -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="banner-swiper swiper">
                <div class="swiper-wrapper">
                    @for($i = 1; $i <= 5; $i++)
                        <div class="swiper-slide">
                            <img src="{{ asset("images/banners/banner{$i}.jpg") }}" 
                                 alt="Баннер {{ $i }}"
                                 class="w-100">
                        </div>
                    @endfor
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </div>
    
    <!-- Рекомендуемые товары -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="text-imperial-red mb-4">
                <i class="fas fa-star me-2"></i>Рекомендуем
            </h2>
        </div>
        
        @php
            $featuredProducts = \App\Models\Product::with('images')
                ->where('is_featured', true)
                ->take(6)
                ->get();
        @endphp
        
        @foreach($featuredProducts as $product)
            <div class="col-lg-2 col-md-4 col-sm-6 mb-4">
                <div class="card card-gaming h-100">
                    @if($product->mainImage)
                        <img src="{{ asset('storage/' . $product->mainImage->image_path) }}" 
                             class="card-img-top" 
                             alt="{{ $product->name }}"
                             style="height: 150px; object-fit: cover;">
                    @endif
                    <div class="card-body">
                        <h6 class="card-title text-white-smoke">{{ Str::limit($product->name, 30) }}</h6>
                        <p class="h5 text-imperial-red mb-0">{{ $product->price }} ₽</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <!-- Категории -->
    <div class="row">
        <div class="col-12">
            <h2 class="text-imperial-red mb-4">
                <i class="fas fa-th-large me-2"></i>Категории
            </h2>
        </div>
        
        @php
            $categories = [
                ['icon' => 'fa-keyboard', 'key' => 'keyboards', 'name' => 'Клавиатуры', 'color' => 'bg-cornell-red'],
                ['icon' => 'fa-mouse', 'key' => 'mice', 'name' => 'Мыши', 'color' => 'bg-imperial-red'],
                ['icon' => 'fa-headset', 'key' => 'headsets', 'name' => 'Наушники', 'color' => 'bg-blood-red'],
                ['icon' => 'fa-square', 'key' => 'mousepads', 'name' => 'Коврики', 'color' => 'bg-cornell-red-2'],
                ['icon' => 'fa-gamepad', 'key' => 'controllers', 'name' => 'Контроллеры', 'color' => 'bg-imperial-red'],
                ['icon' => 'fa-desktop', 'key' => 'monitors', 'name' => 'Мониторы', 'color' => 'bg-blood-red'],
                ['icon' => 'fa-chair', 'key' => 'chairs', 'name' => 'Кресла', 'color' => 'bg-cornell-red'],
                ['icon' => 'fa-cog', 'key' => 'accessories', 'name' => 'Аксессуары', 'color' => 'bg-imperial-red'],
            ];
        @endphp
        
        @foreach($categories as $category)
            <div class="col-md-2 col-sm-4 mb-3">
                <a href="{{ route('products.index') }}?category={{ $category['key'] }}" 
                   class="text-decoration-none">
                    <div class="card card-gaming text-center h-100">
                        <div class="card-body">
                            <div class="{{ $category['color'] }} rounded-circle p-3 d-inline-block mb-3">
                                <i class="fas {{ $category['icon'] }} fa-2x text-white-smoke"></i>
                            </div>
                            <h6 class="card-title text-white-smoke">{{ $category['name'] }}</h6>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>
@endsection

{{-- Swiper инициализируется в app.js --}}