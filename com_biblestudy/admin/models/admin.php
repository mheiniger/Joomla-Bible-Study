<?php

/**
 * @version     $Id: admin.php 1466 2011-01-31 23:13:03Z bcordis $
 * @package     com_biblestudy
 * @license     GNU/GPL
 */
//No Direct Access
defined('_JEXEC') or die();

    jimport('joomla.application.component.modeladmin');

    abstract class adminClass extends JModelAdmin {
        
    }

class biblestudyModeladmin extends adminClass {

//class biblestudyModeladmin extends JModelAdmin
//{
    /**
     * Constructor that retrieves the ID from the request
     *
     * @access	public
     * @return	void
     */
    var $_text_prefix = 'COM_BIBLESTUDY';
    var $_admin;

    function __construct() {
        parent::__construct();

        $array = JRequest::getVar('cid', 0, '', 'array');
        $this->setId((int) $array[0]);
    }

    function setId($id) {
        // Set id and wipe data
        $this->_id = $id;
        $this->_data = null;
    }

    function &getData() {
        // Load the data
        $query = ' SELECT * FROM #__bsms_admin ' .
                '  WHERE id = 1';
        $this->_db->setQuery($query);
        $this->_data = $this->_db->loadObject();
        return $this->_data;
    }

    /**
     * Method to store a record
     *
     * @access	public
     * @return	boolean	True on success
     */
 
    function store($updateNulls = 'false') {
        $row = & $this->getTable();


        $data = JRequest::get('post');
        //dump ($data, 'post: ');
        // Bind the form fields to the hello table
        if (!$row->bind($data)) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        // Make sure the record is valid
        if (!$row->check()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        // Store the web link table to the database
        if (!$row->store()) {
            $this->setError($this->_db->getErrorMsg());
//			$this->setError( $row->getErrorMsg() );
            return false;
        }

        return true;
    }


/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 * @since	1.6
	 */
	public function getTable($type = 'admin', $prefix = 'Table', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
    /**
     * Gets the form from the XML file.
     *
     * @param <Array> $data
     * @param <Boolean> $loadData
     * @return <JForm> Form Object
     */
    public function getForm($data = array(), $loadData = true) {
        // Get the form.
        $form = $this->loadForm('com_biblestudy.admin', 'admin', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form)) {
            return false;
        }

        return $form;
    }

    public function getItem($pk = null) {
        return parent::getItem(1);
    }

public function getItem2($pk = 1)
	{
		if ($item = parent::getItem($pk)) {
			// Convert the params field to an array.
			$registry = new JRegistry;
			$registry->loadJSON($item->params);
			$item->params = $registry->toArray();
		}

		return $item;
	}
    protected function loadFormData() {
        $data = JFactory::getApplication()->getUserState('com_biblestudy.edit.admin.data', array());
        if (empty($data))
            $data = $this->getItem();

        return $data;
    }

    /**
     * Method to get a single record.
     *
     * @param	integer	The id of the primary key.
     *
     * @return	mixed	Object on success, false on failure.
     * @since	1.6
     */
    /* 	public function getItem($pk = null)
      {
      if ($item = parent::getItem($pk)) {
      // Convert the params field to an array.
      $registry = new JRegistry;
      $registry->loadJSON($item->params);
      $item->params = $registry->toArray();
      }

      return $item;
      }
     */
public function save($data)
	{
	//	$table	= JTable::getInstance('extension');
        $table = $this->getTable();
		// Save the rules.
		if (isset($data['params']) && isset($data['params']['rules'])) {
			jimport('joomla.access.rules');
			$rules	= new JRules($data['params']['rules']);
			$asset	= JTable::getInstance('asset');

			if (!$asset->loadByName('com_biblestudy')) {
				$root	= JTable::getInstance('asset');
				$root->loadByName('root.1');
				$asset->name = 'com_biblestudy';
				$asset->title = 'General Rules for Joomla Bible Study';
				$asset->setLocation($root->id,'last-child');
			}
			$asset->rules = (string) $rules;

			if (!$asset->check() || !$asset->store()) {
				$this->setError($asset->getError());
				return false;
			}

			// We don't need this anymore
			unset($data['option']);
			unset($data['params']['rules']);
		}

		// Load the previous Data
		if (!$table->load($data['id'])) {
			$this->setError($table->getError());
			return false;
		}

		unset($data['id']);

		// Bind the data.
		if (!$table->bind($data)) {
			$this->setError($table->getError());
			return false;
		}

		// Check the data.
		if (!$table->check()) {
			$this->setError($table->getError());
			return false;
		}

		// Store the data.
		if (!$table->store()) {
			$this->setError($table->getError());
			return false;
		}

		// Clean the cache.
		$cache = JFactory::getCache('_system');
		$cache->clean();

		return true;
	}
}

?>
