<?php

namespace App\Http\Controllers;

use App\MonthlyCalendar;
use App\Setup;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Redirect;

class MonthlyCalendarController extends Controller
{
    public function __construct()
    {
        $setup = Setup::first();
        if (!empty($setup->close_website)) {
            Redirect::to('close')->send();
        }
        $module_setup = get_module_setup();
        if (!isset($module_setup['校務月曆'])) {
            echo "<h1>已停用</h1>";
            die();
        }
    }

    public function index($month = null)
    {
        $this_month = (empty($month)) ? date('Y-m') : $month;

        // 建議將查詢擴大或一併取出相關欄位
        $items = MonthlyCalendar::where('item_date', 'like', $this_month . '%')
            ->orWhere('item_edate', 'like', $this_month . '%')
            ->get();

        $item_array = [];
        foreach ($items as $item) {
            $item_array[$item->id]['user_id']   = $item->user_id;
            $item_array[$item->id]['item_date'] = $item->item_date;
            $item_array[$item->id]['item_edate'] = $item->item_edate ?? $item->item_date;
            $item_array[$item->id]['item_time']  = $item->item_time ?? '';
            $item_array[$item->id]['item']       = $item->item;
            $item_array[$item->id]['item_kind']  = $item->item_kind;
        }

        $data = [
            'this_month' => $this_month,
            'item_array' => $item_array,
        ];
        return view('monthly_calendars.index', $data);
    }

    public function create($semester)
    {
    }

    public function store(Request $request)
    {
        $att = $request->all();
        $check = MonthlyCalendar::where('item_date', $att['item_date'])
            ->where('item', $att['item'])
            ->first();
        if (empty($check)) {
            MonthlyCalendar::create($att);
        }
        return redirect()->back();
    }

    public function block_store(Request $request)
    {
        $att = $request->all();
        $check = MonthlyCalendar::where('item_date', $att['item_date'])
            ->where('item', $att['item'])
            ->first();
        if (empty($check)) {
            MonthlyCalendar::create($att);
        }
        $result = true;

        echo json_encode($result);
        return;
    }

    public function show($id)
    {
    }

    public function edit()
    {
    }

    public function update(Request $request)
    {
    }

    public function destroy(MonthlyCalendar $monthly_calendar)
    {
        if ($monthly_calendar->user_id == auth()->user()->id or auth()->user()->admin == 1) {
            $monthly_calendar->delete();
        }
        return redirect()->back();
    }

    public function block_destroy(MonthlyCalendar $monthly_calendar)
    {
        if ($monthly_calendar->user_id == auth()->user()->id or auth()->user()->admin == 1) {
            $monthly_calendar->delete();
        }
        $result = true;

        echo json_encode($result);
        return;
    }

