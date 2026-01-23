@extends('layouts.master')

@section('title', '填報學生')

@section('content')
    <?php
    $active['index'] ="";
    $active['admin'] ="active";    
    ?>
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>填報學生-填報管理</h1>
            @include('report_students.nav')
            <hr>
            <div class="card">
                <div class="card-body">
                    <h4>一、建立填報</h4>
                    <table class="table table-bordered">
                        <thead class="table-warning">
                            <tr>
                                <th>學期</th>
                                <th>填報名稱</th>
                                <th>開始日期</th>
                                <th>結束日期</th>
                                <th>動作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <form action="{{ route('report_students.store_report_student') }}" method="POST">
                                @csrf
                                <tr>
                                    <td><input type="text" class="form-control" name="semester" required value="{{ $semester }}"></td>
                                    <td><input type="text" class="form-control" name="name" required autofocus></td>
                                    <td><input type="date" class="form-control" name="started_at" required></td>
                                    <td><input type="date" class="form-control" name="stopped_at" required></td>
                                    <td><button class="btn btn-success btn-sm" onclick="return confirm('確定？')">新增填報</button></td>
                                </tr>
                            </form>                            
                        </tbody>                            
                    </table>
                            <table class="table table-bordered">
                                <thead class="table-success">
                                    <tr>
                                        <th>學期</th>
                                        <th>填報名稱</th>
                                        <th>題目</th>
                                        <th>開始日期</th>
                                        <th>結束日期</th>
                                        <th>建立者</th>
                                        <th>動作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($now_report as $k=>$v)
                                    <form action="{{ route('report_students.update_report_student', ['report_student' => $k]) }}" method="POST">
                                    @csrf
                                    <tr>
                                        <td><input type="text" class="form-control" name="semester" value="{{ $v['semester'] }}"></td>
                                        <td><input type="text" class="form-control" name="name" value="{{ $v['name'] }}"></td>
                                        <td>
                                            <?php
                                                $items = \App\ReportStudentItem::where('report_student_id',$k)->get();    
                                            ?>
                                            <ul>
                                            @foreach($items as $item)
                                                <li>{{ $item->name }}<a href="{{ route('report_students.admin_item_delete',$item->id) }}" onclick="return confirm('確定刪除？')"> <i class="fas fa-times-circle text-danger"></i></a></li>
                                            @endforeach
                                            </ul>
                                        </td>
                                        <td><input type="date" class="form-control" name="started_at" value="{{ $v['started_at'] }}"></td>
                                        <td><input type="date" class="form-control" name="stopped_at" value="{{ $v['stopped_at'] }}"></td>
                                        <td>{{ $v['user'] }}</td>
                                        <td>
                                            <a href="javascript:open_window('{{ route('report_students.admin_item', ['report_student' => $k]) }}','新視窗')" class="btn btn-primary btn-sm">項目管理</a>
                                            <input type="submit" class="btn btn-sm btn-success" name="action" onclick="return confirm('確定更新？')" value="更新">
                                            <input type="submit" class="btn btn-sm btn-info" name="action" onclick="return confirm('確定複製？')" value="複製">                                            
                                            <a href="{{ route('report_students.admin_result',$k) }}" class="btn btn-sm btn-dark">成果</a>
                                            <a href="{{ route('report_students.delete_report_student',$k) }}" class="btn btn-sm btn-danger" onclick="return confirm('確定刪除？')">刪除</a>
                                        </td>
                                    </tr>             
                                    </form>              
                                    @endforeach
                                </tbody>
                            </table>                    
                    <p>
                    <a class="btn btn-primary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                        顯示過期填報
                    </a>
                    </p>
                    <div class="collapse" id="collapseExample">
                        <table class="table table-bordered">
                            <thead class="table-danger">
                                <tr>
                                    <th>學期</th>
                                    <th>填報名稱</th>
                                    <th>題目</th>
                                    <th>開始日期</th>
                                    <th>結束日期</th>
                                    <th>建立者</th>
                                    <th>動作</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($not_report as $k=>$v)
                                <form action="{{ route('report_students.update_report_student', ['report_student' => $k]) }}" method="POST">
                                @csrf
                                <tr>
                                    <td><input type="text" class="form-control" name="semester" value="{{ $v['semester'] }}"></td>
                                    <td><input type="text" class="form-control" name="name" value="{{ $v['name'] }}"></td>
                                    <td>
                                        <?php
                                            $items = \App\ReportStudentItem::where('report_student_id',$k)->get();    
                                        ?>
                                        <ul>
                                        @foreach($items as $item)
                                            <li>{{ $item->name }}<a href="{{ route('report_students.admin_item_delete',$item->id) }}" onclick="return confirm('確定刪除？')"> <i class="fas fa-times-circle text-danger"></i></a></li>
                                        @endforeach
                                        </ul>
                                    </td>
                                    <td><input type="date" class="form-control" name="started_at" value="{{ $v['started_at'] }}"></td>
                                    <td><input type="date" class="form-control" name="stopped_at" value="{{ $v['stopped_at'] }}"></td>
                                    <td>{{ $v['name'] }}</td>
                                    <td>
                                        <a href="javascript:open_window('{{ route('report_students.admin_item', ['report_student' => $k]) }}','新視窗')" class="btn btn-primary btn-sm">項目管理</a>
                                        <input type="submit" class="btn btn-sm btn-success" name="action" onclick="return confirm('確定更新？')" value="更新">
                                        <input type="submit" class="btn btn-sm btn-info" name="action" onclick="return confirm('確定複製？')" value="複製">     
                                        <a href="{{ route('report_students.admin_result',$k) }}" class="btn btn-sm btn-dark">成果</a>                                       
                                        <a href="{{ route('report_students.delete_report_student',$k) }}" class="btn btn-sm btn-danger" onclick="return confirm('確定刪除？')">刪除</a>                                        
                                    </td>
                                </tr> 
                                </form>                           
                                @endforeach
                            </tbody>
                        </table>
                    </div>                    
                    <hr>
                    <h4>二、學生資料</h4>
                     {{ Form::open(['route' => ['sport_meeting.stu_import'], 'method' => 'POST', 'files' => true]) }}
                     @csrf
                     學年<input type="semester" name="semester" value="{{ get_date_semester(date('Y-m-d')) }}" style="width:80px" required maxlength="4">
                    <input type="file" name="file" required>
                    <input type="submit" class="btn btn-success btn-sm" value="匯入學生" onclick="return confirm('要等一下子，確定嗎？')">
                    {{ Form::close() }}
                    @include('layouts.errors')
                    <a href="{{ asset('images/cloudschool_club.png') }}" target="_blank">請先至 cloudschool 下載列表</a>                      
                    <hr>                    
                    <p>
                    <a class="btn btn-primary" data-toggle="collapse" href="#collapseExample2" role="button" aria-expanded="false" aria-controls="collapseExample2">
                        顯示各學期已匯入學生班級資料
                    </a>
                    </p>
                    <div class="collapse" id="collapseExample2">
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
                                        {{ $class_num[$k] }} <a href="{{ route('report_students.stu_adm_more',['semester'=>$k,'student_class_id'=>null]) }}" class="btn btn-info btn-sm">詳細資料</a>
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
    </div>
<script>
    function open_window(url,name)
    {
        window.open(url,name,'statusbar=no,scrollbars=yes,status=yes,resizable=yes,width=1000,height=900');
    }
</script>
@endsection