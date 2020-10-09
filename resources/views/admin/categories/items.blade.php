@extends('vendor.site-bases.admin.inc.layout')

@section('title', 'კატეგორიები')

@section('content')
    @include('vendor.site-bases.admin.inc.message')
    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>კატეგორიები</h2>
                    <a href="{{ url('admin/categories/create') }}" class="btn btn-sm btn-success pull-right">
                        <i class="fa fa-plus"></i> დამატება
                    </a>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    @if(count($categories))
                        <ul class="list-group">
                            @foreach ($categories as $key => $item)
                                <li class="list-group-item sort cursor-move" data-id="{{ $item->id }}" data-ordering="{{ $item->ordering }}">
                                    <div class="for-display">
                                        <div class="col-md-5 for-display">
                                            <span>
                                                <i class="fa fa-dot-circle-o" aria-hidden="true"></i>
                                                @if ($item->image)
                                                    <img src="{{ asset($item->image) }}" alt="" class="post">
                                                @endif
                                                {{ $item->id }} : {{ $item->title }}
                                            </span>

                                            <span class="display-inline-block">
                                                <input type="checkbox" id="{{ $item->id }}" class="js-switch publish" {{ $item->published ? 'checked' : '' }}/>
                                            </span>
                                        </div>
                                        <span>
                                            <a href="{{ url('/admin/categories/edit/' . $item->id) }}" class="btn btn-info btn-sm"><i class="fa fa-pencil"></i></a>
                                            <a href="{{ url('/admin/categories/delete/' . $item->id) }}" class="delete btn btn-danger btn-sm"><i class="fa fa-trash-o"></i></a>
                                        </span>
                                    </div>
                                    @if ($item->childrenRecursive->count() > 0)
                                        @include('vendor.site-bases.admin.categories.category-child' , ['category' => $item, 'depth' => 1])
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="alert alert-info">
                            კატეგორიები ვერ მოიძებნა
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
	<script>
        $(document).ready(function() {
            $('.delete').click(function(e) {
                var target = $(this).attr('href');
                e.preventDefault();
                $.confirm({
                    title: 'დასტური',
                    content: 'დარწმუნებული ხართ, რომ გსურთ კატეგორიის სამუდამოდ წაშლა?',
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
                    url: '{{ url("admin/categories/publish") }}',
                    type: "post",
                    data: {
                        id: id,
                        published: published,
                        _token: '{{ csrf_token() }}',
                        className: "{{ addslashes(config('kakhura.site-bases.publish_classes.categories')) }}"
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

            $('.list-group').sortable({
                update: function(event, ui){
                    var parent = $(ui.item).parent().parent().data('id') == undefined ? $(ui.item).index() : $(ui.item).parent().parent().data('id');
                    var children = $(ui.item).parent().find('> li');
                    var arr = [];
                    children.each(function(){
                        $(this).attr('data-ordering', $(this).index());
                        arr.push([$(this).attr('data-id'), (parent + Number($(this).attr('data-ordering')))]);
                    });
                    arr = JSON.stringify(arr);
                    $.ajax({
                        url:"{{ url('admin/categories/ordering') }}",
                        type:"POST",
                        data:"_token={{ csrf_token( )}}" + "&ordering=" + arr + "&className={{ addslashes(config('kakhura.site-bases.ordering_classes.categories')) }}",
                    })
                    .done(function(data){
                        new PNotify({
                            text: 'წარმატებით განახლდა',
                            type: 'success',
                            styling: 'bootstrap3'
                        });
                    })
                }
            });
        });
	</script>
@endsection

@section('css')
    <style type="text/css">
        .list-group {
            margin-bottom: 10px;
            margin-top: 10px;
        }
        .post {
            width: 55px;
            margin-left: 10px;
            margin-right: 10px;
        }
        .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th{
            vertical-align: middle;
        }
        .table>thead .iCheck-helper{
            background: #101010 !important;
        }
        .cursor-move {
            cursor: move;
        }
        .display-inline-block {
            display: inline-block;
        }
        div.for-display {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
@endsection
