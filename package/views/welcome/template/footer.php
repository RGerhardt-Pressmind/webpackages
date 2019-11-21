    <footer id="footer">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    &copy; 2010 - <?php echo date('Y'); ?> <a target="_blank" href="http://www.webpackages.de" title="webpackages - Kostenloses PHP-Framework">Webpackages</a>. All Rights Reserved.
                </div>
            </div>
        </div>
    </footer><!--/#footer-->

	<?php
	echo \package\system\core\template::getScripts('footer', true);
	echo '<script type="text/javascript">var loadTimeVar	=	'.str_replace(',', '.', \package\system\core\benchmark::finish()).'</script>';
	?>
</body>
</html>
