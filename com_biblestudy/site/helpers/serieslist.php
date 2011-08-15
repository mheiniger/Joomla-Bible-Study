<?php 

/**
 * @version $Id: serieslist.php 1 $
 * @package BibleStudy
 * @Copyright (C) 2007 - 2011 Joomla Bible Study Team All rights reserved
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.JoomlaBibleStudy.org
 **/

defined('_JEXEC') or die('Restriced Access');
require_once (JPATH_ROOT  .DS. 'components' .DS. 'com_biblestudy' .DS. 'lib' .DS. 'biblestudy.images.class.php');
require_once (JPATH_ROOT  .DS. 'components' .DS. 'com_biblestudy' .DS. 'lib' .DS. 'biblestudy.defines.php');
require_once (JPATH_ROOT  .DS. 'components' .DS. 'com_biblestudy' .DS. 'lib' .DS. 'biblestudy.admin.class.php');

function getSerieslist($row, $params, $oddeven, $admin_params, $template, $view)
{
	$listing = '';
	$path1 = JPATH_ROOT.DS.'components'.DS.'com_biblestudy'.DS.'helpers'.DS;
	include_once($path1.'elements.php');
	include_once($path1.'custom.php');
	include_once($path1.'image.php');
	
	if ($params->get('series_show_description') == 0) {$listing .= '<tr class="onlyrow '.$oddeven.'">';}
	else {$listing .= '<tr class="firstrow firstcol '.$oddeven.'">';}
	
	$custom = $params->get('seriescustom1');
	$listelementid = $params->get('serieselement1');
	$islink = $params->get('seriesislink1');
	$r = 'firstcol'; 
	$listelement = seriesGetelement($r, $row, $listelementid, $custom, $islink, $admin_params, $params, $view);
	$listing .= $listelement;
	if (!$listelementid) {
		$listing .= '<td class="firstrow firstcol">';
		$listing .= '</td>';
	}
	
	$custom = $params->get('seriescustom2');
	$listelementid = $params->get('serieselement2');
	$islink = $params->get('seriesislink2');
	$r = '';
	$listelement = seriesGetelement($r, $row, $listelementid, $custom, $islink, $admin_params, $params, $view); 
	$listing .= $listelement;
	if (!$listelementid) {
		$listing .= '<td >';
		$listing .= '</td>';
	}
	$custom = $params->get('seriescustom3');
	$listelementid = $params->get('serieselement3');
	$islink = $params->get('seriesislink3');
	$r = '';
	$listelement = seriesGetelement($r, $row, $listelementid, $custom, $islink, $admin_params, $params, $view); 
	$listing .= $listelement;
	if (!$listelementid) {
		$listing .= '<td >';
		$listing .= '</td>';
	}
	
	$custom = $params->get('seriescustom4');
	$listelementid = $params->get('serieselement4');
	$islink = $params->get('seriesislink4');
	$r = 'lastcol';
	$listelement = seriesGetelement($r, $row, $listelementid, $custom, $islink, $admin_params, $params, $view);
	$listing .= $listelement; 
	if (!$listelementid) {
		$listing .= '<td class="lastcol"></td>'; 
	}
	$listing .= '</tr>';
	
	//add if last row to above
	
	if ($params->get('series_show_description') > 0 ) {
		$listing .= '<tr class="lastrow '.$oddeven.'">';
		$listing .= '<td colspan="4" class="description">';
		if ($params->get('series_characters') && $view == 0) {
			$listing .= substr($row->description,0,$params->get('series_characters'));
			$listing .= ' - '.'<a href="index.php?option=com_biblestudy&view=seriesdetail&t='.$params->get('seriesdetailtemplateid', 1).'&id='.$row->id.'">'.JText::_('JBS_CMN_READ_MORE').'</a>';
		}
		else {
			$listing .= $row->description;
		}
		$listing .= '</td></tr>';
	}
	return $listing;
}

//elements are: series title, series image, series pastor + image, description

