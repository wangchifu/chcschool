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
    <div class="row g-2 align-items-center mb-3 mx-0">
        <div class="col-auto">
            <label for="item_date" class="sr-only">選擇日期</label>
            <input id="item_date" name="item_date" class="form-control form-control-sm" required maxlength="10" value="{{ date('Y-m-d') }}" aria-label="選擇日期">
        </div>
        <div class="col-auto">
            <label for="item" class="sr-only">事項內容</label>
            {{ Form::text('item', null, ['id' => 'item', 'class' => 'form-control form-control-sm', 'required' => 'required', 'placeholder' => '事項說明', 'aria-label' => '事項說明']) }}
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-sm btn-success" onclick="if(confirm('您確定送出嗎?')) add_item('{{ $this_month }}'); else return false">
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
        var data = '<div class="d-flex align-items-center justify-content-between my-2 px-1 pb-2" style="border-bottom: 1px solid #e0e0e0;">';
        data += '<div class="d-flex align-items-center">';
        // 放大切換按鈕（尺寸 36x36px 圓形，圖示 1rem）
        data += '<button type="button" class="btn btn-light rounded-circle p-0 me-2 d-inline-flex align-items-center justify-content-center text-dark" style="width: 36px; height: 36px; border: 1px solid #dadce0;" aria-label="上一個月" onclick="go_submit(\''+result['last_month']+'\')"><i class="fas fa-chevron-left" style="font-size: 1rem;" aria-hidden="true"></i></button>';
        data += '<button type="button" class="btn btn-light rounded-circle p-0 me-3 d-inline-flex align-items-center justify-content-center text-dark" style="width: 36px; height: 36px; border: 1px solid #dadce0;" aria-label="下一個月" onclick="go_submit(\''+result['next_month']+'\')"><i class="fas fa-chevron-right" style="font-size: 1rem;" aria-hidden="true"></i></button>';
        data += '<h2 class="h5 mb-0 font-weight-bold" style="color: #202124; font-size: 1.2rem;">' + result['this_month'] + '</h2>';
        data += '</div></div>';

        data += '<div class="table-responsive"><table class="table table-bordered align-middle mb-0" style="table-layout: fixed; width: 100%; border-color: #dadce0;">';
        data += '<caption class="sr-only">' + result['this_month'] + ' 行事曆</caption>';
        data += '<thead style="background-color: #f8f9fa;"><tr class="text-center" style="border-bottom: 1px solid #dadce0;">';
        data += '<th scope="col" style="color: #b31412; font-weight: 700; padding: 6px 2px; width: 14.28%; font-size: 12px; border-color: #dadce0;">日</th>';
        data += '<th scope="col" style="color: #202124; font-weight: 700; padding: 6px 2px; width: 14.28%; font-size: 12px; border-color: #dadce0;">一</th>';
        data += '<th scope="col" style="color: #202124; font-weight: 700; padding: 6px 2px; width: 14.28%; font-size: 12px; border-color: #dadce0;">二</th>';
        data += '<th scope="col" style="color: #202124; font-weight: 700; padding: 6px 2px; width: 14.28%; font-size: 12px; border-color: #dadce0;">三</th>';
        data += '<th scope="col" style="color: #202124; font-weight: 700; padding: 6px 2px; width: 14.28%; font-size: 12px; border-color: #dadce0;">四</th>';
        data += '<th scope="col" style="color: #202124; font-weight: 700; padding: 6px 2px; width: 14.28%; font-size: 12px; border-color: #dadce0;">五</th>';
        data += '<th scope="col" style="color: #1a56db; font-weight: 700; padding: 6px 2px; width: 14.28%; font-size: 12px; border-color: #dadce0;">六</th>';
        data += '</tr></thead><tbody><tr>';

        for(var k in result['this_month_date']){
            if(k == 1){
                for(i = 1; i <= result['this_month_date_w'][result['this_month_date'][k]]; i++){
                    data += '<td style="border-color: #dadce0; background-color: #ffffff;"></td>';
                }
            }

            var isToday = result['today'] == result['this_month_date'][k];
            var dayNum = result['this_month_date'][k].substring(8,10);

            data += '<td style="padding: 2px; height: 75px; vertical-align: top; border-color: #dadce0; background-color: #ffffff;">';
            
            data += '<div class="d-flex align-items-center justify-content-start mb-1" style="height: 22px;">';
            if (isToday) {
                data += '<span class="d-inline-flex align-items-center justify-content-center rounded-circle text-white" style="width: 22px; height: 22px; font-size: 12px; font-weight: bold; background-color: #1557b0;" aria-label="今天">' + dayNum + '</span>';
            } else {
                data += '<span style="font-size: 12px; font-weight: 700; color: #202124; padding-left: 2px;">' + dayNum + '</span>';
            }
            data += '</div>';

            this_date = result['this_month_date'][k].substring(8,10);
            this_month = result['this_month_date'][k].substring(0,7);

            var bg_array = ['#01579b', '#0b6623', '#bf360c', '#6a1b9a', '#1a237e', '#b71c1c', '#004d40', '#880e4f'];
            var qq = 0;

            for(var k1 in result['item_array']){
                if(result['item_array'][k1]['item_date'] == result['this_month_date'][k]){
                    var rawItem = result['item_array'][k1]['item'];
                    var fullItem = rawItem.replace(/'/g, "\\'").replace(/"/g, "&quot;");
                    var q = qq % bg_array.length;

                    data += '<div class="d-flex align-items-center my-1 w-100" style="min-width: 0;">';
                    data += '<button type="button" class="btn p-0 px-1 text-start text-white border-0 text-truncate flex-grow-1" ';
                    data += 'style="background-color:' + bg_array[q] + '; font-size: 12px; line-height: 20px; height: 20px; border-radius: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; min-width: 0;" ';
                    data += 'title="' + fullItem + '" ';
                    data += 'aria-label="' + result['this_month_date'][k] + ' 事項：' + fullItem + '" ';
                    data += 'onclick="alert(\'' + result['this_month_date'][k] + '\\r\\n' + fullItem + '\')">';
                    data += rawItem;
                    data += '</button>';

                    if(result['user_id'] == result['item_array'][k1]['user_id'] || result['admin'] == "1"){
                        data += '<button type="button" class="btn btn-link p-0 ms-1 border-0 flex-shrink-0" aria-label="刪除事項：' + fullItem + '" ';
                        data += 'onclick="if(confirm(\'確定刪除嗎?\')) del_item(\''+k1+'\',\''+this_month+'\'); else return false">';
                        data += '<img src="{{ asset('images/remove.png') }}" style="height: 12px; width: 12px; display: block;" alt="刪除">';
                        data += '</button>';
                    }
                    data += '</div>';
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
            data += '<td style="border-color: #dadce0; background-color: #ffffff;"></td>';
        }
        data += '</tr></tbody></table></div>';

        return data;
    }
</script>

{{ Form::open(['route' => 'monthly_calendars.return_month', 'method' => 'POST', 'id' => 'calendar_month', 'onsubmit' => 'return false']) }}
<input type="hidden" name="item_month" id="item_month">
{{ Form::close() }}

<div id="calendar_content">    
    <div class="d-flex align-items-center justify-content-between my-2 px-1 pb-2" style="border-bottom: 1px solid #e0e0e0;">
        <div class="d-flex align-items-center">
            <!-- 放大切換按鈕（尺寸 36x36px 圓形，圖示 1rem） -->
            <button type="button" class="btn btn-light rounded-circle p-0 me-2 d-inline-flex align-items-center justify-content-center text-dark" style="width: 36px; height: 36px; border: 1px solid #dadce0;" aria-label="上一個月" onclick="go_submit('{{ $last_month }}')">
                <i class="fas fa-chevron-left" style="font-size: 1rem;" aria-hidden="true"></i>
            </button>
            <button type="button" class="btn btn-light rounded-circle p-0 me-3 d-inline-flex align-items-center justify-content-center text-dark" style="width: 36px; height: 36px; border: 1px solid #dadce0;" aria-label="下一個月" onclick="go_submit('{{ $next_month }}')">
                <i class="fas fa-chevron-right" style="font-size: 1rem;" aria-hidden="true"></i>
            </button>
            <h2 class="h5 mb-0 font-weight-bold" style="color: #202124; font-size: 1.2rem;">{{ $this_month }}</h2>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered align-middle mb-0" style="table-layout: fixed; width: 100%; border-color: #dadce0;">
            <caption class="sr-only">{{ $this_month }} 行事曆</caption>
            <thead style="background-color: #f8f9fa;">
                <tr class="text-center" style="border-bottom: 1px solid #dadce0;">
                    <th scope="col" style="color: #b31412; font-weight: 700; padding: 6px 2px; width: 14.28%; font-size: 12px; border-color: #dadce0;">日</th>
                    <th scope="col" style="color: #202124; font-weight: 700; padding: 6px 2px; width: 14.28%; font-size: 12px; border-color: #dadce0;">一</th>
                    <th scope="col" style="color: #202124; font-weight: 700; padding: 6px 2px; width: 14.28%; font-size: 12px; border-color: #dadce0;">二</th>
                    <th scope="col" style="color: #202124; font-weight: 700; padding: 6px 2px; width: 14.28%; font-size: 12px; border-color: #dadce0;">三</th>
                    <th scope="col" style="color: #202124; font-weight: 700; padding: 6px 2px; width: 14.28%; font-size: 12px; border-color: #dadce0;">四</th>
                    <th scope="col" style="color: #202124; font-weight: 700; padding: 6px 2px; width: 14.28%; font-size: 12px; border-color: #dadce0;">五</th>
                    <th scope="col" style="color: #1a56db; font-weight: 700; padding: 6px 2px; width: 14.28%; font-size: 12px; border-color: #dadce0;">六</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    @foreach($this_month_date as $k => $v)
                        <?php
                        $this_date_w = get_date_w($v);
                        $is_today = ($v == date('Y-m-d'));
                        $num = substr($v, 8, 2);
                        $bg_array = ['#01579b', '#0b6623', '#bf360c', '#6a1b9a', '#1a237e', '#b71c1c', '#004d40', '#880e4f'];
                        $qq = 0;
                        ?>

                        @if($k == 1)
                            @for($i = 1; $i <= $first_w; $i++)
                                <td style="border-color: #dadce0; background-color: #ffffff;"></td>
                            @endfor
                        @endif

                        <td style="padding: 2px; height: 75px; vertical-align: top; border-color: #dadce0; background-color: #ffffff;">
                            <div class="d-flex align-items-center justify-content-start mb-1" style="height: 22px;">
                                @if($is_today)
                                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle text-white" style="width: 22px; height: 22px; font-size: 12px; font-weight: bold; background-color: #1557b0;" aria-label="今天">{{ $num }}</span>
                                @else
                                    <span style="font-size: 12px; font-weight: 700; color: #202124; padding-left: 2px;">{{ $num }}</span>
                                @endif
                            </div>

                            @foreach($item_array as $k1 => $v1)
                                <?php $q = $qq % count($bg_array); ?>
                                @if($v1['item_date'] == $v)
                                    <div class="d-flex align-items-center my-1 w-100" style="min-width: 0;">
                                        <button type="button" class="btn p-0 px-1 text-start text-white border-0 text-truncate flex-grow-1"
                                                style="background-color: {{ $bg_array[$q] }}; font-size: 12px; line-height: 20px; height: 20px; border-radius: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; min-width: 0;"
                                                title="{{ $v1['item'] }}"
                                                aria-label="{{ $v }} 事項：{{ $v1['item'] }}"
                                                onclick="alert('{{ $v }}\r\n{{ addslashes($v1['item']) }}')">
                                            {{ $v1['item'] }}
                                        </button>

                                        @auth
                                            @if($v1['user_id'] == auth()->user()->id or auth()->user()->admin == 1)
                                                <button type="button" class="btn btn-link p-0 ms-1 border-0 flex-shrink-0" aria-label="刪除事項：{{ $v1['item'] }}"
                                                        onclick="if(confirm('確定刪除嗎?')) del_item('{{ $k1 }}','{{ $this_month }}'); else return false">
                                                    <img src="{{ asset('images/remove.png') }}" style="height: 12px; width: 12px; display: block;" alt="刪除">
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

                    @for($i = 1; $i <= 6 - $this_date_w; $i++)
                        <td style="border-color: #dadce0; background-color: #ffffff;"></td>
                    @endfor
                </tr>
            </tbody>
        </table>
    </div>
</div>