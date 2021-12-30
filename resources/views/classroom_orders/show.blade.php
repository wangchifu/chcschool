@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '教室預約 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>
                教室預約
            </h1>
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('classroom_orders.index') }}">教室預約</a>
                </li>
                @if($classroom_admin)
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('classroom_orders.admin') }}">教室管理</a>
                    </li>
                @endif
                <li class="nav-item">
            </ul>
            <?php
            $cht_week = config("chcschool.cht_week");
            $class_sections = config("chcschool.class_sections");
            ?>
            <br>
            <h2>{{ $classroom->name }}</h2>
            <table class="table table-striped">
                <thead>
                <tr>
                    <td rowspan="2">
                        <h3><a href="{{ route('classroom_orders.show',['classroom_id'=>$classroom->id,'select_sunday'=>$last_sunday]) }}"><i class="fas fa-arrow-alt-circle-left"></i></a></h3>
                    </td>
                    @foreach($week as $k => $v)
                        <?php
                        $font="";
                        $bg="";
                        if($k=="0"){
                            $font="text-danger";
                            $bg = "red";
                        }
                        if($k=="6"){
                            $font="text-success";
                            $bg = "green";
                        }
                        ?>
                        <td>
                            <span class="{{ $font }}">{{ $cht_week[$k] }}</span>
                        </td>
                    @endforeach
                    <td rowspan="2">
                        <h3><a href="{{ route('classroom_orders.show',['classroom'=>$classroom->id,'select_sunday'=>$next_sunday]) }}"><i class="fas fa-arrow-alt-circle-right"></i></a></h3>
                    </td>
                </tr>
                <tr>
                    @foreach($week as $k => $v)
                        <?php
                        $font="";
                        if($k=="0"){
                            $font="text-danger";
                        }
                        if($k=="6"){
                            $font="text-success";
                        }
                        ?>
                        <?php $class = ($v==date('Y-m-d'))?"btn btn-info btn-sm":""; ?>
                        <td>
                            <span class="{{ $class }} {{ $font }}">{{ $v }}</span>
                        </td>
                    @endforeach
                </tr>
                </thead>
                <tbody>
                @foreach($class_sections as $k1=>$v1)
                    <tr>
                        <td>{{ $v1 }}</td>
                        @foreach($week as $k2 => $v2)
                            <td>
                                @if(empty($has_order[$v2][$k1]['id']))
                                    @if(strpos($classroom->close_sections, "'".$k2."-".$k1."'") !== false)
                                        -
                                    @else
                                        @if(str_replace('-','',$v2) < date('Ymd'))
                                            逾期
                                        @else
                                            <a href="{{ route('classroom_orders.select',['classroom_id'=>$classroom->id,'section'=>$k1,'order_date'=>$v2]) }}"
                                               class="btn btn-secondary btn-sm"
                                               id="s{{ $k1 }}{{ $k2 }}" onclick="return confirm('確定預約{{ $classroom->name }} {{ $v2 }} {{ $v1 }} 嗎？')">
                                                <i class="fas fa-check-circle"></i> 選我</a>
                                        @endif
                                    @endif
                                @else
                                    {{ $has_order[$v2][$k1]['user_name'] }}
                                    @if(auth()->user()->id == $has_order[$v2][$k1]['id'] and str_replace('-','',$v2) >= date('Ymd'))
                                        <a href="#" onclick="if(confirm('確定刪除 {{ $classroom->name }} {{ $v2 }} {{ $v1 }} 的預約？')) go_delete{{ $k1 }}{{ $k2 }}(); else return false;"><i class="fas fa-times-circle text-danger"></i></a>
                                        {{ Form::open(['route' => 'classroom_orders.destroy', 'method' => 'DELETE','id'=>'delete'.$k1.$k2,'onsubmit'=>'return false;']) }}
                                        <input type="hidden" name="classroom_id" value="{{ $classroom->id }}">
                                        <input type="hidden" name="order_date" value="{{ $v2 }}">
                                        <input type="hidden" name="section" value="{{ $k1 }}">
                                        {{ Form::close() }}
                                        <script>
                                            function go_delete{{ $k1 }}{{ $k2 }}(){
                                                document.getElementById('delete{{ $k1 }}{{ $k2 }}').submit();
                                            }
                                        </script>
                                    @endif
                                @endif
                            </td>
                        @endforeach
                        <td></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
