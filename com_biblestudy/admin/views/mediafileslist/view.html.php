<?php

/**
 * @version     $Id: view.html.php 1466 2011-01-31 23:13:03Z bcordis $
 * @package     com_biblestudy
 * @license     GNU/GPL
 */
//No Direct Access
defined('_JEXEC') or die();
require_once (JPATH_ADMINISTRATOR  .DS. 'components' .DS. 'com_biblestudy' .DS. 'lib' .DS. 'biblestudy.defines.php');
jimport('joomla.application.component.view');

/**
 * @package     BibleStudy.Administrator
 * @since       7.0
 */
class biblestudyViewmediafileslist extends JView {

    protected $items;
    protected $pagination;
    protected $state;

    function display($tpl = null) {
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->state = $this->get('State');
        $this->mediatypes = $this->get('Mediatypes');

        //Check for errors
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode("\n", $errors));
            return false;
        }

        $this->addToolbar();
        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar
     *
     * @since 7.0
     */
    protected function addToolbar() {
        JToolBarHelper::title(JText::_('JBS_MED_MEDIA_FILES_MANAGER'), 'mp3.png');
        JToolBarHelper::addNew('mediafilesedit.add');
        JToolBarHelper::editList('mediafilesedit.edit');
        JToolBarHelper::divider();
        JToolBarHelper::publishList('mediafileslist.publish');
        JToolBarHelper::unpublishList('mediafileslist.unpublish');
        if($this->state->get('filter.state') == -2)
            JToolBarHelper::deleteList('', 'mediafileslist.delete','JTOOLBAR_EMPTY_TRASH');
        else
            JToolBarHelper::trash('mediafileslist.trash');
    }

}

?>