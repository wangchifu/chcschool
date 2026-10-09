<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use App\Sql;

class SwitchDatabaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $mapping = config('tenants.mapping', []);
        
        // 1. 切換資料庫連線
        $dbName = $mapping[$host] ?? config('tenants.default');
        Config::set('database.connections.mysql.database', $dbName);
        DB::purge('mysql');
        DB::reconnect('mysql');

        // 2. 檢查並執行 SQL 升級檔（排除開發/預設域名）
        if ($host !== 'chcschool.localhost:8084' && $host !== 'chcschool.chc.edu.tw') {
            $this->autoMigrateSql();
        }

        return $next($request);
    }

    /**
     * 自動比對並執行未安裝的 SQL 檔案
     */
    private function autoMigrateSql(): void
    {
        $sqls = get_files(database_path('sqls'));
        $installSqls = Sql::where('install', 1)->pluck('name')->toArray();

        foreach ($sqls as $v) {
            if (!in_array($v, $installSqls)) {
                $file = database_path('sqls') . '/' . $v;
                
                if (file_exists($file)) {
                    DB::unprepared(file_get_contents($file));
                    
                    Sql::create([
                        'name' => $v,
                        'install' => 1,
                    ]);
                }
            }
        }
    }
}