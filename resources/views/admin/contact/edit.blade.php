@extends('vendor.site-bases.admin.inc.layout')

@section('title', 'საკონტაქტო ინფორმაცია')

@section('content')
    @include('vendor.site-bases.admin.inc.message')

    <div class="page-title">
        <div class="title_left">
            <h3>საკონტაქტო ინფორმაცია</h3>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_content">
                    <form method="POST" action="" data-parsley-validate class="form-horizontal"  enctype="multipart/form-data">
                        @csrf
                        <div class="col-md-10 col-sm-12">
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
                                            <label class="control-label col-md-2 col-sm-2 col-xs-12" for="address_{{ $localeCode }}">მისამართი</label>
                                            <div class="col-md-10 col-sm-10  col-xs-12">
                                                <input type="text" name="address_{{ $localeCode }}" class="form-control" id="address_{{ $localeCode }}" required value="{{ !empty($contact) ? $contact->detail()->where('locale', $localeCode)->first()->address : old('address_' . $localeCode) }}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-2 col-sm-2 col-xs-12" for="description_{{ $localeCode }}">ტექსტი</label>
                                            <div class="col-md-10 col-sm-10 col-xs-12">
                                                <textarea id="description_{{ $localeCode }}" class="textarea" name="description_{{ $localeCode }}">{{ !empty($contact) ? $contact->detail()->where('locale', $localeCode)->first()->description : old('description_' . $localeCode) }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                <hr>

                                <div class="form-group margin-top">
                                    <label class="control-label col-md-2 col-sm-2 col-xs-12" id="email">ელ.ფოსტა</label>
                                    <div class="col-md-10 col-sm-10  col-xs-12">
                                        <input type="text" name="email" class="form-control" id="email" required value="{{ !empty($contact) ? $contact->email : old('email') }}">
                                    </div>
                                </div>

                                <div class="form-group margin-top">
                                    <label class="control-label col-md-2 col-sm-2 col-xs-12" id="phone">ტელეფონი</label>
                                    <div class="col-md-10 col-sm-10  col-xs-12">
                                        <input type="text" name="phone" class="form-control" id="phone" required value="{{ !empty($contact) ? $contact->phone : old('phone') }}">
                                    </div>
                                </div>

                                <div class="form-group margin-top">
                                    <label class="control-label col-md-2 col-sm-2 col-xs-12" id="long">რუკა (long)</label>
                                    <div class="col-md-10 col-sm-10  col-xs-12">
                                        <input type="text" name="long" class="form-control" id="long" value="{{ !empty($contact) ? $contact->long : old('long') }}">
                                    </div>
                                </div>

                                <div class="form-group margin-top">
                                    <label class="control-label col-md-2 col-sm-2 col-xs-12" id="lat">რუკა (lat)</label>
                                    <div class="col-md-10 col-sm-10  col-xs-12">
                                        <input type="text" name="lat" class="form-control" id="lat" value="{{ !empty($contact) ? $contact->lat : old('lat') }}">
                                    </div>
                                </div>

                                @if (!is_null(config('kakhura.site-bases.contact_socials')) && is_array(config('kakhura.site-bases.contact_socials')))
                                    @foreach (config('kakhura.site-bases.contact_socials') as $social)
                                        <div class="form-group margin-top">
                                            <label class="control-label col-md-2 col-sm-2 col-xs-12" id="{{ $social }}">{{ Str::ucfirst($social) }}</label>
                                            <div class="col-md-10 col-sm-10  col-xs-12">
                                                <input type="text" name="{{ $social }}" class="form-control" id="{{ $social }}" value="{{ !empty($contact) ? $contact->$social : old($social) }}">
                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                                <hr>

                                <div class="form-group">
                                    <div class="col-md-10 col-md-offset-2">
                                        <button type="submit" class="btn btn-success btn-add">განახლება</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
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
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endsection

@section('script')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="{{ asset('assets/admin/vendors/jquery.tagsinput/src/jquery.tagsinput.js') }}"></script>
    <script src="{{ asset('assets/admin/vendors/switchery/dist/switchery.min.js') }}"></script>
    <script src="{{ asset('assets/admin/vendors/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/admin/vendors/parsleyjs/dist/parsley.min.js') }}"></script>
    <script src="{{ asset('assets/admin/vendors/autosize/dist/autosize.min.js') }}"></script>
    <script src="{{ asset('assets/admin/vendors/devbridge-autocomplete/dist/jquery.autocomplete.min.js') }}"></script>
    <script src="{{ asset('assets/admin/vendors/starrr/dist/starrr.js') }}"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="{{ asset('assets/admin/vendors/redactor/redactor1.js') }}"></script>
  	<script src="{{ asset('assets/admin/vendors/jquery.fileuploader/jquery.fileuploader.js') }}"></script>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('input[name="image"]').fileuploader({addMore: false});

            $('textarea').redactor({
                imageUpload: "{{ url('admin/upload') }}?_token=" + "{{csrf_token()}}",
                fileUpload: "{{ url('admin/upload') }}?_token=" + "{{csrf_token()}}",
                lang: 'ka',
                autoresize: true,
                minHeight: 300
            });
        });
    </script>
@endsection
