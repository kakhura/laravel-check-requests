@extends('vendor.site-bases.admin.inc.layout')

@section('title', 'ბრენდები')

@section('content')
    <div class="page-title">
        <div class="title_left">
            <h3>ბრენდები</h3>
        </div>
    </div>
    @include('vendor.site-bases.admin.inc.message')
    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <a href="{{url('admin/brands/create')}}" class="btn btn-sm btn-success pull-right">
                        <i class="fa fa-models"></i> დამატება
                    </a>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content admin_container">
                    @if(count($brands))
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
                                @foreach($brands as $key => $brand)
                                    <tr class="cursor-move">
                                        <td class="text-center sort" id="sort{{ $key }}" data-id="{{ $brand->id }}" data-ordering ="{{ $brand->ordering }}">
                                            {{ $brand->ordering }}
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" value="published" id="{{ $brand->id }}" class="js-switch publish" {{ $brand->published ? 'checked' : '' }} />
                                        </td>
                                        <td>
                                            @if($brand->image)
                                                <img class="post" src="{{ asset($brand->image) }}" alt="" />
                                            @endif
                                            <a href="{{ url('admin/brands/edit/' . $brand->id) }}">
                                                {{ Str::limit($brand->detail()->where('locale', 'ka')->first()->title, 35) }}
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <i class="fa fa-clock-o" aria-hidden="true"></i>
                                            {{ $brand->created_at }}
                                        </td>
                                        <td align="right">
                                            <a href="{{ url('admin/brands/edit/' . $brand->id) }}" class="btn btn-info btn-sm"><i class="fa fa-pencil"></i> </a>

                                            <a href="{{ url('admin/brands/delete/' . $brand->id) }}" class="delete btn btn-danger btn-sm"><i class="fa fa-trash-o"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $brands->links() }}
                    @else
                        <div class="alert alert-info">ბრენდები ვერ მოიძებნა</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    @if(isset($limit))
        <script>
            $(document).ready(function () {

                $page = "<?php echo ( isset($_REQUEST['page'])) ?  $_REQUEST['page'] :  "1" ?>";

                $limit = "<?php echo  $limit  ?>";
                $k = $limit * ($page-1);

                $('.admin_container tbody').sortable({
                    start: function(event, ui) {

                        var start_pos = ui.item.index();
                        ui.item.data('start_pos', start_pos);
                    },
                    update : function(event, ui) {
                        var index = ui.item.index();
                        var start_pos = ui.item.data('start_pos');

                        //update the html of the moved item to the current index
                        $('.admin_container tbody tr:nth-child(' + (index + 1) + ') .sort').html(index+$k+1).attr('data-ordering', index+$k+1);

                        if (start_pos < index) {
                            //update the items before the re-ordered item
                            for(var i=index; i > 0; i--){
                                $('.admin_container tbody tr:nth-child(' + i + ') .sort').html(i+ $k).attr('data-ordering', i+ $k);

                            }
                        }else {
                            //update the items after the re-ordered item
                            for(var i=index+2;i <= $(".admin_container tbody tr").length; i++){
                                $('.admin_container tbody tr:nth-child(' + i + ') .sort').html(i+$k).attr('data-ordering', i+ $k);


                            }
                        }
                        changeordering();
                    },
                });

            });

            function changeordering(){
                var multi = $('.sort');
                var arr = [];
                for (var i = 0 ; i < multi.length ; i++){
                    arr.push( [$('#sort'+i).attr('data-id') , $('#sort'+i).attr('data-ordering')] );
                }

                arr = JSON.stringify(arr);
                $.ajax({
                    url:"{{ url('admin/brands/ordering') }}",
                    type:"POST",
                    data:"_token={{ csrf_token() }}" + "&ordering=" + arr + "&className={{ addslashes(config('kakhura.site-bases.ordering_classes.brands')) }}",
                }).done(function(data){})
            }
        </script>
    @endif

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
                    url: '{{ url("admin/brands/publish") }}',
                    type: "post",
                    data: {
                        id: id,
                        published: published,
                        _token: '{{ csrf_token() }}',
                        className: "{{ addslashes(config('kakhura.site-bases.publish_classes.brands')) }}"
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
