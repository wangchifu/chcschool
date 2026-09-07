<?php
$fix_classes = \App\FixClass::where('disable',null)->orderBy('order_by')->get();
$n=1;
?>
<ul class="nav nav-tabs" id="fix-Tab" role="tablist" aria-label="報修分類頁籤">
  <li class="nav-item" role="presentation">
    <button class="nav-link active" id="fix-home-tab" data-toggle="tab" data-target="#fix-home" type="button" role="tab" aria-controls="fix-home" aria-selected="true" title="顯示全部報修項目">全部</button>
  </li>
  @foreach($fix_classes as $fix_class)
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="fix{{ $n }}-tab" data-toggle="tab" data-target="#fix{{ $n }}" type="button" role="tab" aria-controls="fix{{ $n }}" aria-selected="false" title="顯示 {{ $fix_class->name }} 分類報修">
        {{ $fix_class->name }}
      </button>
    </li>  
    <?php $n++; ?>
  @endforeach
</ul>

<?php $n=1; ?>
<div class="tab-content" id="myTabContent">
  <?php
  $fixes = \App\Fix::where('situation','!=',1)->orderBy('situation','DESC')->orderBy('created_at','DESC')->paginate(10);
  ?>
  <div class="tab-pane fade show active" id="fix-home" role="tabpanel" aria-labelledby="fix-home-tab">
    <ul class="list-unstyled mt-2">
      @foreach($fixes as $fix)
        <?php               
          if($fix->situation==3) $color = "danger";
          if($fix->situation==2) $color = "warning";
          if($fix->situation==1) $color = "success";
        ?>
        <li class="mb-1">
          <small class="text-muted">{{ substr($fix->created_at,0,10) }}</small>
          <span class="badge badge-{{ $color }}">{{ substr_cut_name($fix->user->name) }}</span>
          
          {{-- 無障礙 HM1240401C 修正：改用標準 href，加上開新視窗提示、title 與 aria-label --}}
          <a href="{{ route('fixes.show_clean',$fix->id) }}" onclick="open_window('{{ route('fixes.show_clean',$fix->id) }}','新視窗'); return false;" title="檢視報修詳情：{{ $fix->title }}（另開新視窗）" aria-label="檢視報修詳情：{{ $fix->title }}（另開新視窗）">
            {{ $fix->title }}
            <span class="sr-only">（另開新視窗）</span>
          </a>
        </li>
      @endforeach
    </ul>
    
    {{-- 無障礙 HM1240401C 修正：補上完整標題提示 --}}
    <a href="{{ route('fixes.index') }}" title="查看全部報修列表" aria-label="查看全部報修列表">
      <span class="badge badge-secondary">更多報修...</span>
    </a>
  </div>

  @foreach($fix_classes as $fix_class)
    <?php
    $fixes = \App\Fix::where('situation','!=',1)->where('type',$fix_class->id)->orderBy('situation','DESC')->orderBy('created_at','DESC')->paginate(10);
    ?>    
    <div class="tab-pane fade" id="fix{{ $n }}" role="tabpanel" aria-labelledby="fix{{ $n }}-tab">
      <ul class="list-unstyled mt-2">
        @foreach($fixes as $fix)
          <?php               
            if($fix->situation==3) $color = "danger";
            if($fix->situation==2) $color = "warning";
            if($fix->situation==1) $color = "success";
          ?>
          <li class="mb-1">
            <small class="text-muted">{{ substr($fix->created_at,0,10) }}</small>
            <span class="badge badge-{{ $color }}">{{ substr_cut_name($fix->user->name) }}</span>
            
            <a href="{{ route('fixes.show_clean',$fix->id) }}" onclick="open_window('{{ route('fixes.show_clean',$fix->id) }}','新視窗'); return false;" title="檢視 {{ $fix_class->name }} 報修詳情：{{ $fix->title }}（另開新視窗）" aria-label="檢視 {{ $fix_class->name }} 報修詳情：{{ $fix->title }}（另開新視窗）">
              {{ $fix->title }}
              <span class="sr-only">（另開新視窗）</span>
            </a>
          </li>
        @endforeach
      </ul>
      
      <a href="{{ route('fixes.index') }}" title="查看更多 {{ $fix_class->name }} 報修列表" aria-label="查看更多 {{ $fix_class->name }} 報修列表">
        <span class="badge badge-secondary">更多 {{ $fix_class->name }} 報修...</span>
      </a>
    </div>    
    <?php $n++; ?>
  @endforeach
</div>