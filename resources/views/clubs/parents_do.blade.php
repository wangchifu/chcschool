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
                    <h4>
                        社團列表
                    </h4>
                    <small>可報 {{ $club_semester->club_limit }} 社團</small>
                </div>
                <div class="card-body">
                    @include('layouts.errors')
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
                                正取/備取
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
                                    <a href="{{ route('clubs.sign_show',$club->id) }}" class="badge badge-info">{{ $count_num }}</a>
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
                                            for($n=1;$n<=($taking+$prepare);$n++){
                                                if($my_order<=$taking){
                                                    $order = "正取".$n;
                                                    break;
                                                }
                                                if($my_order<=($taking+$prepare)){
                                                    $order = "備取".($my_order-$taking);
                                                    break;
                                                }
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
                        為了平時聯絡或發生殊殊狀況時的聯繫，社團老師必須有學生家長的聯絡電話，請填入「家長電話」並同意此資料的蒐集，才能操作本系統。
                        <br>
                        <br>
                        {{ Form::open(['route' => ['clubs.get_telephone',$user->id], 'method' => 'POST']) }}
                        <div class="form-group">
                            <label for="parents_telephone"><strong>家長電話*</strong></label>
                            {{ Form::text('parents_telephone',null,['id'=>'parents_telephone','class' => 'form-control','placeholder'=>'請在此填入電話','maxlength'=>'10','required'=>'required']) }}
                        </div>
                        <small class="text-secondary">(錯誤資料將會影響報名資格，請留下正確聯絡電話)</small>
                        <br>
                        <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('確定儲存嗎？')">
                            <i class="fas fa-save"></i> 同意資料蒐集
                        </button>
                        {{ Form::close() }}
                    </div>
                </div>
            @endif

        </div>
    </div>
@endsection
