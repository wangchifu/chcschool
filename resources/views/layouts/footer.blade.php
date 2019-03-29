<br>
<br>
<br>
<br>
<footer class="footer bg-light">
    <div class="container">
        <?php
            $people = $setup->views;
            $footer = str_replace('%訪客人次%','訪客人次：'.$people,$setup->footer);
        ?>
        {!! $footer !!}
    </div>
</footer>
