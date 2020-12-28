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
                        <h4>社團報名狀況</h4>
                        <form name=myform>
                            <div class="form-group">
                                {{ Form::select('semester', $club_semesters_array,$semester, ['id'=>'semester','class' => 'form-control','placeholder'=>'--請選擇學期--','onchange'=>'jump()']) }}
                            </div>
                        </form>
                            <a href="{{ route('clubs.report') }}" class="btn btn-secondary btn-sm"><i class="fas fa-backward"></i> 返回</a>
                            <h3 class="text-primary">
                                [ 1.學生特色社團 ]
                            </h3>
                            <a href="{{ route('clubs.report_situation_download',['semester'=>$semester,'class_id'=>1]) }}" class="btn btn-primary btn-sm"><i class="fas fa-download"></i> 下載 Excel 檔</a>
                        @foreach($clubs1 as $club)
                            <div class="card">
                                <div class="card-header">
                                    <h4>
                                        {{ $club->no }} {{ $club->name }}
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <?php
                                        $club_registers = \App\ClubRegister::where('semester',$semester)
                                                            ->where('club_id',$club->id)
                                                            ->get();
                                        $taking = $club->taking;
                                        $prepare = $club->prepare;
                                        $i=1;
                                        $j=1;

                                    ?>
                                    <table class="table table-hover">
                                        <tr>
                                            <th>
                                                社團
                                            </th>
                                            <th>
                                                班級座號
                                            </th>
                                            <th>
                                                姓名
                                            </th>
                                            <th>
                                                家長電話
                                            </th>
                                            <th>
                                                錄取狀況
                                            </th>
                                            <th>
                                                報名時間
                                            </th>
                                            <th>
                                                開班狀態
                                            </th>
                                            <th>
                                                動作
                                            </th>
                                        </tr>
                                        @if(count($club_registers) >0)

                                        @else

                                        @endif
                                        @foreach($club_registers as $club_register)
                                            @if($i <= $taking)
                                                <tr>
                                            @endif
                                            @if($i > $taking and $j <= $prepare)
                                                <tr bgcolor="gray">
                                            @endif
                                                <td>
                                                    {{ $club->name }}
                                                </td>
                                                <td>
                                                    {{ $club_register->user->class_num }}
                                                </td>
                                                <td>
                                                    {{ $club_register->user->name }}
                                                </td>
                                                <td>
                                                    {{ $club_register->user->parents_telephone }}
                                                </td>
                                                <td>
                                                    @if($i <= $taking)
                                                        正取{{ $i }}
                                                    @endif
                                                    @if($i > $taking and $j <= $prepare)
                                                        候補{{ $j }}
                                                        <?php $j++; ?>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $club_register->created_at }}
                                                </td>
                                                <td>
                                                    @if(count($club_registers) < $club->people)
                                                        <span class="text-danger">不開班</span>
                                                    @else
                                                        <span class="text-success">開班成功</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('clubs.report_register_delete',$club_register->id) }}" onclick="return confirm('確定刪除報名？')"><i class="fas fa-times-circle text-danger"></i></a>
                                                </td>

                                            </tr>
                                            <?php $i++; ?>
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                            <hr>
                        @endforeach
                            <h3 class="text-primary">
                                [ 2.學生課後活動 ]
                            </h3>
                            <a href="{{ route('clubs.report_situation_download',['semester'=>$semester,'class_id'=>2]) }}" class="btn btn-primary btn-sm"><i class="fas fa-download"></i> 下載 Excel 檔</a>
                            @foreach($clubs2 as $club)
                                <div class="card">
                                    <div class="card-header">
                                        <h4>
                                            {{ $club->no }} {{ $club->name }}
                                        </h4>
                                    </div>
                                    <div class="card-body">
                                        <?php
                                        $club_registers = \App\ClubRegister::where('semester',$semester)
                                            ->where('club_id',$club->id)
                                            ->get();
                                        $taking = $club->taking;
                                        $prepare = $club->prepare;
                                        $i=1;
                                        $j=1;

                                        ?>
                                        <table class="table table-hover">
                                            <tr>
                                                <th>
                                                    社團
                                                </th>
                                                <th>
                                                    班級座號
                                                </th>
                                                <th>
                                                    姓名
                                                </th>
                                                <th>
                                                    家長電話
                                                </th>
                                                <th>
                                                    錄取狀況
                                                </th>
                                                <th>
                                                    報名時間
                                                </th>
                                                <th>
                                                    開班狀態
                                                </th>
                                                <th>
                                                    動作
                                                </th>
                                            </tr>
                                            @if(count($club_registers) >0)

                                            @else

                                            @endif
                                            @foreach($club_registers as $club_register)
                                                @if($i <= $taking)
                                                    <tr>
                                                @endif
                                                @if($i > $taking and $j <= $prepare)
                                                    <tr bgcolor="gray">
                                                        @endif
                                                        <td>
                                                            {{ $club->name }}
                                                        </td>
                                                        <td>
                                                            {{ $club_register->user->class_num }}
                                                        </td>
                                                        <td>
                                                            {{ $club_register->user->name }}
                                                        </td>
                                                        <td>
                                                            {{ $club_register->user->parents_telephone }}
                                                        </td>
                                                        <td>
                                                            @if($i <= $taking)
                                                                正取{{ $i }}
                                                            @endif
                                                            @if($i > $taking and $j <= $prepare)
                                                                候補{{ $j }}
                                                                <?php $j++; ?>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            {{ $club_register->created_at }}
                                                        </td>
                                                        <td>
                                                            @if(count($club_registers) < $club->people)
                                                                <span class="text-danger">不開班</span>
                                                            @else
                                                                <span class="text-success">開班成功</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('clubs.report_register_delete',$club_register->id) }}" onclick="return confirm('確定刪除報名？')"><i class="fas fa-times-circle text-danger"></i></a>
                                                        </td>

                                                    </tr>
                                                    <?php $i++; ?>
                                                    @endforeach
                                        </table>
                                    </div>
                                </div>
                                <hr>
                            @endforeach
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
