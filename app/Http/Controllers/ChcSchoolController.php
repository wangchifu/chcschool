<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PDO;          // 引入原生的 PDO
use PDOException; // 引入原生的 PDOException
use Illuminate\Support\Facades\Cache;
use Net_DNS2_Resolver;
use Net_DNS2_Updater;
use Net_DNS2_RR;
use Net_DNS2_Exception;
use Carbon\Carbon;

// RP申請獲得
define('CLIENT_ID', env('CLIENT_ID'));
define('CLIENT_SECRET', env('CLIENT_SECRET'));
//
define('AUTH_SECRET', '');
//define('REDIR_URI0', 'http://openid.zipko.info/callback.php');
//define('REDIR_URI0', 'https://newstu.chc.edu.tw/auth/callback');
$url = $_SERVER['SERVER_NAME'];
define('REDIR_URI0', 'https://'.$url.'/chcschool_auth/callback');
define('WELL_KNOWN_URL', 'https://chc.sso.edu.tw/.well-known/openid-configuration');
// 預設0由設定檔的URL決定；設定為1則每次皆由WELL_KNOWN取回END POINT URL
define('DYNAMICAL_ENDPOINT', 0);
// DYNAMICAL_ENDPOINT設為0下方三項需填寫
define('AUTH_ENDPOINT', 'https://chc.sso.edu.tw/oidc/v1/azp');
define('TOKEN_ENDPOINT', 'https://chc.sso.edu.tw/oidc/v1/token');
define('USERINFO_ENDPOINT', 'https://chc.sso.edu.tw/oidc/v1/userinfo');
define('JWKS_URI', 'https://chc.sso.edu.tw/oidc/v1/jwksets');
//
// PROFILE URL
define('PROFILE_ENDPOINT', 'https://chc.sso.edu.tw/moeresource/api/v1/oidc/profile');

class openid {
    /**
     *
    */
    public function getEndPoint($rtn_array=false){
      $options = array(
        'http' => array(
          'header'  => '',
          'method'  => 'GET',
          'content' => ''
        ));
      $context = stream_context_create($options);
      $result = file_get_contents(WELL_KNOWN_URL, false, $context);
      $u= json_decode($result, $rtn_array);
      return $u; //object
    }
  
    public function getAccessToken($token_ep='' ,$code='', $redir_uri='' ,$rtn_array=false){
      $hash = base64_encode( CLIENT_ID . ":" . CLIENT_SECRET);
      $data = array('grant_type' => 'authorization_code', 'code'=> $code,
        'redirect_uri' => $redir_uri);
      $header= array( "Content-type: application/x-www-form-urlencoded",
         "Authorization: Basic $hash" ) ;
      $options = array(
          'http' => array(
            'header'  => $header,
            'method'  => 'POST',
            'content' => http_build_query($data)
          ));
      $context = stream_context_create($options);
      $result = file_get_contents($token_ep, false, $context);
      $j= json_decode($result, $rtn_array);
      return $j;
    }
    public function getModnExp($jwks_uri){
      $options = array(
        'http' => array(
          'header'  => '',
          'method'  => 'GET',
          'content' => ''
        ));
      $context = stream_context_create($options);
      $result = file_get_contents($jwks_uri, false, $context);
      $u= json_decode($result, true);
      return $u; //object
    }
   
    public function getUserinfo($token_ep='' ,$accesstoken='',$rtn_array=false){
      $header= array( "Authorization: Bearer $accesstoken" );
      $options = array(
          'http' => array(
            'header'  => $header,
            'method'  => 'GET',
            'content' => ''
          ));
      $context = stream_context_create($options);
      $result = file_get_contents($token_ep, false, $context);
      $u= json_decode($result,$rtn_array);
      return $u;
    }
    public function urlsafeB64Encode($input)
    {
      return str_replace('=', '', strtr(base64_encode($input), '+/', '-_'));
    }
  
    public function urlsafeB64Decode($input)
    {
      $remainder = strlen($input) % 4;
      if ($remainder) {
         $padlen = 4 - $remainder;
         $input .= str_repeat('=', $padlen);
      }
      return base64_decode(strtr($input, '-_', '+/'));
    }       
  
}

class ChcSchoolController extends Controller
{
    public function __construct()
    {
        $this->dnsServer = env('DDNS_SERVER', '127.0.0.1');            
    }

    public function chcschool_sso(){
        //session_start();
        $obj= new openid();
        session(['azp_state'=>rand(0,9999999)]);
        if(!session()->has('nonce')){
            session(['nonce'=>base64_encode(session('azp_state'))]);
        }else{
            session(['nonce'=>session('nonce')]);
        }

        //$_SESSION['azp_state']=rand(0,9999999); //隨機產生state值
        //$_SESSION['nonce']=isset($_SESSION['nonce'])? $_SESSION['nonce']:base64_encode($_SESSION['azp_state']);

        $auth_ep=AUTH_ENDPOINT;
        if(DYNAMICAL_ENDPOINT){
            $auth_ep=$ep->getEndPoint()->authorization_endpoint;
        }
        $link = $auth_ep . "?response_type=code&client_id=". CLIENT_ID ."&redirect_uri=".urlencode(REDIR_URI0) ."&scope=openid+email+profile+eduinfo+personid&state=".session('azp_state')."&nonce=".session('nonce');
    //       dd($link);
        return redirect($link);
    }

    private $csvPath = 'privacy/dns_admin.csv';

    public function chcschool_callback(){
        $code= $_GET['code'];
        $state= $_GET['state'];
        
        //驗證 $state
        if( !isset($_GET['code']) ||  !isset($_GET['state'])){
            die ("認證伺服器回傳結果失敗！");
        }
        
        if( strcmp($state, session('azp_state'))){
            die ("錯誤的認證狀態，請重新嘗試！");
        }      
        
        $obj= new openid();
        
        $token_ep=TOKEN_ENDPOINT;
        if(DYNAMICAL_ENDPOINT){
            $token_ep=$ep->getEndPoint()->token_endpoint;
        }
        
        $acctoken= $obj->getAccessToken($token_ep ,$code, REDIR_URI0);
        if( !$acctoken || !isset($acctoken->access_token) ) {
            die ("無法取得ACCESS TOKEN");
        }
        // 把access token, id_token記到session中
        // 未來需要取得其他scope再用此access token 來做
        session(['access_token'=>$acctoken->access_token]);
        session(['id_token'=>$acctoken->id_token]);
        
            //驗證 access token
        if(!session()->has('access_token')){
            die ("無存取用權杖，無法取回使用者資料！");
        }

        //取回access token
        //include "config.php";
        //include "library.class.php";
        //$obj= new openid();

        $token_ep2=USERINFO_ENDPOINT;
        if(DYNAMICAL_ENDPOINT){
            $token_ep2=$ep->getEndPoint()->userinfo_endpoint;
        }

        $userinfo = $obj->getUserinfo($token_ep2 ,session('access_token'), true);
        $profile = $obj->getUserinfo("https://chc.sso.edu.tw/cncresource/api/v1/personid" ,session('access_token'), true);
        $edufile = $obj->getUserinfo("https://chc.sso.edu.tw/cncresource/api/v1/eduinfo" ,session('access_token'), true);
        if( !$userinfo) {
            die ("無法取得 USER INFO");
        }
        $user_obj['username'] = $userinfo['sub'];
        $user_obj['code'] = $edufile['schoolid'];
        $user_obj['title'] = $edufile['titles'][0]['titles'][0];
        $user_obj['name'] = $userinfo['name'];     
        if ($user_obj['title'] == "學生") {
            $url = "https://chc.sso.edu.tw/oidc/v1/logout-to-go";
            $post_logout_redirect_uri = url('index');        
            $id_token_hint = session('id_token');
            $link = $url . "?post_logout_redirect_uri=".$post_logout_redirect_uri."&id_token_hint=" . $id_token_hint;
            return redirect($link);
        }else{
            //$csvPath = 'privacy/dns_admin.csv'; 

            // 預設將 session 設為 0 或清除
            session()->forget('dns_admin');
            session()->forget('dns_code');
            session()->forget('dns_name');
            session()->forget('dns_title');
            session()->forget('dns_username');

            // 3. 檢查檔案是否存在
            if (Storage::exists($this->csvPath)) {
                // 讀取 CSV 內容
                $fileContent = Storage::get($this->csvPath);
                
                // 按行拆分 CSV
                $lines = explode("\n", str_replace("\r", "", $fileContent));
                
                foreach ($lines as $line) {
                    if (trim($line) === '') continue; // 跳過空行
                    
                    // 解析每一列 (預設逗號分隔)
                    $data = str_getcsv($line); 
                    
                    // 確保至少有兩欄 (Index 0: 學校代碼, Index 1: Username)
                    if (count($data) >= 2) {
                        $csvCode = trim($data[0]);
                        $csvUsername = trim($data[1]);
                        
                        // 比對兩欄是否皆符合
                        if ($csvCode === (string)$user_obj['code'] && $csvUsername === (string)$user_obj['username']) {
                            // 符合條件，寫入 Session
                            session(['dns_admin' => 1]);
                            session(['dns_code' => $user_obj['code']]);
                            session(['dns_title' => $user_obj['title']]);
                            session(['dns_name' => $user_obj['name']]);                            
                            session(['dns_username' => $user_obj['username']]);  
                            break; // 找到了就可以中斷迴圈
                        }
                    }
                }
            }
        }
        if(session('dns_admin') == 1){
            return redirect()->route('dns_admin');
        }else{
            echo "帳號:".$user_obj['username']."<br>";
            echo "學校代碼:".$user_obj['code']."<br>";
            echo "職稱:".$user_obj['title']."<br>";
            echo "姓名:".$user_obj['name']."<br>";
            echo "您不是本系統管理員，請 <a href='https://chcschool.chc.edu.tw/chcschool_logout'>[ 離開 ]</a> 。<br>";
        }
    }

    public function chcschool_logout(){
        session()->forget('dns_admin');
        session()->forget('dns_code');
        session()->forget('dns_name');
        session()->forget('dns_title');
        session()->forget('dns_username');
        $url = "https://chc.sso.edu.tw/oidc/v1/logout-to-go";
        $post_logout_redirect_uri = url('index');        
        $id_token_hint = session('id_token');
        $link = $url . "?post_logout_redirect_uri=".$post_logout_redirect_uri."&id_token_hint=" . $id_token_hint;
        return redirect($link);
    }

