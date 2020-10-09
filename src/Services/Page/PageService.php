<?php

namespace Kakhura\CheckRequest\Services\Page;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Kakhura\CheckRequest\Models\Page\Page;
use Kakhura\CheckRequest\Models\Page\PageImage;
use Kakhura\CheckRequest\Services\Service;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class PageService extends Service
{
    /**
     * @param array $data
     * @return void
     */
    public function create(array $data)
    {
        $image = $this->uploadFile(Arr::get($data, 'image.0'), '/upload/pages/');
        $videoImage = $this->uploadFile(Arr::get($data, 'video_image.0'), '/upload/pages/');
        /** @var Page $page */
        $page = Page::create([
            'image' => Arr::get($image, 'fileName'),
            'thumb' => Arr::get($image, 'thumbFileName'),
            'video_image' => Arr::get($videoImage, 'fileName'),
            'published' => Arr::get($data, 'published') == 'on' ? true : false,
            'in_main_menu' => Arr::get($data, 'in_main_menu') == 'on' ? true : false,
            'video' => Arr::get($data, 'video'),
        ]);
        $page->update([
            'ordering' => $page->id,
        ]);
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $page->detail()->create([
                'title' => Arr::get($data, 'title_' . $localeCode),
                'description' => Arr::get($data, 'description_' . $localeCode),
                'locale' => $localeCode,
            ]);
        }
        foreach (Arr::get($data, 'images', []) as $image) {
            $file = $this->uploadFile($image, '/upload/pages/');
            $page->images()->create([
                'image' => Arr::get($file, 'fileName'),
                'thumb' => Arr::get($file, 'thumbFileName'),
            ]);
        }
    }

    /**
     * @param array $data
     * @param Page $page
     * @return bool
     */
    public function update(array $data, Page $page): bool
    {
        $image = $this->uploadFile(Arr::get($data, 'image.0'), '/upload/pages/', [public_path($page->image), public_path($page->thumb)], $page);
        $videoImage = $this->uploadFile(Arr::get($data, 'video_image.0'), '/upload/pages/', [public_path($page->video_image)], $page, false, true, 'video_image');
        $update = $page->update([
            'image' => Arr::get($image, 'fileName'),
            'thumb' => Arr::get($image, 'thumbFileName'),
            'video_image' => Arr::get($videoImage, 'fileName'),
            'published' => Arr::get($data, 'published') == 'on' ? true : false,
            'in_main_menu' => Arr::get($data, 'in_main_menu') == 'on' ? true : false,
            'video' => Arr::get($data, 'video'),
        ]);
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $page->detail()->where('locale', $localeCode)->first()->update([
                'title' => Arr::get($data, 'title_' . $localeCode),
                'description' => Arr::get($data, 'description_' . $localeCode),
            ]);
        }
        foreach (Arr::get($data, 'images', []) as $image) {
            $file = $this->uploadFile($image, '/upload/pages/');
            $page->images()->create([
                'image' => Arr::get($file, 'fileName'),
                'thumb' => Arr::get($file, 'thumbFileName'),
            ]);
        }
        return $update;
    }

    /**
     * @param Page $page
     * @return boolean
     */
    public function delete(Page $page): bool
    {
        $this->deleteFiles([public_path($page->image), public_path($page->thumb), public_path($page->video_image)]);
        foreach ($page->images as $image) {
            $this->deleteFiles([public_path($image->image), public_path($image->thumb)]);
        }
        $page->detail()->delete();
        return $page->delete();
    }

    /**
     * @param Request $request
     * @return boolean
     */
    public function deleteImg(Request $request): bool
    {
        $image = PageImage::find($request->id);
        $this->deleteFiles([public_path($image->image), public_path($image->thumb)]);
        return $image->delete();
    }
}
