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

<a href="{{ route('lends.index') }}" class="btn btn-primary btn-sm">我要借用</a><br><br>
<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">今日要借出</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">今日要歸還</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">近十筆借單</a>
    </li>
  </ul>
  <div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
        <br>
        <table>
            <tr>
                <td>
                    <a href="#!" onclick="change_date(-1,'last_lend','lend_date')">
                    <i class="fas fa-angle-left"></i>往前
                    </a>
                </td>
                <td>
                    <input type="date" value="{{ date('Y-m-d') }}" class="form-control" id="this_date1" readonly style="font-size:20px;font-weight:bold;color:black">
                </td>
                <td>
                    <a href="#!" onclick="change_date(1,'last_lend','lend_date')">
                    往後<i class="fas fa-angle-right"></i>
                    </a>
                </td>
            </tr>
        </table>
        <div class="table-responsive">
            <div id="last_lend">
                <table class="table table-border table-striped">
                    <tr>
                        <th>
                            填寫時間
                        </th>
                        <th>
                            借用人
                        </th>
                        <th>
                            借用物品
                        </th>
                        <th>
                            借用時間
                        </th>
                        <th>
                            歸還時間
                        </th>
                        <th>
                            備註
                        </th>
                    </tr>                                    
                        @foreach($lend_orders2 as $lend_order)
                        <tr>
                            <td>
                                {{ $lend_order->created_at }}
                            </td>
                            <td>
                                {{ $lend_order->user->name }}
                            </td>
                            <td>
                                {{ $lend_order->lend_item->name }}<br>{{ $lend_order->num }}
                            </td>
                            <td>
                                {{ $lend_order->lend_date }}<br>{{ $sections_array[$lend_order->lend_section] }}
                            </td>
                            <td>
                                {{ $lend_order->back_date }}<br>{{ $sections_array[$lend_order->back_section] }}
                            </td>
                            <td>
                                {{ $lend_order->ps }}
                            </td>
                        </tr>
                        @endforeach                                    
                </table>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
        <br>
        <table>
            <tr>
                <td>
                    <a href="#!" onclick="change_date(-1,'next_lend','back_date')">
                    <i class="fas fa-angle-left"></i>往前
                    </a>
                </td>
                <td>
                    <input type="date" value="{{ date('Y-m-d') }}" class="form-control" id="this_date2" readonly style="font-size:20px;font-weight:bold;color:black">
                </td>
                <td>
                    <a href="#!" onclick="change_date(1,'next_lend','back_date')">
                    往後<i class="fas fa-angle-right"></i>
                    </a>
                </td>
            </tr>
        </table>
        <div class="table-responsive">
            <div id="next_lend">
            <table class="table table-border table-striped">
                <tr>
                    <th>
                        填寫時間
                    </th>
                    <th>
                        借用人
                    </th>
                    <th>
                        借用物品
                    </th>
                    <th>
                        借用時間
                    </th>
                    <th>
                        歸還時間
                    </th>
                    <th>
                        備註
                    </th>
                </tr>
                <div id="today_back">
                    @foreach($lend_orders3 as $lend_order)
                    <tr>
                        <td>                                        
                            {{ $lend_order->created_at }}
                        </td>
                        <td>
                            {{ $lend_order->user->name }}
                        </td>
                        <td>
                            {{ $lend_order->lend_item->name }}<br>{{ $lend_order->num }}
                        </td>
                        <td>
                            {{ $lend_order->lend_date }}<br>{{ $sections_array[$lend_order->lend_section] }}
                        </td>
                        <td>
                            {{ $lend_order->back_date }}<br>{{ $sections_array[$lend_order->back_section] }}
                        </td>
                        <td>
                            {{ $lend_order->ps }}
                        </td>
                    </tr>
                    @endforeach
                </div>
            </table>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
        <br>
        <div class="table-responsive">
            <table class="table table-border table-striped">
                <tr>
                    <th>
                        填寫時間
                    </th>
                    <th>
                        借用人
                    </th>
                    <th>
                        借用物品
                    </th>
                    <th>
                        借用時間
                    </th>
                    <th>
                        歸還時間
                    </th>
                    <th>
                        備註
                    </th>
                </tr>
                @foreach($lend_orders as $lend_order)
                <?php
                    $lend_items = \App\LendItem::where('lend_class_id',$lend_order->lend_item->lend_class_id)->get();
                ?>
                <tr>
                    <td>                        
                        {{ $lend_order->created_at }}
                    </td>
                    <td>
                        {{ $lend_order->user->name }}
                    </td>
                    <td>                              
                        {{ $lend_order->lend_item->name }}<br>                                                                                     
                        {{ $lend_order->num }}                                                                            
                    </td>
                    <td>
                        {{ $lend_order->lend_date }}<br>
                        {{ $lend_sections[$lend_order->lend_section] }}
                    </td>
                    <td>
                        {{ $lend_order->back_date }}<br>
                        {{ $lend_sections[$lend_order->back_section] }}
                    </td>
                    <td>
                        {{ $lend_order->ps }}
                    </td>
                </tr>
                @endforeach
            </table>
            {{  $lend_orders->links() }}
        </div>
    </div>
  </div>

  <script>
    function change_date(n,id,action){
        if(action == 'lend_date'){
            var this_date = $('#this_date1').val();
        }
        if(action == 'back_date'){
            var this_date = $('#this_date2').val();
        }
        var date = new Date(this_date);
        date.setDate(date.getDate() + n );
        date = formatDate(date);    
        if(action == 'lend_date'){
            $('#this_date1').val(date);
        }
        if(action == 'back_date'){
            $('#this_date2').val(date); 
        }
               
        //alert(date);
        $.ajax({
            url: 'https://{{ $_SERVER['HTTP_HOST'] }}'+'/lends/check_order_out_clean/'+date+'/'+action,
            type : 'get',
            dataType : 'json',
            //data : $('#sunday_form').serialize(),
            success : function(result) {
                if(result != 'failed') {
                    document.getElementById(id).innerHTML = get_table(result);
                }
            },
            error: function(result) {
                alert('失敗');
            }
        })
    }

    function formatDate(date) {
        var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2) 
            month = '0' + month;
        if (day.length < 2) 
            day = '0' + day;

        return [year, month, day].join('-');
    }

    

    function get_table(result){
        data = "<table class='table table-border table-striped'><tr><th>填寫時間</th><th>借用人</th><th>借用物品</th><th>借用時間</th><th>歸還時間</th><th>備註</th></tr>";
        for(var k in result){
            var d = new Date(result[k]['created_at']);            
            dt = d.toLocaleString('sv');
            data = data+"<tr><td>"+dt+"</td><td>"+result[k]['user']+"</td><td>"+result[k]['lend_item']+"<br>"+result[k]['num']+"</td><td>"+result[k]['lend_date']+"<br>"+result[k]['lend_section']+"</td><td>"+result[k]['back_date']+"<br>"+result[k]['back_section']+"</td><td>"+result[k]['ps']+"</td></tr>";
        }
        data = data+"</table>";
        return data;
    }

</script>  