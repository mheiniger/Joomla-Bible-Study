<?php

/**
 * @version $Id: jbspodcast.php 1 $
 * @package PLG_JBSPODCAST
 * @Copyright (C) 2007 - 2011 Joomla Bible Study Team All rights reserved
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.JoomlaBibleStudy.org
 **/

defined('_JEXEC') or die('Restricted access');

/* Import library dependencies */
jimport('joomla.plugin.plugin');
class plgSystemjbspodcast extends JPlugin {

	/**
	 * Constructor
	 *
	 * @access      protected
	 * @param       object  $subject The object to observe
	 * @param       array   $config  An array that holds the plugin configuration
	 * @since       1.5
	 * based on plg_weblinks
	 */

	public function __construct(& $subject, $config)
	{

		parent::__construct($subject, $config);

		$this->loadLanguage();
		$this->loadLanguage('com_biblestudy',JPATH_ADMINISTRATOR);

	}
	function onAfterInitialise() {


		$plugin =& JPluginHelper::getPlugin( 'system', 'jbspodcast' );
		$params = $this->params;

		//First check to see what method of updating the podcast we are using
		$method = $params->get('method','0');
		if ($method == '0')
		{
			$check = $this->checktime($params);
		}
		else
		{
			$check = $this->checkdays($params);
		}
		if ($check)
		{
			//perform the podcast and email and update time
			$dopodcast = $this->doPodcast();

			//If we have run the podcastcheck and it returned no errors then the last thing we do is reset the time we did it to current
		//	if ($dopodcast)
		//	{
				$updatetime = $this->updatetime();
		//	}

			// Last we check to see if we need to email anything
			if ($params->get('email')> 0)
			{
				if ($params->get('email') > 1)
                {
                    $iserror = substr_count($dopodcast,'not');
                    if ($iserror)
                    {
                        $email = $this->doEmail($params, $dopodcast);
                    }
                }
                else
                {$email = $this->doEmail($params, $dopodcast);}
			}
		}

	}

	function checktime($params)
	{

		$now = time();
		$db = JFactory::getDBO();
		$db->setQuery('SELECT `timeset` FROM `#__jbspodcast_timeset`', 0, 1);
		$result = $db->loadObject();
		$lasttime = $result->timeset;
		$frequency = $params->get('xhours','86400');
		$difference = $frequency * 3600;
		$checkit = $now - $lasttime;
		if ($checkit > $difference) {
			return true;
		}
		else {return false;
		}
	}

	function checkdays($params)
	{
		$checkdays = FALSE;
		$config =& JFactory::getConfig();
		$offset = $config->getValue('config.offset');

		$now = time();
		$db = JFactory::getDBO();
		$db->setQuery('SELECT `timeset` FROM `#__jbspodcast_timeset`', 0, 1);
		$result = $db->loadObject();
		$lasttime = $result->timeset;
		$difference = $now - $lasttime;
		$date = getdate($now);
		$day = $date['wday'];
		$systemhour = $date['hours'];
		if ($params->get('offset', '0') > 0){
			$hour = $systemhour + $offset;
		} else {$hour = $systemhour;
		}

		if ($params->get('day1')== $day && $params->get('hour1') == $hour && $difference > 3600)
		{
			$checkdays = TRUE;
		}
		if ($params->get('day2')== $day)
		{
			if ($params->get('hour2') == $hour && $difference > 3600)
			{
				$checkdays = TRUE;
			}
		}
		if ($params->get('day3')== $day)
		{
			if ($params->get('hour3') == $hour && $difference > 3600)
			{
				$checkdays = TRUE;
			}
		}
		if ($params->get('day4')== $day)
		{
			if ($params->get('hour4') == $hour && $difference > 3600)
			{
				$checkdays = TRUE;
			}
		}
		if ($params->get('day5')== $day)
		{
			if ($params->get('hour5') == $hour && $difference > 3600)
			{
				$checkdays = TRUE;
			}
		}
		if ($params->get('day6')== $day)
		{
			if ($params->get('hour6') == $hour && $difference > 3600)
			{
				$checkdays = TRUE;
			}
		}
		if ($params->get('day7')== $day)
		{
			if ($params->get('hour7') == $hour && $difference > 3600)
			{
				$checkdays = TRUE;
			}
		}
		if ($params->get('day8')== $day)
		{
			if ($params->get('hour8') == $hour && $difference > 3600)
			{
				$checkdays = TRUE;
			}
		}
		if ($params->get('day9')== $day)
		{
			if ($params->get('hour9') == $hour && $difference > 3600)
			{
				$checkdays = TRUE;
			}
		}
		if ($params->get('day10')== $day)
		{
			if ($params->get('hour10') == $hour && $difference > 3600)
			{
				$checkdays = TRUE;
			}
		}

		return $checkdays;
	}
	function updatetime()
	{
		$time = time();
		$db = JFactory::getDBO();
		$db->setQuery('UPDATE `#__jbspodcast_timeset` SET `timeset` = '.$time);
		$db->query();
		$updateresult = $db->getAffectedRows();
		if ($updateresult > 0) {
			return true;
		} else {return false;
		}
	}

	function doPodcast()
	{
		$path1 = JPATH_SITE . '/plugins/system/jbspodcast/';
        require_once($path1.'biblestudy.podcast.class.php');
        $podcasts = new JBSPodcast();
        $result = $podcasts->makePodcasts();
        return $result;
        /*
        $path1 = JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_biblestudy'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR;
		include_once($path1.'writexml.php');
		$podcast = writeXML();
		return $podcast;
        */
	}

	function doEmail($params, $dopodcast)
	{
		
        $livesite = JURI::root();
		$config = JFactory::getConfig();
		$mailfrom   = $config->getValue('config.mailfrom');
		$fromname   = $config->getValue('config.fromname');
		jimport('joomla.filesystem.file');

		$mail = JFactory::getMailer();
		$mail->IsHTML(true);
		jimport('joomla.utilities.date');
		$year = '('.date('Y').')';
		$date = date('r');
		$Body   = $params->def( 'Body', '<strong>'.JText::_('PLG_JBSPODCAST_TITLE').': '.$fromname.'</strong><br />' );
		$Body .= JText::_('Process run at: ').$date.'<br />';
		$Body2 = '';
        $Body2 = $dopodcast;
        /*
		$db =& JFactory::getDBO();
		$query = 'SELECT * FROM #__bsms_podcast WHERE #__bsms_podcast.published = 1';
		$db->setQuery($query);
		$podid = $db->loadObjectList();
		//Here we get links to the actual podcast files
		if (count($podid))
		{
			foreach ($podid as $podids2)
			{
				$file = JURI::root().$podids2->filename;
				$Body2 .= '<br><a href="'.$file.'">'.$podids2->title.'</a>';
				if (!$dopodcast){
					$Body2 .= ' - '.JText::_('PLG_JBSPODCAST_ERRORS');
				}
				if ($dopodcast){
					$Body2 .= ' - '.JText::_('PLG_JBSPODCAST_NO_ERRORS');
				}
			}
		}
        */
		$Body3 = $Body.$Body2;
		$Subject       = $params->def( 'subject', JText::_('PLG_JBSPODCAST_UPDATE') );
		$FromName       = $params->def( 'fromname', $fromname );

		$recipients = explode(",",$params->get('recipients'));
		foreach ($recipients AS $recipient)
		{
			$mail->addRecipient($recipient);
			$mail->setSubject($Subject.' '.$livesite);
			$mail->setBody($Body3);
			$mail->Send();
		}

	}
}