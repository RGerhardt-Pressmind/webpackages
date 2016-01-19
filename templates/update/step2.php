<div class="container">
	<div class="text-center" style="margin-top: 50px;">
		<h1>Server Kontrolle</h1>
		<p class="lead">Hier wird Ihr Server kontrolliert. Bestimmte Bibliotheken müssen installiert und Rechte im Framework gesetzt sein.</p>
	</div>

	<hr>

	<div class="row">
		<div class="col-md-3"></div>
		<div class="col-md-8">
			<?php
			$isError	=	false;
			?>

			<h3>Schreibrechte gesetzt</h3>
			<div class="form-group">
				<p class="col-sm-5"><strong>Ordner "cache"</strong></p>
				<p class="col-sm-7"><?php
					if(is_writable(ROOT.SEP.'cache'.SEP) === true)
					{
						echo '<span class="text-success">Gesetzt</span>';
					}
					else
					{
						echo '<span class="text-danger">nicht gesetzt</span>';

						$isError	=	true;
					}
					?>
				</p>
			</div>

			<div class="form-group">
				<p class="col-sm-5"><strong>Ordner "language"</strong></p>
				<p class="col-sm-7"><?php
					if(is_writable(ROOT.SEP.'language'.SEP) === true)
					{
						echo '<span class="text-success">Gesetzt</span>';
					}
					else
					{
						echo '<span class="text-danger">nicht gesetzt</span>';

						$isError	=	true;
					}
					?></p>
			</div>

			<div class="form-group">
				<p class="col-sm-5"><strong>Ordner "package"</strong></p>
				<p class="col-sm-7"><?php
					if(is_writable(ROOT.SEP.'package'.SEP) === true)
					{
						echo '<span class="text-success">Gesetzt</span>';
					}
					else
					{
						echo '<span class="text-danger">nicht gesetzt</span>';

						$isError	=	true;
					}
					?></p>
			</div>

			<div class="form-group">
				<p class="col-sm-5"><strong>Ordner "templates"</strong></p>
				<p class="col-sm-7"><?php
					if(is_writable(ROOT.SEP.'templates'.SEP) === true)
					{
						echo '<span class="text-success">Gesetzt</span>';
					}
					else
					{
						echo '<span class="text-danger">nicht gesetzt</span>';

						$isError	=	true;
					}
					?></p>
			</div>

			<div class="form-group">
				<p class="col-sm-5"><strong>Ordner "update"</strong></p>
				<p class="col-sm-7"><?php
					if(is_writable(ROOT.SEP.'update'.SEP) === true)
					{
						echo '<span class="text-success">Gesetzt</span>';
					}
					else
					{
						echo '<span class="text-danger">nicht gesetzt</span>';

						$isError	=	true;
					}
					?></p>
			</div>

			<h3>Bibliotheken installiert</h3>
			<div class="form-group">
				<p class="col-sm-5"><strong>cURL</strong></p>
				<p class="col-sm-7"><?php
					if(function_exists('curl_setopt') === true)
					{
						echo '<span class="text-success">Installiert</span>';
					}
					else
					{
						echo '<span class="text-danger">nicht installiert</span>';

						$isError	=	true;
					}
					?></p>
			</div>
			<div class="form-group">
				<p class="col-sm-5"><strong>PDO</strong></p>
				<p class="col-sm-7"><?php
					if(defined('PDO::ATTR_DRIVER_NAME') === true)
					{
						echo '<span class="text-success">Installiert</span>';
					}
					else
					{
						echo '<span class="text-danger">nicht installiert</span>';

						$isError	=	true;
					}

					?></p>
			</div>
			<div class="form-group">
				<p class="col-sm-5"><strong>fileinfo</strong></p>
				<p class="col-sm-7"><?php
					if(function_exists('finfo_open') === true)
					{
						echo '<span class="text-success">Installiert</span>';
					}
					else
					{
						echo '<span class="text-danger">nicht installiert</span>';

						$isError	=	true;
					}
					?></p>
			</div>
			<div class="form-group">
				<p class="col-sm-5"><strong>HASH</strong></p>
				<p class="col-sm-7"><?php
					if(function_exists('hash_hmac') === true)
					{
						echo '<span class="text-success">Installiert</span>';
					}
					else
					{
						echo '<span class="text-danger">nicht installiert</span>';

						$isError	=	true;
					}
					?></p>
			</div>

		</div>
		<div class="col-md-3"></div>
	</div>

	<div class="col-md-12">
		<div class="col-md-6 text-left">
			<a href="<?php echo HTTP; ?>index.php?c=update&m=step1" class="btn btn-default">Zurück</a>
		</div>
		<div class="col-md-6 text-right">
			<?php
			if($isError === false)
			{
				$_SESSION['step3']	=	true;
				echo '<a href="'.HTTP.'index.php?c=update&m=step3" class="btn btn-success">Update prüfen</a>';
			}
			else
			{
				echo '<button class="btn btn-warning">Bitte beheben Sie die Probleme</button>';
			}
			?>
		</div>
	</div>

</div>