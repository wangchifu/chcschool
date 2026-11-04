<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;

// RP申請獲得
define('CLIENT_ID', env('CLIENT_ID'));
define('CLIENT_SECRET', env('CLIENT_SECRET'));
define('AUTH_SECRET', '');

$url = request()->getHost();
define('REDIR_URI0', 'https://'.$url.'/auth/callback');
define('WELL_KNOWN_URL', 'https://chc.sso.edu.tw/.well-known/openid-configuration');
// 預設0由設定檔的URL決定；設定為1則每次皆由WELL_KNOWN取回END POINT URL
define('DYNAMICAL_ENDPOINT', 0);
// DYNAMICAL_ENDPOINT設為0下方三項需填寫
define('AUTH_ENDPOINT', 'https://chc.sso.edu.tw/oidc/v1/azp');
define('TOKEN_ENDPOINT', 'https://chc.sso.edu.tw/oidc/v1/token');
define('USERINFO_ENDPOINT', 'https://chc.sso.edu.tw/oidc/v1/userinfo');
define('JWKS_URI', 'https://chc.sso.edu.tw/oidc/v1/jwksets');
define('PROFILE_ENDPOINT', 'https://chc.sso.edu.tw/moeresource/api/v1/oidc/profile');

class OpenIDController extends Controller
{
    public function sso(){
        $obj = new openid();
        session(['azp_state' => rand(0, 9999999)]);
        if(!session()->has('nonce')){
            session(['nonce' => base64_encode(session('azp_state'))]);
        }else{
            session(['nonce' => session('nonce')]);
        }

        $auth_ep = AUTH_ENDPOINT;
        if(DYNAMICAL_ENDPOINT){
            $auth_ep = $obj->getEndPoint()->authorization_endpoint;
        }
        $link = $auth_ep . "?response_type=code&client_id=". CLIENT_ID ."&redirect_uri=".urlencode(REDIR_URI0) ."&scope=openid+email+profile+eduinfo+personid&state=".session('azp_state')."&nonce=".session('nonce');
        return redirect($link);
    }

