@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '運動會報名')

@section('content')
    <?php
    $active['admin'] ="active";
    $active['show'] ="";    
    $active['list'] ="";
    $active['score'] ="";
    $active['teacher'] ="";
    ?>
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>運動會報名-學校管理</h1>
            @include('sport_meetings.nav')
            <hr>
            <ul class="nav nav-pills">
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('sport_meeting.action') }}">1.報名任務</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link active" href="{{ route('sport_meeting.admin') }}">2.學生資料</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('sport_meeting.user') }}">3.教師帳號</a>
                </li>                
              </ul>            
              <div class="card">
                <div class="card-body">
                  @if($admin)
                    <h4>學生資料</h4>
                     {{ Form::open(['route' => ['sport_meeting.stu_import'], 'method' => 'POST', 'files' => true]) }}
                     @csrf
                     學年<input type="semester" name="semester" value="{{ get_date_semester(date('Y-m-d')) }}" style="width:80px" required maxlength="4">
                    <input type="file" name="file" required>
                    <input type="submit" class="btn btn-success btn-sm" value="匯入學生" onclick="return confirm('要等一下子，確定嗎？')">
                    {{ Form::close() }}
                    @include('layouts.errors')
                    <a href="{{ asset('images/cloudschool_club.png') }}" target="_blank">請先至 cloudschool 下載列表</a>                    
                  @endif
                  <h4>已匯入學生班級資料</h4>
                  <table class="table">
                    <thead class="table-warning">
                    <tr>
                        <th>
                            學期
                        </th>
                        <th>
                            班級數
                        </th>
                        <th>
                            學生數
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($class_num as $k=>$v)                      
                        <tr>
                            <td>
                              {{ $k }}
                            </td>
                            <td>
                                @if(isset($class_num[$k]))
                                  {{ $class_num[$k] }} <a href="{{ route('sport_meeting.stu_adm_more',['semester'=>$k,'student_class_id'=>null]) }}" class="btn btn-info btn-sm">詳細資料</a>
                                @endif
                            </td>
                            <td>
                              @if(isset($club_student_num[$k]))
                                {{ $club_student_num[$k] }}   
                              @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
              </div>
        </div>        
    </div>
@endsection
