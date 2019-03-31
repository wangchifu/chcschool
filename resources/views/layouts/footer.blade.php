<br>
<br>
<footer class="font-small bg-light py-4">
    <div class="container-fluid text-center text-md-left">
        <div class="row justify-content-center">
            {!! $setup->footer !!}
        </div>
        <div class="row justify-content-center">
            <a href="{{ route('index') }}">{{ $setup->site_name }}</a>　　訪客人次:{{ $setup->views }}
        </div>
    </div>
</footer>