    public function callback(Request $request){
      $code  = $request->input('code');
      $state = $request->input('state');
      
      // 1. 驗證 $state 與 $code
      if( !$code || !$state ){
        die("【除錯 1 失敗】認證伺服器回傳結果失敗！未取得 code 或 state。<br>GET 參數：" . json_encode($_GET));
      }
      
      if( strcmp($state, session('azp_state')) !== 0 ){
        die("【除錯 2 失敗】錯誤的認證狀態！<br>Session azp_state: " . session('azp_state') . "<br>GET state: " . $state);
      }      
      
      $obj = new openid();
      
      $token_ep = TOKEN_ENDPOINT;
      if(DYNAMICAL_ENDPOINT){
         $token_ep = $obj->getEndPoint()->token_endpoint;
      }
      
      // 2. 取得 Access Token
      $acctoken = $obj->getAccessToken($token_ep, $code, REDIR_URI0);
      if( !$acctoken || !isset($acctoken->access_token) ) {
        die("【除錯 3 失敗】無法取得 ACCESS TOKEN！<br>SSO 端點回傳內容：<pre>" . print_r($acctoken, true) . "</pre>");
      }
      
      session(['access_token' => $acctoken->access_token]);
      session(['id_token' => $acctoken->id_token]);
      
      if(!session()->has('access_token')){
        die("【除錯 4 失敗】Session 無法存入 access_token！");
      }

      $token_ep2 = USERINFO_ENDPOINT;
      if(DYNAMICAL_ENDPOINT){
        $token_ep2 = $obj->getEndPoint()->userinfo_endpoint;
      }

      // 3. 取得 UserInfo / Profile / Edufile 資料
      $userinfo = $obj->getUserinfo($token_ep2, session('access_token'), true);
      $profile  = $obj->getUserinfo("https://chc.sso.edu.tw/cncresource/api/v1/personid", session('access_token'), true);
      $edufile  = $obj->getUserinfo("https://chc.sso.edu.tw/cncresource/api/v1/eduinfo", session('access_token'), true);

      if( !$userinfo ) {
        die("【除錯 5 失敗】無法取得 USER INFO 資料。");
      }

      if( !$profile ){
        die("【除錯 6 失敗】無法取得 PROFILE 資料。");
      }

      if( empty($profile['personid']) ){
        die("【除錯 7 失敗】PROFILE 中無法取得 PERSONID！<br>Profile 回傳內容：<pre>" . print_r($profile, true) . "</pre>");
      }      

      if( !$edufile ){
        die("【除錯 8 失敗】無法取得 EDUFILE 資料。");
      }

      // 4. 檢查 edufile 基本陣列結構
      if (empty($edufile['schoolid'])) {
        die("【除錯 9 失敗】EDUFILE 中缺少 schoolid！<br>Edufile 內容：<pre>" . print_r($edufile, true) . "</pre>");
      }

      if (!isset($edufile['titles'][0]['titles'][0])) {
        die("【除錯 10 失敗】EDUFILE 中的 titles 結構不符或為空！<br>Edufile 內容：<pre>" . print_r($edufile, true) . "</pre>");
      }

      $user_obj['username'] = isset($userinfo['sub']) ? $userinfo['sub'] : '';
      $user_obj['password'] = "openID";
      $user_obj['success']  = 1;
      $user_obj['name']     = isset($userinfo['name']) ? $userinfo['name'] : '';      
      $user_obj['personid'] = $profile['personid'];
      $user_obj['code']     = $edufile['schoolid'];
      $user_obj['title']    = $edufile['titles'][0]['titles'][0];
      $user_obj['kind']     = "";
              
      if ($user_obj['success']) {            
          // 5. 由 config/tenants.php 讀取 Domain 與 資料庫名稱 mapping
          $host = $request->getHost();
          $mapping = config('tenants.mapping', []);
          
          // 解析目前域名對應的資料庫名稱 (例如 s074308)
          $d = isset($mapping[$host]) ? $mapping[$host] : config('tenants.default');

          if (empty($d)) {
              die("【除錯 11 失敗】無法解析對應的資料庫名稱 \$d！<br>Host: {$host}<br>tenants.mapping config: <pre>" . print_r($mapping, true) . "</pre>");
          }

          // 6. 學校 Config 檢查
          $schools_array = config('chcschool.schools');
          if (is_null($schools_array)) {
              die("【除錯 12 失敗】無法讀取到 config('chcschool.schools')，請檢查 config/chcschool.php 設定檔。");
          }

          $schoolCode = $user_obj['code']; 
          if(isset($schools_array[$schoolCode])){
            $school = $schools_array[$schoolCode];
          } else {
            $message = "非學校教職員";
            $url = "https://chc.sso.edu.tw/oidc/v1/logout-to-go";
            $post_logout_redirect_uri = url('logins');        
            $id_token_hint = session('id_token');
            $link = $url . "?post_logout_redirect_uri=".$post_logout_redirect_uri."&id_token_hint=" . $id_token_hint;
            return redirect($link)->withErrors(['gsuite_error' => [$message]]);              
          }

          // 學校代碼與資料庫名稱比對 (如 s074308 -> 074308)
          if ($schoolCode != substr($d, 1, 6)) {
              $check_code = 0;
              if(substr($d, 1, 6) == "074760" && $schoolCode == "074543"){ $check_code = 1; }
              if(substr($d, 1, 6) == "074541" && $schoolCode == "074774"){ $check_code = 1; }
              if(substr($d, 1, 6) == "074542" && $schoolCode == "074778"){ $check_code = 1; }
              if(substr($d, 1, 6) == "074537" && $schoolCode == "074745"){ $check_code = 1; }                                                
              
              if ($check_code == 0) {
                  $message = "非本校教職員生";
                  $url = "https://chc.sso.edu.tw/oidc/v1/logout-to-go";
                  $post_logout_redirect_uri = url('logins');        
                  $id_token_hint = session('id_token');
                  $link = $url . "?post_logout_redirect_uri=".$post_logout_redirect_uri."&id_token_hint=" . $id_token_hint;
                  return redirect($link)->withErrors(['gsuite_error' => [$message]]);                                                    
              }
          }
        
          // 7. 學生身份處理與 classinfo 防護檢查
          if ($user_obj['title'] == "學生") {
            if (!isset($edufile['classinfo'][0]['grade']) || !isset($edufile['classinfo'][0]['classno'])) {
              die("【除錯 13 失敗】學生身份缺少班級資料 (classinfo)！<br>Edufile 內容：<pre>" . print_r($edufile, true) . "</pre>");
            }

            $user_obj['classno'] = $edufile['classinfo'][0]['grade'] . sprintf('%02d', $edufile['classinfo'][0]['classno']);
            session(['stu_data' => $user_obj['classno']." ".$user_obj['name']]);
            session(['stu_fix'  => $user_obj['classno']." ".$user_obj['name']]);
            
            try {
                return redirect()->route('stu.index');
            } catch (\Exception $e) {
                die("【除錯 14 失敗】路由 'stu.index' 不存在！錯誤訊息：" . $e->getMessage());
            }
          } else {
            $title_array = isset($edufile['titles'][0]['titles']) ? $edufile['titles'][0]['titles'] : [];
            $title = implode(',', $title_array);
            $user_obj['kind'] = $title;      
          }
          
          // 8. 資料庫 User 讀寫檢查
          try {
              $user = User::where('edu_key', $user_obj['personid'])          
                  ->where('login_type','openID')                  
                  ->first();

              if (empty($user)) {
                  $att['username']   = $user_obj['username'];
                  $att['name']       = $user_obj['name'];
                  $att['edu_key']    = $user_obj['personid'];
                  $att['uid']        = "openID";
                  $att['password']   = $user_obj['password'];
                  $att['code']       = $schoolCode;
                  $att['school']     = $school;
                  $att['kind']       = $user_obj['kind'];
                  $att['title']      = $user_obj['title'];
                  $att['login_type'] = "openID";

                  $user = User::create($att);
              } else {
                  if($user->disable == 1){
                    $message = "你被停權了";
                    $url = "https://chc.sso.edu.tw/oidc/v1/logout-to-go";
                    $post_logout_redirect_uri = url('logins');        
                    $id_token_hint = session('id_token');
                    $link = $url . "?post_logout_redirect_uri=".$post_logout_redirect_uri."&id_token_hint=" . $id_token_hint;
                    return redirect($link)->withErrors(['gsuite_error' => [$message]]);                                                    
                  }

                  $att['username']   = $user_obj['username'];
                  $att['name']       = $user_obj['name'];
                  $att['edu_key']    = $user_obj['personid'];
                  $att['uid']        = "openID";
                  $att['password']   = $user_obj['password'];
                  $att['code']       = $schoolCode;
                  $att['school']     = $school;
                  $att['kind']       = $user_obj['kind'];
                  $att['login_type'] = "openID";           

                  $user->update($att);
              }
          } catch (\Exception $e) {
              die("【除錯 15 失敗】User 資料庫操作失敗！<br>錯誤訊息：" . $e->getMessage());
          }

          // 9. 登入與路由跳轉檢查
          try {
              Auth::login($user);
              return redirect()->route('index');
          } catch (\Exception $e) {
              die("【除錯 16 失敗】Auth 登入或跳轉路由 'index' 失敗！<br>錯誤訊息：" . $e->getMessage());
          }
      }      
    }
}

