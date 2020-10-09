<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Unicode Admin Panel</title>
    <link href="{{ asset('assets/admin/favicon.png') }}" rel="shortcut icon" type="image/x-icon" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/2.8.0/css/flag-icon.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="{{ asset('css/fonts.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/vendors/bootstrap/dist/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/vendors/iCheck/skins/flat/green.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/admin/vendors/jquery-confirm-master/css/jquery-confirm.css') }}">
    <link href="{{ asset('assets/admin/vendors/switchery/dist/switchery.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/vendors/pnotify/dist/pnotify.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/vendors/pnotify/dist/pnotify.buttons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/vendors/pnotify/dist/pnotify.nonblock.css') }}" rel="stylesheet">
    @yield('style')
    <link href="{{ asset('assets/admin/css/custom.css') }}" rel="stylesheet">
    @yield('css')
</head>
