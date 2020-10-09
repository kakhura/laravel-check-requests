<?php

namespace Kakhura\CheckRequest\Services\Brand;

use Illuminate\Support\Arr;
use Kakhura\CheckRequest\Models\Brand\Brand;
use Kakhura\CheckRequest\Services\Service;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class BrandService extends Service
{
    /**
     * @param array $data
     * @return void
     */
    public function create(array $data)
    {
        $image = $this->uploadFile(Arr::get($data, 'image.0'), '/upload/brands/');
        $videoImage = $this->uploadFile(Arr::get($data, 'video_image.0'), '/upload/brands/');
        /** @var Brand $brand */
        $brand = Brand::create([
            'image' => Arr::get($image, 'fileName'),
            'thumb' => Arr::get($image, 'thumbFileName'),
            'video_image' => Arr::get($videoImage, 'fileName'),
            'published' => Arr::get($data, 'published') == 'on' ? true : false,
            'show_on_brands' => Arr::get($data, 'show_on_brands') == 'on' ? true : false,
            'video' => Arr::get($data, 'video'),
            'link' => Arr::get($data, 'link'),
        ]);
        $brand->update([
            'ordering' => $brand->id,
        ]);
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $brand->detail()->create([
                'title' => Arr::get($data, 'title_' . $localeCode),
                'description' => Arr::get($data, 'description_' . $localeCode),
                'locale' => $localeCode,
            ]);
        }
    }

    /**
     * @param array $data
     * @param Brand $brand
     * @return bool
     */
    public function update(array $data, Brand $brand): bool
    {
        $image = $this->uploadFile(Arr::get($data, 'image.0'), '/upload/brands/', [public_path($brand->image), public_path($brand->thumb)], $brand);
        $videoImage = $this->uploadFile(Arr::get($data, 'video_image.0'), '/upload/brands/', [public_path($brand->video_image)], $brand, false, true, 'video_image');
        $update = $brand->update([
            'image' => Arr::get($image, 'fileName'),
            'thumb' => Arr::get($image, 'thumbFileName'),
            'video_image' => Arr::get($videoImage, 'fileName'),
            'published' => Arr::get($data, 'published') == 'on' ? true : false,
            'show_on_brands' => Arr::get($data, 'show_on_brands') == 'on' ? true : false,
            'video' => Arr::get($data, 'video'),
            'link' => Arr::get($data, 'link'),
        ]);
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $brand->detail()->where('locale', $localeCode)->first()->update([
                'title' => Arr::get($data, 'title_' . $localeCode),
                'description' => Arr::get($data, 'description_' . $localeCode),
            ]);
        }
        return $update;
    }

    /**
     * @param Brand $brand
     * @return boolean
     */
    public function delete(Brand $brand): bool
    {
        $this->deleteFiles([public_path($brand->image), public_path($brand->thumb), public_path($brand->video_image)]);
        $brand->detail()->delete();
        return $brand->delete();
    }
}
