@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '校務行事曆 | ')

@section('content')
<style>
    /* 無障礙 HM1020401C 修正：下拉選單、按鈕與操作連結 Focus 高對比視覺提示 */
    .form-control:focus-visible,
    select:focus-visible,
    .btn:focus-visible,
    a:focus-visible {
        outline: 3px solid #0056b3 !important;
        outline-offset: 2px !important;
        z-index: 5;
    }
</style>

<div class="row justify-content-center">
    <main class="col-md-11" aria-label="校務行事曆內容">
        <!-- 無障礙 HM1010301C 修正：主標題 -->
        <h1 class="h2 mb-4">校務行事曆</h1>

        <div class="card">
            <!-- 頂部工具列：改用 Flexbox 替代原本排版用的 Table -->
            <div class="card-header bg-light">
                <div class="d-flex flex-wrap align-items-center justify-content-between">
                    <form name="myform" class="form-inline my-1">
                        @csrf
                        <!-- 無障礙 HM1150100C 修正：對應專屬 Label -->
                        <label for="semester_select" class="mr-2 font-weight-bold">學期選單：</label>
                        <select name="semester" id="semester_select" class="form-control form-control-sm" onchange="jump();" title="請選擇年度學期">
                            <option value="">--請選擇--</option>
                            @foreach($semesters as $v)
                                <option value="{{ $v }}" {{ ($v == $semester) ? 'selected' : '' }}>{{ $v }}</option>
                            @endforeach
                        </select>
                    </form>

                    <div class="my-1">
                        @if($has_week)
                            @can('create',\App\Post::class)
                                <a href="{{ route('calendars.create',$semester) }}" class="btn btn-success btn-sm mr-2">
                                    <i class="fas fa-plus" aria-hidden="true"></i> 新增 {{ $semester }} 學期行事
                                </a>
                            @endcan
                        @endif

                        @auth
                            @if(auth()->user()->admin)
                                <a href="{{ route('calendar_weeks.index') }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-cogs" aria-hidden="true"></i> 學期管理
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>

            <!-- 行事曆主要資料表格 -->
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <!-- 無障礙 HM1010301C 修正：卡片標題階層調整為 h2 -->
                    <h2 class="h5 m-0 font-weight-bold">{{ $semester }} 學期校務行事曆</h2>
                    
                    <!-- 無障礙 HM1200101C 修正：另開視窗列印提示 -->
                    <a href="{{ route('calendars.print',$semester) }}" class="btn btn-outline-dark btn-sm" target="_blank" rel="noopener noreferrer" aria-label="列印 {{ $semester }} 學期校務行事曆 (另開新視窗)">
                        <i class="fas fa-print" aria-hidden="true"></i> 列印
                        <span class="sr-only">(另開新視窗)</span>
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-bordered" aria-label="{{ $semester }} 學期校務行事曆表格">
                        <caption class="sr-only">{{ $semester }} 學期校務行事曆，包含週別、起迄日期與各類別活動規劃</caption>
                        <thead class="thead-light">
                        <tr>
                            <th scope="col" style="width: 100px;">週別</th>
                            <th scope="col" style="width: 120px;">
                                起迄
                                @auth
                                    @if(auth()->user()->admin)
                                        <a href="{{ route('calendar_weeks.edit',$semester) }}" class="badge badge-info ml-1" aria-label="修改 {{ $semester }} 學期起迄週別">修改</a>
                                    @endif
                                @endauth
                            </th>
                            @foreach(config('chcschool.calendar_kind') as $v)
                                <th scope="col" style="white-space: nowrap;">
                                    {{ $v }}
                                </th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($calendar_weeks as $calendar_week)
                            <tr>
                                <th scope="row" class="align-middle text-nowrap">
                                    第 {{ $calendar_week->week }} 週
                                </th>
                                <td class="align-middle text-nowrap">
                                    <small class="text-muted">{{ $calendar_week->start_end }}</small>
                                </td>
                                
                                @foreach(config('chcschool.calendar_kind') as $k => $v)
                                    <!-- 無障礙 HM1010301C 修正：將原本誤用的 <th> 改為語意正確的 <td> -->
                                    <td class="align-middle" data-th="{{ $v }}">
                                        @if(!empty($calendar_data[$calendar_week->id][$k]))
                                            <?php $i = 1; ?>
                                            @foreach($calendar_data[$calendar_week->id][$k] as $item_key => $item_val)
                                                <div class="mb-1">
                                                    <span class="text-primary small">{{ $i }}. {{ $item_val['content'] }}</span>
                                                    @auth
                                                        @if($item_val['user_id'] == auth()->user()->id or auth()->user()->admin == 1)
                                                            <!-- 無障礙 HM1200101C 修正：彈出視窗提示與完整語意標籤 -->
                                                            <a href="javascript:open_url('{{ route('calendars.edit',$item_key) }}','編輯視窗')" class="text-info ml-1" aria-label="編輯第 {{ $calendar_week->week }} 週內容：{{ $item_val['content'] }} (彈出新視窗)">
                                                                <i class="fas fa-edit" aria-hidden="true"></i>
                                                            </a>
                                                            <a href="{{ route('calendars.delete',$item_key) }}" class="text-danger ml-1" id="del{{ $item_key }}" onclick="return confirm('確定要刪除？')" aria-label="刪除第 {{ $calendar_week->week }} 週內容：{{ $item_val['content'] }}">
                                                                <i class="fas fa-minus-square" aria-hidden="true"></i>
                                                            </a>
                                                        @endif
                                                    @endauth
                                                </div>
                                                <?php $i++; ?>
                                            @endforeach
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
    function jump(){
        var semesterSelect = document.getElementById('semester_select');
        if(semesterSelect && semesterSelect.value !== ''){
            location.href = "/calendars/index/" + semesterSelect.value;
        }
    }

    function open_url(url, name) {
        window.open(url, name, 'statusbar=no,scrollbars=yes,status=yes,resizable=yes,width=850,height=300');
    }
</script>
@endsection