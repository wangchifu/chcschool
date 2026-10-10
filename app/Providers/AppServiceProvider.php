<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);        
        // 💡 在非 CLI (網頁請求) 環境下，動態將學校代碼帶入 lfm 的 base_directory
        if (!app()->runningInConsole()) {
            $schoolCode = school_code();

            if ($schoolCode) {
                config([
                    'lfm.base_directory' => 'storage/app/public/' . $schoolCode,
                ]);
            }
        }      
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

}
