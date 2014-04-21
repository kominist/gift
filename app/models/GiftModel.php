<?php
namespace Model;
use LaravelBook\Ardent\Ardent as Eloquent;

/**
 * @package Model
 * @subpackage GiftModel
 * @category classes
 * @licence GPL3
 */
class GiftModel extends Eloquent
{
  /**
   *  @var string $table
   */
  protected $table = "gifts";

  /**
   *  @var array $rules
   */
  public static $rules = [
    "status"      => "required|alpha",
    "id_giver"    => "required",
    "id_getter"   => "required",
    "id_meeting"  => "required",
  ];

  /**
   *  id giver <-> user(id)
   *  @return mixed relation
   */
  public function id_giver()
  {
    return $this->hasMany("User", "id_giver");
  }

  /**
   * id_getter <-> user(id)
   * @return mixed relation
   */
  public function id_getter()
  {
    return $this->hasMany("User", "user_id", "id_getter");
  }
}
