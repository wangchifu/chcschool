<div class="card my-4">
    <h3 class="card-header">在職列表</h3>
    <div class="card-body">
        <table class="table table-striped" style="word-break:break-all;">
            <thead class="thead-light">
            <tr>
                <th>序號</th>
                <th>帳號</th>
                <th>排序</th>
                <th>姓名</th>
                <th>職稱</th>
                <th>群組</th>
                <th>動作</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <td>
                        {{ $user->id }}
                    </td>
                    <td>
                        {{ $user->username }}
                    </td>
                    <td>
                        {{ $user->order_by }}
                    </td>
                    <td>
                        @if($user->admin)
                            <i class="fas fa-crown"></i>
                        @endif
                        {{ $user->name }}
                    </td>
                    <td>
                        {{ $user->title }}
                    </td>
                    <td>
                        <?php $groups = config('chcschool.groups'); ?>
                        @if($user->group_id)
                            {{ $groups[$user->group_id] }}
                        @endif
                    </td>
                    <td>
                        <a href="javascript:open_window('{{ route('users.edit',$user->id) }}','新視窗')" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> 修改</a>
                        <a href="{{ route('sims.impersonate',$user->id) }}" class="btn btn-secondary btn-sm">模擬登入</a>
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
        window.open(url,name,'statusbar=no,scrollbars=yes,status=yes,resizable=yes,width=1000,height=230');
    }
</script>
