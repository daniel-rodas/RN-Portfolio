<?php
/**
 * Created by PhpStorm.
 * User: Rodas
 * Date: 8/8/2015
 * Time: 12:54 AM
 */

namespace Rodasnet\Portfolio;

use Rodasnet\Portfolio\Model\Portfolio as ModelPortfolio;
use Rodasnet\Portfolio\Model\Access as ModelAccess;

class Access
{
    // TODO https://en.wikipedia.org/wiki/Fluent_interface#PHP
    protected $id;
    protected $name;
    protected $slug;
    protected $url;
    protected $parent_id;
    protected $category_id;
    protected $description;


    /*
     * @var $model will hold the value of Model\Skill
     */
    public $model;

    public function __construct( ModelAccess $model )
    {
        $this->model = $model;
    }

    public function setModel( ModelAccess $modelAccess )
    {
        unset( $this->model );
        $this->model = $modelAccess;
    }

    public function set( $key, $value )
    {
//        if($this->$key)
            $this->$key = $value;
    }

    /**
     * @param $identifier
     * @param string $field
     * @return \Orm\Model
     */
    public function get($identifier , $field = 'id' )
    {
            return $this->model->query()->where( $field , $identifier )->get();
    }

    /**
     * @param $identifier
     * @param string $field
     * @return \Orm\Model
     */
    public function getOne($identifier , $field = 'id' )
    {
        return $this->model->query()->where( $field , $identifier )->get_one();
    }

    public function getChildren( $id )
    {
        return $this->model->query()->where( 'parent_id' , $id )->get();
    }

    public function getParent( $id )
    {
        $asset = $this->model->query()->where( 'id' , $id )->get_one();

        return $this->model = $this->model->query()
            ->where( 'id' , $asset->parent_id )->get();
    }

    public function setParent( $id )
    {
        $this->model->parent_id = $id;
        return $this->model->save();
    }

    public function getAll()
    {
        return $this->model->query()->get();
    }

    public function getTopLevel()
    {
        return $this->model->query()->where( 'parent_id' , null )->get();
    }

    public function getAllBriefList()
    {
        return $this->model = $this->model->query()
            ->select( 'id', 'name', 'slug', 'url', 'description', 'parent_id')->get();
    }

    public function getAllRelated( $related )
    {
        return  $this->model->query()->related($related)->get();
    }

    public function create( $name , $slug , $url , $description = null, $parent_id = null , $cat_id = null )
    {
        $val = $this->model->validateCreate('new_exhibition');
        if( $val->run([ 'name' => $name , 'slug' => $slug ] , true ) )
        {
            $this->model->name = $name;
            $this->model->slug = $slug;
            $this->model->url = $url;
            $this->model->parent_id = empty($parent_id) ? null : (integer) $parent_id;
            $this->model->category_id = empty($cat_id) ? null : (integer) $cat_id;
            $this->model->description = $description;
        }
        else
        {
            var_dump( $val->error() );
            die('Portfolio Item Not Validated');
        }

        try {

            $this->model->save();

            return $this->model;

        } catch (Orm\ValidationFailed $e) {
            // returns the individual ValidationError objects
            return $e->get_fieldset()->validation()->error();
        }
    }
}
