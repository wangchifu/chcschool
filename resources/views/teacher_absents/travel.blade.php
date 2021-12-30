@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '教師差假')

@section('content')
    <?php

    $active['index'] ="";
    $active['deputy'] ="";
    $active['sir'] ="";
    $active['travel'] ="active";
    $active['list'] ="";
    $active['total'] ="";
    ?>
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>教師差假：差旅費列表</h1>
            @include('teacher_absents.nav')
            <br>
            {{ Form::select('select_semester',$semesters,$semester,['id'=>'select_semester']) }}
            <form action="{{ route('teacher_absents.travel_print') }}" method="post" id="print" target="_blank">
                @csrf
                <table class="table table-striped">
                    <thead class="thead-light">
                    <tr>
                        <th>
                            #公差假單 <img src="{{ asset('images/printer.gif') }}" onclick="go_print()">
                        </th>
                        <th>
                            姓名
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
                            差旅費資訊
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($teacher_absents as $teacher_absent)
                        <tr>
                            <td>
                                <input type="checkbox" name="travels[{{ $teacher_absent->id }}]">
                                <small>
                                {{ $teacher_absent->id }} 核准 <a href="javascript:open_url('{{ route('teacher_absents.outlay',$teacher_absent->id) }}','新視窗')"><i class="fas fa-plus-circle text-success"></i></a>
                                </small>
                                <br>
                                <small class="text-primary">{{ $teacher_absent->place }}</small>
                            </td>
                            <td>
                                {{ $user_name[$teacher_absent->user_id] }}<br>
                                <small>{{ substr($teacher_absent->created_at,0,10) }}</small>
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
                                <ul>
                                @foreach($teacher_absent->teacher_absent_outlays as $teacher_absent_outlay)
                                    <li>
                                        {{ $teacher_absent_outlay->outlay_date }}
                                        {{ $teacher_absent_outlay->places }}({{ $teacher_absent_outlay->outlay1+$teacher_absent_outlay->outlay2+$teacher_absent_outlay->outlay3+$teacher_absent_outlay->outlay4+$teacher_absent_outlay->outlay5+$teacher_absent_outlay->outlay6+$teacher_absent_outlay->outlay7+$teacher_absent_outlay->outlay8 }} 元)
                                        <a href="javascript:open_url('{{ route('teacher_absents.edit_outlay',$teacher_absent_outlay->id) }}','新視窗')"><i class="fas fa-edit"></i></a>
                                        <a href="{{ route('teacher_absents.delete_outlay',$teacher_absent_outlay->id) }}" onclick="return confirm('刪除？')"><i class="fas fa-times-circle text-danger"></i></a>
                                    </li>
                                @endforeach
                                </ul>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </form>
        </div>
    </div>
    <script>
        $('#select_semester').change(function(){
            location= '/teacher_absents/travel/'+ $('#select_semester').val();
        });

        function open_url(url,name)
        {
            window.open(url,name,'statusbar=no,scrollbars=yes,status=yes,resizable=yes,width=1300,height=300');
        }
        function go_print(){
            $('#print').submit();
        }
    </script>


@endsection
