<?php

/**
 * @version     $Id: mediaimage.php 2025 2011-08-28 04:08:06Z genu $
 * @package BibleStudy
 * @Copyright (C) 2007 - 2011 Joomla Bible Study Team All rights reserved
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.JoomlaBibleStudy.org
 **/
//No Direct Access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

abstract class controllerClass extends JControllerForm {

}

class BiblestudyControllerMediaimage extends controllerClass
{
	/*
	 * NOTE: This is needed to prevent Joomla 1.6's pluralization mechanisim from kicking in
	*
	* @todo  BCC  We should rename this controler to "mediafile" and the list view controller
	* to "mediafiles" so that the pluralization in 1.6 would work properly
	*
	* @since 7.0
	*/
	protected $view_list = 'mediaimages';

	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();

	}



}