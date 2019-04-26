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
        'chcschool.localhost'=>'s074999',
        'www.smes.chc.edu.tw'=>'s074608',
        'www.dches.chc.edu.tw'=>'s074775',
        'www.tces.chc.edu.tw'=>'s074610',
        'www.cses.chc.edu.tw'=>'s074601',
        'www.phes.chc.edu.tw'=>'s074603',
        'www.mses.chc.edu.tw'=>'s074602',
        'www.spes.chc.edu.tw'=>'s074613',
        'www.kges.chc.edu.tw'=>'s074612',
        'wwwtest.jsps.chc.edu.tw'=>'s074614',
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
        'www.smses.chc.edu.tw'=>'s074655',
        'www.hlps.chc.edu.tw'=>'s074656',
        'www.wkes.chc.edu.tw'=>'s074640',
        'www.sdses.chc.edu.tw'=>'s074646',
        'www.ljes.chc.edu.tw'=>'s074641',
        'www.hpes.chc.edu.tw'=>'s074642',
        'www.tges.chc.edu.tw'=>'s074644',
        'wwwtest.dfes.chc.edu.tw'=>'s074645',
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
        'www.ydps.chc.edu.tw'=>'s074745',
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
        'www.ydjh.chc.edu.tw'=>'s074537',
        'www.whjh.chc.edu.tw'=>'s074512',
        'www.tcjhs.chc.edu.tw'=>'s074515',
        'www.fyjhs.chc.edu.tw'=>'s074517',
        'www.thjh.chc.edu.tw'=>'s074516',
        'www.esjh.chc.edu.tw'=>'s074529',
        'www.elsh.chc.edu.tw'=>'s074313',
    ],
];
