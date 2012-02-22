<?php

/**
 * @version $Id: biblestudy.podcast.class.php 1 $
 * @package BibleStudy
 * @Copyright (C) 2007 - 2011 Joomla Bible Study Team All rights reserved
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.JoomlaBibleStudy.org
 **/
//No Direct Access
defined('_JEXEC') or die;
jimport('joomla.html.parameter');
$path1 = JPATH_SITE.'/components/com_biblestudy/helpers/';
include_once($path1.'custom.php');
include_once($path1.'helper.php');
include_once($path1.'scripture.php');
$this->admin_params = getAdminsettings();

class JBSPodcast
{
    function makePodcasts()
    {
        $msg = array();
        $db = JFactory::getDBO();
        jimport('joomla.utilities.date');
		$year = '('.date('Y').')';
		$date = date('r');
        //First get all of the podcast that are published
        $query = 'SELECT * FROM #__bsms_podcast WHERE #__bsms_podcast.published = 1';
		$db->setQuery($query);
		$podids = $db->loadObjectList();


        //Now iterate through the podcasts, and pick up the mediafiles
        if ($podids)
		  {
		      foreach ($podids AS $podinfo)
              {
                //check to see if there is a media file associated - if not, don't continue
                $query = 'SELECT id FROM #__bsms_mediafiles WHERE podcast_id LIKE "%'.$podinfo->id.'%" AND published = 1';
                $db->setQuery($query);
                $checkresult = $db->loadObjectList();
                if ($checkresult)
                {
                $description = str_replace("&","and",$podinfo->description);
				$detailstemplateid = $podinfo->detailstemplateid;
				if (!$detailstemplateid) {$detailstemplateid = 1;}
		  		$detailstemplateid = '&amp;t='.$detailstemplateid;
				$podhead = '<?xml version="1.0" encoding="utf-8"?>
                <rss xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd" version="2.0">
                <channel>
                	<title>'.$podinfo->title.'</title>
                	<link>http://'.$podinfo->website.'</link>
                	<description>'.$description.'</description>
                	<itunes:summary>'.$description.'</itunes:summary>
                	<itunes:subtitle>'.$podinfo->title.'</itunes:subtitle>
                	<image>
                		<link>http://'.$podinfo->website.'</link>
                		<url>http://'.$podinfo->image.'</url>
                		<title>'.$podinfo->title.'</title>
                		<height>'.$podinfo->imageh.'</height>
                		<width>'.$podinfo->imagew.'</width>
                	</image>
                	<itunes:image href="http://'.$podinfo->podcastimage.'" />
                	<category>Religion &amp; Spirituality</category>
                	<itunes:category text="Religion &amp; Spirituality">
                		<itunes:category text="Christianity" />
                	</itunes:category>
                	<language>'.$podinfo->language.'</language>
                	<copyright>'.$year.' All rights reserved.</copyright>
                	<pubDate>'.$date.'</pubDate>
                	<lastBuildDate>'.$date.'</lastBuildDate>
                	<generator>Joomla Bible Study</generator>
                	<managingEditor>'.$podinfo->editor_email.' ('.$podinfo->editor_name.')</managingEditor>
                	<webMaster>'.$podinfo->editor_email.' ('.$podinfo->editor_name.')</webMaster>
                	<itunes:owner>
                		<itunes:name>'.$podinfo->editor_name.'</itunes:name>
                		<itunes:email>'.$podinfo->editor_email.'</itunes:email>
                	</itunes:owner>
                	<itunes:author>'.$podinfo->editor_name.'</itunes:author>
                	<itunes:explicit>no</itunes:explicit>
                	<ttl>1</ttl>
                	<atom:link href="http://'.$podinfo->website.'/'.$podinfo->filename.'" rel="self" type="application/rss+xml" />';
    				//Now let's get the podcast episodes
    				$limit = $podinfo->podcastlimit;
    				if ($limit > 0)
    				{$limit = 'LIMIT '.$limit;}
    				else {$limit = '';}
    				$episodes = $this->getEpisodes($podinfo->id, $limit);
                    $registry = new JRegistry;
                    $podinfo->params = array('{"show_verses":"1"');
    				$registry->loadJSON($podinfo->params);
                    $params = $registry;
                    $params->set('show_verses', '1');
    				if (!$episodes){return false;}
                    $episodedetail = '';
                    foreach ($episodes as $episode)
				        {
        					
                            $episodedate = date("r",strtotime($episode->createdate));
        					$hours = $episode->media_hours;
        					if (!$hours) { $hours = '00'; }
        					if ($hours < 1) { $hours = '00'; }
        					if ($hours > 0) { $hours = $hours; }
        					else { $hours = '00'; }
        					if (!$episode->media_seconds) {$episode->media_seconds = 1;}

        			        $esv = 0;
        					$scripturerow = 1;
        			        $episode->id = $episode->study_id;
        			        $scripture = getScripture($params, $episode, $esv, $scripturerow);
        					$pod_title = $podinfo->episodetitle;
        			         if (!$episode->size){$episode->size = '1024';}
        					switch ($pod_title)
        					{
        						case 0:
        							$title = $scripture.' - '.$episode->studytitle;
        							break;
        						case 1:
        							$title = $episode->studytitle;
        							break;
        						case 2:
        							$title = $scripture;
        							break;
        						case 3:
        							$title = $episode->studytitle.' - '.$scripture;
        							break;
        						case 4:
        							$title = $episodedate.' - '.$scripture.' - '.$episode->studytitle;
        							break;
        						case 5:
        							$element = getCustom($rowid='row1col1', $podinfo->custom, $episode, $params, $admin_params, $detailstemplateid);

        							$title = $element->element;
        							break;
        					}

        					$title = str_replace('&',"and",$title);
        					$description = str_replace('&',"and",$episode->studyintro);
        					$episodedetailtemp = '';
        					$episodedetailtemp = '
                        	   <item>
                        		<title>'.$title.'</title>
                        		<link>http://'.$podinfo->website.'/index.php?'.rawurlencode('option=com_biblestudy&view=studydetails&id=').$episode->sid.$detailstemplateid.'</link>
                        		<comments>http://'.$podinfo->website.'/index.php?'.rawurlencode('option=com_biblestudy&view=studydetails&id=').$episode->sid.$detailstemplateid.'</comments>
                        		<itunes:author>'.$episode->teachername.'</itunes:author>
                        		<dc:creator>'.$episode->teachername.'</dc:creator>
                        		<description>'.$description.'</description>
                        		<content:encoded>'.$description.'</content:encoded>
                        		<pubDate>'.$episodedate.'</pubDate>
                        		<itunes:subtitle>'.$title.'</itunes:subtitle>
                        		<itunes:summary>'.$description.'</itunes:summary>
                        		<itunes:keywords>'.$podinfo->podcastsearch.'</itunes:keywords>
                        		<itunes:duration>'.$hours.':'.sprintf("%02d", $episode->media_minutes).':'.sprintf("%02d", $episode->media_seconds).'</itunes:duration>';
                        					//Here is where we test to see if the link should be an article or docMan link, otherwise it is a mediafile
                        					if ($episode->article_id > 1)
                        						{
                        							$episodedetailtemp .=
                        							'<enclosure url="http://'.$episode->server_path.'/index.php?option=com_content&amp;view=article&amp;id='.$episode->article_id.'" length="'.$episode->size.'" type="'
                        							.$episode->mimetype.'" />
                        			<guid>http://'.$episode->server_path.'/index.php?option=com_content&amp;view=article&amp;id='.$episode->article_id.'</guid>';

                        						}
                        					if ($episode->docMan_id > 1)
                        						{
                        							$episodedetailtemp .=
                        							'<enclosure url="http://'.$episode->server_path.'/index.php?option=com_docman&amp;task=doc_download&amp;gid='.$episode->docMan_id.'" length="'.$episode->size.'" type="'
                        							.$episode->mimetype.'" />
                        			<guid>http://'.$episode->server_path.'/index.php?option=com_docman&amp;task=doc_download&amp;gid='.$episode->docMan_id.'</guid>';
                        						}
                        					else
                        						{
                        							$episodedetailtemp .=
                        							'<enclosure url="http://'.$episode->server_path.$episode->folderpath.str_replace(' ',"%20",$episode->filename).'" length="'.$episode->size.'" type="'
                        							.$episode->mimetype.'" />
                        			<guid>http://'.$episode->server_path.$episode->folderpath.str_replace(' ',"%20",$episode->filename).'</guid>';
                        						}
                        					$episodedetailtemp .= '
                        		<itunes:explicit>no</itunes:explicit>
                        	       </item>';
                					$episodedetail = $episodedetail.$episodedetailtemp;
                				} //end of foreach for episode details

                        $podfoot = '
                        </channel>
                        </rss>';
                        $client =& JApplicationHelper::getClientInfo(JRequest::getVar('client', '0', '', 'int'));
                        $file = $client->path.DIRECTORY_SEPARATOR.$podinfo->filename;
                        $filecontent = $podhead.$episodedetail.$podfoot;
                        $filewritten = $this->writeFile($file, $filecontent);
                        if (!$filewritten){$msg[] = $file.' - '.JText::_('JBS_CMN_FILES_FAILURE'); }
                        else {$msg[] = $file.' - '.JText::_('JBS_CMN_FILES_SUCCESS');}
                    } // end if $checkresult if positive
             }//end foreach podids as podinfo
          }
      $message = '';
      foreach ($msg as $m)
      {$message .= $m.'<br />';}
      if (!$message){$message = 'No message';}
     return $message;
    }



