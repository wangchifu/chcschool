<?php
    use Carbon\Carbon;
    $classrooms = \App\Classroom::where('disable','=',null)->get();
    $i=1;

    $select_sunday = date('Y-m-d');
    $s_cht_week = config("chcschool.s_cht_week");
    $s_class_sections = config("chcschool.s_class_sections");

    $n = date('w',strtotime($select_sunday));
    $sunday = new Carbon($select_sunday);
    $sunday->subDays($n);

    $last_sunday = $sunday->subDays(7)->toDateString();
    $next_sunday = $sunday->addDays(14)->toDateString();

    $sunday->subDays(7);

    $week = [
        '0'=>$sunday->toDateString(),
        '1'=>$sunday->addDay()->toDateString(),
        '2'=>$sunday->addDay()->toDateString(),
        '3'=>$sunday->addDay()->toDateString(),
        '4'=>$sunday->addDay()->toDateString(),
        '5'=>$sunday->addDay()->toDateString(),
        '6'=>$sunday->addDay()->toDateString(),
    ];

?>
<ul class="nav nav-tabs" id="myTab" role="tablist">
    @foreach($classrooms as $classroom)
        <?php $active = ($i==1)?"active":""; ?>
        <li class="nav-item">
            <a class="nav-link {{ $active }}" id="profile-tab" data-toggle="tab" href="#classroom_profile{{ $i }}" role="tab" aria-controls="profile" aria-selected="false">
                {{ $classroom->name }}
            </a>
        </li>
        <?php $i++; ?>
    @endforeach
</ul>
<script>
    //印出陣列
    function print_r(theObj){
        if(theObj.constructor == Array || theObj.constructor == Object){
            document.write("<ul>")
            for(var p in theObj){
                if(theObj[p].constructor == Array || theObj[p].constructor == Object){
                    document.write("<li>["+p+"] => "+typeof(theObj)+"</li>");
                    document.write("<ul>")
                    print_r(theObj[p]);
                    document.write("</ul>")
                } else {
                    document.write("<li>["+p+"] => "+theObj[p]+"</li>");
                }
            }
            document.write("</ul>")
        }
    }

    function change_classroom_order(select_sunday,classroom_id){
        $('#select_sunday').val(select_sunday);
        $('#select_classroom').val(classroom_id);
        $.ajax({
            url: '{{ route('classroom_orders.block_show') }}',
            type : 'post',
            dataType : 'json',
            data : $('#sunday_form').serialize(),
            success : function(result) {
                if(result != 'failed') {
                    document.getElementById('classroom_order_content').innerHTML = get_classroom_order(result);
                }
            },
            error: function(result) {
                alert('失敗');
            }
        })
    }

    function get_classroom_order(result){
        var i =1;
        data = '';
        for(var k in result['classroom_data']){
            if(k == result['select_classroom']){
                data = data + '<div class="tab-pane fade show active" id="classroom_profile'+i+'" role="tabpanel" aria-labelledby="profile-tab" style="margin: 10px;">';
            }else{
                data = data + '<div class="tab-pane fade" id="classroom_profile'+i+'" role="tabpanel" aria-labelledby="profile-tab" style="margin: 10px;">';
            }
            data = data + '<table class="table table-striped table-sm">';
            data = data +'<thead>';
            data = data +'<tr>';
            data = data +'<td rowspan="2">';
            data = data + '<span style="font-size: 16px;" onclick="change_classroom_order(\''+result['last_sunday']+'\',\''+k+'\')"><i class="fas fa-arrow-alt-circle-left text-primary"></i></span>';
            data = data + '</td>';
            for(var k1 in result['week']){
                data = data +'<td>';
                if(k1==0){
                    data = data + '<span class="text-danger">'+result['s_cht_week'][k1]+'</span>';
                }else if(k1==6){
                    data = data + '<span class="text-success">'+result['s_cht_week'][k1]+'</span>';
                }else{
                    data = data + '<span>'+result['s_cht_week'][k1]+'</span>';
                }
                data = data +'</td>';
            }
            data = data +'<td rowspan="2">';
            data = data + '<span style="font-size: 16px;" onclick="change_classroom_order(\''+result['next_sunday']+'\',\''+k+'\')"><i class="fas fa-arrow-alt-circle-right text-primary"></i></span>';
            data = data + '</td>';
            data = data +'</tr>';
            data = data +'<tr>';
            for(var k1 in result['week']){
                data = data +'<td>';
                if(result['today'] == result['week'][k1].substring(5,10)){
                    style = "text-decoration: underline solid green 5px";
                }else{
                    style = "";
                }
                if(k1==0){
                    data = data + '<span class="text-danger" style="'+style+'">'+result['week'][k1].substring(5,10)+'</span>';
                }else if(k1==6){
                    data = data + '<span class="text-success" style="'+style+'">'+result['week'][k1].substring(5,10)+'</span>';
                }else{
                    data = data + '<span class="" style="'+style+'">'+result['week'][k1].substring(5,10)+'</span>';
                }
                data = data +'</td>';
            }
            data = data + '</tr>';
            data = data + '</thead>';
            data = data + '<tbody>';

            for(var k1 in result['s_class_sections']){
                data = data +'<tr>';
                data = data + '<td>'+result['s_class_sections'][k1]+'</td>'

                for(var k2 in result['week']){
                    data = data + '<td>';
                    if(result['has_order'][result['week'][k2]][k1][k] != ""){
                        data = data+ '<i class="fas fa-user text-danger" onclick="alert(\'被 '+result['has_order'][result['week'][k2]][k1][k]+' 預約了\')"></i>';
                    }
                    if(result['can_not_order'][result['week'][k2]][k1][k] == "1"){
                        data = data+ '<span onclick="alert(\'無法預約\')">-</span>';
                    }
                    //data = data+ result['has_order']['2021-08-10']['10']['6'];
                    data = data + '</td>';
                }
                data = data + '<td>';
                data = data + '</td>';
                data = data + '</tr>';
            }

            data = data + '</tbody>';
            data = data + '</table>';
            data = data +'<a href="./classroom_orders/'+k+'/show/'+result['today2']+'" class="btn btn-success">前往預約 '+result['classroom_data'][k]+'</a>';
            data = data + '</div>';
            i++;
        }
        data = data + '';
        return data;
    }
