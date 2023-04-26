<?php

use system\core\Http;
use system\core\Language;

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo Language::translate('website.description'); ?>">
    <meta name="author" content="webpackages">
	<title><?php echo Language::translate('website.title'); ?></title>
	<link rel="stylesheet" href="<?php echo Http::getSkinURL().'css/bootstrap.min.css'; ?>">
	<link rel="stylesheet" href="<?php echo Http::getSkinURL().'css/font-awesome.min.css'; ?>">
	<link rel="stylesheet" href="<?php echo Http::getSkinURL().'css/main.css'; ?>">

	<!--[if lt IE 9]>
    <script src="<?php echo Http::getSkinURL().'js/html5shiv.js'; ?>"></script>
    <script src="<?php echo Http::getSkinURL().'js/respond.min.js'; ?>"></script>
    <![endif]-->
</head>
<body data-spy="scroll" data-target="#navbar" data-offset="0">
	<div style="position: fixed; top: 0; right: 0; padding: 5px; background-color: #155273; z-index: 99999;">
		<a href="<?php echo Http::getURL().'index.php?c=welcome&m=change_language&lng=de_DE'; ?>">
			<img src="<?php echo Http::getSkinURL().'images/flags/Germany.png'; ?>" title="Deutsch" alt="Deutsch" width="24" height="24">
		</a>
		<a href="<?php echo Http::getURL().'index.php?c=welcome&m=change_language&lng=en_US'; ?>">
			<img src="<?php echo Http::getSkinURL().'images/flags/United-States.png'; ?>" title="English" alt="English" width="24" height="24">
		</a>
		<a href="<?php echo Http::getURL().'index.php?c=welcome&m=change_language&lng=fr_FR'; ?>">
			<img src="<?php echo Http::getSkinURL().'images/flags/France.png'; ?>" title="Français" alt="Français" width="24" height="24">
		</a>
		<a href="<?php echo Http::getURL().'index.php?c=welcome&m=change_language&lng=it_IT'; ?>">
			<img src="<?php echo Http::getSkinURL().'images/flags/Italy.png'; ?>" title="Italiano" alt="Italiano" width="24" height="24">
		</a>
	</div>
