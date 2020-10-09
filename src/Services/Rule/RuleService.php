<?php

namespace Kakhura\CheckRequest\Services\Rule;

use Illuminate\Support\Arr;
use Kakhura\CheckRequest\Models\Rule\Rule;
use Kakhura\CheckRequest\Services\Service;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class RuleService extends Service
{
    /**
     * @param array $data
     * @return void
     */
    public function create(array $data)
    {
        $image = $this->uploadFile(Arr::get($data, 'image.0'), '/upload/rules/');
        $videoImage = $this->uploadFile(Arr::get($data, 'video_image.0'), '/upload/rules/');
        $rules = Rule::create([
            'image' => Arr::get($image, 'fileName'),
            'thumb' => Arr::get($image, 'thumbFileName'),
            'video_image' => Arr::get($videoImage, 'fileName'),
            'video' => Arr::get($data, 'video'),
        ]);
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $rules->detail()->create([
                'title' => Arr::get($data, 'title_' . $localeCode),
                'description' => Arr::get($data, 'description_' . $localeCode),
                'locale' => $localeCode,
            ]);
        }
    }

    /**
     * @param array $data
     * @param Rule $rules
     * @return void
     */
    public function update(array $data, Rule $rules)
    {
        $image = $this->uploadFile(Arr::get($data, 'image.0'), '/upload/rules/', [public_path($rules->image), public_path($rules->thumb)], $rules);
        $videoImage = $this->uploadFile(Arr::get($data, 'video_image.0'), '/upload/rules/', [public_path($rules->video_image)], $rules, false, true, 'video_image');
        $rules->update([
            'image' => Arr::get($image, 'fileName'),
            'thumb' => Arr::get($image, 'thumbFileName'),
            'video_image' => Arr::get($videoImage, 'fileName'),
            'video' => Arr::get($data, 'video'),
        ]);
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $rules->detail()->where('locale', $localeCode)->first()->update([
                'title' => Arr::get($data, 'title_' . $localeCode),
                'description' => Arr::get($data, 'description_' . $localeCode),
            ]);
        }
    }
}
