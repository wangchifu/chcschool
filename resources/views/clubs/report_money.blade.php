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
                        <h4>社團收費報表</h4>
                        <form name=myform>
                            <div class="form-group">
                                {{ Form::select('semester', $club_semesters_array,$semester, ['id'=>'semester','class' => 'form-control','placeholder'=>'--請選擇學期--','onchange'=>'jump()']) }}
                            </div>
                        </form>
                        <a href="{{ route('clubs.report') }}" class="btn btn-secondary btn-sm"><i class="fas fa-backward"></i> 返回</a>
                        <a href="{{ route('clubs.report_money_download',$semester) }}" class="btn btn-primary btn-sm"><i class="fas fa-download"></i> 下載 Excel 檔</a>

                        <table class="table table-striped">
                            <tr>
                                <th>
                                    學號
                                </th>
                                <th>
                                    座號
                                </th>
                                <th>
                                    姓名
                                </th>
                                <th>
                                    身分證字號
                                </th>
                                <th>
                                    生日
                                </th>
                                <th>
                                    年級
                                </th>
                                <th>
                                    班別
                                </th>
                                <th>
                                    減免
                                </th>
                                @foreach($clubs as $club)
                                    <th>
                                        {{ $club->name }}
                                    </th>
                                @endforeach
                            </tr>
                            <?php $check_id=0; ?>
                            @foreach($club_registers as $club_register)
                                @if($check_id != $club_register->user->id)
                                    <tr>
                                        <td>
                                            {{ $register_data[$club_register->club->name][$club_register->user->id]['stud_no'] }}
                                        </td>
                                        <td>
                                            {{ $register_data[$club_register->club->name][$club_register->user->id]['stud_num'] }}
                                        </td>
                                        <td>
                                            {{ $register_data[$club_register->club->name][$club_register->user->id]['stud_name'] }}
                                        </td>
                                        <td>

                                        </td>
                                        <td>

                                        </td>
                                        <td>
                                            {{ $register_data[$club_register->club->name][$club_register->user->id]['stud_year'] }}
                                        </td>
                                        <td>
                                            {{ $register_data[$club_register->club->name][$club_register->user->id]['stud_class'] }}
                                        </td>
                                        <td>

                                        </td>
                                        @foreach($clubs as $club)
                                            <td>
                                                @if(isset($register_data[$club->name][$club_register->user->id]['money']))
                                                    {{ $register_data[$club->name][$club_register->user->id]['money'] }}
                                                @else
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endif
                                <?php $check_id = $club_register->user->id; ?>
                            @endforeach
                        </table>
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
                location="/clubs/report_situation/" + document.myform.semester.options[document.myform.semester.selectedIndex].value;
            }
        }

    </script>
@endsection
