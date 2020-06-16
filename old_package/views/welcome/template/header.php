<!DOCTYPE html>
<html lang="de" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="webpackages ist das Leistungsstärkste PHP Framework zum erstellen von PHP Projekten.">
    <meta name="author" content="webpackages">
    <title><?php echo \package\system\core\language::translate('webpackages - das Leistungsstärkste PHP Framework'); ?></title>

	<?php
	echo \package\system\core\template::getStyles('header', true);
	?>
	<script>
		var currentTime	=	new Date();
	</script>

    <!--[if lt IE 9]>
    <script src="<?php echo \package\system\core\template::getJsUrl('html5shiv.js'); ?>"></script>
    <script src="<?php echo \package\system\core\template::getJsUrl('respond.min.js'); ?>"></script>
    <![endif]-->
</head><!--/head-->

<body data-spy="scroll" data-target="#navbar" data-offset="0">
	<div style="position: fixed; top: 0; right: 0; padding: 5px; background-color: #155273; z-index: 99999;">
		<a href="<?php echo HTTP.'index.php?c=welcome&m=change_language&lng=de_DE'; ?>">
			<img src="<?php echo \package\system\core\template::getImageUrl('flags/Germany.png'); ?>" title="Deutsch" alt="Deutsch" width="24" height="24">
		</a>
		<a href="<?php echo HTTP.'index.php?c=welcome&m=change_language&lng=en_US'; ?>">
			<img src="<?php echo \package\system\core\template::getImageUrl('flags/United-States.png'); ?>" title="English" alt="English" width="24" height="24">
		</a>
		<a href="<?php echo HTTP.'index.php?c=welcome&m=change_language&lng=fr_FR'; ?>">
			<img src="<?php echo \package\system\core\template::getImageUrl('flags/France.png'); ?>" title="Français" alt="Français" width="24" height="24">
		</a>
		<a href="<?php echo HTTP.'index.php?c=welcome&m=change_language&lng=it_IT'; ?>">
			<img src="<?php echo \package\system\core\template::getImageUrl('flags/Italy.png'); ?>" title="Italiano" alt="Italiano" width="24" height="24">
		</a>
	</div>
