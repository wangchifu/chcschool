@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '報修系統 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>學生報修系統</h1>                                    
            <a href="{{ route('fixes.stu_create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> 新增報修</a>  
            <a href="{{ route('stu.logout') }}" class="btn btn-danger btn-sm"><i class="fas fa-sign-out-alt"></i> 登出 ({{ session('stu_data') }})</a>                        
            <table class="table table-striped">
                <thead class="thead-light">
                <tr>
                    <th>類別</th>
                    <th>處理狀況</th>
                    <th>申報日期</th>
                    <th>申報人</th>
                    <th>標題</th>
                    <th>處理日期</th>
                </tr>
                </thead>
                <tbody>
                @foreach($fixes as $fix)
                    <tr>
                        <td>                            
                            {{ $types[$fix->type] }}
                        </td>
                        <td>
                            <?php
                            $situation=['1'=>'處理完畢','2'=>'處理中','3'=>'申報中'];
                            $icon = [
                                '1'=>'<i class="fas fa-check-square text-success"></i>',
                                '2'=>'<i class="fas fa-exclamation-triangle text-warning"></i>',
                                '3'=>'<i class="fas fa-phone-square text-danger"></i>'
                            ];
                            ?>
                            {!! $icon[$fix->situation] !!} {{ $situation[$fix->situation] }}
                        </td>
                        <td>
                            {{ substr($fix->created_at,0,10) }}
                        </td>
                        <td>
                            @if($fix->user_id == 0)
                                學生
                            @else
                                {{ $fix->user->name }}
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('fixes.stu_show',$fix->id) }}">{{ $fix->title }}</a>
                        </td>
                        <td>
                            @if($fix->situation < 3)
                                {{ substr($fix->updated_at,0,10) }}
                            @endif
                        </td>
                    </tr>

                @endforeach
                </tbody>
            </table>
            <div class="table-responsive">
            {{ $fixes->links() }}
            </div>
        </div>
    </div>
@endsection
