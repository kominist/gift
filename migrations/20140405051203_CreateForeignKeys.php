<?php

use Phpmig\Migration\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateForeignKeys extends Migration
{

  protected $schema;
  protected $tableName;

  public function init()
  {
    $this->schema = $this->get('schema');
  }

  /**
   * Do the migration
   */
  public function up()
  {
    $this->schema->table('gifts', function (Blueprint $table) {
      $table->foreign('id_giver')->references('id')->on('users')
        ->onDelete('restrict')
        ->onUpdate('restrict');
      $table->foreign('id_getter')->references('id')->on('users')
        ->onDelete('restrict')
        ->onUpdate('restrict');
      $table->foreign('id_meeting')->references('id')->on('meetings')
        ->onDelete('restrict')
        ->onUpdate('restrict');
    });

    $this->schema->table('throttle', function (Blueprint $table) {
      $table->foreign('user_id')->references('id')->on('users')
        ->onDelete('restrict')
        ->onUpdate('restrict');
    });

    $this->schema->table('users_groups', function (Blueprint $table) {
      $table->foreign('user_id')->references('id')->on('users')
        ->onDelete('restrict')
        ->onUpdate('restrict');
      $table->foreign('group_id')->references('id')->on('groups')
        ->onDelete('restrict')
        ->onUpdate('restrict');
    });
  }

  /**
   * Undo the migration
   */
  public function down()
  {
    $this->schema->table('gifts', function(Blueprint $table) {
      $table->dropForeign('users_id_giver_foreign');
      $table->dropForeign('users_id_getter_foreign');
      $table->dropForeign('meetings_id_meeting_foreign');
      $table->dropForeign('users_user_id_foreign');
    });
  }
}
