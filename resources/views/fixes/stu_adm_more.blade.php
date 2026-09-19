@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '報修系統 |')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>報修系統 | 學生列表</h1>    
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">首頁</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('fixes.index') }}">報修列表</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('fixes.stu_adm') }}">學生管理</a></li>
                    <li class="breadcrumb-item active" aria-current="page">學生列表</li>
                </ol>
            </nav>
            
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
                </div>
                <div class="card-body">
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
                                動作
                            </th>
                        </tr>
                        <?php $i=1; ?>
                        @foreach($club_students as $club_student)
                            @if($club_student->disable == null)
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
                                    <a href="{{ route('fixes.stu_backPWD',['club_student'=>$club_student->id,'student_class_id'=>$this_class->id]) }}" class="btn btn-secondary btn-sm" onclick="return confirm('確定還原密碼為生日嗎？')">還密</a>
                                    <a href="{{ route('fixes.stu_edit',['club_student'=>$club_student->id,'student_class'=>$this_class->id]) }}" class="btn btn-primary btn-sm">編輯</a>                                    
                                </td>
                            </tr>
                            <?php $i++; ?>
                            @endif
                        @endforeach
                    </table>
                </div>
            </div>            
        </div>
    </div>
    <script>
        function jump(){
          if($('#select_class').val() !=''){
            location="/fixes/"+{{ $semester }}+"/stu_adm_more/" + $('#select_class').val();
          }
        }
    </script>
@endsection
