<?php

use Phpmig\Migration\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration
{
    protected $schema;
    protected $tableName;

    public function init()
    {
      $this->tableName = 'users';
      $this->schema = $this->get('schema');
    }

    /**
     * Do the migration
     */
    public function up()
    {
      $this->schema->create($this->tableName, function(Blueprint $table) {
        $table->increments('id');
        $table->string('email')->unique('users_email_unique');
        $table->string('password');
        $table->string('permissions');
        $table->tinyInteger('activated')->default(0);
        $table->string('activation_code')->index("users_activation_code_index")->nullable()->default(null);
        $table->string('activated_at')->nullable()->default(null);
        $table->string('last_login')->nullable()->default(null);
        $table->string('persist_code')->nullable()->default(null);
        $table->string('reset_password_code')->index("users_reset_passwords_index")->nullable()->default(null);
        $table->string('first_name')->nullable()->default(null);
        $table->string('last_name')->nullable()->default(null);
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
