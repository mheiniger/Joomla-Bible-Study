<?php
/**
 * teacherlist View for Bible Study Component
 * 
 * @license		GNU/GPL
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

/**
 * teacherlist View
 *
 */
class biblestudyViewsharelist extends JView
{
	/**
	 * teacherlist view display method
	 * @return void
	 **/
	function display($tpl = null)
	{
		global $mainframe, $option; 
		$params = &JComponentHelper::getParams($option);
		JToolBarHelper::title(   JText::_( 'Social Network Manager' ), 'generic.png' );
		//Checks to see if the admin allows rows to be deleted
		JToolBarHelper::deleteList();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
		JToolBarHelper::editListX();
		JToolBarHelper::addNewX();
		//JToolBarHelper::preferences('com_biblestudy', '550');
		jimport( 'joomla.i18n.help' );
		//JToolBarHelper::help( 'biblestudy.teachers', true );
		$uri	=& JFactory::getURI();
		
		// Get data from the model
		$items		= & $this->get( 'Data');
		$total		= & $this->get( 'Total');
		$pagination = & $this->get( 'Pagination' );
		
	$this->assignRef('items',		$items);
	$this->assignRef('pagination',	$pagination);
// table order
		
		$this->assignRef('lists', $lists);
		$this->assignRef('items',		$items);
		$this->assignRef('request_url',	$uri->toString());
		parent::display($tpl);
	}
}
?>