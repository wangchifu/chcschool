{{-- 無障礙 HM1240401C 修正：補上 title 與 aria-label，明確說明可展開/收合 --}}
<a data-toggle="collapse" href="#collapse_hd" role="button" aria-expanded="false" aria-controls="collapse_hd" style="color:black;" title="點擊展開或收合硬碟容量詳細使用率" aria-label="點擊展開或收合硬碟容量詳細使用率">
    容量使用率：{{ $per }} %
</a>

<div class="collapse mt-2" id="collapse_hd">
    <div class="card card-body p-2">
        <p class="mb-1 small font-weight-bold">
            已使用容量：{{ $size }} MB / 5 GB ({{ $per }}%)
        </p>
        
        <div class="progress" style="height: 1.25rem;">
            {{-- 無障礙 4.1.2 修正：aria-valuenow 改為動態變數 {{ $per }}，補上 aria-valuetext 提供完整說明 --}}
            <div class="progress-bar progress-bar-striped progress-bar-animated" 
                 role="progressbar" 
                 aria-valuenow="{{ $per }}" 
                 aria-valuemin="0" 
                 aria-valuemax="100" 
                 aria-valuetext="已使用 {{ $per }}%，共 {{ $size }} MB"
                 style="width: {{ $per }}%;">
                <span class="sr-only">{{ $per }}%</span>
            </div>
        </div>
    </div>
    <hr class="my-2">
</div>