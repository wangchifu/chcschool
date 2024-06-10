<?php

namespace App\Http\Controllers;

use App\Block;
use App\PhotoLink;
use App\PhotoType;
use App\Post;
use App\PostType;
use App\Setup;
use App\SetupCol;
use App\TitleImageDesc;
use App\Tree;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
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
        $setup = Setup::first();
        //檢查有無關閉網站
        if (!empty($setup->close_website)) {
            Redirect::to('close')->send();
        }
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
        $photos = get_files(storage_path('app/public/' . $school_code . '/title_image/random'));
        $title_image_desc = TitleImageDesc::orderBy('order_by')->get();
        $photo_desc = [];
        foreach ($title_image_desc as $desc) {
            $photo_desc[$desc->image_name]['order_by'] = $desc->order_by;
            $photo_desc[$desc->image_name]['link'] = $desc->link;
            $photo_desc[$desc->image_name]['title'] = $desc->title;
            $photo_desc[$desc->image_name]['desc'] = $desc->desc;
            $photo_desc[$desc->image_name]['disable'] = $desc->disable;
        }

        foreach($photos as $k=>$v){
            if(!isset($photo_desc[$v]['order_by'])) $photo_desc[$v]['order_by'] = 0;
            if(!isset($photo_desc[$v]['link'])) $photo_desc[$v]['link'] = null;
            if(!isset($photo_desc[$v]['title'])) $photo_desc[$v]['title'] = null;
            if(!isset($photo_desc[$v]['desc'])) $photo_desc[$v]['desc'] = null;
            if(!isset($photo_desc[$v]['disable'])) $photo_desc[$v]['disable'] = null;
            if($photo_desc[$v]['disable']==1){
                unset($photo_desc[$v]);
            }
        }
        
        $photo_data = [];
        foreach($photo_desc as $k=>$v){ 
            $photo_data[$v['order_by']][$k]['link'] = $v['link'];
            $photo_data[$v['order_by']][$k]['title'] = $v['title'];
            $photo_data[$v['order_by']][$k]['desc'] = $v['desc'];
        }

        krsort($photo_data);


        $setup = Setup::first();
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
        $post_types = PostType::where('disable',null)->orderBy('order_by')->get();

        $photo_link_number = ($setup->photo_link_number)?$setup->photo_link_number:"24";
        $photo_links = PhotoLink::orderBy('order_by', 'DESC')->paginate($photo_link_number);
        $photo_types = PhotoType::orderBy('order_by')->get();

        $post_type_array = PostType::orderBy('order_by')->pluck('name', 'id')->toArray();

        $data = [
            'school_code' => $school_code,
            //'photos' => $photos,
            'photo_data' => $photo_data,
            'setup' => $setup,
            'setup_cols' => $setup_cols,
            'blocks' => $blocks,
            'posts' => $posts,
            'request' => $request,
            'marquee' => $marquee,
            'marquee_css' => $marquee_css,
            'photo_links' => $photo_links,
            'photo_types'=>$photo_types,
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
