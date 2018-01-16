<?php

namespace Rodasnet\Portfolio\Category\Model;

use Rodasnet\Portfolio\Category\Validation;
use Rodasnet\Portfolio\Model\Portfolio as ModelPortfolio;

class Category extends \Orm\Model_Soft
{
	public static function _init()
	{

	}
	protected static $_properties = [
		'id',
		'name',
		'slug',
		'url',
		'description',
		'item_count',
		'parent_id',
		'deleted_at',
		'created_at',
		'updated_at',
    ];

	protected static $_observers = [
		'Orm\Observer_CreatedAt' => [
			'events' => ['before_insert'],
			'mysql_timestamp' => false,
        ],
		'Orm\Observer_UpdatedAt' => [
			'events' => ['before_update'],
			'mysql_timestamp' => false,
        ],
    ];

	protected static $_soft_delete = [
		'mysql_timestamp' => false,
    ];

	protected static $_table_name = 'category';

	/**
	 * Category HasMany Portfolios
	 *
	 * @var array
	 */
	protected static $_has_many = [
		'posts' => [
			'key_from' => 'id',
			'model_to' => 'Portfolio\Model\Portfolio',
			'key_to' => 'category_id',
			'cascade_save' => false,
			'cascade_delete' => false,  // We delete all post from the category deleted
        ],
    ];

	public static function set_form_fields($form, $instance = null)
	{
		// Call parent for create the fieldset and set default value
		parent::set_form_fields($form, $instance);

		// Add null value for parent categories.
		$form->field('parent_id')->set_options('null','None');

		$categories = ModelPortfolio::find('all', [
			'where' => [
				['parent_id', null]
            ]
        ]);

		// Set categories
		foreach($categories as $category)
			$form->field('parent_id')->set_options($category->id, $category->name);
	}

	public static function validate($factory)
	{
		$val = Validation::forge($factory);
		$val->add_field('name', 'Name', 'required');
		return $val;
	}
}
