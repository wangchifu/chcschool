@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '社團報名-')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>社團報名</h1>
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('clubs.index') }}">學期設定</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('clubs.setup') }}">社團設定</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('clubs.report') }}">報表輸出</a>
                </li>
            </ul>
            <?php
                $admin = check_power('社團報名','A',auth()->user()->id);
            ?>
            @if($admin)
                <div class="card">
                <div class="card-header">
                    <h5>
                        @if(empty($this_class->class_name))
                        {{ $semester }} 學期 {{ $this_class->student_year }}年{{ $this_class->student_class }}班學生列表
                        @else
                        {{ $semester }} 學期 {{ $this_class->class_name }}學生列表
                        @endif
                        請選擇：  
                    </h5>          
            <table>
                <tr>                    
                    <td>
                        <form>
                            <select class="form-control" id="select_class" onchange="jump()">
                                @foreach($student_classes as $student_class)
                                <?php  $selected=($this_class->id==$student_class->id)?"selected":"";  ?>
                                    <option value="{{ $student_class->id }}" {{ $selected }}>
                                        @if(empty($student_class->class_name))
                                        {{ $student_class->student_year }}年{{ $student_class->student_class }}班
                                        - {{ $student_class->user_names }}
                                        @else
                                        {{ $student_class->class_name }}
                                        - {{ $student_class->user_names }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </form>  
                    </td>
                </tr>
            </table> 
                    </h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('clubs.stu_adm',$semester) }}" class="btn btn-secondary btn-sm"><i class="fas fa-backward"></i> 返回</a>
                    <a href="{{ route('clubs.stu_create',['semester'=>$semester,'student_class'=>$this_class->id]) }}" class="btn btn-success btn-sm">新增此班學生</a>
                    <table class="table table-hover">
                        <tr>
                            <th>
                                序
                            </th>
                            <th>
                                學號
                            </th>
                            <th>
                                班級座號(帳號)
                            </th>
                            <th>
                                密碼
                            </th>
                            <th>
                                姓名
                            </th>
                            <th>
                                生日
                            </th>
                            <th>
                                家長電話
                            </th>
                            <th>
                                動作
                            </th>
                        </tr>
                        <?php $i=1; ?>
                        @foreach($club_students as $club_student)
                            <?php
                                if(isset($black_list[$semester][$club_student->no])){
                                    $black = "bg-dark text-danger";
                                }else{
                                    $black = "";
                                }
                            ?>
                            <tr class="{{ $black }}">
                                <td>
                                    {{ $i }}
                                </td>
                                <td>
                                    {{ $club_student->no }}
                                </td>
                                <td>
                                    {{ $club_student->class_num }}
                                </td>
                                <td>
                                    {{ $club_student->pwd }}
                                </td>
                                <td>
                                    {{ $club_student->name }}
                                </td>
                                <td>
                                    {{ $club_student->birthday }}
                                </td>
                                <td>
                                    {{ $club_student->parents_telephone }}
                                </td>
                                <td>
                                    <a href="{{ route('clubs.stu_backPWD',['club_student'=>$club_student->id,'student_class_id'=>$this_class->id]) }}" class="btn btn-secondary btn-sm" onclick="return confirm('確定還原密碼為生日嗎？')">還密</a>
                                    <a href="{{ route('clubs.stu_edit',['club_student'=>$club_student->id,'student_class'=>$this_class->id]) }}" class="btn btn-primary btn-sm">編輯</a>
                                    <!--
                                    <a href="{{ route('clubs.stu_delete',['club_student'=>$club_student->id,'student_class_id'=>$this_class->id]) }}" class="btn btn-danger btn-sm" onclick="return confirm('確定刪除？')">刪除</a>
                                    -->
                                    <a href="{{ route('clubs.stu_disable',['club_student'=>$club_student->id,'student_class_id'=>$this_class->id]) }}" class="btn btn-warning btn-sm" onclick="return confirm('確定停用？')">停用</a>
                                </td>
                            </tr>
                            <?php $i++; ?>
                        @endforeach
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>
    <script>
        function jump(){
          if($('#select_class').val() !=''){
            location="/clubs/"+{{ $semester }}+"/stu_adm_more/" + $('#select_class').val();
          }
        }
    </script>
@endsection
