<?php


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );


class biblestudyViewtopicslist extends JView
{
	/**
	 * Topicslist view display method
	 * @return void
	 **/
	function display($tpl = null)
	{
		$mainframe =& JFactory::getApplication();, $option; 
		$params = &JComponentHelper::getParams($option);
		JHTML::_('stylesheet', 'icons.css', JURI::base().'components/com_biblestudy/css/');
		JToolBarHelper::title(   JText::_( 'Topics Manager' ), 'topics.png' );
		//Checks to see if the admin allows rows to be deleted
		JToolBarHelper::deleteList();
		JToolBarHelper::editListX();
		JToolBarHelper::addNewX();
		//JToolBarHelper::preferences('com_biblestudy', '550');
		jimport( 'joomla.i18n.help' );
		JToolBarHelper::help( 'biblestudy', true );

		// Get data from the model
		$items		= & $this->get( 'Data');

		$this->assignRef('items',		$items);

		parent::display($tpl);
	}
}
?>