<link rel="StyleSheet" href="{{ asset('dtree/dtree.css') }}" type="text/css" />
<script type="text/javascript" src="{{ asset('dtree/dtree.js') }}"></script>
<?php
$trees = \App\Tree::orderBy('type')->orderBy('name')->get();
?>
<div class="dtree">

    <p><a href="javascript: d.openAll();">全部打開</a> | <a href="javascript: d.closeAll();">全部關閉</a></p>

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
