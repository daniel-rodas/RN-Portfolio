<?php

namespace Portfolio\Model;

use Portfolio\Model\Portfolio as ModelPortfolio;
use Portfolio\Model\Asset as ModelAsset;
use Portfolio\Model\Access as ModelAccess;

class Exhibit extends ModelAccess
{
	protected static $_properties = [
		'id',
		'name',
		'slug',
		'url',
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

	protected static $_table_name = 'exhibits';

    /**
     * Portfolio BelongsTo Category
     *
     * @var array
     */
    protected static $_belongs_to = [
        'portfolio' => [
            'key_from' => 'parent_id',
            'model_to' => 'Portfolio\Model\Portfolio',
            'key_to' => 'id',
            'cascade_save' => false,
            'cascade_delete' => false,
        ],
    ];

	protected static $_has_many = [
		'assets' => [
			'key_from' => 'id',
			'model_to' => 'Portfolio\Model\Asset',
			'key_to' => 'parent_id',
			'cascade_save' => true,
			'cascade_delete' => false,
		]
	];

    public static function set_form_fields($form, $instance = null)
    {
        // Call parent for create the fieldset and set default value
        parent::set_form_fields($form, $instance);

        // Set categories
        foreach(ModelPortfolio::find('all') as $exhibition)
            $form->field('parent_id')->set_options($exhibition->id, $exhibition->name);

    }

//	public function getExhibitionName( ModelAccess $model )
//    {
//
//        // Find Parent Exhibition
//        $model =  ModelPortfolio::query()->select('name')->where('id', $model->parent_id)->get_one();
//        return $model->name;
//    }

	public function getExhibition( ModelAccess $model )
    {
        // Find Parent Exhibition
        return  ModelPortfolio::query()->where('id', $model->parent_id)->get_one();;
    }
}
