@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '分類搜尋報修 | ')

@section('content')
    <?php $situations=['1'=>'處理完畢','2'=>'處理中','3'=>'申報中'];$types=['1'=>'1.設備或資訊','2'=>'2.總務']; ?>
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>{{ $situations[$situation] }}</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">首頁</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('fixes.index') }}">報修列表</a></li>
                    <li class="breadcrumb-item active" aria-current="page">分類搜尋-{{ $situations[$situation] }}</li>
                </ol>
            </nav>
            <a href="{{ route('fixes.index') }}" class="btn btn-outline-dark btn-sm"><i class="fas fa-check-square"></i> 全部列表</a>
            @include('fixes.nav',['situation'=>$situation])
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
                            {{ $fix->user->name }}
                        </td>
                        <td>
                            <a href="{{ route('fixes.show',$fix->id) }}">{{ $fix->title }}</a>
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
            {{ $fixes->links() }}
        </div>
    </div>
@endsection
