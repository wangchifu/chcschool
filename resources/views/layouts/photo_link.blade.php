<div class="container-fluid">
    <div class="row justify-content-start">
        @foreach($photo_links as $photo_link)
            <div class="col-6 col-sm-4 col-md-3 col-lg-2 col-xl-2">
                <?php
                $school_code = school_code();
                $img = "storage/".$school_code.'/photo_links/'.$photo_link->image;
                ?>
                <figure class="figure">
                    <a href="{{ $photo_link->url }}" target="_blank">
                        <img src="{{ asset($img) }}" class="figure-img img-fluid rounded">
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
            <small><a href="{{ route('photo_links.show') }}"><i class="far fa-hand-point-up"></i> 更多 圖片連結...</a></small>
        </div>
    </div>
</div>
