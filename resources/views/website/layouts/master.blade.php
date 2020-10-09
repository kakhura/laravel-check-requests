<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title')</title>

        <meta property="og:type" content="website" />
        <meta property="og:url" content="{{ URL::current() }}" />
        <meta property="og:title" content="@yield('title', env('APP_NAME'))"/>
        <meta property="og:image" content="@yield('img', asset('assets/client/images/for-fb.png'))"/>
        <meta property="og:site_name" content="{{ env('APP_NAME') }}"/>
        <meta property="og:description" content="@yield('desc', env('APP_NAME'))"/>

        @yield('style')
    </head>
    <body>
        @if (Route::current()->getName() != 'home')
        @endif

        @yield('content')

        @yield('scripts')
    </body>
</html>
