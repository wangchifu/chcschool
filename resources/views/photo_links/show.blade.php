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
            <?php
                $active0 = ($photo_type_id==null)?"active":null;
            ?>
            <ul class="nav nav-tabs">
                <li class="nav-item">
                  <a class="nav-link {{ $active0 }}" href="{{ route('photo_links.show') }}">全部</a>
                </li>
                @foreach($photo_types as $photo_type)
                <?php $active[$photo_type->id] = ($photo_type->id==$photo_type_id)?"active":null; ?>
                    <li class="nav-item">
                    <a class="nav-link {{ $active[$photo_type->id] }}" href="{{ route('photo_links.show',$photo_type->id) }}">{{ $photo_type->name }}</a>
                    </li>
                @endforeach
              </ul>
            <table class="table table-striped" style="word-break:break-all;">
                <thead class="thead-light">
                <tr>
                    <th>排序</th>
                    <th>代表圖片</th>
                    <th>名稱</th>
                </tr>
                </thead>
                <tbody>
                @foreach($photo_links as $photo_link)
                    <tr>
                        <td>
                            {{ $photo_link->order_by }}
                        </td>
                        <td>
                            <?php
                                $school_code = school_code();
                                $img = "storage/".$school_code.'/photo_links/'.$photo_link->image;
                            ?>
                            <style>
                                a:hover img{filter:alpha(Opacity=50);-moz-opacity:0.5;opacity: 0.5;}
                            </style>
                            <a href="{{ $photo_link->url }}" target="_blank">
                                <img src="{{ asset($img) }}" height="50">
                            </a>
                        </td>
                        <td>
                            <a href="{{ $photo_link->url }}" target="_blank">{{ $photo_link->name }}</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $photo_links->links() }}
        </div>
    </div>
@endsection
