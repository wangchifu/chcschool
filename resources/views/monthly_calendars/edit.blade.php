<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>修改校務行程</title>

    <!-- Bootstrap 4 / FontAwesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Flatpickr CSS & JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/zh-tw.js"></script>

    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }
    </style>
</head>
<body>
    <?php
    $kind_colors = [
        0 => '#01579b', // 校務行政 (深藍)
        1 => '#0b6623', // 教務 (深綠)
        2 => '#bf360c', // 學務 (磚紅)
        3 => '#4e342e', // 總務 (深棕)
        4 => '#6a1b9a', // 輔導室 (深紫)
    ];

    $calendarKinds = config('chcschool.calendar_kind', [
        0 => '校務行政',
        1 => '教務',
        2 => '學務',
        3 => '總務',
        4 => '輔導室'
    ]);
    ?>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white font-weight-bold">
            <i class="fas fa-edit me-1"></i> 修改校務行程
        </div>
        <div class="card-body">
            {{ Form::open(['route' => ['monthly_calendars.update', $monthly_calendar->id], 'method' => 'POST']) }}
                <!-- 處室類別 -->
                <div class="form-group">
                    <label for="item_kind" class="font-weight-bold">處室類別：</label>
                    <select name="item_kind" id="item_kind" class="form-control text-white font-weight-bold" onchange="updateSelectColor(this)">
                        @foreach($calendarKinds as $kValue => $kName)
                            @php $optBg = $kind_colors[$kValue] ?? '#01579b'; @endphp
                            <option value="{{ $kValue }}" style="background-color: {{ $optBg }}; color: #ffffff;" {{ $monthly_calendar->item_kind == $kValue ? 'selected' : '' }}>
                                {{ $kName }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- 開始與結束日期 -->
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="item_date" class="font-weight-bold">開始日期 <span class="text-danger">*</span>：</label>
                        <input id="item_date" name="item_date" class="form-control" required value="{{ $monthly_calendar->item_date }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="item_edate" class="font-weight-bold">結束日期 (選填)：</label>
                        <input id="item_edate" name="item_edate" class="form-control" value="{{ $monthly_calendar->item_edate }}">
                    </div>
                </div>

                <!-- 行程時間 -->
                <div class="form-group">
                    <label for="item_time" class="font-weight-bold">行程時間 (選填)：</label>
                    <input id="item_time" name="item_time" class="form-control" value="{{ $monthly_calendar->item_time }}" placeholder="例如：14:30">
                </div>

                <!-- 事項內容 -->
                <div class="form-group">
                    <label for="item" class="font-weight-bold">事項內容 <span class="text-danger">*</span>：</label>
                    <input id="item" name="item" class="form-control" required value="{{ $monthly_calendar->item }}">
                </div>

                <div class="d-flex justify-content-between pt-2">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i> 儲存修改
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="window.close();">
                        <i class="fas fa-times me-1"></i> 取消關閉
                    </button>
                </div>
            {{ Form::close() }}
        </div>
    </div>

    <script>
        function updateSelectColor(select) {
            if (!select) return;
            const selectedOption = select.options[select.selectedIndex];
            if (selectedOption) {
                select.style.backgroundColor = selectedOption.style.backgroundColor;
                select.style.color = '#ffffff';
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            const itemKindSelect = document.getElementById("item_kind");
            if (itemKindSelect) {
                updateSelectColor(itemKindSelect);
            }
        });

        flatpickr("#item_date, #item_edate", {
            locale: "zh_tw",
            dateFormat: "Y-m-d",
            allowInput: true,
            disableMobile: true
        });

        flatpickr("#item_time", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true,
            minuteIncrement: 5
        });
    </script>
</body>
</html>
