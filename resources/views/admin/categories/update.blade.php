@extends('vendor.site-bases.admin.inc.layout')

@section('title', $category->detail()->where('locale', config('kakhura.site-bases.admin_editors_default_locale'))->first()->title)

@section('content')
    @include('vendor.site-bases.admin.inc.message')
    <div class="page-title">
        <div class="title_left">
            <h3>{{ $category->detail()->where('locale', config('kakhura.site-bases.admin_editors_default_locale'))->first()->title }}</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_content">
                    <div class="col-md-10 col-sm-12">
                        <form method="POST" action="" data-parsley-validate class="form-horizontal"  enctype="multipart/form-data">

                            @csrf

                            <ul class="nav nav-tabs">
                                @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                    @php
                                        if($localeCode == 'en') {
                                            $ico = 'gb';
                                        } elseif($localeCode == 'ka') {
                                            $ico = 'ge';
                                        } else {
                                            $ico = $localeCode;
                                        }
                                    @endphp

                                    <li class="{{ $localeCode == config('kakhura.site-bases.admin_editors_default_locale') ? 'active' : '' }}">
                                        <a  href="#{{ $localeCode }}" data-toggle="tab">
                                            <span class="flag-icon flag-icon-{{ $ico }}"></span>
                                            {{ $properties['native'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="tab-content">
                                @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                    <div class="tab-pane {{ $localeCode == config('kakhura.site-bases.admin_editors_default_locale') ? 'active' : '' }}" id="{{ $localeCode }}">
                                        <div class="form-group">
                                            <label class="control-label col-md-2 col-sm-2 col-xs-12" for="title_{{ $localeCode }}">სათაური</label>
                                            <div class="col-md-10 col-sm-10  col-xs-12">
                                                <input type="text" name="title_{{ $localeCode }}" class="form-control" id="title_{{ $localeCode }}" value="{{ $category->detail()->where('locale', $localeCode)->first()->title }}" required>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-2 col-sm-2 col-xs-12" for="description_{{ $localeCode }}">აღწერა</label>
                                            <div class="col-md-10 col-sm-10 col-xs-12">
                                                <textarea id="description_{{ $localeCode }}" class="textarea" name="description_{{ $localeCode }}">{{ $category->detail()->where('locale', $localeCode)->first()->description }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <hr>

                            <div class="form-group margin-top">
                                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="parent_id">კატეგორია</label>
                                <div class="col-md-10 col-sm-10  col-xs-12">
                                    <select name="parent_id" id="parent_id" class="form-control">
                                        <option value="">აირჩიეთ</option>
                                        @foreach($categories as $item)
                                            <option value="{{ $item->id }}" {{ $item->id === $category->parent_id ? 'selected' : '' }}>
                                                {{ $item->parent_id ? ' --- ' . $item->title : $item->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group margin-top">
                                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="image">მთავარი სურათი</label>
                                <div class="col-md-10 col-sm-10  col-xs-12">
                                    <input type="file" name="image" class="form-control" id="image">
                                </div>
                            </div>

                            @if($category->image)
                                <div class="form-group" id="imgWrap">
                                    <div class="col-md-10 col-md-offset-2">
                                        <div class="panel panel-default" style="border-radius:0">
                                            <div class="panel-heading">ატვირთული სურათი</div>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="thumbnail">
                                                            <div class="image view view-first" style="height:260px">
                                                                <img src="{{ asset($category->image) }}" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="form-group">
                                <div class="col-md-offset-2 col-md-3 col-sm-4 col-xs-12">
                                    <label>
                                        <input type="checkbox" name="published" class="js-switch" {{ $category->published ? 'checked' : '' }} />
                                        გამოქვეყნებულია
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-10 col-md-offset-2">
                                    <button type="submit" class="btn btn-success btn-add">განახლება</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('style')
    <style>
        form .margin-top {
            margin-top: 50px !important;
        }
    </style>
    <link href="{{ asset('assets/admin/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/vendors/switchery/dist/switchery.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/vendors/redactor/redactor1.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/vendors/jquery.fileuploader/jquery.fileuploader.css') }}" rel="stylesheet">
@endsection

@section('script')
    <script src="{{ asset('assets/admin/vendors/jquery.tagsinput/src/jquery.tagsinput.js') }}"></script>
    <script src="{{ asset('assets/admin/vendors/switchery/dist/switchery.min.js') }}"></script>
    <script src="{{ asset('assets/admin/vendors/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/admin/vendors/autosize/dist/autosize.min.js') }}"></script>
    <script src="{{ asset('assets/admin/vendors/devbridge-autocomplete/dist/jquery.autocomplete.min.js') }}"></script>
    <script src="{{ asset('assets/admin/vendors/starrr/dist/starrr.js') }}"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="{{ asset('assets/admin/vendors/redactor/redactor1.js') }}"></script>
    <script src="{{ asset('assets/admin/vendors/jquery.fileuploader/jquery.fileuploader.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('input[name="image"]').fileuploader({addMore: false});
            $('input[name="images"]').fileuploader({addMore: true});

            $('textarea').redactor({
                imageUpload: "{{ url('admin/upload') }}?_token=" + "{{ csrf_token() }}",
                fileUpload: "{{ url('admin/upload') }}?_token=" + "{{ csrf_token() }}",
                lang: 'en',
                autoresize: true,
                minHeight: 200,
            });
        });
    </script>
@endsection
