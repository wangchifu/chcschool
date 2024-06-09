<?php

namespace App\Http\Controllers;

use App\Block;
use App\Module;
use App\Post;
use App\Setup;
use App\SetupCol;
use App\TitleImageDesc;
use Illuminate\Http\Request;

class SetupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $i = "";
        $setup = Setup::first();
        $data = [
            'setup' => $setup,
        ];
        return view('setups.index', $data);
    }

    public function photo_link_number(Request $request)
    {
        $att['photo_link_number'] = $request->input('photo_link_number');
        $setup = Setup::first();
        $setup->update($att);
        return back();
    }

    public function photo()
    {
        $school_code = school_code();
        $setup = Setup::first();
        $photos = get_files(storage_path('app/public/' . $school_code . '/title_image/random'));
        $title_image_desc = TitleImageDesc::orderBy('order_by')->get();
        $photo_desc = [];
        foreach ($title_image_desc as $desc) {
            $photo_desc[$desc->image_name]['order_by'] = $desc->order_by;
            $photo_desc[$desc->image_name]['link'] = $desc->link;
            $photo_desc[$desc->image_name]['title'] = $desc->title;
            $photo_desc[$desc->image_name]['desc'] = $desc->desc;
        }

        foreach($photos as $k=>$v){
            if(!isset($photo_desc[$v]['order_by'])) $photo_desc[$v]['order_by'] = 0;
            if(!isset($photo_desc[$v]['link'])) $photo_desc[$v]['link'] = null;
            if(!isset($photo_desc[$v]['title'])) $photo_desc[$v]['title'] = null;
            if(!isset($photo_desc[$v]['desc'])) $photo_desc[$v]['desc'] = null;
        }
        $photo_data = [];
        foreach($photo_desc as $k=>$v){ 
            $photo_data[$v['order_by']][$k]['link'] = $v['link'];
            $photo_data[$v['order_by']][$k]['title'] = $v['title'];
            $photo_data[$v['order_by']][$k]['desc'] = $v['desc'];
        }

        krsort($photo_data);

        $data = [
            'school_code' => $school_code,
            'setup' => $setup,
            //'photos' => $photos,
            'photo_data' => $photo_data,
        ];
        return view('setups.photo', $data);
    }

    public function photo_desc(Request $request)
    {
        $att = $request->all();
        foreach($att['order_by'] as $k=>$v){
            $image = TitleImageDesc::where('image_name', $k)->first();
            $att2['order_by'] = $att['order_by'][$k];
            $att2['link'] = $att['link'][$k];
            $att2['title'] = $att['title'][$k];
            $att2['desc'] = $att['desc'][$k];
            $att2['image_name'] = $att['image_name'][$k];
            if(!empty($image)){
                $image->update($att2);
            }else{
                TitleImageDesc::create($att2);
            }
        }
        return redirect()->route('setups.photo');
    }

    public function update_title_image(Request $request, Setup $setup)
    {
        $att['title_image'] = $request->input('title_image');
        $setup->update($att);
        return redirect()->route('setups.photo');
    }

    public function add_logo(Request $request)
    {
        //新增使用者的上傳目錄
        $school_code = school_code();
        $new_path = 'public/' . $school_code . '/title_image';

        //處理檔案上傳
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');

            $info = [
                'original_filename' => $logo->getClientOriginalName(),
                'extension' => $logo->getClientOriginalExtension(),
            ];

            if ($info['extension'] != "png" and $info['extension'] != "ico") {
                return back()->withErrors(['errors' => '只接受 png 或 ico 檔']);
            }
            $logo->storeAs($new_path, 'logo.ico');
        }
        return redirect()->route('setups.photo');
    }

    public function del_img($folder, $filename)
    {
        $school_code = school_code();
        $folder = str_replace('&', '/', $folder);
        if(file_exists(storage_path('app/public/' . $school_code . '/' . $folder . '/' . $filename))){
            unlink(storage_path('app/public/' . $school_code . '/' . $folder . '/' . $filename));
        }
        TitleImageDesc::where('image_name', $filename)->delete();
        return redirect()->route('setups.photo');
    }

    public function add_imgs(Request $request)
    {
        //新增使用者的上傳目錄
        $school_code = school_code();
        $new_path = 'public/' . $school_code . '/title_image/random';

        //處理檔案上傳
        if ($request->hasFile('files')) {
            $files = $request->file('files');
            $n = 1;
            foreach ($files as $file) {
                $info = [
                    'original_filename' => $file->getClientOriginalName(),
                    'extension' => $file->getClientOriginalExtension(),
                ];
                $filename = date("Y-m-d-H-i-s") . "-" . $n . "." . $info['extension'];
                $file->storeAs($new_path, $filename);
                $n++;
            }
        }

        return redirect()->route('setups.photo');
    }

    public function nav_color(Request $request, Setup $setup)
    {
        $nav_color = $request->input('color');
        $att['fixed_nav'] = ($request->input('fixed_nav'))?1:null;


        $att['nav_color'] = "";
        foreach ($nav_color as $v) {
            $att['nav_color'] .= $v . ",";
        }
        $setup->update($att);
        return redirect()->route('setups.index');
    }

    public function nav_default()
    {
        $setup = Setup::first();
        $att['nav_color'] = null;
        $setup->update($att);
        return redirect()->route('setups.index');
    }

    public function text(Request $request, Setup $setup)
    {
        $request->validate([
            'site_name' => 'required',
            'views' => ['required', 'numeric'],
        ]);
        $att['site_name'] = $request->input('site_name');
        $att['views'] = $request->input('views');
        $att['footer'] = $request->input('footer');
        $att['ip1'] = $request->input('ip1');
        $att['ip2'] = $request->input('ip2');
        $att['ipv6'] = $request->input('ipv6');
        if ($request->input('set_close_website') == "off") {
            $att['close_website'] = (empty($request->input('close_website'))) ? "網站關閉" : $request->input('close_website');
        }
        if ($request->input('set_close_website') == "on") {
            $att['close_website'] = null;
        }
        $setup->update($att);
        return redirect()->route('setups.index');
    }

    public function col()
    {
        $setup_cols = SetupCol::orderBy('order_by')->get();
        $data = [
            'setup_cols' => $setup_cols,
        ];
        return view('setups.col', $data);
    }

    public function add_col_table()
    {
        return view('setups.add_col_table');
    }

    public function add_col(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'order_by' => ['nullable', 'numeric'],
            'num' => ['required', 'numeric'],
        ]);
        $att['title'] = $request->input('title');
        $att['num'] = $request->input('num');
        $att['order_by'] = $request->input('order_by');
        SetupCol::create($att);
        echo "<body onload='opener.location.reload();window.close();'>";
    }

    public function edit_col(SetupCol $setup_col)
    {
        $data = [
            'setup_col' => $setup_col,
        ];
        return view('setups.edit_col', $data);
    }

    public function update_col(Request $request, SetupCol $setup_col)
    {
        $request->validate([
            'title' => 'required',
            'order_by' => ['nullable', 'numeric'],
            'num' => ['required', 'numeric'],
        ]);
        $att['order_by'] = $request->input('order_by');
        $att['title'] = $request->input('title');
        $att['num'] = $request->input('num');
        $setup_col->update($att);
        echo "<body onload='opener.location.reload();window.close();'>";
    }

    public function delete_col(SetupCol $setup_col)
    {
        $att['setup_col_id'] = null;
        Block::where('setup_col_id', $setup_col->id)->update($att);
        $setup_col->delete();
        echo "<body onload='opener.location.reload();window.close();'>";
    }

    public function block()
    {
        $setup_cols = SetupCol::orderBy('order_by')->get();
        foreach ($setup_cols as $setup_col) {
            $setup_array[$setup_col->id] = $setup_col->title . '(' . $setup_col->id . ')';
        }

        $blocks = Block::orderBy('setup_col_id')
            ->orderBy('order_by')
            ->get();
        $data = [
            'setup_array' => $setup_array,
            'blocks' => $blocks,
        ];
        return view('setups.block', $data);
    }

    public function add_block_table()
    {
        $setup_cols = SetupCol::orderBy('order_by')->get();
        foreach ($setup_cols as $setup_col) {
            $setup_array[$setup_col->id] = $setup_col->title . '(' . $setup_col->id . ')';
        }
        $block_colors = config('chcschool.block_colors');
        $block_colors = array_flip($block_colors);
        $data = [
            'setup_array' => $setup_array,
            'block_colors' => $block_colors,
        ];
        return view('setups.add_block_table', $data);
    }

    public function add_block(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'order_by' => ['nullable', 'numeric'],
        ]);
        $att = $request->all();
        Block::create($att);
        echo "<body onload='opener.location.reload();window.close();'>";
    }

    public function edit_block(Block $block)
    {
        $setup_cols = SetupCol::orderBy('order_by')->get();
        foreach ($setup_cols as $setup_col) {
            $setup_array[$setup_col->id] = $setup_col->title . '(' . $setup_col->id . ')';
        }
        $block_colors = config('chcschool.block_colors');
        $block_colors = array_flip($block_colors);

        $data = [
            'setup_array' => $setup_array,
            'block' => $block,
            'block_colors' => $block_colors,
        ];
        return view('setups.edit_block', $data);
    }

    function update_block(Request $request, Block $block)
    {
        $request->validate([
            'title' => 'required',
            'order_by' => ['nullable', 'numeric'],
        ]);
        $att = $request->all();
        $block->update($att);
        echo "<body onload='opener.location.reload();window.close();'>";
    }

    public function delete_block(Block $block)
    {
        $block->delete();
        echo "<body onload='opener.location.reload();window.close();'>";
    }

    public function block_color()
    {
        return view('setups.block_color');
    }

    public function module()
    {
        $modules = config('chcschool.modules');
        $data = [
            'modules' => $modules,
        ];
        return view('setups.module', $data);
    }

    public function update_module(Request $request)
    {
        $modules = Module::orderBy('id')->get();
        foreach ($modules as $m) {
            $m->delete();
        }


        $module = $request->input('module');

        foreach ($module as $k => $v) {
            $att['name'] = $k;
            $att['active'] = $v;
            Module::create($att);
        }
        return redirect()->route('setups.module');
    }

    public function quota()
    {
        $school_code = school_code();

        $f1 = storage_path('app/public/' . $school_code);
        $quota['public']['all'] = get_dir_size($f1);

        $f2 = storage_path('app/privacy/' . $school_code);
        $quota['privacy']['all'] = get_dir_size($f2);

        $dir_size = $quota['public']['all'] + $quota['privacy']['all'];

        $size = round($dir_size / 1024, 2);
        $per = round($size * 100 / 5120, 2);


        //公告  public/code/posts
        $posts = storage_path('app/public/' . $school_code . '/posts');
        $quota['public']['公告附件'] = get_dir_size($posts);

        //檔案庫  public/code/open_files
        $open_files = storage_path('app/public/' . $school_code . '/open_files');
        $quota['public']['檔案庫'] = get_dir_size($open_files);

        //ckeditor public/code/photos
        $photos = storage_path('app/public/' . $school_code . '/photos');
        $quota['public']['ckeditor圖片'] = get_dir_size($photos);

        //ckeditor public/code/files
        $files = storage_path('app/public/' . $school_code . '/files');
        $quota['public']['ckeditor檔案'] = get_dir_size($files);

        //標題圖片  public/code/title_image
        $title_image = storage_path('app/public/' . $school_code . '/title_image');
        $quota['public']['首頁橫幅圖片'] = get_dir_size($title_image);

        //圖片連結  public/code/photo_links
        $photo_links = storage_path('app/public/' . $school_code . '/photo_links');
        $quota['public']['圖片連結封面'] = get_dir_size($photo_links);

        //校園部落格 public/code/blogs
        $blogs = storage_path('app/public/' . $school_code . '/blogs');
        $quota['public']['校園部落格封面'] = get_dir_size($blogs);

        //內部文件  privacy/code/inside_files
        $inside_files = storage_path('app/privacy/' . $school_code . '/inside_files');
        $quota['privacy']['內部文件'] = get_dir_size($inside_files);

        //會議文稿  privacy/code/reports
        $reports = storage_path('app/privacy/' . $school_code . '/reports');
        $quota['privacy']['會議文稿附件'] = get_dir_size($reports);

        //午餐  privacy/code/lunches
        $lunches = storage_path('app/privacy/' . $school_code . '/lunches');
        $quota['privacy']['午餐模組印章檔'] = get_dir_size($lunches);

        $data = [
            'quota' => $quota,
            'size' => $size,
            'per' => $per,
        ];

        return view('setups.quota', $data);
    }

    public function batch_delete_posts()
    {
        return view('setups.batch_delete_posts');
    }

    public function batch_delete(Request $request)
    {

        $posts = Post::where('id', '<=', $request->input('post_no'))->get();
        if (auth()->user()->admin == 1) {
            $school_code = school_code();
            foreach ($posts as $post) {
                $folder = storage_path('app/public/' . $school_code . '/posts/' . $post->id);
                if (is_dir($folder)) {
                    delete_dir($folder);
                }

                $post->delete();
            }
        }

        echo "<body onload='opener.location.reload();window.close();'>";
    }

    public function all_post(Request $request)
    {
        $setup = Setup::first();
        $att['all_post'] = ($request->input('all_post'))?1:null;
        $setup->update($att);
        echo "<body onload='opener.location.reload();window.close();'>";
    }

    public function close()
    {
        $setup = Setup::first();
        $data = [
            'setup' => $setup,
        ];
        return view('setups.close', $data);
    }

    public function pic()
    {
        $key = rand(10000, 99999);
        $back = rand(0, 9);
        $r = rand(0, 255);
        $g = rand(0, 255);
        $b = rand(0, 255);

        session(['chaptcha' => $key]);

        $cht = array(0 => "零", 1 => "壹", 2 => "貳", 3 => "參", 4 => "肆", 5 => "伍", 6 => "陸", 7 => "柒", 8 => "捌", 9 => "玖");
        //$cht = array(0=>"0",1=>"1",2=>"2",3=>"3",4=>"4",5=>"5",6=>"6",7=>"7",8=>"8",9=>"9");
        $cht_key = "";
        for ($i = 0; $i < 5; $i++) $cht_key .= $cht[substr($key, $i, 1)];

        header("Content-type: image/gif");
        $images = asset('images/captcha_bk' . $back . '.gif');

        $context = stream_context_create([
            "ssl" => [
                "verify_peer"      => false,
                "verify_peer_name" => false
            ]
        ]);

        $fileContent = file_get_contents($images, false, $context);
        $im = imagecreatefromstring($fileContent);
        $text_color = imagecolorallocate($im, $r, $g, $b);

        imagettftext($im, 50, 0, 50, 50, $text_color, public_path('font/wt071.ttf'), $cht_key);
        imagegif($im);
        imagedestroy($im);
    }
}