</script>
{{ Form::open(['route' => 'classroom_orders.block_show', 'method' => 'POST','id'=>'sunday_form','onsubmit'=>'return false']) }}
<input type="hidden" name="select_sunday" id="select_sunday">
<input type="hidden" name="select_classroom" id="select_classroom">
{{ Form::close() }}
<div class="tab-content" id="classroom_order_content">
    <?php $i=1; ?>
    @foreach($classrooms as $classroom)
            <?php
            $active = ($i==1)?"show active":"";
            $check_orders = \App\ClassroomOrder::where('classroom_id',$classroom->id)
                ->get();
            $has_order = [];
            foreach($check_orders as $check_order){
                $has_order[$check_order->order_date][$check_order->section]['id'] = $check_order->user_id;
                $has_order[$check_order->order_date][$check_order->section]['user_name'] = $check_order->user->name;
            }
            ?>
        <div class="tab-pane fade {{ $active }}" id="classroom_profile{{ $i }}" role="tabpanel" aria-labelledby="profile-tab" style="margin: 10px;">
            <div class="table-responsive">
            <table class="table table-striped table-sm">
                <thead>
                <tr>
                    <td rowspan="2">
                        <span style="font-size: 16px;" onclick="change_classroom_order('{{ $last_sunday }}','{{ $classroom->id }}')"><i class="fas fa-arrow-alt-circle-left text-primary"></i></span>
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
                            <span class="{{ $font }}">{{ $s_cht_week[$k] }}</span>
                        </td>
                    @endforeach
                    <td rowspan="2">
                        <span style="font-size: 16px;" onclick="change_classroom_order('{{ $next_sunday }}','{{ $classroom->id }}')"><i class="fas fa-arrow-alt-circle-right text-primary"></i></span>
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
                        <?php $style = ($v==date('Y-m-d'))?"text-decoration: underline solid green 5px;":""; ?>
                        <td>
                            <span class="{{ $font }}" style="{{ $style }}">{{ substr($v,5,5) }}</span>
                        </td>
                    @endforeach
                </tr>
                </thead>
                <tbody>
                @foreach($s_class_sections as $k1=>$v1)
                    <tr>
                        <td>{{ $v1 }}</td>
                        @foreach($week as $k2 => $v2)
                            <td>
                                @if(empty($has_order[$v2][$k1]['id']))
                                    @if(strpos($classroom->close_sections, "'".$k2."-".$k1."'") !== false)
                                        <span onclick="alert('無法預約');">-</span>
                                    @endif
                                @else
                                    <i class="fas fa-user text-danger" onclick="alert('被 {{ $has_order[$v2][$k1]['user_name'] }} 預約了');"></i>
                                @endif
                            </td>
                        @endforeach
                        <td></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            </div>
            <a href="{{ route('classroom_orders.show',[$classroom->id,date('Y-m-d')]) }}" class="btn btn-success">前往預約 {{ $classroom->name }}</a>
        </div>
        <?php $i++; ?>
    @endforeach
</div>