    public function file(Request $request)
    {
        if ($request->hasFile('filename')) {
            $file = $request->file('filename');
            $content = file_get_contents($file->getRealPath());

            // 1. 處理長行折疊
            $content = preg_replace("/\r\n[ \t]/", '', $content);

            // 2. 切割事件區塊
            $events = explode('BEGIN:VEVENT', $content);
            array_shift($events);

            $item = [];
            $sessionEvents = [];
            $today = date('Ymd');
            $eventId = 0;

            foreach ($events as $v) {
                $lines = preg_split('/[\r\n]+/', $v);

                $rawDtstart = null;
                $rawDtend = null;
                $isAllDay = false;
                $summary = '（無標題）';

                foreach ($lines as $line) {
                    $line = trim($line);

                    if (strpos($line, 'DTSTART') === 0) {
                        $rawDtstart = preg_replace('/^DTSTART.*?:/i', '', $line);
                    }

                    if (strpos($line, 'DTEND') === 0) {
                        if (strpos($line, 'VALUE=DATE') !== false) {
                            $isAllDay = true;
                        }
                        $rawDtend = preg_replace('/^DTEND.*?:/i', '', $line);
                    }

                    if (strpos($line, 'SUMMARY') === 0) {
                        $summary = preg_replace('/^SUMMARY.*?:/i', '', $line);
                        $summary = str_replace(['\,', '\;', '\N', '\n'], [',', ';', ' ', ' '], $summary);
                        $summary = trim($summary);
                    }
                }

                // 3. 解析 DTSTART 日期與時間
                $dtstart = null;
                $itemTime = null;

                if ($rawDtstart) {
                    if (strpos($rawDtstart, 'T') !== false) {
                        try {
                            if (substr($rawDtstart, -1) === 'Z') {
                                $startCarbon = Carbon::createFromFormat('Ymd\THis\Z', $rawDtstart, 'UTC')
                                    ->setTimezone('Asia/Taipei');
                            } else {
                                $startCarbon = Carbon::createFromFormat('Ymd\THis', substr($rawDtstart, 0, 15), 'Asia/Taipei');
                            }
                            $dtstart = $startCarbon->format('Ymd');
                            $itemTime = $startCarbon->format('H:i');
                        } catch (\Exception $e) {
                            if (preg_match('/(\d{8})/', $rawDtstart, $m)) {
                                $dtstart = $m[1];
                            }
                        }
                    } else {
                        if (preg_match('/(\d{8})/', $rawDtstart, $m)) {
                            $dtstart = $m[1];
                        }
                    }
                }

                // 4. 解析 DTEND 日期
                $dtend = null;
                if ($rawDtend) {
                    if (strpos($rawDtend, 'T') !== false) {
                        try {
                            if (substr($rawDtend, -1) === 'Z') {
                                $endCarbon = Carbon::createFromFormat('Ymd\THis\Z', $rawDtend, 'UTC')
                                    ->setTimezone('Asia/Taipei');
                            } else {
                                $endCarbon = Carbon::createFromFormat('Ymd\THis', substr($rawDtend, 0, 15), 'Asia/Taipei');
                            }
                            $dtend = $endCarbon->format('Ymd');
                        } catch (\Exception $e) {
                            if (preg_match('/(\d{8})/', $rawDtend, $m)) {
                                $dtend = $m[1];
                            }
                        }
                    } else {
                        if (preg_match('/(\d{8})/', $rawDtend, $m)) {
                            $dtend = $m[1];
                        }
                    }
                }

                if ($dtstart) {
                    $actualDtend = !empty($dtend) ? $dtend : $dtstart;

                    // 只有在「全天行程」且「結束日期大於開始日期」時，才將 DTEND 減去 1 天
                    if ($isAllDay && !empty($dtend) && $dtend > $dtstart) {
                        $endDateObj = \DateTime::createFromFormat('Ymd', $dtend);
                        if ($endDateObj) {
                            $endDateObj->modify('-1 day');
                            $actualDtend = $endDateObj->format('Ymd');
                        }
                    }

                    // 確保結束日期不小於開始日期
                    if ($actualDtend < $dtstart) {
                        $actualDtend = $dtstart;
                    }

                    $startDateFormatted = substr($dtstart, 0, 4) . '-' . substr($dtstart, 4, 2) . '-' . substr($dtstart, 6, 2);

                    if ($actualDtend > $dtstart) {
                        $endDateFormatted = substr($actualDtend, 0, 4) . '-' . substr($actualDtend, 4, 2) . '-' . substr($actualDtend, 6, 2);
                        $dateDisplay = $startDateFormatted . ' ~ ' . $endDateFormatted;
                    } else {
                        $dateDisplay = $startDateFormatted;
                    }

                    // 正確於日期計算完成後，才判斷是否過期
                    $isExpired = ($actualDtend < $today);

                    $eventData = [
                        'id'           => $eventId,
                        'DTSTART'      => $dtstart,
                        'DTEND'        => $actualDtend,
                        'ITEM_TIME'    => $itemTime,
                        'DATE_DISPLAY' => $dateDisplay,
                        'SUMMARY'      => $summary,
                        'IS_EXPIRED'   => $isExpired,
                    ];

                    $item[$dtstart][] = $eventData;
                    $sessionEvents[$eventId] = $eventData;
                    $eventId++;
                }
            }

            ksort($item);

            session(['ics_import_events' => $sessionEvents]);
            session()->save();

            return view('monthly_calendars.ics', ['item' => $item]);
        }

        return redirect()->back();
    }

