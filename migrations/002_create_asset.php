<?php

namespace Fuel\Migrations;

class Create_asset
{
	public function up()
	{
		\DBUtil::create_table('assets', [
			'id' => ['constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true],
			'name' => ['type' => 'text'],
			'slug' => ['type' => 'text', 'null' => true],
			'url' => ['type' => 'text', 'null' => true],
			'type' => ['type' => 'text', 'null' => true],
			'category_id' => ['constraint' => 11, 'type' => 'int', 'null' => true],
			'description' => ['type' => 'text', 'null' => true],
			'parent_id' => ['constraint' => 11, 'type' => 'int', 'null' => true],
			'deleted_at' => ['constraint' => 11, 'type' => 'int', 'null' => true],
			'created_at' => ['constraint' => 11, 'type' => 'int', 'null' => true],
			'updated_at' => ['constraint' => 11, 'type' => 'int', 'null' => true],

		], ['id']);
	}

	public function down()
	{
		\DBUtil::drop_table('assets');
	}
}