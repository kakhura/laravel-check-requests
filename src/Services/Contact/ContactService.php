<?php

namespace Kakhura\CheckRequest\Services\Contact;

use Illuminate\Support\Arr;
use Kakhura\CheckRequest\Models\Contact\Contact;
use Kakhura\CheckRequest\Services\Service;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class ContactService extends Service
{
    /**
     * @param array $data
     * @return void
     */
    public function create(array $data)
    {
        $contact = Contact::create(array_merge([
            'phone' => Arr::get($data, 'phone'),
            'email' => Arr::get($data, 'email'),
            'long' => Arr::get($data, 'long'),
            'lat' => Arr::get($data, 'lat'),
        ], $this->generateSocialsFields($data)));
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $contact->detail()->create([
                'address' => Arr::get($data, 'address_' . $localeCode),
                'description' => Arr::get($data, 'description_' . $localeCode),
                'locale' => $localeCode,
            ]);
        }
    }

    /**
     * @param array $data
     * @param Contact $contact
     * @return void
     */
    public function update(array $data, Contact $contact)
    {
        $contact->update(array_merge([
            'phone' => Arr::get($data, 'phone'),
            'email' => Arr::get($data, 'email'),
            'long' => Arr::get($data, 'long'),
            'lat' => Arr::get($data, 'lat'),
        ], $this->generateSocialsFields($data)));
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $contact->detail()->where('locale', $localeCode)->first()->update([
                'address' => Arr::get($data, 'address_' . $localeCode),
                'description' => Arr::get($data, 'description_' . $localeCode),
            ]);
        }
    }

    /**
     * @param array $data
     * @return array
     */
    protected function generateSocialsFields(array $data): array
    {
        $result = [];
        if (!is_null(config('kakhura.site-bases.contact_socials')) && is_array(config('kakhura.site-bases.contact_socials'))) {
            foreach (config('kakhura.site-bases.contact_socials') as $social) {
                $result[$social] = Arr::get($data, $social);
            }
        }
        return $result;
    }
}
