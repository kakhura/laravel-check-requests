<?php

namespace Kakhura\CheckRequest\Services\Photo;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Kakhura\CheckRequest\Models\Photo\Photo;
use Kakhura\CheckRequest\Models\Photo\PhotoImage;
use Kakhura\CheckRequest\Services\Service;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class PhotoService extends Service
{
    /**
     * @param array $data
     * @return void
     */
    public function create(array $data)
    {
        $image = $this->uploadFile(Arr::get($data, 'image.0'), '/upload/photos/');
        $videoImage = $this->uploadFile(Arr::get($data, 'video_image.0'), '/upload/photos/');
        /** @var Photo $photo */
        $photo = Photo::create([
            'image' => Arr::get($image, 'fileName'),
            'thumb' => Arr::get($image, 'thumbFileName'),
            'video_image' => Arr::get($videoImage, 'fileName'),
            'published' => Arr::get($data, 'published') == 'on' ? true : false,
            'published_at' => Arr::get($data, 'published_at', now()),
            'video' => Arr::get($data, 'video'),
        ]);
        $photo->update([
            'ordering' => $photo->id,
        ]);
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $photo->detail()->create([
                'title' => Arr::get($data, 'title_' . $localeCode),
                'description' => Arr::get($data, 'description_' . $localeCode),
                'locale' => $localeCode,
            ]);
        }
        foreach (Arr::get($data, 'images', []) as $image) {
            $file = $this->uploadFile($image, '/upload/photos/');
            $photo->images()->create([
                'image' => Arr::get($file, 'fileName'),
                'thumb' => Arr::get($file, 'thumbFileName'),
            ]);
        }
    }

    /**
     * @param array $data
     * @param Photo $photo
     * @return bool
     */
    public function update(array $data, Photo $photo): bool
    {
        $image = $this->uploadFile(Arr::get($data, 'image.0'), '/upload/photos/', [public_path($photo->image), public_path($photo->thumb)], $photo);
        $videoImage = $this->uploadFile(Arr::get($data, 'video_image.0'), '/upload/photos/', [public_path($photo->video_image)], $photo, false, true, 'video_image');
        $update = $photo->update([
            'image' => Arr::get($image, 'fileName'),
            'thumb' => Arr::get($image, 'thumbFileName'),
            'video_image' => Arr::get($videoImage, 'fileName'),
            'published' => Arr::get($data, 'published') == 'on' ? true : false,
            'published_at' => Arr::get($data, 'published_at', now()),
            'video' => Arr::get($data, 'video'),
        ]);
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $photo->detail()->where('locale', $localeCode)->first()->update([
                'title' => Arr::get($data, 'title_' . $localeCode),
                'description' => Arr::get($data, 'description_' . $localeCode),
            ]);
        }
        foreach (Arr::get($data, 'images', []) as $image) {
            $file = $this->uploadFile($image, '/upload/photos/');
            $photo->images()->create([
                'image' => Arr::get($file, 'fileName'),
                'thumb' => Arr::get($file, 'thumbFileName'),
            ]);
        }
        return $update;
    }

    /**
     * @param Photo $photo
     * @return boolean
     */
    public function delete(Photo $photo): bool
    {
        $this->deleteFiles([public_path($photo->image), public_path($photo->thumb), public_path($photo->video_image)]);
        foreach ($photo->images as $image) {
            $this->deleteFiles([public_path($image->image), public_path($image->thumb)]);
        }
        $photo->detail()->delete();
        return $photo->delete();
    }

    /**
     * @param Request $request
     * @return boolean
     */
    public function deleteImg(Request $request): bool
    {
        $image = PhotoImage::find($request->id);
        $this->deleteFiles([public_path($image->image), public_path($image->thumb)]);
        return $image->delete();
    }
}