class openid {
    public function getEndPoint($rtn_array=false){
      $options = array(
        'http' => array(
          'header'  => '',
          'method'  => 'GET',
          'content' => ''
        ));
      $context = stream_context_create($options);
      $result = file_get_contents(WELL_KNOWN_URL, false, $context);
      $u = json_decode($result, $rtn_array);
      return $u;
    }
  
    public function getAccessToken($token_ep='' ,$code='', $redir_uri='' ,$rtn_array=false){
      $hash = base64_encode( CLIENT_ID . ":" . CLIENT_SECRET);
      $data = array('grant_type' => 'authorization_code', 'code'=> $code,
        'redirect_uri' => $redir_uri);
      $header = array( "Content-type: application/x-www-form-urlencoded",
         "Authorization: Basic $hash" ) ;
      $options = array(
          'http' => array(
            'header'  => $header,
            'method'  => 'POST',
            'content' => http_build_query($data)
          ));
      $context = stream_context_create($options);
      $result = file_get_contents($token_ep, false, $context);
      $j = json_decode($result, $rtn_array);
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
      $u = json_decode($result, true);
      return $u;
    }
   
    public function getUserinfo($token_ep='' ,$accesstoken='',$rtn_array=false){
      $header = array( "Authorization: Bearer $accesstoken" );
      $options = array(
          'http' => array(
            'header'  => $header,
            'method'  => 'GET',
            'content' => ''
          ));
      $context = stream_context_create($options);
      $result = file_get_contents($token_ep, false, $context);
      $u = json_decode($result,$rtn_array);
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