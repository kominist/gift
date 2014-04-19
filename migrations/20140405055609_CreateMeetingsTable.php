<?php
use Phpmig\Migration\Migration;
use Illuminate\Database\Schema\Blueprint;
class CreateMeetingsTable extends Migration
{

  protected $schema;
  protected $tableName;

  public function init()
  {
    $this->tableName = "meetings";
    $this->schema = $this->get("schema");
  }

  /**
   * Do the migration
   */
  public function up()
  {
    $this->schema->create($this->tableName, function(Blueprint $table){
      $table->increments('id');
      $table->timestamp('date');
      $table->integer('location_x')->unsigned();
      $table->integer('location_y')->unsigned();
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
