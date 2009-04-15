<?php defined('_JEXEC') or die('Restricted access'); ?>
<script type="text/javascript" src="components/com_biblestudy/tooltip.js"></script>
<?php
global $mainframe, $option;
$message = JRequest::getVar('msg');
$database = & JFactory::getDBO();
$teacher_menu = $this->params->get('teacher_id', 1);
$topic_menu = $this->params->get('topic_id', 1);
$book_menu = $this->params->get('booknumber', 101);
$location_menu = $this->params->get('locations', 1);
$series_menu = $this->params->get('series_id', 1);
$messagetype_menu = $this->params->get('messagetype', 1);
$imageh = $this->params->get('imageh', 24);
$imagew = $this->params->get('imagew', 24);
$color1 = $this->params->get('color1');
$color2 = $this->params->get('color2');
$page_width = $this->params->get('page_width', '100%');
$widpos1 = $this->params->get('widthcol1');
$widpos2 = $this->params->get('widthcol2');
$widpos3 = $this->params->get('widthcol3');
$widpos4 = $this->params->get('widthcol4');
$show_description = $this->params->get('show_description', 1);
$downloadCompatibility = $this->params->get('compatibilityMode');
?>

<form action="<?php echo str_replace("&","&amp;",$this->request_url); ?>" method="post" name="adminForm"><?php
 //Set some initalization parameters
 $user =& JFactory::getUser();
 $entry_user = $user->get('gid');
 if (!$entry_user) {
  $entry_user = 0;
 }
 $entry_access = $this->params->get('entry_access');
 if (!$entry_access) {
  $entry_access = 23;
 }
 $allow_entry = $this->params->get('allow_entry_study');
?>

<?php
//Start test to see if message needs to be displayed
if ($message) {?>
 <!-- Begin Message -->
 <div style="width:100%">
  <h2><?php echo $message;?></h2>
 </div>
 <!-- End Message --><?php
  }?>

<?php
//Start test to see if frontend study entry is allowed
if ($allow_entry > 0) {
 if ($entry_access <= $entry_user) {?>
 <!-- Begin Front End Study Submission -->
 <div style="width:100%;">
  <strong>Studies</strong>
  <br><a href="<?php echo JURI::base()?>index.php?option=com_biblestudy&controller=studiesedit&view=studiesedit&layout=form">Add a New Study</a>
  <br><a href="<?php echo JURI::base()?>index.php?option=com_biblestudy&controller=mediafilesedit&view=mediafilesedit&layout=form">Add a New Media File Record</a>
<?php
  if ($this->params->get('show_comments') > 0){?>
  <br><a href="<?php echo JURI::base()?>index.php?option=com_biblestudy&view=commentslist">Manage Comments</a>
<?php
  }?>
 </div>
 <!-- End Front End Study Submission --><?php
 }
}
//End test to see if frontend study entry is allowed?>

<?php
 //Start test to see if frontend podcast entry is allowed
if ($this->params->get('allow_podcast') > 0){
 $podcast_access = $this->params->get('podcast_access');
 if (!$podcast_access) {
  $podcast_access = 23;
 }
 if ($podcast_access <= $entry_user){
  $query = ('SELECT id, title, published FROM #__bsms_podcast WHERE published = 1 ORDER BY title ASC');
  $database->setQuery( $query );
  $podcasts = $database->loadAssocList();
  ?>
 <!-- Begin Front End Podcast Submission -->
 <div style="width:100%;">
  <strong>Podcasts</strong>
  <br><a href="<?php echo JURI::base().'index.php?option=com_biblestudy&controller=podcastedit&view=podcastedit&layout=form';?>">Add A Podcast</a>
<?php 
  foreach ($podcasts as $podcast){
  $pod = $podcast['id']; $podtitle = $podcast['title'];?>
  <br><a href="<?php echo JURI::base().'index.php?option=com_biblestudy&controller=podcastedit&view=podcastedit&layout=form&task=edit&cid[]='.$pod;?>"><?php echo $podtitle;?></a>
<?php
  }?>
 </div>
 <!-- End Front End Podcast Submission --><?php
 }
}
 //End test to see if frontend podcast entry is allowed?>


<?php 
 //Begin display page logo and title
$wtd = $this->params->get('pimagew');?>
 <!-- Begin Display Page image and Title -->
 <div style="width:100%;">
  <!-- <h1 class="componentheading<?php echo $this->params->get( 'pageclass_sfx' ); ?>" style="border: 1px solid;"> -->
<?php
 if ($this->params->get( 'show_page_image' ) >0) {
 $pimagew = $this->params->get('pimagew');
 $pimageh = $this->params->get('pimageh');
 if ($pimagew) {$width = $pimagew;} else {$width = 24;}
 if ($pimageh) {$height = $pimageh;} else {$height= 24;}
 ?>
  <img src="<?php echo JURI::base().$this->params->get('page_image');?>" alt="<?php echo $this->params->get('page_title'); ?>" width="<?php echo $width;?>" height="<?php echo $height;?>" />
<?php //End of column for logo
}
if ( $this->params->get( 'show_page_title_list' ) >0 ) {
?>
  <span class="componentheading<?php echo $this->params->get( 'pageclass_sfx' ); ?>" style="line-height:<?php echo $height; ?>px;"><?php echo $this->params->get('page_title');?></span>
<?php
}?>
  <!-- </h1> -->
 </div>
 <!-- End Display Page image and Title --><?php
 //End display page logo and title?>


 <table width="<?php echo $page_width; ?>">
 <?php if ($this->params->get('show_teacher_list') >0) { ?>
 <tr>
  <td width="<?php echo $this->params->get('teacherw');?>"><img
   src="<?php echo $this->params->get('teacherimage');?>" border="1"
   width="<?php echo $this->params->get('teacherw');?>"
   height="<?php echo $this->params->get('teacherh');?>" /><br />
   <?php echo $this->params->get('teachername');?></td>
 </tr>
 <?php }?>
 <tr>
  <td><?php //Row for drop down boxes?> <?php //This is the column that holds the search drop downs?>

  <?php if ($this->params->get('show_locations_search') > 0 && !($location_menu)) { echo $this->lists['locations'];}?>
  <?php if ($this->params->get('show_book_search') >0 && !($book_menu) ){ ?>

  <?php $query2 = 'SELECT id, booknumber AS value, bookname AS text, published'
  . ' FROM #__bsms_books'
  . ' WHERE published = 1'
  . ' ORDER BY booknumber';
  $database->setQuery( $query2 );
  $bookid = $database->loadAssocList();
  $filter_book  = $mainframe->getUserStateFromRequest( $option.'filter_book', 'filter_book',0,'int' );
  echo '<select name="filter_book" id="filter_book" class="inputbox" size="1" onchange="this.form.submit()"><option value="0"';
  if (!$filter_book ) {
   echo 'selected="selected"';}
   echo '>- '.JText::_('Select a Book').' -'.'</option>';
   foreach ($bookid as $bookid2) {
    $format = $bookid2['text'];
    $output = JText::_($format);
    $bookvalue = $bookid2['value'];
    if ($bookvalue == $filter_book){
     $selected = 'selected="selected"';
     echo '<option value="'.$bookvalue.'"'.$selected.' >'.$bookid2['text'].'</option>';
    } else {
     echo '<option value="'.$bookvalue.'">'.$output.'</option>';
    }
   };
   echo '</select>';?> <?php } ?> <?php if ($this->params->get('show_teacher_search') >0 && !($teacher_menu)) { ?>
   <?php echo $this->lists['teacher_id'];?> <?php } ?> <?php if ($this->params->get('show_series_search') >0 && !($series_menu)){ ?>
   <?php echo $this->lists['seriesid'];?> <?php } ?> <?php if ($this->params->get('show_type_search') >0 && !($messagetype_menu)) { ?>
   <?php echo $this->lists['messagetypeid'];?> <?php } ?> <?php if ($this->params->get('show_year_search') >0){ ?>
   <?php echo $this->lists['studyyear'];?> <?php } ?> <?php if ($this->params->get('show_order_search') >0) { ?>
   <?php
   $query6 = ' SELECT * FROM #__bsms_order '
   . ' ORDER BY id ';
   $database->setQuery( $query6 );
   $sortorder = $database->loadAssocList();
   $filter_orders  = $mainframe->getUserStateFromRequest( $option.'filter_orders','filter_orders','DESC','word' );
   echo '<select name="filter_orders" id="filter_orders" class="inputbox" size="1" onchange="this.form.submit()"><option value="0"';
   if (!$filter_orders ) {
    echo 'selected="selected"';}
    echo '>- '.JText::_('Select an Order').' -'.'</option>';
    foreach ($sortorder as $sortorder2) {
     $format = $sortorder2['text'];
     $output = JText::sprintf($format);
     $sortvalue = $sortorder2['value'];
     if ($sortvalue == $filter_orders){
      $selected = 'selected="selected"';
      echo '<option value="'.$sortvalue.'"'.$selected.' >'.$output.'</option>';
     } else {
      echo '<option value="'.$sortvalue.'">'.$output.'</option>';
     }
    };
    echo '</select>';?> <?php //echo $this->lists['sorting'];?> <?php } ?>
    <?php if ($this->params->get('show_topic_search') >0) { ?> <?php
    $query8 = 'SELECT DISTINCT #__bsms_studies.topics_id AS value, #__bsms_topics.topic_text AS text'
    . ' FROM #__bsms_studies'
    . ' LEFT JOIN #__bsms_topics ON (#__bsms_topics.id = #__bsms_studies.topics_id)'
    . ' WHERE #__bsms_topics.published = 1'
    . ' ORDER BY #__bsms_topics.topic_text ASC';
    $database->setQuery( $query8 );
    $topicsid = $database->loadAssocList();
    $filter_topic  = $mainframe->getUserStateFromRequest( $option.'filter_topic', 'filter_topic',0,'int' );
    echo '<select name="filter_topic" id="filter_topic" class="inputbox" size="1" onchange="this.form.submit()"><option value="0"';
    if (!$filter_topic ) {
     echo 'selected="selected"';}
     echo '>- '.JText::_('Select a Topic').' -'.'</option>';
     foreach ($topicsid as $topicsid2) {
      $format = $topicsid2['text'];
      $output = JText::sprintf($format);
      $topicsvalue = $topicsid2['value'];
      if ($topicsvalue == $filter_topic){
       $selected = 'selected="selected"';
       echo '<option value="'.$topicsvalue.'"'.$selected.' >'.$output.'</option>';
      } else {
      echo '<option value="'.$topicsvalue.'">'.$output.'</option>';}
     };
     echo '</select>';?> <?php //echo $this->lists['topics'];?> <?php } ?>

  </td>
 </tr>
 <?php //End of row for drop down boxes?>

 <?php // The table to hold header rows ?>
 <tr><td>
 <table width="<?php echo $this->params->get('header_width', '100%');?>" cellpadding="0">
<?php //mirrors 6 colum table below?>
  <tr>
   <td></td>
<?php 
$header_call = JView::loadHelper('header');
$header = getHeader($this->params);
echo $header;
?>
  </tr>
 </table>
 </td></tr><?php //End of table for header rows?>


  <?php //This is where each result from the database of studies is diplayed with options for each 6 column table?>

  <?php

  $k = 1;
  $row_count = 0;
  ?>
 <tr>
  <td><?php 
  for ($i=0, $n=count( $this->items ); $i < $n; $i++)
  { // This is the beginning of a loop that will cycle through all the records according to the query?>
  <?php $bgcolor = ($row_count % 2) ? $color1 : $color2; //This code cycles through the two color choices made in the parameters?>
  <?php $row = &$this->items[$i]; ?>
  <?php
 

  /* Now we do this small line which is basically going to tell
   PHP to alternate the colors between the two colors we defined above. */
  $bgcolor = ($row_count % 2) ? $color1 : $color2;
  ?>
  <?php
$id4 = $row->id;
$filesizefield = '#__bsms_mediafiles.study_id';
//$filepath_call = JView::loadHelper('filepath'); 
$filesize_call = JView::loadHelper('filesize');
$file_size = getFilesize($id4, $filesizefield);

  
  
  $show_media = $this->params->get('show_media',1);
  $filesize_showm = $this->params->get('filesize_showm');
  $link = JRoute::_('index.php?option=com_biblestudy&view=studydetails&id=' . $row->id);
  $duration = $row->media_hours.$row->media_minutes.$row->media_seconds;
  if (!$duration) { $duration = '';}
  else {
	  $duration_type = $this->params->get('duration_type');
	  $hours = $row->media_hours;
	  $minutes = $row->media_minutes;
	  $seconds = $row->media_seconds;
	  $duration_call = JView::loadHelper('duration');
	  $duration = getDuration($duration_type, $hours, $minutes, $seconds);
  }


  $booknumber = $row->booknumber;
  $ch_b = $row->chapter_begin;
  $ch_e = $row->chapter_end;
  $v_b = $row->verse_begin;
  $v_e = $row->verse_end;
  $id2 = $row->id;
  $show_verses = $this->params->get('show_verses');
  $scripture1 = format_scripture2($id2, $esv, $booknumber, $ch_b, $ch_e, $v_b, $v_e, $show_verses);
  if ($row->booknumber2){
   $booknumber = $row->booknumber2;
   $ch_b = $row->chapter_begin2;
   $ch_e = $row->chapter_end2;
   $v_b = $row->verse_begin2;
   $v_e = $row->verse_end2;
   $id2 = $row->id;
  $scripture2 = format_scripture2($id2, $esv, $booknumber, $ch_b, $ch_e, $v_b, $v_e, $show_verses);
  }
  $df =  ($this->params->get('date_format'));
  $date_call = JView::loadHelper('date');
  $date = getstudyDate($df, $row->studydate);	

  $textwidth=$this->params->get('imagew');
  $textwidth = ($textwidth + 1);
  $storewidth = $this->params->get('storewidth');
  $teacher = $row->teachername;
  $study = $row->studytitle;
  $sname = $row->series_text;
  $intro = str_replace('"','',$row->studyintro);
  $mtype = $row->message_type;
  $snumber = $row->studynumber;
  $details_text = $this->params->get('details_text');
  $filesize_show = $this->params->get('filesize_show');
  $secondary = $row->secondary_reference;
  if (!$row->booknumber2){$scripture2 = '';}
  //if ($number_rows < 1) {$file_size = '0';}
  $params		=& $mainframe->getParams('com_biblestudy');
$listarraycall = JView::loadHelper('listarray');
$a = getListarray($params, $row, $scripture1, $scripture2, $date, $file_size, $duration);

  //This calls the helper once that will process each column's array, coming from the $a variable. We will then call a function in each column from this helper file
  $array_call = JView::loadHelper('columnarray');
  $color = $this->params->get('use_color');
  ?>

<?php //Beginning of row for 6 column table?> <?php if ($this->params->get('line_break') > 0) {echo '<br />'; } ?>
  <table <?php if ($color > 0){echo 'bgcolor="'.$bgcolor.'"';}?>
   width="<?php echo $page_width; ?>" cellpadding="0" cellspacing="0">
   <?php //6 Column table?>
   <tr valign="<?php echo $this->params->get('colalign');?>">
   <?php //Row for 6 column table

if ($entry_user >= $entry_access){//This adds a <td> for user frontend editing of the record?>
    <td width="10" valign="<?php echo $this->params->get('colalign');?>"><a
     href="<?php echo JURI::base();?>index.php?option=com_biblestudy&controller=studiesedit&view=studiesedit&task=edit&layout=form&cid[]=<?php echo $row->id;?>"><?php echo JText::_('[Edit]');?></a></td>
     <?php } //End of front end user authorized to edit records?>
<?php 	
		$columnnumber = 1;
		$column = getColumnarray($a, $row, $columnnumber, $this->params);
		echo $column; 
		
		$columnnumber = 2;
		$column = getColumnarray($a, $row, $columnnumber, $this->params);
		echo $column; 
		
		$columnnumber = 3;
		$column = getColumnarray($a, $row, $columnnumber, $this->params);
		echo $column; 
	 	
		$columnnumber = 4;
		$column = getColumnarray($a, $row, $columnnumber, $this->params);
		echo $column; 
		
		if (($this->params->get('show_full_text') + $this->params->get('show_pdf_text')) > 0) { //Tests to see if show text and/or pdf is set to "show"?>

    <td width="<?php echo $textwidth;?>"><?php //Column 5 of 6 column table - this is to hold the text and pdf images/links?>
    <table align="left">
     <tr valign="<?php echo $this->params->get('colalign');?>">

     <?php
$text_call = JView::loadHelper('textlink');
     if ($this->params->get('show_full_text') > 0) {

$textorpdf = 'text';
$textlink = getTextlink($params, $row, $scripture1, $textorpdf);
echo $textlink;    ?>

      <?php } // end of show_full_text if ?>

      <?php if ($this->params->get('show_pdf_text') > 0) {
        $textorpdf = 'pdf';
$pdflink = getTextlink($params, $row, $scripture1, $textorpdf);
echo $pdflink;
?>

       <?php } // End of show pdf text ?>
     </tr>
    </table>
    </td>
    <?php //End column 5 of 6?>

    <?php } //This is the end of the if statement to see if text and/or pdf images set to "show"?>
    <?php if ($this->params->get('show_store') > 0){?>
<?php 
	$rowid = $row->id;
	$callstore = JView::loadHelper('store');
	$store = getStore($this->params, $rowid);
	echo $store; ?>
    <?php  }//End of store column?>

    <?php if ($this->params->get('show_media') > 0) { ?>
<?php 
$ismodule = 0;
$params = $this->params;
$filesize_call = JView::loadHelper('filesize');
$call_filepath = JView::loadHelper('filepath');
$call_mediatable = JView::loadHelper('mediatable');
$mediatable = getMediatable($params, $row->id, $ismodule, $duration);
echo $mediatable;
?>  
    <?php } //This is the end of the if show media statement ?>
    
<?php //this is the end of the move media to funciont ?>

   </tr>
   <?php //End row for 6 column table?>
  </table>
  <?php //End 6 Column table?>
  <table width="<?php echo $page_width; ?>"
  <?php if ($color > 0){echo 'bgcolor="'.$bgcolor.'"';}?>>
   <?php if ($show_description > 0) { ?>
   <tr>
    <td><?php  echo '<span '.$this->params->get('descriptionspan').'> '.$row->studyintro.'</span>'; ?>
    </td>
   </tr>
   <?php if ($this->params->get('line') > 0) { ?>
   <tr>
    <td width="<?php echo $this->params->get('mod_table_width');?>"><?php //This row is to hold the line and should run along the bottom of the 5 column table?>
    <?php echo '<img src="'.JURI::base().'components/com_biblestudy/images/square.gif" height="2" width="100%" alt="" />'; ?>
    <?php } //End of if show lines?></td>
   </tr>
   <?php } // End show description?>

  </table>

  <?php    $row_count++; // This increments the row count and adjusts the variable for the color background
  $k = 3 - $k;
  } //This is the end of the for statement for each result from the database that will create its own 6 column table?>

  </td>
 </tr>
 <?php //End of row for 6 column table?>


 <tfoot>
 <tr>
  <td align="center"><?php 
  echo '&nbsp;&nbsp;&nbsp;'.JText::_('Display Num').'&nbsp;';
  echo $this->pagination->getLimitBox();
  echo $this->pagination->getPagesLinks();
  echo $this->pagination->getPagesCounter();
  //echo $this->pagination->getListFooter(); ?>
  </td>
 </tr>
 </tfoot>
</table> <?php //This is the end of the table for the overall listing page?>
<input type="hidden" name="option" value="com_biblestudy" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="studieslist" />
</form>
