<?php

namespace Kakhura\CheckRequest\Services\Blog;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Kakhura\CheckRequest\Models\Blog\Blog;
use Kakhura\CheckRequest\Models\Blog\BlogImage;
use Kakhura\CheckRequest\Services\Service;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class BlogService extends Service
{
    /**
     * @param array $data
     * @return void
     */
    public function create(array $data)
    {
        $image = $this->uploadFile(Arr::get($data, 'image.0'), '/upload/blogs/');
        $videoImage = $this->uploadFile(Arr::get($data, 'video_image.0'), '/upload/blogs/');
        /** @var Blog $blog */
        $blog = Blog::create([
            'image' => Arr::get($image, 'fileName'),
            'thumb' => Arr::get($image, 'thumbFileName'),
            'video_image' => Arr::get($videoImage, 'fileName'),
            'published' => Arr::get($data, 'published') == 'on' ? true : false,
            'published_at' => Arr::get($data, 'published_at', now()),
            'video' => Arr::get($data, 'video'),
            'photo_id' => Arr::get($data, 'photo_id'),
        ]);
        $blog->update([
            'ordering' => $blog->id,
        ]);
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $blog->detail()->create([
                'title' => Arr::get($data, 'title_' . $localeCode),
                'description' => Arr::get($data, 'description_' . $localeCode),
                'locale' => $localeCode,
            ]);
        }
        foreach (Arr::get($data, 'images', []) as $image) {
            $file = $this->uploadFile($image, '/upload/blogs/');
            $blog->images()->create([
                'image' => Arr::get($file, 'fileName'),
                'thumb' => Arr::get($file, 'thumbFileName'),
            ]);
        }
    }

    /**
     * @param array $data
     * @param Blog $blog
     * @return bool
     */
    public function update(array $data, Blog $blog): bool
    {
        $image = $this->uploadFile(Arr::get($data, 'image.0'), '/upload/blogs/', [public_path($blog->image), public_path($blog->thumb)], $blog);
        $videoImage = $this->uploadFile(Arr::get($data, 'video_image.0'), '/upload/blogs/', [public_path($blog->video_image)], $blog, false, true, 'video_image');
        $update = $blog->update([
            'image' => Arr::get($image, 'fileName'),
            'thumb' => Arr::get($image, 'thumbFileName'),
            'video_image' => Arr::get($videoImage, 'fileName'),
            'published' => Arr::get($data, 'published') == 'on' ? true : false,
            'published_at' => Arr::get($data, 'published_at', now()),
            'video' => Arr::get($data, 'video'),
            'photo_id' => Arr::get($data, 'photo_id'),
        ]);
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $blog->detail()->where('locale', $localeCode)->first()->update([
                'title' => Arr::get($data, 'title_' . $localeCode),
                'description' => Arr::get($data, 'description_' . $localeCode),
            ]);
        }
        foreach (Arr::get($data, 'images', []) as $image) {
            $file = $this->uploadFile($image, '/upload/blogs/');
            $blog->images()->create([
                'image' => Arr::get($file, 'fileName'),
                'thumb' => Arr::get($file, 'thumbFileName'),
            ]);
        }
        return $update;
    }

    /**
     * @param Blog $blog
     * @return boolean
     */
    public function delete(Blog $blog): bool
    {
        $this->deleteFiles([public_path($blog->image), public_path($blog->thumb), public_path($blog->video_image)]);
        foreach ($blog->images as $image) {
            $this->deleteFiles([public_path($image->image), public_path($image->thumb)]);
        }
        $blog->detail()->delete();
        return $blog->delete();
    }

    /**
     * @param Request $request
     * @return boolean
     */
    public function deleteImg(Request $request): bool
    {
        $image = BlogImage::find($request->id);
        $this->deleteFiles([public_path($image->image), public_path($image->thumb)]);
        return $image->delete();
    }
}
