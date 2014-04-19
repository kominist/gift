<?php
namespace Model;
use LaravelBook\Ardent\Ardent as Eloquent;
class GiftModel extends Eloquent
{
  protected $table = "gifts";
  public static $rules = [
    "status"      => "required|alpha",
    "id_giver"    => "required",
    "id_getter"   => "required",
    "id_meeting"  => "required",
  ];
  public function id_giver()
  {
    return $this->hasMany("User", "id_giver");
  }
  public function id_getter()
  {
    return $this->hasMany("User", "user_id", "id_getter");
  }
}
