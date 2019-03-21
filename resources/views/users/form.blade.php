<div class="card my-4">
    <h3 class="card-header">在職列表</h3>
    <div class="card-body">
        <table class="table table-striped" style="word-break:break-all;">
            <thead class="thead-light">
            <tr>
                <th>排序</th>
                <th>姓名(帳號)</th>
                <th>職稱</th>
                <th>群組</th>
                <th>動作</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <td>
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
                        <a href="javascript:open_window('{{ route('users.edit',$user->id) }}','新視窗')" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> 修改</a>
                        <a href="{{ route('sims.impersonate',$user->id) }}" class="btn btn-secondary btn-sm" onclick="return confirm('確定模擬？')">模擬登入</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $users->links() }}
    </div>
</div>
<script>
    function submit_form(id){
        document.getElementById(id).submit();
    }
    function open_window(url,name)
    {
        window.open(url,name,'statusbar=no,scrollbars=yes,status=yes,resizable=yes,width=1000,height=330');
    }
</script>
