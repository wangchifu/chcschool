<?php

namespace App\Http\Controllers;

use App\Block;
use App\PhotoLink;
use App\Post;
use App\PostType;
use App\SetupCol;
use App\TitleImageDesc;
use App\Tree;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    /**
    public function index(Request $request,$insite=null)
    {
        if(is_null($insite)) $insite="index";

        $school_code = school_code();
        $files = get_files(storage_path('app/public/'.$school_code.'/title_image/random'));
        if($files) {
            foreach ($files as $k=>$v) {
                $photos[$k] = asset('storage/'.$school_code.'/title_image/random/'.$v);
            }
        }else{
            $photos = [
                '0'=>asset('images/top0.svg'),
                '1'=>asset('images/top1.svg'),
                '2'=>asset('images/top2.svg'),
            ];
        }

        $setup = \App\Setup::find(1);
        $setup_cols = SetupCol::orderBy('order_by')->get();
        foreach($setup_cols as $setup_col){
            $bs = Block::where('setup_col_id',$setup_col->id)
                ->orderBy('order_by')
                ->get();

            $blocks[$setup_col->id] = $bs;

            //跑馬燈css設定
            if($setup_col->title == "榮譽榜跑馬燈") {
                $marquee_css = $bs[0]->content;
            }
        }
        //跑馬燈css預設設定
        if(empty($marquee_css)) {
            $marquee_css = "direction='left' height='30' scrollamount='5' align='midden'";
        }
        if($insite=="insite"){
            $posts = Post::where('insite','1')
                ->orderBy('top','DESC')
                ->orderBy('created_at','DESC')
                ->paginate(10);
        }elseif($insite=="honor"){
            $posts = Post::where('insite','2')
                ->orderBy('top','DESC')
                ->orderBy('created_at','DESC')
                ->paginate(10);
        }elseif($insite=="index"){
            $posts = Post::where('insite',null)
                ->orderBy('top','DESC')
                ->orderBy('created_at','DESC')
                ->paginate(10);
        }
        //榮譽榜資料庫資料
        $honors = Post::where('insite','2')
            ->orderBy('top','DESC')
            ->orderBy('created_at','DESC')
            ->paginate(10);
        //跑馬燈取得榮譽榜資料庫資料
        $marquee = "";
        foreach($honors as $honor) {
            $href = "../posts/".$honor->id;
            $marquee .= "<a href=".$href.">"
                .$honor->title."   ".
                "</a>";
        }


        //分類公告
        $post_types = PostType::orderBy('order_by')->get();

        $photo_links = PhotoLink::orderBy('order_by')->paginate(24);

        $data = [
            'school_code'=>$school_code,
            'photos'=>$photos,
            'setup'=>$setup,
            'setup_cols'=>$setup_cols,
            'blocks'=>$blocks,
            'posts'=>$posts,
            'insite'=>$insite,
            'request'=>$request,
            'marquee' =>$marquee,
            'marquee_css'=>$marquee_css,
            'photo_links'=>$photo_links,
            'post_types'=>$post_types,
        ];
        return view('index',$data);
    }
     * */

    public function index(Request $request)
    {
        $school_code = school_code();
        $files = get_files(storage_path('app/public/' . $school_code . '/title_image/random'));
        rsort($files);
        if ($files) {
            foreach ($files as $k => $v) {
                $photos[$k] = $v;
            }
        } else {
            $photos = [
                '0' => asset('images/top0.svg'),
                '1' => asset('images/top1.svg'),
                '2' => asset('images/top2.svg'),
            ];
        }

        $title_image_desc = TitleImageDesc::all();
        $photo_desc = [];
        foreach ($title_image_desc as $desc) {
            $photo_desc[$desc->image_name]['link'] = $desc->link;
            $photo_desc[$desc->image_name]['title'] = $desc->title;
            $photo_desc[$desc->image_name]['desc'] = $desc->desc;
        }


        $setup = \App\Setup::find(1);
        $setup_cols = SetupCol::orderBy('order_by')->get();
        foreach ($setup_cols as $setup_col) {
            $bs = Block::where('setup_col_id', $setup_col->id)
                ->orderBy('order_by')
                ->get();
            $blocks[$setup_col->id] = $bs;
        }
        //跑馬燈css預設設定
        $marquee_block = Block::where('title', "榮譽榜跑馬燈")
            ->first();
        $marquee_css = $marquee_block->content;
        if (empty($marquee_css)) {
            $marquee_css = "direction='left' height='30' scrollamount='5' align='midden'";
        }

        $posts = Post::orderBy('top', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->paginate(10);

        //榮譽榜資料庫資料
        $honors = Post::where('insite', '2')
            ->orderBy('top', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->paginate(10);
        //跑馬燈取得榮譽榜資料庫資料
        $marquee = "";
        foreach ($honors as $honor) {
            $href = "../posts/" . $honor->id;
            $marquee .= "<i class=\"fas fa-crown text-warning\"></i> <a href=" . $href . ">"
                . $honor->title . "   " .
                "</a>　　";
        }


        //分類公告
        $post_types = PostType::orderBy('order_by')->get();

        $photo_links = PhotoLink::orderBy('order_by', 'DESC')->paginate(24);

        $post_type_array = PostType::orderBy('order_by')->pluck('name', 'id')->toArray();

        $data = [
            'school_code' => $school_code,
            'photos' => $photos,
            'photo_desc' => $photo_desc,
            'setup' => $setup,
            'setup_cols' => $setup_cols,
            'blocks' => $blocks,
            'posts' => $posts,
            'request' => $request,
            'marquee' => $marquee,
            'marquee_css' => $marquee_css,
            'photo_links' => $photo_links,
            'post_types' => $post_types,
            'post_type_array' => $post_type_array,
        ];
        return view('index', $data);
    }

    public function edit_password()
    {
        return view('edit_password');
    }

    public function update_password(Request $request)
    {

        if (!password_verify($request->input('password0'), auth()->user()->password)) {
            return back()->withErrors(['error' => ['舊密碼錯誤！你不是本人！？']]);
        }
        if ($request->input('password1') != $request->input('password2')) {
            return back()->withErrors(['error' => ['兩次新密碼不相同']]);
        }

        $att['id'] = auth()->user()->id;
        $att['password'] = bcrypt($request->input('password1'));
        $user = User::where('id', $att['id'])->first();
        $user->update($att);
        return redirect()->route('index');
    }

    public function getFile($file)
    {
        $file = str_replace('&', '/', $file);
        $file = storage_path('app/privacy/' . $file);
        return response()->download($file);
    }

    public function openFile($file)
    {
        $file = str_replace('&', '/', $file);
        $file = storage_path('app/privacy/' . $file);
        return response()->file($file);
    }

    public function getImg($path)
    {
        $school_code = school_code();
        $path = str_replace('&', '/', $path);
        $path = storage_path('app/privacy/' . $school_code . '/' . $path);
        $file = File::get($path);
        $type = File::mimeType($path);

        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);
        return $response;
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

    public function not_bot(Request $request)
    {
        $request->validate([
            'check_bot' => 'required',
        ]);
        if ($request->input('check_bot') == session('check_bot')) {
            session(['login_error' => null]);
            return back();
        } else {
            return back()->withErrors(['error' => ['你是機器人？']]);
        }
    }

    public function teach_system()
    {
        return view('teach_system');
    }
}
