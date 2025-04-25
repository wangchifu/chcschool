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
use App\SchoolMarquee;
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

    public $school_check_file = [
        'www.bcses.chc.edu.tw'=>'A',
        'www.bdes.chc.edu.tw'=>'A',
        'www.bdses.chc.edu.tw'=>'A',
        'www.bdsps.chc.edu.tw'=>'A',
        'www.bses.chc.edu.tw'=>'A',
        'www.bsps.chc.edu.tw'=>'A',
        'www.bsses.chc.edu.tw'=>'A',
        'www.caes.chc.edu.tw'=>'A',
        'www.cajh.chc.edu.tw'=>'A',
        'www.caps.chc.edu.tw'=>'A',
        'www.ccjh.chc.edu.tw'=>'A',
        'www.ccps.chc.edu.tw'=>'A',
        'www.cfjh.chc.edu.tw'=>'A',
        'www.cges.chc.edu.tw'=>'A',
        'www.chash.chc.edu.tw'=>'A',
        'www.chcses.chc.edu.tw'=>'A',
        'www.ches.chc.edu.tw'=>'A',
        'www.cksh.chc.edu.tw'=>'A',
        'www.cles.chc.edu.tw'=>'A',
        'www.cses.chc.edu.tw'=>'A',
        'www.csjh.chc.edu.tw'=>'A',
        'www.csnes.chc.edu.tw'=>'A',
        'www.csps.chc.edu.tw'=>'A',
        'www.ctes.chc.edu.tw'=>'A',
        'www.ctjh.chc.edu.tw'=>'A',
        'www.ctjhs.chc.edu.tw'=>'A',
        'www.ctps.chc.edu.tw'=>'A',
        'www.ctsjh.chc.edu.tw'=>'A',
        'www.cyes.chc.edu.tw'=>'A',
        'www.cyps.chc.edu.tw'=>'A',
        'www.daes.chc.edu.tw'=>'B',
        'www.dces.chc.edu.tw'=>'B',
        'www.dches.chc.edu.tw'=>'B',
        'www.dcps.chc.edu.tw'=>'B',
        'www.dfes.chc.edu.tw'=>'B',
        'www.dhes.chc.edu.tw'=>'B',
        'www.dhps.chc.edu.tw'=>'B',
        'www.djps.chc.edu.tw'=>'B',
        'www.dres.chc.edu.tw'=>'B',
        'www.dses.chc.edu.tw'=>'B',
        'www.dsps.chc.edu.tw'=>'B',
        'www.dsses.chc.edu.tw'=>'B',
        'www.dtes.chc.edu.tw'=>'B',
        'www.dtps.chc.edu.tw'=>'B',
        'www.dyes.chc.edu.tw'=>'B',
        'www.elps.chc.edu.tw'=>'B',
        'www.elsh.chc.edu.tw'=>'B',
        'www.eses.chc.edu.tw'=>'B',
        'www.esjh.chc.edu.tw'=>'B',
        'www.fces.chc.edu.tw'=>'B',
        'www.fdes.chc.edu.tw'=>'B',
        'www.fdps.chc.edu.tw'=>'B',
        'www.fles.chc.edu.tw'=>'B',
        'www.fses.chc.edu.tw'=>'B',
        'www.fsjh.chc.edu.tw'=>'B',
        'www.fsps.chc.edu.tw'=>'B',
        'www.fsses.chc.edu.tw'=>'B',
        'www.fyes.chc.edu.tw'=>'B',
        'www.fyjh.chc.edu.tw'=>'B',
        'www.fyjhs.chc.edu.tw'=>'B',
        'www.fyps.chc.edu.tw'=>'B',
        'www.gses.chc.edu.tw'=>'B',
        'www.gsps.chc.edu.tw'=>'B',
        'www.gyes.chc.edu.tw'=>'B',
        'www.hbes.chc.edu.tw'=>'C',
        'www.hbps.chc.edu.tw'=>'C',
        'www.hcjh.chc.edu.tw'=>'C',
        'www.hdes.chc.edu.tw'=>'C',
        'www.hhjh.chc.edu.tw'=>'C',
        'www.hles.chc.edu.tw'=>'C',
        'www.hlps.chc.edu.tw'=>'C',
        'www.hmjh.chc.edu.tw'=>'C',
        'www.hmps.chc.edu.tw'=>'C',
        'www.hnes.chc.edu.tw'=>'C',
        'www.hnps.chc.edu.tw'=>'C',
        'www.hpes.chc.edu.tw'=>'C',
        'www.hres.chc.edu.tw'=>'C',
        'www.hses.chc.edu.tw'=>'C',
        'www.hsjh.chc.edu.tw'=>'C',
        'www.hsps.chc.edu.tw'=>'C',
        'www.htes.chc.edu.tw'=>'C',
        'www.htjh.chc.edu.tw'=>'C',
        'www.hyjh.chc.edu.tw'=>'C',
        'www.hyjhes.chc.edu.tw'=>'C',
        'www.jges.chc.edu.tw'=>'C',
        'www.jles.chc.edu.tw'=>'C',
        'www.jses.chc.edu.tw'=>'C',
        'www.jsps.chc.edu.tw'=>'C',
        'www.kges.chc.edu.tw'=>'C',
        'www.ldes.chc.edu.tw'=>'C',
        'www.lfes.chc.edu.tw'=>'C',
        'www.lges.chc.edu.tw'=>'C',
        'www.ljes.chc.edu.tw'=>'C',
        'www.ljis.chc.edu.tw'=>'C',
        'www.lkjh.chc.edu.tw'=>'C',
        'www.lmjh.chc.edu.tw'=>'C',
        'www.lses.chc.edu.tw'=>'C',
        'www.lsps.chc.edu.tw'=>'C',
        'www.lyps.chc.edu.tw'=>'C',
        'www.mcps.chc.edu..tw'=>'D',
        'www.mcws.chc.edu..tw'=>'D',
        'www.mfes.chc.edu..tw'=>'D',
        'www.mhes.chc.edu..tw'=>'D',
        'www.mjes.chc.edu..tw'=>'D',
        'www.mles.chc.edu..tw'=>'D',
        'www.mljh.chc.edu..tw'=>'D',
        'www.mses.chc.edu..tw'=>'D',
        'www.msps.chc.edu..tw'=>'D',
        'www.mtes.chc.edu..tw'=>'D',
        'www.naes.chc.edu..tw'=>'D',
        'www.nges.chc.edu..tw'=>'D',
        'www.ngps.chc.edu..tw'=>'D',
        'www.njes.chc.edu..tw'=>'D',
        'www.njps.chc.edu..tw'=>'D',
        'www.nses.chc.edu..tw'=>'D',
        'www.nyes.chc.edu..tw'=>'D',
        'www.phes.chc.edu..tw'=>'D',
        'www.pses.chc.edu..tw'=>'D',
        'www.psjh.chc.edu..tw'=>'D',
        'www.ptes.chc.edu..tw'=>'D',
        'www.ptjh.chc.edu..tw'=>'D',
        'www.ptjhs.chc.edu..tw'=>'D',
        'www.pyes.chc.edu..tw'=>'D',
        'www.pyjh.chc.edu..tw'=>'D',
        'www.pyps.chc.edu..tw'=>'D',
        'www.rces.chc.edu..tw'=>'D',
        'www.rfes.chc.edu..tw'=>'D',
        'www.rmes.chc.edu..tw'=>'D',
        'www.rses.chc.edu..tw'=>'D',
        'www.rtes.chc.edu..tw'=>'D',
        'www.ryes.chc.edu..tw'=>'D',      
        'www.sbes.chc.edu.tw'=>'E',
        'www.sces.chc.edu.tw'=>'E',
        'www.scses.chc.edu.tw'=>'E',
        'www.scsps.chc.edu.tw'=>'E',
        'www.sdes.chc.edu.tw'=>'E',
        'www.sdses.chc.edu.tw'=>'E',
        'www.sdsps.chc.edu.tw'=>'E',
        'www.sfses.chc.edu.tw'=>'E',
        'www.sfsps.chc.edu.tw'=>'E',
        'www.sges.chc.edu.tw'=>'E',
        'www.sgps.chc.edu.tw'=>'E',
        'www.shes.chc.edu.tw'=>'E',
        'www.shps.chc.edu.tw'=>'E',
        'www.shses.chc.edu.tw'=>'E',
        'www.sjps.chc.edu.tw'=>'E',
        'www.sjses.chc.edu.tw'=>'E',
        'www.skjh.chc.edu.tw'=>'E',
        'www.smes.chc.edu.tw'=>'E',
        'www.smps.chc.edu.tw'=>'E',
        'www.smses.chc.edu.tw'=>'E',
        'www.spes.chc.edu.tw'=>'E',
        'www.sres.chc.edu.tw'=>'E',
        'www.sses.chc.edu.tw'=>'E',
        'www.ssjes.chc.edu.tw'=>'E',
        'www.ssps.chc.edu.tw'=>'E',
        'www.ssses.chc.edu.tw'=>'E',
        'www.sssps.chc.edu.tw'=>'E',
        'www.sstes.chc.edu.tw'=>'E',
        'www.sstps.chc.edu.tw'=>'E',
        'www.steps.chc.edu.tw'=>'E',
        'www.stes.chc.edu.tw'=>'E',
        'www.stjh.chc.edu.tw'=>'E',
        'www.stps.chc.edu.tw'=>'E',
        'www.swes.chc.edu.tw'=>'E',
        'www.syes.chc.edu.tw'=>'E',
        'www.taes.chc.edu.tw'=>'E',
        'www.tces.chc.edu.tw'=>'E',
        'www.tcjh.chc.edu.tw'=>'E',
        'www.tcjhs.chc.edu.tw'=>'E',
        'www.tdes.chc.edu.tw'=>'E',     
        'www.tfps.chc.edu.tw'=>'F',
        'www.tges.chc.edu.tw'=>'F',
        'www.thes.chc.edu.tw'=>'F',
        'www.thjh.chc.edu.tw'=>'F',
        'www.thps.chc.edu.tw'=>'F',
        'www.tjes.chc.edu.tw'=>'F',
        'www.tkes.chc.edu.tw'=>'F',
        'www.tpes.chc.edu.tw'=>'F',
        'www.tses.chc.edu.tw'=>'F',
        'www.tsps.chc.edu.tw'=>'F',
        'www.ttes.chc.edu.tw'=>'F',
        'www.ttjh.chc.edu.tw'=>'F',
        'www.ttjhs.chc.edu.tw'=>'F',
        'www.twjh.chc.edu.tw'=>'F',
        'www.twps.chc.edu.tw'=>'F',
        'www.wces.chc.edu.tw'=>'F',
        'www.wdes.chc.edu.tw'=>'F',
        'www.wfes.chc.edu.tw'=>'F',
        'www.wges.chc.edu.tw'=>'F',
        'www.whes.chc.edu.tw'=>'F',
        'www.wkes.chc.edu.tw'=>'F',
        'www.wles.chc.edu.tw'=>'F',
        'www.wses.chc.edu.tw'=>'F',
        'www.wsps.chc.edu.tw'=>'F',
        'www.whjh.chc.edu.tw'=>'F',
        'www.yces.chc.edu.tw'=>'F',
        'www.ycjh.chc.edu.tw'=>'F',
        'www.ycps.chc.edu.tw'=>'F',
        'www.ydes.chc.edu.tw'=>'F',
        'www.ydps.chc.edu.tw'=>'F',
        'www.yfes.chc.edu.tw'=>'F',
        'www.yhes.chc.edu.tw'=>'F',
        'www.yles.chc.edu.tw'=>'F',
        'www.yljh.chc.edu.tw'=>'F',
        'www.ylps.chc.edu.tw'=>'F',
        'www.ymes.chc.edu.tw'=>'F',
        'www.ymsc.chc.edu.tw'=>'F',
        'www.yses.chc.edu.tw'=>'F',
        'www.ysps.chc.edu.tw'=>'F',
        'www.ytes.chc.edu.tw'=>'F',
        'www.yyes.chc.edu.tw'=>'F',             
        'chcschool.localhost'=>'G',
    ];

    public function check_file(){
        $check_file = $this->school_check_file[$_SERVER['HTTP_HOST']];        
        $filePath = storage_path('app/public/check_file/'.$check_file.'/DN_CHECK_FILE.htm');
        return response()->file($filePath);
    }

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
        $blocks = [];
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

        $post_show_number = ($setup->post_show_number)?$setup->post_show_number:10;
        $posts = Post::where(function ($query) {
            $query->where('die_date',null)->orWhere('die_date','>=',date('Y-m-d'));
            })->where('created_at','<',date('Y-m-d H:i:s'))->orderBy('top', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->paginate($post_show_number);
        
        //檢查置頂日期
        foreach($posts as $post){
            if($post->top ==1){
                if($post->top_date < date('Y-m-d')){
                    $att['top'] = null;
                    $att['top_date'] = null;
                    $post->update($att);
                }    
            }
        }


        //校園跑馬燈
        $school_marquees = SchoolMarquee::where('start_date','<=',date('Y-m-d'))
        ->where('stop_date','>=',date('Y-m-d'))
        ->orderBy('id','DESC')
        ->get();

        //榮譽榜資料庫資料
        $honors = Post::where('insite', '2')
                ->where(function ($query) {
                    $query->where('die_date',null)->orWhere('die_date','>=',date('Y-m-d'));
                })->orderBy('top', 'DESC')
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
        $photo_links = PhotoLink::orderBy('created_at','DESC')->orderBy('order_by', 'DESC')->paginate($photo_link_number);
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
            'post_show_number'=>$post_show_number,
            'school_marquees'=>$school_marquees,
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

    public function rss()
    {
        $posts = Post::where(function ($query) {
            $query->where('die_date',null)->orWhere('die_date','>=',date('Y-m-d'));
            })->where('created_at','<',date('Y-m-d H:i:s'))->orderBy('top', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->paginate(50);
        
        $post_types = PostType::orderBy('order_by')->get();
        $type[0] = "一般公告";
        foreach($post_types as $post_type){
            $type[$post_type->id] = $post_type->name;
        }        

        $items = "";
        $setup = Setup::first();
        foreach ($posts as $post) {
            $insite = (empty($post->insite))?0:$post->insite;
            $web = $_SERVER['HTTP_HOST'];
            $items .= '
            <item>
                <link>
                https://' . $web . '/posts/' . $post->id . '
                </link>
                <title>
                    <![CDATA[ ' . $post->title . ' ]]>
                </title>
                <author>' . $post->job_title . '</author>
                <category>
                    <![CDATA[ ' . $type[$insite] . ' ]]>
                </category>
                <pubDate>' . substr($post->passed_at, 0, 16) . '</pubDate>
                <guid>
                    ' . $web . '/posts/' . $post->id . '
                </guid>
                <description>
                    <![CDATA[
                        ' . $post->content . '
                    ]]>
                </description>
            </item>
            ';
        }

        $content = '<?xml version="1.0" encoding="UTF-8"?>
            <rss version="2.0">
                <channel>
                <title>
                    <![CDATA[ '.$setup->site_name.' ]]>
                </title>
                <link>https://'.$web.'</link>
                <description>
                    <![CDATA[
                        歡迎光臨本校網站，教育之美，美在人心！
                    ]]>
                </description>
                <language>utf-8</language>
                <copyright>
                    <![CDATA[
                        版權來自：https://'.$web.'
                    ]]>
                </copyright>
                ' . $items . '
                </channel>
            </rss>

        ';
        $invalid_characters = '/[^\x9\xa\x20-\xD7FF\xE000-\xFFFD]/';
        $content = preg_replace($invalid_characters, '', $content);
        return Response::make($content, '200')->header('Content-Type', 'text/xml');
    }
}
