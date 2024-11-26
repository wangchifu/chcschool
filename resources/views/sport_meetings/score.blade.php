@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '運動會報名')

@section('content')
    <?php
    $active['admin'] ="";
    $active['show'] ="";    
    $active['list'] ="";
    $active['score'] ="active";
    $active['teacher'] ="";
    ?>
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>運動會報名-成績處理</h1>
            @include('sport_meetings.nav')
            <hr>
            <ul class="nav nav-pills">
                <li class="nav-item">
                  <a class="nav-link active" href="{{ route('sport_meeting.score') }}">1.自訂獎狀</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('sport_meeting.score_input') }}">2.成績登錄</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('sport_meeting.all_scores') }}">3.成績一覽表</a>                  
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('sport_meeting.total_scores') }}">4.田徑賽計分總表</a>
                </li>                
              </ul>    
              <hr>
              <h3>
                學校獎狀範本上傳
            </h3>
            <form method="post" action="{{ route('sport_meeting.demo_upload') }}" enctype="multipart/form-data">
              @csrf
              {{ Form::file('demo', ['class' => 'form-control','required'=>'required','accept'=>'.odt']) }}
              <br>
              <a href="{{ asset('demo.odt') }}" class="btn btn-success btn-sm"><i class="fas fa-download"></i> 範本下載</a>
              <button class="btn btn-primary btn-sm" onclick="return confirm('會覆蓋已上傳喔！')"><i class="fas fa-upload"></i> 上傳自校範本</button>
              @if(is_file(storage_path('app/public').'/demo.odt'))
                  [<a href="{{ asset('storage/demo.odt') }}">已上傳</a>]
              @endif
          </form>
            建議用 libreoffice 編輯，符號由左至右依序打上，例如 &#123;&#123;姓名&#125;&#125; ，避免被文書軟體依格式不同變成不連續字串<br>
            <table class="table table-striped">
                <thead class="table-danger">
                    <tr>
                        <th>代號</th>
                        <th>替換成</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>&#123;&#123;年班同學&#125;&#125;</td>
                        <td>一年1班王大頭</td>
                    </tr>
                    <tr>
                        <td>&#123;&#123;年班&#125;&#125;</td>
                        <td>一年1班</td>
                    </tr>
                    <tr>
                        <td>&#123;&#123;國字年班&#125;&#125;</td>
                        <td>一年一班</td>
                    </tr>
                    <tr>
                        <td>&#123;&#123;年級&#125;&#125;</td>
                        <td>一年級</td>
                    </tr>
                    <tr>
                        <td>&#123;&#123;班別&#125;&#125;</td>
                        <td>1班</td>
                    </tr>
                    <tr>
                        <td>&#123;&#123;國字班別&#125;&#125;</td>
                        <td>一班</td>
                    </tr>
                    <tr>
                        <td>&#123;&#123;姓名&#125;&#125;</td>
                        <td>王大頭</td>
                    </tr>
                    <tr>
                        <td>&#123;&#123;運動會名稱&#125;&#125;</td>
                        <td>100週年校慶運動會</td>
                    </tr>
                    <tr>
                        <td>&#123;&#123;組別&#125;&#125;</td>
                        <td>男子組</td>
                    </tr>
                    <tr>
                        <td>&#123;&#123;項目&#125;&#125;</td>
                        <td>60公尺</td>
                    </tr>
                    <tr>
                        <td>&#123;&#123;名次&#125;&#125;</td>
                        <td>第1名</td>
                    </tr>
                    <tr>
                        <td>&#123;&#123;國字名次&#125;&#125;</td>
                        <td>第一名</td>
                    </tr>
                    <tr>
                        <td>&#123;&#123;成績&#125;&#125;</td>
                        <td>10.1秒</td>
                    </tr>
                    <tr>
                        <td>&#123;&#123;日期&#125;&#125;</td>
                        <td>中華民國110年10月26日</td>
                    </tr>
                    <tr>
                        <td>&#123;&#123;西元日期&#125;&#125;</td>
                        <td>2021年10月26日</td>
                    </tr>
                </tbody>
            </table>
            <hr>
            <h3>例外列印</h3>
            <div style="border:1px black solid;padding: 10px" class="col-4">
                <h2 class="text-center">獎 狀</h2>
                <form action="{{ route('sport_meeting.print_extra') }}" method="post">
                    @csrf
                    <table>
                        <tr><td>查 <input type="text" name="this_student" value="年班同學"></td></tr>
                        <tr><td>參加 <input type="text" name="action_name" value="{{ substr(auth()->user()->semester,0,3) }}學年度校慶運動會"></td></tr>
                        <tr><td>表現優異</td></tr>
                        <tr><td>組別：<input type="text" name="group"></td></tr>
                        <tr><td>項目：<input type="text" name="item"></td></tr>
                        <tr><td>名次：<input type="text" name="ranking"></td></tr>
                        <tr><td>成績：<input type="text" name="score"></td></tr>
                        <tr><td>特頒此狀以資鼓勵</td></tr>
                    </table>
                    <div style="border:10px red solid;" class="col-3">
                        　<br>
                        　
                    </div>
                    <br>
                    <div class="col-9 text-right">
                        <input type="text" name="print_date" value="中華民國{{ date('Y')-1911 }}年{{ date('m') }}月{{ date('d') }}日">
                    </div>
                    <br>
                    <input type="submit" value="送出">
                </form>
            </div>        
        </div>

    </div>
@endsection
