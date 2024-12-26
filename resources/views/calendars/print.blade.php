<?php $setup = \App\Setup::find(1); ?>
@extends('layouts.master_print')

@section('title',$setup->site_name." ".$semester." 學期校務行事曆")

@section('content')
<h3 class="text-center">{{ $setup->site_name }} {{ $semester }} 學期校務行事曆</h3>
<table class="table table-bordered">
    <thead>
    <tr>
        <th width="80" scope="col">
            週別
        </th>
        <th width="100" scope="col">
            起迄
        </th>
        @foreach(config('chcschool.calendar_kind') as $v)
        <th scope="col" nowrap>
            {{ $v }}
        </th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($calendar_weeks as $calendar_week)
        <tr>
            <td nowrap>
                第 {{ $calendar_week->week }} 週
            </td>
            <td nowrap>
                <small>{{ $calendar_week->start_end }}</small>
            </td>
            @foreach(config('chcschool.calendar_kind') as $k =>$v)
                <th scope="col">
                    @if(!empty($calendar_data[$calendar_week->id][$k]))
                        <?php $i=1; ?>
                        @foreach($calendar_data[$calendar_week->id][$k] as $k=>$v)
                            <small class="text-dark">{{ $i }}.{{ $v['content'] }}</small><br>
                            <?php $i++; ?>
                        @endforeach
                    @endif
                </th>
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>

@endsection
