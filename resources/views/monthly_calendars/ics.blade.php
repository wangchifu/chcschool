@extends('layouts.master')

@section('nav_school_active', 'active')

@section('title', '校務月曆 | ')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>校務月曆</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">首頁</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('monthly_calendars.index') }}">校務月曆</a></li>
                    <li class="breadcrumb-item active" aria-current="page">從 Google ICS 匯入</li>
                </ol>
            </nav>

            {{ Form::open(['route' => 'monthly_calendars.file_store', 'method' => 'POST', 'id' => 'this_form']) }}

            <div class="d-flex justify-content-between align-items-center mb-3">
                <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('確定要匯入勾選的行程嗎？')">
                    <i class="fas fa-save"></i> 把勾選的匯入
                </button>
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="toggleSelectAll()">
                    <i class="fas fa-check-square"></i> 全選 / 取消全選
                </button>
            </div>

            <hr>

            @forelse($item as $date => $events)
                @php
                    $displayDate = strlen($date) === 8 
                        ? substr($date, 0, 4) . '-' . substr($date, 4, 2) . '-' . substr($date, 6, 2) 
                        : $date;
                @endphp

                <div class="card mb-3">
                    <div class="card-header bg-light font-weight-bold">
                        <i class="far fa-calendar-alt"></i> {{ $displayDate }}
                    </div>
                    <div class="card-body py-2">
                        @foreach($events as $v2)
                            @php
                                $id = $v2['id'];
                                $summary = $v2['SUMMARY'] ?? '（無標題）';
                                $startDate = $v2['DTSTART'] ?? '';
                                $endDate = $v2['DTEND'] ?? $startDate;
                                $dateDisplay = $v2['DATE_DISPLAY'] ?? '';
                                $itemTime = $v2['ITEM_TIME'] ?? null;
                                $isMultiDay = ($startDate !== $endDate);
                                $isExpired = !empty($v2['IS_EXPIRED']);
                            @endphp
                            <div class="form-check my-2">
                                {{-- 僅傳送被勾選的 ID 陣列 selected_ids[] --}}
                                <input type="checkbox" 
                                       class="form-check-input event-checkbox" 
                                       id="check_{{ $id }}" 
                                       name="selected_ids[]" 
                                       value="{{ $id }}" 
                                       {{ !$isExpired ? 'checked' : '' }}>

                                <label class="form-check-label {{ $isExpired ? 'text-muted' : '' }}" for="check_{{ $id }}">
                                    {{ $summary }}

                                    @if($itemTime)
                                        <span class="badge {{ $isExpired ? 'badge-secondary' : 'badge-primary' }} ml-1">
                                            <i class="far fa-clock"></i> {{ $itemTime }}
                                        </span>
                                    @endif

                                    @if($isMultiDay && $dateDisplay)
                                        <span class="badge {{ $isExpired ? 'badge-secondary' : 'badge-info' }} ml-1">
                                            <i class="far fa-calendar-week"></i> {{ $dateDisplay }}
                                        </span>
                                    @endif

                                    @if($isExpired)
                                        <span class="badge badge-secondary ml-1">已過期</span>
                                    @endif
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="alert alert-warning" role="alert">
                    <i class="fas fa-exclamation-circle"></i> 目前沒有解析到任何可匯入的行程資料。
                </div>
            @endforelse

            <hr>

            <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('確定要匯入勾選的行程嗎？')">
                <i class="fas fa-save"></i> 把勾選的匯入
            </button>
            
            {{ Form::close() }}
        </div>
    </div>

    <script>
        function toggleSelectAll() {
            const checkboxes = document.querySelectorAll('.event-checkbox');
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            checkboxes.forEach(cb => cb.checked = !allChecked);
        }
    </script>
@endsection