<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */

    'name' => env('APP_NAME', 'Laravel'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services the application utilizes. Set this in your ".env" file.
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | your application so that it is used when running Artisan tasks.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. We have gone
    | ahead and set this to a sensible default for you out of the box.
    |
    */

    'timezone' => 'Asia/Taipei',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */

    'locale' => 'zh-TW',

    /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use when the current one
    | is not available. You may change the value to correspond to any of
    | the language folders that are provided through your application.
    |
    */

    'fallback_locale' => 'zh-TW',

    /*
    |--------------------------------------------------------------------------
    | Faker Locale
    |--------------------------------------------------------------------------
    |
    | This locale will be used by the Faker PHP library when generating fake
    | data for your database seeds. For example, this will be used to get
    | localized telephone numbers, street address information and more.
    |
    */

    'faker_locale' => 'en_US',

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is used by the Illuminate encrypter service and should be set
    | to a random, 32 character string, otherwise these encrypted strings
    | will not be safe. Please do this before deploying an application!
    |
    */

    'key' => env('APP_KEY'),

    'cipher' => 'AES-256-CBC',

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => [

        /*
         * Laravel Framework Service Providers...
         */
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Notifications\NotificationServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,

        /*
         * Package Service Providers...
         */

        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        // App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,

    ],

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */

    'aliases' => [

        'App' => Illuminate\Support\Facades\App::class,
        'Artisan' => Illuminate\Support\Facades\Artisan::class,
        'Auth' => Illuminate\Support\Facades\Auth::class,
        'Blade' => Illuminate\Support\Facades\Blade::class,
        'Broadcast' => Illuminate\Support\Facades\Broadcast::class,
        'Bus' => Illuminate\Support\Facades\Bus::class,
        'Cache' => Illuminate\Support\Facades\Cache::class,
        'Config' => Illuminate\Support\Facades\Config::class,
        'Cookie' => Illuminate\Support\Facades\Cookie::class,
        'Crypt' => Illuminate\Support\Facades\Crypt::class,
        'DB' => Illuminate\Support\Facades\DB::class,
        'Eloquent' => Illuminate\Database\Eloquent\Model::class,
        'Event' => Illuminate\Support\Facades\Event::class,
        'File' => Illuminate\Support\Facades\File::class,
        'Gate' => Illuminate\Support\Facades\Gate::class,
        'Hash' => Illuminate\Support\Facades\Hash::class,
        'Lang' => Illuminate\Support\Facades\Lang::class,
        'Log' => Illuminate\Support\Facades\Log::class,
        'Mail' => Illuminate\Support\Facades\Mail::class,
        'Notification' => Illuminate\Support\Facades\Notification::class,
        'Password' => Illuminate\Support\Facades\Password::class,
        'Queue' => Illuminate\Support\Facades\Queue::class,
        'Redirect' => Illuminate\Support\Facades\Redirect::class,
        'Redis' => Illuminate\Support\Facades\Redis::class,
        'Request' => Illuminate\Support\Facades\Request::class,
        'Response' => Illuminate\Support\Facades\Response::class,
        'Route' => Illuminate\Support\Facades\Route::class,
        'Schema' => Illuminate\Support\Facades\Schema::class,
        'Session' => Illuminate\Support\Facades\Session::class,
        'Storage' => Illuminate\Support\Facades\Storage::class,
        'URL' => Illuminate\Support\Facades\URL::class,
        'Validator' => Illuminate\Support\Facades\Validator::class,
        'View' => Illuminate\Support\Facades\View::class,
    ],
    'database'=>[
        //網址轉換資料庫代碼
        //'localhost:8888'=>'s074600',
        'chcschool.localhost'=>'s074999',
        //'web.chc.edu.tw'=>'s079998',
        //'www.chc.edu.tw'=>'s079998',
        //'www.chsport.chc.edu.tw'=>'s079999',        
        'wwwtest.hdes.chc.edu.tw'=>'s074000',
        'www1.hdes.chc.edu.tw'=>'s074001',
        'www2.hdes.chc.edu.tw'=>'s074002',
        'www3.hdes.chc.edu.tw'=>'s074003',
        'www4.hdes.chc.edu.tw'=>'s074004',
        'www5.hdes.chc.edu.tw'=>'s074005',
        'www6.hdes.chc.edu.tw'=>'s074006',
        'www7.hdes.chc.edu.tw'=>'s074007',
        'www8.hdes.chc.edu.tw'=>'s074008',
        'www9.hdes.chc.edu.tw'=>'s074009',
        'www10.hdes.chc.edu.tw'=>'s074010',
        'www11.hdes.chc.edu.tw'=>'s074011',
        'www12.hdes.chc.edu.tw'=>'s074012',
        'www13.hdes.chc.edu.tw'=>'s074013',
        'www14.hdes.chc.edu.tw'=>'s074014',
        'www15.hdes.chc.edu.tw'=>'s074015',
        'www16.hdes.chc.edu.tw'=>'s074016',
        'www17.hdes.chc.edu.tw'=>'s074017',
        'www18.hdes.chc.edu.tw'=>'s074018',
        'www19.hdes.chc.edu.tw'=>'s074019',
        'www20.hdes.chc.edu.tw'=>'s074020',
        'www21.hdes.chc.edu.tw'=>'s074021',
        'www22.hdes.chc.edu.tw'=>'s074022',
        'www23.hdes.chc.edu.tw'=>'s074023',
        'www24.hdes.chc.edu.tw'=>'s074024',
        'www25.hdes.chc.edu.tw'=>'s074025',
        'www26.hdes.chc.edu.tw'=>'s074026',
        'www27.hdes.chc.edu.tw'=>'s074027',
        'www28.hdes.chc.edu.tw'=>'s074028',
        'www29.hdes.chc.edu.tw'=>'s074029',
        'www30.hdes.chc.edu.tw'=>'s074030',
        'www31.hdes.chc.edu.tw'=>'s074031',
        'www32.hdes.chc.edu.tw'=>'s074032',
        'www33.hdes.chc.edu.tw'=>'s074033',
        'www34.hdes.chc.edu.tw'=>'s074034',
        'www35.hdes.chc.edu.tw'=>'s074035',
        'www36.hdes.chc.edu.tw'=>'s074036',
        'www37.hdes.chc.edu.tw'=>'s074037',
        'www38.hdes.chc.edu.tw'=>'s074038',
        'www39.hdes.chc.edu.tw'=>'s074039',
        'www40.hdes.chc.edu.tw'=>'s074040',
        'www.chash.chc.edu.tw'=>'s074308',
        'www.smes.chc.edu.tw'=>'s074608',
        'www.dches.chc.edu.tw'=>'s074775',
        'www.tces.chc.edu.tw'=>'s074610',
        'www.cses.chc.edu.tw'=>'s074601',
        'www.phes.chc.edu.tw'=>'s074603',
        'www.mses.chc.edu.tw'=>'s074602',
        'www.spes.chc.edu.tw'=>'s074613',
        'www.kges.chc.edu.tw'=>'s074612',
        'www.jsps.chc.edu.tw'=>'s074614',
        'www.tfps.chc.edu.tw'=>'s074606',
        'www.nges.chc.edu.tw'=>'s074604',
        'www.nses.chc.edu.tw'=>'s074605',
        'www.thps.chc.edu.tw'=>'s074607',
        'www.gses.chc.edu.tw'=>'s074611',
        'www.lsps.chc.edu.tw'=>'s074609',
        'www.wdes.chc.edu.tw'=>'s074619',
        'www.taes.chc.edu.tw'=>'s074618',
        'www.fyps.chc.edu.tw'=>'s074615',
        'www.cles.chc.edu.tw'=>'s074620',
        'www.fsps.chc.edu.tw'=>'s074616',
        'www.bses.chc.edu.tw'=>'s074617',
        'www.sstps.chc.edu.tw'=>'s074625',
        'www.wses.chc.edu.tw'=>'s074622',
        'www.bsps.chc.edu.tw'=>'s074626',
        'www.htes.chc.edu.tw'=>'s074621',
        'www.hnes.chc.edu.tw'=>'s074623',
        'www.caps.chc.edu.tw'=>'s074624',
        'www.hses.chc.edu.tw'=>'s074654',
        'www.ymes.chc.edu.tw'=>'s074659',
	    'www.mcps.chc.edu.tw'=>'s074657',
	    'www.ssps.chc.edu.tw'=>'s074658',
        'www.smses.chc.edu.tw'=>'s074655',
        'www.hlps.chc.edu.tw'=>'s074656',
        'www.wkes.chc.edu.tw'=>'s074640',
        'www.sdses.chc.edu.tw'=>'s074646',
        'www.ljes.chc.edu.tw'=>'s074641',
        'www.hpes.chc.edu.tw'=>'s074642',
        'www.tges.chc.edu.tw'=>'s074644',
        'www.dfes.chc.edu.tw'=>'s074645',
        'www.ldes.chc.edu.tw'=>'s074771',
        'www.lges.chc.edu.tw'=>'s074639',
        'www.bsses.chc.edu.tw'=>'s074643',
        'www.bdsps.chc.edu.tw'=>'s074650',
        'www.wces.chc.edu.tw'=>'s074648',
        'www.rses.chc.edu.tw'=>'s074652',
        'www.yfes.chc.edu.tw'=>'s074651',
        'www.ssses.chc.edu.tw'=>'s074649',
        'www.yses.chc.edu.tw'=>'s074653',
        'www.gyes.chc.edu.tw'=>'s074647',
        'www.sces.chc.edu.tw'=>'s074633',
        'www.syes.chc.edu.tw'=>'s074634',
        'www.dces.chc.edu.tw'=>'s074629',
        'www.dres.chc.edu.tw'=>'s074630',
        'www.hres.chc.edu.tw'=>'s074769',
        'www.hdes.chc.edu.tw'=>'s074628',
        'wwwtwo.hdes.chc.edu.tw'=>'s074628_2',
        //'xn--cjrw7a18c01hqxel3o7v2b.tw'=>'s074628',
        'www.hmps.chc.edu.tw'=>'s074627',
        'www.pyps.chc.edu.tw'=>'s074632',
        'www.ssjes.chc.edu.tw'=>'s074631',
        'www.dtes.chc.edu.tw'=>'s074638',
        'www.sres.chc.edu.tw'=>'s074637',
        'www.sdes.chc.edu.tw'=>'s074636',
        'www.sgps.chc.edu.tw'=>'s074635',
        'www.yyes.chc.edu.tw'=>'s074681',
        'www.mhes.chc.edu.tw'=>'s074688',
        'www.dsps.chc.edu.tw'=>'s074686',
        'www.chcses.chc.edu.tw'=>'s074687',
        'www.ytes.chc.edu.tw'=>'s074684',
        'www.ylps.chc.edu.tw'=>'s074680',
        'www.csps.chc.edu.tw'=>'s074683',
        'www.sjses.chc.edu.tw'=>'s074682',
        'www.rmes.chc.edu.tw'=>'s074685',
        'www.stps.chc.edu.tw'=>'s074704',
        'www.lyps.chc.edu.tw'=>'s074773',
        'www.bcses.chc.edu.tw'=>'s074707',
        'www.scsps.chc.edu.tw'=>'s074706',
        'www.nyes.chc.edu.tw'=>'s074708',
        'www.ctps.chc.edu.tw'=>'s074705',
        'www.csnes.chc.edu.tw'=>'s074772',
        'www.yces.chc.edu.tw'=>'s074693',
        'www.ysps.chc.edu.tw'=>'s074695',
        'www.fdps.chc.edu.tw'=>'s074694',
        'www.sfses.chc.edu.tw'=>'s074696',
        'www.sdsps.chc.edu.tw'=>'s074697',
        'www.tpes.chc.edu.tw'=>'s074674',
        'www.msps.chc.edu.tw'=>'s074679',
        'www.pses.chc.edu.tw'=>'s074673',
        'www.wfes.chc.edu.tw'=>'s074678',
        'www.sfsps.chc.edu.tw'=>'s074677',
        'www.jges.chc.edu.tw'=>'s074675',
        'www.rtes.chc.edu.tw'=>'s074676',
        'www.bdses.chc.edu.tw'=>'s074661',
        'www.hbps.chc.edu.tw'=>'s074777',
        'www.fses.chc.edu.tw'=>'s074662',
        'www.fdes.chc.edu.tw'=>'s074663',
        'www.hnps.chc.edu.tw'=>'s074664',
        'www.mtes.chc.edu.tw'=>'s074665',
        'www.shps.chc.edu.tw'=>'s074660',
        'www.dses.chc.edu.tw'=>'s074690',
        'www.dtps.chc.edu.tw'=>'s074689',
        'www.tsps.chc.edu.tw'=>'s074691',
        'www.tdes.chc.edu.tw'=>'s074692',
        'www.dyes.chc.edu.tw'=>'s074667',
        'www.tses.chc.edu.tw'=>'s074672',
        'www.yles.chc.edu.tw'=>'s074670',
        'www.hsps.chc.edu.tw'=>'s074669',
        'www.ngps.chc.edu.tw'=>'s074668',
        'www.pyes.chc.edu.tw'=>'s074666',
        'www.sses.chc.edu.tw'=>'s074671',
        'www.stes.chc.edu.tw'=>'s074699',
        'www.daes.chc.edu.tw'=>'s074700',
        'www.naes.chc.edu.tw'=>'s074701',
        'www.tjes.chc.edu.tw'=>'s074698',
        'www.mles.chc.edu.tw'=>'s074703',
        'www.dhps.chc.edu.tw'=>'s074702',
        'www.smps.chc.edu.tw'=>'s074776',
        'www.dsses.chc.edu.tw'=>'s074715',
        'www.bdes.chc.edu.tw'=>'s074712',
        'www.wles.chc.edu.tw'=>'s074713',
        'www.rces.chc.edu.tw'=>'s074714',
        'www.ryes.chc.edu.tw'=>'s074716',
        'www.rfes.chc.edu.tw'=>'s074720',
        'www.twps.chc.edu.tw'=>'s074717',
        'www.njes.chc.edu.tw'=>'s074718',
        'www.lfes.chc.edu.tw'=>'s074719',
        'www.dhes.chc.edu.tw'=>'s074726',
        'www.ches.chc.edu.tw'=>'s074725',
        'www.shses.chc.edu.tw'=>'s074722',
        'www.fces.chc.edu.tw'=>'s074724',
        'www.ptes.chc.edu.tw'=>'s074721',
        'www.fles.chc.edu.tw'=>'s074723',
        'www.steps.chc.edu.tw'=>'s074729',
        'www.djps.chc.edu.tw'=>'s074734',
        'www.swes.chc.edu.tw'=>'s074730',
        'www.jles.chc.edu.tw'=>'s074733',
        'www.cges.chc.edu.tw'=>'s074732',
        'www.njps.chc.edu.tw'=>'s074735',
        'www.sjps.chc.edu.tw'=>'s074727',
        'www.cyes.chc.edu.tw'=>'s074728',
        'www.cyps.chc.edu.tw'=>'s074731',
        'www.tkes.chc.edu.tw'=>'s074757',
        'www.mjes.chc.edu.tw'=>'s074755',
        'www.ttes.chc.edu.tw'=>'s074754',
        'www.ctes.chc.edu.tw'=>'s074753',
        'www.caes.chc.edu.tw'=>'s074756',
        'www.elps.chc.edu.tw'=>'s074736',
        'www.ccps.chc.edu.tw'=>'s074738',
        'www.scses.chc.edu.tw'=>'s074744',
        'www.ydes.chc.edu.tw'=>'s074739',
        'www.sstes.chc.edu.tw'=>'s074740',
        //'www.ydps.chc.edu.tw'=>'s074745',
        'www.ydps.chc.edu.tw'=>'s074537',//原斗國中小用074537(原國中)，網址用原國小
        'www.sssps.chc.edu.tw'=>'s074743',
        'www.whes.chc.edu.tw'=>'s074746',
        'www.wsps.chc.edu.tw'=>'s074742',
        'www.gsps.chc.edu.tw'=>'s074741',
        'www.shes.chc.edu.tw'=>'s074737',
        'www.dcps.chc.edu.tw'=>'s074747',
        'www.yges.chc.edu.tw'=>'s074748',
        'www.sges.chc.edu.tw'=>'s074749',
        'www.mfes.chc.edu.tw'=>'s074750',
        'www.djes.chc.edu.tw'=>'s074751',
        'www.tcps.chc.edu.tw'=>'s074752',
        'www.wges.chc.edu.tw'=>'s074765',
        'www.mces.chc.edu.tw'=>'s074760',
        'www.mcws.chc.edu.tw'=>'s074760',
        'www.yhes.chc.edu.tw'=>'s074761',
        'www.fyes.chc.edu.tw'=>'s074758',
        'www.jses.chc.edu.tw'=>'s074763',
        'www.hles.chc.edu.tw'=>'s074759',
        'www.thes.chc.edu.tw'=>'s074762',
        'www.sbes.chc.edu.tw'=>'s074766',
        'www.lses.chc.edu.tw'=>'s074767',
        'www.hbes.chc.edu.tw'=>'s074764',
        'www.eses.chc.edu.tw'=>'s074709',
        'www.fsses.chc.edu.tw'=>'s074710',
        'www.ycps.chc.edu.tw'=>'s074711',
        'www.spps.chc.edu.tw'=>'s074732',
        'www.hyjhes.chc.edu.tw'=>'s074541',
        'www.ymsc.chc.edu.tw'=>'s074505',
        'www.cajh.chc.edu.tw'=>'s074506',
        'www.ctsjh.chc.edu.tw'=>'s074540',
        'www.ctjh.chc.edu.tw'=>'s074507',
        'www.csjh.chc.edu.tw'=>'s074538',
        'www.fyjh.chc.edu.tw'=>'s074509',
        'www.htjh.chc.edu.tw'=>'s074526',
        'www.hsjh.chc.edu.tw'=>'s074522',
        'www.lmjh.chc.edu.tw'=>'s074503',
        'www.lkjh.chc.edu.tw'=>'s074502',
        'www.ljis.chc.edu.tw'=>'s074542',
        'www.fsjh.chc.edu.tw'=>'s074521',
        'www.hhjh.chc.edu.tw'=>'s074504',
        'www.hmjh.chc.edu.tw'=>'s074323',
        'www.hcjh.chc.edu.tw'=>'s074535',
        'www.skjh.chc.edu.tw'=>'s074524',
        'www.ttjhs.chc.edu.tw'=>'s074536',
        'www.mljh.chc.edu.tw'=>'s074511',
        'www.yljh.chc.edu.tw'=>'s074510',
        'www.stjh.chc.edu.tw'=>'s074530',
        'www.ycjh.chc.edu.tw'=>'s074527',
        'www.psjh.chc.edu.tw'=>'s074520',
        'www.ckjh.chc.edu.tw'=>'s074339',
        'www.cksh.chc.edu.tw'=>'s074339',
        'www.cfjh.chc.edu.tw'=>'s074518',
        'www.ttjh.chc.edu.tw'=>'s074525',
        'www.pyjh.chc.edu.tw'=>'s074519',
        'www.tcjh.chc.edu.tw'=>'s074328',
        'www.ptjhs.chc.edu.tw'=>'s074501',
        'www.twjh.chc.edu.tw'=>'s074531',
        'www.ptjh.chc.edu.tw'=>'s074534',
        'www.ccjh.chc.edu.tw'=>'s074532',
        'www.hyjh.chc.edu.tw'=>'s074533',
        'www.ctjhs.chc.edu.tw'=>'s074514',
        //'www.ydjh.chc.edu.tw'=>'s074537',//原斗國中小用074537(原國中)，網址用原國小
        'www.whjh.chc.edu.tw'=>'s074512',
        'www.tcjhs.chc.edu.tw'=>'s074515',
        'www.fyjhs.chc.edu.tw'=>'s074517',
        'www.thjh.chc.edu.tw'=>'s074516',
        'www.esjh.chc.edu.tw'=>'s074529',
        'www.elsh.chc.edu.tw'=>'s074313',
        //'web.smes.chc.edu.tw'=>'s074608',
        //'web.dches.chc.edu.tw'=>'s074775',
        //'web.tces.chc.edu.tw'=>'s074610',
        //'web.cses.chc.edu.tw'=>'s074601',
        //'web.phes.chc.edu.tw'=>'s074603',
        //'web.mses.chc.edu.tw'=>'s074602',
        //'web.spes.chc.edu.tw'=>'s074613',
        //'web.kges.chc.edu.tw'=>'s074612',
        //'web.jsps.chc.edu.tw'=>'s074614',
        //'web.tfps.chc.edu.tw'=>'s074606',
        //'web.nges.chc.edu.tw'=>'s074604',
        //'web.nses.chc.edu.tw'=>'s074605',
        //'web.thps.chc.edu.tw'=>'s074607',
        //'web.gses.chc.edu.tw'=>'s074611',
        //'web.lsps.chc.edu.tw'=>'s074609',
        //'web.wdes.chc.edu.tw'=>'s074619',
        //'web.taes.chc.edu.tw'=>'s074618',
        //'web.fyps.chc.edu.tw'=>'s074615',
        //'web.cles.chc.edu.tw'=>'s074620',
        //'web.fsps.chc.edu.tw'=>'s074616',
        //'web.bses.chc.edu.tw'=>'s074617',
        //'web.sstps.chc.edu.tw'=>'s074625',
        //'web.wses.chc.edu.tw'=>'s074622',
        //'web.bsps.chc.edu.tw'=>'s074626',
        //'web.htes.chc.edu.tw'=>'s074621',
        //'web.hnes.chc.edu.tw'=>'s074623',
        //'web.caps.chc.edu.tw'=>'s074624',
        //'web.hses.chc.edu.tw'=>'s074654',
        //'web.ymes.chc.edu.tw'=>'s074659',
        //'web.mcps.chc.edu.tw'=>'s074657',
        //'web.smses.chc.edu.tw'=>'s074655',
        //'web.hlps.chc.edu.tw'=>'s074656',
        //'web.wkes.chc.edu.tw'=>'s074640',
        //'web.sdses.chc.edu.tw'=>'s074646',
        //'web.ljes.chc.edu.tw'=>'s074641',
        //'web.hpes.chc.edu.tw'=>'s074642',
        //'web.tges.chc.edu.tw'=>'s074644',
        //'web.dfes.chc.edu.tw'=>'s074645',
        //'web.ldes.chc.edu.tw'=>'s074771',
        //'web.lges.chc.edu.tw'=>'s074639',
        //'web.bsses.chc.edu.tw'=>'s074643',
        //'web.bdsps.chc.edu.tw'=>'s074650',
        //'web.wces.chc.edu.tw'=>'s074648',
        //'web.rses.chc.edu.tw'=>'s074652',
        //'web.yfes.chc.edu.tw'=>'s074651',
        //'web.ssses.chc.edu.tw'=>'s074649',
        //'web.yses.chc.edu.tw'=>'s074653',
        //'web.gyes.chc.edu.tw'=>'s074647',
        //'web.sces.chc.edu.tw'=>'s074633',
        //'web.syes.chc.edu.tw'=>'s074634',
        //'web.dces.chc.edu.tw'=>'s074629',
        //'web.dres.chc.edu.tw'=>'s074630',
        //'web.hres.chc.edu.tw'=>'s074769',
        //'web.hdes.chc.edu.tw'=>'s074628',
        //'web.hmps.chc.edu.tw'=>'s074627',
        //'web.pyps.chc.edu.tw'=>'s074632',
        //'web.ssjes.chc.edu.tw'=>'s074631',
        //'web.dtes.chc.edu.tw'=>'s074638',
        //'web.sres.chc.edu.tw'=>'s074637',
        //'web.sdes.chc.edu.tw'=>'s074636',
        //'web.sgps.chc.edu.tw'=>'s074635',
        //'web.yyes.chc.edu.tw'=>'s074681',
        //'web.mhes.chc.edu.tw'=>'s074688',
        //'web.dsps.chc.edu.tw'=>'s074686',
        //'web.chcses.chc.edu.tw'=>'s074687',
        //'web.ytes.chc.edu.tw'=>'s074684',
        //'web.ylps.chc.edu.tw'=>'s074680',
        //'web.csps.chc.edu.tw'=>'s074683',
        //'web.sjses.chc.edu.tw'=>'s074682',
        //'web.rmes.chc.edu.tw'=>'s074685',
        //'web.stps.chc.edu.tw'=>'s074704',
        //'web.lyps.chc.edu.tw'=>'s074773',
        //'web.bcses.chc.edu.tw'=>'s074707',
        //'web.scsps.chc.edu.tw'=>'s074706',
        //'web.nyes.chc.edu.tw'=>'s074708',
        //'web.ctps.chc.edu.tw'=>'s074705',
        //'web.csnes.chc.edu.tw'=>'s074772',
        //'web.yces.chc.edu.tw'=>'s074693',
        //'web.ysps.chc.edu.tw'=>'s074695',
        //'web.fdps.chc.edu.tw'=>'s074694',
        //'web.sfses.chc.edu.tw'=>'s074696',
        //'web.sdsps.chc.edu.tw'=>'s074697',
        //'web.tpes.chc.edu.tw'=>'s074674',
        //'web.msps.chc.edu.tw'=>'s074679',
        //'web.pses.chc.edu.tw'=>'s074673',
        //'web.wfes.chc.edu.tw'=>'s074678',
        //'web.sfsps.chc.edu.tw'=>'s074677',
        //'web.jges.chc.edu.tw'=>'s074675',
        //'web.rtes.chc.edu.tw'=>'s074676',
        //'web.bdses.chc.edu.tw'=>'s074661',
        //'web.hbps.chc.edu.tw'=>'s074777',
        //'web.fses.chc.edu.tw'=>'s074662',
        //'web.fdes.chc.edu.tw'=>'s074663',
        //'web.hnps.chc.edu.tw'=>'s074664',
        //'web.mtes.chc.edu.tw'=>'s074665',
        //'web.shps.chc.edu.tw'=>'s074660',
        //'web.dses.chc.edu.tw'=>'s074690',
        //'web.dtps.chc.edu.tw'=>'s074689',
        //'web.tsps.chc.edu.tw'=>'s074691',
        //'web.tdes.chc.edu.tw'=>'s074692',
        //'web.dyes.chc.edu.tw'=>'s074667',
        //'web.tses.chc.edu.tw'=>'s074672',
        //'web.yles.chc.edu.tw'=>'s074670',
        //'web.hsps.chc.edu.tw'=>'s074669',
        //'web.ngps.chc.edu.tw'=>'s074668',
        //'web.pyes.chc.edu.tw'=>'s074666',
        //'web.sses.chc.edu.tw'=>'s074671',
        //'web.stes.chc.edu.tw'=>'s074699',
        //'web.daes.chc.edu.tw'=>'s074700',
        //'web.naes.chc.edu.tw'=>'s074701',
        //'web.tjes.chc.edu.tw'=>'s074698',
        //'web.mles.chc.edu.tw'=>'s074703',
        //'web.dhps.chc.edu.tw'=>'s074702',
        //'web.smps.chc.edu.tw'=>'s074776',
        //'web.dsses.chc.edu.tw'=>'s074715',
        //'web.bdes.chc.edu.tw'=>'s074712',
        //'web.wles.chc.edu.tw'=>'s074713',
        //'web.rces.chc.edu.tw'=>'s074714',
        //'web.ryes.chc.edu.tw'=>'s074716',
        //'web.rfes.chc.edu.tw'=>'s074720',
        'web.twps.chc.edu.tw'=>'s074717',//田尾國小有在用
        //'web.njes.chc.edu.tw'=>'s074718',
        //'web.lfes.chc.edu.tw'=>'s074719',
        //'web.dhes.chc.edu.tw'=>'s074726',
        //'web.ches.chc.edu.tw'=>'s074725',
        //'web.shses.chc.edu.tw'=>'s074722',
        //'web.fces.chc.edu.tw'=>'s074724',
        //'web.ptes.chc.edu.tw'=>'s074721',
        //'web.fles.chc.edu.tw'=>'s074723',
        //'web.steps.chc.edu.tw'=>'s074729',
        //'web.djps.chc.edu.tw'=>'s074734',
        //'web.swes.chc.edu.tw'=>'s074730',
        //'web.jles.chc.edu.tw'=>'s074733',
        //'web.cges.chc.edu.tw'=>'s074732',
        //'web.njps.chc.edu.tw'=>'s074735',
        //'web.sjps.chc.edu.tw'=>'s074727',
        //'web.cyes.chc.edu.tw'=>'s074728',
        //'web.cyps.chc.edu.tw'=>'s074731',
        //'web.tkes.chc.edu.tw'=>'s074757',
        //'web.mjes.chc.edu.tw'=>'s074755',
        //'web.ttes.chc.edu.tw'=>'s074754',
        //'web.ctes.chc.edu.tw'=>'s074753',
        //'web.caes.chc.edu.tw'=>'s074756',
        //'web.elps.chc.edu.tw'=>'s074736',
        //'web.ccps.chc.edu.tw'=>'s074738',
        //'web.scses.chc.edu.tw'=>'s074744',
        //'web.ydes.chc.edu.tw'=>'s074739',
        //'web.sstes.chc.edu.tw'=>'s074740',
        //'web.ydps.chc.edu.tw'=>'s074745',
        //'web.sssps.chc.edu.tw'=>'s074743',
        //'web.whes.chc.edu.tw'=>'s074746',
        //'web.wsps.chc.edu.tw'=>'s074742',
        //'web.gsps.chc.edu.tw'=>'s074741',
        //'web.shes.chc.edu.tw'=>'s074737',
        //'web.dcps.chc.edu.tw'=>'s074747',
        //'web.yges.chc.edu.tw'=>'s074748',
        //'web.sges.chc.edu.tw'=>'s074749',
        //'web.mfes.chc.edu.tw'=>'s074750',
        //'web.djes.chc.edu.tw'=>'s074751',
        //'web.tcps.chc.edu.tw'=>'s074752',
        //'web.wges.chc.edu.tw'=>'s074765',
        //'web.mces.chc.edu.tw'=>'s074760',
        //'web.yhes.chc.edu.tw'=>'s074761',
        //'web.fyes.chc.edu.tw'=>'s074758',
        //'web.jses.chc.edu.tw'=>'s074763',
        //'web.hles.chc.edu.tw'=>'s074759',
        //'web.thes.chc.edu.tw'=>'s074762',
        //'web.sbes.chc.edu.tw'=>'s074766',
        //'web.lses.chc.edu.tw'=>'s074767',
        //'web.hbes.chc.edu.tw'=>'s074764',
        //'web.eses.chc.edu.tw'=>'s074709',
        //'web.fsses.chc.edu.tw'=>'s074710',
        //'web.ycps.chc.edu.tw'=>'s074711',
        //'web.spps.chc.edu.tw'=>'s074732',
        //'web.hyjhes.chc.edu.tw'=>'s074541',
        //'web.ymsc.chc.edu.tw'=>'s074505',
        //'web.cajh.chc.edu.tw'=>'s074506',
        //'web.ctsjh.chc.edu.tw'=>'s074540',
        //'web.ctjh.chc.edu.tw'=>'s074507',
        //'web.csjh.chc.edu.tw'=>'s074538',
        //'web.fyjh.chc.edu.tw'=>'s074509',
        //'web.htjh.chc.edu.tw'=>'s074526',
        //'web.hsjh.chc.edu.tw'=>'s074522',
        //'web.lmjh.chc.edu.tw'=>'s074503',
        //'web.lkjh.chc.edu.tw'=>'s074502',
        //'web.ljis.chc.edu.tw'=>'s074542',
        //'web.fsjh.chc.edu.tw'=>'s074521',
        //'web.hhjh.chc.edu.tw'=>'s074504',
        //'web.hmjh.chc.edu.tw'=>'s074323',
        //'web.hcjh.chc.edu.tw'=>'s074535',
        //'web.skjh.chc.edu.tw'=>'s074524',
        //'web.ttjhs.chc.edu.tw'=>'s074536',
        //'web.mljh.chc.edu.tw'=>'s074511',
        //'web.yljh.chc.edu.tw'=>'s074510',
        //'web.stjh.chc.edu.tw'=>'s074530',
        //'web.ycjh.chc.edu.tw'=>'s074527',
        //'web.psjh.chc.edu.tw'=>'s074520',
        //'web.ckjh.chc.edu.tw'=>'s074339',
        //'web.cfjh.chc.edu.tw'=>'s074518',
        //'web.ttjh.chc.edu.tw'=>'s074525',
        //'web.pyjh.chc.edu.tw'=>'s074519',
        //'web.tcjh.chc.edu.tw'=>'s074328',
        //'web.ptjhs.chc.edu.tw'=>'s074501',
        //'web.twjh.chc.edu.tw'=>'s074531',
        //'web.ptjh.chc.edu.tw'=>'s074534',
        //'web.ccjh.chc.edu.tw'=>'s074532',
        //'web.hyjh.chc.edu.tw'=>'s074533',
        //'web.ctjhs.chc.edu.tw'=>'s074514',
        //'web.ydjh.chc.edu.tw'=>'s074537',
        //'web.whjh.chc.edu.tw'=>'s074512',
        //'web.tcjhs.chc.edu.tw'=>'s074515',
        //'web.fyjhs.chc.edu.tw'=>'s074517',
        //'web.thjh.chc.edu.tw'=>'s074516',
        //'web.esjh.chc.edu.tw'=>'s074529',
        //'web.elsh.chc.edu.tw'=>'s074313',
    ],
];
