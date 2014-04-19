<?php

use Phpmig\Migration\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGroupsTable extends Migration
{
  protected $schema;
  protected $tableName;

  public function init()
  {
    $this->tableName = 'groups';
    $this->schema = $this->get('schema');
  }

  /**
   * Do the migration
   */
  public function up()
  {
    $this->schema->create($this->tableName, function(Blueprint $table) {
      $table->increments('id');
      $table->string('name', 100)->unique();
      $table->string('permissions', 100);
      $table->timestamps();
    });
  }

  /**
   * Undo the migration
   */
  public function down()
  {
    $this->schema->drop($this->tableName);
  }
}
