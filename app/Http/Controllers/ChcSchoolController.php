<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDO;          // 引入原生的 PDO
use PDOException; // 引入原生的 PDOException

class ChcSchoolController extends Controller
{
    public function pages(){        
        // 1. 資料庫連線
        $dbms = 'mysql';
        $host = env('DNS_DB_HOST');
        $dbName = env('DNS_DB_NAME');
        $user = env('DNS_DB_USER');
        $pass = env('DNS_DB_PASS');
        $dsn = "$dbms:host=$host;dbname=$dbName";

        try {
            $dbh = new PDO($dsn, $user, $pass);
            $dbh->query('SET NAMES "utf8"');
        } catch (PDOException $e) {
            die ("Error!: " . $e->getMessage() . "<br/>");
        }

        $schools_status = [];
        $school3_1 = 0;
        $school3_2 = 0;

        $sql = "SELECT DISTINCT u.brief, r.rdata 
                FROM RR r, unit u  
                WHERE CONCAT(r.fqdn, '.chc.edu.tw') = u.domain 
                AND r.rdata IN ('163.23.200.50', '163.23.200.49')";
                
        $result = $dbh->query($sql);
        
        if ($result) {
            foreach ($result as $row) {
                if ($row['rdata'] == '163.23.200.50') {
                    $schools_status[$row['brief']] = "50";
                    $school3_1++;
                } elseif ($row['rdata'] == '163.23.200.49') {
                    $schools_status[$row['brief']] = "49";
                    $school3_2++;
                }
            }
        }

        // 2. 學校原始清單（扁平化陣列，易於維護）
        $raw_schools = [
            ['town' => '彰化市', 'code' => '074308', 'school' => '彰化藝術高中', 'website' => 'chash.chc.edu.tw'],
            ['town' => '二林鎮', 'code' => '074313', 'school' => '二林高中', 'website' => 'elsh.chc.edu.tw'],
            ['town' => '二水鄉', 'code' => '074529', 'school' => '二水國中', 'website' => 'esjh.chc.edu.tw'],
            ['town' => '和美鎮', 'code' => '074323', 'school' => '和美高中', 'website' => 'hmjh.chc.edu.tw'],
            ['town' => '田中鎮', 'code' => '074328', 'school' => '田中高中', 'website' => 'tcjh.chc.edu.tw'],
            ['town' => '溪湖鎮', 'code' => '074339', 'school' => '成功高中', 'website' => 'ckjh.chc.edu.tw'],
            ['town' => '北斗鎮', 'code' => '074501', 'school' => '北斗國中', 'website' => 'ptjhs.chc.edu.tw'],
            ['town' => '鹿港鎮', 'code' => '074502', 'school' => '鹿港國中', 'website' => 'lkjh.chc.edu.tw'],
            ['town' => '鹿港鎮', 'code' => '074503', 'school' => '鹿鳴國中', 'website' => 'lmjh.chc.edu.tw'],
            ['town' => '線西鄉', 'code' => '074504', 'school' => '線西國中', 'website' => 'hhjh.chc.edu.tw'],
            ['town' => '彰化市', 'code' => '074505', 'school' => '陽明國中', 'website' => 'ymsc.chc.edu.tw'],
            ['town' => '彰化市', 'code' => '074506', 'school' => '彰安國中', 'website' => 'cajh.chc.edu.tw'],
            ['town' => '彰化市', 'code' => '074507', 'school' => '彰德國中', 'website' => 'ctjh.chc.edu.tw'],
            ['town' => '芬園鄉', 'code' => '074509', 'school' => '芬園國中', 'website' => 'fyjh.chc.edu.tw'],
            ['town' => '員林市', 'code' => '074510', 'school' => '員林國中', 'website' => 'yljh.chc.edu.tw'],
            ['town' => '員林市', 'code' => '074511', 'school' => '明倫國中', 'website' => 'mljh.chc.edu.tw'],
            ['town' => '二林鎮', 'code' => '074512', 'school' => '萬興國中', 'website' => 'whjh.chc.edu.tw'],
            ['town' => '竹塘鄉', 'code' => '074514', 'school' => '竹塘國中', 'website' => 'ctjhs.chc.edu.tw'],
            ['town' => '大城鄉', 'code' => '074515', 'school' => '大城國中', 'website' => 'tcjhs.chc.edu.tw'],
            ['town' => '芳苑鄉', 'code' => '074516', 'school' => '草湖國中', 'website' => 'thjh.chc.edu.tw'],
            ['town' => '芳苑鄉', 'code' => '074517', 'school' => '芳苑國中', 'website' => 'fyjhs.chc.edu.tw'],
            ['town' => '溪湖鎮', 'code' => '074518', 'school' => '溪湖國中', 'website' => 'cfjh.chc.edu.tw'],
            ['town' => '埔鹽鄉', 'code' => '074519', 'school' => '埔鹽國中', 'website' => 'pyjh.chc.edu.tw'],
            ['town' => '埔心鄉', 'code' => '074520', 'school' => '埔心國中', 'website' => 'psjh.chc.edu.tw'],
            ['town' => '福興鄉', 'code' => '074521', 'school' => '福興國中', 'website' => 'fsjh.chc.edu.tw'],
            ['town' => '秀水鄉', 'code' => '074522', 'school' => '秀水國中', 'website' => 'hsjh.chc.edu.tw'],
            ['town' => '伸港鄉', 'code' => '074524', 'school' => '伸港國中', 'website' => 'skjh.chc.edu.tw'],
            ['town' => '大村鄉', 'code' => '074525', 'school' => '大村國中', 'website' => 'ttjh.chc.edu.tw'],
            ['town' => '花壇鄉', 'code' => '074526', 'school' => '花壇國中', 'website' => 'htjh.chc.edu.tw'],
            ['town' => '永靖鄉', 'code' => '074527', 'school' => '永靖國中', 'website' => 'ycjh.chc.edu.tw'],
            ['town' => '社頭鄉', 'code' => '074530', 'school' => '社頭國中', 'website' => 'stjh.chc.edu.tw'],
            ['town' => '田尾鄉', 'code' => '074531', 'school' => '田尾國中', 'website' => 'twjh.chc.edu.tw'],
            ['town' => '溪州鄉', 'code' => '074532', 'school' => '溪州國中', 'website' => 'ccjh.chc.edu.tw'],
            ['town' => '溪州鄉', 'code' => '074533', 'school' => '溪陽國中', 'website' => 'hyjh.chc.edu.tw'],
            ['town' => '埤頭鄉', 'code' => '074534', 'school' => '埤頭國中', 'website' => 'ptjh.chc.edu.tw'],
            ['town' => '和美鎮', 'code' => '074535', 'school' => '和群國中', 'website' => 'hcjh.chc.edu.tw'],
            ['town' => '員林市', 'code' => '074536', 'school' => '大同國中', 'website' => 'ttjhs.chc.edu.tw'],
            ['town' => '彰化市', 'code' => '074538', 'school' => '彰興國中', 'website' => 'csjh.chc.edu.tw'],
            ['town' => '彰化市', 'code' => '074540', 'school' => '彰泰國中', 'website' => 'ctsjh.chc.edu.tw'],
            ['town' => '彰化市', 'code' => '074541', 'school' => '信義國中小', 'website' => 'hyjhes.chc.edu.tw'],
            ['town' => '鹿港鎮', 'code' => '074542', 'school' => '鹿江國中小', 'website' => 'ljis.chc.edu.tw'],
            ['town' => '彰化市', 'code' => '074601', 'school' => '中山國小', 'website' => 'cses.chc.edu.tw'],
            ['town' => '彰化市', 'code' => '074602', 'school' => '民生國小', 'website' => 'mses.chc.edu.tw'],
            ['town' => '彰化市', 'code' => '074603', 'school' => '平和國小', 'website' => 'phes.chc.edu.tw'],
            ['town' => '彰化市', 'code' => '074604', 'school' => '南郭國小', 'website' => 'nges.chc.edu.tw'],
            ['town' => '彰化市', 'code' => '074605', 'school' => '南興國小', 'website' => 'nses.chc.edu.tw'],
            ['town' => '彰化市', 'code' => '074606', 'school' => '東芳國小', 'website' => 'tfps.chc.edu.tw'],
            ['town' => '彰化市', 'code' => '074607', 'school' => '泰和國小', 'website' => 'thps.chc.edu.tw'],
            ['town' => '彰化市', 'code' => '074608', 'school' => '三民國小', 'website' => 'smes.chc.edu.tw'],
            ['town' => '彰化市', 'code' => '074609', 'school' => '聯興國小', 'website' => 'lsps.chc.edu.tw'],
            ['town' => '彰化市', 'code' => '074610', 'school' => '大竹國小', 'website' => 'tces.chc.edu.tw'],
            ['town' => '彰化市', 'code' => '074611', 'school' => '國聖國小', 'website' => 'gses.chc.edu.tw'],
            ['town' => '彰化市', 'code' => '074612', 'school' => '快官國小', 'website' => 'kges.chc.edu.tw'],
            ['town' => '彰化市', 'code' => '074613', 'school' => '石牌國小', 'website' => 'spes.chc.edu.tw'],
            ['town' => '彰化市', 'code' => '074614', 'school' => '忠孝國小', 'website' => 'jsps.chc.edu.tw'],
            ['town' => '芬園鄉', 'code' => '074615', 'school' => '芬園國小', 'website' => 'fyps.chc.edu.tw'],
            ['town' => '芬園鄉', 'code' => '074616', 'school' => '富山國小', 'website' => 'fsps.chc.edu.tw'],
            ['town' => '芬園鄉', 'code' => '074617', 'school' => '寶山國小', 'website' => 'bses.chc.edu.tw'],
            ['town' => '芬園鄉', 'code' => '074618', 'school' => '同安國小', 'website' => 'taes.chc.edu.tw'],
            ['town' => '芬園鄉', 'code' => '074619', 'school' => '文德國小', 'website' => 'wdes.chc.edu.tw'],
            ['town' => '芬園鄉', 'code' => '074620', 'school' => '茄荖國小', 'website' => 'cles.chc.edu.tw'],
            ['town' => '花壇鄉', 'code' => '074621', 'school' => '花壇國小', 'website' => 'htes.chc.edu.tw'],
            ['town' => '花壇鄉', 'code' => '074622', 'school' => '文祥國小', 'website' => 'wses.chc.edu.tw'],
            ['town' => '花壇鄉', 'code' => '074623', 'school' => '華南國小', 'website' => 'hnes.chc.edu.tw'],
            ['town' => '花壇鄉', 'code' => '074624', 'school' => '僑愛國小', 'website' => 'caps.chc.edu.tw'],
            ['town' => '花壇鄉', 'code' => '074625', 'school' => '三春國小', 'website' => 'sstps.chc.edu.tw'],
            ['town' => '花壇鄉', 'code' => '074626', 'school' => '白沙國小', 'website' => 'bsps.chc.edu.tw'],
            ['town' => '和美鎮', 'code' => '074627', 'school' => '和美國小', 'website' => 'hmps.chc.edu.tw'],
            ['town' => '和美鎮', 'code' => '074628', 'school' => '和東國小', 'website' => 'hdes.chc.edu.tw'],
            ['town' => '和美鎮', 'code' => '074629', 'school' => '大嘉國小', 'website' => 'dces.chc.edu.tw'],
            ['town' => '和美鎮', 'code' => '074630', 'school' => '大榮國小', 'website' => 'dres.chc.edu.tw'],
            ['town' => '和美鎮', 'code' => '074631', 'school' => '新庄國小', 'website' => 'ssjes.chc.edu.tw'],
            ['town' => '和美鎮', 'code' => '074632', 'school' => '培英國小', 'website' => 'pyps.chc.edu.tw'],
            ['town' => '線西鄉', 'code' => '074633', 'school' => '線西國小', 'website' => 'sces.chc.edu.tw'],
            ['town' => '線西鄉', 'code' => '074634', 'school' => '曉陽國小', 'website' => 'syes.chc.edu.tw'],
            ['town' => '伸港鄉', 'code' => '074635', 'school' => '新港國小', 'website' => 'sgps.chc.edu.tw'],
            ['town' => '伸港鄉', 'code' => '074636', 'school' => '伸東國小', 'website' => 'sdes.chc.edu.tw'],
            ['town' => '伸港鄉', 'code' => '074637', 'school' => '伸仁國小', 'website' => 'sres.chc.edu.tw'],
            ['town' => '伸港鄉', 'code' => '074638', 'school' => '大同國小', 'website' => 'dtes.chc.edu.tw'],
            ['town' => '鹿港鎮', 'code' => '074639', 'school' => '鹿港國小', 'website' => 'lges.chc.edu.tw'],
            ['town' => '鹿港鎮', 'code' => '074640', 'school' => '文開國小', 'website' => 'wkes.chc.edu.tw'],
            ['town' => '鹿港鎮', 'code' => '074641', 'school' => '洛津國小', 'website' => 'ljes.chc.edu.tw'],
            ['town' => '鹿港鎮', 'code' => '074642', 'school' => '海埔國小', 'website' => 'hpes.chc.edu.tw'],
            ['town' => '鹿港鎮', 'code' => '074643', 'school' => '新興國小', 'website' => 'bsses.chc.edu.tw'],
            ['town' => '鹿港鎮', 'code' => '074644', 'school' => '草港國小', 'website' => 'tges.chc.edu.tw'],
            ['town' => '鹿港鎮', 'code' => '074645', 'school' => '頂番國小', 'website' => 'dfes.chc.edu.tw'],
            ['town' => '鹿港鎮', 'code' => '074646', 'school' => '東興國小', 'website' => 'sdses.chc.edu.tw'],
            ['town' => '福興鄉', 'code' => '074647', 'school' => '管嶼國小', 'website' => 'gyes.chc.edu.tw'],
            ['town' => '福興鄉', 'code' => '074648', 'school' => '文昌國小', 'website' => 'wces.chc.edu.tw'],
            ['town' => '福興鄉', 'code' => '074649', 'school' => '西勢國小', 'website' => 'ssses.chc.edu.tw'],
            ['town' => '福興鄉', 'code' => '074650', 'school' => '大興國小', 'website' => 'bdsps.chc.edu.tw'],
            ['town' => '福興鄉', 'code' => '074651', 'school' => '永豐國小', 'website' => 'yfes.chc.edu.tw'],
            ['town' => '福興鄉', 'code' => '074652', 'school' => '日新國小', 'website' => 'rses.chc.edu.tw'],
            ['town' => '福興鄉', 'code' => '074653', 'school' => '育新國小', 'website' => 'yses.chc.edu.tw'],
            ['town' => '秀水鄉', 'code' => '074654', 'school' => '秀水國小', 'website' => 'hses.chc.edu.tw'],
            ['town' => '秀水鄉', 'code' => '074655', 'school' => '馬興國小', 'website' => 'smses.chc.edu.tw'],
            ['town' => '秀水鄉', 'code' => '074656', 'school' => '華龍國小', 'website' => 'hlps.chc.edu.tw'],
            ['town' => '秀水鄉', 'code' => '074657', 'school' => '明正國小', 'website' => 'mcps.chc.edu.tw'],
            ['town' => '秀水鄉', 'code' => '074658', 'school' => '陝西國小', 'website' => 'ssps.chc.edu.tw'],
            ['town' => '秀水鄉', 'code' => '074659', 'school' => '育民國小', 'website' => 'ymes.chc.edu.tw'],
            ['town' => '溪湖鎮', 'code' => '074660', 'school' => '溪湖國小', 'website' => 'shps.chc.edu.tw'],
            ['town' => '溪湖鎮', 'code' => '074661', 'school' => '東溪國小', 'website' => 'bdses.chc.edu.tw'],
            ['town' => '溪湖鎮', 'code' => '074662', 'school' => '湖西國小', 'website' => 'fses.chc.edu.tw'],
            ['town' => '溪湖鎮', 'code' => '074663', 'school' => '湖東國小', 'website' => 'fdes.chc.edu.tw'],
            ['town' => '溪湖鎮', 'code' => '074664', 'school' => '湖南國小', 'website' => 'hnps.chc.edu.tw'],
            ['town' => '溪湖鎮', 'code' => '074665', 'school' => '媽厝國小', 'website' => 'mtes.chc.edu.tw'],
            ['town' => '埔鹽鄉', 'code' => '074666', 'school' => '埔鹽國小', 'website' => 'pyes.chc.edu.tw'],
            ['town' => '埔鹽鄉', 'code' => '074667', 'school' => '大園國小', 'website' => 'dyes.chc.edu.tw'],
            ['town' => '埔鹽鄉', 'code' => '074668', 'school' => '南港國小', 'website' => 'ngps.chc.edu.tw'],
            ['town' => '埔鹽鄉', 'code' => '074669', 'school' => '好修國小', 'website' => 'hsps.chc.edu.tw'],
            ['town' => '埔鹽鄉', 'code' => '074670', 'school' => '永樂國小', 'website' => 'yles.chc.edu.tw'],
            ['town' => '埔鹽鄉', 'code' => '074671', 'school' => '新水國小', 'website' => 'sses.chc.edu.tw'],
            ['town' => '埔鹽鄉', 'code' => '074672', 'school' => '天盛國小', 'website' => 'tses.chc.edu.tw'],
            ['town' => '埔心鄉', 'code' => '074673', 'school' => '埔心國小', 'website' => 'pses.chc.edu.tw'],
            ['town' => '埔心鄉', 'code' => '074674', 'school' => '太平國小', 'website' => 'tpes.chc.edu.tw'],
            ['town' => '埔心鄉', 'code' => '074675', 'school' => '舊館國小', 'website' => 'jges.chc.edu.tw'],
            ['town' => '埔心鄉', 'code' => '074676', 'school' => '羅厝國小', 'website' => 'rtes.chc.edu.tw'],
            ['town' => '埔心鄉', 'code' => '074677', 'school' => '鳳霞國小', 'website' => 'sfsps.chc.edu.tw'],
            ['town' => '埔心鄉', 'code' => '074678', 'school' => '梧鳳國小', 'website' => 'wfes.chc.edu.tw'],
            ['town' => '埔心鄉', 'code' => '074679', 'school' => '明聖國小', 'website' => 'msps.chc.edu.tw'],
            ['town' => '員林市', 'code' => '074680', 'school' => '員林國小', 'website' => 'ylps.chc.edu.tw'],
            ['town' => '員林市', 'code' => '074681', 'school' => '育英國小', 'website' => 'yyes.chc.edu.tw'],
            ['town' => '員林市', 'code' => '074682', 'school' => '靜修國小', 'website' => 'sjses.chc.edu.tw'],
            ['town' => '員林市', 'code' => '074683', 'school' => '僑信國小', 'website' => 'csps.chc.edu.tw'],
            ['town' => '員林市', 'code' => '074684', 'school' => '員東國小', 'website' => 'ytes.chc.edu.tw'],
            ['town' => '員林市', 'code' => '074685', 'school' => '饒明國小', 'website' => 'rmes.chc.edu.tw'],
            ['town' => '員林市', 'code' => '074686', 'school' => '東山國小', 'website' => 'dsps.chc.edu.tw'],
            ['town' => '員林市', 'code' => '074687', 'school' => '青山國小', 'website' => 'chcses.chc.edu.tw'],
            ['town' => '員林市', 'code' => '074688', 'school' => '明湖國小', 'website' => 'mhes.chc.edu.tw'],
            ['town' => '大村鄉', 'code' => '074689', 'school' => '大村國小', 'website' => 'dtps.chc.edu.tw'],
            ['town' => '大村鄉', 'code' => '074690', 'school' => '大西國小', 'website' => 'dses.chc.edu.tw'],
            ['town' => '大村鄉', 'code' => '074691', 'school' => '村上國小', 'website' => 'tsps.chc.edu.tw'],
            ['town' => '大村鄉', 'code' => '074692', 'school' => '村東國小', 'website' => 'tdes.chc.edu.tw'],
            ['town' => '永靖鄉', 'code' => '074693', 'school' => '永靖國小', 'website' => 'yces.chc.edu.tw'],
            ['town' => '永靖鄉', 'code' => '074694', 'school' => '福德國小', 'website' => 'fdps.chc.edu.tw'],
            ['town' => '永靖鄉', 'code' => '074695', 'school' => '永興國小', 'website' => 'ysps.chc.edu.tw'],
            ['town' => '永靖鄉', 'code' => '074696', 'school' => '福興國小', 'website' => 'sfses.chc.edu.tw'],
            ['town' => '永靖鄉', 'code' => '074697', 'school' => '德興國小', 'website' => 'sdsps.chc.edu.tw'],
            ['town' => '田中鎮', 'code' => '074698', 'school' => '田中國小', 'website' => 'tjes.chc.edu.tw'],
            ['town' => '田中鎮', 'code' => '074699', 'school' => '三潭國小', 'website' => 'stes.chc.edu.tw'],
            ['town' => '田中鎮', 'code' => '074700', 'school' => '大安國小', 'website' => 'daes.chc.edu.tw'],
            ['town' => '田中鎮', 'code' => '074701', 'school' => '內安國小', 'website' => 'naes.chc.edu.tw'],
            ['town' => '田中鎮', 'code' => '074702', 'school' => '東和國小', 'website' => 'dhps.chc.edu.tw'],
            ['town' => '田中鎮', 'code' => '074703', 'school' => '明禮國小', 'website' => 'mles.chc.edu.tw'],
            ['town' => '社頭鄉', 'code' => '074704', 'school' => '社頭國小', 'website' => 'stps.chc.edu.tw'],
            ['town' => '社頭鄉', 'code' => '074705', 'school' => '橋頭國小', 'website' => 'ctps.chc.edu.tw'],
            ['town' => '社頭鄉', 'code' => '074706', 'school' => '朝興國小', 'website' => 'scsps.chc.edu.tw'],
            ['town' => '社頭鄉', 'code' => '074707', 'school' => '清水國小', 'website' => 'bcses.chc.edu.tw'],
            ['town' => '社頭鄉', 'code' => '074708', 'school' => '湳雅國小', 'website' => 'nyes.chc.edu.tw'],
            ['town' => '二水鄉', 'code' => '074709', 'school' => '二水國小', 'website' => 'eses.chc.edu.tw'],
            ['town' => '二水鄉', 'code' => '074710', 'school' => '復興國小', 'website' => 'fsses.chc.edu.tw'],
            ['town' => '二水鄉', 'code' => '074711', 'school' => '源泉國小', 'website' => 'ycps.chc.edu.tw'],
            ['town' => '北斗鎮', 'code' => '074712', 'school' => '北斗國小', 'website' => 'bdes.chc.edu.tw'],
            ['town' => '北斗鎮', 'code' => '074713', 'school' => '萬來國小', 'website' => 'wles.chc.edu.tw'],
            ['town' => '北斗鎮', 'code' => '074714', 'school' => '螺青國小', 'website' => 'rces.chc.edu.tw'],
            ['town' => '北斗鎮', 'code' => '074715', 'school' => '大新國小', 'website' => 'dsses.chc.edu.tw'],
            ['town' => '北斗鎮', 'code' => '074716', 'school' => '螺陽國小', 'website' => 'ryes.chc.edu.tw'],
            ['town' => '田尾鄉', 'code' => '074717', 'school' => '田尾國小', 'website' => 'twps.chc.edu.tw'],
            ['town' => '田尾鄉', 'code' => '074718', 'school' => '南鎮國小', 'website' => 'njes.chc.edu.tw'],
            ['town' => '田尾鄉', 'code' => '074719', 'school' => '陸豐國小', 'website' => 'lfes.chc.edu.tw'],
            ['town' => '田尾鄉', 'code' => '074720', 'school' => '仁豐國小', 'website' => 'rfes.chc.edu.tw'],
            ['town' => '埤頭鄉', 'code' => '074721', 'school' => '埤頭國小', 'website' => 'ptes.chc.edu.tw'],
            ['town' => '埤頭鄉', 'code' => '074722', 'school' => '合興國小', 'website' => 'shses.chc.edu.tw'],
            ['town' => '埤頭鄉', 'code' => '074723', 'school' => '豐崙國小', 'website' => 'fles.chc.edu.tw'],
            ['town' => '埤頭鄉', 'code' => '074724', 'school' => '芙朝國小', 'website' => 'fces.chc.edu.tw'],
            ['town' => '埤頭鄉', 'code' => '074725', 'school' => '中和國小', 'website' => 'ches.chc.edu.tw'],
            ['town' => '埤頭鄉', 'code' => '074726', 'school' => '大湖國小', 'website' => 'dhes.chc.edu.tw'],
            ['town' => '溪州鄉', 'code' => '074727', 'school' => '溪州國小', 'website' => 'sjps.chc.edu.tw'],
            ['town' => '溪州鄉', 'code' => '074728', 'school' => '僑義國小', 'website' => 'cyes.chc.edu.tw'],
            ['town' => '溪州鄉', 'code' => '074729', 'school' => '三條國小', 'website' => 'steps.chc.edu.tw'],
            ['town' => '溪州鄉', 'code' => '074730', 'school' => '水尾國小', 'website' => 'swes.chc.edu.tw'],
            ['town' => '溪州鄉', 'code' => '074731', 'school' => '潮洋國小', 'website' => 'cyps.chc.edu.tw'],
            ['town' => '溪州鄉', 'code' => '074732', 'school' => '成功國小', 'website' => 'cges.chc.edu.tw'],
            ['town' => '溪州鄉', 'code' => '074733', 'school' => '圳寮國小', 'website' => 'jles.chc.edu.tw'],
            ['town' => '溪州鄉', 'code' => '074734', 'school' => '大莊國小', 'website' => 'djps.chc.edu.tw'],
            ['town' => '溪州鄉', 'code' => '074735', 'school' => '南州國小', 'website' => 'njps.chc.edu.tw'],
            ['town' => '二林鎮', 'code' => '074736', 'school' => '二林國小', 'website' => 'elps.chc.edu.tw'],
            ['town' => '二林鎮', 'code' => '074737', 'school' => '興華國小', 'website' => 'shes.chc.edu.tw'],
            ['town' => '二林鎮', 'code' => '074738', 'school' => '中正國小', 'website' => 'ccps.chc.edu.tw'],
            ['town' => '二林鎮', 'code' => '074739', 'school' => '育德國小', 'website' => 'ydes.chc.edu.tw'],
            ['town' => '二林鎮', 'code' => '074740', 'school' => '香田國小', 'website' => 'sstes.chc.edu.tw'],
            ['town' => '二林鎮', 'code' => '074741', 'school' => '廣興國小', 'website' => 'gsps.chc.edu.tw'],
            ['town' => '二林鎮', 'code' => '074742', 'school' => '萬興國小', 'website' => 'wsps.chc.edu.tw'],
            ['town' => '二林鎮', 'code' => '074743', 'school' => '新生國小', 'website' => 'sssps.chc.edu.tw'],
            ['town' => '二林鎮', 'code' => '074744', 'school' => '中興國小', 'website' => 'scses.chc.edu.tw'],
            ['town' => '二林鎮', 'code' => '074537', 'school' => '原斗國中小', 'website' => 'ydps.chc.edu.tw'],
            ['town' => '二林鎮', 'code' => '074746', 'school' => '萬合國小', 'website' => 'whes.chc.edu.tw'],
            ['town' => '大城鄉', 'code' => '074747', 'school' => '大城國小', 'website' => 'dcps.chc.edu.tw'],
            ['town' => '大城鄉', 'code' => '074749', 'school' => '西港國小', 'website' => 'sges.chc.edu.tw'],
            ['town' => '大城鄉', 'code' => '074750', 'school' => '美豐國小', 'website' => 'mfes.chc.edu.tw'],
            ['town' => '竹塘鄉', 'code' => '074753', 'school' => '竹塘國小', 'website' => 'ctes.chc.edu.tw'],
            ['town' => '竹塘鄉', 'code' => '074754', 'school' => '田頭國小', 'website' => 'ttes.chc.edu.tw'],
            ['town' => '竹塘鄉', 'code' => '074756', 'school' => '長安國小', 'website' => 'caes.chc.edu.tw'],
            ['town' => '竹塘鄉', 'code' => '074757', 'school' => '土庫國小', 'website' => 'tkes.chc.edu.tw'],
            ['town' => '芳苑鄉', 'code' => '074758', 'school' => '芳苑國小', 'website' => 'fyes.chc.edu.tw'],
            ['town' => '芳苑鄉', 'code' => '074759', 'school' => '後寮國小', 'website' => 'hles.chc.edu.tw'],
            ['town' => '芳苑鄉', 'code' => '074760', 'school' => '民權國小', 'website' => 'mcws.chc.edu.tw'],
            ['town' => '芳苑鄉', 'code' => '074761', 'school' => '育華國小', 'website' => 'yhes.chc.edu.tw'],
            ['town' => '芳苑鄉', 'code' => '074762', 'school' => '草湖國小', 'website' => 'thes.chc.edu.tw'],
            ['town' => '芳苑鄉', 'code' => '074763', 'school' => '建新國小', 'website' => 'jses.chc.edu.tw'],
            ['town' => '芳苑鄉', 'code' => '074764', 'school' => '漢寶國小', 'website' => 'hbes.chc.edu.tw'],
            ['town' => '芳苑鄉', 'code' => '074765', 'school' => '王功國小', 'website' => 'wges.chc.edu.tw'],
            ['town' => '芳苑鄉', 'code' => '074766', 'school' => '新寶國小', 'website' => 'sbes.chc.edu.tw'],
            ['town' => '芳苑鄉', 'code' => '074767', 'school' => '路上國小', 'website' => 'lses.chc.edu.tw'],
            ['town' => '和美鎮', 'code' => '074769', 'school' => '和仁國小', 'website' => 'hres.chc.edu.tw'],
            ['town' => '鹿港鎮', 'code' => '074771', 'school' => '鹿東國小', 'website' => 'ldes.chc.edu.tw'],
            ['town' => '社頭鄉', 'code' => '074772', 'school' => '舊社國小', 'website' => 'csnes.chc.edu.tw'],
            ['town' => '社頭鄉', 'code' => '074773', 'school' => '崙雅國小', 'website' => 'lyps.chc.edu.tw'],
            ['town' => '彰化市', 'code' => '074775', 'school' => '大成國小', 'website' => 'dches.chc.edu.tw'],
            ['town' => '田中鎮', 'code' => '074776', 'school' => '新民國小', 'website' => 'smps.chc.edu.tw'],
            ['town' => '溪湖鎮', 'code' => '074777', 'school' => '湖北國小', 'website' => 'hbps.chc.edu.tw'],
        ];

        // 3. 使用 Laravel Collection 進行強大的自動分組
        $grouped_schools = collect($raw_schools)->groupBy('town');

        $data = [
            'school3_1'      => $school3_1,
            'school3_2'      => $school3_2,
            'schools_status' => $schools_status,
            'grouped_schools'=> $grouped_schools,
        ];

        return view('chcschool', $data);
    }
}
