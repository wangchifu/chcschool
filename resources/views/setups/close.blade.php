@extends('layouts.master_close')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-11">
        <div class="text-center">
            <h1>{{ $setup->close_website }}</h1>
            <img src="{{ asset('images/closed.png') }}" class="img-fluid">
        </div>
        <div class="text-right">
            <a href="{{ route('admin_login_close') }}"><i class="fas fa-cog"></i></a>
        </div>
    </div>
</div>
@endsection