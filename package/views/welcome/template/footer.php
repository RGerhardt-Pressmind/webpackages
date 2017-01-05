    <footer id="footer">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    &copy; 2010 - <?php echo date('Y'); ?> <a target="_blank" href="http://www.webpackages.de" title="webpackages - Kostenloses PHP-Framework">Webpackages</a>. All Rights Reserved.
                </div>
            </div>
        </div>
    </footer><!--/#footer-->

    <script src="<?php echo HTTP_SKIN.'js/jquery.js'; ?>"></script>
    <script src="<?php echo HTTP_SKIN.'js/bootstrap.min.js' ?>"></script>
	<script src="<?php echo HTTP_SKIN.'js/fusioncharts.js'; ?>"></script>
    <script src="<?php echo HTTP_SKIN.'js/fusioncharts.charts.js'; ?>"></script>
    <script src="<?php echo HTTP_SKIN.'js/main.js'; ?>"></script>
	<?php
	echo '<script type="text/javascript">var loadTime	=	"'.number_format(\package\core\benchmark::finish(), 4, '.', '').'"</script>';
	?>
</body>
</html>