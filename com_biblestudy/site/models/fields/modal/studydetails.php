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

jimport('joomla.form.formfield');

/**
 * Supports a modal study picker.
 *
 * @package  BibleStudy.Site
 * @since    7.0.0
 */
class JFormFieldModal_studydetails extends JFormField
{

	/**
	 * The form field type.
	 *
	 * @var        string
	 * @since    1.6
	 */
	protected $type = 'Modal_studydetails';

	/**
	 * Method to get the field input markup.
	 *
	 * @return    string    The field input markup.
	 *
	 * @since    1.6
	 */
	protected function getInput()
	{
		// Load the javascript and css
		JHtml::_('behavior.framework');
		JHTML::_('script', 'system/modal.js', false, true);
		JHTML::_('stylesheet', 'system/modal.css', array(), true);

		// Build the script.
		$script   = array();
		$script[] = '	function jSelectChart_' . $this->id . '(id, name, object) {';
		$script[] = '		document.id("' . $this->id . '_id").value = id;';
		$script[] = '		document.id("' . $this->id . '_name").value = name;';
		$script[] = '		SqueezeBox.close();';
		$script[] = '	}';

		// Add the script to the document head.
		JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));

		// Build the script.
		$script   = array();
		$script[] = '	window.addEvent("domready", function() {';
		$script[] = '		var div = new Element("div").setStyle("display", "none").injectBefore(document.id("menu-types"));';
		$script[] = '		document.id("menu-types").injectInside(div);';
		$script[] = '		SqueezeBox.initialize();';
		$script[] = '		SqueezeBox.assign($$("input.modal"), {parse:"rel"});';
		$script[] = '	});';

		// Add the script to the document head.
		JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));


		// Get the title of the linked chart
		$db    = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('studytitle AS name')->from('#__bsms_studies')->where('id = ' . (int) $this->value);
		$db->setQuery($query);
		$title = $db->loadResult();

		if (empty($title))
		{
			$title = JText::_('JBS_CMN_SELECT_STUDY');
		}

		$link = 'index.php?option=com_biblestudy&amp;view=sermon&amp;layout=modal&amp;tmpl=component&amp;function=jSelectChart_' . $this->id;

		JHTML::_('behavior.modal', 'a.modal');
		$html = "\n" . '<div class="fltlft"><input type="text" id="' . $this->id . '_name" value="' .
			htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '" disabled="disabled" /></div>';
		$html .= '<div class="button2-left"><div class="blank"><a class="modal" title="' . JText::_('JBS_CMN_SELECT_STUDY') .
			'"  href="' . $link . '" rel="{handler: \'iframe\', size: {x: 800, y: 450}}">' . JText::_('JBS_CMN_SELECT_STUDY') . '</a></div></div>' . "\n";

		// The active study id field.
		if (0 == (int) $this->value)
		{
			$value = '';
		}
		else
		{
			$value = (int) $this->value;
		}

		// This class='required' for client side validation
		$class = '';

		if ($this->required)
		{
			$class = ' class="required modal-value"';
		}

		$html .= '<input type="hidden" id="' . $this->id . '_id"' . $class . ' name="' . $this->name . '" value="' . $value . '" />';

		return $html;
	}

}
