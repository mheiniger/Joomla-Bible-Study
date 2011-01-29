<?php
/**
 * Podcast list View for Bible Study Component
 * 
 * @license		GNU/GPL
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );


class biblestudyViewcommentslist extends JView
{
    protected $items;
    protected $pagination;
    protected $state;
	
	function display($tpl = null)
	{
		$lists = array();
		$params = &JComponentHelper::getParams($option);
		$db=& JFactory::getDBO();
		$uri	=& JFactory::getURI();
	$filter_order		= $mainframe->getUserStateFromRequest( $option.'filter_order',		'filter_order',		'published' );
	$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option.'filter_order_Dir',	'filter_order_Dir',	'ASC' );
	$filter_studyid		= $mainframe->getUserStateFromRequest( $option.'filter_studyid',		'filter_studyid',		0,				'int' );

// table order
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;
		
	/*$query5 = " SELECT DISTINCT date_format(comment_date, '%Y') AS value, date_format(comment_date, '%Y') AS text "
			. ' FROM #__bsms_comments '
			. ' ORDER BY value DESC';
		$db->setQuery( $query5 );
		$studyyear = $db->loadObjectList();
		$years[] 		= JHTML::_('select.option',  '0', '- '. JText::_( 'JBS_CMN_SELECT_YEAR' ) .' -' );
		$years 			= array_merge( $years, $db->loadObjectList() );
		$lists['years']	= JHTML::_('select.genericlist',   $years, 'filter_year', 'class="inputbox" size="1" onchange="this.form.submit()"', 'value', 'text', "$filter_year" );
*/
	$query6 = ' SELECT * FROM #__bsms_order '
		. ' ORDER BY id ';
		$db->setQuery( $query6 );
		$orders[] 		= JHTML::_('select.option',  '0', '- '. JText::_( 'JBS_CMN_SELECT_ORDER' ) .' -' );
		$orders 			= array_merge( $orders, $db->loadObjectList() );
		$lists['sorting']	= JHTML::_('select.genericlist',   $orders, 'filter_order', 'class="inputbox" size="1" onchange="this.form.submit()"', 'value', 'text', "$filter_order" );

$query = "SELECT id AS value, CONCAT(studytitle,' - ', studydate, ' - ', studynumber) AS text FROM #__bsms_studies WHERE published = 1 ORDER BY studydate DESC";
		$db->setQuery($query);
		$studies[] = JHTML::_('select.option', '0', '- '. JText::_( 'JBS_CMN_SELECT_STUDY' ) .' -' );
		$studies = array_merge($studies,$db->loadObjectList() );
		$lists['studyid'] = JHTML::_('select.genericlist', $studies, 'study_id', 'class="inputbox" size="1" onchange="this.form.submit()"', 'value', 'text', "$filter_studyid");		
	// Get data from the model
	$items		= & $this->get( 'Data');
	$total		= & $this->get( 'Total');
	$pagination = & $this->get( 'Pagination' );
	
	$javascript 	= 'onchange="document.adminForm.submit();"';
	//$lists['studyid'] = JHTML::_('list.category',  'filter_studyid', $option, intval( $filter_studyid ), $javascript );	
// build list of categories
		
	$this->assignRef('lists', $lists);
	$this->assignRef('items',		$items);
	$this->assignRef('pagination',	$pagination);
	$this->assignRef('request_url',	$uri->toString());
	
	$this->addToolbar();
	parent::display($tpl);
	}
	
    /**
     * Add the page title and toolbar
     *
     * @since 7.0
     */
    protected function addToolbar() {
        JToolBarHelper::title(JText::_('JBS_FLD_FOLDERS_MANAGER'), 'servers.png');
        JToolBarHelper::addNew('commentsedit.add');
        JToolBarHelper::editList('commentsedit.edit');
        JToolBarHelper::divider();
        JToolBarHelper::publishList('commentslist.publish');
        JToolBarHelper::unpublishList('commentslist.unpublish');
        JToolBarHelper::trash('commentslist.trash');    }
	
}
?>