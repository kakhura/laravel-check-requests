@extends('vendor.site-bases.admin.inc.layout')

@section('title', 'ადმინისტრატორები')

@section('content')
    <div class="page-title">
        <div class="title_left">
            <h3>ადმინისტრატორები</h3>
        </div>
    </div>
    @include('vendor.site-bases.admin.inc.message')
    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <a href="{{url('admin/admins/create')}}" class="btn btn-sm btn-success pull-right">
                        <i class="fa fa-models"></i> დამატება
                    </a>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content admin_container">
                    @if(count($admins))
                        <table class="table table-striped jambo_table bulk_action">
                            <thead>
                                <tr class="headings">
                                    <th class="text-center column-title" width="40px">ID</th>
                                    <th class="column-title">სახელი</th>
                                    <th class="column-title"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($admins as $key => $admin)
                                    <tr>
                                        <td class="text-center">
                                            {{ $admin->id }}
                                        </td>
                                        <td>
                                            <a href="{{ url('admin/admins/edit/' . $admin->id) }}">
                                                {{ $admin->name }}
                                            </a>
                                        </td>
                                        <td align="right">
                                            <a href="{{ url('admin/admins/edit/' . $admin->id) }}" class="btn btn-info btn-sm"><i class="fa fa-pencil"></i> </a>

                                            <a href="{{ url('admin/admins/delete/' . $admin->id) }}" class="delete btn btn-danger btn-sm"><i class="fa fa-trash-o"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $admins->links() }}
                    @else
                        <div class="alert alert-info">ადმინისტრატორები ვერ მოიძებნა</div>
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
                    content: 'დარწმუნებული ხართ, რომ გსურთ ამის წაშლა?',
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