function getSerieslink($islink, $row, $element, $params, $admin_params)
{
	if ($islink == 1)
	{
		$link = '<a href="'.JRoute::_('index.php?option=com_biblestudy&view=seriesdetail&t='.$params->get('seriesdetailtemplateid', 1).'&id='.$row->id).'">'.$element.'</a>';
	}
	else
	{
		$link = '<a href="'.JRoute::_('index.php?option=com_biblestudy&view=teacherdisplay&t='.$params->get('teachertemplateid', 1).'&id='.$row->id).'">'.$element.'</a>';	
	}
	return $link;
}

function getStudieslink($islink, $row, $element, $params, $admin_params)
{
	$link = '<a href="'.JRoute::_('index.php?option=com_biblestudy&view=studydetails&t='.$params->get('studiesdetailtemplateid', 1).'&id='.$row->id).'">'.$element.'</a>';
	return $link;
}

function seriesGetelement($r, $row, $listelementid, $custom, $islink, $admin_params, $params, $view)
{
	$element = '';
	switch ($listelementid)
	{ 
		case 1:
			$element = $row->series_text;
			if ($islink > 0) {$element = getSerieslink($islink, $row, $element, $params, $admin_params);}
			$element = '<td class="'.$r.' title">'.$element.'</td>';
			break;
		case 2:
			$images = new jbsImages(); 
			$image = $images->getSeriesThumbnail($row->series_thumbnail);

			$element = '<img src="'.$image->path.'" height="'.$image->height.'" width="'.$image->width.'" alt="'.$row->series_text.'">';
			if ($islink > 0 && $view == 0) {$element = getSerieslink($islink, $row, $element, $params, $admin_params);}
			$element = '<td class="'.$r.' thumbnail image">'.$element.'</td>';
			break;
		case 3: 
			$images = new jbsImages(); 
			$image = $images->getSeriesThumbnail($row->series_thumbnail);
			$element1 = '<td class="'.$r.' thumbnail"> <table id="seriestable" cellspacing="0"><tr class="noborder"><td>';
			$element2 = '<img src="'.$image->path.'" height="'.$image->height.'" width="'.$image->width.'" alt="'.$row->series_text.'">';
			$element3 = '</td></tr>';
			$element4 = $row->series_text;
			if ($islink > 0 && $view == 0) {$element4 = getSerieslink($islink, $row, $element4, $params, $admin_params);}
			$element = $element1.$element2.$element3.'</td></tr>';
			$element .= '<tr class="noborder"><td class="'.$r.' title">'.$element4.'</td>';
			$element .= '</tr></table></td>';
			break;
		case 4:
			$element = $row->teachertitle.' - '.$row->teachername;
			if ($islink > 0) {$element = getSerieslink($islink, $row, $element, $params, $admin_params);}
			$element = '<td class="'.$r.' teacher">'.$element.'</td>';
			break;
		case 5:
			$images = new jbsImages();
			$image = $images->getTeacherThumbnail($row->teacher_thumbnail, $row->thumb);

			$element = '<img src="'.$image->path.'" height="'.$image->height.'" width="'.$image->width.'" alt="'.$row->teachername.'">';
			if ($islink > 0) {$element = getSerieslink($islink, $row, $element, $params, $admin_params);}
			$element = '<td class="'.$r.' teacher image">'.$element.'</td>';
			break;
		case 6:
			$element1 = '<table id="seriestable" cellspacing="0"><tr class="noborder"><td class="'.$r.' teacher">';
			$images = new jbsImages();
			$image = $images->getTeacherThumbnail($row->teacher_thumbnail, $row->thumb);
			$element2 = '<img src="'.$image->path.'" height="'.$image->height.'" width="'.$image->width.'" alt="'.$row->teachername.'">';
			$element3 = '</td></tr><tr class="noborder"><td class="'.$r.' teacher">';
			$element4 = $row->teachertitle.' - '.$row->teachername;
			if ($islink > 0) {$element4 = getSerieslink($islink, $row, $element4, $params, $admin_params);}
			$element = $element1.$element2.$element3.$element4.'</td></tr></table>';
			$element = '<td class="'.$r.' teacher image">'.$element.'</td>';
			break;
		case 7:
			$element = $row->description;
			if ($islink > 0) {$element = getSerieslink($islink, $row, $element, $params, $admin_params);}
			$element = '<td class="'.$r.' description"><p>'.$element.'</p></td>';
			break;
	}
	return $element;
}

