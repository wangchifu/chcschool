@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '社團資訊-')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>社團資訊</h1>
            @include('clubs.nav')
            <br>
            <a class="btn btn-secondary btn-sm" href="{{ route('clubs.parents_do',$class_id) }}"><i class="fas fa-backward"></i> 返回</a>
            <div class="card">
                <div class="card-header">
                <h4>
                    {{ $user->semester }} {{ $club->name }} 報名資訊
                </h4>
                    <?php
                    $taking = $club->taking;
                    $prepare = $club->prepare;
                    ?>
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <tr>
                            <th>
                                順位
                            </th>
                            <th>
                                班級座號
                            </th>
                            <th>
                                姓名
                            </th>
                            <th>
                                報名時間
                            </th>
                        </tr>
                        <?php
                            $i=1;
                            $j=1;
                        ?>
                        @foreach($club_registers as $club_register)
                            @if($i <= $taking)
                                <tr>
                            @endif
                            @if($i > $taking and $j <= $prepare)
                                <tr bgcolor="gray">
                            @endif
                                <td>
                                    @if($i <= $taking)
                                        正取{{ $i }}
                                    @endif
                                    @if($i > $taking and $j <= $prepare)
                                        候補{{ $j }}
                                        <?php $j++; ?>
                                    @endif
                                </td>
                                <td>
                                    {{ $club_register->user->class_num }}
                                </td>
                                <td>
                                    {{ $club_register->user->name }}
                                </td>
                                <td>
                                    {{ $club_register->created_at }}
                                </td>
                            </tr>
                            <?php $i++; ?>
                        @endforeach
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection
