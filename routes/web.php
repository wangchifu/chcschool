<?php
if (isset($_SERVER['REQUEST_URI'])) {
    if (strpos($_SERVER['REQUEST_URI'], '//')) {
        die('what do you want!!??');
    }
};
//檢查有無新版本的sql檔
$sqls = get_files(database_path('sqls'));

if (isset($_SERVER['HTTP_HOST'])) {
    $install_sqls = \App\Sql::where('install', 1)->pluck('name')->toArray();

    foreach ($sqls as $k => $v) {
        if (!in_array($v, $install_sqls)) {
            $file = database_path('sqls') . '/' . $v;
            \Illuminate\Support\Facades\DB::unprepared(file_get_contents($file));
            $att['name'] = $v;
            $att['install'] = 1;
            \App\Sql::create($att);
        }
    }
}
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@index')->name('index');
Route::post('not_bot', 'HomeController@not_bot')->name('not_bot');
//Auth::routes();
#登入
Route::get('login', 'Auth\LoginController@showLoginForm')->name('admin_login');
//Route::post('login', 'Auth\LoginController@login');
Route::post('login', 'Auth\MLoginController@auth')->name('auth');

#登出
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

#註冊
//Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
//Route::post('register', 'Auth\RegisterController@register')->name('register.post');

#忘記密碼
//Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
//Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
//Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
//Route::post('password/reset', 'Auth\ResetPasswordController@reset');

//gsuite登入
Route::get('glogin', 'Auth\GLoginController@showLoginForm')->name('login');
Route::post('glogin', 'Auth\GLoginController@auth')->name('gauth');

Route::get('pic', 'HomeController@pic')->name('pic');


//公告系統
Route::get('posts', 'PostsController@index')->name('posts.index');
//Route::get('posts/insite' , 'PostsController@insite')->name('posts.insite');
//Route::get('posts/honor' , 'PostsController@honor')->name('posts.honor');
Route::get('posts/{post}', 'PostsController@show')->where('post', '[0-9]+')->name('posts.show');
Route::match(['post', 'get'], 'posts/search/{search?}', 'PostsController@search')->name('posts.search');
Route::get('posts/{job_title}/job_title', 'PostsController@job_title')->name('posts.job_title');
Route::get('posts/{type}/type', 'PostsController@type')->name('posts.type');
Route::post('posts/select_type', 'PostsController@select_type')->name('posts.select_type');

//公開文件
Route::get('open_files/{path?}', 'OpenFileController@index')->name('open_files.index');
Route::get('open_files_download/{path}', 'OpenFileController@download')->name('open_files.download');

//圖片連結
Route::get('photo_links/show', 'PhotoLinksController@show')->name('photo_links.show');

//內容頁面
Route::get('contents/{content}/show', 'ContentsController@show')->where('content', '[0-9]+')->name('contents.show');
//處室
Route::get('departments/{department}/show', 'DepartmentController@show')->name('departments.show');

//校務行事曆
Route::get('calendars/index/{semester?}', 'CalendarController@index')->name('calendars.index');
Route::get('calendars/print/{semester}', 'CalendarController@print')->name('calendars.print');

//廠商頁面
Route::match(['get', 'post'], 'lunch_lists/factory/{lunch_order_id?}', 'LunchListController@factory')->name('lunch_lists.factory');
Route::get('lunch_lists/change_factory/', 'LunchListController@change_factory')->name('lunch_lists.change_factory');


//社團家長頁面
Route::get('clubs/semester_select', 'ClubsController@semester_select')->name('clubs.semester_select');
Route::get('clubs/{semester}/{class_id}/parents_login', 'ClubsController@parents_login')->name('clubs.parents_login');
Route::post('clubs/do_login', 'ClubsController@do_login')->name('clubs.do_login');
Route::get('clubs/parents_do/{class_id}', 'ClubsController@parents_do')->name('clubs.parents_do');
Route::get('clubs/parents_logout', 'ClubsController@parents_logout')->name('clubs.parents_logout');
Route::get('clubs/{class_id}/change_pwd', 'ClubsController@change_pwd')->name('clubs.change_pwd');
Route::patch('clubs/change_pwd_do', 'ClubsController@change_pwd_do')->name('clubs.change_pwd_do');
Route::post('clubs/{club_student}/get_telephone', 'ClubsController@get_telephone')->name('clubs.get_telephone');
Route::get('clubs/{club}/show_club', 'ClubsController@show_club')->name('clubs.show_club');
Route::get('clubs/{club}/sign_up', 'ClubsController@sign_up')->name('clubs.sign_up');
Route::get('clubs/{club_id}/sign_down', 'ClubsController@sign_down')->name('clubs.sign_down');
Route::get('clubs/{club}/{class_id}/sign_show', 'ClubsController@sign_show')->name('clubs.sign_show');


//校園部落格
Route::get('blogs', 'BlogsController@index')->name('blogs.index');
Route::get('blogs/{blog}', 'BlogsController@show')->where('blog', '[0-9]+')->name('blogs.show');

//行政待辦
Route::get('tasks/index', 'TaskController@index')->name('tasks.index');
Route::get('tasks/index2', 'TaskController@index2')->name('tasks.index2');
Route::get('tasks/index3', 'TaskController@index3')->name('tasks.index3');
Route::get('tasks/self', 'TaskController@self')->name('tasks.self');
Route::get('tasks/logout', 'TaskController@logout')->name('tasks.logout');
Route::get('tasks/{task}/disable', 'TaskController@disable')->name('tasks.disable');
Route::post('tasks/store', 'TaskController@store')->name('tasks.store');
Route::post('tasks/self_store', 'TaskController@self_store')->name('tasks.self_store');
Route::post('tasks/user_condition', 'TaskController@user_condition')->name('tasks.user_condition');

//登入的使用者可用
Route::group(['middleware' => 'auth'], function () {
    //結束模擬
    Route::get('sims/impersonate_leave', 'SimulationController@impersonate_leave')->name('sims.impersonate_leave');

    //下載上傳的檔案
    Route::get('file/{file}', 'HomeController@getFile')->name('getFile');

    //打開上傳的檔案
    Route::get('file_open/{file}', 'HomeController@openFile')->name('openFile');

    //會議文稿
    Route::get('meetings', 'MeetingController@index')->name('meetings.index');
    Route::get('meetings/{meeting}', 'MeetingController@show')->where('meeting', '[0-9]+')->name('meetings.show');
    Route::get('meetings/{meeting}/download', 'MeetingController@txtDown')->name('meetings.txtDown');

    //報修系統
    Route::get('fixes', 'FixController@index')->name('fixes.index');
    Route::get('fixes_search/{situation}/type', 'FixController@search')->name('fixes.search');
    Route::get('fixes/{fix}', 'FixController@show')->where('fix', '[0-9]+')->name('fixes.show');
    Route::get('fixes/create', 'FixController@create')->name('fixes.create');
    Route::post('fixes', 'FixController@store')->name('fixes.store');
    Route::match(['delete', 'get'], 'fixes/{fix}/delete', 'FixController@destroy')->name('fixes.destroy');

    //教室預約
    Route::get('classroom_orders/index', 'ClassroomOrderController@index')->name('classroom_orders.index');
    Route::get('classroom_orders/{classroom}/show/{select_sunday?}', 'ClassroomOrderController@show')->name('classroom_orders.show');
    Route::get('classroom_orders/{classroom_id}/{section}/{order_date}/select', 'ClassroomOrderController@select')->name('classroom_orders.select');
    Route::delete('classroom_orders/destroy', 'ClassroomOrderController@destroy')->name('classroom_orders.destroy');

    Route::get('classroom_orders/admin', 'ClassroomOrderController@admin')->name('classroom_orders.admin');
    Route::get('classroom_orders/admin_create', 'ClassroomOrderController@admin_create')->name('classroom_orders.admin_create');
    Route::get('classroom_orders/{classroom}/admin_edit', 'ClassroomOrderController@admin_edit')->name('classroom_orders.admin_edit');
    Route::patch('classroom_orders/{classroom}/admin_update', 'ClassroomOrderController@admin_update')->name('classroom_orders.admin_update');
    Route::post('classroom_orders/admin_store', 'ClassroomOrderController@admin_store')->name('classroom_orders.admin_store');
    Route::get('classroom_orders/{classroom}/admin_destroy', 'ClassroomOrderController@admin_destroy')->name('classroom_orders.admin_destroy');

    //午餐系統
    Route::get('lunches/{lunch_order_id?}', 'LunchController@index')->name('lunches.index');
    Route::post('lunches', 'LunchController@store')->name('lunches.store');
    Route::patch('lunches', 'LunchController@update')->name('lunches.update');

    Route::get('lunch_setup', 'LunchSetupController@index')->name('lunch_setups.index');
    Route::get('lunch_setup/create', 'LunchSetupController@create')->name('lunch_setups.create');
    Route::post('lunch_setup/store', 'LunchSetupController@store')->name('lunch_setups.store');
    Route::get('lunch_setup/{lunch_setup}/edit', 'LunchSetupController@edit')->name('lunch_setups.edit');
    Route::patch('lunch_setup/{lunch_setup}/update', 'LunchSetupController@update')->name('lunch_setups.update');
    Route::delete('lunch_setup/{lunch_setup}/destroy', 'LunchSetupController@destroy')->name('lunch_setups.destroy');
    Route::get('lunch_setup/{path}/{id}/del_file', 'LunchSetupController@del_file')->name('lunch_setups.del_file');
    Route::post('lunch_setup/place_add', 'LunchSetupController@place_add')->name('lunch_setups.place_add');
    Route::patch('lunch_setup/{lunch_place}/place_update', 'LunchSetupController@place_update')->name('lunch_setups.place_update');
    Route::post('lunch_setup/factory_add', 'LunchSetupController@factory_add')->name('lunch_setups.factory_add');
    Route::patch('lunch_setup/{lunch_factory}/factory_update', 'LunchSetupController@factory_update')->name('lunch_setups.factory_update');
    Route::post('lunch_setup/stu_store', 'LunchSetupController@stu_store')->name('lunch_setups.stu_store');
    Route::get('lunch_setup/{semester}/stu_more/{student_class_id?}', 'LunchSetupController@stu_more')->name('lunch_setups.stu_more');

    Route::get('lunch_orders/index', 'LunchOrderController@index')->name('lunch_orders.index');
    Route::get('lunch_orders/{semester}/create', 'LunchOrderController@create')->name('lunch_orders.create');
    Route::post('lunch_orders/store', 'LunchOrderController@store')->name('lunch_orders.store');
    Route::get('lunch_orders/{semester}/edit', 'LunchOrderController@edit')->name('lunch_orders.edit');
    Route::get('lunch_orders/{lunch_order}/edit_order', 'LunchOrderController@edit_order')->name('lunch_orders.edit_order');
    Route::patch('lunch_orders/{lunch_order}/order_save', 'LunchOrderController@order_save')->name('lunch_orders.order_save');
    Route::patch('lunch_orders/update', 'LunchOrderController@update')->name('lunch_orders.update');

    Route::get('lunch_specials/index', 'LunchSpecialController@index')->name('lunch_specials.index');
    Route::get('lunch_specials/one_day', 'LunchSpecialController@one_day')->name('lunch_specials.one_day');
    Route::post('lunch_specials/one_day_store', 'LunchSpecialController@one_day_store')->name('lunch_specials.one_day_store');
    Route::get('lunch_specials/late_teacher', 'LunchSpecialController@late_teacher')->name('lunch_specials.late_teacher');
    Route::post('lunch_specials/late_teacher_show', 'LunchSpecialController@late_teacher_show')->name('lunch_specials.late_teacher_show');
    Route::post('lunch_specials/late_teacher選單連結_store', 'LunchSpecialController@late_teacher_store')->name('lunch_specials.late_teacher_store');
    Route::get('lunch_specials/teacher_change_month', 'LunchSpecialController@teacher_change_month')->name('lunch_specials.teacher_change_month');
    Route::post('lunch_specials/teacher_change_month_show', 'LunchSpecialController@teacher_change_month_show')->name('lunch_specials.teacher_change_month_show');
    Route::post('lunch_specials/teacher_update_month', 'LunchSpecialController@teacher_update_month')->name('lunch_specials.teacher_update_month');
    Route::get('lunch_specials/teacher_change', 'LunchSpecialController@teacher_change')->name('lunch_specials.teacher_change');
    Route::post('lunch_specials/teacher_change_store', 'LunchSpecialController@teacher_change_store')->name('lunch_specials.teacher_change_store');
    Route::get('lunch_specials/bad_factory', 'LunchSpecialController@bad_factory')->name('lunch_specials.bad_factory');
    Route::post('lunch_specials/bad_factory2', 'LunchSpecialController@bad_factory2')->name('lunch_specials.bad_factory2');
    Route::post('lunch_specials/bad_factory3', 'LunchSpecialController@bad_factory3')->name('lunch_specials.bad_factory3');
    Route::get('lunch_specials/add7', 'LunchSpecialController@add7')->name('lunch_specials.add7');
    Route::post('lunch_specials/store7', 'LunchSpecialController@store7')->name('lunch_specials.store7');

    Route::get('lunch_lists/index', 'LunchListController@index')->name('lunch_lists.index');
    Route::get('lunch_lists/more_list_factory/{lunch_order_id}/{factory_id}', 'LunchListController@more_list_factory')->name('lunch_lists.more_list_factory');
    Route::get('lunch_lists/every_day/{lunch_order_id?}', 'LunchListController@every_day')->name('lunch_lists.every_day');
    Route::get('lunch_lists/teacher_money_print/{lunch_order_id}', 'LunchListController@teacher_money_print')->name('lunch_lists.teacher_money_print');
    Route::get('lunch_lists/every_day_download/{lunch_order_id}', 'LunchListController@every_day_download')->name('lunch_lists.every_day_download');
    Route::get('lunch_lists/call_money/{lunch_order_id}', 'LunchListController@call_money')->name('lunch_lists.call_money');
    Route::get('lunch_lists/get_money/{lunch_order_id}', 'LunchListController@get_money')->name('lunch_lists.get_money');
    Route::get('lunch_lists/all_semester', 'LunchListController@all_semester')->name('lunch_lists.all_semester');
    Route::post('lunch_lists/semester_print', 'LunchListController@semester_print')->name('lunch_lists.semester_print');

    Route::get('lunch_stus/index/{semester?}', 'LunchStuController@index')->name('lunch_stus.index');
    Route::get('lunch_stus/delete/{semester}', 'LunchStuController@delete')->name('lunch_stus.delete');
    Route::post('lunch_stus/store/{semester}', 'LunchStuController@store')->name('lunch_stus.store');
    Route::post('lunch_stus/change_num', 'LunchStuController@change_num')->name('lunch_stus.change_num');
    Route::post('lunch_stus/store_ps/{lunch_order}', 'LunchStuController@store_ps')->name('lunch_stus.store_ps');

    //顯示上傳的圖片
    Route::get('img/{path}', 'HomeController@getImg')->name('getImg');

    Route::get('teacher_absents/index/{select_semester?}', 'TeacherAbsentController@index')->name('teacher_absents.index');
    Route::get('teacher_absents/create', 'TeacherAbsentController@create')->name('teacher_absents.create');
    Route::post('teacher_absents/store', 'TeacherAbsentController@store')->name('teacher_absents.store');
    Route::get('teacher_absents/{teacher_absent}/edit', 'TeacherAbsentController@edit')->name('teacher_absents.edit');
    Route::get('teacher_absents/{filename}/{teacher_absent}/{type}/delete_file', 'TeacherAbsentController@delete_file')->name('teacher_absents.delete_file');
    Route::get('teacher_absents/{teacher_absent}/destroy', 'TeacherAbsentController@destroy')->name('teacher_absents.destroy');
    Route::patch('teacher_absents/{teacher_absent}/update', 'TeacherAbsentController@update')->name('teacher_absents.update');

    Route::get('teacher_absents/deputy/{select_semester?}', 'TeacherAbsentController@deputy')->name('teacher_absents.deputy');
    Route::get('teacher_absents/check/{type}/{teacher_absent}/', 'TeacherAbsentController@check')->name('teacher_absents.check');
    Route::get('teacher_absents/sir/{select_semester?}', 'TeacherAbsentController@sir')->name('teacher_absents.sir');
    Route::get('teacher_absents/total/{select_semester?}/{select_month?}', 'TeacherAbsentController@total')->name('teacher_absents.total');
    Route::get('teacher_absents/list/{select_semester?}/{select_teacher?}/{select_abs?}/{select_month?}', 'TeacherAbsentController@list')->name('teacher_absents.list');

    Route::get('teacher_absents/{teacher_absent}/back', 'TeacherAbsentController@back')->name('teacher_absents.back');
    Route::post('teacher_absents/{teacher_absent}/store_back', 'TeacherAbsentController@store_back')->name('teacher_absents.store_back');

    Route::get('teacher_absents/{teacher_absent}/admin_edit', 'TeacherAbsentController@admin_edit')->name('teacher_absents.admin_edit');
    Route::patch('teacher_absents/{teacher_absent}/admin_update', 'TeacherAbsentController@admin_update')->name('teacher_absents.admin_update');

    Route::get('teacher_absents/travel/{select_semester?}', 'TeacherAbsentController@travel')->name('teacher_absents.travel');
    Route::get('teacher_absents/travel/{teacher_absent}/outlay', 'TeacherAbsentController@outlay')->name('teacher_absents.outlay');
    Route::post('teacher_absents/travel/store_outlay', 'TeacherAbsentController@store_outlay')->name('teacher_absents.store_outlay');
    Route::get('teacher_absents/travel/{teacher_absent_outlay}/delete_outlay', 'TeacherAbsentController@delete_outlay')->name('teacher_absents.delete_outlay');
    Route::get('teacher_absents/travel/{teacher_absent_outlay}/edit_outlay', 'TeacherAbsentController@edit_outlay')->name('teacher_absents.edit_outlay');
    Route::post('teacher_absents/travel/{teacher_absent_outlay}/update_outlay', 'TeacherAbsentController@update_outlay')->name('teacher_absents.update_outlay');
    Route::post('teacher_absents/travel/travel_print', 'TeacherAbsentController@travel_print')->name('teacher_absents.travel_print');

    //內部文件
    Route::get('inside_files/{path?}', 'InsideFilesController@index')->name('inside_files.index');
    Route::get('inside_files_download/{path}', 'InsideFilesController@download')->name('inside_files.download');

    //社團報名
    Route::get('clubs', 'ClubsController@index')->name('clubs.index');
    Route::get('clubs/semester_create', 'ClubsController@semester_create')->name('clubs.semester_create');
    Route::post('clubs/semester_store', 'ClubsController@semester_store')->name('clubs.semester_store');
    Route::get('clubs/{semester}/semester_delete', 'ClubsController@semester_delete')->name('clubs.semester_delete');
    Route::get('clubs/{club_semester}/semester_edit', 'ClubsController@semester_edit')->name('clubs.semester_edit');
    Route::patch('clubs/{club_semester}/semester_update', 'ClubsController@semester_update')->name('clubs.semester_update');
    Route::get('clubs/setup/{semester?}', 'ClubsController@setup')->name('clubs.setup');
    Route::get('clubs/{semester}/club_create', 'ClubsController@club_create')->name('clubs.club_create');
    Route::post('clubs/club_store', 'ClubsController@club_store')->name('clubs.club_store');
    Route::post('clubs/club_copy', 'ClubsController@club_copy')->name('clubs.club_copy');
    Route::get('clubs/{club}/club_edit', 'ClubsController@club_edit')->name('clubs.club_edit');
    Route::patch('clubs/{club}/club_update', 'ClubsController@club_update')->name('clubs.club_update');
    Route::get('clubs/{club}/club_delete', 'ClubsController@club_delete')->name('clubs.club_delete');
    Route::get('clubs/{semester}/stu_adm', 'ClubsController@stu_adm')->name('clubs.stu_adm');
    Route::get('clubs/{semester}/stu_adm_more/{student_class_id?}', 'ClubsController@stu_adm_more')->name('clubs.stu_adm_more');
    Route::post('clubs/{semester}/stu_import', 'ClubsController@stu_import')->name('clubs.stu_import');
    Route::get('clubs/{semester}/stu_create/{student_class}', 'ClubsController@stu_create')->name('clubs.stu_create');
    Route::post('clubs/{semester}/stu_store', 'ClubsController@stu_store')->name('clubs.stu_store');
    Route::get('clubs/{club_student}/stu_edit/{student_class}', 'ClubsController@stu_edit')->name('clubs.stu_edit');
    Route::patch('clubs/{club_student}/stu_update', 'ClubsController@stu_update')->name('clubs.stu_update');
    Route::get('clubs/{club_student}/stu_delete/{student_class_id}', 'ClubsController@stu_delete')->name('clubs.stu_delete');
    Route::get('clubs/{club_student}/stu_disable/{student_class_id}', 'ClubsController@stu_disable')->name('clubs.stu_disable');
    Route::get('clubs/{club_student}/stu_backPWD/{student_class_id}', 'ClubsController@stu_backPWD')->name('clubs.stu_backPWD');

    Route::get('clubs/report_situation/{semester?}', 'ClubsController@report_situation')->name('clubs.report_situation');
    Route::get('clubs/{semester}/report_situation_download/{class_id}', 'ClubsController@report_situation_download')->name('clubs.report_situation_download');
    Route::get('clubs/{club_register}/report_register_delete', 'ClubsController@report_register_delete')->name('clubs.report_register_delete');
    Route::get('clubs/report_money/{semester?}', 'ClubsController@report_money')->name('clubs.report_money');
    Route::get('clubs/{semester}/{class_id}/report_money_download', 'ClubsController@report_money_download')->name('clubs.report_money_download');
    Route::get('clubs/{semester}/{class_id}/report_money_download2', 'ClubsController@report_money_download2')->name('clubs.report_money_download2');
    Route::get('clubs/{semester}/{class_id}/report_money2_print', 'ClubsController@report_money2_print')->name('clubs.report_money2_print');
    Route::get('clubs/report', 'ClubsController@report')->name('clubs.report');

    Route::post('clubs/black', 'ClubsController@black')->name('clubs.store_black');
    Route::get('clubs/{semester}/{club_black}/destroy_black', 'ClubsController@destroy_black')->name('clubs.destroy_black');


    //報錯
    Route::get('wrench/index/{page?}', 'WrenchController@index')->name('wrench.index');
    Route::post('wrench/store', 'WrenchController@store')->name('wrench.store');
    Route::get('wrench/download/{wrench_id}/{filename}', 'WrenchController@download')->name('wrench.download');
});

