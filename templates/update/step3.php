<div class="container">
	<div class="text-center" style="margin-top: 50px;">
		<h1>Update Kontrolle</h1>
		<p class="lead">Es wird kontrolliert ob ein neues Update für Ihre WebPackage Framework zur Verfügung steht.</p>
	</div>

	<hr>

	<h4 class="text-center">Folgende Updates stehen Ihnen zur Verfügung</h4>

	<div class="row">
		<div class="col-md-3"></div>
		<div class="col-md-6">
			<?php
			if(is_array($boxIn) === true && count($boxIn) > 0)
			{
				echo '<p class="text-center">Ihre Version: '.\package\version::VERSION.'</p>';
				$noUpdates	=	false;

				echo '
				<div class="panel-group" id="updates" role="tablist" aria-multiselectable="true">
				';

				foreach($boxIn as $key => $version)
				{
					echo '
					<div class="panel panel-default">
						<div class="panel-heading" role="tab" id="heading'.$key.'">
					  		<h4 class="panel-title row">
								<a data-toggle="collapse" class="col-md-4" data-parent="#updates" href="#collapse'.$key.'" aria-expanded="true" aria-controls="collapse'.$key.'">'.date('d.m.Y H:i', strtotime($version['commit']['committer']['date'])).'</a><small style="margin-top:3px;" class="col-md-8 text-right">v'.$version['version'].'</small>
					  		</h4>
						</div>
						<div id="collapse'.$key.'" class="panel-collapse collapse'.(($key == 0) ? ' in' : '').'" role="tabpanel" aria-labelledby="heading'.$key.'">
					  		<div class="panel-body">
								'.$version['commit']['message'].'
					  		</div>
						</div>
				  	</div>
					';
				}

				echo '
				</div>
				';
			}
			else
			{
				$noUpdates	=	true;

				echo '
				<p class="col-md-12 text-center">Es stehen keine Updates zur Verfügung</p>
				';
			}
			?>
		</div>
		<div class="col-md-3"></div>
	</div>

	<div class="col-md-12">
		<div class="col-md-6 text-left">
			<a href="<?php echo HTTP; ?>index.php?c=update&m=step2" class="btn btn-default">Zurück</a>
		</div>
		<div class="col-md-6 text-right">
			<?php
			if($noUpdates === false)
			{
				$_SESSION['step4']	=	true;
				echo '<a href="'.HTTP.'index.php?c=update&m=step4" class="btn btn-success">Framework aktualisieren</a>';
			}
			else
			{
				echo '<button class="btn btn-default">Keine Updates</button>';
			}
			?>
		</div>
	</div>

</div>