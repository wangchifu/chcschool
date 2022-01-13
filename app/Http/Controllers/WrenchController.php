<?php

namespace App\Http\Controllers;

use App\User;
use App\Wrench;
use Illuminate\Http\Request;
Use SQLite3;

class WrenchController extends Controller
{
    public function __construct()
    {
        $this->db = new SQLite3(env('SQLITE'));
    }
    public function index($page=null)
    {
        //$wrenches = Wrench::orderBy('id','DESC')->paginate('10');
        //$sql = "INSERT INTO wrenches (user_id, content, created_at,updated_at) VALUES ('1', 'test title', '2021-08-11 12:00','2021-08-11 13:00');";
        //$sql ="select * from wrenches";
        //$this->db->exec($sql);
        //$ret = $this->db->query($sql);
        //while($row = $ret->fetchArray(SQLITE3_ASSOC)){
        //    echo $row['content']."<br>";
        //}
        $page = (empty($page))?"1":$page;
        $n1 = ($page-1)*10;
        $admin =(auth()->user()->code == env('ADMIN_CODE') and auth()->user()->username == env('ADMIN_USERNAME'))?"1":"";
        $sql = "select * from wrenches order by id DESC limit ".$n1.",10";
        $this->db->exec($sql);
        $ret = $this->db->query($sql);
        $i=0;
        $wrenches = [];
        while($row = $ret->fetchArray(SQLITE3_ASSOC)){
            $wrenches[$i]['id'] = $row['id'];
            //$user = User::find($row['user_id']);
	    //$wrenches[$i]['username'] = $user->name;
	    $wrenches[$i]['school'] = $row['school'];
	    $wrenches[$i]['job_title'] = $row['job_title'];
	    $wrenches[$i]['name'] = $row['name'];
            $wrenches[$i]['content'] = $row['content'];
            $wrenches[$i]['reply'] = $row['reply'];
            if(!isset($row['show'])) $row['show'] = 0;
            $wrenches[$i]['show'] = $row['show'];
            $wrenches[$i]['created_at'] = $row['created_at'];
            $wrenches[$i]['updated_at'] = $row['updated_at'];
            $i++;
        }

        $sql = "SELECT COUNT(*) as pages FROM wrenches;";
        $this->db->exec($sql);
        $ret = $this->db->query($sql);
        $pages = 0;
        while($row = $ret->fetchArray(SQLITE3_ASSOC)){
            $pages = $row['pages'];
        }

        $disabled1 = ($page==1)?"disabled":"";
        $disabled2 = ($page*10>=$pages)?"disabled":"";
        $page1 = ($page==1)?$page:$page-1;
        $page2 = ($page*10>=$pages)?$page:$page+1;
        $data = [
            'admin'=>$admin,
            'wrenches'=>$wrenches,
            'pages'=>$pages,
            'disabled1'=>$disabled1,
            'disabled2'=>$disabled2,
            'page1'=>$page1,
            'page2'=>$page2,
        ];
        return view('wrenches.index',$data);
    }

    public function store(Request $request)
    {
        $sql = "INSERT INTO wrenches (school,job_title,name,email, content, created_at,updated_at) VALUES ('".auth()->user()->school."','".auth()->user()->title."','".auth()->user()->name."','".$request->input('email')."','".$request->input('content')."','".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."');";
        $this->db->exec($sql);

        $id = $this->db->lastInsertRowID();

        //處理檔案上傳
        if ($request->hasFile('files')) {
            $files = $request->file('files');
            foreach($files as $file){
                $info = [
                    'mime-type' => $file->getMimeType(),
                    'original_filename' => $file->getClientOriginalName(),
                    'extension' => $file->getClientOriginalExtension(),
                    'size' => $file->getClientSize(),
                ];
                $file->storeAs('public/wrenches/'.$id, $info['original_filename']);
            }
        }

        $att['email'] = $request->input('email');
        $user = User::where('id',auth()->user()->id)->first();
        $user->update($att);

        $subject = '學校網站平台有人回報系統錯誤與建議';
        $body = $request->input('content');
        send_mail(env('ADMIN_EMAIL'),$subject,$body);
        return redirect()->route('wrench.index');
    }

    public function reply(Request $request)
    {
        $sql = "select * from wrenches where id='".$request->input('id')."'";
        $this->db->exec($sql);
        $ret = $this->db->query($sql);
        while($row = $ret->fetchArray(SQLITE3_ASSOC)){
            $sql = "update wrenches set show = '1',reply='".$request->input('reply')."',updated_at='".date('Y-m-d H:i:s')."' where id='".$row['id']."';";
            $this->db->exec($sql);
            $content = $row['content'];
            $user_email = $row['email'];
        }

        //$user = User::find($user_id);

        if(!empty($user_email)){
            $subject = '回覆「學校網站平台」中「系統錯誤與建議」';
            $body = "您問到：\r\n";
            $body .= $content."\r\n";
            $body .= "\r\n系統管理員回覆：\r\n";
            $body .= $request->input('reply');
            $body .= "\r\n-----這是系統信件，請勿回信-----";
            send_mail($user_email,$subject,$body);
        }

        return redirect()->route('wrench.index');
    }

    public function set_show($id)
    {
        $sql = "update wrenches set show = '1' where id='".$id."';";
        $this->db->exec($sql);

        return redirect()->route('wrench.index');
    }

    public function destroy($id)
    {
        $folder = storage_path('app/public/wrenches/'.$id);
        del_folder($folder);

        $sql = "delete from wrenches where id='".$id."';";
        $this->db->exec($sql);

        return redirect()->route('wrench.index');
    }

    public function download($wrench_id, $filename)
    {
        $file = storage_path('app/public/wrenches/' . $wrench_id . '/' . $filename);
        return response()->download($file);
    }
}

