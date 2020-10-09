@extends('vendor.site-bases.admin.inc.layout')

@section('title', 'ფოტო გალერეა')

@section('content')
    <div class="page-title">
        <div class="title_left">
            <h3>ფოტო გალერეა</h3>
        </div>
    </div>
    @include('vendor.site-bases.admin.inc.message')
    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <a href="{{url('admin/photos/create')}}" class="btn btn-sm btn-success pull-right">
                        <i class="fa fa-models"></i> დამატება
                    </a>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    @if(count($photos))
                        <table class="table table-striped jambo_table bulk_action">
                            <thead>
                                <tr class="headings">
                                    <th class="text-center column-title" width="40px">ID</th>
                                    <th class="text-center column-title" width="70px">სტატუსი</th>
                                    <th class="column-title">სათაური</th>
                                    <th class="column-title text-center">თარიღი</th>
                                    <th class="column-title"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($photos as $key => $photo)
                                    <tr>
                                        <td class="text-center">
                                            {{ $photo->id }}
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" value="published" id="{{ $photo->id }}" class="js-switch publish" {{ $photo->published ? 'checked' : '' }} />
                                        </td>
                                        <td>
                                            @if($photo->image)
                                                <img class="post" src="{{ asset($photo->image) }}" alt="" />
                                            @endif
                                            <a href="{{ url('admin/photos/edit/' . $photo->id) }}">
                                                {{ Str::limit($photo->detail()->where('locale', 'ka')->first()->title, 35) }}
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <i class="fa fa-clock-o" aria-hidden="true"></i>
                                            {{ $photo->created_at }}
                                        </td>
                                        <td align="right">
                                            <a href="{{ url('admin/photos/edit/' . $photo->id) }}" class="btn btn-info btn-sm"><i class="fa fa-pencil"></i> </a>

                                            <a href="{{ url('admin/photos/delete/' . $photo->id) }}" class="delete btn btn-danger btn-sm"><i class="fa fa-trash-o"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $photos->links() }}
                    @else
                        <div class="alert alert-info">ფოტო გალერეა ვერ მოიძებნა</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.delete').click(function(e) {
                var target = $(this).attr('href');
                e.preventDefault();
                $.confirm({
                    title: 'დასტური',
                    content: 'დარწმუნებული ხართ, რომ გურთ ამის სამუდამოდ წაშლა?',
                    buttons: {

                        confirm: {

                            text: 'წაშლა',

                            btnClass: 'btn-red',

                            action: function(){

                                location.replace(target)

                            }

                        },

                        close: {

                            text: 'დახურვა',

                            action: function(){

                            }

                        }

                    }
                });
            });

            $('.publish').change(function(event) {
                var id = $(this).attr('id');
                var published = ($(this).is(':checked')) ? 1 : 0;
                $.ajax({
                    headers: { 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
                    url: '{{ url("admin/photos/publish") }}',
                    type: "post",
                    data: {
                        id: id,
                        published: published,
                        _token: '{{ csrf_token() }}',
                        className: "{{ addslashes(config('kakhura.site-bases.publish_classes.photos')) }}"
                    },
                    success: function (response) {
                        if (response.status == 'success'){
                            new PNotify({
                                text: 'წარმატებით განახლდა',
                                type: 'success',
                                styling: 'bootstrap3'
                            });
                        } else {
                            new PNotify({
                                text: 'დაფიქსირდა შეცდომა',
                                type: 'error',
                                styling: 'bootstrap3'
                            });
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        new PNotify({
                            text: 'დაფიქსირდა შეცდომა',
                            type: 'error',
                            styling: 'bootstrap3'
                        });
                    }
                });
            });
        });
    </script>

@endsection

@section('css')

    <style type="text/css">

        .post { width: 55px;  vertical-align: middle; margin-right: 10px;}

        .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th{ vertical-align: middle;}

        .table>thead .iCheck-helper{background: #101010 !important;}

        .cursor-move {
            cursor: move;
        }

    </style>

@endsection
