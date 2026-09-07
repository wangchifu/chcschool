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
$dt = Carbon::create($d[0], $d[1],1);
$next_month = $dt->addMonthsNoOverflow(1)->format('Y-m');

$dt = Carbon::create($d[0], $d[1],1);
$last_month = $dt->subMonthsNoOverflow(1)->format('Y-m');

$this_month_date = get_month_date($this_month);
$first_w = get_date_w($this_month_date[1]);
?>

@can('create',\App\Post::class)
    <script src="{{ asset('gijgo/js/gijgo.min.js') }}" type="text/javascript"></script>
    <link href="{{ asset('gijgo/css/gijgo.min.css') }}" rel="stylesheet" type="text/css">
    
    {{ Form::open(['route' => 'monthly_calendars.block_store', 'method' => 'POST','id'=>'create_calendar_form','onsubmit'=>'return false']) }}
    <div class="form-row align-items-center mb-3">
        <div class="col-auto">
            {{-- 無障礙 HM1130100C 修正：補上對應的標籤 --}}
            <label for="item_date" class="sr-only">選擇日期</label>
            <input id="item_date" name="item_date" class="form-control" required maxlength="10" value="{{ date('Y-m-d') }}" aria-label="新增事項的日期">
        </div>
        <div class="col-auto">
            <label for="item" class="sr-only">輸入事項內容</label>
            {{ Form::text('item',null,['id'=>'item','class' => 'form-control','required'=>'required', 'placeholder' => '請輸入事項內容', 'aria-label' => '事項內容']) }}
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-success" onclick="if(confirm('您確定送出嗎?')) add_item('{{ $this_month }}');else return false">
                <i class="fas fa-plus" aria-hidden="true"></i> 新增事項
            </button>
        </div>
    </div>
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
            success : function(result) {
                if(result != 'failed') {
                    go_submit(this_month);
                }
            },
            error: function(result) {
                alert('刪除失敗！');
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
                alert('切換失敗！');
            }
        })
    }

    function show_calendar(result){        
        /* 無障礙 HM1120201C 修正：改用具備無障礙標籤的按鈕控制月份切換 */
        data = '<div class="d-flex align-items-center mb-2">';
        data += '<button type="button" class="btn btn-link text-primary p-0 border-0" aria-label="切換至上一個月 ('+result['last_month']+')" onclick="go_submit(\''+result['last_month']+'\')"><i class="fas fa-arrow-alt-circle-left fa-2x" aria-hidden="true"></i></button>';
        data += '<span class="h4 mx-3 mb-0 font-weight-bold">' + result['this_month'] + '</span>';
        data += '<button type="button" class="btn btn-link text-primary p-0 border-0" aria-label="切換至下一個月 ('+result['next_month']+')" onclick="go_submit(\''+result['next_month']+'\')"><i class="fas fa-arrow-alt-circle-right fa-2x" aria-hidden="true"></i></button>';
        data += '</div>';

        data += '<div class="table-responsive"><table class="table table-bordered table-sm" aria-label="'+result['this_month']+' 行事曆數據表格">';
        data += '<thead><tr class="bg-secondary text-white">';
        data += '<th class="text-danger bg-light">日</th><th>一</th><th>二</th><th>三</th><th>四</th><th>五</th><th class="text-success bg-light">六</th>';
        data += '</tr></thead><tbody><tr>';

        for(var k in result['this_month_date']){
            if(k==1){
                for(i=1;i<=result['this_month_date_w'][result['this_month_date'][k]];i++){
                    data += '<td width="14%"></td>';
                }
            }
            /* 無障礙 CS2140401C 修正：改用相對單位 font-size: 1.0625rem; */
            if(result['today'] == result['this_month_date'][k]){
                data += '<td width="14%" style="background-color:#FFFFBB;">';
                data += '<div style="font-weight: bold;font-size: 1.0625rem;color:green;">';
            }else{
                data += '<td width="14%" style="background-color:#FFFFFF;">';
                data += '<div style="font-weight: bold;font-size: 1.0625rem;">';
            }

            this_date = result['this_month_date'][k].substring(8,10);
            this_month = result['this_month_date'][k].substring(0,7);
            data += this_date;

            var bg_array = ['info','success','warning','primary','secondary','danger'];
            var qq=0;

            for(var k1 in result['item_array']){
                if(result['item_array'][k1]['item_date'] == [result['this_month_date'][k]]){
                    var cht = cht_str(result['item_array'][k1]['item'],20);
                    var q = qq%6;
                    
                    data += '<div class="bg-'+bg_array[q]+' text-white p-1 my-1 rounded" title="'+result['item_array'][k1]['item']+'" tabindex="0" aria-label="事項：'+result['item_array'][k1]['item']+'">';
                    data += cht;
                    
                    if(result['user_id'] == result['item_array'][k1]['user_id'] || result['admin'] == "1"){
                        /* 無障礙 HM1240401C 修正：圖片補上 alt 屬性 */
                        data += ' <button type="button" class="btn btn-sm btn-link p-0 text-white float-right" aria-label="刪除此事項" onclick="if(confirm(\'確定刪除嗎?\')) del_item(\''+k1+'\',\''+this_month+'\');else return false;">';
                        data += '<img src="{{ asset('images/remove.png') }}" height="15px" alt="刪除事項" title="刪除事項">';
                        data += '</button>';
                    }
                    data += '</div>';
                    qq++;
                }
            }
            data += '</div></td>';

            if(result['this_month_date_w'][result['this_month_date'][k]]==6){
                data += '</tr><tr>';
            }
        }
        $nn = 6-result['last_w'];
        for($i=1;$i<=$nn;$i++){
            data += '<td></td>';
        }
        data += '</tr></tbody></table></div>';

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
    {{-- 無障礙 HM1120201C 修正：補上明確的可朗讀按鈕與區域標示 --}}
    <div class="d-flex align-items-center mb-2">
        <button type="button" class="btn btn-link text-primary p-0 border-0" aria-label="切換至上一個月 ({{ $last_month }})" onclick="go_submit('{{ $last_month }}')">
            <i class="fas fa-arrow-alt-circle-left fa-2x" aria-hidden="true"></i>
        </button> 
        <span class="h4 mx-3 mb-0 font-weight-bold">{{ $this_month }}</span>
        <button type="button" class="btn btn-link text-primary p-0 border-0" aria-label="切換至下一個月 ({{ $next_month }})" onclick="go_submit('{{ $next_month }}')">
            <i class="fas fa-arrow-alt-circle-right fa-2x" aria-hidden="true"></i>
        </button>
    </div>

    <div class="table-responsive">
    <table class="table table-bordered table-sm" aria-label="{{ $this_month }} 行事曆數據表格">
        <thead>
        <tr class="bg-secondary text-white">
            <th class="text-danger bg-light">日</th>
            <th>一</th>
            <th>二</th>
            <th>三</th>
            <th>四</th>
            <th>五</th>
            <th class="text-success bg-light">六</th>
        </tr>
        </thead>
        <tbody>
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
                    {{-- 無障礙 CS2140401C 修正：改用相對單位 font-size: 1.0625rem; --}}
                    <div style="font-weight: bold;font-size: 1.0625rem;{{ $c }}">
                        {{ $num }}
                    </div>
                    @foreach($item_array as $k1=>$v1)
                        <?php
                            $q = $qq%6;
                        ?>
                        @if($v1['item_date'] == $v)
                            <div class="bg-{{ $bg_array[$q] }} text-white p-1 my-1 rounded" title="{{ $v1['item'] }}" tabindex="0" aria-label="事項：{{ $v1['item'] }}">
                                {{ str_limit($v1['item'],20) }}
                                @auth
                                    @if($v1['user_id'] == auth()->user()->id or auth()->user()->admin == 1)
                                        <button type="button" class="btn btn-sm btn-link p-0 text-white float-right" aria-label="刪除此事項" onclick="if(confirm('確定刪除嗎?')) del_item('{{ $k1 }}','{{ $this_month }}');else return false;">
                                            <img src="{{ asset('images/remove.png') }}" height="15px" alt="刪除事項" title="刪除事項">
                                        </button>
                                    @endif
                                @endauth
                            </div>
                            <?php $qq++; ?>
                        @endif
                    @endforeach
                </td>
                @if($this_date_w == 6)
                    </tr><tr>
                @endif
            @endforeach
            @for($i=1;$i<=6-$this_date_w;$i++)
                <td></td>
            @endfor
        </tr>
        </tbody>
    </table>
    </div>
</div>