@extends('vendor.site-bases.admin.inc.layout')

@section('title', 'დამატება')

@section('content')
    @include('vendor.site-bases.admin.inc.message')

    <div class="page-title">
        <div class="title_left">
            <h3>დამატება</h3>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_content">
                    <div class="col-md-10 col-sm-12">
                        <form method="POST" action="" data-parsley-validate class="form-horizontal"  enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="name">სახელი</label>
                                <div class="col-md-10 col-sm-10  col-xs-12">
                                    <input type="text" name="name" class="form-control" id="name" required value="{{ old('name') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="email">ელ.ფოსტა</label>
                                <div class="col-md-10 col-sm-10  col-xs-12">
                                    <input type="email" name="email" class="form-control" id="email" required value="{{ old('email') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="password">პაროლი</label>
                                <div class="col-md-10 col-sm-10  col-xs-12">
                                    <input type="password" name="password" class="form-control" id="password" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-10 col-md-offset-2">
                                    <button type="submit" class="btn btn-success btn-add">დამატება</button>
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
    <link href="{{ asset('assets/admin') }}/vendors/select2/dist/css/select2.min.css" rel="stylesheet">

    <link href="{{ asset('assets/admin') }}/vendors/switchery/dist/switchery.min.css" rel="stylesheet">

    <link href="{{ asset('assets/admin') }}/vendors/redactor/redactor1.css" rel="stylesheet">

    <link href="{{asset('assets/admin/vendors/jquery.fileuploader/jquery.fileuploader.css')}}" rel="stylesheet">
@endsection

@section('script')
    <script src="{{ asset('assets/admin') }}/vendors/jquery.tagsinput/src/jquery.tagsinput.js"></script>
    <script src="{{ asset('assets/admin') }}/vendors/switchery/dist/switchery.min.js"></script>
    <script src="{{ asset('assets/admin') }}/vendors/select2/dist/js/select2.full.min.js"></script>
    <script src="{{ asset('assets/admin') }}/vendors/autosize/dist/autosize.min.js"></script>
    <script src="{{ asset('assets/admin') }}/vendors/devbridge-autocomplete/dist/jquery.autocomplete.min.js"></script>
    <script src="{{ asset('assets/admin') }}/vendors/starrr/dist/starrr.js"></script>
    <script src="{{ asset('assets/admin/vendors/redactor/redactor1.js') }}"></script>
    <script src="{{ asset('assets/admin/vendors/jquery.fileuploader/jquery.fileuploader.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('input[name="image"]').fileuploader({addMore: false});
            $('input[name="images"]').fileuploader({addMore: true});
        });

        $('textarea').redactor({
            imageUpload: "{{url('admin/upload')}}?_token=" + "{{csrf_token()}}",
            fileUpload: "{{url('admin/upload')}}?_token=" + "{{csrf_token()}}",
            lang: 'ka',
            autoresize: true,
            minHeight: 500
        });
    </script>
@endsection
