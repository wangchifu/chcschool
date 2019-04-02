@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '校務行事曆-新增行事曆 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1><i class="fas fa-calendar"></i> 校務行事曆-新增行事曆</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('calendars.index') }}">校務行事曆</a></li>
                    <li class="breadcrumb-item active" aria-current="page">新增行事曆</li>
                </ol>
            </nav>
            <div class="card">
                <div class="card-header">
                    行事曆資料
                </div>
                {{ Form::open(['route' => 'calendars.store', 'method' => 'POST','id'=>'this_form']) }}
                <div class="card-body">
                    <div class="form-group">
                        <label for="group_id"><strong class="text-danger">1.先選校務類別</strong></label>
                        {{ Form::select('calendar_kind',config('chcschool.calendar_kind'),null,['id' => 'calendar_kind', 'class' => 'form-control']) }}
                    </div>
                    <h3>{{ $semester }} 學期的週次</h3>
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th width="80">
                                週別
                            </th>
                            <th width="120">
                                起迄
                            </th>
                            <th>
                                2.再填內容
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($calendar_weeks as $calendar_week)
                            <tr>
                                <td nowrap>
                                    第 {{ $calendar_week->week }} 週
                                </td>
                                <td nowrap>
                                    {{ $calendar_week->start_end }}
                                </td>
                                <td>
                                    <div id="show{{ $calendar_week->week }}">
                                        <p>
                                            <label for='var1'>本週行事1：</label>
                                            <input type='text' name='w{{ $calendar_week->week }}_content[]' class='form-control'>
                                            <button type="button" onclick="add({{ $calendar_week->week }})">+增加</button>
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <input type="hidden" name="semester" value="{{ $semester }}">
                    @if(!empty($calendar_weeks))
                        <button type="submit" class="btn btn-primary" onclick="return confirm('確定儲存嗎？')">
                            <i class="fas fa-save"></i> 儲存設定
                        </button>
                    @else
                        <a href="#" class="btn btn-danger">尚未設定週次</a>
                    @endif
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>

<script>
    var item = [1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1];
    function add(t) {
        var content;
        item[t]++;

        content = "<p>" +
            "<label for='var"+item[t]+"'>本週行事"+item[t]+"：</label>" +
            "<input type='text' name='w"+t+"_content[]' class='form-control'> " +
            "<i class='fas fa-trash text-danger' onclick='remove(this)'></i>" +
            "</p>";
        $("#show"+t).append(content);
    }

    function remove(obj,t) {
        item[t]--;
        $(obj).parent().remove();
    }

</script>
@endsection
