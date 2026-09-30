@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '校務月曆 | ')

@section('content')
    <?php
    use Carbon\Carbon;

    $d = explode('-', $this_month);
    $dt = Carbon::create($d[0], $d[1], 1);

    // 使用 copy() 避免 Carbon 物件連續運算時產生的月份偏差
    $next_month = $dt->copy()->addMonthsNoOverflow(1)->format('Y-m');
    $last_month = $dt->copy()->subMonthsNoOverflow(1)->format('Y-m');

    $this_month_date = get_month_date($this_month);
    $first_w = get_date_w($this_month_date[1]);
    ?>
    <script src="{{ asset('gijgo/js/gijgo.min.js') }}" type="text/javascript"></script>
    <link href="{{ asset('gijgo/css/gijgo.min.css') }}" rel="stylesheet" type="text/css">

    <div class="row justify-content-center">
        <div class="col-md-11">
            <!-- 頁面標題與麵包屑導覽 -->
            <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                <div>
                    <h1 class="h3 font-weight-bold text-dark mb-1">校務月曆</h1>
                    <nav aria-label="麵包屑導覽">
                        <ol class="breadcrumb bg-transparent p-0 m-0 style-sm">
                            <li class="breadcrumb-item"><a href="{{ route('index') }}" class="text-secondary">首頁</a></li>
                            <li class="breadcrumb-item active text-dark" aria-current="page">校務月曆</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <!-- 新增事項表單 -->
            <div class="card mb-4 border-0 shadow-sm bg-light">
                <div class="card-body p-3">
                    {{ Form::open(['route' => 'monthly_calendars.store', 'method' => 'POST', 'id' => 'this_form', 'class' => 'row g-2 align-items-center']) }}
                        <div class="col-auto">
                            <label for="item_date" class="sr-only">選擇日期</label>
                            <input id="item_date" name="item_date" class="form-control form-control-sm" required maxlength="10" value="{{ date('Y-m-d') }}" aria-label="選擇日期">
                        </div>
                        <div class="col-auto flex-grow-1">
                            <label for="item" class="sr-only">事項說明</label>
                            {{ Form::text('item', null, ['id' => 'item', 'class' => 'form-control form-control-sm', 'required' => 'required', 'placeholder' => '新增事項說明', 'aria-label' => '事項說明']) }}
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('確定儲存嗎？')">
                                <i class="fas fa-plus" aria-hidden="true"></i> 新增事項
                            </button>
                        </div>
                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                    {{ Form::close() }}
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

            <!-- 月份切換區域 (Google 經典 36x36px 大按鈕) -->
            <div class="d-flex align-items-center justify-content-between my-3 px-1 pb-2" style="border-bottom: 1px solid #e0e0e0;">
                <div class="d-flex align-items-center">
                    <a href="{{ route('monthly_calendars.index', $last_month) }}" 
                       class="btn btn-light rounded-circle p-0 me-2 d-inline-flex align-items-center justify-content-center text-dark" 
                       style="width: 36px; height: 36px; border: 1px solid #dadce0;" 
                       aria-label="上一個月 ({{ $last_month }})">
                        <i class="fas fa-chevron-left" style="font-size: 1rem;" aria-hidden="true"></i>
                    </a>
                    <a href="{{ route('monthly_calendars.index', $next_month) }}" 
                       class="btn btn-light rounded-circle p-0 me-3 d-inline-flex align-items-center justify-content-center text-dark" 
                       style="width: 36px; height: 36px; border: 1px solid #dadce0;" 
                       aria-label="下一個月 ({{ $next_month }})">
                        <i class="fas fa-chevron-right" style="font-size: 1rem;" aria-hidden="true"></i>
                    </a>
                    <h2 class="h4 mb-0 font-weight-bold" style="color: #202124;">{{ $this_month }}</h2>
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
                                    // 無障礙 AA 驗證高對比色庫（白字對比度 >= 4.5:1）
                                    $bg_array = ['#01579b', '#0b6623', '#bf360c', '#6a1b9a', '#1a237e', '#b71c1c', '#004d40', '#880e4f'];
                                    $qq = 0;
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
                                        <?php $q = $qq % count($bg_array); ?>
                                        @if($v1['item_date'] == $v)
                                            <div class="d-flex align-items-center my-1 w-100" style="min-width: 0;">
                                                <!-- AA 級高對比膠囊事項說明 -->
                                                <div class="px-2 text-white text-truncate flex-grow-1"
                                                     style="background-color: {{ $bg_array[$q] }}; font-size: 12px; line-height: 22px; height: 22px; border-radius: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; min-width: 0;"
                                                     title="{{ $v1['item'] }}"
                                                     aria-label="{{ $v }} 事項：{{ $v1['item'] }}">
                                                    {{ $v1['item'] }}
                                                </div>

                                                @auth
                                                    @if($v1['user_id'] == auth()->user()->id or auth()->user()->admin == 1)
                                                        <a href="{{ route('monthly_calendars.destroy', $k1) }}"
                                                           class="ms-1 flex-shrink-0 text-danger d-inline-flex align-items-center justify-content-center"
                                                           style="width: 20px; height: 20px;"
                                                           onclick="return confirm('確定刪除嗎？')"
                                                           aria-label="刪除事項：{{ $v1['item'] }}">
                                                            <img src="{{ asset('images/remove.png') }}" style="height: 12px; width: 12px;" alt="刪除">
                                                        </a>
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

            <!-- Google 日曆匯入區塊 -->
            <div class="card border mb-4 shadow-sm" style="border-color: #dadce0 !important;">
                <div class="card-header bg-light border-bottom" style="border-color: #dadce0 !important;">
                    <h3 class="h5 mb-0 font-weight-bold text-dark">
                        <i class="fab fa-google text-primary me-2" aria-hidden="true"></i>從 Google 日曆匯入
                    </h3>
                </div>
                <div class="card-body">
                    {{ Form::open(['route' => 'monthly_calendars.file', 'files' => true, 'method' => 'POST', 'id' => 'import_form', 'class' => 'mb-3']) }}
                        <div class="row g-2 align-items-center">
                            <div class="col-md-6 col-sm-8">
                                <label for="filename" class="sr-only">選擇 ics 檔案</label>
                                {{ Form::file('filename', ['id' => 'filename', 'class' => 'form-control form-control-sm', 'aria-label' => '選擇 ics 檔案']) }}
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('確定儲存嗎？')">
                                    <i class="fas fa-file-import me-1" aria-hidden="true"></i> 從 ics 檔匯入
                                </button>
                            </div>
                        </div>
                    {{ Form::close() }}

                    <hr style="border-color: #e0e0e0;">

                    <div class="row align-items-center">
                        <div class="col-md-5 mb-3 mb-md-0 text-center">
                            <img src="{{ asset('images/google_calendar1.png') }}" class="img-fluid rounded border shadow-sm" alt="Google 日曆匯出步驟示意圖">
                        </div>
                        <div class="col-md-7">
                            <h4 class="h6 font-weight-bold text-dark mb-2">匯入步驟說明：</h4>
                            <ol class="ps-3 text-secondary mb-0" style="line-height: 1.8;">
                                <li>前往個人 Google 日曆，點選<strong>「設定」&rarr;「匯出日曆」</strong>，系統將自動下載 ZIP 壓縮檔。</li>
                                <li>將 ZIP 檔解壓縮後會取得 <code>.ics</code> 檔案。</li>
                                <li>在本頁點選選擇檔案並上傳該 <code>.ics</code> 檔即可將行程匯入校務月曆。</li>
                                <li>提示：您也可直接下載 <a href="https://calendar.google.com/calendar/ical/zh.taiwan%23holiday%40group.v.calendar.google.com/public/basic.ics" target="_blank" rel="noopener noreferrer" class="font-weight-bold text-primary">台灣節日.ics</a> 進行匯入使用。</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection