<?php defined('_JEXEC') or die('Restriced Access');
//Helper file - master list creater for study lists
function getListing($row, $params, $oddeven, $admin_params, $template, $ismodule)
{
	$path1 = JPATH_SITE.DS.'components'.DS.'com_biblestudy'.DS.'helpers'.DS;
	include_once($path1.'elements.php');
	include_once($path1.'custom.php');
	//Here we test to see if this is a studydetails or list view. If details, we reset the params to the details. this keeps us from having to rewrite all this code.
	$view = JRequest::getVar('view', 'get');
	if ($view == 'studydetails' && $ismodule < 1)
		{
			
		$params->set('row1col1', $params->get('drow1col1'));
		$params->set('r1c1custom', $params->get('dr1c1custom'));
		$params->set('r1c1span', $params->get('dr1c1span'));
		$params->set('linkr1c1', $params->get('dlinkr1c1'));
		
		$params->set('row1col2', $params->get('drow1col2'));
		$params->set('r1c2custom', $params->get('dr1c2custom'));
		$params->set('r1c2span', $params->get('dr1c2span'));
		$params->set('linkr1c2', $params->get('dlinkr1c2'));
		
		$params->set('row1col3', $params->get('drow1col3'));
		$params->set('r1c3custom', $params->get('dr1c3custom'));
		$params->set('r1c3span', $params->get('dr1c3span'));
		$params->set('linkr1c3', $params->get('dlinkr1c3'));
		
		$params->set('row1col4', $params->get('drow1col4'));
		$params->set('r1c4custom', $params->get('dr1c4custom'));
		$params->set('linkr1c4', $params->get('dlinkr1c4'));
		
		
		$params->set('row2col1', $params->get('drow2col1'));
		$params->set('r2c1custom', $params->get('dr2c1custom'));
		$params->set('r2c1span', $params->get('dr2c1span'));
		$params->set('linkr2c1', $params->get('dlinkr2c1'));
		
		$params->set('row2col2', $params->get('drow2col2'));
		$params->set('r2c2custom', $params->get('dr2c2custom'));
		$params->set('r2c2span', $params->get('dr2c2span'));
		$params->set('linkr2c2', $params->get('dlinkr2c2'));
		
		$params->set('row2col3', $params->get('drow2col3'));
		$params->set('r2c3custom', $params->get('dr2c3custom'));
		$params->set('r2c3span', $params->get('dr2c3span'));
		$params->set('linkr2c3', $params->get('dlinkr2c3'));
		
		$params->set('row2col4', $params->get('drow2col4'));
		$params->set('r2c4custom', $params->get('dr2c4custom'));
		$params->set('linkr2c4', $params->get('dlinkr2c4'));
		
		
		$params->set('row3col1', $params->get('drow3col1'));
		$params->set('r3c1custom', $params->get('dr3c1custom'));
		$params->set('r3c1span', $params->get('dr3c1span'));
		$params->set('linkr3c1', $params->get('dlinkr3c1'));
		
		$params->set('row3col2', $params->get('drow3col2'));
		$params->set('r3c2custom', $params->get('dr3c2custom'));
		$params->set('r3c2span', $params->get('dr3c2span'));
		$params->set('linkr3c2', $params->get('dlinkr3c2'));
		
		$params->set('row3col3', $params->get('drow3col3'));
		$params->set('r3c3custom', $params->get('dr3c3custom'));
		$params->set('r3c3span', $params->get('dr3c3span'));
		$params->set('linkr3c3', $params->get('dlinkr3c3'));
		
		$params->set('row3col4', $params->get('drow3col4'));
		$params->set('r3c4custom', $params->get('dr3c4custom'));
		$params->set('linkr3c4', $params->get('dlinkr3c4'));
		
	
		$params->set('row4col1', $params->get('drow4col1'));
		$params->set('r4c1custom', $params->get('dr4c1custom'));
		$params->set('r4c1span', $params->get('dr4c1span'));
		$params->set('linkr4c1', $params->get('dlinkr4c1'));
		
		$params->set('row4col2', $params->get('drow4col2'));
		$params->set('r4c2custom', $params->get('dr4c2custom'));
		$params->set('r4c2span', $params->get('dr4c2span'));
		$params->set('linkr4c2', $params->get('dlinkr4c2'));
		
		$params->set('row4col3', $params->get('drow4col3'));
		$params->set('r4c3custom', $params->get('dr4c3custom'));
		$params->set('r4c3span', $params->get('dr4c3span'));
		$params->set('linkr4c3', $params->get('dlinkr4c3'));
		
		$params->set('row4col4', $params->get('drow4col4'));
		$params->set('r4c4custom', $params->get('dr4c4custom'));
		$params->set('linkr4c4', $params->get('dlinkr4c4'));
		
		}
	//Need to know if last column and last row
	$columns = 1;
	if ($params->get('row1col2') > 0 || $params->get('row2col2') > 0 || $params->get('row3col2') > 0 || $params->get('row4col2') > 0){$columns = 2;}
	if ($params->get('row1col3') > 0 || $params->get('row2col3') > 0 || $params->get('row3col3') > 0 || $params->get('row4col3') > 0) {$columns = 3;}
	if ($params->get('row1col4') > 0 || $params->get('row2col4') > 0 || $params->get('row3col4') > 0 || $params->get('row4col4') > 0) {$columns = 4;}
	$rows = 1;
	if ($params->get('row2col1') > 0 || $params->get('row2col2') > 0 || $params->get('row2col3') > 0 || $params->get('row2col4') > 0) {$rows = 2;}
	if ($params->get('row3col1') > 0 || $params->get('row3col2') > 0 || $params->get('row3col3') > 0 || $params->get('row3col4') > 0) {$rows = 3;}
	if ($params->get('row4col1') > 0 || $params->get('row4col2') > 0 || $params->get('row4col3') > 0 || $params->get('row4col4') > 0) {$rows = 4;}
	$islink = $params->get('islink');
	$id3 = $row->id;
	$smenu = $params->get('detailsitemid');
	$tmenu = $params->get('teacheritemid');
	$tid = $row->tid;
	$entry_access = $admin_params->get('entry_access');
	$allow_entry = $admin_params->get('allow_entry_study');
	//This is the beginning of row 1
	$lastrow = 0;
 	if ($rows == 1) {$lastrow = 1;}
	
	$listing = '<tr class="'.$oddeven; //This begins the row of the display data
	if ($lastrow == 1) {$listing .= ' lastrow';}
	$listing .= '">
	'; 
	
		$rowcolid = 'row1col1';
		if ($params->get('row1col1') < 1) {$params->set('row1col1', 100);}
		if ($params->get('row1col1') == 24) {$elementid = getCustom($params->get('row1col1'), $params->get('r1c1custom'), $row, $params, $admin_params, $template);}
		else {$elementid = getElementid($params->get('row1col1'), $row, $params, $admin_params, $template);}
		//dump ($params->get('row1col1'), 'elementid: ');
 		$colspan = $params->get('r1c1span');
 		$rowspan = $params->get('rowspanr1c1');
		 $lastcol = 0;
		 if ($columns == 1 || $colspan > 3) {$lastcol = 1;}
		 if (isset($elementid)) {
		 $listing .= getCell($elementid->id, $elementid->element, $rowcolid, $colspan, $rowspan, $lastcol, $params->get('linkr1c1'),$id3, $tid, $smenu, $tmenu, $entry_access, $allow_entry, $params);
		 }
	if ($columns > 1 && $params->get('r1c1span') < 2 )
	{
 		$rowcolid = 'row1col2';
		if ($params->get('row1col2') < 1) {$params->set('row1col2', 100);}
		if ($params->get('row1col2') == 24) {$elementid = getCustom($params->get('row1col2'), $params->get('r1c2custom'), $row, $params, $admin_params, $template);}
		else {$elementid = getElementid($params->get('row1col2'), $row, $params, $admin_params, $template);}
 		$colspan = $params->get('r1c2span');
 		$rowspan = $params->get('rowspanr1c2');
 		$lastcol = 0;
 		if ($columns == 2 || $colspan > 2) {$lastcol = 1;} 
 		if (isset($elementid)) {
		$listing .= getCell($elementid->id, $elementid->element, $rowcolid, $colspan, $rowspan, $lastcol, $params->get('linkr1c2'), $id3, $tid, $smenu, $tmenu, $entry_access, $allow_entry, $params);
		}
	}
	
	if ($columns > 2  && ( $params->get('r1c1span') < 3 && $params->get('r1c2span') < 2)) 
	{
		 $rowcolid = 'row1col3';
		 if ($params->get('row1col3') < 1) {$params->set('row1col3', 100);}
		 if ($params->get('row1col3') == 24) {$elementid = getCustom($params->get('row1col3'), $params->get('r1c3custom'), $row, $params, $admin_params, $template);}
		else {$elementid = getElementid($params->get('row1col3'), $row, $params, $admin_params, $template);}
		 $colspan = $params->get('r1c3span');
		 $rowspan = $params->get('rowspanr1c3');
		 $lastcol = 0;
		 if ($columns == 3 || $colspan > 1) {$lastcol = 1;} 
		 if (isset($elementid)) {
		 $listing .= getCell($elementid->id, $elementid->element, $rowcolid, $colspan, $rowspan, $lastcol, $params->get('linkr1c3'), $id3, $tid, $smenu, $tmenu, $entry_access, $allow_entry, $params);
		 }
	}
	
	if ($columns > 3 && ( $params->get('r1c1span') < 4 && $params->get('r1c2span') < 3 && $params->get('r1c3span') < 2))
	{
		 $rowcolid = 'row1col4';
		 if ($params->get('row1col4') < 1) {$params->set('row1col4', 100);}
		 if ($params->get('row1col4') == 24) {$elementid = getCustom($params->get('row1col4'), $params->get('r1c4custom'), $row, $params, $admin_params, $template);}
		else {$elementid = getElementid($params->get('row1col4'), $row, $params, $admin_params, $template);}
		 $colspan = $params->get('r1c4span');
		 $rowspan = $params->get('rowspanr1c4');
		 $lastcol = 0;
		 if ($columns == 4) {$lastcol = 1;}
		 if (isset($elementid)) {
		 $listing .= getCell($elementid->id, $elementid->element, $rowcolid, $colspan, $rowspan, $lastcol, $params->get('linkr1c4'), $id3, $tid, $smenu, $tmenu, $entry_access, $allow_entry, $params);
		 }
	}
	$listing .= '
	</tr>
	'; //This ends the row of the data to be displayed				 
	//This is the end of row 1
	
	//This is the beginning of row 2
	
	$lastrow = 0;
 	if ($rows == 2) {$lastrow = 1;}
	$listing .= '<tr class="'.$oddeven; //This begins the row of the display data
	if ($lastrow == 1) {$listing .= ' lastrow';}
	
	$listing .= '">
	'; 
	
		 $rowcolid = 'row2col1';
		 if ($params->get('row2col1') < 1) {$params->set('row2col1', 100);}
		 if ($params->get('row2col1') == 24) {$elementid = getCustom($params->get('row2col1'), $params->get('r2c1custom'), $row, $params, $admin_params, $template);}
		else {$elementid = getElementid($params->get('row2col1'), $row, $params, $admin_params, $template);}
 		$colspan = $params->get('r2c1span');
 		$rowspan = $params->get('rowspanr2c1');;
		 $lastcol = 0;
		 if ($columns == 1 || $colspan > 3) {$lastcol = 1;}
		 if (isset($elementid)) {
		 $listing .= getCell($elementid->id, $elementid->element, $rowcolid, $colspan, $rowspan, $lastcol, $params->get('linkr2c1'), $id3, $tid, $smenu, $tmenu, $entry_access, $allow_entry, $params);
		 }
 	//dump ($elementid, 'elementid: ');
	if ($columns > 1  && $params->get('r2c1span') < 2)
	{
 		$rowcolid = 'row2col2';
		if ($params->get('row2col2') < 1) {$params->set('row2col2', 100);} 
		if ($params->get('row2col2') == 24) {$elementid = getCustom($params->get('row2col2'), $params->get('r2c2custom'), $row, $params, $admin_params, $template);}
		else {$elementid = getElementid($params->get('row2col2'), $row, $params, $admin_params, $template);}
 		$colspan = $params->get('r2c2span');
 		$rowspan = $params->get('rowspanr2c2');
 		$lastcol = 0;
 		if ($columns == 2 || $colspan > 2) {$lastcol = 1;} 
		if (isset($elementid)) {
 		$listing .= getCell($elementid->id, $elementid->element, $rowcolid, $colspan, $rowspan, $lastcol, $params->get('linkr2c2'), $id3, $tid, $smenu, $tmenu, $entry_access, $allow_entry, $params);
		}
	}
	
	if ($columns > 2   && ( $params->get('r2c1span') < 3 && $params->get('r2c2span') < 2)) 
	{
		 $rowcolid = 'row2col3';
		 if ($params->get('row2col3') < 1) {$params->set('row2col3', 100);}
		 if ($params->get('row2col3') == 24) {$elementid = getCustom($params->get('row2col3'), $params->get('r2c3custom'), $row, $params, $admin_params, $template);}
		 else {$elementid = getElementid($params->get('row2col3'), $row, $params, $admin_params, $template);}
		 //if (!$elementid->id){$element->id = ''; $element->element = '';}
		 $colspan = $params->get('r2c3span');
		 $rowspan = $params->get('rowspanr2c3');
		 $lastcol = 0;
		 if ($columns == 3 || $colspan > 1) {$lastcol = 1;}
		 if (isset($elementid)) {
		 $listing .= getCell($elementid->id, $elementid->element, $rowcolid, $colspan, $rowspan, $lastcol, $params->get('linkr2c3'), $id3, $tid, $smenu, $tmenu, $entry_access, $allow_entry, $params);
		 }
	}
	
	if ($columns > 3  && (  $params->get('r2c1span') < 4 && $params->get('r2c2span') < 3 && $params->get('r2c3span') < 2))
	{
		 $rowcolid = 'row2col4';
		 if ($params->get('row2col4') < 1) {$params->set('row2col4', 100);}
		 if ($params->get('row2col4') == 24) {$elementid = getCustom($params->get('row2col4'), $params->get('r2c4custom'), $row, $params, $admin_params, $template);}
		 else {$elementid = getElementid($params->get('row2col4'), $row, $params, $admin_params, $template);}
		 $colspan = $params->get('r2c4span');
		 $rowspan = $params->get('rowspanr2c4');
		 $lastcol = 0;
		 if ($columns == 4) {$lastcol = 1;}
		 if (isset($elementid)) {
		 $listing .= getCell($elementid->id, $elementid->element, $rowcolid, $colspan, $rowspan, $lastcol, $params->get('linkr2c4'), $id3, $tid, $smenu, $tmenu, $entry_access, $allow_entry, $params);
		 }
	}
	$listing .= '
	</tr>
	'; //This ends the row of the data to be displayed		
//End of row 2

//Beginning of row 3

	$lastrow = 0;
 	if ($rows == 3) {$lastrow = 1;}
	$listing .= '<tr class="'.$oddeven; //This begins the row of the display data
	if ($lastrow == 1) {$listing .= ' lastrow';}
	
	$listing .= '">'; 
	
		 $rowcolid = 'row3col1';
		 if ($params->get('row3col1') < 1) {$params->set('row3col1', 100);}
		 if ($params->get('row3col1') == 24) {$elementid = getCustom($params->get('row3col1'), $params->get('r3c1custom'), $row, $params, $admin_params, $template);}
		else {$elementid = getElementid($params->get('row3col1'), $row, $params, $admin_params, $template);}
 		$colspan = $params->get('r3c1span');
 		$rowspan = $params->get('rowspanr3c1');;
		 $lastcol = 0;
		 if ($columns == 1 || $colspan > 3) {$lastcol = 1;}
		 if (isset($elementid))
		 {$listing .= getCell($elementid->id, $elementid->element, $rowcolid, $colspan, $rowspan, $lastcol, $params->get('linkr3c1'), $id3, $tid, $smenu, $tmenu, $entry_access, $allow_entry, $params);
		 }
	if ($columns > 1 && $params->get('r3c1span') < 2)
	{
 		$rowcolid = 'row3col2';
		if ($params->get('row3col2') < 1) {$params->set('row3col2', 100);}
		if ($params->get('row3col2') == 24) {$elementid = getCustom($params->get('row3col2'), $params->get('r3c2custom'), $row, $params, $admin_params, $template);}
		else {$elementid = getElementid($params->get('row3col2'), $row, $params, $admin_params, $template);}
 		$colspan = $params->get('r3c2span');
 		$rowspan = $params->get('rowspanr3c2');
 		$lastcol = 0;
 		if ($columns == 2 || $colspan > 2) {$lastcol = 1;}
		if (isset($elementid)) {
 		$listing .= getCell($elementid->id, $elementid->element, $rowcolid, $colspan, $rowspan, $lastcol, $params->get('linkr3c2'), $id3, $tid, $smenu, $tmenu, $entry_access, $allow_entry, $params);
		}
	}
	
	if ($columns > 2   && ( $params->get('r3c1span') < 3 && $params->get('r3c2span') < 2) )
	{
		 $rowcolid = 'row3col3';
		 if ($params->get('row3col3') < 1) {$params->set('row3col3', 100);}
		 if ($params->get('row3col3') == 24) {$elementid = getCustom($params->get('row3col3'), $params->get('r3c3custom'), $row, $params, $admin_params, $template);}
		else {$elementid = getElementid($params->get('row3col3'), $row, $params, $admin_params, $template);}
		 $colspan = $params->get('r3c3span');
		 $rowspan = $params->get('rowspanr3c3');
		 $lastcol = 0;
		 if ($columns == 3 || $colspan > 1) {$lastcol = 1;}
		 if (isset($elementid)) {
		 $listing .= getCell($elementid->id, $elementid->element, $rowcolid, $colspan, $rowspan, $lastcol, $params->get('linkr3c3'), $id3, $tid, $smenu, $tmenu, $entry_access, $allow_entry, $params);
		 }
	}
	
	if ($columns > 3 && (  $params->get('r3c1span') < 4 && $params->get('r3c2span') < 3 && $params->get('r3c3span') < 2))
	{
		 $rowcolid = 'row3col4';
		 if ($params->get('row3col4') < 1) {$params->set('row3col4', 100);}
		 if ($params->get('row3col4') == 24) {$elementid = getCustom($params->get('row3col4'), $params->get('r3c4custom'), $row, $params, $admin_params, $template);}
		else {$elementid = getElementid($params->get('row3col4'), $row, $params, $admin_params, $template);}
		 $colspan = $params->get('r3c4span');
		 $rowspan = $params->get('rowspanr3c4');
		 $lastcol = 0;
		 if ($columns == 4) {$lastcol = 1;}
		 if (isset($elementid)) {
		 $listing .= getCell($elementid->id, $elementid->element, $rowcolid, $colspan, $rowspan, $lastcol, $params->get('linkr3c4'), $id3, $tid, $smenu, $tmenu, $entry_access, $allow_entry, $params);
		 }
	}
	$listing .= '
	</tr>
	'; //This ends the row of the data to be displayed		
	//end of row 3
	
	//beginning of row 4
//	$row4colspan = $params->get('r4c1span') + $params->get('r4c2span') + $params->get('r4c3span') + $params->get('r4c4span');
	$lastrow = 0;
 	if ($rows == 4) {$lastrow = 1;}
	$listing .= '
	<tr class="'.$oddeven; //This begins the row of the display data
	if ($lastrow == 1) {$listing .= ' lastrow';}
	
	$listing .= '">
	'; 
	
		 $rowcolid = 'row4col1';
		 if ($params->get('row4col1') < 1) {$params->set('row4col1', 100);}
		 if ($params->get('row4col1') == 24) {$elementid = getCustom($params->get('row4col1'), $params->get('r4c1custom'), $row, $params, $admin_params, $template);}
		else {$elementid = getElementid($params->get('row4col1'), $row, $params, $admin_params, $template);}
 		$colspan = $params->get('r4c1span');
 		$rowspan = $params->get('rowspanr4c1');;
		 $lastcol = 0;
		 if ($columns == 1 || $colspan > 3) {$lastcol = 1;}
		 if (isset($elementid)) {
		 $listing .= getCell($elementid->id, $elementid->element, $rowcolid, $colspan, $rowspan, $lastcol, $params->get('linkr4c1'), $id3, $tid, $smenu, $tmenu, $entry_access, $allow_entry, $params);
		 }
 	
	if ($columns > 1  && $params->get('r4c1span') < 2)
	{
 		$rowcolid = 'row4col2';
		if ($params->get('row4col2') < 1) {$params->set('row4col2', 100);}
		if ($params->get('row4col2') == 24) {$elementid = getCustom($params->get('row4col2'), $params->get('r4c2custom'), $row, $params, $admin_params, $template);}
		else {$elementid = getElementid($params->get('row4col2'), $row, $params, $admin_params, $template);}
 		$colspan = $params->get('r4c2span');
 		$rowspan = $params->get('rowspanr4c2');
 		$lastcol = 0;
 		if ($columns == 2 || $colspan > 2) {$lastcol = 1;}
		if (isset($elementid)) {
 		$listing .= getCell($elementid->id, $elementid->element, $rowcolid, $colspan, $rowspan, $lastcol, $params->get('linkr4c2'), $id3, $tid, $smenu, $tmenu, $entry_access, $allow_entry, $params);
		}
	}
	
	if ($columns > 2   && ( $params->get('r4c1span') < 3 && $params->get('r4c2span') < 2) )
	{
		 $rowcolid = 'row4col3';
		 if ($params->get('row4col3') < 1) {$params->set('row4col3', 100);}
		 if ($params->get('row4col3') == 24) {$elementid = getCustom($params->get('row4col3'), $params->get('r4c3custom'), $row, $params, $tempalte);}
		else {$elementid = getElementid($params->get('row4col3'), $row, $params, $admin_params, $template);}
		 $colspan = $params->get('r4c3span');
		 $rowspan = $params->get('rowspanr4c3');
		 $lastcol = 0;
		 if ($columns == 3 || $colspan > 1) {$lastcol = 1;}
		 if (isset($elementid)) {
		 $listing .= getCell($elementid->id, $elementid->element, $rowcolid, $colspan, $rowspan, $lastcol, $params->get('linkr4c3'), $id3, $tid, $smenu, $tmenu, $entry_access, $allow_entry, $params);
		 }
	}
	
	if ($columns > 3 && ( $params->get('r4c1span') < 4 && $params->get('r4c2span') < 3 && $params->get('r4c3span') < 2))
	{
		 $rowcolid = 'row4col4';
		 if ($params->get('row4col4') < 1) {$params->set('row4col4', 100);}
		 if ($params->get('row4col4') == 24) {$elementid = getCustom($params->get('row4col4'), $params->get('r4c4custom'), $row, $params, $admin_params, $template);}
		else {$elementid = getElementid($params->get('row4col4'), $row, $params, $admin_params, $template);}
		 $colspan = $params->get('r4c4span');
		 $rowspan = $params->get('rowspanr4c4');
		 $lastcol = 0;
		 if ($columns == 4) {$lastcol = 1;}
		 if (isset($elementid)) {
		 $listing .= getCell($elementid->id, $elementid->element, $rowcolid, $colspan, $rowspan, $lastcol, $params->get('linkr4c4'), $id3, $tid, $smenu, $tmenu, $entry_access, $allow_entry, $params);
		 }
	}
	$listing .= '
	</tr>
	'; //This ends the row of the data to be displayed		
	
return $listing;
}

	function getCell($elementid, $element, $rowcolid, $colspan, $rowspan, $lastcol, $islink, $id3, $tid, $smenu, $tmenu, $entry_access, $allow_entry, $params)
		{
$entry_user = 0;			
if (($allow_entry > 0) && ($rowcolid == 'row1col1')){
$user =& JFactory::getUser();
$entry_user = $user->get('gid');
if (!$entry_user) { $entry_user = 0;}
if (!$entry_access) {$entry_access = 23;}
$item = JRequest::getVar('Itemid');
}
		
			$cell = '
						<td class="'.$rowcolid.' '.$elementid;
						if ($lastcol == 1) {$cell .= ' lastcol';}
						$cell .= '" ';
						if ($colspan > 1) {$cell .= 'colspan="'.$colspan.'" ';}
						//if ($rowspan > 1){$cell .='rowspan="'.$rowspan.'"';}
						$cell .= '>';
						if (($rowcolid == 'row1col1') && ($entry_user >= $entry_access) && ($allow_entry > 0)){
							$cell .= '<a href="'.JURI::base().'index.php?option=com_biblestudy&controller=studiesedit&view=studiesedit&task=edit&layout=form&cid[]='.$id3.'&item='.$item.'">'.JText::_(' [Edit] ').'</a>';}
						if ($islink > 0){$cell .= getLink($islink, $id3, $tid, $smenu, $tmenu, $params);}
						$cell .= $element;
						if ($islink > 0){$cell .= '</a>';}
						$cell .='</td>';
			return $cell;
		}
	
	function getLink($islink, $id3, $tid, $smenu, $tmenu, $params)
		{
			$column = '';
			$mime = ' AND #__bsms_mediafiles.mime_type = 1';
			switch ($islink) {
			case 1 :
			$Itemid = JRequest::getVar('Itemid','','get');
			if (!$Itemid)
				{
				$Itemid='1';
			 	$link = JRoute::_('index.php?option=com_biblestudy&view=studydetails' . '&id=' . $id3.'&templatemenuid='.$params->get('detailstemplateid')).'&Itemid='.$Itemid;
			 	}
			 else 
			 	{
			 	$link = JRoute::_('index.php?option=com_biblestudy&view=studydetails' . '&id=' . $id3.'&templatemenuid='.$params->get('detailstemplateid'));
		 		}
			// if ($smenu > 0) {$link .= '&Itemid='.$smenu;}
			 $column = '<a href="'.$link.'">';
			 break;
			case 2 :
			 $filepath = getFilepath($id3, 'study_id',$mime);
			 $link = JRoute::_($filepath);
			 $column .= '<a href="'.$link.'">';

			 break;
			case 3 :
			 $link = JRoute::_('index.php?option=com_biblestudy&view=teacherdisplay' . '&id=' . $tid.'&templatemenuid='.$params->get('teachertemplateid'));
			 if ($tmenu > 0) {$link .= '&Itemid='.$tmenu;}
			 $column .= '<a href="'.$link.'">';
			 break;
		   }
		   return $column;
		}