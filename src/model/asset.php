<?php

namespace Rodasnet\Portfolio\Model;

use Rodasnet\Portfolio\Model\Access as ModelAccess;
use Rodasnet\Category\Model\Category as ModelCategory;
use Rodasnet\Portfolio\Model\Portfolio as ModelPortfolio;
use Rodasnet\Portfolio\Model\Exhibit as ModelExhibit;

class Asset extends ModelAccess
{
	protected static $_properties = [
		'id',
		'name',
		'slug',
		'url',
		'type',
		'category_id',
		'description',
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

	protected static $_table_name = 'assets';

    /**
     * Portfolio BelongsTo Category
     *
     * @var array
     */
    protected static $_belongs_to = [
        'category' => [
            'key_from' => 'category_id',
            'model_to' => 'Category\Model\Category',
            'key_to' => 'id',
            'cascade_save' => false,
            'cascade_delete' => false,
        ],
		'exhibit' => [
            'key_from' => 'parent_id',
            'model_to' => 'Portfolio\Model\Exhibit',
            'key_to' => 'id',
            'cascade_save' => false,
            'cascade_delete' => false,
        ],
    ];

    public static function set_form_fields($form, $instance = null)
    {

        // Call parent for create the fieldset and set default value
        parent::set_form_fields($form, $instance);

        // Set categories
        foreach(ModelCategory::find('all') as $category)
            $form->field('category_id')->set_options($category->id, $category->name);

    }
}
