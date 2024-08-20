@extends('layouts.master')

@section('title', '校園跑馬燈 | ')

@section('content')        
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>
                校園跑馬燈
            </h1>
            <ul class="nav nav-tabs">
                @if(auth()->user()->admin)
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('school_marquee.setup') }}">管理</a>
                </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('school_marquee.index') }}">列表</a>
                </li>
            </ul>
            <br>
            <div class="card">
                <div class="card-header text-center">
                    <h3 class="py-2">
                        跑馬燈列表
                    </h3>
                </div>
                <div class="card-body">                    
                    @if($school_marquees->count()>0)                    
                    <?php
                    $school_marquee_color = (empty($setup->school_marquee_color))?$school_marquee_color="warning":$setup->school_marquee_color;
                    $school_marquee_behavior = (empty($setup->school_marquee_behavior))?$school_marquee_behavior="scroll":$setup->school_marquee_behavior;
                    $school_marquee_direction = (empty($setup->school_marquee_direction))?$school_marquee_direction="up":$setup->school_marquee_direction;
                    $school_marquee_scrollamount = (empty($setup->school_marquee_scrollamount))?$school_marquee_scrollamount="2":$setup->school_marquee_scrollamount;
                    ?>
                    @if($school_marquees->count()>0)
                        <div class="row justify-content-center">
                            <div class="col-lg-11">
                                <div class="alert alert-{{ $school_marquee_color }}" style="margin-top: -15px;">
                                    <marquee behavior="{{ $school_marquee_behavior }}" direction="{{ $school_marquee_direction }}" scrollamount="{{ $school_marquee_scrollamount }}" height="20px">
                                        @if($school_marquee_direction=="up" or $school_marquee_direction=="down")
                                            @foreach($school_marquees as $school_marquee)
                                                <p>{{ $school_marquee->title }}</p>                                                
                                            @endforeach
                                        @endif
                                        @if($school_marquee_direction=="left" or $school_marquee_direction=="right")
                                            @foreach($school_marquees as $school_marquee)
                                                <span>{{ $school_marquee->title }}</span>
                                                <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                            @endforeach
                                        @endif
                                    </marquee>
                                </div>
                            </div>
                        </div>
                    @endif
                    @endif
                    <a href="{{ route('school_marquee.create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> 新增跑馬燈</a>
                    <table class="table table-striped" style="word-break:break-all;">
                        <thead class="thead-light">
                        <tr>
                            <th>id</th>
                            <th>標題</th>
                            <th>開始日期</th>
                            <th>結束日期</th>
                            <th>上架者</th>
                            <th>動作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i=0;$j=0; ?>
                        @foreach($school_marquees as $school_marquee)
                            <tr>
                                <td>
                                    {{ $school_marquee->id }}
                                </td>
                                <?php
                                        if($school_marquee->stop_date < date('Y-m-d') or $school_marquee->start_date >  date('Y-m-d')){
                                            $not = "text-decoration: line-through;";
                                            $color = "text-secondary";
                                        }else{
                                            $not = "";
                                            $color = "";
                                        }
                                ?>
                                <td class="{{ $color }}" style="{{ $not }}">                                    
                                    {{ $school_marquee->title }}
                                </td>
                                <td>
                                    {{ $school_marquee->start_date }}
                                </td>
                                <td>
                                    {{ $school_marquee->stop_date }}
                                </td>
                                <td>
                                    {{ $school_marquee->user->name }}
                                </td>
                                <td>
                                    @if($school_marquee->user_id == auth()->user()->id or auth()->user()->admin)                                        
                                        <a href="javascript:open_window('{{ route('school_marquee.edit',$school_marquee->id) }}','新視窗')" class="btn btn-primary btn-sm" onclick=""><i class="fas fa-edit"></i> 修改</a>
                                        <a href="#" class="btn btn-danger btn-sm" onclick="if(confirm('確定刪除？')) document.getElementById('delete{{ $school_marquee->id }}').submit();else return false;"><i class="fas fa-trash"></i> 刪除</a>                                        
                                    @endif
                                </td>
                            </tr>
                            {{ Form::open(['route' => ['school_marquee.destroy',$school_marquee->id], 'method' => 'DELETE','id'=>'delete'.$school_marquee->id,'onsubmit'=>'return false;']) }}
                            {{ Form::close() }}
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        function open_window(url,name)
        {
            window.open(url,name,'statusbar=no,scrollbars=yes,status=yes,resizable=yes,width=900,height=800');
        }
    </script>
@endsection
