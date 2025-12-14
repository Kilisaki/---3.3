@extends('layouts.app')

@section('title', 'Добавить товар')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card card-gaming">
                <div class="card-header bg-eerie-black text-white-smoke">
                    <h4 class="mb-0">
                        <i class="fas fa-plus-circle me-2"></i>Добавить новый товар
                    </h4>
                </div>
                
                <div class="card-body">
                    <form action="{{ route('products.store') }}" method="POST" 
                          enctype="multipart/form-data" id="productForm">
                        @csrf
                        
                        <div class="row g-3">
                            <!-- Название -->
                            <div class="col-md-6">
                                <label for="name" class="form-label text-timberwolf">
                                    Название товара *
                                </label>
                                <input type="text" class="form-control bg-eerie-black text-white-smoke border-silver" 
                                       id="name" name="name" 
                                       value="{{ old('name') }}" 
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
                                       value="{{ old('price') }}" required>
                            </div>
                            
                            <!-- Категория -->
                            <div class="col-md-6">
                                <label for="category" class="form-label text-timberwolf">
                                    Категория *
                                </label>
                                <select class="form-select bg-eerie-black text-white-smoke border-silver" 
                                        id="category" name="category" required>
                                    <option value="">Выберите категорию</option>
                                    @foreach($categories as $key => $category)
                                        <option value="{{ $key }}" 
                                                {{ old('category') == $key ? 'selected' : '' }}>
                                            {{ $category }}
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
                                       value="{{ old('brand') }}">
                            </div>
                            
                            <!-- Описание -->
                            <div class="col-12">
                                <label for="description" class="form-label text-timberwolf">
                                    Описание
                                </label>
                                <textarea class="form-control bg-eerie-black text-white-smoke border-silver" 
                                          id="description" name="description" 
                                          rows="3">{{ old('description') }}</textarea>
                            </div>
                            
                            <!-- Количество -->
                            <div class="col-md-6">
                                <label for="stock" class="form-label text-timberwolf">
                                    Количество на складе *
                                </label>
                                <input type="number" min="0" 
                                       class="form-control bg-eerie-black text-white-smoke border-silver" 
                                       id="stock" name="stock" 
                                       value="{{ old('stock', 0) }}" required>
                            </div>
                            
                            <!-- Особый товар -->
                            <div class="col-md-6">
                                <div class="form-check mt-4 pt-2">
                                    <input type="checkbox" class="form-check-input" 
                                           id="is_featured" name="is_featured" value="1"
                                           {{ old('is_featured') ? 'checked' : '' }}>
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
                                        @if(old('attributes'))
                                            @foreach(old('attributes') as $index => $attribute)
                                                <div class="row attribute-row mb-2">
                                                    <div class="col-md-5">
                                                        <input type="text" 
                                                               class="form-control bg-eerie-black text-white-smoke border-silver" 
                                                               name="attributes[{{ $index }}][key]" 
                                                               placeholder="Название атрибута"
                                                               value="{{ $attribute['key'] ?? '' }}">
                                                    </div>
                                                    <div class="col-md-5">
                                                        <input type="text" 
                                                               class="form-control bg-eerie-black text-white-smoke border-silver" 
                                                               name="attributes[{{ $index }}][value]" 
                                                               placeholder="Значение"
                                                               value="{{ $attribute['value'] ?? '' }}">
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
                                </small>
                                
                                <div id="imagePreview" class="row mt-2"></div>
                            </div>
                        </div>
                        
                        <div class="mt-4 d-flex justify-content-between">
                            <a href="{{ route('products.index') }}" 
                               class="btn btn-outline-silver">
                                <i class="fas fa-arrow-left me-1"></i>Назад
                            </a>
                            <button type="submit" class="btn btn-gaming">
                                <i class="fas fa-save me-1"></i>Сохранить товар
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
    // Добавление динамических атрибутов
    let attributeIndex = {{ old('attributes') ? count(old('attributes')) : 0 }};
    
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