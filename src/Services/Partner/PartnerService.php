<?php

namespace Kakhura\CheckRequest\Services\Partner;

use Illuminate\Support\Arr;
use Kakhura\CheckRequest\Models\Partner\Partner;
use Kakhura\CheckRequest\Services\Service;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class PartnerService extends Service
{
    /**
     * @param array $data
     * @return void
     */
    public function create(array $data)
    {
        $image = $this->uploadFile(Arr::get($data, 'image.0'), '/upload/partners/');
        $videoImage = $this->uploadFile(Arr::get($data, 'video_image.0'), '/upload/partners/');
        /** @var Partner $partner */
        $partner = Partner::create([
            'image' => Arr::get($image, 'fileName'),
            'thumb' => Arr::get($image, 'thumbFileName'),
            'video_image' => Arr::get($videoImage, 'fileName'),
            'published' => Arr::get($data, 'published') == 'on' ? true : false,
            'video' => Arr::get($data, 'video'),
            'link' => Arr::get($data, 'link'),
        ]);
        $partner->update([
            'ordering' => $partner->id,
        ]);
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $partner->detail()->create([
                'title' => Arr::get($data, 'title_' . $localeCode),
                'description' => Arr::get($data, 'description_' . $localeCode),
                'locale' => $localeCode,
            ]);
        }
    }

    /**
     * @param array $data
     * @param Partner $partner
     * @return bool
     */
    public function update(array $data, Partner $partner): bool
    {
        $image = $this->uploadFile(Arr::get($data, 'image.0'), '/upload/partners/', [public_path($partner->image), public_path($partner->thumb)], $partner);
        $videoImage = $this->uploadFile(Arr::get($data, 'video_image.0'), '/upload/partners/', [public_path($partner->video_image)], $partner, false, true, 'video_image');
        $update = $partner->update([
            'image' => Arr::get($image, 'fileName'),
            'thumb' => Arr::get($image, 'thumbFileName'),
            'video_image' => Arr::get($videoImage, 'fileName'),
            'published' => Arr::get($data, 'published') == 'on' ? true : false,
            'video' => Arr::get($data, 'video'),
            'link' => Arr::get($data, 'link'),
        ]);
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $partner->detail()->where('locale', $localeCode)->first()->update([
                'title' => Arr::get($data, 'title_' . $localeCode),
                'description' => Arr::get($data, 'description_' . $localeCode),
            ]);
        }
        return $update;
    }

    /**
     * @param Partner $partner
     * @return boolean
     */
    public function delete(Partner $partner): bool
    {
        $this->deleteFiles([public_path($partner->image), public_path($partner->thumb), public_path($partner->video_image)]);
        $partner->detail()->delete();
        return $partner->delete();
    }
}
