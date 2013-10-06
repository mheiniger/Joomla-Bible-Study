<?php
/**
 * Part of Joomla BibleStudy Package
 *
 * @package    BibleStudy.Admin
 * @copyright  (C) 2007 - 2013 Joomla Bible Study Team All rights reserved
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link       http://www.JoomlaBibleStudy.org
 * */
// No Direct Access
defined('_JEXEC') or die;

require_once JPATH_ADMINISTRATOR . '/components/com_biblestudy/lib/biblestudy.defines.php';

// Helper file - master list creater for study lists
JLoader::register('JBSMImages', BIBLESTUDY_PATH_LIB . '/biblestudy.images.class.php');
JLoader::register('jbsMedia', BIBLESTUDY_PATH_LIB . '/biblestudy.media.class.php');
JLoader::register('JBSMHelperRoute', BIBLESTUDY_PATH_HELPERS . '/route.php');
JLoader::register('JBSMElements', BIBLESTUDY_PATH_HELPERS . '/elements.php');
JLoader::register('JBSMCustom', BIBLESTUDY_PATH_HELPERS . '/custom.php');
JLoader::register('JBSMHelper', BIBLESTUDY_PATH_ADMIN_HELPERS . '/helper.php');

/**
 * BibleStudy listing class
 *
 * @package  BibleStudy.Site
 * @since    7.0.0
 */
class JBSMListing extends JBSMElements
{
    /**
     * @param $items
     * @param $params
     * @param $admin_params
     * @param $template
     * @return string
     */
    public function getFluidListing($items, $params, $admin_params, $template)
    {
        //Find out what view we are in
        $input = new JInput();
        $view = $input->getString('view');
        $list = '';
        $row = array();
        $this->params = $params;

        if ($view == 'sermons')
        {
            foreach ($items as $item)
            {
                if (isset($item->mids))
                {
                    $medias[] = $this->getFluidMediaids($item);
                }
            }
        }
        if ($view == 'sermon')
        {
            $medias = $this->getFluidMediaids($items);
            $item = $items;
        }
        //get the media files in one query
        if (isset($medias))
        {
            $db    = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('#__bsms_mediafiles.*, #__bsms_servers.id AS ssid, #__bsms_servers.server_path AS spath, #__bsms_folders.id AS fid,'
            . ' #__bsms_folders.folderpath AS fpath, #__bsms_media.id AS mid, #__bsms_media.media_image_path AS impath, '
            . ' #__bsms_media.media_image_name AS imname,'
            . ' #__bsms_media.path2 AS path2, s.studytitle, s.studydate, s.studyintro, s.media_hours, s.media_minutes, s.media_seconds, s.teacher_id,'
            . ' s.booknumber, s.chapter_begin, s.chapter_end, s.verse_begin, s.verse_end, t.teachername, t.id as tid, s.id as sid, s.studyintro,'
            . ' #__bsms_media.media_alttext AS malttext, #__bsms_mimetype.id AS mtid, #__bsms_mimetype.mimetext, #__bsms_mimetype.mimetype');
            $query->from('#__bsms_mediafiles');
            $query->leftJoin('#__bsms_media ON (#__bsms_media.id = #__bsms_mediafiles.media_image)');
            $query->leftJoin('#__bsms_servers ON (#__bsms_servers.id = #__bsms_mediafiles.server)');
            $query->leftJoin('#__bsms_folders ON (#__bsms_folders.id = #__bsms_mediafiles.path)');
            $query->leftJoin('#__bsms_mimetype ON (#__bsms_mimetype.id = #__bsms_mediafiles.mime_type)');
            $query->leftJoin('#__bsms_studies AS s ON (s.id = #__bsms_mediafiles.study_id)');
            $query->leftJoin('#__bsms_teachers AS t ON (t.id = s.teacher_id)');
            $where2   = array();
            $subquery = '(';
            foreach ($medias as $media)
            {
                if (is_array($media))
                {
                    foreach ($media as $m)
                    {
                        $where2[] = '#__bsms_mediafiles.id = ' . (int) $m;
                    }
                }
                else
                {$where2[] = '#__bsms_mediafiles.id = ' . (int) $media;}
            }
            $subquery .= implode(' OR ', $where2);
            $subquery .= ')';
            $query->where($subquery);
            $query->where('#__bsms_mediafiles.published = 1');
            $query->order('ordering ASC, #__bsms_media.media_image_name ASC');
            $db->setQuery($query);
            $mediafiles = $db->loadObjectList();
        }
        //create an array from each param variable set
        //Find out what view we are in
        $input = new JInput();
        $view = $input->getString('view');
        switch ($view)
        {
            case 'sermons':
                $extra = '';
                break;
            case 'sermon':
                $extra = 'd';
                break;
        }

        $listparams = array();
        if ($params->get($extra.'scripture1row') > 0){$listparams[]= $this->getListParamsArray($extra.'scripture1');}
        if ($params->get($extra.'scripture2row') > 0){$listparams[]= $this->getListParamsArray($extra.'scripture2');}
        if ($params->get($extra.'secondaryrow') > 0){$listparams[]= $this->getListParamsArray($extra.'secondary');}
        if ($params->get($extra.'titlerow') > 0){$listparams[]= $this->getListParamsArray($extra.'title');}
        if ($params->get($extra.'daterow') > 0){$listparams[]= $this->getListParamsArray($extra.'date');}
        if ($params->get($extra.'teacherrow') > 0){$listparams[]= $this->getListParamsArray($extra.'teacher');}
        if ($params->get($extra.'teacher-titlerow') > 0){$listparams[]= $this->getListParamsArray($extra.'teacher-title');}
        if ($params->get($extra.'durationrow') > 0){$listparams[]= $this->getListParamsArray($extra.'duration');}
        if ($params->get($extra.'studyintrorow') > 0){$listparams[]= $this->getListParamsArray($extra.'studyintro');}
        if ($params->get($extra.'seriesrow') > 0){$listparams[]= $this->getListParamsArray($extra.'series');}
        if ($params->get($extra.'seriesdescriptionrow') > 0){$listparams[]= $this->getListParamsArray($extra.'seriesdescription');}
        if ($params->get($extra.'seriesthumbnailrow') > 0){$listparams[]= $this->getListParamsArray($extra.'seriesthumbnail');}
        if ($params->get($extra.'submittedrow') > 0){$listparams[]= $this->getListParamsArray($extra.'submitted');}
        if ($params->get($extra.'hitsrow') > 0){$listparams[]= $this->getListParamsArray($extra.'hits');}
        if ($params->get($extra.'downloadsrow') > 0){$listparams[]= $this->getListParamsArray($extra.'downloads');}
        if ($params->get($extra.'studynumberrow') > 0){$listparams[]= $this->getListParamsArray($extra.'studynumber');}
        if ($params->get($extra.'topicrow') > 0){$listparams[]= $this->getListParamsArray($extra.'topic');}
        if ($params->get($extra.'locationsrow') > 0){$listparams[]= $this->getListParamsArray($extra.'locations');}
        if ($params->get($extra.'jbsmediarow') > 0){$listparams[]= $this->getListParamsArray($extra.'jbsmedia');}
        if ($params->get($extra.'messagetyperow') > 0){$listparams[]= $this->getListParamsArray($extra.'messagetype');}
        if ($params->get($extra.'thumbnailrow') > 0){$listparams[]= $this->getListParamsArray($extra.'thumbnail');}
        if ($params->get($extra.'teacherimagerrow') >0){$listparams[] = $this->getListParamsArray($extra.'teacherimage');}
        $row1 = array();
        $row2 = array();
        $row3 = array();
        $row4 = array();
        $row5 = array();
        $row6 = array();
        $row1sorted = array();
        $row2sorted = array();
        $row3sorted = array();
        $row4sorted = array();
        $row5sorted = array();
        $row6sorted = array();
        //Create an array sorted by row and then by column
        foreach ($listparams as $listing)
        {
            if ($listing->row == 1){$row1[] = $listing;}
            if ($listing->row == 2){$row2[] = $listing;}
            if ($listing->row == 3){$row3[] = $listing;}
            if ($listing->row == 4){$row4[] = $listing;}
            if ($listing->row == 5){$row5[] = $listing;}
            if ($listing->row == 6){$row6[] = $listing;}
        }
        if (count($row1)){$row1sorted = $this->sortArrayofObjectByProperty($row1,'col',$order="ASC");}
        if (count($row2)){$row2sorted = $this->sortArrayofObjectByProperty($row2,'col',$order="ASC");}
        if (count($row3)){$row3sorted = $this->sortArrayofObjectByProperty($row3,'col',$order="ASC");}
        if (count($row4)){$row4sorted = $this->sortArrayofObjectByProperty($row4,'col',$order="ASC");}
        if (count($row5)){$row5sorted = $this->sortArrayofObjectByProperty($row5,'col',$order="ASC");}
        if (count($row6)){$row6sorted = $this->sortArrayofObjectByProperty($row6,'col',$order="ASC");}
        $listrows = array_merge($row1sorted, $row2sorted, $row3sorted, $row4sorted, $row5sorted, $row6sorted);

        $class1 = $params->get('listcolor1', '');
        $class2 = $params->get('listcolor2', '');
        $oddeven = $class1;
        if ($view == 'sermons')
        {
            if ($params->get('use_headers_list') > 0)
            {
                $list .= $this->getFluidRow($listrows, $item, $params, $admin_params, $template, $row1sorted, $row2sorted, $row3sorted, $row4sorted, $row5sorted, $row6sorted, $oddeven, $header=1);
            }
        }
        if ($view == 'sermon')
        {
            if ($params->get('use_headers_view') > 0)
            {
                $list .= $this->getFluidRow($listrows, $item, $params, $admin_params, $template, $row1sorted, $row2sorted, $row3sorted, $row4sorted, $row5sorted, $row6sorted, $oddeven, $header=1);
            }
        }
        // Go through and attach the media files as an array to their study
        if ($view == 'sermons')
        {
            foreach ($items as $item)
            {
                $oddeven = ($oddeven == $class1) ? $class2 : $class1;
                $studymedia = array();
                if (isset($mediafiles))
                {
                    foreach ($mediafiles as $mediafile)
                        {
                            if ($mediafile->study_id == $item->id)
                            {
                                $studymedia[] = $mediafile;
                            }
                        }
                }
                if (isset($studymedia))
                {
                    $item->mediafiles = $studymedia;
                }
                $row[]= $this->getFluidRow($listrows, $item, $params, $admin_params, $template, $row1sorted, $row2sorted, $row3sorted, $row4sorted, $row5sorted, $row6sorted, $oddeven, $header=0);
            }
        }
        if ($view == 'sermon')
        {
            $oddeven = ($oddeven == $class1) ? $class2 : $class1;
            $studymedia = array();
            if (isset($mediafiles))
            {
                foreach ($mediafiles as $mediafile)
                {
                    if ($mediafile->study_id == $item->id)
                    {
                        $studymedia[] = $mediafile;
                    }
                }
            }
            if (isset($studymedia))
            {
                $item->mediafiles = $studymedia;
            }
            $row[]= $this->getFluidRow($listrows, $item, $params, $admin_params, $template, $row1sorted, $row2sorted, $row3sorted, $row4sorted, $row5sorted, $row6sorted, $oddeven, $header=0);
        }
        foreach ($row as $key=>$value)
        {
            $list .= $value;
        }

        return $list;
    }

