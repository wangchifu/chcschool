<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDO;          // 引入原生的 PDO
use PDOException; // 引入原生的 PDOException

class ChcSchoolController extends Controller
{
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

        return view('chcschool', $data);
    }

    public function chc_air(){    
        $data = [

        ];
        return view('chc_air', $data);
    }

}
