# Инструкция по использованию WebP поддержки

## Быстрый старт

### Автоматическая обработка
Просто загружайте изображения как обычно. Система автоматически:
1. Сохраняет оригинальное изображение
2. Создает WebP версию
3. Создает миниатюры с WebP версиями

### В Blade шаблонах

**Способ 1: С современной поддержкой WebP (рекомендуется)**
```blade
<picture>
    <source srcset="{{ asset($image->getWebpAssetPath()) }}" type="image/webp">
    <img src="{{ asset($image->getAssetPath()) }}" alt="Описание">
</picture>
```

**Способ 2: Простой (только оригинальное изображение)**
```blade
<img src="{{ asset($image->getAssetPath()) }}" alt="Описание">
```

## API Методов

### ProductImage модель

```php
// Получить путь к WebP версии (без 'storage/')
$webpPath = $image->getWebpPath();

// Получить полный путь для asset()
$assetPath = $image->getWebpAssetPath();

// Получить полный путь оригинального для asset()
$originalPath = $image->getAssetPath();
```

### ImageHelper класс

```php
use App\Helpers\ImageHelper;

// Получить путь к WebP
$webpPath = ImageHelper::getWebpPath('products/1/image.jpg');

// Проверить наличие WebP версии
if (ImageHelper::hasWebpVersion('products/1/image.jpg')) {
    // WebP существует
}

// Получить поддерживаемые форматы
$formats = ImageHelper::getSupportedMimes(); 
// ['jpeg', 'png', 'jpg', 'gif', 'webp']

// Максимальный размер файла
$maxSize = ImageHelper::getMaxFileSize(); // 2048KB
```

## Примеры использования

### Карусель изображений

```blade
<div id="carousel" class="carousel">
    @foreach($product->images as $image)
        <picture>
            <source srcset="{{ asset($image->getWebpAssetPath()) }}" type="image/webp">
            <img src="{{ asset($image->getAssetPath()) }}" 
                 alt="{{ $product->name }}">
        </picture>
    @endforeach
</div>
```

### Сетка товаров

```blade
@foreach($products as $product)
    @if($product->mainImage)
        <picture>
            <source srcset="{{ asset($product->mainImage->getWebpAssetPath()) }}" 
                    type="image/webp">
            <img src="{{ asset($product->mainImage->getAssetPath()) }}" 
                 alt="{{ $product->name }}">
        </picture>
    @endif
@endforeach
```

## Поддерживаемые форматы

Загружайте в любом из этих форматов:
- ✅ JPEG (.jpg, .jpeg)
- ✅ PNG (.png)
- ✅ GIF (.gif)
- ✅ WebP (.webp)

Все будут автоматически преобразованы в WebP версию.

## Проверка работы

### В браузере

1. Откройте DevTools (F12)
2. Перейдите на вкладку Network
3. Загрузите товар с изображением
4. Проверьте, что используется .webp файл в современных браузерах

### В PHP коде

```php
// Проверить, существует ли WebP версия
if (file_exists(storage_path('app/public/' . $image->getWebpPath()))) {
    echo 'WebP версия создана успешно';
}
```

## Требования к серверу

Нужно, чтобы был установлен:
- **GD** (встроен в PHP) - базовая поддержка, или
- **Imagick** - лучшая производительность (опционально)

Проверить:
```php
phpinfo();
// Ищите раздел "GD Support" или "Imagick"
```

## Оптимизация

### Размеры файлов (пример)

Качество WebP при сжатии 80 (по умолчанию):

| Формат | Размер | Примечание |
|--------|--------|-----------|
| JPEG   | 150 KB | Оригинал  |
| PNG    | 250 KB | Оригинал  |
| WebP   | 100 KB | -33% от JPEG |
| WebP   | 160 KB | -36% от PNG  |

### Для максимальной оптимизации

Используйте `<picture>` элемент вместо простого `<img>`. Браузер выберет лучший формат:

```blade
<picture>
    <source srcset="{{ asset($image->getWebpAssetPath()) }}" 
            type="image/webp">
    <source srcset="{{ asset($image->getAssetPath()) }}" 
            type="image/jpeg">
    <img src="{{ asset($image->getAssetPath()) }}" 
         alt="Описание">
</picture>
```

## Решение проблем

### WebP файлы не создаются

1. Проверьте, установлен ли GD:
   ```php
   extension_loaded('gd') // должен вернуть true
   ```

2. Проверьте права на запись в `storage/app/public/`

3. Проверьте логи Laravel в `storage/logs/`

### WebP не отображается в браузере

Это нормально для старых браузеров (IE11). Будет использовано оригинальное изображение.

### Производительность низкая

Установите Imagick для более быстрой конвертации:
```bash
sudo apt-get install php-imagick  # Ubuntu/Debian
```

## Дополнительные ресурсы

- [MDN: HTML picture element](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/picture)
- [WebP official](https://developers.google.com/speed/webp)
- [Browser support](https://caniuse.com/webp)
