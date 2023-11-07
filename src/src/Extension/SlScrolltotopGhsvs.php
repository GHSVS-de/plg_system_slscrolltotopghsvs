<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Software (http://extstore.com). All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @copyright	Edited by GHSVS 2023-11. Modifications by ghsvs.de will no longer reflect the original work of its authors.
 */

namespace GHSVS\Plugin\System\SlScrolltotopGhsvs\Extension;

\defined('_JEXEC') or die();

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Plugin\CMSPlugin;
use Exception;
use Joomla\Application\ApplicationInterface;
use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Language\Text;
use Joomla\Event\DispatcherInterface;
use Joomla\CMS\Uri\Uri;

final class SlScrolltotopGhsvs extends CMSPlugin
{
	/**
	 * Load plugin language files automatically
	 *
	 * @var    boolean
	 * @since  3.6.3
	 */
	protected $autoloadLanguage = true;

	public function onBeforeCompileHead()
	{
		if ($this->getApplication()->getDocument()->getType() !== 'html')
		{
			return;
		}

		$admin_enable	= $this->params->get('admin_enable', '|site|administrator|');

		if (strpos($admin_enable, '|' . strtolower($this->getApplication()->getName()) . '|') === false)
		{
			return;
		}

		$wa = $this->getApplication()->getDocument()->getWebAssetManager();
		$wa->getRegistry()->addExtensionRegistryFile('plg_system_slscrolltotopghsvs');

		$text = $this->clean($this->params->get('text', ''));
		$title = $this->clean($this->params->get('title', ''));
		$duration = (int) $this->params->get('duration', 500);
		$transition = $this->params->get('transition', 'Fx.Transitions.linear');
		$custom_css = trim($this->params->get('custom_css', ''));
		$engine = $this->params->get('engine', 'jquery');
		$image = $this->params->get('image', '');
		$position = $this->params->get('position', 'bottom_right');
		$border_radius = (int) $this->params->get('border_radius', 3);
		$offset_x = (int) $this->params->get('offset_x', 20);
		$offset_y = (int) $this->params->get('offset_y', 20);
		$padding_x = (int) $this->params->get('padding_x', 12);
		$padding_y = (int) $this->params->get('padding_y', 12);
		$background_color	= $this->params->get('background_color', '#121212');
		$color = $this->params->get('color', '#fff');
		$hover_background_color	= $this->params->get('hover_background_color', '#08C');
		$hover_color = $this->params->get('hover_color', '#fff');

		if ($image)
		{
			$wurmInfos = HTMLHelper::_('cleanImageURL', $image);
			// $image = HTMLHelper::_('image', $image, '');
			$image = Uri::root() . $wurmInfos->url;
		}

		if (empty($image) && empty($text))
		{
			$text = 'ToTop';
		}

		$position_css	= '';

		switch ($position) {
			case 'top_left':
				$position_css	= "left: {$offset_x}px; top: {$offset_y}px;";
				break;
			case 'top_right':
				$position_css	= "right: {$offset_x}px; top: {$offset_y}px;";
				break;
			case 'bottom_left':
				$position_css	= "left: {$offset_x}px; bottom: {$offset_y}px;";
				break;
			case 'bottom_right':
				$position_css	= "right: {$offset_x}px; bottom: {$offset_y}px;";
				break;
		}

		$class = 'scrollToTop';

		// Build Custom CSS
		$css	= <<<CSS
#scrollToTop {
	cursor: pointer;
	font-size: 0.9em;
	position: fixed;
	text-align: center;
	z-index: 9999;
	-webkit-transition: background-color 0.2s ease-in-out;
	-moz-transition: background-color 0.2s ease-in-out;
	-ms-transition: background-color 0.2s ease-in-out;
	-o-transition: background-color 0.2s ease-in-out;
	transition: background-color 0.2s ease-in-out;

	background: $background_color;
	color: $color;
	border-radius: {$border_radius}px;
	padding-left: {$padding_x}px;
	padding-right: {$padding_x}px;
	padding-top: {$padding_y}px;
	padding-bottom: {$padding_y}px;
	$position_css
}

#scrollToTop:hover {
	background: $hover_background_color;
	color: $hover_color;
}

#scrollToTop > img {
	display: block;
	margin: 0 auto;
}
CSS;

		$wa->addInlineStyle(
			$css,
			[],
			['name' => 'plg_system_slscrolltotopghsvs.css'],
		);

		if ($custom_css)
		{
			$wa->addInlineStyle(
				$custom_css,
				[],
				['name' => 'plg_system_slscrolltotopghsvs.custom_css'],
			);
		}

		$wa->useScript('plg_system_slscrolltotopghsvs.jquery');

			$js			= <<<SCRIPTHERE
jQuery(document).ready(function() {
	jQuery(document.body).SLScrollToTop({
		'image':		'$image',
		'text':			'$text',
		'title':		'$title',
		'className':	'$class',
		'duration':		$duration
	});
});
SCRIPTHERE;


		$wa->addInlineScript(
			$js,
			[],
			['name' => 'plg_system_slscrolltotopghsvs.js'],
		);
	}

	private function clean(String $string) : string
	{
		return empty($string) ? '' : htmlspecialchars(Text::_($string), ENT_QUOTES,
			'UTF-8');
	}
}
