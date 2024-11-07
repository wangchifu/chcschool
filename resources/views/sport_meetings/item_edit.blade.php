@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '運動會報名')

@section('content')
    <?php
    $active['admin'] ="active";
    $active['show'] ="";    
    $active['list'] ="";
    $active['score'] ="";
    $active['teacher'] ="";
    ?>
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>運動會報名-修改比賽項目</h1>
            @include('sport_meetings.nav')
            <hr>
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('sport_meeting.action') }}">1.報名任務</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="{{ route('sport_meeting.admin') }}">2.學生資料</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="{{ route('sport_meeting.user') }}">3.教師帳號</a>
                  </li>  
              </ul>            
              <div class="card">
                <div class="card-body">
                  @if($admin)
                  <h2>{{ $item->action->name }}</h2>
                  <form action="{{ route('sport_meeting.item_update',$item->id) }}" method="post">
                    @csrf
                    @method('patch')
                    <div class="form-row">
                        <div class="form-check form-check-inline">
                            <?php
                                $check1 = ($item->game_type == "personal")?"checked":null;
                                $check2 = ($item->game_type == "group")?"checked":null;
                                $check3 = ($item->game_type == "class")?"checked":null;
                            ?>
                            <input class="form-check-input" type="radio" id="personal" value="personal" name="game_type" {{ $check1 }} onchange="hide()">
                            <label class="form-check-label" for="personal"><i class="fas fa-user"></i> 個人賽</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="group" value="group" name="game_type" {{ $check2 }} onchange="show()">
                            <label class="form-check-label" for="group"><i class="fas fa-users"></i> 團體賽</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="class" value="class" name="game_type" {{ $check3 }} onchange="hide2()">
                            <label class="form-check-label" for="class"><i class="fas fa-school"></i> 班際賽</label>
                        </div>
                    </div>
                    <?php
                        $style1 = ($item->game_type <> "group")?"display:none;":"";
                        $style2 = ($item->game_type == "class")?"display:none;":"";
                        $style3 = ($item->game_type == "class")?"display:none;":"";
                    ?>
                    <div class="form-row" style="{{ $style1 }}" id="official_reserve">
                        <div class="form-group col-md-6">
                            <label for="order">正式選手數</label>
                            <input type="number" class="form-control" id="official" name="official" placeholder="數字" value="{{ $item->official }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="order">預備選手數</label>
                            <input type="number" class="form-control" id="reserve" name="reserve" placeholder="數字" value="{{ $item->reserve }}">
                        </div>
                    </div>
                    <hr>
                    <div class="form-row">
                        <div class="form-group col-md-1">
                            <label for="order">排序</label>
                            <input type="text" class="form-control" id="order" name="order" placeholder="數字" value="{{ $item->order }}">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="name">名稱<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="名稱" required value="{{ $item->name }}">
                        </div>
                        <div class="form-group col-md-3" id="sex_select" style="{{ $style2 }}">
                            <?php
                            $g1 =($item->group==1)?"selected":null;
                            $g2 =($item->group==2)?"selected":null;
                            $g3 =($item->group==3)?"selected":null;
                            $g4 =($item->group==4)?"selected":null;
                            ?>
                            <label for="group">男女組别<span class="text-danger">*</span></label>
                            <select id="group" class="form-control" name="group">
                                <option value="3" {{ $g3 }}>男子組+女子組</option>
                                <option value="1" {{ $g1 }}>男子組</option>
                                <option value="2" {{ $g2 }}>女子組</option>
                                <option value="4" {{ $g4 }}>不分性別</option>
                            </select>
                        </div>
                        <div class="form-group col-md-2" id="people_select" style="{{ $style3 }}">
                            <?php
                            $p1 = ($item->people==1)?"selected":null;
                            $p2 = ($item->people==2)?"selected":null;
                            $p3 = ($item->people==3)?"selected":null;
                            $p4 = ($item->people==4)?"selected":null;
                            $p5 = ($item->people==5)?"selected":null;
                            $p6 = ($item->people==6)?"selected":null;
                            $p7 = ($item->people==7)?"selected":null;
                            $p8 = ($item->people==8)?"selected":null;
                            $p9 = ($item->people==9)?"selected":null;
                            $p10 = ($item->people==10)?"selected":null;
                            ?>
                            <label for="people">每班每組派幾個(隊)<span class="text-danger">*</span></label>
                            <select id="people" class="form-control" name="people">
                                <option value="1" {{ $p1 }}>1</option>
                                <option value="2" {{ $p2 }}>2</option>
                                <option value="3" {{ $p3 }}>3</option>
                                <option value="4" {{ $p4 }}>4</option>
                                <option value="5" {{ $p5 }}>5</option>
                                <option value="6" {{ $p6 }}>6</option>
                                <option value="7" {{ $p7 }}>7</option>
                                <option value="8" {{ $p8 }}>8</option>
                                <option value="9" {{ $p9 }}>9</option>
                                <option value="10" {{ $p10 }}>10</option>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="reward">錄取名次<span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="reward" name="reward" placeholder="數字" required value="{{ $item->reward }}">
                        </div>
                        <div class="form-group col-md-1">
                            <?php
                            $t1 = null;
                            $t2 = null;
                            $t3 = null;
                            if($item->type==1) $t1 = "selected";
                            if($item->type==2) $t2 = "selected";
                            if($item->type==3) $t3 = "selected";
                            ?>
                            <label for="sex">類别<span class="text-danger">*</span></label>
                            <select id="sex" class="form-control" name="type">
                                <option value="1" {{ $t1 }}>徑賽</option>
                                <option value="2" {{ $t2 }}>田賽</option>
                                <option value="3" {{ $t3 }}>其他</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="limit" name="limit" @if($item->limit) checked @endif>
                            <label class="form-check-label" for="limit">限制選手參賽項目</label>
                        </div>
                    </div>
                    <div class="form-row">
                        <?php
                            $years = unserialize($item->years);
                        ?>
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="u1" name="years[]"  @if(in_array('幼小',$years)) checked @endif value="幼小">
                            <label class="form-check-label" for="u1">幼小　</label>
                        </div>
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="u2" name="years[]"  @if(in_array('幼中',$years)) checked @endif value="幼中">
                            <label class="form-check-label" for="u2">幼中　</label>
                        </div>
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="u3" name="years[]"  @if(in_array('幼大',$years)) checked @endif value="幼大">
                            <label class="form-check-label" for="u3">幼大　</label>
                        </div>
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="y1" name="years[]" @if(in_array(1,$years)) checked @endif value="1">
                            <label class="form-check-label" for="y1">一年級　</label>
                        </div>
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="y2" name="years[]" @if(in_array(2,$years)) checked @endif value="2">
                            <label class="form-check-label" for="y2">二年級　</label>
                        </div>
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="y3" name="years[]" @if(in_array(3,$years)) checked @endif value="3">
                            <label class="form-check-label" for="y3">三年級　</label>
                        </div>
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="y4" name="years[]" @if(in_array(4,$years)) checked @endif value="4">
                            <label class="form-check-label" for="y4">四年級　</label>
                        </div>
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="y5" name="years[]" @if(in_array(5,$years)) checked @endif value="5">
                            <label class="form-check-label" for="y5">五年級　</label>
                        </div>
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="y6" name="years[]" @if(in_array(6,$years)) checked @endif value="6">
                            <label class="form-check-label" for="y6">六年級　</label>
                        </div>
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="y7" name="years[]" @if(in_array(7,$years)) checked @endif value="7">
                            <label class="form-check-label" for="y7">七年級　</label>
                        </div>
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="y8" name="years[]" @if(in_array(8,$years)) checked @endif value="8">
                            <label class="form-check-label" for="y8">八年級　</label>
                        </div>
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="y9" name="years[]" @if(in_array(9,$years)) checked @endif value="9">
                            <label class="form-check-label" for="y9">九年級　</label>
                        </div>
                    </div>
                    <a href="{{ route('sport_meeting.item',$item->action_id) }}" class="btn btn-secondary">返回</a>
                    <button type="submit" class="btn btn-primary" onclick="return confirm('確定嗎？')">儲存</button>
                </form>
                  @endif
                </div>
              </div>        
        </div>        
    </div>
    <script>
        function show(){
            $('#official_reserve').show();
            $('#sex_select').show();
            $('#people_select').show();
        }
        function hide(){
            $('#official_reserve').hide();
            $('#sex_select').show();
            $('#people_select').show();
        }
        function hide2(){
            $('#official_reserve').hide();
            //document.getElementById('people').value = 1;
            //document.getElementById('sex').value = 4;
            $('#sex_select').hide();
            $('#people_select').hide();
        }
    </script>    
@endsection
