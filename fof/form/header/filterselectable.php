<?php
/**
 * @package    FrameworkOnFramework
 * @subpackage form
 * @copyright  Copyright (C) 2010 - 2014 Akeeba Ltd. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace FOF30\Form\Header;

// Protect from unauthorized access
defined('FOF30_INCLUDED') or die;

/**
 * Generic filter, drop-down based on fixed options
 *
 * @package  FrameworkOnFramework
 * @since    2.0
 */
class Filterselectable extends F0FFormHeaderFieldselectable
{
	/**
	 * Get the header
	 *
	 * @return  string  The header HTML
	 */
	protected function getHeader()
	{
		return '';
	}
}
