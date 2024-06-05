@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', '圖片連結 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>圖片連結</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">首頁</a></li>
                    <li class="breadcrumb-item active" aria-current="page">圖片連結</li>
                </ol>
            </nav>
            <table class="table table-striped" style="word-break:break-all;">
                <thead class="thead-light">
                <tr>
                    <th>排序</th>
                    <th>名稱</th>
                    <th>代表圖片</th>
                </tr>
                </thead>
                <tbody>
                @foreach($photo_links as $photo_link)
                    <tr>
                        <td>
                            {{ $photo_link->order_by }}
                        </td>
                        <td>
                            <a href="{{ $photo_link->url }}" target="_blank">{{ $photo_link->name }}</a>
                        </td>
                        <td>
                            <?php
                                $school_code = school_code();
                                $img = "storage/".$school_code.'/photo_links/'.$photo_link->image;
                            ?>
                            <img src="{{ asset($img) }}" height="50">
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $photo_links->links() }}
        </div>
    </div>
@endsection