    public function pages(){        
        ///////////////////////////////連接資料庫
        $dbms='mysql';     //数据库类型
        $host=env('DNS_DB_HOST'); //数据库主机名
        $dbName=env('DNS_DB_NAME');    //使用的数据库
        $user=env('DNS_DB_USER');      //数据库连接用户名
        $pass=env('DNS_DB_PASS');          //对应的密码
        $dsn="$dbms:host=$host;dbname=$dbName";

        try {
            $dbh = new PDO($dsn, $user, $pass); //初始化一个PDO对象
            $dbh->query('SET NAMES "utf8"');

        } catch (PDOException $e) {
            die ("Error!: " . $e->getMessage() . "<br/>");
        }

        $schools = [];
        $school3_1 = 0;
        $school3_2 = 0;
        // 使用 IN 一次查詢兩種 rdata，並將 rdata 一併選出
        $sql = "SELECT DISTINCT u.brief, r.rdata 
                FROM RR r, unit u 
                WHERE CONCAT(r.fqdn, '.chc', '.edu', '.tw') = u.domain 
                AND r.rdata IN ('163.23.200.50', '163.23.200.49');";

        $result = $dbh->query($sql);

        foreach ($result as $row) {
            if ($row['rdata'] === '163.23.200.50') {
                $schools[$row['brief']] = "50";
                $school3_1++;
            } elseif ($row['rdata'] === '163.23.200.49') {
                $schools[$row['brief']] = "49";
                $school3_2++;
            }
        }

        $schools['原斗國中小'] = $schools['原斗國小'];
        $schools['成功高中'] = "50";
        $school3_1++;
        
        $all_school = [];
        $all_school['彰化市'] = [];
        $all_school['芬園鄉'] = [];
        $all_school['花壇鄉'] = [];
        $all_school['秀水鄉'] = [];
        $all_school['鹿港鎮'] = [];
        $all_school['福興鄉'] = [];
        $all_school['線西鄉'] = [];
        $all_school['和美鎮'] = [];
        $all_school['伸港鄉'] = [];
        $all_school['員林市'] = [];
        $all_school['社頭鄉'] = [];
        $all_school['永靖鄉'] = [];
        $all_school['埔心鄉'] = [];
        $all_school['溪湖鎮'] = [];
        $all_school['大村鄉'] = [];
        $all_school['埔鹽鄉'] = [];
        $all_school['田中鎮'] = [];
        $all_school['北斗鎮'] = [];
        $all_school['田尾鄉'] = [];
        $all_school['埤頭鄉'] = [];
        $all_school['溪州鄉'] = [];
        $all_school['竹塘鄉'] = [];
        $all_school['二林鎮'] = [];
        $all_school['大城鄉'] = [];
        $all_school['芳苑鄉'] = [];
        $all_school['二水鄉'] = [];
        $all_school['彰化市']['074308'] = ['school' => '彰化藝術高中', 'website' => 'chash.chc.edu.tw'];
        $all_school['二林鎮']['074313'] = ['school' => '二林高中', 'website' => 'elsh.chc.edu.tw'];
        $all_school['二水鄉']['074529'] = ['school' => '二水國中', 'website' => 'esjh.chc.edu.tw'];
        $all_school['和美鎮']['074323'] = ['school' => '和美高中', 'website' => 'hmjh.chc.edu.tw'];
        $all_school['田中鎮']['074328'] = ['school' => '田中高中', 'website' => 'tcjh.chc.edu.tw'];
        $all_school['溪湖鎮']['074339'] = ['school' => '成功高中', 'website' => 'cksh.chc.edu.tw'];
        $all_school['北斗鎮']['074501'] = ['school' => '北斗國中', 'website' => 'ptjhs.chc.edu.tw'];
        $all_school['鹿港鎮']['074502'] = ['school' => '鹿港國中', 'website' => 'lkjh.chc.edu.tw'];
        $all_school['鹿港鎮']['074503'] = ['school' => '鹿鳴國中', 'website' => 'lmjh.chc.edu.tw'];
        $all_school['線西鄉']['074504'] = ['school' => '線西國中', 'website' => 'hhjh.chc.edu.tw'];
        $all_school['彰化市']['074505'] = ['school' => '陽明國中', 'website' => 'ymsc.chc.edu.tw'];
        $all_school['彰化市']['074506'] = ['school' => '彰安國中', 'website' => 'cajh.chc.edu.tw'];
        $all_school['彰化市']['074507'] = ['school' => '彰德國中', 'website' => 'ctjh.chc.edu.tw'];
        $all_school['芬園鄉']['074509'] = ['school' => '芬園國中', 'website' => 'fyjh.chc.edu.tw'];
        $all_school['員林市']['074510'] = ['school' => '員林國中', 'website' => 'yljh.chc.edu.tw'];
        $all_school['員林市']['074511'] = ['school' => '明倫國中', 'website' => 'mljh.chc.edu.tw'];
        $all_school['二林鎮']['074512'] = ['school' => '萬興國中', 'website' => 'whjh.chc.edu.tw'];
        $all_school['竹塘鄉']['074514'] = ['school' => '竹塘國中', 'website' => 'ctjhs.chc.edu.tw'];
        $all_school['大城鄉']['074515'] = ['school' => '大城國中', 'website' => 'tcjhs.chc.edu.tw'];
        $all_school['芳苑鄉']['074516'] = ['school' => '草湖國中', 'website' => 'thjh.chc.edu.tw'];
        $all_school['芳苑鄉']['074517'] = ['school' => '芳苑國中', 'website' => 'fyjhs.chc.edu.tw'];
        $all_school['溪湖鎮']['074518'] = ['school' => '溪湖國中', 'website' => 'cfjh.chc.edu.tw'];
        $all_school['埔鹽鄉']['074519'] = ['school' => '埔鹽國中', 'website' => 'pyjh.chc.edu.tw'];
        $all_school['埔心鄉']['074520'] = ['school' => '埔心國中', 'website' => 'psjh.chc.edu.tw'];
        $all_school['福興鄉']['074521'] = ['school' => '福興國中', 'website' => 'fsjh.chc.edu.tw'];
        $all_school['秀水鄉']['074522'] = ['school' => '秀水國中', 'website' => 'hsjh.chc.edu.tw'];
        $all_school['伸港鄉']['074524'] = ['school' => '伸港國中', 'website' => 'skjh.chc.edu.tw'];
        $all_school['大村鄉']['074525'] = ['school' => '大村國中', 'website' => 'ttjh.chc.edu.tw'];
        $all_school['花壇鄉']['074526'] = ['school' => '花壇國中', 'website' => 'htjh.chc.edu.tw'];
        $all_school['永靖鄉']['074527'] = ['school' => '永靖國中', 'website' => 'ycjh.chc.edu.tw'];
        $all_school['社頭鄉']['074530'] = ['school' => '社頭國中', 'website' => 'stjh.chc.edu.tw'];
        $all_school['田尾鄉']['074531'] = ['school' => '田尾國中', 'website' => 'twjh.chc.edu.tw'];
        $all_school['溪州鄉']['074532'] = ['school' => '溪州國中', 'website' => 'ccjh.chc.edu.tw'];
        $all_school['溪州鄉']['074533'] = ['school' => '溪陽國中', 'website' => 'hyjh.chc.edu.tw'];
        $all_school['埤頭鄉']['074534'] = ['school' => '埤頭國中', 'website' => 'ptjh.chc.edu.tw'];
        $all_school['和美鎮']['074535'] = ['school' => '和群國中', 'website' => 'hcjh.chc.edu.tw'];
        $all_school['員林市']['074536'] = ['school' => '大同國中', 'website' => 'ttjhs.chc.edu.tw'];
        $all_school['彰化市']['074538'] = ['school' => '彰興國中', 'website' => 'csjh.chc.edu.tw'];
        $all_school['彰化市']['074540'] = ['school' => '彰泰國中', 'website' => 'ctsjh.chc.edu.tw'];
        $all_school['彰化市']['074541'] = ['school' => '信義國中小', 'website' => 'hyjhes.chc.edu.tw'];
        $all_school['鹿港鎮']['074542'] = ['school' => '鹿江國中小', 'website' => 'ljis.chc.edu.tw'];
        $all_school['彰化市']['074601'] = ['school' => '中山國小', 'website' => 'cses.chc.edu.tw'];
        $all_school['彰化市']['074602'] = ['school' => '民生國小', 'website' => 'mses.chc.edu.tw'];
        $all_school['彰化市']['074603'] = ['school' => '平和國小', 'website' => 'phes.chc.edu.tw'];
        $all_school['彰化市']['074604'] = ['school' => '南郭國小', 'website' => 'nges.chc.edu.tw'];
        $all_school['彰化市']['074605'] = ['school' => '南興國小', 'website' => 'nses.chc.edu.tw'];
        $all_school['彰化市']['074606'] = ['school' => '東芳國小', 'website' => 'tfps.chc.edu.tw'];
        $all_school['彰化市']['074607'] = ['school' => '泰和國小', 'website' => 'thps.chc.edu.tw'];
        $all_school['彰化市']['074608'] = ['school' => '三民國小', 'website' => 'smes.chc.edu.tw'];
        $all_school['彰化市']['074609'] = ['school' => '聯興國小', 'website' => 'lsps.chc.edu.tw'];
        $all_school['彰化市']['074610'] = ['school' => '大竹國小', 'website' => 'tces.chc.edu.tw'];
        $all_school['彰化市']['074611'] = ['school' => '國聖國小', 'website' => 'gses.chc.edu.tw'];
        $all_school['彰化市']['074612'] = ['school' => '快官國小', 'website' => 'kges.chc.edu.tw'];
        $all_school['彰化市']['074613'] = ['school' => '石牌國小', 'website' => 'spes.chc.edu.tw'];
        $all_school['彰化市']['074614'] = ['school' => '忠孝國小', 'website' => 'jsps.chc.edu.tw'];
        $all_school['芬園鄉']['074615'] = ['school' => '芬園國小', 'website' => 'fyps.chc.edu.tw'];
        $all_school['芬園鄉']['074616'] = ['school' => '富山國小', 'website' => 'fsps.chc.edu.tw'];
        $all_school['芬園鄉']['074617'] = ['school' => '寶山國小', 'website' => 'bses.chc.edu.tw'];
        $all_school['芬園鄉']['074618'] = ['school' => '同安國小', 'website' => 'taes.chc.edu.tw'];
        $all_school['芬園鄉']['074619'] = ['school' => '文德國小', 'website' => 'wdes.chc.edu.tw'];
        $all_school['芬園鄉']['074620'] = ['school' => '茄荖國小', 'website' => 'cles.chc.edu.tw'];
        $all_school['花壇鄉']['074621'] = ['school' => '花壇國小', 'website' => 'htes.chc.edu.tw'];
        $all_school['花壇鄉']['074622'] = ['school' => '文祥國小', 'website' => 'wses.chc.edu.tw'];
        $all_school['花壇鄉']['074623'] = ['school' => '華南國小', 'website' => 'hnes.chc.edu.tw'];
        $all_school['花壇鄉']['074624'] = ['school' => '僑愛國小', 'website' => 'caps.chc.edu.tw'];
        $all_school['花壇鄉']['074625'] = ['school' => '三春國小', 'website' => 'sstps.chc.edu.tw'];
        $all_school['花壇鄉']['074626'] = ['school' => '白沙國小', 'website' => 'bsps.chc.edu.tw'];
        $all_school['和美鎮']['074627'] = ['school' => '和美國小', 'website' => 'hmps.chc.edu.tw'];
        $all_school['和美鎮']['074628'] = ['school' => '和東國小', 'website' => 'hdes.chc.edu.tw'];
        $all_school['和美鎮']['074629'] = ['school' => '大嘉國小', 'website' => 'dces.chc.edu.tw'];
        $all_school['和美鎮']['074630'] = ['school' => '大榮國小', 'website' => 'dres.chc.edu.tw'];
        $all_school['和美鎮']['074631'] = ['school' => '新庄國小', 'website' => 'ssjes.chc.edu.tw'];
        $all_school['和美鎮']['074632'] = ['school' => '培英國小', 'website' => 'pyps.chc.edu.tw'];
        $all_school['線西鄉']['074633'] = ['school' => '線西國小', 'website' => 'sces.chc.edu.tw'];
        $all_school['線西鄉']['074634'] = ['school' => '曉陽國小', 'website' => 'syes.chc.edu.tw'];
        $all_school['伸港鄉']['074635'] = ['school' => '新港國小', 'website' => 'sgps.chc.edu.tw'];
        $all_school['伸港鄉']['074636'] = ['school' => '伸東國小', 'website' => 'sdes.chc.edu.tw'];
        $all_school['伸港鄉']['074637'] = ['school' => '伸仁國小', 'website' => 'sres.chc.edu.tw'];
        $all_school['伸港鄉']['074638'] = ['school' => '大同國小', 'website' => 'dtes.chc.edu.tw'];
        $all_school['鹿港鎮']['074639'] = ['school' => '鹿港國小', 'website' => 'lges.chc.edu.tw'];
        $all_school['鹿港鎮']['074640'] = ['school' => '文開國小', 'website' => 'wkes.chc.edu.tw'];
        $all_school['鹿港鎮']['074641'] = ['school' => '洛津國小', 'website' => 'ljes.chc.edu.tw'];
        $all_school['鹿港鎮']['074642'] = ['school' => '海埔國小', 'website' => 'hpes.chc.edu.tw'];
        $all_school['鹿港鎮']['074643'] = ['school' => '新興國小', 'website' => 'bsses.chc.edu.tw'];
        $all_school['鹿港鎮']['074644'] = ['school' => '草港國小', 'website' => 'tges.chc.edu.tw'];
        $all_school['鹿港鎮']['074645'] = ['school' => '頂番國小', 'website' => 'dfes.chc.edu.tw'];
        $all_school['鹿港鎮']['074646'] = ['school' => '東興國小', 'website' => 'sdses.chc.edu.tw'];
        $all_school['福興鄉']['074647'] = ['school' => '管嶼國小', 'website' => 'gyes.chc.edu.tw'];
        $all_school['福興鄉']['074648'] = ['school' => '文昌國小', 'website' => 'wces.chc.edu.tw'];
        $all_school['福興鄉']['074649'] = ['school' => '西勢國小', 'website' => 'ssses.chc.edu.tw'];
        $all_school['福興鄉']['074650'] = ['school' => '大興國小', 'website' => 'bdsps.chc.edu.tw'];
        $all_school['福興鄉']['074651'] = ['school' => '永豐國小', 'website' => 'yfes.chc.edu.tw'];
        $all_school['福興鄉']['074652'] = ['school' => '日新國小', 'website' => 'rses.chc.edu.tw'];
        $all_school['福興鄉']['074653'] = ['school' => '育新國小', 'website' => 'yses.chc.edu.tw'];
        $all_school['秀水鄉']['074654'] = ['school' => '秀水國小', 'website' => 'hses.chc.edu.tw'];
        $all_school['秀水鄉']['074655'] = ['school' => '馬興國小', 'website' => 'smses.chc.edu.tw'];
        $all_school['秀水鄉']['074656'] = ['school' => '華龍國小', 'website' => 'hlps.chc.edu.tw'];
        $all_school['秀水鄉']['074657'] = ['school' => '明正國小', 'website' => 'mcps.chc.edu.tw'];
        $all_school['秀水鄉']['074658'] = ['school' => '陝西國小', 'website' => 'ssps.chc.edu.tw'];
        $all_school['秀水鄉']['074659'] = ['school' => '育民國小', 'website' => 'ymes.chc.edu.tw'];
        $all_school['溪湖鎮']['074660'] = ['school' => '溪湖國小', 'website' => 'shps.chc.edu.tw'];
        $all_school['溪湖鎮']['074661'] = ['school' => '東溪國小', 'website' => 'bdses.chc.edu.tw'];
        $all_school['溪湖鎮']['074662'] = ['school' => '湖西國小', 'website' => 'fses.chc.edu.tw'];
        $all_school['溪湖鎮']['074663'] = ['school' => '湖東國小', 'website' => 'fdes.chc.edu.tw'];
        $all_school['溪湖鎮']['074664'] = ['school' => '湖南國小', 'website' => 'hnps.chc.edu.tw'];
        $all_school['溪湖鎮']['074665'] = ['school' => '媽厝國小', 'website' => 'mtes.chc.edu.tw'];
        $all_school['埔鹽鄉']['074666'] = ['school' => '埔鹽國小', 'website' => 'pyes.chc.edu.tw'];
        $all_school['埔鹽鄉']['074667'] = ['school' => '大園國小', 'website' => 'dyes.chc.edu.tw'];
        $all_school['埔鹽鄉']['074668'] = ['school' => '南港國小', 'website' => 'ngps.chc.edu.tw'];
        $all_school['埔鹽鄉']['074669'] = ['school' => '好修國小', 'website' => 'hsps.chc.edu.tw'];
        $all_school['埔鹽鄉']['074670'] = ['school' => '永樂國小', 'website' => 'yles.chc.edu.tw'];
        $all_school['埔鹽鄉']['074671'] = ['school' => '新水國小', 'website' => 'sses.chc.edu.tw'];
        $all_school['埔鹽鄉']['074672'] = ['school' => '天盛國小', 'website' => 'tses.chc.edu.tw'];
        $all_school['埔心鄉']['074673'] = ['school' => '埔心國小', 'website' => 'pses.chc.edu.tw'];
        $all_school['埔心鄉']['074674'] = ['school' => '太平國小', 'website' => 'tpes.chc.edu.tw'];
        $all_school['埔心鄉']['074675'] = ['school' => '舊館國小', 'website' => 'jges.chc.edu.tw'];
        $all_school['埔心鄉']['074676'] = ['school' => '羅厝國小', 'website' => 'rtes.chc.edu.tw'];
        $all_school['埔心鄉']['074677'] = ['school' => '鳳霞國小', 'website' => 'sfsps.chc.edu.tw'];
        $all_school['埔心鄉']['074678'] = ['school' => '梧鳳國小', 'website' => 'wfes.chc.edu.tw'];
        $all_school['埔心鄉']['074679'] = ['school' => '明聖國小', 'website' => 'msps.chc.edu.tw'];
        $all_school['員林市']['074680'] = ['school' => '員林國小', 'website' => 'ylps.chc.edu.tw'];
        $all_school['員林市']['074681'] = ['school' => '育英國小', 'website' => 'yyes.chc.edu.tw'];
        $all_school['員林市']['074682'] = ['school' => '靜修國小', 'website' => 'sjses.chc.edu.tw'];
        $all_school['員林市']['074683'] = ['school' => '僑信國小', 'website' => 'csps.chc.edu.tw'];
        $all_school['員林市']['074684'] = ['school' => '員東國小', 'website' => 'ytes.chc.edu.tw'];
        $all_school['員林市']['074685'] = ['school' => '饒明國小', 'website' => 'rmes.chc.edu.tw'];
        $all_school['員林市']['074686'] = ['school' => '東山國小', 'website' => 'dsps.chc.edu.tw'];
        $all_school['員林市']['074687'] = ['school' => '青山國小', 'website' => 'chcses.chc.edu.tw'];
        $all_school['員林市']['074688'] = ['school' => '明湖國小', 'website' => 'mhes.chc.edu.tw'];
        $all_school['大村鄉']['074689'] = ['school' => '大村國小', 'website' => 'dtps.chc.edu.tw'];
        $all_school['大村鄉']['074690'] = ['school' => '大西國小', 'website' => 'dses.chc.edu.tw'];
        $all_school['大村鄉']['074691'] = ['school' => '村上國小', 'website' => 'tsps.chc.edu.tw'];
        $all_school['大村鄉']['074692'] = ['school' => '村東國小', 'website' => 'tdes.chc.edu.tw'];
        $all_school['永靖鄉']['074693'] = ['school' => '永靖國小', 'website' => 'yces.chc.edu.tw'];
        $all_school['永靖鄉']['074694'] = ['school' => '福德國小', 'website' => 'fdps.chc.edu.tw'];
        $all_school['永靖鄉']['074695'] = ['school' => '永興國小', 'website' => 'ysps.chc.edu.tw'];
        $all_school['永靖鄉']['074696'] = ['school' => '福興國小', 'website' => 'sfses.chc.edu.tw'];
        $all_school['永靖鄉']['074697'] = ['school' => '德興國小', 'website' => 'sdsps.chc.edu.tw'];
        $all_school['田中鎮']['074698'] = ['school' => '田中國小', 'website' => 'tjes.chc.edu.tw'];
        $all_school['田中鎮']['074699'] = ['school' => '三潭國小', 'website' => 'stes.chc.edu.tw'];
        $all_school['田中鎮']['074700'] = ['school' => '大安國小', 'website' => 'daes.chc.edu.tw'];
        $all_school['田中鎮']['074701'] = ['school' => '內安國小', 'website' => 'naes.chc.edu.tw'];
        $all_school['田中鎮']['074702'] = ['school' => '東和國小', 'website' => 'dhps.chc.edu.tw'];
        $all_school['田中鎮']['074703'] = ['school' => '明禮國小', 'website' => 'mles.chc.edu.tw'];
        $all_school['社頭鄉']['074704'] = ['school' => '社頭國小', 'website' => 'stps.chc.edu.tw'];
        $all_school['社頭鄉']['074705'] = ['school' => '橋頭國小', 'website' => 'ctps.chc.edu.tw'];
        $all_school['社頭鄉']['074706'] = ['school' => '朝興國小', 'website' => 'scsps.chc.edu.tw'];
        $all_school['社頭鄉']['074707'] = ['school' => '清水國小', 'website' => 'bcses.chc.edu.tw'];
        $all_school['社頭鄉']['074708'] = ['school' => '湳雅國小', 'website' => 'nyes.chc.edu.tw'];
        $all_school['二水鄉']['074709'] = ['school' => '二水國小', 'website' => 'eses.chc.edu.tw'];
        $all_school['二水鄉']['074710'] = ['school' => '復興國小', 'website' => 'fsses.chc.edu.tw'];
        $all_school['二水鄉']['074711'] = ['school' => '源泉國小', 'website' => 'ycps.chc.edu.tw'];
        $all_school['北斗鎮']['074712'] = ['school' => '北斗國小', 'website' => 'bdes.chc.edu.tw'];
        $all_school['北斗鎮']['074713'] = ['school' => '萬來國小', 'website' => 'wles.chc.edu.tw'];
        $all_school['北斗鎮']['074714'] = ['school' => '螺青國小', 'website' => 'rces.chc.edu.tw'];
        $all_school['北斗鎮']['074715'] = ['school' => '大新國小', 'website' => 'dsses.chc.edu.tw'];
        $all_school['北斗鎮']['074716'] = ['school' => '螺陽國小', 'website' => 'ryes.chc.edu.tw'];
        $all_school['田尾鄉']['074717'] = ['school' => '田尾國小', 'website' => 'twps.chc.edu.tw'];
        $all_school['田尾鄉']['074718'] = ['school' => '南鎮國小', 'website' => 'njes.chc.edu.tw'];
        $all_school['田尾鄉']['074719'] = ['school' => '陸豐國小', 'website' => 'lfes.chc.edu.tw'];
        $all_school['田尾鄉']['074720'] = ['school' => '仁豐國小', 'website' => 'rfes.chc.edu.tw'];
        $all_school['埤頭鄉']['074721'] = ['school' => '埤頭國小', 'website' => 'ptes.chc.edu.tw'];
        $all_school['埤頭鄉']['074722'] = ['school' => '合興國小', 'website' => 'shses.chc.edu.tw'];
        $all_school['埤頭鄉']['074723'] = ['school' => '豐崙國小', 'website' => 'fles.chc.edu.tw'];
        $all_school['埤頭鄉']['074724'] = ['school' => '芙朝國小', 'website' => 'fces.chc.edu.tw'];
        $all_school['埤頭鄉']['074725'] = ['school' => '中和國小', 'website' => 'ches.chc.edu.tw'];
        $all_school['埤頭鄉']['074726'] = ['school' => '大湖國小', 'website' => 'dhes.chc.edu.tw'];
        $all_school['溪州鄉']['074727'] = ['school' => '溪州國小', 'website' => 'sjps.chc.edu.tw'];
        $all_school['溪州鄉']['074728'] = ['school' => '僑義國小', 'website' => 'cyes.chc.edu.tw'];
        $all_school['溪州鄉']['074729'] = ['school' => '三條國小', 'website' => 'steps.chc.edu.tw'];
        $all_school['溪州鄉']['074730'] = ['school' => '水尾國小', 'website' => 'swes.chc.edu.tw'];
        $all_school['溪州鄉']['074731'] = ['school' => '潮洋國小', 'website' => 'cyps.chc.edu.tw'];
        $all_school['溪州鄉']['074732'] = ['school' => '成功國小', 'website' => 'cges.chc.edu.tw'];
        $all_school['溪州鄉']['074733'] = ['school' => '圳寮國小', 'website' => 'jles.chc.edu.tw'];
        $all_school['溪州鄉']['074734'] = ['school' => '大莊國小', 'website' => 'djps.chc.edu.tw'];
        $all_school['溪州鄉']['074735'] = ['school' => '南州國小', 'website' => 'njps.chc.edu.tw'];
        $all_school['二林鎮']['074736'] = ['school' => '二林國小', 'website' => 'elps.chc.edu.tw'];
        $all_school['二林鎮']['074737'] = ['school' => '興華國小', 'website' => 'shes.chc.edu.tw'];
        $all_school['二林鎮']['074738'] = ['school' => '中正國小', 'website' => 'ccps.chc.edu.tw'];
        $all_school['二林鎮']['074739'] = ['school' => '育德國小', 'website' => 'ydes.chc.edu.tw'];
        $all_school['二林鎮']['074740'] = ['school' => '香田國小', 'website' => 'sstes.chc.edu.tw'];
        $all_school['二林鎮']['074741'] = ['school' => '廣興國小', 'website' => 'gsps.chc.edu.tw'];
        $all_school['二林鎮']['074742'] = ['school' => '萬興國小', 'website' => 'wsps.chc.edu.tw'];
        $all_school['二林鎮']['074743'] = ['school' => '新生國小', 'website' => 'sssps.chc.edu.tw'];
        $all_school['二林鎮']['074744'] = ['school' => '中興國小', 'website' => 'scses.chc.edu.tw'];
        $all_school['二林鎮']['074537'] = ['school' => '原斗國中小', 'website' => 'ydps.chc.edu.tw'];
        $all_school['二林鎮']['074746'] = ['school' => '萬合國小', 'website' => 'whes.chc.edu.tw'];
        $all_school['大城鄉']['074747'] = ['school' => '大城國小', 'website' => 'dcps.chc.edu.tw'];
        $all_school['大城鄉']['074749'] = ['school' => '西港國小', 'website' => 'sges.chc.edu.tw'];
        $all_school['大城鄉']['074750'] = ['school' => '美豐國小', 'website' => 'mfes.chc.edu.tw'];
        $all_school['竹塘鄉']['074753'] = ['school' => '竹塘國小', 'website' => 'ctes.chc.edu.tw'];
        $all_school['竹塘鄉']['074754'] = ['school' => '田頭國小', 'website' => 'ttes.chc.edu.tw'];
        $all_school['竹塘鄉']['074756'] = ['school' => '長安國小', 'website' => 'caes.chc.edu.tw'];
        $all_school['竹塘鄉']['074757'] = ['school' => '土庫國小', 'website' => 'tkes.chc.edu.tw'];
        $all_school['芳苑鄉']['074758'] = ['school' => '芳苑國小', 'website' => 'fyes.chc.edu.tw'];
        $all_school['芳苑鄉']['074759'] = ['school' => '後寮國小', 'website' => 'hles.chc.edu.tw'];
        $all_school['芳苑鄉']['074760'] = ['school' => '民權國小', 'website' => 'mcws.chc.edu.tw'];
        $all_school['芳苑鄉']['074761'] = ['school' => '育華國小', 'website' => 'yhes.chc.edu.tw'];
        $all_school['芳苑鄉']['074762'] = ['school' => '草湖國小', 'website' => 'thes.chc.edu.tw'];
        $all_school['芳苑鄉']['074763'] = ['school' => '建新國小', 'website' => 'jses.chc.edu.tw'];
        $all_school['芳苑鄉']['074764'] = ['school' => '漢寶國小', 'website' => 'hbes.chc.edu.tw'];
        $all_school['芳苑鄉']['074765'] = ['school' => '王功國小', 'website' => 'wges.chc.edu.tw'];
        $all_school['芳苑鄉']['074766'] = ['school' => '新寶國小', 'website' => 'sbes.chc.edu.tw'];
        $all_school['芳苑鄉']['074767'] = ['school' => '路上國小', 'website' => 'lses.chc.edu.tw'];
        $all_school['和美鎮']['074769'] = ['school' => '和仁國小', 'website' => 'hres.chc.edu.tw'];
        $all_school['鹿港鎮']['074771'] = ['school' => '鹿東國小', 'website' => 'ldes.chc.edu.tw'];
        $all_school['社頭鄉']['074772'] = ['school' => '舊社國小', 'website' => 'csnes.chc.edu.tw'];
        $all_school['社頭鄉']['074773'] = ['school' => '崙雅國小', 'website' => 'lyps.chc.edu.tw'];
        $all_school['彰化市']['074775'] = ['school' => '大成國小', 'website' => 'dches.chc.edu.tw'];
        $all_school['田中鎮']['074776'] = ['school' => '新民國小', 'website' => 'smps.chc.edu.tw'];
        $all_school['溪湖鎮']['074777'] = ['school' => '湖北國小', 'website' => 'hbps.chc.edu.tw'];

        $townships = [];
        foreach($all_school as $k => $v) {
            $townships[] = $k;
        }

        $data = [
            'school3_1'=> $school3_1,
            'school3_2'=> $school3_2,
            'schools'=> $schools,
            'townships' => $townships,
            'all_school' => $all_school,
        ];        

        return view('chcschool.pages', $data);
    }