function seriesGetcustom($r, $row, $customelement, $custom, $islink, $admin_params, $params)
{
	$countbraces = substr_count($custom, '{');
	$braceend = 0;
	while ($countbraces > 0)
	{
		$bracebegin = strpos($custom,'{');
		$braceend = strpos($custom, '}');
		$subcustom = substr($custom, ($bracebegin + 1), (($braceend - $bracebegin) - 1));
		$customelement = getseriesElementnumber($subcustom);
		$element = seriesGetelement($r, $row, $customelement, $custom, $islink, $admin_params, $params);
		$custom = substr_replace($custom,$element,$bracebegin,(($braceend - $bracebegin) + 1));
		$countbraces = $countbraces - 1;
	}
	
	return $custom;
}

function getseriesElementnumber($subcustom)
{
	switch ($subcustom)
	{
		case 'title':
		$customelement = 1;
		break;
		
		case 'thumbnail':
		$customelement = 2;
		break;
		
		case 'thumbnail-title':
		$customelement = 3;
		break;
		
		case 'teacher':
		$customelement = 4;
		break;
		
		case 'teacherimage':
		$customelement = 5;
		break;
		
		case 'teacher-title':
		$customelement = 6;
		break;
		
		case 'description':
		$customelement = 7;
		break;
	}
	return $customelement;
}

function getSeriesstudies($id, $params, $admin_params, $template)
{
	$studies = '';
	$db	= & JFactory::getDBO();
	$query = 'SELECT s.series_id FROM #__bsms_studies AS s WHERE s.published = 1 AND s.series_id = '.$id;
	$db->setQuery($query);
	$allrows = $db->loadObjectList();
	$rows = $db->getAffectedRows();

	$query = 'SELECT s.*, se.id AS seid, t.id AS tid, t.teachername, t.title AS teachertitle, t.thumb, t.thumbh, t.thumbw, '
	. ' t.teacher_thumbnail, se.series_text, se.description AS sdescription, '
	. ' se.series_thumbnail, #__bsms_message_type.id AS mid,'
	. ' #__bsms_message_type.message_type AS message_type, #__bsms_books.bookname,'
	. ' group_concat(#__bsms_topics.id separator ", ") AS tp_id, group_concat(#__bsms_topics.topic_text separator ", ") as topic_text, group_concat(#__bsms_topics.params separator ", ") as topic_params, '
//	. ' #__bsms_topics.id AS tp_id, #__bsms_topics.topic_text, '
	. ' #__bsms_locations.id AS lid, #__bsms_locations.location_text '
	. ' FROM #__bsms_studies AS s'
	. ' LEFT JOIN #__bsms_series AS se ON (s.series_id = se.id)'
	. ' LEFT JOIN #__bsms_teachers AS t ON (s.teacher_id = t.id)'
	. ' LEFT JOIN #__bsms_books ON (s.booknumber = #__bsms_books.booknumber)'
	. ' LEFT JOIN #__bsms_message_type ON (s.messagetype = #__bsms_message_type.id)'
	. ' LEFT JOIN #__bsms_studytopics ON (#__bsms_studytopics.study_id = s.id)'
	. ' LEFT JOIN #__bsms_topics ON (#__bsms_topics.id = #__bsms_studytopics.topic_id)'
// 	. '	LEFT JOIN #__bsms_topics ON (s.topics_id = #__bsms_topics.id)'
	. ' LEFT JOIN #__bsms_locations ON (s.location_id = #__bsms_locations.id)'
	.' WHERE s.series_id = '.$id.' AND s.published = 1 ORDER BY '.$params->get('series_detail_sort', 'studydate').' '.$params->get('series_detail_order', 'DESC');
	$db->setQuery($query);
	$results = $db->loadObjectList();
	$items = $results;
	 //check permissions for this view by running through the records and removing those the user doesn't have permission to see
	$user = JFactory::getUser();
	$groups	= $user->getAuthorisedViewLevels(); 
	$count = count($items);

	for ($i = 0; $i < $count; $i++)
	{
		if ($items[$i]->access > 1)
		{
			if (!in_array($items[$i]->access,$groups))
			{
				unset($items[$i]); 
			}
		}
	}
	foreach($items as $item)
	{
		// concat topic_text and concat topic_params do not fit, so translate individually
		$topic_text = getConcatTopicItemTranslated($item);
		$item->topic_text = $topic_text;
	}
	
	$result = $items;
	$numrows = count($result);

	$class1 = 'bsodd';
	$class2 = 'bseven';
	$oddeven = $class1;
	foreach ($result AS $row)
	{
		if($oddeven == $class1){ //Alternate the color background
			$oddeven = $class2;
		} else {
			$oddeven = $class1;
		}
		$studies .= '<tr class="'.$oddeven;
		if ($numrows > 1) {$studies .=' studyrow';} else {$studies .= ' lastrow';}
		$studies .= '">
		';
		$element = getElementid($params->get('series_detail_1'), $row, $params, $admin_params, $template);
		if ($params->get('series_detail_islink1') > 0) {$element->element = getStudieslink($params->get('series_detail_islink1'), $row, $element->element, $params, $admin_params);}
		$studies .= '<td class="'.$element->id.'">'.$element->element.'</td>
		';
		$element = getElementid($params->get('series_detail_2'), $row, $params, $admin_params, $template);
		if ($params->get('series_detail_islink2') > 0) {$element->element = getStudieslink($params->get('series_detail_islink1'), $row, $element->element, $params, $admin_params);}
		$studies .= '<td class="'.$element->id.'">'.$element->element.'</td>
		';
		$element = getElementid($params->get('series_detail_3'), $row, $params, $admin_params, $template);
		if ($params->get('series_detail_islink3') > 0) {$element->element = getStudieslink($params->get('series_detail_islink1'), $row, $element->element, $params, $admin_params);}
		$studies .= '<td class="'.$element->id.'">'.$element->element.'</td>
		';
		$element = getElementid($params->get('series_detail_4'), $row, $params, $admin_params, $template);
		if ($params->get('series_detail_islink4') > 0) {$element->element = getStudieslink($params->get('series_detail_islink1'), $row, $element->element, $params, $admin_params);}
		$studies .= '<td class="'.$element->id.'">'.$element->element.'</td>
		';
		$numrows = $numrows - 1;
		
	}
	$t = $params->get('serieslisttemplateid');
					if (!$t) {$t = JRequest::getVar('t',1,'get','int');}
	$studies .= '</tr>';
        
return $studies;
}

