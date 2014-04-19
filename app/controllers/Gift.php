<?php
namespace Controller;
use Model\GiftModel as GiftModel;
use Model\UserModel as UserModel;
use LaravelBook\Ardent\Ardent as Ardent;
use Cartalyst\Sentry\Facades\Native\Sentry as Sentry;

class Gift
{
  private $app;

  public function __construct($app)
  {
    $this->app = $app;
  }

  public function findAll($current = false)
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

  public function send($from, $to, $id_meeting = 1)
  {
    $gift = new GiftModel;
    $gift->id_giver   = $from;
    $gift->id_getter  = $to;
    $gift->id_meeting = $id_meeting;
    $gift->status     = "initialized";
    return $gift->save();
  }

  public function delete($id)
  {
    return GiftModel::find($id)->delete();
  }

  public function setStatus($id, $status)
  {
    $gift = GiftModel::find($id);
    $gift->status = $status;
    return $gift->save();
  }

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
