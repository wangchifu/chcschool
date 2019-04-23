<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link {{ $active['index'] }}" href="{{ route('teacher_absents.index') }}">假單處理</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $active['deputy'] }}" href="{{ route('teacher_absents.deputy') }}">職務代理</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $active['sir'] }}" href="{{ route('teacher_absents.sir') }}">批示簽核</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $active['teach'] }}" href="">教學排代</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $active['travel'] }}" href="">差旅費列表</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $active['travel_print'] }}" href="">差旅費列印</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $active['list'] }}" href="">差假列表</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $active['total'] }}" href="">差假統計</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $active['admin'] }}" href="">管理功能</a>
    </li>
</ul>
