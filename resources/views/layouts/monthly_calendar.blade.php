<?php
use Carbon\Carbon;
$this_month = (empty($month)) ? date('Y-m') : $month;

$items = \App\MonthlyCalendar::where('item_date', 'like', $this_month . '%')->get();
$item_array = [];
foreach ($items as $item) {
    $item_array[$item->id]['user_id'] = $item->user_id;
    $item_array[$item->id]['item_date'] = $item->item_date;
    $item_array[$item->id]['item'] = $item->item;
}

$d = explode('-', $this_month);
$dt = Carbon::create($d[0], $d[1], 1);
$next_month = $dt->addMonthsNoOverflow(1)->format('Y-m');

$dt = Carbon::create($d[0], $d[1], 1);
$last_month = $dt->subMonthsNoOverflow(1)->format('Y-m');

$this_month_date = get_month_date($this_month);
$first_w = get_date_w($this_month_date[1]);
?>

@can('create', \App\Post::class)
    <script src="{{ asset('gijgo/js/gijgo.min.js') }}" type="text/javascript"></script>
    <link href="{{ asset('gijgo/css/gijgo.min.css') }}" rel="stylesheet" type="text/css">
    
    {{ Form::open(['route' => 'monthly_calendars.block_store', 'method' => 'POST', 'id' => 'create_calendar_form', 'onsubmit' => 'return false']) }}
    <div class="row g-2 align-items-center mb-3">
        <div class="col-auto">
            <label for="item_date" class="visually-hidden">選擇日期</label>
            <input id="item_date" name="item_date" class="form-control" required maxlength="10" value="{{ date('Y-m-d') }}" aria-label="選擇日期">
        </div>
        <div class="col-auto">
            <label for="item" class="visually-hidden">事項內容</label>
            {{ Form::text('item', null, ['id' => 'item', 'class' => 'form-control', 'required' => 'required', 'placeholder' => '事項說明', 'aria-label' => '事項說明']) }}
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-success" onclick="if(confirm('您確定送出嗎?')) add_item('{{ $this_month }}'); else return false">
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

    function del_item(id, this_month){
        $.ajax({
            url: './monthly_calendars/block_destroy/' + id,
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
                alert('失敗！');
            }
        })
    }

    function show_calendar(result){        
        var data = '<div class="d-flex align-items-center justify-content-center my-3">';
        data += '<button type="button" class="btn btn-link p-0 text-primary me-2" aria-label="上一個月" onclick="go_submit(\''+result['last_month']+'\')"><i class="fas fa-arrow-alt-circle-left fa-2x" aria-hidden="true"></i></button>';
        data += '<h2 class="h4 mb-0 mx-2">' + result['this_month'] + '</h2>';
        data += '<button type="button" class="btn btn-link p-0 text-primary ms-2" aria-label="下一個月" onclick="go_submit(\''+result['next_month']+'\')"><i class="fas fa-arrow-alt-circle-right fa-2x" aria-hidden="true"></i></button>';
        data += '</div>';

        data += '<div class="table-responsive"><table class="table table-bordered align-middle">';
        data += '<caption class="visually-hidden">' + result['this_month'] + ' 行事曆</caption>';
        data += '<thead><tr style="background-color: #333333; color: #ffffff;">';
        data += '<th scope="col" class="text-warning">日</th>';
        data += '<th scope="col">一</th><th scope="col">二</th><th scope="col">三</th><th scope="col">四</th><th scope="col">五</th>';
        data += '<th scope="col" class="text-info">六</th>';
        data += '</tr></thead><tbody><tr>';

        for(var k in result['this_month_date']){
            if(k == 1){
                for(i = 1; i <= result['this_month_date_w'][result['this_month_date'][k]]; i++){
                    data += '<td width="14%"></td>';
                }
            }

            var isToday = result['today'] == result['this_month_date'][k];
            var bgStyle = isToday ? 'background-color:#FFFDE7;' : 'background-color:#FFFFFF;';
            var textColor = isToday ? 'color:#006600; font-weight:bold;' : 'color:#000000; font-weight:bold;';

            data += '<td width="14%" style="' + bgStyle + ' vertical-align: top;">';
            data += '<div style="font-size: 1.0625rem; ' + textColor + '">' + result['this_month_date'][k].substring(8,10) + '</div>';

            this_date = result['this_month_date'][k].substring(8,10);
            this_month = result['this_month_date'][k].substring(0,7);

            var bg_array = ['#0056b3', '#1e7e34', '#d39e00', '#117a8b', '#5a6268', '#bd2130'];
            var qq = 0;

            for(var k1 in result['item_array']){
                if(result['item_array'][k1]['item_date'] == result['this_month_date'][k]){
                    var fullItem = result['item_array'][k1]['item'].replace(/'/g, "\\'").replace(/"/g, "&quot;");
                    var cht = cht_str(result['item_array'][k1]['item'], 20);
                    var q = qq % 6;

                    data += '<button type="button" class="btn btn-sm text-start w-100 my-1 p-1 text-white border-0" ';
                    data += 'style="background-color:' + bg_array[q] + '; font-size:0.875rem; line-height:1.2;" ';
                    data += 'title="' + fullItem + '" ';
                    data += 'onclick="alert(\'' + result['this_month_date'][k] + '\\r\\n' + fullItem + '\')">';
                    data += cht;
                    data += '</button>';

                    if(result['user_id'] == result['item_array'][k1]['user_id'] || result['admin'] == "1"){
                        data += '<button type="button" class="btn btn-link p-0 ms-1 border-0" aria-label="刪除事項" ';
                        data += 'onclick="if(confirm(\'確定刪除嗎?\')) del_item(\''+k1+'\',\''+this_month+'\'); else return false">';
                        data += '<img src="{{ asset('images/remove.png') }}" height="15" alt="刪除事項">';
                        data += '</button>';
                    }
                    qq++;
                }
            }
            data += '</td>';

            if(result['this_month_date_w'][result['this_month_date'][k]] == 6){
                data += '</tr><tr>';
            }
        }

        var nn = 6 - result['last_w'];
        for(var i = 1; i <= nn; i++){
            data += '<td></td>';
        }
        data += '</tr></tbody></table></div>';

        return data;
    }

    function cht_str(str, n){
        var r = /[^\x00-\xff]/g;
        if(str.replace(r, "mm").length <= n){ return str; }
        var m = Math.floor(n / 2);
        for(var i = m; i < str.length; i++){
            if(str.substr(0, i).replace(r, "mm").length >= n){
                return str.substr(0, i) + "...";
            }
        }
        return str;
    };
