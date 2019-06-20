<div class="container-fluid">
    <div class="row">
        @foreach($photo_links as $photo_link)
            <div class="col-3 col-sm-2 col-md-2 col-lg-1">
                <?php
                $school_code = school_code();
                $img = "storage/".$school_code.'/photo_links/'.$photo_link->image;
                ?>
                <figure class="figure">
                    <a href="{{ $photo_link->url }}" target="_blank"><img src="{{ asset($img) }}" class="figure-img img-fluid rounded" alt="A generic square placeholder image with rounded corners in a figure."></a>
                    <figcaption class="figure-caption">{{ $photo_link->name }}</figcaption>
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
