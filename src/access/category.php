<?php
/**
 * Created by PhpStorm.
 * User: Rodas
 * Date: 8/8/2015
 * Time: 12:54 AM
 */

namespace Rodasnet\Portfolio\Access;

use Rodasnet\Portfolio\Model\Category as ModelCategory;

class Category
{
    // TODO https://en.wikipedia.org/wiki/Fluent_interface#PHP
    protected $id;
    protected $name;
    protected $description;
    protected $slug;
    protected $url;
    protected $parent_id;
    
    /*
     * @var $model will hold the value of Model\Skill
     */
    public $model;

    public function __construct()
    {
        $this->model = ModelCategory::forge();
    }

    public function set( $key, $value )
    {
//        if($this->$key)
            $this->$key = $value;
    }

    public function get( $identifier , $field = 'id' )
    {
            return $this->model = $this->model->query()->where( $field , $identifier )->get_one();
    }

    public function getChildren( $id )
    {
        return $this->model = $this->model->query()
            ->where( 'parent_id' , $id )->get();
    }

    public function getParent( $id )
    {
        $asset = $this->model->query()
            ->where( 'id' , $id )->get_one();

        return $this->model = $this->model->query()
            ->where( 'id' , $asset->parent_id )->get();
    }

    public function getAll()
    {
            return $this->model = $this->model->query()->get();
    }

    public function getTopLevel()
    {
            return $this->model->query()
                ->where( 'parent_id' , null )->get();
    }

    public function getAllBriefList()
    {
            return $this->model = $this->model->query()
                ->select( 'id', 'name', 'slug', 'url', 'description', 'parent_id')->get();
    }

    public function create( $name , $slug, $url , $description = null , $parent_id = null )
    {
        try {
            // Run query and hope for the best.
            $this->model->name = $name;
            $this->model->slug = $slug;
            $this->model->url = $url;
            $this->model->description = $description;
            $this->model->parent_id = $parent_id;

            $this->model->save();

            return $this->model;

        } catch (Orm\ValidationFailed $e) {
            // returns the individual ValidationError objects
            return $e->get_fieldset()->validation()->error();
        }
    }
}