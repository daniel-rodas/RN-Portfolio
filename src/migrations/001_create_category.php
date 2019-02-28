<?php

namespace Fuel\Migrations;

class Create_category
{
	public function up()
	{
		\DBUtil::create_table('category', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'name' => array('type' => 'text'),
			'slug' => array('type' => 'text', 'null' => true),
			'url' => array('type' => 'text', 'null' => true),
			'description' => array('type' => 'text', 'null' => true),
			'item_count' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'parent_id' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'deleted_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('category');
	}
}