function getSeriesLandingPage($params, $id, $admin_params)
{
	$mainframe =& JFactory::getApplication(); $option = JRequest::getCmd('option');
	$path1 = JPATH_SITE.DS.'components'.DS.'com_biblestudy'.DS.'helpers'.DS;
	include_once($path1.'image.php');
	include_once($path1.'helper.php');
	$series = null;
	$seriesid = null;
	$template = $params->get('serieslisttemplateid',1);
	$limit = $params->get('landingserieslimit');
	if (!$limit) {$limit = 10000;}
	
	$series = "\n" . '<table id="landing_table" width=100%>';
	$db	=& JFactory::getDBO();
	$query = 'select distinct a.* from #__bsms_series a inner join #__bsms_studies b on a.id = b.series_id';

	$db->setQuery($query);

	$tresult = $db->loadObjectList();
	$t = 0;
	$i = 0;

	$series .= "\n\t" . '<tr>';
	$showdiv = 0;
	foreach ($tresult as &$b) {
	    if ($t >= $limit)
		{
			if ($showdiv < 1)
			{
				if ($i == 1) {
					$series .= "\n\t\t" . '<td  id="landing_td"></td>' . "\n\t\t" . '<td id="landing_td"></td>';
					$series .= "\n\t" . '</tr>';
	    		};
				if ($i == 2) {
					$series .= "\n\t\t" . '<td  id="landing_td"></td>';
					$series .= "\n\t" . '</tr>';
				};

				$series .= "\n" .'</table>';
				$series .= "\n\t" . '<div id="showhideseries" style="display:none;"> <!-- start show/hide series div-->';
				$series .= "\n" . '<table width = "100%" id="landing_table">';

				$i = 0;
				$showdiv = 1;
			}
		}   

	    if ($i == 0) {
	        $series .= "\n\t" . '<tr>';
	    }
	    $series .= "\n\t\t" . '<td id="landing_td">';

	    if ($params->get('series_linkto') == '0') {
	        $series .= '<a href="index.php?option=com_biblestudy&view=studieslist&filter_series='.$b->id.'&filter_book=0&filter_teacher=0&filter_topic=0&filter_location=0&filter_year=0&filter_messagetype=0&t='.$template.'">';
	    } else {
	        $series .= '<a href="index.php?option=com_biblestudy&view=seriesdetail&id='.$b->id.'&t='.$template.'">';    
	    }

	    $series .= $numRows;
	    $series .= $b->series_text;
		
	    $series .='</a>';
	    
	    $series .= '</td>';
	    
	    $i++;
	    $t++;
	    if ($i == 3) {
	        $series .= "\n\t" . '</tr>';
	        $i = 0;
	    }
	}
	if ($i == 1) {
	    $series .= "\n\t\t" . '<td  id="landing_td"></td>' . "\n\t\t" . '<td id="landing_td"></td>';
	};
	if ($i == 2) {
	    $series .= "\n\t\t" . '<td  id="landing_td"></td>';
	};
	
	$series .= "\n". '</table>' ."\n";
	
	if ($showdiv == 1) {	
		$series .= "\n\t". '</div> <!-- close show/hide series div-->';
		$showdiv = 2;
	}
	$series .= '<div id="landing_separator"></div>';

	return $series;
}

