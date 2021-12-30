@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '批示簽核  | 教師差假')

@section('content')
    <?php

    $active['index'] ="";
    $active['deputy'] ="";
    $active['sir'] ="active";
    $active['travel'] ="";
    $active['list'] ="";
    $active['total'] ="";
    ?>
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>教師差假：簽核記錄排代</h1>
            @include('teacher_absents.nav')
            <br>
            @if($not_admin)
                <span class="text-danger">你沒有管理權</span>
            @else
                {{ Form::select('select_semester',$semesters,$semester,['id'=>'select_semester']) }}
                <table class="table table-striped">
                    <thead class="thead-light">
                    <tr>
                        <th>
                            #
                        </th>
                        <th>
                            姓名
                        </th>
                        <th>
                            假別
                        </th>
                        <th>
                            事由
                        </th>
                        <th>
                            開始時間<br>
                            結束時間
                        </th>
                        <th>
                            日數時數
                        </th>
                        <th>
                            課務
                        </th>
                        <th>
                            職務代理人
                        </th>
                        <th>
                            單位主管
                        </th>
                        <th>
                            教學組長
                        </th>
                        <th>
                            校長
                        </th>
                        <th>
                            人事主任
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($teacher_absents as $teacher_absent)
                        <tr>
                            <td>
                                <small>
                                {{ $teacher_absent->id }}
                                <a href="javascript:open_url2('{{ route('teacher_absents.admin_edit',$teacher_absent->id) }}','新視窗')"><i class="fas fa-edit"></i></a>
                                </small>
                                <br>
                                @if($teacher_absent->status==1)
                                    <small>
                                        送核
                                    </small>
                                @endif
                                @if($teacher_absent->status==2)
                                    <small class="text-success">
                                        核准
                                    </small>
                                @endif
                            </td>
                            <td>
                                {{ $user_name[$teacher_absent->user_id] }}<br>
                                <small>{{ substr($teacher_absent->created_at,0,10) }}</small>
                            </td>
                            <td>
                                <a href="{{ url('teacher_absents/list/'.$semester.'/'.$teacher_absent->user_id.'/'.$teacher_absent->abs_kind.'/0') }}" target="_blank">
                                    <small>{{ $abs_kinds[$teacher_absent->abs_kind] }}</small>
                                </a>
                                @if($teacher_absent->abs_kind == 52)
                                    <br><small class="text-primary">{{ $teacher_absent->place }}</small>
                                @endif
                            </td>
                            <td>
                                <small>
                                    {{ $teacher_absent->reason }}<br>
                                    <span class="text-primary">{{ $teacher_absent->note }}</span>
                                </small>
                                @if($teacher_absent->note_file)
                                    <?php $file = $school_code.'&teacher_absent&'.$teacher_absent->id.'&'.$teacher_absent->note_file; ?>
                                    <a href="{{ route('openFile',$file) }}" target="_blank">
                                        <img src="{{ asset('images/file.png') }}">
                                    </a>
                                @endif
                            </td>
                            <td>
                                <small>
                                    {{ $teacher_absent->start_date }}<br>
                                    {{ $teacher_absent->end_date }}
                                </small>
                            </td>
                            <td>
                                @if($teacher_absent->day)
                                    {{ $teacher_absent->day }}日
                                @endif
                                @if($teacher_absent->hour)
                                    {{ $teacher_absent->hour }}時
                                @endif
                            </td>
                            <td>
                                <small>
                                    {{ $class_dises[$teacher_absent->class_dis] }}
                                </small>
                                @if($teacher_absent->class_file)
                                    <?php $file = $school_code.'&teacher_absent&'.$teacher_absent->id.'&'.$teacher_absent->class_file; ?>
                                    <a href="{{ route('openFile',$file) }}" target="_blank">
                                        <img src="{{ asset('images/file.png') }}">
                                    </a>
                                @endif
                            </td>
                            <td>
                                {{ $user_name[$teacher_absent->deputy_user_id] }}
                                <br>
                                <small>{{ substr($teacher_absent->deputy_date,0,10) }}</small>
                            </td>
                            <td>
                                @if($check_power['d'] and empty($teacher_absent->check1_date))
                                    <button onclick="if(confirm('您確定送出嗎?')) location.href='/teacher_absents/check/check1/{{ $teacher_absent->id }}';else return false">簽核</button> <button onclick="open_url('{{ route('teacher_absents.back',$teacher_absent->id) }}','新視窗')">退</button>
                                @endif
                                @if(!empty($teacher_absent->check1_date))
                                    {{ $user_name[$teacher_absent->check1_user_id] }}<br>
                                    <small>{{ substr($teacher_absent->check1_date,0,10) }}</small>
                                @endif
                            </td>
                            <td>
                                @if($check_power['e'] and empty($teacher_absent->check4_date))
                                    <button onclick="if(confirm('您確定送出嗎?')) location.href='/teacher_absents/check/check4/{{ $teacher_absent->id }}';else return false">排代</button>
                                @endif
                                @if(!empty($teacher_absent->check4_date))
                                    {{ $user_name[$teacher_absent->check4_user_id] }}<br>
                                    <small>{{ substr($teacher_absent->check4_date,0,10) }}</small>
                                @endif
                            </td>
                            <td>
                                @if($check_power['a'] and !empty($teacher_absent->check1_date) and empty($teacher_absent->check2_date))
                                    <button onclick="if(confirm('您確定送出嗎?')) location.href='/teacher_absents/check/check2/{{ $teacher_absent->id }}';else return false">核准</button> <button onclick="open_url('{{ route('teacher_absents.back',$teacher_absent->id) }}','新視窗')">退</button>
                                @endif
                                @if(!empty($teacher_absent->check2_date))
                                    {{ $user_name[$teacher_absent->check2_user_id] }}<br>
                                    <small>{{ substr($teacher_absent->check2_date,0,10) }}</small>
                                @endif
                            </td>
                            <td>
                                @if($check_power['b'] and !empty($teacher_absent->check1_date) and empty($teacher_absent->check3_date))
                                    <button onclick="if(confirm('您確定送出嗎?')) location.href='/teacher_absents/check/check3/{{ $teacher_absent->id }}';else return false">記錄</button>
                                @endif
                                @if(!empty($teacher_absent->check3_date))
                                    {{ $user_name[$teacher_absent->check3_user_id] }}<br>
                                    <small>{{ substr($teacher_absent->check3_date,0,10) }}</small>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $teacher_absents->links() }}
            @endif
        </div>

    </div>
    <script>
        $('#select_semester').change(function(){
            location= '/teacher_absents/sir/'+ $('#select_semester').val();
        });

        function open_url(url,name)
        {
            window.open(url,name,'statusbar=no,scrollbars=yes,status=yes,resizable=yes,width=850,height=200');
        }
        function open_url2(url,name)
        {
            window.open(url,name,'statusbar=no,scrollbars=yes,status=yes,resizable=yes,width=850,height=800');
        }
    </script>
@endsection