     public function getFluidMediaids($item)
     {
         $mediatemp = array();
         $mediatemp = explode(',',$item->mids);
         foreach ($mediatemp as $mtemp)
         {$medias[] = $mtemp;}
         return $medias;
     }


    /**
     * @param $paramtext
     * @return stdClass
     */
    public function getListParamsArray($paramtext)
    {
        $l = new stdClass();
        $l->row = $this->params->get($paramtext.'row');
        $l->col = $this->params->get($paramtext.'col');
        $l->colspan = $this->params->get($paramtext.'colspan');
        $l->element = $this->params->get($paramtext.'element');
        $l->custom = $this->params->get($paramtext.'custom');
        $l->linktype = $this->params->get($paramtext.'linktype');
        $l->name = $paramtext;
        return $l;
    }

    /**
     * Get Fluid Row
     */
    public function getFluidRow($listrows, $item, $params, $admin_params, $template, $row1sorted, $row2sorted, $row3sorted, $row4sorted, $row5sorted, $row6sorted, $oddeven, $header)
    {
        $span = '';
        $headerstyle = '';
        if ($header == 1){$headerstyle = "style=visibility:hidden;";}
        $extra = '';
        $input = new JInput();
        $view = $input->getString('view');
        if ($view =='sermon'){$extra = 'd';}

        $rowspanitem = $params->get($extra.'rowspanitem');
        if ($rowspanitem)
        {
            switch ($rowspanitem)
            {
                case 1:
                    (isset($item->thumb) ? $span = '<img src="'.JURI::base().$item->thumb.'" class="'.$params->get('rowspanitemimage').'" alt="'.JText::_('JBS_CMN_TEACHER').'">' : $span = '');
                    break;
                case 2:
                    (isset($item->thumbm) ? $span = '<img src="'.JURI::base().$item->thumbm.'" class="'.$params->get('rowspanitemimage').'" alt="'.JText::_('JBS_CMN_THUMBNAIL').'">' : $span = '');
                    break;
                case 3:
                    (isset($item->series_thumbnail) ? $span = '<img src="'.JURI::base().$item->series_thumbnail.'" class="'.$params->get('rowspanitemimage').'" alt="'.JText::_('JBS_CMN_SERIES').'">' : $span = '');
                    break;
            }
        }

        $smenu        = $params->get('detailsitemid');
        $tmenu        = $params->get('teacheritemid');
        $input = new JInput();
        $view = $input->getString('view');
        $rowspanitemspan = $params->get('rowspanitemspan');
        $rowspanbalance = 12 - $rowspanitemspan;
        $frow = '';
        $frow = '<div class="row-fluid" style="background-color:'.$oddeven.'; padding:5px;">';
        $pull = '';
        if ($view == 'sermons'){$pull = $params->get('rowspanitempull');}
        if ($view == 'sermon'){$pull = $params->get('drowspanitempull');}
        if ($span)
        {
            $frow .= '<div class="row-fluid" >';
            $frow .= '<div class="span'.$rowspanitemspan.' '.$pull.'"><div '.$headerstyle.' class="">'.$span.'</div></div>';
            $frow .= '<div class="span'.$rowspanbalance.'">';
        }

        $row1count = count($row1sorted);
        $row1count2 = count($row1sorted);
        $row2count = count($row2sorted);
        $row2count2 = count($row2sorted);
        $row3count = count($row3sorted);
        $row3count2 = count($row3sorted);
        $row4count = count($row4sorted);
        $row4count2 = count($row4sorted);
        $row5count = count($row5sorted);
        $row5count2 = count($row5sorted);
        $row6count = count($row6sorted);
        $row6count2 = count($row6sorted);
        foreach ($listrows as $row)
        {
            if ($row->row == 1)
            {

                if ($row1count == $row1count2){$frow .= '<div class="row-fluid">';}
                if ($header == 1){$frow .= '<b>'.$this->getFluidData($item, $row, $params, $admin_params, $template, $header=1).'</b>';}
                else {$frow .= $this->getFluidData($item, $row, $params, $admin_params, $template, $header=0);}
                $row1count = $row1count - 1;
                if ($row1count == 0){$frow .= '</div>';}
            }
            if ($row->row == 2)
            {

                if ($row2count == $row2count2){$frow .= '<div class="row-fluid">';}
                if ($header == 1){$frow .= '<b>'.$this->getFluidData($item, $row, $params, $admin_params, $template, $header=1).'</b>';}
                else {$frow .= $this->getFluidData($item, $row, $params, $admin_params, $template, $header=0);}
                $row2count = $row2count - 1;
                if ($row2count == 0){$frow .= '</div>';}
            }
            if ($row->row == 3)
            {

                if ($row3count == $row3count2){$frow .= '<div class="row-fluid">';}
                if ($header == 1){'<b>'.$frow .= $this->getFluidData($item, $row, $params, $admin_params, $template, $header=1).'</b>';}
                else {$frow .= $this->getFluidData($item, $row, $params, $admin_params, $template, $header=0);}
                $row3count = $row3count - 1;
                if ($row3count == 0){$frow .= '</div>';}
            }
            if ($row->row == 4)
            {

                if ($row4count == $row4count2){$frow .= '<div class="row-fluid">';}
                if ($header == 1){'<b>'.$frow .= $this->getFluidData($item, $row, $params, $admin_params, $template, $header=1).'</b>';}
                else {$frow .= $this->getFluidData($item, $row, $params, $admin_params, $template, $header=0);}
                $row4count = $row4count - 1;
                if ($row4count == 0){$frow .= '</div>';}
            }
            if ($row->row == 5)
            {

                if ($row5count == $row5count2){$frow .= '<div class="row-fluid">';}
                if ($header == 1){'<b>'.$frow .= $this->getFluidData($item, $row, $params, $admin_params, $template, $header=1).'</b>';}
                else {$frow .= $this->getFluidData($item, $row, $params, $admin_params, $template, $header=0);}
                $row5count = $row5count - 1;
                if ($row5count == 0){$frow .= '</div>';}
            }
            if ($row->row == 6)
            {

                if ($row6count == $row6count2){$frow .= '<div class="row-fluid">';}
                if ($header == 1){$frow .= '<b>'.$this->getFluidData($item, $row, $params, $admin_params, $template, $header=1).'</b>';}
                else {$frow .= $this->getFluidData($item, $row, $params, $admin_params, $template, $header=0);}
                $row6count = $row6count - 1;
                if ($row6count == 0){$frow .= '</div>';}
            }
        }
        $frow .= '</div>';
        if ($span){$frow .= '</div></div>';}


        return $frow;
    }

