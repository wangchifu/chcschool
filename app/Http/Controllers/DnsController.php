<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Net_DNS2_Resolver;
use Net_DNS2_Updater;
use Net_DNS2_RR;
use Net_DNS2_Exception;
use Carbon\Carbon;

class DnsController extends Controller
{   
    private $dnsServer;
    private $zoneDomain;
    public function __construct()
    {
        $this->dnsServer = env('DDNS_SERVER', '127.0.0.1');        
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $domain = parse_url('http://' . $host, PHP_URL_HOST);
        
        $this->zoneDomain = preg_replace('/^www\./i', '', $domain);
    }

    public function index(Request $request)
    {        
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

        if ($name === '@' || $name === $this->zoneDomain) {
            $fqdn = $this->zoneDomain;
        } else {
            $cleanInput = str_replace('.' . $this->zoneDomain, '', $name);
            $fqdn = $cleanInput . '.' . $this->zoneDomain;
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
                    $val = $rr->address ?? $rr->cname ?? $rr->nsdname ?? ($rr->rdata ?? '');
                    if (!empty($val)) {
                        $answers[] = in_array($type, ['CNAME', 'NS', 'MX', 'SRV']) ? (rtrim($val, '.') . '.') : $val;
                    }
                }

                return response()->json([
                    'success' => true,
                    'title'   => '解析成功！',
                    'message' => "DNS 伺服器 ({$this->dnsServer}) 已順利解析到記錄：\n" . implode("\n", $answers),
                ]);
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
                'message' => "無法從 DNS 伺服器取得回應：\n" . $e->getMessage(),
            ]);
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

    public function ptr(Request $request, $networkSubnet = '163.23.200')
    {
        $records = [];
        $error = null;

        // 1. 將 IP 網段轉為 PTR 專用的 Zone 名稱
        // 例如 "163.23.200" -> "200.23.163.in-addr.arpa"
        $ipParts = explode('.', $networkSubnet);
        if (count($ipParts) !== 3) {
            return redirect()->back()->with('error', 'PTR 網段格式錯誤，需為三組數字（例如：163.23.200）');
        }
        $ptrZoneDomain = sprintf('%s.%s.%s.in-addr.arpa', $ipParts[2], $ipParts[1], $ipParts[0]);

        // 取得持久化快取中 3 小時內新增的紀錄清單
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
                    $rawName = rtrim($rr->name, '.'); // 例如: 2.200.23.163.in-addr.arpa
                    
                    // 抓出 IP 最後一碼主機號 (例如: "2")
                    $lastOctet = str_replace('.' . $ptrZoneDomain, '', $rawName);
                    
                    // 組裝成完整的 IPv4 位址 (例如: "163.23.200.2")
                    $fullIp = "{$networkSubnet}.{$lastOctet}";

                    // 處理 PTR 指向的主機域名 (ptrdname)
                    $ptrdname = $rr->ptrdname ?? ($rr->rdata ?? '');
                    if (!empty($ptrdname)) {
                        $ptrdname = rtrim($ptrdname, '.') . '.';
                    }

                    // 判斷是否為 3 小時內新增
                    $createdAt = isset($recentAdded[$fullIp]) ? Carbon::parse($recentAdded[$fullIp]) : null;

                    $records[] = [
                        'ip_last'    => $lastOctet,   // 最後一碼 IP (如 2)
                        'ip_full'    => $fullIp,      // 完整 IP (如 163.23.200.2)
                        'type'       => $rr->type,    // PTR
                        'ttl'        => $rr->ttl,     // TTL 秒數
                        'domain'     => $ptrdname,    // 指向的完整域名 (如 pc1.chc.edu.tw.)
                        'created_at' => $createdAt,
                    ];
                }
            }

            // 依 IP 最後一碼由小到大排序 (例如 .1, .2, .10)
            usort($records, function ($a, $b) {
                return (int)$a['ip_last'] <=> (int)$b['ip_last'];
            });

        } catch (Net_DNS2_Exception $e) {
            $error = "無法抓取 PTR 反解記錄 ({$ptrZoneDomain}): " . $e->getMessage();
        }

        $schools = config('chcschool.schools', []);

        return view('dns.ptr', [
            'dnsServer'     => $this->dnsServer,
            'networkSubnet' => $networkSubnet,
            'ptrZoneDomain' => $ptrZoneDomain,
            'records'       => $records,
            'error'         => $error,
            'schools'       => $schools,
        ]);
    }
}
