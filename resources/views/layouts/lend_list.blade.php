<?php
$lend_orders = \App\LendOrder::orderBy('id','DESC')
            ->paginate(10);
        
$lend_orders2 = \App\LendOrder::where('lend_date',date('Y-m-d'))
            ->get();

$lend_orders3 = \App\LendOrder::where('back_date',date('Y-m-d'))
            ->get();     
$lend_sections = config('chcschool.lend_sections');
$sections_array = config('chcschool.lend_sections');
?>

{{-- 無障礙 HM1240401C 修正：按鈕補上完整的 title 與 aria-label --}}
<a href="{{ route('lends.index') }}" class="btn btn-primary btn-sm mb-3" title="前往申請物品借用頁面" aria-label="前往申請物品借用頁面">我要借用</a>

<ul class="nav nav-tabs" id="myTab" role="tablist" aria-label="物品借用記錄頁籤">
    <li class="nav-item" role="presentation">
      <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true" title="切換至今日要借出列表">今日要借出</a>
    </li>
    <li class="nav-item" role="presentation">
      <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false" title="切換至今日要歸還列表">今日要歸還</a>
    </li>
    <li class="nav-item" role="presentation">
      <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false" title="切換至近十筆借單記錄">近十筆借單</a>
    </li>
</ul>

<div class="tab-content" id="myTabContent">
    {{-- 頁籤一：今日要借出 --}}
    <div class="tab-pane fade show active py-3" id="home" role="tabpanel" aria-labelledby="home-tab">
        <div class="d-flex align-items-center mb-3">
            {{-- 無障礙 HM1240401C 修正：改用標準 button 並提供明確 title/aria-label --}}
            <button type="button" class="btn btn-outline-secondary mr-2" onclick="change_date(-1,'last_lend','lend_date')" title="查詢前一天的借出記錄" aria-label="查詢前一天的借出記錄">
                <i class="fas fa-angle-left" aria-hidden="true"></i> 往前一天
            </button>
            
            {{-- 無障礙 HM1130100C 修正：輸入框補上輔助 label --}}
            <label for="this_date1" class="sr-only">選擇借出日期</label>
            <input type="date" value="{{ date('Y-m-d') }}" class="form-control w-auto font-weight-bold text-dark mr-2" id="this_date1" readonly aria-readonly="true">
            
            <button type="button" class="btn btn-outline-secondary" onclick="change_date(1,'last_lend','lend_date')" title="查詢後一天的借出記錄" aria-label="查詢後一天的借出記錄">
                往後一天 <i class="fas fa-angle-right" aria-hidden="true"></i>
            </button>
        </div>

        <div class="table-responsive">
            <div id="last_lend">
                <table class="table table-bordered table-striped" aria-label="今日借出物品列表">
                    <caption class="sr-only">今日借出物品明細清單</caption>
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">填寫時間</th>
                            <th scope="col">借用人</th>
                            <th scope="col">借用物品</th>
                            <th scope="col">借用時間</th>
                            <th scope="col">歸還時間</th>
                            <th scope="col">備註</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lend_orders2 as $lend_order)
                        <tr>
                            <td>{{ $lend_order->created_at }}</td>
                            <td>{{ $lend_order->user->name }}</td>
                            <td>{{ $lend_order->lend_item->name }}<br>數量：{{ $lend_order->num }}</td>
                            <td>{{ $lend_order->lend_date }}<br>{{ $sections_array[$lend_order->lend_section] }}</td>
                            <td>{{ $lend_order->back_date }}<br>{{ $sections_array[$lend_order->back_section] }}</td>
                            <td>{{ $lend_order->ps }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- 頁籤二：今日要歸還 --}}
    <div class="tab-pane fade py-3" id="profile" role="tabpanel" aria-labelledby="profile-tab">
        <div class="d-flex align-items-center mb-3">
            <button type="button" class="btn btn-outline-secondary mr-2" onclick="change_date(-1,'next_lend','back_date')" title="查詢前一天的歸還記錄" aria-label="查詢前一天的歸還記錄">
                <i class="fas fa-angle-left" aria-hidden="true"></i> 往前一天
            </button>
            
            <label for="this_date2" class="sr-only">選擇歸還日期</label>
            <input type="date" value="{{ date('Y-m-d') }}" class="form-control w-auto font-weight-bold text-dark mr-2" id="this_date2" readonly aria-readonly="true">
            
            <button type="button" class="btn btn-outline-secondary" onclick="change_date(1,'next_lend','back_date')" title="查詢後一天的歸還記錄" aria-label="查詢後一天的歸還記錄">
                往後一天 <i class="fas fa-angle-right" aria-hidden="true"></i>
            </button>
        </div>

        <div class="table-responsive">
            <div id="next_lend">
                <table class="table table-bordered table-striped" aria-label="今日歸還物品列表">
                    <caption class="sr-only">今日歸還物品明細清單</caption>
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">填寫時間</th>
                            <th scope="col">借用人</th>
                            <th scope="col">借用物品</th>
                            <th scope="col">借用時間</th>
                            <th scope="col">歸還時間</th>
                            <th scope="col">備註</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lend_orders3 as $lend_order)
                        <tr>
                            <td>{{ $lend_order->created_at }}</td>
                            <td>{{ $lend_order->user->name }}</td>
                            <td>{{ $lend_order->lend_item->name }}<br>數量：{{ $lend_order->num }}</td>
                            <td>{{ $lend_order->lend_date }}<br>{{ $sections_array[$lend_order->lend_section] }}</td>
                            <td>{{ $lend_order->back_date }}<br>{{ $sections_array[$lend_order->back_section] }}</td>
                            <td>{{ $lend_order->ps }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- 頁籤三：近十筆借單 --}}
    <div class="tab-pane fade py-3" id="contact" role="tabpanel" aria-labelledby="contact-tab">
        <div class="table-responsive">
            <table class="table table-bordered table-striped" aria-label="最近十筆借用記錄列表">
                <caption class="sr-only">最近十筆借用記錄清單</caption>
                <thead class="thead-light">
                    <tr>
                        <th scope="col">填寫時間</th>
                        <th scope="col">借用人</th>
                        <th scope="col">借用物品</th>
                        <th scope="col">借用時間</th>
                        <th scope="col">歸還時間</th>
                        <th scope="col">備註</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lend_orders as $lend_order)
                    <tr>
                        <td>{{ $lend_order->created_at }}</td>
                        <td>{{ $lend_order->user->name }}</td>
                        <td>{{ $lend_order->lend_item->name }}<br>數量：{{ $lend_order->num }}</td>
                        <td>{{ $lend_order->lend_date }}<br>{{ $lend_sections[$lend_order->lend_section] }}</td>
                        <td>{{ $lend_order->back_date }}<br>{{ $lend_sections[$lend_order->back_section] }}</td>
                        <td>{{ $lend_order->ps }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $lend_orders->links() }}
        </div>
    </div>
