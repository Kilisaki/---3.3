<div class="row">
    <div class="col-md-6">
        @if($product->images->count() > 0)
            <!-- Карусель изображений -->
            <div id="productCarousel{{ $product->id }}" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @foreach($product->images as $index => $image)
                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                            <img src="{{ asset('storage/' . $image->image_path) }}" 
                                 class="d-block w-100 rounded" 
                                 alt="{{ $product->name }}"
                                 style="height: 400px; object-fit: cover;">
                        </div>
                    @endforeach
                </div>
                @if($product->images->count() > 1)
                    <button class="carousel-control-prev" type="button" 
                            data-bs-target="#productCarousel{{ $product->id }}" 
                            data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Предыдущий</span>
                    </button>
                    <button class="carousel-control-next" type="button" 
                            data-bs-target="#productCarousel{{ $product->id }}" 
                            data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Следующий</span>
                    </button>
                @endif
            </div>
            
            <!-- Миниатюры -->
            @if($product->images->count() > 1)
                <div class="row mt-2">
                    @foreach($product->images as $index => $image)
                        <div class="col-3">
                            <button type="button" 
                                    class="btn btn-sm p-0 border-0"
                                    data-bs-target="#productCarousel{{ $product->id }}" 
                                    data-bs-slide-to="{{ $index }}"
                                    aria-label="Slide {{ $index + 1 }}">
                                <img src="{{ asset('storage/' . $image->image_path) }}" 
                                     class="img-thumbnail" 
                                     style="height: 80px; object-fit: cover;"
                                     alt="Миниатюра {{ $index + 1 }}">
                            </button>
                        </div>
                    @endforeach
                </div>
            @endif
        @else
            <div class="text-center py-5 bg-eerie-black rounded">
                <i class="fas fa-image fa-5x text-silver mb-3"></i>
                <p class="text-timberwolf">Изображение отсутствует</p>
            </div>
        @endif
    </div>
    
    <div class="col-md-6">
        <h3 class="text-imperial-red">{{ $product->name }}</h3>
        
        <!-- Цена -->
        <div class="mb-3">
            <span class="h2 text-timberwolf">{{ $product->price }} ₽</span>
            @if($product->stock > 0)
                <span class="badge bg-success ms-2">В наличии: {{ $product->stock }} шт.</span>
            @else
                <span class="badge bg-danger ms-2">Нет в наличии</span>
            @endif
        </div>
        
        <!-- Основная информация -->
        <div class="mb-4">
            <h5 class="text-white-smoke mb-3">Основные характеристики</h5>
            <table class="table table-borderless text-timberwolf">
                <tbody>
                    <tr>
                        <td><strong>Категория:</strong></td>
                        <td>
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
                        </td>
                    </tr>
                    @if($product->brand)
                        <tr>
                            <td><strong>Бренд:</strong></td>
                            <td>{{ $product->brand }}</td>
                        </tr>
                    @endif
                    <tr>
                        <td><strong>Артикул:</strong></td>
                        <td>#{{ str_pad($product->id, 6, '0', STR_PAD_LEFT) }}</td>
                    </tr>
                    <tr>
                        <td><strong>Добавлен:</strong></td>
                        <td>{{ $product->created_at->format('d.m.Y') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Расширенные атрибуты -->
        @if($product->attributes && count($product->attributes) > 0)
            <div class="mb-4">
                <h5 class="text-white-smoke mb-3">Технические характеристики</h5>
                <div class="row">
                    @foreach($product->attributes as $key => $value)
                        <div class="col-md-6 mb-2">
                            <div class="bg-night p-2 rounded">
                                <small class="text-silver d-block">{{ $key }}</small>
                                <strong class="text-timberwolf">{{ $value }}</strong>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        
        <!-- Описание -->
        @if($product->description)
            <div class="mb-4">
                <h5 class="text-white-smoke mb-3">Описание</h5>
                <p class="text-timberwolf">{{ $product->description }}</p>
            </div>
        @endif
        
        <!-- Кнопки действий -->
        <div class="d-flex gap-2">
            <a href="{{ route('products.edit', $product) }}" 
               class="btn btn-gaming flex-fill">
                <i class="fas fa-edit me-1"></i>Редактировать
            </a>
            
            <form action="{{ route('products.destroy', $product) }}" 
                  method="POST" class="flex-fill"
                  id="deleteForm{{ $product->id }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-blood-red w-100"
                        onclick="event.preventDefault(); handleDelete(event, {{ $product->id }});">
                    <i class="fas fa-trash-alt me-1"></i>Удалить
                </button>
            </form>
        </div>
    </div>
</div>

<script>
// Handle delete with toast notification (if not already defined)
if (typeof handleDelete === 'undefined') {
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
}
</script>