    public function getFluidData($item, $row, $params, $admin_params, $template, $header)
    {
        $smenu        = $params->get('detailsitemid');
        $tmenu        = $params->get('teacheritemid');
        $data = '';
        //match the data in $item to a row/col in $row->name
        $input = new JInput();
        $view = $input->getString('view');
        $extra = '';
        if ($view == 'sermon')
        {
            $extra = 'd';
        }
        switch ($row->name)
        {
            case $extra.'scripture1':
                $esv = 0;
                $scripturerow          = 1;
                if ($header == 1){$data = JText::_('JBS_CMN_SCRIPTURE');}
                else {(isset($item->booknumber) ? $data = $this->getScripture($params, $item, $esv, $scripturerow) : $data = '');}
                break;
            case $extra.'scripture2':
                $esv = 0;
                $scripturerow          = 2;
                if ($header == 1){$data = JText::_('JBS_CMN_SCRIPTURE');}
                else {(isset($item->booknumber2) ? $data = $this->getScripture($params, $item, $esv, $scripturerow) : $data = '');}
                break;
            case $extra.'secondary':
                if ($header == 1){$data = JText::_('JBS_CMN_SECONDARY_REFERENCES');}
                else {(isset($item->secondary) ? $item->secondary : '');}
                break;
            case $extra.'title':
                if ($header == 1){$data = JText::_('JBS_CMN_TITLE');}
                else {(isset($item->studytitle) ? $data = stripslashes($item->studytitle) : $data = '');}
                break;
            case $extra.'date':
                if ($header == 1){$data = JText::_('JBS_CMN_STUDY_DATE');}
                else {(isset($item->studydate) ? $data = $this->getstudyDate($params, $item->studydate) : $data = '');}
                break;
            case $extra.'teacher':
                if ($header == 1){$data = JText::_('JBS_CMN_TEACHER');}
                else {(isset($item->teachername)? $data = $item->teachername : $data = '');}
                break;
            case $extra.'teacher-title':
                if ($header == 1){$data = JText::_('JBS_CMN_TEACHER');}
                elseif (isset($item->teachertitle) && isset($item->teachername))
                {
                    $data = $item->teachertitle . ' ' . $item->teachername;
                }
                else {$data = $item->teachername;}
                break;
            case $extra.'duration':
                if ($header == 1){$data = JText::_('JBS_CMN_DURATION');}
                else {(isset($item->media_minutes) ? $data = $this->getDuration($params, $item): $data = '');}
                break;
            case $extra.'studyintro':
                if ($header == 1){$data = JText::_('JBS_CMN_INTRODUCTION');}
                else {(isset($item->studyintro) ? stripslashes($data = $item->studyintro) : $data = '');}
                break;
            case $extra.'series':
                if ($header == 1){ $data = JText::_('JBS_CMN_SERIES');}
                else {(isset($item->series_text) ? $data = stripslashes($item->series_text) : $data = '');}
                break;
            case $extra.'seriesthumbnail':
                if ($header == 1){ $data = JText::_('JBS_CMN_THUMBNAIL');}
                else {(isset($item->series_thumbnail) ? $data = '<img src="'.JURI::base().$item->series_thumbnail.'" alt="'.JText::_('JBS_CMN_THUMBNAIL').'">' : $data = '');}
                break;
            case $extra.'seriesdescription':
                if ($header == 1){$data = JText::_('JBS_CMN_DESCRIPTION');}
                else {(isset($item->sdescription) ? $data = stripslashes($item->sdescription) : $data = '');}
                break;
            case $extra.'submitted':
                if ($header == 1){$data = JText::_('JBS_CMN_SUBMITTED_BY');}
                else {(isset($item->submitted) ? $data = $item->submitted : $data = '');}
                break;
            case $extra.'hits':
                if ($header == 1){$data = JText::_('JBS_CMN_VIEWS');}
                else {(isset($item->hits) ? $data = $item->hits : $data = '');}
                break;
            case $extra.'downloads':
                if ($header == 1){$data = JText::_('JBS_CMN_DOWNLOADS');}
                else {(isset($item->downloads) ? $data = $item->downloads : $data = '');}
                break;
            case $extra.'studynumber':
                if ($header == 1){$data = JText::_('JBS_CMN_STUDYNUMBER');}
                else {(isset($item->studynumber) ? $data = $item->studynumber : $data = '');}
                break;
            case $extra.'topic':
                if ($header == 1){$data = JText::_('JBS_CMN_TOPIC');}
                elseif (isset($item->topics_text))
                {
                    if (substr_count($item->topics_text, ','))
                    {
                        $topics = explode(',', $item->topics_text);

                        foreach ($topics as $key => $value)
                        {
                            $topics[$key] = JText::_($value);
                        }
                        $data = implode(', ', $topics);
                    }
                    else
                    {
                        (isset($item->topics_text) ? $data = JText::_($item->topics_text) : $data = '');
                    }
                }
                break;
            case $extra.'locations':
                if ($header == 1){$data = JText::_('JBS_CMN_LOCATION');}
                else {(isset($item->location_text) ? $data = $item->location_text : $data = '');}
                break;
            case $extra.'jbsmedia':
                if ($header == 1){$data = JText::_('JBS_CMN_MEDIA');}
                else {$data = $this->getFluidMediaFiles($item, $params, $admin_params, $template);}
                break;
            case $extra.'messagetype':
                if ($header == 1){$data = JText::_('JBS_CMN_MESSAGE_TYPE');}
                else {(isset($item->messaget_type) ? $data = $item->message_type : $data = '');}
                break;
            case $extra.'thumbnail':
                if ($header == 1){$data = JText::_('JBS_CMN_THUMBNAIL');}
                else {(isset($item->thumbnailm) ? $data = '<img src="'.JURI::base().$item->thumbnailm.'" alt="'.JText::_('JBS_CMN_THUMBNAIL').'">' : $data = '');}
                break;
            case $extra.'teacherimage':
                if ($header == 1){$data = JText::_('JBS_CMN_TEACHER_IMAGE');}
               else {(isset($item->thumb)? $data = 'img src="'.JURI::base().$item->thumb.'" alt="'.JText::_('JBS_CMN_THUMBNAIL').'">' : $data = '');}
                break;
        }

        $style = '';
        $customclass = '';
        if (isset($row->custom))
        {
            if (strpos($row->custom,'style=') !==false){$style = $row->custom;}
            else {$customclass = $row->custom;}
        }
        switch ($row->element)
        {
            case 0:
                $classelement = '';
                break;
            case 1:
                $classelement = '<p';
                break;
            case 2:
                $classelement = '<h1';
                break;
            case 3:
                $classelement = '<h2';
                break;
            case 4:
                $classelement = '<h3';
                break;
            case 5:
                $classelement = '<h4';
                break;
            case 6:
                $classelement = '<h5';
                break;
            case 7:
                $classelement = '<blockquote>';
        }
        if ($header == 1){$classelement = ''; $style='style="font-weight:bold;"';}
        if ($classelement){$classopen = $classelement.' '.$style.'>'; $classclose = '</'.$classelement.'>';}
        else {$classopen = ''; $classclose='';}
        //See whether the element is a link to something and get the link from the function
        $link = 0;
        if ($view == 'sermons')
        {
            if ($row->linktype > 0 && $header == 0)
            {
                $link = $this->getLink($row->linktype, $item->id, $item->teacher_id, $smenu, $tmenu, $params, $admin_params, $item, $template);
            }
        }
        $frow = '<div class="span'.$row->colspan.' '.$customclass.'"><div class="">'.$classopen;
        if ($link)
        {
            $frow .= $link;
        }
        if ($data){$frow .= $data;}

        if ($link)
        {
            $frow .= '</a>';
        }

        $frow .= $classclose.'</div>';

        $frow .= '</div>';
    return $frow;
    }

