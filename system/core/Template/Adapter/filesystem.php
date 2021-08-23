<?php
/**
 *  Copyright (C) 2010 - 2021  <Robbyn Gerhardt>
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
 * @package       webpackages
 * @author        Robbyn Gerhardt
 * @copyright     Copyright (c) 2010 - 2021
 * @license       http://opensource.org/licenses/MIT	MIT License
 * @since         Version 2.0.0
 * @filesource
 */

namespace system\core\Template\Adapter;

use system\core\Template\TemplateConfig;

class filesystem implements AdapterInterface
{
	private $templatePath;
	private $skin;

	/**
	 * Create new filesystem template
	 *
	 * @param TemplateConfig $config
	 *
	 * @return mixed
	 */
	public function create(TemplateConfig $config): mixed
	{
		$this->templatePath	=	ROOT.str_replace('/', DIRECTORY_SEPARATOR, trim(trim($config->templatePath, '/'), '\\'));
		$this->skin			=	$config->skin;

		return true;
	}

	/**
	 * Get template path back
	 *
	 * @return string
	 */
	public function getTemplatePath(): string
	{
		return $this->templatePath.DIRECTORY_SEPARATOR.$this->skin.DIRECTORY_SEPARATOR.'template'.DIRECTORY_SEPARATOR;
	}

	/**
	 * Parse template file and get out
	 *
	 * @param array $params
	 * @param string $template
	 *
	 * @return void
	 */
	public function parse(array $params, string $template)
	{
		$templateFile	=	$this->getTemplatePath().$template.'.php';

		if(!file_exists($templateFile))
		{
			echo 'Failed, template file "'.$templateFile.'" not found';
			exit;
		}

		foreach($params as $key => $value)
		{
			${$key}	=	$value;
		}

		ob_start();

		require_once $templateFile;

		$content	=	ob_get_contents();
		ob_end_clean();

		echo $content;
	}
}
