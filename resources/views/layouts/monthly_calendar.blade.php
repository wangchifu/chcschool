<?php
use Carbon\Carbon;
$this_month =(empty($month))?date('Y-m'):$month;

$items = \App\MonthlyCalendar::where('item_date','like',$this_month.'%')->get();
$item_array = [];
foreach($items as $item){
    $item_array[$item->id]['user_id'] = $item->user_id;
    $item_array[$item->id]['item_date'] = $item->item_date;
    $item_array[$item->id]['item'] = $item->item;
}

$d = explode('-',$this_month);
$dt = Carbon::create($d[0], $d[1]);

$next_month =  substr($dt->addMonth()->toDateTimeString(),0,7);
$last_month =  substr($dt->subMonth()->toDateTimeString(),0,7);

$this_month_date = get_month_date($this_month);
$first_w = get_date_w($this_month_date[1]);
?>
@can('create',\App\Post::class)
    <script src="{{ asset('gijgo/js/gijgo.min.js') }}" type="text/javascript"></script>
    <link href="{{ asset('gijgo/css/gijgo.min.css') }}" rel="stylesheet" type="text/css">
    {{ Form::open(['route' => 'monthly_calendars.block_store', 'method' => 'POST','id'=>'create_calendar_form','onsubmit'=>'return false']) }}
    <table>
        <tr>
            <td>
                <input id="item_date" name="item_date" required maxlength="10" value="{{ date('Y-m-d') }}">
            </td>
            <td>
                {{ Form::text('item',null,['id'=>'item','class' => 'form-control','required'=>'required', 'placeholder' => '事項']) }}
            </td>
            <td>
                <button type="submit" class="btn btn-success btn-sm" onclick="if(confirm('您確定送出嗎?')) add_item('{{ $this_month }}');else return false">
                    <i class="fas fa-plus"></i> 新增事項
                </button>
            </td>
        </tr>
    </table>
    <script src="{{ asset('gijgo/js/messages/messages.zh-TW.js') }}"></script>
    <script>
        $('#item_date').datepicker({
            uiLibrary: 'bootstrap4',
            format: 'yyyy-mm-dd',
            locale: 'zh-TW',
        });
    </script>
    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
    {{ Form::close() }}
@endcan
<script>
    function add_item(){
        $.ajax({
            url: '{{ route('monthly_calendars.block_store') }}',
            type : 'post',
            dataType : 'json',
            data : $('#create_calendar_form').serialize(),
            success : function(result) {
                if(result != 'failed') {
                    var m = document.getElementById("item_date").value;
                    month = m.substring(0,7);
                    go_submit(month);
                    document.getElementById("item").value = "";
                }
            },
            error: function(result) {
                alert('是不是忘了填事項？！');
            }
        })
    }

    function del_item(id,this_month){
        $.ajax({
            url: './monthly_calendars/block_destroy/'+id,
            type : 'get',
            dataType : 'json',
            //data : $('#create_calendar_form').serialize(),
            success : function(result) {
                if(result != 'failed') {
                    go_submit(this_month);
                }
            },
            error: function(result) {
                alert('？！');
            }
        })
    }
    function go_submit(month){
        $('#item_month').val(month);
        $.ajax({
            url: '{{ route('monthly_calendars.return_month') }}',
            type : 'post',
            dataType : 'json',
            data : $('#calendar_month').serialize(),
            success : function(result) {
                if(result != 'failed') {
                    total_data = show_calendar(result);
                    document.getElementById('calendar_content').innerHTML = total_data;
                }
            },
            error: function(result) {
                alert('失敗！');
            }
        })
    }

    function show_calendar(result){
        data = '<h2>';
        data = data+'<span style="text-decoration:none;" onclick="go_submit(\''+result['last_month']+'\')"><i class="fas fa-arrow-alt-circle-left text-primary"></i></span> '+result['this_month']+' <span style="text-decoration:none;" onclick="go_submit(\''+result['next_month']+'\')"><i class="fas fa-arrow-alt-circle-right text-primary"></i></span>';
        data = data+'</h2>';
        data = data+'<table class="table table-bordered table-sm">';
        data = data+'<thead>';
        data = data+'<tr style="background-color: #888888">';
        data = data+'<th class="text-danger">日</th>';
        data = data+'<th>一</th>';
        data = data+'<th>二</th>';
        data = data+'<th>三</th>';
        data = data+'<th>四</th>';
        data = data+'<th>五</th>';
        data = data+'<th class="text-success">六</th>';
        data = data+'</tr>';
        data = data+'</thead>';
        data = data+'<tbody>';
        data = data+'<tr>';
        for(var k in result['this_month_date']){
            if(k==1){
                for(i=1;i<=result['this_month_date_w'][result['this_month_date'][k]];i++){
                    data = data+'<td width="14%"></td>';
                }
            }
            if(result['today'] == result['this_month_date'][k]){
                data = data+'<td width="14%" style="background-color:#FFFFBB;">';
                data = data+'<div style="font-weight: bold;font-size: 17px;color:green;">';
            }else{
                data = data+'<td width="14%" style="background-color:#FFFFFF;">';
                data = data+'<div style="font-weight: bold;font-size: 17px;">';
            }

            this_date = result['this_month_date'][k].substring(8,10);
            this_month = result['this_month_date'][k].substring(0,7);
            data = data + this_date;

            var bg_array = ['info','success','warning','primary','secondary','danger'];
            var qq=0;

            for(var k1 in result['item_array']){
                if(result['item_array'][k1]['item_date'] == [result['this_month_date'][k]]){
                    var cht = cht_str(result['item_array'][k1]['item'],16);
                    var q = qq%6;
                    data = data+'<div class="bg-'+bg_array[q]+'" style="font-size:6px;width: 100%;border-radius: 3px;margin: 2px;color: #FFFFFF;padding: 2px;" data-toggle="tooltip" data-placement="top" title="'+result['item_array'][k1]['item']+'" onclick="alert(\''+[result['this_month_date'][k]]+'\\r\\n'+result['item_array'][k1]['item']+'\')">';
                    data = data+cht;
                    data = data+'</div>';
                    if(result['user_id'] == result['item_array'][k1]['user_id'] || result['admin'] == "1"){
                        //data = data + '<a href="./monthly_calendars/destroy/'+k1+'" onclick="return confirm(\'確定刪除嗎？\')">';
                        data = data + '<img src="{{ asset('images/remove.png') }}" height="15px" onclick="if(confirm(\'確定刪除嗎?\')) del_item(\''+k1+'\',\''+this_month+'\');else return false">';
                        //data = data + '</a>';
                    }
                    qq++;
                }
            }
            data =data+'</div>';
            data = data+'</td>';

            if(result['this_month_date_w'][result['this_month_date'][k]]==6){
                data = data+'</tr>';
            }
        }
        $nn = 6-result['last_w'];
        for($i=1;$i<=$nn;$i++){
            data = data + '<td></td>';
        }
        data = data+'</tr>';
        data = data+'</tbody>';
        data = data+'</table>';

        return data;
    }

    function cht_str(str,n){
        var r=/[^\x00-\xff]/g;
        if(str.replace(r,"mm").length<=n){return str;}
        var m=Math.floor(n/2);
        for(var i=m;i<str.length;i++){
            if(str.substr(0,i).replace(r,"mm").length>=n){
                return str.substr(0,i)+"...";
            }
        }
        return str;
    };

