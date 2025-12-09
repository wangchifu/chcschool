@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '運動會報名')

@section('content')
    <?php
    $active['admin'] ="";
    $active['show'] ="active";    
    $active['list'] ="";
    $active['score'] ="";
    $active['teacher'] ="";
    ?>
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>運動會報名-歷屆成績</h1>
            @include('sport_meetings.nav')
            <form name="myform">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        {{ Form::select('select_action', $action_array, $select_action, ['class' => 'form-control','onchange'=>'jump()']) }}
                    </div>
                </div>
            </form>
            <table class="table table-striped">
                <thead class="table-primary">
                <tr>
                    <td>

                    </td>
                    @foreach($items as $item)
                        <td>
                            {{ $item->name }}
                        </td>
                    @endforeach
                </tr>
                </thead>
                <tbody>
                @foreach($student_classes as $student_class)
                    <tr>
                        <td>
                            {{ $student_class->student_year }}年{{ $student_class->student_class }}班
                        </td>
                        @foreach($items as $item)
                            <?php
                            $years_array = unserialize($item->years);
                            $student_signs = \App\StudentSign::where('item_id',$item->id)
                                ->where('student_year',$student_class->student_year)
                                ->where('student_class',$student_class->student_class)
                                ->orderBy('sex','DESC')
                                ->get();
                            $rankings = config('chcschool.rankings');
                            ?>
                            <td>
                                @if(in_array($student_class->student_year,$years_array) and count($student_signs)==0)
                                    未報名
                                @endif
                                @if(!in_array($student_class->student_year,$years_array))
                                    --
                                @endif
                                @foreach($student_signs as $student_sign)
                                    <?php
                                        if($student_sign->sex == "男") $color="text-primary";
                                        if($student_sign->sex == "女") $color = "text-danger";
                                        if($student_sign->sex == "4") $color = "text-info";
                                    ?>
                                    <span class="{{ $color }}">
                                        @if($item->game_type == "personal")
                                            {{ $student_sign->student->name }}
                                        @endif
                                        @if($item->game_type == "group")
                                            {{ $student_sign->student->name }}
                                        @endif
                                        @if($item->game_type == "class")
                                            全班
                                        @endif
                                        @if($student_sign->achievement or $student_sign->ranking)
                                            @if(isset($rankings[$student_sign->ranking]))
                                                [{{ $student_sign->achievement }} {{ $rankings[$student_sign->ranking] }}]
                                            @endif
                                        @endif
                                    </span>
                                    <br>
                                @endforeach
                            </td>
                        @endforeach
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

    </div>
    <script>
        function jump(){
            if(document.myform.select_action.options[document.myform.select_action.selectedIndex].value!=''){
                location="/sport_meeting/index/" + document.myform.select_action.options[document.myform.select_action.selectedIndex].value;
            }
        }
    </script>
@endsection
