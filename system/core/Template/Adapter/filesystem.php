<?php
/**
 *  Copyright (C) 2010 - 2023  <Robbyn Gerhardt>
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
 * @copyright     Copyright (C) 2010 - 2023
 * @license       http://opensource.org/licenses/MIT	MIT License
 * @since         Version 2.0.0
 * @filesource
 */

namespace system\core\Template\Adapter;

use system\core\Plugin;
use system\core\Template\TemplateConfig;

class filesystem implements AdapterInterface
{
	private string $templatePath;
	private string $skin;

	/**
	 * Create new filesystem template
	 *
	 * @param TemplateConfig $config
	 *
	 * @return mixed
	 */
	public function create(TemplateConfig $config): mixed
	{
		$this->templatePath	=	ROOT.str_replace('/', SEP, trim(trim($config->templatePath, '/'), '\\'));
		$this->skin			=	$config->skin;

		list($this->templatePath, $this->skin) = Plugin::call_filter('afterTemplateCreate', [$this->templatePath, $this->skin]);

		Plugin::hook('afterTemplateCreate', [$this->templatePath, $this->skin]);

		return true;
	}

	/**
	 * Get template path back
	 *
	 * @return string
	 */
	public function getTemplatePath(): string
	{
		$templatePath	=	$this->templatePath.SEP.$this->skin.SEP.'template'.SEP;
		$templatePath	=	Plugin::call_filter('getTemplatePath', $templatePath);

		Plugin::hook('getTemplatePath', [$templatePath]);

		return $templatePath;
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
		list($params, $template) = Plugin::call_filter('beforeParseTemplate', [$params, $template]);

		Plugin::hook('beforeParseTemplate', [$params, $template]);

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

		$content	=	Plugin::call_filter('afterParseTemplate', $content);

		Plugin::hook('afterParseTemplate', [$content]);

		echo $content;
	}
}
