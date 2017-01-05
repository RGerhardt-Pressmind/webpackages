<!DOCTYPE html>
<html lang="de" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="webpackages ist das Leistungsstärkste PHP Framework zum erstellen von PHP Projekten.">
    <meta name="author" content="webpackages">
    <title><?php echo \package\core\language::translate('webpackages - das Leistungsstärkste PHP Framework'); ?></title>
    <link href="<?php echo HTTP_SKIN.'css/bootstrap.min.css'; ?>" rel="stylesheet">
    <link href="<?php echo HTTP_SKIN.'css/font-awesome.min.css'; ?>" rel="stylesheet">
    <link href="<?php echo HTTP_SKIN.'css/main.css'; ?>" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="<?php echo HTTP_SKIN.'js/html5shiv.js'; ?>"></script>
    <script src="<?php echo HTTP_SKIN.'js/respond.min.js'; ?>"></script>
    <![endif]-->
</head><!--/head-->

<body data-spy="scroll" data-target="#navbar" data-offset="0">
	<div style="position: fixed; top: 0; right: 0; padding: 5px; background-color: #155273; z-index: 99999;">
		<a href="<?php echo HTTP.'index.php?c=welcome&m=change_language&lng=de_DE'; ?>">
			<img src="<?php echo HTTP_SKIN.'images/flags/Germany.png'; ?>" title="Deutsch" alt="Deutsch" width="24" height="24">
		</a>
		<a href="<?php echo HTTP.'index.php?c=welcome&m=change_language&lng=en_US'; ?>">
			<img src="<?php echo HTTP_SKIN.'images/flags/United-States.png'; ?>" title="English" alt="English" width="24" height="24">
		</a>
		<a href="<?php echo HTTP.'index.php?c=welcome&m=change_language&lng=fr_FR'; ?>">
			<img src="<?php echo HTTP_SKIN.'images/flags/France.png'; ?>" title="Français" alt="Français" width="24" height="24">
		</a>
		<a href="<?php echo HTTP.'index.php?c=welcome&m=change_language&lng=it_IT'; ?>">
			<img src="<?php echo HTTP_SKIN.'images/flags/Italy.png'; ?>" title="Italiano" alt="Italiano" width="24" height="24">
		</a>
	</div>
