<?php

namespace Kakhura\CheckRequest\Services\News;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Kakhura\CheckRequest\Models\News\News;
use Kakhura\CheckRequest\Models\News\NewsImage;
use Kakhura\CheckRequest\Services\Service;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class NewsService extends Service
{
    /**
     * @param array $data
     * @return void
     */
    public function create(array $data)
    {
        $image = $this->uploadFile(Arr::get($data, 'image.0'), '/upload/news/');
        $videoImage = $this->uploadFile(Arr::get($data, 'video_image.0'), '/upload/news/');
        /** @var News $news */
        $news = News::create([
            'image' => Arr::get($image, 'fileName'),
            'thumb' => Arr::get($image, 'thumbFileName'),
            'video_image' => Arr::get($videoImage, 'fileName'),
            'published' => Arr::get($data, 'published') == 'on' ? true : false,
            'published_at' => Arr::get($data, 'published_at', now()),
            'video' => Arr::get($data, 'video'),
            'photo_id' => Arr::get($data, 'photo_id'),
            'photo_position' => Arr::get($data, 'photo_position'),
        ]);
        $news->update([
            'ordering' => $news->id,
        ]);
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $news->detail()->create([
                'title' => Arr::get($data, 'title_' . $localeCode),
                'description' => Arr::get($data, 'description_' . $localeCode),
                'locale' => $localeCode,
            ]);
        }
        foreach (Arr::get($data, 'images', []) as $image) {
            $file = $this->uploadFile($image, '/upload/news/');
            $news->images()->create([
                'image' => Arr::get($file, 'fileName'),
                'thumb' => Arr::get($file, 'thumbFileName'),
            ]);
        }
    }

    /**
     * @param array $data
     * @param News $news
     * @return bool
     */
    public function update(array $data, News $news): bool
    {
        $image = $this->uploadFile(Arr::get($data, 'image.0'), '/upload/news/', [public_path($news->image), public_path($news->thumb)], $news);
        $videoImage = $this->uploadFile(Arr::get($data, 'video_image.0'), '/upload/news/', [public_path($news->video_image)], $news, false, true, 'video_image');
        $update = $news->update([
            'image' => Arr::get($image, 'fileName'),
            'thumb' => Arr::get($image, 'thumbFileName'),
            'video_image' => Arr::get($videoImage, 'fileName'),
            'published' => Arr::get($data, 'published') == 'on' ? true : false,
            'published_at' => Arr::get($data, 'published_at', now()),
            'video' => Arr::get($data, 'video'),
            'photo_id' => Arr::get($data, 'photo_id'),
            'photo_position' => Arr::get($data, 'photo_position'),
        ]);
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $news->detail()->where('locale', $localeCode)->first()->update([
                'title' => Arr::get($data, 'title_' . $localeCode),
                'description' => Arr::get($data, 'description_' . $localeCode),
            ]);
        }
        foreach (Arr::get($data, 'images', []) as $image) {
            $file = $this->uploadFile($image, '/upload/news/');
            $news->images()->create([
                'image' => Arr::get($file, 'fileName'),
                'thumb' => Arr::get($file, 'thumbFileName'),
            ]);
        }
        return $update;
    }

    /**
     * @param News $news
     * @return boolean
     */
    public function delete(News $news): bool
    {
        $this->deleteFiles([public_path($news->image), public_path($news->thumb), public_path($news->video_image)]);
        foreach ($news->images as $image) {
            $this->deleteFiles([public_path($image->image), public_path($image->thumb)]);
        }
        $news->detail()->delete();
        return $news->delete();
    }

    /**
     * @param Request $request
     * @return boolean
     */
    public function deleteImg(Request $request): bool
    {
        $image = NewsImage::find($request->id);
        $this->deleteFiles([public_path($image->image), public_path($image->thumb)]);
        return $image->delete();
    }
}
