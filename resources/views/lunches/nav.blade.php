<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link {{ $active['teacher'] }}" href="{{ route('lunches.index') }}">教職員訂餐</a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ $active['setup'] }}" href="{{ route('lunch_setups.index') }}">午餐設定</a>
    </li>
</ul>