    function getEpisodes($id, $limit)
    {
        //here's where we look at each mediafile to see if they are connected to this podcast
        $db = JFactory::getDBO();
   		$query = 'SELECT p.id AS pid, p.podcastlimit,'
		. ' mf.id AS mfid, mf.study_id, mf.server, mf.path, mf.filename, mf.size, mf.mime_type, mf.podcast_id, mf.published AS mfpub, mf.createdate, mf.params,'
		. ' mf.docMan_id, mf.article_id,'
		. ' s.id AS sid, s.studydate, s.teacher_id, s.booknumber, s.chapter_begin, s.verse_begin, s.chapter_end, s.verse_end, s.studytitle, s.studyintro, s.published AS spub,'
		. ' s.media_hours, s.media_minutes, s.media_seconds,'
		. ' se.series_text,'
		. ' sr.id AS srid, sr.server_path,'
		. ' f.id AS fid, f.folderpath,'
		. ' t.id AS tid, t.teachername,'
		. ' b.id AS bid, b.booknumber AS bnumber, b.bookname,'
		. ' mt.id AS mtid, mt.mimetype'
		. ' FROM #__bsms_mediafiles AS mf'
		. ' LEFT JOIN #__bsms_studies AS s ON (s.id = mf.study_id)'
		. ' LEFT JOIN #__bsms_series AS se ON (se.id = s.series_id)'
		. ' LEFT JOIN #__bsms_servers AS sr ON (sr.id = mf.server)'
		. ' LEFT JOIN #__bsms_folders AS f ON (f.id = mf.path)'
		. ' LEFT JOIN #__bsms_books AS b ON (b.booknumber = s.booknumber)'
		. ' LEFT JOIN #__bsms_teachers AS t ON (t.id = s.teacher_id)'
		. ' LEFT JOIN #__bsms_mimetype AS mt ON (mt.id = mf.mime_type)'
		. ' LEFT JOIN #__bsms_podcast AS p ON (p.id = mf.podcast_id)'
		. ' WHERE mf.podcast_id LIKE "%'.$id.'%" AND mf.published = 1 ORDER BY createdate DESC '.$limit;

		$db->setQuery( $query );
		$episodes = $db->loadObjectList();
        return $episodes;
    }

    function writeFile($file, $filecontent)
    {

        // Set FTP credentials, if given
        $files[] = $file;
        $podcastresults = '';
		jimport('joomla.client.helper');
		jimport('joomla.filesystem.file');
		JClientHelper::setCredentialsFromRequest('ftp');
		$ftp = JClientHelper::getCredentials('ftp');

        // Try to make the template file writeable
		if (JFile::exists($file) && !$ftp['enabled'] && !JPath::setPermissions($file, '0755'))
		{
			JError::raiseNotice('SOME_ERROR_CODE', 'Could not make the file writable');
		}

		$fileit = JFile::write($file, $filecontent);
        if ($fileit){
            return true;
            //JError::raiseNotice('SOME_ERROR_CODE', $file.' - Success');
            }
        if (!$fileit){JError::raiseNotice('SOME_ERROR_CODE', 'Could not make the file unwritable');
        return false;}
		// Try to make the template file unwriteable
		if (!$ftp['enabled'] && !JPath::setPermissions($file, '0555'))
		{
			JError::raiseNotice('SOME_ERROR_CODE', 'Could not make the file unwritable');
            return false;
		}
        return $podcastresults;
    }
}