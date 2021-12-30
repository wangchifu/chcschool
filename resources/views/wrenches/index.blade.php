@extends('layouts.master')

@section('nav_user_active', 'active')

@section('title', '系統報錯與建議 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>
                系統報錯與建議
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">首頁</a></li>
                    <li class="breadcrumb-item active" aria-current="page">報錯與建議列表</li>
                </ol>
            </nav>
            <div class="card">
                <div class="card-header text-center">
                    <h3 class="py-2">
                        填寫資料
                    </h3>
                </div>
                <div class="card-body">
                    {{ Form::open(['route' => 'wrench.store', 'method' => 'POST', 'files' => true]) }}
                    <div class="form-group">
                        <label class="text-primary"><strong>填 EMail 可收回覆信件</strong></label>
                        <input type="email" name="email" value="{{ auth()->user()->email }}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="text-danger"><strong>反應內容*</strong></label>
                        <textarea name="content" class="form-control" required placeholder="請詳細描述，或留公務電話聯絡"></textarea>
                    </div>
                    <div class="form-group">
                        <label>附件</label><br>
                        {{ Form::file('files[]', ['class' => 'form-control','multiple'=>'multiple']) }}
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary btn-sm" onclick="return confirm('確定送出？')">送出</button>
                    </div>
                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                    {{ Form::close() }}
                    <hr>
                    若不願意公開留言，可 email 至 {{ env('ADMIN_EMAIL') }} 反應。
                    <hr>
                    <h4>已填報列表</h4>
                    @foreach($wrenches as $k=>$wrench)
                        <div class="card">
                            <div class="card-header" style="background-color: #FFCC22">
                                @if($admin==1)
                                    @if($wrench['show'] !=1)
                                        <a href="{{ route('wrench.set_show',$wrench['id']) }}" class="btn btn-success btn-sm" onclick="return confirm('確定嗎？')">設為顯示</a>
                                    @endif
				    {{ $wrench['school'] }} / {{ $wrench['job_title']  }} / {{ $wrench['name'] }} /
                                @else
                                    {{ mb_substr($wrench['name'],0,1) }}** /
                                @endif
                                {{ $wrench['created_at'] }}
                                @if($admin==1)
                                    <a href="{{ route('wrench.destroy',$wrench['id']) }}" onclick="return confirm('確定刪除？')"><i class="fas fa-times-circle text-danger"></i></a>
                                @endif
                            </div>
                            <div class="card-body" style="background-color: #FFFFBB">
                                @if($wrench['show'] ==1 or $admin==1)
                                    @if($wrench['show'] !=1)
                                        <span class="text-danger">**審核中**</span><br>
                                    @endif
                                    {!! nl2br($wrench['content']) !!}
                                    <?php
                                    $files = get_files(storage_path('app/public/wrenches/' . $wrench['id']));
                                    ?>
                                    <br>
                                    @if(!empty($files))
                                        <small>
                                            附檔：
                                            @foreach($files as $file)
                                                <a href="{{ route('wrench.download',['wrench_id'=>$wrench['id'],'filename'=>$file]) }}"
                                                   title="點選下載附加檔案({{ $file }})">
                                                    {{ $file }}
                                                </a>,
                                            @endforeach
                                        </small>
                                    @endif
                                @else
                                    <span class="text-danger">**審核中**</span>
                                @endif
                                @if($admin==1)
                                    @if(empty($wrench['reply']))
                                        {{ Form::open(['route' => 'wrench.reply', 'method' => 'POST']) }}
                                        <div class="form-group">
                                            <textarea name="reply" class="form-control" placeholder="管理者回覆"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <button class="btn btn-primary btn-sm" onclick="return confirm('確定送出？')">送出</button>
                                        </div>
                                        <input type="hidden" name="id" value="{{ $wrench['id'] }}">
                                        {{ Form::close() }}
                                    @endif
                                @endif
                                @if(!empty($wrench['reply']))
                                    <hr>
                                    <strong>管理者回覆：</strong><small class="text-secondary">{{ $wrench['updated_at']  }}</small><br>
                                    <span class="text-danger">{!! nl2br($wrench['reply']) !!}</span>
                                @endif
                            </div>
                        </div>
                        <br>
                    @endforeach
                    <div style="text-align:center">
                        <a href="{{ route('wrench.index',$page1) }}" class="btn btn-secondary btn-sm {{ $disabled1 }}">
                            <i class="fas fa-arrow-alt-circle-left"></i> 上一頁
                        </a>
                        <a href="{{ route('wrench.index',$page2) }}" class="btn btn-secondary btn-sm {{ $disabled2 }}">
                            <i class="fas fa-arrow-alt-circle-right"></i> 下一頁
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
