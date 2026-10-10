<?php $module_setup = get_module_setup(); ?>
<?php
    //$setup = \App\Setup::first();
    $fixed_top = ($setup->fixed_nav)?"fixed-top ":null;
?>
@if($nav_color == 'navbar-custom' && isset($navbar_custom[0]))
<style>
    .navbar-custom {
        /* $navbar_custom[0]: Navbar 底色 */
        background-color: {{ $navbar_custom[0] }} !important;
        
        /* $navbar_custom[1]: 網站名稱 / 文字顏色 */
        --nav-brand-color: {{ $navbar_custom[1] }};
        
        /* $navbar_custom[2]: 連結文字顏色 */
        --nav-link-color: {{ $navbar_custom[2] }};
        
        /* $navbar_custom[3]: Hover / Active 文字顏色 */
        --nav-hover-color: {{ $navbar_custom[3] }};
    }
</style>
@endif
<nav class="navbar navbar-expand-lg {{ $nav_color }} {{ $fixed_top }}" id="mainNav" role="navigation" aria-label="主要選單導覽">
    <div class="container-fluid">        
        <a class="navbar-brand js-scroll-trigger d-inline-flex align-items-center" href="{{ route('index') }}" title="返回網站首頁">
            @if(file_exists(storage_path('app/public/'.$school_code.'/title_image/logo.ico')))
                <img src="{{ asset('storage/'.$school_code.'/title_image/logo.ico') }}" width="30" height="30" class="mr-2" alt="" aria-hidden="true">
            @else
                <img src="{{ asset('images/site_logo.png') }}" width="30" height="30" class="mr-2" alt="" aria-hidden="true">
            @endif
            <span style="white-space: pre-wrap;">{{ $setup->site_name }}</span>
        </a>        
        
        <button class="navbar-toggler custom-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="切換導覽選單顯示">
            <span class="navbar-toggler-icon" aria-hidden="true"></span>
        </button>                
    </div>
</nav>