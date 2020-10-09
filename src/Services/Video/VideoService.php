<?php

namespace Kakhura\CheckRequest\Services\Video;

use Illuminate\Support\Arr;
use Kakhura\CheckRequest\Models\Video\Video;
use Kakhura\CheckRequest\Services\Service;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class VideoService extends Service
{
    /**
     * @param array $data
     * @return void
     */
    public function create(array $data)
    {
        $image = $this->uploadFile(Arr::get($data, 'image.0'), '/upload/videos/');
        $video = Video::create([
            'image' => Arr::get($image, 'fileName'),
            'thumb' => Arr::get($image, 'thumbFileName'),
            'published' => Arr::get($data, 'published') == 'on' ? true : false,
            'video_url' => Arr::get($data, 'video_url'),
        ]);

        $video->update([
            'ordering' => $video->id,
        ]);

        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $video->detail()->create([
                'title' => Arr::get($data, 'title_' . $localeCode),
                'locale' => $localeCode,
            ]);
        }
    }

    /**
     * @param array $data
     * @param Video $video
     * @return bool
     */
    public function update(array $data, Video $video): bool
    {
        $image = $this->uploadFile(Arr::get($data, 'image.0'), '/upload/videos/', [public_path($video->image), public_path($video->thumb)], $video);
        $update = $video->update([
            'image' => Arr::get($image, 'fileName'),
            'thumb' => Arr::get($image, 'thumbFileName'),
            'published' => Arr::get($data, 'published') == 'on' ? true : false,
            'video_url' => Arr::get($data, 'video_url'),
        ]);
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $video->detail()->where('locale', $localeCode)->first()->update([
                'title' => Arr::get($data, 'title_' . $localeCode),
            ]);
        }
        return $update;
    }

    /**
     * @param Video $video
     * @return boolean
     */
    public function delete(Video $video): bool
    {
        $this->deleteFiles([public_path($video->image), public_path($video->thumb)]);
        $video->detail()->delete();
        return $video->delete();
    }
}
