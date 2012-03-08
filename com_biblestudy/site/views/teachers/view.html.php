<?php

/**
 * @version     $Id: view.html.php 1330 2011-01-06 08:01:38Z genu $
 * @package     com_biblestudy
 * @license     GNU/GPL
 */
//No Direct Access
defined('_JEXEC') or die;

require_once (JPATH_SITE . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_biblestudy' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'biblestudy.defines.php');
require_once (JPATH_ROOT  .DIRECTORY_SEPARATOR. 'components' .DIRECTORY_SEPARATOR. 'com_biblestudy' .DIRECTORY_SEPARATOR. 'lib' .DIRECTORY_SEPARATOR. 'biblestudy.images.class.php');
$path1 = JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_biblestudy'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR;
include_once($path1.'image.php');
jimport('joomla.application.component.view');

class BiblestudyViewTeachers extends JView {

    protected $items;
    protected $pagination;
    protected $state;
    /**
     * teacherlist view display method
     * @return void
     * */
    function display($tpl = null) {

        //Load the Admin settings and params from the template
        $this->addHelperPath(JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'helpers');
        $this->loadHelper('params');
        $this->admin = BsmHelper::getAdmin(true);

       
        $template = $this->get('template');
        // Convert parameter fields to objects.
        $registry = new JRegistry;
        $registry->loadJSON($template->params);
        $params = $registry;
        $t = $params->get('teachertemplateid');
        if (!$t) {$t = JRequest::getVar('t', 1, 'get', 'int');}
        $a_params = $this->get('Admin');
        // Convert parameter fields to objects.
        $registry = new JRegistry;
        $registry->loadJSON($a_params[0]->params);
        $this->admin_params = $registry;
        $mainframe = JFactory::getApplication();
        $document = JFactory::getDocument();
        $itemparams = $mainframe->getPageParameters();
        $uri = JFactory::getURI();
        //Prepare meta information (under development)
        if ($itemparams->get('metakey')) {
            $document->setMetadata('keywords', $itemparams->get('metakey'));
        } elseif (!$itemparams->get('metakey')) {
            $document->setMetadata('keywords', $this->admin_params->get('metakey'));
        }

        if ($itemparams->get('metadesc')) {
            $document->setDescription($itemparams->get('metadesc'));
        } elseif (!$itemparams->get('metadesc')) {
            $document->setDescription($this->admin_params->get('metadesc'));
        }
        $document->addScript(JURI::base() . 'media/com_biblestudy/js/jquery.js');
        $document->addScript(JURI::base() . 'media/com_biblestudy/js/biblestudy.js');
        $document->addScript(JURI::base() . 'media/com_biblestudy/js/tooltip.js');
        $document->addScript(JURI::base() . 'media/com_biblestudy/player/jwplayer.js');

        //Import Stylesheets
        $document->addStylesheet(JURI::base() . 'media/com_biblestudy/css/general.css');
        $document->addStylesheet(JURI::base() . 'media/com_biblestudy/css/studieslist.css');
        $css = $params->get('css','biblestudy.css');
        $document->addStyleSheet(JURI::base() . 'media/com_biblestudy/css/site/'.$css);
        $images = new jbsImages();
        // Get data from the model
        $items = & $this->get('Items');
        
        foreach ($items as $i=>$item)
        {
            $items[$i]->image = $images->getTeacherThumbnail($item->teacher_thumbnail, $item->thumb);
            $items[$i]->teacherlink = JRoute::_('index.php?option=com_biblestudy&view=teacher&id='.$item->slug.'&t='.$t);
            $items[$i]->slug = $item->alias ? ($item->id . ':' . $item->alias) : $item->id . ':' . str_replace(' ', '-', htmlspecialchars_decode($item->teachername, ENT_QUOTES));
        }
                
        $menu = & JSite::getMenu();
        //	$item =& $menu->getActive();

        $pagination = $this->get('Pagination');
        $this->page->pagelinks = $pagination->getPagesLinks();
        $this->page->counter = $pagination->getPagesCounter();
        $this->assignRef('pagination', $pagination);
        $this->assignRef('items', $items);

        $this->assignRef('request_url', $uri->toString());
        $this->assignRef('params', $params);

        parent::display($tpl);
    }

}