    public function chc_air(){    
        $data = [

        ];
        return view('chcschool.chc_air', $data);
    }

    private $dns_data;    
    private $dnsServer;
    private $zoneDomain;


    public function dns_admin()
    {                        
        $admin_list = $this->getAdminList();
        $dns_data   = $this->getDnsData();

        return view('chcschool.dns_admin', compact('admin_list', 'dns_data'));
    }

// 2. 新增管理員
    public function add_admin(Request $request)
    {
        if(session('dns_admin') != 1) return redirect()->route('index');

        $request->validate([
            'code' => 'required|string',
            'username' => 'required|string',
            'name' => 'required|string',
        ]);

        $code = trim($request->input('code'));
        $username = trim($request->input('username'));
        $name = trim($request->input('name'));

        $list = $this->getAdminList();

        // 檢查是否已存在相同資料
        foreach ($list as $item) {
            if ($item['code'] === $code && strtolower($item['username']) === strtolower($username)) {
                return back()->with('error', '該管理員已存在！');
            }
        }

        // 寫入新資料
        $list[] = ['code' => $code, 'username' => $username, 'name' => $name]; 
        $this->saveAdminList($list);

        return back()->with('success', '新增管理員成功！');
    }

    // 3. 刪除管理員
    public function delete_admin(Request $request)
    {
        if(session('dns_admin') != 1) return redirect()->route('index');
        
        $code = trim($request->input('code'));
        $username = trim($request->input('username'));

        $list = $this->getAdminList();

        // 陣列過濾：移除吻合的列
        $newList = array_filter($list, function ($item) use ($code, $username) {
            return !($item['code'] === $code && strtolower($item['username']) === strtolower($username));
        });

        $this->saveAdminList(array_values($newList));

        return back()->with('success', '刪除成功！');
    }    

// Helper: 讀取 CSV 轉成陣列
    private function getAdminList()
    {
        $list = [];

        if (Storage::exists($this->csvPath)) {
            $content = Storage::get($this->csvPath);
            $content = preg_replace('/[\x{EF}\x{BB}\x{BF}]/u', '', $content); // 去除 BOM
            $lines = explode("\n", str_replace("\r", "", $content));

            foreach ($lines as $line) {
                $line = trim($line);
                if ($line === '') continue;

                $data = str_getcsv($line);
                if (count($data) >= 2) {
                    $list[] = [
                        'code' => trim($data[0]),
                        'username' => trim($data[1]),
                        'name' => trim($data[2])
                    ];
                }
            }
        }

        return $list;
    }

