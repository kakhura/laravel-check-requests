<div class="col-md-3 left_col">
    <div class="left_col scroll-view">
        <div class="navbar nav_title text-center">
            <a href="{{ url('admin') }}" class="site_title">
                <img src="{{ asset('assets/img/logo.png') }}">
            </a>
        </div>
        <div class="profile clearfix">
            <div class="profile_pic">
                <img src="{{ asset('assets/admin/img/default_avatar.png') }}" class="img-circle profile_img">
            </div>
            <div class="profile_info">
                <span>გამარჯობა,</span>
                <h2>{{ auth()->user()->name }}!</h2>
            </div>
        </div>
        <br />
        <div class="clearfix"></div>
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
                <ul class="nav side-menu">
                    <li>
                        <a href="{{ url('admin') }}">
                            <i class="fa fa-file-text-o"></i> გვერდები
                            <span class="fa fa-chevron-right"></span>
                        </a>
                    </li>
                    @foreach (config('kakhura.site-bases.sidebar_modules') as $module => $item)
                        @if (in_array($module, config('kakhura.site-bases.modules_publish_mapper')))
                            <li>
                                <a href="{{ Arr::get($item, 'children', false) ? '#!' : Arr::get($item, 'url') }}">
                                    {!! Arr::get($item, 'icon') !!} {{ Arr::get($item, 'title') }}
                                    {!! Arr::get($item, 'arrow-icon') !!}
                                </a>
                                @if (Arr::get($item, 'children', false))
                                    <ul class="nav child_menu">
                                        @foreach (Arr::get($item, 'children', []) as $childModule => $child)
                                            @if (in_array($childModule, config('kakhura.site-bases.modules_publish_mapper')))
                                                <li>
                                                    <a href="{{ Arr::get($child, 'url') }}">
                                                        {{ Arr::get($child, 'title') }}
                                                        <span class="fa fa-chevron-right"></span>
                                                    </a>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endif
                    @endforeach
                    <li>
                        <a href="{{ url('admin/admins') }}">
                            <i class="fa fa-users"></i> ადმინისტრატორები
                            <span class="fa fa-chevron-right"></span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('admin/translations') }}">
                            <i class="fa fa-globe"></i> თარგმნა
                            <span class="fa fa-chevron-right"></span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

