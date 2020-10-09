<?php

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::group(['prefix' => 'admin', 'namespace' => 'Kakhura\CheckRequest\Http\Controllers\Admin', 'middleware' => array_merge(['web', 'auth', 'with_db_transactions'], config('kakhura.site-bases.use_two_type_users') ? ['admin'] : [])], function () {
    Route::post('/upload', 'Controller@uploadFromRedactor');

    Route::get('/', 'Controller@index');

    Route::group(['prefix' => 'pages/edit', 'namespace' => 'Page'], function () {
        Route::get('/about', 'AboutController@about');
        Route::post('/about', 'AboutController@storeAbout');

        Route::get('/rules', 'RuleController@rules');
        Route::post('/rules', 'RuleController@storeRules');

        Route::get('/contact', 'ContactController@contact');
        Route::post('/contact', 'ContactController@storeContact');
    });

    Route::group(['prefix' => 'slides', 'namespace' => 'Slide'], function () {
        Route::get('/', 'SlideController@slides');
        Route::get('/create', 'SlideController@createSlide');
        Route::post('/create', 'SlideController@storeSlide');
        Route::get('/edit/{slide}', 'SlideController@editSlide');
        Route::post('/edit/{slide}', 'SlideController@updateSlide');
        Route::get('/delete/{slide}', 'SlideController@deleteSlide');
        Route::post('/publish', 'SlideController@publish');
        Route::post('/ordering', 'SlideController@ordering');
    });

    Route::group(['prefix' => 'projects', 'namespace' => 'Project'], function () {
        Route::get('/', 'ProjectController@projects');
        Route::get('/create', 'ProjectController@createProject');
        Route::post('/create', 'ProjectController@storeProject');
        Route::get('/edit/{project}', 'ProjectController@editProject');
        Route::post('/edit/{project}', 'ProjectController@updateProject');
        Route::get('/delete/{project}', 'ProjectController@deleteProject');
        Route::post('/publish', 'ProjectController@publish');
        Route::post('/ordering', 'ProjectController@ordering');
        Route::post('/delete-project-img', 'ProjectController@projectDeleteImg');
    });

    Route::group(['prefix' => 'products', 'namespace' => 'Product'], function () {
        Route::get('/', 'ProductController@products');
        Route::get('/create', 'ProductController@createProduct');
        Route::post('/create', 'ProductController@storeProduct');
        Route::get('/edit/{product}', 'ProductController@editProduct');
        Route::post('/edit/{product}', 'ProductController@updateProduct');
        Route::get('/delete/{product}', 'ProductController@deleteProduct');
        Route::post('/publish', 'ProductController@publish');
        Route::post('/ordering', 'ProductController@ordering');
        Route::post('/delete-product-img', 'ProductController@productDeleteImg');
    });

    Route::group(['prefix' => 'categories', 'namespace' => 'Category'], function () {
        Route::get('/', 'CategoryController@categories');
        Route::get('/create', 'CategoryController@createCategory');
        Route::post('/create', 'CategoryController@storeCategory');
        Route::get('/edit/{category}', 'CategoryController@editCategory');
        Route::post('/edit/{category}', 'CategoryController@updateCategory');
        Route::get('/delete/{category}', 'CategoryController@deleteCategory');
        Route::post('/publish', 'CategoryController@publish');
        Route::post('/ordering', 'CategoryController@ordering');
    });

    Route::group(['prefix' => 'blogs', 'namespace' => 'Blog'], function () {
        Route::get('/', 'BlogController@blogs');
        Route::get('/create', 'BlogController@createBlog');
        Route::post('/create', 'BlogController@storeBlog');
        Route::get('/edit/{blog}', 'BlogController@editBlog');
        Route::post('/edit/{blog}', 'BlogController@updateBlog');
        Route::get('/delete/{blog}', 'BlogController@deleteBlog');
        Route::post('/publish', 'BlogController@publish');
        Route::post('/ordering', 'BlogController@ordering');
        Route::post('/delete-blog-img', 'BlogController@blogDeleteImg');
    });

    Route::group(['prefix' => 'pages', 'namespace' => 'DynamicPage'], function () {
        Route::get('/', 'PageController@pages');
        Route::get('/create', 'PageController@createPage');
        Route::post('/create', 'PageController@storePage');
        Route::get('/edit/{page}', 'PageController@editPage');
        Route::post('/edit/{page}', 'PageController@updatePage');
        Route::get('/delete/{page}', 'PageController@deletePage');
        Route::post('/publish', 'PageController@publish');
        Route::post('/ordering', 'PageController@ordering');
        Route::post('/delete-page-img', 'PageController@pageDeleteImg');
    });

    Route::group(['prefix' => 'photos', 'namespace' => 'Photo'], function () {
        Route::get('/', 'PhotoController@photos');
        Route::get('/create', 'PhotoController@createPhoto');
        Route::post('/create', 'PhotoController@storePhoto');
        Route::get('/edit/{photo}', 'PhotoController@editPhoto');
        Route::post('/edit/{photo}', 'PhotoController@updatePhoto');
        Route::get('/delete/{photo}', 'PhotoController@deletePhoto');
        Route::post('/publish', 'PhotoController@publish');
        Route::post('/ordering', 'PhotoController@ordering');
        Route::post('/delete-photo-img', 'PhotoController@photoDeleteImg');
    });

    Route::group(['prefix' => 'news', 'namespace' => 'News'], function () {
        Route::get('/', 'NewsController@news');
        Route::get('/create', 'NewsController@createNews');
        Route::post('/create', 'NewsController@storeNews');
        Route::get('/edit/{news}', 'NewsController@editNews');
        Route::post('/edit/{news}', 'NewsController@updateNews');
        Route::get('/delete/{news}', 'NewsController@deleteNews');
        Route::post('/publish', 'NewsController@publish');
        Route::post('/ordering', 'NewsController@ordering');
        Route::post('/delete-news-img', 'NewsController@newsDeleteImg');
    });

    Route::group(['prefix' => 'services', 'namespace' => 'Service'], function () {
        Route::get('/', 'ServiceController@services');
        Route::get('/create', 'ServiceController@createService');
        Route::post('/create', 'ServiceController@storeService');
        Route::get('/edit/{service}', 'ServiceController@editService');
        Route::post('/edit/{service}', 'ServiceController@updateService');
        Route::get('/delete/{service}', 'ServiceController@deleteService');
        Route::post('/publish', 'ServiceController@publish');
        Route::post('/ordering', 'ServiceController@ordering');
    });

    Route::group(['prefix' => 'partners', 'namespace' => 'Partner'], function () {
        Route::get('/', 'PartnerController@partners');
        Route::get('/create', 'PartnerController@createPartner');
        Route::post('/create', 'PartnerController@storePartner');
        Route::get('/edit/{partner}', 'PartnerController@editPartner');
        Route::post('/edit/{partner}', 'PartnerController@updatePartner');
        Route::get('/delete/{partner}', 'PartnerController@deletePartner');
        Route::post('/publish', 'PartnerController@publish');
        Route::post('/ordering', 'PartnerController@ordering');
    });

    Route::group(['prefix' => 'brands', 'namespace' => 'Brand'], function () {
        Route::get('/', 'BrandController@brands');
        Route::get('/create', 'BrandController@createBrand');
        Route::post('/create', 'BrandController@storeBrand');
        Route::get('/edit/{brand}', 'BrandController@editBrand');
        Route::post('/edit/{brand}', 'BrandController@updateBrand');
        Route::get('/delete/{brand}', 'BrandController@deleteBrand');
        Route::post('/publish', 'BrandController@publish');
        Route::post('/ordering', 'BrandController@ordering');
    });

    Route::group(['prefix' => 'videos', 'namespace' => 'Video'], function () {
        Route::get('/', 'VideoController@videos');
        Route::get('/create', 'VideoController@createVideo');
        Route::post('/create', 'VideoController@storeVideo');
        Route::get('/edit/{video}', 'VideoController@editVideo');
        Route::post('/edit/{video}', 'VideoController@updateVideo');
        Route::get('/delete/{video}', 'VideoController@deleteVideo');
        Route::post('/publish', 'VideoController@publish');
        Route::post('/ordering', 'VideoController@ordering');
    });

    Route::group(['prefix' => 'admins', 'namespace' => 'Admin'], function () {
        Route::get('/', 'AdminController@admins');
        Route::get('/create', 'AdminController@createAdmin');
        Route::post('/create', 'AdminController@storeAdmin');
        Route::get('/edit/{admin}', 'AdminController@editAdmin');
        Route::post('/edit/{admin}', 'AdminController@updateAdmin');
        Route::get('/delete/{admin}', 'AdminController@deleteAdmin');
    });
});

Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath', 'web'], 'namespace' => 'Kakhura\CheckRequest\Http\Controllers\Website'], function () {
    foreach (config('kakhura.site-bases.routes_mapper') as $module) {
        Route::group(['namespace' => Arr::get($module, 'namespace')], function () use ($module) {
            Route::get(sprintf('/%s', Arr::get($module, 'main_url')), sprintf('%s@%s', Arr::get($module, 'controller'), Arr::get($module, 'main_method_name')))->name(Arr::get($module, 'main_method_name'));
            if (Arr::get($module, 'item_url', false) && Arr::get($module, 'item_method_name', false)) {
                Route::get(sprintf('/%s/{%s}', Arr::get($module, 'main_url'), Arr::get($module, 'item_url')), sprintf('%s@%s', Arr::get($module, 'controller'), Arr::get($module, 'item_method_name')))->name(Arr::get($module, 'item_method_name'));
            }
        });
    }
});
