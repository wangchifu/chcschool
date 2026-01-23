@extends('layouts.master')

@section('title', '填報結果')

@section('content')
    <?php
    $active['index'] ="";
    $active['admin'] ="active";    
    ?>
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>{{ $report_student->name }}-填報結果</h1>
            @include('report_students.nav')
            <hr>            
            <a href="{{ route('report_students.admin') }}" class="btn btn-secondary mb-3">
                <i class="fas fa-arrow-left"></i> 返回填報管理
            </a>
            <a href="{{ route('report_students.admin_result_download', $report_student->id) }}" class="btn btn-primary mb-3">
                <i class="fas fa-download"></i> 下載 Excel
            </a>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>班級</th>
                        @foreach($report_student->items as $index => $item)
                            <th>{{ $item->name }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($answer_data as $class_num => $items)
                        <tr>
                            <td>{{ $class_num }}</td>
                            @foreach($report_student->items as $item)
                                <td>
                                    @if(isset($items[$item->id]))
                                        {{ $items[$item->id]['name'] }}
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>    
            </table>
        </div>
    </div>
@endsection