<?php
use Carbon\Carbon;
$this_month = (empty($month)) ? date('Y-m') : $month;

// 五大類別對應顏色定義 (0或null:校務行政, 1:教務, 2:學務, 3:總務, 4:輔導室)
$kind_colors = [
    0 => '#01579b', // 校務行政 (深藍)
    1 => '#0b6623', // 教務 (深綠)
    2 => '#bf360c', // 學務 (磚紅)
    3 => '#4e342e', // 總務 (深棕色/大地咖啡)
    4 => '#6a1b9a', // 輔導室 (深紫)
];

$calendarKinds = config('chcschool.calendar_kind', [
    0 => '校務行政',
    1 => '教務',
    2 => '學務',
    3 => '總務',
    4 => '輔導室'
]);

// 跨日查詢相容：讀取開始或結束於本月的行程
$items = \App\MonthlyCalendar::where('item_date', 'like', $this_month . '%')
    ->orWhere('item_edate', 'like', $this_month . '%')
    ->get();

$item_array = [];
foreach ($items as $item) {
    $item_array[$item->id]['user_id']   = $item->user_id;
    $item_array[$item->id]['item_date'] = $item->item_date;
    $item_array[$item->id]['item_edate'] = $item->item_edate ?? $item->item_date;
    $item_array[$item->id]['item_time']  = $item->item_time ?? '';
    $item_array[$item->id]['item']       = $item->item;
    $item_array[$item->id]['item_kind']  = $item->item_kind ?? 0; // 讀取處室類別
}

$d = explode('-', $this_month);
$dt = Carbon::create($d[0], $d[1], 1);
$next_month = $dt->copy()->addMonthsNoOverflow(1)->format('Y-m');
$last_month = $dt->copy()->subMonthsNoOverflow(1)->format('Y-m');

$this_month_date = get_month_date($this_month);
$first_w = get_date_w($this_month_date[1]);
?>

@can('create', \App\Post::class)
    <!-- Flatpickr CSS & JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <!-- 繁體中文語系檔 -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/zh-tw.js"></script>
    
    {{ Form::open(['route' => 'monthly_calendars.block_store', 'method' => 'POST', 'id' => 'create_calendar_form', 'onsubmit' => 'return false']) }}
    <div class="row g-2 align-items-center mb-3 mx-0">
        <!-- 選擇類別 -->
        <div class="col-auto">
            <label for="item_kind" class="sr-only">選擇類別</label>
            <select name="item_kind" id="item_kind" class="form-control form-control-sm text-white font-weight-bold" onchange="updateSelectColor(this)" aria-label="選擇類別">
                @foreach($calendarKinds as $kValue => $kName)
                    @php $optBg = $kind_colors[$kValue] ?? '#01579b'; @endphp
                    <option value="{{ $kValue }}" style="background-color: {{ $optBg }}; color: #ffffff;" {{ old('item_kind') == $kValue ? 'selected' : '' }}>
                        {{ $kName }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- 開始日期 (必填) -->
        <div class="col-auto">
            <label for="item_date" class="sr-only">選擇日期</label>
            <input id="item_date" name="item_date" class="form-control form-control-sm" required maxlength="10" value="{{ date('Y-m-d') }}" placeholder="開始日期" aria-label="選擇日期">
        </div>
        <!-- 結束日期 (選填) -->
        <div class="col-auto">
            <label for="item_edate" class="sr-only">結束日期</label>
            <input id="item_edate" name="item_edate" class="form-control form-control-sm" maxlength="10" placeholder="結束日期 (選填)" aria-label="結束日期">
        </div>

        <!-- 事項時間 (選填) -->
        <div class="col-auto">
            <label for="item_time" class="sr-only">事項時間</label>
            <input id="item_time" name="item_time" class="form-control form-control-sm" maxlength="10" placeholder="時間 (選填)" aria-label="事項時間">
        </div>

        <!-- 事項說明 (必填) -->
        <div class="col-auto flex-grow-1">
            <label for="item" class="sr-only">事項說明</label>
            {{ Form::text('item', null, ['id' => 'item', 'class' => 'form-control form-control-sm', 'required' => 'required', 'placeholder' => '新增事項說明', 'aria-label' => '事項說明']) }}
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-sm btn-success" onclick="if(confirm('您確定送出嗎?')) add_item('{{ $this_month }}'); else return false">
                <i class="fas fa-plus" aria-hidden="true"></i> 新增事項
            </button>
        </div>
    </div>    
    <script>
        // 動態切換 Select 本身的背景顏色
        function updateSelectColor(select) {
            if (!select) return;
            const selectedOption = select.options[select.selectedIndex];
            if (selectedOption) {
                select.style.backgroundColor = selectedOption.style.backgroundColor;
                select.style.color = '#ffffff';
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            const itemKindSelect = document.getElementById("item_kind");
            if (itemKindSelect) {
                updateSelectColor(itemKindSelect);
            }
        });

        flatpickr("#item_date, #item_edate", {
            locale: "zh_tw",
            dateFormat: "Y-m-d",
            allowInput: true,
            disableMobile: true
        });
        flatpickr("#item_time", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true,
            minuteIncrement: 5
        });
    </script>
    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
    {{ Form::close() }}
