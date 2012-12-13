<?php

/**
 * View Html
 *
 * @package BibleStudy.Admin
 * @Copyright (C) 2007 - 2011 Joomla Bible Study Team All rights reserved
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link    http://www.JoomlaBibleStudy.org
 * */
//No Direct Access
defined('_JEXEC') or die;
require_once(JPATH_ADMINISTRATOR.'/components/com_biblestudy/helpers/biblestudy.php');

/**
 * View class for Comment
 *
 * @package BibleStudy.Admin
 * @since   7.0.0
 */
class BiblestudyViewComment extends JViewLegacy
{

	/**
	 * Form Data
	 *
	 * @var array
	 */
	protected $form;

	/**
	 * Item
	 *
	 * @var array
	 */
	protected $item;

	/**
	 * State
	 *
	 * @var array
	 */
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		//$input = new JInput; $a_id = $input->get('a_id','int'); dump($a_id);
        $this->form = $this->get("Form");
		$this->item = $this->get("Item"); 
		$this->state = $this->get("State");
		$this->canDo = JBSMHelper::getActions($this->item->id, 'comment');
        $document = JFactory::getDocument();
        $document->addStyleSheet(JURI::base() . 'administrator/templates/system/css/system.css');
        //$document->addStyleSheet(JURI::base() . 'administrator/templates/bluestork/css/template.css');
        $document->addStyleSheet(JURI::base().'administrator/templates/isis/css/template.css');
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			throw new Exception(implode("\n", $errors), 500);
			return false;
		}
        //check permissions to enter comments
        if (!$this->canDo->get('core.edit')) {
            JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
            return false;
        }
		// Set the toolbar
		$this->addToolbar();

		// Display the template
		parent::display($tpl);

		// Set the document
		$this->setDocument();
	}

	/**
	 * Adds ToolBar
	 *
	 * @since 7.0
	 */
	protected function addToolbar()
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);
		$isNew = ($this->item->id == 0);
		$title = $isNew ? JText::_('JBS_CMN_NEW') : JText::_('JBS_CMN_EDIT');
		JToolBarHelper::title(JText::_('JBS_CMN_COMMENTS') . ': <small><small>[ ' . $title . ' ]</small></small>', 'comments.png');

		if ($isNew && $this->canDo->get('core.create', 'com_biblestudy')) {
			JToolBarHelper::apply('comment.apply');
			JToolBarHelper::save('comment.save');
			JToolBarHelper::save2new('comment.save2new');
			JToolBarHelper::cancel('comment.cancel');
		} else {
			if ($this->canDo->get('core.edit', 'com_biblestudy')) {
				JToolBarHelper::apply('comment.apply');
				JToolBarHelper::save('comment.save');
			}
			JToolBarHelper::cancel('comment.cancel', 'JTOOLBAR_CLOSE');
		}
		JToolBarHelper::divider();
		JToolBarHelper::help('biblestudy', true);
	}

	/**
	 * Add the page title to browser.
	 *
	 * @since    7.1.0
	 */
	protected function setDocument()
	{
		$isNew = ($this->item->id < 1);
		$document = JFactory::getDocument();
		$document->setTitle($isNew ? JText::_('JBS_TITLE_COMMENT_CREATING') : JText::sprintf('JBS_TITLE_COMMENT_EDITING', $this->item->id));
	}

}