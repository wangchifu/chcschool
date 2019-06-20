<div class="container-fluid">
    <div class="row">
        @foreach($photo_links as $photo_link)
            <div class="float-left" style="margin: 5px;">
                <?php
                $school_code = school_code();
                $img = "storage/".$school_code.'/photo_links/'.$photo_link->image;
                ?>
                <figure class="figure">
                    <a href="{{ $photo_link->url }}" target="_blank"><img src="{{ asset($img) }}" class="figure-img img-fluid rounded" width="100"></a>
                    <figcaption class="figure-caption"><small>{{ $photo_link->name }}</small></figcaption>
                </figure>
            </div>
        @endforeach
    </div>
    <div class="row">
        <div class="col1">
            <a href="{{ route('photo_links.show') }}">更多...</a>
        </div>
    </div>
</div>
