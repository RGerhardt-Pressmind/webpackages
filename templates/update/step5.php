<div class="container">
	<div class="text-center" style="margin-top: 50px;">
		<h1>master.zip gefunden</h1>
		<p class="lead">Das Framework hat die Datei master.zip im Ordner update gefunden. Wollen Sie Ihr Framework nun aktualisieren?</p>
	</div>

	<hr>

	<div class="row" id="updateProgress" style="display:none;">
		<div class="col-md-3"></div>
		<div class="col-md-6">
			<p id="updateMessage" class="text-center"></p>
			<div class="progress">
				<div id="progressbar" class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="1" aria-valuemin="0" aria-valuemax="100" style="width:1%">
					1%
			  	</div>
			</div>
		</div>
		<div class="col-md-3"></div>
	</div>

	<div class="col-md-12">
		<div class="col-md-6 text-left">
			<a href="<?php echo HTTP; ?>index.php?c=update&m=step3" class="btn btn-default">Zurück</a>
		</div>
		<div class="col-md-6 text-right">
			<button class="btn btn-success" onclick="updateFramework();">Aktualisierung starten</button>
		</div>
	</div>

	<script type="text/javascript">
		function updateFramework()
		{
			$(this).prop('disabled', true);
			$('#updateProgress').show();
			$('#updateMessage').html('Starte Aktualisierung...');

			startUpdate('_unpackArchive');
		}


		function startUpdate(versionUpdate)
		{
			var nextVersionUpdate	=	'';

			switch(versionUpdate)
			{
				case '_updateAllClassesInPackage':

					updateAllClassesInPackage();
					return;

				break;
				default:

					nextVersionUpdate	=	'_updateAllClassesInPackage';

				break;
			}

			$.ajax({
				url: "<?php echo HTTP; ?>index.php?c=update&m="+versionUpdate,
				method: 'POST',
				dataType: 'json',
				error: function(xhr, ajaxOptions, thrownError){
					console.log(xhr.responseText);
				}
			}).done(function(data){
				$('#updateMessage').html(data.message);
				$('#progressbar').attr('aria-valuenow', data.percent).html(data.percent+'%').css('width', data.percent+'%');

				if(data.error === false)
				{
					setTimeout(function(){
						startUpdate(nextVersionUpdate);
					}, 1000);
				}
			});
		}


		function updateAllClassesInPackage()
		{
			var beginPercent				=	20;
			var intervallAllPackageClasses	=	setInterval(function(){

				$.ajax({
					url: "<?php echo HTTP; ?>index.php?c=update&m=_updateAllClassesInPackage",
					method: 'POST',
					dataType: 'json',
					error: function(xhr){
						console.log(xhr.responseText);
					}
				}).done(function(data){
					$('#updateMessage').html(data.message);
					$('#progressbar').attr('aria-valuenow', beginPercent).html(beginPercent+'%').css('width', beginPercent+'%');

					if(data.endAll === true)
					{
						updateRest();
					}

					if(data.error === true)
					{
						clearInterval(intervallAllPackageClasses);
					}

					++beginPercent;
				});

			}, 800);
		}


		function updateRest()
		{
			var beginPercent				=	50;
			var intervallAllPackageClasses	=	setInterval(function(){

				$.ajax({
					url: "<?php echo HTTP; ?>index.php?c=update&m=_updateAllFilesInRoot",
					method: 'POST',
					dataType: 'json',
					error: function(xhr){
						console.log(xhr.responseText);
					}
				}).done(function(data){
					$('#updateMessage').html(data.message);
					$('#progressbar').attr('aria-valuenow', beginPercent).html(beginPercent+'%').css('width', beginPercent+'%');

					if(data.endAll === true)
					{
						$('#progressbar').attr('aria-valuenow', beginPercent).html('100%').css('width', '100%');
					}

					if(data.error === true)
					{
						clearInterval(intervallAllPackageClasses);
						$('#progressbar').attr('aria-valuenow', beginPercent).html('100%').css('width', '100%');
					}

					++beginPercent;
				});

			}, 800);
		}
	</script>

</div>