function getSerieslistExp($row, $params, $admin_params, $template)
{
	$path1 = JPATH_SITE.DS.'components'.DS.'com_biblestudy'.DS.'helpers'.DS;
	include_once($path1.'elements.php');
	include_once($path1.'custom.php');
	include_once($path1.'image.php');
	include_once($path1.'helper.php');
	$t = $params->get('serieslisttemplateid');
	include_once($path1.'image.php');
	$images = new jbsImages();
	$image = $images->getSeriesThumbnail($row->series_thumbnail);
			
	$label = $params->get('series_templatecode');
    $label = str_replace('{{teacher}}', $row->teachername, $label);
    $label = str_replace('{{teachertitle}}', $row->teachertitle, $label);
	$label = str_replace('{{title}}', $row->series_text, $label);
	$label = str_replace('{{description}}', $row->description, $label);
	$label = str_replace('{{thumbnail}}', '<img src="'. $image->path .'" width="' .$image->width .'" height="' . $image->height . '" />', $label);
	$label = str_replace('{{url}}', 'index.php?option=com_biblestudy&view=seriesdetail&t='.$template.'&id='.$row->id, $label);
    
	return $label;
}

function getSeriesDetailsExp($row, $params, $admin_params, $template)
{
	//seriesdesc_template
	$path1 = JPATH_SITE.DS.'components'.DS.'com_biblestudy'.DS.'helpers'.DS;
	include_once($path1.'elements.php');
	include_once($path1.'scripture.php');
	include_once($path1.'custom.php');
	include_once($path1.'passage.php');
	//include_once($path1.'mediatable.php');
	//This will eventually replace mediatable in this context.  Just for clarity.
	include_once($path1.'media.php');
	include_once($path1.'share.php');
	include_once($path1.'comments.php');
	include_once($path1.'date.php');
	include_once($path1.'image.php');    
	$images = new jbsImages();
	$image = $images->getSeriesThumbnail($row->series_thumbnail);
	$label = $params->get('series_detailcode');
	$label = str_replace('{{teacher}}', $row->teachername, $label);
	$label = str_replace('{{teachertitle}}', $row->teachertitle, $label);
	$label = str_replace('{{description}}', $row->description, $label);
	$label = str_replace('{{title}}', $row->series_text, $label);
	$label = str_replace('{{thumbnail}}', '<img src="'. $image->path .'" width="' .$image->width .'" height="' . $image->height . '" />', $label);
	$label = str_replace('{{plays}}', $row->totalplays, $label);
	$label = str_replace('{{downloads}}', $row->totaldownloads, $label);

	return $label;
}

