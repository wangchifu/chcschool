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
            <h1>運動會報名-學校管理員</h1>
            @include('sport_meetings.nav')
            <hr>
            <ul class="nav nav-pills">
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('sport_meeting.admin') }}">1.學生資料</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link active" href="{{ route('sport_meeting.user') }}">2.教師帳號</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('sport_meeting.action') }}">3.報名任務</a>
                </li>
              </ul>            
              <div class="card">
                <div class="card-body">
                  @if($admin)
                    <h4>教師帳號</h4>
                    <table class="table table-hover" style="margin-top: -50px;">
                      <thead class="table-warning">
                        <tr>
                          <th>
                            職稱
                          </th>
                          <th>
                            姓名
                          </th>
                          <th>
                            動作
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($users as $user)
                        <tr>
                          <td>
                            {{ $user->title }}
                          </td>
                          <td>
                            {{ $user->name }}
                          </td>
                          <td>
                            @if(check_power('運動會報名', 'A', $user->id))
                              本模組管理權
                            @endif
                          </td>
                        </tr>
                        @endforeach
                      </tbody>
                  @endif
                </div>
              </div>        
        </div>        
    </div>
@endsection