</script>

{{ Form::open(['route' => 'monthly_calendars.return_month', 'method' => 'POST', 'id' => 'calendar_month', 'onsubmit' => 'return false']) }}
<input type="hidden" name="item_month" id="item_month">
{{ Form::close() }}

<div id="calendar_content">    
    <div class="d-flex align-items-center justify-content-center my-3">
        <button type="button" class="btn btn-link p-0 text-primary me-2" aria-label="上一個月" onclick="go_submit('{{ $last_month }}')">
            <i class="fas fa-arrow-alt-circle-left fa-2x" aria-hidden="true"></i>
        </button>
        <h2 class="h4 mb-0 mx-2">{{ $this_month }}</h2>
        <button type="button" class="btn btn-link p-0 text-primary ms-2" aria-label="下一個月" onclick="go_submit('{{ $next_month }}')">
            <i class="fas fa-arrow-alt-circle-right fa-2x" aria-hidden="true"></i>
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <caption class="visually-hidden">{{ $this_month }} 行事曆</caption>
            <thead>
                <tr style="background-color: #333333; color: #ffffff;">
                    <th scope="col" class="text-warning">日</th>
                    <th scope="col">一</th>
                    <th scope="col">二</th>
                    <th scope="col">三</th>
                    <th scope="col">四</th>
                    <th scope="col">五</th>
                    <th scope="col" class="text-info">六</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    @foreach($this_month_date as $k => $v)
                        <?php
                        $this_date_w = get_date_w($v);
                        $is_today = ($v == date('Y-m-d'));
                        $bgcolor = $is_today ? "background-color:#FFFDE7;" : "background-color:#FFFFFF;";
                        $color = $is_today ? "color:#006600;" : "color:#000000;";
                        ?>
                        @if($k == 1)
                            @for($i = 1; $i <= $first_w; $i++)
                                <td width="14%"></td>
                            @endfor
                        @endif

                        <td width="14%" style="{{ $bgcolor }} vertical-align: top;">
                            <?php
                            $num = substr($v, 8, 2);
                            $bg_array = ['#0056b3', '#1e7e34', '#d39e00', '#117a8b', '#5a6268', '#bd2130'];
                            $qq = 0;
                            ?>
                            <div style="font-weight: bold; font-size: 1.0625rem; {{ $color }}">
                                {{ $num }}
                            </div>

                            @foreach($item_array as $k1 => $v1)
                                <?php $q = $qq % 6; ?>
                                @if($v1['item_date'] == $v)
                                    <button type="button" class="btn btn-sm text-start w-100 my-1 p-1 text-white border-0"
                                            style="background-color: {{ $bg_array[$q] }}; font-size: 0.875rem; line-height: 1.2;"
                                            title="{{ $v1['item'] }}"
                                            onclick="alert('{{ $v }}\r\n{{ addslashes($v1['item']) }}')">
                                        {{ str_limit($v1['item'], 20) }}
                                    </button>

                                    @auth
                                        @if($v1['user_id'] == auth()->user()->id or auth()->user()->admin == 1)
                                            <button type="button" class="btn btn-link p-0 ms-1 border-0" aria-label="刪除事項"
                                                    onclick="if(confirm('確定刪除嗎?')) del_item('{{ $k1 }}','{{ $this_month }}'); else return false">
                                                <img src="{{ asset('images/remove.png') }}" height="15" alt="刪除事項">
                                            </button>
                                        @endif
                                    @endauth
                                    <?php $qq++; ?>
                                @endif
                            @endforeach
                        </td>

                        @if($this_date_w == 6)
                            </tr><tr>
                        @endif
                    @endforeach

                    @for($i = 1; $i <= 6 - $this_date_w; $i++)
                        <td></td>
                    @endfor
                </tr>
            </tbody>
        </table>
    </div>
</div>