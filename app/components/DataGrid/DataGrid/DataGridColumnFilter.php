<?php

require_once dirname(__FILE__) . '/IDataGridColumnFilter.php';



/**
 * Base class that implements the basic common functionality to data grid column's filters.
 *
 * @author     Roman Sklenář
 * @copyright  Copyright (c) 2009 Roman Sklenář (http://romansklenar.cz)
 * @license    New BSD License
 * @example    http://nettephp.com/extras/datagrid
 * @package    Nette\Extras\DataGrid
 * @version    $Id: DataGridColumnFilter.php 42 2009-07-27 13:55:50Z mail@romansklenar.cz $
 */
abstract class DataGridColumnFilter extends Component implements IDataGridColumnFilter
{
	/** @var FormControl  form element */
	protected $element;

	/** @var string  value of filter (if was filtered) */
	protected $value;


	public function __construct()
	{
		parent::__construct();
	}



	/********************* interface \IDataGridColumnFilter *********************/



	/**
	 * Returns filter's form element.
	 * @return FormControl
	 */
	public function getFormControl()
	{
	}


	/**
	 * Gets filter's value, if was filtered.
	 * @return string
	 */
	public function getValue()
	{
		$dataGrid = $this->lookup('DataGrid', TRUE);

		// set value if was data grid filtered yet
		parse_str($dataGrid->filters, $list);
		foreach ($list as $key => $value) {
			if ($key == $this->getName()) {
				$this->setValue($value);
				break;
			}
		}
		return $this->value;
	}


	/**
	 * Sets filter's value.
	 * @param string
	 * @return void
	 */
	public function setValue($value)
	{
		$control = $this->getFormControl();
		$control->value = $this->value = $value;
	}
}