    public function getFluidMediaFiles($item, $params, $admin_params, $template)
    {
        $med = new jbsMedia();
        $mediarow = '<div style="display:inline;">';
        foreach ($item->mediafiles as $media)
        {
            $mediarow  .= $med->getFluidMedia($media, $params, $admin_params, $template);
        }
        $mediarow .= '</div>';
        return $mediarow;
    }
    /**
     * @param $array
     * @param $property
     * @param string $order
     * @return array
     */
    function sortArrayofObjectByProperty($array,$property,$order="ASC")
    {
        $cur = 1;
        $stack[1]['l'] = 0;
        $stack[1]['r'] = count($array)-1;

        do
        {
            $l = $stack[$cur]['l'];
            $r = $stack[$cur]['r'];
            $cur--;

            do
            {
                $i = $l;
                $j = $r;
                $tmp = $array[(int)( ($l+$r)/2 )];

                // split the array in to parts
                // first: objects with "smaller" property $property
                // second: objects with "bigger" property $property
                do
                {
                    while( $array[$i]->{$property} < $tmp->{$property} ) $i++;
                    while( $tmp->{$property} < $array[$j]->{$property} ) $j--;

                    // Swap elements of two parts if necesary
                    if( $i <= $j)
                    {
                        $w = $array[$i];
                        $array[$i] = $array[$j];
                        $array[$j] = $w;

                        $i++;
                        $j--;
                    }

                } while ( $i <= $j );

                if( $i < $r ) {
                    $cur++;
                    $stack[$cur]['l'] = $i;
                    $stack[$cur]['r'] = $r;
                }
                $r = $j;

            } while ( $l < $r );

        } while ( $cur != 0 );
        // Added ordering.
        if($order == "DESC"){ $array = array_reverse($array); }
        return $array;
    }


