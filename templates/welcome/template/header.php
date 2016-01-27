<!DOCTYPE html>
<html lang="de" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="webpackages ist kostenlos und das Leistungsstärkste PHP Framework zum erstellen von PHP Projekten.">
    <meta name="author" content="webpackages">
    <title><?php echo \package\core\language::translate('webpackages - das Leistungsstärkste PHP Framework'); ?></title>
    <link href="<?php echo $this->load_template_file('bootstrap.min', 'css'); ?>" rel="stylesheet">
    <link href="<?php echo $this->load_template_file('font-awesome.min', 'css'); ?>" rel="stylesheet">
    <link href="<?php echo $this->load_template_file('prettyPhoto', 'css'); ?>" rel="stylesheet">
    <link href="<?php echo $this->load_template_file('main', 'css'); ?>" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="<?php echo $this->load_template_file('html5shiv', 'js'); ?>"></script>
    <script src="<?php echo $this->load_template_file('respond.min', 'js'); ?>"></script>
    <![endif]-->
</head><!--/head-->

<body data-spy="scroll" data-target="#navbar" data-offset="0">
	<div style="position: fixed; top: 0; right: 0; padding: 5px; background-color: #155273; z-index: 99999;">
		<a href="<?php echo HTTP.'index.php?c=welcome&m=change_language&lng=de_DE'; ?>"><img src="<?php echo $this->load_template_file('flags/Germany.png', 'img', 'images'); ?>" title="Deutsch" alt="Deutsch"></a>
		<a href="<?php echo HTTP.'index.php?c=welcome&m=change_language&lng=en_US'; ?>"><img src="<?php echo $this->load_template_file('flags/United-States.png', 'img', 'images'); ?>" title="English" alt="English"></a>
		<a href="<?php echo HTTP.'index.php?c=welcome&m=change_language&lng=fr_FR'; ?>"><img src="<?php echo $this->load_template_file('flags/France.png', 'img', 'images'); ?>" title="Français" alt="Français"></a>
		<a href="<?php echo HTTP.'index.php?c=welcome&m=change_language&lng=it_IT'; ?>"><img src="<?php echo $this->load_template_file('flags/Italy.png', 'img', 'images'); ?>" title="Italiano" alt="Italiano"></a>
	</div>
