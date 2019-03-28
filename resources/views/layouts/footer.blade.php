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
<script src="{{ asset('bootstrap-4.1.1/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>

