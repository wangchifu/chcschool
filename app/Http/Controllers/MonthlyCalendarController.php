<?php

namespace App\Http\Controllers;

use App\MonthlyCalendar;
use App\Setup;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;

class MonthlyCalendarController extends Controller
{
    public function __construct()
    {
        $setup = Setup::first();
        //檢查有無關閉網站
        if (!empty($setup->close_website)) {
            Redirect::to('close')->send();
        }
        $module_setup = get_module_setup();
        if (!isset($module_setup['校務月曆'])) {
            echo "<h1>已停用</h1>";
            die();
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index($month = null)
    {
        $this_month = (empty($month)) ? date('Y-m') : $month;

        $items = MonthlyCalendar::where('item_date', 'like', $this_month . '%')->get();
        $item_array = [];
        foreach ($items as $item) {
            $item_array[$item->id]['user_id'] = $item->user_id;
            $item_array[$item->id]['item_date'] = $item->item_date;
            $item_array[$item->id]['item'] = $item->item;
        }

        $data = [
            'this_month' => $this_month,
            'item_array' => $item_array,
        ];
        return view('monthly_calendars.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($semester)
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
        //處理檔案上傳
        if ($request->hasFile('filename')) {
            $filename = $request->file('filename');

            $file = fopen($filename, "r");
            $str = "";
            if ($file != NULL) {
                while (!feof($file)) {
                    $str .= fgets($file);
                }
                fclose($file);
            }
            $str_array = explode('BEGIN:VEVENT', $str);

            //
            $item = [];
            foreach ($str_array as $k => $v) {
                if ($k > 0) {
                    $str_array2 = preg_split('/[\r\n]+/s', $v);
                    foreach ($str_array2 as $k1 => $v1) {
                        if (substr($v1, 0, 7) == "DTSTART") {
                            $dtstart = substr($v1, -8);
                            $item[$dtstart][$k]['DTSTART'] = substr($v1, -8);
                        }
                        if (substr($v1, 0, 5) == "DTEND") {
                            $item[$dtstart][$k]['DTEND'] = substr($v1, -8);
                        }
                        if (substr($v1, 0, 7) == "SUMMARY") {
                            $item[$dtstart][$k]['SUMMARY'] = str_replace('SUMMARY:', '', $v1);
                        }
                    }
                }
            }

            ksort($item);

            $data = [
                'item' => $item,
            ];
            return view('monthly_calendars.ics', $data);
        }

        return redirect()->back();
    }

    public function file_store(Request $request)
    {
        $items = $request->input('items');
        $att['user_id'] = auth()->user()->id;
        foreach ($items as $k => $v) {
            $att['item_date'] = substr($k, 0, 4) . '-' . substr($k, 4, 2) . '-' . substr($k, 6, 2);
            $att['item'] = $v;
            $check = MonthlyCalendar::where('item_date', $att['item_date'])
                ->where('item', $att['item'])
                ->first();
            if (empty($check)) {
                MonthlyCalendar::create($att);
            }
        }
        return redirect()->route('monthly_calendars.index');
    }

    public function return_month(Request $request)
    {
        $this_month = $request->input('item_month');
        $items = MonthlyCalendar::where('item_date', 'like', $this_month . '%')->get();

        $item_array = [];
        foreach ($items as $item) {
            $item_array[$item->id]['user_id'] = $item->user_id;
            $item_array[$item->id]['item_date'] = $item->item_date;
            $item_array[$item->id]['item'] = $item->item;
        }

        $d = explode('-', $this_month);
        $dt = Carbon::create($d[0], $d[1]);
        $next_month =  substr($dt->addMonth()->toDateTimeString(), 0, 7);
        $dt = Carbon::create($d[0], $d[1]);
        $last_month =  substr($dt->subMonth()->toDateTimeString(), 0, 7);

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
