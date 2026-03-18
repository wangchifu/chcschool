@extends('layouts.master')

@section('title', '校園跑馬燈 | ')

@section('content')        
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>
                校園跑馬燈
            </h1>
            <ul class="nav nav-tabs">
                @if(auth()->user()->admin)
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('school_marquee.setup') }}">管理</a>
                </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('school_marquee.index') }}">列表</a>
                </li>
            </ul>
            <br>
            <div class="card">
                <div class="card-header text-center">
                    <h3 class="py-2">
                        跑馬燈列表
                    </h3>
                </div>
                <div class="card-body">                    
                    <?php
                    $school_marquee_width = (empty($setup->school_marquee_width))?"12":$setup->school_marquee_width;
                    $school_marquee_color = (empty($setup->school_marquee_color))?"warning":$setup->school_marquee_color;
                    $school_marquee_behavior = (empty($setup->school_marquee_behavior))?"scroll":$setup->school_marquee_behavior;
                    $school_marquee_direction = (empty($setup->school_marquee_direction))?"up":$setup->school_marquee_direction;
                    $school_marquee_scrollamount = (empty($setup->school_marquee_scrollamount))?"2":$setup->school_marquee_scrollamount;
                    ?>                                        
                    @if($school_marquee2s->count()>0)
                        <div class="row justify-content-center">
                            <div class="col-lg-{{ $school_marquee_width }}">
                                <div class="alert alert-{{ $school_marquee_color }} p-1" style="margin-top: -15px; overflow: hidden;">
                                    
                                    <div class="marquee-wrapper" id="marquee-container" 
                                        style="height: 25px; overflow: hidden; position: relative; background: transparent;">                            
                                        
                                        <div class="marquee-inner" id="marquee-content">
                                            @foreach($school_marquees as $school_marquee)
                                                <span class="marquee-item" style="margin-right: 50px; display: inline-block;">
                                                    📣 {{ $school_marquee->title }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    @endif                    
                    <a href="{{ route('school_marquee.create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> 新增跑馬燈</a>
                    <table class="table table-striped" style="word-break:break-all;">
                        <thead class="thead-light">
                        <tr>
                            <th nowrap>id</th>
                            <th>標題</th>
                            <th>開始日期</th>
                            <th>結束日期</th>
                            <th>上架者</th>
                            <th>動作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i=0;$j=0; ?>
                        @foreach($school_marquees as $school_marquee)
                            <tr>
                                <td nowrap>
                                    {{ $school_marquee->id }}
                                </td>
                                <?php
                                        if($school_marquee->stop_date < date('Y-m-d') or $school_marquee->start_date >  date('Y-m-d')){
                                            $not = "text-decoration: line-through;";
                                            $color = "text-secondary";
                                        }else{
                                            $not = "";
                                            $color = "";
                                        }
                                ?>
                                <td class="{{ $color }}" style="{{ $not }}">                                    
                                    {{ $school_marquee->title }}
                                </td>
                                <td>
                                    {{ $school_marquee->start_date }}
                                </td>
                                <td>
                                    {{ $school_marquee->stop_date }}
                                </td>
                                <td>
                                    {{ $school_marquee->user->name }}
                                </td>
                                <td>
                                    @if($school_marquee->user_id == auth()->user()->id or auth()->user()->admin)                                        
                                        <a href="javascript:open_window('{{ route('school_marquee.edit',$school_marquee->id) }}','新視窗')" class="btn btn-primary btn-sm" onclick=""><i class="fas fa-edit"></i> 修改</a>
                                        <a href="#" class="btn btn-danger btn-sm" onclick="if(confirm('確定刪除？')) document.getElementById('delete{{ $school_marquee->id }}').submit();else return false;"><i class="fas fa-trash"></i> 刪除</a>                                        
                                    @endif
                                </td>
                            </tr>
                            {{ Form::open(['route' => ['school_marquee.destroy',$school_marquee->id], 'method' => 'DELETE','id'=>'delete'.$school_marquee->id,'onsubmit'=>'return false;']) }}
                            {{ Form::close() }}
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        function open_window(url,name)
        {
            window.open(url,name,'statusbar=no,scrollbars=yes,status=yes,resizable=yes,width=900,height=800');
        }
    </script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // 1. 取得後端傳入的參數 (若變數名稱不同請自行調整)
        const behavior = "{{ $school_marquee_behavior }}";     // scroll, slide, alternate
        const direction = "{{ $school_marquee_direction }}";   // left, right, up, down
        const amount = parseInt("{{ $school_marquee_scrollamount }}") || 6;

        const container = document.getElementById('marquee-container');
        const content = document.getElementById('marquee-content');

        // 2. 基礎樣式設定
        content.style.position = 'absolute';
        content.style.display = 'flex';
        content.style.whiteSpace = 'nowrap';
        
        if (direction === 'up' || direction === 'down') {
            content.style.flexDirection = 'column';
        }

        // 3. 動態計算動畫路徑
        const contentWidth = content.offsetWidth;
        const containerWidth = container.offsetWidth;
        const contentHeight = content.offsetHeight;
        const containerHeight = container.offsetHeight;

        // 定義動畫 Keyframes
        let keyframes = '';
        if (direction === 'left') {
            keyframes = `@keyframes marqueeMove { 
                0% { transform: translateX(${containerWidth}px); } 
                100% { transform: translateX(-${contentWidth}px); } 
            }`;
        } else if (direction === 'right') {
            keyframes = `@keyframes marqueeMove { 
                0% { transform: translateX(-${contentWidth}px); } 
                100% { transform: translateX(${containerWidth}px); } 
            }`;
        } else if (direction === 'up') {
            keyframes = `@keyframes marqueeMove { 
                0% { transform: translateY(${containerHeight}px); } 
                100% { transform: translateY(-${contentHeight}px); } 
            }`;
        } else if (direction === 'down') {
            keyframes = `@keyframes marqueeMove { 
                0% { transform: translateY(-${contentHeight}px); } 
                100% { transform: translateY(${containerHeight}px); } 
            }`;
        }

        // 注入 CSS
        const style = document.createElement('style');
        style.innerHTML = keyframes;
        document.head.appendChild(style);

        // 4. 套用動畫效果
        const duration = (direction === 'left' || direction === 'right') 
                        ? (contentWidth + containerWidth) / (amount * 10) 
                        : (contentHeight + containerHeight) / (amount * 5);

        content.style.animation = `marqueeMove ${duration}s linear infinite`;

        // 5. 處理 Behavior
        if (behavior === 'slide') {
            content.style.animationIterationCount = '1';
            content.style.animationFillMode = 'forwards';
        } else if (behavior === 'alternate') {
            content.style.animationDirection = 'alternate';
        }

        // 滑鼠移入停止 (可選，通常跑馬燈需要這個功能)
        container.onmouseover = () => content.style.animationPlayState = 'paused';
        container.onmouseout = () => content.style.animationPlayState = 'running';
    });
    </script>    
@endsection
