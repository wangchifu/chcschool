<div class="card my-4">
    <h3 class="card-header">列表</h3>
    <div class="card-body">
        <a href="javascript:open_window('{{ route('users.create') }}','新視窗')" class="btn btn-success btn-sm">新增本機帳號</a>
        <table class="table table-striped" style="word-break:break-all;">
            <thead class="thead-light">
            <tr>
                <th>排序</th>
                <th>姓名(帳號)</th>
                <th>職稱</th>
                <th>群組</th>
                <th>類別</th>
                <th>動作</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <td nowrap>
                        @if($user->disable)
                            <i class="fas fa-times-circle text-danger"></i>
                        @else
                            <i class="fas fa-check-circle text-success"></i>
                        @endif
                        {{ $user->order_by }}
                    </td>
                    <td>
                        @if($user->admin)
                            <i class="fas fa-crown"></i>
                        @endif
                        {{ $user->name }} ({{ $user->username }})
                    </td>
                    <td>
                        {{ $user->title }}
                    </td>
                    <td>
                        @foreach($user->groups as $group)
                            {{ $group->group->name }}
                        @endforeach
                    </td>
                    <td>
                        @if($user->login_type=="local")
                            本機帳號
                        @elseif($user->login_type=="gsuite")
                            gsuite帳號
                        @elseif($user->login_type=="openID")
                            openID帳號
                        @endif
                    </td>
                    <td>
                        <a href="javascript:open_window('{{ route('users.edit',$user->id) }}','新視窗')" class="btn btn-outline-primary btn-sm"><i class="fas fa-edit"></i> 修改</a>
                        @if($user->login_type=="local")
                            <a href="{{ route('users.back_pwd',$user->id) }}" class="btn btn-warning" onclick="return confirm('確定？')">還原密碼</a>
                        @endif
                        @if($user->id != auth()->user()->id)
                            <a href="{{ route('sims.impersonate',$user->id) }}" class="btn btn-secondary btn-sm" onclick="return confirm('確定模擬？')"><i class="fas fa-user-ninja"></i> 模擬登入</a>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>        
    </div>
</div>
<script>
    function submit_form(id){
        document.getElementById(id).submit();
    }
    function open_window(url,name)
    {
        window.open(url,name,'statusbar=no,scrollbars=yes,status=yes,resizable=yes,width=1000,height=400');
    }
</script>
