@extends('vendor.site-bases.admin.inc.layout')

@section('title', 'გვერდები')

@section('content')
    @include('vendor.site-bases.admin.inc.message')
    <div class="page-title">
        <div class="title_left">
            <h3>გვერდები</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_content">
                    <table class="table table-striped jambo_table bulk_action">
                        <thead>
                            <tr>
                                <th class="column-title">სათაური</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (config('kakhura.site-bases.pages_menu') as $page => $item)
                                @if (in_array($page, config('kakhura.site-bases.modules_publish_mapper')))
                                    <tr>
                                        <td><a href="{{ Arr::get($item, 'link') }}">{{ Arr::get($item, 'title') }}</a></td>
                                        <td align="right">
                                            <a href="{{ Arr::get($item, 'link') }}" class="btn btn-info btn-sm"><i class="fa fa-pencil"></i> რედაქტირება</a>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
