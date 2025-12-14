<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{
    // Валидационные правила
    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'attributes' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_featured' => 'boolean'
        ];
    }

    // Главная страница со списком продуктов
    public function index()
    {
        $products = Product::with('images')->latest()->paginate(12);
        return view('products.index', compact('products'));
    }

    // Страница создания продукта
    public function create()
    {
        $categories = $this->getCategories();
        return view('products.create', compact('categories'));
    }

    // Сохранение нового продукта
    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());
        
        // Обработка JSON атрибутов
        if ($request->has('attributes')) {
            $attributes = [];
            foreach ($request->input('attributes') as $key => $value) {
                if (!empty($value['key']) && !empty($value['value'])) {
                    $attributes[$value['key']] = $value['value'];
                }
            }
            $validated['attributes'] = $attributes;
        }

        // Создание продукта
        $product = Product::create($validated);

        // Обработка загрузки изображений
        if ($request->hasFile('images')) {
            $this->processImages($request->file('images'), $product);
        }

        return redirect()->route('products.index')
            ->with('success', 'Товар успешно добавлен!');
        
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            // Сохранит в storage/app/public/products/[filename]
            
            // Для получения URL:
            $url = Storage::disk('public')->url($path);
            // Вернет: /storage/products/[filename]
    }
    }

    // Просмотр деталей продукта
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    // Страница редактирования
    public function edit(Product $product)
    {
        $categories = $this->getCategories();
        return view('products.edit', compact('product', 'categories'));
    }

    // Обновление продукта
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate($this->rules());
        
        // Обработка JSON атрибутов
        if ($request->has('attributes')) {
            $attributes = [];
            foreach ($request->input('attributes') as $key => $value) {
                if (!empty($value['key']) && !empty($value['value'])) {
                    $attributes[$value['key']] = $value['value'];
                }
            }
            $validated['attributes'] = $attributes;
        }

        // Обновление продукта
        $product->update($validated);

        // Обработка новых изображений
        if ($request->hasFile('images')) {
            $this->processImages($request->file('images'), $product);
        }

        return redirect()->route('products.index')
            ->with('success', 'Товар успешно обновлен!');
    }

    // Удаление продукта (Soft Delete)
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')
            ->with('success', 'Товар успешно удален!');
    }

    // Восстановление продукта
    public function restore($id)
    {
        Product::withTrashed()->findOrFail($id)->restore();
        return redirect()->route('products.index')
            ->with('success', 'Товар успешно восстановлен!');
    }

    // Полное удаление
    public function forceDelete($id)
    {
        $product = Product::withTrashed()->findOrFail($id);
        
        // Удаление изображений
        foreach ($product->images as $image) {
            Storage::delete('public/' . $image->image_path);
        }
        
        $product->forceDelete();
        return redirect()->route('products.index')
            ->with('success', 'Товар полностью удален!');
    }

    // Вспомогательные методы
    private function getCategories()
    {
        return [
            'keyboards' => 'Клавиатуры',
            'mice' => 'Мыши',
            'headsets' => 'Наушники',
            'mousepads' => 'Коврики для мыши',
            'controllers' => 'Контроллеры',
            'monitors' => 'Мониторы',
            'chairs' => 'Кресла',
            'accessories' => 'Аксессуары'
        ];
    }

private function processImages($images, $product)
{
    foreach ($images as $index => $image) {
        $filename = time() . '_' . $image->getClientOriginalName();
        $path = 'products/' . $product->id . '/' . $filename;
        
        // Сохранение оригинального изображения
        $image->storeAs('public/' . $path);
        
        // Создание миниатюры с помощью GD (встроенный PHP)
        if (extension_loaded('gd')) {
            $this->createThumbnail($image, $product->id, $filename);
        }
        
        // Создание записи в БД
        ProductImage::create([
            'product_id' => $product->id,
            'image_path' => $path,
            'is_main' => $index === 0
        ]);
    }
}

private function createThumbnail($image, $productId, $filename)
{
    $sourcePath = $image->getRealPath();
    $thumbnailPath = storage_path('app/public/products/' . $productId . '/thumbnail_' . $filename);
    
    // Создаем директорию если не существует
    if (!file_exists(dirname($thumbnailPath))) {
        mkdir(dirname($thumbnailPath), 0755, true);
    }
    
    // Получаем информацию об изображении
    list($width, $height, $type) = getimagesize($sourcePath);
    
    // Создаем изображение в зависимости от типа
    switch ($type) {
        case IMAGETYPE_JPEG:
            $source = imagecreatefromjpeg($sourcePath);
            break;
        case IMAGETYPE_PNG:
            $source = imagecreatefrompng($sourcePath);
            break;
        case IMAGETYPE_GIF:
            $source = imagecreatefromgif($sourcePath);
            break;
        default:
            return; // Не поддерживаемый тип
    }
    
    // Размеры миниатюры
    $newWidth = 400;
    $newHeight = 400;
    
    // Создаем новое изображение для миниатюры
    $thumbnail = imagecreatetruecolor($newWidth, $newHeight);
    
    // Для PNG сохраняем прозрачность
    if ($type == IMAGETYPE_PNG || $type == IMAGETYPE_GIF) {
        imagealphablending($thumbnail, false);
        imagesavealpha($thumbnail, true);
        $transparent = imagecolorallocatealpha($thumbnail, 0, 0, 0, 127);
        imagefill($thumbnail, 0, 0, $transparent);
    }
    
    // Изменяем размер с сохранением пропорций
    imagecopyresampled($thumbnail, $source, 0, 0, 0, 0, 
        $newWidth, $newHeight, $width, $height);
    
    // Сохраняем миниатюру
    switch ($type) {
        case IMAGETYPE_JPEG:
            imagejpeg($thumbnail, $thumbnailPath, 90);
            break;
        case IMAGETYPE_PNG:
            imagepng($thumbnail, $thumbnailPath, 9);
            break;
        case IMAGETYPE_GIF:
            imagegif($thumbnail, $thumbnailPath);
            break;
    }
    
    // Освобождаем память
    imagedestroy($source);
    imagedestroy($thumbnail);
}
}