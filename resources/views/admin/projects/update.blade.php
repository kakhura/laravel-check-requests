@extends('vendor.site-bases.admin.inc.layout')

@section('title', $project->detail()->where('locale', config('kakhura.site-bases.admin_editors_default_locale'))->first()->title)

@section('content')
    @include('vendor.site-bases.admin.inc.message')
    <div class="page-title">
        <div class="title_left">
            <h3>{{ $project->detail()->where('locale', config('kakhura.site-bases.admin_editors_default_locale'))->first()->title }}</h3>
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
                                            <span class="flag-icon flag-icon-{{$ico}}"></span>
                                            {{$properties['native']}}
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
                                                <input type="text" name="title_{{ $localeCode }}" class="form-control" id="title_{{ $localeCode }}" value="{{ $project->detail()->where('locale', $localeCode)->first()->title }}" required>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-2 col-sm-2 col-xs-12" for="description_{{ $localeCode }}">აღწერა</label>
                                            <div class="col-md-10 col-sm-10 col-xs-12">
                                                <textarea id="description_{{ $localeCode }}" class="textarea" name="description_{{ $localeCode }}" required>{{ $project->detail()->where('locale', $localeCode)->first()->description }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <hr>

                            <div class="form-group">
                                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="video">ვიდეო</label>
                                <div class="col-md-10 col-sm-10  col-xs-12">
                                    <input type="text" name="video" class="form-control" id="video" value="{{ $project->video }}">
                                </div>
                            </div>

                            <div class="form-group margin-top">
                                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="video_image">ვიდეოს სურათი</label>
                                <div class="col-md-10 col-sm-10  col-xs-12">
                                    <input type="file" name="video_image" class="form-control" id="video_image">
                                </div>
                            </div>

                            @if($project->video_image)
                                <div class="form-group" id="imgWrap">
                                    <div class="col-md-10 col-md-offset-2">
                                        <div class="panel panel-default" style="border-radius:0">
                                            <div class="panel-heading">ატვირთული სურათი</div>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="thumbnail">
                                                            <div class="image view view-first" style="height:260px">
                                                                <img src="{{ asset($project->video_image) }}" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="form-group margin-top">
                                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="image">მთავარი სურათი</label>
                                <div class="col-md-10 col-sm-10  col-xs-12">
                                    <input type="file" name="image" class="form-control" id="image">
                                </div>
                            </div>

                            @if($project->image)
                                <div class="form-group" id="imgWrap">
                                    <div class="col-md-10 col-md-offset-2">
                                        <div class="panel panel-default" style="border-radius:0">
                                            <div class="panel-heading">ატვირთული სურათი</div>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="thumbnail">
                                                            <div class="image view view-first" style="height:260px">
                                                                <img src="{{ asset($project->image) }}" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="form-group margin-top">
                                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="images">სურათები</label>
                                <div class="col-md-10 col-sm-10  col-xs-12">
                                    <input type="file" name="images" class="form-control" id="images">
                                </div>
                            </div>

                            @if(!empty($project->images))
                                <div class="form-group" id="imgWrap">
                                    <div class="col-md-10 col-md-offset-2">
                                        <div class="panel panel-default" style="border-radius:0">
                                            <div class="panel-heading">ატვირთული სურათი</div>
                                            <div class="panel-body">
                                                <div class="row">
                                                    @foreach ($project->images as $key => $image)
                                                        <div class="col-md-4">
                                                            <div class="thumbnail">
                                                                <div class="image view view-first" data-id="{{ $image->id }}" data-main="{{ $project->id }}">
                                                                    <img src="{{ asset($image->image) }}">
                                                                </div>
                                                                <div class="caption">
                                                                    <div class="btn btn-danger delImg" data-img="{{ asset($image->image) }}" data-id="{{ $image->id }}" data-main="{{ $project->id }}"><i class="fa fa-close"></i> სურათის წაშლა</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="form-group">
                                <div class="col-md-offset-2 col-md-3 col-sm-4 col-xs-12">
                                    <label>
                                        <input type="checkbox" name="published" class="js-switch" {{ $project->published ? 'checked' : '' }} />
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
            $('input[name="video_image"]').fileuploader({addMore: true});

            $('.delImg').click(function(e) {
                var id = $(this).data('id');
                var main_id = $(this).data('main');
                var that = this;
                var img = $(this).data('img');
                $.confirm({
                    title: 'დასტური',
                    content: 'დარწმუნებული ხართ, რომ გსურთ სურათის წაშლა?',
                    buttons: {
                        confirm: {
                            text: 'წაშლა',
                            btnClass: 'btn-red',
                            action: function(){
                                $.ajax({
                                    headers: { 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
                                    url: '{{ url("admin/projects/delete-gallery-img") }}',
                                    type: "post",
                                    data: { id: id, main_id: main_id, img: img, _token: '{{ csrf_token() }}' },
                                    success: function (response) {
                                        var res = $.parseJSON(response);
                                        if (res.status == 'success'){
                                            new PNotify({
                                                text: 'სურათი წარმატებით წაიშალა',
                                                type: 'success',
                                                styling: 'bootstrap3'
                                            });
                                            $(that).parent().parent().parent().remove();
                                        } else {
                                            new PNotify({
                                                text: 'დაფიქსირდა შეცდომა',
                                                type: 'error',
                                                styling: 'bootstrap3'
                                            });
                                        }
                                    },
                                    error: function(jqXHR, textStatus, errorThrown) {
                                        alert(2)
                                    }
                                });
                            }
                        },
                        close: {
                            text: 'დახურვა',
                            action: function(){}
                        }
                    }
                });

            });

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