    // Helper: 將陣列重寫回 CSV 檔
    private function saveAdminList(array $list)
    {
        $lines = [];
        foreach ($list as $item) {
            $lines[] = $item['code'] . ',' . $item['username']. ',' . $item['name'];
        }

        $content = implode("\n", $lines);
        Storage::put($this->csvPath, $content);
    }        

    public function forward(Request $request,$my_zoneDomain = null)
    {          
        $dns_data = $this->getDnsData();              
        $this->zoneDomain = $my_zoneDomain;                
        $records = [];
        $error = null;

        // 取得持久化快取中 3 小時內新增的紀錄清單
        $recentAdded = Cache::get('recent_dns_records', []);

        // 💡 1. 讀取 SQLite 備註資料並建立關聯索引陣列
        $notesMap = [];
        $dbPath = storage_path('app/privacy/dns_records.db');
        if (file_exists($dbPath)) {
            $db = new \SQLite3($dbPath);
            $stmt = $db->prepare("SELECT name, type, value, note FROM forward WHERE zone = :zone");
            $stmt->bindValue(':zone', $this->zoneDomain, SQLITE3_TEXT);
            $result = $stmt->execute();

            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                // 標準化 Key：字串轉小寫並去末端點，確保比對精準
                $keyName  = strtolower(rtrim($row['name'], '.'));
                $keyType  = strtoupper($row['type']);
                $keyValue = strtolower(rtrim($row['value'], '.'));

                $mapKey = "{$keyName}|{$keyType}|{$keyValue}";
                $notesMap[$mapKey] = $row['note'];
            }
            $db->close();
        }

