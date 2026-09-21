<link rel="StyleSheet" href="{{ asset('dtree/dtree.css') }}" type="text/css" />
<script type="text/javascript" src="{{ asset('dtree/dtree.js') }}"></script>

<?php
$trees = \App\Tree::orderBy('type')->orderBy('order_by')->orderBy('name')->get();
?>

<div class="dtree" aria-label="網站連結樹狀圖分類">

    {{-- 無障礙 HM1240401C 修正：改用 button 或具備完整 title/aria-label/role 的連結 --}}
    <p>
        <a href="#" role="button" onclick="d.openAll(); return false;" title="展開全部樹狀分類連結" aria-label="展開全部樹狀分類連結">全部打開</a> 
        | 
        <a href="#" role="button" onclick="d.closeAll(); return false;" title="收合全部樹狀分類連結" aria-label="收合全部樹狀分類連結">全部關閉</a>
    </p>

    <script type="text/javascript">
        <!--

        d = new dTree('d');
        d.config.useSelection = false;
        d.add(0,-1,'連結收集');
        <?php $i=1; ?>
        @foreach($trees as $tree)
            d.add({{ $tree->id }},{{ $tree->folder_id }},'{{ $tree->name }}','{{ $tree->url }}');
            <?php $i++; ?>
        @endforeach

        document.write(d);

        //-->
    </script>

</div>