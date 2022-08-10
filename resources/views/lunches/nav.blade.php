<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link {{ $active['teacher'] }}" href="{{ route('lunches.index') }}">教職員訂餐</a>
    </li>
    @if($admin)
    <li class="nav-item">
        <a class="nav-link {{ $active['student'] }}" href="{{ route('lunch_stus.index') }}">學生訂餐</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $active['list'] }}" href="{{ route('lunch_lists.index') }}">報表輸出</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $active['special'] }}" href="{{ route('lunch_specials.index') }}">特殊處理</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $active['order'] }}" href="{{ route('lunch_orders.index') }}">餐期管理</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $active['setup'] }}" href="{{ route('lunch_setups.index') }}">午餐設定</a>
    </li>
    @endif
</ul>
