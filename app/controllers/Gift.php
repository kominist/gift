<?php
namespace Controller;
use Model\GiftModel as GiftModel;
use Model\UserModel as UserModel;
use LaravelBook\Ardent\Ardent as Ardent;
use Cartalyst\Sentry\Facades\Native\Sentry as Sentry;

/**
 * Gift
 * Manage gifts
 *
 * @package Controller
 * @subpackage Gift
 * @category classes
 * @licence GPL3
 */
class Gift
{

  /**
   *  App instance
   *  @var mixed $app
   */
  private $app;

  /**
   *  Constructor
   *
   *  @param mixed $app
   *  @return void
   */
  public function __construct($app)
  {
    $this->app = $app;
  }

  /**
   * Get the last 10 Gifts
   *
   * @return array gifts
   */
  public function findAll()
  {
    $gift = new GiftModel;
    $user = new UserModel;
    $data = [];
    $gifts = $gift
      ->take(10)
      ->get();
    $data = $this->format($gifts, $user);
    return $this->app->json($data);
  }

  /**
   *  Send a gift
   *
   *  @param integer $from giver
   *  @param integer $to getter
   *  @param integer $id_meeting foreign key of the meeting table
   *  @return boolean success of the save
   */
  public function send($from, $to, $id_meeting = 1)
  {
    $gift = new GiftModel;
    $gift->id_giver   = $from;
    $gift->id_getter  = $to;
    $gift->id_meeting = $id_meeting;
    $gift->status     = "initialized";
    return $gift->save();
  }

  /**
   *  Delete a gift
   *
   *  @param integer $id identifier of gift
   *  @return boolean success of the deletion
   */
  public function delete($id)
  {
    return GiftModel::find($id)->delete();
  }

  /**
   *  Change the status of a gift
   *
   *  @param integer $id identifier of gift
   *  @param string $status can be initialized, accepted, refused
   *  @return boolean success of the status change
   */
  public function setStatus($id, $status)
  {
    $gift = GiftModel::find($id);
    $gift->status = $status;
    return $gift->save();
  }

  /**
   * Get gift trades from a given giver or given getter
   *
   * @param integer $id identifier
   * @return array gifts
   */
  public function getUser($id)
  {
    $gift = new GiftModel;
    $gifts = $gift->where("id_giver", "=", $id)
      ->orWhere("id_getter", "=", $id)
      ->get();
    $user = new UserModel;
    $gifts = $this->format($gifts, $user);
    return $this->app->json($gifts);
  }

  /**
   * Format a list of gifts depending of the giver and the getter
   *
   * @param array $gifts
   * @param array $user
   * @return array formatted gift trades
   */
  private function format($gifts, $user)
  {
    foreach($gifts as $g)
    {
      $data [] = [
        "id" => $g->id,
        "status" => $g->status,
        "getter" => [
          "id" => $g->id_getter,
          "name" => isset($user::find($g->id_getter)->first_name)?$user::find($g->id_getter)->first_name:false
        ],
        "giver" => [
          "id" => $g->id_giver,
          "name" => isset($user::find($g->id_giver)->first_name)?$user::find($g->id_giver)->first_name:false
        ]
      ];
    }
    return $data;
  }
}
