@include('chcschool.header')

    <!-- 上方大橫幅 (Hero Banner) -->
    <header class="hero-header text-center">
        <div class="container">            
            <h1 class="display-4 fw-bold mb-3">彰化縣學校網站代管中心</h1>
            <p class="lead col-lg-8 mx-auto text-light opacity-75">
                為減輕各校自管官方網站(首頁)的壓力及符合資安法D級單位規定！由縣網中心提供公版首頁，讓國中小各校申請代管！
            </p>
        </div>
    </header>

    <!-- 主要功能按鈕區域 -->
    <main class="container my-5" style="margin-top: -50px !important;">
        <div class="row g-4 justify-content-center">
            
            <div class="card mt-4 border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <span class="fw-bold me-2">圖例：</span>
                    <button class="btn btn-secondary btn-sm me-1" type="button">學校名 <span class="badge bg-light text-dark">自管</span></button>
                    <button class="btn btn-info btn-sm me-1 text-white" type="button">學校名 <span class="badge bg-light text-dark">公版-1 ({{ $school3_1 }}校)</span></button>
                    <button class="btn btn-primary btn-sm" type="button">學校名 <span class="badge bg-light text-dark">公版-2 ({{ $school3_2 }}校)</span></button>
                </div>
                <div class="card-body">                                            
                    @foreach($townships as $k1 => $v1)
                        <h4 class="h5 fw-bold text-primary mt-2"><i class="bi bi-geo-alt-fill me-1"></i> {{ $v1 }}</h4>
                        @if(isset($all_school[$v1]))
                            <div class="mb-2">
                            @foreach($all_school[$v1] as $k2 => $v2)    
                                @if(isset($schools[$v2['school']]))                            
                                    @if($schools[$v2['school']] != "50" and $schools[$v2['school']] != "49")
                                        <a href="http://{{ $v2['website'] }}" class="btn btn-secondary btn-sm m-1" target="_blank">{{ $v2['school'] }} <span class="badge bg-light text-dark">自管</span></a>
                                    @endif                            
                                    @if($schools[$v2['school']] == "50")
                                        <a href="http://{{ $v2['website'] }}" class="btn btn-info btn-sm text-white m-1" target="_blank">{{ $v2['school'] }} <span class="badge bg-light text-dark">公版-1</span></a>
                                    @endif
                                    @if($schools[$v2['school']] == "49")
                                        <a href="http://{{ $v2['website'] }}" class="btn btn-primary btn-sm m-1" target="_blank">{{ $v2['school'] }} <span class="badge bg-light text-dark">公版-2</span></a>
                                    @endif
                                @else    
                                    <a href="http://{{ $v2['website'] }}" class="btn btn-secondary btn-sm m-1" target="_blank">{{ $v2['school'] }} <span class="badge bg-dark">自管</span></a>
                                @endif                           
                            @endforeach
                            </div>
                        @endif               
                        @if(!$loop->last)
                            <hr class="my-3 opacity-25">
                        @endif
                    @endforeach                                     
                </div>
            </div>                       

        </div>
    </main>

@include('chcschool.footer')