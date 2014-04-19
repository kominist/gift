<?php
namespace Model;
use Model\Gift;
use LaravelBook\Ardent\Ardent as Eloquent;

class UserModel extends Eloquent
{
  protected $table = "users";
  public function id_giver()
  {
    return $this->belongsTo("Gift");
  }
  public function id_getter()
  {
    return $this->belongsTo("Gift");
  }
}
