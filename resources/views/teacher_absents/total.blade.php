@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '教師差假')

@section('content')
    <?php

    $active['index'] ="";
    $active['deputy'] ="";
    $active['sir'] ="";
    $active['travel'] ="";
    $active['list'] ="";
    $active['total'] ="active";
    ?>
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>教師差假：差假統計</h1>
            @include('teacher_absents.nav')
            <br>
            {{ Form::select('select_semester',$semesters,$semester,['id'=>'select_semester']) }}
            {{ Form::select('select_month',$monthes,$month,['id'=>'select_month']) }}
            <table class="table table-hover" style="font-size: 5px;">
                <thead class="thead-light">
                <tr>
                    <th width="60px">
                        姓名
                    </th>
                    @foreach($abs_kinds as $k=>$v)
                    <th>
                        {{ $v }}
                    </th>
                    @endforeach
                </tr>
                </thead>
                <tbody>
                @foreach($teachers as $k1=>$v1)
                <tr>
                    <td>
                        {{ $v1 }}
                    </td>
                    @foreach($abs_kinds as $k=>$v)
                        <td>
                            {{ $abs_kind_total[$k1][$k] }}
                        </td>
                    @endforeach
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>

    </div>
    <script>
        $('#select_semester').change(function(){
            location= '/teacher_absents/total/'+ $('#select_semester').val()+'/'+$('#select_month').val();
        });
        $('#select_month').change(function(){
            location= '/teacher_absents/total/'+ $('#select_semester').val()+'/'+$('#select_month').val();
        });
    </script>
@endsection
