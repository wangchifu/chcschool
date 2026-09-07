<script src="{{ asset('gijgo/js/gijgo.min.js') }}" type="text/javascript"></script>
<link href="{{ asset('gijgo/css/gijgo.min.css') }}" rel="stylesheet" type="text/css">

<?php
    $today = date('Y-m-d');
    $lunch_today = \App\LunchToday::find(3);
    $s = get_url("https://fatraceschool.k12ea.gov.tw/offered/meal?SchoolId=".$lunch_today->school_id."&KitchenId=all&period=".$today);
    $lunch_datas = json_decode($s,true);

    if(isset($lunch_datas['result'])){
        if($lunch_datas['result'] == 1){
            $link = "連線成功";
            if(empty($lunch_datas['data'])){
                $has_data = "此日無資料";
            }else{
                $has_data = "此日有資料";
                foreach($lunch_datas['data'] as $lunch_data){
                    $kitchen_datas[$lunch_data['KitchenName']][$lunch_data['MenuTypeName']] = $lunch_data['BatchDataId'];
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

@if($link == "連線成功")
    <div class="card card-body mb-3">
        <div class="form-group row align-items-center mb-0">
            <div class="col-auto">
                <strong class="h5 mb-0">
                    {{ $school_name = $lunch_today->school_name }}
                </strong>
            </div>
            <div class="col-auto">
                {{ Form::open(['route' => 'lunch_todays.return_date'.$lunch_today->id, 'method' => 'POST','id'=>'date_form'.$lunch_today->id,'onsubmit'=>'return false']) }}
                
                {{-- 無障礙 HM1130100C 修正：補上對應 label 與完整說明 --}}
                <label for="date{{ $lunch_today->id }}" class="sr-only">選擇午餐菜單日期</label>
                <input id="date{{ $lunch_today->id }}" name="date{{ $lunch_today->id }}" required maxlength="10" value="{{ $today }}" class="form-control" title="請輸入或選擇查詢日期，格式為 YYYY-MM-DD" aria-label="請選擇查詢日期">
                
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
                                    alert('載入菜單失敗，請稍後再試！');
                                }
                            });
                        });
                    });

                    function show_data{{ $lunch_today->id }}(result){
                        if(result == "此日無資料"){
                            return '<p class="alert alert-info mt-2">'+result+'</p>';
                        }else{
                            var data = '<ul class="nav nav-tabs" id="myTab" role="tablist" style="margin-top: 5px">';
                            var p = 0;
                            for (var k in result) {
                                if(k != 'dish'){
                                    p++;
                                    data += '<li class="nav-item" role="presentation">';
                                    if(p == 1){
                                        data += '<a class="nav-link active" id="home-tab" data-toggle="tab" href="#lunch_today{{ $lunch_today->id }}_home" role="tab" aria-controls="home" aria-selected="true" title="檢視 '+k+' 菜單">'+k+'</a>';
                                    }else{
                                        data += '<a class="nav-link" id="profile-tab'+p+'" data-toggle="tab" href="#lunch_today{{ $lunch_today->id }}_profile'+p+'" role="tab" aria-controls="profile'+p+'" aria-selected="false" title="檢視 '+k+' 菜單">'+k+'</a>';
                                    }
                                    data += '</li>';
                                }
                            }
                            data += '</ul><div class="tab-content" id="myTabContent">';
                            var p = 0;

                            for (var k in result) {
                                if(k != 'dish'){
                                    p++;
                                    if(p == 1){
                                        data += '<div class="tab-pane fade show active" id="lunch_today{{ $lunch_today->id }}_home" role="tabpanel" aria-labelledby="home-tab" style="margin: 10px;">';
                                    }else{
                                        data += '<div class="tab-pane fade" id="lunch_today{{ $lunch_today->id }}_profile'+p+'" role="tabpanel" aria-labelledby="profile-tab" style="margin: 10px;">';
                                    }
                                    data += '<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">';
                                    var q = 0;
                                    for(var k1 in result[k]){
                                        q++;
                                        data += '<li class="nav-item" role="presentation">';
                                        if(q == 1){
                                            data += '<a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#lunch_dish{{ $lunch_today->id }}_pills-home" role="tab" aria-controls="pills-home" aria-selected="true" title="切換至 '+k1+'">'+k1+'</a>';
                                        }else{
                                            data += '<a class="nav-link" id="pills-profile-tab'+q+'" data-toggle="pill" href="#lunch_dish{{ $lunch_today->id }}_pills-profile'+q+'" role="tab" aria-controls="pills-profile'+q+'" aria-selected="false" title="切換至 '+k1+'">'+k1+'</a>';
                                        }
                                        data += '</li>';
                                    }
                                    data += '</ul><div class="tab-content" id="pills-tabContent">';

                                    var q = 0;
                                    for(var k1 in result[k]){
                                        q++;
                                        if(q == 1){
                                            data += '<div class="tab-pane fade show active" id="lunch_dish{{ $lunch_today->id }}_pills-home" role="tabpanel" aria-labelledby="pills-home-tab">';
                                        }else{
                                            data += '<div class="tab-pane fade" id="lunch_dish{{ $lunch_today->id }}_pills-profile'+q+'" role="tabpanel" aria-labelledby="pills-profile-tab">';
                                        }
                                        
                                        for(var k3 in result['dish'][result[k][k1]]){
                                            var dishItem = result['dish'][result[k][k1]][k3];
                                            data += '<figure class="figure mr-2 mb-2">';
                                            {{-- JS 無障礙 HM1240401C 與 CS2140401C 修正 --}}
                                            data += '<a href="https://fatraceschool.k12ea.gov.tw/dish/pic/'+dishItem['DishId']+'" target="_blank" rel="noopener noreferrer" title="放大檢視菜餚圖片：'+dishItem['DishName']+'（另開新視窗）" aria-label="放大檢視菜餚圖片：'+dishItem['DishName']+'（另開新視窗）">';
                                            data += '<img src="https://fatraceschool.k12ea.gov.tw/dish/pic/'+dishItem['DishId']+'" class="figure-img img-fluid rounded" alt="菜餚圖片：'+dishItem['DishName']+'" style="width: 6.25rem; height: 6.25rem; object-fit: cover;"></a>';
                                            data += '<figcaption class="figure-caption"><strong>'+dishItem['DishType']+'</strong><br>'+dishItem['DishName']+'</figcaption>';
                                            data += '</figure>';
                                        }

                                        data += '<br>';
                                        data += '<a class="badge badge-info p-2 mt-2" href="https://fatraceschool.k12ea.gov.tw/frontend/search.html?school={{ $lunch_today->school_id }}&period='+$('#date{{ $lunch_today->id }}').val()+'" target="_blank" rel="noopener noreferrer" title="前往教育部智慧食材大平台查看詳細食材營養成份（另開新視窗）" aria-label="前往教育部智慧食材大平台查看詳細食材營養成份（另開新視窗）">查看詳細食材營養成份 <span class="sr-only">（另開新視窗）</span></a>';
                                        data += '</div>';
                                    }
                                    data += '</div></div>';
                                }
                            }
                            data += '</div>';
                            return data;
                        }
                    }
                </script>
            </div>
        </div>
    </div>

    <div id="lunch_content{{ $lunch_today->id }}">
    @if($has_data == "此日有資料")
            <ul class="nav nav-tabs" id="myTab" role="tablist" style="margin-top: 5px">
                <?php $p = 0; ?>
                @foreach($kitchen_datas as $k => $v)
                    <?php $p++; ?>
                    <li class="nav-item" role="presentation">
                        @if($p == 1)
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#lunch_today{{ $lunch_today->id }}_home" role="tab" aria-controls="home" aria-selected="true" title="檢視 {{ $k }} 菜單">{{ $k }}</a>
                        @else
                            <a class="nav-link" id="profile-tab{{ $p }}" data-toggle="tab" href="#lunch_today{{ $lunch_today->id }}_profile{{ $p }}" role="tab" aria-controls="profile{{ $p }}" aria-selected="false" title="檢視 {{ $k }} 菜單">{{ $k }}</a>
                        @endif
                    </li>
                @endforeach
            </ul>

            <div class="tab-content" id="myTabContent">
                <?php $p = 0; ?>
                @foreach($kitchen_datas as $k => $v)
                    <?php $p++; ?>
                    @if($p == 1)
                        <div class="tab-pane fade show active" id="lunch_today{{ $lunch_today->id }}_home" role="tabpanel" aria-labelledby="home-tab" style="margin: 10px;">
                    @else
                        <div class="tab-pane fade" id="lunch_today{{ $lunch_today->id }}_profile{{ $p }}" role="tabpanel" aria-labelledby="profile-tab" style="margin: 10px;">
                    @endif
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <?php $n = 0; ?>
                            @foreach($v as $k1 => $v1)
                                <?php $n++; ?>
                                <li class="nav-item" role="presentation">
                                    @if($n == 1)
                                        <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#lunch_dish{{ $lunch_today->id }}_pills-home" role="tab" aria-controls="pills-home" aria-selected="true" title="切換至 {{ $k1 }}">{{ $k1 }}</a>
                                    @else
                                        <a class="nav-link" id="pills-profile-tab{{ $n }}" data-toggle="pill" href="#lunch_dish{{ $lunch_today->id }}_pills-profile{{ $n }}" role="tab" aria-controls="pills-profile{{ $n }}" aria-selected="false" title="切換至 {{ $k1 }}">{{ $k1 }}</a>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <?php $n = 0; ?>
                            @foreach($v as $k1 => $v1)
                                <?php $n++; ?>
                                @if($n == 1)
                                    <div class="tab-pane fade show active" id="lunch_dish{{ $lunch_today->id }}_pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                                @else
                                    <div class="tab-pane fade" id="lunch_dish{{ $lunch_today->id }}_pills-profile{{ $n }}" role="tabpanel" aria-labelledby="pills-profile-tab">
                                @endif
                                    <?php
                                    $json = get_url("https://fatraceschool.k12ea.gov.tw/dish?BatchDataId={$v1}");
                                    $dish = json_decode($json, true);
                                    if(!isset($dish['data'])) $dish['data'] = [];
                                    ?>
                                    @foreach($dish['data'] as $d)
                                        @if(isset($d['DishType']))
                                            <figure class="figure mr-2 mb-2">
                                                {{-- 無障礙 HM1240401C 與 CS2140401C 修正 --}}
                                                <a href="https://fatraceschool.k12ea.gov.tw/dish/pic/{{ $d['DishId'] }}" target="_blank" rel="noopener noreferrer" title="放大檢視菜餚照片：{{ $d['DishName'] }}（另開新視窗）" aria-label="放大檢視菜餚照片：{{ $d['DishName'] }}（另開新視窗）">
                                                    <img src="https://fatraceschool.k12ea.gov.tw/dish/pic/{{ $d['DishId'] }}" class="figure-img img-fluid rounded" alt="菜餚照片：{{ $d['DishName'] }}" style="width: 6.25rem; height: 6.25rem; object-fit: cover;">
                                                </a>
                                                <figcaption class="figure-caption">
                                                    <strong>{{ $d['DishType'] }}</strong><br>
                                                    {{ $d['DishName'] }}
                                                </figcaption>
                                            </figure>
                                        @endif
                                    @endforeach
                                    <br>
                                    <a class="badge badge-info p-2 mt-2" href="https://fatraceschool.k12ea.gov.tw/frontend/search.html?school={{ $lunch_today->school_id }}&period={{ $today }}" target="_blank" rel="noopener noreferrer" title="前往教育部智慧食材大平台查看詳細食材營養成份（另開新視窗）" aria-label="前往教育部智慧食材大平台查看詳細食材營養成份（另開新視窗）">
                                        查看詳細食材營養成份
                                        <span class="sr-only">（另開新視窗）</span>
                                    </a>
                                </div>
                            @endforeach
                            </div>
                        </div>
                    @endforeach
            </div>
    @else
        <div class="alert alert-info mt-2" role="status">{{ $has_data }}</div>
    @endif
    </div>
@elseif($link == "連線不成功")
    <div class="alert alert-warning mt-2" role="alert">食材平台連線不成功</div>
@endif