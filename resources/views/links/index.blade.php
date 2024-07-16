@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', '選單連結 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
        <h1>選單連結</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">首頁</a></li>
                    <li class="breadcrumb-item active" aria-current="page">選單連結</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    第一層連結類別
                </div>
                <div class="card-body">
                        @include('layouts.errors')
                        <form action="{{ route('links.store_type') }}" method="post" id="this_form1">
                            @csrf
                        <table class="table table-striped" style="word-break:break-all;">
                        <tr>
                            <td width="100">
                                排序
                                {{ Form::number('order_by',$new_type_order_by,['id'=>'order_by','class' => 'form-control', 'placeholder' => '排序']) }}
                            </td>
                            <td>
                                名稱
                                {{ Form::text('name',null,['id'=>'name','class' => 'form-control','required'=>'required', 'placeholder' => '名稱']) }}
                            </td>
                            <td>
                                <button class="btn btn-success btn-sm" onclick="return confirm('確定？')"><i class="fas fa-plus"></i> 新增</button>
                            </td>
                        </tr>
                        </table>
                        </form>
                    <table class="table table-striped" style="word-break:break-all;">
                        <tbody>
                        @foreach($types as $type)
                            {{ Form::open(['route' => ['links.update_type',$type->id], 'method' => 'patch','id'=>'update'.$type->id]) }}
                                @csrf
                                @method('patch')
                            <tr>                                
                                <td width="100">
                                    {{ Form::number('order_by',$type->order_by,['id'=>'order_by','class' => 'form-control', 'placeholder' => '數字']) }}
                                </td>
                                <td>
                                    {{ Form::text('name',$type->name,['id'=>'name','class' => 'form-control','required'=>'required', 'placeholder' => '名稱']) }}
                                </td>                                
                                <td nowrap>
                                    <button onclick="return confirm('儲存修改？')" class="btn btn-primary btn-sm"><i class="fas fa-save"></i></button>
                                    <a href="#" class="text-danger" onclick="if(confirm('確定刪除？會一併刪除所屬連結喔！')) document.getElementById('delete{{ $type->id }}').submit();else return false;"><i class="fas fa-times-circle"></i></a>
                                </td>
                            </tr>
                            {{ Form::close() }}
                            {{ Form::open(['route' => ['links.destroy_type',$type->id], 'method' => 'DELETE','id'=>'delete'.$type->id]) }}
                            {{ Form::close() }}
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <hr>
            <div class="card">
                <div class="card-header">
                    第二層連結子類別
                </div>
                <div class="card-body">
                        @include('layouts.errors')
                        <form action="{{ route('links.store_type') }}" method="post" id="this_form1">
                            @csrf
                        <table class="table table-striped" style="word-break:break-all;">
                        <tr>
                            <td width="120">
                                類別
                                {{ Form::select('type_id', $type_array,null, ['id' => 'type_id2', 'class' => 'form-control','required'=>'required']) }}
                            </td>
                            <td width="100">
                                排序
                                {{ Form::number('order_by',null,['id'=>'order_by2','class' => 'form-control', 'placeholder' => '排序']) }}
                            </td>
                            <td>
                                子類別名稱
                                {{ Form::text('name',null,['id'=>'name2','class' => 'form-control','required'=>'required', 'placeholder' => '名稱']) }}
                            </td>
                            <td>
                                <button class="btn btn-success btn-sm" onclick="return confirm('確定？')"><i class="fas fa-plus"></i> 新增</button>
                            </td>
                        </tr>
                        </table>
                        </form>
                    <table class="table table-striped" style="word-break:break-all;">
                        <tbody>
                        @foreach($type2s as $type2)
                            {{ Form::open(['route' => ['links.update_type',$type2->id], 'method' => 'patch','id'=>'update'.$type2->id]) }}
                                @csrf
                                @method('patch')
                            <tr>
                                <td width="120">
                                    <select name="type_id" class="form-control" required>
                                        @foreach($type_array as $k=>$v)
                                            <?php
                                                $selected = ($type2->type_id==$k)?"selected":null;
                                            ?>
                                            <option value="{{ $k }}" {{ $selected }}>{{ $v }}</option>
                                        @endforeach
                                    </select>                                    
                                </td>
                                <td width="100">
                                    {{ Form::number('order_by',$type2->order_by,['id'=>'order_by','class' => 'form-control', 'placeholder' => '數字']) }}
                                </td>
                                <td>
                                    {{ Form::text('name',$type2->name,['id'=>'name','class' => 'form-control','required'=>'required', 'placeholder' => '名稱']) }}
                                </td>                                
                                <td nowrap>
                                    <button onclick="return confirm('儲存修改？')" class="btn btn-primary btn-sm"><i class="fas fa-save"></i></button>
                                    <a href="#" class="text-danger" onclick="if(confirm('確定刪除？會一併刪除所屬連結喔！')) document.getElementById('delete{{ $type2->id }}').submit();else return false;"><i class="fas fa-times-circle"></i></a>
                                </td>
                            </tr>
                            {{ Form::close() }}
                            {{ Form::open(['route' => ['links.destroy_type',$type2->id], 'method' => 'DELETE','id'=>'delete'.$type2->id]) }}
                            {{ Form::close() }}
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <a href="{{ route('links.create',$type->id) }}" class="btn btn-success btn-sm" id="go_create"><i class="fas fa-plus"></i> 新增連結</a><br><br>
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <?php $p=1; ?>
                @foreach($types as $type)
                    @if($p==1)
                        <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="home-tab" data-toggle="tab" data-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true" onclick="change_create_id({{ $type->id }})">{{ $type->name }}</button>
                        </li>
                    @else
                        <li class="nav-item" role="presentation">
                        <button class="nav-link" id="link{{ $p }}-tab" data-toggle="tab" data-target="#link{{ $p }}" type="button" role="tab" aria-controls="link{{ $p }}" aria-selected="false" onclick="change_create_id({{ $type->id }})">{{ $type->name}}</button>
                        </li>
                    @endif
                    <?php $p++; ?>
                @endforeach
              </ul>
              <div class="tab-content" id="myTabContent">
                <?php $p=1; ?>
                @foreach($types as $type)
                    @if($p==1)
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            
                            <table class="table table-striped" style="word-break:break-all;">
                                <thead class="thead-light">
                                <tr>
                                    <th width="100">類別</th>
                                    <th width="60">排序</th>
                                    <th>圖示+名稱</th>
                                    <th>目標</th>                                        
                                    <th>動作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $folders = \App\Type::where('type_id',$type->id)->orderBy('order_by')->get();
                                ?>
                                @foreach($folders as $folder)
                                    <tr>
                                        <td>
                                            {{  $type->name }}
                                        </td>
                                        <td>
                                            {{ $folder->order_by }}
                                        </td>
                                        <td>
                                            <i class="fas fa-folder"></i> {{ $folder->name }}
                                        </td>
                                        <td>

                                        </td>
                                        <td>
                                            1
                                        </td>
                                    </tr>
                                    @if(isset($link_data[$folder->id]))
                                        @foreach($link_data[$folder->id] as $k=>$v)
                                        <tr>
                                            <td>
                                                
                                            </td>
                                            <td>
                                                {{ $v['order_by'] }}
                                            </td>
                                            <td>
                                                ---->
                                                @if($v['icon']==null)
                                                <i class="fas fa-globe"></i>
                                                @else
                                                <i class="{{ $v['icon'] }}"></i>
                                                @endif
                                                <a href="{{ $v['url'] }}" target="_blank">{{ $v['name'] }}</a>
                                            </td>
                                            <td>
                                                @if($v['target']==null)
                                                    開新視窗 <i class="fas fa-level-up-alt"></i>
                                                @elseif($v['target']=="_self")
                                                    本視窗
                                                @endif
                                            </td>                                            
                                            <td>
                                                <a href="{{ route('links.edit',$v['id']) }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-edit"></i> 修改</a>
                                                <a href="{{ route('links.delete',$v['id']) }}" class="btn btn-danger btn-sm" onclick="confirm('確定刪除？')"><i class="fas fa-trash"></i> 刪除</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @endif
                                @endforeach
                                @if(isset($link_data[$type->id]))
                                    @foreach($link_data[$type->id] as $k=>$v)
                                        <tr>
                                            <td>
                                                {{ $type->name }}
                                            </td>
                                            <td>
                                                {{ $v['order_by'] }}
                                            </td>
                                            <td>
                                                @if($v['icon']==null)
                                                <i class="fas fa-globe"></i>
                                                @else
                                                <i class="{{ $v['icon'] }}"></i>
                                                @endif
                                                <a href="{{ $v['url'] }}" target="_blank">{{ $v['name'] }}</a>
                                            </td>
                                            <td>
                                                @if($v['target']==null)
                                                    開新視窗 <i class="fas fa-level-up-alt"></i>
                                                @elseif($v['target']=="_self")
                                                    本視窗
                                                @endif
                                            </td>                                            
                                            <td>
                                                <a href="{{ route('links.edit',$v['id']) }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-edit"></i> 修改</a>
                                                <a href="{{ route('links.delete',$v['id']) }}" class="btn btn-danger btn-sm" onclick="confirm('確定刪除？')"><i class="fas fa-trash"></i> 刪除</a>
                                            </td>
                                        </tr>                                        
                                    @endforeach
                                @endif
                                </tbody>
                            </table>                            
                        </div>
                    @else
                        <div class="tab-pane fade" id="link{{ $p }}" role="tabpanel" aria-labelledby="link{{ $p }}-tab">
                            
                                <table class="table table-striped" style="word-break:break-all;">
                                    <thead class="thead-light">
                                    <tr>
                                        <th width="100">類別</th>
                                        <th width="60">排序</th>
                                        <th>圖示+名稱</th>
                                        <th>目標</th>                                        
                                        <th>動作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        $folders = \App\Type::where('type_id',$type->id)->orderBy('order_by')->get();
                                    ?>
                                    @foreach($folders as $folder)
                                        <tr>
                                            <td>
                                                {{  $type->name }}
                                            </td>
                                            <td>
                                                {{ $folder->order_by }}
                                            </td>
                                            <td>
                                                <i class="fas fa-folder"></i> {{ $folder->name }}
                                            </td>
                                            <td>

                                            </td>
                                            <td>
                                                
                                            </td>
                                        </tr>
                                        @if(isset($link_data[$folder->id]))
                                            @foreach($link_data[$folder->id] as $k=>$v)
                                            <tr>
                                                <td>
                                                    
                                                </td>
                                                <td>
                                                    {{ $v['order_by'] }}
                                                </td>
                                                <td>
                                                    ---->
                                                    @if($v['icon']==null)
                                                    <i class="fas fa-globe"></i>
                                                    @else
                                                    <i class="{{ $v['icon'] }}"></i>
                                                    @endif
                                                    <a href="{{ $v['url'] }}" target="_blank">{{ $v['name'] }}</a>
                                                </td>
                                                <td>
                                                    @if($v['target']==null)
                                                        開新視窗 <i class="fas fa-level-up-alt"></i>
                                                    @elseif($v['target']=="_self")
                                                        本視窗
                                                    @endif
                                                </td>                                            
                                                <td>
                                                    <a href="{{ route('links.edit',$v['id']) }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-edit"></i> 修改</a>
                                                    <a href="{{ route('links.delete',$v['id']) }}" class="btn btn-danger btn-sm" onclick="confirm('確定刪除？')"><i class="fas fa-trash"></i> 刪除</a>
                                                </td>
                                            </tr>                                            
                                            @endforeach
                                        @endif
                                    @endforeach
                                    @if(isset($link_data[$type->id]))
                                        @foreach($link_data[$type->id] as $k=>$v)
                                            <tr>
                                                <td>
                                                    {{ $type->name }}
                                                </td>
                                                <td>
                                                    {{ $v['order_by'] }}
                                                </td>
                                                <td>
                                                    @if($v['icon']==null)
                                                    <i class="fas fa-globe"></i>
                                                    @else
                                                    <i class="{{ $v['icon'] }}"></i>
                                                    @endif
                                                    <a href="{{ $v['url'] }}" target="_blank">{{ $v['name'] }}</a>
                                                </td>
                                                <td>
                                                    @if($v['target']==null)
                                                        開新視窗 <i class="fas fa-level-up-alt"></i>
                                                    @elseif($v['target']=="_self")
                                                        本視窗
                                                    @endif
                                                </td>                                            
                                                <td>
                                                    <a href="{{ route('links.edit',$v['id']) }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-edit"></i> 修改</a>
                                                    <a href="{{ route('links.delete',$v['id']) }}" class="btn btn-danger btn-sm" onclick="confirm('確定刪除？')"><i class="fas fa-trash"></i> 刪除</a>
                                                </td>
                                            </tr>                                            
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                          
                        </div>
                    @endif
                <?php $p++; ?>
                @endforeach
                </div>  
        </div>
    </div>
    <script>
        var validator = $("#this_form1").validate();
        function change_create_id(id){
            $('#go_create').attr('href', '{{ route("links.create") }}'+'/'+id);
        }
    </script>
    @if(!empty($type_id))    
        <?php $p=1; ?>
        @foreach($types as $type)
            @if($type->id == $type_id)
                @if($p != 1)  
                    <script>                                  
                        $("#home-tab").removeClass("active");
                        $("#home").removeClass("show");
                        $("#home").removeClass("active");
                        $("#link{{ $p }}-tab").addClass("active");
                        $("#link{{ $p }}").addClass("show");
                        $("#link{{ $p }}").addClass("active");
                    </script>
                @endif
            @endif
            <?php $p++; ?>
        @endforeach    
    @endif
@endsection
