<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link {{ $active['index'] }}" href="{{ route('teacher_absents.index') }}">假單處理</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $active['deputy'] }}" href="{{ route('teacher_absents.deputy') }}">職務代理</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $active['sir'] }}" href="{{ route('teacher_absents.sir') }}"><i class="fas fa-crown"></i> 簽核記錄排代</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $active['travel'] }}" href="{{ route('teacher_absents.travel') }}">差旅費列表</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $active['list'] }}" href="{{ route('teacher_absents.list') }}"><i class="fas fa-crown"></i> 差假列表</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $active['total'] }}" href="{{ route('teacher_absents.total') }}">差假統計</a>
    </li>
</ul>
