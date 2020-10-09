<?php

namespace Kakhura\CheckRequest\Services\Category;

use Illuminate\Support\Arr;
use Kakhura\CheckRequest\Models\Category\Category;
use Kakhura\CheckRequest\Services\Service;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class CategoryService extends Service
{
    /**
     * @param array $data
     * @return void
     */
    public function create(array $data)
    {
        $image = $this->uploadFile(Arr::get($data, 'image.0'), '/upload/categories/');
        /** @var Category $category */
        $category = Category::create([
            'image' => Arr::get($image, 'fileName'),
            'thumb' => Arr::get($image, 'thumbFileName'),
            'published' => Arr::get($data, 'published') == 'on' ? true : false,
            'parent_id' => Arr::get($data, 'parent_id'),
        ]);
        $category->update([
            'ordering' => $category->id,
        ]);
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $category->detail()->create([
                'title' => Arr::get($data, 'title_' . $localeCode),
                'description' => Arr::get($data, 'description_' . $localeCode),
                'locale' => $localeCode,
            ]);
        }
    }

    /**
     * @param array $data
     * @param Category $category
     * @return bool
     */
    public function update(array $data, Category $category): bool
    {
        $image = $this->uploadFile(Arr::get($data, 'image.0'), '/upload/categories/', [public_path($category->image), public_path($category->thumb)], $category);
        $update = $category->update([
            'image' => Arr::get($image, 'fileName'),
            'thumb' => Arr::get($image, 'thumbFileName'),
            'published' => Arr::get($data, 'published') == 'on' ? true : false,
            'parent_id' => Arr::get($data, 'parent_id'),
        ]);
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $category->detail()->where('locale', $localeCode)->first()->update([
                'title' => Arr::get($data, 'title_' . $localeCode),
                'description' => Arr::get($data, 'description_' . $localeCode),
            ]);
        }
        return $update;
    }

    /**
     * @param Category $category
     * @return boolean
     */
    public function delete(Category $category): bool
    {
        $this->deleteFiles([public_path($category->image), public_path($category->thumb)]);
        $category->detail()->delete();
        return $category->delete();
    }
}
