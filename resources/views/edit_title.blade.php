@extends('layouts.master')

@section('nav_user_active', 'active')

@section('title', '更改職稱')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3>
                        變更職稱：你目前是「{{ auth()->user()->title }}」 {{ auth()->user()->name }}
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('update_title',auth()->user()->id) }}" method="post">
                        @csrf
                        @method('patch')
                        <div class="form-group">
                            <label for="title">請選擇正確的職稱</label>
                            <select class="form-control" id="title" name="title" tabindex="1" required>
                            @foreach($title_array as $k => $v)
                                <option value="{{ $v }}" {{ (auth()->user()->title == $v) ? 'selected' : '' }}>{{ $v }}</option>
                            @endforeach                            
                            </select>
                        </div>                      
                        <button type="submit" class="btn btn-primary btn-sm" tabindex="2" onclick="return confirm('確定？')"><i class="fas fa-save"></i> 送出</button>
                    </form>
                    @include('layouts.errors')
                </div>
            </div>
        </div>
    </div>
@endsection
