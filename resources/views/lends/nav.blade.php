<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link {{ $active['index'] }}" href="{{ route('lends.index') }}">教職員借用</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $active['my_list'] }}" href="{{ route('lends.my_list') }}">我的借用</a>
    </li>
    @if($admin)
    <li class="nav-item">
        <a class="nav-link {{ $active['admin'] }}" href="{{ route('lends.admin') }}">管理者</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $active['list'] }}" href="{{ route('lends.list') }}">借用清單</a>
    </li>
    @endif
</ul>