//行政人員可用
Route::group(['middleware' => 'exec'], function () {

    Route::get('posts/create', 'PostsController@create')->name('posts.create');
    Route::post('posts', 'PostsController@store')->name('posts.store');

    //刪標題圖片
    Route::get('posts/{post}/delete_title_image', 'PostsController@delete_title_image')->name('posts.delete_title_image');
    //刪檔案
    Route::get('posts/{post}/delete_file/{filename}', 'PostsController@delete_file')->name('posts.delete_file');

    //公開文件
    Route::get('open_files_create', 'OpenFileController@create')->name('open_files.create');

    Route::post('open_files_create_folder', 'OpenFileController@create_folder')->name('open_files.create_folder');
    Route::post('open_files_upload_file', 'OpenFileController@upload_file')->name('open_files.upload_file');

    //內部文件
    Route::get('inside_files_create', 'InsideFilesController@create')->name('inside_files.create');

    Route::post('inside_files_create_folder', 'InsideFilesController@create_folder')->name('inside_files.create_folder');
    Route::post('inside_files_upload_file', 'InsideFilesController@upload_file')->name('inside_files.upload_file');


    //報修回復
    Route::patch('fixes/{fix}', 'FixController@update')->name('fixes.update');


    //會議文稿
    Route::get('meetings/create', 'MeetingController@create')->name('meetings.create');
    Route::post('meetings', 'MeetingController@store')->name('meetings.store');
    //報告內容
    Route::get('meetings_reports/{meeting}/create', 'ReportController@create')->name('meetings_reports.create');
    Route::post('meetings_reports', 'ReportController@store')->name('meetings_reports.store');
    Route::get('meetings_reports/{report}/edit', 'ReportController@edit')->name('meetings_reports.edit');
    Route::patch('meetings_reports/{report}', 'ReportController@update')->name('meetings_reports.update');
    Route::delete('meetings_reports/{report}', 'ReportController@destroy')->name('meetings_reports.destroy');
    //刪檔案
    Route::get('meetings_reports/{file}/fileDel', 'ReportController@fileDel')->name('meetings_reports.fileDel');

    //校務行事曆
    Route::get('calendars/{semester}/create', 'CalendarController@create')->name('calendars.create');
    Route::post('calendars', 'CalendarController@store')->name('calendars.store');
    Route::get('calendars/{calendar}/edit', 'CalendarController@edit')->name('calendars.edit');
    Route::patch('calendars/{calendar}', 'CalendarController@update')->name('calendars.update');
    Route::delete('calendars/{calendar}', 'CalendarController@destroy')->name('calendars.destroy');
    Route::get('calendars/{calendar}/delete', 'CalendarController@delete')->name('calendars.delete');
    Route::get('calendars/{calendar}/edit', 'CalendarController@edit')->name('calendars.edit');
    Route::post('calendars/update', 'CalendarController@update')->name('calendars.update');

    //校務月曆
    Route::get('monthly_calendars/index/{month?}', 'MonthlyCalendarController@index')->name('monthly_calendars.index');
    Route::post('monthly_calendars/store', 'MonthlyCalendarController@store')->name('monthly_calendars.store');
    Route::post('monthly_calendars/block_store', 'MonthlyCalendarController@block_store')->name('monthly_calendars.block_store');
    Route::post('monthly_calendars/file', 'MonthlyCalendarController@file')->name('monthly_calendars.file');
    Route::post('monthly_calendars/file/store', 'MonthlyCalendarController@file_store')->name('monthly_calendars.file_store');
    Route::get('monthly_calendars/destroy/{monthly_calendar}', 'MonthlyCalendarController@destroy')->name('monthly_calendars.destroy');
    Route::get('monthly_calendars/block_destroy/{monthly_calendar}', 'MonthlyCalendarController@block_destroy')->name('monthly_calendars.block_destroy');


    //行政人員編輯
    Route::get('contents_exec/{content}/edit', 'ContentsController@exec_edit')->name('contents.exec_edit');
    Route::patch('contents_exec/{content}', 'ContentsController@exec_update')->name('contents.exec_update');

    //行政人員編輯學校介紹
    Route::get('departments_exec/{department}/edit', 'DepartmentController@exec_edit')->name('departments.exec_edit');
    Route::patch('departments_exec/{department}', 'DepartmentController@exec_update')->name('departments.exec_update');

    //行政人員編輯校園部落格
    Route::get('blogs/create', 'BlogsController@create')->name('blogs.create');
    Route::post('blogs', 'BlogsController@store')->name('blogs.store');
    Route::get('blogs/{blog}/edit', 'BlogsController@edit')->name('blogs.edit');
    Route::patch('blogs/{blog}', 'BlogsController@update')->name('blogs.update');
    //刪標題圖片
    Route::get('blogs/{blog}/delete_title_image', 'BlogsController@delete_title_image')->name('blogs.delete_title_image');
});

