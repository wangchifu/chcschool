<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link {{ $active[1] }}" href="{{ route('setups.index') }}">基本資料</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $active[2] }}" href="{{ route('setups.photo') }}">首頁圖片</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $active[3] }}" href="{{ route('setups.col') }}">首頁欄位</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $active[4] }}" href="{{ route('setups.block') }}">區塊內容</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $active[5] }}" href="{{ route('setups.module') }}">模組功能</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $active[6] }}" href="{{ route('setups.quota') }}">空間管理</a>
    </li>
</ul>
