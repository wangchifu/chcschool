@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '顯示報修 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>顯示報修</h1>       
            <a href="{{ route('fixes.stu_logout') }}" class="btn btn-danger btn-sm"><i class="fas fa-sign-out-alt"></i> 登出 ({{ session('stu_data') }})</a>                             
            <p class="lead">
                <?php
                $s=['1'=>'處理完畢','2'=>'處理中','3'=>'申報中'];
                $icon = [
                    '1'=>'<i class="fas fa-check-square text-success"></i>',
                    '2'=>'<i class="fas fa-exclamation-triangle text-warning"></i>',
                    '3'=>'<i class="fas fa-phone-square text-danger"></i>'
                ];
                ?>
                {!! $icon[$fix->situation] !!} {{ $s[$fix->situation] }}

                 / 張貼者 
                 @if($fix->user_id == 0)
                    學生
                 @else
                    {{ $fix->user->name }}
                @endif
            </p>
            <hr>
            <p>
                張貼日期： {{ $fix->created_at }}　　　
            </p>
            <hr>
            <h3>{{ $fix->title }}</h3>
            <div style="border:2px #ccc solid;border-radius:10px;background-color:#eee;padding:10px;">
                <p style="font-size: 1.2rem;" >
                    <?php $content = str_replace(chr(13) . chr(10), '<br>', $fix->content);?>
                    {!! $content !!}
                </p>
            </div>
            <hr>
            @if(!empty($fix->reply))
                <?php $reply = str_replace(chr(13) . chr(10), '<br>', $fix->reply);?>
                <h4 class="text-danger">管理員回覆：</h4>
                <p style="font-size: 1.2rem;" class="text-danger">
                    {!! $reply !!}
                </p>
            @endif   
            <a href="{{ route('fixes.stu_list') }}" class="btn btn-secondary btn-sm"><i class="fas fa-backward"></i> 返回</a>                     
        </div>
    </div>
@endsection
