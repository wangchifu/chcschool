@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '社團報名-')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>社團報名</h1>
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('clubs.index') }}">學期設定</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('clubs.setup') }}">社團設定</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('clubs.report') }}">報表輸出</a>
                </li>
            </ul>
            <div class="card">
                <div class="card-body">
                    <?php
                        $admin = check_power('社團報名','A',auth()->user()->id);
                    ?>
                    @if($admin and $semester)
                            <h4>{{ $semester }}新增社團</h4>
                            {{ Form::open(['route' => 'clubs.club_store', 'method' => 'POST']) }}
                            <input type="hidden" name="semester" value="{{ $semester }}">
                            <div class="form-group">
                                <label for="class_id"><strong>社團類別*</strong></label>
                                {{ Form::select('class_id',$club_classes,null,['id'=>'class_id','class' => 'form-control','required'=>'required']) }}
                            </div>
                            <div class="form-group">
                                <label for="name"><strong>社團名稱*</strong></label>
                                {{ Form::text('name',null,['id'=>'name','class' => 'form-control','required'=>'required']) }}
                            </div>
                            <div class="form-group">
                                <label for="contact_person">聯絡人</label>
                                {{ Form::text('contact_person',null,['id'=>'contact_person','class' => 'form-control']) }}
                            </div>
                            <div class="form-group">
                                <label for="telephone_num">聯絡電話</label>
                                {{ Form::text('telephone_num',null,['id'=>'telephone_num','class' => 'form-control']) }}
                            </div>
                            <div class="form-group">
                                <label for="money"><strong>收費標準*</strong></label>
                                {{ Form::number('money',null,['id'=>'money','class' => 'form-control','required'=>'required']) }}
                            </div>
                            <div class="form-group">
                                <label for="teacher_info">師資</label>
                                {{ Form::text('teacher_info',null,['id'=>'teacher_info','class' => 'form-control']) }}
                            </div>
                            <div class="form-group">
                                <label for="start_date">開課日期</label>
                                {{ Form::text('start_date',null,['id'=>'start_date','class' => 'form-control']) }}
                            </div>
                            <div class="form-group">
                                <label for="start_time"><strong>上課時間*</strong><small class="text-primary">(請用24小時制)</small></label>
                                <br>
                                1.週<select name="start1_time1" style="height:30px;">
                                    <option value="一">一</option>
                                    <option value="二">二</option>
                                    <option value="三">三</option>
                                    <option value="四">四</option>
                                    <option value="五">五</option>
                                </select>
                                <input type="text" name="start1_time2" required placeholder="16:00(五碼)" maxlength="5">到<input type="text" name="start1_time3" required placeholder="17:30(五碼)" maxlength="5">
                                <br>
                                2.週<select name="start2_time1" style="height:30px;">
                                    <option value="0">無第二次</option>
                                    <option value="一">一</option>
                                    <option value="二">二</option>
                                    <option value="三">三</option>
                                    <option value="四">四</option>
                                    <option value="五">五</option>
                                </select>
				<input type="text" name="start2_time2" placeholder="16:00(五碼)" maxlength="5">到<input type="text" name="start2_time3" placeholder="17:30(五碼)" maxlength="5">
                               <br>
                               3.週<select name="start3_time1" style="height:30px;">
                                    <option value="0">無第三次</option>
                                    <option value="一">一</option>
                                    <option value="二">二</option>
                                    <option value="三">三</option>
                                    <option value="四">四</option>
                                    <option value="五">五</option>
                                </select>
				<input type="text" name="start3_time2" placeholder="16:00(五碼)" maxlength="5">到<input type="text" name="start3_time3" placeholder="17:30(五碼)" maxlength="5">
                               <br>
                               4.週<select name="start4_time1" style="height:30px;">
                                    <option value="0">無第四次</option>
                                    <option value="一">一</option>
                                    <option value="二">二</option>
                                    <option value="三">三</option>
                                    <option value="四">四</option>
                                    <option value="五">五</option>
                                </select>
				<input type="text" name="start4_time2" placeholder="16:00(五碼)" maxlength="5">到<input type="text" name="start4_time3" placeholder="17:30(五碼)" maxlength="5">
                               <br>
                               5.週<select name="start5_time1" style="height:30px;">
                                    <option value="0">無第五次</option>
                                    <option value="一">一</option>
                                    <option value="二">二</option>
                                    <option value="三">三</option>
                                    <option value="四">四</option>
                                    <option value="五">五</option>
                                </select>
                                <input type="text" name="start5_time2" placeholder="16:00(五碼)" maxlength="5">到<input type="text" name="start5_time3" placeholder="17:30(五碼)" maxlength="5">
                            </div>
                            <div class="form-group">
                                <label for="place">首次上課集合地點</label>
                                {{ Form::text('place',null,['id'=>'place','class' => 'form-control']) }}
                            </div>
                            <div class="form-group">
                                <label for="people"><strong>開課人數(最少)*</strong></label>
                                {{ Form::number('people',null,['id'=>'people','class' => 'form-control','required'=>'required']) }}
                            </div>
                            <div class="form-group">
                                <label for="taking"><strong>正取人數(最多)*</strong></label>
                                {{ Form::number('taking',null,['id'=>'taking','class' => 'form-control','required'=>'required']) }}
                            </div>
                            <div class="form-group">
                                <label for="prepare"><strong>候補人數*</strong></label>
                                {{ Form::number('prepare',null,['id'=>'prepare','class' => 'form-control','required'=>'required']) }}
                            </div>
                            <div class="form-group">
                                <label for="year_limit"><strong>年級限制*</strong><small class="text-primary">(用小寫,分隔 如：1,2,3,4,5,6 代表這些年級均可)</small></label>
                                {{ Form::text('year_limit',null,['id'=>'year_limit','class' => 'form-control','required'=>'required']) }}
                            </div>
                            <div class="form-group">
                                <label for="ps">備註</label>
                                {{ Form::text('ps',null,['id'=>'ps','class' => 'form-control']) }}
                            </div>
                            <div class="form-group">
                                <a class="btn btn-secondary btn-sm" href="{{ route('clubs.setup') }}"><i class="fas fa-backward"></i> 返回</a>
                                <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('確定儲存嗎？')">
                                    <i class="fas fa-save"></i> 儲存
                                </button>
                            </div>
                            @include('layouts.errors')
                            {{ Form::close() }}
                    @else
                        <span class="text-danger">你不是管理者</span>
                    @endif
                </div>
            </div>

        </div>
    </div>
@endsection
