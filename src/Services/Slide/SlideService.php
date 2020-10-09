<?php

namespace Kakhura\CheckRequest\Services\Slide;

use Illuminate\Support\Arr;
use Kakhura\CheckRequest\Models\Slide\Slide;
use Kakhura\CheckRequest\Services\Service;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class SlideService extends Service
{
    /**
     * @param array $data
     * @return void
     */
    public function create(array $data)
    {
        config(['kakhura.site-bases.images_thumbs.generate_thumb_for_images' => false]);
        $image = $this->uploadFile(Arr::get($data, 'image.0'), '/upload/slides/');
        $slide = Slide::create([
            'image' => Arr::get($image, 'fileName'),
            'published' => Arr::get($data, 'published') == 'on' ? true : false,
            'link' => Arr::get($data, 'link'),
        ]);

        $slide->update([
            'ordering' => $slide->id,
        ]);

        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $slide->detail()->create([
                'title' => Arr::get($data, 'title_' . $localeCode),
                'description' => Arr::get($data, 'description_' . $localeCode),
                'locale' => $localeCode,
            ]);
        }
    }

    /**
     * @param array $data
     * @param Slide $slide
     * @return bool
     */
    public function update(array $data, Slide $slide): bool
    {
        config(['kakhura.site-bases.images_thumbs.generate_thumb_for_images' => false]);
        $image = $this->uploadFile(Arr::get($data, 'image.0'), '/upload/slides/', [public_path($slide->image)], $slide);
        $update = $slide->update([
            'image' => Arr::get($image, 'fileName'),
            'published' => Arr::get($data, 'published') == 'on' ? true : false,
            'link' => Arr::get($data, 'link'),
        ]);
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $slide->detail()->where('locale', $localeCode)->first()->update([
                'title' => Arr::get($data, 'title_' . $localeCode),
                'description' => Arr::get($data, 'description_' . $localeCode),
            ]);
        }
        return $update;
    }

    /**
     * @param Slide $slide
     * @return boolean
     */
    public function delete(Slide $slide): bool
    {
        $this->deleteFiles([public_path($slide->image)]);
        $slide->detail()->delete();
        return $slide->delete();
    }
}
