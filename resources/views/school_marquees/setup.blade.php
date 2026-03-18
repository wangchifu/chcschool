@extends('layouts.master')

@section('title', '校園跑馬燈 | ')

@section('content')        
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>
                校園跑馬燈
            </h1>
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('school_marquee.setup') }}">管理</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('school_marquee.index') }}">列表</a>
                </li>
            </ul>
            <br>
            <div class="card">
                <div class="card-header text-center">
                    <h3 class="py-2">
                        跑馬燈管理
                    </h3>
                </div>
                <div class="card-body">
                    @if($school_marquees->count()>0)                    
                    <?php
                    $school_marquee_width = (empty($setup->school_marquee_width))?"12":$setup->school_marquee_width;
                    $school_marquee_color = (empty($setup->school_marquee_color))?"warning":$setup->school_marquee_color;
                    $school_marquee_behavior = (empty($setup->school_marquee_behavior))?"scroll":$setup->school_marquee_behavior;
                    $school_marquee_direction = (empty($setup->school_marquee_direction))?"up":$setup->school_marquee_direction;
                    $school_marquee_scrollamount = (empty($setup->school_marquee_scrollamount))?"2":$setup->school_marquee_scrollamount;
                    ?>
                    @if($school_marquees->count()>0)
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
                    @endif
                    {{ Form::open(['route' => 'school_marquee.setup_store', 'method' => 'POST']) }}     
                    <div class="form-group">
                        <label for="school_marquee_width">寬度</label>
                        <?php
                            $select_width[1] = "";
                            $select_width[2] = "";
                            $select_width[3] = "";
                            $select_width[4] = "";
                            $select_width[5] = "";
                            $select_width[6] = "";
                            $select_width[7] = "";
                            $select_width[8] = "";
                            $select_width[9] = "";
                            $select_width[10] = "";
                            $select_width[11] = "";
                            $select_width[12] = "";
                            if(!empty($setup->school_marquee_width)){
                                $select_width[$setup->school_marquee_width] = "selected";
                            }
                        ?>
                        <select class="form-control" style="width:250px" name="school_marquee_width" id="school_marquee_width">
                          <option value="1" {{ $select_width[1] }}>1格</option>
                          <option value="2" {{ $select_width[2] }}>2格</option>
                          <option value="3" {{ $select_width[3] }}>3格</option>
                          <option value="4" {{ $select_width[4] }}>4格</option>
                          <option value="5" {{ $select_width[5] }}>5格</option>
                          <option value="6" {{ $select_width[6] }}>6格</option>
                          <option value="7" {{ $select_width[7] }}>7格</option>
                          <option value="8" {{ $select_width[8] }}>8格</option>
                          <option value="9" {{ $select_width[9] }}>9格</option>
                          <option value="10" {{ $select_width[10] }}>10格</option>
                          <option value="11" {{ $select_width[11] }}>11格</option>
                          <option value="12" {{ $select_width[12] }}>12格</option>
                        </select>
                    </div>              
                    <div class="form-group">
                        <label for="school_marquee_color">底部顏色</label>
                        <?php
                            $select_color['primary'] = "";
                            $select_color['secondary'] = "";
                            $select_color['success'] = "";
                            $select_color['danger'] = "";
                            $select_color['warning'] = "";
                            $select_color['info'] = "";
                            $select_color['light'] = "";
                            $select_color['dark'] = "";
                            if(!empty($setup->school_marquee_color)){
                                $select_color[$setup->school_marquee_color] = "selected";
                            }
                        ?>
                        <select class="form-control" style="width:250px" name="school_marquee_color" id="school_marquee_color">
                          <option value="primary" {{ $select_color['primary'] }}>藍 primary</option>
                          <option value="secondary" {{ $select_color['secondary'] }}>灰 secondary</option>
                          <option value="success" {{ $select_color['success'] }}>綠 success</option>
                          <option value="danger" {{ $select_color['danger'] }}>紅 danger</option>
                          <option value="warning" {{ $select_color['warning'] }}>黃 warning</option>
                          <option value="info" {{ $select_color['info'] }}>青 info</option>
                          <option value="light" {{ $select_color['light'] }}>白 light</option>
                          <option value="dark" {{ $select_color['dark'] }}>暗 dark</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="school_marquee_behavior">移動樣式</label>
                        <?php
                            $select_behavior['scroll'] = ""; 
                            $select_behavior['slide'] = "";
                            $select_behavior['alternate'] = "";
                            if(!empty($setup->school_marquee_behavior)){
                                $select_behavior[$setup->school_marquee_behavior] = "selected";
                            }
                        ?>
                        <select class="form-control" style="width:250px" name="school_marquee_behavior" id="school_marquee_behavior">
                          <option value="scroll" {{ $select_behavior['scroll'] }}>滾動 scroll</option>
                          <option value="slide" {{ $select_behavior['slide'] }}>滑動 slide</option>
                          <option value="alternate" {{ $select_behavior['alternate'] }}>交替 alternate</option>                          
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="school_marquee_direction">移動方向</label>
                        <?php
                            $select_direction['left'] = ""; 
                            $select_direction['right'] = "";
                            $select_direction['up'] = "";
                            $select_direction['down'] = "";
                            if(!empty($setup->school_marquee_direction)){
                                $select_direction[$setup->school_marquee_direction] = "selected";
                            }
                        ?>
                        <select class="form-control" style="width:250px" name="school_marquee_direction" id="school_marquee_direction">
                          <option value="left" {{ $select_direction['left'] }}>左</option>
                          <option value="right" {{ $select_direction['right'] }}>右</option>
                          <option value="up" {{ $select_direction['up'] }}>上</option>
                          <option value="down" {{ $select_direction['down'] }}>下</option>                          
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="school_marquee_scrollamount">移動速度</label>
                        <?php
                            $select_scrollamount[2] = "";                             
                            $select_scrollamount[4] = "";
                            $select_scrollamount[6] = "";                            
                            $select_scrollamount[8] = "";
                            $select_scrollamount[10] = "";
                            $select_scrollamount[12] = "";
                            $select_scrollamount[14] = "";
                            $select_scrollamount[16] = "";
                            if(!empty($setup->school_marquee_scrollamount)){
                                $select_scrollamount[$setup->school_marquee_scrollamount] = "selected";
                            }
                        ?>
                        <select class="form-control" style="width:250px" name="school_marquee_scrollamount" id="school_marquee_scrollamount">
                          <option {{ $select_scrollamount[2] }}>2</option>                          
                          <option {{ $select_scrollamount[4] }}>4</option>
                          <option {{ $select_scrollamount[6] }}>6</option>                          
                          <option {{ $select_scrollamount[8] }}>8</option>
                          <option {{ $select_scrollamount[10] }}>10</option>
                          <option {{ $select_scrollamount[12] }}>12</option>
                          <option {{ $select_scrollamount[14] }}>14</option>
                          <option {{ $select_scrollamount[16] }}>16</option>
                        </select>
                    </div>
                    <div class="form-group">                        
                        <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('確定儲存嗎？')">
                            <i class="fas fa-save"></i> 儲存設定
                        </button>
                    </div>                    
                    {{ Form::close() }}                    
                </div>
            </div>
        </div>
    </div>
@endsection
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
