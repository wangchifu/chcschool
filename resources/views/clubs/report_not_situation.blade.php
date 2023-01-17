@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '社團報名-')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>社團報名</h1>
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('clubs.index') }}">學期設定</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('clubs.setup') }}">社團設定</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('clubs.report') }}">報表輸出</a>
                </li>
            </ul>
            <div class="card">
                <div class="card-body">
                    <?php
                        $admin = check_power('社團報名','A',auth()->user()->id);
                    ?>
                    @if($admin and $semester != null)
                        <h4>社團取消報名狀況</h4>
                        <form name=myform>
                            <div class="form-group">
                                {{ Form::select('semester', $club_semesters_array,$semester, ['id'=>'semester','class' => 'form-control','placeholder'=>'--請選擇學期--','onchange'=>'jump()']) }}
                            </div>
                        </form>
                        <a href="{{ route('clubs.report') }}" class="btn btn-secondary btn-sm"><i class="fas fa-backward"></i> 返回</a>
                        <div class="card">
                            <div class="card-header">
                            </div>
                            <div class="card-body">
                                <table class="table table-hover">
                                    <tr>
                                        <th>
                                            時間
                                        </th>
                                        <th>
                                            事件
                                        </th>
                                        <th>
                                            IP
                                        </th>
                                    </tr>
                                    @foreach($not_registers as $not_register)
                                        <tr>
                                            <td>
                                                {{ $not_register->created_at }}
                                            </td>
                                            <td>
                                                {{ $not_register->event }}
                                            </td>
                                            <td>
                                                {{ $not_register->ip }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    @elseif(!$admin)
                        <span class="text-danger">你不是管理者</span>
                    @else
                        <span class="text-danger">請先新增學期</span>
                    @endif
                </div>
            </div>

        </div>
    </div>
    <script language='JavaScript'>

        function jump(){
            if(document.myform.semester.options[document.myform.semester.selectedIndex].value!=''){
                location="/clubs/report_not_situation/" + document.myform.semester.options[document.myform.semester.selectedIndex].value;
            }
        }

    </script>
@endsection
