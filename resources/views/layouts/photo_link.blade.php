<style>
    /* 統一圖片與容器樣式 */
    .photo-link-img {
        width: 100%;
        aspect-ratio: 16 / 9;
        object-fit: cover;
        object-position: center;
        display: block;
        transition: opacity 0.2s ease;
    }
    
    .photo-link-item a:hover img,
    .photo-link-item a:focus img {
        opacity: 0.7;
    }

    /* 無障礙 HM1020401C 修正：鍵盤 Focus 高對比視覺提示 */
    .photo-link-item a:focus-visible,
    #myTab button:focus-visible {
        outline: 3px solid #0056b3 !important;
        outline-offset: 3px !important;
    }
</style>

<!-- 頁籤導覽列 -->
<ul class="nav nav-tabs" id="myTab" role="tablist" aria-label="圖片連結分類頁籤">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="photo_type_home-tab" data-toggle="tab" data-target="#photo_type_home" type="button" role="tab" aria-controls="photo_type_home" aria-selected="true">全部</button>
    </li>
    <?php $p = 1; ?>
    @foreach($photo_types as $photo_type)
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="photo_type{{ $p }}-tab" data-toggle="tab" data-target="#photo_type{{ $p }}" type="button" role="tab" aria-controls="photo_type{{ $p }}" aria-selected="false">{{ $photo_type->name }}</button>
        </li>
    <?php $p++; ?>
    @endforeach
</ul>

<!-- 頁籤內容區塊 -->
<div class="tab-content pt-3" id="myTabContent">
    <!-- 全部圖片連結 -->
    <div class="tab-pane fade show active" id="photo_type_home" role="tabpanel" aria-labelledby="photo_type_home-tab">
        <div class="container-fluid">
            <div class="row justify-content-start">        
                @foreach($photo_links as $photo_link)
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2 col-xl-2 photo-link-item mb-3">
                        <?php
                        $school_code = school_code();
                        $img = "storage/".$school_code.'/photo_links/'.$photo_link->image;
                        ?>
                        <figure class="figure w-100 mb-0">
                            {{-- 無障礙 HM1120201C & HM1240401C 修正：提供明確 alt 文字與新視窗開啟提示 --}}
                            <a href="{{ $photo_link->url }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $photo_link->name }}（另開新視窗）">
                                <img src="{{ asset($img) }}" 
                                     class="figure-img img-fluid rounded photo-link-img" 
                                     alt="{{ $photo_link->name }}">
                            </a>
                            
                            <figcaption class="figure-caption text-center" style="word-wrap: break-word; word-break: break-all;">
                                <small class="font-weight-bold">{{ $photo_link->name }}</small>
                            </figcaption>
                        </figure>
                    </div>                    
                @endforeach
            </div>
            <div class="row mt-2">
                <div class="col">
                    <small>
                        <a href="{{ route('photo_links.show') }}" class="btn btn-sm btn-outline-primary">
                            <i class="far fa-hand-point-up" aria-hidden="true"></i> 查看更多圖片連結...
                        </a>
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- 各分類圖片連結 -->
    <?php $p = 1; ?>
    @foreach($photo_types as $photo_type)
        <div class="tab-pane fade" id="photo_type{{ $p }}" role="tabpanel" aria-labelledby="photo_type{{ $p }}-tab">
            <div class="container-fluid">
                <div class="row justify-content-start">
                    <?php 
                    $photo_links = []; 
                    $photo_links = \App\PhotoLink::where('photo_type_id',$photo_type->id)->orderBy('order_by','DESC')->get();
                    ?>       
                    @foreach($photo_links as $photo_link)
                        <div class="col-6 col-sm-4 col-md-3 col-lg-2 col-xl-2 photo-link-item mb-3">
                            <?php
                            $school_code = school_code();
                            $img = "storage/".$school_code.'/photo_links/'.$photo_link->image;
                            ?>
                            <figure class="figure w-100 mb-0">
                                <a href="{{ $photo_link->url }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $photo_link->name }}（另開新視窗）">
                                    <img src="{{ asset($img) }}" 
                                         class="figure-img img-fluid rounded photo-link-img" 
                                         alt="{{ $photo_link->name }}">
                                </a>
                                <figcaption class="figure-caption text-center" style="word-wrap: break-word; word-break: break-all;">
                                    <small class="font-weight-bold">{{ $photo_link->name }}</small>
                                </figcaption>
                            </figure>
                        </div>
                    @endforeach
                </div>
                <div class="row mt-2">
                    <div class="col">
                        <small>
                            <a href="{{ route('photo_links.show',$photo_type->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="far fa-hand-point-up" aria-hidden="true"></i> 查看更多 {{ $photo_type->name }} 圖片連結...
                            </a>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    <?php $p++; ?>
    @endforeach
</div>