<?php

/**
 * Base abstract model class.
 *
 * @author     Roman Sklenář
 * @package    DataGrid\Example
 */
abstract class BaseModel extends Object implements IModel
{
	/** @var DibiConnection */
	protected $connection;

	/** @var string  table name */
	protected $table;

	/** @var string|array  primary key column name */
	protected $primary = 'id';

	/** @var array  of function(IModel $sender) */
	public $onStartup;

	/** @var array  of function(IModel $sender) */
	public $onShutdown;


	public function __construct($table = NULL, $primary = NULL)
	{
		$this->onStartup($this);
		$this->connection = dibi::getConnection();

		if ($table) $this->setTable($table);
		if ($primary) $this->setPrimary($primary);
	}


	public function __destruct()
	{
		$this->onShutdown($this);
	}


	public static function initialize()
	{
		$conf = Environment::getConfig('database');
		$connection = dibi::connect($conf[$conf->engine]);

		if ($conf->engine == 'sqlite') {
			$connection->getDriver()->registerFunction('regexp', 'Sqlite::regexp', 2);
		}

		if ($conf->profiler) {
			$profiler = is_numeric($conf->profiler) || is_bool($conf->profiler) ?
				new DibiProfiler : new $conf->profiler;
			$profiler->setFile(Environment::expand('%logDir%') . '/sql.log');
			$connection->setProfiler($profiler);
		}
	}


	public static function disconnect()
	{
		dibi::getConnection()->disconnect();
	}



	/***** Public getters and setters *****/



	public function getTable()
	{
		return $this->table;
	}


	public function setTable($table)
	{
		$this->table = $table;
	}


	public function getPrimary()
	{
		return $this->primary;
	}


	public function setPrimary($primary)
	{
		$this->primary = $primary;
	}



	/***** Model's API *****/



	public function findAll()
	{
		throw new NotImplementedExceptiont('Method is not implemented.');
	}


	public function find($id)
	{
		throw new NotImplementedExceptiont('Method is not implemented.');
	}


	public function update($id, array $data)
	{
		throw new NotImplementedExceptiont('Method update is not implemented.');
	}


	public function insert(array $data)
	{
		throw new NotImplementedExceptiont('Method insert is not implemented.');
	}


	public function delete($id)
	{
		throw new NotImplementedExceptiont('Method delete is not implemented.');
	}

}