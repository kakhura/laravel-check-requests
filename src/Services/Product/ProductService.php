<?php

namespace Kakhura\CheckRequest\Services\Product;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Kakhura\CheckRequest\Models\Product\Product;
use Kakhura\CheckRequest\Models\Product\ProductImage;
use Kakhura\CheckRequest\Services\Service;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class ProductService extends Service
{
    /**
     * @param array $data
     * @return void
     */
    public function create(array $data)
    {
        $image = $this->uploadFile(Arr::get($data, 'image.0'), '/upload/products/');
        $videoImage = $this->uploadFile(Arr::get($data, 'video_image.0'), '/upload/products/');
        /** @var Product $product */
        $product = Product::create([
            'image' => Arr::get($image, 'fileName'),
            'thumb' => Arr::get($image, 'thumbFileName'),
            'video_image' => Arr::get($videoImage, 'fileName'),
            'published' => Arr::get($data, 'published') == 'on' ? true : false,
            'video' => Arr::get($data, 'video'),
            'price' => Arr::get($data, 'price'),
            'discounted_price' => Arr::get($data, 'discounted_price'),
            'category_id' => Arr::get($data, 'category_id'),
        ]);
        $product->update([
            'ordering' => $product->id,
        ]);
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $product->detail()->create([
                'title' => Arr::get($data, 'title_' . $localeCode),
                'description' => Arr::get($data, 'description_' . $localeCode),
                'locale' => $localeCode,
            ]);
        }
        foreach (Arr::get($data, 'images', []) as $image) {
            $file = $this->uploadFile($image, '/upload/products/');
            $product->images()->create([
                'image' => Arr::get($file, 'fileName'),
                'thumb' => Arr::get($file, 'thumbFileName'),
            ]);
        }
    }

    /**
     * @param array $data
     * @param Product $product
     * @return bool
     */
    public function update(array $data, Product $product): bool
    {
        $image = $this->uploadFile(Arr::get($data, 'image.0'), '/upload/products/', [public_path($product->image), public_path($product->thumb)], $product);
        $videoImage = $this->uploadFile(Arr::get($data, 'video_image.0'), '/upload/products/', [public_path($product->video_image)], $product, false, true, 'video_image');
        $update = $product->update([
            'image' => Arr::get($image, 'fileName'),
            'thumb' => Arr::get($image, 'thumbFileName'),
            'video_image' => Arr::get($videoImage, 'fileName'),
            'published' => Arr::get($data, 'published') == 'on' ? true : false,
            'video' => Arr::get($data, 'video'),
            'price' => Arr::get($data, 'price'),
            'discounted_price' => Arr::get($data, 'discounted_price'),
            'category_id' => Arr::get($data, 'category_id'),
        ]);
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $product->detail()->where('locale', $localeCode)->first()->update([
                'title' => Arr::get($data, 'title_' . $localeCode),
                'description' => Arr::get($data, 'description_' . $localeCode),
            ]);
        }
        foreach (Arr::get($data, 'images', []) as $image) {
            $file = $this->uploadFile($image, '/upload/products/');
            $product->images()->create([
                'image' => Arr::get($file, 'fileName'),
                'thumb' => Arr::get($file, 'thumbFileName'),
            ]);
        }
        return $update;
    }

    /**
     * @param Product $product
     * @return boolean
     */
    public function delete(Product $product): bool
    {
        $this->deleteFiles([public_path($product->image), public_path($product->thumb), public_path($product->video_image)]);
        foreach ($product->images as $image) {
            $this->deleteFiles([public_path($image->image), public_path($image->thumb)]);
        }
        $product->detail()->delete();
        return $product->delete();
    }

    /**
     * @param Request $request
     * @return boolean
     */
    public function deleteImg(Request $request): bool
    {
        $image = ProductImage::find($request->id);
        $this->deleteFiles([public_path($image->image), public_path($image->thumb)]);
        return $image->delete();
    }
}