@endcan

<script>
    // JS 類別顏色對照表
    var globalKindColors = {
        0: '#01579b', // 校務行政
        1: '#0b6623', // 教務
        2: '#bf360c', // 學務
        3: '#6a1b9a', // 總務
        4: '#880e4f'  // 輔導室
    };

    var globalKindNames = {
        0: '校務行政',
        1: '教務',
        2: '學務',
        3: '總務',
        4: '輔導室'
    };

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
                alert('失敗！(是不是忘了啟用它？)');
            }
        })
    }

    function show_calendar(result){        
        var data = '<div class="d-flex flex-wrap align-items-center justify-content-between my-3 px-1 pb-2" style="border-bottom: 1px solid #e0e0e0;">';
        data += '<div class="d-flex align-items-center my-1">';
        data += '<button type="button" class="btn btn-light rounded-circle p-0 me-2 d-inline-flex align-items-center justify-content-center text-dark" style="width: 36px; height: 36px; border: 1px solid #dadce0;" aria-label="上一個月" onclick="go_submit(\''+result['last_month']+'\')"><i class="fas fa-chevron-left" style="font-size: 1rem;" aria-hidden="true"></i></button>';
        data += '<button type="button" class="btn btn-light rounded-circle p-0 me-3 d-inline-flex align-items-center justify-content-center text-dark" style="width: 36px; height: 36px; border: 1px solid #dadce0;" aria-label="下一個月" onclick="go_submit(\''+result['next_month']+'\')"><i class="fas fa-chevron-right" style="font-size: 1rem;" aria-hidden="true"></i></button>';
        data += '<h2 class="h4 mb-0 px-3 font-weight-bold" style="color: #202124;">' + result['this_month'] + '</h2>';
        data += '</div>';

        // 動態生成圖例區塊
        data += '<div class="d-flex align-items-center flex-wrap my-1">';        
        for (var kVal in globalKindNames) {
            var kColor = globalKindColors[kVal] || '#01579b';
            data += '<span class="badge text-white px-2 py-1 me-1 mb-1" style="background-color: ' + kColor + '; font-size: 12px; font-weight: normal;">' + globalKindNames[kVal] + '</span>';
        }
        data += '</div></div>';

        data += '<div class="table-responsive mb-4"><table class="table table-bordered align-middle mb-0" style="table-layout: fixed; width: 100%; border-color: #dadce0;">';
        data += '<caption class="sr-only">' + result['this_month'] + ' 行事曆</caption>';
        data += '<thead style="background-color: #f8f9fa;"><tr class="text-center" style="border-bottom: 1px solid #dadce0;">';
        data += '<th scope="col" style="color: #b31412; font-weight: 700; padding: 8px 2px; width: 14.28%; font-size: 13px; border-color: #dadce0;">日</th>';
        data += '<th scope="col" style="color: #202124; font-weight: 700; padding: 8px 2px; width: 14.28%; font-size: 13px; border-color: #dadce0;">一</th>';
        data += '<th scope="col" style="color: #202124; font-weight: 700; padding: 8px 2px; width: 14.28%; font-size: 13px; border-color: #dadce0;">二</th>';
        data += '<th scope="col" style="color: #202124; font-weight: 700; padding: 8px 2px; width: 14.28%; font-size: 13px; border-color: #dadce0;">三</th>';
        data += '<th scope="col" style="color: #202124; font-weight: 700; padding: 8px 2px; width: 14.28%; font-size: 13px; border-color: #dadce0;">四</th>';
        data += '<th scope="col" style="color: #202124; font-weight: 700; padding: 8px 2px; width: 14.28%; font-size: 13px; border-color: #dadce0;">五</th>';
        data += '<th scope="col" style="color: #1a56db; font-weight: 700; padding: 8px 2px; width: 14.28%; font-size: 13px; border-color: #dadce0;">六</th>';
        data += '</tr></thead><tbody><tr>';

        for(var k in result['this_month_date']){
            var curDate = result['this_month_date'][k];
            var curOfWeek = result['this_month_date_w'][curDate];

            if(k == 1){
                for(i = 1; i <= curOfWeek; i++){
                    data += '<td style="border-color: #dadce0; background-color: #ffffff;"></td>';
                }
            }

            var isToday = result['today'] == curDate;
            var dayNum = curDate.substring(8, 10);

            data += '<td style="padding: 4px; height: 85px; vertical-align: top; border-color: #dadce0; background-color: #ffffff;">';
            
            data += '<div class="d-flex align-items-center justify-content-start mb-1" style="height: 24px;">';
            if (isToday) {
                data += '<span class="d-inline-flex align-items-center justify-content-center rounded-circle text-white" style="width: 24px; height: 24px; font-size: 12px; font-weight: bold; background-color: #1557b0;" aria-label="今天">' + dayNum + '</span>';
            } else {
                data += '<span style="font-size: 12px; font-weight: 700; color: #202124; padding-left: 2px;">' + dayNum + '</span>';
            }
            data += '</div>';

            this_month = curDate.substring(0,7);

            for(var k1 in result['item_array']){
                var itemObj   = result['item_array'][k1];
                var startDate = itemObj['item_date'] || '';
                var endDate   = itemObj['item_edate'] ? itemObj['item_edate'] : startDate;
                var time      = itemObj['item_time'] ? itemObj['item_time'] : '';
                var isMultiDay = (startDate !== endDate);

                if(curDate >= startDate && curDate <= endDate){
                    var rawItem  = itemObj['item'];
                    var fullItem = rawItem.replace(/'/g, "\\'").replace(/"/g, "&quot;");
                    
                    // 根據 item_kind 取得對應處室顏色
                    var itemKind = (itemObj['item_kind'] !== undefined && itemObj['item_kind'] !== null && itemObj['item_kind'] !== '') ? parseInt(itemObj['item_kind']) : 0;
                    var itemColor = globalKindColors[itemKind] || globalKindColors[0];

                    var isStartDay = (curDate === startDate || curOfWeek == 0);
                    var isEndDay   = (curDate === endDate || curOfWeek == 6);

                    var borderRadius = '4px';
                    var marginStyle  = '';

                    if (isMultiDay) {
                        if (isStartDay && !isEndDay) {
                            borderRadius = '4px 0 0 4px';
                            marginStyle  = 'margin-right: -5px;';
                        } else if (!isStartDay && isEndDay) {
                            borderRadius = '0 4px 4px 0';
                            marginStyle  = 'margin-left: -5px;';
                        } else if (!isStartDay && !isEndDay) {
                            borderRadius = '0';
                            marginStyle  = 'margin-left: -5px; margin-right: -5px;';
                        }
                    }

                    var showDelete = (curDate === startDate) && (result['user_id'] == itemObj['user_id'] || result['admin'] == "1");

                    // 構建點擊後的 Alert 訊息
                    var alertMsg = startDate + (isMultiDay ? ' ~ ' + endDate : '') + (time ? '\\n時間：' + time : '') + '\\n事項：' + fullItem;

                    data += '<div class="d-flex align-items-center my-1 w-100" style="min-width: 0; ' + marginStyle + '">';
                    
                    // 使用標準 <button> 符合 WCAG AA 鍵盤可存取性
                    data += '<button type="button" class="btn p-0 px-2 text-start text-white border-0 text-truncate flex-grow-1" ';
                    data += 'style="background-color:' + itemColor + '; font-size: 12px; line-height: 22px; height: 22px; border-radius:' + borderRadius + '; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; min-width: 0;" ';
                    data += 'title="' + startDate + (isMultiDay ? ' ~ ' . endDate : '') + ' ' + (time ? time + ' ' : '') + fullItem + '" ';
                    data += 'aria-label="' + curDate + (time ? ' 時間 ' + time : '') + ' 事項：' + fullItem + '" ';
                    data += 'onclick="alert(\'' + alertMsg + '\')">';

                    if (time && (curDate === startDate || !isMultiDay)) {
                        data += '<span class="font-weight-bold me-1" style="opacity: 0.95;">' + time + '</span> ';
                    }
                    data += rawItem;
                    data += '</button>';

                    // 外置獨立紅色圖示刪除按鈕
                    if(showDelete){
                        data += '<button type="button" class="btn btn-link p-0 ms-1 border-0 flex-shrink-0 d-inline-flex align-items-center justify-content-center" ';
                        data += 'style="width: 20px; height: 20px;" ';
                        data += 'onclick="if(confirm(\'確定刪除嗎？\')) del_item(\''+k1+'\', \''+this_month+'\'); else return false;" ';
                        data += 'aria-label="刪除事項：' + fullItem + '">';
                        data += '<img src="{{ asset('images/remove.png') }}" style="height: 12px; width: 12px;" alt="刪除">';
                        data += '</button>';
                    }
                    data += '</div>';
                }
            }
            data += '</td>';

            if(curOfWeek == 6){
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
    <!-- 月份切換區域與顏色圖例 -->
    <div class="d-flex flex-wrap align-items-center justify-content-between my-3 px-1 pb-2" style="border-bottom: 1px solid #e0e0e0;">
        {{-- 月份切換按鈕 --}}
        <div class="d-flex align-items-center my-1">
            <button type="button" class="btn btn-light rounded-circle p-0 me-2 d-inline-flex align-items-center justify-content-center text-dark" 
                    style="width: 36px; height: 36px; border: 1px solid #dadce0;" 
                    aria-label="上一個月" onclick="go_submit('{{ $last_month }}')">
                <i class="fas fa-chevron-left" style="font-size: 1rem;" aria-hidden="true"></i>
            </button>
            <button type="button" class="btn btn-light rounded-circle p-0 me-3 d-inline-flex align-items-center justify-content-center text-dark" 
                    style="width: 36px; height: 36px; border: 1px solid #dadce0;" 
                    aria-label="下一個月" onclick="go_submit('{{ $next_month }}')">
                <i class="fas fa-chevron-right" style="font-size: 1rem;" aria-hidden="true"></i>
            </button>
            <h2 class="h4 mb-0 px-3 font-weight-bold" style="color: #202124;">{{ $this_month }}</h2>
        </div>

        {{-- 類別顏色圖例 --}}
        <div class="d-flex align-items-center flex-wrap my-1">            
            @foreach($calendarKinds as $kValue => $kName)
                <span class="badge text-white px-2 py-1 me-1 mb-1" 
                      style="background-color: {{ $kind_colors[$kValue] ?? '#01579b' }}; font-size: 12px; font-weight: normal;">
                    {{ $kName }}
                </span>
            @endforeach
        </div>
    </div>

    <!-- 月曆主要表格 -->
    <div class="table-responsive mb-4">
        <table class="table table-bordered align-middle mb-0" style="table-layout: fixed; width: 100%; border-color: #dadce0;">
            <caption class="sr-only">{{ $this_month }} 校務月曆</caption>
            <thead style="background-color: #f8f9fa;">
                <tr class="text-center" style="border-bottom: 1px solid #dadce0;">
                    <th scope="col" style="color: #b31412; font-weight: 700; padding: 8px 2px; width: 14.28%; font-size: 13px; border-color: #dadce0;">日</th>
                    <th scope="col" style="color: #202124; font-weight: 700; padding: 8px 2px; width: 14.28%; font-size: 13px; border-color: #dadce0;">一</th>
                    <th scope="col" style="color: #202124; font-weight: 700; padding: 8px 2px; width: 14.28%; font-size: 13px; border-color: #dadce0;">二</th>
                    <th scope="col" style="color: #202124; font-weight: 700; padding: 8px 2px; width: 14.28%; font-size: 13px; border-color: #dadce0;">三</th>
                    <th scope="col" style="color: #202124; font-weight: 700; padding: 8px 2px; width: 14.28%; font-size: 13px; border-color: #dadce0;">四</th>
                    <th scope="col" style="color: #202124; font-weight: 700; padding: 8px 2px; width: 14.28%; font-size: 13px; border-color: #dadce0;">五</th>
                    <th scope="col" style="color: #1a56db; font-weight: 700; padding: 8px 2px; width: 14.28%; font-size: 13px; border-color: #dadce0;">六</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    @foreach($this_month_date as $k => $v)
                        <?php
                            $this_date_w = get_date_w($v);
                            $is_today = ($v == date('Y-m-d'));
                            $num = substr($v, 8, 2);
                        ?>

                        @if($k == 1)
                            @for($i = 1; $i <= $first_w; $i++)
                                <td style="border-color: #dadce0; background-color: #ffffff;"></td>
                            @endfor
                        @endif

                        <td style="padding: 4px; height: 85px; vertical-align: top; border-color: #dadce0; background-color: #ffffff;">
                            <div class="d-flex align-items-center justify-content-start mb-1" style="height: 24px;">
                                @if($is_today)
                                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle text-white" 
                                          style="width: 24px; height: 24px; font-size: 12px; font-weight: bold; background-color: #1557b0;" 
                                          aria-label="今天 ({{ $num }}日)">{{ $num }}</span>
                                @else
                                    <span style="font-size: 12px; font-weight: 700; color: #202124; padding-left: 2px;">{{ $num }}</span>
                                @endif
                            </div>

                            @foreach($item_array as $k1 => $v1)
                                @php
                                    $startDate = $v1['item_date'] ?? '';
                                    $endDate   = !empty($v1['item_edate']) ? $v1['item_edate'] : $startDate;
                                    $time      = !empty($v1['item_time']) ? $v1['item_time'] : '';
                                    $isMultiDay = ($startDate !== $endDate);

                                    // 取得處室類別顏色，無值預設為 0 (校務行政)
                                    $itemKind = isset($v1['item_kind']) ? (int)$v1['item_kind'] : 0;
                                    $bgColor  = $kind_colors[$itemKind] ?? $kind_colors[0];
                                @endphp

                                @if($v >= $startDate && $v <= $endDate)
                                    @php
                                        $isStartDay = ($v === $startDate || $this_date_w == 0);
                                        $isEndDay   = ($v === $endDate || $this_date_w == 6);

                                        $borderRadius = '4px';
                                        $marginStyle  = '';

                                        if ($isMultiDay) {
                                            if ($isStartDay && !$isEndDay) {
                                                $borderRadius = '4px 0 0 4px';
                                                $marginStyle  = 'margin-right: -5px;';
                                            } elseif (!$isStartDay && $isEndDay) {
                                                $borderRadius = '0 4px 4px 0';
                                                $marginStyle  = 'margin-left: -5px;';
                                            } elseif (!$isStartDay && !$isEndDay) {
                                                $borderRadius = '0';
                                                $marginStyle  = 'margin-left: -5px; margin-right: -5px;';
                                            }
                                        }

                                        // 組合 PHP 初始渲染時的 Alert 字串
                                        $alertMsg = $startDate . ($isMultiDay ? ' ~ ' . $endDate : '') . ($time ? '\n時間：' . $time : '') . '\n事項：' . addslashes($v1['item']);
                                    @endphp

                                    <div class="d-flex align-items-center my-1 w-100" style="min-width: 0; {{ $marginStyle }}">
                                        {{-- 使用標準 <button> 符合 WCAG AA 鍵盤可存取性 --}}
                                        <button type="button" 
                                                class="btn p-0 px-2 text-start text-white border-0 text-truncate flex-grow-1"
                                                style="background-color: {{ $bgColor }}; font-size: 12px; line-height: 22px; height: 22px; border-radius: {{ $borderRadius }}; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; min-width: 0;"
                                                title="{{ $startDate }}{{ $isMultiDay ? ' ~ ' . $endDate : '' }} {{ $time ? $time . ' ' : '' }}{{ $v1['item'] }}"
                                                aria-label="{{ $v }}{{ $time ? ' 時間 ' . $time : '' }} 事項：{{ $v1['item'] }}"
                                                onclick="alert('{{ $alertMsg }}')">

                                            @if($time && ($v === $startDate || !$isMultiDay))
                                                <span class="font-weight-bold me-1" style="opacity: 0.95;">{{ $time }}</span>
                                            @endif

                                            {{ $v1['item'] }}
                                        </button>

                                        {{-- 獨立紅色圓圈刪除鈕 --}}
                                        @auth
                                            @if($v === $startDate && ($v1['user_id'] == auth()->user()->id or auth()->user()->admin == 1))
                                                <button type="button" 
                                                        class="btn btn-link p-0 ms-1 border-0 flex-shrink-0 d-inline-flex align-items-center justify-content-center"
                                                        style="width: 20px; height: 20px;"
                                                        onclick="if(confirm('確定刪除嗎？')) del_item('{{ $k1 }}', '{{ $this_month }}'); else return false;"
                                                        aria-label="刪除事項：{{ $v1['item'] }}">
                                                    <img src="{{ asset('images/remove.png') }}" style="height: 12px; width: 12px;" alt="刪除">
                                                </button>
                                            @endif
                                        @endauth
                                    </div>
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