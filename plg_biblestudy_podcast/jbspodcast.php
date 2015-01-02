<?php
/**
 * @package     BibleStudy
 * @subpackage  Plugin.JBSPodcast
 * @copyright   (C) 2007 - 2014 Joomla Bible Study Team All rights reserved
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link        http://www.JoomlaBibleStudy.org
 * */
defined('_JEXEC') or die;

/* Import library dependencies */
jimport('joomla.plugin.plugin');

/**
 * Podcast plugin class
 *
 * @package     BibleStudy
 * @subpackage  Plugin.JBSPodcast
 * @since       7.0.0
 */
class PlgSystemJbspodcast extends JPlugin
{

	/**
	 * Constructor
	 *
	 * @param   object &$subject   The object to observe
	 * @param   array  $config     An optional associative array of configuration settings.
	 *                             Recognized key values include 'name', 'group', 'params', 'language'
	 *                             (this list is not meant to be comprehensive).
	 */
	public function __construct(& $subject, $config)
	{

		parent::__construct($subject, $config);

		$this->loadLanguage();
		$this->loadLanguage('com_biblestudy', JPATH_ADMINISTRATOR);
	}

	/**
	 * Initialise
	 *
	 * @return void
	 */
	public function onAfterInitialise()
	{

		JPluginHelper::getPlugin('system', 'jbspodcast');
		$params = $this->params;

		// First check to see what method of updating the podcast we are using
		$method = $params->get('method', '0');

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
			// Perform the podcast and email and update time
			$dopodcast = $this->doPodcast();

			// Update the database to show a new time
			$this->updatetime();

			// Last we check to see if we need to email anything
			if ($params->get('email') > 0)
			{
				if ($params->get('email') > 1)
				{
					if (substr_count($dopodcast, 'not') || $dopodcast == false)
					{
						$iserror = 1;
					}
					else
					{
						$iserror = 0;
					}

					if ($iserror)
					{
						$this->doEmail($params, $dopodcast);
					}
				}
				else
				{
					$this->doEmail($params, $dopodcast);
				}
			}
		}
	}

	/**
	 * Check Time
	 *
	 * @param   object $params Plugin params
	 *
	 * @return boolean
	 */
	public function checktime($params)
	{

		$now   = time();
		$db    = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('timeset')->from('#__jbspodcast_timeset');
		$db->setQuery($query, 0, 1);
		$result     = $db->loadObject();
		$lasttime   = $result->timeset;
		$frequency  = $params->get('xhours', '86400');
		$difference = $frequency * 3600;
		$checkit    = $now - $lasttime;

		if ($checkit > $difference)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Check Days
	 *
	 * @param   object $params Plugin params
	 *
	 * @return boolean
	 */
	public function checkdays($params)
	{
		$checkdays = false;
		$config    = & JFactory::getConfig();
		$offset    = $config->get('config.offset');

		$now   = time();
		$db    = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('timeset')->from('#__jbspodcast_timeset');
		$db->setQuery($query, 0, 1);
		$result     = $db->loadObject();
		$lasttime   = $result->timeset;
		$difference = $now - $lasttime;
		$date       = getdate($now);
		$day        = $date['wday'];
		$systemhour = $date['hours'];

		if ($params->get('offset', '0') > 0)
		{
			$hour = $systemhour + $offset;
		}
		else
		{
			$hour = $systemhour;
		}

		if ($params->get('day1') == $day && $params->get('hour1') == $hour && $difference > 3600)
		{
			$checkdays = true;
		}
		if ($params->get('day2') == $day)
		{
			if ($params->get('hour2') == $hour && $difference > 3600)
			{
				$checkdays = true;
			}
		}
		if ($params->get('day3') == $day)
		{
			if ($params->get('hour3') == $hour && $difference > 3600)
			{
				$checkdays = true;
			}
		}
		if ($params->get('day4') == $day)
		{
			if ($params->get('hour4') == $hour && $difference > 3600)
			{
				$checkdays = true;
			}
		}
		if ($params->get('day5') == $day)
		{
			if ($params->get('hour5') == $hour && $difference > 3600)
			{
				$checkdays = true;
			}
		}
		if ($params->get('day6') == $day)
		{
			if ($params->get('hour6') == $hour && $difference > 3600)
			{
				$checkdays = true;
			}
		}
		if ($params->get('day7') == $day)
		{
			if ($params->get('hour7') == $hour && $difference > 3600)
			{
				$checkdays = true;
			}
		}
		if ($params->get('day8') == $day)
		{
			if ($params->get('hour8') == $hour && $difference > 3600)
			{
				$checkdays = true;
			}
		}
		if ($params->get('day9') == $day)
		{
			if ($params->get('hour9') == $hour && $difference > 3600)
			{
				$checkdays = true;
			}
		}
		if ($params->get('day10') == $day)
		{
			if ($params->get('hour10') == $hour && $difference > 3600)
			{
				$checkdays = true;
			}
		}

		return $checkdays;
	}

	/**
	 * Do Podcast
	 *
	 * @return object
	 */
	public function doPodcast()
	{
		$podcasts = new JBSMPodcast;
		$result   = $podcasts->makePodcasts();

		return $result;
	}

	/**
	 * Update Time
	 *
	 * @return boolean
	 */
	public function updatetime()
	{
		$time  = time();
		$db    = JFactory::getDBO();
		$query = 'UPDATE #__jbspodcast_timeset SET `timeset` = ' . $time;
		$db->setQuery($query);
		$db->execute();
		$updateresult = $db->getAffectedRows();

		if ($updateresult > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Do Email
	 *
	 * @param   JRegistry $params Plugin params
	 * @param   object    $dopodcast  ?
	 *
	 * @return void
	 */
	public function doEmail($params, $dopodcast)
	{

		$livesite = JURI::root();
		$config   = JFactory::getConfig();
		$mailfrom = $config->get('config.mailfrom');
		$fromname = $config->get('config.fromname');
		jimport('joomla.filesystem.file');

		$mail = JFactory::getMailer();
		$mail->IsHTML(true);
		jimport('joomla.utilities.date');
		$year = '(' . date('Y') . ')';
		$date = date('r');
		$Body = $params->get('body') . '<br />';
		$Body .= JText::_('JBS_PLG_PODCAST_EMAIL_BODY_RUN') . $date . '<br />';
		$Body2    = '';
		$Body2    = $dopodcast;
		$Body3    = $Body . $Body2;
		$Subject  = $params->get('subject');
		$FromName = $params->def('fromname', $fromname);

		$recipients = explode(",", $params->get('recipients'));

		foreach ($recipients AS $recipient)
		{
			$mail->addRecipient($recipient);
			$mail->setSubject($Subject . ' ' . $livesite);
			$mail->setBody($Body3);
			$mail->Send();
		}
	}

}
