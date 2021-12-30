<?php

namespace App\Http\Controllers;

use App\LunchFactory;
use App\LunchOrder;
use App\LunchPlace;
use App\LunchSetup;
use App\LunchTeaDate;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LunchStuController extends Controller
{
    public function __construct()
    {
        $module_setup = get_module_setup();
        if (!isset($module_setup['午餐系統'])) {
            echo "<h1>已停用</h1>";
            die();
        }
    }

    public function index()
    {

    }
}
