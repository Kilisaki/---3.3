@extends('layouts.app')

@section('title', isset($product) ? 'Редактировать товар' : 'Добавить товар')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card card-gaming">
                <div class="card-header bg-eerie-black text-white-smoke">
                    <h4 class="mb-0">
                        <i class="fas {{ isset($product) ? 'fa-edit' : 'fa-plus-circle' }} me-2"></i>
                        {{ isset($product) ? 'Редактировать товар' : 'Добавить новый товар' }}
                    </h4>
                </div>
                
                <div class="card-body">
                    <form action="{{ isset($product) ? route('products.update', $product) : route('products.store') }}" 
                          method="POST" 
                          enctype="multipart/form-data" id="productForm">
                        @csrf
                        @if(isset($product))
                            @method('PUT')
                        @endif
                        
                        <div class="row g-3">
                            <!-- Название -->
                            <div class="col-md-6">
                                <label for="name" class="form-label text-timberwolf">
                                    Название товара *
                                </label>
                                <input type="text" class="form-control bg-eerie-black text-white-smoke border-silver" 
                                       id="name" name="name" 
                                       value="{{ old('name', $product->name ?? '') }}" 
                                       required maxlength="255">
                            </div>
                            
                            <!-- Цена -->
                            <div class="col-md-6">
                                <label for="price" class="form-label text-timberwolf">
                                    Цена (₽) *
                                </label>
                                <input type="number" step="0.01" min="0" 
                                       class="form-control bg-eerie-black text-white-smoke border-silver" 
                                       id="price" name="price" 
                                       value="{{ old('price', $product->price ?? '') }}" required>
                            </div>
                            
                            <!-- Категория -->
                            <div class="col-md-6">
                                <label for="category" class="form-label text-timberwolf">
                                    <i class="fas fa-tags me-1"></i>Категория *
                                </label>
                                <select class="form-select bg-eerie-black text-white-smoke border-silver" 
                                        id="category" name="category" required>
                                    <option value="">Выберите категорию</option>
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
                                    @foreach($categories as $key => $category)
                                        <option value="{{ $key }}" 
                                                {{ old('category', $product->category ?? '') == $key ? 'selected' : '' }}>
                                            {{ ($categoryIcons[$key] ?? '📦') . ' ' . $category }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Бренд -->
                            <div class="col-md-6">
                                <label for="brand" class="form-label text-timberwolf">
                                    Бренд
                                </label>
                                <input type="text" 
                                       class="form-control bg-eerie-black text-white-smoke border-silver" 
                                       id="brand" name="brand" 
                                       value="{{ old('brand', $product->brand ?? '') }}">
                            </div>
                            
                            <!-- Описание -->
                            <div class="col-12">
                                <label for="description" class="form-label text-timberwolf">
                                    Описание
                                </label>
                                <textarea class="form-control bg-eerie-black text-white-smoke border-silver" 
                                          id="description" name="description" 
                                          rows="3">{{ old('description', $product->description ?? '') }}</textarea>
                            </div>
                            
                            <!-- Количество -->
                            <div class="col-md-6">
                                <label for="stock" class="form-label text-timberwolf">
                                    Количество на складе *
                                </label>
                                <input type="number" min="0" 
                                       class="form-control bg-eerie-black text-white-smoke border-silver" 
                                       id="stock" name="stock" 
                                       value="{{ old('stock', $product->stock ?? 0) }}" required>
                            </div>
                            
                            <!-- Особый товар -->
                            <div class="col-md-6">
                                <div class="form-check mt-4 pt-2">
                                    <input type="checkbox" class="form-check-input" 
                                           id="is_featured" name="is_featured" value="1"
                                           {{ old('is_featured', $product->is_featured ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label text-timberwolf" for="is_featured">
                                        Отображать на главной
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Динамические атрибуты -->
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label text-timberwolf">
                                        Дополнительные атрибуты
                                    </label>
                                    <div id="attributesContainer">
                                        @php
                                            // Обработка old('attributes') - может быть в формате [['key' => '...', 'value' => '...']]
                                            $oldAttributes = old('attributes', []);
                                            $productAttributes = isset($product) && $product->attributes ? $product->attributes : [];
                                            
                                            // Нормализуем формат атрибутов
                                            $attributes = [];
                                            if (!empty($oldAttributes)) {
                                                // Если old('attributes') уже в правильном формате
                                                foreach ($oldAttributes as $index => $attr) {
                                                    if (is_array($attr) && isset($attr['key']) && isset($attr['value'])) {
                                                        $key = $attr['key'];
                                                        $value = $attr['value'];
                                                        // Преобразуем массив в строку, если нужно
                                                        if (is_array($value)) {
                                                            $value = json_encode($value, JSON_UNESCAPED_UNICODE);
                                                        }
                                                        $attributes[] = ['key' => $key, 'value' => (string)$value];
                                                    }
                                                }
                                            } elseif (!empty($productAttributes)) {
                                                // Если это атрибуты из БД (формат ['key' => 'value'])
                                                foreach ($productAttributes as $key => $value) {
                                                    // Преобразуем массив в строку, если нужно
                                                    if (is_array($value)) {
                                                        $value = json_encode($value, JSON_UNESCAPED_UNICODE);
                                                    }
                                                    $attributes[] = ['key' => (string)$key, 'value' => (string)$value];
                                                }
                                            }
                                        @endphp
                                        @if(!empty($attributes))
                                            @foreach($attributes as $index => $attr)
                                                <div class="row attribute-row mb-2">
                                                    <div class="col-md-5">
                                                        <input type="text" 
                                                               class="form-control bg-eerie-black text-white-smoke border-silver" 
                                                               name="attributes[{{ $index }}][key]" 
                                                               placeholder="Название атрибута"
                                                               value="{{ htmlspecialchars($attr['key'], ENT_QUOTES, 'UTF-8') }}">
                                                    </div>
                                                    <div class="col-md-5">
                                                        <input type="text" 
                                                               class="form-control bg-eerie-black text-white-smoke border-silver" 
                                                               name="attributes[{{ $index }}][value]" 
                                                               placeholder="Значение"
                                                               value="{{ htmlspecialchars($attr['value'], ENT_QUOTES, 'UTF-8') }}">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <button type="button" 
                                                                class="btn btn-outline-blood-red w-100 remove-attribute">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <button type="button" id="addAttribute" 
                                            class="btn btn-outline-timberwolf btn-sm mt-2">
                                        <i class="fas fa-plus me-1"></i>Добавить атрибут
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Изображения -->
                            <div class="col-12">
                                <label for="images" class="form-label text-timberwolf">
                                    Изображения товара
                                </label>
                                <input type="file" 
                                       class="form-control bg-eerie-black text-white-smoke border-silver" 
                                       id="images" name="images[]" 
                                       accept="image/*" multiple>
                                <small class="text-silver">
                                    Можно загрузить несколько изображений. Первое изображение будет главным.
                                    @if(isset($product) && $product->images->count() > 0)
                                        <br>Текущие изображения будут сохранены. Новые изображения будут добавлены.
                                    @endif
                                </small>
                                
                                @if(isset($product) && $product->images->count() > 0)
                                    <div class="row mt-2">
                                        <label class="text-timberwolf mb-2">Текущие изображения:</label>
                                        @foreach($product->images as $image)
                                            <div class="col-md-3 mb-2">
                                                <div class="position-relative">
                                                    <img src="{{ asset('storage/' . $image->image_path) }}" 
                                                         class="img-thumbnail border-silver" 
                                                         style="height: 100px; object-fit: cover;">
                                                    @if($image->is_main)
                                                        <span class="badge bg-imperial-red position-absolute top-0 start-0">Главное</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                
                                <div id="imagePreview" class="row mt-2"></div>
                            </div>
                        </div>
                        
                        <div class="mt-4 d-flex justify-content-between">
                            <a href="{{ route('products.index') }}" 
                               class="btn btn-outline-silver">
                                <i class="fas fa-arrow-left me-1"></i>Назад
                            </a>
                            <button type="submit" class="btn btn-gaming">
                                <i class="fas {{ isset($product) ? 'fa-save' : 'fa-plus-circle' }} me-1"></i>
                                {{ isset($product) ? 'Обновить товар' : 'Создать товар' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Show success toast after form submission if redirected from controller
    @if(session('success'))
        if (typeof showToast === 'function') {
            showToast('{{ session('success') }}', 'success');
        }
    @endif
    
    // Добавление динамических атрибутов
    @php
        $attributeCount = !empty($attributes) ? count($attributes) : 0;
    @endphp
    let attributeIndex = {{ $attributeCount }};
    
    document.getElementById('addAttribute').addEventListener('click', function() {
        const container = document.getElementById('attributesContainer');
        const html = `
            <div class="row attribute-row mb-2">
                <div class="col-md-5">
                    <input type="text" 
                           class="form-control bg-eerie-black text-white-smoke border-silver" 
                           name="attributes[${attributeIndex}][key]" 
                           placeholder="Название атрибута">
                </div>
                <div class="col-md-5">
                    <input type="text" 
                           class="form-control bg-eerie-black text-white-smoke border-silver" 
                           name="attributes[${attributeIndex}][value]" 
                           placeholder="Значение">
                </div>
                <div class="col-md-2">
                    <button type="button" 
                            class="btn btn-outline-blood-red w-100 remove-attribute">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
        attributeIndex++;
    });
    
    // Удаление атрибутов
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-attribute')) {
            e.target.closest('.attribute-row').remove();
        }
    });
    
    // Предпросмотр изображений
    document.getElementById('images').addEventListener('change', function(e) {
        const preview = document.getElementById('imagePreview');
        preview.innerHTML = '';
        
        Array.from(e.target.files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const col = document.createElement('div');
                col.className = 'col-md-3 mb-2';
                col.innerHTML = `
                    <div class="position-relative">
                        <img src="${e.target.result}" 
                             class="img-thumbnail border-silver" 
                             style="height: 100px; object-fit: cover;">
                        ${index === 0 ? '<span class="badge bg-imperial-red position-absolute top-0 start-0">Главное</span>' : ''}
                    </div>
                `;
                preview.appendChild(col);
            };
            reader.readAsDataURL(file);
        });
    });
</script>
@endpush