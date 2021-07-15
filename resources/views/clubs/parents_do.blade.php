@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '社團報名-')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>{{ $user->semester }}學期 社團報名</h1>

            @include('clubs.nav')

            <br>
            @if($user->parents_telephone)
                <div class="card">
                <div class="card-header">
                    <form name=myform>
                    <div class="form-group">
                        <label>
                            <strong class="text-danger">請選擇：可下拉選單選擇報名類別</strong>
                        </label>
                        {{ Form::select('class_id', $club_classes,$class_id, ['id'=>'class_id','class' => 'form-control','onchange'=>'jump()']) }}
                    </div>
                    </form>
                    <h3>
                        @if($class_id==1)
                            學生特色社團
                        @elseif($class_id==2)
                            學生課後活動
                        @endif
                    </h3>
                    <strong class="text-primary">可報 {{ $club_semester->club_limit }} 社團</strong>
                </div>
                <style>
                    .blink {
                        animation-duration: 1s;
                        animation-name: blink;
                        animation-iteration-count: infinite;
                        animation-direction: alternate;
                        animation-timing-function: ease-in-out;
                    }
                    @keyframes blink {
                        from {
                            opacity: 1;
                        }
                        to {
                            opacity: 0;
                        }
                    }
                </style>
                <div class="card-body">
                    @if($errors->any())
                        <div class="form-group">
                            <h5>
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li class="text-danger blink">
                                            {{ $error }}
                                    </li>
                                @endforeach
                            </ul>
                            </h5>
                        </div>
                    @endif
                    <table class="table table-hover">
                        <tr>
                            <th>
                                編號
                            </th>
                            <th>
                                名稱
                            </th>
                            <th>
                                收費
                            </th>
                            <th>
                                上課時間
                            </th>
                            <th>
                                年級限制
                            </th>
                            <th>
                                最少人數
                            </th>
                            <th>
                                正取/候補
                            </th>
                            <th>
                                已報
                            </th>
                            <th>
                                動作
                            </th>
                        </tr>
                        <?php
                        $check_num = \App\ClubRegister::where('semester',$user->semester)
                            ->where('club_student_id',$user->id)
                            ->where('class_id',$class_id)
                            ->count();
                        ?>
                        @foreach($clubs as $club)
                            <tr>
                                <td>
                                    {{ $club->no }}
                                </td>
                                <td>
                                    <a href="{{ route('clubs.show_club',$club->id) }}" class="btn btn-info btn-sm">
                                        {{ $club->name }}
                                    </a>
                                </td>
                                <td>
                                    {{ $club->money }}
                                </td>
                                <td>
                                    {{ $club->start_time }}
                                </td>
                                <td>
                                    {{ $club->year_limit }}
                                </td>
                                <th>
                                    {{ $club->people }}
                                </th>
                                <th>
                                    {{ $club->taking }} / {{ $club->prepare }}
                                </th>
                                <th>
                                    <?php
                                        $count_num = \App\ClubRegister::where('semester',$user->semester)
                                            ->where('club_id',$club->id)
                                            ->count();
                                    ?>
                                    <a href="{{ route('clubs.sign_show',['club_id'=>$club->id,'class_id'=>$class_id]) }}" class="badge badge-info">{{ $count_num }}</a>
                                </th>
                                <td>
                                    <?php
                                    $club_register = \App\ClubRegister::where('semester',$user->semester)
                                        ->where('club_id',$club->id)
                                        ->where('club_student_id',$user->id)
                                        ->first();
                                    ?>
                                    @if(empty($club_register) and $check_num < $club_semester->club_limit and $count_num < ($club->taking+$club->prepare))
                                        <a href="{{ route('clubs.sign_up',$club->id) }}" class="btn btn-success btn-sm" onclick="return confirm('確定報名？')"><i class="fas fa-plus-circle"></i> 報名</a>
                                    @elseif($club_register)
                                        <?php
                                            $taking = $club->taking;
                                            $prepare = $club->prepare;
                                            $club_registers = \App\ClubRegister::where('semester',$user->semester)
                                                ->where('club_id',$club->id)
                                                ->orderBy('created_at')
                                                ->get();
                                            $i=1;
                                            foreach($club_registers as $club_register){
                                                if($club_register->club_student_id == $user->id){
                                                    $my_order = $i;
                                                }
                                                $i++;
                                            }

                                            if($my_order<=$taking){
                                                $order = "正取".$my_order;
                                            }

                                            if($my_order > $taking and $my_order <= ($taking+$prepare)){
                                                $order = "候補".($my_order-$taking);
                                            }
                                        ?>
                                        <span class="text-success">已報名({{ $order }})</span><a href="{{ route('clubs.sign_down',$club->id) }}" onclick="return confirm('確定取消報名？')"><i class="fas fa-times-circle text-danger"></i></a>
                                    @else
                                        <span class="text-secondary">---</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
            @else
                <div class="card">
                    <div class="card-header">
                        <h4>
                            資料蒐集同意
                        </h4>
                    </div>
                    <div class="card-body">
                        為了平時聯絡或發生殊殊狀況時的聯繫，社團老師必須有學生家長的聯絡電話或手機號碼，請填入「家長電話」並同意此資料的蒐集，才能操作本系統。
                        <br>
                        <br>
                        {{ Form::open(['route' => ['clubs.get_telephone',$user->id], 'method' => 'POST']) }}
                        <div class="form-group">
                            <label for="parents_telephone"><strong>家長電話*</strong></label>
                            {{ Form::text('parents_telephone',null,['id'=>'parents_telephone','class' => 'form-control','placeholder'=>'請在此填入電話','maxlength'=>'10','required'=>'required']) }}
                        </div>
                        <small class="text-secondary">(錯誤資料將會影響報名資格，請留下正確聯絡電話)</small>
                        <br>
                        <br>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="exampleCheck1" required>
                            <label class="form-check-label text-danger" for="exampleCheck1">同意報名成功正取後，若無故不繳費而佔名額，可能被處罰下學期無法報名</label>
                        </div>
                        <br>
                        <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('確定儲存嗎？')">
                            <i class="fas fa-save"></i> 同意資料蒐集
                        </button>
                        <input type="hidden" name="class_id" value="{{ $class_id }}">
                        {{ Form::close() }}
                    </div>
                </div>
            @endif

        </div>
    </div>
    <script>
        function jump(){
            if(document.myform.class_id.options[document.myform.class_id.selectedIndex].value!=''){
                location= document.myform.class_id.options[document.myform.class_id.selectedIndex].value;
            }
        }
    </script>
@endsection
