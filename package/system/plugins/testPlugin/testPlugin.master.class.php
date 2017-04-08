<?php
/**
 *  Copyright (C) 2010 - 2017  <Robbyn Gerhardt>
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package       Webpackages
 * @subpackage    core
 * @author        Robbyn Gerhardt
 * @copyright     Copyright (c) 2010 - 2017, Robbyn Gerhardt (http://www.robbyn-gerhardt.de/)
 * @license       http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link          http://webpackages.de
 * @since         Version 2017.0
 * @filesource
 */

namespace package\plugins;

use package\core\database;
use package\core\template;
use package\implement\IPlugin;
use package\system\valueObjects\plugins\VOApplyPlugin;

class testPlugin implements IPlugin
{
	private $db, $template;

	public function construct()
	{
	}

	public function getClassName()
	{
		return 'testPlugin';
	}

	/**
	 * Gibt die Instanzierung zurück
	 *
	 * @return VOApplyPlugin[]
	 */
	public function getApplyPlugin()
	{
		$applyPlugin = array();

		$plugin                           = new VOApplyPlugin();
		$plugin->class                    = 'welcome';
		$plugin->methode                  = 'change_language';
		$plugin->replace_default_function = true;
		$plugin->call                     = array($this, 'change_language');

		$applyPlugin[] = $plugin;

		$plugin           = new VOApplyPlugin();
		$plugin->call     = array($this, 'hello_template');
		$plugin->hook_key = 'hello_template';

		$applyPlugin[] = $plugin;

		return $applyPlugin;
	}

	public function hello_template()
	{
		echo '<div class="col-md-12 text-center">Call fom testPlugin</div>';
	}

	/**
	 * Übernimmt die Funktion der Methode
	 * welcome:change_language und ersetzt
	 * Sie durch diese
	 *
	 * @return bool
	 */
	public function change_language()
	{
		$lng = \package\core\security::url('lng', 'GET', 'string');

		$_SESSION['default_lng'] = $lng;

		echo '<p id="call_from_plugin">Call from plugin ... wait 5 seconds</p>';

		echo '
		<script type="text/javascript">
		var x	=	4;
		
		setInterval(function() {
		    console.log("Foo");
		  	document.getElementById("call_from_plugin").innerHTML	=	"Call from plugin ... wait "+x+" seconds";
		  	
		  	--x;
		}, 1000);
		
		setTimeout(function() {
		  location.href = "'.HTTP.'";
		}, 5000);
		</script>
		';

		return true;
	}

	public function setAllClasses($allClasses)
	{
		if(isset($allClasses['db']) && $allClasses['db'] instanceof database)
		{
			$this->db = $allClasses['db'];
		}

		if(isset($allClasses['template']) && $allClasses['template'] instanceof template)
		{
			$this->template = $allClasses['template'];
		}
	}
}