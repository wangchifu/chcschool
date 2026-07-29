<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>彰化縣國中小學校首頁連結</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
    <link href="{{ asset('fontawesome-5.15.4/css/all.css') }}" rel="stylesheet">

</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <a class="navbar-brand" href="index.php">        
        🏫 彰化縣國中小學校首頁連結
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">

        </ul>
    </div>
</nav>    
<div class="jumbotron jumbotron-fluid">
    <div class="container">
        <h1 class="display-4">國中小學校首頁代管</h1>
        <p class="lead">為減輕各校自管官方網站(首頁)的壓力及符合資安法D級單位規定！由縣網中心提供公版首頁，讓國中小各校申請代管！</p>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="card mt-4">
                <div class="card-header">
                    <span>圖例：</span>
                    <button class="btn btn-secondary btn-sm">學校名 <span class="badge badge-light">自管</span></button>
                    <button class="btn btn-info btn-sm">學校名 <span class="badge badge-light">公版-1 ({{ $school3_1 }}校)</span></button>
		            <button class="btn btn-primary btn-sm">學校名 <span class="badge badge-light">公版-2 ({{ $school3_2 }}校)</span></button>
                </div>
                <div class="card-body">		                                       
                    @foreach($townships as $k1 => $v1)
                        <h4><i class="fab fa-fort-awesome"></i> {{ $v1 }}</h4>
                        @if(isset($all_school[$v1]))
                            @foreach($all_school[$v1] as $k2 => $v2)    
                                @if(isset($schools[$v2['school']]))                            
                                    @if($schools[$v2['school']] != "50" and $schools[$v2['school']] != "49")
                                        <a href="http://{{ $v2['website'] }}" class="btn btn-secondary btn-sm" style="margin:3px" target="_blank">{{ $v2['school'] }} <span class="badge badge-light">自管</span></a>
                                    @endif                            
                                    @if($schools[$v2['school']] == "50")
                                        <a href="http://{{ $v2['website'] }}" class="btn btn-info btn-sm" style="margin:3px" target="_blank">{{ $v2['school'] }} <span class="badge badge-light">公版-1</span></a>
                                    @endif
                                    @if($schools[$v2['school']] == "49")
                                        <a href="http://{{ $v2['website'] }}" class="btn btn-primary btn-sm" style="margin:3px" target="_blank">{{ $v2['school'] }} <span class="badge badge-light">公版-2</span></a>
                                    @endif
                                @else    
                                    <a href="http://{{ $v2['website'] }}" class="btn btn-secondary btn-sm" style="margin:3px" target="_blank">{{ $v2['school'] }} <span class="badge badge-dark">自管</span></a>
                                @endif                           
                            @endforeach
                        @endif               
                    <hr>
                    @endforeach                         		    
                </div>
            </div>
        </div>
    </div>
</div>
<br>
<footer class="py-3 bg-primary" id="footer">
    <div class="container">
        <p class="m-0 text-center text-white">
            Copyright &copy; 彰化縣教育網路中心 {{ date('Y') }}
        </p>
    </div>
</footer>
</body>
</html>
