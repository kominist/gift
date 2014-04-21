<?php
namespace Model;
use Model\Gift;
use LaravelBook\Ardent\Ardent as Eloquent;

/**
 * @package Model
 * @subpackage UserModel
 * @category classes
 * @licence GPL3
 */
class UserModel extends Eloquent
{
  /**
   *  @var string $table
   */
  protected $table = "users";

  /**
   * id <-> gift(id_giver)
   * @return mixed relation
   */
  public function id_giver()
  {
    return $this->belongsTo("Gift");
  }
  /**
   *  id <-> gift(id_getter)
   *  @return mixed relation
   */
  public function id_getter()
  {
    return $this->belongsTo("Gift");
  }
}
