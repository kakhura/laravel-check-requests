<?php

namespace Kakhura\CheckRequest\Http\Controllers\Website\Brand;

use Kakhura\CheckRequest\Http\Controllers\Website\Controller;
use Kakhura\CheckRequest\Models\Brand\Brand;

class BrandController extends Controller
{
    public function brands()
    {
        $brands = Brand::where('published', true)
            ->where('show_on_brands', true)
            ->orderBy('ordering', 'asc')
            ->with([
                'detail' => function ($query) {
                    $query->where('locale', app()->getLocale());
                },
            ])->paginate(config('kakhura.site-bases.pagination_mapper.brands'));
        return view('vendor.site-bases.website.brands.main', compact('brands'));
    }

    public function brand(Brand $brand)
    {
        $brand->load([
            'detail' => function ($query) {
                $query->where('locale', app()->getLocale());
            },
        ]);
        return view('vendor.site-bases.website.brands.item', compact('brand'));
    }
}