    /**
     * Get Scripture
     *
     * @param   object $params        Item Params
     * @param   object $row           Row Info
     * @param   string $esv           ESV String
     * @param   string $scripturerow  Scripture Row
     *
     * @return string
     */
    public function getScripture($params, $row, $esv, $scripturerow)
    {
        $scripture = '';

        if (!isset($row->id))
        {
            return null;
        }

        if (!isset($row->booknumber))
        {
            $row->booknumber = 0;
        }

        if (!isset($row->booknumber2))
        {
            $row->booknumber2 = 0;
        }

        if ($scripturerow == 2 && $row->booknumber2 >= 1)
        {
            $booknumber = $row->booknumber2;
            $ch_b       = $row->chapter_begin2;
            $ch_e       = $row->chapter_end2;
            $v_b        = $row->verse_begin2;
            $v_e        = $row->verse_end2;
        }
        elseif ($scripturerow == 1 && isset($row->booknumber) >= 1)
        {
            $booknumber = $row->booknumber;
            $ch_b       = $row->chapter_begin;
            $ch_e       = $row->chapter_end;
            $v_b        = $row->verse_begin;
            $v_e        = $row->verse_end;
        }

        if (!isset($booknumber))
        {
            return $scripture;
        }
        $show_verses = $params->get('show_verses');

        if (!isset($row->bookname))
        {
            $scripture = '';

            return $scripture;
        }

        $book = JText::_($row->bookname);

        $b1  = ' ';
        $b2  = ':';
        $b2a = ':';
        $b3  = '-';

        if ($show_verses == 1)
        {
            /** @var $ch_b string */
            /** @var $v_b string */
            /** @var $ch_e string */
            /** @var $v_e string */
            if ($ch_e == $ch_b)
            {
                $ch_e = '';
                $b2a  = '';
            }
            if ($ch_e == $ch_b && $v_b == $v_e)
            {
                $b3   = '';
                $ch_e = '';
                $b2a  = '';
                $v_e  = '';
            }
            if ($v_b == 0)
            {
                $v_b = '';
                $v_e = '';
                $b2a = '';
                $b2  = '';
            }
            if ($v_e == 0)
            {
                $v_e = '';
                $b2a = '';
            }
            if ($ch_e == 0)
            {
                $b2a  = '';
                $ch_e = '';

                if ($v_e == 0)
                {
                    $b3 = '';
                }
            }
            $scripture = $book . $b1 . $ch_b . $b2 . $v_b . $b3 . $ch_e . $b2a . $v_e;
        }
        // Else
        if ($show_verses == 0)
        {
            /** @var $ch_e string */
            /** @var $ch_b string */
            if ($ch_e > $ch_b)
            {
                $scripture = $book . $b1 . $ch_b . $b3 . $ch_e;
            }
            else
            {
                $scripture = $book . $b1 . $ch_b;
            }
        }
        if ($esv == 1)
        {
            /** @var $ch_b string */
            /** @var $v_b string */
            /** @var $ch_e string */
            /** @var $v_e string */
            if ($ch_e == $ch_b)
            {
                $ch_e = '';
                $b2a  = '';
            }
            if ($v_b == 0)
            {
                $v_b = '';
                $v_e = '';
                $b2a = '';
                $b2  = '';
            }
            if ($v_e == 0)
            {
                $v_e = '';
                $b2a = '';
            }
            if ($ch_e == 0)
            {
                $b2a  = '';
                $ch_e = '';

                if ($v_e == 0)
                {
                    $b3 = '';
                }
            }
            $scripture = $book . $b1 . $ch_b . $b2 . $v_b . $b3 . $ch_e . $b2a . $v_e;
        }

        if ($row->booknumber > 166)
        {
            $scripture = $book;
        }

        if ($show_verses == 2)
        {
            $scripture = $book;
        }

        return $scripture;
    }

    /**
     * Get Duration
     *
     * @param   object $params  Item Params
     * @param   object $row     Row info
     *
     * @return  null|string
     */
    public function getDuration($params, $row)
    {

        $duration = $row->media_hours . $row->media_minutes . $row->media_seconds;

        if (!$duration)
        {
            $duration = null;

            return $duration;
        }
        $duration_type = $params->get('duration_type', 2);
        $hours         = $row->media_hours;
        $minutes       = $row->media_minutes;
        $seconds       = $row->media_seconds;

        switch ($duration_type)
        {
            case 1:
                if (!$hours)
                {
                    $duration = $minutes . ' mins ' . $seconds . ' secs';
                }
                else
                {
                    $duration = $hours . ' hour(s) ' . $minutes . ' mins ' . $seconds . ' secs';
                }
                break;
            case 2:
                if (!$hours)
                {
                    $duration = $minutes . ':' . $seconds;
                }
                else
                {
                    $duration = $hours . ':' . $minutes . ':' . $seconds;
                }
                break;
            default:
                $duration = $hours . ':' . $minutes . ':' . $seconds;
                break;

        } // End switch

        return $duration;
    }

    /**
     * Get StudyDate
     *
     * @param   object $params     Item Params
     * @param   string $studydate  Study Date
     *
     * @return string
     */
    public function getstudyDate($params, $studydate)
    {
        switch ($params->get('date_format'))
        {
            case 0:
                $date = JHTML::_('date', $studydate, "M j, Y");
                break;
            case 1:
                $date = JHTML::_('date', $studydate, "M J");
                break;
            case 2:
                $date = JHTML::_('date', $studydate, "n/j/Y");
                break;
            case 3:
                $date = JHTML::_('date', $studydate, "n/j");
                break;
            case 4:
                $date = JHTML::_('date', $studydate, "l, F j, Y");
                break;
            case 5:
                $date = JHTML::_('date', $studydate, "F j, Y");
                break;
            case 6:
                $date = JHTML::_('date', $studydate, "j F Y");
                break;
            case 7:
                $date = JHTML::_('date', $studydate, "j/n/Y");
                break;
            case 8:
                $date = JHTML::_('date', $studydate, JText::_('DATE_FORMAT_LC'));
                break;
            case 9:
                $date = JHTML::_('date', $studydate, "Y/M/D");
                break;
            default:
                $date = JHTML::_('date', $studydate, "n/j");
                break;
        }

        $customDate = $params->get('custom_date_format');

        if ($customDate != '')
        {
            $date = JHTML::_('date', $studydate, $customDate);
        }

        return $date;
    }

