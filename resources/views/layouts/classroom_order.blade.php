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
        <li class="nav-item" role="presentation">
            <a class="nav-link {{ $active }}" id="tab-classroom-{{ $i }}" data-toggle="tab" href="#classroom_profile{{ $i }}" role="tab" aria-controls="classroom_profile{{ $i }}" aria-selected="{{ $i==1 ? 'true' : 'false' }}">
                {{ $classroom->name }}
            </a>
        </li>
        <?php $i++; ?>
    @endforeach
</ul>

<script>
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
        var i = 1;
        data = '';
        for(var k in result['classroom_data']){
            if(k == result['select_classroom']){
                data += '<div class="tab-pane fade show active" id="classroom_profile'+i+'" role="tabpanel" aria-labelledby="tab-classroom-'+i+'" style="margin: 10px;">';
            }else{
                data += '<div class="tab-pane fade" id="classroom_profile'+i+'" role="tabpanel" aria-labelledby="tab-classroom-'+i+'" style="margin: 10px;">';
            }
            data += '<div class="table-responsive">';
            data += '<table class="table table-striped table-sm" aria-label="教室預約課表">';
            data += '<thead><tr>';
            
            // 無障礙 HM1240401C & CS2140401C 修正：改用可點擊且有 title/aria-label 的按鈕，16px 改為 1rem
            data += '<td rowspan="2">';
            data += '<button type="button" class="btn btn-link p-0" style="font-size: 1rem;" onclick="change_classroom_order(\''+result['last_sunday']+'\',\''+k+'\')" title="切換至上一週" aria-label="切換至上一週">';
            data += '<i class="fas fa-arrow-alt-circle-left text-primary" aria-hidden="true"></i>';
            data += '</button>';
            data += '</td>';

            for(var k1 in result['week']){
                data += '<td>';
                if(k1==0){
                    data += '<span class="text-danger">'+result['s_cht_week'][k1]+'</span>';
                }else if(k1==6){
                    data += '<span class="text-success">'+result['s_cht_week'][k1]+'</span>';
                }else{
                    data += '<span>'+result['s_cht_week'][k1]+'</span>';
                }
                data += '</td>';
            }

            data += '<td rowspan="2">';
            data += '<button type="button" class="btn btn-link p-0" style="font-size: 1rem;" onclick="change_classroom_order(\''+result['next_sunday']+'\',\''+k+'\')" title="切換至下一週" aria-label="切換至下一週">';
            data += '<i class="fas fa-arrow-alt-circle-right text-primary" aria-hidden="true"></i>';
            data += '</button>';
            data += '</td>';
            data += '</tr><tr>';

            for(var k1 in result['week']){
                data += '<td>';
                var style = (result['today'] == result['week'][k1].substring(5,10)) ? "text-decoration: underline solid green 5px" : "";
                if(k1==0){
                    data += '<span class="text-danger" style="'+style+'">'+result['week'][k1].substring(5,10)+'</span>';
                }else if(k1==6){
                    data += '<span class="text-success" style="'+style+'">'+result['week'][k1].substring(5,10)+'</span>';
                }else{
                    data += '<span style="'+style+'">'+result['week'][k1].substring(5,10)+'</span>';
                }
                data += '</td>';
            }
            data += '</tr></thead><tbody>';

            for(var k1 in result['s_class_sections']){
                data += '<tr>';
                data += '<td>'+result['s_class_sections'][k1]+'</td>';

                for(var k2 in result['week']){
                    data += '<td>';
                    if(result['has_order'][result['week'][k2]][k1][k] != ""){
                        data += '<button type="button" class="btn btn-link p-0 border-0" onclick="alert(\'被 '+result['has_order'][result['week'][k2]][k1][k]+' 預約了\')" title="預約狀態：被 '+result['has_order'][result['week'][k2]][k1][k]+' 預約了" aria-label="預約狀態：被 '+result['has_order'][result['week'][k2]][k1][k]+' 預約了">';
                        data += '<i class="fas fa-user text-danger" aria-hidden="true"></i>';
                        data += '</button>';
                    }
                    if(result['can_not_order'][result['week'][k2]][k1][k] == "1"){
                        data += '<span title="無法預約" aria-label="無法預約" style="cursor:pointer;" onclick="alert(\'無法預約\')">-</span>';
                    }
                    data += '</td>';
                }
                data += '<td></td></tr>';
            }

            data += '</tbody></table></div>';
            data += '<a href="./classroom_orders/'+k+'/show/'+result['today2']+'" class="btn btn-success" title="前往預約 '+result['classroom_data'][k]+'" aria-label="前往預約 '+result['classroom_data'][k]+'">前往預預 '+result['classroom_data'][k]+'</a>';
            data += '</div>';
            i++;
        }
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
        $check_orders = \App\ClassroomOrder::where('classroom_id',$classroom->id)->get();
        $has_order = [];
        foreach($check_orders as $check_order){
            $has_order[$check_order->order_date][$check_order->section]['id'] = $check_order->user_id;
            $has_order[$check_order->order_date][$check_order->section]['user_name'] = $check_order->user->name;
        }
        ?>
        <div class="tab-pane fade {{ $active }}" id="classroom_profile{{ $i }}" role="tabpanel" aria-labelledby="tab-classroom-{{ $i }}" style="margin: 10px;">
            <div class="table-responsive">
            <table class="table table-striped table-sm" aria-label="{{ $classroom->name }} 週預約課表">
                <thead>
                <tr>
                    <td rowspan="2">
                        {{-- 無障礙 HM1240401C & CS2140401C：16px 改為 1rem，將 span 改為 button --}}
                        <button type="button" class="btn btn-link p-0" style="font-size: 1rem;" onclick="change_classroom_order('{{ $last_sunday }}','{{ $classroom->id }}')" title="切換至上一週" aria-label="切換至上一週">
                            <i class="fas fa-arrow-alt-circle-left text-primary" aria-hidden="true"></i>
                        </button>
                    </td>
                    @foreach($week as $k => $v)
                        <?php
                        $font="";
                        if($k=="0") $font="text-danger";
                        if($k=="6") $font="text-success";
                        ?>
                        <td>
                            <span class="{{ $font }}">{{ $s_cht_week[$k] }}</span>
                        </td>
                    @endforeach
                    <td rowspan="2">
                        <button type="button" class="btn btn-link p-0" style="font-size: 1rem;" onclick="change_classroom_order('{{ $next_sunday }}','{{ $classroom->id }}')" title="切換至下一週" aria-label="切換至下一週">
                            <i class="fas fa-arrow-alt-circle-right text-primary" aria-hidden="true"></i>
                        </button>
                    </td>
                </tr>
                <tr>
                    @foreach($week as $k => $v)
                        <?php
                        $font="";
                        if($k=="0") $font="text-danger";
                        if($k=="6") $font="text-success";
                        $style = ($v==date('Y-m-d'))?"text-decoration: underline solid green 5px;":"";
                        ?>
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
                                        <span style="cursor:pointer;" onclick="alert('無法預約');" title="無法預約" aria-label="無法預約">-</span>
                                    @endif
                                @else
                                    <button type="button" class="btn btn-link p-0 border-0" onclick="alert('被 {{ $has_order[$v2][$k1]['user_name'] }} 預約了');" title="預約狀態：被 {{ $has_order[$v2][$k1]['user_name'] }} 預約了" aria-label="預約狀態：被 {{ $has_order[$v2][$k1]['user_name'] }} 預約了">
                                        <i class="fas fa-user text-danger" aria-hidden="true"></i>
                                    </button>
                                @endif
                            </td>
                        @endforeach
                        <td></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            </div>
            <a href="{{ route('classroom_orders.show',[$classroom->id,date('Y-m-d')]) }}" class="btn btn-success" title="前往預約 {{ $classroom->name }}" aria-label="前往預約 {{ $classroom->name }}">前往預約 {{ $classroom->name }}</a>
        </div>
        <?php $i++; ?>
    @endforeach
</div>