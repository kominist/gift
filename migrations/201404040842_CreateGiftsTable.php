<?php
use Phpmig\Migration\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGiftsTable extends Migration
{

    protected $schema;
    protected $tableName;

    public function init()
    {
      $this->tableName = 'gifts';
      $this->schema = $this->get('schema');
    }

    public function up()
    {
      $this->schema->create($this->tableName, function(Blueprint $table){
        $table->increments('id');
        $table->bigInteger('id_giver')->unsigned();
        $table->bigInteger('id_getter')->unsigned();
        $table->string('status');
        $table->bigInteger('id_meeting')->unsigned();
        $table->timestamps();
      });
    }
    public function down()
    {
      $this->schema->drop($this->tableName);
    }
}
