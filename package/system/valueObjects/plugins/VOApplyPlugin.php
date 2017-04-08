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
 * @author        Robbyn Gerhardt
 * @copyright     Copyright (c) 2010 - 2017, Robbyn Gerhardt (http://www.robbyn-gerhardt.de/)
 * @license       http://opensource.org/licenses/MIT	MIT License
 * @link          http://webpackages.de
 * @since         Version 2017.0
 * @filesource
 */

namespace package\system\valueObjects\plugins;

class VOApplyPlugin
{
	const BEFORE	=	'BEFORE';
	const AFTER		=	'AFTER';

	/**
	 * Der Klassennamen in dem das Plugin aufgerufen werden soll
	 *
	 * @var string
	 */
	public $class	=	'';

	/**
	 * Die Methode in der Klasse in dem das Plugin aufgerufen werden soll
	 *
	 * @var string
	 */
	public $methode	=	'';

	/**
	 * Die Funktion/Klasse(Methode) die aufgerufen werden soll, die das Plugin enthält
	 *
	 * @var mixed
	 */
	public $call;

	/**
	 * Wann das Plugin ausgeführt werden soll, vor dem Funktionsaufruf oder anschließend
	 *
	 * @var string
	 */
	public $call_position = self::BEFORE;

	/**
	 * Entscheidet ob es die Standard Funktion mit der Plugin Funktion überschreibt
	 *
	 * @var bool
	 */
	public $replace_default_function	=	true;

	/**
	 * Alle Klassen
	 *
	 * @var bool
	 */
	public $all_dynamic_class	=	false;

	/**
	 * Alle Methoden in einer Klasse
	 *
	 * @var bool
	 */
	public $all_dynamic_method	=	false;

	/**
	 * @var string
	 */
	public $hook_key;
}