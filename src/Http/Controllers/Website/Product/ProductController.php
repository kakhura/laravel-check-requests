<?php

namespace Kakhura\CheckRequest\Http\Controllers\Website\Product;

use Kakhura\CheckRequest\Http\Controllers\Website\Controller;
use Kakhura\CheckRequest\Models\Product\Product;

class ProductController extends Controller
{
    public function products()
    {
        $products = Product::where('published', true)
            ->orderBy('ordering', 'asc')
            ->with([
                'detail' => function ($query) {
                    $query->where('locale', app()->getLocale());
                },
            ])->paginate(config('kakhura.site-bases.pagination_mapper.products'));
        return view('vendor.site-bases.website.products.main', compact('products'));
    }

    public function product(Product $product)
    {
        $product->load([
            'detail' => function ($query) {
                $query->where('locale', app()->getLocale());
            },
        ]);
        return view('vendor.site-bases.website.products.item', compact('product'));
    }
}
