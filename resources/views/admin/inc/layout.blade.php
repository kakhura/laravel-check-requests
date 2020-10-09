<!DOCTYPE html>
<html>
    @include('vendor.site-bases.admin.inc.head')

    <body class="nav-md">
        <div class="container body">
            <div class="main_container">
                @include('vendor.site-bases.admin.inc.sidebar')
                <div class="top_nav">
                    <div class="nav_menu">
                        <nav>
                            <div class="nav toggle">
                                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                            </div>
                            <ul class="nav navbar-nav navbar-right">
                                <li>
                                    <a href="{{url('/')}}" target="_blank"> საიტზე გადასვლა <i class="fa fa-angle-right"></i></a>
                                </li>
                                <li>
                                    <a href="{{ url('admin/profile')}}" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                        <img src="{{ asset('assets/admin/img/default_avatar.png') }}">
                                        {{ auth()->user()->name }}
                                        <span class=" fa fa-angle-down"></span>
                                    </a>

                                    <ul class="dropdown-menu dropdown-usermenu pull-right">
                                        <li>
                                            <a href="{{ url('admin/admins/edit/' . auth()->user()->id) }}" data-remote="false" data-toggle="modal" data-target="#modal" >
                                                <i class="fa fa-user pull-right"></i> პროფილი
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('logout') }}"
                                                onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();">
                                                <i class="fa fa-sign-out pull-right"></i> გასვლა
                                            </a>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <div class="right_col" role="main">
                    @include('vendor.site-bases.admin.inc.errors')
                    @yield('content')
                </div>
            </div>
        </div>

        <footer>
            <div class="pull-right">
                <a href="https://unicode.ge/" target="_blank">Unicode</a> Admin Panel
            </div>
            <div class="clearfix"></div>
        </footer>
    </body>

    @include('vendor.site-bases.admin.inc.footer')
</html>