        try {
            $resolver = new Net_DNS2_Resolver([
                'nameservers' => [$this->dnsServer],
                'timeout'     => 5,
            ]);

            $response = $resolver->query($this->zoneDomain, 'AXFR');

            foreach ($response->answer as $rr) {
                if ($rr->type !== 'SOA') {
                    $rawName = rtrim($rr->name, '.'); // 轉送回來的名稱 (如: chc.edu.tw)
                    
                    // 1. 名稱轉換：如果等於 zoneDomain，名稱統一轉成 '@'
                    if ($rawName === $this->zoneDomain) {
                        $displayName = '@';
                    } else {
                        $displayName = str_replace('.' . $this->zoneDomain, '', $rawName);
                    }

                    // 2. 針對 NS、CNAME、MX、A/AAAA 精確拿取原始紀錄值（保留真實點號）
                    $value = '';
                    switch ($rr->type) {
                        case 'A':
                        case 'AAAA':
                            $value = $rr->address ?? '';
                            break;
                        case 'NS':
                            $value = $rr->nsdname ?? ($rr->ns ?? '');
                            if (!empty($value)) {
                                $value = rtrim($value, '.') . '.';
                            }
                            break;
                        case 'CNAME':
                            $value = $rr->cname ?? '';
                            if (!empty($value)) {
                                $value = rtrim($value, '.') . '.';
                            }
                            break;
                        case 'MX':
                            $preference = $rr->preference ?? '';
                            $exchange   = $rr->exchange ?? '';
                            if (!empty($exchange)) {
                                $exchange = rtrim($exchange, '.') . '.';
                            }
                            $value = trim("{$preference} {$exchange}");
                            break;
                        case 'TXT':
                            if (isset($rr->text) && is_array($rr->text)) {
                                $value = implode(' ', $rr->text);
                            } else {
                                $value = $rr->rdata ?? '';
                            }
                            break;
                        default:
                            $value = $rr->rdata ?? ($rr->address ?? '');
                            break;
                    }                    

                    // 判斷該名稱是否在 3 小時內新增過
                    $createdAt = isset($recentAdded[$displayName]) ? Carbon::parse($recentAdded[$displayName]) : null;

                    // 💡 2. 進行 SQLite 備註比對
                    $cleanName  = strtolower(rtrim($displayName, '.'));
                    $cleanType  = strtoupper($rr->type);
                    $cleanValue = strtolower(rtrim($value, '.'));
                    $lookupKey  = "{$cleanName}|{$cleanType}|{$cleanValue}";

                    $note = $notesMap[$lookupKey] ?? null;

                    $records[] = [
                        'name'       => $displayName,
                        'type'       => $rr->type,
                        'ttl'        => $rr->ttl,
                        'value'      => trim($value),
                        'note'       => $note, // 💡 帶入備註內容
                        'created_at' => $createdAt,
                    ];
                }
            }

        } catch (Net_DNS2_Exception $e) {
            $error = '無法抓取 Zone 記錄 (請確認 163.23.200.6 是否有開啟 allow-transfer): ' . $e->getMessage();
        }        
        $schools = config('chcschool.schools', []);        

