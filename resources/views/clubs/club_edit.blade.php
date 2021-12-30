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
                    @if($admin)
                            <h4>{{ $club->semester }}修改社團</h4>
                            {{ Form::model($club,['route' => ['clubs.club_update',$club->id], 'method' => 'patch']) }}
                            <div class="form-group">
                                <label for="class_id"><strong>社團類別*</strong></label>
                                {{ Form::select('class_id',$club_classes,$club->class_id,['id'=>'class_id','class' => 'form-control','required'=>'required']) }}
                            </div>
                            <div class="form-group">
                                <label for="no"><strong>社團編號*</strong><small class="text-primary">(如 A)</small></label>
                                {{ Form::text('no',$club->no,['id'=>'no','class' => 'form-control','required'=>'required']) }}
                            </div>
                            <div class="form-group">
                                <label for="name"><strong>社團名稱*</strong></label>
                                {{ Form::text('name',$club->name,['id'=>'name','class' => 'form-control','required'=>'required']) }}
                            </div>
                            <div class="form-group">
                                <label for="contact_person">聯絡人</label>
                                {{ Form::text('contact_person',$club->contact_person,['id'=>'contact_person','class' => 'form-control']) }}
                            </div>
                            <div class="form-group">
                                <label for="telephone_num">聯絡電話</label>
                                {{ Form::text('telephone_num',$club->telephone_num,['id'=>'telephone_num','class' => 'form-control']) }}
                            </div>
                            <div class="form-group">
                                <label for="money"><strong>收費標準*</strong></label>
                                {{ Form::number('money',$club->money,['id'=>'money','class' => 'form-control','required'=>'required']) }}
                            </div>
                            <div class="form-group">
                                <label for="teacher_info">師資</label>
                                {{ Form::text('teacher_info',$club->teacher_info,['id'=>'teacher_info','class' => 'form-control']) }}
                            </div>
                            <div class="form-group">
                                <label for="start_date">開課日期</label>
                                {{ Form::text('start_date',$club->start_date,['id'=>'start_date','class' => 'form-control']) }}
                            </div>
                            <div class="form-group">
                                <label for="start_time"><strong>上課時間*</strong><small class="text-primary">(請用24小時制)</small></label>
                                <br>
                                <?php
                                    $t = explode(';',$club->start_time);
                                    $s1 = explode('-',$t[0]);
                                    if(isset($t[1])){
                                        $s2 = explode('-',$t[1]);
                                    }else{
                                        $s2[0] =0;
                                        $s2[1] =null;
                                        $s2[2] =null;
                                    }
                                    if(isset($t[2])){
                                        $s3 = explode('-',$t[2]);
                                    }else{
                                        $s3[0] =0;
                                        $s3[1] =null;
                                        $s3[2] =null;
                                    }
                                    if(isset($t[3])){
                                        $s4 = explode('-',$t[3]);
                                    }else{
                                        $s4[0] =0;
                                        $s4[1] =null;
                                        $s4[2] =null;
                                    }
                                    if(isset($t[4])){
                                        $s5 = explode('-',$t[4]);
                                    }else{
                                        $s5[0] =0;
                                        $s5[1] =null;
                                        $s5[2] =null;
                                    }
                                    $select1_1 = null;
                                    $select1_2 = null;
                                    $select1_3 = null;
                                    $select1_4 = null;
                                    $select1_5 = null;
                                    if($s1[0]==="一") $select1_1 = "selected";
                                    if($s1[0]==="二") $select1_2 = "selected";
                                    if($s1[0]==="三") $select1_3 = "selected";
                                    if($s1[0]==="四") $select1_4 = "selected";
                                    if($s1[0]==="五") $select1_5 = "selected";

                                    $select2_1 = null;
                                    $select2_2 = null;
                                    $select2_3 = null;
                                    $select2_4 = null;
                                    $select2_5 = null;
                                    if($s2[0]==="一") $select2_1 = "selected";
                                    if($s2[0]==="二") $select2_2 = "selected";
                                    if($s2[0]==="三") $select2_3 = "selected";
                                    if($s2[0]==="四") $select2_4 = "selected";
                                    if($s2[0]==="五") $select2_5 = "selected";
                                    
                                    $select3_1 = null;
                                    $select3_2 = null;
                                    $select3_3 = null;
                                    $select3_4 = null;
                                    $select3_5 = null;
                                    if($s3[0]==="一") $select3_1 = "selected";
                                    if($s3[0]==="二") $select3_2 = "selected";
                                    if($s3[0]==="三") $select3_3 = "selected";
                                    if($s3[0]==="四") $select3_4 = "selected";
                                    if($s3[0]==="五") $select3_5 = "selected";
                                    
                                    $select4_1 = null;
                                    $select4_2 = null;
                                    $select4_3 = null;
                                    $select4_4 = null;
                                    $select4_5 = null;
                                    if($s4[0]==="一") $select4_1 = "selected";
                                    if($s4[0]==="二") $select4_2 = "selected";
                                    if($s4[0]==="三") $select4_3 = "selected";
                                    if($s4[0]==="四") $select4_4 = "selected";
                                    if($s4[0]==="五") $select4_5 = "selected";
                                    
                                    $select5_1 = null;
                                    $select5_2 = null;
                                    $select5_3 = null;
                                    $select5_4 = null;
                                    $select5_5 = null;
                                    if($s5[0]==="一") $select5_1 = "selected";
                                    if($s5[0]==="二") $select5_2 = "selected";
                                    if($s5[0]==="三") $select5_3 = "selected";
                                    if($s5[0]==="四") $select5_4 = "selected";
                                    if($s5[0]==="五") $select5_5 = "selected";

                                ?>
                                1.週<select name="start1_time1" style="height:30px;">
                                    <option value="一" {{ $select1_1 }}>一</option>
                                    <option value="二" {{ $select1_2 }}>二</option>
                                    <option value="三" {{ $select1_3 }}>三</option>
                                    <option value="四" {{ $select1_4 }}>四</option>
                                    <option value="五" {{ $select1_5 }}>五</option>
                                </select>
                                <input type="text" name="start1_time2" value="{{ $s1[1] }}" required placeholder="16:00(五碼)" maxlength="5">到<input type="text" name="start1_time3" value="{{ $s1[2] }}" required placeholder="17:30(五碼)" maxlength="5">
                                <br>
                                2.週<select name="start2_time1" style="height:30px;">
                                    <option value="0">無第二次</option>
                                    <option value="一" {{ $select2_1 }}>一</option>
                                    <option value="二" {{ $select2_2 }}>二</option>
                                    <option value="三" {{ $select2_3 }}>三</option>
                                    <option value="四" {{ $select2_4 }}>四</option>
                                    <option value="五" {{ $select2_5 }}>五</option>
                                </select>
                                <input type="text" name="start2_time2" value="{{ $s2[1] }}" placeholder="16:00(五碼)" maxlength="5">到<input type="text" name="start2_time3" value="{{ $s2[2] }}" placeholder="17:30(五碼)" maxlength="5">
                                <br>
                                3.週<select name="start3_time1" style="height:30px;">
                                    <option value="0">無第三次</option>
                                    <option value="一" {{ $select3_1 }}>一</option>
                                    <option value="二" {{ $select3_2 }}>二</option>
                                    <option value="三" {{ $select3_3 }}>三</option>
                                    <option value="四" {{ $select3_4 }}>四</option>
                                    <option value="五" {{ $select3_5 }}>五</option>
                                </select>
                                <input type="text" name="start3_time2" value="{{ $s3[1] }}" placeholder="16:00(五碼)" maxlength="5">到<input type="text" name="start3_time3" value="{{ $s3[2] }}" placeholder="17:30(五碼)" maxlength="5">
                                <br>
                                4.週<select name="start4_time1" style="height:30px;">
                                    <option value="0">無第四次</option>
                                    <option value="一" {{ $select4_1 }}>一</option>
                                    <option value="二" {{ $select4_2 }}>二</option>
                                    <option value="三" {{ $select4_3 }}>三</option>
                                    <option value="四" {{ $select4_4 }}>四</option>
                                    <option value="五" {{ $select4_5 }}>五</option>
                                </select>
                                <input type="text" name="start4_time2" value="{{ $s4[1] }}" placeholder="16:00(五碼)" maxlength="5">到<input type="text" name="start4_time3" value="{{ $s4[2] }}" placeholder="17:30(五碼)" maxlength="5">
                                <br>
                                5.週<select name="start5_time1" style="height:30px;">
                                    <option value="0">無第五次</option>
                                    <option value="一" {{ $select5_1 }}>一</option>
                                    <option value="二" {{ $select5_2 }}>二</option>
                                    <option value="三" {{ $select5_3 }}>三</option>
                                    <option value="四" {{ $select5_4 }}>四</option>
                                    <option value="五" {{ $select5_5 }}>五</option>
                                </select>
                                <input type="text" name="start5_time2" value="{{ $s5[1] }}" placeholder="16:00(五碼)" maxlength="5">到<input type="text" name="start5_time3" value="{{ $s5[2] }}" placeholder="17:30(五碼)" maxlength="5">
                            </div>
                            <div class="form-group">
                                <label for="place">首次上課集合地點</label>
                                {{ Form::text('place',$club->place,['id'=>'place','class' => 'form-control']) }}
                            </div>
                            <div class="form-group">
                                <label for="people"><strong>開課人數(最少)*</strong></label>
                                {{ Form::number('people',$club->people,['id'=>'people','class' => 'form-control','required'=>'required']) }}
                            </div>
                            <div class="form-group">
                                <label for="taking"><strong>正取人數(最多)*</strong></label>
                                {{ Form::number('taking',$club->taking,['id'=>'taking','class' => 'form-control','required'=>'required']) }}
                            </div>
                            <div class="form-group">
                                <label for="prepare"><strong>候補人數*</strong></label>
                                {{ Form::number('prepare',$club->prepare,['id'=>'prepare','class' => 'form-control','required'=>'required']) }}
                            </div>
                            <div class="form-group">
                                <label for="year_limit"><strong>年級限制*</strong><small class="text-primary">(用小寫,分隔 如：1,2,3,4,5,6 代表這些年級均可)</small></label>
                                {{ Form::text('year_limit',$club->year_limit,['id'=>'year_limit','class' => 'form-control','required'=>'required']) }}
                            </div>
                            <div class="form-group">
                                <label for="ps">備註</label>
                                {{ Form::text('ps',$club->ps,['id'=>'ps','class' => 'form-control']) }}
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
