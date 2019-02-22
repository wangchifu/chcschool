<nav class="navbar navbar-expand-lg navbar-dark bg-dark" id="mainNav">
    <div class="container">
        <a href="#page-top">
            <img src="{{ asset('images/site_logo.png') }}" width="30" height="30" class="d-inline-block align-top" alt="">
        </a>　
        <a class="navbar-brand js-scroll-trigger" href="{{  route('index') }}">
            {{ env('APP_NAME') }}
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-info-circle"></i> 公開資訊
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href=""><i class="fas fa-bullhorn"></i> 公告系統</a>
                        <a class="dropdown-item" href=""><i class="fas fa-box-open"></i> 公開文件</a>
                    </div>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-user"></i> {{ auth()->user()->name }}
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
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