</div>

<script>
    function change_date(n,id,action){
        var this_date = (action == 'lend_date') ? $('#this_date1').val() : $('#this_date2').val();
        var date = new Date(this_date);
        date.setDate(date.getDate() + n );
        date = formatDate(date);    
        
        if(action == 'lend_date'){
            $('#this_date1').val(date);
        } else {
            $('#this_date2').val(date); 
        }
               
        $.ajax({
            url: 'https://{{ $_SERVER['HTTP_HOST'] }}'+'/lends/check_order_out_clean/'+date+'/'+action,
            type : 'get',
            dataType : 'json',
            success : function(result) {
                if(result != 'failed') {
                    document.getElementById(id).innerHTML = get_table(result);
                }
            },
            error: function(result) {
                alert('載入失敗，請稍後再試');
            }
        });
    }

    function formatDate(date) {
        var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2) month = '0' + month;
        if (day.length < 2) day = '0' + day;

        return [year, month, day].join('-');
    }

    function get_table(result){
        var data = "<table class='table table-bordered table-striped' aria-label='動態載入的借用記錄列表'><thead class='thead-light'><tr><th scope='col'>填寫時間</th><th scope='col'>借用人</th><th scope='col'>借用物品</th><th scope='col'>借用時間</th><th scope='col'>歸還時間</th><th scope='col'>備註</th></tr></thead><tbody>";
        for(var k in result){
            var d = new Date(result[k]['created_at']);            
            var dt = d.toLocaleString('sv');
            data += "<tr><td>"+dt+"</td><td>"+result[k]['user']+"</td><td>"+result[k]['lend_item']+"<br>數量："+result[k]['num']+"</td><td>"+result[k]['lend_date']+"<br>"+result[k]['lend_section']+"</td><td>"+result[k]['back_date']+"<br>"+result[k]['back_section']+"</td><td>"+result[k]['ps']+"</td></tr>";
        }
        data += "</tbody></table>";
        return data;
    }
</script>