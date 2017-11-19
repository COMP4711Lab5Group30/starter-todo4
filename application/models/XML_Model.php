<?php

/**
 * CSV-persisted collection.
 * 
 * @author		JLP
 * @copyright           Copyright (c) 2010-2017, James L. Parry
 * ------------------------------------------------------------------------
 */
class XML_Model extends Memory_Model
{
//---------------------------------------------------------------------------
//  Housekeeping methods
//---------------------------------------------------------------------------

	/**
	 * Constructor.
	 * @param string $origin Filename of the CSV file
	 * @param string $keyfield  Name of the primary key field
	 * @param string $entity	Entity name meaningful to the persistence
	 */
	function __construct($origin = 'tasks.xml', $keyfield = 'id', $entity = null)
	{
		parent::__construct();

		// guess at persistent name if not specified
		if ($origin == null)
			$this->_origin = get_class($this);
		else
			$this->_origin = $origin;

		// remember the other constructor fields
		$this->_keyfield = $keyfield;
		$this->_entity = $entity;

		// start with an empty collection
		$this->_data = array(); // an array of objects
		$this->fields = array(); // an array of strings
		// and populate the collection
		$this->load();
	}

	/**
	 * Load the collection state appropriately, depending on persistence choice.
	 * OVER-RIDE THIS METHOD in persistence choice implementations
	 */
	protected function load()
	{
		//---------------------
		if (($this->xml = simplexml_load_file('../data/' . $this->_origin)) !== FALSE) {
			$first = TRUE;
			// var_dump($this->xml->task);
			// for($i = 0; $i < count($this->xml); $i++) {
			foreach($this->xml as $child){
				// var_dump((array)$child);
				// echo "<br />";
				// echo "<br />";
				// echo "<br />";
				if ($first) {
					$count = 0;
					foreach ($child as $property)
						var_dump($property);
						// $this->_fields[$count++] = $property->{0};
					
					$first = !$first;
					// var_dump($this->_fields);
				} else {
					// $record = new stdClass();
					// for ($i = 0; $i < count($this->_fields); $i++)
					// 	$record->{$this->_fields[$i]} = $child[$i];
					// $key = $record->{$this->_keyfield};
					// $this->_data[$key] = $record;
				}
			}
		}
		// --------------------
		// rebuild the keys table
		// $this->reindex();
	}

	/**
	 * Store the collection state appropriately, depending on persistence choice.
	 * OVER-RIDE THIS METHOD in persistence choice implementations
	 */
	protected function store()
	{
		// rebuild the keys table
		$this->reindex();
		//---------------------
		if (($handle = fopen($this->_origin, "w")) !== FALSE)
		{
			fputcsv($handle, $this->_fields);
			foreach ($this->_data as $key => $record)
				fputcsv($handle, array_values((array) $record));
			fclose($handle);
		}
		// --------------------
	}

}