</script>
{{ Form::open(['route' => 'monthly_calendars.return_month', 'method' => 'POST','id'=>'calendar_month','onsubmit'=>'return false']) }}
<input type="hidden" name="item_month" id="item_month">
{{ Form::close() }}
<div id="calendar_content">
    <h2>
        <span style="text-decoration:none;" onclick="go_submit('{{ substr($dt->subMonth()->toDateTimeString(),0,7) }}')"><i class="fas fa-arrow-alt-circle-left text-primary"></i></span> {{ $this_month }} <span style="text-decoration:none;" onclick="go_submit('{{ $next_month }}')"><i class="fas fa-arrow-alt-circle-right text-primary"></i></span>
    </h2>
    <table class="table table-bordered table-sm">
        <thead>
        <tr style="background-color: #888888">
            <th class="text-danger">日</th>
            <th>一</th>
            <th>二</th>
            <th>三</th>
            <th>四</th>
            <th>五</th>
            <th class="text-success">六</th>
        </tr>
        </thead>
        <tr>
        <tr>
            @foreach($this_month_date as $k => $v)
                <?php
                $this_date_w = get_date_w($v);
                $bgcolor = ($v == date('Y-m-d'))?"background-color:#FFFFBB;":"background-color:#FFFFFF;";
                ?>
                @if($k == 1)
                    @for($i=1;$i<=$first_w;$i++)
                        <td width="14%"></td>
                    @endfor
                @endif
                <td width="14%" style="{{ $bgcolor }}">
                    <?php
                    $num = substr($v,8,2);
                    $c =($v==date('Y-m-d'))?"color:green;":"";
                    $bg_array = ['info','success','warning','primary','secondary','danger'];
                    $qq = 0;
                    ?>
                    <div style="font-weight: bold;font-size: 17px;{{ $c }}">
                        {{ $num }}
                    </div>
                    @foreach($item_array as $k1=>$v1)
                        <?php
                            $q = $qq%6;
                        ?>
                        @if($v1['item_date'] == $v)
                            <div class="bg-{{ $bg_array[$q] }}" style="font-size:18px;width: 100%;border-radius: 3px;margin: 2px;color: #FFFFFF;padding: 2px;" data-toggle="tooltip" data-placement="top" title="{{ $v1['item'] }}" onclick="alert('{{ $v }}\r\n{{ $v1['item'] }}')">
                                {{ str_limit($v1['item'],16) }}
                            </div>
                            @auth
                                @if($v1['user_id'] == auth()->user()->id or auth()->user()->admin ==1)
                                    <img src="{{ asset('images/remove.png') }}" height="15px" onclick="if(confirm('確定刪除嗎?')) del_item('{{ $k1 }}','{{ $this_month }}');else return false">
                                @endif
                            @endauth
                                <?php
                                $qq++;
                                ?>
                        @endif
                    @endforeach
                </td>
                @if($this_date_w == 6)
                    </tr>
                @endif
        @endforeach
        @for($i=1;$i<=6-$this_date_w;$i++)
            <td></td>
        @endfor
        </tr>
        </tbody>
    </table>
</div>
