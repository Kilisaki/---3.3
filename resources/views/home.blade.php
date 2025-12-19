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
                <div class="card card-gaming h-100" style="cursor: pointer;" 
                     onclick="openProductModal({{ $product->id }})">
                    @if($product->mainImage)
                        <img src="{{ asset('storage/' . $product->mainImage->image_path) }}" 
                             class="card-img-top" 
                             alt="{{ $product->name }}"
                             style="height: 150px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" 
                             style="height: 150px;">
                            <i class="fas fa-image fa-2x text-silver"></i>
                        </div>
                    @endif
                    <div class="card-body">
                        <h6 class="card-title text-white-smoke">{{ Str::limit($product->name, 30) }}</h6>
                        <p class="h5 text-imperial-red mb-0">{{ $product->price }} ₽</p>
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
                    window.location.href = '{{ route('products.index') }}';
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

// Функция для открытия модального окна товара
function openProductModal(productId) {
    const modalElement = document.getElementById('productModal' + productId);
    if (modalElement) {
        let modal = bootstrap.Modal.getInstance(modalElement);
        if (!modal) {
            modal = new bootstrap.Modal(modalElement);
        }
        
        // Инициализация карусели при открытии модального окна
        modalElement.addEventListener('shown.bs.modal', function() {
            const carousel = modalElement.querySelector('.carousel');
            if (carousel && !carousel._carousel) {
                const carouselInstance = new bootstrap.Carousel(carousel);
                carousel._carousel = carouselInstance;
            }
        }, { once: true });
        
        modal.show();
    }
}

// Навигация между модальными окнами товаров с помощью клавиатуры
document.addEventListener('keydown', function(event) {
    // Проверяем, открыто ли модальное окно
    const openModal = document.querySelector('.modal.show');
    if (!openModal) return;
    
    // Получаем ID открытого модального окна
    const modalId = openModal.id;
    const matchId = modalId.match(/\d+$/);
    if (!matchId) return;
    
    const currentId = parseInt(matchId[0]);
    
    if (event.key === 'ArrowRight') {
        // Находим следующее модальное окно
        let nextId = currentId + 1;
        let nextModal = document.getElementById('productModal' + nextId);
        
        // Если нет модального окна с таким ID, ищем первый доступный
        if (!nextModal) {
            nextId = 1;
            nextModal = document.getElementById('productModal' + nextId);
        }
        
        if (nextModal) {
            event.preventDefault();
            const currentModal = bootstrap.Modal.getInstance(openModal);
            if (currentModal) {
                currentModal.hide();
            }
            openProductModal(nextId);
        }
    } else if (event.key === 'ArrowLeft') {
        // Находим предыдущее модальное окно
        let prevId = currentId - 1;
        let prevModal = document.getElementById('productModal' + prevId);
        
        if (prevModal) {
            event.preventDefault();
            const currentModal = bootstrap.Modal.getInstance(openModal);
            if (currentModal) {
                currentModal.hide();
            }
            openProductModal(prevId);
        }
    }
});

document.addEventListener('DOMContentLoaded', function() {
    // Инициализация модальных окон Bootstrap
    const productModals = document.querySelectorAll('.modal');
    productModals.forEach(function(modal) {
        if (!modal._modal) {
            modal._modal = new bootstrap.Modal(modal);
        }
    });
});
</script>
@endpush

{{-- Swiper инициализируется в app.js --}}