	/**
	 * Get Link
	 *
	 * @param   string    $islink        IS A Link
	 * @param   string    $id3           ID3
	 * @param   int       $tid           Template Id
	 * @param   string    $smenu         Sermon Menu
	 * @param   string    $tmenu         Teacher Menu
	 * @param   JRegistry $params        Item Params
	 * @param   JRegistry $admin_params  Admin Params
	 * @param   object    $row           Item Info
	 * @param   int       $template      Template
	 *
	 * @return string
	 */
	private function getLink($islink, $id3, $tid, $smenu, $tmenu, $params, $admin_params, $row, $template)
	{
		$input    = new JInput;
		$Itemid   = $input->get('Itemid', '', 'int');
		$column   = '';
		$mime     = ' AND #__bsms_mediafiles.mime_type = 1';
		$itemlink = $params->get('itemidlinktype');

		switch ($islink)
		{

			case 1 :
				$Itemid = $input->get('Itemid', '', 'int');

				if (!$Itemid)
				{
					$link = JRoute::_('index.php?option=com_biblestudy&view=sermon&id=' . $row->slug . '&t=' . $params->get('detailstemplateid'));
				}
				else
				{
					$link = JRoute::_('index.php?option=com_biblestudy&view=sermon&id=' . $row->slug . '&t=' . $params->get('detailstemplateid'));
				}
				$column = '<a href="' . $link . '">';
				break;

			case 2 :
				$filepath = $this->getFilepath($id3, 'study_id', $mime);
				$link     = JRoute::_($filepath);
				$column .= '<a href="' . $link . '">';
				break;

			case 3 :
				$link = JRoute::_('index.php?option=com_biblestudy&view=teacher&id=' . $tid . '&t=' . $params->get('teachertemplateid'));

				if ($tmenu > 0)
				{
					$link .= '&Itemid=' . $tmenu;
				}
				$column .= '<a href="' . $link . '">';
				break;

			case 4 :
				// Case 4 is a details link with tooltip
				if (!$Itemid)
				{
					$link = JRoute::_(JBSMHelperRoute::getArticleRoute($row->slug) . '&t=' . $params->get('detailstemplateid'));
				}
				else
				{
					$link = JRoute::_(JBSMHelperRoute::getArticleRoute($row->slug) . '&t=' . $params->get('detailstemplateid'));
				}
				$column = JBSMHelper::getTooltip($row->id, $row, $params, $admin_params, $template);
				$column .= '<a href="' . $link . '">';

				break;

			case 5 :
				// Case 5 is a file link with Tooltip
				$filepath = $this->getFilepath($id3, 'study_id', $mime);
				$link     = JRoute::_($filepath);
				$column   = JBSMHelper::getTooltip($row->id, $row, $params, $admin_params, $template);
				$column .= '<a href="' . $link . '">';

				break;

			case 6 :
				// Case 6 is for a link to the 1st article in the media file records
				$column .= '<a href="' . $this->getOtherlinks($id3, $islink, $params) . '">';
				break;

			case 7 :
				// Case 7 is for Virtuemart
				$column .= '<a href="' . $this->getOtherlinks($id3, $islink, $params) . '">';
				break;

			case 8 :
				// Case 8 is for Docman
				$column .= '<a href="' . $this->getOtherlinks($id3, $islink, $params) . '">';
				break;

			case 9 :
				// Case 9 is a link to download
				$column .= '<a href="index.php?option=com_biblestudy&amp;mid=' .
					$row->download_id . '&amp;view=sermons&amp;task=download">';
		}

		return $column;
	}

	/**
	 * Get Listing Exp
	 *
	 * @param   object    $row           Item Info
	 * @param   JRegistry $params        Item Params
	 * @param   JRegistry $admin_params  Admin Params
	 * @param   object    $template      Template
	 *
	 * @return object
	 */
	public function getListingExp($row, $params, $admin_params, $template)
	{
		$Media  = new jbsMedia;
		$images = new JBSMImages;
		$image  = $images->getStudyThumbnail($row->thumbnailm);
		$label  = $params->get('templatecode');
		$label  = str_replace('{{teacher}}', $row->teachername, $label);
		$label  = str_replace('{{title}}', $row->studytitle, $label);
		$label  = str_replace('{{date}}', $this->getStudydate($params, $row->studydate), $label);
		$label  = str_replace('{{studyintro}}', $row->studyintro, $label);
		$label  = str_replace('{{scripture}}', $this->getScripture($params, $row, 0, 1), $label);
		$label  = str_replace('{{topics}}', $row->topic_text, $label);
		$label  = str_replace('{{url}}', JRoute::_('index.php?option=com_biblestudy&view=sermon&id=' . $row->id . '&t=' . $template->id), $label);
		$label  = str_replace('{{mediatime}}', $this->getDuration($params, $row), $label);
		$label  = str_replace('{{thumbnail}}', '<img src="' . $image->path . '" width="' . $image->width . '" height="'
			. $image->height . '" id="bsms_studyThumbnail" />', $label
		);
		$label  = str_replace('{{seriestext}}', $row->series_text, $label);
		$label  = str_replace('{{messagetype}}', $row->message_type, $label);
		$label  = str_replace('{{bookname}}', $row->bookname, $label);
		$label  = str_replace('{{topics}}', $row->topic_text, $label);
		$label  = str_replace('{{hits}}', $row->hits, $label);
		$label  = str_replace('{{location}}', $row->location_text, $label);
		$label  = str_replace('{{plays}}', $row->totalplays, $label);
		$label  = str_replace('{{downloads}}', $row->totaldownloads, $label);

		// For now we need to use the existing mediatable function to get all the media
		$mediaTable = $Media->getMediaTable($row, $params, $admin_params);
		$label      = str_replace('{{media}}', $mediaTable, $label);

		// Need to add template items for media...

		return $label;
	}

	/**
	 * Get Study Exp
	 *
	 * @param   object    $row           Item Info
	 * @param   JRegistry $params        Item Params
	 * @param   JRegistry $admin_params  Admin Params
	 * @param   object    $template      Template
	 *
	 * @return object
	 */
	public function getStudyExp($row, $params, $admin_params, $template)
	{
		$Media = new jbsMedia;

		$images = new JBSMImages;
		$image  = $images->getStudyThumbnail($row->thumbnailm);
		$label  = $params->get('study_detailtemplate');
		$label  = str_replace('{{teacher}}', $row->teachername, $label);
		$label  = str_replace('{{title}}', $row->studytitle, $label);
		$label  = str_replace('{{date}}', $this->getStudydate($params, $row->studydate), $label);
		$label  = str_replace('{{studyintro}}', $row->studyintro, $label);
		$label  = str_replace('{{scripture}}', $this->getScripture($params, $row, 0, 1), $label);
		$label  = str_replace('{{topics}}', $row->topic_text, $label);
		$label  = str_replace('{{mediatime}}', $this->getDuration($params, $row), $label);
		$label  = str_replace('{{thumbnail}}', '<img src="' . $image->path . '" width="' . $image->width . '" height="'
			. $image->height . '" id="bsms_studyThumbnail" />', $label
		);
		$label  = str_replace('{{seriestext}}', $row->seriestext, $label);
		$label  = str_replace('{{messagetype}}', $row->message_type, $label);
		$label  = str_replace('{{bookname}}', $row->bname, $label);
		$label  = str_replace('{{studytext}}', $row->studytext, $label);
		$label  = str_replace('{{hits}}', $row->hits, $label);
		$label  = str_replace('{{location}}', $row->location_text, $label);

		// Passage
		$link = '<strong><a class="heading" href="javascript:ReverseDisplay(\'bsms_scripture\')">>>' . JText::_('JBS_CMN_SHOW_HIDE_SCRIPTURE') . '<<</a>';
		$link .= '<div id="bsms_scripture" style="display:none;"></strong>';
		$response = $this->getPassage($params, $row);
		$link .= $response;
		$link .= '</div>';
		$label = str_replace('{{scripturelink}}', $link, $label);
		$label = str_replace('{{plays}}', $row->totalplays, $label);
		$label = str_replace('{{downloads}}', $row->totaldownloads, $label);


		$mediaTable = $Media->getMediaTable($row, $params, $admin_params);
		$label      = str_replace('{{media}}', $mediaTable, $label);

		// Share
		// Prepares a link string for use in social networking
		$u           = JURI::getInstance();
		$detailslink = htmlspecialchars($u->toString());
		$detailslink = JRoute::_($detailslink);

		// End social networking
		$share = $this->getShare($detailslink, $row, $params, $admin_params);
		$label = str_replace('{{share}}', $share, $label);

		// PrintableView
		$printview = JHTML::_('image.site', 'printButton.png', '/images/M_images/', null, null, JText::_('JBS_CMN_PRINT'));
		$printview = '<a href="#&tmpl=component" onclick="window.print();return false;">' . $printview . '</a>';

		$label = str_replace('{{printview}}', $printview, $label);

		// PDF View
		$url                = 'index.php?option=com_biblestudy&view=sermon&id=' . $row->id . '&format=pdf';
		$status             = 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no';
		$text               = JHTML::_(
			'image.site', 'pdf24.png', '/media/com_biblestudy/images/', null, null, JText::_('JBS_MED_PDF'), JText::_('JBS_MED_PDF')
		);
		$attribs['title']   = JText::_('JBS_MED_PDF');
		$attribs['onclick'] = "window.open(this.href,'win2','" . $status . "'); return false;";
		$attribs['rel']     = 'nofollow';
		$link               = JHTML::_('link', JRoute::_($url), $text, $attribs);

		$label = str_replace('{{pdfview}}', $link, $label);

		// Comments

		return $label;
	}

