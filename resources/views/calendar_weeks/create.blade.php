@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '校務行事曆-週次設定 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>校務行事曆-週次設定</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('calendars.index') }}">校務行事曆</a></li>
                    <li class="breadcrumb-item active" aria-current="page">週次設定</li>
                </ol>
            </nav>
            <div class="card">
                <div class="card-header">
                    <h4>{{ $semester }}學期 週次設定，請新增或移除多餘週次(保持空著)</h4>
                </div>
                <div class="card-body">
                    {{ Form::open(['route' => 'calendar_weeks.store','id'=>'save' ,'method' => 'POST']) }}
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th></th>
                            <th>
                                週次
                            </th>
                            <th>
                                起迄
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>

                            </td>
                            <td>
                                {{ Form::text('week[d]',null,['class' => 'form-control']) }}
                            </td>
                            <td>
                                {{ Form::text('start_end[d]',null,['class' => 'form-control']) }}
                            </td>
                        </tr>
                        @foreach($start_end as $k=>$v)
                            <tr>
                                <td>
                                    <span class="btn btn-danger btn-sm" onclick="clean_this({{ $k }})" href="#">清除此列</span>
                                </td>
                                <td>
                                    {{ Form::text('week['.$k.']',$k,['class' => 'form-control','id'=>'week1'.$k]) }}
                                </td>
                                <td>
                                    {{ Form::text('start_end['.$k.']',substr($v[0],5,5).'~'.substr($v[6],5,5),['class' => 'form-control','id'=>'week2'.$k]) }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <script>
                        function clean_this(k){
                            $('#week1'+k).val('');
                            $('#week2'+k).val('');
                        }
                    </script>
                    <input type="hidden" name="semester" value="{{ $semester }}">
                    <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('確定嗎？')">
                        <i class="fas fa-save"></i> 儲存設定
                    </button>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
