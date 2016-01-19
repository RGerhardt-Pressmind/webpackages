<div class="container">
	<div class="text-center" style="margin-top: 50px;">
		<h1>ZIP herunterladen</h1>
		<p class="lead">Bitte laden Sie mit dem Button "ZIP herunterladen" das aktualisierte Framework herunter. Speichern Sie die .zip Datei als "master.zip" (ohne Anführungszeichen) im Framework Ordner update ab.</p>
	</div>

	<hr>

	<div class="col-md-12">
		<div class="col-md-6 text-left">
			<a href="<?php echo HTTP; ?>index.php?c=update&m=step3" class="btn btn-default">Zurück</a>
		</div>
		<div class="col-md-6 text-right">
			<a href="<?php echo \package\version::REPO; ?>" class="btn btn-success">ZIP herunterladen</a>
		</div>
	</div>

	<script type="text/javascript">
		setInterval(function(){

			$.ajax({
				url: "<?php echo HTTP; ?>index.php?c=update&m=existMasterZIP",
				method: "POST",
				dataType: 'json'
			}).done(function(data){
				if(data.exists === true)
				{
					location.href = "<?php echo HTTP; ?>index.php?c=update&m=step5";
				}
				else
				{
					console.log('Not exists');
				}
			});

		}, 3000)
	</script>

</div>