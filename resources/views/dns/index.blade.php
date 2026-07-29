@extends('layouts.master')

@section('nav_setup_active', 'active')

@section('title', 'DNS 網域設定 | ')

@section('content')        
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h1>
                DNS 網域設定(測試中,請勿使用)
            </h1>                       
            <div class="container-fluid">
                
                <!-- 頂部麵包屑導覽列 -->
                <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 mb-3">
                    <li class="breadcrumb-item"><a href="#">DNS管理首頁</a></li>
                    <li class="breadcrumb-item"><a href="#">DNS設定管理</a></li>
                    <li class="breadcrumb-item active" aria-current="page">管理網域 <strong class="text-primary">{{ $domainWithoutWww }}</strong></li>
                </ol>
                </nav>

                <!-- 右上角 [+新增記錄] -->
                <div class="text-right mb-2">
                    <a href="javascript:open_window('{{ route('school_dns.create') }}','新視窗')" class="btn btn-success">
                        <i class="fas fa-plus"></i> 新增記錄
                    </a>                
                </div>

                <!-- DNS 資料表格（外框使用 border-primary） -->
                <div class="border border-primary">
                    <table class="table table-hover table-bordered mb-0">
                        <thead>
                            <tr class="table-primary">
                                <!-- 表頭 -->
                                <th scope="col" style="width: 12%;">記錄名稱</th>
                                <th scope="col" style="width: 10%;">類型</th>
                                <th scope="col" style="width: 8%;">逾時</th>
                                <th scope="col" style="width: 50%;">記錄內容</th>                        
                                <th scope="col" style="width: 20%;">管理</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>@</td>
                                <td>MX</td>
                                <td>38400</td>
                                <td>10 ASPMX.L.GOOGLE.COM.</td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-outline-primary">測試</button>
                                        <button type="button" class="btn btn-outline-secondary">註解</button>
                                        <button type="button" class="btn btn-outline-danger">刪</button>
                                    </div>
                                </td>
                            </tr>
                            <tr class="table-success">
                                <td>@</td>
                                <td>MX</td>
                                <td>38400</td>
                                <td>20 ALT1.ASPMX.L.GOOGLE.COM.</td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-outline-primary">測試</button>
                                        <button type="button" class="btn btn-outline-secondary">註解</button>
                                        <button type="button" class="btn btn-outline-danger">刪</button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>@</td>
                                <td>MX</td>
                                <td>38400</td>
                                <td>20 ALT2.ASPMX.L.GOOGLE.COM.</td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-outline-primary">測試</button>
                                        <button type="button" class="btn btn-outline-secondary">註解</button>
                                        <button type="button" class="btn btn-outline-danger">刪</button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>@</td>
                                <td>MX</td>
                                <td>38400</td>
                                <td>30 ASPMX2.GOOGLEMAIL.COM.</td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-outline-primary">測試</button>
                                        <button type="button" class="btn btn-outline-secondary">註解</button>
                                        <button type="button" class="btn btn-outline-danger">刪</button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>@</td>
                                <td>MX</td>
                                <td>38400</td>
                                <td>30 ASPMX3.GOOGLEMAIL.COM.</td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-outline-primary">測試</button>
                                        <button type="button" class="btn btn-outline-secondary">註解</button>
                                        <button type="button" class="btn btn-outline-danger">刪</button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>@</td>
                                <td>MX</td>
                                <td>38400</td>
                                <td>30 ASPMX4.GOOGLEMAIL.COM.</td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-outline-primary">測試</button>
                                        <button type="button" class="btn btn-outline-secondary">註解</button>
                                        <button type="button" class="btn btn-outline-danger">刪</button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>@</td>
                                <td>MX</td>
                                <td>38400</td>
                                <td>30 ASPMX5.GOOGLEMAIL.COM.</td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-outline-primary">測試</button>
                                        <button type="button" class="btn btn-outline-secondary">註解</button>
                                        <button type="button" class="btn btn-outline-danger">刪</button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>@</td>
                                <td>TXT</td>
                                <td>86400</td>
                                <td class="text-break">apple-domain-verification=JG1ddtnZXbdCgeVW</td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-outline-primary">測試</button>
                                        <button type="button" class="btn btn-outline-secondary">註解</button>
                                        <button type="button" class="btn btn-outline-danger">刪</button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>@</td>
                                <td>TXT</td>
                                <td>86400</td>
                                <td class="text-break">google-site-verification=gKVsr8kGAGVMh5tlHWihOP2TWkh7BpF2G1h_iF6Ww9Y</td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-outline-primary">測試</button>
                                        <button type="button" class="btn btn-outline-secondary">註解</button>
                                        <button type="button" class="btn btn-outline-danger">刪</button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>app</td>
                                <td>A</td>
                                <td>86400</td>
                                <td>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span>122.117.71.71</span>
                                        <span class="text-muted small">中華電信固定ip</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-outline-primary">測試</button>
                                        <button type="button" class="btn btn-outline-secondary">註解</button>
                                        <button type="button" class="btn btn-outline-danger">刪</button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>autodiscover.o365</td>
                                <td>CNAME</td>
                                <td>86400</td>
                                <td>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span>autodiscover.outlook.com.</span>
                                        <span class="text-muted small">TTL : 3600</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-outline-primary">測試</button>
                                        <button type="button" class="btn btn-outline-secondary">註解</button>
                                        <button type="button" class="btn btn-outline-danger">刪</button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>dns</td>
                                <td>A</td>
                                <td>38400</td>
                                <td>163.28.83.195</td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-outline-primary">測試</button>
                                        <button type="button" class="btn btn-outline-secondary">註解</button>
                                        <button type="button" class="btn btn-outline-danger">刪</button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>dns</td>
                                <td>AAAA</td>
                                <td>38400</td>
                                <td class="text-break">2001:288:5000:10:163:28:83:195</td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-outline-primary">測試</button>
                                        <button type="button" class="btn btn-outline-secondary">註解</button>
                                        <button type="button" class="btn btn-outline-danger">刪</button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>elearning</td>
                                <td>CNAME</td>
                                <td>86400</td>
                                <td>ghs.googlehosted.com.</td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-outline-primary">測試</button>
                                        <button type="button" class="btn btn-outline-secondary">註解</button>
                                        <button type="button" class="btn btn-outline-danger">刪</button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>error</td>
                                <td>A</td>
                                <td>86400</td>
                                <td>163.23.200.212</td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-outline-primary">測試</button>
                                        <button type="button" class="btn btn-outline-secondary">註解</button>
                                        <button type="button" class="btn btn-outline-danger">刪</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>             
                </div>
                <div class="alert alert-danger text-dark rounded-0 border-1 p-3 border-danger mt-2" role="alert">
                    <h4 class="alert-heading font-weight-bold mb-2">小提示</h4>
                    <ol class="pl-3 mb-0">
                        <li>綠色底：三小時內有異動的資料。</li>
                        <li>設定後1~3分鐘後生效。</li>
                    </ol>
                </div>
            </div>            
        </div>
    </div>    
    <script>
        function open_window(url,name)
        {
            window.open(url,name,'statusbar=no,scrollbars=yes,status=yes,resizable=yes,width=500,height=400');
        }
    </script>    
@endsection
