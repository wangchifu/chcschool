<div class="card">
    <div class="card-body">
        你是 {{ substr($user->class_num,0,3) }}班 {{ substr($user->class_num,3,2) }}號 {{ $user->name }}
        <a href="{{ route('clubs.parents_logout') }}" class="btn btn-danger btn-sm" onclick="return confirm('確定登出？')">登出</a>
        <a href="{{ route('clubs.change_pwd',$class_id) }}" class="btn btn-warning btn-sm">更換密碼</a>
    </div>
</div>
