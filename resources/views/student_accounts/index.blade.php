@extends('layouts.master')

@section('title', '學生帳號 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>學生帳號</h1>
            @if($admin)  
                <div class="card">
                    <div class="card-body">
                        XLSX檔上傳。<br>
                        {{ Form::open(['route' => ['student_account.upload'], 'method' => 'POST', 'files' => true]) }}
                            @csrf             
                            <input type="file" name="file" accept='.xlsx' required>
                            <input type="submit" class="btn btn-success btn-sm" value="匯入學生帳號清單">
                            <a href="{{ route('student_account.check') }}" class="btn btn-info btn-sm" target="_blank">學生帳號查詢頁面</a>
                        {{ Form::close() }}
                        @include('layouts.errors')
                        <hr>
                        已上傳檔案，只能存在一個，其餘請刪除：<br>
                        @foreach($files as $file)
                            <a href="{{ route('student_account.delete', ['file' => $file]) }}" onclick="return confirm('確定要刪除這個檔案嗎？')"><i class="fas fa-times-circle text-danger"></i></a> <a href="{{ asset('storage/'.$school_code.'/student_account/'.$file) }}" target="_blank">{{ $file }}</a><br>
                        @endforeach
                        <hr>
                        學生帳號清單：<br>
                        <table class="table table-hover table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" style="width: 80px;">#</th>
                                    <th scope="col">學生班級座號</th>
                                    <th scope="col">西元生日</th>
                                    <th scope="col">帳號</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($all_students as $index => $student)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $student['classnum'] ?? '--' }}</td>
                                        <td>{{ $student['birthday'] ?? '--' }}</td>
                                        <td>
                                            <code class="fw-bold">{{ $student['account'] ?? '未設定' }}</code>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">
                                            目前沒有學生資料
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>                        

                    </div>
                </div>
            @endif            
        </div>
    </div>
@endsection