	/**
	 * Share Helper file
	 *
	 * @param   string    $link          Link
	 * @param   object    $row           Item Info
	 * @param   JRegistry $params        Item Params
	 * @param   JRegistry $admin_params  Admin Params
	 *
	 * @return null|string
	 *
	 * FIXME Look like this is missing the $template var
	 */
	public function getShare($link, $row, $params, $admin_params)
	{
		jimport('joomla.html.parameter');

		// Finde a better way to do this.
		$template = (int) '1';

		$sharetype = $params->get('sharetype', 1);

		if ($sharetype == 1)
		{
			$shareit = '<div class="row-fluid">
						<div class="span2 pull-right">
						';
			$shareit .= '<!-- AddThis Button BEGIN -->
						<a class="addthis_button" href="http://www.addthis.com/bookmark.php?v=250&amp;username=tomfuller2">
						<img src="http://s7.addthis.com/static/btn/v2/lg-share-en.gif" width="125" height="16" alt="Bookmark and Share" style="border:0"/>
						</a>
						<script type="text/javascript">var addthis_config = {"data_track_clickback":true};</script>
						<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username="></script>
						<!-- AddThis Button END --></div>';
		}
		else
		{
			// This will come from $admin_params
			$sharetitle = 'Share This';

			// Get the information from the database on what social networking sites to use
			$db    = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->select('*')->from('#__bsms_share')->where('published = ' . 1)->order('name asc');
			$db->setQuery($query);
			$rows      = $db->loadObjectList();
			$sharerows = count($rows);

			if ($sharerows < 1)
			{
				$share = null;

				return $share;
			}

			// Begin to form the table
			$shareit = '<div class="container-fluid"><div class="row-fluid">
						<div class="span3 pull-right">
						<th id="bsmssharetitle" colspan=' . $sharerows . '>' . $sharetitle . '</th></tr></thead>
						<tbody><tr class="bsmsshareiconrow">';

			foreach ($rows as $sharerow)
			{

				// Convert parameter fields to objects.
				$registry = new JRegistry;
				$registry->loadString($sharerow->params);
				$share_params = $registry;
				$custom       = new JBSMCustom;

				$image      = $share_params->get('shareimage');
				$height     = $share_params->get('shareimageh', '44px');
				$width      = $share_params->get('shareimagew', '44px');
				$totalchars = $share_params->get('totalcharacters');
				$use_bitly  = $share_params->get('use_bitly');
				$mainlink   = $share_params->get('mainlink');
				$appkey     = $share_params->get('api', 'R_dc86635ad2d1e883cab8fad316ca12f6');
				$login      = $share_params->get('username', 'joomlabiblestudy');

				if ($use_bitly == 1)
				{
					$url = $this->make_bitly_url($link, $login, $appkey, 'json', '2.0.1');
				}
				else
				{
					$url = $link;
				}
				$element1          = new stdClass;
				$element1->element = '';
				$element2          = new stdClass;
				$element2->element = '';
				$element3          = new stdClass;
				$element3->element = '';
				$element4          = new stdClass;
				$element4->element = '';

				if ($share_params->get('item1'))
				{
					if ($share_params->get('item1') == 200)
					{
						$element1->element = $url;
					}
					elseif ($share_params->get('item1') == 24)
					{
						$element           = $custom->getCustom(
							$share_params->get('item1'), $share_params->get('item1custom'), $row, $params, $admin_params, $template
						);
						$element1->element = $element->element;
					}
					else
					{
						$element1 = JBSMElements::getElementid($share_params->get('item1'), $row, $params, $admin_params, $template);
					}
				}
				if ($share_params->get('item2'))
				{
					if ($share_params->get('item2') == 200)
					{
						$element2->element = $url;
					}
					elseif ($share_params->get('item2') == 24)
					{
						$element           = $custom->getCustom(
							$share_params->get('item2'), $share_params->get('item2custom'), $row, $params, $admin_params, $template
						);
						$element2->element = $element->element;
					}
					else
					{
						$element2 = JBSMElements::getElementid((int) $share_params->get('item2'), $row, $params, $admin_params, $template);
					}
				}
				if ($share_params->get('item3'))
				{
					if ($share_params->get('item3') == 200)
					{
						$element3->element = $url;
					}
					elseif ($share_params->get('item3') == 24)
					{
						$element           = $custom->getCustom(
							$share_params->get('item3'), $share_params->get('item3custom'),
							$row, $params, $admin_params, $template
						);
						$element3->element = $element->element;
					}
					else
					{
						$element3 = JBSMElements::getElementid($share_params->get('item3'), $row, $params, $admin_params, $template);
					}
				}
				if ($share_params->get('item4'))
				{
					if ($share_params->get('item4') == 200)
					{
						$element4->element = $url;
					}
					elseif ($share_params->get('item4') == 24)
					{
						$element           = $custom->getCustom(
							$share_params->get('item4'), $share_params->get('item4custom'), $row, $params, $admin_params, $template
						);
						$element4->element = $element->element;
					}
					else
					{
						$element4 = JBSMElements::getElementid($share_params->get('item4'), $row, $params, $admin_params, $template);
					}
				}

				$sharelink = $element1->element . ' ' . $share_params->get('item2prefix') . $element2->element . ' ' . $share_params->get('item3prefix')
					. $element3->element . ' ' . $share_params->get('item4prefix') . $element4->element;

				// Added to see if would make Facebook sharer work
				$sharelink = urlencode($sharelink);

				if ($share_params->get('totalcharacters'))
				{
					$sharelength = strlen($sharelink);

					if ($sharelength > $share_params->get('totalcharacters'))
					{
						$linkstartposition  = strpos($sharelink, 'http://', 0);
						$linkendposition    = strpos($sharelink, ' ', $linkstartposition);
						$linkextract        = substr($sharelink, $linkstartposition, $linkendposition);
						$linklength         = strlen($linkextract);
						$sharelink          = substr_replace($sharelink, '', $linkstartposition, $linkendposition);
						$newsharelinklength = $share_params->get('totalcharacters') - $linklength - 1;
						$sharelink          = substr($sharelink, 0, $newsharelinklength);
						$sharelink          = $sharelink . ' ' . $linkextract;
					}
				}
				$shareit .= '<td id="bsmsshareicons">
							<a href="' . $mainlink . $share_params->get('item1prefix') . $sharelink . '" target="_blank">
							<img src="' . JURI::base() . $image . '" alt="' . $share_params->get('alttext') . '" title="'
					. $share_params->get('alttext') . '" width="' . $width . '" height="' . $height . '" border="0">
							</a></td>';

			} // End of foreach

		} // End of else $sharetype
		$shareit .= '</tr></tbody></table></div>';

		return $shareit;
	}