    public function file_store(Request $request)
    {
        // 取得被勾選的行程 ID 陣列
        $selectedIds   = $request->input('selected_ids', []);
        $sessionEvents = session('ics_import_events', []);

        if (empty($selectedIds)) {
            return redirect()->route('monthly_calendars.index')
                ->with('warning', '匯入失敗：未勾選任何行程（若行程已過期，請點選「全選 / 取消全選」或手動勾選後再匯入）。');
        }

        $userId = auth()->user()->id;
        $successCount = 0;
        $existCount = 0;

        foreach ($selectedIds as $id) {
            if (!isset($sessionEvents[$id])) {
                continue;
            }

            $event     = $sessionEvents[$id];
            $summary   = trim($event['SUMMARY'] ?? '');
            $startDate = trim($event['DTSTART'] ?? '');
            $endDate   = trim($event['DTEND'] ?? $startDate);
            $itemTime  = $event['ITEM_TIME'] ?? null; // 取得時間

            if (empty($startDate) || empty($summary)) {
                continue;
            }

            // 格式化 YYYYMMDD -> YYYY-MM-DD
            if (strlen($startDate) === 8 && is_numeric($startDate)) {
                $startDate = substr($startDate, 0, 4) . '-' . substr($startDate, 4, 2) . '-' . substr($startDate, 6, 2);
            }
            if (strlen($endDate) === 8 && is_numeric($endDate)) {
                $endDate = substr($endDate, 0, 4) . '-' . substr($endDate, 4, 2) . '-' . substr($endDate, 6, 2);
            }

            try {
                // 檢查是否已存在相同開始日期與事項名稱的紀錄
                $check = MonthlyCalendar::where('item_date', $startDate)
                    ->where('item', $summary)
                    ->first();

                if (empty($check)) {
                    MonthlyCalendar::create([
                        'user_id'    => $userId,
                        'item_date'  => $startDate,
                        'item_edate' => ($startDate !== $endDate) ? $endDate : $startDate, // 寫入結束日期
                        'item_time'  => $itemTime,
                        'item'       => $summary,
                        'item_kind'  => 0, // 預設為校務行政 (0)
                    ]);
                    $successCount++;
                } else {
                    $existCount++;
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('校務月曆寫入失敗：' . $e->getMessage());
            }
        }

        // 清除暫存
        session()->forget('ics_import_events');

        if ($successCount > 0) {
            return redirect()->route('monthly_calendars.index')
                ->with('success', "成功匯入 {$successCount} 筆行程事項！");
        }

        if ($existCount > 0) {
            return redirect()->route('monthly_calendars.index')
                ->with('warning', '未新增任何行程（您勾選的行程在資料庫中皆已存在）。');
        }

        return redirect()->route('monthly_calendars.index')
            ->with('warning', '未寫入任何行程，請檢查資料。');
    }

    public function return_month(Request $request)
    {        
        $this_month = $request->input('item_month');        
        $items = MonthlyCalendar::where('item_date', 'like', $this_month . '%')->get();

        $item_array = [];
        foreach ($items as $item) {
            $item_array[$item->id]['user_id'] = $item->user_id;
            $item_array[$item->id]['item_date'] = $item->item_date;
            $item_array[$item->id]['item_edate'] = $item->item_edate ?? $item->item_date;
            $item_array[$item->id]['item_time']  = $item->item_time ?? '';
            $item_array[$item->id]['item'] = $item->item;
            $item_array[$item->id]['item_kind'] = $item->item_kind;
        }

        $d = explode('-', $this_month);
        $dt = Carbon::create($d[0], $d[1], 1);        
        $next_month = $dt->addMonthsNoOverflow(1)->format('Y-m');
        
        $dt2 = Carbon::create($d[0], $d[1], 1);
        $last_month = $dt2->subMonthsNoOverflow(1)->format('Y-m');
        
        $this_month_date = get_month_date($this_month);
        foreach ($this_month_date as $k => $v) {
            $this_month_date_w[$v] = get_date_w($v);
        }
        $first_w = get_date_w($this_month_date[1]);
        $last_w = get_date_w($v);

        $result['item_array'] = $item_array;
        $result['this_month'] = $this_month;
        $result['next_month'] = $next_month;
        $result['last_month'] = $last_month;
        $result['this_month_date'] = $this_month_date;
        $result['this_month_date_w'] = $this_month_date_w;
        $result['first_w'] = $first_w;
        $result['last_w'] = $last_w;
        $result['today'] = date('Y-m-d');
        $result['admin'] = 0;
        if (auth()->check()) {
            $result['user_id'] = auth()->user()->id;
            if (auth()->user()->admin == 1) {
                $result['admin'] = 1;
            }
        } else {
            $result['user_id'] = 0;
        }

        echo json_encode($result);
        return;
    }
}