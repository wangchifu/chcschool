<script src="{{ asset('gijgo/js/gijgo.min.js') }}" type="text/javascript"></script>
<link href="{{ asset('gijgo/css/gijgo.min.css') }}" rel="stylesheet" type="text/css">
<?php
    $today = date('Y-m-d');
    $lunch_today = \App\LunchToday::find(4);
    $s = get_url("https://fatraceschool.k12ea.gov.tw/offered/meal?SchoolId=".$lunch_today->school_id."&KitchenId=all&period=".$today);
    $lunch_datas = json_decode($s,true);

    if(isset($lunch_datas['result'])){
        if($lunch_datas['result'] ==1){
            $link = "連線成功";
            if(empty($lunch_datas['data'])){
                $has_data = "此日無資料";
            }else{
                $has_data = "此日有資料";
                foreach($lunch_datas['data'] as $lunch_data){
                    $kitchen_datas[$lunch_data['KitchenName']][$lunch_data['MenuTypeName']] =$lunch_data['BatchDataId'];
                }
                ksort($kitchen_datas);
            }
        }else{
            $link = "連線不成功";
        }
    }else{
        $link = "連線不成功";
    }


?>
@if($link=="連線成功")
    <table>
        <tr>
            <td>
                <strong>
                    {{ $school_name = $lunch_today->school_name }}
                </strong>
            </td>
            <td>
                {{ Form::open(['route' => 'lunch_todays.return_date'.$lunch_today->id, 'method' => 'POST','id'=>'date_form'.$lunch_today->id,'onsubmit'=>'return false']) }}
                <input id="date{{ $lunch_today->id }}" name="date{{ $lunch_today->id }}" required maxlength="10" value="{{ $today }}">
                <input type="hidden" name="school_id" value="{{ $lunch_today->school_id }}">
                {{ Form::close() }}

                <script src="{{ asset('gijgo/js/messages/messages.zh-TW.js') }}"></script>
                <script>
                    $('#date{{ $lunch_today->id }}').datepicker({
                        uiLibrary: 'bootstrap4',
                        format: 'yyyy-mm-dd',
                        locale: 'zh-TW',
                    });

                    $(document).ready(function(){
                        $('#date{{ $lunch_today->id }}').change(function(){
                            $.ajax({
                                url: '{{ route('lunch_todays.return_date'.$lunch_today->id) }}',
                                type : 'post',
                                dataType : 'json',
                                data : $('#date_form{{ $lunch_today->id }}').serialize(),
                                success : function(result) {
                                    if(result != 'failed') {
                                        total_data = show_data{{ $lunch_today->id }}(result);
                                        document.getElementById('lunch_content{{ $lunch_today->id }}').innerHTML = total_data;
                                    }
                                },
                                error: function(result) {
                                    alert('失敗！');
                                }
                            })
                        });
                    });

                    function show_data{{ $lunch_today->id }}(result){
                        if(result=="此日無資料"){
                            return result;
                        }else{
                            data = '<ul class="nav nav-tabs" id="myTab" role="tablist" style="margin-top: 5px">';
                            var p=0;
                            for (var k in result) {
                                if(k != 'dish'){
                                    p++;
                                    data = data+'<li class="nav-item">';
                                    if(p==1){
                                        data = data +'<a class="nav-link active" id="home-tab" data-toggle="tab" href="#lunch_today{{ $lunch_today->id }}_home" role="tab" aria-controls="home" aria-selected="true">'+k+'</a>';
                                    }else{
                                        data = data +'<a class="nav-link" id="profile-tab'+p+'" data-toggle="tab" href="#lunch_today{{ $lunch_today->id }}_profile'+p+'" role="tab" aria-controls="profile'+p+'" aria-selected="false">'+k+'</a>';
                                    }
                                    data = data+'</li>';
                                }
                            }
                            data = data+'</ul>';
                            data = data+'<div class="tab-content" id="myTabContent">';
                            var p=0;

                            for (var k in result) {
                                if(k != 'dish'){
                                    p++;
                                    if(p==1){
                                        data = data +'<div class="tab-pane fade show active" id="lunch_today{{ $lunch_today->id }}_home" role="tabpanel" aria-labelledby="home-tab" style="margin: 10px;">';
                                    }else{
                                        data = data +'<div class="tab-pane fade" id="lunch_today{{ $lunch_today->id }}_profile'+p+'" role="tabpanel" aria-labelledby="profile-tab" style="margin: 10px;">';
                                    }
                                    data = data+'<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">';
                                    var q=0;
                                    for(var k1 in result[k]){
                                        q++;
                                        data = data+'<li class="nav-item">';
                                        if(q==1){
                                            data = data+'<a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#lunch_dish{{ $lunch_today->id }}_pills-home" role="tab" aria-controls="pills-home" aria-selected="true">'+k1+'</a>';
                                        }else{
                                            data = data+'<a class="nav-link" id="pills-profile-tab'+q+'" data-toggle="pill" href="#lunch_dish{{ $lunch_today->id }}_pills-profile'+q+'" role="tab" aria-controls="pills-profile" aria-selected="false">'+k1+'</a>';
                                        }
                                        data =data+'</li>';
                                    }
                                    data = data+'</ul>';

                                    data = data+'<div class="tab-content" id="pills-tabContent">';


                                    var q=0;
                                    for(var k1 in result[k]){
                                        q++;
                                        if(q==1){
                                            data =data+'<div class="tab-pane fade show active" id="lunch_dish{{ $lunch_today->id }}_pills-home" role="tabpanel" aria-labelledby="pills-home-tab">';
                                        }else{
                                            data =data+'<div class="tab-pane fade" id="lunch_dish{{ $lunch_today->id }}_pills-profile'+q+'" role="tabpanel" aria-labelledby="pills-profile-tab">';
                                        }
                                        for(var k3 in result['dish'][result[k][k1]]){

                                            data = data+'<figure class="figure" style="margin: 2px;">';
                                            data = data+'<a href="https://fatraceschool.k12ea.gov.tw/dish/pic/'+result['dish'][result[k][k1]][k3]['DishId']+'" target="_blank"><img src="https://fatraceschool.k12ea.gov.tw/dish/pic/'+result['dish'][result[k][k1]][k3]['DishId']+'" class="figure-img img-fluid rounded" alt="..." style="width:100px;height:100px;object-fit: cover;"></a>';
                                            data = data+'<figcaption class="figure-caption">';
                                            data = data+'<strong>'+result['dish'][result[k][k1]][k3]['DishType']+'</strong><br>';
                                            data = data+result['dish'][result[k][k1]][k3]['DishName'];
                                            data = data+'</figcaption>';
                                            data = data+'</figure>';
                                        }

                                        data = data+'<br>';
                                        data = data +'<a class="badge badge-info" href="https://fatraceschool.k12ea.gov.tw/frontend/search.html?school={{ $lunch_today->school_id }}&period='+$('#date{{ $lunch_today->id }}').val()+'" target="_blank">查看詳細食材營養成份</a>';
                                        data = data+'</div>';

                                    }
                                    data = data+'</div>';

                                    data = data+'</div>';
                                }

                            }
                            data = data+'</div>';
                            return data;
                        }
                    }
                </script>
            </td>
        </tr>
    </table>
    <div id="lunch_content{{ $lunch_today->id }}">
    @if($has_data =="此日有資料")
            <ul class="nav nav-tabs" id="myTab" role="tablist" style="margin-top: 5px">
                <?php
                $p=0;
                ?>
                @foreach($kitchen_datas as $k=>$v)
                    <?php $p++; ?>
                    <li class="nav-item">
                        @if($p==1)
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#lunch_today{{ $lunch_today->id }}_home" role="tab" aria-controls="home" aria-selected="true">{{ $k }}</a>
                        @else
                            <a class="nav-link" id="profile-tab{{ $p }}" data-toggle="tab" href="#lunch_today{{ $lunch_today->id }}_profile{{ $p }}" role="tab" aria-controls="profile{{ $p }}" aria-selected="false">{{ $k }}</a>
                        @endif
                    </li>
                @endforeach
            </ul>


            <div class="tab-content" id="myTabContent">
                <?php $p = 0;?>
                    @foreach($kitchen_datas as $k=>$v)
                    <?php $p++;?>
                    @if($p==1)
                    <div class="tab-pane fade show active" id="lunch_today{{ $lunch_today->id }}_home" role="tabpanel" aria-labelledby="home-tab" style="margin: 10px;">
                    @else
                    <div class="tab-pane fade" id="lunch_today{{ $lunch_today->id }}_profile{{ $p }}" role="tabpanel" aria-labelledby="profile-tab" style="margin: 10px;">
                    @endif
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <?php $n=0; ?>
                            @foreach($v as $k1=>$v1)
                                <?php $n++; ?>
                                <li class="nav-item">
                                    @if($n==1)
                                        <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#lunch_dish{{ $lunch_today->id }}_pills-home" role="tab" aria-controls="pills-home" aria-selected="true">{{ $k1 }}</a>
                                    @else
                                        <a class="nav-link" id="pills-profile-tab{{ $n }}" data-toggle="pill" href="#lunch_dish{{ $lunch_today->id }}_pills-profile{{ $n }}" role="tab" aria-controls="pills-profile" aria-selected="false">{{ $k1 }}</a>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <?php $n=0; ?>
                            @foreach($v as $k1=>$v1)
                                <?php $n++; ?>
                                @if($n==1)
                                <div class="tab-pane fade show active" id="lunch_dish{{ $lunch_today->id }}_pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                                @else
                                <div class="tab-pane fade" id="lunch_dish{{ $lunch_today->id }}_pills-profile{{ $n }}" role="tabpanel" aria-labelledby="pills-profile-tab">
                                @endif
                                    <?php
                                    $json = get_url("https://fatraceschool.k12ea.gov.tw/dish?BatchDataId={$v1}");
                                    $dish = json_decode($json, true);
                                    if(!isset($dish['data'])) $dish['data'] = [] ;
                                    ?>
                                    @foreach($dish['data'] as $d)
                                        @if(isset($d['DishType']))
                                        <figure class="figure" style="margin: 2px;">
                                            <a href="https://fatraceschool.k12ea.gov.tw/dish/pic/{{ $d['DishId'] }}" target="_blank"><img src="https://fatraceschool.k12ea.gov.tw/dish/pic/{{ $d['DishId'] }}" class="figure-img img-fluid rounded" alt="..." style="width:100px;height:100px;object-fit: cover;"></a>
                                            <figcaption class="figure-caption">
                                                    <strong>{{ $d['DishType'] }}</strong><br>
                                                {{ $d['DishName'] }}
                                            </figcaption>
                                        </figure>
                                        @endif
                                    @endforeach
                                    <br>
                                    <a class="badge badge-info" href="https://fatraceschool.k12ea.gov.tw/frontend/search.html?school={{ $lunch_today->school_id }}&period={{ $today }}" target="_blank">查看詳細食材營養成份</a>
                                </div>
                            @endforeach
                            </div>
                        </div>
                    @endforeach
            </div>
    @else
        {{ $has_data }}
    @endif
    </div>
@elseif($link=="連線不成功")
    {{ $link }}
@endif
