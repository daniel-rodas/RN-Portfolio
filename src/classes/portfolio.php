<?php

namespace Rodasnet\Portfolio;

use Rodasnet\Portfolio\Model\Portfolio as ModelPortfolio;
use Rodasnet\Portfolio\Model\Exhibit as ModelExhibit;
use Rodasnet\Portfolio\Model\Asset as ModelAsset;

class PortfolioException extends \FuelException {}

class Portfolio
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
	protected $exhibition;
	protected $exhibit;
	protected $asset;

    protected $exhibitionArray = [];
    protected $exhibitArray = [];
    protected $assetArray = [];

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
		$config = \Arr::merge(static::$_defaults, \Config::get('portfolio', array()), $config);

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
		$this->access = new Access(  ModelPortfolio::forge() );
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

	public function newAsset( $name , $slug , $url = null , $description = null , $parent_id = null , $cat_id = null )
	{
        $this->access->setModel( ModelAsset::forge() );

        return $this->access->create( $name , $slug , $url  , $description  , $parent_id  , $cat_id );
	}

	public function newExhibit( $name , $slug , $url = null , $description = null , $parent_id = null , $cat_id = null )
	{
        $this->access->setModel( ModelExhibit::forge() );
        return $this->access->create( $name , $slug , $url  , $description  , $parent_id  , $cat_id );
	}

	public function newExhibition( $name , $slug , $url = null , $description = null , $parent_id = null , $cat_id = null )
	{
        $this->access->setModel( ModelPortfolio::forge() );
        return $this->access->create( $name , $slug , $url , $description , $parent_id , $cat_id );
	}

	public function getAssets()
	{
        $this->access->setModel( ModelAsset::forge() );

			return $this->access->getAll();
	}
	public function getExhibitions()
	{
        $this->access->setModel( ModelPortfolio::forge() );
		return $this->access->getAll();
	}

	public function exhibitionFeature()
	{
//	    $asset = $this->getOneRandomAsset();
        $this->access->setModel( ModelExhibit::forge() );
		$this->exhibit = $this->access->getOne( 'zoe-sense' , 'slug' );
        $this->access->setModel( ModelAsset::forge() );
		return $this->asset = $this->access->getOne( $this->exhibit->id , 'parent_id' );
	}

    public function setFeatured( $slug )
    {
//        $this->access->setModel( ModelAsset::forge() );

//            return $this->access->create( $name , $slug , $url  , $description  , $parent_id  , $cat_id );

    }

	public function getExhibits()
	{
        $this->access->setModel( ModelExhibit::forge() );

		return $this->access->getAll();
	}

    /**
     * @param $slug
     * @return ModelExhibit;
     */

    public function exhibit( $slug )
	{
        $this->access->setModel( ModelExhibit::forge() );
		return $this->access->getOne( $slug , 'slug' );
	}

    /**
     * @param $asset
     * @return ModelExhibit;
     */

    public function exhibitByAsset( $asset )
	{
	    $this->access->setModel( ModelExhibit::forge() );
		return $this->access->getOne( $asset->parent_id , 'id' );

/*
        return $exhibit->query()->where( 'parent_id' , $exhibit->parent_id )
            ->and_where_open()
            ->where('id', '!=', $exhibit->id )
            ->and_where_close()
            ->get();
        */
	}

    /**
     * @param $slug
     * @return ModelAsset;
     */

    public function asset( $slug )
	{
        $this->access->setModel( ModelAsset::forge() );
		return $this->access->getOne( $slug , 'slug' );
	}

	public function getAll( $topLevel = false )
	{
        if( $topLevel )
		{
			return $this->access->getTopLevel();
		}

		return $this->access->getAllBriefList();
	}

	public function getByUrl( $url , $children = false )
	{
        if( $children )
		{
			$skill = $this->access->getOne( $url , 'url' );
			// Run query and hope for the best.
			return $this->access->getChildren( $skill->id );
		}
		// Run query and hope for the best.
		return $this->access->getOne( $url , 'url' );
	}

	public function getRelatedExhibits( $exhibit )
    {
        return $exhibit->query()->where( 'parent_id' , $exhibit->parent_id )
            ->and_where_open()
                ->where('id', '!=', $exhibit->id )
            ->and_where_close()
            ->get();
    }

    public function getOneRandomAsset()
    {
        $this->access->setModel( ModelAsset::forge() );
        return $this->access->model->getOneRandom();
    }
}
