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
        $sql = "select distinct(u.brief) from RR r, unit u  where  CONCAT(r.fqdn,'.chc','.edu','.tw') = u.domain and r.rdata = '163.23.200.50';";
        $result=$dbh->query($sql);
        foreach ($result as $row) {
            $schools[$row['brief']]="50";            
            $school3_1++;         
        }

        $sql = "select distinct(u.brief) from RR r, unit u  where  CONCAT(r.fqdn,'.chc','.edu','.tw') = u.domain and r.rdata = '163.23.200.49';";
        $result=$dbh->query($sql);
        foreach ($result as $row) {
            $schools[$row['brief']]="49";            
            $school3_2++;
        }
        dd($schools);
    }

}
