<?php

namespace App\Http\Controllers\API\GaInventaris;

use App\Http\Controllers\Controller;
use App\Models\API\GaInventaris\AppGainventaris;
use App\Models\API\User\AppUser;
use Illuminate\Http\Request;

class GaInventarisDetailController extends Controller
{
  public function handleDetailCode()
  {
    return AppGainventaris::whereIn('id', json_decode(request()->id))
      ->with('locname', 'merkname', 'pemilikname', 'deptusername', 'user1name', 'user2name')
      ->get();
  }

  public function test()
  {
    return 'test';
  }

  public function handleDetailInventarisUser()
  {
    $data = AppGainventaris::where('user1_id', request()->id)
      ->orWhere('user2_id', request()->id)
      ->with('locname', 'deptusername', 'user1name', 'user2name', 'merkname')
      ->get();
    $datauser = AppUser::where('id', request()->id)->first();
    return response()->json([ 
      'message' => $data,
      'message2' => $datauser], 200);
  }

  public function handleDetailInventarisLokasi()
  {
    return AppGainventaris::where('lokasi_id', request()->id)
      ->with('locname', 'deptusername', 'user1name', 'user2name', 'merkname')
      ->get();
  }

  public function handleDetail($id)
  {
    $code = str_replace('-', ' ', $id);
    return AppGainventaris::where('id', $id)
      ->with('locname', 'merkname', 'pemilikname', 'user1name', 'user2name', 'user1name.position.deptname')
      ->first();
  }

  public function handleArchieve($id)
  {
    $data = AppGainventaris::find($id);
    $active = !$data->active;
    $data->update([
      'user1_id' => null,
      'user2_id' => null,
      'status_id' => 1,
      'dept_user' => null,
      'active' => $active,
    ]);
    if(!$data){ return response()->json(['message' => 'Update gagal'], 500); }
    return response()->json(['message' => 'Update berhasil'], 200);
  }

}
