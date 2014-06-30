<?php
/**
 * @package     FrameworkOnFramework
 * @subpackage  render
 * @copyright   Copyright (C) 2010 - 2014 Akeeba Ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace FOF30\Render;

defined('FOF30_INCLUDED') or die;

/**
 * Joomla! 3 view renderer class
 *
 * @package  FrameworkOnFramework
 * @since    2.0
 */
class Joomla3 extends Strapper
{
	/**
	 * Public constructor. Determines the priority of this class and if it should be enabled
	 */
	public function __construct()
	{
		$this->priority	 = 55;
		$this->enabled	 = version_compare(JVERSION, '3.0', 'ge');
	}

	/**
	 * Echoes any HTML to show before the view template
	 *
	 * @param   string    $view    The current view
	 * @param   string    $task    The current task
	 * @param   F0FInput  $input   The input array (request parameters)
	 * @param   array     $config  The view configuration array
	 *
	 * @return  void
	 */
	public function preRender($view, $task, $input, $config = array())
	{
		$format	 = $input->getCmd('format', 'html');

		if (empty($format))
		{
			$format	 = 'html';
		}

		if ($format != 'html')
		{
			return;
		}

		$platform = F0FPlatform::getInstance();

		if ($platform->isCli())
		{
			return;
		}

		if ($platform->isBackend())
		{
			// Wrap output in various classes
			$version = new JVersion;
			$versionParts = explode('.', $version->RELEASE);
			$minorVersion = str_replace('.', '', $version->RELEASE);
			$majorVersion = array_shift($versionParts);

			$option = $input->getCmd('option', '');
			$view = $input->getCmd('view', '');
			$layout = $input->getCmd('layout', '');
			$task = $input->getCmd('task', '');
			$itemid = $input->getCmd('Itemid', '');

			$classes = ' class="' . implode(array(
				'joomla-version-' . $majorVersion,
				'joomla-version-' . $minorVersion,
				'admin',
				$option,
				'view-' . $view,
				'layout-' . $layout,
				'task-' . $task,
				'itemid-' . $itemid,
			), ' ') . '"';
		}
		else
		{
			$classes = '';
		}

		echo '<div id="akeeba-renderjoomla"' . $classes . ">\n";

		// Render the submenu and toolbar
		if ($input->getBool('render_toolbar', true))
		{
			$this->renderButtons($view, $task, $input, $config);
			$this->renderLinkbar($view, $task, $input, $config);
		}
	}

	/**
	 * Echoes any HTML to show after the view template
	 *
	 * @param   string    $view    The current view
	 * @param   string    $task    The current task
	 * @param   F0FInput  $input   The input array (request parameters)
	 * @param   array     $config  The view configuration array
	 *
	 * @return  void
	 */
	public function postRender($view, $task, $input, $config = array())
	{
		$format	 = $input->getCmd('format', 'html');

		if (empty($format))
		{
			$format	 = 'html';
		}

		if ($format != 'html')
		{
			return;
		}

		// Closing tag only if we're not in CLI
		if (F0FPlatform::getInstance()->isCli())
		{
			return;
		}

		echo "</div>\n";    // Closes akeeba-renderjoomla div
	}

	/**
	 * Renders the submenu (link bar)
	 *
	 * @param   string    $view    The active view name
	 * @param   string    $task    The current task
	 * @param   F0FInput  $input   The input object
	 * @param   array     $config  Extra configuration variables for the toolbar
	 *
	 * @return  void
	 */
	protected function renderLinkbar($view, $task, $input, $config = array())
	{
		$style = 'joomla';

		if (array_key_exists('linkbar_style', $config))
		{
			$style = $config['linkbar_style'];
		}

		switch ($style)
		{
			case 'joomla':
				$this->renderLinkbar_joomla($view, $task, $input);
				break;

			case 'classic':
			default:
				$this->renderLinkbar_classic($view, $task, $input);
				break;
		}
	}

	/**
	 * Renders a label for a fieldset.
	 *
	 * @param   object  	$field  	The field of the label to render
	 * @param   F0FForm   	&$form      The form to render
	 * @param 	string		$title		The title of the label
	 *
	 * @return 	string		The rendered label
	 */
	protected function renderFieldsetLabel($field, F0FForm &$form, $title)
	{
		$html = '';

		$labelClass	 = $field->labelClass;
		$required	 = $field->required;

		$tooltip = $form->getFieldAttribute($field->fieldname, 'tooltip', '', $field->group);

		if (!empty($tooltip))
		{
			JHtml::_('bootstrap.tooltip');

			$tooltipText = '<strong>' . JText::_($title) . '</strong><br />' . JText::_($tooltip);

			$html .= "\t\t\t\t" . '<label class="control-label hasTooltip ' . $labelClass . '" for="' . $field->id . '" title="' . $tooltipText . '" rel="tooltip">';
		}
		else
		{
			$html .= "\t\t\t\t" . '<label class="control-label ' . $labelClass . '" for="' . $field->id . '">';
		}

		$html .= JText::_($title);

		if ($required)
		{
			$html .= ' *';
		}

		$html .= '</label>' . PHP_EOL;

		return $html;
	}
}
