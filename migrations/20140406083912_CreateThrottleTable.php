<?php
use Phpmig\Migration\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateThrottleTable extends Migration
{

  protected $schema;
  protected $tableName;

  public function init()
  {
    $this->tableName = 'throttle';
    $this->schema = $this->get("schema");
  }

  /**
   * Do the migration
   */
  public function up()
  {
    $this->schema->create($this->tableName, function(BluePrint $table){
      $table->increments('id');
      $table->bigInteger('user_id')->unsigned();
      $table->string('ip_address', 255)->nullable()->default(null);
      $table->integer('attempts')->unsigned()->default(0);
      $table->tinyInteger('suspended')->default(0);
      $table->tinyInteger('banned')->default(0);
      $table->timestamp('last_attempt_at')->nullable()->default(null);
      $table->timestamp('suspended_at')->nullable()->default(null);
      $table->timestamp('banned_at')->nullable()->default(null);
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
