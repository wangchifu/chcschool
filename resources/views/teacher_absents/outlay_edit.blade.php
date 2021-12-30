@extends('layouts.master_clean')

@section('nav_school_active', 'active')

@section('title', '教師差假')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <form action="{{ route('teacher_absents.update_outlay',$teacher_absent_outlay->id) }}" method="post">
                @csrf
                <table class="table table-bordered">
                    <thead class="thead-light">
                    <tr>
                        <th rowspan="2">
                            日期
                        </th>
                        <th rowspan="2">
                            起迄地點
                        </th>
                        <th rowspan="2">
                            工作記要
                        </th>
                        <th colspan="4">
                            交通費
                        </th>
                        <th rowspan="2">
                            住宿費
                        </th>
                        <th rowspan="2">
                            旅行業代收轉付
                        </th>
                        <th rowspan="2">
                            單據號數
                        </th>
                        <th rowspan="2">
                            膳什費
                        </th>
                    </tr>
                    <tr>
                        <th>
                            飛機
                        </th>
                        <th>
                            汔車及捷運
                        </th>
                        <th>
                            火車
                        </th>
                        <th>
                            高鐵
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            <input type="text" name="outlay_date" required maxlength="10" value="{{ $teacher_absent_outlay->outlay_date }}">
                        </td>
                        <td>
                            <input type="text" name="places" required value="{{ $teacher_absent_outlay->places }}">
                        </td>
                        <td>
                            <input type="text" name="remember" required value="{{ $teacher_absent_outlay->remember }}">
                        </td>
                        <td>
                            <input type="text" name="outlay1" size="5" value="{{ $teacher_absent_outlay->outlay1 }}">
                        </td>
                        <td>
                            <input type="text" name="outlay2" size="5" value="{{ $teacher_absent_outlay->outlay2 }}">
                        </td>
                        <td>
                            <input type="text" name="outlay3" size="5" value="{{ $teacher_absent_outlay->outlay3 }}">
                        </td>
                        <td>
                            <input type="text" name="outlay4" size="5" value="{{ $teacher_absent_outlay->outlay4 }}">
                        </td>
                        <td>
                            <input type="text" name="outlay5" size="5" value="{{ $teacher_absent_outlay->outlay5 }}">
                        </td>
                        <td>
                            <input type="text" name="outlay6" size="5" value="{{ $teacher_absent_outlay->outlay6 }}">
                        </td>
                        <td>
                            <input type="text" name="outlay7" size="5" value="{{ $teacher_absent_outlay->outlay7 }}">
                        </td>
                        <td>
                            <input type="text" name="outlay8" size="5" value="{{ $teacher_absent_outlay->outlay8 }}">
                        </td>
                    </tr>
                    </tbody>
                </table>
                <button class="btn btn-primary btn-sm" onclick="return confirm('確定？')">送出</button>
            </form>
        </div>
    </div>
@endsection
