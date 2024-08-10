<a data-toggle="collapse" href="#collapse_hd" role="button" aria-expanded="false" aria-controls="collapse_hd" style="color:black;">
    容量使用率：{{ $per }} %
</a>
<div class="collapse" id="collapse_hd">
    <div class="progress">
        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: {{ $per }}%">已使用容量( {{ $size }}MB / 5GB )</div>
    </div>
    <hr>
</div>
