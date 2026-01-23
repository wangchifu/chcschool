@extends('layouts.master')

@section('title', '填報學生')

@section('content')
    <?php
    $active['index'] ="active";
    $active['admin'] ="";    
    ?>
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>填報學生-導師填報</h1>
            @include('report_students.nav')
            <hr>
            <table class="table table-striped">
                <thead class="table-primary">
                <tr>
                    <th>
                        報名期限
                    </th>
                    <th>
                        名稱
                    </th>
                    <th>
                        建立者
                    </th>
                    <th>
                        動作
                    </th>
                </tr>
                </thead>
                <tbody>                
                @foreach($report_students as $report_student)
                    <tr>
                        <td>
                            {{ $report_student->started_at }}<br>
                            <span class="text-danger">{{ $report_student->stopped_at }}</span>
                        </td>
                        <td>
                            {{ $report_student->name }}
                            @if($report_student->disable==1)
                                <span class="text-danger">[已停止填報 ]</span>
                            @endif
                        </td>
                        <td>
                            {{ $report_student->user->name }}
                        </td>
                        <td> 
                            @if(!isset($teacher_class[$report_student->semester]))       
                                <span class="text-danger">您本學期沒有導師班級，無法填報學生</span>
                            @else                    
                                <a href="javascript:open_window('{{ route('report_students.teacher_fill', ['report_student' => $report_student->id]) }}','新視窗')" class="btn btn-primary btn-sm">填報學生</a>                            
                            @endif
                            <?php
                                $check_answers = \App\ReportStudentAnswer::where('report_student_id', $report_student->id)
                                    ->where('user_id', auth()->user()->id)
                                    ->first();
                                if($check_answers){
                                    echo '<span class="text-success">已有填報資料</span>';
                                }else{
                                    echo '<span class="text-danger">尚未填報資料</span>';
                                } 
                            ?>
                        </td>
                    </tr>
                @endforeach
                </tbody>                
            </table>            
             
        </div>
    </div>
<script>
    function open_window(url,name)
    {
        window.open(url,name,'statusbar=no,scrollbars=yes,status=yes,resizable=yes,width=1000,height=900');
    }
</script>    
@endsection