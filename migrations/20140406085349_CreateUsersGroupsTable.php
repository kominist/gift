<?php
use Phpmig\Migration\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersGroupsTable extends Migration
{
  protected $schema;
  protected $tableName;

  public function init()
  {
    $this->tableName = 'users_groups';
    $this->schema = $this->get('schema');
  }

  /**
   * Do the migration
   */
  public function up()
  {
    $this->schema->create($this->tableName, function(Blueprint $table) {
      $table->increments('id');
      $table->integer('user_id')->unsigned();
      $table->integer('group_id')->unsigned();
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
