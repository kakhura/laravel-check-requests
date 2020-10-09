<?php

namespace Kakhura\CheckRequest\Services\Service;

use Illuminate\Support\Arr;
use Kakhura\CheckRequest\Models\Service\Service;
use Kakhura\CheckRequest\Services\Service as BaseService;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class ServiceService extends BaseService
{
    /**
     * @param array $data
     * @return void
     */
    public function create(array $data)
    {
        $image = $this->uploadFile(Arr::get($data, 'image.0'), '/upload/services/');
        $videoImage = $this->uploadFile(Arr::get($data, 'video_image.0'), '/upload/services/');
        $service = Service::create([
            'image' => Arr::get($image, 'fileName'),
            'thumb' => Arr::get($image, 'thumbFileName'),
            'video_image' => Arr::get($videoImage, 'fileName'),
            'published' => Arr::get($data, 'published') == 'on' ? true : false,
            'video' => Arr::get($data, 'video'),
        ]);

        $service->update([
            'ordering' => $service->id,
        ]);

        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $service->detail()->create([
                'title' => Arr::get($data, 'title_' . $localeCode),
                'description' => Arr::get($data, 'description_' . $localeCode),
                'locale' => $localeCode,
            ]);
        }
    }

    /**
     * @param array $data
     * @param Service $service
     * @return bool
     */
    public function update(array $data, Service $service): bool
    {
        $image = $this->uploadFile(Arr::get($data, 'image.0'), '/upload/services/', [public_path($service->image), public_path($service->thumb)], $service);
        $videoImage = $this->uploadFile(Arr::get($data, 'video_image.0'), '/upload/services/', [public_path($service->video_image)], $service, false, true, 'video_image');
        $update = $service->update([
            'image' => Arr::get($image, 'fileName'),
            'thumb' => Arr::get($image, 'thumbFileName'),
            'video_image' => Arr::get($videoImage, 'fileName'),
            'published' => Arr::get($data, 'published') == 'on' ? true : false,
            'video' => Arr::get($data, 'video'),
        ]);
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $service->detail()->where('locale', $localeCode)->first()->update([
                'title' => Arr::get($data, 'title_' . $localeCode),
                'description' => Arr::get($data, 'description_' . $localeCode),
            ]);
        }
        return $update;
    }

    /**
     * @param Service $service
     * @return boolean
     */
    public function delete(Service $service): bool
    {
        $this->deleteFiles([public_path($service->image), public_path($service->thumb), public_path($service->video_image)]);
        $service->detail()->delete();
        return $service->delete();
    }
}
