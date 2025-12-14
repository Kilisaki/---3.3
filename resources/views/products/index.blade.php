@extends('layouts.app')

@section('title', 'Магазин - Gaming Periphery')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="text-imperial-red mb-4">
                <i class="fas fa-shopping-cart me-2"></i>Магазин периферии
            </h1>
        </div>
    </div>
    
    <!-- Поиск и фильтры -->
    <div class="row mb-4">
        <div class="col-md-4">
            <input type="text" class="form-control bg-eerie-black text-white-smoke border-silver" 
                   placeholder="Поиск товаров...">
        </div>
        <div class="col-md-4">
            <select class="form-select bg-eerie-black text-white-smoke border-silver">
                <option value="">Все категории</option>
                <option value="keyboards">Клавиатуры</option>
                <option value="mice">Мыши</option>
                <option value="headsets">Наушники</option>
                <option value="mousepads">Коврики</option>
            </select>
        </div>
        <div class="col-md-4">
            <select class="form-select bg-eerie-black text-white-smoke border-silver">
                <option value="">Сортировка</option>
                <option value="price_asc">По цене (возрастание)</option>
                <option value="price_desc">По цене (убывание)</option>
                <option value="newest">Сначала новые</option>
            </select>
        </div>
    </div>
    
    <!-- Карточки товаров -->
    <div class="row g-4">
        @forelse($products as $product)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card card-gaming h-100">
                    @if($product->mainImage)
                        <img src="{{ asset('storage/' . $product->mainImage->image_path) }}" 
                             class="card-img-top" 
                             alt="{{ $product->name }}"
                             style="height: 200px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" 
                             style="height: 200px;">
                            <i class="fas fa-image fa-3x text-silver"></i>
                        </div>
                    @endif
                    
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-white-smoke">{{ $product->name }}</h5>
                        <p class="card-text text-timberwolf flex-grow-1">
                            {{ Str::limit($product->description, 100) }}
                        </p>
                        
                        <!-- Атрибуты -->
                        @if($product->attributes)
                            <div class="mb-3">
                                @foreach(array_slice($product->attributes, 0, 2) as $key => $value)
                                    <small class="d-block text-silver">
                                        <strong>{{ $key }}:</strong> {{ $value }}
                                    </small>
                                @endforeach
                            </div>
                        @endif
                        
                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            <span class="h4 text-imperial-red mb-0">
                                {{ $product->price }} ₽
                            </span>
                            
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-outline-silver" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#productModal{{ $product->id }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                                
                                <a href="{{ route('products.edit', $product) }}" 
                                   class="btn btn-sm btn-outline-timberwolf">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <form action="{{ route('products.destroy', $product) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-blood-red"
                                            onclick="return confirm('Удалить товар?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-transparent border-top border-silver">
                        <small class="text-silver">
                            <i class="fas fa-layer-group me-1"></i>{{ $product->category }}
                            @if($product->stock > 0)
                                <span class="ms-3 text-success">
                                    <i class="fas fa-check-circle me-1"></i>В наличии
                                </span>
                            @else
                                <span class="ms-3 text-danger">
                                    <i class="fas fa-times-circle me-1"></i>Нет в наличии
                                </span>
                            @endif
                        </small>
                    </div>
                </div>
            </div>
            
            <!-- Модальное окно для детального просмотра -->
            <div class="modal fade" id="productModal{{ $product->id }}" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content bg-eerie-black text-white-smoke">
                        <div class="modal-header border-silver">
                            <h5 class="modal-title">{{ $product->name }}</h5>
                            <button type="button" class="btn-close btn-close-white" 
                                    data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            @include('products.partials.show', ['product' => $product])
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-box-open fa-4x text-silver mb-3"></i>
                    <h3 class="text-timberwolf">Товары не найдены</h3>
                    <p class="text-silver">Добавьте первый товар в магазин</p>
                    <a href="{{ route('products.create') }}" class="btn btn-gaming">
                        <i class="fas fa-plus me-1"></i>Добавить товар
                    </a>
                </div>
            </div>
        @endforelse
    </div>
    
    <!-- Пагинация -->
    @if($products->hasPages())
        <div class="row mt-4">
            <div class="col-12">
                <nav>
                    {{ $products->links('vendor.pagination.bootstrap-5') }}
                </nav>
            </div>
        </div>
    @endif
</div>
@endsection