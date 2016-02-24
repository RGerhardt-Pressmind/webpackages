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

namespace package\system\plugins\commandLinePlugin;

use package\implement\IPlugin;

class CommandLine implements IPlugin
{
	public $args;

	public function construct(){}
	public function setAllClasses($allClasses){}

	public function getClassName()
	{
		return 'CommandLine';
	}


	/**
     * PARSE ARGUMENTS
     *
     * This command line option parser supports any combination of three types
     * of options (switches, flags and arguments) and returns a simple array.
     *
     * [pfisher ~]$ php test.php --foo --bar=baz
     *   ["foo"]   => true
     *   ["bar"]   => "baz"
     *
     * [pfisher ~]$ php test.php -abc
     *   ["a"]     => true
     *   ["b"]     => true
     *   ["c"]     => true
     *
     * [pfisher ~]$ php test.php arg1 arg2 arg3
     *   [0]       => "arg1"
     *   [1]       => "arg2"
     *   [2]       => "arg3"
     *
     * [pfisher ~]$ php test.php plain-arg --foo --bar=baz --funny="spam=eggs" --also-funny=spam=eggs \
     * > 'plain arg 2' -abc -k=value "plain arg 3" --s="original" --s='overwrite' --s
     *   [0]       => "plain-arg"
     *   ["foo"]   => true
     *   ["bar"]   => "baz"
     *   ["funny"] => "spam=eggs"
     *   ["also-funny"]=> "spam=eggs"
     *   [1]       => "plain arg 2"
     *   ["a"]     => true
     *   ["b"]     => true
     *   ["c"]     => true
     *   ["k"]     => "value"
     *   [2]       => "plain arg 3"
     *   ["s"]     => "overwrite"
     *
     * @author              Patrick Fisher <patrick@pwfisher.com>
     * @since               August 21, 2009
     * @see                 http://www.php.net/manual/en/features.commandline.php
     *                      #81042 function arguments($argv) by technorati at gmail dot com, 12-Feb-2008
     *                      #78651 function getArgs($args) by B Crawford, 22-Oct-2007
     * @usage               $args = CommandLine::parseArgs($_SERVER['argv']);
     */
	public function parseArgs($argv)
	{
		array_shift($argv);

        $out                            = array();

        foreach($argv as $arg)
        {
            // --foo --bar=baz
            if(substr($arg,0,2) == '--')
            {
                $eqPos                  = strpos($arg,'=');
                // --foo
                if($eqPos === false)
                {
                    $key                = substr($arg,2);
                    $value              = isset($out[$key]) ? $out[$key] : true;
                    $out[$key]          = $value;
                }
                else
                {
                    $key                = substr($arg,2,$eqPos-2);
                    $value              = substr($arg,$eqPos+1);
                    $out[$key]          = $value;
                }
            }
            // -k=value -abc
            elseif(substr($arg,0,1) == '-')
            {
                if(substr($arg,2,1) == '=')
                {
                    $key                = substr($arg,1,1);
                    $value              = substr($arg,3);
                    $out[$key]          = $value;
                }
                else
                {
                    $chars              = str_split(substr($arg,1));

                    foreach($chars as $char)
                    {
                        $key            = $char;
                        $value          = isset($out[$key]) ? $out[$key] : true;
                        $out[$key]      = $value;
                    }
                }
            }
            else
            {
                $value                  = $arg;
                $out[]                  = $value;
            }
        }

        $this->args                     = $out;

        return $out;
	}

	/**
	 * GET BOOLEAN
	 *
	 * @param      $key
	 * @param bool $default
	 *
	 * @return bool|string
	 */
    public function getBoolean($key, $default = false)
    {
        if(!isset($this->args[$key]))
        {
            return $default;
        }

        $value                          = $this->args[$key];

        if(is_bool($value))
        {
            return $value;
        }

        if(is_int($value))
        {
            return (bool)$value;
        }

        if(is_string($value))
        {
            $value                      = strtolower($value);
            $map = array(
                'y'                     => true,
                'n'                     => false,
                'yes'                   => true,
                'no'                    => false,
                'true'                  => true,
                'false'                 => false,
                '1'                     => true,
                '0'                     => false,
                'on'                    => true,
                'off'                   => false,
            );

            if(isset($map[$value]))
            {
                return $map[$value];
            }
        }

        return $default;
    }
}