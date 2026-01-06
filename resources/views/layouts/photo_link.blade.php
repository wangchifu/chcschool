<ul class="nav nav-tabs" id="myTab" role="tablist">
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
  <div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="photo_type_home" role="tabpanel" aria-labelledby="photo_type_home-tab">
        <div class="container-fluid">
            <div class="row justify-content-start">        
                @foreach($photo_links as $photo_link)
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2 col-xl-2">
                        <?php
                        $school_code = school_code();
                        $img = "storage/".$school_code.'/photo_links/'.$photo_link->image;
                        ?>
                        <figure class="figure w-100">
                            <style>
                                .fixed-size-img {
                                    width: 100%;            /* 寬度撐滿 Bootstrap 的欄位 */
                                    aspect-ratio: 16 / 9;    /* 設定為 16:9 比例 */
                                    object-fit: cover;       /* 圖片裁切以填滿容器，不變形 */
                                    object-position: center; /* 裁切時以中心點為準 */
                                    display: block;          /* 消除圖片下方微小間隙 */
                                }
                                
                                a:hover img {
                                    opacity: 0.5;
                                    transition: opacity 0.2s ease; /* 增加平滑過渡效果 */
                                }
                            </style>
                            <a href="{{ $photo_link->url }}" target="_blank">
                                <img src="{{ asset($img) }}" 
                                    class="figure-img img-fluid rounded fixed-size-img" 
                                    alt="編號{{ $photo_link->id }}圖片連結的縮圖">
                            </a>
                            
                            <figcaption class="figure-caption" style="word-wrap: break-word;word-break: break-all;">
                                <small>{{ $photo_link->name }}</small>
                            </figcaption>
                        </figure>
                    </div>                    
                @endforeach
            </div>
            <div class="row">
                <div class="col1">
                    <small><a href="{{ route('photo_links.show') }}"><i class="far fa-hand-point-up"></i> 更多 圖片連結...</a></small>
                </div>
            </div>
        </div>
    </div>

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
                        <div class="col-6 col-sm-4 col-md-3 col-lg-2 col-xl-2">
                            <?php
                            $school_code = school_code();
                            $img = "storage/".$school_code.'/photo_links/'.$photo_link->image;
                            ?>
                            <figure class="figure">
                                <style>
                                    a:hover img{filter:alpha(Opacity=50);-moz-opacity:0.5;opacity: 0.5;}
                                </style>
                                <a href="{{ $photo_link->url }}" target="_blank">
                                    <img src="{{ asset($img) }}" class="figure-img img-fluid rounded" alt="編號{{ $photo_link->id }}圖片連結的縮圖">
                                </a>
                                    <figcaption class="figure-caption" style="word-wrap: break-word;word-break: break-all;">
                                        <small>
                                            {{ $photo_link->name }}
                                        </small>
                                    </figcaption>
                            </figure>
                        </div>
                    @endforeach
                </div>
                <div class="row">
                    <div class="col1">
                        <small><a href="{{ route('photo_links.show',$photo_type->id) }}"><i class="far fa-hand-point-up"></i> 更多 {{ $photo_type->name }} 圖片連結...</a></small>
                    </div>
                </div>
            </div>
        </div>
    <?php $p++; ?>
    @endforeach
  </div>