function getSeriesstudiesExp($id, $params, $admin_params, $template)
{
	$path1 = JPATH_SITE.DS.'components'.DS.'com_biblestudy'.DS.'helpers'.DS;
	include_once($path1.'listing.php');
	$path2 = JPATH_SITE.DS.'components'.DS.'com_biblestudy'.DS.'models'.DS;

	$limit = '';
	$nolimit = JRequest::getVar('nolimit', 'int', 0);
	if ($params->get('series_detail_limit')) {$limit = ' LIMIT '.$params->get('series_detail_limit');}
	if ($nolimit == 1) {$limit = '';}
	$db	= & JFactory::getDBO();
	$query = 'SELECT s.series_id FROM #__bsms_studies AS s WHERE s.published = 1 AND s.series_id = '.$id;
	$db->setQuery($query);
	$allrows = $db->loadObjectList();
	$rows = $db->getAffectedRows();
   
	// 6.2
	$query = 'SELECT #__bsms_studies.*, #__bsms_teachers.id AS tid, #__bsms_teachers.teachername,'
		. ' #__bsms_series.id AS sid, #__bsms_series.series_text, #__bsms_message_type.id AS mid,'
		. ' #__bsms_message_type.message_type AS message_type, #__bsms_books.bookname,'
		. ' group_concat(#__bsms_topics.id separator ", ") AS tp_id, group_concat(#__bsms_topics.topic_text separator ", ") as topic_text'
		. ' FROM #__bsms_studies'
		. ' left join #__bsms_studytopics ON (#__bsms_studies.id = #__bsms_studytopics.study_id)'
		. ' LEFT JOIN #__bsms_books ON (#__bsms_studies.booknumber = #__bsms_books.booknumber)'
		. ' LEFT JOIN #__bsms_teachers ON (#__bsms_studies.teacher_id = #__bsms_teachers.id)'
		. ' LEFT JOIN #__bsms_series ON (#__bsms_studies.series_id = #__bsms_series.id)'
		. ' LEFT JOIN #__bsms_message_type ON (#__bsms_studies.messagetype = #__bsms_message_type.id)'
		. ' LEFT JOIN #__bsms_topics ON (#__bsms_topics.id = #__bsms_studytopics.topic_id)'
		. ' where #__bsms_series.id = ' .$id
		. ' GROUP BY #__bsms_studies.id'
		. ' order by studydate desc'
		. $limit;	
	$db->setQuery($query);
	$result = $db->loadObjectList();
	$numrows = $db->getAffectedRows();

	//check permissions for this view by running through the records and removing those the user doesn't have permission to see
	$user = JFactory::getUser();
	$groups	= $user->getAuthorisedViewLevels(); 
	$count = count($result);

	for ($i = 0; $i < $count; $i++)
	{
	    if ($result[$i]->access > 1)
	    {
	       if (!in_array($result[$i]->access,$groups))
	       {
	            unset($result[$i]); 
	       } 
	    }

// santon tobedone translation


	}
	
	$numrows = count($result);

	$studies = '';

	switch ($params->get('series_wrapcode')) {
		case '0':
		  //Do Nothing
		  break;
		case 'T':
		  //Table
		  $studies .= '<table id="bsms_seriestable" width="100%">'; 
		  break;
		case 'D':
		  //DIV
		  $studies .= '<div>';
		  break;
		}
	echo $params->get('series_headercode');

	foreach ($result AS $row)
	{
	    $oddeven = 0;
		$studies .= getListingExp($row, $params, $params, $params->get('seriesdetailtemplateid'));	
	}
	
	switch ($params->get('series_wrapcode')) {
		case '0':
		  //Do Nothing
		  break;
		case 'T':
		  //Table
		  $studies .= '</table>'; 
		  break;
		case 'D':
		  //DIV
		  $studies .= '</div>';
		  break;
		}
	echo $params->get('series_headercode');

	return $studies;
}

function getSeriesFooter($t, $id)
{
	$seriesfooter = '<tr class="seriesreturnlink"><td><a href="'.JRoute::_('index.php?option=com_biblestudy&view=studieslist&filter_series='.$id.'&t='.$template).'">'.JText::_('JBS_CMN_SHOW_ALL').' '.JText::_('JBS_SER_STUDIES_FROM_THIS_SERIES').' >></a></td></tr>';
	return $seriesfooter;
}