	/**
	 * make a URL small
	 *
	 * @param   string $url      Url
	 * @param   string $login    Login
	 * @param   string $appkey   AppKey
	 * @param   string $format   Format
	 * @param   string $version  Version
	 *
	 * @return string
	 */
	private function make_bitly_url($url, $login, $appkey, $format = 'xml', $version = '2.0.1')
	{
		// Create the URL

		$bitly = 'http://api.bit.ly/shorten?version=' . $version . '&longUrl=' . urlencode($url) . '&login='
			. $login . '&apiKey=' . $appkey . '&format=' . $format;

		// Get the url
		// Could also use cURL here
		$response = file_get_contents($bitly);

		// Parse depending on desired format
		if (strtolower($format) == 'json')
		{
			$json  = json_decode($response, true);
			$short = $json['results'][$url]['shortUrl'];
		}
		else
		{ // Xml
			$xml   = simplexml_load_string($response);
			$short = 'http://bit.ly/' . $xml->results->nodeKeyVal->hash;
		}

		return $short;
	}

	/**
	 * Get Passage
	 *
	 * @param   object $params  Item Params
	 * @param   object $row     Item Info
	 *
	 * @return string
	 */
	public function getPassage($params, $row)
	{
		$esv          = 1;
		$scripturerow = 1;
		$scripture    = $this->getScripture($params, $row, $esv, $scripturerow);

		if ($scripture)
		{
			$key      = "IP";
			$response = "" . $scripture . " (ESV)";
			$passage  = urlencode($scripture);
			$options  = "include-passage-references=false";
			$url      = "http://www.esvapi.org/v2/rest/passageQuery?key=$key&passage=$passage&$options";

			// This tests to see if the curl functions are there. It will return false if curl not installed
			$p = (get_extension_funcs("curl"));

			if ($p)
			{ // If curl is installed then we go on

				// This will return false if curl is not enabled
				$ch = curl_init($url);

				if ($ch)
				{ // This will return false if curl is not enabled
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					$response .= curl_exec($ch);
					curl_close($ch);

				} // End of if ($ch)

			} // End if ($p)
		}
		else
		{
			$response = JText::_('JBS_STY_NO_PASSAGE_INCLUDED');
		}

		return $response;
	}

	/**
	 * Get Other Links
	 *
	 * @param   int    $id3     Study ID ID
	 * @param   string $islink  Is a Link
	 * @param   object $params  Item Params
	 *
	 * @return string
	 */
	public function getOtherlinks($id3, $islink, $params)
	{
		$link  = '';
		$db    = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('#__bsms_mediafiles.*')
			->from('#__bsms_mediafiles')
			->where('study_id = ' . $db->q($id3))
			->where('#__bsms_mediafiles.published = 1');
		$db->setQuery($query);
		$db->query();
		$num_rows = $db->getNumRows();

		if ($num_rows > 0)
		{
			$mediafiles = $db->loadObjectList();

			foreach ($mediafiles AS $media)
			{
				switch ($islink)
				{
					case 6:
						if ($media->article_id > 0)
						{
							$link = 'index.php?option=com_content&view=article&id=' . $media->article_id;
						}
						break;

					case 7:
						if ($media->virtueMart_id > 0)
						{
							$link = 'index.php?option=com_virtuemart&page=shop.product_details&flypage='
								. $params->get('store_page', 'flypage.tpl') . '&product_id=' . $media->virtueMart_id;
						}
						break;

					case 8:
						if ($media->docMan_id > 0)
						{
							$link = 'index.php?option=com_docman&task=doc_download&gid=' . $media->docMan_id;
						}
						break;
				}
			}
		}

		return $link;
	}
/* @todo I believe all of the functions below can be removed TF */
	/**
	 * Get Title
	 *
	 * @param   JRegistry $params        System Params
	 * @param   object    $row           Item info
	 * @param   JRegistry $admin_params  Admin Params
	 * @param   int       $template      Template
	 *
	 * @return string
	 */
	public function getTitle($params, $row, $admin_params, $template)
	{

		$title  = null;
		$custom = new JBSMCustom;

		if ($params->get('title_line_1') > 0)
		{
			$title = '<table class="table" id="titletable"><tbody><tr><td class="titlefirstline">';

			switch ($params->get('title_line_1'))
			{
				case 0:
					$title .= null;
					break;
				case 1:
					$title .= $row->studytitle;
					break;
				case 2:
					$title .= $row->teachername;
					break;
				case 3:
					$title .= $row->title . ' ' . $row->teachername;
					break;
				case 4:
					$esv       = 0;
					$scripture = $this->getScripture($params, $row, $esv, $scripturerow = 1);
					$title .= $scripture;
					break;
				case 5:
					$title .= $row->stext;
					break;
				case 6:
					$title .= $row->topics_text;
					break;
				case 7:
					$elementid = $custom->getCustom($rowid = 0, $params->get('customtitle1'), $row, $params, $admin_params, $template);
					$title .= $elementid->element;
					break;
			}
			$title .= '</td></tr>';
		}

		if ($params->get('title_line_2') > 0)
		{
			$title .= '<tr><td class="titlesecondline" >';

			switch ($params->get('title_line_2'))
			{
				case 0:
					$title .= null;
					break;
				case 1:
					$title .= $row->studytitle;
					break;
				case 2:
					$title .= $row->teachername;
					break;
				case 3:
					$title .= $row->title . ' ' . $row->teachername;
					break;
				case 4:
					$esv       = 0;
					$scripture = $this->getScripture($params, $row, $esv, $scripturerow = 1);
					$title .= $scripture;
					break;
				case 5:
					$title .= $row->stext;
					break;
				case 6:
					$title .= $row->topics_text;
					break;
				case 7:
					$elementid = $custom->getCustom($rowid = 0, $params->get('customtitle2'), $row, $params, $admin_params, $template);
					$title .= $elementid->element;
					break;
			}
			$title .= '</td></tr>';

		} // End of if title2
		$title .= '</tbody></table>';

		return $title;
	}


	/**
	 * Get CustomHead
	 *
	 * @param   int       $rowcolid  Row ID Column
	 * @param   JRegistry $params    Item Params
	 *
	 * @return string
	 */
	private function getCustomhead($rowcolid, $params)
	{
		$row        = substr($rowcolid, 3, 1);
		$col        = substr($rowcolid, 7, 1);
		$customhead = $params->get('r' . $row . 'c' . $col . 'customlabel');

		return $customhead;
	}

}
