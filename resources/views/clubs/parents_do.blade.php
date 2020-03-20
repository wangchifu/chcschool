@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '社團報名-')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>{{ $user->semester }}學期 社團報名</h1>

            @include('clubs.nav')

            <br>
            <div class="card">
                <div class="card-header">
                    社團列表
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <tr>
                            <th>
                                編號
                            </th>
                            <th>
                                名稱
                            </th>
                            <th>
                                收費
                            </th>
                            <th>
                                上課時間
                            </th>
                            <th>
                                年級
                            </th>
                            <th>
                                正取/備取
                            </th>
                            <th>
                                已報
                            </th>
                            <th>
                                動作
                            </th>
                        </tr>
                        @foreach($clubs as $club)
                            <tr>
                                <td>
                                    {{ $club->no }}
                                </td>
                                <td>
                                    {{ $club->name }}
                                </td>
                                <td>
                                    {{ $club->money }}
                                </td>
                                <td>
                                    {{ $club->start_time }}
                                </td>
                                <td>
                                    {{ $club->year_limit }}
                                </td>
                                <th>
                                    {{ $club->taking }} / {{ $club->prepare }}
                                </th>
                                <th>

                                </th>
                                <td>
                                    <a href="" class="btn btn-success btn-sm"><i class="fas fa-plus-circle"></i> 報名</a>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection
