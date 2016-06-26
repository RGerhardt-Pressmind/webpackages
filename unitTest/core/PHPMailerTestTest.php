<?php
/**
 *  Copyright (C) 2010 - 2016  <Robbyn Gerhardt>
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
 * @author        Robbyn Gerhardt
 * @copyright     Copyright (c) 2010 - 2016, Robbyn Gerhardt (http://www.robbyn-gerhardt.de/)
 * @license       http://opensource.org/licenses/MIT	MIT License
 * @link          http://webpackages.de
 * @since         Version 2.0.0
 * @filesource
 */

namespace unitTest\core;

use package\core\autoload;
use package\system\core\phpMailer;
use package\system\valueObjects\phpMailer\VOMailAddress;
use package\system\valueObjects\phpMailer\VOPHPMailer;

class PHPMailerTestTest extends \PHPUnit_Framework_TestCase
{
	public function testSend()
	{
		$param	=	new VOPHPMailer();
		$param->host			=	'alfa3101.alfahosting-server.de';
		$param->is_smtp			=	false;
		$param->is_smtp_auth	=	false;
		$param->username		=	'web704p2';
		$param->password		=	'Moppi666';
		
		$mail	=	autoload::get('phpMailer', '\package\system\core\\', false, $param, false);

		if($mail instanceof phpMailer)
		{
			$from	=	new VOMailAddress();
			$from->address	=	'gerhardt@webpackages.de';

			$_to	=	new VOMailAddress();
			$_to->address	=	'robbyn@worldwideboard.de';

			$to		=	array();
			$to[]	=	$_to;

			$this->assertTrue($mail->send($from, $to, 'Unit Test Subject', 'This ist Unit Test Message from webpackages Framework'));
		}
		else
		{
			throw new \Exception('Error: not instance of phpMailer');
		}
	}
}
