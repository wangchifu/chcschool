<nav class="navbar navbar-expand-lg {{ $nav_color }}" id="mainNav">
    <div class="container">
        <a href="#page-top">
            @if(file_exists(storage_path('app/public/'.$school_code.'/title_image/logo.ico')))
                <img src="{{ asset('storage/'.$school_code.'/title_image/logo.ico') }}" width="30" height="30" class="d-inline-block align-top" alt="">
            @else
                <img src="{{ asset('images/site_logo.png') }}" width="30" height="30" class="d-inline-block align-top" alt="">
            @endif
        </a>　
        <a class="navbar-brand js-scroll-trigger" href="{{  route('index') }}">
            {{ $setup->site_name }}
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item @yield('nav_home_active')">
                    <a class="nav-link" href="{{ route('index') }}"><i class="fas fa-home"></i> 首頁 <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item @yield('nav_post_active')">
                    <a class="nav-link" href="{{ route('posts.index') }}"><i class="fas fa-bullhorn"></i> 公告系統</a>
                </li>
                <li class="nav-item @yield('nav_open_files_active')">
                    <a class="nav-link" href="#"><i class="fas fa-inbox"></i> 檔案庫</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-link"></i> 好站連結
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="">
                            <i class="fas fa-globe"></i> Link1
                        </a>
                    </div>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fab fa-linkedin"></i> 校務行政
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <a class="dropdown-item" href="">
                                <i class="fas fa-wrench"></i> 報修系統
                            </a>
                            <a class="dropdown-item" href="">
                                <i class="fas fa-comments"></i> 會議文稿
                            </a>
                        </div>
                    </li>
                    @if(auth()->user()->admin)
                        <li class="nav-item dropdown @yield('nav_setup_active')">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-cogs"></i> 系統設定
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <a class="dropdown-item" href="{{ route('users.index') }}"><i class="fas fa-user"></i> 帳號管理</a>
                                <a class="dropdown-item" href=""><i class="fas fa-file-alt"></i> 內容管理</a>
                                <a class="dropdown-item" href=""><i class="fas fa-link"></i> 連結管理</a>
                                <a class="dropdown-item" href="{{ route('setups.index') }}"><i class="fas fa-desktop"></i> 網站設定</a>
                            </div>
                        </li>
                    @endif
                    <li class="nav-item dropdown @yield('nav_user_active')">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-user"></i> {{ auth()->user()->title }} {{ auth()->user()->name }}
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            @if(auth()->user()->username=="admin")
                                <a class="dropdown-item" href="{{ route('edit_password') }}"><i class="fas fa-key"></i> 更改密碼</a>
                            @endif
                            @impersonating
                            <a class="dropdown-item" href="{{ route('sims.impersonate_leave') }}" onclick="return confirm('確定返回原本帳琥？')"><i class="fab fa-snapchat-ghost"></i> 結束模擬</a>
                            @endImpersonating
                            <a class="dropdown-item" href="#" onclick="
                            if(confirm('您確定登出嗎?')) document.getElementById('logout-form').submit();
                                else return false">
                                <i class="fas fa-sign-out-alt"></i> 登出
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endauth
                @guest
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-user"></i> 使用者登入
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <a class="dropdown-item" href="{{ route('glogin') }}">
                                <i class="fas fa-sign-in-alt"></i> 國中小學登入
                            </a>
                            <a class="dropdown-item" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt"></i> 系統管理登入
                            </a>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