//行政人員及管理者
Route::group(['middleware' => 'admin_exec'], function () {
    Route::get('posts/{post}/edit', 'PostsController@edit')->name('posts.edit');
    Route::patch('posts/{post}', 'PostsController@update')->name('posts.update');
    Route::delete('posts/{post}', 'PostsController@destroy')->name('posts.destroy');

    Route::get('open_files_delete/{path}', 'OpenFileController@delete')->name('open_files.delete');
    Route::get('open_files_edit/{upload}/{path}', 'OpenFileController@edit')->name('open_files.edit');
    Route::patch('open_files_update/{upload}', 'OpenFileController@update')->name('open_files.update');

    Route::get('inside_files_delete/{path}', 'InsideFilesController@delete')->name('inside_files.delete');
    Route::get('inside_files_edit/{inside_file}/{path}', 'InsideFilesController@edit')->name('inside_files.edit');
    Route::patch('inside_files_update/{inside_file}', 'InsideFilesController@update')->name('inside_files.update');


    Route::get('/laravel-filemanager', '\UniSharp\LaravelFilemanager\Controllers\LfmController@show');
    Route::post('/laravel-filemanager/upload', '\UniSharp\LaravelFilemanager\Controllers\UploadController@upload');

    //圖片連結管理
    Route::get('photo_links', 'PhotoLinksController@index')->name('photo_links.index');
    Route::post('photo_links', 'PhotoLinksController@store')->name('photo_links.store');
    Route::delete('photo_links/{photo_link}', 'PhotoLinksController@destroy')->name('photo_links.destroy');
    Route::get('photo_links/{photo_link}/edit', 'PhotoLinksController@edit')->name('photo_links.edit');
    Route::patch('photo_links/{photo_link}', 'PhotoLinksController@update')->name('photo_links.update');


    //校園部落格
    Route::delete('blogs/{blog}', 'BlogsController@destroy')->name('blogs.destroy');
});


