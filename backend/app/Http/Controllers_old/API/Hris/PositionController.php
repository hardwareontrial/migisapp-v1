<?php

namespace App\Http\Controllers\API\Hris;

use App\Http\Controllers\Controller;
use App\Models\API\HRIS\AppHrisPosition;
use App\Models\API\User\AppUser;
use Illuminate\Http\Request;

class PositionController extends Controller
{
  public function handleUpdatePositionUser(Request $request)
  {
    $posid = $request->input('positionid');
    $requsersid =  $request->input('usersid');
    $currusersid = AppUser::where('position_id', $posid)->get()->pluck('id')->toArray();
    $to_set = array_diff($requsersid, $currusersid);
    $to_nulled = array_diff($currusersid, $requsersid);
    
    if(count($to_set) > 0 && count($to_nulled) > 0){
      foreach($to_set as $set){
        $setuser = AppUser::find($set);
        $setuser->position_id = $posid;
        $setuser->save();
      }

      foreach($to_nulled as $nulled){
        $nulleduser = AppUser::find($nulled);
        $nulleduser->position_id = null;
        $nulleduser->save();
      }
    }

    if(count($to_set) <= 0 && count($to_nulled) > 0){
      foreach($to_nulled as $nulled){
        $nulleduser = AppUser::find($nulled);
        $nulleduser->position_id = null;
        $nulleduser->save();
      }
    }

    if(count($to_set) > 0 && count($to_nulled) <= 0){
      foreach($to_set as $set){
        $setuser = AppUser::find($set);
        $setuser->position_id = $posid;
        $setuser->save();
      }
    }
  }


  public function testpostion()
  {
    return AppHrisPosition::with('parent1')->get();
  }

  public function testdetailuser()
  {
    return AppUser::where('id', 9)
        ->with('login', 'position', 'position.parent', 'position.children', 'position.department')
        ->first();
  }

  public function testhris2()
  {
    return AppHrisPosition::orderBy('id', 'asc')
    ->with('user2')
    ->get();
  }

  public function testhris3(Request $request)
  {
    $postid = $request->input('positionid');
    $usersid =  $request->input('usersid');

    // $position = AppHrisPosition::find($postid);
    // $users = AppUser::find($usersid);
    // $data = $position->user2()->sync($users);
    // return $data;

    foreach ($usersid as $user){
      $data = AppUser::find($user);
      $data->position_id = $postid;
      $data->save();
    }
  }

  public function testhris4()
  {
    // return AppHrisPosition::with('user3')->get();
    return AppUser::with('position3')->get();
  }
}
