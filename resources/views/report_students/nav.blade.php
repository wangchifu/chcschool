<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link {{ $active['index'] }}" href="{{ route('report_students.index') }}">導師填報</a>
    </li>
    @if($admin)    
    <li class="nav-item">
        <a class="nav-link {{ $active['admin'] }}" href="{{ route('report_students.admin') }}">填報管理</a>
    </li>
    @endif
</ul>
