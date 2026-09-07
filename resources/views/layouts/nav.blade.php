<?php $module_setup = get_module_setup(); ?>
<style>
    .custom-toggler.navbar-toggler {
        border-color: rgba(255,255,255,0.5);
    }
    .custom-toggler .navbar-toggler-icon {
        background-image: url("data:image/svg+xml;charset=utf8,%3Csvg viewBox='0 0 32 32' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='rgba(255,255,255, 0.5)' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 8h24M4 16h24M4 24h24'/%3E%3C/svg%3E");
    }

    /* 解決手機狀態下固定 Navbar 時下拉選單無法滾動的問題 */
    @media (max-width: 767.98px) {
        .navbar.fixed-top {
            overflow-y: auto !important; /* 允許 Navbar 滾動 */
            -webkit-overflow-scrolling: touch; /* 改善滾動效果 */
        }
        .navbar-collapse {
            max-height: calc(100vh - 56px); /* 調整 Navbar 折疊時的最大高度 */
            overflow-y: auto; /* 允許 Navbar 折疊內容滾動 */
        }
    }
</style>
<?php
    //$setup = \App\Setup::first();
    $fixed_top = ($setup->fixed_nav)?"fixed-top ":null;
?>

<nav class="navbar navbar-expand-lg {{ $nav_color }} {{ $fixed_top }}" id="mainNav" role="navigation" aria-label="主要選單導覽">
    <div class="container-fluid">
        {{-- 無障礙定位點：頂部導覽區 U --}}
        <a href="#page-top" accesskey="U" title="頂部主要導覽區 (AccessKey: U)" style="margin-right: 10px;">
            @if(file_exists(storage_path('app/public/'.$school_code.'/title_image/logo.ico')))
                <img src="{{ asset('storage/'.$school_code.'/title_image/logo.ico') }}" width="30" height="30" class="d-inline-block align-top" alt="{{ $setup->site_name }}標誌">
            @else
                <img src="{{ asset('images/site_logo.png') }}" width="30" height="30" class="d-inline-block align-top" alt="預設的學校標誌">
            @endif
        </a>
        <a class="navbar-brand js-scroll-trigger" href="{{ route('index') }}" style="white-space:pre-wrap;" title="返回網站首頁">{{ $setup->site_name }}</a>
        
        <button class="navbar-toggler custom-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="切換導覽選單顯示">
            <span class="navbar-toggler-icon" aria-hidden="true"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item @yield('nav_home_active')">
                    <?php 
                        $homepage_name = ($setup->homepage_name)?$setup->homepage_name:"首頁"; 
                        $is_home_active = (trim($__env->yieldContent('nav_home_active')) == 'active');
                    ?>
                    <a class="nav-link" href="{{ route('index') }}" @if($is_home_active) aria-current="page" @endif>
                        {{ $homepage_name }}
                        @if($is_home_active) <span class="sr-only">(目前頁面)</span> @endif
                    </a>
                </li>
                @if(isset($module_setup['公告系統']))
                    <li class="nav-item @yield('nav_post_active')">
                        <?php 
                            $post_name = ($setup->post_name)?$setup->post_name:"公告系統"; 
                            $is_post_active = (trim($__env->yieldContent('nav_post_active')) == 'active');
                        ?>
                        <a class="nav-link" href="{{ route('posts.index') }}" @if($is_post_active) aria-current="page" @endif>
                            {{ $post_name }}
                            @if($is_post_active) <span class="sr-only">(目前頁面)</span> @endif
                        </a>
                    </li>
                @endif
                @if(isset($module_setup['檔案庫']))
                    <li class="nav-item @yield('nav_open_files_active')">
                        <?php 
                            $openfile_name = ($setup->openfile_name)?$setup->openfile_name:"檔案庫"; 
                            $is_files_active = (trim($__env->yieldContent('nav_open_files_active')) == 'active');
                        ?>
                        <a class="nav-link" href="{{ route('open_files.index') }}" @if($is_files_active) aria-current="page" @endif>
                            {{ $openfile_name }}
                            @if($is_files_active) <span class="sr-only">(目前頁面)</span> @endif
                        </a>
                    </li>
                @endif
                @if(isset($module_setup['學校介紹']))
                    <li class="nav-item dropdown @yield('nav_departments_active')">
                        <?php $department_name = ($setup->department_name)?$setup->department_name:"學校介紹"; ?>
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLinkDepartments" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ $department_name }}
                        </a>
                        <?php $departments = \App\Department::orderBy('order_by')->get(); ?>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLinkDepartments">
                            @foreach($departments as $department)
                                <a class="dropdown-item" href="{{ route('departments.show',$department->id) }}">
                                    <i class="fas fa-puzzle-piece" aria-hidden="true"></i> {{ $department->title }}
                                </a>
                            @endforeach
                        </div>
                    </li>
                @endif
                @if(isset($module_setup['選單連結']))
                    <?php 
                        $types = \App\Type::where('type_id',null)->orderBy('order_by')->get();
                        $type2s = \App\Type::where('type_id','<>',null)->orderBy('order_by')->get();
                        $links = \App\Link::orderBy('order_by')->get();
                        $type2_data = [];
                        foreach($type2s as $type2){
                            $type2_data[$type2->type_id][$type2->id]['name'] = $type2->name;
                        }
                        
                        $link_data = [];
                        foreach($links as $link){
                            $link_data[$link->type_id][$link->id]['target'] = $link->target;
                            $link_data[$link->type_id][$link->id]['url'] = $link->url;
                            $link_data[$link->type_id][$link->id]['icon'] = $link->icon;
                            $link_data[$link->type_id][$link->id]['name'] = $link->name;
                        }
                    ?>
                    @foreach($types as $type)
                       <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#!" id="navbarDropdownMenuLink_{{ $type->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ $type->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink_{{ $type->id }}">
                                @if(isset($type2_data[$type->id]))
                                    @foreach($type2_data[$type->id] as $k=>$v)
                                    <li>
                                        <a class="dropdown-item dropdown-toggle" href="#">
                                            <i class="fas fa-folder" aria-hidden="true"></i> {{ $v['name'] }}
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-right">
                                            @if(isset($link_data[$k]))
                                                @foreach($link_data[$k] as $k2=>$v2)
                                                <?php
                                                    if($v2['target'] == null) $target = "_blank";
                                                    if($v2['target'] == "_self") $target = "_self";
                                                ?>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ $v2['url'] }}" target="{{ $target }}" @if($target == '_blank') title="{{ $v2['name'] }} (另開新視窗)" @endif>
                                                            @if($v2['icon']==null)
                                                            <i class="fas fa-globe" aria-hidden="true"></i>
                                                            @else
                                                            <i class="{{ $v2['icon'] }}" aria-hidden="true"></i>
                                                            @endif
                                                            {{ $v2['name'] }}
                                                            @if($v2['target'] == null)
                                                            <i class="fas fa-level-up-alt" aria-hidden="true"></i>
                                                            <span class="sr-only">(另開新視窗)</span>
                                                            @endif
                                                        </a>
                                                    </li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </li>
                                    @endforeach
                                @endif
                                @if(isset($link_data[$type->id]))
                                    @foreach($link_data[$type->id] as $k=>$v)
                                        <?php
                                            if($v['target'] == null) $target = "_blank";
                                            if($v['target'] == "_self") $target = "_self";
                                        ?>
                                        <li>
                                            <a class="dropdown-item" href="{{ $v['url'] }}" target="{{ $target }}" @if($target == '_blank') title="{{ $v['name'] }} (另開新視窗)" @endif>
                                                @if($v['icon']==null)
                                                <i class="fas fa-globe" aria-hidden="true"></i>
                                                @else
                                                <i class="{{ $v['icon'] }}" aria-hidden="true"></i>
                                                @endif
                                                {{ $v['name'] }}
                                                @if($v['target'] == null)
                                                <i class="fas fa-level-up-alt" aria-hidden="true"></i>
                                                <span class="sr-only">(另開新視窗)</span>
                                                @endif
                                            </a>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </li>
                    @endforeach
                @endif
            </ul>
            <ul class="nav navbar-nav navbar-right">                
                @auth
                    @if(isset($module_setup['校務行政']))
                        <li class="nav-item dropdown @yield('nav_school_active')">
                            <?php $schoolexec_name = ($setup->schoolexec_name)?$setup->schoolexec_name:"校務行政"; ?>
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLinkSchoolExec" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ $schoolexec_name }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLinkSchoolExec">
                                @if(isset($module_setup['校務行事曆']))
                                    <a class="dropdown-item" href="{{ route('calendars.index') }}">
                                        <i class="fas fa-calendar" aria-hidden="true"></i> 校務行事曆
                                    </a>
                                @endif
                                @if(isset($module_setup['校務月曆']))
                                    <a class="dropdown-item" href="{{ route('monthly_calendars.index') }}">
                                        <i class="fas fa-calendar-alt" aria-hidden="true"></i> 校務月曆
                                    </a>
                                @endif
                                @if(isset($module_setup['內部文件']))
                                    <a class="dropdown-item" href="{{ route('inside_files.index') }}">
                                        <i class="fab fa-linkedin-in" aria-hidden="true"></i> 內部文件
                                    </a>
                                @endif
                                @if(isset($module_setup['會議文稿']))
                                    <a class="dropdown-item" href="{{ route('meetings.index') }}">
                                        <i class="fas fa-comments" aria-hidden="true"></i> 會議文稿
                                    </a>
                                @endif
                                @if(isset($module_setup['報修系統']))
                                    <a class="dropdown-item" href="{{ route('fixes.index') }}">
                                        <i class="fas fa-wrench" aria-hidden="true"></i> 報修系統
                                    </a>
                                @endif
                                @if(isset($module_setup['教室預約']))
                                    <a class="dropdown-item" href="{{ route('classroom_orders.index') }}">
                                        <i class="fas fa-chess-rook" aria-hidden="true"></i> 教室預約
                                    </a>
                                @endif
                                @if(isset($module_setup['午餐系統']))
                                    <a class="dropdown-item" href="{{ route('lunches.index') }}">
                                        <i class="fas fa-utensils" aria-hidden="true"></i> 午餐系統
                                    </a>
                                @endif
                                @if(isset($module_setup['社團報名']))
                                    <a class="dropdown-item" href="{{ route('clubs.index') }}">
                                        <i class="fas fa-table-tennis" aria-hidden="true"></i> 社團報名
                                    </a>
                                @endif
                                @if(isset($module_setup['校園部落格']))
                                    <a class="dropdown-item" href="{{ route('blogs.index') }}">
                                        <i class="fas fa-newspaper" aria-hidden="true"></i> 校園部落格
                                    </a>
                                @endif
                                @if(isset($module_setup['行政待辦']))
                                    <a class="dropdown-item" href="{{ route('tasks.index') }}">
                                        <i class="fas fa-tasks" aria-hidden="true"></i> 行政待辦
                                    </a>
                                @endif
                                @if(isset($module_setup['借用系統']))
                                    <a class="dropdown-item" href="{{ route('lends.index') }}">
                                        <i class="fas fa-archive" aria-hidden="true"></i> 借用系統
                                    </a>
                                @endif
                                @if(isset($module_setup['校園跑馬燈']))
                                    <a class="dropdown-item" href="{{ route('school_marquee.index') }}"><i class="fas fa-running" aria-hidden="true"></i> 校園跑馬燈</a>
                                @endif
                                @if(isset($module_setup['運動會報名']))
                                    <a class="dropdown-item" href="{{ route('sport_meeting.index') }}"><i class="fas fa-volleyball-ball" aria-hidden="true"></i> 運動會報名</a>
                                @endif   
                                @if(isset($module_setup['填報學生']))
                                    <a class="dropdown-item" href="{{ route('report_students.index') }}"><i class="fas fa-child" aria-hidden="true"></i> 填報學生</a>
                                @endif                             
                                @if(isset($module_setup['學生帳號']))
                                    <a class="dropdown-item" href="{{ route('student_account.index') }}"><i class="far fa-user-circle" aria-hidden="true"></i> 學生帳號</a>
                                @endif                             
                                <a class="dropdown-item" href="{{ route('photo_links.index') }}"><i class="fas fa-image" aria-hidden="true"></i> 圖片連結</a>
                            </div>
                        </li>
                    @endif
                    @if(auth()->user()->admin)
                        <li class="nav-item dropdown @yield('nav_setup_active')">
                            <?php $setup_name = ($setup->setup_name)?$setup->setup_name:"系統設定"; ?>
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLinkAdminSetup" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ $setup_name }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLinkAdminSetup">
                                <a class="dropdown-item" href="{{ route('users.index') }}"><i class="fas fa-user" aria-hidden="true"></i> 帳號管理</a>
                                <a class="dropdown-item" href="{{ route('groups.index') }}"><i class="fas fa-users" aria-hidden="true"></i> 群組管理</a>
                                <a class="dropdown-item" href="{{ route('departments.index') }}"><i class="fas fa-puzzle-piece" aria-hidden="true"></i> 學校介紹管理</a>
                                <a class="dropdown-item" href="{{ route('school_marquee.setup') }}"><i class="fas fa-running" aria-hidden="true"></i> 校園跑馬燈管理</a>
                                <a class="dropdown-item" href="{{ route('contents.index') }}"><i class="fas fa-file-alt" aria-hidden="true"></i> 內容管理</a>
                                <a class="dropdown-item" href="{{ route('links.index') }}"><i class="fas fa-link" aria-hidden="true"></i> 選單連結</a>
                                <a class="dropdown-item" href="{{ route('photo_links.index') }}"><i class="fas fa-image" aria-hidden="true"></i> 圖片連結</a>
                                <a class="dropdown-item" href="{{ route('trees.index') }}"><i class="fas fa-tree" aria-hidden="true"></i> 樹狀目錄</a>
                                <a class="dropdown-item" href="{{ route('rss_feeds.index') }}"><i class="fas fa-rss" aria-hidden="true"></i> RSS 訊息</a>
                                <a class="dropdown-item" href="{{ route('setups.index') }}"><i class="fas fa-desktop" aria-hidden="true"></i> 網站設定</a>
                                <a class="dropdown-item" href="{{ route('dns.index') }}" target="_blank" title="DNS 網域設定 (另開新視窗)">
                                    <i class="fas fa-route" aria-hidden="true"></i> DNS 網域設定
                                    <span class="sr-only">(另開新視窗)</span>
                                </a>                                
                            </div>
                        </li>
                    @endif
                    <li class="nav-item dropdown @yield('nav_user_active')">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLinkUser" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="使用者選單">
                            <i class="fas fa-user" aria-hidden="true"></i>
                            <span class="sr-only">使用者選單</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLinkUser">
                            <a class="dropdown-item" href="#" onclick="click_count()"><i class="fas fa-user" aria-hidden="true"></i> {{ auth()->user()->title }} {{ auth()->user()->name }}</a>
                            <script>
                                var n=0;
                                function click_count(){
                                    n = n+1;
                                    alert('點了'+n+'下!');
                                }
                            </script>
                            @if(auth()->user()->login_type=="openID")
                                <a class="dropdown-item" href="{{ route('edit_title') }}"><i class="fas fa-user-tag" aria-hidden="true"></i> 更改職稱</a>
                            @endif
                            @if(auth()->user()->login_type=="local")
                                <a class="dropdown-item" href="{{ route('edit_password') }}"><i class="fas fa-key" aria-hidden="true"></i> 更改密碼</a>
                            @endif
                            @if(auth()->user()->admin)
                                <a class="dropdown-item" href="{{ route('teach_system') }}"><i class="fas fa-tag" aria-hidden="true"></i> 系統教學</a>
                            @endif
                            <a class="dropdown-item" href="{{ route('wrench.index') }}"><i class="fas fa-wrench" aria-hidden="true"></i> 系統報錯與建議</a>
                            @impersonating
                            <a class="dropdown-item" href="{{ route('sims.impersonate_leave') }}" onclick="return confirm('確定返回原本帳號？')"><i class="fas fa-user-ninja" aria-hidden="true"></i> 結束模擬</a>
                            @endImpersonating
                            <a class="dropdown-item" href="#" onclick="
                            if(confirm('您確定登出嗎?')) document.getElementById('logout-form').submit();
                                else return false">
                                <i class="fas fa-sign-out-alt" aria-hidden="true"></i> 登出
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endauth
                @guest
                    <li class="nav-item">
                        <?php $login_name = ($setup->login_name)?$setup->login_name:"登入"; ?>
                        <a class="nav-link" href="{{ route('logins') }}">{{ $login_name }}</a>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>