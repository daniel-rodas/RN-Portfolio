<?php

namespace Rodasnet\Portfolio;

use Rodasnet\Portfolio\Access\Category as Access;

class PortfolioCategoryException extends \FuelException {}

class Category
{
	/**
	 * Default config
	 * @var array
	 */
	protected static $_defaults = array();

	/**
	* Driver config
	* @var array
	*/
	protected $config = array();

	/*
	 * Perform CRUD operations.
	 * @var access
	 */

	protected $access;

	/**
	 * Init
	 */
	public static function _init()
	{
		\Config::load('portfolio', true);
	}

	/**
	 * Skills driver forge.
	 *
	 * @param	array			$config		Config array
	 * @return  Skills
	 */
	public static function forge($config = array())
	{
		$config = \Arr::merge(static::$_defaults, \Config::get('skills', array()), $config);

		$class = new static($config);

		return $class;
	}

	/**
	* Driver constructor
	*
	* @param array $config driver config
	*/
	public function __construct(array $config = array())
	{
		$this->config = $config;
		$this->access = new Access();
	}

	/**
	* Get a config setting.
	*
	* @param string $key the config key
	* @param mixed  $default the default value
	* @return mixed the config setting value
	*/
	public function get_config($key, $default = null)
	{
		return \Arr::get($this->config, $key, $default);
	}

	/**
	* Set a config setting.
	*
	* @param string $key the config key
	* @param mixed $value the new config value
	* @return object $this for chaining
	*/
	public function set_config($key, $value)
	{
		\Arr::set($this->config, $key, $value);

		return $this;
	}

	public function create( $name , $slug, $url = null, $description = null, $parent_id = null , $cat_id = null )
	{
		try {
			// Run query and hope for the best.
			return $this->access->create( $name , $slug , $url , $description , $parent_id );
		} catch ( \PhpErrorException $e) {
			// returns the individual ValidationError objects
			return $e->getMessage();
		}
	}

	public function getAll( $topLevel = false )
	{
		try {
			if( $topLevel )
			{
				return $this->access->getTopLevel();
			}
			// Run query and hope for the best.
			return $this->access->getAllBriefList();
		} catch ( \PhpErrorException $e) {
			// returns the individual ValidationError objects
			return $e->getMessage();
		}
	}

	public function getByUrl( $url , $children = false )
	{
		try {
			if( $children )
			{
				$skill = $this->access->get( $url , 'url' );
				// Run query and hope for the best.
				return $this->access->getChildren( $skill->id );
			}
			// Run query and hope for the best.
			return $this->access->get( $url , 'url' );
		} catch ( \PhpErrorException $e) {
			// returns the individual ValidationError objects
			return $e->getMessage();
		}
	}
}
