@extends('layouts.master')

@section('title', '社團一覽-')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>
                {{ $semester }}
                @if($class_id==1) 學期「學生特色社團」一覽 @endif
                @if($class_id==2) 學期「學生課後社團」一覽 @endif                
            </h1>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <span class="text-danger">*使用手機等小螢幕觀看，請注意最右邊仍有資訊。</span>
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th scope="col" nowrap>編號</th>
                                <th scope="col" nowrap>名稱</th>
                                <th scope="col" nowrap>聯絡人</th>                            
                                <th scope="col" nowrap>連絡<br>電話</th>
                                <th scope="col">收費標準</th>
                                <th scope="col" nowrap>師　　資</th>
                                <th scope="col" nowrap>開課日期</th>
                                <th scope="col" nowrap>上課時間</th>
                                <th scope="col" nowrap>集合地點</th>
                                <th scope="col" nowrap>開課<br>(最少)</th>
                                <th scope="col" nowrap>正取<br>(最多)</th>
                                <th scope="col" nowrap>候補</th>
                                <th scope="col">年級限制</th>
                                <th scope="col" nowrap>備　　　　註</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($clubs as $club)
                            <tr>
                                <th scope="row">{{ $club->no }}</th>                            
                                <td>{{ $club->name }}</td>
                                <td>{{ $club->contact_person }}</td>
                                <td style="word-break: break-all;">{{ $club->telephone_num }}</td>
                                <td nowrap>{{ $club->money }}</td>
                                <td>{{ $club->teacher_info }}</td>
                                <td>{{ $club->start_date }}</td>
                                <td>{{ $club->start_time }}</td>
                                <td>{{ $club->place }}</td>
                                <td>{{ $club->people }}</td>
                                <td>{{ $club->taking }}</td>
                                <td>{{ $club->prepare }}</td>
                                <td nowrap>{{ $club->year_limit }}</td>
                                <td>{{ $club->ps }}</td>
                            </tr>
                            @endforeach                          
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