        return view('chcschool.dns_admin_forward', [
            'dns_data'   => $dns_data,
            'dnsServer'  => $this->dnsServer,
            'zoneDomain' => $this->zoneDomain,
            'records'    => $records,
            'error'      => $error,
            'schools'    => $schools,
            'schoolName' => $this->getSchoolNameByZone($this->zoneDomain),
        ]);
    }

    /**
     * 新增正解記錄
     */
    public function store(Request $request)
    {        
        $request->validate([
            'name'       => 'required|string',
            'type'       => 'required|string|in:A,AAAA,CNAME,TXT,MX,SRV,CAA,NS',
            'ttl_option' => 'required|string',
            'value'      => 'required|string',
            'note'       => 'nullable|string|max:255', // 可為空值，最多 255 字元
        ]);

        $this->zoneDomain = $request->input('zoneDomain');

        $name      = trim($request->input('name'));
        $type      = strtoupper($request->input('type'));
        $ttlOption = $request->input('ttl_option');
        $value     = trim($request->input('value'));
        $note = $request->input('note'); // 取得備註內容 (未填則為 null)

        $ttlSeconds = $this->convertTtlToSeconds($ttlOption);

        // 左側域名處理 (FQDN)
        if ($name === '@' || $name === $this->zoneDomain) {
            $fqdn = $this->zoneDomain . '.';
            $saveKey = '@';
        } else {
            $cleanInput = str_replace('.' . $this->zoneDomain, '', $name);
            $fqdn = $cleanInput . '.' . $this->zoneDomain . '.';
            $saveKey = $cleanInput;
        }

        // 組裝 RR 字串 (保留使用者輸入的原始 value，不強加或裁切點)
        if ($type === 'MX') {
            if (!preg_match('/^\d+\s+/', $value)) {
                $value = "20 {$value}";
            }
            $rrString = "{$fqdn} {$ttlSeconds} IN MX {$value}";
        } else {
            $rrString = "{$fqdn} {$ttlSeconds} IN {$type} {$value}";
        }

        try {
            $updater = new Net_DNS2_Updater($this->zoneDomain, [
                'nameservers' => [$this->dnsServer],
                'timeout'     => 5,
            ]);

            $rr = Net_DNS2_RR::fromString($rrString);
            $updater->add($rr);
            $updater->update();

            // 寫入持久化 Cache (僅保留 3 小時內的紀錄)
            $recentAdded = Cache::get('recent_dns_records', []);

            // 💡 1. 清理超過 3 小時 (10800 秒) 的舊資料
            $now = now();
            foreach ($recentAdded as $key => $timeStr) {
                if ($now->diffInSeconds(\Carbon\Carbon::parse($timeStr)) > 10800) {
                    unset($recentAdded[$key]);
                }
            }

            // 💡 2. 寫入本次新新增的紀錄
            $recentAdded[$saveKey] = $now->toDateTimeString();

            // 💡 3. 重新寫回 Cache (保存 3 小時)
            Cache::put('recent_dns_records', $recentAdded, 10800);     
            
            //寫入資料庫
            if(!empty($note)){
                // 建立/連接 SQLite 資料庫檔
                $dbPath = storage_path('app/privacy/dns_records.db');
                $this->db = new \SQLite3($dbPath);
                $stmt = $this->db->prepare("INSERT INTO forward (name, type, value, zone, note) VALUES (:name, :type, :value, :zone, :note)");
                $stmt->bindValue(':name', $saveKey, SQLITE3_TEXT);
                $stmt->bindValue(':type', $type, SQLITE3_TEXT);
                $stmt->bindValue(':value', $value, SQLITE3_TEXT);
                $stmt->bindValue(':zone', $this->zoneDomain, SQLITE3_TEXT);
                $stmt->bindValue(':note', $note, SQLITE3_TEXT);
                $stmt->execute();
            }

            return redirect()->back()->with('success', "成功新增紀錄：{$saveKey} ({$type})");

        } catch (Net_DNS2_Exception $e) {
            return redirect()->back()->with('error', "新增失敗: " . $e->getMessage());
        }
    }

    /**
     * 刪除指定的正解記錄
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'name'  => 'required|string',
            'type'  => 'required|string',
            'value' => 'nullable|string',
        ]);

        $this->zoneDomain = $request->input('zoneDomain');

        $name  = trim($request->input('name'));
        $type  = strtoupper($request->input('type'));
        $value = trim($request->input('value'));

        // 💡 關鍵安全防護：禁止刪除根網域 (@) 的 NS 紀錄，避免 Zone 崩潰
        if (($name === '@' || $name === $this->zoneDomain) && $type === 'NS') {
            return redirect()->back()->with('error', '刪除失敗：系統保護中，禁止刪除根網域 (@) 的 NS 名稱伺服器紀錄！');
        }

        try {
            $updater = new Net_DNS2_Updater($this->zoneDomain, [
                'nameservers' => [$this->dnsServer],
                'timeout'     => 5,
            ]);

            if ($name === '@' || $name === $this->zoneDomain) {
                $fullName = $this->zoneDomain . '.';
            } else {
                $cleanInput = str_replace('.' . $this->zoneDomain, '', $name);
                $fullName = $cleanInput . '.' . $this->zoneDomain . '.';
            }

            $rrString = "{$fullName} 86400 IN {$type}";
            if (!empty($value)) {
                $rrString .= " {$value}";
            }

            $rr = Net_DNS2_RR::fromString($rrString);
            $updater->delete($rr);
            $updater->update();

            // 💡 刪除 BIND9 紀錄成功後，同步刪除 SQLite forward 資料表中的備註
            $dbPath = storage_path('app/privacy/dns_records.db');
            if (file_exists($dbPath)) {
                $db = new \SQLite3($dbPath);

                // 準備查詢條件：與寫入時的格式保持一致
                // 註：若你的 forward 資料表 name 欄位存的是 displayName (如 '@' 或 'www')，則直接傳入 $name
                $stmt = $db->prepare("DELETE FROM forward WHERE zone = :zone AND name = :name AND type = :type");
                $stmt->bindValue(':zone', $this->zoneDomain, SQLITE3_TEXT);
                $stmt->bindValue(':name', $name, SQLITE3_TEXT);
                $stmt->bindValue(':type', $type, SQLITE3_TEXT);
                
                $stmt->execute();
                $db->close();
            }

            // 💡 使用 back() 可自動彈回上一頁並帶入成功訊息
            return redirect()->back()->with('success', "成功刪除紀錄：{$name} ({$type})");

        } catch (Net_DNS2_Exception $e) {
            return redirect()->back()->with('error', "刪除失敗: " . $e->getMessage());
        }
    }

    /**
     * 測試 DNS 紀錄解析
     */
    public function check(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'type' => 'required|string',
        ]);

        $name = trim($request->input('name'));
        $type = strtoupper($request->input('type'));
        $zoneDomain = $request->input('zoneDomain');

        // 💡 判斷是否為 PTR 反解查詢 (包含 PTR 與前端帶來的 PTR6)
        if ($type === 'PTR' || $type === 'PTR6') {
            $type = 'PTR'; // Net_DNS2 的標準查詢類型名稱皆為 PTR

            // 1. 傳入標準 IPv4 (例: 163.23.200.10)
            if (filter_var($name, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                $ipParts = explode('.', $name);
                $fqdn = sprintf('%s.%s.%s.%s.in-addr.arpa.', $ipParts[3], $ipParts[2], $ipParts[1], $ipParts[0]);
            } 
            // 2. 傳入標準 IPv6 (例: 2001:288:5637::1) -> 補滿 32 位元並倒轉為 ip6.arpa
            elseif (filter_var($name, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                $bin = inet_pton($name);
                $hex = unpack('H*', $bin)[1];
                $fqdn = implode('.', array_reverse(str_split($hex))) . '.ip6.arpa.';
            } 
            // 3. 傳入的已經是 in-addr.arpa 或 ip6.arpa 完整 FQDN 格式
            else {
                $fqdn = rtrim($name, '.') . '.';
            }
        } else {
            // 正解邏輯：組裝 FQDN
            $this->zoneDomain = $zoneDomain;
            if ($name === '@' || $name === $this->zoneDomain) {
                $fqdn = $this->zoneDomain;
            } else {
                $cleanInput = str_replace('.' . $this->zoneDomain, '', $name);
                $fqdn = $cleanInput . '.' . $this->zoneDomain;
            }
        }

        try {
            $resolver = new Net_DNS2_Resolver([
                'nameservers' => [$this->dnsServer],
                'timeout'     => 3,
            ]);

            $response = $resolver->query($fqdn, $type);

            if (!empty($response->answer)) {
                $answers = [];
                foreach ($response->answer as $rr) {
                    // 💡 PTR 紀錄精準讀取 ptrdname 欄位
                    if ($type === 'PTR') {
                        $val = $rr->ptrdname ?? ($rr->rdata ?? '');
                    } elseif ($type === 'TXT' && !empty($rr->text)) {
                        $val = is_array($rr->text) ? implode('', $rr->text) : $rr->text;
                    } else {
                        $val = $rr->address ?? $rr->cname ?? $rr->nsdname ?? ($rr->rdata ?? '');
                    }

                    if (!empty($val)) {
                        $cleanVal = mb_convert_encoding($val, 'UTF-8', 'UTF-8');
                        $answers[] = in_array($type, ['CNAME', 'NS', 'MX', 'SRV', 'PTR']) ? (rtrim($cleanVal, '.') . '.') : $cleanVal;
                    }
                }

                $message = "DNS 伺服器 ({$this->dnsServer}) 已順利解析到記錄：\n" . implode("\n", $answers);

                return response()->json([
                    'success' => true,
                    'title'   => '解析成功！',
                    'message' => $message,
                ], 200, [], JSON_INVALID_UTF8_SUBSTITUTE);
            } else {
                return response()->json([
                    'success' => false,
                    'title'   => '解析失敗',
                    'message' => "DNS 伺服器回應了查詢，但找不到 {$fqdn} 的 PTR 紀錄。",
                ]);
            }

        } catch (Net_DNS2_Exception $e) {
            return response()->json([
                'success' => false,
                'title'   => '連線失敗或逾時',
                'message' => "無法從 DNS 伺服器取得回應：\n" . mb_convert_encoding($e->getMessage(), 'UTF-8', 'UTF-8'),
            ], 200, [], JSON_INVALID_UTF8_SUBSTITUTE);
        }
    }

    private function convertTtlToSeconds(string $option): int
    {
        $ttls = [
            '1s'  => 1,
            '1h'  => 3600,
            '3h'  => 10800,
            '6h'  => 21600,
            '12h' => 43200,
            '1d'  => 86400,
            '1w'  => 604800,
            '1m'  => 2592000,
        ];

        return $ttls[$option] ?? 86400;
    }    

    public function ptr(Request $request, $networkSubnet = null)
    {
        // 1. 若網址未帶入 $networkSubnet 參數，自動抓取目前登入學校的第一組 ipv4_ptr 網段
        if (empty($networkSubnet)) {
            $dns_data = $this->getDnsData(); // 呼叫先前讀取 CSV 的私有方法
            $networkSubnet = $dns_data['ipv4_ptr'][0];
        }

        $records = [];
        $error = null;

        // 2. 判斷傳入的 $networkSubnet 是完整 PTR Zone 名稱還是標準 3 碼 IP 網段
        if (str_contains($networkSubnet, 'in-addr.arpa')) {
            // 情況 A：傳入完整 Zone，例如 "64-26.93.23.163.in-addr.arpa"
            $ptrZoneDomain = rtrim($networkSubnet, '.');

            // 從 Zone 名稱逆向推算基本 IP 前三碼 (例如 "64-26.93.23.163.in-addr.arpa" -> "163.23.93")
            $zoneParts = explode('.', $ptrZoneDomain); // ['64-26', '93', '23', '163', 'in-addr', 'arpa']
            $baseSubnet = sprintf('%s.%s.%s', $zoneParts[3] ?? '', $zoneParts[2] ?? '', $zoneParts[1] ?? '');
        } else {
            // 情況 B：傳入標準三碼網段，例如 "163.23.200"
            $ipParts = explode('.', $networkSubnet);
            if (count($ipParts) !== 3) {
                return redirect()->back()->with('error', 'PTR 網段格式錯誤，需為三組數字或完整 PTR Zone 名稱');
            }
            $ptrZoneDomain = sprintf('%s.%s.%s.in-addr.arpa', $ipParts[2], $ipParts[1], $ipParts[0]);
            $baseSubnet = $networkSubnet;
        }

        // 3. 取得持久化快取中 3 小時內新增的紀錄清單
        $recentAdded = Cache::get('recent_ptr_records', []);

        // 💡 4. 讀取 SQLite 的 ptr 資料表備註資料並建立關聯索引陣列
        $notesMap = [];
        $dbPath = storage_path('app/privacy/dns_records.db');
        if (file_exists($dbPath)) {
            $db = new \SQLite3($dbPath);
            $stmt = $db->prepare("SELECT ip, name, note FROM ptr WHERE zone = :zone");
            $stmt->bindValue(':zone', $ptrZoneDomain, SQLITE3_TEXT);
            $result = $stmt->execute();

            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                // 標準化 Key：IP 去空白，域名轉小寫並去除末端的點
                $keyIp   = trim($row['ip']);
                $keyName = strtolower(rtrim($row['name'], '.'));

                $mapKey = "{$keyIp}|{$keyName}";
                $notesMap[$mapKey] = $row['note'];
            }
            $db->close();
        }

        try {
            $resolver = new Net_DNS2_Resolver([
                'nameservers' => [$this->dnsServer],
                'timeout'     => 5,
            ]);

            // 發送 AXFR 抓取 PTR Zone 內的所有紀錄
            $response = $resolver->query($ptrZoneDomain, 'AXFR');

            foreach ($response->answer as $rr) {
                // 過濾 SOA 與 NS 紀錄，只保留真正的 PTR 紀錄
                if ($rr->type === 'PTR') {
                    $rawName = rtrim($rr->name, '.'); // 例如: 100.64-26.93.23.163.in-addr.arpa 或 2.200.23.163.in-addr.arpa
                    
                    // 抓出 IP 最後一碼主機號 (例如: "100" 或 "2")
                    $lastOctet = str_replace('.' . $ptrZoneDomain, '', $rawName);
                    
                    // 組裝成完整的 IPv4 位址 (例如: "163.23.93.100")
                    $fullIp = "{$baseSubnet}.{$lastOctet}";

                    // 處理 PTR 指向的主機域名 (ptrdname)
                    $ptrdname = $rr->ptrdname ?? ($rr->rdata ?? '');
                    if (!empty($ptrdname)) {
                        // 安全轉碼，避免非 UTF-8 字元引起 JSON/頁面渲染錯誤
                        $ptrdname = mb_convert_encoding($ptrdname, 'UTF-8', 'UTF-8');
                        $ptrdname = rtrim($ptrdname, '.') . '.';
                    }

                    // 判斷是否為 3 小時內新增
                    $createdAt = isset($recentAdded[$fullIp]) ? Carbon::parse($recentAdded[$fullIp]) : null;

                    // 💡 5. 進行 SQLite 備註比對
                    $cleanIp   = trim($fullIp);
                    $cleanName = strtolower(rtrim($ptrdname, '.'));
                    $lookupKey = "{$cleanIp}|{$cleanName}";

                    $note = $notesMap[$lookupKey] ?? null;

                    $records[] = [
                        'ip_last'    => $lastOctet,   // 最後一碼 IP (如 100)
                        'ip_full'    => $fullIp,      // 完整 IP (如 163.23.93.100)
                        'type'       => $rr->type,    // PTR
                        'ttl'        => $rr->ttl,     // TTL 秒數
                        'domain'     => $ptrdname,    // 指向的完整域名 (如 pc1.chc.edu.tw.)
                        'note'       => $note,        // 💡 帶入備註內容
                        'created_at' => $createdAt,
                    ];
                }
            }

            // 依 IP 最後一碼數字由小到大排序 (例如 .1, .2, .100)
            usort($records, function ($a, $b) {
                return (int)$a['ip_last'] <=> (int)$b['ip_last'];
            });

        } catch (Net_DNS2_Exception $e) {
            $error = "無法抓取 PTR 反解記錄 ({$ptrZoneDomain}): " . mb_convert_encoding($e->getMessage(), 'UTF-8', 'UTF-8');
        }

        $schools = config('chcschool.schools', []);

        return view('chcschool.dns_admin_ptr', [
            'dnsServer'     => $this->dnsServer,
            'networkSubnet' => $networkSubnet,
            'ptrZoneDomain' => $ptrZoneDomain,
            'records'       => $records,
            'error'         => $error,
            'schools'       => $schools,
            'dns_data'      => $this->getDnsData(), // 傳入 view 供上方按鈕列繪製切換選單
            'schoolName' => $this->getSchoolNameByZone($ptrZoneDomain),
        ]);
    }    

    public function ptr_store(Request $request)
    {
        $request->validate([
            'network_subnet' => 'required|string',
            'ip_last'        => 'required|integer|between:1,254',
            'ttl_option'     => 'required|string',
            'domain'         => 'required|string',
            'note'       => 'nullable|string|max:255', // 可為空值，最多 255 字元
        ]);

        $networkSubnet = trim($request->input('network_subnet'));
        $ipLast        = (int)$request->input('ip_last');
        $ttlOption     = $request->input('ttl_option');
        $domain        = trim($request->input('domain'));
        $note = $request->input('note'); // 取得備註內容 (未填則為 null)

        $ttlSeconds = $this->convertTtlToSeconds($ttlOption);

        // 1. 判斷傳入的是 PTR Zone 名稱還是標準 3 碼 IP 網段
        if (str_contains($networkSubnet, 'in-addr.arpa')) {
            // 情況 A：例如 "64-26.93.23.163.in-addr.arpa"
            $ptrZoneDomain = rtrim($networkSubnet, '.');

            // PTR 左側 FQDN (例: "100.64-26.93.23.163.in-addr.arpa.")
            $ptrFqdn = "{$ipLast}.{$ptrZoneDomain}.";

            // 計算完整 IP (供頁面高亮辨識)
            $zoneParts = explode('.', $ptrZoneDomain);
            $baseSubnet = sprintf('%s.%s.%s', $zoneParts[3] ?? '', $zoneParts[2] ?? '', $zoneParts[1] ?? '');
            $fullIp = "{$baseSubnet}.{$ipLast}";
        } else {
            // 情況 B：例如 "163.23.200"
            $ipParts = explode('.', $networkSubnet);
            if (count($ipParts) !== 3) {
                return redirect()->back()->with('error', 'PTR 網段格式錯誤');
            }
            $ptrZoneDomain = sprintf('%s.%s.%s.in-addr.arpa', $ipParts[2], $ipParts[1], $ipParts[0]);

            // PTR 左側 FQDN (例: "10.200.23.163.in-addr.arpa.")
            $ptrFqdn = "{$ipLast}.{$ptrZoneDomain}.";
            $fullIp = "{$networkSubnet}.{$ipLast}";
        }

        // 2. 指向域名處理 (末端補點)
        $targetDomain = rtrim($domain, '.') . '.';

        // 3. 組裝 PTR RR 字串 (例: "10.200.23.163.in-addr.arpa. 86400 IN PTR pc1.chc.edu.tw.")
        $rrString = "{$ptrFqdn} {$ttlSeconds} IN PTR {$targetDomain}";

        try {
            // 💡 1. 類別名稱加上全域反斜線 \
            $updater = new \Net_DNS2_Updater($ptrZoneDomain, [
                'nameservers' => [$this->dnsServer],
                'timeout'     => 5,
            ]);

            $rr = \Net_DNS2_RR::fromString($rrString);
            $updater->add($rr);
            $updater->update();

            // 💡 2. 讀取 PTR 快取並進行超過 3 小時 (10800 秒) 的舊資料清理
            $recentAdded = Cache::get('recent_ptr_records', []);
            $now = now();

            foreach ($recentAdded as $key => $timeStr) {
                if ($now->diffInSeconds(\Carbon\Carbon::parse($timeStr)) > 10800) {
                    unset($recentAdded[$key]);
                }
            }

            // 寫入本次新增的 PTR 紀錄 (鍵名為完整 IP)
            $recentAdded[$fullIp] = $now->toDateTimeString();
            Cache::put('recent_ptr_records', $recentAdded, 10800);

            // 寫入 SQLite 資料庫
            if (!empty($note)) {
                $dbPath = storage_path('app/privacy/dns_records.db');
                $db = new \SQLite3($dbPath);

                // 使用 REPLACE INTO 或 INSERT INTO
                // 此處使用 fullIp 作為 ip，domain/targetDomain 作為 name
                $stmt = $db->prepare("INSERT INTO ptr (ip, name, zone, note) VALUES (:ip, :name, :zone, :note)");
                $stmt->bindValue(':ip', $fullIp, SQLITE3_TEXT);             // 例如: "163.23.200.10"
                $stmt->bindValue(':name', $targetDomain, SQLITE3_TEXT);     // 例如: "pc1.chc.edu.tw."
                $stmt->bindValue(':zone', $ptrZoneDomain, SQLITE3_TEXT);   // 例如: "200.23.163.in-addr.arpa"
                $stmt->bindValue(':note', $note, SQLITE3_TEXT);
                $stmt->execute();
                $db->close();
            }

            return redirect()->back()->with('success', "成功新增 PTR 反解紀錄：{$fullIp} -> {$targetDomain}");

        } catch (\Net_DNS2_Exception $e) {
            // 💡 3. Exception 加上反斜線，並解析標準錯誤碼
            $rcodeName = \Net_DNS2_Lookups::$rcode_name[$e->getCode()] ?? '';
            $extraMsg = $rcodeName ? " (錯誤碼: {$rcodeName})" : '';

            return redirect()->back()->with('error', "新增 PTR 失敗: " . $e->getMessage() . $extraMsg);
        }
    }

    public function ptr_destroy(Request $request)
    {        
        $request->validate([
            'network_subnet' => 'required|string',
            'ip_last'        => 'required|integer|between:1,254',
            'domain'         => 'nullable|string',
        ]);

        $networkSubnet = trim($request->input('network_subnet'));
        $ipLast        = (int)$request->input('ip_last');
        $domain        = trim($request->input('domain'));

        // 1. 判斷傳入的是 PTR Zone 名稱還是標準 3 碼 IP 網段
        if (str_contains($networkSubnet, 'in-addr.arpa')) {
            // 情況 A：例如 "64-26.93.23.163.in-addr.arpa"
            $ptrZoneDomain = rtrim($networkSubnet, '.');

            // PTR 左側 FQDN (例: "100.64-26.93.23.163.in-addr.arpa.")
            $ptrFqdn = "{$ipLast}.{$ptrZoneDomain}.";

            // 計算完整 IP (供訊息顯示與資料庫比對)
            $zoneParts = explode('.', $ptrZoneDomain);
            $baseSubnet = sprintf('%s.%s.%s', $zoneParts[3] ?? '', $zoneParts[2] ?? '', $zoneParts[1] ?? '');
            $fullIp = "{$baseSubnet}.{$ipLast}";
        } else {
            // 情況 B：例如 "163.23.200"
            $ipParts = explode('.', $networkSubnet);
            if (count($ipParts) !== 3) {
                return redirect()->back()->with('error', 'PTR 網段格式錯誤');
            }
            $ptrZoneDomain = sprintf('%s.%s.%s.in-addr.arpa', $ipParts[2], $ipParts[1], $ipParts[0]);

            // PTR 左側 FQDN (例: "10.200.23.163.in-addr.arpa.")
            $ptrFqdn = "{$ipLast}.{$ptrZoneDomain}.";
            $fullIp = "{$networkSubnet}.{$ipLast}";
        }

        try {
            $updater = new Net_DNS2_Updater($ptrZoneDomain, [
                'nameservers' => [$this->dnsServer],
                'timeout'     => 5,
            ]);

            // 組裝用來刪除的 RR 字串
            $rrString = "{$ptrFqdn} 86400 IN PTR";
            if (!empty($domain)) {
                $targetDomain = rtrim($domain, '.') . '.';
                $rrString .= " {$targetDomain}";
            }

            $rr = Net_DNS2_RR::fromString($rrString);
            $updater->delete($rr);
            $updater->update();

            // 刪除成功後，同步清除可能存在的 3 小時高亮 Cache
            $recentAdded = Cache::get('recent_ptr_records', []);
            if (isset($recentAdded[$fullIp])) {
                unset($recentAdded[$fullIp]);
                Cache::put('recent_ptr_records', $recentAdded, 10800);
            }

            // 💡 2. 同步刪除 SQLite ptr 資料表中的備註紀錄
            $dbPath = storage_path('app/privacy/dns_records.db');
            if (file_exists($dbPath)) {
                $db = new \SQLite3($dbPath);
                
                $stmt = $db->prepare("DELETE FROM ptr WHERE zone = :zone AND ip = :ip");
                $stmt->bindValue(':zone', $ptrZoneDomain, SQLITE3_TEXT);
                $stmt->bindValue(':ip', $fullIp, SQLITE3_TEXT);
                
                $stmt->execute();
                $db->close();
            }

            return redirect()->back()->with('success', "成功刪除 PTR 反解紀錄：{$fullIp}");

        } catch (Net_DNS2_Exception $e) {
            return redirect()->back()->with('error', "刪除 PTR 失敗: " . mb_convert_encoding($e->getMessage(), 'UTF-8', 'UTF-8'));
        }
    }  

    // ==================== IPv6 PTR 列表頁面 ====================
    public function ptr6(Request $request, $networkSubnet = null)
    {
        if (empty($networkSubnet)) {
            $dnsData = $this->getDnsData();
            $networkSubnet = $dnsData['ipv6_ptr'][0] ?? null;
        }

        $records = [];
        $error = null;
        
        // 💡 修正點：統一強制轉為小寫，確保與 SQLite、Cache 以及 DNS 比對格式一致
        $ptrZoneDomain = $networkSubnet ? strtolower(rtrim($networkSubnet, '.')) : '';

        if (!empty($ptrZoneDomain)) {
            $recentAdded = Cache::get('recent_ptr6_records', []);

            // 💡 1. 讀取 SQLite ptr6 資料表備註資料並建立關聯索引陣列
            $notesMap = [];
            $dbPath = storage_path('app/privacy/dns_records.db');
            if (file_exists($dbPath)) {
                $db = new \SQLite3($dbPath);
                // $ptrZoneDomain 已經是小寫，能精準 matches SQLite 中的小寫 zone 欄位
                $stmt = $db->prepare("SELECT ip, name, note FROM ptr6 WHERE LOWER(zone) = :zone");
                $stmt->bindValue(':zone', $ptrZoneDomain, SQLITE3_TEXT);
                $result = $stmt->execute();

                while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                    // 標準化 Key：IP (raw_fqdn) 與域名皆轉小寫並去除尾端的點
                    $keyIp   = strtolower(rtrim($row['ip'], '.'));
                    $keyName = strtolower(rtrim($row['name'], '.'));

                    $mapKey = "{$keyIp}|{$keyName}";
                    $notesMap[$mapKey] = $row['note'];
                }
                $db->close();
            }

            try {
                $resolver = new Net_DNS2_Resolver([
                    'nameservers' => [$this->dnsServer],
                    'timeout'     => 5,
                ]);

                $response = $resolver->query($ptrZoneDomain, 'AXFR');

                foreach ($response->answer as $rr) {
                    if ($rr->type === 'PTR') {
                        // 轉小寫以利比對
                        $rawName = strtolower(rtrim($rr->name, '.')); // 例如: 1.0.0.0...ip6.arpa
                        $hostPart = str_replace('.' . $ptrZoneDomain, '', $rawName);

                        $ptrdname = $rr->ptrdname ?? ($rr->rdata ?? '');
                        if (!empty($ptrdname)) {
                            $ptrdname = mb_convert_encoding($ptrdname, 'UTF-8', 'UTF-8');
                            $ptrdname = rtrim($ptrdname, '.') . '.';
                        }

                        $createdAt = isset($recentAdded[$rawName]) ? Carbon::parse($recentAdded[$rawName]) : null;

                        // 💡 2. 進行 SQLite 備註比對
                        $cleanIp   = strtolower(rtrim($rawName, '.'));
                        $cleanName = strtolower(rtrim($ptrdname, '.'));
                        $lookupKey = "{$cleanIp}|{$cleanName}";

                        $note = $notesMap[$lookupKey] ?? null;

                        $records[] = [
                            'host_part'  => $hostPart,
                            'raw_fqdn'   => $rawName,
                            'type'       => 'PTR',
                            'ttl'        => $rr->ttl,
                            'domain'     => $ptrdname,
                            'note'       => $note, // 帶入備註內容
                            'created_at' => $createdAt,
                        ];
                    }
                }
            } catch (Net_DNS2_Exception $e) {
                $error = "無法抓取 IPv6 PTR 反解記錄 ({$ptrZoneDomain}): " . mb_convert_encoding($e->getMessage(), 'UTF-8', 'UTF-8');
            }
        }

        return view('chcschool.dns_admin_ptr6', [
            'dnsServer'     => $this->dnsServer,
            'networkSubnet' => $networkSubnet,
            'ptrZoneDomain' => $ptrZoneDomain,
            'records'       => $records,
            'error'         => $error,
            'schools'       => config('chcschool.schools', []),
            'dns_data'      => $this->getDnsData(),
            'schoolName'    => $this->getSchoolNameByZone($ptrZoneDomain),
        ]);
    }

    // ==================== IPv6 PTR 新增邏輯 ====================
    public function ptr6_store(Request $request)
    {
        $request->validate([
            'network_subnet' => 'required|string',
            'host_part'      => 'required|string',
            'ttl_option'     => 'required|string',
            'domain'         => 'required|string',
            'note'           => 'nullable|string|max:255',
        ]);

        $networkSubnet = trim($request->input('network_subnet'));
        $hostPart      = trim($request->input('host_part'));
        $ttlOption     = $request->input('ttl_option');
        $domain        = trim($request->input('domain'));
        $note          = $request->input('note');

        // 💡 修改點 1：將 ptrZoneDomain 統一轉為「小寫」，確保比對與資料庫寫入一致
        $ptrZoneDomain = strtolower(rtrim($networkSubnet, '.'));
        $ttlSeconds    = $this->convertTtlToSeconds($ttlOption);
        $targetDomain  = rtrim($domain, '.') . '.';

        // 處理 host_part
        if (filter_var($hostPart, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            // 轉出小寫 Nibbles 反解名稱
            $ptrFqdn = strtolower($this->ipv6ToPtrFqdn($hostPart));
            
            // 💡 修改點 2：兩邊統一轉小寫再比對 (避免 A 與 a 判定不同的問題)
            $cleanPtrFqdn = strtolower(rtrim($ptrFqdn, '.'));
            
            if (!str_ends_with($cleanPtrFqdn, $ptrZoneDomain)) {
                return redirect()->back()->with('error', "新增失敗：輸入的 IPv6 位址與目前的 Zone 網段 ({$ptrZoneDomain}) 不符！");
            }
        } else {
            $cleanHost = strtolower(rtrim($hostPart, '.'));
            $ptrFqdn   = "{$cleanHost}.{$ptrZoneDomain}.";
        }

        $rrString = "{$ptrFqdn} {$ttlSeconds} IN PTR {$targetDomain}";

        try {
            $updater = new \Net_DNS2_Updater($ptrZoneDomain, [
                'nameservers' => [$this->dnsServer],
                'timeout'     => 5,
            ]);

            $rr = \Net_DNS2_RR::fromString($rrString);
            $updater->add($rr);
            $updater->update();

            // 快取的讀取與更新
            $rawName = strtolower(rtrim($ptrFqdn, '.'));
            $recentAdded = Cache::get('recent_ptr6_records', []);
            $now = now();

            // 清理超過 3 小時 (10800 秒) 的舊紀錄
            foreach ($recentAdded as $key => $timeStr) {
                if ($now->diffInSeconds(\Carbon\Carbon::parse($timeStr)) > 10800) {
                    unset($recentAdded[$key]);
                }
            }

            // 寫入本次新增的紀錄並存入 Cache
            $recentAdded[$rawName] = $now->toDateTimeString();
            Cache::put('recent_ptr6_records', $recentAdded, 10800);
            
            // 寫入 SQLite 資料庫
            if (!empty($note)) {
                $dbPath = storage_path('app/privacy/dns_records.db');
                $db = new \SQLite3($dbPath);

                $stmt = $db->prepare("INSERT INTO ptr6 (ip, name, zone, note) VALUES (:ip, :name, :zone, :note)");
                $stmt->bindValue(':ip', $rawName, SQLITE3_TEXT);           // 完整反解名稱
                $stmt->bindValue(':name', $targetDomain, SQLITE3_TEXT);   // 目標域名
                $stmt->bindValue(':zone', $ptrZoneDomain, SQLITE3_TEXT); // Zone 網段 (小寫)
                $stmt->bindValue(':note', $note, SQLITE3_TEXT);
                $stmt->execute();
                $db->close();
            }      

            return redirect()->back()->with('success', "成功新增 IPv6 PTR 紀錄：{$targetDomain}");

        } catch (\Net_DNS2_Exception $e) {
            $rcodeName = \Net_DNS2_Lookups::$rcode_name[$e->getCode()] ?? '';
            $extraMsg = $rcodeName ? " (錯誤碼: {$rcodeName})" : '';

            return redirect()->back()->with('error', "新增失敗: " . $e->getMessage() . $extraMsg);
        }        
    }

    // ==================== IPv6 PTR 刪除邏輯 ====================
    public function ptr6_destroy(Request $request)
    {
        $request->validate([
            'network_subnet' => 'required|string',
            'raw_fqdn'       => 'required|string',
            'domain'         => 'nullable|string',
        ]);

        $networkSubnet = trim($request->input('network_subnet'));
        $rawFqdn       = trim($request->input('raw_fqdn'));
        $domain        = trim($request->input('domain'));

        $ptrZoneDomain = rtrim($networkSubnet, '.');
        $ptrFqdn       = rtrim($rawFqdn, '.') . '.';

        try {
            $updater = new Net_DNS2_Updater($ptrZoneDomain, [
                'nameservers' => [$this->dnsServer],
                'timeout'     => 5,
            ]);

            $rrString = "{$ptrFqdn} 86400 IN PTR";
            if (!empty($domain)) {
                $rrString .= " " . rtrim($domain, '.') . '.';
            }

            $rr = Net_DNS2_RR::fromString($rrString);
            $updater->delete($rr);
            $updater->update();

            $recentAdded = Cache::get('recent_ptr6_records', []);
            if (isset($recentAdded[$rawFqdn])) {
                unset($recentAdded[$rawFqdn]);
                Cache::put('recent_ptr6_records', $recentAdded, 10800);
            }

            // 💡 同步刪除 SQLite ptr6 資料表中的備註紀錄
            $dbPath = storage_path('app/privacy/dns_records.db');
            if (file_exists($dbPath)) {
                $db = new \SQLite3($dbPath);

                $stmt = $db->prepare("DELETE FROM ptr6 WHERE zone = :zone AND ip = :ip");
                $stmt->bindValue(':zone', $ptrZoneDomain, SQLITE3_TEXT);
                $stmt->bindValue(':ip', rtrim($rawFqdn, '.'), SQLITE3_TEXT);

                $stmt->execute();
                $db->close();
            }

            return redirect()->back()->with('success', "成功刪除 IPv6 PTR 反解紀錄！");

        } catch (Net_DNS2_Exception $e) {
            return redirect()->back()->with('error', "刪除失敗: " . mb_convert_encoding($e->getMessage(), 'UTF-8', 'UTF-8'));
        }
    }

// ==================== IPv6 轉全展開 PTR FQDN Helper ====================
    private function ipv6ToPtrFqdn($ipv6)
    {
        $bin = inet_pton($ipv6);
        if ($bin === false) return $ipv6;

        $hex = unpack('H*', $bin)[1];
        return implode('.', array_reverse(str_split($hex))) . '.ip6.arpa.';
    }    


    private function getDnsData()
    {        
        $schools = [];
        $csvPath = 'privacy/dns_data.csv';

        if (Storage::exists($csvPath)) {
            $stream = Storage::readStream($csvPath);

            while (($data = fgetcsv($stream)) !== false) {
                if (count($data) >= 4) {
                    $code  = trim($data[0]);
                    $name  = trim($data[1]);
                    $type  = trim($data[2]);
                    $value = trim($data[3]);

                    // 若該學校代碼尚未建立，初始化學校結構
                    if (!isset($schools[$code])) {
                        $schools[$code] = [
                            'code'     => $code,
                            'name'     => $name,
                            'ipv4'     => [],
                            'ipv4_ptr' => [],
                            'ipv6_ptr' => [],
                        ];
                    }

                    // 根據 type 分類塞入對應陣列
                    if (isset($schools[$code][$type])) {
                        $schools[$code][$type][] = $value;
                    }
                }
            }
            fclose($stream);
        }

        // 重設索引陣列回傳 (從關聯陣列轉為純索引陣列)
        return array_values($schools);
    }

    private function getSchoolNameByZone($targetZone)
    {
        $targetZone = strtolower(trim($targetZone));
        $dnsData = $this->getDnsData();

        foreach ($dnsData as $school) {
            // 合併檢查 ipv4, ipv4_ptr, ipv6_ptr 三種類型
            $allZones = array_merge(
                $school['ipv4'] ?? [],
                $school['ipv4_ptr'] ?? [],
                $school['ipv6_ptr'] ?? []
            );

            foreach ($allZones as $zone) {
                if (strtolower(trim($zone)) === $targetZone) {
                    return $school['name']; // 找到匹配，回傳學校名稱
                }
            }
        }

        return '未知學校'; // 若找不到傳回預設值
    }

}
