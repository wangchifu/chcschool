@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '校務月曆 | ')

@section('content')
    <?php
    use Carbon\Carbon;

    $d = explode('-',$this_month);
    $dt = Carbon::create($d[0], $d[1]);

    $next_month =  substr($dt->addMonth()->toDateTimeString(),0,7);
    $last_month =  substr($dt->subMonth()->toDateTimeString(),0,7);

    $this_month_date = get_month_date($this_month);
    $first_w = get_date_w($this_month_date[1]);
    ?>
    <script src="{{ asset('gijgo/js/gijgo.min.js') }}" type="text/javascript"></script>
    <link href="{{ asset('gijgo/css/gijgo.min.css') }}" rel="stylesheet" type="text/css">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>
                校務月曆
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">首頁</a></li>
                    <li class="breadcrumb-item active" aria-current="page">校務月曆</li>
                </ol>
            </nav>
            {{ Form::open(['route' => 'monthly_calendars.store', 'method' => 'POST','id'=>'this_form']) }}
            <table>
                <tr>
                    <td>
                        <input id="item_date" name="item_date" required maxlength="10" value="{{ date('Y-m-d') }}">
                    </td>
                    <td>
                        {{ Form::text('item',null,['id'=>'item','class' => 'form-control','required'=>'required', 'placeholder' => '事項']) }}
                    </td>
                    <td>
                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('確定儲存嗎？')">
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
            <h2>
                <a href="{{ route('monthly_calendars.index',substr($dt->subMonth()->toDateTimeString(),0,7)) }}" style="text-decoration:none;"><i class="fas fa-arrow-alt-circle-left"></i></a> {{ $this_month }} <a href="{{ route('monthly_calendars.index',$next_month) }}" style="text-decoration:none;"><i class="fas fa-arrow-alt-circle-right"></i></a>
            </h2>
            <table class="table table-bordered">
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
                              $a = substr($num,0,1);
                              $b = substr($num,1,1);
                            ?>
                            <div style="font-weight: bold;font-size: 20px;">
                                <img src="{{ asset('images/calendars/'.$a.'.png') }}" width="20"><img src="{{ asset('images/calendars/'.$b.'.png') }}" width="20">
                                @if($v==date('Y-m-d'))
                                    <img src="{{ asset('images/check.png') }}" height="20px">
                                @endif
                            </div>
                            @foreach($item_array as $k1=>$v1)
                                @if($v1['item_date'] == $v)
                                    <div class="bg-info" style="width: 100%;border-radius: 3px;margin: 2px;color: #FFFFFF;padding: 2px;" data-toggle="tooltip" data-placement="top" title="{{ $v1['item'] }}">
                                        {{ str_limit($v1['item'],16) }}
                                        @auth
                                            @if($v1['user_id'] == auth()->user()->id or auth()->user()->admin ==1)
                                                <a href="{{ route('monthly_calendars.destroy',$k1) }}" onclick="return confirm('確定刪除嗎？')">
                                                    <img src="{{ asset('images/remove.png') }}" height="15px">
                                                </a>
                                            @endif
                                        @endauth
                                    </div>
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
                </tbody>
            </table>
            <hr>
            <h1>從 Google 日曆匯入</h1>
            {{ Form::open(['route' => 'monthly_calendars.file', 'files' => true, 'method' => 'POST','id'=>'this_form']) }}
            <table>
                <tr>
                    <td>
                        {{ Form::file('filename', ['class' => 'form-control']) }}
                    </td>
                    <td>
                        <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('確定儲存嗎？')">
                            <i class="fas fa-save"></i> 從 ics 檔匯入
                        </button>
                    </td>
                </tr>
            </table>
            {{ Form::close() }}
            <hr>
            <img src="{{ asset('images/google_calendar1.png') }}"><br>
            步驟：<br>
            <ul>
                <li>
                    1.至個人 google 日曆，日曆設定->匯出日曆->自動下載一個 zip 檔
                </li>
                <li>
                    2.解開 zip 檔，出現一個 ics 檔，再將它上傳即可。
                </li>
                <li>
                    3.[<a href="https://calendar.google.com/calendar/ical/zh.taiwan%23holiday%40group.v.calendar.google.com/public/basic.ics" target="_blank">台灣節日</a>] ics 下載可供利用
                </li>
            </ul>
        </div>
    </div>
@endsection