//管理者可用
Route::group(['middleware' => 'admin'], function () {
    //模擬登入
    Route::get('sims/{user}/impersonate', 'SimulationController@impersonate')->name('sims.impersonate');
    //網站管理
    Route::get('setups', 'SetupController@index')->name('setups.index');
    Route::post('setups/add_logo', 'SetupController@add_logo')->name('setups.add_logo');
    //Route::post('setups/add_img', 'SetupController@add_img')->name('setups.add_img');
    Route::post('setups/add_imgs', 'SetupController@add_imgs')->name('setups.add_imgs');
    Route::get('setups/{folder}/del_img/{filename}', 'SetupController@del_img')->name('setups.del_img');
    Route::get('setups/photo', 'SetupController@photo')->name('setups.photo');
    Route::post('setups/photo_desc', 'SetupController@photo_desc')->name('setups.photo_desc');
    Route::patch('setups/{setup}/photo/update_title_image', 'SetupController@update_title_image')->name('setups.update_title_image');
    //Route::patch('setups/{setup}', 'SetupController@update')->where('setup', '[0-9]+')->name('setups.update');
    Route::patch('setups/{setup}/nav_color', 'SetupController@nav_color')->where('setup', '[0-9]+')->name('setups.nav_color');
    Route::get('setups/nav_default/', 'SetupController@nav_default')->name('setups.nav_default');
    Route::patch('setups/{setup}/text', 'SetupController@text')->name('setups.text');
    Route::get('setups/col', 'SetupController@col')->name('setups.col');
    Route::get('setups/add_col_table', 'SetupController@add_col_table')->name('setups.add_col_table');
    Route::post('setups/add_col', 'SetupController@add_col')->name('setups.add_col');
    Route::get('setups/{setup_col}/edit_col', 'SetupController@edit_col')->name('setups.edit_col');
    Route::patch('setups/{setup_col}/update_col', 'SetupController@update_col')->name('setups.update_col');
    Route::delete('setups/{setup_col}/delete_col', 'SetupController@delete_col')->name('setups.delete_col');

    //區塊管理
    Route::get('setups/block', 'SetupController@block')->name('setups.block');
    Route::get('setups/add_block_table', 'SetupController@add_block_table')->name('setups.add_block_table');
    Route::post('setups/add_block', 'SetupController@add_block')->name('setups.add_block');
    Route::get('setups/{block}/edit_block', 'SetupController@edit_block')->name('setups.edit_block');
    Route::patch('setups/{block}/update_block', 'SetupController@update_block')->name('setups.update_block');
    Route::delete('setups/{block}/delete_block', 'SetupController@delete_block')->name('setups.delete_block');
    Route::get('setups/block_color', 'SetupController@block_color')->name('setups.block_color');

    //模組功能
    Route::get('setups/module', 'SetupController@module')->name('setups.module');
    Route::post('setups/module', 'SetupController@update_module')->name('setups.update_module');

    //空間管理
    Route::get('setups/quota', 'SetupController@quota')->name('setups.quota');
    Route::get('setups/batch_delete_posts', 'SetupController@batch_delete_posts')->name('setups.batch_delete_posts');
    Route::delete('delete/batch_delete', 'SetupController@batch_delete')->name('setups.batch_delete');

    //使用者權限
    Route::get('user_powers/{module}/{type}', 'UserPowerController@create')->name('user_powers.create');
    Route::post('user_powers', 'UserPowerController@store')->name('user_powers.store');
    Route::get('user_powers_destroy/{user_power}', 'UserPowerController@destroy')->name('user_powers.destroy');

    //使用者管理
    Route::get('users', 'UsersController@index')->name('users.index');
    Route::get('users/create', 'UsersController@create')->name('users.create');
    Route::get('users/back_pwd/{user}', 'UsersController@back_pwd')->name('users.back_pwd');
    Route::post('users/store', 'UsersController@store')->name('users.store');
    Route::get('users/leave', 'UsersController@leave')->name('users.leave');
    Route::get('users/{user}', 'UsersController@edit')->name('users.edit');
    Route::patch('users/{user}/update', 'UsersController@update')->name('users.update');

    Route::get('groups', 'GroupController@index')->name('groups.index');
    Route::get('groups/create', 'GroupController@create')->name('groups.create');
    Route::post('groups', 'GroupController@store')->name('groups.store');
    Route::delete('groups/{group}', 'GroupController@destroy')->name('groups.destroy');
    Route::get('groups/{group}', 'GroupController@edit')->name('groups.edit');
    Route::patch('groups/{group}', 'GroupController@update')->name('groups.update');
    //記錄使用者群組
    Route::get('users_groups/{group}', 'GroupController@users_groups')->name('users_groups');
    Route::post('users_groups', 'GroupController@users_groups_store')->name('users_groups.store');
    Route::delete('users_groups', 'GroupController@users_groups_destroy')->name('users_groups.destroy');

    //處室管理
    Route::get('departments', 'DepartmentController@index')->name('departments.index');
    Route::get('departments/create', 'DepartmentController@create')->name('departments.create');
    Route::post('departments', 'DepartmentController@store')->name('departments.store');
    Route::delete('departments/{department}', 'DepartmentController@destroy')->name('departments.destroy');
    Route::get('departments/{department}/edit', 'DepartmentController@edit')->name('departments.edit');
    Route::patch('departments/{department}', 'DepartmentController@update')->name('departments.update');

    //內容管理
    Route::get('contents', 'ContentsController@index')->name('contents.index');
    Route::get('contents/create', 'ContentsController@create')->name('contents.create');
    Route::post('contents/store', 'ContentsController@store')->name('contents.store');
    Route::delete('contents/{content}', 'ContentsController@destroy')->name('contents.destroy');
    Route::get('contents/{content}/edit', 'ContentsController@edit')->name('contents.edit');
    Route::patch('contents/{content}/update', 'ContentsController@update')->name('contents.update');

    //類別管理
    Route::post('types', 'LinksController@store_type')->name('links.store_type');
    Route::delete('types/{type}', 'LinksController@destroy_type')->name('links.destroy_type');
    Route::patch('types/{type}', 'LinksController@update_type')->name('links.update_type');

    //連結管理
    Route::get('links', 'LinksController@index')->name('links.index');
    Route::get('links/create', 'LinksController@create')->name('links.create');
    Route::post('links', 'LinksController@store')->name('links.store');
    Route::delete('links/{link}', 'LinksController@destroy')->name('links.destroy');
    Route::get('links/{link}/edit', 'LinksController@edit')->name('links.edit');
    Route::patch('links/{link}', 'LinksController@update')->name('links.update');


    //樹狀目錄
    Route::get('trees', 'TreesController@index')->name('trees.index');
    Route::post('trees/store', 'TreesController@store')->name('trees.store');
    Route::get('trees/{tree}/edit', 'TreesController@edit')->name('trees.edit');
    Route::patch('trees/{tree}/update', 'TreesController@update')->name('trees.update');
    Route::get('trees/{tree}/delete', 'TreesController@delete')->name('trees.delete');

    //置頂公告
    Route::get('posts/{post}/top_up', 'PostsController@top_up')->name('posts.top_up');
    Route::get('posts/{post}/top_down', 'PostsController@top_down')->name('posts.top_down');
    Route::get('posts/show_type', 'PostsController@show_type')->name('posts.show_type');
    Route::post('posts/store_type', 'PostsController@store_type')->name('posts.store_type');
    Route::patch('posts/{post_type}/update_type', 'PostsController@update_type')->name('posts.update_type');
    Route::get('posts/{post_type}/delete_type', 'PostsController@delete_type')->name('posts.delete_type');

    //會議文稿
    Route::get('meetings/{meeting}/edit', 'MeetingController@edit')->name('meetings.edit');
    Route::patch('meetings/{meeting}', 'MeetingController@update')->name('meetings.update');
    Route::delete('meetings/{meeting}', 'MeetingController@destroy')->name('meetings.destroy');

    //校務行事曆
    Route::get('calendar_weeks/index', 'CalendarWeekController@index')->name('calendar_weeks.index');
    Route::get('calendar_weeks/{semester}/edit', 'CalendarWeekController@edit')->name('calendar_weeks.edit');
    Route::post('calendar_weeks/update', 'CalendarWeekController@update')->name('calendar_weeks.update');
    Route::post('calendar_weeks/create', 'CalendarWeekController@create')->name('calendar_weeks.create');
    Route::post('calendar_weeks/store', 'CalendarWeekController@store')->name('calendar_weeks.store');
    Route::get('calendar_weeks/{semester}/destroy', 'CalendarWeekController@destroy')->name('calendar_weeks.destroy');

    //今日午餐
    Route::get('lunch_today/index', 'LunchTodayController@index')->name('lunch_todays.index');
    Route::post('lunch_today/update', 'LunchTodayController@update')->name('lunch_todays.update');
    Route::get('lunch_today/{lunch_today}/delete', 'LunchTodayController@delete')->name('lunch_todays.delete');

    //RSS訊息
    Route::get('rss_feed/index', 'RssFeedController@index')->name('rss_feeds.index');
    Route::post('rss_feed/store', 'RssFeedController@store')->name('rss_feeds.store');
    Route::get('rss_feed/{rss_feed}/destory', 'RssFeedController@destory')->name('rss_feeds.destory');

    //報錯管理員回覆
    Route::post('wrench/reply', 'WrenchController@reply')->name('wrench.reply');
    Route::get('wrench/set_show/{id}', 'WrenchController@set_show')->name('wrench.set_show');
    Route::get('wrench/destroy/{id}', 'WrenchController@destroy')->name('wrench.destroy');

    //系統教學
    Route::get('teach_system', 'HomeController@teach_system')->name('teach_system');
});

Route::group(['middleware' => 'local'], function () {
    //更改密碼
    Route::get('edit_password', 'HomeController@edit_password')->name('edit_password');
    Route::patch('update_password', 'HomeController@update_password')->name('update_password');
});

Route::post('lunch_today/return_date1', 'LunchTodayController@return_date1')->name('lunch_todays.return_date1');
Route::post('lunch_today/return_date2', 'LunchTodayController@return_date2')->name('lunch_todays.return_date2');
Route::post('lunch_today/return_date3', 'LunchTodayController@return_date3')->name('lunch_todays.return_date3');
Route::post('lunch_today/return_date4', 'LunchTodayController@return_date4')->name('lunch_todays.return_date4');

Route::post('monthly_calendars/return_month', 'MonthlyCalendarController@return_month')->name('monthly_calendars.return_month');
Route::post('classroom_orders/block_show', 'ClassroomOrderController@block_show')->name('classroom_orders.block_show');
