<?php $module_setup = get_module_setup(); ?>
<style>
    .custom-toggler.navbar-toggler {
        border-color: rgb(255,255,255,0.5);
    }
    .custom-toggler .navbar-toggler-icon {
        background-image: url("data:image/svg+xml;charset=utf8,%3Csvg viewBox='0 0 32 32' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='rgba(255,255,255, 0.5)' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 8h24M4 16h24M4 24h24'/%3E%3C/svg%3E");
    }
</style>
<nav class="navbar navbar-expand-lg {{ $nav_color }}" id="mainNav">
    <div class="container-fluid">
            <a href="#page-top" style="margin-right: 10px;">
                @if(file_exists(storage_path('app/public/'.$school_code.'/title_image/logo.ico')))
                    <img src="{{ asset('storage/'.$school_code.'/title_image/logo.ico') }}" width="30" height="30" class="d-inline-block align-top" alt="">
                @else
                    <img src="{{ asset('images/site_logo.png') }}" width="30" height="30" class="d-inline-block align-top" alt="">
                @endif
            </a>
            <a class="navbar-brand js-scroll-trigger" href="{{  route('index') }}">{{ $setup->site_name }}</a>
        <button class="navbar-toggler custom-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item @yield('nav_home_active')">
                    <a class="nav-link" href="{{ route('index') }}">首頁 <span class="sr-only">(current)</span></a>
                </li>
                @if(isset($module_setup['公告系統']))
                    <li class="nav-item @yield('nav_post_active')">
                        <a class="nav-link" href="{{ route('posts.index') }}">公告系統</a>
                    </li>
                @endif
                @if(isset($module_setup['檔案庫']))
                    <li class="nav-item @yield('nav_open_files_active')">
                        <a class="nav-link" href="{{ route('open_files.index') }}">檔案庫</a>
                    </li>
                @endif
                @if(isset($module_setup['學校介紹']))
                    <li class="nav-item dropdown @yield('nav_departments_active')">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            學校介紹
                        </a>
                        <?php $departments = \App\Department::orderBy('order_by')->get(); ?>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                            @foreach($departments as $department)
                                <a class="dropdown-item" href="{{ route('departments.show',$department->id) }}">
                                    <i class="fas fa-puzzle-piece"></i> {{ $department->title }}
                                </a>
                            @endforeach
                        </div>
                    </li>
                @endif
                @if(isset($module_setup['選單連結']))
                    <?php $types = \App\Type::orderBy('order_by')->get();
                    ?>
                    @foreach($types as $type)
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ $type->name }}
                            </a>
                            <?php
                            $links = \App\Link::where('type_id',$type->id)->orderBy('order_by')->get();
                            ?>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                                @foreach($links as $link)
                                    <a class="dropdown-item" href="{{ $link->url }}" target="_blank">
                                        <i class="fas fa-globe"></i> {{ $link->name }}
                                    </a>
                                @endforeach
                            </div>
                        </li>
                    @endforeach
                @endif
            </ul>
            <ul class="nav navbar-nav navbar-right">
                @auth
                    @if(isset($module_setup['校務行政']))
                        <li class="nav-item dropdown @yield('nav_school_active')">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                校務行政
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                                @if(isset($module_setup['校務行事曆']))
                                    <a class="dropdown-item" href="{{ route('calendars.index') }}">
                                        <i class="fas fa-calendar"></i> 校務行事曆
                                    </a>
                                @endif
                                @if(isset($module_setup['校務月曆']))
                                    <a class="dropdown-item" href="{{ route('monthly_calendars.index') }}">
                                        <i class="fas fa-calendar-alt"></i> 校務月曆
                                    </a>
                                @endif
                                @if(isset($module_setup['內部文件']))
                                    <a class="dropdown-item" href="{{ route('inside_files.index') }}">
                                        <i class="fab fa-linkedin-in"></i> 內部文件
                                    </a>
                                @endif
                                @if(isset($module_setup['會議文稿']))
                                    <a class="dropdown-item" href="{{ route('meetings.index') }}">
                                        <i class="fas fa-comments"></i> 會議文稿
                                    </a>
                                @endif
                                @if(isset($module_setup['報修系統']))
                                    <a class="dropdown-item" href="{{ route('fixes.index') }}">
                                        <i class="fas fa-wrench"></i> 報修系統
                                    </a>
                                @endif
                                    @if(isset($module_setup['教室預約']))
                                        <a class="dropdown-item" href="{{ route('classroom_orders.index') }}">
                                            <i class="fas fa-chess-rook"></i> 教室預約
                                        </a>
                                    @endif
                                @if(isset($module_setup['午餐系統']))
                                    <a class="dropdown-item" href="{{ route('lunches.index') }}">
                                        <i class="fas fa-utensils"></i> 午餐系統
                                    </a>
                                @endif
                                @if(isset($module_setup['教師差假']))
                                    <!--
                                    <a class="dropdown-item" href="{{ route('teacher_absents.index') }}">
                                        <i class="fas fa-user-times"></i> 教師差假
                                    </a>
                                    -->
                                @endif
                                @if(isset($module_setup['社團報名']))
                                    <a class="dropdown-item" href="{{ route('clubs.index') }}">
                                        <i class="fas fa-table-tennis"></i> 社團報名
                                    </a>
                                @endif
                                @if(isset($module_setup['校園部落格']))
                                    <a class="dropdown-item" href="{{ route('blogs.index') }}">
                                        <i class="fas fa-newspaper"></i> 校園部落格
                                    </a>
                                @endif
                                @if(isset($module_setup['行政待辦']))
                                    <a class="dropdown-item" href="{{ route('tasks.index') }}">
                                        <i class="fas fa-tasks"></i> 行政待辦
                                    </a>
                                @endif
                                <a class="dropdown-item" href="{{ route('photo_links.index') }}"><i class="fas fa-image"></i> 圖片連結</a>
                            </div>
                        </li>
                    @endif
                    @if(auth()->user()->admin)
                        <li class="nav-item dropdown @yield('nav_setup_active')">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                系統設定
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                                <a class="dropdown-item" href="{{ route('users.index') }}"><i class="fas fa-user"></i> 帳號管理</a>
                                <a class="dropdown-item" href="{{ route('groups.index') }}"><i class="fas fa-users"></i> 群組管理</a>
                                <a class="dropdown-item" href="{{ route('departments.index') }}"><i class="fas fa-puzzle-piece"></i> 學校介紹管理</a>
                                <a class="dropdown-item" href="{{ route('contents.index') }}"><i class="fas fa-file-alt"></i> 內容管理</a>
                                <a class="dropdown-item" href="{{ route('links.index') }}"><i class="fas fa-link"></i> 選單連結</a>
                                <a class="dropdown-item" href="{{ route('photo_links.index') }}"><i class="fas fa-image"></i> 圖片連結</a>
                                <a class="dropdown-item" href="{{ route('trees.index') }}"><i class="fas fa-tree"></i> 樹狀目錄</a>
                                <!--
                                <a class="dropdown-item" href="{{ route('lunch_todays.index') }}"><i class="fas fa-utensils"></i> 今日午餐</a>
                                -->
                                <a class="dropdown-item" href="{{ route('rss_feeds.index') }}"><i class="fas fa-rss"></i> RSS 訊息</a>
                                <a class="dropdown-item" href="{{ route('setups.index') }}"><i class="fas fa-desktop"></i> 網站設定</a>
                            </div>
                        </li>
                    @endif
                    <li class="nav-item dropdown @yield('nav_user_active')">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-user"></i> {{ auth()->user()->title }} {{ auth()->user()->name }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                            @if(auth()->user()->login_type=="local")
                                <a class="dropdown-item" href="{{ route('edit_password') }}"><i class="fas fa-key"></i> 更改密碼</a>
                            @endif
                            @if(auth()->user()->admin)
                                <a class="dropdown-item" href="{{ route('teach_system') }}"><i class="fas fa-tag"></i> 系統教學</a>
                            @endif
                            <a class="dropdown-item" href="{{ route('wrench.index') }}"><i class="fas fa-wrench"></i> 系統報錯與建議</a>
                            @impersonating
                            <a class="dropdown-item" href="{{ route('sims.impersonate_leave') }}" onclick="return confirm('確定返回原本帳琥？')"><i class="fas fa-user-ninja"></i> 結束模擬</a>
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
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">登入</a>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
