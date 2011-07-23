<?php
/**
* @version		$Id: default.php 8591 2007-08-27 21:09:32Z Tom Fuller $
* @package		mod_biblestudy
* @copyright            2010-2011
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die(); 

if ($this->params->get('useexpert_module')> 0)
     {
     	echo $this->loadTemplate('custom');
	 }
else
	{
		echo $this->loadTemplate('main');
	}