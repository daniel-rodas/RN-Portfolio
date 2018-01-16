<?php
/**
 * Created by PhpStorm.
 * User: Rodas
 * Date: 4/9/2016
 * Time: 12:36 AM
 */

namespace Rodasnet\Portfolio\Model;

use Orm\Model_Soft;
use Rodasnet\Portfolio\Validation;

class Access extends Model_Soft
{
    public static function _init()
    {
        // this is called upon loading the class
        \Package::load('portfolio');
        \Package::load('category');
    }

    public static function validateCreate($factory)
    {
        $val = Validation::forge($factory);
        $val->add_field('name', 'Name', 'required');
        $val->add_field('slug', 'Slug', 'required');
        $val->add_field('parent_id', 'Parent ID', 'numeric_min[1]');
        $val->add_field('category_id', 'Category ID', 'numeric_min[1]');
        return $val;
    }

    function get_class_name($classname)
    {
        if ($pos = strrpos($classname, '\\')) return substr($classname, $pos + 1);
        return $pos;
    }

    /**
     * @return \Orm\Model
     */
    public function getOneRandom()
    {
        $count = $this->query()->count();
        return $this->query()->where( 'id' , rand(1,$count) )->get_one();
    }

}