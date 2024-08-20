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
                    {{ Form::open(['route' => 'school_marquee.setup_store', 'method' => 'POST']) }}                   
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
