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
    <form method="GET" action="{{ route('products.index') }}" id="filterForm">
        <div class="row mb-4">
            <div class="col-md-4">
                <input type="text" 
                       name="search" 
                       id="searchInput"
                       class="form-control bg-eerie-black text-white-smoke border-silver" 
                       placeholder="Поиск товаров..."
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <select name="category" 
                        id="categorySelect"
                        class="form-select bg-eerie-black text-white-smoke border-silver">
                    <option value="">Все категории</option>
                    @php
                        $categoryIcons = [
                            'keyboards' => '⌨️',
                            'mice' => '🖱️',
                            'headsets' => '🎧',
                            'mousepads' => '🖱️',
                            'controllers' => '🎮',
                            'monitors' => '🖥️',
                            'chairs' => '🪑',
                            'accessories' => '⚙️'
                        ];
                    @endphp
                    @foreach($categories as $key => $label)
                        <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>
                            {{ ($categoryIcons[$key] ?? '📦') . ' ' . $label }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <select name="sort" 
                        id="sortSelect"
                        class="form-select bg-eerie-black text-white-smoke border-silver">
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Сначала новые</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>По цене (возрастание)</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>По цене (убывание)</option>
                </select>
            </div>
        </div>
    </form>
    
    <!-- Карточки товаров -->
    <div class="row g-4">
        @forelse($products as $product)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card card-gaming h-100 product-card" tabindex="0" role="button" aria-label="Открыть {{ $product->name }}" data-product-id="{{ $product->id }}" style="cursor: pointer;" 
                     onclick="openProductModal({{ $product->id }})">
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
                        
                        <div class="d-flex justify-content-between align-items-start mt-auto gap-3">
                            <span class="h4 text-imperial-red mb-0">
                                {{ $product->price }} ₽
                            </span>
                            
                            <div class="d-flex flex-column gap-2" style="min-width: 150px;" onclick="event.stopPropagation();">
                                <button type="button" class="btn btn-sm btn-outline-silver w-100" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#productModal{{ $product->id }}"
                                        onclick="event.stopPropagation();">
                                    <i class="fas fa-eye me-1"></i>Просмотр
                                </button>
                                
                                @can('update', $product)
                                <a href="{{ route('products.edit', $product) }}" 
                                   class="btn btn-sm btn-outline-timberwolf w-100"
                                   onclick="event.stopPropagation();">
                                    <i class="fas fa-edit me-1"></i>Редактировать
                                </a>
                                @endcan
                                
                                @can('delete', $product)
                                <form action="{{ route('products.destroy', $product) }}" 
                                      method="POST"
                                      onclick="event.stopPropagation();"
                                      id="deleteForm{{ $product->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-blood-red w-100"
                                            onclick="event.stopPropagation(); handleDelete(event, {{ $product->id }});">
                                        <i class="fas fa-trash-alt me-1"></i>Удалить
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-transparent border-top border-silver">
                        <small class="text-silver d-flex justify-content-between align-items-center">
                            <span>
                                @php
                                    $categoryIcons = [
                                        'keyboards' => 'fa-keyboard',
                                        'mice' => 'fa-mouse',
                                        'headsets' => 'fa-headset',
                                        'mousepads' => 'fa-square',
                                        'controllers' => 'fa-gamepad',
                                        'monitors' => 'fa-desktop',
                                        'chairs' => 'fa-chair',
                                        'accessories' => 'fa-cog'
                                    ];
                                    $icon = $categoryIcons[strtolower($product->category)] ?? 'fa-layer-group';
                                @endphp
                                <i class="fas {{ $icon }} me-1"></i>{{ $product->category }}
                            </span>

                            <span>
                                <a href="{{ route('users.objects', $product->user->username) }}" class="text-silver text-decoration-none">
                                    <i class="fas fa-user me-1"></i>{{ $product->user->username }}
                                </a>
                                @if($product->stock > 0)
                                    <span class="ms-3 text-success">
                                        <i class="fas fa-check-circle me-1"></i>В наличии
                                    </span>
                                @else
                                    <span class="ms-3 text-danger">
                                        <i class="fas fa-times-circle me-1"></i>Нет в наличии
                                    </span>
                                @endif
                            </span>
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
                                    data-bs-dismiss="modal" aria-label="Close"></button>
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
                    @auth
                    <a href="{{ route('products.create') }}" class="btn btn-gaming">
                        <i class="fas fa-plus me-1"></i>Добавить товар
                    </a>
                    @endauth
                </div>
            </div>
        @endforelse
    </div>
    
    <!-- Пагинация -->
    @if($products->hasPages())
        <div class="row mt-4">
            <div class="col-12">
                <nav>
                    {{ $products->links('pagination::bootstrap-5') }}
                </nav>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
// Handle delete with toast notification
function handleDelete(event, productId) {
    event.preventDefault();
    if (confirm('Вы уверены, что хотите удалить этот товар?')) {
        const form = document.getElementById('deleteForm' + productId);
        const formData = new FormData(form);
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (response.ok || response.redirected) {
                if (typeof showToast === 'function') {
                    showToast('Товар успешно удален!', 'success');
                }
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                if (typeof showToast === 'function') {
                    showToast('Ошибка при удалении товара', 'error');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (typeof showToast === 'function') {
                showToast('Ошибка при удалении товара', 'error');
            }
        });
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterForm');
    const searchInput = document.getElementById('searchInput');
    const categorySelect = document.getElementById('categorySelect');
    const sortSelect = document.getElementById('sortSelect');
    
    let searchTimeout;
    
    // Поиск с задержкой (debounce)
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                filterForm.submit();
            }, 500);
        });
    }
    
    // Фильтр по категории
    if (categorySelect) {
        categorySelect.addEventListener('change', function() {
            filterForm.submit();
        });
    }
    
    // Сортировка
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            filterForm.submit();
        });
    }
});

// Keyboard navigation for product modals' carousels and close-button fallback
document.addEventListener('shown.bs.modal', function(e) {
    const modal = e.target;
    // ensure we only handle product modals
    if (!modal.id || !modal.id.startsWith('productModal')) return;

    const carousel = modal.querySelector('.carousel');
    if (!carousel) return;

    // make carousel focusable and focus it so keyboard events are intuitive
    carousel.setAttribute('tabindex', '0');
    carousel.focus();

    const carouselInstance = bootstrap.Carousel.getOrCreateInstance(carousel, { ride: false });

    const keyHandler = function(ev) {
        if (ev.key === 'ArrowLeft') {
            carouselInstance.prev();
            ev.preventDefault();
        } else if (ev.key === 'ArrowRight') {
            carouselInstance.next();
            ev.preventDefault();
        }
    };

    // store handler on the modal element so we can remove it later
    modal.__carouselKeyHandler = keyHandler;
    document.addEventListener('keydown', keyHandler);

    // fallback: ensure close buttons call bootstrap hide if data-bs-dismiss doesn't work
    const closeBtn = modal.querySelector('[data-bs-dismiss="modal"]');
    if (closeBtn) {
        closeBtn.addEventListener('click', function () {
            const modalInstance = bootstrap.Modal.getInstance(modal) || new bootstrap.Modal(modal);
            modalInstance.hide();
        });
    }
});

document.addEventListener('hidden.bs.modal', function(e) {
    const modal = e.target;
    if (modal && modal.__carouselKeyHandler) {
        document.removeEventListener('keydown', modal.__carouselKeyHandler);
        delete modal.__carouselKeyHandler;
    }
});
</script>
@endpush