<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Net_DNS2_Resolver;
use Net_DNS2_Updater;
use Net_DNS2_RR;
use Net_DNS2_Exception;
use Carbon\Carbon;

class DnsController extends Controller 
{   
    private $dns_data;    
    private $dnsServer;
    private $zoneDomain;
    public function __construct()
    {
        $this->dnsServer = env('DDNS_SERVER', '127.0.0.1');            
    }

    public function index(Request $request,$my_zoneDomain = null)
    {                
        $dns_data = $this->getDnsData();
        $this->zoneDomain = (empty($my_zoneDomain)) ? $dns_data['ipv4'][0] : $my_zoneDomain;        
        if(!in_array($this->zoneDomain, $dns_data['ipv4'])){
            return redirect()->route('index')->with('error', '您無權限管理此網域的 DNS 記錄！');
        }        
        $records = [];
        $error = null;

        // 取得持久化快取中 3 小時內新增的紀錄清單
        $recentAdded = Cache::get('recent_dns_records', []);

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

                    // 2. 💡 修正點：針對 NS、CNAME、MX、A/AAAA 精確拿取原始紀錄值（保留真實點號）
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

                    $records[] = [
                        'name'       => $displayName,
                        'type'       => $rr->type,
                        'ttl'        => $rr->ttl,
                        'value'      => trim($value),
                        'created_at' => $createdAt,
                    ];
                }
            }

        } catch (Net_DNS2_Exception $e) {
            $error = '無法抓取 Zone 記錄 (請確認 163.23.200.6 是否有開啟 allow-transfer): ' . $e->getMessage();
        }        
        $schools = config('chcschool.schools', []);

        return view('dns.index', [
            'dns_data'   => $dns_data,
            'dnsServer'  => $this->dnsServer,
            'zoneDomain' => $this->zoneDomain,
            'records'    => $records,
            'error'      => $error,
            'schools' => $schools,
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
        ]);

        $this->zoneDomain = $request->input('zoneDomain');

        $name      = trim($request->input('name'));
        $type      = strtoupper($request->input('type'));
        $ttlOption = $request->input('ttl_option');
        $value     = trim($request->input('value'));

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

            // 寫入持久化 Cache (保存 3 小時)
            $recentAdded = Cache::get('recent_dns_records', []);
            $recentAdded[$saveKey] = now()->toDateTimeString();
            Cache::put('recent_dns_records', $recentAdded, 10800);

            return redirect()->route('dns.index')->with('success', "成功新增紀錄：{$saveKey} ({$type})");

        } catch (Net_DNS2_Exception $e) {
            return redirect()->route('dns.index')->with('error', "新增失敗: " . $e->getMessage());
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

            // 💡 使用 back() 可自動彈回上一頁並帶入成功訊息（避免路由命名為 index 或 dns.index 造成錯亂）
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

        // 💡 判斷是否為 PTR 反解查詢
        if ($type === 'PTR') {
            // 如果傳入的是標準 IPv4 (例如: 163.23.200.10)，自動翻轉為 10.200.23.163.in-addr.arpa
            if (filter_var($name, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                $ipParts = explode('.', $name);
                $fqdn = sprintf('%s.%s.%s.%s.in-addr.arpa', $ipParts[3], $ipParts[2], $ipParts[1], $ipParts[0]);
            } else {
                // 若傳入的已經是 in-addr.arpa 格式，確保末端有點號
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
                    // 💡 針對不同 RR 類型精準抓取值
                    if ($type === 'PTR') {
                        $val = $rr->ptrdname ?? ($rr->rdata ?? '');
                    } elseif ($type === 'TXT' && !empty($rr->text)) {
                        $val = is_array($rr->text) ? implode('', $rr->text) : $rr->text;
                    } else {
                        $val = $rr->address ?? $rr->cname ?? $rr->nsdname ?? ($rr->rdata ?? '');
                    }

                    if (!empty($val)) {
                        // 💡 強制清理為標準 UTF-8，剔除不可見或非法位元組
                        $cleanVal = mb_convert_encoding($val, 'UTF-8', 'UTF-8');
                        $answers[] = in_array($type, ['CNAME', 'NS', 'MX', 'SRV', 'PTR']) ? (rtrim($cleanVal, '.') . '.') : $cleanVal;
                    }
                }

                $message = "DNS 伺服器 ({$this->dnsServer}) 已順利解析到記錄：\n" . implode("\n", $answers);

                // 💡 加上 JSON_INVALID_UTF8_SUBSTITUTE 確保 json_encode 不會崩潰
                return response()->json([
                    'success' => true,
                    'title'   => '解析成功！',
                    'message' => $message,
                ], 200, [], JSON_INVALID_UTF8_SUBSTITUTE);
            } else {
                return response()->json([
                    'success' => false,
                    'title'   => '解析失敗',
                    'message' => "DNS 伺服器回應了查詢，但找不到 {$fqdn} 的 {$type} 紀錄。",
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

                    $records[] = [
                        'ip_last'    => $lastOctet,   // 最後一碼 IP (如 100)
                        'ip_full'    => $fullIp,      // 完整 IP (如 163.23.93.100)
                        'type'       => $rr->type,    // PTR
                        'ttl'        => $rr->ttl,     // TTL 秒數
                        'domain'     => $ptrdname,    // 指向的完整域名 (如 pc1.chc.edu.tw.)
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

        return view('dns.ptr', [
            'dnsServer'     => $this->dnsServer,
            'networkSubnet' => $networkSubnet,
            'ptrZoneDomain' => $ptrZoneDomain,
            'records'       => $records,
            'error'         => $error,
            'schools'       => $schools,
            'dns_data'      => $this->getDnsData(), // 傳入 view 供上方按鈕列繪製切換選單
        ]);
    }    

    public function ptr_store(Request $request)
    {
        $request->validate([
            'network_subnet' => 'required|string',
            'ip_last'        => 'required|integer|between:1,254',
            'ttl_option'     => 'required|string',
            'domain'         => 'required|string',
        ]);

        $networkSubnet = trim($request->input('network_subnet'));
        $ipLast        = (int)$request->input('ip_last');
        $ttlOption     = $request->input('ttl_option');
        $domain        = trim($request->input('domain'));

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
            $updater = new Net_DNS2_Updater($ptrZoneDomain, [
                'nameservers' => [$this->dnsServer],
                'timeout'     => 5,
            ]);

            $rr = Net_DNS2_RR::fromString($rrString);
            $updater->add($rr);
            $updater->update();

            // 寫入持久化 Cache (保存 3 小時，鍵名使用完整 IP)
            $recentAdded = Cache::get('recent_ptr_records', []);
            $recentAdded[$fullIp] = now()->toDateTimeString();
            Cache::put('recent_ptr_records', $recentAdded, 10800);

            return redirect()->route('dns.ptr', ['networkSubnet' => $networkSubnet])
                            ->with('success', "成功新增 PTR 反解紀錄：{$fullIp} -> {$targetDomain}");

        } catch (Net_DNS2_Exception $e) {
            return redirect()->route('dns.ptr', ['networkSubnet' => $networkSubnet])
                            ->with('error', "新增 PTR 失敗: " . mb_convert_encoding($e->getMessage(), 'UTF-8', 'UTF-8'));
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

            // 計算完整 IP (供訊息顯示)
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
        $ptrZoneDomain = $networkSubnet ? rtrim($networkSubnet, '.') : '';

        if (!empty($ptrZoneDomain)) {
            $recentAdded = Cache::get('recent_ptr6_records', []);

            try {
                $resolver = new Net_DNS2_Resolver([
                    'nameservers' => [$this->dnsServer],
                    'timeout'     => 5,
                ]);

                $response = $resolver->query($ptrZoneDomain, 'AXFR');

                foreach ($response->answer as $rr) {
                    if ($rr->type === 'PTR') {
                        $rawName = rtrim($rr->name, '.'); // 例如: 1.0.0.0.0.0.0.0...ip6.arpa
                        $hostPart = str_replace('.' . $ptrZoneDomain, '', $rawName);

                        $ptrdname = $rr->ptrdname ?? ($rr->rdata ?? '');
                        if (!empty($ptrdname)) {
                            $ptrdname = mb_convert_encoding($ptrdname, 'UTF-8', 'UTF-8');
                            $ptrdname = rtrim($ptrdname, '.') . '.';
                        }

                        $createdAt = isset($recentAdded[$rawName]) ? Carbon::parse($recentAdded[$rawName]) : null;

                        $records[] = [
                            'host_part'  => $hostPart,
                            'raw_fqdn'   => $rawName,
                            'type'       => 'PTR',
                            'ttl'        => $rr->ttl,
                            'domain'     => $ptrdname,
                            'created_at' => $createdAt,
                        ];
                    }
                }
            } catch (Net_DNS2_Exception $e) {
                $error = "無法抓取 IPv6 PTR 反解記錄 ({$ptrZoneDomain}): " . mb_convert_encoding($e->getMessage(), 'UTF-8', 'UTF-8');
            }
        }

        return view('dns.ptr6', [
            'dnsServer'     => $this->dnsServer,
            'networkSubnet' => $networkSubnet,
            'ptrZoneDomain' => $ptrZoneDomain,
            'records'       => $records,
            'error'         => $error,
            'schools'       => config('chcschool.schools', []),
            'dns_data'      => $this->getDnsData(),
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
        ]);

        $networkSubnet = trim($request->input('network_subnet'));
        $hostPart      = trim($request->input('host_part'));
        $ttlOption     = $request->input('ttl_option');
        $domain        = trim($request->input('domain'));

        $ptrZoneDomain = rtrim($networkSubnet, '.');
        $ttlSeconds    = $this->convertTtlToSeconds($ttlOption);
        $targetDomain  = rtrim($domain, '.') . '.';

        // 💡 關鍵修正：處理 host_part
        // 情況 A：傳入的是標準 IPv6 位址 (例如 2001:288:5863:3700::1)
        if (filter_var($hostPart, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $ptrFqdn = $this->ipv6ToPtrFqdn($hostPart);
            
            // 安全檢查：確認轉換後的 FQDN 是否真的以當前 Zone 結尾
            if (!str_ends_with(rtrim($ptrFqdn, '.'), $ptrZoneDomain)) {
                return redirect()->back()->with('error', "新增失敗：輸入的 IPv6 位址與目前的 Zone 網段 ({$ptrZoneDomain}) 不符！");
            }
        } 
        // 情況 B：傳入的是主機末端前綴 (例如輸入 "1" 或 "1.0.0.0.0.0.0.0")
        else {
            $cleanHost = rtrim($hostPart, '.');
            $ptrFqdn   = "{$cleanHost}.{$ptrZoneDomain}.";
        }

        $rrString = "{$ptrFqdn} {$ttlSeconds} IN PTR {$targetDomain}";

        try {
            $updater = new Net_DNS2_Updater($ptrZoneDomain, [
                'nameservers' => [$this->dnsServer],
                'timeout'     => 5,
            ]);

            $rr = Net_DNS2_RR::fromString($rrString);
            $updater->add($rr);
            $updater->update();

            $rawName = rtrim($ptrFqdn, '.');
            $recentAdded = Cache::get('recent_ptr6_records', []);
            $recentAdded[$rawName] = now()->toDateTimeString();
            Cache::put('recent_ptr6_records', $recentAdded, 10800);

            return redirect()->route('dns.ptr6', ['networkSubnet' => $networkSubnet])
                            ->with('success', "成功新增 IPv6 PTR 紀錄：{$targetDomain}");

        } catch (Net_DNS2_Exception $e) {
            return redirect()->route('dns.ptr6', ['networkSubnet' => $networkSubnet])
                            ->with('error', "新增失敗: " . mb_convert_encoding($e->getMessage(), 'UTF-8', 'UTF-8'));
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
        $userCode = auth()->user()->code ?? null;
        //原斗
        if($userCode=="074537") $userCode = "074745";

        $result = [
            'code' => $userCode,
            'name' => '',
            'ipv4' => [],
            'ipv4_ptr' => [],
            'ipv6_ptr' => [],
        ];

        $csvPath = 'privacy/dns_data.csv';

        if ($userCode && Storage::exists($csvPath)) {
            $stream = Storage::readStream($csvPath);

            while (($data = fgetcsv($stream)) !== false) {
                if (count($data) >= 4) {
                    $code = trim($data[0]);
                    $name = trim($data[1]);
                    $type = trim($data[2]);
                    $value = trim($data[3]);

                    if ($code === $userCode) {
                        $result['name'] = $name;

                        if (array_key_exists($type, $result)) {
                            $result[$type][] = $value;
                        }
                    }
                }
            }
            fclose($stream);
        }

        return $result;
    }    
}
