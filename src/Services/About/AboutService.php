<?php

namespace Kakhura\CheckRequest\Services\About;

use Illuminate\Support\Arr;
use Kakhura\CheckRequest\Models\About\About;
use Kakhura\CheckRequest\Services\Service;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class AboutService extends Service
{
    /**
     * @param array $data
     * @return void
     */
    public function create(array $data)
    {
        $image = $this->uploadFile(Arr::get($data, 'image.0'), '/upload/about/');
        $videoImage = $this->uploadFile(Arr::get($data, 'video_image.0'), '/upload/about/');
        $about = About::create([
            'image' => Arr::get($image, 'fileName'),
            'thumb' => Arr::get($image, 'thumbFileName'),
            'video_image' => Arr::get($videoImage, 'fileName'),
            'video' => Arr::get($data, 'video'),
        ]);
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $about->detail()->create([
                'title' => Arr::get($data, 'title_' . $localeCode),
                'description' => Arr::get($data, 'description_' . $localeCode),
                'locale' => $localeCode,
            ]);
        }
    }

    /**
     * @param array $data
     * @param About $about
     * @return void
     */
    public function update(array $data, About $about)
    {
        $image = $this->uploadFile(Arr::get($data, 'image.0'), '/upload/about/', [public_path($about->image), public_path($about->thumb)], $about);
        $videoImage = $this->uploadFile(Arr::get($data, 'video_image.0'), '/upload/about/', [public_path($about->video_image)], $about, false, true, 'video_image');
        $about->update([
            'image' => Arr::get($image, 'fileName'),
            'thumb' => Arr::get($image, 'thumbFileName'),
            'video_image' => Arr::get($videoImage, 'fileName'),
            'video' => Arr::get($data, 'video'),
        ]);
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $about->detail()->where('locale', $localeCode)->first()->update([
                'title' => Arr::get($data, 'title_' . $localeCode),
                'description' => Arr::get($data, 'description_' . $localeCode),
            ]);
        }
    }
}
