<ul class="nav nav-tabs">
    @if($admin)
    <li class="nav-item">
        <a class="nav-link {{ $active['admin'] }}" href="{{ route('sport_meeting.admin') }}">學校管理員</a>
    </li>
    @endif        
    <li class="nav-item">
        <a class="nav-link {{ $active['show'] }}" href="{{ route('sport_meeting.index') }}">歷屆成績</a>
    </li>
    @if($admin)
    <li class="nav-item">
        <a class="nav-link {{ $active['list'] }}" href="{{ route('sport_meeting.list') }}">各式表單</a>
    </li>
    @endif
    @if($admin or $bdmin)
    <li class="nav-item">
        <a class="nav-link {{ $active['score'] }}" href="{{ route('sport_meeting.score') }}">成績處理</a>
    </li>    
    @endif
    <li class="nav-item">
        <a class="nav-link {{ $active['teacher'] }}" href="{{ route('sport_meeting.teacher') }}">導師填報</a>
    </li>    
</ul>
